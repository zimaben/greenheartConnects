<?php
namespace gh_connects\control;

//spin it
#\gh_connects\control\Core::run();


class GravityFormsAuthNet extends \GreenheartConnects {
    private function __construct(){
        \add_action( 'gform_authorizenet_post_capture', array(get_class(), 'connects_postcapture'), 10, 6 );
        \add_filter( 'gform_authorizenet_transaction_pre_authorize', array(get_class(),'connects_preauthorize'), 10, 4 );
        \add_filter( 'gform_authorizenet_transaction_pre_capture', array(get_class(),'connects_precapture'), 10, 5 );

    }

    public static function connects_postcapture( $is_authorized, $amount, $entry, $form, $config, $response ){
        error_log('POST CAPTURE: ');
        error_log($is_authorized);
        error_log($amount);
        error_log($entry);
        error_log($form);
        error_log($config);
        error_log($response );

    }
    public static function preauthorize( $transaction, $form_data, $config, $form ){
        error_log('PRE AUTHORIZE: ');
        error_log($transaction);
        error_log($form_data);
        error_log($config);
        error_log($form);      
    }
    public static function precapture( $transaction, $form_data, $config, $form, $entry ){
        error_log('PRE CAPTURE: ');
        error_log($transaction);
        error_log($form_data);
        error_log($config);
        error_log($form);      
        error_log($entry);
    }
    

}