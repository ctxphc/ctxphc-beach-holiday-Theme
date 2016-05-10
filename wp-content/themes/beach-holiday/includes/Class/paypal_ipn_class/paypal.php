<?php

/*  PHP Paypal IPN Integration Class Demonstration File
 *  6.25.2008 - Eric Wang, http://code.google.com/p/paypal-ipn-class-php/
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *	If you want submit a payment form to Paypal sandbox. Please add the
 *	_GET[sandbox=1] parameter to link. ie: paypal.php?sandbox=1.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/

define('EMAIL_ADD', 'support@ctxphc.com'); // For system notification.
define('PAYPAL_EMAIL_ADD', 'paypal@ctxphc.com');

// Setup class
require_once('paypal_class.php');  // include the class file
$pp_ipn = new paypal_class( ); 				 // initiate an instance of the class.
$pp_ipn -> admin_mail = EMAIL_ADD;
//$pp_ipn -> paypal_mail = PAYPAL_EMAIL_ADD;  // If set, class will verify the receiver.

switch ($_GET['action']) {

    default:      // Process and order...

        // There should be no output at this point.  To process the POST data,
        // the submit_paypal_post() function will output all the HTML tags which
        // contains a FORM which is submited instantaneously using the BODY onload
        // attribute.  In other words, don't echo or printf anything when you're
        // going to be calling the submit_paypal_post() function.

        // adds or edits a "$pp_ipn->add_field(key, value);" in following, which is what will be
            // sent to paypal as POST variables. Refer to PayPal HTML Variables:
            // https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables

        // This is where you would have your form validation  and all that jazz.
        // You would take your POST vars and load them into the class like below,
        // only using the POST values instead of constant string expressions.

        // setup a current URL variable for this script
        $this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        $pp_ipn->add_field('business', PAYPAL_EMAIL_ADD); //don't need add this item. if your set the $pp_ipn -> paypal_mail.
        $pp_ipn->add_field('return', $this_script.'?action=success');
        $pp_ipn->add_field('cancel_return', $this_script.'?action=cancel');
        $pp_ipn->add_field('notify_url', $this_script.'?action=ipn');
        $pp_ipn->add_field('item_name', 'Paypal Test Transaction');
        $pp_ipn->add_field('cmd', '_donations');
        $pp_ipn->add_field('rm', '2');	// Return method = POST

        $pp_ipn->submit_paypal_post(); // submit the fields to paypal
        $pp_ipn->dump_fields();      // for debugging, output a table of all the fields
        break;

   case 'success':      // Order was successful...

      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.

      echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
      foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
      echo "</body></html>";

      // You could also simply re-direct them to another page, or your own
      // order status page which presents the user with the status of their
      // order based on a database (which can be modified with the IPN code
      // below).

      break;

   case 'cancel':       // Order was canceled...

      // The order was canceled before being completed.

      echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
      echo "</body></html>";

      break;

   case 'ipn':          // Paypal is calling page for IPN validation...

      // It's important to remember that paypal is calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.

      if ($pp_ipn->validate_ipn()) {

         // Payment has been received and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.

         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.

         // For this example, we'll just email ourselves ALL the data.
				 $subject = 'Instant Payment Notification - Received Payment';
				 $pp_ipn->send_report ( $subject );
      } else {
				 $subject = 'Instant Payment Notification - Payment Failed';
			 	 $pp_ipn->send_report ( $subject );
    	}
      break;
 }