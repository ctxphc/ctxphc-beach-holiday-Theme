<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 8/16/2015
 * Time: 12:06 PM
 */

namespace CTXPHC\BeachHoliday\Classes;


class CLUB_Registration {
	public $defaults = array(
		'states' => array(),
	);

	public function __construct( $args ) {
		$args = wp_parse_args( $args, $this->defaults );
		foreach ( $args as $arg_key => $arg_val ) {
			$this->$arg_key = $arg_val;
		}
	}

	private function parc_args( $args ){
		$args = wp_parse_args( $args, $this->defaults );
		return $args;
	}


	private function get_pb_reg_classes() {

		if ( $this->pb_priv_reg ) {
			$pb_display_classes = array(
				'pb_memb_class'  => $pb_memb_class = 'pb_hidden',
				'pb_early_class' => $pb_early_class = 'pb_hidden',
				'pb_late_class'  => $pb_late_class = 'pb_hidden',
				'pb_priv_class'  => $pb_priv_class = 'pb_display',
			);
		} else if ( $this->pb_today >= $this->expiry && $this->pb_today <= $this->expiry2 ) {
			$pb_display_classes = array(
				'pb_memb_class'  => $pb_memb_class = 'pb_hidden',
				'pb_early_class' => $pb_early_class = 'pb_display',
				'pb_late_class'  => $pb_late_class = 'pb_hidden',
				'pb_priv_class'  => $pb_priv_class = 'pb_hidden',
			);
		} else if ( $this->pb_today > $this->expiry2 ) {
			$pb_display_classes = array(
				'pb_memb_class'  => $pb_memb_class = 'pb_hidden',
				'pb_early_class' => $pb_early_class = 'pb_hidden',
				'pb_late_class'  => $pb_late_class = 'pb_display',
				'pb_priv_class'  => $pb_priv_class = 'pb_hidden',
			);
		} else {
			$pb_display_classes = array(
				'pb_memb_class'  => $pb_memb_class = 'pb_display',
				'pb_early_class' => $pb_early_class = 'pb_hidden',
				'pb_late_class'  => $pb_late_class = 'pb_hidden',
				'pb_priv_class'  => $pb_priv_class = 'pb_hidden',
			);
		}

		return $pb_display_classes;
	}

	public function display_reg_form(
		$form_type, $pb_reg_user_id = null
	) {

		switch ( $form_type ) {
			case 'review':
				$this->render_reg_review_form( $pb_reg_user_id );
				break;
			case 'failed':
				$this->render_reg_failed_form();
				break;
			default:
				$this->render_reg_blank_form();
		}
	}

	public function render_pb_review_form(
		$pb_reg_user_id
	) {
		global $wpdb;

		$pb_reg_data = $wpdb->get_row( "SELECT * FROM ctxphc_pb_reg WHERE pbRegID = $pb_reg_user_id" );


		if ( strlen( $pb_reg_data->attendee_2 ) > 1 ) {
			$pb_display_attendee_2_class = 'pb_display';
		} else {
			$pb_display_attendee_2_class = 'pb_hidden';
		}

		if ( strlen( $pb_reg_data->attendee_3 ) > 1 ) {
			$pb_display_attendee_3_class = 'pb_display';
		} else {
			$pb_display_attendee_3_class = 'pb_hidden';
		}

		if ( strlen( $pb_reg_data->attendee_4 ) > 1 ) {
			$pb_display_attendee_4_class = 'pb_display';
		} else {
			$pb_display_attendee_4_class = 'pb_hidden';
		}

		?>

		<div>
			<h3>Thank you <?php echo "{$pb_reg_data->first_name}  {$pb_reg_data->last_name}"; ?>,</h3>

			<p>Your registration for the 2015 CTXPHC Pirate's Ball is almost complete!</p>

			<p> Please verify your information then pay through PayPal using a credit card of your choice .</p>

			<div class="spacer"></div>

			<form id="pbMembOnlyRegForm" name="regForm" method="post" action=""
			      onsubmit="return checkEmail(this);">

				<fieldset class="pb_reg_form" id=members_info>
					<legend><span class="memb_legend"> Your Information </span></legend>
					<div class="personal_info">
						<input type="hidden"
						       name="pb_user_id"
						       value="<?php echo $pb_reg_user_id; ?>"/>

						<div class="pb_rows" id="personal_info">
							<label class="pb_lbl_left"
							       id="lbl_pb_fname"
							       for="pb_fname"> First Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
							       data - prompt - position="bottomLeft"
							       id="pb_fname"
							       type="text"
							       name="pb_fname"
							       value="<?php echo $pb_reg_data->first_name; ?>"
							       required
							/>
							<label class="pb_lbl_right"
							       id="lbl_pb_lname"
							       for="pb_lname"> Last Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data - prompt - position="bottomLeft"
							       id="pb_lname"
							       type="text"
							       name="pb_lname"
							       value="<?php echo $pb_reg_data->last_name; ?>"
							       required
							/>
						</div>

						<div class="pb_rows" id="div_pb_email">
							<label class="pb_lbl_left"
							       id="lbl_pb_email"
							       for="pb_email"> Email:
							</label>
							<input class="validate[required, custom[email]] pb_lbl_left"
							       data - prompt - position="bottomLeft"
							       id="pb_email"
							       name="pb_email"
							       type="text"
							       value="<?php echo $pb_reg_data->email; ?>"
							       required
							/>

							<label class="pb_lbl_right"
							       id="lbl_pb_email_verify"
							       for="pb_email_verify"> Verify Email:
							</label>
							<input class="validate[required, custom[email]] pb_input_right"
							       data - prompt - position="bottomLeft"
							       id="pb_email_verify"
							       name="pb_email_verify"
							       type="text"
							       value="<?php echo $pb_reg_data->email; ?>"
							       required
							/>
						</div>

						<div class="pb_rows" id="div_pb_phone">
							<label class="pb_lbl_left"
							       id="lbl_pb_phone"
							       for="pb_phone"> Phone:
							</label>
							<input class="validate[required, custom[phone]] pb_input_left"
							       data - prompt - position="bottomLeft"
							       id="pb_phone"
							       name="pb_phone"
							       type="tel"
							       value="<?php echo formatPhoneNumber( $pb_reg_data->phone ); ?>"
							       required
							/>

							<label class="pb_lbl_right"
							       id="lbl_pb_club"
							       for="pb_club"> Club Affiliation:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data - prompt - position="bottomLeft"
							       id="pb_club"
							       name="pb_club"
							       type="text"
							       value="<?php echo $pb_reg_data->club_aff; ?>"
							       required
							/>
						</div>

						<div class="pb_rows" id="pb_shirt">
							<label class="pb_shirt"
							       id="pb_lbl_tshirt"
							       for="pb_t-shirt"> T - Shirt Size:
							</label>
							<select class="validate[required] pb_input_left"
							        id="pb_t-shirt"
							        name="pb_shirt_size">
								<?php $defSel = $pb_reg_data->shirt_size;
								echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
							</select>
						</div>
					</div>
				</fieldset>

				<div class='spacer'></div>

				<fieldset class="pb_reg_form" id="pb_Attend_Info">
					<legend><span class="memb_legend">Additional Attendees</span></legend>
					<div class="attendee_count" id="pb_attendee_count">
						<input class="pb_attendeeCount"
						       data-prompt-position="bottomLeft"
						       id="pb_attendee_count_2"
						       type="radio"
						       name="attendee_count"
						       value="2" <?php if ( $pb_reg_data->quantity == 2 ) {
							echo 'checked';
						} ?>
						       required
						/>
						<label class="pb_attendeeCount"
						       for="pb_attendee_count_2">2 Attendees
							$<?php echo $this->pb_reg_cost * 2; ?>
						</label>

						<input class="pb_attendeeCount"
						       data-prompt-position="bottomLeft"
						       id="pb_attendee_count_3"
						       type="radio"
						       name="attendee_count"
						       value="3" <?php if ( $pb_reg_data->quantity == 3 ) {
							echo 'checked';
						} ?>
						       required
						/>
						<label class="pb_attendeeCount"
						       for="pb_attendee_count_3">3 Attendees
							$<?php echo $this->pb_reg_cost * 3; ?>
						</label>

						<input class="pb_attendeeCount"
						       data-prompt-position="bottomLeft"
						       id="pb_attendee_count_4"
						       type="radio"
						       name="attendee_count"
						       value="4" <?php if ( $pb_reg_data->quantity == 4 ) {
							echo 'checked';
						} ?>
						/>
						<label class="pb_attendeeCount"
						       for="pb_attendee_count_4">4 Attendees:
							$<?php echo $this->pb_reg_cost * 4; ?>
						</label>
					</div>

					<div class="pb_reg_attendee <?php echo $pb_display_attendee_2_class; ?>" id="pb_attendee_2">
						<?php if ( isset( $pb_reg_data->attendee_2 ) ) {
							$names = preg_split( '/\s+/', $pb_reg_data->attendee_2 );
						} ?>
						<div class="pb_rows">
							<label class="pb_lbl_left"
							       id="pb_lbl_attendee_fname_2"
							       for="pb_attendee_fname_2">First Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_fname_2"
							       name="pb_attendee_fname_2"
							       type="text"
							       value="<?php echo $names[ 0 ]; ?>"
							/>

							<label class="pb_lbl_right"
							       id="pb_lbl_attendee_lname_2"
							       for="pb_attendee_lname_2">Last Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_lname_2"
							       name="pb_attendee_lname_2"
							       type="text"
							       value="<?php echo $names[ 1 ]; ?>"
							/>
						</div>
						<div class="pb_rows">
							<label class="pb_lbl_left"
							       id="pb_lbl_attendee_shirt_2"
							       for="pb_attendee_shirt_2">T-Shirt Size:
							</label>
							<select class="validate[required] pb_input_left"
							        id="pb_attendee_shirt_2"
							        name="pb_attendee_shirt_size_2">
								<?php $defSel = $pb_reg_data->attendee_shirt_size_2;
								echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
							</select>

							<label class="pb_lbl_right attendee_club_lbl"
							       id="pb_lbl_attendee_club_2"
							       for="pb_attendee_club_2">Club Affiliation:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_club_2"
							       name="pb_attendee_club_2"
							       type="text"
							       value="<?php echo $pb_reg_data->attendee_club_2; ?>"/>
						</div>
					</div>
					<div class="pb_reg_attendee <?php echo $pb_display_attendee_3_class; ?>" id="pb_attendee_3">
						<?php if ( isset( $pb_reg_data->attendee_3 ) ) {
							$names = preg_split( '/\s+/', $pb_reg_data->attendee_3 );
						} ?>
						<div class="pb_rows">
							<label class="pb_lbl_left"
							       id="pb_attendee_fname_3"
							       for="pb_attendee_fname_3">First Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_fname_3"
							       name="pb_attendee_fname_3"
							       type="text"
							       value="<?php echo $names[ 0 ]; ?>"/>

							<label class="pb_lbl_right"
							       id="pb_attendee_lname_3"
							       for="pb_attendee_lname_3">Last Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_lname_3"
							       name="pb_attendee_lname_3"
							       type="text"
							       value="<?php echo $names[ 1 ]; ?>"/>
						</div>

						<div class="pb_rows">
							<label class="pb_lbl_left"
							       id="pb_lbl_attendee_shirt_3"
							       for="pb_attendee_shirt_3">T-Shirt Size:
							</label>
							<select class="validate[required] pb_attendee_shirt pb_input_left"
							        id="pb_attendee_shirt_3"
							        name="pb_attendee_shirt_size_3">
								<?php $defSel = $pb_reg_data->attendee_shirt_size_3;
								echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
							</select>

							<label class="pb_lbl_right attendee_club_lbl"
							       id="pb_lbl_attendee_club_3"
							       for="pb_attendee_club_3">Club Affiliation:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_club_3"
							       name="pb_attendee_club_3"
							       type="text"
							       value="<?php echo $pb_reg_data->attendee_club_3; ?>"
							/>
						</div>
					</div>

					<div class="pb_reg_attendee <?php echo $pb_display_attendee_4_class; ?>" id="pb_attendee_4">
						<?php if ( isset( $pb_reg_data->attendee_4 ) ) {
							$names = preg_split( '/\s+/', $pb_reg_data->attendee_4 );
						} ?>
						<div class="pb_rows">
							<label class="pb_lbl_left"
							       id="pb_attendee_fname_4"
							       for="pb_attendee_fname_4">First Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
							       data-prompt-position="bottomLeft"
							       value="<?php echo $names[ 0 ]; ?>"
							       id="pb_attendee_fname_4"
							       name="pb_attendee_fname_4"
							       type="text"
							/>

							<label class="pb_lbl_right"
							       id="pb_attendee_lname_4"
							       for="pb_attendee_lname_4">Last Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       value="<?php echo $names[ 1 ]; ?>"
							       id="pb_attendee_lname_4"
							       name="pb_attendee_lname_4"
							       type="text"
							/>
						</div>
						<div class="pb_rows">
							<label class="pb_lbl_left"
							       id="pb_lbl_attendee_shirt_4"
							       for="pb_attendee_shirt_4">T-Shirt Size:
							</label>
							<select class="validate[required] pb_attendee_shirt pb_input_left"
							        id="pb_attendee_shirt_4"
							        name="pb_attendee_shirt_size_4">
								<?php $defSel = $pb_reg_data->attendee_shirt_size_4;;
								echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
							</select>

							<label class="pb_lbl_right attendee_club_lbl"
							       id="pb_lbl_attendee_club_4"
							       for="pb_attendee_club_4">Club Affiliation:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_attendee_club_4"
							       name="pb_attendee_club_4"
							       type="text"
							       value="<?php echo $pb_reg_data->attendee_club_4; ?>"
							/>
						</div>
					</div>
				</fieldset>

				<div class="spacer"></div>

				<div>
					<!--<input class="PB_Reg_button" id="update" type=submit name="update" value="update" />-->
				</div>

			</form>

			<!-- Pirate's Ball CTXPHC Members Only Registration PayPal Button -->
			<form class="<?php echo $this->pb_reg_classes[ 'pb_memb_class' ]; ?>" action="https://www.paypal.com/cgi-bin/webscr" method="post"
			      target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="VDW65WDHYXXYJ">
				<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>
				<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>
				<input class="paypal_input" type="image"
				       src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif"
				       name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img class="paypal_button" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
				     width="1" height="1">
			</form>


			<!--  Pirate's Ball Early Registration PayPal Button -->
			<form class="<?php echo $this->pb_reg_classes[ 'pb_early_class' ]; ?>" action="https://www.paypal.com/cgi-bin/webscr" method="post"
			      target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="4PH2DEAAH4LD8">
				<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>
				<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0"
				       name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img class="paypal_button" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
				     width="1" height="1">
			</form>


			<!-- Pirate's Ball Late Registration PayPal Button -->

			<form class="<?php echo $this->pb_reg_classes[ 'pb_late_class' ]; ?>" action="https://www.paypal.com/cgi-bin/webscr" method="post"
			      target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="DU9MPK4H5L3ZQ">
				<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>
				<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0"
				       name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img class="paypal_button" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
				     width="1" height="1">
			</form>

			<!-- Pirate's Ball Private Registration PayPal Button -->

			<form class="<?php echo $this->pb_reg_classes[ 'pb_priv_class' ]; ?>" action="https://www.paypal.com/cgi-bin/webscr" method="post"
			      target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="5YCZ8AV3GT83S">
				<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>
				<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0"
				       name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>

		</div>

		<div class="spacer"></div>
		<?php
	}

	public function render_pb_failed_form() {
		?>
		<div>
			<h3>There was a problem with Pirate's Ball Registration!!!!!</h3>

			Your registration includes:
			<ul>
				<li><span class="pb_bold">Friday's</span> Welcome Aboard Party with <a
						href="http://www.thedetentions.com/">The Detentions!</a>.
				</li>
				<li><span class="pb_bold">Saturday Afternoon's</span> Walk The Plank Pool Party with live music
					by <a href="">TBA</a>.
				</li>
				<li><span class="pb_bold">Saturday Night's</span> Pirate's Ball with <a
						href="http://donnybrewer.com/">Donny Brewer and The Dock Rockers</a> and two free drink
					tickets!
				</li>
				<li><span class="pb_bold">Sunday's</span>: SUNDAY SEND OFF Music and Breakfast Tacos by the Pool!
				</li>
			</ul>

			<p>We look forward to seeing you and celebrating another wonderful CTXPHC Pirate's Ball!
		</div>

		<div class="<?php echo $this->pb_reg_classes[ 'pb_memb_class' ]; ?>" id="memb_reg_cost">
			<h4>CTXPHC Members only early registration cost: $<?php echo $this->pb_memb_cost; ?> per person</h4>
			After June 30th Early Registration cost: $<?php echo $this->pb_early_cost; ?> per person.
			After July 31st Registration cost: $<?php echo $this->pb_cost; ?> pre person.

			<p class="pb_center">
				<a href="https://www.ctxphc.com/pirates-ball-details/">
					Click here for additional event and hotel information!
				</a>
			</p>
		</div>
		<div class="<?php echo $this->pb_reg_classes[ 'pb_early_class' ]; ?>" id="early_reg_cost">
			<h4>CTXPHC Early registration cost: $<?php echo $this->pb_early_cost; ?> per person</h4>
			Registration is $<?php echo $this->pb_cost; ?> after July 31 and at the door.

			<p class="pb_center">
				<a href="https://www.ctxphc.com/pirates-ball-details/">
					Click here for additional event and hotel information!
				</a>
			</p>
		</div>
		<div class="<?php echo $this->pb_reg_classes[ 'pb_late_class' ]; ?>" id="late_reg_cost">
			<h4>CTXPHC Registration cost: $<?php echo $this->pb_cost; ?> per person</h4>

			<p class="pb_center">
				<a href="https://www.ctxphc.com/pirates-ball-details/">
					Click here for additional event and hotel information!
				</a>
			</p>
		</div>

		<div class="spacer"></div>
		<?php
	}

	public function render_pb_blank_form() {
		?>
		<div class="spacer"></div>

		<form class="memb_reg_form" id="regForm" name="regForm" method="post"
		      action="<?php bloginfo( 'url' ); ?>/registration-review/">
			<input type="hidden" name="mb_relationship" value=1/>
			<fieldset class="reg_form" id="memb_type">
				<legend><span class="memb_legend">Membership Options</span></legend>
				<div class="memb_type" id="memb_type_div">
					<!-- Individual Member Option -->
					<input class="memb_type" id="memb_type_1" type="radio" name="memb_type" value="1" checked/>
					<label class="memb_type" for="memb_type_1">Individual $<?php echo $memb_costs[ 1 ]->cost; ?></label>

					<!-- Individual + Child(ren) Member Option -->
					<input class="memb_type" id="memb_type_2" type="radio" name="memb_type" value="2"/>
					<label class="memb_type" for="memb_type_2">Individual + Children
						$<?php echo $memb_costs[ 2 ]->cost; ?></label>

					<!-- Couple Member Option -->
					<input class="memb_type" id="memb_type_3" type="radio" name="memb_type" value="3"/>
					<label class="memb_type" for="memb_type_3">Couple $<?php echo $memb_costs[ 3 ]->cost; ?></label>

					<!-- Household Member Option -->
					<input class="memb_type" id="memb_type_4" type="radio" name="memb_type" value="4"/>
					<label class="memb_type" for="memb_type_4">Household $<?php echo $memb_costs[ 4 ]->cost; ?></label>
				</div>
			</fieldset>

			<div class="spacer"></div>

			<fieldset class="reg_form" id="personal_info">
				<legend><span class="memb_legend">Your Information</span></legend>
				<div class="reg_form_row" id="personal_info_div">
					<label class="reg_first_name" id="lbl_mb_first_name" for="mb_first_name">First Name:</label>
					<input class="reg_first_name validate[required, custom[onlyLetterSp]]"
					       data-prompt-position="bottomLeft" id="mb_fast_name" name="mb_first_name" type="text"
					       value="" title="first_name"/>

					<label class="reg_last_name" id="lbl_mb_last_name" for="mb_last_name">Last Name:</label>
					<input class="reg_last_name validate[required, custom[onlyLetterSp]]"
					       data-prompt-position="bottomLeft" id="mb_last_name" name="mb_last_name" type="text"
					       value=""/>
				</div>
				<div class="reg_form_row">
					<label class="cm_birthday" id="lbl_mb_birthday" for="mb_birthday">Birthdate:</label>
					<input class="cm_birthday validate[required, custom[onlyNumber]]"
					       data-prompt-position="bottomLeft" id="mb_birthday" name="mb_birthday" type="date" value=""/>

					<label class="reg_email" id="lbl_mb_email" for="mb_email">Email:</label>
					<input class="reg_email validate[required, custom[email]]" data-prompt-position="bottomLeft"
					       id="mb_email" name="mb_email" type="email" value=""/>
				</div>
				<div class="reg_form_row">
					<label class="reg_phone" id="lbl_mb_phone" for="mb_phone">Phone:</label>
					<input class="reg_phone validate[required, custom[onlyNumber]]"
					       data-prompt-position="bottomLeft" id="mb_phone" name="mb_phone" type="tel"/>

					<label id="lbl_mb_occupation" for="mb_occupation">Occupation:</label>
					<input class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft"
					       id="mb_occupation" name="mb_occupation" type="text" value=""/>
				</div>
			</fieldset>

			<div class="spacer"></div>

			<fieldset class="reg_form" id="mb_address">
				<legend><span class="memb_legend">Address</span></legend>
				<div class="reg_form_row">
					<label id="lbl_mb_addr1" for="mb_addr1">Address:</label>
					<input class="validate[required, custom[address]]" data-prompt-position="bottomLeft"
					       id="mb_addr1" name="mb_addr1" type="text" value=""/>

					<label id="lbl_mb_addr2" for="mb_addr2">Suite/Apt:</label>
					<input class="validate[custom[onlyLetterNumber]]" data-prompt-position="bottomLeft"
					       id="mb_addr2" name="mb_addr2" type="text" value=""/>
				</div>
				<div class="reg_form_row">
					<label id="lbl_mb_city" for="mb_city">City:</label>
					<input class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft"
					       id="mb_city" name="mb_city" type="text" value=""/>

					<label id="lbl_mb_state" for="mb_state">State:</label>
					<select class="validate[required]" id="mb_state" name="mb_state">
						<?php $defSel = 'TX';
						echo showOptionsDrop( $states_arr, $defSel, true ); ?>
					</select>

					<label id="lbl_mb_zip" for="mb_zip">Zip:</label>
					<input id="mb_zip" class="validate[required, custom[zip-code]]"
					       data-prompt-position="bottomLeft" name="mb_zip" type="text" value=""/>
				</div>
			</fieldset>

			<div class="spacer" id="spouse_spacer"></div>

			<fieldset class="reg_form" id="spouse_info">
				<legend><span class="memb_legend">Spouse/Partner</span></legend>
				<div class="reg_form_row">
					<label class="reg_first_name" id="lbl_sp_first_name" for="sp_first_name">First Name:</label>
					<input class="reg_first_name validate[required, custom[onlyLetterSp]]"
					       data-prompt-position="bottomLeft" id="sp_first_name" name="sp_first_name" type="text"
					       value=""/>

					<label class="reg_last_name" id="lbl_sp_last_name" for="sp_last_name">Last Name:</label>
					<input class="reg_last_name validate[required, custom[onlyLetterSp]]"
					       data-prompt-position="bottomLeft" id="sp_last_name" name="sp_last_name" type="text"
					       value=""/>
				</div>
				<div class="reg_form_row">
					<label class="cm_birthday" id="lbl_sp_birthday" for="sp_birthday">Birthdate:</label>
					<input class="cm_birthday validate[required, custom[onlyNumber]]" id="sp_birthday"
					       data-prompt-position="bottomLeft" name="sp_birthday" type="date"/>

					<label class="reg_email" id="lbl_sp_email" for="sp_email">Email:</label>
					<input class="reg_email validate[custom[email]]" data-prompt-position="bottomLeft"
					       id="sp_email" name="sp_email" type="email" value=""/>
				</div>
				<div class="reg_form_row">
					<label class="reg_phone" id="lbl_sp_phone" for="sp_phone">Phone:</label>
					<input class="reg_phone validate[custom[onlyNumber]]" data-prompt-position="bottomLeft"
					       id="sp_phone" name="sp_phone" type="tel" value=""/>

					<label class="sp_relationship" id="lbl_sp_relationship"
					       for="sp_relationship">Relationship:</label>
					<select class="sp_relationship validate[required]"
					        id="sp_relationship" name="sp_relationship">
						<?php $defSel = 2 ?>
						<?php echo showOptionsDrop( $relationship_arr, $defSel, true ); ?>
					</select>

				</div>
			</fieldset>

			<div class="spacer" id="family_spacer"></div>
			<!--    BEGIN 1ST FAMILY MEMBER  -->

			<fieldset class="reg_form" id="family_info">
				<legend><span class="memb_legend">Family Members</span></legend>
				<section id="child1">
					<div class="reg_form_row">
						<label class="reg_first_name" for="c1_first_name">First Name:</label>
						<input class="reg_first_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c1_first_name" name="c1_first_name" type="text"
						       value=""/>

						<label class="reg_last_name" for="c1_last_name">Last Name:</label>
						<input class="reg_last_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c1_last_name" name="c1_last_name" type="text"
						       value=""/></div>
					<div class="reg_form_row">
						<label class="cm_birthday" id="lbl_c_birthday" for="c1_birthday">Birthdate:</label>
						<input class="reg_birth_month validate[custom[onlyNumber]]"
						       data-prompt-position="bottomLeft" id="c1_birthday" name="c1_birthday" type="date"/>

						<label class="child_relationship" id="lbl_c1_relationship"
						       for="c1_relationship">Relationship:</label>
						<select class="child_relationship" id="c1_relationship" name="c1_relationship">
							<?php $defSel = 4 ?>
							<?php echo showOptionsDrop( $relationship_arr, $defSel, true ); ?>
						</select>
					</div>
					<div class="reg_form_row">
						<label class="child_email" id="lbl_c1_email" for="c1_email">Email:</label>
						<input class="child_email validate[custom[email]]" data-prompt-position="bottomLeft"
						       id="c1_email" name="c1_email" type="email" value=""/>
					</div>
				</section>
				<!--  //END of CHILD1 -->

				<div class="spacer"></div>
				<!--    BEGIN 2ND FAMILY MEMBER  -->

				<section id="child2">
					<div class="reg_form_row">
						<label class="reg_first_name" id="lbl_c2_first_name" for="c2_first_name">First Name:</label>
						<input class="reg_first_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c2_first_name" name="c2_first_name" type="text"
						       value=""/>

						<label class="reg_last_name" id="lbl_c2_last_name" for="c2_last_name">Last Name:</label>
						<input class="reg_last_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c2_last_name" name="c2_last_name" type="text"
						       value=""/>
					</div>
					<div class="reg_form_row">
						<label class="cm_birthday" id="lbl_c2_birthday" for="c2_birthday">Birthdate:</label>
						<input class="cm_birthday" id="c2_birthday" name="c2_birthday" type="date"/>

						<label class="child_relationship" id="lbl_c2_relationship"
						       for="c2_relationship">Relationship:</label>
						<select class="child_relationship" id="c2_relationship" name="c2_relationship">
							<?php $defSel = 4; ?>
							<?php echo showOptionsDrop( $relationship_arr, $defSel, true ); ?>
						</select>
					</div>
					<div class="reg_form_row">
						<label class="child_email" id="lbl_c2_email" for="c2_email">Email:</label>
						<input class="child_email validate[custom[email]]" data-prompt-position="bottomLeft"
						       id="c2_email" name="c2_email" type="email" value=""/>
					</div>
				</section>
				<!--  //END CHILD2 -->

				<div class="spacer"></div>
				<!--    BEGIN 3RD FAMILY MEMBER  -->

				<section id="child3">
					<div class="reg_form_row">
						<label class="reg_first_name" id="lbl_c3_first_name" for="c3_first_name">First Name:</label>
						<input class="reg_first_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c3_first_name" name="c3_first_name" type="text"
						       value=""/>

						<label class="reg_last_name" id="lbl_c3_last_name" for="c3_last_name">Last Name:</label>
						<input class="reg_last_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c3_last_name" name="c3_last_name" type="text"
						       value=""/>
					</div>
					<div class="reg_form_row">
						<label class="cm_birthday" id="lbl_c3_birthday" for="c3_birthday">Birthdate:</label>
						<input class="cm_birthday" id="c3_birthday" name="c3_birthday" type="date"/>

						<label class="child_relationship" id="lbl_c3_relationship" for="c3_relationship">Relationship:</label>
						<select class="child_relationship" id="c3_relationship" name="c3_relationship">
							<?php $defSel = 4; ?>
							<?php echo showOptionsDrop( $relationship_arr, $defSel, true ); ?>
						</select>
					</div>
					<div class="reg_form_row">
						<label class="child_email" id="lbl_c3_email" for="c3_email">Email:</label>
						<input class="child_email validate[custom[email]]" data-prompt-position="bottomLeft"
						       id="c3_email" name="c3_email" type="email" value=""/>
					</div>
				</section>
				<!--  //END of CHILD3  -->

				<div class="spacer"></div>
				<!--    BEGIN 4th FAMILY MEMBER  -->

				<section id="child4">
					<div class="reg_form_row">
						<label class="reg_first_name" id="lbl_c4_first_name" for="c4_first_name">First Name:</label>
						<input class="reg_first_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c4_first_name" name="c4_first_name" type="text"
						       value=""/>

						<label class="reg_last_name" id="lbl_c4_last_name" for="c4_last_name">Last Name:</label>
						<input class="reg_last_name validate[custom[onlyLetterSp]]"
						       data-prompt-position="bottomLeft" id="c4_last_name" name="c4_last_name" type="text"
						       value=""/>
					</div>
					<div class="reg_form_row">
						<label class="cm_birthday" id="lbl_c4_birthday" for="c4_birthday">Birthdate:</label>
						<input class="cm_birthday" id="c4_birthday" name="c4_birthday" type="date"/>

						<label class="child_relationship" id="lbl_c4_relationship"
						       for="c4_relationship">Relationship:</label>
						<select class="child_relationship" id="c4_relationship" name="c4_relationship">
							<?php $defSel = 4; ?>
							<?php echo showOptionsDrop( $relationship_arr, $defSel, true ); ?>
						</select>
					</div>
					<div class="reg_form_row">
						<label class="child_email" id="lbl_c4_email" for="c4_email">Email:</label>
						<input class="child_email validate[custom[email]]" data-prompt-position="bottomLeft"
						       id="c4_email" name="c4_email" type="email" value=""/>
					</div>
				</section>
				<!--  //END of CHILD3  -->
			</fieldset>

			<div class="spacer"></div>

			<div>
				<input class="ctxphc_button3 screen" id="reg_submit" type="submit" name="registration"
				       value="Submit"/>
			</div>

		</form>
		<?php
	}

	public function load_user_data(
		$user_post_data
	) {
		$attendee_count = intval( $user_post_data[ 'attendee_count' ] );
		foreach ( $user_post_data as $u_key => $u_value ) {
			switch ( $u_key ) {
				case ( $u_key == 'pb_fname' ):
					$pb_reg_data[ 'first_name' ] = $u_value;
					break;
				case ( $u_key == 'pb_lname' ):
					$pb_reg_data[ 'last_name' ] = $u_value;
					break;
				case ( $u_key == 'pb_email' ):
					$user = get_user_by( 'email', $u_value );
					if ( ! $user ) {
						$to      = "support@ctxphc.com";
						$subject = "PB Member's Only Registration Issue!";
						$body    = "{$pb_reg_data['first_name']} {$pb_reg_data['last_name']} email address didn't have a match in the wordpress users on in family members tables.  Review is needed!!! \n\n";
						mail( $to, $subject, $body );
					}
					$pb_reg_data[ 'email' ] = $u_value;
					break;
				case ( strpos( $u_key, 'pb_phone' ) ):
					$pb_reg_data[ 'phone' ] = format_save_phone( $u_value );
					break;
				case ( strpos( $u_key, 'pb_shirt_size' ) ):
					$pb_reg_data[ 'shirt_size' ] = $u_value;
					break;
				case ( strpos( $u_key, 'pb_club' ) );
					$pb_reg_data[ 'club_aff' ] = $u_value;
					break;
				case ( $u_key == 'attendee_count' ):
					$pb_reg_data[ 'quantity' ] = intval( $u_value );
					$pb_reg_data[ 'amount' ]   = intval( $this->pb_reg_cost * $u_value );
					break;
				default:
					if ( $attendee_count >= 2 ) {
						switch ( $u_key ) {
							case ( strpos( $u_key, 'pb_attendee_fname' ) ):
								$attendee_name = $u_value;
								break;
							case ( strpos( $u_key, 'pb_attendee_lname' ) ):
								$this->pb_attend_count ++;
								$attendee_name .= ' ' . $u_value;
								$pbkey                 = 'attendee_' . $this->pb_attend_count;
								$pb_reg_data[ $pbkey ] = $attendee_name;
								break;
							case ( strpos( $u_key, 'pb_attendee_shirt_size' ) ):
								$this->pb_attend_shirt_count ++;
								$pb_attend_shirt_key                 = 'attendee_shirt_size_' . $this->pb_attend_shirt_count;
								$pb_reg_data[ $pb_attend_shirt_key ] = $u_value;
								break;
							case ( strpos( $u_key, 'pb_attendee_club' ) );
								$this->pb_attend_club_count ++;
								$pb_club_key                 = 'attendee_club_' . $this->pb_attend_club_count;
								$pb_reg_data[ $pb_club_key ] = $u_value;
								break;
						}
					}
			}
		}

		return $pb_reg_data;
	}

	/**
	 * @param $table
	 * @param $pb_reg_data
	 *
	 * @return bool|int
	 */
	public function pb_data_insert(
		$table, $pb_reg_data
	) {
		global $wpdb;

		$pb_insert_results = $wpdb->insert( $table, $pb_reg_data );
		//$wpdb->print_error();

		if ( $pb_insert_results ) {
			$pb_reg_recID = $wpdb->insert_id;

			return $pb_reg_recID;
		} else {
			return false;
		}
	}

	function pb_data_update( $pbTable, $pbRegID, $pb_updateData ) {
		global $wpdb;

		$pbWhere = array( 'pbRegID' => $pbRegID );

		$updated = $wpdb->update( $pbTable, $pb_updateData, $pbWhere );  //Updates PB registration data in to DB.

		If ( $updated === false ) {  //Update of PB Registration data failed.  Alert end user and support!
			//use email to alert CTXPHC support of failed db update.

			//todo: update support@ctxphc.com that update of pb reg data failed.

			$to      = "ctxphc_test1@localhost.com";
			$subject = "PB Registration Update FAILED!";
			$body    = "There was an error when trying to update the DB entry for $pbRegID \n\n";

			mail( $to, $subject, $body );

			$pbRegData = false;
		} else {
			$pbRegData = $wpdb->get_row( "SELECT * from ctxphc_pb_reg where pbRegID = {$pbRegID}" );
			//todo: Complete PB Registration
		}

		return $pbRegData;
	}

	private function get_reg_cost() {
		if ( isset( $this->pb_priv_reg ) ) {
			$pb_reg_cost = $this->pb_memb_cost;
		} else if ( $this->pb_today >= $this->expiry && $this->pb_today <= $this->expiry2 ) {
			$pb_reg_cost = $this->pb_early_cost;
		} else if ( $this->pb_today > $this->expiry2 ) {
			$pb_reg_cost = $this->pb_cost;
		} else {
			$pb_reg_cost = $this->pb_memb_cost;
		}

		return $pb_reg_cost;
	}
}
