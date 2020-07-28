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
        
    }
    public static function flag_payment( $is_authorized, $amount, $entry, $form, $config, $response ){
        //set minimum
        $monthly_min = 12;
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
}
/*
https://docs.gravityforms.com/gform_authorizenet_post_capture/
*/

