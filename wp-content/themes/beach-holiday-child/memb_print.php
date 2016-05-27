<?php
/*
Template Name: Reg_Printing
*/
?>
<?php 
global $wpdb;
require_once TEMPLATEPATH.'/includes/randPassGen.php';
$states_arr = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois",'IN'=>"Indiana",'IA'=>"Iowa",'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland",'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
$relationship_arr = array('S'=>"Spouse",'P'=>"Partner",'C'=>"Child",'O'=>"Other");
get_header('print'); 
?>

<?php 
If ($_POST["check"]) {
	$membRec = $wpdb->get_row("SELECT * FROM ctxphc_ctxphc_members WHERE memb_id = {$_POST['membID']}");
	if ($membRec) {
		$membFname 		= $membRec->memb_fname;
		$membLname 		= $membRec->memb_lname;
		$membEmail 		= $membRec->memb_email;
		$membPhone 		= $membRec->memb_phone;
		$membBdayMonth 	= $membRec->memb_bday_month;
		$membBdayDay 	= $membRec->memb_bday_day;
		$membOccup 		= $membRec->memb_occup;
		$membAddr1 		= $membRec->memb_addr;
		$membCity 		= $membRec->memb_city;
		$membState 		= $membRec->memb_state;
		$membZip 		= $membRec->memb_zip;
		$membType 		= $membRec->memb_type;
		$membContact 	= $membRec->memb_contact;
		$membProfile	= $membRec->memb_share;
	
		switch ($membType) {
			case "single";
				$membType = "Single";
				$membCost = "25.00";
				break;
			case "couple";
				$membType = "Couple";
				$membCost = "40.00";
				break;
			case "family";
				$membType = "Family";
				$membCost = "45.00";
				break;
		}
	} 
	
	$spRec = $wpdb->get_row("SELECT * FROM ctxphc_ctxphc_memb_spouses WHERE memb_id = {$_POST['membID']}");
	if ($spRec) {
		$spFname 		= $spRec->sp_fname;
		$spLname 		= $spRec->sp_lname;
		$spEmail 		= $spRec->sp_email;
		$spPhone 		= $spRec->sp_phone;
		$spBdayMonth 	= $spRec->sp_bday_month;
		$spBdayDay 		= $spRec->sp_bday_day;
		$spRel			= $spRec->sp_rel;
	} 
	
	$famRows = $wpdb->get_results("SELECT * FROM ctxphc_ctxphc_family_members WHERE memb_id = {$_POST['membID']}");
	if ($famRows) {
		$famNum = 0;
		foreach( $famRows as $famRow) {
			$famNum++;
			switch ($famNum) {
				case 1;
					$fam1Fname			= $famRow->fam_fname;
					$fam1Lname			= $famRow->fam_lname;
					$fam1BdayMonth		= $famRow->fam_bday_month;
					$fam1BdayDay		= $famRow->fam_bday_day;
					$fam1Email			= $famRow->fam_email;
					$fam1Rel			= $famRow->fam_rel;
					Break;
				case 2;
					$fam2Fname			= $famRow->fam_fname;
					$fam2Lname			= $famRow->fam_lname;
					$fam2BdayMonth		= $famRow->fam_bday_month;
					$fam2BdayDay		= $famRow->fam_bday_day;
					$fam2Email			= $famRow->fam_email;
					$fam2Rel			= $famRow->fam_rel;
					Break;
				case 3;
					$fam3Fname			= $famRow->fam_fname;
					$fam3Lname			= $famRow->fam_lname;
					$fam3BdayMonth		= $famRow->fam_bday_month;
					$fam3BdayDay		= $famRow->fam_bday_day;
					$fam3Email			= $famRow->fam_email;
					$fam3Rel			= $famRow->fam_rel;
					Break;
			} 
		} 
	}
	
	?>
	
	<div id="content">
		<div class="spacer"></div>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post_title">
						<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
						<span class="post_author">Author: <?php the_author_posts_link('nickname'); ?><?php edit_post_link(' Edit ',' &raquo;','&laquo;'); ?></span>
						<span class="post_date_m"><?php the_time('M');?></span>
						<span class="post_date_d"><?php the_time('d');?></span>
					</div> <!-- post_title -->
					<div class="clear"></div>
					<div class="entry">
						<?php the_content('more...'); ?><div class="clear"></div>
					
						<div class=print>Thank you for submitting your membership registration.  Mail your check for <?php echo $memPrice; ?> to:</div>
						<div class=print>700 Brown Dr. Pflugerville, TX 78660</div>
					
						<div class="spacer"></div>
						
							<fieldset class="reg_form" id="mem_type">
							   <legend>Membership Options</legend>
							   <div id="mem_type">
								  <input class="memType" id=mem_type_1 type="radio" name="mem_type" value="single" <?php if($membType == 'Single') { ?>checked<?php }; ?>>
								  <label for=mem_type_1>Single $25</label>
								  <input class="memType" id=mem_type_2 type="radio" name="mem_type" value="couple" <?php if($membType == 'Couple') { ?>checked<?php }; ?>>
								  <label for=mem_type_2>Couple $40</label>
								  <input class="memType" id=mem_type_3 type="radio" name="mem_type" value="family" <?php if($membType == 'Family') { ?>checked<?php }; ?>>
								  <label for=mem_type_3>Family $45</label>
							   </div>
							</fieldset>
				
							<div  class="spacer"></div>
				
							<fieldset class="reg_form"   id=personal_info>
							   <legend>Your Information</legend>
								   <div id="personal_info">
									  <label id="fname" for="fname">First Name:</label>
										 <input class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id="fname" name="fname" type=text <?php echo 'value=' . $membFname ?> size=15>
									  <label id="lname" for="lname">Last Name:</label>
									   <input  class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id="lname" name="lname" type=text <?php echo 'value=' . $membLname ?> size=25>
								   </div>
								   <div>
									  <label id="email" for=email>Email:</label>
										 <input class="validate[required, custom[email]]" data-prompt-position="bottomLeft" id="email" name="email" type="text" <?php echo 'value=' . $membEmail ?> size=35>
									  <label id="phone" for="phone">Phone:</label>
										 <input class="validate[required, custom[onlyNumber]]" data-prompt-position="bottomLeft" id="phone1" name="phone1" type="text" <?php echo 'value=' . $membPhone ?> size=12>
								   </div>
								   <div>
									  <label id="b-day"  for=b-day>Birthdate:</label>
										 <select id=month name=month class="validate[required]" data-prompt-position="bottomLeft" >
										<?php for( $m=1;$m<=12;$m++) {
											if ($m == $membBdayMonth) {
											if ($m < 10) {
												echo '<option selected value="0'.$m.'">0'.$m.'</option>';
											} else {	
												echo '<option selected value="'.$m.'">'.$m.'</option>';
											}
										  } else {
											if ($m < 10) {
												echo '<option value="0'.$m.'">0'.$m.'</option>';
											} else {	
												echo '<option value="'.$m.'">'.$m.'</option>';
											}
											}
										} ?>
										</select>/<select id=day name=day class="validate[required]" data-prompt-position="bottomLeft">
											<?php for( $d=1;$d<=31;$d++) {
												if ($d == $membBdayDay) {
													if ($d < 10) {
														echo '<option selected value="0'.$d.'">0'.$d.'</option>';
													} else {
														echo '<option selected value="'.$d.'">'.$d.'</option>';
													}
												  } else {
													if ($d < 10) {
														echo '<option value="0'.$d.'">0'.$d.'</option>';
													} else {
														echo '<option value="'.$d.'">'.$d.'</option>';
													}
												}
											} ?>
										</select>
									  <label id="occu" for=occupation>Occupation:</label>
											<input id=occu name=occu type=text class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" <?php echo 'value="' . $membOccup .'"' ?>>
								   </div>
										<input type=hidden <?php echo 'value=' . $username ?> name=username>
										<input type=hidden <?php echo 'value=' . $pass ?> name=pass>
										<input type=hidden <?php echo 'value=' . $s_username ?> name=s_username>
										<input type=hidden <?php echo 'value=' . $s_pass ?> name=s_pass>
					
								   <div>
									  <label id=addr1 for=addr1>Address:
										 <input id=addr1 name=addr1 type=text size=35 class="validate[required, custom[onlyLetterNumberSp]]" data-prompt-position="bottomLeft" <?php echo 'value="' . $membAddr1 . '"' ?>>
									  </label>
								   </div>
								   <!--<div>
									  <label for=addr2>Address:</label>
										 <input id=addr2 name=addr2 type=text size=35 class="validate[required, custom[onlyLetterSp]]">
								   </div> -->
								   <div>
									  <label id=city for=city>City:</label>
										 <input id=city name=city type=text size=15 class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" <?php echo 'value="' . $membCity . '"' ?>>
									  <label id=state for=state>State:</label>
										 <select id=state name=state class="validate[required]">
										 <?php echo showOptionsDrop($states_arr,$membState, true); ?>
										 </select>
									  <label id=zip for=zip>Zip:</label>
										 <input id=zip name=zip type=text size=5 class="validate[required, custom[onlyNumberSp]]" data-prompt-position="bottomLeft" <?php echo 'value="' . $membZip . '"' ?> size=5>
								   </div>
							</fieldset>
				
							<div  class="spacer"></div>

							<?php //Begin display for Spouse/Partner info
							if( $memType != "single" && $spFname ) { ?>
								<fieldset class="reg_form" id=spouse_info>
									<legend>Spouse/Partner</legend>
									<div>
										<label id=sfname for=sfname>First Name:</label>
										<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=sfname name=sfname type=text value=<?php echo $spFname; ?> size=15>
										<label id=slname for=slname>Last Name:</label>
										<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=slname name=slname type=text value=<?php echo $spLname; ?> size=25>
									</div>
									<div>
										<label id=semail for=semail>Email:</label>
											<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=semail name=semail type=text value=<?php echo $spEmail; ?> size=35>
									</div>
									<div>
										<label id=sphone for=sphone>Phone:</label>
											<input class="validate[custom[onlyNumber]]" id=sphone1 name=sphone1 type=text value=<?php echo $spPhone; ?> size=12>
									</div>
									<div>
										<label id=sb-day for=sb-day>Birthdate:</label>
										<select id=smonth name=smonth>
										<?php for( $m=1;$m<=12;$m++) {
											if ($m == $spBdayMonth) { 
												if ($m < 10) {
													echo '<option selected value="0'.$m.'">0'.$m.'</option>';
												} else {	
													echo '<option selected value="'.$m.'">'.$m.'</option>';
												}
											  } else {
												if ($m < 10) {
													echo '<option value="0'.$m.'">0'.$m.'</option>';
												} else {	
													echo '<option value="'.$m.'">'.$m.'</option>';
												}
											}
										} ?>
										</select>/<select id=sday name=sday>
										<?php for( $d=1;$d<=31;$d++) {
											if ($d == $spBdayDay) {
												if ($d < 10) {
													echo '<option selected value="0'.$d.'">0'.$d.'</option>';
												} else {
													echo '<option selected value="'.$d.'">'.$d.'</option>';
												}
											  } else {
												if ($d < 10) {
													echo '<option value="0'.$d.'">0'.$d.'</option>';
												} else {
													echo '<option value="'.$d.'">'.$d.'</option>';
												}
											}
										} ?>
										</select>
										<label id=srelationship for=srelationship>Relationship:</label>
										<select id=srelationship name=srelationship>;
										<?php echo showOptionsDrop($relationship_arr, $spRel, true); ?>
										</select>
									</div>
								</fieldset>
							<?php } //End of Spouse/Partner Info ?>

							<div class="spacer"></div>

							<?php //Begin display of Family Members info
							//If there is no info in the first family members name then there is no family member data to display
							
							if($fam1Fname) { ?>
								<fieldset class="reg_form"   id=family_info>
								<legend>Family Members</legend>
								<div>
									<label id=f1name for=f1name>First Name:</label>
										<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=fam1fname name=fam1Fname type=text value=<?php echo $fam1Fname; ?> size=15>
									<label id="f1lname" for="lname">Last Name:</label>
									   <input  class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=fam1lname" name=fam1Lname type=text <?php echo 'value=' . $fam1Lname ?> size=25>
									<label id=fmrelationship for=f1relationship>Relationship:</label>
										<select id=f1relationship name=f1relationship>
										<?php echo showOptionsDrop($relationship_arr, $fam1Rel, true); ?>
										</select>
								</div>
								<div>
								<label id=fmb-day for=b-day>Birthdate:</label>
								<select id=fmmonth name=f1month>
								<?php for( $m=1;$m<=12;$m++) {
									if ($m == $fam1BdayMonth) {
										if ($m < 10) {
											echo '<option selected value="0'.$m.'">0'.$m.'</option>';
										} else {	
											echo '<option selected value="'.$m.'">'.$m.'</option>';
										}
									  } else {
										if ($m < 10) {
											echo '<option value="0'.$m.'">0'.$m.'</option>';
										} else {	
											echo '<option value="'.$m.'">'.$m.'</option>';
										}
									}
								} ?>
								</select><b>/</b><select id=fmday name=f1day>
								<?php for( $d=1;$d<=31;$d++) {
									if ($d == $fam1BdayDay) {
										if ($d < 10) {
											echo '<option selected value="0'.$d.'">0'.$d.'</option>';
										} else {
											echo '<option selected value="'.$d.'">'.$d.'</option>';
										}
									  } else {
										if ($d < 10) {
											echo '<option value="0'.$d.'">0'.$d.'</option>';
										} else {
											echo '<option value="'.$d.'">'.$d.'</option>';
										}
									}
								} ?>
								</select>
								<label id=fmemail for=f1email>Email:</label>
								<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=fmemail name=f1email type=text value=<?php echo $fam1Email; ?> size=35>
								</div>
								
								<div class="spacer"></div>
								
								<?php if($fam2Fname) { ?>
								   <div>
									<label id=f2name for=f2fname>First Name:</label>
										<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=fam2fname name=fam2Fname type=text value=<?php echo $fam2Fname; ?> size=15>
									<label id="f1lname" for="lname">Last Name:</label>
									   <input  class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=fam2lname" name=fam2Lname type=text <?php echo 'value=' . $fam2Lname ?> size=25>
									<label id=fmrelationship for=f1relationship>Relationship:</label>
										<select id=f1relationship name=f1relationship>
										<?php echo showOptionsDrop($relationship_arr, $fam2Rel, true); ?>
										</select>
								</div>
								<div>
								<label id=fmb-day for=b-day>Birthdate:</label>
								<select id=fmmonth name=f1month>
								<?php for( $m=1;$m<=12;$m++) {
									if ($m == $fam2BdayMonth) {
										if ($m < 10) {
											echo '<option selected value="0'.$m.'">0'.$m.'</option>';
										} else {	
											echo '<option selected value="'.$m.'">'.$m.'</option>';
										}
									  } else {
										if ($m < 10) {
											echo '<option value="0'.$m.'">0'.$m.'</option>';
										} else {	
											echo '<option value="'.$m.'">'.$m.'</option>';
										}
									}
								} ?>
								</select><b>/</b><select id=fmday name=f1day>
								<?php for( $d=1;$d<=31;$d++) {
									if ($d == $fam2BdayDay) {
										if ($d < 10) {
											echo '<option selected value="0'.$d.'">0'.$d.'</option>';
										} else {
											echo '<option selected value="'.$d.'">'.$d.'</option>';
										}
									  } else {
										if ($d < 10) {
											echo '<option value="0'.$d.'">0'.$d.'</option>';
										} else {
											echo '<option value="'.$d.'">'.$d.'</option>';
										}
									}
								} ?>
								</select>
								<label id=fmemail for=f1email>Email:</label>
								<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=fmemail name=f1email type=text value=<?php echo $fam2Email; ?> size=35>
								</div>
								<?php } //End of 2nd Family Member?>

								<div class="spacer"></div>

								<?php if($fam3Fname) { ?>
								   <div>
									<label id=f1name for=f1name>Name:</label>
										<input id=fam3fname name=fam3Fname type=text value=<?php echo $fam3Fname; ?> size=15>
									<label id="f1lname" for="lname">Last Name:</label><?php echo $fam3Lname ?>
									<label id=fmrelationship for=f1relationship>Relationship:</label>
										<select id=f1relationship name=f1relationship>
										<?php echo showOptionsDrop($relationship_arr, $fam3Rel, true); ?>
										</select>
								</div>
								<div>
								<label id=fmb-day for=b-day>Birthdate:</label>
								<select id=fmmonth name=f1month>
								<?php for( $m=1;$m<=12;$m++) {
									if ($m == $fam3BdayMonth) {
										if ($m < 10) {
											echo '<option selected value="0'.$m.'">0'.$m.'</option>';
										} else {	
											echo '<option selected value="'.$m.'">'.$m.'</option>';
										}
									  } else {
										if ($m < 10) {
											echo '<option value="0'.$m.'">0'.$m.'</option>';
										} else {	
											echo '<option value="'.$m.'">'.$m.'</option>';
										}
									}
								} ?>
								</select><b>/</b><select id=fmday name=f1day>
								<?php for( $d=1;$d<=31;$d++) {
									if ($d == $fam3BdayDay) {
										if ($d < 10) {
											echo '<option selected value="0'.$d.'">0'.$d.'</option>';
										} else {
											echo '<option selected value="'.$d.'">'.$d.'</option>';
										}
									  } else {
										if ($d < 10) {
											echo '<option value="0'.$d.'">0'.$d.'</option>';
										} else {
											echo '<option value="'.$d.'">'.$d.'</option>';
										}
									}
								} ?>
								</select>
								<label id=fmemail for=f1email>Email:</label>
								<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=fmemail name=f1email type=text value=<?php echo $fam3Email; ?> size=35>
								</div>
								<?php } //End of 3rd family member ?>
								</fieldset>
							<?php } //End of all Family Members ?>
							
							<div class="spacer"></div>
							
							<fieldset id=preferences>
								<legend>Preferences</legend>
								   <div>
									  <input type=checkbox name="profile" value="checked" <?php if($membProfile == 'TRUE') { echo "checked"; } ?>> <lable for=profile>Check here if you want your contact information available in the SECURE online Membership Directory. (Childrens information will automatically be hidden).</lable>
								   </div>

								   <div class="spacer"></div>

								   <div>
									  <input type=checkbox name="contact" value="checked" <?php if($membContact == 'TRUE') { echo "checked"; } ?>><lable for=contact>Check here if you are willing to help out with future events or event planing.</lable>
								   </div>
							</fieldset>
			
							<div class="spacer"></div>
							
							<div id=print>
								<fieldset class="screen">
									<legend class="screen">Update</legend>
								<div class="screen">If you have made any changes to the form please click:</div>
								<div class=aligncenter>
									<input class=button3 class=screen id="print" type=submit name="print" value="Print" onClick="window.print()" />
								</div>
								</fieldset>
							</div>
						</form>
			
					<?php wp_link_pages(array('before' => '<div><strong><center>Pages: ', 'after' => '</center></strong></div>', 'next_or_number' => 'number')); ?>
					<div class="clear"></div>
				</div> <!-- entry -->
			</div> <!-- post -->
<?php 	endwhile; 
	endif; ?>
	</div> <!-- content -->
<?php } //End of POST Processing ?>
<?php get_sidebar(); ?>
<?php get_footer();?>