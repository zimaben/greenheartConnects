<?php
namespace obp_emailer;

//spin it up
Emailer::get_instance();

class Emailer {

    private static $instance = null;
    const parentName = 'Greenheart Connects';
    const description = 'This Email will send once it\'s been connected to a trigger.';

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    private function __construct() {   
        self::load_libraries();
    }
    public static function load_libraries() {
        require_once dirname(__FILE__) . '/emailer/email-options.php'; 
        require_once dirname(__FILE__) . '/emailer/email-send.php'; 
    }
}