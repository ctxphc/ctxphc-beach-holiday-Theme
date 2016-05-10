<?php
/*
Template Name: Club Registration
*/
global $defSel, $wpdb, $memb_error;

require_once TEMPLATEPATH . '/includes/randPassGen.php';

use CTXPHC\BeachHoliday\Classes\CLUB_Registration;

define( 'CLUB_CLASSES', trailingslashit( get_template_directory() ) . trailingslashit( 'includes/Class' ) );

if ( ! class_exists( 'PB_Reg' ) ) {
	require_once( CLUB_CLASSES . 'class-CLUB_Registration' );
}

//Change to false for production use
$debug = true;

if ( $debug ) {
	$wpdb->show_errors();
}

date_default_timezone_set( 'America/Chicago' );

/** @var STRING $relationship_table */
$relationship_table = 'ctxphc_member_relationships';

/** @var STRING $membership_type_table */
$membership_type_table = 'ctxphc_membership_types';

/** @var STRING $ctxphc_status */
$status_table = 'ctxphc_member_status';


$args             = array();
$args[ 'states' ] = get_states_array();
$args[ 'relationships' ] = load_relationships_array();
$args[ 'costs' ] = get_membership_pricing();
$args[ 'cost_count' ] = count( $args[ 'costs' ] );
$args[ 'memb_error' ] = new WP_Error();

$args[ 'rel_ids' ] = $wpdb->get_results( "SELECT * FROM $relationship_table" );
if ( $debug ) {$wpdb->print_error();}

$args[ 'status_ids' ] = $wpdb->get_results( "SELECT * FROM $status_table" );
if ( $debug ) {$wpdb->print_error();}


$mb_id = '';



get_header(); ?>
<!--suppress ALL -->
<script type="text/javascript">
	<!--
	//--------------------------------
	// This code compares two fields in a form and submit it
	// if they're the same, or not if they're different.
	//--------------------------------
	function checkEmail(theForm) {
		if (theForm.pb_email.value != theForm.pb_email_verify.value) {
			alert('Those emails don\'t match!');
			$ret_val = false;
		} else {
			$ret_val = true;
		}
		return $ret_val;
	}
	//-->
</script>

<div id="content">
	<div class="spacer"></div>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div id="post_title" class="post_title">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark"
				       title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</div>
			<!-- Post_title -->

			<div class="clear"></div>

			<div class="entry">
				<?php the_content( 'more...' ); ?>
				<div class="clear"></div>

				<div class="spacer"></div>

				<div>
					<h2 class="ctxphc_center">Central Texas Parrot Head Club</h2>
				</div>
				<p>So, you've decided you want to join in our mission to Party with a Purpose,
					have PHun and help the community? If so, <img class="alignright wp-image-7" title="membership-image"
					                                              src="<?php echo get_template_directory_uri();
					                                              ?>/includes/Images/membership-image.jpg"
					                                              alt="ctxphc membership image"/>you
					can complete the application below and make a payment using PayPal or print
					out the application and mail a check to:</p>

				<p>
					Central Texas Parrot Head Club<br/>
					c/o Membership Director<br/>
					P.O. Box 82655<br/>
					Austin, TX 78708
				</p>

				<p>Membership entitles you to attend our numerous monthly events; an official club badge; access to the monthly CTXPHC newsletter and ParrotHead-related electronic bulletins. The newsletter and bulletins keep you up-to-date regarding local, regional and statewide PHlockings (which you would be eligible to attend); community events; special discounts; concert news; VIP passes and much more!</p>

				<p>If you have any questions, contact our <a
						href="mailto:<?php echo antispambot( 'membership@ctxphc.com' ); ?>">Membership
						Director</a>.</p>

				<p>If you are ready to join in the PHun, scroll down and fill out our registration form!</p>

				<p><strong>NOTE: If this is a renewal, login to your profile and click on the
						"Renew Membership" button. If you don't know how to login, send email to
						our <a href="mailto:<?php echo antispambot( 'support@ctxphc.com' ); ?>">Support
							Staff</a>.</strong></p>

				<div class="spacer"></div>

				<div class="reg_form_row" id="mem_reg_types">
					<h4>Membership Types:</h4>
					<ul id="memb_reg_list">
						<li>Individual -
							$<?php echo $memb_costs[ 1 ]->cost; ?></li>
						<li>Individual + Child -
							$<?php echo $memb_costs[ 2 ]->cost; ?></li>
						<li>Couples -
							$<?php echo $memb_costs[ 3 ]->cost; ?></li>
						<li>Household -
							$<?php echo $memb_costs[ 4 ]->cost; ?></li>
					</ul>
				</div>

				/**
				*
				*  check for submit type.  If not
				*
				*/

				<?php
				if ( isset( $_POST[ 'submit' ] ) ) {
					$post_data = array_map( 'mysql_real_escape_string', $_POST );
					if ( ! isset( $post_data[ 'attendee_count' ] ) ) {
						$post_data[ 'attendee_count' ] = 1;
					}

					foreach ( $post_data as $ckey => $cval ) {
						error_log( $ckey . ' ------------> ' . $cval );
					}
					$memb_reg    = new CLUB_Registration( $args );
					$pb_loaded_data = $memb_reg->load_user_data( $post_data );
					foreach ( $pb_loaded_data as $lkey => $lval ) {
						error_log( $lkey . ' ------> ' . $lval );
					}
					$pb_data_insert_results = $memb_reg->pb_data_insert( $pb_reg_table, $pb_loaded_data );
					error_log( $pb_data_insert_results );

					$form_type = 'review';
					$memb_reg->display_pb_form( $form_type, $pb_data_insert_results );
				} else {
					$form_type   = 'new';
					$memb_reg = new PB_Reg( $args );
					$memb_reg->display_pb_form( $form_type );
				}
				?>

			</div>
			<!-- entry -->
		</div> <!-- post -->
		<?php
	endwhile;
	endif;
	?>
</div> <!-- content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
