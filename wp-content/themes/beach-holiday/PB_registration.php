<?php
/*
Template Name: PB Reg
*/

//xdebug_start_trace();

global $defSel, $wpdb;

use CTXPHC\BeachHoliday\Classes\PB_Reg;

define( 'THEME_CLASSES', trailingslashit( get_template_directory() ) . trailingslashit( 'includes/Class' ) );

if ( ! class_exists( 'PB_Reg' ) ) {
	require_once( THEME_CLASSES . 'class-PB_Reg.php' );
}

if ( $_POST[ 'submit' ] ) {
	$pb_submit_type = sanitize_text_field( $_POST[ 'submit' ] );

	if ( isset( $pb_submit_type ) && ! empty( $pb_submit_type ) ) {
		$clean_post_data = filter_var_array( $_POST, FILTER_SANITIZE_STRING );
	}
} else {
	//This was accessed from a link and not from a submit button
	$pb_submit_type = 'new';
}

$pb_cost                = number_format( 65, 2, '.', '' );
$pb_open_cost           = number_format( 55, 2, '.', '' );
$pb_memb_cost           = number_format( 45, 2, '.', '' );
$pb_cruise_cost         = number_format( 40, 2, '.', '' );
$pb_reg_begin_time      = "23:59:00";
$pb_curr_reg_year       = date( "Y" );
$pb_begin_open_reg_date = 'June 1, ' . $pb_curr_reg_year . ' ' . $pb_reg_begin_time;
$pb_begin_reg_reg_date  = 'August 1, ' . $pb_curr_reg_year . ' ' . $pb_reg_begin_time;
$pb_reg_table           = 'ctxphc_pb_reg';

$pb_today = new DateTime();
$expiry   = new DateTime( $pb_begin_open_reg_date );
$expiry2  = new DateTime( $pb_begin_reg_reg_date );

if ( isset( $_GET[ 'pb_reg_type' ] ) && ( $_GET[ 'pb_reg_type' ] == 'member' || $_GET[ 'pb_reg_type' ] == 'complimentary' ) ) {
	$pb_reg_type = $_GET[ 'pb_reg_type' ];
} else {
	if ( $expiry >= $pb_today && $expiry <= $expiry2 ) {
		$pb_reg_type = 'open';
	} else {
		$pb_reg_type = 'registration';
	}
}

/* Define args to pass to PB_Reg class */
$args             = array();
$args[ 'states' ] = load_states_array();

$args[ 'pb_reg_req_type' ] = $pb_reg_type;
$args[ 'pb_cost' ]         = $pb_cost;
$args[ 'pb_open_cost' ]    = $pb_open_cost;
$args[ 'pb_memb_cost' ]    = $pb_memb_cost;
$args[ 'pb_cruise_cost' ]   = $pb_cruise_cost;

if ( isset( $pb_today->date ) ) {
	$args[ 'pb_today' ] = $pb_today->date;
}
if ( isset( $expiry->date ) ) {
	$args[ 'expiry' ] = $expiry->date;
}
if ( isset( $expiry2 ) ) {
	$args[ 'expiry2' ] = $expiry2->date;
}
$args[ 'table' ] = $pb_reg_table;

$args[ 'pb_reg_year' ]   = $pb_curr_reg_year;
$args[ 'pb_title_text' ] = "CTXPHC " . $pb_curr_reg_year . " Pirate's Ball";

$args[ 'pb_member_reg_head_text' ] = 'Members Only Early Registration';
$args[ 'pb_member_reg_cost_text' ] = 'CTXPHC Members only early registration cost';

$args[ 'pb_open_reg_head_text' ]      = 'Open Registration';
$args[ 'pb_open_reg_cost_text' ]      = 'Open Registration cost';
$args[ 'pb_open_reg_cost_next_text' ] = 'Beginning June 1st Open Registration will cost';

$args[ 'pb_reg_reg_head_text' ]      = 'Registration';
$args[ 'pb_reg_reg_cost_text' ]      = 'Registration cost';
$args[ 'pb_reg_reg_cost_next_text' ] = 'Beginning August 1st Registration will cost';

$args[ 'form_type' ] = $pb_submit_type;


switch ( $pb_submit_type ) {
	case 'review':
	case 'update':
		
		$result = pb_insert_registration_data( $pb_reg_table, $clean_post_data );

		foreach ( $clean_post_data as $ckey => $cval ) {
			error_log( $ckey . ' ------------> ' . $cval );
		}

		$args[ 'form_type' ] = $pb_reg_form_type = 'review';
		$memb_pb_reg         = new PB_Reg( $args );
		$pb_loaded_data      = $memb_pb_reg->load_user_data( $clean_post_data );

		foreach ( $pb_loaded_data as $lkey => $lval ) {
			error_log( $lkey . ' ------> ' . $lval );
		}
		$pb_data_insert_results = $memb_pb_reg->pb_data_insert( $pb_reg_table, $pb_loaded_data );
		error_log( $pb_data_insert_results );


		break;
	case 'failed':
		$form_type   = $pb_reg_type;
		$memb_pb_reg = new PB_Reg( $args );
		break;
	default:
		$memb_pb_reg = new PB_Reg( $args );
		break;
}

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
					<h2 class="pieces_of_eight"><?php echo $memb_pb_reg->pb_reg_text[ 'pb_title_text' ]; ?></h2>
					<h2 class="pb_center" id="memb_reg"><?php echo $memb_pb_reg->pb_reg_text[ 'pb_reg_head_text' ]; ?></h2>
				</div>

				<div class="spacer"></div>

				<div>
					<img id='PB_logo'
					     alt="CTXPHC Pirate's Ball 2016 Logo"
					     src="http://www.ctxphc.com/wp-content/uploads/2016/05/2016pirate27sballlogo.jpg"
					     width="300"
					/>
				</div>

				<div class="spacer"></div>

				<div class="pb_cost" id="memb_reg_cost">
					<h4 class="pb_center pb_header">
						<?php echo $memb_pb_reg->pb_reg_text[ 'pb_reg_cost_text' ] . ': $' . $memb_pb_reg->pb_memb_cost; ?> per person
					</h4>
					<ul>
						<li class="pb_details">
							<?php echo $memb_pb_reg->pb_reg_text[ 'pb_reg_cost_text_A' ] . ': $' . $memb_pb_reg->pb_open_cost . ' per person.'; ?>
						</li>
						<li class="pb_details">
							<?php echo $memb_pb_reg->pb_reg_text[ 'pb_reg_cost_text_B' ] . ': $' . $memb_pb_reg->pb_cost . ' per person.'; ?>
						</li>
					</ul>
				</div>

				<p class="pb_center pb_details">
					<a class="pb_details_link" href="https://www.ctxphc.com/pirates-ball-details/">
						Click here for additional event and hotel information!
					</a>
				</p>
				<?php
				if ( isset( $_GET[ 'pb_reg_type' ] ) && ! empty( $_GET[ 'pb_reg_type' ] ) ) {
					$memb_pb_reg->display_pb_form( $pb_reg_form_type, $pb_reg_req_type );
				} else if ( isset( $_POST[ 'submit' ] ) ) {
					$memb_pb_reg->display_pb_form( $pb_reg_form_type, $pb_data_insert_results );
				} else {
					$memb_pb_reg->display_pb_form( $pb_reg_form_type );
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
<?php //xdebug_stop_trace(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
