<?php
namespace gh_connects\theme;

use gh_connects\theme\Template as Template;
use gh_connects\control\Core as Core;
//Classes
use gh_connects\theme\classes\HeaderAvatar as HeaderAvatar;
//spin it
#\gh_connects\theme\Modules::run();
class Modules extends \GreenheartConnects {
    private function __construct(){

    
    }
    public static function open_page(){
        require_once self::get_plugin_path('theme/views/components/header.php');

    }
    public static function top_logo(){
        require_once self::get_plugin_path('theme/views/components/top_logo.php');
    }
    public static function top_menu(){
        require_once self::get_plugin_path('theme/views/components/top_navigation.php');
    }
    public static function top_avatar(){

    require_once self::get_plugin_path( 'theme/views/classes/header-avatar-class.php');
            
        if( \is_user_logged_in() ){

            $user_instance = \wp_get_current_user();
            $userState = false;
            $payment_status = Core::get_payment_status( $user_instance->ID );
            if( $payment_status ){
               # $user_object = (object)array_merge((array)$user_instance->data, $payment_status );
               $user_object = $user_instance;
            }
            $the_avatar = new HeaderAvatar( $user_object );

        } else {
            $login_url = \wp_login_url();
            header( "Location: ".$login_url );
            exit;

        }
    
        return $userState;

    }
    public static function hero_section(){

    }
    public static function left_col($userState){

    }
    public static function right_col($userState){

    }
    public static function footer(){
        require_once self::get_plugin_path('theme/views/components/footer.php');
    }
    public static function close_page(){
        echo '</body></html>';
    }
}