<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/12/2016
 * Time: 11:37 PM
 *
 */

/**
 * @param $pb_reg_data
 *
 * @return mixed
 */
function prep_pb_reg_data( $pb_user_data ) {
	$attendee_count         = 1;
	$attending_cruise_count = 0;
	$pb_reg_type             = '';

	foreach ( $pb_user_data as $u_key => $u_value ) {
		if ( strpos( $u_key, 'pb_attendee_fname' ) !== false ) {
			$attendee_count ++;
		}

		if ( ! empty( $u_value ) ) {
			switch ( $u_key ) {
				case ( $u_key == 'pb_fname' ):
					$pb_reg_data['first_name'] = $u_value;
					break;
				case ( $u_key == 'pb_lname' ):
					$pb_reg_data['last_name'] = $u_value;
					break;
				case ( $u_key == 'pb_email' ):
					$pb_reg_data['email'] = $u_value;
					break;
				case ( strpos( $u_key, 'pb_phone' ) ):
					$pb_reg_data['phone'] = format_save_phone( $u_value );
					break;
				case ( strpos( $u_key, 'pb_cruise' ) ):
					$pb_reg_data['cruise'] = $u_value;
					break;
				case ( strpos( $u_key, 'pb_club' ) );
					$pb_reg_data['club_aff'] = $u_value;
					break;
				default:
					if ( $attendee_count >= 2 ) {
						switch ( $u_key ) {
							case ( strpos( $u_key, 'pb_attendee_fname' ) ):
								$attendee_name = $u_value;
								break;
							case ( strpos( $u_key, 'pb_attendee_lname' ) ):
								$attendee_name = $attendee_name .' ' . $u_value;
								$pb_reg_data[ "attendee_name_{$attendee_count}" ] = $attendee_name;
								break;
							case ( strpos( $u_key, 'pb_attendee_cruise' ) ):
								$pb_reg_data[ "attendee_cruise_{$attendee_count}" ] = $u_value;
								break;
							case ( strpos( $u_key, 'pb_attendee_club' ) );
								$pb_club_key                 = 'attendee_club_' . $attendee_count;
								$pb_reg_data[ $pb_club_key ] = $u_value;
								break;
						}
					}
			}
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