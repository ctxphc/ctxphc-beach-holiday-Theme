<?php
/*
Template Name: Reg_Payment
*/
?>


<?php
global $wpdb;

function set_variables() {


}

function add_to_db() {

}
// Process the post data into local variables
if ($_POST['check']) {
	switch ($_POST[mem_type]) {
		case "single";
			$memType = "Single";
			$memCost = "25.00";
			break;
		case "couple";
			$memType = "Couple";
			$memCost = "40.00";
			break;
		case "family";
			$memType = "Family";
			$memCost = "45.00";
			break;
	}

	$fname=$_POST['fname'];
	$lname=$_POST['lname'];
	$email=$_POST['email'];
	$phone=$_POST['phone1'] . "-" . $_POST['phone2'] . "-" . $_POST['phone3'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	$occu=$_POST['occu'];
	$addr1=$_POST['addr1'];
	$city=$_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	if ($_POST['profile'] == "checked") {
		$share = 1;
	} else {
		$share = 0;
	}
	if ($_POST['contact'] == "checked") {
		$contact = 1;
	} else {
		$contact = 0;
	}
	$username = $_POST['username'];
	$pass = $_POST['pass'];

	if ($memType != "Single") {
	// Spouse or Partner info
		if ($_POST['sfname']) { $s_fname=$_POST['sfname']; }
		if ($_POST['slname']) { $s_lname=$_POST['slname']; }
		if ($_POST['semail']) { $s_email=$_POST['semail']; }
		if ($_POST['sphone1']) { $s_phone=$_POST['sphone1'] . "-" . $_POST['sphone2'] . "-" . $_POST['sphone3']; }
		if ($_POST['smonth']) { $s_month=$_POST['smonth']; }
		if ($_POST['sday']) { $s_day=$_POST['sday']; }
		if ($_POST['srelationship']) { 
			switch ($_POST['srelationship']) {
				case "S";
					$s_rel = "Spouse";
					break;
				case "P";
					$s_rel = "Partner";
					break;
				case "O";
					$s_rel = "Other";
					break;
			}
		}
		if ($_POST['s_username']) { $s_username = $_POST['s_username']; }
		if ($_POST['s_pass']) { $s_pass = $_POST['s_pass'];}
	}

	if ($memType = "Family") {
		// 1st family member info
		if ($_POST['f1name']) { $f1_name=$_POST['f1name']; }
		if ($_POST['f1relationship'] == "C") {
			$f1_rel = "Child";
		} else {	
			$f1_rel = "Other";
		}
		if ($_POST['f1month']) { $f1_month=$_POST['f1month']; }
		if ($_POST['f1day']) { $f1_day=$_POST['f1day']; }
		if ($_POST['f1email']) { $f1_email=$_POST['f1email']; }
		// 2nd family member info
		if ($_POST['f2name']) { $f2_name=$_POST['f2name']; }
		if ($_POST['f2relationship'] == "C") {
			$f2_rel = "Child";
		} else {	
			$f2_rel = "Other";
		}
		if ($_POST['f2month']) { $f2_month=$_POST['f2month']; }
		if ($_POST['f2day']) { $f2_day=$_POST['f2day']; }
		if ($_POST['f2email']) { $f2_email=$_POST['f2email']; }
		// 3rd family member info
		if ($_POST['f3name']) { $f3_name=$_POST['f3name']; }
		if ($_POST['f3relationship'] == "C") {
			$f3_rel = "Child";
		} else {	
			$f3_rel = "Other";
		}
		if ($_POST['f3month']) { $f3_month=$_POST['f3month']; }
		if ($_POST['f3day']) { $f3_day=$_POST['f3day']; }
		if ($_POST['f3email']) { $f3_email=$_POST['f3email']; }
	}
} else {
   switch ($_POST[mem_type]) {
      case "single";
         $memType = "Single";
         $memCost = "25.00";
         break;
      case "couple";
         $memType = "Couple";
         $memCost = "40.00";
         break;
      case "family";
         $memType = "Family";
         $memCost = "45.00";
         break;
   }

   $fname=$_POST['fname'];
   $lname=$_POST['lname'];
   $email=$_POST['email'];
   $phone=$_POST['phone1'] . "-" . $_POST['phone2'] . "-" . $_POST['phone3'];
   $month=$_POST['month'];
   $day=$_POST['day'];
   $occu=$_POST['occu'];
   $addr1=$_POST['addr1'];
   $city=$_POST['city'];
   $state = $_POST['state'];
   $zip = $_POST['zip'];
   if ($_POST['profile'] == "checked") {
      $share = 1;
   } else {
      $share = 0;
   }
   if ($_POST['contact'] == "checked") {
      $contact = 1;
   } else {
      $contact = 0;
   }
   $username = $_POST['username'];
   $pass = $_POST['pass'];

   if ($memType != "Single") {
   // Spouse or Partner info
      if ($_POST['sfname']) { $s_fname=$_POST['sfname']; }
      if ($_POST['slname']) { $s_lname=$_POST['slname']; }
      if ($_POST['semail']) { $s_email=$_POST['semail']; }
      if ($_POST['sphone1']) { $s_phone=$_POST['sphone1'] . "-" . $_POST['sphone2'] . "-" . $_POST['sphone3']; }
      if ($_POST['smonth']) { $s_month=$_POST['smonth']; }
      if ($_POST['sday']) { $s_day=$_POST['sday']; }
      if ($_POST['srelationship']) {
         switch ($_POST['srelationship']) {
            case "S";
               $s_rel = "Spouse";
               break;
            case "P";
               $s_rel = "Partner";
               break;
            case "O";
               $s_rel = "Other";
               break;
         }
      }
      if ($_POST['s_username']) { $s_username = $_POST['s_username']; }
      if ($_POST['s_pass']) { $s_pass = $_POST['s_pass'];}
   }

   if ($memType = "Family") {
      // 1st family member info
      if ($_POST['f1name']) { $f1_name=$_POST['f1name']; }
      if ($_POST['f1relationship'] == "C") {
         $f1_rel = "Child";
      } else {
         $f1_rel = "Other";
      }
      if ($_POST['f1month']) { $f1_month=$_POST['f1month']; }
      if ($_POST['f1day']) { $f1_day=$_POST['f1day']; }
      if ($_POST['f1email']) { $f1_email=$_POST['f1email']; }
      // 2nd family member info
      if ($_POST['f2name']) { $f2_name=$_POST['f2name']; }
      if ($_POST['f2relationship'] == "C") {
         $f2_rel = "Child";
      } else {
         $f2_rel = "Other";
      }
      if ($_POST['f2month']) { $f2_month=$_POST['f2month']; }
      if ($_POST['f2day']) { $f2_day=$_POST['f2day']; }
      if ($_POST['f2email']) { $f2_email=$_POST['f2email']; }
      // 3rd family member info
      if ($_POST['f3name']) { $f3_name=$_POST['f3name']; }
      if ($_POST['f3relationship'] == "C") {
         $f3_rel = "Child";
      } else {
         $f3_rel = "Other";
      }
      if ($_POST['f3month']) { $f3_month=$_POST['f3month']; }
      if ($_POST['f3day']) { $f3_day=$_POST['f3day']; }
      if ($_POST['f3email']) { $f3_email=$_POST['f3email']; }
   }


}

?>

<?php get_header('print'); ?>
<div id="content"><div class="spacer"></div>
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
         <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="post_title">
               <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
               <span class="post_author">Author: <?php the_author_posts_link('nickname'); ?><?php edit_post_link(' Edit ',' &raquo;','&laquo;'); ?></span>
               <span class="post_date_m"><?php the_time('M');?></span>
               <span class="post_date_d"><?php the_time('d');?></span>
            </div> <!-- Post_title -->
            <div class="clear"></div>
            <div class="entry">
               <?php the_content('more...'); ?><div class="clear"></div>
<?php

//$wpdb->show_errors();


//   Add Data to the Database marking member as pending
If ($fname != "") {
	$wpdb->insert( 
		'wp_ctxphc_members', 
		array( 
			'memb_fname' => $fname, 
			'memb_lname' => $lname, 
			'memb_email' => $email,
			'memb_phone' => $phone,
			'memb_bday_month' => $month,
			'memb_bday_day' => $day,
			'memb_occup' => $occu,
			'memb_addr' => $addr1,
			'memb_city' => $city,
			'memb_state' => $state,
			'memb_zip' => $zip,
			'memb_type' => $memType,
			'memb_contact' => $contact,
			'memb_share' => $share,
			'memb_user' => $username,
			'memb_pass' => $pass
		) 
	);
	//$wpdb->print_error();

} else {
	echo "The primary member data failed to load. fname  was empty";
}

$membID = $wpdb->insert_id;

if ($s_fname != "") {
	$wpdb->insert(
		'wp_ctxphc_memb_spouses',
		array(
			'sp_fname' => $s_fname,
			'sp_lname' => $s_lname,
			'sp_email' => $s_email,
			'sp_phone' => $s_phone,
			'sp_bday_month' => $s_month,
			'sp_bday_day' => $s_day,
			'sp_rel' => $s_rel,
			'sp_user' => $s_username,
			'sp_pass' => $s_pass,
			'memb_id' => $membID
		)
	);
	//$wpdb->print_error();
}

if ($f1_name != "") {
	$wpdb->insert(
		'wp_ctxphc_family_members',
		array(
			'fam_fname' => $f1_name,
			'fam_rel' => $f1_rel,
			'fam_bday_month' => $f1_month,
			'fam_bday_day' => $f1_day,
			'fam_email' => $f1_email,
			'memb_id' => $membID
		)
	);
	//$wpdb->print_error();

}

if ($f2_name != "") {
	$wpdb->insert(
		'wp_ctxphc_family_members',
		array(
			'fam_fname' => $f2_name,
			'fam_rel' => $f2_rel,
			'fam_bday_month' => $f2_month,
			'fam_bday_day' => $f2_day,
			'fam_email' => $f2_email,
			'memb_id' => $membID
		)
	);
	//$wpdb->print_error();

}

if ($f3_name != "") {
	$wpdb->insert(
		'wp_ctxphc_family_members',
		array(
			'fam_fname' => $f3_name,
			'fam_rel' => $f3_rel,
			'fam_bday_month' => $f3_month,
			'fam_bday_day' => $f3_day,
			'fam_email' => $f3_email,
			'memb_id' => $membID
		)
	);
	//$wpdb->print_error();

}


//  Paypal payment processing
if ($_POST['paypal']) { 
//  Display results of paypal payment processing.  If sucessfull switch member to active and email username and password to member and spouse.
	echo "<div>This is where I will display the paypal payment confirmation screen.</div>";
?>

<?php // Production PAYPAL PROCESSING ?>
<!--	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id=paypal1 name="paypal1">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="orders@ctxphc.com">
		<input type="hidden" name="item_name" value="Central Texas Parrothead Club <%= strMemType %> Membership <%= Year(Now()) %> (<%= strFirstname & " " & strLastname %>)">
		<input type="hidden" name="amount" value="<%= mAmount %>">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="lc" value="US">
		<input type=hidden name="tax" value="0.00">
	</form> -->

<?php // SANDBOX PAYPAL TESTING 
	echo "<div> memType = " . $memType;
	switch ($memType) {
      case "Single"; ?>
		<!-- Single Membership Registration Button -->
		<div>Single Membership</div>
         <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="X3GM2FJ2VMR7N">
			<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
         <?php break;
      case "Couple"; ?>
		<!-- Couple Membership Registration Button -->
		<div>Couple Membership</div>
		<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="VRWMZQGE647LG">
			<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
         <?php break;
      case "Family"; ?>
         <!-- Family Membership Registration Button -->
		 <div>Family Membership</div>
		<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="66W5CLV7LBPT2">
			<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
         <?php break;
   }

} elseif ($_POST['check']) { ?>

	<div id="reg-pay-text">

		<form id="procPayment" name="procPayment" method="post">
			<fieldset class="reg_form" id="mem_type">
				<legend>Membership Type</legend>
				<div id="mem_type">
					<?php setlocale(LC_MONETARY, "en_US"); ?>
					<?php echo money_format($memType . " : %n", $memCost); ?>
				</div>
			</fieldset>

			<div id="reg_form" class="spacer"></div>

			<fieldset class="reg_form" id="mem_type">
				<legend>Your Information</legend>
					<div id="personal_info">
						<div id="label"><?php echo "Name: " . $fname . " " . $lname; ?></div>
						<div id="label"><?php echo "Email: " . $email; ?></div>
						<div id="label"><?php echo "Phone: " . $phone; ?> </div>
						<div id="label"><?php echo "Birthday: " . $month . "/" . $day; ?> </div>	
						<div id="label"><?php echo "Occupation: " . $occu; ?> </div>	
						<div id="label"><?php echo "Address: " . $addr1; ?></div>
						<div id="label"><?php echo "City: " . $city; ?></div>
						<div id="label"><?php echo "State: " . $state; ?> <?php echo "Zip: " . $zip; ?></div>
					</div>
			</fieldset>

			<div id="reg_form" class="spacer"></div>

		<?php if ($memType != "Single") { ?>
		   <div id="reg_form" class="spacer"></div>
			<fieldset class="reg_form" id=spouse_info>
				<legend>Spouse/Partner</legend>
				<div id="spouse_info">
					<div id="label"><?php echo "Name: " . $s_fname . " " . $s_lname; ?></div>
					<div id="label"><?php echo "Email: " . $s_email; ?></div>
					<div id="label"><?php echo "phone: " . $s_phone; ?> </div>
					<div id="label"><?php echo "Birthday: " . $s_month . "/" . $s_day; ?></div>
					<div id="label"><?php echo "Relationship: " . $s_rel; ?></div>	
				</div>
			</fieldset>
			<div id="reg_form" class="spacer"></div>
		<?php } ?>

		<?php if ($memType == "Family") { ?>
		   <div id="reg_form" class="spacer"></div>
			<fieldset class="reg_form" id=spouse_info>
				<legend>Family Members</legend>
				<div id="family_info">
				<?php if ($f1_name != "") { ?>
					<div id=label ><?php echo "Name: " . $f1_name;?></div>
					<div id=label><?php echo "Birthday: " . $f1_month . "/" . $f1_day; ?></div>
					<div id=label><?php echo "Email: " . $f1_email; ?></div>
					<div id=label><?php echo "Relationship: " . $f1_rel; ?></div>
					<div id="reg_form" class="spacer"></div>
				<?php } ?>
			<div id="reg_form" class="spacer"></div>
				<?php if ($f2_name != "") { ?>
					<div id=label><?php echo "Name: " . $f2_name;?></div>
					<div id=label><?php echo "Birthday: " . $f2_month . "/" . $f2_day; ?></div>
					<div id=label><?php echo "Email: " . $f2_email; ?></div>
					<div id=label><?php echo "Relationship: " . $f2_rel; ?></div>
					<div id="reg_form" class="spacer"></div>
				<?php } ?>
				<?php if ($f3_name != "") { ?>
					<div id=label><?php echo "Name: " . $f3_name;?></div>
					<div id=label><?php echo "Birthday: " . $f3_month . "/" . $f3_day; ?></div>
					<div id=label><?php echo "Email: " . $f3_email; ?></div>
					<div id=label><?php echo "Relationship: " . $f3_rel; ?></div>
					<div id="reg_form" class="spacer"></div>
				<?php } ?>
				</div>
			</fieldset>
			<div id="reg_form" class="spacer"></div>
		<?php } ?>
		</form>
	</div>

<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>

            </div> <!-- entry -->
         </div> <!-- post -->


<?php
}
      endwhile;
   endif;
?>
</div> <!-- content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
