<?php
namespace gh_connects\admin;
use \gh_connects\admin\AuthNet as AuthNet;

//spin it up
\gh_connects\admin\PaymentCron::get_instance();

class PaymentCron extends \GreenheartConnects {

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
        /* ADD WEEKLY CLEANUP */
        \add_action('ghc_daily_subscription_update', array(get_class(), 'ghc_daily_subscription_update'));       
    }
    public static function ghc_daily_subscription_update() {
        error_log('running ghc daily subscription function');
        //Add Subscription Flag for UserID 
        $subscriptions = AuthNet::getListOfSubscriptions('subscriptionActive');
        if( $subscriptions->getMessages()->getResultCode() == "Ok" ){
            if(is_array( $subscriptions->getSubscriptionDetails() ) ){
                foreach ($subscriptions->getSubscriptionDetails() as $subscriptionDetails) {
                    if( !empty($subscriptionDetails->getInvoice()) ) {
                        $userID = $subscriptionDetails->getInvoice();
                        $current_value = \get_user_meta( $userID, 'cn_subscriptionid', true);
                        if($current_value){
                            if($current_value !== $subscriptionDetails->getId() ){
                                #update Subscription info for this user
                                $logentry = array( 'Updated Subscription Info'=> 'User: '.$userID.' payment subscription updated, subscriptionid: '.$subscriptionDetails->getId());
                                self::activity_log($logentry);
                                $logentry = json_encode($logentry);
                                self::activity_log_json($logentry);
                                \update_user_meta( $userID, 'cn_subscriptionid', $subscriptionDetails->getId() );
                            }

                        } else {
                            #first time Subscription has been found for this user
                            $logentry = array( 'Added New Subscription'=> 'User: '.$userID.' payment subscription added, subscriptionid: '.$subscriptionDetails->getId());
                            self::activity_log($logentry);
                            $logentry = json_encode($logentry);
                            self::activity_log_json($logentry);
                            \update_user_meta( $userID, 'cn_subscriptionid', $subscriptionDetails->getId() );
                        }
                        
                    }
                }
            }
        }
        $subscriptions = AuthNet::getListOfSubscriptions('subscriptionInactive');
        if(is_array( $subscriptions->getSubscriptionDetails() ) ){
            foreach ($subscriptions->getSubscriptionDetails() as $subscriptionDetails) {
                if( !empty($subscriptionDetails->getInvoice()) ) {
                    $userID = $subscriptionDetails->getInvoice();
                    error_log('inactive subscription for userid: '.$userID);
                    $current_value = \get_user_meta( $userID, 'cn_status', true);
                    if($current_value !== 'unpaid'){
                        #user is marked as unpaid for the first time
                        $logentry = array( 'User Marked as Unpaid'=> 'User: '.$userID.', subscriptionid: '.$subscriptionDetails->getId());
                        $dotlogentry = self::activity_log($logentry);
                        $logentry = json_encode($logentry);
                        $jsonentry = self::activity_log_json($logentry);

                        \update_user_meta( $userID, 'cn_status', 'unpaid' );
                    }
                    
                }
            }
        }
    }

    public static function activity_log_json($entry){
        $entry = (array)$entry;
        //get some details
        $timestamp = date('D Y-m-d h:i:s A');
        $server = $_SERVER['REMOTE_ADDR']; 
        $entry['timestamp'] = $timestamp;
        $entry['server'] = $server;

        //naming convention of today's logfile 
        $stCurLogFileName='activitylog_'.date('Ymd').'.json';
        if( file_exists(self::get_plugin_path('/admin/log/').$stCurLogFileName)) {
            $strActivityLog = file_get_contents(self::get_plugin_path('/admin/log/').$stCurLogFileName);
            $activityLog = json_decode( $strActivityLog );
            if( $activityLog && is_array($activityLog) ){
                $activityLog = array_merge($activityLog, $entry);
            } else {
                $activityLog = $entry;
            }
            
            $encoded_file = json_encode( $activityLog );
            $file = file_put_contents( self::get_plugin_path('/admin/log/').$stCurLogFileName, $encoded_file ); 
            
        } else {
            $encoded_file = json_encode( $entry );
            $file = file_put_contents( self::get_plugin_path('/admin/log/').$stCurLogFileName, $encoded_file ); 
        }
    return $file;
    }

    public static function activity_log($entry){  
        error_log('logging file called');
        //define empty string                                 
        $stEntry="";  

        //get the event occur date time,when it will happened  
        $timestamp = date('D Y-m-d h:i:s A');
        $server = $_SERVER['REMOTE_ADDR'];  
        //if message is array type  
        $stEntry = $timestamp.' : server='.$server.' : ';
        if(is_array($entry))  {  
        //concatenate msg with datetime  
        foreach($entry as $key => $val)  
            $stEntry.=($key.'='.$val."/n");  
        }  else  {   //concatenate msg with datetime  
            
            $stEntry.=$entry."/n";  
        }
        //create file with current date name  
        $stCurLogFileName='activitylog_'.date('Ymd').'.log';  
        //open the file append mode,dats the log file will create day wise  
        #$server_path = $_SERVER['DOCUMENT_ROOT'];        
        $fHandler=fopen(self::get_plugin_path('/admin/log/').$stCurLogFileName,'a+');  
        //write the info into the file  
        fwrite($fHandler,$stEntry);  
        //close handler  
        fclose($fHandler);  
    }
}      