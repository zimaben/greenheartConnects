<?php
namespace gh_connects\admin;


//spin it up
\gh_connects\admin\Admin::get_instance();

class Admin extends \GreenheartConnects{

    private static $instance = null;

    public static function get_instance() 
    {

        if ( 
            null == self::$instance 
        ) {

            self::$instance = new self;

        }

        return self::$instance;

    }
    
    
    private function __construct() 
    {   
        \add_action( 'gform_authorizenet_post_capture', array(get_class(), 'flag_payment'), 10, 6 );


        \add_action('wp_ajax_register_user_front_end', array(get_class(),'register_user_front_end') );
        \add_action('wp_ajax_nopriv_register_user_front_end', array(get_class(),'register_user_front_end') );

        \add_action('admin_init', array(get_class(),'allow_subscriber_uploads') );
     
    }
    public static function flag_payment( $is_authorized, $amount, $entry, $form, $config, $response ){
        //set minimum
        $monthly_min = 7;
        //GET USER ID
        $userID = \get_current_user_id(); //Form Can't be sent if not logged in
        if( $is_authorized ){
            if( intval($amount) >= $monthly_min){

                \update_user_meta( $userID, 'cn_last_payment_on', date('d-m-Y') );
                \update_user_meta( $userID, 'cn_last_payment_amt', $amount );
                \update_user_meta( $userID, 'cn_status', 'paid' );
            } else {
                /* Minimum Amount Not Processed Tree */
            }

        } else {
            /* Not Authorized Tree */
        }
    }

    public static function register_user_front_end() {

        $new_user_name = stripcslashes($_POST['username']);
        $new_user_email = stripcslashes($_POST['email']);
        $new_user_password = $_POST['password'];
        $user_data = array(
            'user_login' => $new_user_name,
            'user_email' => $new_user_email,
            'user_pass' => $new_user_password,
            'role' => 'subscriber'
            );
        $user_id = wp_insert_user($user_data);
            
            if (!is_wp_error($user_id)) {
                echo json_encode( array('status'=>200, 'message'=> 'Email and password saved.'));
            } else {
                if (isset($user_id->errors['empty_user_login'])) {
                $notice_key = 'Missing Email or Password field.';
                echo json_encode( array('status'=>400, 'message'=> $notice_key));
                } elseif (isset($user_id->errors['existing_user_login'])) {
                echo json_encode( array('status'=>400, 'message'=> 'That email already exists.'));
                } else {
                echo json_encode( array('status'=>400, 'message'=> 'There was an error trying to add that email.'));
                }
            }
        die;
    }

    public static function allow_subscriber_uploads() {
        if ( current_user_can('subscriber') && !current_user_can('upload_files') ){
            $subscriber = get_role('subscriber');
            $subscriber->add_cap('upload_files');
        }
    }
}
/*
https://docs.gravityforms.com/gform_authorizenet_post_capture/
*/

