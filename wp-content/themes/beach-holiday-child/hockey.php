<?php
/*
Template Name: Hockey
*/
?>


<?php get_header(); ?>
<div id="content"><div class="spacer"></div>
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
					
<div id=hockey>
	<img id=txstars title="Texas Stars Hockey" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/Hockey/TX_Stars_logo_white2.png" alt="" width="180" height="136" />
	<img id=vs title="VS" src="http://ctxphc.com/wp-content\themes\beach-holiday\Images\Hockey\vs.png" alt="" width="43" height="44" />
	<img id=rampage title="San Antonio Rampage" src="http://ctxphc.com/wp-content\themes\beach-holiday\Images\Hockey\Rampage-head.png" alt="" width="166" height="176" />
</div>
<div>
	<h2 style="text-align: center;">CTXPHC HOCKEY NIGHT</h2>
	<h2 style="text-align: center;">TEXAS STARS vs SAN ANTONIO RAMPAGE!</h2>
	<h2 style="text-align: center;">SATURDAY, JANUARY 26, 2013</h2>
</div>
<div>
	<div>
		<ul>
			<li>Doors Open at 6:00PM</li>
			<li>Game Starts at 7:00PM</li>
		</ul>
	</div>

	<div><strong>FUNDRAISER for the <a title="www.austinzoo.org" href="http://www.austinzoo.org" target="_blank">AUSTIN ZOO and ANIMAL SANCTUARY</a></strong></div>

	<div class="spacer"></div>

	<div><strong>***THE FIRST 35 TICKET BUYERS take part in the TEXAS STARS FAN TUNNEL EXPERIENCE***</strong></div>

	<div class="spacer"></div>
	<div><a title="CTXPHC Parrothead Club" href="http://ctxphc.com"><img class="alignright" title="CTXPHC Logo" src="http://www.ctxphc.com/wp-content/Images/HomePage-Image.jpg" alt="" width="233" height="235" /></a>
		<div>
			<ul>
				<li>$15.00 Pre-Paid Executive Level Tickets (regularly $17.00)</li>
				<li>$5 Pre-Paid Parking (regularly $10.00)</li>
				<li>$3.00 Pre-Paid Hot Dog/Soda Vouchers</li>
				<li>CTXPHC Name on Video Board</li>
				<li>Buffett music played throughout the night</li>
			</ul>		
			<div><strong>TO ORDER YOUR TICKETS</strong> and other pre-paid options</div>
			<div>**    USE PROMOTIONS CODE:    <strong>PARROT</strong>    **</div>
			<div>and go to: <a title="CTXPHC Hockey Night Fundraiser" href="https://oss.ticketmaster.com/html/group_corp_start.htmI?l=EN&amp;team=txstars&amp;owner=1665414&amp;group=850&amp;err=&amp;event=&amp;customerID=" target="_blank">CTXPHC Hockey Night Fundraiser</a></div>
			
			<div> &nbsp; </div>
			
			<div>OR CONTACT WALTER MANNINO: </div>
			<div>(P) 512-600-5043</div>
			<div>(E) wmannino@texasstarshockey.com</div>
		</div>
	</div>
	
	<div class="spacer"></div>
	
	<div><rem><strong>*ORDER DEADLINE MONDAY, JANUARY 21, 2013. Offer Not valid at Box Office on day of game.*</strong></rem></div>
	
	<div class="spacer"></div>
	
	<div>
		Bring friends, co-workers, family - everyone is welcome to attend this fundraiser for the <a title="www.austinzoo.org" href="http://www.austinzoo.org" target="_blank">AUSTIN ZOO and ANIMAL SANCTUARY</a>. 
		Jimmy Buffett music will be played in the arena throughout the night to help keep our Phins Up.
	</div>
	
	<div class="spacer"></div>
	
	<div>A Zoo Representative and Club Representatives will be manning our table side by side in the foyer handing out information, answering questions and passing out leis.</div>
	
	<div class="spacer"></div>
	
	<div><rem>Money from every ticket sold goes toward our donation to the Austin Zoo!</rem></div>
	
	<div class="spacer"></div>
	
	<div><rem>Out of town guests?</rem> No worries! The Texas Stars are pleased to offer a <rem>special hotel rate</rem> to patrons visiting Cedar Park Center. Please click on <strong><a title="Cedar Park Center Hotel Partner Hotels" href="http://www.texasstarshockey.com/arena/hotel-information" target="_blank">Cedar Park Center Hotel Partner Hotels</a></strong> for a list of hotels.</div>
</div>

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