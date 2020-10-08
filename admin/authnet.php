<?php
namespace gh_connects\admin;
//use gh_connects\control\Core as Core;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
//spin it
\gh_connects\admin\AuthNet::run();
/* This class is used with a huge tip of the hat to open source code written by Ren Aysha: https://wordpress.org/plugins/tako-movable-comments/ */

class AuthNet extends \GreenheartConnects {

    //update per installation - using sandbox

    public static function run(){
        //load up the SDK 
        require_once self::get_plugin_path( '/authnet-sdk/autoload.php' );
         
    }
    public static function check_TLS(){
        $ch = curl_init('https://apitest.authorize.net/xml/v1/request.api');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public static function see_system_TLS(){
        #self::see_system_TLS(); // 1.2 is minimum required value, 1.2 returned for XAMPP localhost
        $ch = curl_init('https://www.howsmyssl.com/a/check');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($data);
        return $json->tls_version;
    }
    public static function getListOfSubscriptions($listType) {
        /* Create a merchantAuthenticationType object with authentication details retrieved from the database */
        $loginID = trim( \get_option( 'authnet_api_login_id', true ) );
        $trans_key = trim( \get_option( 'authnet_api_trans_key', true ));
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($loginID);
        $merchantAuthentication->setTransactionKey($trans_key);
        
        // Set the transaction's refId
        $refId = 'ref' . time();

        $sorting = new AnetAPI\ARBGetSubscriptionListSortingType();
        $sorting->setOrderBy("id");
        $sorting->setOrderDescending(false);

        $paging = new AnetAPI\PagingType();
        $paging->setLimit("100");
        $paging->setOffset("1");

        $request = new AnetAPI\ARBGetSubscriptionListRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSearchType($listType);
        #$request->setSearchType("subscriptionInactive");
        /* Possible Subscription Types 
            cardExpiringThisMonth
            subscriptionActive
            subscriptionExpiringThisMonth
            subscriptionInactive
        */
        $request->setSorting($sorting);
        $request->setPaging($paging);


        $controller = new AnetController\ARBGetSubscriptionListController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {

            $totalSubscriptions = $response->getTotalNumInResultSet();

        } else {
        
            $errorMessages = $response->getMessages()->getMessage();

        }

        return $response;
    }
    public static function cancelSubscription($subscriptionId) {
        /* Create a merchantAuthenticationType object with authentication details retrieved from the database */
        $loginID = trim( \get_option( 'authnet_api_login_id', true ) );
        $trans_key = trim( \get_option( 'authnet_api_trans_key', true ));
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($loginID);
        $merchantAuthentication->setTransactionKey($trans_key);
        
    
        // Set the transaction's refId
        $refId = 'ref' . time();

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        {
            $successMessages = $response->getMessages()->getMessage();
            echo "SUCCESS : " . $successMessages[0]->getCode() . "  " .$successMessages[0]->getText() . "\n";
            
        }
        else
        {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
            
        }
    return $response;
  }

}