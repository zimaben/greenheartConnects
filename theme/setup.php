<?php
namespace gh_connects\theme;
use gh_connects\theme\Modules as Modules;
use gh_connects\control\Core as Core;
//spin it
\gh_connects\theme\Setup::run();



class Setup extends \GreenheartConnects {
    public static function run(){
        //add role caps
        \add_action( 'plugins_loaded', array( get_class(),'add_subscriber_upload' ));
        //this annoying thing
        \add_filter( 'wp_image_editors', array( get_class(),'wpb_image_editor_default_to_gd' ));
        //add login & app main page
        \add_action( 'init', array( get_class(), 'add_custom_login' ), 1 );
        \add_action ( 'init', array( get_class(), 'add_custom_registration'), 1 );
        \add_action( 'init', array( get_class(), 'add_dashboard' ), 1 );
        \add_action( 'init', array( get_class(), 'add_profile' ), 1 );
        \add_action( 'init', array( get_class(), 'add_homesplash' ), 1 );
        \add_action( 'wp_loaded', array( get_class(), 'gate_generic_page_templates'));

        //add custom styling and bootstrap to login page
        \add_action( 'login_enqueue_scripts', array( get_class(), 'login_enqueue') ); //add login page styles
        \add_action( 'wp_enqueue_scripts', array( get_class(), 'connects_enqueue') );
        \add_action( 'admin_enqueue_scripts', array( get_class(), 'connects_admin_enqueue') );

        //assign templates to added plugin pages
        \add_filter( 'page_template', array( get_class(), 'set_login_template' ), 99 );
        
        \add_filter( 'page_template', array( get_class(), 'set_registration_template' ), 99 );
        \add_filter( 'page_template', array( get_class(), 'set_main_template' ), 99 );
        \add_filter( 'page_template', array( get_class(), 'set_profile_template' ), 99 );
        \add_filter( 'page_template', array( get_class(), 'set_homesplash_template'), 99 );
        /* Filter the single_template with our custom function*/
        \add_filter('single_template', array( get_class(),'ghc_postype_assign_templates') );
        \add_filter( 'archive_template', array( get_class(),'ghc_postype_assign_archive_templates') );

        //Add video post type
        \add_action( 'init', array( get_class(), 'add_video_cpt' )  ); 
        //Add meta boxes for editing
        \add_action( 'add_meta_boxes', array( get_class(),'add_video_metabox' ) );
        \add_action( 'add_meta_boxes', array( get_class(),'add_public_page_metabox' ) );
        //Add meta save function 
        \add_action('save_post', array( get_class(), 'video_metabox_save') );
        \add_action('save_post', array( get_class(), 'public_page_metabox_save'));

        //Add promo post type
        \add_action( 'init', array( get_class(), 'add_promo_cpt') );
        //Add meta box
        \add_action( 'add_meta_boxes', array( get_class(), 'add_promo_metabox') );
        //Add meta save function
        \add_action( 'save_post', array( get_class(), 'promo_metabox_save') );
        
        //Add livestream post type
        \add_action( 'init', array( get_class(), 'add_stream_cpt' )  ); 
        //Add meta boxes for editing
        \add_action( 'add_meta_boxes', array( get_class(),'add_stream_metabox' ) );
        //Add meta save function 
        \add_action('save_post', array( get_class(), 'stream_metabox_save') );

        \add_action( 'add_meta_boxes', array( get_class(),'add_login_video_embed_metabox' ) );
        //Add meta save function 
        \add_action('save_post', array( get_class(), 'login_embed_metabox_save') );


        \add_filter( 'login_url', array(get_class(), 'set_wp_login_page' ), 10, 3 );      //update login url for core functionality to work with
        \add_filter( 'register_url', array(get_class(), 'set_wp_register_page' ), 10, 1 );//update register url for core functionality
        \add_filter( 'lostpassword_url', array(get_class(),'set_wp_lost_password_page'), 10, 2 );//update lost password url for core functionality
        \add_filter( 'registration_redirect', array(get_class(), 'after_registration_home') );
        \add_filter( 'login_redirect', array(get_class(), 'after_registration_home') );
        \add_action('wp_logout', array(get_class(),'logout_home'));
        \add_action('template_redirect', array(get_class(), 'home_page_loggedin'));

        //add Javascript Actions
        \add_action('wp_ajax_cheatMetaKeys', array(get_class(),'cheatMetaKeys'));
        \add_action('wp_ajax_nopriv_cheatMetaKeys', array(get_class(),'cheatMetaKeys'));

        \add_action('wp_ajax_updateUserPhoto', array( get_class(), 'updateUserPhoto' ));
        #\add_action('wp_ajax_nopriv_updateUserPhoto', array( get_class(),'updateUserPhoto' )); 
        
        \add_action('wp_ajax_updateUserName', array( get_class(), 'updateUserName' ));
        \add_action('wp_ajax_nopriv_updateUserName', array( get_class(),'updateUserName' )); 

        \add_action('wp_ajax_updateEmail', array( get_class(), 'updateEmail' ));
        \add_action('wp_ajax_nopriv_updateEmail', array( get_class(),'updateEmail' )); 

        \add_action('wp_ajax_updateConsent', array( get_class(), 'updateConsent' ));
        \add_action('wp_ajax_nopriv_updateConsent', array( get_class(),'updateConsent' )); 

        \add_action('wp_ajax_updatePassword', array( get_class(), 'updatePassword' ));
        \add_action('wp_ajax_nopriv_updatePassword', array( get_class(),'updatePassword' )); 

        \add_action('wp_ajax_ghc_BTTTTR_deleteUser', array( get_class(), 'ghc_BTTTTR_deleteUser' ));
        \add_action('wp_ajax_nopriv_ghc_BTTTTR_deleteUser', array( get_class(),'ghc_BTTTTR_deleteUser' )); 
        
        //Javascript wp_ajax Real Time Comments
        \add_action('wp_ajax_get_new_comments', array( get_class(), 'get_new_comments'));
        \add_action('wp_ajax_nopriv_get_new_comments', array( get_class(), 'get_new_comments'));

        \add_action( 'comment_post', array(get_class(), 'add_ajax_comment'), 20, 2 );
        \add_filter( 'comment_form_submit_button',array(get_class(), 'add_new_comment_button'), 10, 2 );


        //set Javascript variables
        \add_action( 'wp_head', array(get_class(), 'set_plugin_js_variables'));
        \add_action( 'login_head', array(get_class(), 'set_plugin_js_variables') );

        \add_filter( 'comments_open', array(get_class(), 'open_cpt_comments'), 10, 2 );
        
        //image sizes
        \add_action( 'after_setup_theme', array(get_class(), 'gh_connects_image_sizes'));

        /* Disable the Admin Bar. */
        \add_filter( 'show_admin_bar', array(get_class(), 'kill_topbar'), 100, 1);
        \remove_action( 'wp_head', '_admin_bar_bump_cb' );
        
        \add_filter( 'wp_nav_menu_items', array(get_class(), 'loggedin_filter_nav_links'), 10, 2 );

        /* Do Analytics */
         \add_action( 'wp_head', array(get_class(), 'do_analytics') );
         \add_action( 'login_head', array(get_class(), 'do_analytics') );

         /* Filter Menu */
         \add_filter( 'wp_nav_menu_args', array(get_class(), 'connects_filter_menu'), 10, 2 );
         
         /* Disable Admin Email Confirmation */
        \add_filter( 'admin_email_check_interval', array(get_class(), 'disable_admin_email_confirmation' ));


    }


    public static function home_page_loggedin(){
        if(!\is_front_page() ) return false;
        if(!\is_user_logged_in() ) return false;
        $user = \wp_get_current_user();
        $roles = $user->roles;
        if( in_array('ghc_user', $roles)){
            \wp_safe_redirect( \get_home_url(). '/dashboard/');
            exit();
        }
        
    }

    public static function disable_admin_email_confirmation( $interval ) {
        return 0;
    }
    public static function logout_home(){
        \wp_safe_redirect( home_url() );
        exit;
      }
    public static function connects_filter_menu($args){
        if($args['menu_id'] == 'primary-menu'){
            if( \is_user_logged_in() ){
                $args['menu'] = 'logged-in';
            } else {
                $args['menu'] = 'logged-out';
            }
        }
        return $args;
    }
    //AJAX RT Comments 
    public static function add_new_comment_button( $submit_button, $args ) {
        #<input name="submit" type="submit" id="submit" class="submit" value="Post Comment" />
        if ( \get_post_type( \get_the_ID() ) == 'streams' ) {
            
            $submit_button = '<button id="submit" class="submit" onclick="addAjaxComment(event);return false;">Post Comment</button>'; 
        }
        return $submit_button;
    }
    public static function add_ajax_comment( $comment_ID, $comment_status ){
        //arbitrary flag in the request
        if( isset($_POST['doing_ghc_ajax_comment']) ){
            #bail if moderation settings prevent real time comments
            if($comment_status == 0) return false;
            $post_id = $_POST['comment_post_ID'];
            $comments = \get_comments(array(
                    'post_id' => $post_id,
                    'status' => 'approve' 
                ));
            $com_code = \wp_list_comments(array(
                'per_page' => 50, 
                'reverse_top_level'=> false,
                'echo'=> false), $comments);
            $com_code = str_replace(array("\r", "\n", "\t", "\v" ), '', $com_code );
            $com_code = self::remove_html_comments($com_code);
            if($com_code){
                $ret = array(
                    "status"=>200, 
                    "markup"=>$com_code);
            } else {
                $ret = array("status"=>400, "markup"=> "Failed to load comment");
            }
            
            // echo it back
            die( json_encode($ret) );
            #wp_die(); cant use wp_die(), hijacking the core comment handler this way produces WP Error object, which will wreck our json if allowed
            //stop downstream actions like page reload
            }
    }
    public static function remove_html_comments($content = '') {
        return preg_replace('/<!--(.|\s)*?-->/', '', $content);
    }
    public static function get_new_comments(){
        if( isset($_POST['comment_count']) && isset($_POST['post_id']) ){
            #check if Real Time Commenting is Disabled
            $RTC_enabled = (int)\get_option( 'do_realtime_comments' );
            if($RTC_enabled !== 1){
                $retval = array('status'=>200,'killRTC'=>'true','markup'=>'Real Time Commenting has been turned off for this post. Please refresh page.');
                echo json_encode($retval);
                wp_die();
            }
            $client_count = (int)$_POST['comment_count'];
            $post_id = $_POST['post_id'];

            $server_count = \get_comments_number($post_id);
            if($server_count > $client_count){
                $comments = \get_comments(array(
                    'post_id' => $post_id,
                    'status' => 'approve' 
                ));
                $com_code = \wp_list_comments(array(
                    'per_page' => 50, 
                    'reverse_top_level'=> false,
                    'echo'=> false), $comments);
                $com_code = str_replace(array("\r", "\n", "\t", "\v" ), '', $com_code );
                $com_code = self::remove_html_comments($com_code);
                $retval = array(
                    'status'=>200, 
                    'commentCount'=>$server_count, 
                    'markup'=>$com_code);
                echo json_encode($retval);
                wp_die();
            } else {
                $retval = array('status'=>200, 'commentCount'=>$server_count);
                echo json_encode($retval);
                wp_die();
            } 
        } else {
            $retval = array('status'=>400, 'message'=>'comment is missing required data');
            echo json_encode($retval);
            wp_die();
        }
    $retval = array('status'=>400, 'message'=>'Bad Request');
    echo json_encode($retval);
    wp_die(); 
    }
    public static function do_analytics(){
        $x = \get_option( 'do_ga' );
        if( \get_option( 'do_ga' )){
            echo get_option( 'google_analytics_code' );
        }
    }
    public static function loggedin_filter_nav_links( $items, $args ) {
        $user_instance = \wp_get_current_user();
        $payment_keys = Core::get_payment_keys( $user_instance->ID );
        if($payment_keys['cn_status'] == 'unpaid'){
            $new_items = str_replace('<a href="https://greenheartconnects.org/resources/">Resources</a></li>','', $items);
            return $new_items;
        }
        return $items;
    }
    public static function wpb_image_editor_default_to_gd( $editors ) {
        $gd_editor = 'WP_Image_Editor_GD';
        $editors = array_diff( $editors, array( $gd_editor ) );
        array_unshift( $editors, $gd_editor );
        return $editors;
    }
    public static function add_subscriber_upload(){
        $role = get_role( 'subscriber' );
        $role->add_cap( 'upload_files' );
    }
    
    public static function gate_generic_page_templates(){
        if ( basename(\get_page_template()) === 'page.php' ) {      
            \add_filter( 'page_template', function($page_template){
                $page_template = \GreenheartConnects::get_plugin_path( 'theme/views/single-page.php' );
                return $page_template;
            }, 5);
        }   
    }

    public static function kill_topbar($admin_bar){
        return false;
    }

    public static function ghc_BTTTTR_deleteUser(){
        //add nonce check
        $userid = $_POST['userid'];

       # $updated = \wp_delete_user( $userid );
       $user_info = get_userdata($userid);
       $user_name = $user_info->display_name;
       $user_email = $user_info->user_email;
       $user_string = $user_name .", ". $user_email;
       $admin_email = get_option('admin_email');
       $finance_email = self::finance_email;
       $message = 'Please cancel Greenheart Connects billing for '.$user_string .'.';
       $headers = array(
                        'From: support@greenheart.org', 
                        'CC: '.$admin_email
                    );
        $updated = wp_mail( 
            $finance_email, 
            'Please stop billing for GH Connects account', 
            $message, 
            $headers
        );

        if($updated){
            echo json_encode( array('status'=>200, 'message'=> 'Account has been marked for cancellation.'));
        } else {
            echo json_encode( array('status'=>400, 'message'=>'There was a problem canceling your account. Please email support@greenheart.org for further assistance.'));
        }
        die();
    }
    public static function updateConsent(){
        
        $userid = $_POST['userid'];
        $field = false;
        $value = false;
        $updated = false;
        foreach($_POST as $key => $val){
            if( $key!=='userid'){

                $field = 'ghc_consent_'.$key;
                $value = $val;
            }

        }
        if( $userid && $field && $value){

            $updated = \update_user_meta( $userid, $field, $value );
        }
     
        if($updated){
            echo json_encode( array('status'=>200, 'message'=> 'Your settings have been updated.'));
        } else {
            echo json_encode( array( 'status'=>400, 'message'=> 'There was a problem updating your settings.'));
        }   
        die();
    }
    public static function updatePassword(){

        $userid = $_POST['userid'];
        $password = $_POST['password'];
        //add nonce check
        if(strlen($password) < 8 ){
        
            echo json_encode( array('status'=>200, 'message'=> 'Passwords must be at least 8 characters in length.'));      
        
        } else {

            $updated = \wp_update_user( array( 'ID' => $userid, 'password' => $password ) );
            
            if($updated){
                echo json_encode( array('status'=>200, 'message'=> 'Your password has been updated.'));
            } else {
                echo json_encode( array( 'status'=>400, 'message'=> 'There was a problem updating your password.'));
            } 
        }     
        die();
    }
    public static function updateUserName(){
        //add nonce check
        $userid = $_POST['userid'];
        $name = $_POST['name'];
        $updated = \wp_update_user( array( 'ID' => $userid, 'display_name' => $name ) );
        $updated2 = \wp_update_user( array( 'ID' => $userid, 'user_nicename' => $name ) );
        if($updated && $updated2){
            echo json_encode( array('status'=>200, 'message'=> 'Your name has been updated.'));
        } else {
            echo json_encode( array('status'=>400, 'message'=>'There was a problem updating your name.'));
        }
        die();
    }
    public static function updateEmail(){
        //add nonce check
        $userid = $_POST['userid'];
        $email = $_POST['email'];
        $updated = \wp_update_user( array( 'ID' => $userid, 'user_email' => $email ) );
        if($updated){
            echo json_encode( array('status'=>200, 'message'=> 'Your email has been updated.'));
        } else {
            echo json_encode( array( 'status'=>400, 'message'=> 'There was a problem updating your email.'));
        }      
        die();
    }
    public static function updateUserPhoto(){
        $userid = $_POST['data']['userid'];
        $attachmentid = $_POST['data']['attachmentid'];
        $updated = update_user_meta( $userid, 'neon_avatar_image', $attachmentid );
        echo ($updated) ? json_encode(array('status' => '200')) : json_encode(array('status' => '400'));
        //yes/no response
        die();
    }

    public static function set_plugin_js_variables(){
        echo '<!-- SET JS VARIABLES -->';
        echo '<script type="text/javascript">';
        echo 'const ajaxurl="'.\admin_url('admin-ajax.php').'";';
        echo 'const CN_DEBUG="'.self::$debug.'";';
        echo '</script>';
    }
    public static function after_registration_home( $registration_redirect ) {
        return \home_url().'/dashboard/';
    }
    public static function cheatMetaKeys(){
        $userID = $_POST['userID'];
        \update_user_meta( $userID, 'cn_last_payment_on', date('d-m-Y') );
        \update_user_meta( $userID, 'cn_last_payment_amt', 10 );
        \update_user_meta( $userID, 'cn_status', 'paid' );
        $response = array('status'=> 200, 'message'=>'hell yeah');
        echo json_encode( $response );
        //JSON calls, like surf nazis, must die.
        die(); 
    }
    public static function add_public_page_metabox(){
        $screen = \get_current_screen();
        error_log(print_r($screen, true));
        \add_meta_box(
            'public_page', #id
            'Visible to Logged-Out Users', #title
            array( get_class(), 'call_public_page_metabox'), #callback function
            'page',
            'side',
            'high'
        );
    }
    public static function add_video_metabox(){
        \add_meta_box(
            'video_url', #id
            'Video URL or File Location', #title
            array( get_class(),'call_video_url_metabox'), #callback function
            'videos', # WP Screen (just use post type)
            'normal', #center column
            'high' 
        );
    }
    public static function add_stream_metabox(){
        \add_meta_box(
            'stream_meta', #id
            'Livestream Details', #title
            array( get_class(),'call_stream_meta_metabox'), #callback function
            'streams', # WP Screen (just use post type)
            'normal', #center column
            'high' 
        );
    }
    public static function add_promo_metabox(){
        \add_meta_box(
            'promo_meta', #id
            'Promo Code Details',
            array( get_class(), 'call_promo_metabox'), #cb
            'promo',
            'normal',
            'high'
        );
    }
    public static function add_login_video_embed_metabox(){ 
        $login_page_object = get_page_by_path('login');
        $post_id = (isset($_GET['post'])) ? $_GET['post'] : false;
        //show this metabox only if you are editing a page with slug 'login'
        if ( $post_id && $post_id == $login_page_object->ID ) {
            \add_meta_box(
                'login_video_embed_meta', #id
                'Embed Code for Login Video', #title
                array( get_class(),'call_login_embed_metabox'), #callback function
                'page', # WP Screen (just use post type)
                'normal', #center column
                'high' 
            );
        }
    }
    public static function call_login_embed_metabox(){
        global $post;
        \wp_nonce_field( 'loginvid_metabox_nonce', 'loginvid_metabox_nonce' );
        $vidurl_meta = get_post_meta( $post->ID, 'ghc_login_video',true );
         if( $vidurl_meta ) {
        ?>
        <h4>Login Page Video Embed</h4>
        <label for="ghc_loginvid_embed">Paste the full embed code below:</label>
        <textarea rows="5" cols="50" style="width:100%;" id="ghc_loginvid_embed" name="ghc_loginvid_embed">
        <?php echo $vidurl_meta ?>
        </textarea>
        <?php
        } else {
            ?>
            <h4>Login Page Video Embed</h4>
        <label for="ghc_loginvid_embed">Paste the full embed code below:</label>
        <textarea rows="4" cols="5" id="ghc_loginvid_embed" name="ghc_loginvid_embed"></textarea>
            <?php
        }
    }
    public static function call_public_page_metabox(){
        global $post;
        \wp_nonce_field( 'public_page_metabox_nonce', 'public_page_metabox_nonce' );
        $public_page_checkstatus = get_post_meta($post->ID, 'public_page_checkstatus', true);
        ?>
        <h4>Make Page Public to Non-Logged in Users?</h4>
        <div>
          <input type="checkbox" id="public_page_checkstatus" name="public_page_checkstatus" value="true" <?php echo ($public_page_checkstatus == 'true') ? ' checked' : '' ?>>
          <label for="public_page_checkstatus">Make Visible to Everyone</label>
        </div>
        <?php

    }
    public static function call_video_url_metabox(){
        global $post;
        \wp_nonce_field( 'vidurl_metabox_nonce', 'vidurl_metabox_nonce' );
        $vidurl_meta = get_post_meta( $post->ID, 'ghc_video_path',true );
        $vidurl_meta_prev = get_post_meta( $post->ID, 'ghc_video_preview', true );
        $vidtype_meta = get_post_meta( $post->ID, 'ghc_video_type',true );
        $vid_author_bio = get_post_meta( $post->ID, 'ghc_author_bio',true );
        $vid_author_name = get_post_meta( $post->ID, 'ghc_video_author', true);

        if( $vidurl_meta && $vidtype_meta ) {
        ?>
        <h4>Author Name</h4>
        <input type="text" id="ghc_video_author" name="ghc_video_author" value="<?php echo $vid_author_name ?>">
        <h4>Author Bio</h4>
        <textarea type="textarea" rows="5" cols="50" class="widefat" style="width:100%;" id="ghc_author_bio" name="ghc_author_bio"><?php echo $vid_author_bio?></textarea>
        <h4>Video Embed Code</h4>
        <textarea type="textarea" rows="5" cols="50" class="widefat" style="width:100%;" id="ghc_video_path" name="ghc_video_path"><?php echo $vidurl_meta ?></textarea>

        <h4>Preview Embed Code</h4>
        <span>Upload a preview/teaser video</span>
        <textarea type="textarea" rows="5" cols="50" class="widefat" style="width:100%;" id="ghc_video_preview" name="ghc_video_preview"><?php echo $vidurl_meta_prev ?></textarea>

        <input type="radio" style="display:none;" id="vidtypefile" name="ghc_video_type" value="file"<?php echo ($vidtype_meta == "file") ? 'checked' : ''?>>
        <input type="radio" style="display:none;" id="vidtypeembed" name="ghc_video_type" value="embed"<?php echo ($vidtype_meta == "embed") ? 'checked' : ''?>>
        
        <?php
        } else {
            ?>
            <h4>Video Embed Code:</h4>
            <textarea type="textarea" rows="5" cols="50" class="widefat" style="width:100%;" id="vidurl" name="ghc_video_path"></textarea>
            <h4>Preview Embed Code</h4>
            <span>Upload a preview/teaser video</span>
            <textarea type="textarea" rows="5" cols="50" class="widefat" style="width:100%;" id="ghc_video_preview" name="ghc_video_preview"><?php echo $vidurl_meta_prev ?></textarea>
            <h4>Type:</h4>
            <input type="radio" id="vidtypefile" name="ghc_video_type" value="file">
            <label for="vidtypefile">File</label><br>
            <input type="radio" id="vidtypeembed" name="ghc_video_type" value="embed">
            <label for="vidtypefile">Embed</label><br>
            <?php
        }
    }

    public static function call_stream_meta_metabox(){
        global $post;
        \wp_nonce_field( 'stream_metabox_nonce', 'stream_metabox_nonce' );
        $streamstart_meta = get_post_meta( $post->ID, 'ghc_stream_start',true );
        $streamlength_meta = get_post_meta( $post->ID, 'ghc_stream_length',true );
        $streamspeaker_meta = get_post_meta( $post->ID, 'ghc_author_name',true );
        $streambio_meta = get_post_meta( $post->ID, 'ghc_author_bio',true );
        $streambio_name = get_post_meta( $post->ID, 'ghc_author_name',true );
        $streamid_meta = get_post_meta( $post->ID, 'ghc_stream_embed_code', true );
        $stream_chat = get_post_meta( $post->ID, 'ghc_stream_embed_chat', true);
        $stream_preview = get_post_meta( $post->ID, 'ghc_stream_preview',true );
        //$stream_promo = get_post_meta( $post->ID, 'ghc_stream_promo', true );
        //$stream_promocount = get_post_meta( $post->ID, 'ghc_stream_promocount', true);
        ?>
        <h4>Live Stream Details</h4>
        <div id="timepicker_container" style="position:relative;max-width:60%;margin:0 auto;"></div>
        <input type="text" class="form-control js-full-picker" id="ghc_stream_start" name="ghc_stream_start" value="<?php echo ($streamstart_meta) ? $streamstart_meta : '' ?>">
        <label for="ghc_stream_start">Start Date/Time (CST):</label><br>
        <input type="text" pattern="[0-9]{1,3}" id="ghc_stream_length" name="ghc_stream_length" value="<?php echo ($streamlength_meta) ? $streamlength_meta : ''?>">
        <label for="vidtypefile">Minutes:</label><br>
        <input type="text" id="ghc_author_name" name="ghc_author_name" value="<?php echo ($streamspeaker_meta) ? $streamspeaker_meta : ''?>">
        <label for="ghc_author_name">Author Name:</label><br>
        <label for="ghc_stream_embed_code">Embed Code of Stream:</label><br>
        <textarea type="textarea" style="width:100%;" id="ghc_stream_embed_code" name="ghc_stream_embed_code"><?php echo ($streamid_meta) ? $streamid_meta : ''?></textarea>   
        <label for="ghc_stream_embed_chat">Embed Code for Live Comments:</label><br>
        <textarea type="textarea" style="width:100%;" id="ghc_stream_embed_chat" placeholder="Leave blank for no YouTube live comments. Ex: <iframe width=700 height=200 src='htt‍ps://www‍.youtube.com/live_chat?v=XXXXXX&embed_domain=GREENHEARTCONNECTS.ORG'>"></iframe>" name="ghc_stream_embed_chat"><?php echo ($stream_chat) ? $stream_chat : ''?></textarea>    
        <label for="ghc_stream_embed_code">Video Preview Embed Code:</label><br>
        <textarea type="textarea" style="width:100%;" id="ghc_stream_preview" name="ghc_stream_preview"><?php echo ($stream_preview) ? $stream_preview: ''?></textarea><br>
        <label for="ghc_author_name">Author Name:</label><br>
        <input type="text" style="margin-bottom:20px;" id="ghc_author_name" name="ghc_author_name" value="<?php echo ($streambio_name) ? $streambio_name: ''?>"/>
        <br>
        <label for="ghc_author_bio">Author Bio:</label><br>
        <textarea type="textarea" class="widefat" style="width:100%;" id="ghc_author_bio" name="ghc_author_bio"><?php echo ($streambio_meta) ? $streambio_meta: ''?></textarea>
        <?php 
            #$new_promo_markup = '<label for="ghc_stream_promo">Promo Code:</label><br><input type="text" id="ghc_stream_promo" name="ghc_stream_promo" value=""><br>';
           # $exists_promo_markup = '<p>Promo Code (once set, Promo Codes cannot be changed): ';
        #echo ($stream_promo) ? $exists_promo_markup.$stream_promo.'</p>' : $new_promo_markup; ?>
        <!--<label for="ghc_stream_promocount">Promo Codes Remaining:</label><br>
        <input type="text" id="ghc_stream_promocount" name="ghc_stream_promocount" value="<?php #echo $stream_promocount ?>" disabled> -->
        


        <script type="text/javascript">
            window.addEventListener('load', function(){
                new Picker(document.querySelector('#ghc_stream_start'), {
                    controls: true,
                    format: 'YYYY-MM-DD HH:mm',
                    headers: true,
                    container:'#timepicker_container',
                });
            });  
        </script>
        <?php 
    }
    public static function call_promo_metabox(){
        global $post;
        \wp_nonce_field( 'promo_metabox_nonce', 'promo_metabox_nonce' );
        echo self::promo_metabox_stylesheet();
        $promo_code = \get_post_meta( $post->ID, 'ghc_stream_promo',true );
        $promo_quantity = \get_post_meta( $post->ID, 'ghc_stream_promocount',true );
        $promo_quantity_max = \get_post_meta( $post->ID, 'ghc_promo_quantity_max',true );
        $promo_expiration = \get_post_meta( $post->ID, 'ghc_promo_expiration',true );
        $promo_active = \get_post_meta( $post->ID, 'ghc_promo_active', true);
        if($promo_active === "false") $promo_active = false;
        if( $promo_code ) {
        ?>
        <h4>Promo Code (cannot be modified):</h4>
        <input type="text" id="ghc_stream_promo" name="ghc_stream_promo" value="<?php echo $promo_code ?>"<?php echo ' disabled'?>/>
        <h4>Quantity Remaining:</h4>
        <?php
           $amount_remaining = $promo_quantity_max - $promo_quantity;
        ?>
        <input type="text" id="ghc_promo_quantity" name="ghc_promo_quantity" value="<?php echo $amount_remaining . ' of ' . $promo_quantity_max ?>" disabled>
        <br><br>
        <label for="ghc_promo_addcheck">Add More Codes:</label> 
        <input type="checkbox" name="addcheck" id="addcheck" data-target="ghc_promo_addcode" onclick="toggle_hideit(event);" \>
        <input type="text" class="hideit inline_input" id="ghc_promo_addcode" name="ghc_promo_addcode" value="" placeholder="Additional Codes" />
        <br><br>

        <h4>Expiration Date</h4>
        <input type="text" id="ghc_promo_expiration" onfocus="popCalendar();" name="ghc_promo_expiration" value="<?php echo $promo_expiration ?>">
        <br>
        <div id="calendar_wrap"></div>
        <br>
        <label for="ghc_promo_active">Promo Code Active:</label>
        <input type="checkbox" name="ghc_promo_active" <?php echo ($promo_active) ? 'checked' : ''?>>
        
        <?php 
        echo self::promo_metabox_edit();
        } else {
            ?>
            <h4>Promo Code:</h4>
            <input type="text" id="ghc_stream_promo" name="ghc_stream_promo" value="">
            <h4>Total Quantity:</h4>
            <input type="text" id="ghc_promo_quantity_max" name="ghc_promo_quantity_max" value="">
            <h4>Expiration Date</h4>
            <input type="text" id="ghc_promo_expiration" onfocus="popCalendar();" name="ghc_promo_expiration" value="<?php echo $promo_expiration ?>">
            <br>
            <div id="calendar_wrap"></div>
            <br>
            <label for="ghc_promo_active">Promo Code Active:</label>
            <input type="checkbox" name="ghc_promo_active" checked>
            <?php
            echo self::promo_metabox_edit();
        }
    }
    public static function video_metabox_save($post_id) {
        if ( ! isset( $_POST['vidurl_metabox_nonce'] ) ||
        ! wp_verify_nonce( $_POST['vidurl_metabox_nonce'], 'vidurl_metabox_nonce' ) ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (!current_user_can('edit_post', $post_id)) return;

        foreach( $_POST as $key => $value ) {
            
            if( $key == 'ghc_video_type' || $key == 'ghc_video_path' || $key == 'ghc_video_preview' || $key == 'ghc_author_bio' || $key == 'ghc_video_author' || $key == 'ghc_author_name'){
                \update_post_meta( $post_id, $key, $value );
            }
        }
    }
    public static function login_embed_metabox_save($post_id){
        if ( ! isset( $_POST['loginvid_metabox_nonce'] ) ||
        ! wp_verify_nonce( $_POST['loginvid_metabox_nonce'], 'loginvid_metabox_nonce' ) ) {
            return;
        }
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if(!current_user_can('edit_post', $post_id)) return;

        if(isset( $_POST['ghc_loginvid_embed'] )){
            \update_post_meta( $post_id, 'ghc_login_video', $_POST['ghc_loginvid_embed']);
        }
    }
    public static function stream_metabox_save($post_id) {
        if ( ! isset( $_POST['stream_metabox_nonce'] ) ||
        ! wp_verify_nonce( $_POST['stream_metabox_nonce'], 'stream_metabox_nonce' ) ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (!current_user_can('edit_post', $post_id)) return;
        foreach( $_POST as $key => $value ) {
            if( $key == 'ghc_stream_start' 
            || $key == 'ghc_stream_length' 
            || $key == 'ghc_author_name'
            || $key == 'ghc_author_bio' 
            || $key == 'ghc_stream_embed_code'
            || $key == 'ghc_stream_embed_chat'
            || $key == 'ghc_stream_preview' 
            /*|| $key == 'ghc_stream_promo'*/ ){

                \update_post_meta( $post_id, $key, $value );

               /* if($key == 'ghc_stream_promo'){
                    \update_post_meta( $post_id, 'ghc_stream_promocount', 50);
                } */
            }
        }
    }
    public static function public_page_metabox_save($post_id) {
        if( ! isset( $_POST['public_page_checkstatus']) || 
        ! wp_verify_nonce( $_POST['public_page_metabox_nonce'], 'public_page_metabox_nonce')){
            return;
        }
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if(!current_user_can('edit_post', $post_id) ) return;

        foreach( $_POST as $key => $value ) {    
            if( $key == 'public_page_checkstatus' ){
                if($value !== "true") $value = "false";
                \update_post_meta( $post_id, $key, $value);
            }
        }

    }
    public static function promo_metabox_save($post_id){
        if( ! isset( $_POST['promo_metabox_nonce'] ) ||
        ! wp_verify_nonce( $_POST['promo_metabox_nonce'], 'promo_metabox_nonce') ) {
            return;
        }
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if(!current_user_can('edit_post', $post_id)) return;
        
        $is_active_checkbox = false; #in case active checkbox is unchecked
        foreach( $_POST as $key => $value ) {    
            if( $key == 'ghc_promo_quantity_max' || $key == 'ghc_stream_promo' ){
                \update_post_meta( $post_id, $key, $value);
                if($key == 'ghc_promo_quantity_max'){
                    $current_promocount = \get_post_meta($post_id, 'ghc_stream_promocount', true);
                    if(!$current_promocount) \update_post_meta($post_id, 'ghc_stream_promocount', 0);
                }
            } else if($key == 'ghc_promo_active'){
                \update_post_meta( $post_id, $key, "true");
                $is_active_checkbox = true;
            } else if($key == 'ghc_promo_addcode') {
                $current_max = \get_post_meta($post_id, 'ghc_promo_quantity_max', true);
                $new_max = intval($current_max) + intval($value);
                 \update_post_meta($post_id, 'ghc_promo_quantity_max', $new_max);   
            } else if( $key == 'ghc_promo_expiration' ){
                \update_post_meta($post_id, 'ghc_promo_expiration', $value); 
            }

        }
        if(!$is_active_checkbox){
            \update_post_meta($post_id, 'ghc_promo_active', "false");
        }
    }
    public static function promo_metabox_stylesheet(){
        $markup = '<style>';
        $markup .= '.hideit {display:none;}#calendar_wrap{width:50%;}';
        $markup .= '.inline_input {margin:10px;transition:all .4s ease-in-out;}';
        $markup .= '</style>';
        return $markup;
    }
    /* 
    https://www.cssscript.com/calendar-date-picke/ (for the widget placement & moment to translate)
    moment().format();
    */
    public static function promo_metabox_javascript(){
        $markup = '<script>';
        $markup .= 'function toggle_hideit(e){';
        $markup .= '    var target = document.getElementById(e.target.dataset.target);';
        $markup .= '    target.classList.toggle("hideit");';
        $markup .= '}';
        $markup .= 'function initCalendar(){';
        $markup .= '    var widget = new CalendarPicker("#calendar_wrap", {';
        $markup .= '    min: new Date() ';  
        $markup .= '    });';
        $markup .= '    widget.onValueChange(function(currentValue){';
        $markup .= '        var convertedDate = moment(currentValue).format("YYYY-MM-DD");';
        $markup .= '        var targetInput = document.getElementById("ghc_promo_expiration");';
        $markup .= '        targetInput.value = convertedDate;';
        $markup .= '    });';
        $markup .= '}';
        $markup .= 'initCalendar();';
        $markup .= '</script>';
        return $markup;
    }
    public static function promo_metabox_edit(){
        $markup = '<script>';
        $markup .= 'function toggle_hideit(e){';
        $markup .= '    var target = document.getElementById(e.target.dataset.target);';
        $markup .= '    target.classList.toggle("hideit");';
        $markup .= '}';
        $markup .= 'function popCalendar(){';
        $markup .= '    document.getElementById("calendar_wrap").innerHTML = "";';
        $markup .= '    document.getElementById("calendar_wrap").classList.remove("hideit");';
        $markup .= '    var widget = new CalendarPicker("#calendar_wrap", {';
        $markup .= '    min: new Date() ';  
        $markup .= '    });';
        $markup .= '    widget.onValueChange(function(currentValue){';
        $markup .= '        var convertedDate = moment(currentValue).format("YYYY-MM-DD");';
        $markup .= '        var targetInput = document.getElementById("ghc_promo_expiration");';
        $markup .= '        targetInput.value = convertedDate;';
        $markup .= '        document.getElementById("calendar_wrap").classList.add("hideit");';
        $markup .= '    });';
        $markup .= '}';
        $markup .= '</script>';
        return $markup;
    }
    public static function open_cpt_comments( $open, $post_id ) {     
        $post = get_post( $post_id );  
        if ( 'streams' == $post->post_type ||  'videos' == $post->post_type )
            $open = true; 
        return $open;
    }

    public static function login_enqueue(){
        
        \wp_enqueue_style( 'bootstrap-grid-css', self::get_plugin_url( 'library/dist/css/bootstrap-grid.min.css'), array(), '4.0.0', 'all' );
        \wp_enqueue_style( 'bootstrap-css', self::get_plugin_url( 'library/dist/css/bootstrap.min.css'), array('bootstrap-grid-css'), '4.0.0', 'all' );
        \wp_enqueue_style( 'connects-login-css', self::get_plugin_url( 'library/dist/css/login.min.css'), array(), self::version, 'all' );
        \wp_enqueue_script( 'regenerator-runtime', self::get_plugin_url( 'library/dist/js/runtime.js'), array(), '1.1', false);
        \wp_enqueue_script( 'connects-login-js', self::get_plugin_url('/library/dist/js/login.min.js'), array('jquery'), VERSION, false );  
    }
    public static function connects_admin_enqueue(){
        \wp_enqueue_script( 'picker-js', self::get_plugin_url( 'library/dist/js/picker.min.js'), array(), '1.2.1', false );
        \wp_enqueue_style( 'picker-css', self::get_plugin_url( 'library/dist/css/picker.min.css'), array(), '1.2.1', 'all');
        \wp_enqueue_script( 'calendarpicker-js', self::get_plugin_url( 'library/dist/js/CalendarPicker.js'), array(), '1.1', false );
        \wp_enqueue_style( 'calendarpicker-css', self::get_plugin_url( 'library/dist/css/CalendarPicker.style.css'), array(), '1.1', 'all');
        \wp_enqueue_script( 'moment-js', self::get_plugin_url( 'library/dist/js/moment.min.js'), array(), '1.1', false );    
    }

    public static function connects_enqueue(){
        \wp_enqueue_script("jquery");
        \wp_enqueue_media();
        \wp_enqueue_script( 'jquery-ui-core');
        \wp_enqueue_script( 'jquery-ui');

        \wp_enqueue_script('runtime', self::get_plugin_url( 'library/dist/js/runtime.js'), array(), '1.1', false);
        \wp_enqueue_script('wpmedia', self::get_plugin_url( 'library/dist/js/wpmedia.js'), array('jquery', 'media-views', 'media-editor','media-audiovideo' ), '1.1', false);
        \wp_enqueue_style( 'connects-css', self::get_plugin_url( 'library/dist/css/app.min.css'), array(), self::version, 'all' );
        \wp_enqueue_script( 'connects-js', self::get_plugin_url( 'library/dist/js/app.min.js'), array('jquery', 'runtime'), self::version, false );
        \wp_enqueue_script( 'vimeo-sdk', 'https://player.vimeo.com/api/player.js', array(), self::version, true);
        \wp_enqueue_script( 'youtube-api', 'https://www.youtube.com/iframe_api', array(), self::version, true);
        \wp_enqueue_script( 'connects-footer-js', self::get_plugin_url( 'library/dist/js/footer.min.js'), array('connects-js', 'vimeo-sdk'), self::version, true );
    }
    public static function gh_connects_image_sizes(){
        \add_image_size( 'neon_avatar_tiny', 32, 32, ['center','top']);
        \add_image_size( 'neon_avatar_small', 90, 90, ['center','top']);
        \add_image_size( 'neon_avatar_large', 233, 233, ['center','top']);

        \add_image_size( 'large_thumb_square', 600, 600, array( 'center', 'top' ) );
    }
    //Sets WP Login Page within core functions
    public static function set_wp_login_page( $login_url, $redirect, $force_reauth ) {
        return home_url( '/login/?action=login' );
    }
    //Sets WP Register Page within core functions
    public static function set_wp_register_page( $login_url ) {
        return home_url( '/register/' );
    }
    //Sets WP Lost Password Page within core functions
    public static function set_wp_lost_password_page( $lostpassword_url, $redirect ) {
        return home_url( '/login/?action=lostpassword' );
    }
    //Creates a blank WP Login Page 
    public static function add_custom_login(){
        $login_title = 'Log Into Greenheart Connects';
        $login_content = '';
        $page_check = \get_page_by_path('login', OBJECT, 'page');
        $login_page = array(
                'post_type' => 'page',
                'post_title' => $login_title,
                'post_content' => $login_content,
                'post_status' => 'publish',
                'post_name' => 'login'
        );
        if( !isset($page_check->ID) ){
           $new_page_id = wp_insert_post($login_page);
        }
    }
        //Creates a blank WP Login Page 
        public static function add_custom_registration(){
            $login_title = 'Join Us';
            $login_content = '';
            $page_check = \get_page_by_title($login_title);
            $login_page = array(
                    'post_type' => 'page',
                    'post_title' => $login_title,
                    'post_content' => $login_content,
                    'post_status' => 'publish',
                    'post_name' => 'register'
            );
            if( !isset($page_check->ID) ){
                $new_page_id = wp_insert_post($login_page);
            }
        }
    //Creates a blank home page for application
    public static function add_dashboard(){
        $main_title = 'Greenheart Connects';
        $main_content = '';
        $page_check = \get_page_by_title($main_title);
        $main_page = array(
                'post_type' => 'page',
                'post_title' => $main_title,
                'post_content' => $main_content,
                'post_status' => 'publish',
                'post_name' => 'greenheart-connects'
        );
        if( !isset($page_check->ID) ){
            $new_page_id = wp_insert_post($main_page);
        }
    }
    public static function add_homesplash(){
        $main_title = 'Coming Soon';
        $main_content = '';
        $page_check = \get_page_by_title($main_title);
        $main_page = array(
                'post_type' => 'page',
                'post_title' => $main_title,
                'post_content' => $main_content,
                'post_status' => 'publish',
                'post_name' => 'test-home-page'
        );
        if( !isset($page_check->ID) ){
            $new_page_id = wp_insert_post($main_page);
        }
    }
    //Creates a blank splash page for application
    public static function add_profile(){
        $main_title = 'Settings';
        $main_content = '';
        $page_check = \get_page_by_title($main_title);
        $main_page = array(
                'post_type' => 'page',
                'post_title' => $main_title,
                'post_content' => $main_content,
                'post_status' => 'publish',
                'post_name' => 'profile'
        );
        if( !isset($page_check->ID) ){
            $new_page_id = wp_insert_post($main_page);
        }
    }
    //Creates a blank user profile page for application
    //Assigns template to our blank login page
    public static function set_login_template($page_template){
        if ( \is_page( 'login' ) ) {
            $page_template = self::get_plugin_path( 'theme/views/login.php' );
        }
    return $page_template;
    }
    //Assigns template to our blank registration page
    public static function set_registration_template($page_template){
        if ( \is_page( 'register' ) ) {
            $page_template = self::get_plugin_path( 'theme/views/registration.php' );
        }
    return $page_template;
    }
    //Assigns master template to our home page
    public static function set_main_template($page_template){
        if ( \is_page( 'greenheart-connects' ) ) {
           # $page_template = self::get_plugin_path( 'theme/views/dashboard.php' ); //assigns master theme to our application
            $page_template = self::get_plugin_path( 'theme/views/homesplash.php' ); //assigns master theme to our application
        }
    return $page_template;
    }
    public static function set_profile_template($page_template){
        if ( \is_page( 'profile' ) ) {
            $page_template = self::get_plugin_path( 'theme/views/profile.php' ); //assigns master theme to our application
        }
    return $page_template;
    }

    public static function set_homesplash_template($page_template){
        if ( \is_page( 'dashboard' ) ) {
           # $page_template = self::get_plugin_path( 'theme/views/homesplash.php' ); //assigns master theme to our application
            $page_template = self::get_plugin_path( 'theme/views/dashboard.php' ); //assigns master theme to our application
        }
    return $page_template;
    }

    //Assign Single & Archive templates for post types
    public static function ghc_postype_assign_archive_templates( $archive ){
        global $post;
        /* Checks for archive template by post type */
        if ( \is_post_type_archive ( 'streams' ) ) {
            if ( file_exists( self::get_plugin_path('theme/views/archive-streams.php' ) ) ) {
                return self::get_plugin_path('theme/views/archive-streams.php' );
            }
        }
        if ( \is_post_type_archive ( 'videos' ) ) {
            if ( file_exists( self::get_plugin_path('theme/views/archive-videos.php' ) ) ) {
                return self::get_plugin_path('theme/views/archive-videos.php' );
            }
        }
        return $archive;
    }
    
    public static function ghc_postype_assign_templates($single) {
        global $post;
        /* Checks for single template by post type */
        if ( $post->post_type == 'streams' ) {
            if ( file_exists( self::get_plugin_path('theme/views/single-stream.php' ) ) ) {
                return self::get_plugin_path('theme/views/single-stream.php' );
            }
        }
        if ( $post->post_type == 'videos' ) {
            if ( file_exists( self::get_plugin_path('theme/views/single-video.php' ) ) ) {
                return self::get_plugin_path('theme/views/single-video.php' );
            }
        }
        if( $post->post_type == 'promo' ) {
            if(file_exists( self::get_plugin_path('theme/views/single-promo.php' ) ) ) {
                return self::get_plugin_path('theme/views/single-promo.php' );
            }
        }
        return $single;
    }

    //Add Video Post Type 
    public static function add_video_cpt(){

        $labels = array(
            'name'               => _x( 'Videos', 'post type general name', self::text_domain ),
            'singular_name'      => _x( 'Video', 'post type singular name', self::text_domain ),
            'menu_name'          => _x( 'Videos', 'admin menu', self::text_domain ),
            'name_admin_bar'     => _x( 'Videos', 'add new on admin bar', self::text_domain ),
            'add_new'            => _x( 'New', 'Video', self::text_domain ),
            'add_new_item'       => __( 'New Video', self::text_domain ),
            'new_item'           => __( 'New Video', self::text_domain ),
            'edit_item'          => __( 'Edit Video', self::text_domain ),
            'view_item'          => __( 'View Video', self::text_domain ),
            'all_items'          => __( 'All Videos', self::text_domain ),
            'search_items'       => __( 'Search Videos', self::text_domain ),
            'not_found'          => __( 'No Videos found.', self::text_domain ),
            'not_found_in_trash' => __( 'No Videos found in Trash.', self::text_domain )
        );
        
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', self::text_domain ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'videos' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'show_in_menu'       => true,
            'menu_position'      => 5,
            'show_in_rest'       => false,
            'supports'           => array( 'title', 'custom-fields','post-formats','thumbnail','excerpt', 'comments' )
        );
     
        \register_post_type( 'videos', $args );
    }
    //Add Stream Post Type 
    public static function add_stream_cpt(){

        $labels = array(
            'name'               => _x( 'Livestreams', 'post type general name', self::text_domain ),
            'singular_name'      => _x( 'Livestream', 'post type singular name', self::text_domain ),
            'menu_name'          => _x( 'Livestreams', 'admin menu', self::text_domain ),
            'name_admin_bar'     => _x( 'Livestreams', 'add new on admin bar', self::text_domain ),
            'add_new'            => _x( 'New', 'Livestream', self::text_domain ),
            'add_new_item'       => __( 'New Livestream', self::text_domain ),
            'new_item'           => __( 'New Livestream', self::text_domain ),
            'edit_item'          => __( 'Edit Livestream', self::text_domain ),
            'view_item'          => __( 'View Livestream', self::text_domain ),
            'all_items'          => __( 'All Livestreams', self::text_domain ),
            'search_items'       => __( 'Search Videos', self::text_domain ),
            'not_found'          => __( 'No Livestreams found.', self::text_domain ),
            'not_found_in_trash' => __( 'No Livestreams found in Trash.', self::text_domain )
        );
        
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', self::text_domain ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'streams' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'show_in_menu'       => true,
            'menu_position'      => 5,
            'show_in_rest'       => false,
            'supports'           => array( 'title', 'editor', 'custom-fields', 'excerpt', 'thumbnail', 'comments'  )
        );
     
        \register_post_type( 'streams', $args );
    }
    //Add Promo Post Type 
    public static function add_promo_cpt(){

        $labels = array(
            'name'               => _x( 'Promo Code', 'post type general name', self::text_domain ),
            'singular_name'      => _x( 'Promo Code', 'post type singular name', self::text_domain ),
            'menu_name'          => _x( 'Promo Codes', 'admin menu', self::text_domain ),
            'name_admin_bar'     => _x( 'Promo Codes', 'add new on admin bar', self::text_domain ),
            'add_new'            => _x( 'New', 'Promo Code', self::text_domain ),
            'add_new_item'       => __( 'New Promo Code', self::text_domain ),
            'new_item'           => __( 'New Promo Code', self::text_domain ),
            'edit_item'          => __( 'Edit Promo Code', self::text_domain ),
            'view_item'          => __( 'View Promo Codes', self::text_domain ),
            'all_items'          => __( 'All Promo Codes', self::text_domain ),
            'search_items'       => __( 'Search Promo Codes', self::text_domain ),
            'not_found'          => __( 'No Promo Codes found.', self::text_domain ),
            'not_found_in_trash' => __( 'No Promo Codes found in Trash.', self::text_domain )
        );
        
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', self::text_domain ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'promo' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'show_in_menu'       => true,
            'menu_position'      => 5,
            'show_in_rest'       => false,
            'supports'           => array( 'title', 'editor', 'custom-fields'  )
        );
        
        \register_post_type( 'promo', $args );
    }

}