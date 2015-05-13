<?php

/**
 * Redirect to the custom login page
 */
function cubiq_login_init () {
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

	if ( isset( $_POST['wp-submit'] ) ) {
		$action = 'post-data';
	} else if ( isset( $_GET['reauth'] ) ) {
		$action = 'reauth';
	}

	// redirect to change password form
	if ( $action == 'rp' || $action == 'resetpass' ) {
		if( isset($_GET['key']) && isset($_GET['login']) ) {
			$rp_path = wp_unslash('/login/');
			$rp_cookie	= 'wp-resetpass-' . COOKIEHASH;
			$value = sprintf( '%s:%s', wp_unslash( $_GET['login'] ), wp_unslash( $_GET['key'] ) );
			setcookie( $rp_cookie, $value, 0, $rp_path, COOKIE_DOMAIN, is_ssl(), true );
		}
		
		wp_redirect( home_url('/login/?action=resetpass') );
		exit;
	}

	// redirect from wrong key when resetting password
	if ( $action == 'lostpassword' && isset($_GET['error']) && ( $_GET['error'] == 'expiredkey' || $_GET['error'] == 'invalidkey' ) ) {
		wp_redirect( home_url( '/login/?action=forgot&failed=wrongkey' ) );
		exit;
	}

	if (
		$action == 'post-data'		||			// don't mess with POST requests
		$action == 'reauth'			||			// need to reauthorize
		$action == 'logout'						// user is logging out
	) {
		return;
	}

	wp_redirect( home_url( '/login/' ) );
	exit;
}
add_action('login_init', 'cubiq_login_init');


/**
 * Redirect logged in users to the right page
 */
function cubiq_template_redirect () {
	if ( is_page( 'login' ) && is_user_logged_in() ) {
		wp_redirect( home_url( '/user/' ) );
		exit();
	}

	if ( is_page( 'user' ) && !is_user_logged_in() ) {
		wp_redirect( home_url( '/login/' ) );
		exit();
	}
}
add_action( 'template_redirect', 'cubiq_template_redirect' );


/**
 * Prevent subscribers to access the admin
 */
function cubiq_admin_init () {

	if ( current_user_can( 'subscriber' ) && !defined( 'DOING_AJAX' ) ) {
		wp_redirect( home_url('/user/') );
		exit;
	}

}
add_action( 'admin_init', 'cubiq_admin_init' );


/**
 * Registration page redirect
 */
function cubiq_registration_redirect ($errors, $sanitized_user_login, $user_email) {

	// don't lose your time with spammers, redirect them to a success page
	if ( !isset($_POST['confirm_email']) || $_POST['confirm_email'] !== '' ) {

		wp_redirect( home_url('/login/') . '?action=register&success=1' );
		exit;

	}

	if ( !empty( $errors->errors) ) {
		if ( isset( $errors->errors['username_exists'] ) ) {

			wp_redirect( home_url('/login/') . '?action=register&failed=username_exists' );

		} else if ( isset( $errors->errors['email_exists'] ) ) {

			wp_redirect( home_url('/login/') . '?action=register&failed=email_exists' );

		} else if ( isset( $errors->errors['invalid_username'] ) ) {

			wp_redirect( home_url('/login/') . '?action=register&failed=invalid_username' );
			
		} else if ( isset( $errors->errors['invalid_email'] ) ) {
+
+			wp_redirect( home_url('/login/') . '?action=register&failed=invalid_email' );

		} else if ( isset( $errors->errors['empty_username'] ) || isset( $errors->errors['empty_email'] ) ) {

			wp_redirect( home_url('/login/') . '?action=register&failed=empty' );

		} else {

			wp_redirect( home_url('/login/') . '?action=register&failed=generic' );

		}

		exit;
	}

	return $errors;

}
add_filter('registration_errors', 'cubiq_registration_redirect', 10, 3);


/**
 * Login page redirect
 */
function cubiq_login_redirect ($redirect_to, $url, $user) {

	if ( !isset($user->errors) ) {
		return $redirect_to;
	}

	wp_redirect( home_url('/login/') . '?action=login&failed=1');
	exit;

}
add_filter('login_redirect', 'cubiq_login_redirect', 10, 3);


/**
 * Password reset redirect
 */
function cubiq_reset_password () {
	$user_data = '';

	if ( !empty( $_POST['user_login'] ) ) {
		if ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim($_POST['user_login']) );
		} else {
			$user_data = get_user_by( 'login', trim($_POST['user_login']) );
		}
	}

	if ( empty($user_data) ) {
		wp_redirect( home_url('/login/') . '?action=forgot&failed=1' );
		exit;
	}
}
add_action( 'lostpassword_post', 'cubiq_reset_password');


/**
 * Validate password reset
 */
function cubiq_validate_password_reset ($errors, $user) {
	// passwords don't match
	if ( $errors->get_error_code() ) {
		wp_redirect( home_url('/login/?action=resetpass&failed=nomatch') );
		exit;
	}

	// wp-login already checked if the password is valid, so no further check is needed
	if ( !empty( $_POST['pass1'] ) ) {
		reset_password($user, $_POST['pass1']);

		wp_redirect( home_url('/login/?action=resetpass&success=1') );
		exit;
	}

	// redirect to change password form
	wp_redirect( home_url('/login/?action=resetpass') );
	exit;
}
add_action('validate_password_reset', 'cubiq_validate_password_reset', 10, 2);


