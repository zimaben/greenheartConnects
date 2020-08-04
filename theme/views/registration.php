<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
/* GREENHEART LOGIN UTILITY FUNCTIONS - KEEP THESE IF YOU WANT TO KEEP FUNCTIONALITY */
function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}
function getPluginURL(){
	$path = realpath(__DIR__);
	$start = strpos($path, '/htdocs/') + 7; #keep the trailing slash
	$stop = strpos($path, '/core/' );
	$plugin_dir = substr( $path, $start, $stop - $start );
	$url = $_SERVER['SERVER_NAME'] . $plugin_dir;
	return $url;
}
function getSitePath(){
    $path = realpath(__DIR__);
	$stop = strpos($path, '/wp-content/' );
	$site_path = substr( $path, 0, $stop);
	return $site_path;
}
	/* TEMPLATE VARIABLES - Since we're hardcoding */
	$gh_subtitle = 'Earn income for sharing the things you like';
	$gh_logo_url = '/login/';
    $gh_left_image = 'https://' . getPluginURL() . '/theme/library/images/neon_hero.svg';
    $gh_left_video = '#'; //set embed video here

require getSitePath() . '/wp-load.php';

/**
 * Output the login page header.
 *
 * @since 2.1.0
 *
 * @global string      $error         Login error message set by deprecated pluggable wp_login() function
 *                                    or plugins replacing it.
 * @global bool|string $interim_login Whether interim login modal is being displayed. String 'success'
 *                                    upon successful login.
 * @global string      $action        The action that brought the visitor to the login page.
 *
 * @param string   $title    Optional. WordPress login Page title to display in the `<title>` element.
 *                           Default 'Log In'.
 * @param string   $message  Optional. Message to display in header. Default empty.
 * @param WP_Error $wp_error Optional. The error to pass. Default is a WP_Error instance.
 */
function login_header( $title = 'Log In', $message = '', $wp_error = '' ) {
	global $error, $interim_login, $action;

	// Don't index any of these forms
	add_action( 'login_head', 'wp_no_robots' );

	add_action( 'login_head', 'wp_login_viewport_meta' );

	if ( empty($wp_error) )
		$wp_error = new WP_Error();

	// Shake it!
	$shake_error_codes = array( 'empty_password', 'empty_email', 'invalid_email', 'invalidcombo', 'empty_username', 'invalid_username', 'incorrect_password' );
	/**
	 * Filters the error codes array for shaking the login form.
	 *
	 * @since 3.0.0
	 *
	 * @param array $shake_error_codes Error codes that shake the login form.
	 */
	$shake_error_codes = apply_filters( 'shake_error_codes', $shake_error_codes );

	if ( $shake_error_codes && $wp_error->get_error_code() && in_array( $wp_error->get_error_code(), $shake_error_codes ) )
		add_action( 'login_head', 'wp_shake_js', 12 );

	$login_title = get_bloginfo( 'name', 'display' );

	/* translators: Login screen title. 1: Login screen name, 2: Network or site name */
	$login_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $login_title );

	/**
	 * Filters the title tag content for login page.
	 *
	 * @since 4.9.0
	 *
	 * @param string $login_title The page title, with extra context added.
	 * @param string $title       The original page title.
	 */
	$login_title = apply_filters( 'login_title', $login_title, $title );

	?><!DOCTYPE html>
	<!--[if IE 8]>
		<html xmlns="http://www.w3.org/1999/xhtml" class="ie8" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if !(IE 8) ]><!-->
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php echo $login_title; ?></title>
	<?php

	wp_enqueue_style( 'login' );

	/*
	 * Remove all stored post data on logging out.
	 * This could be added by add_action('login_head'...) like wp_shake_js(),
	 * but maybe better if it's not removable by plugins
	 */
	if ( 'loggedout' == $wp_error->get_error_code() ) {
		?>
		<script>if("sessionStorage" in window){try{for(var key in sessionStorage){if(key.indexOf("wp-autosave-")!=-1){sessionStorage.removeItem(key)}}}catch(e){}};</script>
		<?php
	}

	/**
	 * Enqueue scripts and styles for the login page.
	 *
	 * @since 3.1.0
	 */
	do_action( 'login_enqueue_scripts' );

	/**
	 * Fires in the login page header after scripts are enqueued.
	 *
	 * @since 2.1.0
	 */
	do_action( 'login_head' );

	/**
	 * Filters link URL of the header logo above login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $login_header_url Login header logo URL.
	 */
	$login_header_url = apply_filters( 'login_headerurl', $login_header_url );

	/**
	 * Filters the title attribute of the header logo above login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $login_header_title Login header logo title attribute.
	 */
	$login_header_title = apply_filters( 'login_headertitle', $login_header_title );


	/**
	 * Filters the login page body classes.
	 *
	 * @since 3.5.0
	 *
	 * @param array  $classes An array of body classes.
	 * @param string $action  The action that brought the visitor to the login page.
	 */
	$classes = apply_filters( 'login_body_class', $classes, $action );

	?>
	</head>
	<body class="login">
	<?php
	/**
	 * Fires in the login page header after the body tag is opened.
	 *
	 * @since 4.6.0
	 */
	do_action( 'login_header' );
	?>
	<div class="container-fluid">
		<div class="row connects-background">
			<div class="col-6 d-none d-md-block">
				<div class="the-content">
					 <h2 class="left-title">Join our online community etc</h2>
					 <ul>
						<li>Connect with your Greenheart family</li>
						<li>Log and track your volunteer hours</li>
						<li>Apply for scholarships to leadership conferences and grants for service projects</li>
						<li>Access resources for professional development and trainings</li>
						<li>Ask questions and start discussions in our public forums</li>
					</ul>
				</div>
			</div>
			<div class="col-12 col-md-6 logincol">
				<h1>Welcome to Greenheart Connects</h1>
	<?php

	unset( $login_header_url, $login_header_title );

	/**
	 * Filters the message to display above the login form.
	 *
	 * @since 2.1.0
	 *
	 * @param string $message Login message text.
	 */
	$message = apply_filters( 'login_message', $message );
	if ( !empty( $message ) )
		echo $message . "\n";

	// In case a plugin uses $error rather than the $wp_errors object
	if ( !empty( $error ) ) {
		$wp_error->add('error', $error);
		unset($error);
	}

	if ( $wp_error->get_error_code() ) {
		$errors = '';
		$messages = '';
		foreach ( $wp_error->get_error_codes() as $code ) {
			$severity = $wp_error->get_error_data( $code );
			foreach ( $wp_error->get_error_messages( $code ) as $error_message ) {
				if ( 'message' == $severity )
					$messages .= '	' . $error_message . "<br />\n";
				else
					$errors .= '	' . $error_message . "<br />\n";
			}
		}
		if ( ! empty( $errors ) ) {
			/**
			 * Filters the error messages displayed above the login form.
			 *
			 * @since 2.1.0
			 *
			 * @param string $errors Login error message.
			 */
			echo '<div id="login_error">' . apply_filters( 'login_errors', $errors ) . "</div>\n";
		}
		if ( ! empty( $messages ) ) {
			/**
			 * Filters instructional messages displayed above the login form.
			 *
			 * @since 2.5.0
			 *
			 * @param string $messages Login messages.
			 */
			echo '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
		}
	}
} // End of login_header()

function login_middle(){
	if ( isset($_POST['passwordconfirm'] ) ) {
	    registration_validation(
	    $_POST['username'],
	    $_POST['password'],
	    $_POST['email']
	    );
	     
	    // sanitize user form input
	    global $username, $password, $email;
	    $username   =   sanitize_user( $_POST['username'] );
	    $password   =   esc_attr( $_POST['password'] );
	    $email      =   sanitize_email( $_POST['email'] );

	    // call @function complete_registration to create the user
	    // only when no WP_error is found
	    complete_registration(
	    $username,
	    $password,
	    $email
	    );

	} else {
		registration_form(
	    $username,
	    $password,
	    $email
	    );
	}
}

/**
 * Outputs the footer for the login page.
 *
 * @param string $input_id Which input to auto-focus
 */
function login_footer($input_id = '') {
	global $interim_login;

	// Don't allow interim logins to navigate away from the page.
	if ( ! $interim_login ): ?>
	<p id="backtoblog"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php
		/* translators: %s: site title */
		printf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) );
	?></a></p>
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
	</body>
	</html>
	<?php
}


/**
 * Utility Functions
 */
function wp_login_viewport_meta() {
	?>
	<meta name="viewport" content="width=device-width" />
	<?php
}
function registration_validation( $username, $password, $email  )  {

    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    if ( 4 > strlen( $username ) ) {
        $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
    }

    if ( username_exists( $username ) ) {
        $reg_errors->add('user_name', 'Sorry, that username is already taken.');
    }

    if ( ! validate_username( $username ) ) {
        $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid.' );
    }

    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Password length must be greater than 5.' );
    }

    if ( !is_email( $email ) ) {
       $reg_errors->add( 'email_invalid', 'Email is not valid.' );
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add( 'email', 'Sorry, that email is already in use.' );
    }    

    if ( is_wp_error( $reg_errors ) ) {
 
        foreach ( $reg_errors->get_error_messages() as $error ) {
         
            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</div>';    
        }
    }
}
function complete_registration( $username, $password, $email ) {
    global $reg_errors;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password
        );
        $user = wp_insert_user( $userdata );
        

        registration_form(
	    $username,
	    $password,
	    $email,
	    true
	    );
	} else {
		registration_form(
	    $username,
	    $password,
	    $email
	    );
	}
}
function registration_form( $username = '', $password = '', $email = '', $complete = false  ) {
    ?>
    <style>
    div {
        margin-bottom:2px;
    }
     
    input{
        margin-bottom:4px;
    }
    </style>

    <form id="registration" action="<?php echo get_site_url() .'/register/'?>" method="post">
    <?php 
    if( $complete === true ){
    	echo 'Registration complete. You\'re all ready to <a href="' . wp_login_url( site_url('/'), false ) .'">login</a>.'; 
	} else {
	?>

    <div>
   	<p class="message hidden" id="frontendvalidation"></p>
    <label for="username">Username <strong>*</strong></label>
    <input type="text" id="username" name="username" value="<?php echo ( isset( $_POST['username'] ) ? $username : null ) ?>">
    </div>

    <div>
    <label for="email">Email <strong>*</strong></label>
    <input type="text" id="email" name="email" value="<?php echo ( isset( $_POST['email']) ? $email : null ) ?>">
    </div>
     
    <div>
    <label for="password">Password <strong>*</strong></label>
    <input type="password" id="password" name="password" value="<?php echo ( isset( $_POST['password'] ) ? $password : null ) ?>">
    </div>
     
 
    <div>
    <label id="confirmlabel" for="passwordconfirm">Confirm Password</label>
    <input type="password" id="passwordconfirm" name="passwordconfirm" value="">
    </div>
     

    <input type="submit" onclick="submitRegistration(event);return false;" name="register" value="Register"/>
	<?php } ?>
    </form>
<?php
}
login_header();
login_middle();
login_footer();

 
