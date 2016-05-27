<?php
/* Template Name: Login Page AA */

/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 2/20/2016
 * Time: 10:53 AM
 */

get_header(); ?>


	<!-- section -->
	<section class="cm_loginForm">

		<?php global $user_login;

		if ( isset( $_GET[ 'login' ] ) && $_GET[ 'login' ] == 'failed' ) {
			?>
			<div class="aa_error">
				<p>FAILED: Try again!</p>
			</div>
			<?php
		}
		
		if ( is_user_logged_in() ) {
			echo '<div class="aa_logout"> Hello, <div class="aa_logout_user">', $user_login, '. You are already logged in.</div><a id="wp-submit" href="', wp_logout_url(), '" title="Logout">Logout</a></div>';
		} else {
			wp_login_form( $args );

			$args = array(
				'echo'           => true,
				'redirect'       => home_url( '/wp-admin/' ),
				'form_id'        => 'loginform',
				'label_username' => __( 'Username' ),
				'label_password' => __( 'Password' ),
				'label_remember' => __( 'Remember Me' ),
				'label_log_in'   => __( 'Log In' ),
				'id_username'    => 'user_login',
				'id_password'    => 'user_pass',
				'id_remember'    => 'rememberme',
				'id_submit'      => 'wp-submit',
				'remember'       => true,
				'value_username' => null,
				'value_remember' => true,
			);
		}
		?>
	</section>
	<!-- /section -->

	<section class="cm_renewalForm">
		//todo: create renewal form
	</section>

	<section class="cm_profileEdit">
		//todo: create profile edit form
	</section>

<?php get_footer(); ?>