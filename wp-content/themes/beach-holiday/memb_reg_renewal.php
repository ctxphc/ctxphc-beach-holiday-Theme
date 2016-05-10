<?php
/*
Template Name: Reg_Renewal
*/

if ( ! is_user_logged_in() ) {
	auth_redirect();
} //User must be logged in to access this page!

global $display_name, $user_email, $wpdb, $membID, $userInfo, $defSel, $current_user;

$wpdb->show_errors();
$debug = false;

require_once TEMPLATEPATH . '/includes/randPassGen.php';

/*
$states_arr = load_states_array();
$relationship_arr = load_relationships_array();
$memb_costs = get_membership_pricing();
$membership_types = get_membership_types();
*/

get_header(); ?>

	<script>
		jQuery(document).ready(function () {
			jQuery("#procForm").validationEngine('attach', {promptPosition: "centerRight"});
		});
	</script>

<?php
//Get current users data.
//$current_user = get_currentuserinfo();

if ( ! is_user_logged_in() ) {
	echo "you shoudn't be tryn' that!";
	exit;
}

add_action( 'gform_pre_submission_6', 'paypal_renewal_payment' );

function paypal_renewal_payment(){

}

?>

	<div id="content">
		<div class="spacer"></div>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_title">
					<h1><a href="<?php the_permalink() ?>" rel="bookmark"
					       title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				</div> <!-- post_title -->
				<div class="clear"></div>
				<div class="entry">
					<?php the_content( 'more...' ); ?>
					<div class="clear"></div>

					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="DBXH8A467Z58E">
						<table>
							<tr><td><input type="hidden" name="on0" value="Membership Options">Membership Options</td></tr><tr><td><select name="os0">
										<option value="Individual">Individual $25.00 USD</option>
										<option value="Individual + Child(ren)">Individual + Child(ren) $30.00 USD</option>
										<option value="Couple">Couple $40.00 USD</option>
										<option value="Household">Household $45.00 USD</option>
									</select> </td></tr>
						</table>
						<input type="hidden" name="currency_code" value="USD">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>

					<div class="clear"></div>
				</div> <!-- entry -->
			</div> <!-- post -->

			<?php
		endwhile;
		endif;
		?>

		<?php get_sidebar(); ?>
		<?php get_footer(); ?>
<?php