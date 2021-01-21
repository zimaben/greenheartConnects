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
        #\add_action( 'gform_authorizenet_post_capture', array(get_class(), 'flag_payment'), 10, 6 );

        \add_action('wp_ajax_register_user_front_end', array(get_class(),'register_user_front_end') );
        \add_action('wp_ajax_nopriv_register_user_front_end', array(get_class(),'register_user_front_end') );

        \add_action('admin_init', array(get_class(),'allow_subscriber_uploads') );
        \add_action( 'admin_init', array(get_class(),'keep_users_out'), 1 );

        \add_filter('retrieve_password_message', array(get_class(), 'password_reset_connects'),10, 4 );

        require_once self::get_plugin_path( 'admin/switch_postypes.php'); 
        require_once self::get_plugin_path( 'admin/options.php');
        require_once self::get_plugin_path( 'admin/payment_options.php');
        require_once self::get_plugin_path( 'admin/payment_cron.php');


    }
    public static function login_head_message(){
        error_log('this is working');
    }
    public static function password_reset_login_message($errors){
        error_log('message firing');


        $errors->add( 'done', 'Please check your inbox for the reset link.' );
        return $errors;
    }

    public static function password_reset_connects( string $message, string $key, string $user_login, $user_data ){

    \add_action( 'login_head', array(get_class(), 'login_head_message') );
    \add_filter( 'shake_error_codes', array(get_class(), 'password_reset_login_message'), 10, 1 ); 
 

    //$new_key = \get_password_reset_key( $user_data);

    $link = '<'.\get_site_option( 'siteurl' ).'/login/?action=rp&key='.$key.'&login='.$user_login.'>';

    $message = "Someone has requested a password reset for Greenheart Connects for the user ".$user_login."

    If this was a mistake just ignore this email. To reset the password click:

    ".$link."


    Thanks,
    Greenheart Connects";
    error_log($link);
    return $message;
    }

    public static function keep_users_out(){
        $user = \wp_get_current_user( \get_current_user_id() );
        if(! \wp_doing_ajax() ){
            if( in_array( 'ghc_user', (array) $user->roles ) ) { 
                \wp_safe_redirect( get_site_url() );
            }
        }
    }
    public static function register_user_front_end() {
        error_log(print_r($_POST,true));
        $new_user_name = stripcslashes($_POST['username']);
        $new_user_email = stripcslashes($_POST['email']);
        $new_user_password = $_POST['password'];
        $new_user_firstname = stripcslashes($_POST['firstname']);
        $new_user_lastname = stripcslashes($_POST['lastname']);

        $user_data = array(
            'user_login' => $new_user_name,
            'user_email' => $new_user_email,
            'user_pass' => $new_user_password,
            'first_name'=> $new_user_firstname,
            'last_name' => $new_user_lastname,
            'nickname'  => $new_user_firstname.' '.$new_user_lastname,
            'role' => 'ghc_user'
            );
        $user_id = wp_insert_user($user_data);
            if ($user_id && !is_wp_error($user_id)) {
                //log in the user
                $user = get_user_by('id', $user_id);
                error_log(print_r($user,true));
                clean_user_cache($user);
                wp_clear_auth_cookie();
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id, true, false);
                update_user_caches($user_id);
                $ret_first = explode(' ', $user->display_name);
                $ret_last = array_pop($ret_first);
                $ret_first = trim(implode(' ', $ret_first ));

                $return_message = json_encode( array(
                    'user' => $user_id,
                    'email'=> $user->user_email,
                    'firstname'=> $ret_first,
                    'lastname'=> $ret_last
                ));
                echo json_encode( array('status'=>200, 'message'=> $return_message) );
                wp_die();
            } else {
                if (isset($user_id->errors['empty_user_login'])) {
                $notice_key = 'Missing Email or Password field.';
                echo json_encode( array('status'=>400, 'message'=> $notice_key));
                } elseif (isset($user_id->errors['existing_user_login'])) {
                echo json_encode( array('status'=>400, 'message'=> 'That email already exists.'));
                wp_die();
                } else {
                echo json_encode( array('status'=>400, 'message'=> 'There was an error trying to add that email.'));
                wp_die();
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

