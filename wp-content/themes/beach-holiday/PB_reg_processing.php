<?php
/*
Template Name: PB Reg Proc
*/
?>

<?php
  global $defSel, $wpdb;
  $debug = 'false';
  //$debug = 'true';
  $PB_reg_total = 0;
  $item_name = '';
  $item_num = '';
  $cost = 50;
  $pbRegQuantity = 1;
 ?>

<?php $states_arr = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois",'IN'=>"Indiana",'IA'=>"Iowa",'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland",'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming"); ?>

<?php get_header('reg'); ?>

<script type="text/javascript">
   jQuery(document).ready(function(){
      jQuery("#pb_reg_form").validationEngine('attach', {promptPosition : "bottomLeft"});
   });
</script>

<script type="text/javascript">
	jQuery('input:radio[value=attendee_count]').attr('checked', 'checked');
	jQuery(document).ready(function() {
	if (jQuery('input[name=attendee_count]:checked').val() === "1" ) {
		jQuery('#pb_attendee_2').validationEngine('hide');
		jQuery("#pb_attendee_2").css("display","none");
		jQuery('#pb_attendee_3').validationEngine('hide');
		jQuery("#pb_attendee_3").css("display","none");
		jQuery('#pb_attendee_4').validationEngine('hide');
		jQuery("#pb_attendee_4").css("display","none");
	} else if (jQuery('input[name=attendee_count]:checked').val() === "2" ) {
		jQuery("#pb_attendee_2").css("display","block");
		jQuery('#pb_attendee_3').validationEngine('hide');
		jQuery("#pb_attendee_3").css("display","none");
		jQuery('#pb_attendee_4').validationEngine('hide');
		jQuery("#pb_attendee_4").css("display","none");
	} else if (jQuery('input[name=attendee_count]:checked').val() === "3" ) {
		jQuery("#pb_attendee_2").css("display","block");
		jQuery("#pb_attendee_3").css("display","block");
		jQuery('#pb_attendee_4').validationEngine('hide');
		jQuery("#pb_attendee_4").css("display","none");
	} else if (jQuery('input[name=attendee_count]:checked').val() === "4" ) {
		jQuery("#pb_attendee_2").css("display","block");
		jQuery("#pb_attendee_3").css("display","block");
		jQuery("#pb_attendee_4").css("display","block");
	}
	});
</script>

<script>
	jQuery(document).ready(function(){
		jQuery(".pb_attendeeCount").click(function(){
			if (jQuery('input[name=attendee_count]:checked').val() === "1" ) {
				jQuery('#pb_attendee_2').validationEngine('hide');
				jQuery("#pb_attendee_2").css("display","none");
				jQuery('#pb_attendee_3').validationEngine('hide');
				jQuery("#pb_attendee_3").css("display","none");
				jQuery('#pb_attendee_4').validationEngine('hide');
				jQuery("#pb_attendee_4").css("display","none");
			} else if (jQuery('input[name=attendee_count]:checked').val() === "2" ) {
				jQuery("#pb_attendee_2").css("display","block");
				jQuery('#pb_attendee_3').validationEngine('hide');
				jQuery("#pb_attendee_3").css("display","none");
				jQuery('#pb_attendee_4').validationEngine('hide');
				jQuery("#pb_attendee_4").css("display","none");
			} else if (jQuery('input[name=attendee_count]:checked').val() === "3" ) {
				jQuery("#pb_attendee_2").css("display","block");
				jQuery("#pb_attendee_3").css("display","block");
				jQuery('#pb_attendee_4').validationEngine('hide');
				jQuery("#pb_attendee_4").css("display","none");
			} else if (jQuery('input[name=attendee_count]:checked').val() === "4" ) {
				jQuery("#pb_attendee_2").css("display","block");
				jQuery("#pb_attendee_3").css("display","block");
				jQuery("#pb_attendee_4").css("display","block");
			}
		});
	});
</script>

<?php
If (isset($_POST['submit'])) {
	//Load Primary Member data from POST data coming from PB registration page.
	$fname			 = $_POST['fname'];
	$lname			 = $_POST['lname'];
	$email			 = $_POST['email'];
	$addr1			 = $_POST['addr1'];
	$addr2			 = $_POST['addr2'];
	$city			 = $_POST['city'];
	$state			 = $_POST['state'];
	$zip				 = $_POST['zip'];
	$club_affiliation	 = $_POST['club_affiliation'];
	$attendee_count	 = $_POST['attendee_count'];
	$pbRegQuantity		 = (int)$attendee_count;
	$pb_attendee_fname_1 = $_POST['pb_attendee_fname_1'];
	$pb_attendee_lname_1 = $_POST['pb_attendee_lname_1'];

	if (isset($_POST['pb_attendee_fname_2'])) {
	  $pb_attendee_fname_2 = $_POST['pb_attendee_fname_2'];
	  $pb_attendee_lname_2 = $_POST['pb_attendee_lname_2'];
	}

	if (isset($_POST['pb_attendee_fname_3'])) {
	  $pb_attendee_fname_3 = $_POST['pb_attendee_fname_3'];
	  $pb_attendee_lname_3 = $_POST['pb_attendee_lname_3'];
	}

	if (isset($_POST['pb_attendee_fname_4'])) {
	  $pb_attendee_fname_4 = $_POST['pb_attendee_fname_4'];
	  $pb_attendee_lname_4 = $_POST['pb_attendee_lname_4'];
	}

	$pbTable = 'ctxphc_pb_reg';

	//insert into DB
	$pb_userdata = array(
	    'first_name' => $fname,
	    'last_name'  => $lname,
	    'email'	  => $email,
	    'addr1' => $addr1,
	    'addr2' => $addr2,
	    'city'  => $city,
	    'state' => $state,
	    'zip'	  => $zip,
	    'club_aff'	  => $club_affiliation,
	    'quantity'	  => $pbRegQuantity,
	    'amount'	  => $pbRegQuantity * $cost,
	    'reg_date'	  => current_time('mysql'),
	    'attendee_1' => $pb_attendee_fname_1 . ' ' . $pb_attendee_lname_1,
	    'attendee_2' => $pb_attendee_fname_2 . ' ' . $pb_attendee_lname_2,
	    'attendee_3' => $pb_attendee_fname_3 . ' ' . $pb_attendee_lname_3,
	    'attendee_4' => $pb_attendee_fname_4 . ' ' . $pb_attendee_lname_4
	   );

	$inserted = pb_insert_registration_data($pbTable, $pb_userdata);

	If ( $inserted === "FAILED" ) {
	  //Display error message to user with CTXPHC SUPPORT contact info
	  /* ?>
	  <div id="content"><div class="spacer"></div>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			 <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			 <div id="post_title" class="post_title">
			   <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			 </div> <!-- Post_title -->
			 <div class="clear"></div>
			 <div class="entry">
			   <?php the_content('more...'); ?><div class="clear"></div>
			   <div>
				<h2 style="text-align: center;">There was a failure when processing your 2013 Pirate's Ball Registration</h2>
			   </div>
			   <div>CTXPHC technical support has been notified of this event and will be contacting you when this is resolved.</div>
			    <div class="spacer"></div>
			   <div>Thank you,</div>
			   <div>CTXPHC Technical Support</div>

			 </div> <!-- entry -->
			 <div class='spacer'></div>
			 <div class="spacer"></div>
			 </div> <!-- post -->
			 <?php
			 endwhile;
			 endif;
			 ?>
	  </div> <!-- content -->
	    <?php get_sidebar(); ?>
		 <?php get_footer();?>
	  exit;
   <?php */ } else {
       $pbRegID = $inserted;
   }
} else if (isset($_POST['update'])) {
    //Load Primary Member data from POST data coming from PB registration page.
	$fname			=$_POST['fname'];
	$lname			= $_POST['lname'];
	$email			= $_POST['email'];
	$addr1			= $_POST['addr1'];
	$addr2			= $_POST['addr2'];
	$club_affiliation	= $_POST['club_affiliation'];
	$city			= $_POST['city'];
	$state			= $_POST['state'];
	$zip			= $_POST['zip'];
	$pbRegID		= $_POST['pb_regID'];
	$attendee_count         = $_POST['attendee_count'];
	$pbRegQuantity		= (int)$attendee_count;
	$pb_attendee_fname_1    = $_POST['pb_attendee_fname_1'];
	$pb_attendee_lname_1    = $_POST['pb_attendee_lname_1'];

	if ($attendee_count >= 2 && isset($_POST['pb_attendee_fname_3'])) {
	  $pb_attendee_fname_2 = $_POST['pb_attendee_fname_2'];
	  $pb_attendee_lname_2 = $_POST['pb_attendee_lname_2'];

	  if ($attendee_count >= 3 && isset($_POST['pb_attendee_fname_3'])) {
		$pb_attendee_fname_3 = $_POST['pb_attendee_fname_3'];
		$pb_attendee_lname_3 = $_POST['pb_attendee_lname_3'];

		if ($attendee_count === 4 && isset($_POST['pb_attendee_fname_4'])) {
			$pb_attendee_fname_4 = $_POST['pb_attendee_fname_4'];
			$pb_attendee_lname_4 = $_POST['pb_attendee_lname_4'];
		} else {
		  unset($pb_attendee_fname_4);
		  unset($pb_attendee_lname_4);
		}
	  } else {
		  unset($pb_attendee_fname_3);
		  unset($pb_attendee_lname_3);
		  unset($pb_attendee_fname_4);
		  unset($pb_attendee_lname_4);
	  }
	} else {
		unset($pb_attendee_fname_2);
		unset($pb_attendee_lname_2);
		unset($pb_attendee_fname_3);
		unset($pb_attendee_lname_3);
		unset($pb_attendee_fname_4);
		unset($pb_attendee_lname_4);
	}





	$pbTable = 'ctxphc_pb_reg';

	//update db
	$pb_updateData = array(
	    'first_name' => $fname,
	    'last_name'  => $lname,
	    'email'	  => $email,
	    'addr1' => $addr1,
	    'addr2' => $addr2,
	    'city'  => $city,
	    'state' => $state,
	    'zip'	  => $zip,
	    'club_aff'	  => $club_affiliation,
	    'quantity'	  => $pbRegQuantity,
	    'amount'	  => $pbRegQuantity * $cost,
	    'reg_date'	  => current_time('mysql'),
	    'attendee_1' => $pb_attendee_fname_1 . ' ' . $pb_attendee_lname_1,
	    'attendee_2' => $pb_attendee_fname_2 . ' ' . $pb_attendee_lname_2,
	    'attendee_3' => $pb_attendee_fname_3 . ' ' . $pb_attendee_lname_3,
	    'attendee_4' => $pb_attendee_fname_4 . ' ' . $pb_attendee_lname_4
	   );

	$updated = pb_data_update($pbTable, $pbRegID, $pb_updateData);

	If ($updated === false) {
            $to = "support@ctxphc.com";
            $subject = "PB Registration Update Failed!";
            $body = "There was an error when trying to update the DB entry for " . $pb_updateData['first_name'] . ' ' . $pb_updateData['last_name'] . ".\n\n";
            $body .= "The result of the PB DB Update was: $updated \n\n";
            $body .= "The table being updates was $pbTable, the pbRegID was to update was $pbRegID \n\n";
            $body .= "The PB Registration data passed to pb_data_update function was:\n";
            foreach( $pb_updateData as $key => $value ) {
                $body .= $key . " => " . $value . "\n";
            }
            $body .= "\n\nThis was returned when trying to update the PB registration data with the info the user provided.\n\n";
            mail($to, $subject, $body);


	  //Display error message to user with CTXPHC SUPPORT contact info
	  /* ?>
	  <div id="content"><div class="spacer"></div>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			 <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			 <div id="post_title" class="post_title">
			   <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			 </div> <!-- Post_title -->
			 <div class="clear"></div>
			 <div class="entry">
			   <?php the_content('more...'); ?><div class="clear"></div>
			   <div>
				<h2 style="text-align: center;">There was a failure when updating your 2013 Pirate's Ball Registration</h2>
			   </div>
			   <div>CTXPHC technical support has been notified of this event and will be contacting you when this is resolved.</div>
			    <div class="spacer"></div>
			   <div>Thank you,</div>
			   <div>CTXPHC Technical Support</div>

			   <div><?php echo "the result value is: " . $result; //TESTING ?></div>
			   <div><?php echo "the pbRegID value is: " . $pbRegID; //TESTING ?></div>

			 </div> <!-- entry -->
			 <div class='spacer'></div>
			 <div class="spacer"></div>
			 </div> <!-- post -->
			 <?php
			 endwhile;
			 endif;
			 ?>
	  </div> <!-- content -->
	    <?php get_sidebar(); ?>
		 <?php get_footer();?>
	  exit;
   <?php */
            //use email to alert CTXPHC support of failed db update.
        }
}

?>


<div id="content"><div class="spacer"></div>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div id="post_title" class="post_title">
	 <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
    </div> <!-- Post_title -->
    <div class="clear"></div>
    <div class="entry">
	 <?php the_content('more...'); ?><div class="clear"></div>

	 <div>
	   <h2 style="text-align: center;">Review Your 2013 Pirate's Ball Registration</h2>
	 </div>

	 <div class="spacer"></div>

	 <div>
	   <img id='PB_logo'alt="CTXPHC Pirate's Ball 2013 Logo" src="http://www.ctxphc.com/wp-content/Images/Pirates_Ball/Pirates_BallLogo-2013.jpg" />
	 </div>

	 <div class="spacer"></div>

	 <div>
	   Please review your information and make sure it is correct.  Make any changes and click <b>"UPDATE"</b>.  Otherwise, click PayPal's <b>"Buy Now"</b> button to complete your <b>2013 Pirate's Ball Registration</b>.
	 </div>

	 <div class="spacer"></div>

		  <div>
		    The main event of<b> Pirate's Ball will start at 7:00 PM </b>with <b>Live Music, Best Dressed Costume Contest and a Silent Auction to benefit Hope Alliance.</b>
		  </div>

		  <div class="spacer"></div>

		  <div>
		    <img id='PB_CTXPHC_logo' style="float: right;" alt="CTXPHC Logo" src="http://www.ctxphc.com/wp-content/uploads/2011/12/HomePage-Image.jpg" width="233" height="235" />
		    <h4>$50.00 pp registration includes:</h4>

		    <ul>
			 <li><i>Admission to the Pool Party &amp; BBQ</i></li>
			 <li><i>Admission to the Costume Ball</i></li>
			 <li><i>2 drink vouchers for the Ball</i></li>
			 <li><i>Special take home mementos</i></li>
		    </ul>
		  </div>
		  <div>
		    <strong>Saturday afternoon, August 17th, </strong>bring your own drinks and enjoy some sun, fun and <strong>Live Music!</strong>
		  </div>

		  <div class="spacer"></div>

	   <form id="pb_reg_form" name="registration_conformation_form" method="post" action="">
		<fieldset class="pb_reg_form"   id=personal_info>
			<legend>Your Information</legend>
				<div id="personal_info">
					<label id="pb_fname" for="fname">First Name:</label>
						<input type=text value='<?php echo $fname; ?>' class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id="pb_fname" name="fname" />
					<label id="pb_lname" for="lname">Last Name:</label>
						<input type=text value='<?php echo $lname; ?>' class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id="pb_lname" name="lname" />
				</div>

				<div id="pb_email">
					<label id="pb_email" for=email>Email:</label>
						<input class="validate[required, custom[email]]" data-prompt-position="bottomLeft" id="pb_email" name="email" type="text" value='<?php echo $email; ?>' />
					<label id="pb_email_verify" for=email>Verify Email:</label>
						<input class="validate[required, custom[email]]" data-prompt-position="bottomLeft" id="pb_email-verify" name="email-verify" type="text" value='<?php echo $email; ?>' />
				</div>
				<div id='pb_club_affiliation'>
				  <label id="pb_club_affiliation" for=club_affiliation>PHC Affiliation or None:</label>
					<input class="validate[required, custom[ClubAffiliation]]" data-prompt-position="bottomLeft" id="club_affiliation" name="club_affiliation" type="text" value='<?php echo $club_affiliation; ?>' />
				</div>
				<div class='spacer'></div>
		</fieldset>

		<div class='spacer'></div>

		<fieldset class="pb_reg_form" id=address>
			<legend>Address</legend>
				<div>
					<label id="pb_addr1" for="addr1">Address:</label>
						<input id="pb_addr1" name="addr1" type="text" size=35 value='<?php echo $addr1; ?>' class="validate[required, custom[address]]" data-prompt-position="bottomLeft" />
					<label for=addr2>Apt/Suite:</label>
						<input id=pb_addr2 name=addr2 type=text size=35 value='<?php echo $addr2; ?>' class="validate[custom[address-2]]" data-prompt-position="bottomLeft" />
				</div>
				<div>
					<label id=pb_city for=city>City:</label>
						<input id=pb_city name=city type=text size=15 value='<?php echo $city; ?>' class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
					<label id=pb_state for=state>State:</label>
						<select id=pb_state name=state class="validate[required]">
							<?php echo showOptionsDrop($states_arr, $state, true); ?>
						</select>
					<label id=pb_ip for=zip>Zip:</label>
						<input id=pb_zip name=zip type=text size=5 value='<?php echo $zip; ?>' class="validate[required, custom[onlyNumberSp]]" data-prompt-position="bottomLeft" />
						<input type="hidden" value="<?php echo $pbRegID; ?>" name="pb_regID">
				</div>
				<div class='spacer'></div>
		</fieldset>

		<div class="spacer"></div>

		<fieldset class="pb_reg_form"   id=pb_Attend_Info>
			<legend>Attendees</legend>
			<div id="pb_attendee_count">
			   <input class="pb_attendeeCount" id=pb_attendee_count_1 type="radio" name="attendee_count" value="1" <?php if($attendee_count === '1') { ?>checked<?php }; ?> />
			   <label class=pb_attendeeCount for=pb_attendee_count_1>1 Attendee $50</label>
			   <input class="pb_attendeeCount" id=pb_attendee_count_2 type="radio" name="attendee_count" value="2" <?php if($attendee_count === '2') { ?>checked<?php }; ?>/>
			   <label class=pb_attendeeCount for=pb_attendee_count_2>2 Attendees $100</label>
			   <input class="pb_attendeeCount" id=pb_attendee_count_3 type="radio" name="attendee_count" value="3" <?php if($attendee_count === '3') { ?>checked<?php }; ?>/>
			   <label class=pb_attendeeCount for=pb_attendee_count_3>3 Attendees $150</label>
			   <input class="pb_attendeeCount" id=pb_attendee_count_4 type="radio" name="attendee_count" value="4" <?php if($attendee_count === '4') { ?>checked<?php }; ?>/>
			   <label class=pb_attendeeCount for=pb_attendee_count_4>4 Attendees: $200</label>
			</div>

			<div class="spacer"></div>

			<div id='pb_attendee_1'>
			  <label id=pb_attendee_fname for=pb_attendee_fname_1>First Name:</label>
					<input id=pb_attendee_fname name=pb_attendee_fname_1 type=text value='<?php echo $pb_attendee_fname_1; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
				<label id=pb_attendee_lname for=pb_attendee_lname_1>Last Name:</label>
					<input id=pb_attendee_lname name=pb_attendee_lname_1 type=text value='<?php echo $pb_attendee_lname_1; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
			 </div>
			 <div id='pb_attendee_2'>
				<label id=pb_attendee_fname for=pb_attendee_fname_2>First Name:</label>
					<input id=pb_attendee_fname name=pb_attendee_fname_2 type=text value='<?php echo $pb_attendee_fname_2; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
				<label id=pb_attendee_lname for=pb_attendee_lname_2>Last Name:</label>
					<input id=pb_attendee_lname name=pb_attendee_lname_2 type=text value='<?php echo $pb_attendee_lname_2; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
			 </div>
			 <div id='pb_attendee_3'>
				<label id=pb_attendee_fname for=pb_attendee_fname_2>First Name:</label>
					<input id=pb_attendee_fname name=pb_attendee_fname_3 type=text value='<?php echo $pb_attendee_fname_3; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
				<label id=pb_attendee_lname for=pb_attendee_lname_2>Last Name:</label>
					<input id=pb_attendee_lname name=pb_attendee_lname_3 type=text value='<?php echo $pb_attendee_lname_3; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
			 </div>
			 <div id='pb_attendee_4'>
				<label id=pb_attendee_fname for=pb_attendee_fname_4>First Name:</label>
					<input id=pb_attendee_fname name=pb_attendee_fname_4 type=text value='<?php echo $pb_attendee_fname_4; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
				<label id=pb_attendee_lname for=pb_attendee_lname_4>Last Name:</label>
					<input id=pb_attendee_lname name=pb_attendee_lname_4 type=text value='<?php echo $pb_attendee_lname_4; ?>' class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" />
			 </div>
			 <div class='spacer'></div>
		</fieldset>

		<div class='spacer'></div>

		<div id=update>
		  <fieldset class="screen">
		    <legend class="screen">Update</legend>
		    <div class=screen>If you have made any changes to the form please click:</div>
		    <div>
			 <input class="button3 screen" id="update" type=submit name="update" value="update">
		    </div>
		  </fieldset>
		</div>
		</form>

		<div class='spacer'></div>

		<div id=payment>
		  <fieldset class="screen"id=payOptions>
		    <legend class="screen">Payment Options</legend>
			 <div class="screen">Once you are satisfied the information displayed is correct click your preferred payment option.  PayPal will allow you to use most credit cards or your PayPal account.</div>

			 <div class='spacer'></div>

			   <div>
				<?php if ($debug === "true") {  // Using PayPal's SANDBOX server for payment testing ?>
				  <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
				    <input type="hidden" name="cmd" value="_s-xclick">
				    <input type="hidden" name="hosted_button_id" value="X7JALCX78Y6Z4">
				    <input type="hidden" name="quantity" value="<?php echo $pbRegQuantity;?>"/>
				    <input type="hidden" name="custom" value="<?php echo $pbRegID;?>"/>
				    <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				  </form>

			   <?php  } else { // Using PayPal's PRODUCTION server for payment processing ?>
				  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				    <input type="hidden" name="cmd" value="_s-xclick">
				    <input type="hidden" name="hosted_button_id" value="DU9MPK4H5L3ZQ">
				    <input type="hidden" name="quantity" value="<?php echo $pbRegQuantity;?>"/>
				    <input type="hidden" name="custom" value="<?php echo $pbRegID;?>"/>
				    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				  </form>
			   <?php } ?>
			   </div>
		  </fieldset>
		</div>
    </div> <!-- entry -->

    <div class='spacer'></div>
    <div class="spacer"></div>

    </div> <!-- post -->

	 <?php
	 endwhile;
	 endif;
	 ?>
</div> <!-- content -->
<?php get_sidebar(); ?>
<?php get_footer();?>