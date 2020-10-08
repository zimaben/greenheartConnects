<?php
namespace gh_connects\control;
use gh_connects\admin\PaymentOptions as PaymentOptions;

//spin it
\gh_connects\control\GravityFormsAuthNet::run();


class GravityFormsAuthNet extends \GreenheartConnects {
    private function __construct(){


    }
    public static function run(){

        \add_action( 'gform_authorizenet_post_capture', array(get_class(), 'connects_postcapture'), 10, 6 );
        \add_filter( 'gform_authorizenet_transaction_pre_authorize', array(get_class(),'connects_preauthorize'), 10, 4 );
        \add_filter( 'gform_authorizenet_transaction_pre_capture', array(get_class(),'connects_precapture'), 10, 5 );
        \add_filter( 'gform_authorizenet_subscription_pre_create', array(get_class(),'connects_pre_subscription'), 10, 5 );
        #this executes the manage form actions
        \add_action( 'gform_after_submission', array(get_class(), 'connects_post_submission'), 10, 2 );
        #add promo code evaluation to the form marked as master payment form
        $paymentform_val_name = 'gform_field_validation_' . trim( \get_option('ghc_payment_form_id'));
        \add_filter( $paymentform_val_name, array(get_class(), 'ghc_validate_promocode' ), 10, 4 );

    }

    public static function connects_pre_subscription($subscription, $form_data, $config, $form, $entry){
        #Get User ID, this will be our Invoice ID and connect the WP database and Auth.Net
        $userID = \get_current_user_id(); //Form Can't be sent if not logged in
        #Form should not allow subscription amounts below minimum, but check in case of bad content
        $monthly_min = 7;
        if( $subscription->amount >= $monthly_min ){
            \update_user_meta( $userID, 'cn_last_payment_on', date('d-m-Y') );
            \update_user_meta( $userID, 'cn_last_payment_amt', $subscription->amount );
            \update_user_meta( $userID, 'cn_status', 'paid' );
            #Invoice must be set to UserID to tie the WP user to payment info
            $subscription->orderInvoiceNumber = $userID;
            //is there a promocode? If form field has admin label set to promocode this returns field value
            $fieldval = self::gf_get_admin_label_val_by_name('promocode', $form, $entry);
            if($fieldval){
                global $wpdb;
                //get all promo codes
                $rows = $wpdb->get_results($wpdb->prepare( 
                    "SELECT * FROM wp_postmeta WHERE meta_key = %s",
                    'ghc_stream_promo'
                ));
                if( $rows ) {
                    foreach($rows as $row){
                        if( strtoupper(trim($fieldval) ) == strtoupper(trim($row->meta_value)) ){
                            #PROMO CODE MATCH 
                            //get remaining codes
                            $stream_promocount = \get_post_meta( $row->post_id, 'ghc_stream_promocount', true);
                            if($stream_promocount !== false){//has to be strict because it starts at zero
                                if(self::$debug){
                                    error_log( 'Promo Code Match on code: '.$fieldval );
                                }
                                $updated = \update_post_meta($row->post_id, 'ghc_stream_promocount', $stream_promocount + 1 );
                                $userupdated = \update_user_meta( $userID, 'cn_promo_code', $fieldval );
                                #PROMO CODE SUCCESSFULL
                                #$subscription->trialOccurrences = 1; // This will set a 1 month trial period as long as Billig Cycle is using Months on payment feed
                            }
                        }
                    }
                }
            }
        } else {
            #error - minimum amount not reached
            $message = json_encode('7 Dollar minimum required.');
            return $message;
        }
        error_log('SUBSCRIPTION:');
        error_log(print_r($subscription,true));
        return $subscription;
    }

    public static function gf_get_admin_label_val_by_name($labelname, $form, $entry){
        $_return = false;
        $fields = $form['fields'];
        foreach($fields as $field){
            if( isset($field['adminLabel']) && $field['adminLabel'] == $labelname ) {
                //if we are evaluating the right field (promocode) return value
                error_log('GET LABEL MATCH ON '. $labelname);
                $_return = rgar( $entry, strval( $field['id'] ) ); 
                return $_return;
            }
        }
        return $_return;
    }
    public static function gf_get_admin_label_id_by_name_checkbox($labelname, $form, $entry){
        $_return = false;
        $fields = $form['fields'];
        foreach($fields as $field){
            if( isset($field['adminLabel']) && $field['adminLabel'] == $labelname ) {
                //if we are evaluating the right field (promocode) return value
                $_return = $field['id']; 
                return $_return;
            }
        }
        return $_return;
    }
    public static function ghc_validate_promocode($result, $value, $form, $field ){
        #set match to true while no Promo code is set
        $match = true;
        $validform = $result['is_valid']; //BOOLEAN will always return 1 when called here
        
        if($field->adminLabel == 'promocode'){
            #eval the promocode
            if(isset($value) && !empty($value)){
                $match = false;
                $error = false;
                $promo_code = $value;
                global $wpdb;
                //get all promo codes
                $rows = $wpdb->get_results($wpdb->prepare( 
                    "SELECT * FROM wp_postmeta WHERE meta_key = %s",
                    'ghc_stream_promo'
                ));
                if( $rows ) {
                    foreach($rows as $row){

                        if( strtoupper($promo_code) == strtoupper(trim($row->meta_value)) ){  
                            #PROMO CODE MATCH 
                            error_log('MATCH');
                            error_log($promo_code);
                            //EVAL THE PROMO CODE
                            $stream_promocount = get_post_meta( $row->post_id, 'ghc_stream_promocount', true);
                            $promo_quantity_max = get_post_meta( $row->post_id, 'ghc_promo_quantity_max', true);
                            $promo_active = get_post_meta( $row->post_id, 'ghc_promo_active', true);
                            $promo_code_expiration = new \DateTime( get_post_meta( $row->post_id, 'ghc_promo_expiration', true), new \DateTimeZone('America/Chicago') ); 
                            $now = new \DateTime("now", new \DateTimeZone('America/Chicago'));
                            //is expiration date in the future  
                            if( $promo_code_expiration > $now ){
                                //so far so good
                                if($promo_active === "true"){
                                    //so far so good
                                    $codes_remaining = $promo_quantity_max - $stream_promocount;
                                    if($promo_quantity_max && $codes_remaining > 0){
                                        //we made it
                                        $match = true;

                                    } else {
                                        $error = 'Limit Reached for that Promo Code.';
                                    }
                                } else {
                                    $error = 'That Promo Code is not currently active.';
                                }
                            } else {
                                $error = 'That Promo Code has expired.';
                            }     
                        }
                    }
                if(!$match && !$error ) $error = 'We couldn\'t find that Promo Code.';     
                } else {
                    $error = 'There are no current Promo Codes.';
                }
            }
        }

        if(!$match) {
            if(self::$debug) error_log($error);
            $return_result = array( 'is_valid' => false, 'message' => $error );
        } else {
            $return_result = array( 'is_valid' => true );
        }

    return $return_result;
    }
    public static function connects_post_submission( $entry, $form ){
        $manageform_val = trim( \get_option('ghc_manage_form_id'));
        if( $form['id'] == $manageform_val ){

            $cancel_checkbox_id = self::gf_get_admin_label_id_by_name_checkbox('cancel_subscription', $form, $entry);

            /** cancel logic */
            $cancel_field = \GFAPI::get_field( $form, $cancel_checkbox_id );
            $field_value = is_object( $cancel_field) ? $cancel_field->get_value_export( $entry ) : '';
            if( $field_value !== '' && $field_value == 'yes, please cancel my account'){
                $userid = \get_current_user_id();
                $message = PaymentOptions::ghc_cancel_subscription($userid);
                error_log(print_r($message,true));
                error_log((string)$message);
                if( substr((string)$message,0,7) == "SUCCESS" ) {
                    $retmessage = 'We have successfully canceled your subscription. Sorry to see you go but thanks for the support.';
                } else {
                    $retmessage = 'We were unable to find that subscription.';
                }
                print_r($retmessage); #print directly to the notification page because thats what GF gives us

            }
        }

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