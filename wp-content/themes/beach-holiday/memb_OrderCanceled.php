<?php
/*
Template Name: Order_Canceled
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
               <?php wp_link_pages(array('before' => '<div><strong><center>Pages: ', 'after' => '</center></strong></div>', 'next_or_number' => 'number')); ?>
					<div>You have choosen to cancel your registration.  This is wher Paypal will send after canceling an order.</div>

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

