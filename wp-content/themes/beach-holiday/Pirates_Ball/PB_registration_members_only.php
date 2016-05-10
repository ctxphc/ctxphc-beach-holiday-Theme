<?php
/*
Template Name: PB Members Only Reg
*/
global $defSel, $wpdb;

use CTXPHC\BeachHoliday\Classes\PB_Reg;

define( 'BH_CLASSES', trailingslashit( get_template_directory() ) . trailingslashit( 'includes/Class' ) );

if ( ! class_exists( 'PB_Reg' ) ) {
	require_once( BH_CLASSES . 'class-PB_Reg.php' );
}

$args             = array();
$args[ 'states' ] = get_states_array();

$pb_cost       = 65.00;
$pb_early_cost = 55.00;
$pb_memb_cost  = 45.00;
$pb_cruse_cost = 40.00;

$args[ 'pb_today' ] = $pb_today = new DateTime();
$args[ 'expiry' ]   = $expiry = new DateTime( "May 31, 2016 23:59:00" );
$args[ 'expiry2' ]  = $expiry2 = new DateTime( "July 25, 2016 23:59:00" );
$args[ 'table' ]    = $table = 'ctxphc_pb_reg';

if ( $pb_today >= $expiry && $pb_today <= $expiry2 ) {
	$args[ 'pb_memb_class' ]  = $pb_memb_class = 'pb_hidden';
	$args[ 'pb_early_class' ] = $pb_early_class = 'pb_display';
	$args[ 'pb_late_class' ]  = $pb_late_class = 'pb_hidden';
	$args[ 'pb_reg_cost' ]    = $pb_reg_cost = $pb_early_cost;
} else if ( $pb_today > $expiry2 ) {
	$args[ 'pb_memb_class' ]  = $pb_memb_class = 'pb_hidden';
	$args[ 'pb_early_class' ] = $pb_early_class = 'pb_hidden';
	$args[ 'pb_late_class' ]  = $pb_late_class = 'pb_display';
	$args[ 'pb_reg_cost' ]    = $pb_reg_cost = $pb_cost;
} else {
	$args[ 'pb_memb_class' ]  = $pb_memb_class = 'pb_display';
	$args[ 'pb_early_class' ] = $pb_early_class = 'pb_hidden';
	$args[ 'pb_late_class' ]  = $pb_late_class = 'pb_hidden';
	$args[ 'pb_reg_cost' ]    = $pb_reg_cost = $pb_memb_cost;
}

$args[ 'pb_cruse_cost' ] = $pb_cruse_cost;


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
			return false;
		} else {
			return true;
		}
	}
	//-->
</script>

<div id="content">
	<div class="spacer"></div>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post_title">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark"
				       title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</h1>
			</div>
			<div class="clear"></div>
			<div class="entry">
				<?php the_content( 'more...' ); ?>
				<div class="clear"></div>
				<div class="spacer"></div>
				<div class="pb_header">
					<h2 class="pieces_of_eight">CTXPHC 2016 Pirate's Ball</h2>
					<h2 class="pb_center <?php echo $pb_memb_class; ?>" id="memb_reg">Members Only Early Registration</h2>
					<h2 class="pb_center <?php echo $pb_early_class; ?>" id="early_reg">Early Registration</h2>
					<h2 class="pb_center <?php echo $pb_late_class; ?>" id="late_reg">Registration</h2>
				</div>

				<div class="spacer"></div>

				<div>
					<img id='PB_logo'
					     alt="CTXPHC Pirate's Ball 2016 Logo"
					     src="http://www.ctxphc.com/wp-content/uploads/2016/05/2016pirate27sballlogo.jpg"
					     width="300"
					/>
				</div>
				<div class="pb_cost <?php echo $pb_memb_class; ?>" id="memb_reg_cost">
					<h4 class="pb_center pb_header">CTXPHC Members only early registration cost:
						$<?php echo $pb_memb_cost; ?> per
						person</h4>
					<ul>
						<li class="pb_details">Beginning June 1st Early Registration will cost:
							$<?php echo
							$pb_early_cost ?> per person
						</li>
						<li class="pb_details">Beginning Aug 1st Registration will cost:
							$<?php echo $pb_cost; ?> pre person.
						</li>
					</ul>
				</div>
				<div class="pb_cost <?php echo $pb_early_class; ?>" id="early_reg_cost">
					<h4 class="pb_center pb_header">CTXPHC Early registration cost is
						$<?php echo $pb_early_cost; ?> per person</h4>
					<ul class="pb_details">
						<li class="pb_details">Beginning Aug 1st Registration will cost:
							$<?php echo $pb_cost; ?> pre person.
						</li>
					</ul>
				</div>
				<div class="pb_cost <?php echo $pb_late_class; ?>" id="late_reg_cost">
					<h4 class="pb_center pb_header">CTXPHC Registration cost:
						$<?php echo $pb_cost; ?> per person</h4>
				</div>

				<p class="pb_center pb_details">
					<a class="pb_details_link" href="https://www.ctxphc.com/pirates-ball-details/">
						Click here for additional event and hotel information!
					</a>
				</p>


				<?php
				if ( isset( $_POST[ 'submit' ] ) ) {
					$clean_post_data = array_map( 'mysql_real_escape_string', $_POST );
					if ( ! isset( $clean_post_data[ 'attendee_count' ] ) ) {
						$clean_post_data[ 'attendee_count' ] = 1;
					}

					foreach ( $clean_post_data as $ckey => $cval ) {
						error_log( $ckey . ' ------------> ' . $cval );
					}
					$memb_pb_reg    = new PB_Reg( $args );
					$pb_loaded_data = $memb_pb_reg->load_user_data( $clean_post_data );
					foreach ( $pb_loaded_data as $lkey => $lval ) {
						error_log( $lkey . ' ------> ' . $lval );
					}
					$pb_data_insert_results = $memb_pb_reg->pb_data_insert( $table, $pb_loaded_data );
					error_log( $pb_data_insert_results );

					$form_type = 'review';
					$memb_pb_reg->display_pb_form( $form_type, $pb_data_insert_results );
				} else {
					$form_type   = 'new';
					$memb_pb_reg = new PB_Reg( $args );
					$memb_pb_reg->display_pb_form( $form_type );
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
