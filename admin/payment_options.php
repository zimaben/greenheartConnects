<?php
namespace gh_connects\admin;
use \gh_connects\admin\AuthNet as AuthNet;

//spin it up
\gh_connects\admin\PaymentOptions::get_instance();

class PaymentOptions extends \GreenheartConnects {

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
        \add_action('admin_menu', array(get_class(), 'add_payment_submenu') );  //add submenu page
        \add_action( 'admin_init', array(get_class(),'ghc_payment_settings_init') );//register settings
        
        /* ADD PAYMENT CHECK AJAX */

        \add_action('wp_ajax_see_subscription_info', array(get_class(),'see_subscription_info'));
        \add_action('wp_ajax_nopriv_see_subscription_info', array(get_class(),'see_subscription_info'));

        \add_action('wp_ajax_ghc_cancel_subscription', array(get_class(), 'ghc_cancel_subscription' ));
        \add_action('wp_ajax_nopriv_ghc_cancel_subscription', array(get_class(), 'ghc_cancel_subscription' ));

        /* ADD WEEKLY CLEANUP */

        
        // Add our event
        $first_run = strtotime("+1 day");
        \wp_schedule_single_event($first_run, 'daily', array(get_class(), 'ghc_daily_subscription_update'));
        
    }
    public static function ghc_cancel_subscription_ajax($user_id){
        #Try meta flag
        $subscription = \get_user_meta( $user_id, 'cn_subscriptionid', true );
        //try it
        if(!$subscription || intval($subscription) <= 1 ) {
            //else we don't have a cn_subscriptionid flag (legacy user)
            $subscription = self::ghc_get_subscription_by_invoice($user_id);
        } 
        if($subscription){
            $response = AuthNet::cancelSubscription($subscriptionId);
            if( substr($response,0,7) == "SUCCESS" ) {
                $retval = array('status'=>200, 'message'=>$response);
            } else {
                $retval = array('status'=>400, 'message'=> $response);
            }  
            
        } else {
            $retval = array('status'=>400, 'message'=> 'Sorry. We could not find a subscription for this user.');
        }
        echo json_encode($retval);
        wp_die();
    }
    public static function ghc_cancel_subscription($user_id){
        error_log('userid');
        error_log($user_id);
        #Try meta flag
        $subscription = \get_user_meta( $user_id, 'cn_subscriptionid', true );
        error_log($subscription);
        //try it
        if(!$subscription || intval($subscription) <= 1 ) {
            //else we don't have a cn_subscriptionid flag (brand new or legacy user)
            $subscription = self::ghc_get_subscription_by_invoice($user_id);
            error_log($subscription);
        } 
        if($subscription){
            $response = AuthNet::cancelSubscription($subscription);   
        } else {
            $response = 'Sorry. We could not find a subscription for this user.';
        }
        return $response;
    }
    /* Functio Takes an invoice number and returns a subscription number */
    public static function ghc_get_subscription_by_invoice($invoiceNum){
        $returnval = false;
        $subscriptions = AuthNet::getListOfSubscriptions('subscriptionActive');
        if(is_array( $subscriptions->getSubscriptionDetails() ) ){
            foreach ($subscriptions->getSubscriptionDetails() as $subscriptionDetails) {
                if( !empty($subscriptionDetails->getInvoice()) ) {
                    if($subscriptionDetails->getInvoice() == $invoiceNum){
                        $returnval = $subscriptionDetails->getId();
                    }   
                }
            }
        }
        return $returnval;
    }
    function ghc_daily_subscription_update() {
        //Add Subscription Flag for UserID 
        $subscriptions = AuthNet::getListOfSubscriptions('subscriptionActive');
        if( $subscriptions->getMessages()->getResultCode() == "Ok" ){
            if(is_array( $subscriptions->getSubscriptionDetails() ) ){
                foreach ($subscriptions->getSubscriptionDetails() as $subscriptionDetails) {
                    if( !empty($subscriptionDetails->getInvoice()) ) {
                        $userID = $subscriptionDetails->getInvoice();
                        \update_user_meta( $userID, 'cn_subscriptionid', $subscriptionDetails->getId() );
                    }
                }
            }
        }
        $subscriptions = AuthNet::getListOfSubscriptions('subscriptionInactive');
        if(is_array( $subscriptions->getSubscriptionDetails() ) ){
            foreach ($subscriptions->getSubscriptionDetails() as $subscriptionDetails) {
                if( !empty($subscriptionDetails->getInvoice()) ) {
                    $userID = $subscriptionDetails->getInvoice();
                    \update_user_meta( $userID, 'cn_status', 'unpaid' );
                }
            }
        }
    }
    public static function add_payment_submenu(){
        //create sub menu
        \add_submenu_page( 'options', 'Payment Information', 'Payment Information', 'administrator', 'payment-options', array(get_class(), 'ghc_payment_page_cb'), 1 );
    }
  
    public static function ghc_payment_page_cb(){
                ?>

                
                <form method="post" action="options.php"> 
                    <?php settings_fields( 'ghc-payment-settings' ); ?>
                    <?php do_settings_sections( 'ghc-payment-settings' ); ?>
                    <div class="wrap">
                    <h1>Greenheart Connects Payment Settings:</h1>

                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">API Settings:</th></tr>
                        <tr><td><label for="google_analytics_code">Auth.Net API Login ID:</label>
                        <input type="text" id="authnet_api_login_id" name="authnet_api_login_id" value="<?php echo esc_attr( get_option('authnet_api_login_id') ); ?>"/></td>
                        </tr>
                        <tr><td><label for="google_analytics_code">Auth.Net API Transaction Key:</label>
                        <input type="text" id="authnet_api_trans_key" name="authnet_api_trans_key" value="<?php echo esc_attr( get_option('authnet_api_trans_key') ); ?>"/></td>
                        </tr>
                    </table>
                    
                    <?php submit_button(); ?>
                    </form>
                    <!-- set function ajax call/response areas -->
                    <?php echo self::ghc_payment_page_stylesheet() ?>

                    <?php echo self::ghc_payment_page_javascript() ?>
                    <div class="control-container">
                    <span class="payment-button" onclick="see_subscription_info(event);" data-target="show_sub_info" data-typeinfo="cardExpiringThisMonth">See Subscriptions Expiring This month</span>

                    <span class="payment-button" onclick="see_subscription_info(event);" data-target="show_sub_info" data-typeinfo="subscriptionInactive">See Inactive Subscriptions</span>

                    <span class="payment-button" onclick="see_subscription_info(event);" data-target="show_sub_info" data-typeinfo="subscriptionActive">See Active Subscriptions</span>
                    </div>
                    <div class="display-container" id="show_sub_info"></div>
                        
            </div>
            <?php 
    }
    /* Accepts either a request type or standard nice name for 
    * the request type and returns the other
    */
    public static function translate_request_nicename($request){
        $_return = $request;
        $hit = false;
        $map = array(
            'cardExpiringThisMonth'         => 'credit cards expiring this month',
            'subscriptionActive'            => 'active subscriptions',
            'subscriptionExpiringThisMonth' => 'subscriptions expiring this month',
            'subscriptionInactive'          => 'inactive subscriptions'
        );
        #find by translate request as key name to key value
        foreach($map as $k=>$v){
            if($request == $k) { 
                $_return = $v; 
                $hit = true; }
        }
        # if no luck try request as key value to key name
        if(!$hit){
            $key = array_keys($map, $request);
            if(!empty($key)) $_return = $key;
        }
        #send back translation if found or original value
        return $_return;
    }
    public static function see_subscription_info(){
        if(isset($_POST['info_type'])) {
            $request_type = $_POST['info_type'];
            $subscriptions = AuthNet::getListOfSubscriptions($request_type);
            if( $subscriptions->getMessages()->getResultCode() == "Ok" ){
                $totalSubscriptions = $subscriptions->getTotalNumInResultSet();
                $nicename = self::translate_request_nicename($request_type);
                //temp log the response we need to get invoice number
                //build markup for the response
                $html = '<table class="return_payment_info"><tr><th>'.$totalSubscriptions.' '.ucwords($nicename).'</th></tr>';
                $html.= '<tr><td>Subscription ID:</td><td>Status:</td><td>First Name:</td><td>LastName:</td><td>Connects User ID</td><td>Total Paid Cycles:</td><td>Amount:</td>';
                if(is_array( $subscriptions->getSubscriptionDetails() ) ){
                    foreach ($subscriptions->getSubscriptionDetails() as $subscriptionDetails) {
                        if( empty($subscriptionDetails->getAmount()) ) {
                            $amt = '$0 USD';
                        } else {
                            $amt = '$'.$subscriptionDetails->getAmount().' USD';
                        }
                        $html.= '<tr>';
                        $html.= '<td>'.$subscriptionDetails->getId().'</td>';
                        $html.= '<td>'.$subscriptionDetails->getStatus().'</td>';
                        $html.= '<td>'.$subscriptionDetails->getFirstName().'</td>';
                        $html.= '<td>'.$subscriptionDetails->getLastName().'</td>';
                        $html.= '<td>'.$subscriptionDetails->getInvoice().'</td>';
                        $html.= '<td>'.$subscriptionDetails->getPastOccurrences().'</td>';
                        $html.= '<td>'.$amt.'</td>';
                        $html.= '</tr>';
                    }
                }
                $encoded_html = json_encode($html); #this needs to be two lines for some reason
                $_return = json_encode( array('status'=>200, 'markup'=>$encoded_html));
                echo $_return;
                wp_die();
            } else {
                $errorMessage = json_encode($subscriptions->getMessages()->getMessage());
                error_log(print_r($errorMessage,true));
                $_return = json_encode( array( 'status'=> 400, 'markup'=> $errorMessage ));
                echo $_return;
                wp_die();
            }
            error_log(print_r($subscriptions,true));
        } else {
            $message = json_encode("<p>Sorry, that information type is not found.</p>");
            $_return = json_encode( array( 'status'=> 400, 'markup'=> $message ));
            echo $_return;
            wp_die();
        }
    }

    public static function ghc_payment_settings_init() {
        //toggle on/off
        \register_setting( 'ghc-payment-settings', 'authnet_api_login_id');  
        \register_setting( 'ghc-payment-settings', 'authnet_api_trans_key' );
    }
    public static function ghc_payment_page_stylesheet(){
        $markup = '<style>';
        $markup .= '.control-container {display:flex;justify-content:flex-start;margin-bottom:30px;}';
        $markup .= '.payment-button{background: #d9d9d9;text-align:center;cursor:pointer;flex:1;display:flex;margin:0 20px;padding:8px 5px;max-width:250px;border:1px solid #666;border-radius:4px;justify-content:center;align-items:center;}';
        $markup .= '.return_payment_info{border-collapse:collapse;}.return_payment_info tr{border-top:1px solid #ccc;}';
        $markup .= '.return_payment_info tr:first-of-type{border-top:none;}.return_payment_info td{padding:5px;}';
        $markup .= '</style>';
        return $markup;
    }
    public static function ghc_payment_page_javascript(){
        #no es6 :( Note:Admin pages have ajaxurl global in <head> by default
        $markup = '<script>';
        $markup .= 'function see_subscription_info(e){';
        $markup .= '    var location = ajaxurl+"?action=see_subscription_info";';
        $markup .= '    var senddata = encodeURIComponent( "info_type" ) + "=" + encodeURIComponent( e.target.dataset.typeinfo );';
        $markup .= '    var settings = { method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:senddata};';
        $markup .= '    fetch(location, settings).then(response => response.json() )';
        $markup .= '    .then(function(response){';
        $markup .= '        var target = document.getElementById(e.target.dataset.target);';
        $markup .= '        var payload = JSON.parse(response.markup);';
        $markup .= '        if(typeof payload == "object") payload=JSON.stringify(payload);';
        $markup .= '        target.innerHTML=payload';
        $markup .= '    })';
        $markup .= '}';
        $markup .= '</script>';
        return $markup;
    }
}      