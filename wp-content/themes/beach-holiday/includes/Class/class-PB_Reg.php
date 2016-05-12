<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/17/2015
 * Time: 7:36 PM
 */

namespace CTXPHC\BeachHoliday\Classes;


class PB_Reg {

	var $pb_memb_reg_type = 'member';
	var $pb_open_reg_type = 'open';
	var $pb_reg_reg_type = 'registration';
	var $pb_comp_reg_type = 'complimentary';
	var $pb_reg_type = null;
	var $attendee_count;
	var $pb_attend_count;
	var $pb_attend_club_count;
	var $pb_cost;
	var $pb_open_cost;
	var $pb_memb_cost;
	var $pb_reg_cost;
	var $pb_reg_total;
	var $pb_reg_text = array();
	var $item_name;
	var $item_num;
	var $tAmount;
	var $quantity;
	var $states;

	var $pb_cruise_cost;
	var $pb_cruise_count;

	public $defaults = array(
		'attendee_count'       => 1,
		'pb_attend_count'      => 1,
		'pb_attend_club_count' => 1,
		'pb_cruise_count'      => 0,
		'pb_cost'              => 0,
		'pb_open_cost'         => 0,
		'pb_memb_cost'         => 0,
		'pb_cruise_cost'       => 0,
		'pb_reg_cost'          => 0,
		'pb_reg_total'         => 0,
		'pb_reg_req_type'      => null,
		'item_name'            => '',
		'item_num'             => '',
		'tAmount'              => '',
		'quantity'             => 1,
		'states'               => array(),
	);
	public $pb_today;
	public $expiry;
	public $pb_reg_req_type;
	public $expiry2;
	public $pb_submit_button;
	public $pb_member_reg_cost_text;
	public $pb_open_reg_cost_next_text;
	public $pb_reg_reg_cost_next_text;
	public $pb_member_reg_head_text;
	public $pb_open_reg_head_text;
	public $pb_reg_reg_head_text;
	public $pb_reg_reg_cost_text;
	public $pb_title_text;
	public $pb_attend_shirt_count;
	public $pb_attendee_cruise_count;
	public $pb_submit_button_array;
	public $attending_cruise_count;


	/**
	 * PB_Reg constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {

		$args = wp_parse_args( $args, $this->defaults );
		foreach ( $args as $arg_key => $arg_val ) {
			$this->$arg_key = $arg_val;
		}

		//$this->pb_shirt_sizes = $this->pb_load_sizes();
		$this->pb_reg_types = array(
			'member'        => $this->pb_memb_reg_type,
			'open'          => $this->pb_open_reg_type,
			'registration'  => $this->pb_reg_reg_type,
			'complimentary' => $this->pb_comp_reg_type,
		);

		$this->pb_reg_type         = $this->pb_reg_types[ $this->pb_memb_reg_type ];
		$this->pb_reg_form         = $this->form_type;
		$this->pb_reg_text         = $this->get_pb_reg_display_text();
		$this->pb_reg_cost         = $this->get_pb_reg_cost();
		$this->pb_submit_button    = $this->get_submit_button_options();
		$this->pb_submit_pp_button = $this->get_paypal_button();

	}

	private function get_pb_reg_display_text() {

		$pb_reg_text[ 'pb_title_text' ] = $this->pb_title_text;

		if ( isset( $this->pb_memb_reg_type ) ) {
			switch ( $this->pb_reg_type ) {
				case 'member':
				case 'complimentary':
					$pb_reg_text[ 'pb_reg_head_text' ]   = $this->pb_member_reg_head_text;
					$pb_reg_text[ 'pb_reg_cost_text' ]   = $this->pb_member_reg_cost_text;
					$pb_reg_text[ 'pb_reg_cost_text_A' ] = $this->pb_open_reg_cost_next_text;
					$pb_reg_text[ 'pb_reg_cost_text_B' ] = $this->pb_reg_reg_cost_next_text;
					break;
				case 'open':
					$pb_reg_text[ 'pb_reg_head_text' ]        = $this->pb_open_reg_head_text;
					$pb_reg_text[ 'pb_reg_cost_text' ]        = $this->pb_open_reg_cost_next_text;
					$pb_reg_text[ 'pb_reg_cost_next_text_A' ] = $this->pb_reg_reg_cost_next_text;
					break;
				case 'registration':
					$pb_reg_text[ 'pb_reg_head_text' ] = $this->pb_reg_reg_head_text;
					$pb_reg_text[ 'pb_reg_cost_text' ] = $this->pb_reg_reg_cost_text;
					break;
			}
		}

		return $pb_reg_text;
	}

	private function get_pb_reg_cost() {
		if ( isset( $this->pb_reg_type ) ) {
			$pb_reg_cost = $this->pb_memb_cost;
		} else if ( $this->pb_today >= $this->expiry && $this->pb_today <= $this->expiry2 ) {
			$pb_reg_cost = $this->pb_open_cost;
		} else {
			$pb_reg_cost = $this->pb_cost;
		}

		return $pb_reg_cost;
	}

	public function get_submit_button_options() {

		$this->pb_submit_button_array[ 'review' ] = '<input class="ctxphc_button3 screen" id="review" type="submit" name="submit" value="review" />';
		$this->pb_submit_button_array[ 'update' ] = '<input class="ctxphc_button3 screen" id="update" type="submit" name="submit" value="update" />';

		$this->pb_submit_button_array[ 'member_only_pp_button' ] = "<!-- Pirate's Ball CTXPHC Members Only Registration PayPal Button -->";
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<input type="hidden" name="cmd" value="_s-xclick">';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<input type="hidden" name="hosted_button_id" value="VDW65WDHYXXYJ">';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<input class="paypal_input" type="image"';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= ' src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif"';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= ' name="submit" alt="PayPal - The safer, easier way to pay online!">';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '<img class="paypal_button" alt="PayPal - The safer, easier way to pay online!"';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= ' src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">';
		$this->pb_submit_button_array[ 'member_only_pp_button' ] .= '</form>';

		$this->pb_submit_button_array[ 'open_reg_pp_button' ] = "<!--  Pirate's Ball Early Registration PayPal Button -->";
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<input type="hidden" name="cmd" value="_s-xclick">';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<input type="hidden" name="hosted_button_id" value="4PH2DEAAH4LD8">';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<input type="image"';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= 'src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit"';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= 'alt="PayPal - The safer, easier way to pay online!">';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '<img class="paypal_button" alt="" border="0"';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= 'src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= 'width="1" height="1">';
		$this->pb_submit_button_array[ 'open_reg_pp_button' ] .= '</form>';

		$this->pb_submit_button_array[ 'reg_pp_button' ] = "<!-- Pirate's Ball Late Registration PayPal Button -->";
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<input type="hidden" name="cmd" value="_s-xclick">';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<input type="hidden" name="hosted_button_id" value="DU9MPK4H5L3ZQ">';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif"';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= 'name="submit" alt="PayPal - The safer, easier way to pay online!">';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '<img class="paypal_button" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">';
		$this->pb_submit_button_array[ 'reg_pp_button' ] .= '</form>';

		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] = "<!-- Pirate's Ball Private Registration PayPal Button -->";
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<input type="hidden" name="cmd" value="_s-xclick">';
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<input type="hidden" name="hosted_button_id" value="5YCZ8AV3GT83S">';
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<input type="hidden" name="quantity" value="<?php echo $pb_reg_data->quantity; ?>"/>';
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<input type="hidden" name="custom" value="<?php echo $pb_reg_user_id; ?>"/>';
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">';
		$this->pb_submit_button_array[ 'comp_reg_pp_button' ] .= '<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"> </form>';

		return $this->pb_submit_button_array;
	}

	public function get_paypal_button() {

		if ( isset( $this->pb_reg_type ) ) {
			switch ( $this->pb_reg_type ) {
				case 'member':
				case 'complimentary':
					$pb_paypal_button = $this->pb_submit_button_array[ 'member_only_pp_button' ];
					break;
				case 'open':
					$pb_paypal_button = $this->pb_submit_button_array[ 'member_only_pp_button' ];
					break;
				case 'registration':
					$pb_paypal_button = $this->pb_submit_button_array[ 'member_only_pp_button' ];
					break;
			}
		}

		return $pb_paypal_button;
	}

	public function pb_load_sizes() {
		if ( $this->expiry2 < $this->pb_today ) {
			//@format:off
			$shirtsizes = array(
				'XL' => 'X-Large',
			);
		} else {
			$shirtsizes = array(
				'Size' => ' ',
				'SM'   => 'Small',
				'MD'   => 'Medium',
				'LG'   => 'Large',
				'XL'   => 'X-Large',
				'XXL'  => 'XX-Large',
			);
			//@format:on
		}

		return $shirtsizes;
	}

	public function display_pb_form( $pb_reg_form, $pb_user_id = null ) {

		switch ( $pb_reg_form ) {
			case 'review':
				$this->pb_submit_button = $this->pb_submit_button_array[ 'update' ];
				if ( ! empty( $pb_user_id ) ) {
					$this->render_pb_review_form( $pb_user_id );
				} else {
					//some kind of major error has occurred
				}
				break;
			case 'failed':
				$this->render_pb_failed_form();
				break;
			default:
				$this->pb_submit_button = $this->pb_submit_button_array[ 'review' ];
				$this->render_pb_blank_form();
		}
	}

	public function render_pb_review_form( $pb_reg_user_id ) {  // Display REVIEW form
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
			<h3>Thank you <?php echo "{$pb_reg_data->first_name}  {$pb_reg_data->last_name}"; ?>
				,</h3>

			<p>Your registration for the 2016 CTXPHC Pirate's Ball is almost complete!</p>

			<p> Please verify your information then pay through PayPal using a credit card of your
				choice .</p>

			<div class="spacer"></div>

			<form id="pbMembOnlyRegForm" name="regForm" method="post" action=""
			      onsubmit="return checkEmail(this);">

				<fieldset class="pb_reg_form" id="members_info">
					<legend><span class="memb_legend"> Your Information </span></legend>
					<div class="personal_info">
						<input type="hidden"
						       name="pb_user_id"
						       value="<?php echo $pb_reg_user_id; ?>"/>

						<div class="pb_rows" id="personal_info">
							<label class="pb_lbl_left"
							       id="pb_lbl_fname"
							       for="pb_fname"> First Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_left"
							       data-prompt-position="bottomLeft"
							       id="pb_fname"
							       type="text"
							       name="pb_fname"
							       value="<?php echo $pb_reg_data->first_name; ?>"
							       required
							/>
							<label class="pb_lbl_right"
							       id="pb_lbl_lname"
							       for="pb_lname"> Last Name:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_lname"
							       type="text"
							       name="pb_lname"
							       value="<?php echo $pb_reg_data->last_name; ?>"
							       required
							/>
						</div>

						<div class="pb_rows" id="div_pb_email">
							<label class="pb_lbl_left"
							       id="pb_lbl_email"
							       for="pb_email"> Email:
							</label>
							<input class="validate[required, custom[email]] pb_lbl_left"
							       data-prompt-position="bottomLeft"
							       id="pb_email"
							       name="pb_email"
							       type="text"
							       value="<?php echo $pb_reg_data->email; ?>"
							       required
							/>

							<label class="pb_lbl_right"
							       id="pb_lbl_email_verify"
							       for="pb_email_verify"> Verify Email:
							</label>
							<input class="validate[required, custom[email]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_email_verify"
							       name="pb_email_verify"
							       type="text"
							       value="<?php echo $pb_reg_data->email; ?>"
							       required
							/>
						</div>

						<div class="pb_rows" id="div_pb_phone">
							<label class="pb_lbl_left"
							       id="pb_lbl_phone"
							       for="pb_phone"> Phone:
							</label>
							<input class="validate[required, custom[phone]] pb_input_left"
							       data-prompt-position="bottomLeft"
							       id="pb_phone"
							       name="pb_phone"
							       type="tel"
							       value="<?php echo formatPhoneNumber( $pb_reg_data->phone ); ?>"
							       required
							/>

							<label class="pb_lbl_right"
							       id="pb_lbl_club"
							       for="pb_club"> Club Affiliation:
							</label>
							<input class="validate[required, custom[onlyLetterSp]] pb_input_right"
							       data-prompt-position="bottomLeft"
							       id="pb_club"
							       name="pb_club"
							       type="text"
							       value="<?php echo $pb_reg_data->club_aff; ?>"
							       required
							/>
						</div>

						<div class="pb_rows">

							<label class="pb_lbl_cruise pb_cruise_choice"
							       id="pb_cruise_lbl"
							       for="memb_pb_cruise_choice">
								Attending Captain's Castaway Cruise( $<?php echo $this->pb_cruise_cost; ?>)
							</label>
							<input class="validate[required] pb_cruise_choice"
							       id="memb_pb_cruise_choice"
							       name="pb_cruise"
							       type="radio"
							       value="Y"
							       <?php if ( $pb_reg_data->cruise == "Y" ) { ?>checked<?php }; ?>
							>
							Yes
							<input class="validate[required] pb_cruise_choice"
							       id="memb_pb_cruise_choice"
							       name="pb_cruise"
							       type="radio"
							       value="N"
							       <?php if ( $pb_reg_data->cruise == "Y" ) { ?>checked<?php }; ?>
							>
							No
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

					<div class="pb_reg_attendee <?php echo $pb_display_attendee_2_class; ?>"
					     id="pb_attendee_2">
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
							<!--
							<label class="pb_lbl_left"
							       id="pb_lbl_attendee_shirt_2"
							       for="pb_attendee_shirt_2">T-Shirt Size:
							</label>
							<select class="validate[required] pb_input_left"
							        id="pb_attendee_shirt_2"
							        name="pb_attendee_shirt_size_2">
								<?php //$defSel = $pb_reg_data->attendee_shirt_size_2;
							//echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
							</select>
							-->

							<label class="pb_lbl_cruise pb_cruise_choice"
							       id="pb_attendee_2_cruise_lbl"
							       for="pb_attendee_cruise_2">
								Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
							</label>
							<input class="validate[required] pb_cruise_choice"
							       id="pb_attendee_cruise_2"
							       name="pb_attendee_cruise_2"
							       type="radio"
							       value="Y"
							       <?php if ( $pb_reg_data->cruise == "Y" ) { ?>checked<?php }; ?>
							>
							Yes
							<input class="validate[required] pb_cruise_choice"
							       id="pb_attendee_cruise_2"
							       name="pb_attendee_cruise_2"
							       type="radio"
							       value="N"
							       <?php if ( $pb_reg_data->cruise == "N" ) { ?>checked<?php }; ?>
							>
							No

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
					<div class="pb_reg_attendee <?php echo $pb_display_attendee_3_class; ?>"
					     id="pb_attendee_3">
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
							<label class="pb_lbl_cruise pb_cruise_choice"
							       id="pb_attendee_3_cruise_lbl"
							       for="pb_attendee_cruise_3">
								Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
							</label>
							<input class="validate[required] pb_cruise_choice"
							       id="pb_attendee_cruise_3"
							       name="pb_attendee_cruise_3"
							       type="radio"
							       value="Y"
							       <?php if ( $pb_reg_data->cruise == "Y" ) { ?>checked<?php }; ?>
							>
							Yes
							<input class="validate[required] pb_cruise_choice"
							       id="pb_attendee_cruise_3"
							       name="pb_attendee_cruise_3"
							       type="radio"
							       value="N"
							       <?php if ( $pb_reg_data->cruise == "N" ) { ?>checked<?php }; ?>
							>
							No

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

					<div class="pb_reg_attendee <?php echo $pb_display_attendee_4_class; ?>"
					     id="pb_attendee_4">
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
							<label class="pb_lbl_cruise pb_cruise_choice"
							       id="pb_attendee_4_cruise_lbl"
							       for="pb_attendee_cruise_4">
								Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
							</label>
							<input class="validate[required] pb_cruise_choice"
							       id="pb_attendee_cruise_4"
							       name="pb_attendee_cruise_4"
							       type="radio"
							       value="Y"
							       <?php if ( $pb_reg_data->cruise == "Y" ) { ?>checked<?php }; ?>
							>
							Yes
							<input class="validate[required] pb_cruise_choice"
							       id="pb_attendee_cruise_4"
							       name="pb_attendee_cruise_4"
							       type="radio"
							       value="N"
							       <?php if ( $pb_reg_data->cruise == "N" ) { ?>checked<?php }; ?>
							>
							No
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

				<div class="ctxphc_button1">
					<?php echo $this->pb_submit_button; ?>
				</div>

			</form>

			<div class="spacer"></div>

			<?php
			if ( $this->pb_reg_type ) {
				echo $this->pb_submit_pp_button;
			}
			?>
		</div>

		<div class="spacer"></div>
		<?php
	}

	public function render_pb_failed_form() { // Display Error form
		?>
		<div>
			<h3>There was a problem with Pirate's Ball Registration!!!!!</h3>

			Your registration includes:
			<ul>
				<li><span class="pb_bold">Friday's</span> Welcome Aboard Party with <a
						href="http://www.thedetentions.com/">The Detentions!</a>.
				</li>
				<li><span class="pb_bold">Saturday Afternoon's</span> Walk The Plank Pool Party with
					live music
					by <a href="">TBA</a>.
				</li>
				<li><span class="pb_bold">Saturday Night's</span> Pirate's Ball with <a
						href="http://donnybrewer.com/">Donny Brewer and The Dock Rockers</a> and two
					free drink
					tickets!
				</li>
				<li><span class="pb_bold">Sunday's</span>: SUNDAY SEND OFF Music and Breakfast Tacos
					by the Pool!
				</li>
			</ul>

			<p>We look forward to seeing you and celebrating another wonderful CTXPHC Pirate's Ball!
		</div>

		<div id="memb_reg_cost">
			<h4>CTXPHC Members only early registration cost: $<?php echo $this->pb_memb_cost; ?> per
				person</h4>
			After June 30th Early Registration cost: $<?php echo $this->pb_open_cost; ?> per
			person.
			After July 31st Registration cost: $<?php echo $this->pb_cost; ?> pre person.

			<p class="pb_center">
				<a href="https://www.ctxphc.com/pirates-ball-details/">
					Click here for additional event and hotel information!
				</a>
			</p>
		</div>
		<div id="early_reg_cost">
			<h4>CTXPHC Early registration cost: $<?php echo $this->pb_open_cost; ?> per person</h4>
			Registration is $<?php echo $this->pb_cost; ?> after July 31 and at the door.

			<p class="pb_center">
				<a href="https://www.ctxphc.com/pirates-ball-details/">
					Click here for additional event and hotel information!
				</a>
			</p>
		</div>
		<div id="late_reg_cost">
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

	public function render_pb_blank_form() {  // Display New form
		?>
		<div class="spacer"></div>

		<form id="pbMembOnlyRegForm" name="pbRegForm" method="post" action="">
			<fieldset class="pb_reg_form" id="members_info">
				<legend><span class="memb_legend">Your Information</span></legend>
				<div class="personal_info">
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

					<div class="pb_rows">
						<label class="pb_lbl_cruise pb_cruise_choice"
						       id="pb_cruise_lbl"
						       for="memb_pb_cruise_choice">
							Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
						</label>
						<input class="validate[required] pb_cruise_choice"
						       id="memb_pb_cruise_choice"
						       name="pb_cruise"
						       type="radio"
						       value="Y"
						>
						Yes
						<input class="validate[required] pb_cruise_choice"
						       id="memb_pb_cruise_choice"
						       name="pb_cruise"
						       type="radio"
						       value="N"
						>
						No
					</div>
				</div>
			</fieldset>

			<div class='spacer'></div>

			<fieldset class="pb_reg_form" id="pb_Attend_Info">
				<legend><span class="memb_legend">Additional Attendees</span></legend>
				<div class="pb_attendee_count" id="pb_attendee_count">
					<input class="pb_attendeeCount"
					       id="pb_attendee_count_2"
					       type="radio"
					       name="attendee_count"
					       value="2"/>
					<label class="pb_attendeeCount"
					       for="pb_attendee_count_2">2 Attendees
						$<?php echo $this->pb_reg_cost * 2; ?>
					</label>

					<input class="pb_attendeeCount"
					       id="pb_attendee_count_3"
					       type="radio"
					       name="attendee_count"
					       value="3"/>
					<label class="pb_attendeeCount"
					       for="pb_attendee_count_3">3 Attendees
						$<?php echo $this->pb_reg_cost * 3; ?>
					</label>

					<input class="pb_attendeeCount"
					       id="pb_attendee_count_4"
					       type="radio"
					       name="attendee_count"
					       value="4"/>
					<label class="pb_attendeeCount"
					       for="pb_attendee_count_4">4 Attendees:
						$<?php echo $this->pb_reg_cost * 4; ?>
					</label>
				</div>

				<div id="pb_attendee_2">
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
						/>
					</div>

					<div class="pb_rows">
						<!--
						<label class="pb_lbl_left"
						       id="pb_lbl_attendee_shirt_2"
						       for="pb_attendee_shirt_2">T-Shirt Size:
						</label>
						<select class="validate[required] pb_attendee_shirt pb_input_left"
						        id="pb_attendee_shirt_2"
						        name="pb_attendee_shirt_size_2">
							<?php //$defSel = 'LG';
						//echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
						</select>
					-->
						<label class="pb_lbl_cruise pb_cruise_choice"
						       id="pb_attendee_2_cruise_lbl"
						       for="pb_attendee_cruise_2">
							Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
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

						<label class="pb_lbl_right attendee_club_lbl"
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
				</div>

				<div id="pb_attendee_3">
					<div class="pb_rows" id="pb_attendee_3">
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

					<div class="pb_rows">
						<!--
						<label class="pb_lbl_left"
						       id="pb_lbl_attendee_shirt_3"
						       for="pb_attendee_shirt_3">T-Shirt Size:
						</label>
						<select class="validate[required] pb_attendee_shirt pb_input_left"
						        id="pb_attendee_shirt_3"
						        name="pb_attendee_shirt_size_3">
							<?php //$defSel = 'LG';
						//echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
						</select>
						-->
						<label class="pb_lbl_cruise pb_cruise_choice"
						       id="pb_attendee_3_cruise_lbl"
						       for="pb_attendee_cruise_3">
							Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
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

						<label class="pb_lbl_right attendee_club_lbl"
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
				</div>

				<div id="pb_attendee_4">
					<div class="pb_rows" id="pb_attendee_4">
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

					<div class="pb_rows">
						<!--
						<label class="pb_lbl_left"
						       id="pb_lbl_attendee_shirt_4"
						       for="pb_attendee_shirt_4">T-Shirt Size:
						</label>
						<select class="validate[required] pb_input_left"
						        id="pb_attendee_shirt_4"
						        name="pb_attendee_shirt_size_4">
							<?php //$defSel = 'LG';
						//echo showOptionsDrop( $this->pb_shirt_sizes, $defSel, true ); ?>
						</select>
						-->
						<label class="pb_lbl_cruise pb_cruise_choice"
						       id="pb_attendee_4_cruise_lbl"
						       for="pb_attendee_cruise_4">
							Attending Captain's Castaway Cruise(<?php echo $this->pb_cruise_cost; ?>)
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

						<label class="pb_lbl_right attendee_club_lbl"
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
				</div>
			</fieldset>

			<div class="spacer"></div>

			<div id="ctxphc_button1">
				<?php echo $this->pb_submit_button; ?>
			</div>
		</form>
		<?php
	}

	public function prep_user_data( $user_post_data ) {
		$attendee_count = intval( $user_post_data[ 'attendee_count' ] );
		foreach ( $user_post_data as $u_key => $u_value ) {
			if ( ! empty( $u_value ) ) {
				switch ( $u_key ) {
					case ( $u_key == 'pb_fname' ):
						$pb_reg_data[ 'first_name' ] = $u_value;
						break;
					case ( $u_key == 'pb_lname' ):
						$pb_reg_data[ 'last_name' ] = $u_value;
						break;
					case ( $u_key == 'pb_email' ):
						$user = get_user_by( 'email', $u_value );
						if ( ! $user && $this->pb_reg_type == 'member' ) {
							$to      = "support@ctxphc.com";
							$subject = "PB Member's Only Registration Issue!";
							$body    = "{$pb_reg_data['first_name']} {$pb_reg_data['last_name']} email address";
							$body .= "didn't have a match in the wordpress users on in family members tables.  Review is needed!!! \n\n";
							mail( $to, $subject, $body );
						}
						$pb_reg_data[ 'email' ] = $u_value;
						break;
					case ( strpos( $u_key, 'pb_phone' ) ):
						$pb_reg_data[ 'phone' ] = format_save_phone( $u_value );
						break;
					case ( strpos( $u_key, 'pb_cruise' ) ):
						$pb_reg_data[ 'cruise' ] = $u_value;
						if ( $u_value == "Y" ) {
							$this->attending_cruise_count ++;
						}
						break;
					case ( strpos( $u_key, 'pb_club' ) );
						$pb_reg_data[ 'club_aff' ] = $u_value;
						break;
					case ( $u_key == 'attendee_count' ):
						$pb_reg_data[ 'quantity' ] = intval( $u_value );
						$pb_reg_data[ 'amount' ]   = intval( $this->pb_reg_cost * $u_value );
						break;
					case ( $u_key == 'attending_cruise_count' ):
						$pb_reg_data[ 'cruise_quantity' ] = intval( $u_value );
						$pb_reg_data[ 'cruise_amount' ]   = intval( $this->pb_cruise_cost * $u_value );
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
									$pbkey                 = 'attendee_' . $this->pb_attend_count . '_name';
									$pb_reg_data[ $pbkey ] = $attendee_name;
									break;
								case ( strpos( $u_key, 'pb_attendee_cruise' ) ):
									$this->pb_attendee_cruise_count ++;
									if ( $u_value == "Y" ) {
										$this->attending_cruise_count ++;
									}
									$pbkey                 = 'attendee_' . $this->pb_attendee_cruise_count . '_cruise';
									$pb_reg_data[ $pbkey ] = $u_value;
									break;
								case ( strpos( $u_key, 'pb_attendee_club' ) );
									$this->pb_attend_club_count ++;
									$pb_club_key                 = 'attendee_club_' . $this->pb_attend_club_count;
									$pb_reg_data[ $pb_club_key ] = $u_value;
									break;
							}
						}
				}
				$pb_reg_data[ 'attending_cruise_count' ] = $this->attending_cruise_count;
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
	public function pb_data_insert( $table, $pb_reg_data ) {
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
}