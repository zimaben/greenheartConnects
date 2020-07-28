<?php
namespace gh_connects\theme;

use gh_connects\theme\Template as Template;
use gh_connects\control\Core as Core;
//Classes
use gh_connects\theme\classes\HeaderAvatar as HeaderAvatar;
use gh_connects\theme\classes\HeaderAvatarLoggedOut as HeaderAvatarLoggedOut;
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
        $userState = false;  
        if( \is_user_logged_in() ){

            $user_instance = \wp_get_current_user();
            $userState = $user_instance;
            $payment_keys = Core::get_payment_keys( $user_instance->ID );
            if( $payment_keys ){
               $userState = (object)array_merge((array)$user_instance->data, $payment_keys );
            }
            $the_avatar = new HeaderAvatar( $userState );

        } else {
            $login_url = \wp_login_url();
            require_once self::get_plugin_path('theme/views/classes/header-avatar-loggedout-class.php');
            $the_avatar = new HeaderAvatarLoggedOut( $login_url );
        }
    
    return $userState;
    }
    public static function hero_section($userState){
        if($userState){
            if($userState->cn_status === 'paid'){
                
                require_once self::get_plugin_path('theme/views/components/hero_section.php');
            } else {
                require_once self::get_plugin_path('theme/views/components/hero_section-unpaid.php');
            }

            
        } else {
            require_once self::get_plugin_path('theme/views/components/hero_section-loggedout.php');
        }
        
    }
    public static function left_col($userState){
        if($userState && $userState->cn_status === 'paid' ){
            require_once self::get_plugin_path('theme/views/components/left_col.php');
        }
    }
    public static function right_col($userState){
        if($userState && $userState->cn_status === 'paid' ){
            require_once self::get_plugin_path('theme/views/components/right_col.php');
        }
    }
    public static function footer(){
        require_once self::get_plugin_path('theme/views/components/footer.php');
    
    }
    public static function close_page(){
        echo '</body></html>';
    }
}