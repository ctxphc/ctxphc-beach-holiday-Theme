<?php
/**
 * Template Name: PBRegistration
 *
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/12/2016
 * Time: 9:25 PM
 */

if ( file_exists( TEMPLATEPATH . "/includes/pb_reg_functions.php" ) ) {
	/* @noinspection PhpIncludeInspection */
	require_once TEMPLATEPATH . "/includes/pb_reg_functions.php";
}

if ( $_POST[ 'submit' ] ) {
	$form_type       = $_POST[ 'submit' ];
	$clean_post_data = filter_var_array( $_POST, FILTER_SANITIZE_STRING );
	$pb_reg_table = 'ctxphc_pb_reg';

	$pb_display_data = $prep_pb_reg_data( $clean_post_data );

	$pb_data_insert( $pb_reg_table, $pb_display_data );
} else {
	// Accessed from a link and not from a submit button
	$form_type = 'new';
}

$pb_cost        = number_format( 65, 2, '.', '' );
$pb_open_cost   = number_format( 55, 2, '.', '' );
$pb_memb_cost   = number_format( 45, 2, '.', '' );
$pb_cruise_cost = number_format( 40, 2, '.', '' );

$pb_reg_begin_time      = "23:59:00";
$pb_curr_reg_year       = date( "Y" );
$pb_begin_open_reg_date = 'June 1, ' . $pb_curr_reg_year . ' ' . $pb_reg_begin_time;
$pb_begin_reg_reg_date  = 'August 1, ' . $pb_curr_reg_year . ' ' . $pb_reg_begin_time;
$pb_reg_table           = 'ctxphc_pb_reg';

$pb_today = new DateTime();
$expiry   = new DateTime( $pb_begin_open_reg_date );
$expiry2  = new DateTime( $pb_begin_reg_reg_date );

$pb_title_text = "CTXPHC " . $pb_curr_reg_year . " Pirate's Ball";

if ( $expiry >= $pb_today && $expiry <= $expiry2 ) {
	$pb_reg_type           = 'open';
	$pb_reg_cost           = $pb_open_cost;
	$pb_reg_head_text      = 'Open Registration';
	$pb_reg_cost_text      = 'Open Registration cost';
	$pb_reg_cost_text_A    = 'Registration cost';
	$pb_late_reg_cost_text = 'Beginning August 1st Registration will cost';
	$pb_reg_cost_class     = 'pb_display';
	$pb_reg_cost_a_class   = 'pb_display';
	$pb_reg_cost_b_class   = 'pb_hidden';
} else if ( $expiry2 > $expiry ) {
	$pb_reg_type         = 'registration';
	$pb_reg_cost         = $pb_cost;
	$pb_reg_head_text    = 'Registration';
	$pb_reg_cost_text    = 'Registration cost';
	$pb_reg_cost_class   = 'pb_hidden';
	$pb_reg_cost_a_class = 'pb_hidden';
	$pb_reg_cost_b_class = 'pb_hidden';
} else {
	$pb_reg_type           = 'members';
	$pb_reg_head_text      = 'Members Only Early Registration';
	$pb_reg_cost_text      = 'CTXPHC Members only early registration cost';
	$pb_reg_cost_text_A    = 'Open Registration cost';
	$pb_reg_cost_text_B    = 'Registration cost';
	$pb_next_reg_cost_text = 'Beginning June 1st Open Registration will cost';
	$pb_late_reg_cost_text = 'Beginning August 1st Registration will cost';
	$pb_reg_cost_a_class   = 'pb_display';
	$pb_reg_cost_b_class   = 'pb_display';
	$pb_reg_cost_class     = 'pb_display';
}

$states[ 'states' ] = load_states_array();

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
		<?php if ( have_posts() ) : while ( have_posts() ) :
			the_post(); ?>
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
						<h2 class="pieces_of_eight"></h2>
						<h2 class="pb_center" id="memb_reg"><?php echo $pb_reg_head_text; ?></h2>
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

					<div class="pb_cost" id="<?php echo $pb_reg_cost_class; ?>">
						<h4 class="pb_center pb_header">
							<?php echo $pb_reg_cost_text . ': $' . $pb_reg_cost . ' per person.'; ?>
						</h4>
						<ul id="next_reg_dates">
							<li class="pb_details" id="<?php echo $pb_reg_cost_a_class; ?>">
								<?php echo $pb_reg_cost_text_A . ': $' . $pb_open_cost . ' per person.'; ?>
							</li>
							<li class="pb_details" id="<?php echo $pb_reg_cost_b_class; ?>">
								<?php echo $pb_reg_cost_text_B . ': $' . $pb_cost . ' per person.'; ?>
							</li>
						</ul>
					</div>

					<p class="pb_center pb_details">
						<a class="pb_details_link" href="https://www.ctxphc.com/pirates-ball-details/">
							Click here for additional event and hotel information!
						</a>
					</p>

					<div class="spacer"></div>

					<form id="pbMembOnlyRegForm" name="pbRegForm" method="post" action="">
						<fieldset class="pb_reg_form" id="members_info">
							<legend><span class="memb_legend">Your Information</span></legend>

							<!-- First Name -->
							<div class="pb_rows" id="personal_info">
								<label class="pb_lbl_left"
								       id="pb_lbl_fname"
								       for="pb_fname">First Name:
								</label>
								<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
								       data-prompt-position="bottomLeft"
								       id="pb_fname"
								       type="text"
								       name="pb_fname"
								/>

								<!-- Last Name -->
								<label id="pb_lbl_lname"
								       class="pb_lbl_right"
								       for="pb_lname">Last Name:
								</label>
								<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
								       data-prompt-position="bottomLeft"
								       id="pb_lname"
								       type="text"
								       name="pb_lname"
								/>
							</div>

							<!-- Email -->
							<div class="pb_rows" id="div_pb_email">
								<label class="pb_lbl_left"
								       id="pb_lbl_email"
								       for="pb_email">Email:
								</label>
								<input class="validate[required, custom[email]] pb_input_left"
								       data-prompt-position="bottomLeft"
								       id="pb_email"
								       name="pb_email"
								       type="text"/>

								<!-- Verify Email -->
								<label class="pb_lbl_right"
								       id="pb_lbl_email_verify"
								       for="pb_email_verify">Verify Email:
								</label>
								<input class="validate[required, custom[email]] pb_input_right"
								       data-prompt-position="bottomLeft"
								       id="pb_email_verify"
								       name="pb_email_verify"
								       type="text"
								/>
							</div>

							<!-- Phone -->
							<div class="pb_rows" id="div_pb_phone_affiliation">
								<label class="pb_lbl_left"
								       id="pb_lbl_phone"
								       for="pb_phone">Phone:
								</label>
								<input class="validate[required, custom[phone]] pb_input_left"
								       data-prompt-position="bottomLeft"
								       id="pb_phone"
								       name="pb_phone"
								       type="tel"
								/>

								<!-- Club Affiliation -->
								<label class="pb_lbl_right"
								       id="pb_lbl_club"
								       for="pb_club">Club Affiliation:
								</label>
								<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
								       data-prompt-position="bottomLeft"
								       id="pb_club"
								       name="pb_club"
								       type="text"
								/>
							</div>

						</fieldset>

						<div class='spacer'></div>

						<fieldset class="pb_reg_form" id="pb_Attend_2">
							<legend><span class="memb_legend">2nd Attendee</span></legend>
							<div id="pb_attendee_2">
								<div class="pb_rows">
									<!-- Attendee First Name -->
									<label class="pb_lbl_left"
									       id="pb_lbl_attendee_fname_2"
									       for="pb_attendee_fname_2">First Name:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_fname_2"
									       name="pb_attendee_fname_2"
									       type="text"
									/>

									<!-- Attendee Last Name -->
									<label class="pb_lbl_right"
									       id="pb_lbl_attendee_lname_2"
									       for="pb_attendee_lname_2">Last Name:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_lname_2"
									       name="pb_attendee_lname_2"
									       type="text"
									/>
								</div>

								<!-- Attendee Club Affiliation -->
								<div class="pb_rows">
									<label class="pb_lbl_left attendee_club_lbl"
									       id="pb_lbl_attendee_club_2"
									       for="pb_attendee_club_2">Club Affiliation:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_club_2"
									       name="pb_attendee_club_2"
									       type="text"
									/>
								</div>

								<!-- Attendee Cruise Choice -->
								<div class="pb_rows">
									<label class="pb_lbl_cruise pb_cruise_choice"
									       id="pb_attendee_2_cruise_lbl"
									       for="pb_attendee_cruise_2">
										Attending Captain's Castaway Cruise ( $<?php echo $pb_cruise_cost; ?>)
									</label>
									<input class="validate[required] pb_cruise_choice"
									       id="pb_attendee_cruise_2"
									       name="pb_attendee_cruise_2"
									       type="radio"
									       value="Y"
									>
									Yes

									<input class="validate[required] pb_cruise_choice"
									       id="pb_attendee_cruise_2"
									       name="pb_attendee_cruise_2"
									       type="radio"
									       value="N"
									>
									No
								</div>
							</div>
						</fieldset>

						<fieldset class="pb_reg_form" id="pb_Attend_3">
							<legend><span class="memb_legend">3rd Attendees</span></legend>
							<div id="pb_attendee_3">
								<div class="pb_rows" id="pb_attendee_3">

									<!-- Attendee First Name -->
									<label class="pb_lbl_left"
									       id="pb_lbl_attendee_fname_3"
									       for="pb_attendee_fname_3">First Name:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_fname_3"
									       name="pb_attendee_fname_3"
									       type="text"
									/>

									<!-- Attendee Last Name -->
									<label class="pb_lbl_right"
									       id="pb_lbl_attendee_lname_3"
									       for="pb_attendee_lname_3">Last Name:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_lname_3"
									       name="pb_attendee_lname_3"
									       type="text"
									/>
								</div>

								<!-- Attendee Club Affiliation -->
								<div class="pb_rows">
									<label class="pb_lbl_left attendee_club_lbl"
									       id="pb_lbl_attendee_club_3"
									       for="pb_attendee_club_3">Club Affiliation:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_club_3"
									       name="pb_attendee_club_3"
									       type="text"
									/>
								</div>

								<!-- Attendee Cruise Choice-->
								<div class="pb_rows">
									<label class="pb_lbl_cruise pb_cruise_choice"
									       id="pb_attendee_3_cruise_lbl"
									       for="pb_attendee_cruise_3">
										Attending Captain's Castaway Cruise ( $<?php echo $pb_cruise_cost; ?>)
									</label>
									<input class="validate[required] pb_cruise_choice"
									       id="pb_attendee_cruise_3"
									       name="pb_attendee_cruise_3"
									       type="radio"
									       value="Y"
									>
									Yes

									<input class="validate[required] pb_cruise_choice"
									       id="pb_attendee_cruise_3"
									       name="pb_attendee_cruise_3"
									       type="radio"
									       value="N"
									>
									No
								</div>
							</div>
						</fieldset>

						<fieldset class="pb_reg_form" id="pb_Attend_4">
							<legend><span class="memb_legend">4th Attendees</span></legend>
							<div id="pb_attendee_4">
								<div class="pb_rows" id="pb_attendee_4_name">

									<!-- Attendee First Name -->
									<label class="pb_lbl_left"
									       id="pb_lbl_attendee_fname_4"
									       for="pb_attendee_fname_4">First Name:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_fname_4"
									       name="pb_attendee_fname_4"
									       type="text"
									/>

									<!-- Attendee Last Name -->
									<label class="pb_lbl_right"
									       id="pb_lbl_attendee_lname_4"
									       for="pb_attendee_lname_4">Last Name:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_lname_4"
									       name="pb_attendee_lname_4"
									       type="text"
									/>
								</div>

								<!-- Attendee Club Affiliation -->
								<div class="pb_rows">
									<label class="pb_lbl_left attendee_club_lbl"
									       id="pb_lbl_attendee_club_4"
									       for="pb_attendee_club_4">Club Affiliation:
									</label>
									<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
									       data-prompt-position="bottomLeft"
									       id="pb_attendee_club_4"
									       name="pb_attendee_club_4"
									       type="text"
									/>
								</div>

								<!-- Attendee Cruise Choice -->
								<div class="pb_rows">
									<label class="pb_lbl_cruise pb_cruise_choice"
									       id="pb_attendee_4_cruise_lbl"
									       for="pb_attendee_cruise_4">
										Attending Captain's Castaway Cruise ( $<?php echo $pb_cruise_cost; ?>)
									</label>
									<input class="validate[required] pb_cruise_choice"
									       id="pb_attendee_cruise_4"
									       name="pb_attendee_cruise_4"
									       type="radio"
									       value="Y"
									>
									Yes

									<input class="validate[required] pb_cruise_choice"
									       id="pb_attendee_cruise_4"
									       name="pb_attendee_cruise_4"
									       type="radio"
									       value="N"
									>
									No
								</div>
							</div>
						</fieldset>

						<div class="spacer"></div>

						<div>
							<input class="ctxphc_button3 screen" id="submit" type="submit" name="submit" value="submit"/>
						</div>
					</form>
				</div> <!-- entry -->
			</div> <!-- post -->
			<?php
		endwhile;
		endif;
		?>
	</div> <!-- content -->
<?php //xdebug_stop_trace(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>