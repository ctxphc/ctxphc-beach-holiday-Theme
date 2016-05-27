<?php /**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/13/2016
 * Time: 2:56 PM
 * beach-holiday-child-theme
 */


/**
 * Changes the wordpress default login image
 * and loads the custom ctxphc-style sheet
 */

function ctxphc_login_logo() {
	wp_register_style( 'ctxphc-login-style', get_stylesheet_directory_uri() . '/includes/css/ctxphc-login-style.css' );
	wp_enqueue_style( 'ctxphc-login-style' );
}


//add_action( 'login_enqueue_scripts', 'ctxphc_login_logo' );

/**
 * Changes the link value of the login page image to point to the CTXPHC home page.
 */
/**
 * function ctxphc_login_logo_url() {
 * return get_site_url();
 * }
 */


//add_filter( 'login_headerurl', 'ctxphc_login_logo_url' );


/**
 * Changes the site info text
 */
function ctxphc_login_logo_url_title() {
	return 'Central Texas Parrot Head Club:  Where the heart of Margaritaville lives on!';
}

//add_filter( 'login_headertitle', 'ctxphc_login_logo_url_title' );


/**
 * Redirect non-admins to the homepage after logging into the site.
 * @since    1.0
 */
function ctxphc_login_redirect( $redirect_to, $request, $user ) {
	Global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( "administrator", $user->roles ) || in_array( "bod_member", $user->roles ) ) {
			//redirect them to the dashboard
			return admin_url();
		} else {
			//redirect them back to the page them came from.
			return $request;
		}
	} else {
		return $request;
	}
} // end ctxphc_login_redirect
//add_filter( 'login_redirect', 'ctxphc_login_redirect', 10, 3 );


/**
 * Hide email from Spam Bots using a shortcode.
 *
 * @param array $atts Shortcode attributes. Not used.
 * @param string $content The shortcode content. Should be an email address.
 *
 * @return string The obfuscated email address.
 */
function HideMail( $atts, $content = null ) {
	if ( ! is_email( $content ) ) {
		$message = "Some kind of issue wiht HideMail function! Check it out.T";
		//debug_log_message( $message );
	}

	return '<a href="mailto:' . antispambot( $content ) . '">' . antispambot( $content ) . '</a>';
}

add_shortcode( 'email', 'HideMail' );


//Set HTML Content Type for HTML Emails sent in Functions.
/**
 * @return string
 */
function set_html_content_type() {
	return 'text/html';
}

//Displays options for drop down boxes in forms.
/**
 * @param $array
 * @param $selected
 * @param $echo
 *
 * @return string
 */
function showOptionsDrop( $array, $selected, $echo ) {
	$string = '';
	foreach ( $array as $key => $value ) {
		if ( $key === $selected ) {
			$string .= "<option value='$key' selected>$value</option>\n";
		} else {
			$string .= "<option value='$key'>$value</option>\n";
		}
	}

	if ( $echo ) {
		echo $string;
	} else {
		return $string;
	}
}

/**
 * @param $pp_hostname
 * @param $req
 * @param $debug
 *
 * @return mixed
 */
function send_pp_PDT( $pp_hostname, $req, $debug ) {

	if ( $debug ) {
		error_log( "!!!!!!  inside send_pp_PDT  !!!!!!!" );
		error_log( "!!!!!!  values passed are:    !!!!!!!" );
		error_log( "!!!!!!  pp_hostname = $pp_hostname  !!!!!!!" );
		error_log( "!!!!!!  req = $req  !!!!!!!" );
	}

	$tx_token = $_GET[ 'tx' ];

	if ( $debug ) {
		$auth_token = "0l0FV4lIFL_OqPL2IvLTDZdtmXUWJhzB9tayDdkP9zpeteZHxz2Orp-Vrey";
	} else {
		$auth_token = "SJd2IDmU79iIPadE5Rjp9G-jQgWYyAZf7FAUO5F6pvRACHJcEkimuyyxr0a";
	}

	$req .= "&tx=$tx_token&at=$auth_token";

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr" );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
	//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
	//if your server does not bundled with default verisign certificates.
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Host: $pp_hostname" ) );
	$res = curl_exec( $ch );
	curl_close( $ch );

	return $res;
}

/**
 * @param $res
 * @param $debug
 *
 * @return array|string
 */
function process_pp_PDT_response( $res, $debug ) {

	if ( $debug ) {
		error_log( "!!!!!!  inside process_pp_PDT_response  !!!!!!!" );
	}

	// parse the data
	$lines = explode( "\n", $res );

	foreach ( $lines as $line ) {
		if ( $debug ) {
			error_log( "!!!!!!  $line   !!!!!!" );
		}
	}


	$keyarray = array();

	if ( strcmp( $lines[ 0 ], "SUCCESS" ) == 0 ) {
		for ( $i = 1; $i < count( $lines ); $i ++ ) {
			list( $key, $val ) = explode( "=", $lines[ $i ] );
			if ( $debug ) {
				error_log( "!!!!!!  key is $key and value is $val  !!!!!!!" );
			}
			$keyarray[ urldecode( $key ) ] = urldecode( $val );
		}
	} else {
		for ( $i = 1; $i < count( $lines ); $i ++ ) {
			list( $key, $val ) = explode( "=", $lines[ $i ] );
			if ( $debug ) {
				error_log( "!!!!!! Response from PP did not return SUCCESS key is $key and value is $val  !!!!!!!" );
			}
			$keyarray[ urldecode( $key ) ] = urldecode( $val );
		}
	}
	// check the payment_status is Completed
	if ( $keyarray[ 'payment_status' ] !== 'Completed' ) {
		if ( $debug ) {
			error_log( "!!!!!! Payment Status is not Completed.  !!!!!!!" );
		}

		return "Payment Status is not Completed";
	}
	// check that txn_id has not been previously processed

	// check that receiver_email is your Primary PayPal email
	if ( $debug ) {
		$receiver_email = 'sandbox@ctxphc.com';
	} else {
		$receiver_email = 'paypal@ctxphc.com';
	}

	if ( $keyarray[ 'receiver_email' ] !== $receiver_email ) {
		if ( $debug ) {
			error_log( "!!!!!! receiver_email is not $receiver_email.  !!!!!!!" );
		}

		return "Someone is tring to hijack our payments!  {$keyarray['receiver_email']}";
	}
	// check that payment_amount/payment_currency are correct
	// process payment

	foreach ( $keyarray as $key => $value ) {
		if ( $debug ) {
			error_log( "!!!!!! keyarrays: key is $key and value is $val  !!!!!!!" );
		}
	}

	return $keyarray;
}

function select_pay_complete_message( $item_number, $debug ) {

	if ( $debug ) {
		error_log( "!!!!!!  inside select_pay_complete_message. Passed $item_number  !!!!!!!" );
	}

	if ( $item_number == 'renewal' ) {
		$renewal_pay_complete = '<p>Thank you for renewing your Central Texas Parrothead Club Membership.';
		$renewal_pay_complete .= '<div>Your payment is complete and a receipt for your renewal has been emailed to you.</div>';
		$renewal_pay_complete .= '<div>Also you can log into your PayPal account at www.paypal.com/us to view additonal details of this payment.</div>';

		if ( $debug ) {
			error_log( "!!!!!!  renewal_pay_complete = $renewal_pay_complete  !!!!!!!" );
		}

		return $renewal_pay_complete;

	} else if ( $item_number == 'registration' ) {
		$new_registration = '<p>Your Central Texas Parrothead Club Membership payment is complete!';
		$new_registration .= '<div>You will receive a receipt for your membership payment in email.</div>';
		$new_registration .= '<div>Also you can log into your PayPal account at www.paypal.com/us to view additonal details of this payment.</div>';

		if ( $debug ) {
			error_log( "!!!!!!  new_registration = $new_registration  !!!!!!!" );
		}

		return $new_registration;
	}
}


/*
 * collect current users data from ctxphc_user, ctxphc_usermeta,
 * ctxphc_members or ctxphc_members_family
 */
function ctxphc_get_renewing_user_data( $wp_user_id, $wp_user_email ) {
	error_log( "!!!!!!!  Inside ctxphc_get_renewing_user_data function  !!!!!!!!", 0 );
	global $wpdb;

	$renewing_members_data = $wpdb->get_row( "SELECT * FROM ctxphc_members WHERE wp_user_id = $wp_user_id AND email = '$wp_user_email'", ARRAY_A );

	if ( $renewing_members_data ) {
		error_log( "!!!!!!!  Current user data retrieved successfully.  !!!!!!!!", 0 );
		error_log( "!!!!!!!  Running ctxphc_get_renewql_data function.  !!!!!!!!", 0 );
		$type_of_member   = 'member';
		$member_type      = $renewing_members_data[ 'membership_type' ];
		$renewal_data_arr = ctxphc_get_renewal_data( $renewing_members_data, $type_of_member, $member_type );
	} else {
		error_log( "!!!!!!!  Current user data retrieval failed.  Trying to get info from ctxphc_members_family !!!!!!!!", 0 );
		$type_of_member = 'family';
		/** @var OBJECT $current_user */
		$renewing_members_data = $wpdb->get_row( "SELECT * FROM ctxphc_members_family WHERE wp_user_id = {$current_user->ID} AND email = {$current_user->user_email}", ARRAY_A );

		if ( $renewing_members_data ) {
			error_log( "!!!!!!!  Family members data was retrieved successfully.  !!!!!!!!", 0 );
			error_log( "!!!!!!!  Moving on to ctxphc_get_renewal_data.  !!!!!!!!", 0 );
			$member_type      = $wpdb->get_var( "SELECT membership_type FROM ctxphc_members WHERE id = {$renewing_members_data['memb_id']}", ARRAY_A );
			$renewal_data_arr = ctxphc_get_renewal_data( $renewing_members_data, $type_of_member, $member_type );
		} else {
			$renewal_data_arr = false;
		}
	}

	return $renewal_data_arr;
}


function ctxphc_get_renewal_data( $renewing_members_data, $type_of_member, $member_type ) {
	global $wpdb;
	error_log( "!!!!!!!  Inside ctxphc_get_renewal_data function  !!!!!!!!", 0 );

	if ( $type_of_member === 'member' ) { //renewing member is the primary member
		$renewal_data_arr[ 'member' ] = $renewing_members_data;
		$memb_id                      = $renewal_data_arr[ 'member' ][ 'id' ];

		switch ( $member_type ) {
			case 'CO':
				$renewal_data_arr[ 'spouse' ] = get_spouse_data( $memb_id );
				break;
			case 'HH':
				$renewal_data_arr[ 'spouse' ] = get_spouse_data( $memb_id );
				$childs_renewal_data          = get_child_data( $memb_id );
				break;
			case 'IC':
				$childs_renewal_data = get_child_data( $memb_id );
				break;
		}
	} else {  //$type_of_member is equal to family
		if ( $renewing_members_data[ 'relationship' ] === "C" ) { //renewing member is child of primary member
			$memb_id = $renewing_members_data[ 'memb_id' ];

			$renewal_data_arr[ 'member' ] = get_members_data( $memb_id );

			switch ( $member_type ) {
				case 'CO':
					$renewal_data_arr[ 'spouse' ] = get_spouse_data( $memb_id );
					break;
				case 'HH':
					$renewal_data_arr[ 'spouse' ] = get_spouse_data( $memb_id );
					$childs_renewal_data          = get_child_data( $memb_id );
					break;
				case 'IC':
					$childs_renewal_data = get_child_data( $memb_id );
					break;
			}
		} else { //renewing member is spouse/other of primary member
			$renewal_data_arr[ 'spouse' ] = $renewing_members_data;

			if ( isset( $memb_id ) ) {
				$renewal_data_arr[ 'member' ] = get_members_data( $memb_id );
			}

			if ( $type_of_member === 'HH' ) {
				$childs_renewal_data = get_child_data( $renewing_members_data );
				$renewal_data_arr .= $childs_renewal_data;
			}
		}
	}

	return $renewal_data_arr;
}

function get_members_data( $memb_id ) {
	global $wpdb;
	$members_data = $wpdb->get_row( "SELECT * FROM ctxphc_members WHERE id = $memb_id" );
	if ( $members_data ) {
		return $members_data;
	} else {
		return false;
	}
}

function get_spouse_data( $memb_id ) {
	global $wpdb;
	$spouse_data = $wpdb->get_row( "SELECT * FROM ctxphc_members_family WHERE memb_id = $memb_id AND  relationship != 'C'", ARRAY_A );
	if ( $spouse_data ) {
		return $spouse_data;
	} else {
		return false;
	}
}

function get_child_data( $memb_id ) {
	global $wpdb;

	$child_data_arr = $wpdb->get_results( "SELECT * FROM ctxphc_members_family WHERE memb_id = $memb_id AND relationship = 'C'", ARRAY_A );

	if ( $child_data_arr ) {
		$child_count = 0;
		foreach ( $child_data_arr as $child_data ) {
			$child_count ++;
			$childs_data_arr[ 'child' . $child_count ] = $child_data;
		}
	} else {
		error_log( "!!!!!!!  Unable to collect childrens member data from DB  !!!!!!!!", 0 );
		$childs_data_arr[ 'child1' ] = false;
	}

	return $child_data_arr;
}

/***  END of CTXPHC member data collection functions ***/

function split_phone( $phone ) {
	if ( $phone ) {
		$phone_arr[ 1 ] = substr( $phone, 0, 3 );
		$phone_arr[ 2 ] = substr( $phone, 4, 3 );
		$phone_arr[ 3 ] = substr( $phone, 8, 4 );
	} else {
		$phone_arr[ 1 ] = "";
		$phone_arr[ 2 ] = "";
		$phone_arr[ 3 ] = "";
	}

	return $phone_arr;
}

function generatePassword( $length = 9, $strength = 9 ) {
	$vowels     = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ( $strength & 1 ) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ( $strength & 2 ) {
		$vowels .= "AEUY";
	}
	if ( $strength & 4 ) {
		$consonants .= '23456789';
	}
	if ( $strength & 8 ) {
		$consonants .= '@#$%';
	}

	$password = '';
	$alt      = time() % 2;

	for ( $i = 0; $i < $length; $i ++ ) {
		if ( $alt == 1 ) {
			$password .= $consonants[ ( rand() % strlen( $consonants ) ) ];
			$alt = 0;
		} else {
			$password .= $vowels[ ( rand() % strlen( $vowels ) ) ];
			$alt = 1;
		}
	}

	return $password;
}

/**
 * @param $username
 * @param $pass
 * @param $email
 *
 * @return bool|int
 */
function wp_create_user_account( $username, $pass, $email ) {
	$wp_user_id = wp_create_user( $username, $pass, $email );
	error_log( "wp_user_id is $wp_user_id.", 0 );

	if ( is_wp_error( $wp_user_id ) ) {
		error_log( "######################################################", 0 );
		error_log( "######  The wp_create_user FAILED!!!!!!    ##########", 0 );
		error_log( "######################################################", 0 );
	} else {
		error_log( "Update of the wp user metadata worked.  Use id of updated wp user is: $wp_user_id.", 0 );
		//return $wp_user_id;
	}

	return $wp_user_id;
}


/**
 * @param $userdata
 * @param bool $ctxphc_user_id
 *
 * @return int|"WP_Error"
 */
function update_wp_usermeta_data( $userdata, $ctxphc_user_id = false ) {
	if ( ! empty( $ctxphc_user_id ) ) {
		$wp_update_user_meta_data = array( 'ctxphc_user_id' => $ctxphc_user_id );
		$update_wp_user_results   = wp_update_user( $wp_update_user_meta_data );
	} else {
		$update_wp_user_results = wp_update_user( $userdata );
	}

	if ( is_wp_error( $update_wp_user_results ) ) {
		error_log( "######################################################", 0 );
		error_log( "The CTXPHC WP User update failed! $update_wp_user_results.", 0 );
		error_log( "######################################################", 0 );
	}

	return $update_wp_user_results;
}


function log_values( $data ) {
	error_log( "!!!!!!!  $data  !!!!!!!!", 0 );
}

function ctxphc_update_database( $table, $data, $where ) {
	global $wpdb;
	$memb_update_results = array();
	if ( is_array( $data ) ) {
		$memb_update_results = $wpdb->update( $table, $data, $where );
		error_log( $wpdb->print_error(), 0 );
		error_log( "memb_update_results has a value of $memb_update_results", 0 );
	}

	return $memb_update_results;
}

function verify_update_results( $member_update_results, $membtype ) {

}

function send_successful_registration_email( $records ) {
	$to[] = "support@ctxphc.com";

	$subject = "New Mebership Registration Form Submitted";

	$headers[] = "From: Central Texas Parrot Head Club<ctxphc@ctxphc.com>";
	$headers[] = "Reply-To: support@ctxphc.com";
	$headers[] = "MIME-Version: 1.0\r\n";
	$headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$body = "<html><body>";
	$body .= "<h2>This is for TESTING and VERIFICATION ONLY!!!</h2>";
	$body .= "<div>New Member Registration has been submitted for:</div>";


	foreach ( $records as $record ) {
		if ( $records[ 'member' ] === 'member' ) {
			//Insert of new member data Succeeded.  Alert support with an email!
			$memb_first_name = $record->first_name;
			$memb_last_name  = $record->last_name;
			$memb_email      = $record->email;
			$memb_addr1      = $record->addr1;
			$memb_addr2      = $record->addr2;
			$memb_city       = $record->city;
			$memb_state      = $record->state;
			$memb_zip        = $record->zip;
			$memb_bday       = $record->bday_month . "/" . $records->bday_day;

			$body .= "<p /><div>$memb_first_name $memb_last_name</div>";
			$body .= "<div>$memb_addr1, $memb_addr2</div>";
			$body .= "<div>$memb_city, $memb_state $memb_zip</div>";
			$body .= "<p /><div>Email: $memb_email</div>";
			$body .= "<div>Birthday: $memb_bday</div>";

			if ( $records[ 'spouse' ] === 'spouse' ) {
				$spouse_first_name   = $record->first_name;
				$spouse_last_name    = $record->last_name;
				$spouse_email        = $record->email;
				$spouse_bday         = $record->bday_month . "/" . $record->bday_day;
				$spouse_relationship = $record->relationship;

				switch ( $spouse_relationship ) {
					case 'S';
						$body .= "<p /><div>Spouse Info:</div>";
						break;
					case 'P';
						$body .= "<p /><div>Partner Info:</div>";
						break;
					case 'O';
						$body .= "<p /><div>Other Info:</div>";
						break;
				}
				$body .= "<p /><div>$spouse_first_name $spouse_last_name</div>";
				$body .= "<div>Birthday: $spouse_bday</div>";
				$body .= "<div>Email: $spouse_email</div>";
			}

			if ( $records[ 'child1' ] === 'child1' ) {
				$body .= "<p /><div>Family Member(s) Info:</div><ul>";
				$body .= "<li><p /><div>" . $record->first_name . " " . $record->last_name . "</div>";
				$body .= "<div>Birthday: " . $record->bday_month . "/" . $record->bday_day . "</div>";
				$body .= "<div>Email: " . $record->email . "</div></li>";

				if ( $records[ 'child2' ] === 'child2' ) {
					$body .= "<li><p /><div>" . $record->first_name . " " . $record->last_name . "</div>";
					$body .= "<div>Birthday: " . $record->bday_month . "/" . $record->bday_day . "</div>";
					$body .= "<div>Email: " . $record->email . "</div></li>";

					if ( $records[ 'child3' ] === 'child3' ) {
						$body .= "<li><p /><div>" . $record->first_name . " " . $record->last_name . "</div>";
						$body .= "<div>Birthday: " . $record->bday_month . "/" . $record->bday_day . "</div>";
						$body .= "<div>Email: " . $record->email . "</div></li>";
					}
				}
			}

			$body .= "</ul><p><div><h3>The complete members registration data inserted was:</h3></div>";

			foreach ( $records as $record ) {
				$body .= "<ul>";
				foreach ( $record as $column => $value ) {
					$body .= "<li>" . $column . " = " . $value . "</li>";
				}
				$body .= "</ul>";
			}

			$body .= "<p>FinsUp! ";
			$body .= "<p>CTxPHC Support<br />";
			$body .= "Central Texas Parrot Head Club</div>";
			$body .= "</body></html>";

			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
			wp_mail( $to, $subject, $body, $headers );
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		}
	}
}

function send_create_registration_failed_email( $tables, $insert_data_records, $membtypes, $subjects ) {
	foreach ( $membtypes as $membtype ) {

		$to = "support@ctxphc.com";

		$headers[] = "From: Central Texas Parrothead Club<ctxphc@ctxphc.com>";
		$headers[] = "Reply-To: support@ctxphc.com";
		$headers[] = "MIME-Version: 1.0\r\n";
		$headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$subject = $subjects[ $membtype ];

		$body = "<div><h2>" . $subjects[ $membtype ] . "</h2></div>";
		$body .= "<p /><div>The following is the data we tried to add to the " . $tables[ $membtype ] . "table.</div><ul>";

		foreach ( $insert_data_records[ $membtype ] as $column => $value ) {
			$body .= "<li> $column = $value </li>";
		}
		$body .= "</ul>";

		//mail($to, $subject, $body, $headers);
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		wp_mail( $to, $subject, $body, $headers );
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
	}
}

/**
 * End of New Member Data Processing
 */


// Pirate's Ball Registration Database Insert and Update


/**
 * @param $pbTable
 * @param $pb_insertData
 *
 * @return string
 */
function pb_insert_registration_data( $pbTable, $pb_insertData ) {
	global $wpdb;
	//$wpdb->show_errors();

	$inserted = $wpdb->insert( $pbTable, $pb_insertData ); //Inserts PB registration data in to DB, returns inserted record id(pbRegID).
	$wpdb->print_error();

	If ( $inserted ) { //if insert worked, send test email to me.
		$pbRegID = $wpdb->insert_id; //returns the ID generated for the AUTO_INCREMENT column.

		$pbRegData = $wpdb->get_row( "SELECT * from ctxphc_pb_reg where pbRegID = {$pbRegID}" );

		if ( $pbRegData ) {
			//Insert of PB Registration data Succeeded.  Alert support with an email!
			$to[] = "support@ctxphc.com";

			$subject = "Pirates Ball Registration Data Insert Suceeded!!";

			$headers[] = "From: Central Texas Parrothead Club<ctxphc@ctxphc.com>";
			$headers[] = "Reply-To: support@ctxphc.com";
			$headers[] = "MIME-Version: 1.0\r\n";
			$headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$body = "<html><body>";
			$body .= "<h2>This is for TESTING only!</h2>";
			$body .= "<p><div>The PB Registration record was created for " . $pbRegData->first_name . ' ' . $pbRegData->last_name . "</div>";
			$body .= "<div>The pbRegID value is $pbRegID </div>";
			$body .= "<p><h3>The Registration Info:</h3>";
			$body .= "<div>" . $pbRegData->first_name . " " . $pbRegData->last_name . "</div>";
			$body .= "<div>" . $pbRegData->addr1 . "</div>";
			if ( isset( $pbRegData->addr2 ) ) {
				$body .= "<div>" . $pbRegData->addr2 . "</div>";
			}
			$body .= "<div>" . $pbRegData->city . ", " . $pbRegData->state . " " . $pbRegData->zip . "</div>";
			$body .= "<p><ul><li>Date Registered:  " . $pbRegData->reg_date . "</li>";
			$body .= "<li>Email:  " . $pbRegData->email . "</li>";
			$body .= "<li>Club Affiliation:  " . $pbRegData->club_aff . "</li>";
			$body .= "<li>Number Attending:  " . $pbRegData->quantity . "</li></ul>";
			$body .= "<p><h3>Attendees:</h3>";
			$body .= "<ul><li>Attendee 1: " . $pbRegData->attendee_1 . "</li>";
			if ( isset( $pbRegData->attendee_2 ) ) {
				$body .= "<li>Attendee 2:  " . $pbRegData->attendee_2 . "</li>";
			}
			if ( isset( $pbRegData->attendee_3 ) ) {
				$body .= "<li>Attendee 3:  " . $pbRegData->attendee_3 . "</li>";
			}
			if ( isset( $pbRegData->attendee_4 ) ) {
				$body .= "<li>Attendee 4:  " . $pbRegData->attendee_4 . "</li>";
			}
			$body .= "</ul>";
			$body .= "<p>Total Paid:  $" . $pbRegData->amount . "</div>";
			$body .= "<p>FinsUp! ";
			$body .= "<p>CTxPHC Support<br />";
			$body .= "Central Texas Parrot Head Club</div>";
			$body .= "</body></html>";

			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
			wp_mail( $to, $subject, $body, $headers );
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

			//return the pbRegID value to PB_reg_processing.php
			return $pbRegID;
		}
	} else {
		//Insert of PB Registration data failed.  Alert support with an email!
		$to      = "support@ctxphc.com";
		$subject = "PB Registration Insert Failed!";
		$body    = "There was an error when trying to create the PB Registration DB entry for ";
		$body .= $pb_insertData->first_name . " " . $pb_insertData->last_name . ".<br />";

		mail( $to, $subject, $body );

		return "FAILED";
	}
}

function pb_not_used_data_update( $pbTable, $pbRegID, $pb_updateData ) {
	global $wpdb;

	$pbWhere = array( pbRegID => $pbRegID );

	$updated = $wpdb->update( $pbTable, $pb_updateData, $pbWhere );  //Updates PB registration data in to DB.

	If ( $updated === false ) {  //Update of PB Registration data failed.  Alert end user and support!
		//use email to alert CTXPHC support of failed db update.
		$to      = "ctxphc_test1@localhost.com";
		$subject = "PB Registration Update FAILED!";
		$body    = "There was an error when trying to update the DB entry for $pbRegID \n\n";

		mail( $to, $subject, $body );

	} else {
		$pbRegData = $wpdb->get_row( "SELECT * from ctxphc_pb_reg where pbRegID = {$pbRegID}" );

		if ( $pbRegData ) {
			//Update of PB Registration data Succeeded.  Alert support with an email!
			$to[] = "ctxphc_test1@localhost.com";

			$subject = "Pirates Ball Registration Data Update Suceeded!!";

			$headers[] = "From: Central Texas Parrothead Club<ctxphc@ctxphc.com>";
			$headers[] = "Reply-To: support@ctxphc.com";
			$headers[] = "MIME-Version: 1.0\r\n";
			$headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$body = "<html><body>";
			$body .= "<h2>This is for TESTING only!</h2>";
			$body .= "<div><p>The PB Registration record was updated for " . $pbRegData->first_name . ' ' . $pbRegData->last_name . "</div>";
			$body .= "<div>The pbRegID value is $pbRegID </div>";
			$body .= "<p><h3>The Registration Info:</h3>";
			$body .= "<div>" . $pbRegData->first_name . " " . $pbRegData->last_name . "</div>";
			$body .= "<div>" . $pbRegData->addr1 . "</div>";
			if ( isset( $pbRegData->addr2 ) ) {
				$body .= "<div>" . $pbRegData->addr2 . "</div>";
			}
			$body .= "<div>" . $pbRegData->city . ", " . $pbRegData->state . " " . $pbRegData->zip . "</div>";
			$body .= "<p><ul><li>Date Registered:  " . $pbRegData->reg_date . "</li>";
			$body .= "<li>Email:  " . $pbRegData->email . "</li>";
			$body .= "<li>Club Affiliation:  " . $pbRegData->club_aff . "</li>";
			$body .= "<li>Number Attending:  " . $pbRegData->quantity . "</li></ul>";
			$body .= "<p><h3>Attendees:</h3>";
			$body .= "<ul><li>Attendee 1: " . $pbRegData->attendee_1 . "</li>";
			if ( isset( $pbRegData->attendee_2 ) ) {
				$body .= "<li>Attendee 2: " . $pbRegData->attendee_2 . "</li>";
			}
			if ( isset( $pbRegData->attendee_3 ) ) {
				$body .= "<li>Attendee 3: " . $pbRegData->attendee_3 . "</li>";
			}
			if ( isset( $pbRegData->attendee_4 ) ) {
				$body .= "<li>Attendee 4: " . $pbRegData->attendee_4 . "</li>";
			}
			$body .= "</ul></div>";
			$body .= "<div><p>Total Paid: $" . $pbRegData->amount . "</div>";
			$body .= "<div><p>FinsUp! ";
			$body .= "<p>CTxPHC Support<br />";
			$body .= "Central Texas Parrot Head Club</div>";
			$body .= "</body></html>";

			//mail($to, $subject, $body, $headers);
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
			wp_mail( $to, $subject, $body, $headers );
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

			return $updated;
		}
	}
}

function pb_paid_reg( $pbRegID ) {
	Global $wpdb;
	$table       = "ctxphc_pb_reg";
	$update_data = array( 'Paid' => 'Y' );
	$where       = array( 'pbRegID' => $pbRegID );

	$pb_paid_result = $wpdb->update( $table, $update_data, $where );
	$wpdb->print_error();

	return $pb_paid_result;
}

function admin_del_options() {
	global $_wp_admin_css_colors;
	$_wp_admin_css_colors = 0;
}

add_action( 'admin_head', 'admin_del_options' );


//Remove silly contact methods for users.
function removesilly_contactmethods( $contactmethods ) {
	// here you could add other fields
	$contactmethods[ 'phone' ] = 'Phone'; // Add Phone number
	$contactmethods[ 'yahoo' ] = 'Yahoo Username';

	unset( $contactmethods[ 'aim' ] ); // Remove AIM
	unset( $contactmethods[ 'jabber' ] ); // Remove Jabber
	unset( $contactmethods[ 'yim' ] ); // Remove Website

	return $contactmethods;
}

add_filter( 'user_contactmethods', 'removesilly_contactmethods', 10, 1 );


/********
 * Pirates Ball Registration Confirmation and PayPal processing.
 *******/
add_filter( "gform_pre_submission_filter", "pb_reg_processing" );
function pb_reg_processing( $form ) {
	?>
	<div> <?php
		//displays the types of every field in the form
		foreach ( $form[ "fields" ] as $field ) {
			if ( is_array( $field ) ) {
				//echo "<br/>";
				foreach ( $field as $key => $value ) {
					if ( is_array( $value ) ) {
						echo "<br/>";
						foreach ( $value as $subkey => $subvalue ) {
							echo $subkey . " => " . $subvalue . "<br/>";
						}
						echo "<br/>";
					} else {
						echo $key . " => " . $value . "<br/>";
					}
					echo "<br/>";
				}
			} else {
				echo "field value is $field. <br/>";
			}
		}
		?> </div> <?php
	return $form;
}