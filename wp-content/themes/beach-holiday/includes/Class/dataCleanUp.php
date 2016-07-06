<?php

namespace CTXPHC\BeachHoliday\Classes;

class dataCleanUp {

/***********************************
*  Database Cleanup
**********************************/
	//add_action( 'wp_footer', 'kaos_database_cleanup' );
	function kaos_database_cleanup() {
		global $user_error_type;

		$meta_keys_array[ 'member' ] = $member_meta_keys = array(
			'first_name',
			'last_name',
			'membership_type',
			'relationship_id',
			'phone',
			'user_email',
			'occupation',
			'addr1',
			'addr2',
			'city',
			'state',
			'zip',
			'status',
			'share',
			'contact',
			'hatch_date',
			'initiated_date',
			'tag_date',
			'user_login',
			'wp_user_id',
		);

		$meta_keys_array[ 'spouse' ] = $spouse_meta_keys = array(
			'sp_first_name',
			'sp_last_name',
			'sp_email',
			'sp_phone',
			'sp_birthdate',
			'sp_relationship_id',
			'sp_wp_user_id',
		);

		$meta_keys_array[ 'child_1' ] = $c1_meta_keys = array(
			'c1_first_name',
			'c1_last_name',
			'c1_email',
			'c1_phone',
			'c1_birthdate',
			'c1_relationship_id',
			'c1_wp_user_id',
		);

		$meta_keys_array[ 'child_2' ] = $c2_meta_keys = array(
			'c2_first_name',
			'c2_last_name',
			'c2_email',
			'c2_phone',
			'c2_birthdate',
			'c2_relationship_id',
			'c2_wp_user_id',
		);

		$meta_keys_array[ 'child_3' ] = $c3_meta_keys = array(
			'c3_first_name',
			'c3_last_name',
			'c3_email',
			'c3_phone',
			'c3_birthdate',
			'c3_relationship_id',
			'c3_wp_user_id',
		);

		$meta_keys_array[ 'child_4' ] = $c4_meta_keys = array(
			'c4_first_name',
			'c4_last_name',
			'c4_email',
			'c4_phone',
			'c4_birthdate',
			'c4_relationship_id',
			'c4_wp_user_id',
		);

		$cleanup_meta_fields[ 'membership' ] = array(
			'membership',
			'membership_id',
			'memb_type',
			'user_memb_type',
			'cm_membership_type',
			'mb_membership_type',
		);

		$cleanup_meta_fields[ 'relationship' ] = array(
			'relationship',
			'cm_relationship',
			'sp_relationship',
			'c1_relationship',
			'c2_relationship',
			'c3_relationship',
			'c4_relationship',
			'fam_1_relationship_id',
			'fam_2_relationship_id',
			'fam_3_relationship_id',
			'fam_4_relationship_id',
			'mb_relationship_id',
		);

		$cleanup_meta_fields[ 'phone' ] = array(
			'cm_phone',
			'user_phone',
			'dbem_phone',
			'fam_1_phone',
		);

		$cleanup_meta_fields[ 'email' ] = array(
			'cm_email',
			'mb_mb_email',
			'mb_email',
			'c1_email',
			'c2_email',
			'c3_email',
			'c4_email',
			'fam_1_email',
			'fam_2_email',
			'fam_3_email',
			'fam_4_email',
		);

		$cleanup_meta_fields[ 'occupation' ] = array(
			'mb_occupation',
			'cm_occupation',
			'user_occup',
		);

		$cleanup_meta_fields[ 'address' ] = array(
			'user_addr',
			'address',
			'dbem_address',
			'dbem_address_2',
			'mb_addr1',
			'cm_addr1',
			'mb_addr2',
		);

		$cleanup_meta_fields[ 'city' ] = array(
			'dbem_city',
			'mb_city',
			'cm_city',
			'user_city',
		);

		$cleanup_meta_fields[ 'state' ] = array(
			'dbem_state',
			'cm_state',
			'user_state',
		);

		$cleanup_meta_fields[ 'zip' ] = array(
			'dbem_zip',
			'mb_zip',
			'user_zip',
			'cm_zip',
		);

		$cleanup_meta_fields[ 'dates' ] = array(
			'fam_1_hatch_date',
			'fam_2_hatch_date',
			'fam_3_hatch_date',
			'mb_hatch_date',
			'sp_hatch_date',
			'user_hatch_date',
			'fam_1_initiated',
			'user_initiated',
			'fam_1_tab_date',
			'fam_2_tab_date',
			'fam_3_tab_date',
			'sp_tag_date',
			'user_tag',
		);

		$cleanup_meta_fields[ 'misc_fields' ] = array(
			'mb_status_id',
			'status_id',
			'user_share',
			'user_contact',
		);

		$cleanup_meta_fields[ 'usernames' ] = array(
			'fam_1_username',
			'user_login',
		);

		$cleanup_meta_fields[ 'user_ids' ] = array(
			'fam_1_wp_user_id',
			'fam_2_wp_user_id',
			'fam_3_wp_user_id',
			'sp_wp_user_id',
			'fam_memb_id_1',
			'fam_memb_id_2',
			'fam_memb_id_3',
			'fam_memb_id_4',
			'prim_memb_id',
			'membUserID',
		);

		$delete_fields = array(
			'user_bday_day',
			'user_bday_month',
			'user_birthdate',
		);

		foreach ( $meta_keys_array as $member_meta_keys ) {
			$member_meta_keys_count = count( $member_meta_keys );
			foreach ( $cleanup_meta_fields as $cleanup_meta_field_group ) {
				foreach ( $cleanup_meta_field_group as $cleanup_meta_field ) {
					$emessage = 'cleanup_meta_field is ' . $cleanup_meta_field;
					user_error( $emessage, $user_error_type[ 'warning' ] );

					kaos_migrate_data( $cleanup_meta_field, $member_meta_keys, $member_meta_keys_count, $delete_fields );
				}
			}
		}
	}

	/**
	 * @param $user_meta_key
	 * @param $member_meta_keys
	 * @param $member_meta_keys_count
	 *
	 * @param $fields_to_delete
	 *
	 * @internal param $member_meta_key
	 */
	function kaos_migrate_data( $user_meta_key, $member_meta_keys, $member_meta_keys_count, $fields_to_delete ) {
		global $user_error_type;

		if ( ! empty( $fields_to_delete ) ) {
			//TODO: create process to delete these fields as they will never be used again.
		}


		$fdelete       = false;
		$get_user_args = array(
			'meta_key' => $user_meta_key,
			'orderby'  => 'last_name',
			'fields'   => 'all_with_meta',
		);

		$members_data = get_users( $get_user_args );

		if ( ! empty( $members_data ) ) {
			foreach ( $members_data as $member ) {
				$memb_id    = $member->id;
				$user_value = $member->$user_meta_key;

				$validate_args = array(
					'memb_id'       => $memb_id,
					'user_value'    => $user_value,
					'user_meta_key' => $user_meta_key,
					'proc_memb'     => false,
				);

				switch ( $user_meta_key ) {
					case 'memb_type':
					case 'membership':
					case 'membership_id':
					case 'membership_type':
					case 'user_memb_type':
					case 'mb_membership_type':
					case 'cm_membership_type':
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'm_type' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'm_type' ];
						$fdelete                            = kaos_cleanup_membership( $validate_args );
						break;
					/*
					case 'relationship':
					case 'cm_relationship':
					case 'relationship_id':
					case 'mb_relationship_id':
					case 'cm_relationship_id':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'rel' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'rel' ];
						$fdelete                            = kaos_cleanup_relationship( $validate_args );
						break;
					case 'sp_relationship':
					case 'c1_relationship':
					case 'c2_relationship':
					case 'c3_relationship':
					case 'c4_relationship':
					case 'sp_relationship_id':
					case 'c1_relationship_id':
					case 'c2_relationship_id':
					case 'c3_relationship_id':
					case 'c4_relationship_id':
					case 'fam_1_relationship_id':
					case 'fam_2_relationship_id':
					case 'fam_3_relationship_id':
					case 'fam_4_relationship_id':
						$validate_args[ 'member_meta_key' ] = $user_meta_key;
						$validate_args[ 'member_value' ]    = '';
						$fdelete                            = kaos_cleanup_fam_relationship( $validate_args );
						break;
					case 'dbem_phone':
					case 'user_phone':
					case 'phone':
					case 'fam_1_phone':
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'phone' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'phone' ];
						$fdelete                            = kaos_cleanup_phone( $validate_args );
						break;
					case 'email':
					case 'mb_email':
					case 'mb_mb_email':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'email' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'email' ];
						$fdelete                            = kaos_cleanup_emails( $validate_args );
					case 'mb_occupation':
					case 'user_occup':
					case 'occupation':
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'occu' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'occu' ];
						$fdelete                            = kaos_cleanup_occupation( $validate_args );
						break;
					case 'user_addr':
					case 'address':
					case 'dbem_address1':
					case 'mb_addr1':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'addr1' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'addr1' ];
						$fdelete                            = kaos_cleanup_address( $validate_args );
						break;
					case 'dbem_address2':
					case 'mb_addr2':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'addr2' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'addr2' ];
						$fdelete                            = kaos_cleanup_address( $validate_args );
						break;
					case 'user_city':
					case 'mb_city':
					case 'dbem_city':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'city' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'city' ];
						$fdelete                            = kaos_cleanup_city( $validate_args );
						break;
					case 'user_state':
					case 'dbem_state':
					case 'state':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'state' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'state' ];
						$fdelete                            = kaos_cleanup_state( $validate_args );
						break;
					case 'user_zip':
					case 'mb_zip':
					case 'dbem_zip':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'zip' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'zip' ];
						$fdelete                            = kaos_cleanup_zip( $validate_args );
						break;
					case 'status_id':
					case 'mb_status_id':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'status' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'status' ];
						$fdelete                            = kaos_cleanup_status( $validate_args );
						break;
					case 'user_share':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'share' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'share' ];
						$fdelete                            = kaos_cleanup_share( $validate_args );
						break;
					case 'user_contact':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'cont' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'cont' ];
						$fdelete                            = kaos_cleanup_contact( $validate_args );
						break;
					case 'user_hatch_date':
						$validate_args[ 'proc_memb' ] = true;
					case 'fam_1_hatch_date':
					case 'fam_2_hatch_date':
					case 'fam_3_hatch_date':
					case 'mb_hatch_date':
					case 'sp_hatch_date':
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'hatch' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'hatch' ];
						$fdelete                            = kaos_cleanup_hatch_dates( $validate_args );
						break;
					case 'fam_1_initiated':
					case 'user_initiated':
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'init' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'init' ];
						$fdelete                            = kaos_cleanup_initiated_dates( $validate_args );
						break;
					case 'user_tag':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'tag' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'tag' ];
						$fdelete                            = kaos_cleanup_tag_dates( $validate_args );
						break;
					case 'user_login':
					case 'membUserID':
						$validate_args[ 'proc_memb' ]       = true;
						$validate_args[ 'member_meta_key' ] = $member_meta_keys[ 'login' ];
						$validate_args[ 'member_value' ]    = $member->$member_meta_keys[ 'login' ];
						$fdelete                            = kaos_cleanup_usernames( $validate_args );
						break;
					*/
					default:
						$fdelete = false;
				}

				If ( $fdelete === true ) {
					$del_result = kaos_delete_user_meta( $memb_id, $user_meta_key );
				} else {
					$emessage = 'Skipped deleting ' . $user_meta_key . ' for user id: ' . $memb_id;
					user_error( $emessage, $user_error_type[ 'warning' ] );
				}
			}
		}
	}

	function kaos_get_cleanup_defaults() {
		$defaults = array(
			'fdelete'         => false,
			'memb_type'       => null,
			'memb_id'         => null,
			'user_value'      => null,
			'user_meta_key'   => null,
			'member_meta_key' => null,
			'member_value'    => null,
		);

		return $defaults;
	}

	function kaos_cleanup_membership( $args ) {
		global $user_error_type;
		/** @noinspection PhpUndefinedVariableInspection */
		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}


		If ( empty( $member_value ) && ! empty( $user_value ) ) {
			$switch_test = $user_value;
		} elseif ( ! empty( $member_value ) ) {
			$switch_test = $member_value;
		} else {
			return $fdelete = true;
		}

		switch ( $switch_test ) {
			case 'S':
			case 'Single':
			case '1':
				$memb_type = 'ID';
				break;
			case '2':
				$memb_type = 'IC';
				break;
			case 'C':
			case 'Couple':
			case '3':
				$memb_type = 'CO';
				break;
			case 'F':
			case '4':
			case 'Family':
				$memb_type = 'HH';
				break;
			default:
				$memb_type = $switch_test;
		}


		if ( empty( $member_value ) && ! empty( $memb_type ) ) {
			$emessage = 'Membership Type is being updated with: ' . $memb_type;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$result = kaos_update_member_meta( $memb_id, $member_meta_key, $memb_type );
			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $memb_type ) {
			$emessage = 'Membership Type does not need to be updated.';
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$fdelete = true;

		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_relationship( $args ) {
		global $user_error_type;

		//Relationship_id(user_value) trumps relationship(member_value)
		/** @noinspection PhpUndefinedVariableInspection */

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		$update_meta_value = null;

		If ( empty( $member_value ) && ! empty( $user_value ) ) {
			$switch_test = $user_value;
		} elseif ( ! empty( $member_value ) ) {
			$switch_test = $member_value;
		} else {
			return $fdelete = true;
		}

		switch ( $user_value ) {
			//case 'M':
			case '1':
				$update_meta_value = 'M';
				break;
			//case 'S':
			case '2':
				$update_meta_value = 'S';
				break;
			//case 'P':
			case '3':
				$update_meta_value = 'P';
				break;
			//case 'C':
			case '4':
				$update_meta_value = 'C';
				break;
			//case 'O':
			case '5':
				$update_meta_value = 'O';
				break;
			default:
				$update_meta_value = $user_value;
		}

		if ( empty( $member_value ) && ! empty( $update_meta_value ) ) {
			$emessage = $member_meta_key . ' is being updated with: ' . $update_meta_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$result = kaos_update_member_meta( $memb_id, $member_meta_key, $update_meta_value );
			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $update_meta_value ) {
			$emessage = $member_meta_key . ' does not need to be updated.';
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$fdelete = true;

		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_fam_relationship( $args ) {
		global $user_error_type;

		//Relationship_id(user_value) trumps relationship(member_value)
		/** @noinspection PhpUndefinedVariableInspection */

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		$update_meta_value = null;

		If ( empty( $member_value ) && ! empty( $user_value ) ) {
			$switch_test = $user_value;
		} elseif ( ! empty( $member_value ) ) {
			$switch_test = $member_value;
		} else {
			return $fdelete = false;
		}

		switch ( $switch_test ) {
			//case 'M':
			case '1':
				$update_meta_value = 'M';
				break;
			//case 'S':
			case '2':
				$update_meta_value = 'S';
				break;
			//case 'P':
			case '3':
				$update_meta_value = 'P';
				break;
			//case 'C':
			case '4':
				$update_meta_value = 'C';
				break;
			//case 'O':
			case '5':
				$update_meta_value = 'O';
				break;
			default:
				$update_meta_value = $switch_test;
		}

		if ( empty( $member_value ) && ! empty( $update_meta_value ) ) {
			$emessage = $member_meta_key . ' is being updated with: ' . $update_meta_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$result = kaos_update_member_meta( $memb_id, $member_meta_key, $update_meta_value );
			if ( $result ) {
				$fdelete = false;
			}
		} elseif ( $member_value === $update_meta_value ) {
			$emessage = 'cm_relationship does not need to be updated.';
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$fdelete = false;

		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_phone( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( ( empty( $member_value ) && ! empty( $user_value ) ) || ! empty( $user_value ) ) {
			$formatted_phone = formatPhoneNumber( $user_value );
		} elseif ( ! empty( $member_value ) ) {
			$formatted_phone = formatPhoneNumber( $member_value );
		} else {
			return true;
		}

		if ( $proc_memb ) {
			$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $formatted_phone );
			user_error( 'Phone update result is ' . $fdelete, $user_error_type[ 'warning' ] );
		} else {
			$fam_meta_key   = kaos_get_fam_meta_key( $user_meta_key, $memb_id );
			$fam_first_name = get_user_meta( $memb_id, $fam_meta_key, 'single' );

			if ( ! empty( $fam_first_name ) ) {
				if ( kaos_update_member_meta( $memb_id, $user_meta_key, $formatted_phone ) ) {
					$fdelete = false;
				}
			} else {
				$fdelete = true;
			}
		}

		return $fdelete;
	}

	function kaos_cleanup_emails( $args ) {
		global $user_error_type;

		//TODO: Create email cleanup functions
		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		$user_data   = get_userdata( $memb_id );
		$users_email = $user_data->user_email;


		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$user_id = wp_update_user( array( 'ID' => $memb_id, $member_meta_key => $user_value ) );

			if ( is_wp_error( $user_id ) ) {
				user_error( $user_id->get_error_message(), $user_error_type[ 'warning' ] );
				$fdelete = false;
			} else {
				user_error( 'user_email update with ' . $user_value, $user_error_type[ 'warning' ] );
				$fdelete = true;
			}

		} elseif ( ! empty( $member_value ) ) {

		} elseif ( $member_value === $user_value ) {
			user_error( 'user_email : No update needed. ', $user_error_type[ 'warning' ] );
		} else {
			//user value doesn't equal member value
			//hold both values for review


			if ( is_wp_error( $user_id ) ) {
				user_error( 'user_email : No update needed. ', $user_error_type[ 'warning' ] );
				$fdelete = false;
			} else {
				$fdelete = true;
			}
		}

		return $fdelete;
	}

	/**
	 * @param $args
	 *
	 * @return bool
	 */
	function kaos_cleanup_occupation( $args ) {
		global $user_error_type;

		//TODO: Create occupation cleanup function
		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		// If user_value is to be used for updating user_meta_field
		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$emessage = 'Occupation is being updated with: ' . $user_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$result = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );

			if ( $result ) {
				$fdelete = true;
			} elseif ( $user_value === $meta_value ) {
				$emessage = 'Occupation does not need to be updated.';
				user_error( $emessage, $user_error_type[ 'warning' ] );
				$fdelete = true;
			} else {
				$fdelete = false;
			}

		} elseif ( $member_value === $user_value ) {
			$emessage = 'Membership Type does not need to be updated.';
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_address( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		$fdelete      = false;
		$user_value   = ucwords( $user_value );
		$member_value = ucwords( $member_value );
		$member_addr2 = get_user_meta( $memb_id, 'addr2', 'single' );

		if ( strpos( $user_value, ',' ) ) {
			list( $addr1, $addr2 ) = explode( ',', $user_value );
			$addr1 = ucwords( $addr1 );
			$addr2 = '#' . ucwords( $addr2 );

			error_log_message( 'addr1: ' . $addr1 . ' addr2: ' . $addr2 );

			$result1 = kaos_update_member_meta( $memb_id, $member_meta_key, $addr1 );
			$result2 = kaos_update_member_meta( $memb_id, 'addr2', $addr2 );

			if ( ( $result1 ) && ( $result2 ) ) {
				$fdelete = true;
			}
		} elseif ( strpos( $user_value, '#' ) ) {
			list( $addr1, $addr2 ) = explode( '#', $user_value );
			$addr1 = ucwords( $addr1 );
			$addr2 = ucwords( $addr2 );

			error_log_message( 'addr1: ' . $addr1 . ' addr2: ' . $addr2 );

			$result1 = kaos_update_member_meta( $memb_id, $member_meta_key, $addr1 );
			$result2 = kaos_update_member_meta( $memb_id, 'addr2', $addr2 );

			if ( ( $result1 ) && ( $result2 ) ) {
				$fdelete = true;
			}
		} else {
			$addr1 = $user_value;
			if ( $addr1 !== $member_value ) {
				error_log_message( 'addr1:' . $addr1 . ' member value:' . $member_value );
				$result1 = kaos_update_member_meta( $memb_id, $member_meta_key, $addr1 );
			}

			if ( ! empty( $addr2 ) ) {
				error_log_message( 'addr2:' . $addr2 );
				$result2 = kaos_update_member_meta( $memb_id, 'addr2', $addr2 );

				if ( $result1 ) {
					if ( $result2 ) {
						$fdelete = true;
					}
				}
			}
		}

		return $fdelete;
	}

	function kaos_cleanup_city( $args ) {
		global $user_error_type;

		//TODO: Create city cleanup function
		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$user_value = ucwords( $user_value );
			$result     = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
			$emessage   = 'cm_city is being updated with: ' . $user_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $user_value ) {
			$emessage = 'cm_city does not need to be updated!';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_state( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		$state      = null;
		$test_value = null;

		If ( ( empty( $member_value ) && ! empty( $user_value ) ) || ! empty( $user_value ) ) {
			$switch_test = $user_value;
		} elseif ( ! empty( $member_value ) ) {
			$switch_test = $member_value;
		} else {
			return $fdelete = true;
		}

		switch ( $test_value ) {
			case '5':
			case 'CA':
				$state = 'California';
				break;
			case '6':
			case 'CO':
				$state = 'Colorado';
				break;
			case 'FL':
				$state = 'Florida';
				break;
			case 'NY':
			case '32':
				$state = 'New York';
				break;
			case 'TX':
			case '43':
				$state = 'Texas';
				break;
		}

		if ( empty( $member_value ) && ! empty( $state ) ) {
			$emessage = 'State is being updated with: ' . $state;
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$result = kaos_update_member_meta( $memb_id, $member_meta_key, $state );

			if ( $result ) {
				$fdelete = true;
			} else {
				//TODO: Display some kind of error the php error log.
			}

		} elseif ( $member_value === $state ) {
			$emessage = 'State does not need to be updated.';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_zip( $args ) {
		global $user_error_type;

		//TODO: Create zip cleanup function
		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
			$emessage = 'cm_zip is being updated with: ' . $user_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $user_value ) {
			$emessage = 'cm_zip does not need to be updated!';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_status( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		If ( ( empty( $member_value ) && ! empty( $user_value ) ) || ! empty( $user_value ) ) {
			$switch_test = $user_value;
		} elseif ( ! empty( $member_value ) ) {
			$switch_test = $member_value;
		} else {
			return $fdelete = true;
		}

		switch ( $switch_test ) {
			case '0':
			case 'Pending':
				$update_value = 'Pending';
				break;
			case 'Active':
			case '1':
				$update_value = 'Active';
				break;
			case 'Archived':
			case '2':
				$update_value = 'Archived';
				break;
			case 'Inactive':
			case '3':
				$update_value = 'Inactive';
				break;
			default:
				$update_value = $switch_test;
		}

		if ( empty( $member_value ) && ! empty( $update_value ) ) {
			$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $update_value );
			$emessage = 'cm_status is being updated with: ' . $update_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $update_value ) {
			$emessage = 'cm_status does not need to be updated!';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_share( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();

		$args = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
			$emessage = 'cm_share is being updated with: ' . $user_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $user_value ) {
			$emessage = 'cm_share does not need to be updated!';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_contact( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();

		$args = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
			$emessage = 'cm_share is being updated with: ' . $user_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $user_value ) {
			$emessage = 'cm_share does not need to be updated!';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_hatch_dates( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();

		$args = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		//TODO: Compare hatch_ddate to registration date
		$user_data     = get_userdata( $memb_id );
		$memb_reg_date = $user_data->user_registered;

		if ( strtotime( $user_value ) <= strtotime( $memb_reg_date ) ) {
			$emessage = 'user_value: ' . $user_value . ':strtotime is: ' . strtotime( $user_value );
			user_error( $emessage, $user_error_type[ 'warning' ] );

			$emessage = 'user_registered: ' . $memb_reg_date . ' :strtotime is: ' . strtotime( $memb_reg_date );
			user_error( $emessage, $user_error_type[ 'warning' ] );
		}

		if ( empty( $member_value ) && ! empty( $user_value ) ) {
			$result   = kaos_update_member_meta( $memb_id, $member_meta_key, $user_value );
			$emessage = 'cm_share is being updated with: ' . $user_value;
			user_error( $emessage, $user_error_type[ 'warning' ] );

			if ( $result ) {
				$fdelete = true;
			}
		} elseif ( $member_value === $user_value ) {
			$emessage = 'cm_share does not need to be updated!';
			user_error( $emessage, $user_error_type[ 'warning' ] );
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_initiated_dates( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();

		$args = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) ) {

			if ( $user_value !== $member_value ) {
				$result = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
			}

			if ( $fdelete ) {
				$fdelete = true;
			} else {
				$fdelete = false;
			}
		}

		return $fdelete;
	}

	function kaos_cleanup_tag_dates( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) ) {

			if ( $user_value !== $member_value ) {
				$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
			}
		}
		if ( $fdelete ) {
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}

	function kaos_cleanup_usernames( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}


		if ( $user_value === $member_value ) {
			$fdelete = kaos_delete_user_meta( $memb_id, $member_meta_key );
		} elseif ( ! empty( $user_login ) ) {
			$fdelete = kaos_update_member_meta( $memb_id, $member_meta_key, $member_value );
		}

		return $fdelete;
	}

	function kaos_cleanup_wp_user_ids( $args ) {
		global $user_error_type;

		$defaults = kaos_get_cleanup_defaults();
		$args     = wp_parse_args( $args, $defaults );

		foreach ( $args as $arg_key => $arg_value ) {
			${$arg_key} = $arg_value;
		}

		if ( empty( $member_value ) ) {

			if ( $user_value !== $member_value ) {
				$fdelete = club_membership_update_member_meta( $memb_id, $member_meta_key, $member_value );
			}
		}

		return $fdelete;
	}

	function kaos_delete_user_meta( $memb_id, $user_meta_key ) {
		global $user_error_type;

		$delete_result = delete_user_meta( $memb_id, $user_meta_key );
		if ( ! $delete_result ) {
			error_log_message( 'Delete of ' . $memb_id . ' ' . $user_meta_key . ' FAILED!!!!' );
			$delete_result = false;
		} else {
			error_log_message( 'Delete of ' . $memb_id . ' ' . $user_meta_key . ' completed.' );
		}
		error_log_message( " " );

		return $delete_result;
	}

	public function kaos_update_member_meta( $memb_id, $update_meta_key, $update_meta_value ) {
		global $user_error_type;

		$update_result = update_user_meta( $memb_id, $update_meta_key, $update_meta_value );
		if ( ! $update_result ) {
			$curr_meta_value = get_user_meta( $memb_id, $update_meta_key, 'single' );
			if ( $update_meta_value === $curr_meta_value ) {
				$emessage = $memb_id . ': No update of ' . $update_meta_key . ' was needed.';
				error_log_message( $emessage, $user_error_type[ 'warning' ] );
				$update_result = true;
			} else {
				$emessage = $memb_id . ': Update of ' . $update_meta_key . ' FAILED!!!!';
				error_log_message( $emessage, $user_error_type[ 'warning' ] );
				$update_result = false;
			}

		} else {
			$emessage = $memb_id . ' : ' . $update_meta_key . ' Update completed.';
			error_log_message( $emessage, $user_error_type[ 'warning' ] );
			$update_result = true;
		}

		return $update_result;
	}

	function kaos_get_fam_meta_key( $user_meta_key, $memb_id ) {

		switch ( $user_meta_key ) {
			case 'sp_relationship':
			case 'c1_relationship':
			case 'c2_relationship':
			case 'c3_relationship':
			case 'c4_relationship':
			case 'sp_relationship_id':
			case 'c1_relationship_id':
			case 'c2_relationship_id':
			case 'c3_relationship_id':
			case 'c4_relationship_id':
			case 'fam_1_relationship_id':
			case 'fam_2_relationship_id':
			case 'fam_3_relationship_id':
			case 'fam_1_phone':
			case 'sp_phone':
			case 'sp_email':
			case 'fam_1_email':
			case 'fam_2_email':
			case 'fam_3_email':
			case 'c1_email':
			case 'c2_email':
			case 'c3_email':
			case 'c4_email':
			case 'fam_1_hatch_date':
			case 'fam_2_hatch_date':
			case 'fam_3_hatch_date':
			case 'fam_1_initiated':
				$splt_field   = explode( '_', $user_meta_key );
				$fam_meta_key = $splt_field[ 0 ] . '_first_name';
				break;
			default:
				$fam_meta_key = null;
		}

		return $fam_meta_key;
	}

	function kaos_compare_fam_ids( $memb_id ) {
		global $user_error_type, $wpdb;

		$wpdb->show_errors( true );
		$x = 1;

		$fam_meta_keys = array();

		$fam_memb_recs = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, meta_key, meta_value
															FROM ctxphcco_wp_db.ctxphc_usermeta
															WHERE user_id = $memb_id AND meta_key REGEXP \'^[c,fam,sp,mb,prim,wp]*_.*id$\'" ) );

		if ( ! empty( $fam_memb_recs ) ) {
			foreach ( $fam_memb_recs as $fam_memb_rec ) {
				foreach ( $fam_memb_rec as $fmid_key => $fmid_value ) {
					$emessage = 'fam_memb_ids returned: ' . $fmid_key . ': => :' . $fmid_value;
					error_log_message( $emessage, $user_error_type[ 'warning' ] );
				}
				if ( $fam_memb_rec[ 'meta_value' ] === $fam_memb_rec[ 'user_id' ] ) {
					$emessage = 'the meta_value: ' . $fam_memb_rec[ 'meta_value' ] . ' equals the user_id value: ' . $fam_memb_rec[ 'user_id' ];
					error_log_message( $emessage, $user_error_type[ 'warning' ] );
					$fam_args[ 'del_fam_key' ][] = $fam_memb_rec[ 'meta_key' ];
				} else {
					$emessage = 'the meta_value: ' . $fam_memb_rec[ 'meta_value' ] . ' is not equal to the user_id value: ' . $fam_memb_rec[ 'user_id' ];
					error_log_message( $emessage, $user_error_type[ 'warning' ] );
					$fam_args[ 'save_fam_key' ][] = $fam_memb_rec[ 'meta_key' ];
				}
			}
		}

		return $fam_args;
	}

	function kaos_get_relationship_value( $user_value ) {
		global $user_error_type;

		$update_meta_value = null;

		switch ( $user_value ) {
			//case 'M':
			case '1':
				$update_meta_value = 'M';
				break;
			//case 'S':
			case '2':
				$update_meta_value = 'S';
				break;
			//case 'P':
			case '3':
				$update_meta_value = 'P';
				break;
			//case 'C':
			case '4':
				$update_meta_value = 'C';
				break;
			//case 'O':
			case '5':
				$update_meta_value = 'O';
				break;
			default:
				$update_meta_value = $user_value;
		}

		return $update_meta_value;
	}

	function kaos_process_rel_update( $memb_id, $update_meta_key, $update_meta_value ) {
		global $user_error_type;

		$emessage = 'user: ' . $memb_id . ' field ' . $update_meta_key . ' is being replaced with ' . $update_meta_value;
		error_log_message( $emessage, $user_error_type[ 'warning' ] );
		$fdelete = kaos_update_member_meta( $memb_id, $update_meta_key, $update_meta_value );

		if ( $fdelete ) {
			$fdelete = true;
		} else {
			$fdelete = false;
		}

		return $fdelete;
	}
}