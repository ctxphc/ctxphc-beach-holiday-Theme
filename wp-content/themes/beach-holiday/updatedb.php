<?php
/*
Template Name: DB-Update
*/
?>
<?php ob_start(); ?>
<?php if (!is_user_logged_in()) { auth_redirect(); } //User must be logged in to access this page!  ?> 
<?php global $wpdb?>
<?php
if ($_POST['update']) { 
	$referer = $_POST['referer'];
	//Collect User Data from POST data
	$fname = $_POST['fname'];
	$lname = $_POST['lname']; 
	$email = $_POST['email'];
	$phone = $_POST['phone1'] . "-" . $_POST['phone2'] . "-" . $_POST['phone3'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$occup = $_POST['occu'];
	$addr = $_POST['addr1'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$membType = $_POST['membType'];
	$contact = $_POST['contact'];
	$share = $_POST['profile'];
	$pass = $_POST['pass'];
	$pending = 'TRUE';
	$date_renewed = date('Y-m-d H:i:s');
	//Collect Spouse info from POST data
	if ($membType != 'Single') {
		$s_fname = $_POST['s_fname'];
		$s_lname = $_POST['s_lname'];
		$s_email = $_POST['s_email'];
		$s_phone = $_POST['s_phone1'] . "-" . $_POST['s_phone2'] . "-" . $_POST['s_phone3'];
		$s_month = $_POST['s_month'];
		$s_day = $_POST['s_day'];
		$s_rel = $_POST['s_rel'];
		//Collect Family Members info from POST data
		if ($membType == 'Family') {
			if ($_POST['f1_fname']){
				$f1_fname = $_POST['f1_fname'];
				$f1_lname = $_POST['f1_lname'];
				$f1_email = $_POST['f1_email'];
				$f1_rel = $_POST['f1_rel'];
				$f1_month = $_POST['f1month'];
				$f1_day = $_POST['f1_day'];
			}
			if ($_POST['f2_fname']){
				$f2_fname = $_POST['f2_fname'];
				$f2_lname = $_POST['f2_lname'];
				$f2_email = $_POST['f2_email'];
				$f2_rel = $_POST['f2_rel'];
				$f2_month = $_POST['f2_month'];
				$f2_day	= $_POST['f2_day'];
			}
			if ($_POST['f3_fname']){
				$f3_fname = $_POST['f3_fname'];
				$f3_lname = $_POST['f3_lname'];
				$f3_email = $_POST['f3_email'];
				$f3_rel = $_POST['f3_rel'];
				$f3_month = $_POST['f3_month'];
				$f3_day = $_POST['f3_day'];
			}
		}
	}
	//Collect Member info from the database for comparison to POST data
	$userID = get_current_user_id();
	if ($userID >= 1000) {
		$userInfo 	= get_userdata($userID);
		//Compare POST data to DB.  If different update DB with new data.
		if ($userInfo->first_name != $fname) {
			update_user_meta($userID,'first_name', $fname);
		}
		if ($userInfo->last_name != $lname) {
			update_user_meta($userID, 'last_name', $lname);
		}
		if ($userInfo->user_phone != $phone) {
			update_user_meta($userID, 'user_phone',  $phone);
		}
		if ($userInfo->user_email != $email) {
			wp_update_user(array( 'ID' => $userID,'user_email' => $email));
		}
		if ($userInfo->user_addr != $addr) {
			update_user_meta($userID, 'user_addr',  $addr);
		}
		if ($userInfo->user_city != $city) {
			update_user_meta($userID, 'user_city',  $city);
		}
		if ($userInfo->user_state != $state) {
			update_user_meta($userID, 'user_state',  $state);
		}
		if ($userInfo->user_zip != $zip) {
			update_user_meta($userID, 'user_zip',  $zip);
		}
		if ($userInfo->user_bday_month != $month) {
			update_user_meta($userID, 'user_bday_month',  $month);
		}
		if ($userInfo->user_bday_day != $day) {
			update_user_meta($userID, 'user_bday_day',  $day);
		}
		if ($userInfo->user_occup != $occup) {
			update_user_meta($userID, 'user_occup',  $occup);
		}
		if ($userInfo->user_contact != $contact) {
			update_user_meta($userID, 'user_contact',  $contact);
		}
		if ($userInfo->user_share != $share) {
			update_user_meta($userID, 'user_share',  $share);
		}
		if ($membType != "Single") {
			// Get Spouse or Partner info from DB
			$spInfo = $wpdb->get_row("select * from ctxphc_ctxphc_memb_spouses where memb_id = {$userID}");

			if ($s_fname != $spInfo->sp_fname) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_fname' => $s_fname),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($s_lname != $spInfo->sp_lname) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_lname' => $s_lname),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($s_email != $spInfo->sp_email) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_email' => $s_email),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($s_phone != $spInfo->sp_phone) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_phone' => $s_phone),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($s_month != $spInfo->sp_bday_month) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_bday_month' => $s_month),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($s_day != $spInfo->sp_bday_day) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_bday_day' => $s_day),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($s_rel != $spInfo->sp_rel) {
				$wpdb->update(
					'ctxphc_ctxphc_memb_spouses',
					array('sp_rel' => $s_rel),
					array('sp_id' => $spInfo->sp_id)
				);
			}
			if ($membType == 'Family') {
				$familyInfo = $wpdb->get_results("select * from ctxphc_ctxphc_family_members where memb_id = {$userID}");
				$famNum = 0;
				foreach ($familyInfo as $famMem) {
					$famNum++;
					switch ($famNum) {
					case 1;
						if ($f1_fname != $famRow->fam_fname) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_fname' => $f1_fname
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f1_lname != $famRow->fam_lname) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_lname' => $f1_lname
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f1_rel != $famRow->fam_rel) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_rel' => $f1_rel
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f1_month != $famRow->fam_bday_month) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_bday_month' => $f1_month
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f1_day != $famRow->fam_bday_day) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_bday_day' => $f1_day
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f1_email != $famRow->fam_email) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_email' => $f1_email
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						Break;
					case 2;
						if (f2_fname != $famRow->fam_fname) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_fname' => f2_fname
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if (f2_lname != $famRow->fam_lname) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_lname' => f2_lname
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if (f2_rel != $famRow->fam_rel) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_rel' => f2_rel
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if (f2_month != $famRow->fam_bday_month) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_bday_month' => f2_month
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if (f2_day != $famRow->fam_bday_day) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_bday_day' => f2_day
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if (f2_email != $famRow->fam_email) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_email' => f2_email
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						Break;
					case 3;
						if ($f3_fname != $famRow->fam_fname) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_fname' => $f3_fname
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f3_lname != $famRow->fam_lname) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_lname' => $f3_lname
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f3_rel != $famRow->fam_rel) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_rel' => $f3_rel
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f3_month != $famRow->fam_bday_month) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_bday_month' => $f3_month
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f3_day != $famRow->fam_bday_day) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_bday_day' => $f3_day
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						if ($f3_email != $famRow->fam_email) {
							$wpdb->update(
								'ctxphc_ctxphc_family_members',
								array(
									'fam_email' => $f3_email
								),
								array(
									'fam_id' => $famRow->fam_id
								)
							);
						}
						Break;
					}
				}
			}
		}
	}
	if (isset($referer)) {
		header("Location: ". $referer);
	} else { 
		header("Location: http://ctxphc.com");
	}
	ob_end_flush();
} ?>