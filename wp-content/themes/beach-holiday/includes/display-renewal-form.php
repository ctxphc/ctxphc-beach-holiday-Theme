<?php
/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 12/30/2014
 * Time: 7:43 PM
 */
/**
 * Begin the display of the registration renewal page.
 *
 */

function display_renewal_form( $form_data ) {
	require_once( TEMPLATEPATH . '/includes/randPassGen.php' );

	$states_arr = array(
		'AL' => "Alabama",
		'AK' => "Alaska",
		'AZ' => "Arizona",
		'AR' => "Arkansas",
		'CA' => "California",
		'CO' => "Colorado",
		'CT' => "Connecticut",
		'DE' => "Delaware",
		'DC' => "District Of Columbia",
		'FL' => "Florida",
		'GA' => "Georgia",
		'HI' => "Hawaii",
		'ID' => "Idaho",
		'IL' => "Illinois",
		'IN' => "Indiana",
		'IA' => "Iowa",
		'KS' => "Kansas",
		'KY' => "Kentucky",
		'LA' => "Louisiana",
		'ME' => "Maine",
		'MD' => "Maryland",
		'MA' => "Massachusetts",
		'MI' => "Michigan",
		'MN' => "Minnesota",
		'MS' => "Mississippi",
		'MO' => "Missouri",
		'MT' => "Montana",
		'NE' => "Nebraska",
		'NV' => "Nevada",
		'NH' => "New Hampshire",
		'NJ' => "New Jersey",
		'NM' => "New Mexico",
		'NY' => "New York",
		'NC' => "North Carolina",
		'ND' => "North Dakota",
		'OH' => "Ohio",
		'OK' => "Oklahoma",
		'OR' => "Oregon",
		'PA' => "Pennsylvania",
		'RI' => "Rhode Island",
		'SC' => "South Carolina",
		'SD' => "South Dakota",
		'TN' => "Tennessee",
		'TX' => "Texas",
		'UT' => "Utah",
		'VT' => "Vermont",
		'VA' => "Virginia",
		'WA' => "Washington",
		'WV' => "West Virginia",
		'WI' => "Wisconsin",
		'WY' => "Wyoming"
	);

	$relationship_arr = array( 'S' => "Spouse", 'P' => "Partner", 'C' => "Child", 'O' => "Other" );

	get_header( 'reg' ); ?>
	<script>
		jQuery(document).ready(function () {
			jQuery("#procForm").validationEngine('attach', {promptPosition: "centerRight"});
		});
	</script>
	<?php
	$debug    = false;
	$memPrice = 0;

	?>

	<script type="text/javascript">
		jQuery(document).ready(function () {
				switch ($('.memType:checked').val()) {
					case 1:
						jQuery('#child_info').css('display', 'none');
						jQuery('#spouse_info').css('display', 'none');
						break;
					case 2:
						jQuery("#child_info").css("display", "block");
						jQuery("#spouse_info").css("display", "none");
					case 3:
						jQuery("#child_info").css("display", "none");
						jQuery("#spouse_info").css("display", "block");
						break;
					case 4:
						jQuery("#child_info").css("display", "block");
						jQuery("#spouse_info").css("display", "block");
				}
			}
		);
	</script>

	<div id="content">
		<div class="spacer"></div>
		<?php if ( have_posts() ) : while ( have_posts() ) :
			the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_title">
					<h1><a href="<?php the_permalink() ?>" rel="bookmark"
					       title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<span
						class="post_author">Author: <?php the_author_posts_link( 'nickname' ); ?><?php edit_post_link( ' Edit ', ' &raquo;', '&laquo;' ); ?></span>
					<span class="post_date_m"><?php the_time( 'M' ); ?></span>
					<span class="post_date_d"><?php the_time( 'd' ); ?></span>
				</div>
				<!-- post_title -->
				<div class="clear"></div>
				<div class="entry">
					<?php the_content( 'more...' ); ?>
					<div class="clear"></div>
					<div class=print>Thank you for submitting your membership renewal. Mail this form and your check
						for <?php echo $memPrice; ?> to:
					</div>
					<div class=print>700 Brown Dr. Pflugerville, TX 78660</div>
					<div class="screen" id="reg-verify-text">Thank you for renewing your membership! Please review your information and
						click UPDATE after making any changes. If everything is ready to go, click your prefered payment method below.
					</div>
					<div class="spacer"></div>
					<form id="procForm" name="procForm" method="post" action="http://www.ctxphc.com?page_id=554">
						<input type=hidden value=<?php echo $form_data[ 'pmntID' ]; ?> name=pmntID>
						<input type=hidden <?php echo 'value=' . $form_data[ 'm1_id' ]; ?> name=memb_id>
						<input type=hidden <?php echo 'value=' . $form_data[ 'm2_id' ]; ?> name=s_id>
						<input type=hidden <?php echo 'value=' . $form_data[ 'm3_id' ]; ?> name=f1id>
						<input type=hidden <?php echo 'value=' . $form_data[ 'm4_id' ]; ?> name=f2id>
						<input type=hidden <?php echo 'value=' . $form_data[ 'm5_id' ]; ?> name=f3id>
						<input type=hidden <?php echo 'value=' . $form_data[ 'm6_id' ]; ?> name=f4_id>
						<input type=hidden value='http://www.ctxphc.com/registration-renewal/' name=referer>

						<fieldset class="reg_form" id="mem_type">
							<legend>Membership Options</legend>
							<div id="mem_type">
								<input class="memType" id=mem_type_1 type="radio" name="mem_type" value=1
								       <?php if (1 == $form_data[ 'm1_mem_type' ]) { ?>checked<?php }; ?>>
								<label class="memType" for=mem_type_1>Individual $25</label>
								<input class="memType" id=mem_type_2 type="radio" name="mem_type" value=2
								       <?php if (2 == $form_data[ 'm1_mem_type' ]) { ?>checked<?php }; ?>>
								<label class="memType" for=mem_type_2>Individual + Child $30</label>
								<input class="memType" id=mem_type_3 type="radio" name="mem_type" value=3
								       <?php if (3 == $form_data[ 'm1_mem_type' ]) { ?>checked<?php }; ?>>
								<label class="memType" for=mem_type_3>Couple $40</label>
								<input class="memType" id=mem_type_4 type="radio" name="mem_type" value=4
								       <?php if (4 == $form_data[ 'm1_mem_type' ]) { ?>checked<?php }; ?>>
								<label class="memType" for=mem_type_3>Household $45</label>
							</div>
						</fieldset>

						<div class="spacer"></div>

						<fieldset class="reg_form" id=personal_info>
							<legend>Your Information</legend>
							<div id="personal_info">

								<label id="fname" for="fname">First Name:</label>
								<input class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id="fname"
								       name="fname" type=text <?php echo 'value=' . $form_data[ 'm1_fname' ] ?>>
								<label id="lname" for="lname">Last Name:</label>
								<input class="validate[required, custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id="lname"
								       name="lname" type=text <?php echo 'value=' . $form_data[ 'm1_lname' ] ?>>
							</div>
							<div>
								<label id="email" for=email>Email:</label>
								<input class="validate[required, custom[email]]" data-prompt-position="bottomLeft" id="email"
								       name="email" type="text" <?php echo 'value=' . $form_data[ 'm1_email' ] ?>>
								<label id="phone" for="phone">Phone:</label>
								<input class="validate[required, custom[onlyNumber]]" data-prompt-position="bottomLeft" id="phone"
								       name="phone1" type="text" <?php echo 'value=' . $form_data[ 'm1_phone' ] ?> size=12>
							</div>
							<div>
								<label id="bday" for=bday>Birthdate:</label>
								<input type="date" id=bday name=bday class="validate[required]" data-prompt-position="bottomLeft"
								       value="<?php echo $form_data[ 'm1_bday' ]; ?>">

								<label id="occu" for=occupation>Occupation:</label>
								<input id=occu name=occu type=text class="validate[required, custom[onlyLetterSp]]"
								       data-prompt-position="bottomLeft" value="<?php echo $form_data[ 'm1_occupation' ]; ?>">
							</div>

							<div>
								<label id=addr1 for=addr1>Address:
									<input id=addr1 name=addr1 type=text size=35 class="validate[required, custom[onlyLetterNumberSp]]"
									       data-prompt-position="bottomLeft" value="<?php echo $form_data[ 'addr1' ]; ?>">
								</label>
							</div>
							<div>
								<label for=addr2>Address:</label>
								<input id=addr2 name=addr2 type=text size=35 class="validate[required, custom[onlyLetterSp]]"
								       value="<?php echo $form_data[ 'addr2' ]; ?>">
							</div>
							<div>
								<label id=city for=city>City:</label>
								<input id=city name=city type=text size=15 class="validate[required, custom[onlyLetterSp]]"
								       data-prompt-position="bottomLeft" value="<?php echo $form_data[ 'city' ]; ?>">
								<label id=state for=state>State:</label>
								<select id=state name=state class="validate[required]">
									<?php echo showOptionsDrop( $states_arr, $form_data[ 'state' ], true ); ?>
								</select>
								<label id=zip for=zip>Zip:</label>
								<input id=zip name=zip type=text size=5 class="validate[required, custom[onlyNumberSp]]"
								       data-prompt-position="bottomLeft" value="<?php echo $form_data[ 'zip' ] ?>>
							</div>
						</fieldset>

						<?php //Begin display for Spouse/Partner info
								if (2 <= $form_data[ 'm2_mem_type' ] && $form_data[ 'm2_fname' ]) { ?>
							<div class=" spacer">
							</div>

							<fieldset class="reg_form" id=spouse_info>
								<legend>Spouse/Partner</legend>
								<div>
									<label id=sfname for=sfname>First Name:</label>
									<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=sfname
									       name=s_fname type=text value=<?php echo $form_data[ 'm2_fname' ]; ?>>

									<label id=slname for=slname>Last Name:</label>
									<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=slname
									       name=s_lname type=text value=<?php echo $form_data[ 'm2_lname' ]; ?>>
								</div>
								<div>
									<label id=semail for=semail>Email:</label>
									<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=semail name=s_email
									       type=text value=<?php echo $form_data[ 'm2_email' ]; ?>>

									<label id=sphone for=sphone>Phone:</label>
									<input class="validate[custom[onlyNumber]]" data-prompt-position="bottomLeft" id=sphone
									       name=sp_phone type="tel" value=<?php echo $form_data[ 'm2_phone' ]; ?>>
								</div>
								<div>
									<label id=sbday for=sbday class="validate[custom[onlyNumber]]" data-prompt-position="bottomLeft">Birthdate:</label>
									<input type="date" id=smonth name=sp_bday value="<?php echo $form_data[ 'm2_birthday' ]; ?>">

									<label id=srelationship for=srelationship>Relationship:</label>
									<select id=srelationship name=sp_rel>;
										<?php echo showOptionsDrop( $relationship_arr, $form_data[ 'm2_rel' ], true ); ?>
									</select>
								</div>
							</fieldset>
							<?php } //End of Spouse/Partner Info
							?>

							<?php //Begin display of Family Members info
							//If there is no info in the first family members name then there is no family member data to display
							if ( $form_data[ 'm3_fname' ] ) { ?>

								<div class="spacer"></div>

								<fieldset class="reg_form" id=child_info>
									<legend>Family Members</legend>
									<div>
										<label id=f1fname for=f1fname>First Name:</label>
										<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=f1fname
										       name=f1fname type=text value=<?php echo $form_data[ 'm3_fname' ]; ?>>

										<label id=f1lname for=f1lname>Last Name:</label>
										<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=f1lname
										       name=f1lname type=text value=<?php echo $form_data[ 'm3_lname' ]; ?>>

										<label id=f1relationship for=f1relationship>Relationship:</label>
										<select id=f1relationship name=f1relationship>
											<?php echo showOptionsDrop( $relationship_arr, $form_data[ 'm2_rel' ], true ); ?>
										</select>
									</div>
									<div>
										<label id=f1b-day for=b-day>Birthdate:</label>
										<input type="date" id=f1bday name=f1bday value="<?php echo $form_data[ 'm3_birthday' ]; ?>">

										<label id=f1email for=f1email>Email:</label>
										<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=f1email
										       name=f1email
										       type=text value=<?php echo $form_data[ 'm3_email' ]; ?>>
									</div>

									<div class="spacer"></div>

									<!-- 2nd FAMILY MEMBER -->
									<?php if ( $form_data[ 'm4_fname' ] ) { ?>
										<div>
											<label id=f2fname for=f2fname>First Name:</label>
											<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=f2fname
											       name=f2fname type=text value=<?php echo $form_data[ 'm4_fname' ]; ?>>

											<label id=f2lname for=f2lname>Last Name:</label>
											<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=f2lname
											       name=f2lname type=text value=<?php echo $form_data[ 'm4_lname' ]; ?>>

											<label id=f2rel for=f2rel>Relationship:</label>
											<select id=f2rel name=f2rel>
												<?php echo showOptionsDrop( $relationship_arr, $form_data[ 'm4_rel' ], true ); ?>
											</select>
										</div>
										<div>
											<label id=f2bday for=f2bday>Birthdate:</label>
											<input type="date" id=f2bday name=f2bday
											       value="<?php echo $form_data[ 'm4_birthday' ]; ?>">

											<label id=f2email for=f2email>Email:</label>
											<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=f2email
											       name=f2email type=text value=<?php echo $form_data[ 'm4_email' ]; ?>>
										</div>
									<?php } //End of 2nd Family Member?>

									<div class="spacer"></div>

									<!-- 3rd FAMILY MEMBER -->
									<?php if ( $form_data[ 'm5_fname' ] ) { ?>
										<div>
											<label id=f3fname for=f3fname>First Name:</label>
											<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=f3fname
											       name=f3fname type=text value=<?php echo $form_data[ 'm5_fname' ]; ?>>

											<label id=f3lname for=f3lname>Last Name:</label>
											<input class="validate[custom[onlyLetterSp]]" data-prompt-position="bottomLeft" id=f3lname
											       name=f3lname type=text value=<?php echo $form_data[ 'm5_lname' ]; ?>>

											<label id=f3relationship for=f3relationship>Relationship:</label>
											<select id=f3relationship name=f3relationship>
												<?php echo showOptionsDrop( $relationship_arr, $form_data[ 'm5_rel' ], true ); ?>
											</select>
										</div>
										<div>
											<label id=f3bday for=f3bday>Birthdate:</label>
											<input type="date" id=f3bday name=f3bday
											       value="<?php echo $form_data[ 'm5_birthday' ]; ?>">

											<label id=f3email for=f3email>Email:</label>
											<input class="validate[custom[email]]" data-prompt-position="bottomLeft" id=f3email
											       name=f3email type=text value=<?php echo $form_data[ 'm5_email' ]; ?>>
										</div>
									<?php } //End of 3rd family member ?>
								</fieldset>
							<?php } //End of all Family Members
							?>

							<div class="spacer"></div>

							<div id=update>
								<fieldset class="screen">
									<legend class="screen">Update</legend>
									<div class=screen>If you have made any changes to the form please click:</div>
									<div>
										<input class="button3 screen" id="update" type=submit name="update" value="Update">
									</div>
								</fieldset>
							</div>
					</form>

					<div class="spacer"></div>

					<div id=payment>
						<fieldset class="screen" id=payOptions>
							<legend class="screen">Payment Options</legend>
							<div class="cont_wrap">
								<div class="screen">Once you are satisfied the information displayed is correct click your prefered
									payment option. PayPal will allow you to use most credit cards or your PayPal account.
								</div>
								<div class="cont_lf_col">
									<div>
										<?php
										if ( $debug == "true" ) {  // ######     SANDBOX PAYPAL TESTING      ######
											switch ( $$form_data[ 'm1_mem_type' ] ) {
												case 1: ?>
													<!-- Individual Membership Registration Button -->
													<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="9VHY5QX2XQRDA">
														<input type="hidden" name="item_name" value="Renewal">
														<input type="hidden" name="item_number" value="Individula">
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>">
														<input type="image"
														       src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt="" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif"
														     width="1"
														     height="1">
													</form>
													<?php break;
												case 2; ?>
													<!-- Individual + Child Membership Registration Button -->
													<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="3UDP2ZQ4TLEU2">
														<input type="hidden" name="item_name" value="Renewal">
														<input type="hidden" name="item_number" value="Ind+Child">
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>">
														<input type="image"
														       src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt="" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif"
														     width="1"
														     height="1">
													</form>
													<?php break;
												case 3; ?>
													<!-- Couple Membership Registration Button -->
													<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="3UDP2ZQ4TLEU2">
														<input type="hidden" name="item_name" value="Renewal">
														<input type="hidden" name="item_number" value="Couple">
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>">
														<input type="image"
														       src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt="" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif"
														     width="1"
														     height="1">
													</form>
													<?php break;
												case 4; ?>
													<!-- Family Membership Registration Button -->
													<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="C3FYBNFH9HQX2">
														<input type="hidden" name="item_name" value="Renewal">
														<input type="hidden" name="item_number" value="Household">
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>">
														<input type="image"
														       src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt="" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif"
														     width="1"
														     height="1">
													</form>
													<?php break;
											}
										} else {  // ######     PRODUCTION PAYPAL      ######
											switch ( $$form_data[ 'm1_mem_type'] ) {
												case 1; ?>
													<!-- Single Membership Registration Button -->
													<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="YS9S9EP9XJR84">
														<input type="hidden" name="item_name" value="individual"/>
														<input type="hidden" name="item_number" value="Renewal"/>
														<input type="hidden" name="amount" value="25.00"/>
														<input type="hidden" name="currency_code" value="USD"/>
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>"/>
														<input type="image"
														       src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1"
														     height="1">
													</form>
													<?php break;
												case 3; ?>
													<!-- Couple Membership Registration Button -->
													<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="64685RTC9LJEG">
														<input type="hidden" name="item_name" value="Couple">
														<input type="hidden" name="item_number" value="Renewal"/>
														<input type="hidden" name="amount" value="40.00"/>
														<input type="hidden" name="currency_code" value="USD"/>
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>">
														<input type="image"
														       src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1"
														     height="1">
													</form>
													<?php break;
												case 4; ?>
													<!-- Family Membership Registration Button -->
													<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
														<input type="hidden" name="cmd" value="_s-xclick">
														<input type="hidden" name="hosted_button_id" value="55JG5D92JG966">
														<input type="hidden" name="item_name" value="Houseehold">
														<input type="hidden" name="item_number" value="Renewal"/>
														<input type="hidden" name="amount" value="45.00"/>
														<input type="hidden" name="currency_code" value="USD"/>
														<input type="hidden" name="custom"
														       value="<?php echo $$form_data[ 'pmntID' ]; ?>">
														<input type="image"
														       src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif"
														       name="submit" alt="PayPal - The safer, easier way to pay online!">
														<img alt=""
														     src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1"
														     height="1">
													</form>
													<?php break;
											}
										} ?>
									</div>
								</div>
								<div class="cont_rt_col">
									<div>
										<form id="regForm" name="regForm" method="post" action="http://www.ctxphc.com?page_id=161">
											<input type=hidden value="Renewal" name="item_number"/>
											<input type=hidden value=<?php echo $$form_data[ 'pmntID' ]; ?> name=pmntID>
											<input type=hidden <?php echo 'value=' . $form_data[ 'm1_id' ]; ?> name=membID>
											<input type=hidden <?php echo 'value=' . $form_data[ 'm2_id' ]; ?> name=spid>
											<input type=hidden <?php echo 'value=' . $form_data[ 'm3_id' ]; ?> name=f1id>
											<input type=hidden <?php echo 'value=' . $form_data[ 'm4_id' ]; ?> name=f2id>
											<input type=hidden <?php echo 'value=' . $form_data[ 'm5_id' ]; ?> name=f3id>
											<input class="button3 screen" id="check" type=submit name="check" value="Check"
											       onClick="window.print()">
										</form>
									</div>
								</div>
							</div>
						</fieldset>
					</div>

					<?php wp_link_pages( array(
						'before'         => '<div><strong><center>Pages: ',
						'after'          => '</center></strong></div>',
						'next_or_number' => 'number'
					) ); ?>
					<div class="clear"></div>
				</div>
				<!-- entry -->
			</div>
			<!-- post -->
		<?php
		endwhile;
		endif;
		?>
	</div> <!-- content -->
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
<?php }