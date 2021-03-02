<?php
namespace obp_emailer\send;
use \neon_content\components\ShareLinks as ShareLinks;

//spin it up
EmailSend::get_instance();

class EmailSend extends \obp_emailer\Emailer {
    private static $instance = null;

    public static function get_instance(){
        if (self::$instance == null){
            self::$instance = new self;
        }
        return self::$instance;
    }
    /* CONTRUCTOR */
    public function __construct() {
        //ADD CUSTOM ACTION FOR UPDATED AVATAR
        \add_action('do_updated_avatar', array(get_class(), 'emailer_update_avatar', ));
        \add_filter( 'wp_mail_content_type', function( $content_type ) { return 'text/html';} );
  
    }

        public static function emailer_update_avatar(){
        #Email is being sent to current user
        $current_user = \wp_get_current_user();
        if($current_user){
            $email = $current_user->user_email;
            $sendto = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_sendto');
            $sendfrom = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_sendfrom');
            $sendfromname = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_sendfromname');
            $subject = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_subject');
            $message = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_message');
            $message .= '<br><p>';

            $this_url = urlencode( \get_site_url() . '/user/?u='.$current_user->ID);

            $sharelinks = new ShareLinks($this_url, 'Get Your Free Color Fingerprint on NEONID.com!', 'Neon ID');
            $message.= $sharelinks->email_html;

            if($email && $sendto && $message){
                error_log('passed Email check');

                if(!$sendfrom) $sendfrom = \get_option('admin_email');
                if(!$sendfromname) $sendfromname = \get_option('blogname');
                if(!$subject) $subject = 'Neon ID Avatar Updated';
                $headers = array('From: '.$sendfromname.' <'.$sendfrom .'>');
                $sent = \wp_mail( $email, $subject, $message, $headers );
            } else {
                error_log('Email check failed');
                error_log('Email: '. $email);
                error_log('Sendto: '. $sendto);
                error_log('Message: '. $message);

            }
        }    
    }

}