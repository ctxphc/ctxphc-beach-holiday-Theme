<?php
/* Template Name: Membership */
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 2/21/2016
 * Time: 7:18 PM
 */

add_action( 'gform_after_submission', 'get_entry_data', 10, 2 );
function get_entry_data() {

	if ( $submit = 'registration' ) {
		$form_id = 1;
	} else {
		$form_id = 5;
	}


	//Get all entries for registration or renewal forms
	$entries = GFAPI::get_entries( $form_id );

	//Get the most recent entry
	$entry = GFAPI::get_entry( $entry_id );

	//get all entry data
	$field_data = array(
		'$date_created'   => rgar( $entry, 'date_created' ), // returns the entry date
		'membership_type' => rgar( $entry, '1' ),       // Membership Options.
		'm_first_name'    => rgar( $entry, '41.3' ),    // returns the members first name.
		'm_last_name'     => rgar( $entry, '41.6' ),    // returns the members last name.
		'm_email'         => rgar( $entry, '6' ),       // return the members email address.
		'm_phone'         => rgar( $entry, '7' ),       // returns the members phone.
		'm_birthday'      => rgar( $entry, '5' ),        // returns the spouses birthday
		'm_occupation'    => rgar( $entry, '23' ),      // returns the members occupation.
		'addr1'           => rgar( $entry, '10.1' ),    // returns the address field
		'addr2'           => rgar( $entry, '10.2' ),    // returns the suite/apt # field
		'city'            => rgar( $entry, '10.3' ),    // returns the city.
		'state'           => rgar( $entry, '10.4' ),    // returns the state.
		'zip'             => rgar( $entry, '10.5' ),    // returns the zip code.
		's_first_name'    => rgar( $entry, '12.3' ),    // returns the spouses first name.
		's_last_name'     => rgar( $entry, '12.6' ),    // returns the spouses last name.
		's_email'         => rgar( $entry, '13' ),       // return the spouses email address.
		's_phone'         => rgar( $entry, '14' ),       // returns the spouses phone.
		's_birthday'      => rgar( $entry, '15' ),        // returns the spouses birthday
		's_relationship'  => rgar( $entry, '22' ),        // Returns the spouses relationship status
		'c1_first_name'   => rgar( $entry, '17.3' ),    // returns child 1 first name.
		'c1_last_name'    => rgar( $entry, '17.6' ),    // returns child 1 last name.
		'c1_email'        => rgar( $entry, '18' ),       // return child 1 email address.
		'c1_phone'        => rgar( $entry, '20' ),       // returns child 1 phone.
		'c1_birthday'     => rgar( $entry, '33' ),        // returns child 1 birthday
		'c1_relationship' => rgar( $entry, '39' ),        // Returns child 1 relationship status
		'c2_first_name'   => rgar( $entry, '27.3' ),    // returns child 2 first name.
		'c2_last_name'    => rgar( $entry, '27.6' ),    // returns child 2 last name.
		'c2_email'        => rgar( $entry, '31' ),       // returns child 2 email address.
		'c2_phone'        => rgar( $entry, '35' ),       // returns child 2 phone.
		'c2_birthday'     => rgar( $entry, '32' ),        // returns child 2 birthday
		'c2_relationship' => rgar( $entry, '40' ),        // Returns child 2 relationship status
		'c3_first_name'   => rgar( $entry, '26.3' ),    // returns child 3 first name.
		'c3_last_name'    => rgar( $entry, '26.6' ),    // returns child 3 last name.
		'c3_email'        => rgar( $entry, '30' ),       // returns child 3 email address.
		'c3_phone'        => rgar( $entry, '36' ),       // returns child 3 phone.
		'c3_birthday'     => rgar( $entry, '19' ),        // returns child 3 birthday
		'c3_relationship' => rgar( $entry, '21' ),        // Returns child 3 relationship status
		'c4_first_name'   => rgar( $entry, '25.3' ),    // returns child 4 first name.
		'c4_last_name'    => rgar( $entry, '25.6' ),    // returns child 4 last name.
		'c4_email'        => rgar( $entry, '29' ),       // returns child 4 email address.
		'c4_phone'        => rgar( $entry, '37' ),       // returns child 4 phone.
		'c4_birthday'     => rgar( $entry, '34' ),        // returns child 4 birthday
		'c4_relationship' => rgar( $entry, '38' ),        // Returns child 4 relationship status
	);

	email_membership_directory( $field_data );
	//post_to_paypal( $field_data );
}

function email_membership_directory( $memb_data ) {
	$to[] = "support@ctxphc.com";

	$subject = "New Membership Registration Form Submitted";

	$headers[] = "From: Central Texas Parrot Head Club<support@ctxphc.com>";
	$headers[] = "Reply-To: support@ctxphc.com";
	$headers[] = "MIME-Version: 1.0\r\n";
	$headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$body = "<html><body>";

	foreach ( $memb_data as $membkey => $membvalue ) {
		$body .= '<div>' . $membkey . ': ' . $membvalue . '</div>';
	}

	$body .= "<p>FinsUp! ";
	$body .= "<p>CTxPHC Support<br />";
	$body .= "Central Texas Parrot Head Club</div>";
	$body .= "</body></html>";

	add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	wp_mail( $to, $subject, $body, $headers );
	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}


function post_to_paypal( $trans_data ) { //TODO: This needs work and testing!!!!!!

	$pp_button_fields =
	$post_url = 'http://thirdparty.com';
	$body             = array(
		'first_name' => rgar( $entry, '1.3' ),
		'last_name'  => rgar( $entry, '1.6' ),
		'message'    => rgar( $entry, '3' ),
	);


	//log.LogLevel can be any of: FINE, INFO, WARN or ERROR
	$config  = array(
		'mode'           => 'sandbox',
		'acct1.UserName' => 'jb-us-seller_api1.paypal.com',
		'acct1.Password' => 'WX4WTU3S8MY44S7F',
		'log.LogEnabled' => 'true',
		'log.FileName'   => 'PayPal.log',
		'log.LogLevel'   => 'INFO',
	);
	$service = new PayPalAPIInterfaceServiceService( $config );
	$service->SetExpressCheckout();

	$service = new AdaptivePaymentsService( $config );
	$service->Pay();


	$request  = new WP_Http();
	$response = $request->post( $post_url, array( 'body' => $body ) );

}
