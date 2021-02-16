<?php
/* 
 * Greenheart Connects Template
 *
 * @package         greenheart_connects
 * @author          Ben Toth
 * @license         GNU
 * @link            https://ben-toth.com/
 * @copyright       2020 Greenheart Inc
 *
 * @wordpress-plugin
 * Plugin Name:     Greenheart Connects
 * Plugin URI:      https://greenheartconnects.org
 * Description:     Extends the Greenheart theme with Greenheart Connects features, functionality, and feature-specific theming and styling. 
 * Version:         1.2.0
 * Author:          Ben Toth
 * Author URI:      https://ben-toth.com/
 * License:         GNU
 * Copyright:       Greenheart Inc
 * Class:           GreenheartConnects
 * Text Domain:     gh_connects
 * Domain Path:     /languages
 * GitHub Plugin URI: https://github.com/zimaben/greenheartConnects
*/

defined( 'ABSPATH' ) OR exit;

if ( ! class_exists( 'GreenheartConnects' ) ) {

    register_activation_hook( __FILE__, array ( 'GreenheartConnects', 'register_activation_hook' ) );    
    add_action( 'plugins_loaded', array ( 'GreenheartConnects', 'get_instance' ), 5 );
    
    class GreenheartConnects {
 
        private static $instance = null;

        // Plugin Settings Generic
        const version = '1.2.0';
        static $debug = true; //turns PHP and javascript logging on/off
        const text_domain = 'gh_connects'; // for translation ##
        const js_domain = 'gh_connects';
        const finance_email = 'lpuzan@greenheart.org';//UPDATE!

        //Plugin Options

        /**
         * Returns a singleton instance
         */
        public static function get_instance() 
        {

            if ( 
                null == self::$instance 
            ) {

                self::$instance = new self;

            }

            return self::$instance;

        }
        
        private function __construct() {

            // actvation ##
            \register_activation_hook( __FILE__, array ( $this, 'register_activation_hook' ) );

            // deactvation ##
            \register_deactivation_hook( __FILE__, array ( $this, 'register_deactivation_hook' ) );

            // set text domain ##
            \add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );

            #execute deactivation options
            \add_action( 'wp_ajax_deactivate', array( $this, 'deactivate_callback') );

            // load libraries ##
            self::load_libraries();

            // enqueue scripts & styles


        }
        
        private static function load_libraries() {

            // GF Integration ##
            require_once self::get_plugin_path( 'admin/authnet.php') ; //AUTH.NET php SDK 
            require_once self::get_plugin_path( 'admin/admin.php' ); 
            require_once self::get_plugin_path( 'control/gravityforms.php'); //GravityForms Auth.net Integration Class   
            
            
            // setup ##
            require_once self::get_plugin_path( 'theme/setup.php' ); //setup frontend files (wp-load, enqueue, image register)
            require_once self::get_plugin_path( 'theme/template.php'); //Master Template Class to make Templates x
            #require_once self::get_plugin_path( 'theme/master.php'); //The Daily Vibe page (Template Modules Map)
            require_once self::get_plugin_path( 'theme/modules.php' ); //Top Level Modules (Loads components)

            // local storage
            #require_once self::get_plugin_path( 'data/setup.php'); //Database Setup x
            #require_once self::get_plugin_path( 'data/cron.php'); //Database Purge & Maintenance x

            // control classes for render 
            require_once self::get_plugin_path( 'control/core.php'); //Return local data 
            #require_once self::get_plugin_path( 'core/session.php'); //Return Session Data (@todo may be replaced with JSON token) 
            

        }

        /* UTILITY FUNCTIONS */

        public static function register_activation_hook() {

            $option = self::text_domain . '-version';
            \update_option( $option, self::version );     

            \add_role( 'ghc_user', 'Connects User', array( 'read' => true, 'level_1' => true, 'upload_files' => true ) );
            \update_option('default_role','ghc_user');

            #Schedule our Database Cleanup
            #Use wp_next_scheduled to check if the event is already scheduled
            $timestamp = \wp_next_scheduled( 'ghc_daily_subscription_update');
            //If $timestamp == false schedule daily backups since it hasn't been done previously
            if( $timestamp == false ){
                //Schedule the event for right now, then to repeat daily using the hook 'wi_create_daily_backup'
                \wp_schedule_event( time(), 'daily', 'ghc_daily_subscription_update' );
            }
        }


        public function register_deactivation_hook() 
        {
            
            $option = self::text_domain . '-version';
            \delete_option( $option );
            \delete_option('default_role','ghc_user');
        }

        public function load_plugin_textdomain() 
        {
            
            // set text-domain ##
            $domain = self::text_domain;
            
            // The "plugin_locale" filter is also used in load_plugin_textdomain()
            $locale = apply_filters('plugin_locale', get_locale(), $domain);

            // try from global WP location first ##
            load_textdomain( $domain, WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo' );
            
            // try from plugin last ##
            load_plugin_textdomain( $domain, FALSE, plugin_dir_path( __FILE__ ).'library/language/' );
            
        }

        public static function get_plugin_url( $path = '' ) 
        {

            return plugins_url( $path, __FILE__ );

        }
        
        public static function get_plugin_path( $path = '' ) 
        {

            return plugin_dir_path( __FILE__ ).$path;

        }

    }

}