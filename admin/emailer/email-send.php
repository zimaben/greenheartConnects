<?php
namespace obp_emailer\send;


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
       # \add_action('do_updated_avatar', array(get_class(), 'emailer_update_avatar', ));
        \add_filter( 'wp_mail_content_type', function( $content_type ) { return 'text/html';} );

        \add_filter( 'wp_new_user_notification_email', array(get_class(), 'emailer_new_user_notification'), 99, 3 );
  
    }

    public static function emailer_new_user_notification( $email = array(), $user, $blogname ){
        /*
            $email['to']
            $email['subject']
            $email['message']
            $email['headers']

        */
        if($user){
            $email = $user->user_email;
            $sendfrom = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_sendfrom');
            $sendfromname = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_sendfromname');
            $subject = \get_option( str_replace("emailer_", "", __FUNCTION__) . '_subject');
            $message = apply_filters( 'the_content', \get_option( str_replace("emailer_", "", __FUNCTION__) . '_message') );

            if($email && $sendto && $message){
                $email['to'] = $email;
                $email['message'] = $message;
                if($subject) $email['subject'] = $subject;
                if($sendfrom && $sendfromname){
                    $headers = array('From: '.$sendfromname.' <'.$sendfrom .'>');
                    $email['headers'] = $headers;
                }
            }    
        }

        return $email;    
    }
    public static function emailer_new_user_direct( $user ){

        if($user){
            $email = $user->user_email;
            $sendfrom = \get_option( 'new_user_notification_sendfrom');
            $sendfromname = \get_option( 'new_user_notification_sendfromname');
            $subject = \get_option( 'new_user_notification_subject');
            $message = \apply_filters( 'the_content', \get_option('new_user_notification_message') );
            
            if($email && $message){


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