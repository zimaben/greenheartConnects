<?php
namespace gh_connects\control;

//spin it
\gh_connects\control\GravityFormsAuthNet::run();


class GravityFormsAuthNet extends \GreenheartConnects {
    private function __construct(){


    }
    public static function run(){
        error_log('GRAVITY FORMS FIRING');
        \add_action( 'gform_authorizenet_post_capture', array(get_class(), 'connects_postcapture'), 10, 6 );
        \add_filter( 'gform_authorizenet_transaction_pre_authorize', array(get_class(),'connects_preauthorize'), 10, 4 );
        \add_filter( 'gform_authorizenet_transaction_pre_capture', array(get_class(),'connects_precapture'), 10, 5 );
        \add_filter( 'gform_authorizenet_subscription_pre_create', array(get_class(),'connects_pre_subscription'), 10, 5 );
    }

    public static function connects_pre_subscription($subscription, $form_data, $config, $form, $entry){
        error_log('GRAVITY PRE SUBSCRIPTION FIRING');
        $userID = \get_current_user_id();
        \update_user_meta( $userID, 'cn_last_payment_on', date('d-m-Y') );
        \update_user_meta( $userID, 'cn_last_payment_amt', $form_data['item_name'] );
        \update_user_meta( $userID, 'cn_status', 'paid' );
        error_log('ID:');
        error_log($userID);
        error_log(print_r($subscdription));
        return $subscription;
    }

    public static function connects_postcapture( $is_authorized, $amount, $entry, $form, $config, $response ){
        error_log('POST CAPTURE: ');

        $userID = get_current_user_id();
        \update_user_meta( $userID, 'cn_last_payment_on', date('d-m-Y') );
        \update_user_meta( $userID, 'cn_last_payment_amt', $amount );
        \update_user_meta( $userID, 'cn_status', 'paid' );
        error_log($userID);
        error_log($is_authorized);
        error_log($amount);
        error_log($entry);
        error_log($form);
        error_log($config);
        error_log($response );

    }
    public static function preauthorize( $transaction, $form_data, $config, $form ){
        error_log('PRE AUTHORIZE: ');
        $userID = get_current_user_id();
        error_log($userID);
        error_log($transaction);
        error_log($form_data);
        error_log($config);
        error_log($form);  
        return $transaction;    
    }
    public static function precapture( $transaction, $form_data, $config, $form, $entry ){
        error_log('PRE CAPTURE: ');
        error_log($transaction);
        error_log($form_data);
        error_log($config);
        error_log($form);      
        error_log($entry);
        return $transaction; 
    }
    

}