<?php

$action = !empty( $_GET['action'] ) && ($_GET['action'] == 'register' || $_GET['action'] == 'forgot' || $_GET['action'] == 'resetpass') ? $_GET['action'] : 'login';
$success = !empty( $_GET['success'] );
$failed = !empty( $_GET['failed'] ) ? $_GET['failed'] : false;

?>

<?php get_header(); ?>

<main id="main" class="site-main wrapper" role="main">
	<div class="main-column">

	<?php while ( have_posts() ) : the_post(); ?>

<?php if ( !$success && $action != 'resetpass' ): ?>
	<ul class="tabs cf" id="login-tabs">
		<li class="<?php if ($action == 'login') echo 'active-tab'; ?>"><a href="#tab-login">Login</a></li>
		<li class="<?php if ($action == 'register') echo 'active-tab'; ?>"><a href="#tab-register">Register</a></li>
		<li class="<?php if ($action == 'forgot') echo 'active-tab'; ?>"><a href="#tab-forgot">Forgot?</a></li>
	</ul>
<?php endif; ?>

	<article id="page-<?php the_ID(); ?>" class="meta-box hentry">
		<div id="page-login" class="post-content page-login cf">

<?php if ( $action == 'register' && $success ): ?>

			<header class="entry-header">
				<h1>Success!</h1>
			</header>

			<div class="message-box message-success">
				<span class="icon-thumbs-up"></span>
				Check your email for the password and then return to log in.
			</div>

<?php elseif ( $action == 'forgot' && $success ): ?>

			<header class="entry-header">
				<h1>Password recovery</h1>
			</header>

			<div class="message-box message-info">
				<span class="icon-bell"></span>
				Check your email for the instructions to get a new password.
			</div>

<?php elseif ( $action == 'resetpass' && $success ): ?>

			<header class="entry-header">
				<h1>Password reset</h1>
			</header>

			<div class="message-box message-success">
				<span class="icon-thumbs-up"></span>
				Your password has been updated. <a href="/login/">Proceed to login</a>.
			</div>

<?php else: ?>

			<div id="tab-login" class="tab-content" style="<?php if ( $action != 'login' ) echo 'display:none' ?>">

<?php if ( $action == 'login' && $failed ): ?>
			<div class="message-box message-error">
				<span class="icon-attention"></span>
				<?php if ( $failed ): ?>
					Invalid username or password. Please try again.
				<?php endif; ?>
			</div>
<?php endif; ?>

				<header class="entry-header">
					<h1 class="entry-title">Login</h1>
				</header>

				<div class="entry-content">
					<p>Don't have an account? <a href="/login/?action=register">Sign up now</a>!</p>
				</div>

				<?php wp_login_form(); ?>

			</div>

			<div id="tab-register" class="tab-content" style="<?php if ( $action != 'register' ) echo 'display:none' ?>">

<?php if ( $action == 'register' && $failed ): ?>
			<div class="message-box message-error">
				<span class="icon-attention"></span>
				<?php if ( $failed == 'invalid_character' ): ?>
					Username can only contain alphanumerical characters, "_" and "-". Please choose another username.
				<?php elseif ( $failed == 'username_exists' ): ?>
					Username already in use.
				<?php elseif ( $failed == 'email_exists' ): ?>
					E-mail already in use. Maybe you are already registered?
				<?php elseif ( $failed == 'empty' ): ?>
					All fields are required.
				<?php else: ?>
					An error occurred while registering the new user. Please try again.
				<?php endif; ?>
			</div>
<?php endif; ?>

				<header class="entry-header">
					<h1 class="entry-title">Register</h1>
				</header>

				<div class="entry-content">
					<p>Sign up for the cool stuff!</p>
				</div>

				<form name="registerform" id="registerform" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
					<p>
						<label for="user_login">Username</label>
						<input type="text" name="user_login" id="user_login" class="input" value="">
					</p>
					<p>
						<label for="user_email">E-mail</label>
						<input type="text" name="user_email" id="user_email" class="input" value="">
					</p>
					<p style="display:none">
						<label for="confirm_email">Please leave this field empty</label>
						<input type="text" name="confirm_email" id="confirm_email" class="input" value="">
					</p>

					<p id="reg_passmail">A password will be e-mailed to you.</p>

					<input type="hidden" name="redirect_to" value="/login/?action=register&amp;success=1" />
					<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Register" /></p>
				</form>

			</div>

			<div id="tab-forgot" class="tab-content" style="<?php if ( $action != 'forgot' ) echo 'display:none' ?>">

<?php if ( $action == 'forgot' && $failed ): ?>
			<div class="message-box message-error">
				<span class="icon-attention"></span>
				<?php if ( $failed == 'wrongkey' ): ?>
					The reset key is wrong or expired. Please check that you used the right reset link or request a new one.
				<?php else: ?>
					Sorry, we couldn't find any user with that username or email.
				<?php endif; ?>
			</div>
<?php endif; ?>
				<header class="entry-header">
					<h1 class="entry-title">Password recovery</h1>
				</header>

				<div class="entry-content">
					<p>Please enter your username or email address. You will receive a link to create a new password.</p>
				</div>

				<form name="lostpasswordform" id="lostpasswordform" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" method="post">
					<p>
						<label for="user_login">Username or E-mail:</label>
						<input type="text" name="user_login" id="user_login" class="input" value="">
					</p>

					<input type="hidden" name="redirect_to" value="/login/?action=forgot&amp;success=1">
					<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Get New Password" /></p>
				</form>

			</div>


<?php if ( $action == 'resetpass' ): ?>

	<div id="tab-resetpass" class="tab-content">

	<?php if ( $failed ): ?>
			<div class="message-box message-error">
				<span class="icon-attention"></span>
				The passwords don't match. Please try again.
			</div>

	<?php endif; ?>

				<header class="entry-header">
					<h1 class="entry-title">Reset password</h1>
				</header>

				<div class="entry-content">
					<p>Create a new password for your account.</p>
				</div>

				<form name="resetpasswordform" id="resetpasswordform" action="<?php echo site_url('wp-login.php?action=resetpass', 'login_post') ?>" method="post">
					<p class="form-password">
						<label for="pass1">New Password</label>
						<input class="text-input" name="pass1" type="password" id="pass1">
					</p>

					<p class="form-password">
						<label for="pass2">Confirm Password</label>
						<input class="text-input" name="pass2" type="password" id="pass2">
					</p>

					<input type="hidden" name="redirect_to" value="/login/?action=resetpass&amp;success=1">
					<?php
					$rp_key = '';
					$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
					if ( isset( $_COOKIE[ $rp_cookie ] ) && 0 < strpos( $_COOKIE[ $rp_cookie ], ':' ) ) {
						list( $rp_login, $rp_key ) = explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 );
					}
					?>
					<input type="hidden" name="rp_key" value="<?php echo esc_attr( $rp_key ); ?>">
					<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Get New Password" /></p>
				</form>
			</div>
<?php endif; ?>


<?php endif; ?>

		</div>
	</article>

	<?php endwhile; ?>

	</div><!-- .main-column -->

	<?php get_sidebar(); ?>
</main><!-- #main -->

<script>

// tabs
$('#login-tabs a').click(function (e) {
	e.preventDefault();

	$this = $(this);

	// add class to tab
	$('#login-tabs li').removeClass('active-tab');
	$this.parent().addClass('active-tab');

	// show the right tab
	$('#page-login .tab-content').hide();
	$('#page-login ' + $this.attr('href')).show();

	return false;
});

</script>

<?php get_footer(); ?>
