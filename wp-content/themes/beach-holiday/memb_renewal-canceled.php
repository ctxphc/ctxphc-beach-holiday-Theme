<?php
/*
Template Name: Canceled_reg
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
               <h2 style="text-align: center;">CTXPHC Membership Renewal Cancellation</h2>
			<p>If you were intending to renew your membership and ran into some kind of an issue or question<br>
				please email <a href="mailto:support@ctxphc.com">CTXPHC Support</a></p>
			<p>Thank you,<br>
				CTXPHC Support</p>

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

