<?php
namespace gh_connects\control;

//spin it
#\gh_connects\control\Core::run();


class Core extends \GreenheartConnects {
    private function __construct(){

    }

    public static function get_payment_keys( $userid ){
        $umeta = self::validate_umeta($userid);
        $payment_keys = array();
        if( $umeta ){
            foreach( $umeta as $key=>$value ){
                if( $key == 'cn_last_payment_on'  ||
                    $key == 'cn_last_payment_amt' ||
                    $key == 'cn_status'){
                        if(is_array($value)) $value = $value[0];
                        $payment_keys[$key]=$value;
                       
                }        
            }
            error_log(print_r($payment_keys, true));
        } else {
            if( self::$debug ){
                error_log( 'CORE::NO KEYS IN DATABASE');
            }
        }
        if ( empty($payment_keys) ){
            if( self::$debug ){
                error_log( 'CORE::NO PAYMENT KEYS IN DATABASE');
            }
            $payment_keys['cn_status']='unpaid';
        } elseif( $payment_keys['cn_status'] !== 'paid'){
            $payment_keys['cn_status']='unpaid';
        }
    if( self::$debug ){
        error_log(print_r($payment_keys,true));
    }
    return $payment_keys; 
    }
    public static function validate_umeta( $userid ){
        $usermeta = false;
        if ( $userid && is_int($userid) ){
            $usermeta = \get_user_meta( $userid );
         } else {
             if( self::$debug ){
                 error_log( 'CORE::GET PAYMENT STATUS CALLED FOR MISSING OR INVALID USER ID');
             }
         }
     return ( $usermeta ) ? $usermeta : false; #changes blank usermeta to explicit false 
    }

}