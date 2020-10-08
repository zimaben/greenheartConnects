<?php
namespace gh_connects\admin;


//spin it up
\gh_connects\admin\Options::get_instance();

class Options extends \GreenheartConnects {

    private static $instance = null;

    public static function get_instance() 
    {

        if ( 
            null == self::$instance 
        ) {

            self::$instance = new self;

        }

        return self::$instance;

    }
    
    private function __construct() 
    {           
        \add_action('admin_menu', array(get_class(), 'add_assessment_menu') );  //add assessment page
        \add_action( 'admin_init', array(get_class(),'ghc_settings_init') );
        

    }
    public static function add_assessment_menu(){
        //create top-level menu
        \add_menu_page('Plugin Utilities and Options', 'Connects Settings', 'administrator', 'options', array(get_class(),'ghc_settings_page_cb') );
    }

    public static function ghc_settings_init() {
        //toggle on/off
        \register_setting( 'ghc-settings', 'do_ga');
        // register results page settings for "neon_assessment" page
  
        \register_setting( 'ghc-settings', 'google_analytics_code' );
        \register_setting( 'ghc-settings', 'ghc_payment_form_id' );
        \register_setting( 'ghc-settings', 'ghc_manage_form_id');
        \register_setting( 'ghc-settings', 'ghc_login_bullet_point');
        \register_setting( 'ghc-settings', 'do_realtime_comments');
        \add_settings_field( 'do_ga', 'On / Off', array(get_class(), 'ghc_do_radio'), 'options', 'ghc-analytics-on' );

    }
    public static function ghc_do_radio(){
        ?>
        <h3>Turn on Google Analytics? </h3>
        <div>off / on</div>
        <div>
            <input name="do_ga" type="radio" value="0" <?php checked( '0', get_option( 'do_ga' ) ); ?> />
            <input name="do_ga" type="radio" value="1" <?php checked( '1', get_option( 'do_ga' ) ); ?> />
        </div>
        <br>
        <?php
    }   
    public static function ghc_settings_page_cb(){
                ?>

                
                <form method="post" action="options.php">
                    <?php settings_fields( 'ghc-settings' ); ?>
                    <?php do_settings_sections( 'ghc-settings' ); ?>
                    <div class="wrap">
                    <h1>Greenheart Connects Settings:</h1>

                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">Payment Form ID:</th></tr>
                        <tr><td><label for="ghc_payment_form_id">The Gravity Forms Form ID that controls Payments:</label>
                        <input name="ghc_payment_form_id" id="ghc_payment_form_id" value="<?php echo esc_attr( get_option('ghc_payment_form_id') ); ?>">
                        </tr>
                        <tr valign="top">
                        <th scope="row">Management Form ID:</th></tr>
                        <tr><td><label for="ghc_manage_form_id">The Gravity Forms Form ID where User can cancel membership:</label>
                        <input name="ghc_manage_form_id" id="ghc_manage_form_id" value="<?php echo esc_attr( get_option('ghc_manage_form_id') ); ?>">
                        </tr>
                    </table>
                    <h3>Turn on Google Analytics? </h3>
                    <div>off / on</div>
                    <div>
                        <input name="do_ga" type="radio" value="0" <?php checked( '0', get_option( 'do_ga' ) ); ?> />
                        <input name="do_ga" type="radio" value="1" <?php checked( '1', get_option( 'do_ga' ) ); ?> />
                    </div>
                    <br>

                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">Google Analytics</th></tr>
                        <tr><td><label for="google_analytics_code">Paste Code Here:</label>
                        <textarea type="textarea" class="widefat" cols="40" rows="4" name="google_analytics_code"><?php echo esc_attr( get_option('google_analytics_code') ); ?></textarea></td>
                        </tr>
                    </table>

                    <h3>Real Time Comments?</h3>
                    <div>Real Time Commenting is an experimental feature that might affect bandwidth usage and site performance.</div>
                    <div>off / on</div>
                    <div>
                        <input name="do_realtime_comments" type="radio" value="0" <?php checked( '0', get_option( 'do_realtime_comments' ) ); ?> />
                        <input name="do_realtime_comments" type="radio" value="1" <?php checked( '1', get_option( 'do_realtime_comments' ) ); ?> />
                    </div>
                    <br>

                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row">Login Page Bullet Point:</th></tr>
                        <tr><td><label for="ghc_login_bullet_point">Add the next stream title:</label>
                        <textarea type="textarea" class="widefat" cols="40" rows="2" name="ghc_login_bullet_point"><?php echo esc_attr( get_option('ghc_login_bullet_point') ); ?></textarea></td>
                        </tr>
                    </table>
                    
                    <?php submit_button(); ?>
                
                </form>
            </div>
            <?php 
    }
}      