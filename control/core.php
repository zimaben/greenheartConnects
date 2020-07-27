<?php
namespace gh_connects\control;

//spin it
#\gh_connects\control\Core::run();



class Core extends \GreenheartConnects {
    private function __construct(){

    }

    public static function get_payment_status( $userid ){
        return true;
    }

}