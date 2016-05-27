<?php
/*
Template Name: Renewal-Confirmation
*/
?>

<?php get_header(); ?>
<div id="content"><div class="spacer"></div>
   <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
         <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="post_title">
               <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
            </div> <!-- post_title -->
            <div class="clear"></div>
            <div class="entry">
               <?php the_content('more...'); ?><div class="clear"></div>
               <h2 style="text-align: center;">CTXPHC Membership Renewal Confirmation</h2>
			<p>Thank you for renewing your membership with Central Texas Parrot Head Club!<br>
				Check out our <a href="http://www.ctxphc.com/calendar/" >calendar</a> for our current list of phun and exciting things to do!</p>
			<p>Thank you,<br>
				<a href="mailto:membership@ctxphc.com">CTXPHC Membership</a></p>
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

