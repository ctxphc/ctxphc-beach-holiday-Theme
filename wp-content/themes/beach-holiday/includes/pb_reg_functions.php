<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/12/2016
 * Time: 11:37 PM
 *
 */

/**
 * @param $user_post_data
 *
 * @return mixed
 */
function prep_user_data( $user_post_data ) {
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
					if ( ! $user && $pb_reg_type == 'member' ) {
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
						$attending_cruise_count ++;
					}
					break;
				case ( strpos( $u_key, 'pb_club' ) );
					$pb_reg_data[ 'club_aff' ] = $u_value;
					break;
				case ( $u_key == 'attendee_count' ):
					$pb_reg_data[ 'quantity' ] = intval( $u_value );
					$pb_reg_data[ 'amount' ]   = intval( $pb_reg_cost * $u_value );
					break;
				case ( $u_key == 'attending_cruise_count' ):
					$pb_reg_data[ 'cruise_quantity' ] = intval( $u_value );
					$pb_reg_data[ 'cruise_amount' ]   = intval( $pb_cruise_cost * $u_value );
					break;
				default:
					if ( $attendee_count >= 2 ) {
						switch ( $u_key ) {
							case ( strpos( $u_key, 'pb_attendee_fname' ) ):
								$attendee_name = $u_value;
								break;
							case ( strpos( $u_key, 'pb_attendee_lname' ) ):
								$pb_attend_count ++;
								$attendee_name .= ' ' . $u_value;
								$pbkey                 = 'attendee_' . $pb_attend_count . '_name';
								$pb_reg_data[ $pbkey ] = $attendee_name;
								break;
							case ( strpos( $u_key, 'pb_attendee_cruise' ) ):
								$pb_attendee_cruise_count ++;
								if ( $u_value == "Y" ) {
									$attending_cruise_count ++;
								}
								$pbkey                 = 'attendee_' . $pb_attendee_cruise_count . '_cruise';
								$pb_reg_data[ $pbkey ] = $u_value;
								break;
							case ( strpos( $u_key, 'pb_attendee_club' ) );
								$pb_attend_club_count ++;
								$pb_club_key                 = 'attendee_club_' . $pb_attend_club_count;
								$pb_reg_data[ $pb_club_key ] = $u_value;
								break;
						}
					}
			}
			$pb_reg_data[ 'attending_cruise_count' ] = $attending_cruise_count;
		}
	}

	return $pb_reg_data;
}

function pb_data_insert( $table, $pb_reg_data ) {
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