<?php
namespace gh_connects\theme;

//spin it
\gh_connects\theme\Setup::run();



class Setup extends \GreenheartConnects {
    public static function run(){
        //add login & app main page
        \add_action( 'init', array( get_class(), 'add_custom_login' ), 1 );
        \add_action( 'init', array( get_class(), 'add_dashboard' ), 1 );

        //add custom styling and bootstrap to login page
        \add_action( 'login_enqueue_scripts', array( get_class(), 'login_enqueue') ); //add login page styles
        \add_action( 'wp_enqueue_scripts', array( get_class(), 'connects_enqueue') );

        //assign templates to added single pages
        \add_filter( 'page_template', array( get_class(), 'set_login_template' )  );
        \add_filter( 'page_template', array( get_class(), 'set_main_template' )  );

        //Add video post type
        \add_action( 'init', array( get_class(), 'add_video_cpt' )  ); 
        //assign template to video pages
        \add_action('template_redirect', array( get_class(), 'set_video_template') );

        \add_filter( 'login_url', array(get_class(), 'set_wp_login_page' ), 10, 3 );      //update login url for core functionality to work with
        \add_filter( 'register_url', array(get_class(), 'set_wp_register_page' ), 10, 1 );//update register url for core functionality
        \add_filter( 'lostpassword_url', array(get_class(),'set_wp_lost_password_page'), 10, 2 );//update lost password url for core functionality

        
    }
    public static function login_enqueue(){
        \wp_enqueue_style( 'bootstrap-grid-css', self::get_plugin_url( 'library/dist/css/bootstrap-grid.min.css'), array(), '4.0.0', 'all' );
        \wp_enqueue_style( 'bootstrap-css', self::get_plugin_url( 'library/dist/css/bootstrap.min.css'), array('bootstrap-grid-css'), '4.0.0', 'all' );
        \wp_enqueue_style( 'connects-login-css', self::get_plugin_url( 'library/dist/css/login.min.css'), array(), self::version, 'all' );
        wp_enqueue_script( 'connects-login-js', self::get_plugin_url('/library/dist/js/login.min.js'), array(), VERSION, false );  
    }

    public static function connects_enqueue(){
       \wp_enqueue_style( 'connects-css', self::get_plugin_url( 'library/dist/css/app.min.css'), array(), self::version, 'all' );
       \wp_enqueue_script( 'connects-js', self::get_plugin_url( 'library/dist/js/app.min.js'), array(), self::version, false );
       \wp_enqueue_script( 'connects-footer-js', self::get_plugin_url( 'library/dist/js/footer.min.js'), array('connects-js'), self::version, true );
    }
    //Sets WP Login Page within core functions
    public static function set_wp_login_page( $login_url, $redirect, $force_reauth ) {
        return home_url( '/login/' );
    }
    //Sets WP Register Page within core functions
    public static function set_wp_register_page( $login_url ) {
        return home_url( '/login/?action=register' );
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
    //Assigns template to our blank login page
    public static function set_login_template($page_template){
        if ( \is_page( 'login' ) ) {
            $page_template = self::get_plugin_path( 'theme/views/login.php' );
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
    //Assigns template to our video pages
    public static function set_video_template($page_template){
        if( \get_post_type() == 'video') {
            $page_template = self::get_plugin_path( 'theme/views/video.php' );
        }
    return $page_template;
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
            'supports'           => array( 'title', 'custom-fields' )
        );
     
        \register_post_type( 'videos', $args );
    }

}