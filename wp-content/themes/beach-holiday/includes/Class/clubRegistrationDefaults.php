<?php

namespace CTXPHC\BeachHoliday\Classes;

class clubRegistrationDefaults {

	public function __construct() {
		$this->defaults = get_registration_defaults();
	}

	public function get_relationship_types( $table ) {

	}

	public function get_registration_defaults() {
		date_default_timezone_set( 'America/Chicago' );

		/** @var STRING $relationship_table */
		$relationship_table = 'ctxphc_member_relationships';


		/** @var STRING $membership_type_table */
		$membership_type_table = 'ctxphc_membership_types';

		/** @var STRING $ctxphc_status */
		$status_table = 'ctxphc_member_status';
	}


}