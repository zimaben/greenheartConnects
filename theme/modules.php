<?php
namespace gh_connects\theme;

use gh_connects\theme\Template as Template;
use gh_connects\control\Core as Core;
//Classes
use gh_connects\theme\classes\HeaderAvatar as HeaderAvatar;
use gh_connects\theme\classes\HeaderAvatarLoggedOut as HeaderAvatarLoggedOut;
use gh_connects\theme\classes\HomeHero as HomeHero;
use gh_connects\theme\classes\LeftCol as LeftCol;
use gh_connects\theme\classes\Settings as Settings;
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
    public static function return_remaining_seconds_days($seconds){
        $days = floor($seconds / 86400 );
        if ($days >= 1 ){
            $seconds_to_remove = $days * 86400;
            $seconds_minus_days = $seconds - $seconds_to_remove;
        }
    return (isset($seconds_minus_days)) ? $seconds_minus_days : $seconds;     
    }
    public static function top_login(){
        ?>
        <ul class="menu menu-right"><li class="menu-item"><a href="/login/">login / register</a></li></ul>
        </header>
        <?php
    }
    public static function return_remaining_seconds_hours($seconds){
        $hours = floor($seconds / 3600 );
        if ($hours >= 1 ){
            $seconds_to_remove = $hours * 3600;
            $seconds_minus_hours = $seconds - $seconds_to_remove;
        }
    return (isset($seconds_minus_hours)) ? $seconds_minus_hours : $seconds;     
    }   
    public static function return_remaining_seconds_mins($seconds){
        $mins = floor($seconds / 60 );
        if ($mins >= 1 ){
            $seconds_to_remove = $mins * 60;
            $seconds_minus_mins = $seconds - $seconds_to_remove;
        }
    return (isset($seconds_minus_mins)) ? $seconds_minus_mins : $seconds;     
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
                $strt = new \DateTime($this_array['starttime'], new \DateTimeZone('America/Chicago'));
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
    public static function open_page_norobots(){
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
            if( self::$debug ) error_log('USER LOGGED IN PASSED');
            $user_instance = \wp_get_current_user();
            $userState = $user_instance;
            $payment_keys = Core::get_payment_keys( $user_instance->ID );
            if( $payment_keys ){
               $userState = (object)array_merge((array)$user_instance->data, $payment_keys );
            }
            $avatar_img_id = \get_user_meta( $userState->ID, 'neon_avatar_image', true );
            $userState->avatar_img_id = $avatar_img_id;
            // Get user data by user id
            $userdata = get_userdata( $userState->ID );
            $userState->display_name = $userdata->display_name;
            $the_avatar = new HeaderAvatar( $userState );

        } else {
            
            require_once self::get_plugin_path('theme/views/classes/header-avatar-loggedout-class.php');
            $the_avatar = new HeaderAvatarLoggedOut( \wp_login_url() );

        }
    
    return $userState;
    }
    public static function hero_section($userState){
        if($userState){
            if($userState->cn_status === 'paid'){
                require_once self::get_plugin_path('theme/views/classes/home-hero-class.php');
                require_once self::get_plugin_path('theme/views/classes/condenser-class.php');
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
                    $title = get_the_title( get_the_ID());
                    $stream_array[$index ]['id' ] = get_the_ID();
                    $stream_array[$index ]['starttime'] = $starttime;
                    $stream_array[$index ]['length'] = $duration;
                    $index++;
                endwhile;
                wp_reset_postdata(); 
 
                $closest_item = self::get_closest_time_item($stream_array);
                //If Nearest Livestream
                if($closest_item){
                    $now = new \DateTime("now", new \DateTimeZone('America/Chicago'));
                    $start = new \DateTime( \get_post_meta( $closest_item['id'] , 'ghc_stream_start', true ), new \DateTimeZone('America/Chicago') );  
                    $secs_diff = date_timestamp_get($start) - date_timestamp_get($now);
                    $days = floor($secs_diff / 86400 );
                    $new_secs = self::return_remaining_seconds_days($secs_diff);
                    $hours = floor($new_secs / 3600);
                    $new_secs = self::return_remaining_seconds_hours($new_secs);
                    $mins = floor($new_secs / 60 );
                    $secs = self::return_remaining_seconds_mins($new_secs);
                    $embedcode = \get_post_meta( $closest_item['id'], 'ghc_stream_embed_code', true);
                    //Roll Livestream info into $userState object
                    $userState->nearest_stream_id = $closest_item['id']; 
                    $userState->secs_diff = $secs_diff;
                    $userState->days2livestream = $days;
                    $userState->hours2livestream = $hours;
                    $userState->mins2livestream = $mins;
                    $userState->secs2livestream = $secs;
                    $userState->zoomid = $zoomid;
                    $userState->startDate = $start;
                    $the_home_hero = new HomeHero( $userState );
                } else {
                    require_once self::get_plugin_path('theme/views/components/hero_section-no-upcoming-streams.php');
                }
            } else {
                require_once self::get_plugin_path('theme/views/components/hero_section-payment.php');
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
            #require_once self::get_plugin_path('theme/views/components/left_col_blank.php');
        }
    }
    public static function right_col($userState){
        if($userState && $userState->cn_status === 'paid' ){
            require_once self::get_plugin_path('theme/views/components/right_col.php');
        }
    }
    public static function profile($userState){
        if($userState){
            require_once self::get_plugin_path('theme/views/classes/settings-class.php');
            
            $settings = new Settings( $userState);

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
        require_once self::get_plugin_path('theme/views/components/comments.php');
    }
    public static function splash_footer() {
    ?>
    </div><?php // End of <div id="main">. ?>
    <?php
    /**
     * Fires in the login page footer.
     *
     * @since 3.1.0
     */
    do_action( 'login_footer' ); ?>
    <div class="clear"></div>
        <footer>
            <div class="container-fluid footer">
                <div class="modal-warehouse">
                    <div id="why-join">
                        <div class="login-wrap container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <h1>Why Choose Greenheart Connects?</h1>
                                                <div class="card-p">
                                                    <p>We are at a crux. Times are increasingly more challenging—climate change, extinctions, economic disparities, and social upheavals. To turn the tide, it will take all of us working together to make changes to our ways of life and in how we care for the earth and each other.</p>
                                                </div>
                                                <div class="card-p">
                                                    <p>Greenheart wants to inspire and facilitate a connected global community who knows how to champion change for the sake of the earth and each other. </p>
                                                </div>
                                                <div class="card-p">
                                                    <p>To find out more about Greenheart International, <a href="https://www.greenheart.org" target="_blank">click here</a>. 
                                                    </p>
                                                </div>
                                                <div class="card-p-p">Topics will rotate through the four pillars that Greenheart represents, which include:
                                                </div>
                                                <div class="container-fluid pillarswrap">
                                                    <div class="row">
                                                        <div class="col-3 pillar">
                                                            <h5>Environment</h5>
                                                            <img src="https://i.imgur.com/1SC85MC.png">
                                                        </div>
                                                        <div class="col-3 pillar">
                                                            <h5>Fair Trade</h5>
                                                            <img src="https://i.imgur.com/SCIi0Ys.png">
                                                        </div>
                                                        <div class="col-3 pillar">
                                                            <h5>Personal Development</h5>
                                                            <img src="https://i.imgur.com/zXSa63A.png">
                                                        </div>
                                                        <div class="col-3 pillar">
                                                            <h5>Volunteering</h5>
                                                            <img src="https://i.imgur.com/IiNixSK.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>			
                    </div>
                    <div id="membership-levels">
                        <div class="login-wrap">
                            <h4 class="grid-header">Membership packages:</h4>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6 grid-left" style="background:white;border-top:0;border-left:0"></div>
                                    <div class="col-3 grid-center"><h5>Monthly</h5></div>
                                    <div class="col-3 grid-right"><h5>Quarterly</h5></div>
                                </div>
                                <div class="row">
                                <div class="col-6 grid-left"><h5>Cost*</h5></div> 
                                    <div class="col-3 grid-center">$7</div>
                                    <div class="col-3 grid-right">$15 (save 28%)</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 grid-left">Number of Episodes</div>
                                    <div class="col-3 grid-center">1</div>
                                    <div class="col-3 grid-right">3</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 grid-left">Access to live Q &amp; A</div>
                                    <div class="col-6 grid-right"><img src="<?php echo self::get_plugin_url('library/dist/css/img/check-circle-green.svg')?>"></div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-6 grid-left">Access to past episodes</div>
                                    <div class="col-6 grid-right"><img src="<?php echo self::get_plugin_url('library/dist/css/img/check-circle-green.svg')?>"></div>
                                    
                                </div>
                                <div class="row last-row">
                                    <div class="col-6 grid-left">Access to resources</div>
                                    <div class="col-6 grid-right"><img src="<?php echo self::get_plugin_url('library/dist/css/img/check-circle-green.svg')?>"></div>
                                    
                                </div>
                            </div>
                            <div class="grid-footer">*Credit Card payments will be automatic and recurring until canceled.</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    }
    public static function login_footer($input_id = '') {
        global $interim_login;
    
        // Don't allow interim logins to navigate away from the page.
        if ( ! $interim_login ): ?>
        <!-- <p id="backtoblog"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php
            /* translators: %s: site title */
            printf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) );
        ?></a></p> -->
        <?php endif; ?>
    
            
                </div><!--end bootstrap row-->
            </div><!--end container-->
        </div><?php // End of <div id="login">. ?>
    
        <?php if ( !empty($input_id) ) : ?>
        <script type="text/javascript">
        try{document.getElementById('<?php echo $input_id; ?>').focus();}catch(e){}
        if(typeof wpOnload=='function')wpOnload();
        </script>
        <?php endif; ?>
    
        <?php
        /**
         * Fires in the login page footer.
         *
         * @since 3.1.0
         */
        do_action( 'login_footer' ); ?>
        <div class="clear"></div>
        <footer>
            <div class="container-fluid footer">
                <div class="modal-warehouse">
                    <div id="why-join">
                        <div class="login-wrap container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12">
                                                <h1>Why Choose Greenheart Connects?</h1>
                                                <div class="card-p">
                                                    <p>We are at a crux. Times are increasingly more challenging—climate change, extinctions, economic disparities, and social upheavals. To turn the tide, it will take all of us working together to make changes to our ways of life and in how we care for the earth and each other.</p>
                                                </div>
                                                <div class="card-p">
                                                    <p>Greenheart wants to inspire and facilitate a connected global community who knows how to champion change for the sake of the earth and each other. </p>
                                                </div>
                                                <div class="card-p">
                                                    <p>To find out more about Greenheart International, <a href="https://www.greenheart.org" target="_blank">click here</a>. 
                                                    </p>
                                                </div>
                                                <div class="card-p-p">Topics will rotate through the four pillars that Greenheart represents, which include:
                                                </div>
                                                <div class="container-fluid pillarswrap">
                                                    <div class="row">
                                                        <div class="col-3 pillar">
                                                            <h5>Environment</h5>
                                                            <img src="https://i.imgur.com/1SC85MC.png">
                                                        </div>
                                                        <div class="col-3 pillar">
                                                            <h5>Fair Trade</h5>
                                                            <img src="https://i.imgur.com/SCIi0Ys.png">
                                                        </div>
                                                        <div class="col-3 pillar">
                                                            <h5>Personal Development</h5>
                                                            <img src="https://i.imgur.com/zXSa63A.png">
                                                        </div>
                                                        <div class="col-3 pillar">
                                                            <h5>Volunteering</h5>
                                                            <img src="https://i.imgur.com/IiNixSK.png">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>			
                    </div>
                    <div id="membership-levels">
                        <div class="login-wrap">
                            <h4 class="grid-header">Membership packages:</h4>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6 grid-left" style="background:white;border-top:0;border-left:0"></div>
                                    <div class="col-3 grid-center"><h5>Monthly</h5></div>
                                    <div class="col-3 grid-right"><h5>Quarterly</h5></div>
                                </div>
                                <div class="row">
                                <div class="col-6 grid-left"><h5>Cost*</h5></div> 
                                    <div class="col-3 grid-center">$7</div>
                                    <div class="col-3 grid-right">$15 (save 28%)</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 grid-left">Number of Episodes</div>
                                    <div class="col-3 grid-center">1</div>
                                    <div class="col-3 grid-right">3</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 grid-left">Access to live Q &amp; A</div>
                                    <div class="col-6 grid-right"><img src="<?php echo self::get_plugin_url('library/dist/css/img/check-circle-green.svg')?>"></div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-6 grid-left">Access to past episodes</div>
                                    <div class="col-6 grid-right"><img src="<?php echo self::get_plugin_url('library/dist/css/img/check-circle-green.svg')?>"></div>
                                    
                                </div>
                                <div class="row last-row">
                                    <div class="col-6 grid-left">Access to resources</div>
                                    <div class="col-6 grid-right"><img src="<?php echo self::get_plugin_url('library/dist/css/img/check-circle-green.svg')?>"></div>
                                    
                                </div>
                            </div>
                            <div class="grid-footer">*Credit Card payments will be automatic and recurring until canceled.</div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        </body>
        </html>
        <?php
    }
    public static function hack_in_login_enqueue(){
       #\add_action('wp_head', array(get_class(), 'login_enqueue_hack')); 
        \do_action( 'login_enqueue_scripts' );
    }
    public static function login_enqueue_hack(){
        \do_action( 'login_enqueue_scripts' );
    }
}