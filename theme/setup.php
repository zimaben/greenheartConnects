<?php
namespace gh_connects\theme;
use gh_connects\theme\Modules as Modules;
//spin it
\gh_connects\theme\Setup::run();



class Setup extends \GreenheartConnects {
    public static function run(){
        //add login & app main page
        \add_action( 'init', array( get_class(), 'add_custom_login' ), 1 );
        \add_action ( 'init', array( get_class(), 'add_custom_registration'), 1 );
        \add_action( 'init', array( get_class(), 'add_dashboard' ), 1 );
        \add_action( 'init', array( get_class(), 'add_profile' ), 1 );
        \add_action( 'wp_loaded', array( get_class(), 'gate_generic_page_templates'));

        //add custom styling and bootstrap to login page
        \add_action( 'login_enqueue_scripts', array( get_class(), 'login_enqueue') ); //add login page styles
        \add_action( 'wp_enqueue_scripts', array( get_class(), 'connects_enqueue') );
        \add_action( 'admin_enqueue_scripts', array( get_class(), 'connects_admin_enqueue') );

        //assign templates to added plugin pages
        \add_filter( 'page_template', array( get_class(), 'set_login_template' )  );
        
        \add_filter( 'page_template', array( get_class(), 'set_registration_template' )  );
        \add_filter( 'page_template', array( get_class(), 'set_main_template' )  );
        \add_filter( 'page_template', array( get_class(), 'set_profile_template' )  );
        /* Filter the single_template with our custom function*/
        \add_filter('single_template', array( get_class(),'ghc_postype_assign_templates') );
        \add_filter( 'archive_template', array( get_class(),'ghc_postype_assign_archive_templates') );

        //Add video post type
        \add_action( 'init', array( get_class(), 'add_video_cpt' )  ); 
        //Add meta boxes for editing
        \add_action( 'add_meta_boxes', array( get_class(),'add_video_metabox' ) );
        //Add meta save function 
        \add_action('save_post', array( get_class(), 'video_metabox_save') );
        
        
        
        //Add livestream post type
        \add_action( 'init', array( get_class(), 'add_stream_cpt' )  ); 
        //Add meta boxes for editing
        \add_action( 'add_meta_boxes', array( get_class(),'add_stream_metabox' ) );
        //Add meta save function 
        \add_action('save_post', array( get_class(), 'stream_metabox_save') );

        \add_filter( 'login_url', array(get_class(), 'set_wp_login_page' ), 10, 3 );      //update login url for core functionality to work with
        \add_filter( 'register_url', array(get_class(), 'set_wp_register_page' ), 10, 1 );//update register url for core functionality
        \add_filter( 'lostpassword_url', array(get_class(),'set_wp_lost_password_page'), 10, 2 );//update lost password url for core functionality
        #\add_filter( 'registration_redirect', array(get_class(), 'after_registration_home') );
        #add_filter( 'login_redirect', array(get_class(), 'after_registration_home') );
        
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
        

        //set Javascript variables
        \add_action( 'wp_head', array(get_class(), 'set_plugin_js_variables'));
        \add_action( 'login_head', array(get_class(), 'set_plugin_js_variables') );

        \add_filter( 'comments_open', array(get_class(), 'open_cpt_comments'), 10, 2 );
        
        //image sizes
        \add_action( 'after_setup_theme', array(get_class(), 'neon_avatar_image_sizes'));

        /* Disable the Admin Bar. */
        \add_filter( 'show_admin_bar', array(get_class(), 'kill_topbar'), 100, 1);
        \remove_action( 'wp_head', '_admin_bar_bump_cb' );
        
    }

    public static function gate_generic_page_templates(){
        error_log('gate function firing');
        if ( basename(\get_page_template()) === 'page.php' ) {
            error_log('page template detected');
            \add_action('close_header', function(){
                $userState = Modules::top_avatar();
                if(!$userState){
                    
                    require_once self::get_plugin_path('theme/views/components/please_login.php');
                    Modules::footer(); 
                    Modules::close_page();
                    exit;
                }
            });
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
        return \home_url();
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
    public static function call_video_url_metabox(){
        global $post;
        \wp_nonce_field( 'vidurl_metabox_nonce', 'vidurl_metabox_nonce' );
        $vidurl_meta = get_post_meta( $post->ID, 'ghc_video_path',true );
        $vidtype_meta = get_post_meta( $post->ID, 'ghc_video_type',true );

        if( $vidurl_meta && $vidtype_meta ) {
        ?>
        <h4>File name or embed URL</h4>
        <input type="text" id="ghc_video_path" name="ghc_video_path" value="<?php echo $vidurl_meta ?>">
        <h4>Type:</h4>
        <input type="radio" id="vidtypefile" name="ghc_video_type" value="file"<?php echo ($vidtype_meta == "file") ? 'checked' : ''?>>
        <label for="vidtypefile">File</label><br>
        <input type="radio" id="vidtypeembed" name="ghc_video_type" value="embed"<?php echo ($vidtype_meta == "embed") ? 'checked' : ''?>>
        <label for="vidtypefile">Embed</label><br>
        <?php
        } else {
            ?>
            <h4>File name or embed URL</h4>
            <input type="text" id="vidurl" name="ghc_video_path" value="">
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
        $streamid_meta = get_post_meta( $post->ID, 'ghc_zoom_meeting_id', true );
        ?>
        <h4>Live Stream Details</h4>
        <div id="timepicker_container" style="position:relative;max-width:60%;margin:0 auto;"></div>
        <input type="text" class="form-control js-full-picker" id="ghc_stream_start" name="ghc_stream_start" value="<?php echo ($streamstart_meta) ? $streamstart_meta : '' ?>">
        <label for="ghc_stream_start">Start Date/Time (CST):</label><br>
        <input type="text" pattern="[0-9]{1,3}"id="ghc_stream_length" name="ghc_stream_length" value="<?php echo ($streamlength_meta) ? $streamlength_meta : ''?>">
        <label for="vidtypefile">Minutes:</label><br>
        <input type="text" id="ghc_author_name" name="ghc_author_name" value="<?php echo ($streamspeaker_meta) ? $streamspeaker_meta : ''?>">
        <label for="ghc_author_name">Author Name:</label><br>
        <input type="text" id="ghc_zoom_meeting_id" name="ghc_zoom_meeting_id" value="<?php echo ($streamid_meta) ? $streamid_meta : ''?>">
        <label for="ghc_zoom_meeting_id">Meeting ID (on the Zoom Admin area as zoom_api_link meeting_id=?)</label>
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
    public static function video_metabox_save($post_id) {
        if ( ! isset( $_POST['vidurl_metabox_nonce'] ) ||
        ! wp_verify_nonce( $_POST['vidurl_metabox_nonce'], 'vidurl_metabox_nonce' ) ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (!current_user_can('edit_post', $post_id)) return;

        foreach( $_POST as $key => $value ) {
            
            if( $key == 'ghc_video_type' || $key == 'ghc_video_path'){
                \update_post_meta( $post_id, $key, $value );
            }
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
            if( $key == 'ghc_stream_start' || $key == 'ghc_stream_length' || $key == 'ghc_author_name' || $key == 'ghc_zoom_meeting_id' ){
                \update_post_meta( $post_id, $key, $value );
            }
        }
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
        \wp_enqueue_script( 'connects-footer-js', self::get_plugin_url( 'library/dist/js/footer.min.js'), array('connects-js'), self::version, true );
    }
    public static function neon_avatar_image_sizes(){
        \add_image_size( 'neon_avatar_tiny', 32, 32, ['center','top']);
        \add_image_size( 'neon_avatar_small', 90, 90, ['center','top']);
        \add_image_size( 'neon_avatar_large', 233, 233, ['center','top']);
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
        $page_check = \get_page_by_title($login_title);
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
    //Creates a blank home page for application
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
            $page_template = self::get_plugin_path( 'theme/views/dashboard.php' ); //assigns master theme to our application
        }
    return $page_template;
    }
    public static function set_profile_template($page_template){
        if ( \is_page( 'profile' ) ) {
            $page_template = self::get_plugin_path( 'theme/views/profile.php' ); //assigns master theme to our application
        }
    return $page_template;
    }

    //Assign Single & Archive templates for post types
    public static function ghc_postype_assign_archive_templates( $archive ){
        error_log('function is firing');
        global $post;
        /* Checks for archive template by post type */
        if ( \is_post_type_archive ( 'streams' ) ) {
            error_log( self::get_plugin_path('theme/views/archive-streams.php' ) );
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

}