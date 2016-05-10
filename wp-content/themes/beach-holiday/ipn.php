<?php
$debug = true;
$testing = false;

if ($debug == true) {
	$to = 'kaptkaos@gmail.com, support@ctxphc.com';
} else { 
	$to = 'membership@ctxphc.com, support@ctxphc.com, kaptkaos@gmail.com';
}

// STEP 1: Read POST data

// reading posted data directly from $_POST causes serialization 
// issues with array data in POST
// reading raw POST data from input stream instead. 
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
     $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 
foreach ($myPost as $key => $value) {        
   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
   } else {
        $value = urlencode($value);
   }
   $req .= "&$key=$value";
}

 
// STEP 2: Post IPN data back to paypal to validate

$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

// In wamp like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');

if( !($res = curl_exec($ch)) ) {
	$subject = "Got cURL Error Processing IPN Data";
	$body = "Got " . curl_error($ch) . " when processing IPN data";
	mail($to, $subject, $body);
    error_log("Got " . curl_error($ch) . " when processing IPN data");
    curl_close($ch);
    exit;
}
curl_close($ch);
 

// STEP 3: Inspect IPN validation result and act accordingly
if (strcmp ($res, "VERIFIED") == 0) {
    // check whether the payment_status is Completed	
	if ($_POST['payment_status'] != 'Completed') { 
        // Email IPN data for review if not completed
		$body = "IPN Transaction was not completed: \n\n$errmsg\n\n";
        $body .= $req;
        mail($to, 'IPN Fraud Warning: TRX NOT Complete', $body);
        exit(0); 
    }
	
    // check that txn_id has not been previously processed
	$errmsg = '';   // stores errors from fraud checks
	$pmntID = $_POST['custom']; //payment id fpassed in customer field of IPN data.
	$userID = $wpdb->get_var("select pmnt_user from ctxphc_membership_payments where pmnt_id = {$pmntID}");
	$ipn_txn_id = $_POST['txn_id'];
	$txnID = $wpdb->get_var("select txn_id from ctxphc_membership_payments where pmnt_id = {$pmntID}");
	if ($txnID == $ipn_txn_id) {
		$errmsg .= "IPN Duplicate transaction record!\n\n";
		$errmsg .= $ipn_txn_id . " has already been processed!";
	}
	
    // check that receiver_email is your Primary PayPal email
	if ($testing == true) {
		if ($_POST['receiver_email'] != 'seller@paypalsandbox.com') {
			$errmsg .= "'receiver_email' does not match: ";
			$errmsg .= $_POST['receiver_email']."\n\n";
		}
	} else {
		if ($_POST['receiver_email'] != 'paypal@ctxphc.com') {
			$errmsg .= "'receiver_email' does not match: ";
			$errmsg .= $_POST['receiver_email']."\n\n";
		}
	}
	
    // check that payment_amount matches the item purchased
	if ($_POST['item_name'] == 'Single') {
		if ($_POST['mc_gross'] != '25.00') {
			$errmsg .= "'item_name' 'mc_gross' mismatch: " . $_POST['item_name'] . " : " . $_POST['mc_gross'] . ".\n\n";
		}
	} elseif ($_POST['item_name'] == 'Couple') {
		if ($_POST['mc_gross'] != '40.00') {
			$errmsg .= "'item_name' 'mc_gross' mismatch: " . $_POST['item_name'] . " : " . $_POST['mc_gross'] . ".\n\n";
		}
	} elseif ($_POST['item_name'] == 'Family') {
		if ($_POST['mc_gross'] != '45.00') {
			$errmsg .= "'item_name' 'mc_gross' mismatch: " . $_POST['item_name'] . " : " . $_POST['mc_gross'] . ".\n\n";
		}
	} else {
		$errmsg .= "'item_name' is invalid: " . $_POST['item_name'] . ".\n\n";
	}

    // check that the currency code matches
    if ($_POST['mc_currency'] != 'USD') {
        $errmsg .= "'mc_currency' does not match: ";
        $errmsg .= $_POST['mc_currency']."\n\n";
    }
	
	//If there was any fraud alerts created email them!
	if (!empty($errmsg)) {
		// Eamil errors from fraud alerets.  Manually investigate
		$subject = 'IPN Fraud Warning';
		$body = "IPN failed fraud checks: \n\n$errmsg\n\n";
		$body .= $req;
		mail($to, $subject, $body);
	} else {
    // process payment
		if ($_POST['item_number'] == 'Renewal') {
			$userINFO = get_user_by('id',$userID);
			$userName = $userINFO->user_nicename;
			$userEmail = $userINFO->user_email;
			
			//Process Membership Renewal
			if (get_user_meta($userID, 'user_pending', true) == 'TRUE') {
				update_user_meta($userID, 'user_pending', 'FALSE');
				update_user_meta($userID, 'user_renewed', $_POST['payment_date']);
			}
			
			//Update Payment Record
			$result = $wpdb->update( 
				'ctxphc_member_payment',
				array(
					'ipn_txn_id'	=> $ipn_txn_id,
					'ipn_data'		=> $req,
					'ipn_date'		=> $_POST['payment_date'],
					'pmnt_complete'	=> 'Y'
				),
				array(
					'pmnt_id' => $pmntID
				)
			);
			
			// Remove pmnt_id from user's meta data and
			// prepare email to membership or email support 
			// if there is an error with removing the pmnt_id
			if ($result > 0) {
				If (delete_user_meta( $userID, 'pmnt_id', $pmntID)) {
					$body = "The delete of the user_meta payment ID failed!\n\n";
					$body .= "Attempted to delete user id " . $userID . ".\n\n";
					$body .= "Using meta_key pmnt_id and meta_value " . $pmntID . ".\n\n";
					$body .= "last wpdb error was " . $last_error . ".\n\n";
					$subject = 'Delete of pmntID from usermeta FAILED';
				} else {
					$subject = 'Sucessful Membership Renewal';
					$body = "A PayPal membership renewal has been completed for " . $userName . " " . $userEmail . "\n\n";
				}
			} elseif ($result === 0) {
				$subject = "Member Payment DB ERROR";
				$body = "After processing the IPN data from PayPal, the update of the ctxphc_member_payment table returned no rows updated!\n\n";
				$body .= "Attempted to update the following:\n\n";
				$body .= "The user id was " . $userID . ".  The meta_key was pmnt_id and the meta_value was " . $pmntID . "\n\n";
				$body .= "ipn_txn_id: " . $ipn_txn_id . "\n\n";
				$body .= "ipn_date: " . $_POST['payment_date'] . "\n\n";
				$body .= "ipn_data: " . $req . "\n\n";
				
			} else {
				$subject = 'Member Payment DB ERROR';
				$body = "After processing the IPN data from PayPal, the update of the ctxphc_member_payment table failed!\n\n";
				$body .= "Attempted to update the following:\n\n";
				$body .= "ipn_txn_id: " . $ipn_txn_id . "\n\n";
				$body .= "ipn_date: " . $_POST['payment_date'] . "\n\n";
				$body .= "ipn_data: " . $req . "\n\n";
				
			}
			mail($to, $subject, $body);
			
		} else { 
			//Process New Membership
			//TODO: Create New Member Processing
			$subject = 'New Member Registration';
			$body = "PayPal New Member Registration has been completed for " . $userName . " " . $userEmail . "\n\n";
			mail($to, $subject, $req);
		}
	}
} else if (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
	$subject = "PayPal Returned INVALID!!!";
	$body = "Got PayPal INVALID response when processing IPN data\n\n";
	$body .= $req;
	mail($to, $subject, $body);
    error_log("Got PayPal INVALID response when processing IPN data");
	error_log($req);
}
?>