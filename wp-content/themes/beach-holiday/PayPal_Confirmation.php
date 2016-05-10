<?php
/*
Template Name: PayPal Conf
 * 
*/


//Complete registration confirmation.
//Included DB updates to mark user as paid.

?>

<?php get_header('reg'); ?>

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
	   <h2 style="text-align: center;"><img id='PB_logo'alt="CTXPHC Pirate's Ball 2013 Logo" src="http://www.ctxphc.com/wp-content/Images/Pirates_Ball/Pirates_BallLogo-2013.jpg" /></h2>
	 </div>

&nbsp;
<h2 style="text-align: center;"><strong>Your  Central Texas Parrothead Club Pirate's Ball 2013 registration is complete!</strong></h2>
&nbsp;

<strong><a href="http://www.wyndham.com/hotels/texas/austin/wyndham-garden-austin/hotel-overview" target="_blank">Wyndham Garden Austin</a></strong> and CTXPHC have arranged <strong><i>special hotel rates</i>:</strong>
<ul>
	<li>$82/King</li>
	<li>$92/Executive King</li>
	<li>$102/Poolside Suite</li>
</ul>
Just ask for the <strong>Parrothead</strong> rate when contacting the hotel for reservation.

<b>Hotel information:</b>
<ul>
	<li><a href="http://www.wyndham.com/hotels/texas/austin/wyndham-garden-austin/hotel-overview" target="_blank">Wyndham Garden Austin</a></li>
	<li>Direct Number: (512) 448-2444</li>
	<li>Reservation Number:  877-999-3223</li>
</ul>
&nbsp;

Thank you for registering for the Central Texas Parrothead Club's 2013 Pirate's Ball!

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

