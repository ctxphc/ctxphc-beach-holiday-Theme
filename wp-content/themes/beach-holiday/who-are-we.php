<?php
/*
Template Name: Who Are We Page
*/
?>

<?php get_header(); ?>
<div id="content"><div class="spacer"></div>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_title">
					<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				</div>
				<div class="clear"></div>
				<div class="entry">
					<?php the_content('more...'); ?><div class="clear"></div>
                  <div>
					<div>
						<h2 style="text-align: center;">Central Texas Parrot Head Club</h2>
					</div>
                     <div id="WAWmenue">
                        <ul class="inline">
                           <li><a href="http://ctxphc.com/?page_id=64#bod">Board of Directors </a>|</li>
                           <li><a href="http://ctxphc.com/?page_id=64#address">Mailing Address </a>|</li>
                           <li><a href="http://ctxphc.com/?page_id=64#history">History Lesson </a></li>
                        </ul>
                     </div> <!-- #WAWmenue -->
                     <div class="spacer"></div>
                     <div class="spacer"></div>
                     <div class="WAWinline">Wondering what a Parrot Head is, or what we do? Read on!</div>
                     <div>Basically, a Parrot Head is a fan (ehem... sorry, that would be PHan) of <a href="http://www.margaritaville.com/" target="_blank">Jimmy Buffett</a>.<img src="http://ctxphc.com/wp-content/Images/Buffett.jpg" alt="" width="40" height="41" /></div>
                     <div class="spacer"></div>
                     <div>All around the world there are local chapters of <a href="http://www.phip.com/" target="_blank">Parrot Heads in Paradise (PHiP)</a>. These clubs promote fellowship among PHans as well as volunteer services. The Central Texas Parrot Head Club focuses especially on organizations within Austin that need extra helping hands, as well as community involvement and environmental issues around central Texas.</div>
                     <div class="spacer"></div>
                     <div>This madness started with <a href="http://www.atlantaparrotheadclub.org/" target="_blank">one club in Atlanta</a>, and has blossomed into a network of over 250 clubs and growing around the United States plus some international clubs based in Canada, Australia and The Bahamas.</div>
                     <div class="spacer"></div>
                     <div>Here in Austin, our goal is to get Parrot Heads from all over the Hill Country together for PHun, PHood and perhaps some pirate adventures from time-to-time, as well as supporting the goals of PHiP.</div>
				</div>
               <a name="bod"></a>
               <div class="spacer"></div>
               <div class="WAWseperator"></div>
               <div class="spacer"></div>
            <div id="WAWbodcont">
               <div class="WAWinline"><h4 class="WAWinline">2013 Board of Directors</h4></div>
                  <a href="http://ctxphc.com/who-are-we#top">[Back to Top]</a>
            </div>
               <table>
				<tr valign="top" >
					<td class="ctxphc" width="150"><div class="centerTXT"><a href="mailto:president@ctxphc.com"><img class="WAWimg" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/BOD/President.jpg" alt="Doug Hall" width="140" height="112" /></a></div></td>
					<td class="ctxphc" width="150">
						<div class="centerTXT">President</div>
						<div class="centerTXT"><a href="mailto:president@ctxphc.com">Doug Hall</a></div>
					</td>
					<td class="ctxphc" width="150" align="center"><div><a href="mailto:vice-president@ctxphc.com"><img class="WAWimg" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/BOD/Vice-President.jpg" alt="Tammy Camp" width="140" height="112" /></a></div></td>
					<td class="ctxphc" width="150">
						<div class="centerTXT">Vice President</div>
						<div class="centerTXT"><a href="mailto:vice-president@ctxphc.com">Tammy Camp</a></div>
					</td>
				</tr>
				<tr valign="top" >
					<td class="ctxphc" width="150" align="center"><div><a href="mailto:membership@ctxphc.com"><img class="WAWimg" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/BOD/Membership.jpg" alt="Donna Pickrell" width="140" height="112" /></a></div></td>
					<td class="ctxphc">
						<div class="centerTXT">Membership Director</div>
						<div class="centerTXT"><a href="mailto:membership@ctxphc.com">Donna Pickrell</a></div>
					</td>
					<td class="ctxphc" width="150" align="center"><div><a href="mailto:philanthrophy@ctxphc.com"><img class="WAWimg" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/BOD/Philanthropy.jpg" alt="Tony Kyles" width="140" height="112" /></a></div></td>
					<td class="ctxphc">
						<div class="centerTXT">Philanthropy Committee</div>
						<div class="centerTXT"><a href="mailto:philanthropy@ctxphc.com">Tony Kyles</a></div>
						<div class="centerTXT"><a href="mailto:philanthropy@ctxphc.com"></a></div>
						<div class="centerTXT"><a href="mailto:philanthropy@ctxphc.com"></a></div>
					</td>
				</tr>
				<tr valign="top" >
					<td class="ctxphc" width="150" align="center"><div><a href="mailto:treasurer@ctxphc.com"><img class="WAWimg" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/BOD/Treasurer.jpg" alt="Jeff Cohen" width="140" height="112" /></a></div></td>
					<td class="ctxphc">
						<div class="centerTXT">Treasurer/Secretary</div>
						<div class="centerTXT"><a href="mailto:treasurer@ctxphc.com">Jeff Cohen</a></div>
					</td>
					<td class="ctxphc" width="150" align="center"><div><a href="mailto:promotions@ctxphc.com"><img class="WAWimg" src="http://ctxphc.com/wp-content/themes/beach-holiday/Images/BOD/Promotions.jpg" alt="Tony Kyles" width="140" height="112" /></a></div></td>
					<td class="ctxphc">
						<div class="centerTXT">Promotions Committee</div>
						<div class="centerTXT"><a href="mailto:promotions@ctxphc.com">Tony Kyles</a></div>
						<div class="centerTXT"><a href="mailto:promotions@ctxphc.com"></a></div>
						<div class="centerTXT"><a href="mailto:promotions@ctxphc.com"></a></div>
					</td>
				</tr>
			</table>
				<div class="centerTXT"><a href="mailto:bod@ctxphc.com">Email the Board of Directors</a></div>
			<div class="spacer"></div>
            <div class="WAWseperator"></div><a name="address"></a>
            <div class="spacer"></div>
            <div id="WAWmailaddr">
                <div class="WAWinline">Mailing Address</div>
                <a href="http://ctxphc.com/who-are-we#top">[Back to Top]</a>
                <div class="mailadder">You can send us mail at:</div>
                <div class="mailadder"><img src="http://ctxphc.com/wp-content/Images/Title_Address.gif" alt="Central Texas Parrot Head Club" width="330" height="18" /></div>
                <div class="mailadder">P.O. Box 1074</div>
                <div class="mailadder">Austin, Texas 78767-1074</div>
                <div>Or, <a href="mailto:ctxphc@ctxphc.com">click here to e-mail us</a>.</div>
                <div class="spacer"></div>
            </div>
            <div class="WAWseperator"></div>
            <div><a name="history"></a>
                <div class="WAWinline">History Lesson</div>
                <a href="http://ctxphc.com/who-are-we#top">[Back to Top]</a>
                <div class="spacer"></div>
                <div>Our founders, Brian "Elvis" Hoskins (the first president) and Paul Kelley (his first vice president) came up with the idea to form a club here in Austin, and even made a road trip to San Antonio to meet with a Parrot Head who was the owner of a place called Tequila Charlies. The first club "meeting" took place in 1995 at a Hooters on the corner of Burnet and MoPac. According to Paul, "Hooters let us have the meetings there thanks to a manager named Allen. We made a really cool banner and created the logos for our cards over many a beer and wings."</div>
                <div class="spacer"></div>
                <div>Ron Barr was actually the first dues-paying member of our club, and that happened right around Christmas of 1995.</div>
                <div class="spacer"></div>
                <div>In 1998, Karen Rohlfs, took over the leadership and with the help of the founder of PHiP, re-chartered (and re-founded) the club, covering all costs out of her own pocket. And thank goodness she did that, because we wouldn't have the club we all love to work with (and have PHun with) if it weren't for Karen.</div>
                <div class="spacer"></div>
                <div class="WAWseperator"></div>
            </div>

			<div class="clear"></div>
	</div> <!-- entry -->
</div> <!-- post -->
<?php
		endwhile;
	endif;
?>
</div> <!-- Content -->
<?php get_sidebar(); ?>
<?php get_footer();?>
