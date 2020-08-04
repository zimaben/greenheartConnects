<?php
namespace gh_connects\theme;

use gh_connects\theme\Template as Template;
use gh_connects\control\Core as Core;
//Classes
use gh_connects\theme\classes\HeaderAvatar as HeaderAvatar;
use gh_connects\theme\classes\HeaderAvatarLoggedOut as HeaderAvatarLoggedOut;
use gh_connects\theme\classes\HomeHero as HomeHero;
use gh_connects\theme\classes\LeftCol as LeftCol;
//spin it
#\gh_connects\theme\Modules::run();
class Modules extends \GreenheartConnects {
    private function __construct(){

    
    }
    public static function date_sort($a, $b)
    {
        $t1 = strtotime($a['starttime']);
        $t2 = strtotime($b['starttime']);
        return $t1 - $t2;
    }   
    public static function get_closest_time_item($stream_array){
        if( $stream_array && isset( $stream_array) ){
            $return_array= array();
            //Sort array farthest to closest
            if(count($stream_array) > 1 ) {
                usort($stream_array, array(get_class(),'date_sort'));
            }
            //filter past items
            
            foreach($stream_array as $this_array){
                $now = new \DateTime("now", new \DateTimeZone('America/Chicago'));
                $strt = new \DateTime($this_array['starttime']);
                date_modify($strt, '+' .$this_array['length'].'minutes' );
                if($strt->format("Y-m-d H:i:s") > $now->format("Y-m-d H:i:s")){

                    array_push($return_array,$this_array);
                    
                }
            }
            
            if( isset($return_array) ){
                $returnelem = $return_array[0];
                return $returnelem; 
            } else {
                return false;
            }    
        } else {
            return false;
        }   
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
                require_once self::get_plugin_path('theme/views/classes/home-hero-class.php');
                #START HERO INFO LOOP
                $args = array(  
                    'post_type' => 'streams',
                    'post_status' => 'publish',
                    'posts_per_page' => -1, 
                    'order' => 'ASC',
                );

                $today = new \DateTime();
                $loop = new \WP_Query( $args ); 
                $stream_array = array();
                $index = 0;
   
                while ( $loop->have_posts() ) : $loop->the_post(); 
                    $starttime = get_post_meta( get_the_ID() , 'ghc_stream_start', true );
                    $duration = get_post_meta( get_the_ID(), 'ghc_stream_length', true );
                    $stream_array[$index ][ 'id' ] = get_the_ID();
                    $stream_array[$index ]['starttime'] = $starttime;
                    $stream_array[$index ]['length'] = $duration;
                    $index++;
                endwhile;
                wp_reset_postdata(); 
                //Get Nearest Livestream
                /*if(count($stream_array) > 1 ) {    
                    usort($array, array(get_class(),'date_sort'));
                }
                $closest_stream_id = $stream_array[count($stream_array) - 1 ]['id'];
                */
                $closest_item = self::get_closest_time_item($stream_array);
                //If Nearest Livestream
                if($closest_item){
                    //Roll Livestream info into $userState object
                    $userState->nearest_stream_id = $closest_item['id']; 
                    $the_home_hero = new HomeHero( $userState );
                } else {
                    require_once self::get_plugin_path('theme/views/classes/hero_section-no-upcoming-streams.php');
                }
            } else {
                require_once self::get_plugin_path('theme/views/components/hero_section-unpaid.php');
            }    
        } else {
            require_once self::get_plugin_path('theme/views/components/hero_section-loggedout.php');
        }
    return $userState;  
    }
    public static function left_col($userState){
        if($userState && $userState->cn_status === 'paid' ){
            require_once self::get_plugin_path('theme/views/classes/left-col-class.php');
            $the_left_column = new LeftCol( $userState );
        } else {
            require_once self::get_plugin_path('theme/views/components/left_col_blank.php');
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
    public static function single_video($userState){

    }
    public static function single_col($userState){

    }
    public static function single_comments($userState){

    }
}