<?php
/*
Template Name: Order_Confirmation
*/
?>
<?php

global $wpdb, $pmntID, $userInfo;
$wpdb->show_errors();
$testing = 'false';
$debug = 'true';
$renewal = 'FALSE';

if ($debug) {
	$to = "support@ctxphc.com, kaptkaos@gmail.com";
} else {
	$to = "membership@ctxphc.com, support@ctxphc.com";
}
?>

<?php get_header('reg'); ?>
<div id="content"><div class="spacer"></div>
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		 <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post_title">
			   <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			</div> <!-- post_title -->
			<div class="clear"></div>
			<div class="entry">
		   <?php the_content('more...'); ?><div class="clear"></div>
			<?php
			if ($_POST['check'] == 'Check') {
				//Check Processing and Thank you text
				if ($_POST['item_number'] == 'Renewal') {$renewal = 'TRUE';}
				$pmntID = $_POST['pmntID'];
				$userID = $_POST['userID'];
				$key = 'user_pending';
				$single = 'true';
				$pending = get_user_meta($userID, $key, $single);

				if ($pending == 'FALSE') {
					$value = 'TRUE';
					$result = update_user_meta($userID, $key, $value);
					If ($result == 'False') {
						//send email with error!
						$subject = "Failed to set Pending state";
						$body = "failed to update pending status to TRUE from FALSE!";
						mail($to, $subject, $body);
					}
				}

				$userData = get_userdata($userID);
				$nicename = $userData->user_nicename;

				//display completion message for renewing or new member
				if ($renewal == 'TRUE') {
					//send email to membership and support
					$subject = "New CTXPHC Membership Renewal(CHECK)!";
					$body = $nicename . "has completed a renewal and will be paying by check.";
					mail($to, $subject, $body);

					//Display welcome message for membership renewal?>
					<div>Thank you <?php echo $nicename; ?> for renewing your membership with the Central Texas Parrothead Club!</div>
					<div>We look forward to seeing you at an upcoming event soon!</div>
					<div class="spacer"></div>
					<div>Mail the printed renewal form and your check to:</div>
					<div class="spacer"></div>
					<div>CTXPHC Membership</div>
					<div>700 Brown Dr</div>
					<div>Pflugerville, TX 78660</div>
					<div class="spacer"></div>
					<?php
				} else {
					//send email to membership and support
					$subject = "New CTXPHC Registration pay by check!";
					$body = $nicename . "has completed a membership regisration and will be paying by check.";
					mail($to, $subject, $body);

					//Display welcome message for new membership ?>
					<div>Thank you <?php echo $nicename; ?> for joining the Central Texas Parrothead Club!</div>
					<div> We look forward to seeing you at an upcoming event soon!</div>
					<div class="spacer"></div>
					<div> Mail the printed regisration form and your check to:</div>
					<div class="spacer"></div>
					<div> CTXPHC Membership</div>
					<div> 700 Brown Dr</div>
					<div> Pflugerville, TX 78660</div>
					<div class="spacer"></div>
				<?php }
			} elseif ($_GET) {

				//Initialize debug/error logging
				ini_set('log_errors', true);
				ini_set('error_log', dirname(__FILE__).'/pp_pdt_errors.log');

				// If testing use PayPal Sandbox(testing) server: www.sandbox.paypal.com
				// Otherwise use PayPal Production server: www.paypal.com
				if ($testing == 'true') {
					$pp_hostname = "www.sandbox.paypal.com";
				} else {
					$pp_hostname = "www.paypal.com";
				}

				// read the post from PayPal system and add 'cmd'
				$req = 'cmd=_notify-synch';

				$tx_token = $_GET['tx'];
				$auth_token = "SJd2IDmU79iIPadE5Rjp9G-jQgWYyAZf7FAUO5F6pvRACHJcEkimuyyxr0a";
				$req .= "&tx=" . $tx_token . "&at=" .$auth_token;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

				// In wamp like environments that do not come bundled with root authority certificates,
				// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
				// of the certificate as shown below.
				curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
				$res = curl_exec($ch);
				curl_close($ch);

				if(!$res){
					//HTTP ERROR
					$body = "PayPal Payment Data Transfer(PDT) failed with an HTTP ERROR! \n\n";
					$body .= "Below is the PDT data that was delivered: \n\n";
					$body .= $res . "\n\n";
					$body .= "Here is what was sent to PayPal: \n\n";
					$body .= $req;
					$subject = "PDT Processing for Renewal: HTTP ERROR";
					mail($to, $subject, $body);
				} else {
					 // parse the data
					$lines = explode("\n", $res);
					$keyarray = array();

					if (strcmp ($lines[0], "SUCCESS") == 0) {
						for ($i=1; $i<count($lines);$i++){
						list($key,$val) = explode("=", $lines[$i]);
						$keyarray[urldecode($key)] = urldecode($val);
						}
						// check the payment_status is Completed
						// check that txn_id has not been previously processed
						// check that receiver_email is your Primary PayPal email
						// check that payment_amount/payment_currency are correct
						// process payment
						if ($keyarray['item_number'] == 'Renewal') {$renewal = 'TRUE';}
						$firstname = $keyarray['first_name'];
						$lastname = $keyarray['last_name'];
						$itemname = $keyarray['item_name'];
						$amount = $keyarray['payment_gross'];

						if ($renewal) {
							echo "<div><p><h3>Thank you for your membership renewal/purchase!</h3></p></div>";
						} else {
							echo "<div><p><h3>Thank you for completing your Central Texas Parrothead Club registration!!</h3></p></div>";
						} ?>

						<div><b>Payment Details</b></div>
						<div class="spacer"></div>
						<div>
							<li>Name: $firstname $lastname</li>
							<li>Item: $itemname</li>
							<li>Amount: $amount</li>
						</div>
						<div class="spacer"></div>
						<div>Your transaction has been completed, and a receipt for your renewal has been emailed to you. You may log into your PayPal account at www.paypal.com/us to view details of this transaction.</div>

						<?php
						$body = "PayPal Payment Data Transfer was sucessfully processed! \n\n";
						$body .= "Below is the PDT data that was processed: \n\n";
						$body .= $res . "\n\n";
						$subject = "PDT Processing for Renewal: Sucessful";
						mail($to, $subject, $body);
					} else if (strcmp ($lines[0], "FAIL") == 0) {
						// log for manual investigation
						$error_message = "There was an error processing the PayPal PDT data!\n\n";
						$error_message .= "please review the PDT data for any errors. \n\n";
						$error_message .= $res . "\n\n";
						error_log($error_message);

						$subject = "PDT Processing of Renewal:  FAILED";
						mail($to, $subject, $error_message);
					}
				}
			} else {
				$body = "<div>Did not get to this page via post from membership processing or from PDT response!</div>";
				$body .= "<div>Review database for issues!</div>";
				$subject = "Renewal/Membership PDT Processing:Invalid Access! ";
					mail($to, $subject, $body);
			}
				?>

			   <div class="clear"></div>
            </div> <!-- entry -->
         </div> <!-- post -->

<?php
      endwhile;
   endif;
?>
</div> <!-- content -->
<?php get_sidebar(); ?>
<!-- start footer -->
<?php get_footer();?>
<!-- end footer -->

