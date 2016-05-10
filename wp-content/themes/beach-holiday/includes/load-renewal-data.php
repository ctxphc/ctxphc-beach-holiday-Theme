<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 12/30/2014
 * Time: 7:39 PM
 */

function load_renewing_data( $data ) {
	global $wpdb;
	$i = 1;

	while ( $i <= count( $data ) ) {
		switch ( $i ) {
			case 1:
				$member_1 = object;
				foreach ( $data[ 'member_1' ] as $memb_key => $memb_value ) {
					$member_1->$memb_key = $memb_value;
				}
				break;
			case 2:
				$member_2 = object;
				foreach ( $data[ 'member_2' ] as $memb_key => $memb_value ) {
					$member_2->$memb_key = $memb_value;
				}
				break;
			case 3:
				$member_3 = object;
				foreach ( $data[ 'member_3' ] as $memb_key => $memb_value ) {
					$member_3->$memb_key = $memb_value;
				}
				break;
			case 4:
				$member_4 = object;
				foreach ( $data[ 'member_4' ] as $memb_key => $memb_value ) {
					$member_4->$memb_key = $memb_value;
				}
				break;
			case 5:
				$member_5 = object;
				foreach ( $data[ 'member_5' ] as $memb_key => $memb_value ) {
					$member_5->$memb_key = $memb_value;
				}
				break;
		}
		$i ++;
	}

	$address = $wpdb->get_row( "SELECT * FROM ctxphc_member_addresses WHERE ID = {$member_1->address_id}" );

	$renewal_form_data[ 'addr1' ] = $address->addr1;
	$renewal_form_data[ 'addr2' ] = $address->addr2;
	$renewal_form_data[ 'city' ]  = $address->city;
	$renewal_form_data[ 'state' ] = $address->state;
	$renewal_form_data[ 'zip' ]   = $address->zip;

	$renewal_form_data[ 'm1_id' ]         = $member_1->ID;
	$renewal_form_data[ 'm1_fname' ]      = $member_1->first_name;
	$renewal_form_data[ 'm1_lname' ]      = $member_1->last_name;
	$renewal_form_data[ 'm1_phone' ]      = $member_1->phone;
	$renewal_form_data[ 'm1_email' ]      = $member_1->email;
	$renewal_form_data[ 'm1_birthday' ]   = $member_1->bday;
	$renewal_form_data[ 'm1_occupation' ] = $member_1->occupation;
	$renewal_form_data[ 'm1_mem_type' ]   = $member_1->membership_type;

	$renewal_form_data[ 'm2_id' ]       = $member_2->ID;
	$renewal_form_data[ 'm2_fname' ]    = $member_2->first_name;
	$renewal_form_data[ 'm2_lname' ]    = $member_2->last_name;
	$renewal_form_data[ 'm2_email' ]    = $member_2->sp_email;
	$renewal_form_data[ 'm2_phone' ]    = $member_2->phone;
	$renewal_form_data[ 'm2_birthday' ] = $member_2->bday;
	$renewal_form_data[ 'm2_rel' ]      = $member_2->relationship_id;


	$renewal_form_data[ 'm3_id' ]       = $member_3->ID;
	$renewal_form_data[ 'm3_fname' ]    = $member_3->first_name;
	$renewal_form_data[ 'm3_lname' ]    = $member_3->last_name;
	$renewal_form_data[ 'm3_birthday' ] = $member_3->bday;
	$renewal_form_data[ 'm3_email' ]    = $member_3->email;
	$renewal_form_data[ 'm3_rel' ]      = $member_3->fam_rel;

	$renewal_form_data[ 'm4_id' ]       = $member_4->ID;
	$renewal_form_data[ 'm4_fname' ]    = $member_4->first_name;
	$renewal_form_data[ 'm4_lname' ]    = $member_4->last_name;
	$renewal_form_data[ 'm4_birthday' ] = $member_4->bday;
	$renewal_form_data[ 'm4_email' ]    = $member_4->email;
	$renewal_form_data[ 'm4_rel' ]      = $member_4->relationship_id;

	$renewal_form_data[ 'm5_id' ]       = $member_5->ID;
	$renewal_form_data[ 'm5_fname' ]    = $member_5->first_name;
	$renewal_form_data[ 'm5_lname' ]    = $member_5->last_name;
	$renewal_form_data[ 'm5_birthday' ] = $member_5->bday;
	$renewal_form_data[ 'm5_email' ]    = $member_5->email;
	$renewal_form_data[ 'm5_rel' ]      = $member_5->relationship_id;

	return $renewal_form_data;
}