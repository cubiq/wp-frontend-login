<?php

global $current_user;
get_currentuserinfo();

require_once( ABSPATH . WPINC . '/registration.php' );

if ( !empty($_POST) && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

	/* Update user password */
	if ( !empty($_POST['current_pass']) && !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {

		if ( !wp_check_password( $_POST['current_pass'], $current_user->user_pass, $current_user->ID) ) {
			$error = 'Your current password does not match. Please retry.';
		} elseif ( $_POST['pass1'] != $_POST['pass2'] ) {
			$error = 'The passwords do not match. Please retry.';
		} elseif ( strlen($_POST['pass1']) < 4 ) {
			$error = 'A bit short as a password, don\'t you thing?';
		} elseif ( false !== strpos( wp_unslash($_POST['pass1']), "\\" ) ) {
			$error = 'Password may not contain the character "\\" (backslash).';
		} else {
			$error = wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );

			if ( !is_int($error) ) {
				$error = 'An error occurred while updating your profile. Please retry.';
			} else {
				$error = false;
			}
		}

		if ( empty($error) ) {
			do_action('edit_user_profile_update', $current_user->ID);
			wp_redirect( site_url('/user/') . '?success=1' );
			exit;
		}
	}
}

?>

<?php get_header(); ?>

<main id="main" class="site-main wrapper" role="main">
	<div class="main-column">

	<?php while ( have_posts() ) : the_post(); ?>

	<article id="page-<?php the_ID(); ?>" class="meta-box hentry">
		<div class="post-content cf">

<?php if ( !empty($_GET['success']) ): ?>
			<div class="message-box message-success">
				<span class="icon-thumbs-up"></span>
				Profile updated successfully!
			</div>
<?php endif; ?>

<?php if ( !empty($error) ): ?>
			<div class="message-box message-error">
				<span class="icon-thumbs-up"></span>
				<?php echo $error; ?>
			</div>
<?php endif; ?>

			<header class="entry-header">
				<h1 class="entry-title">Welcome, <span class="userColor"><?php echo esc_html($current_user->display_name); ?></span></h1>
			</header>

			<div class="entry-content">
				<p>Pretty empty over here. Don’t worry it will fill up over time.</p>

				<hr>
			</div><!-- .entry-content -->

				<h2>Change password</h2>
				<p>You may change your password if you are so inclined.</p>

				<form method="post" id="adduser" action="/user/">

					<p class="form-password">
						<label for="current_pass">Current Password</label>
						<input class="text-input" name="current_pass" type="password" id="current_pass">
					</p>

					<p class="form-password">
						<label for="pass1">New Password</label>
						<input class="text-input" name="pass1" type="password" id="pass1">
					</p>

					<p class="form-password">
						<label for="pass2">Confirm Password</label>
						<input class="text-input" name="pass2" type="password" id="pass2">
					</p>

<?php

// action hook for plugin and extra fields
do_action('edit_user_profile', $current_user);

?>
					<p class="form-submit">
						<input name="updateuser" type="submit" id="updateuser" class="submit button" value="Update profile">
						<input name="action" type="hidden" id="action" value="update-user">
					</p>
				</form>

				<hr>

				<p><a style="float:right" href="<?php echo wp_logout_url( home_url() ); ?>" class="icon-cancel standard-button button-logout">logout</a></p>

		</div>
	</article>

	<?php endwhile; ?>

	</div><!-- .main-column -->

	<?php get_sidebar(); ?>
</main><!-- #main -->

<?php get_footer(); ?>
