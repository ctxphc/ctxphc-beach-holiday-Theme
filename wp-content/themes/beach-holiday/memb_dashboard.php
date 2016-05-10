<?php
/*
Template Name: Memb_Dashboard
*/

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( site_url('/login/')); exit;
}
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
                <h2>Member Dashboard</h2>
		            <div class="memb_dash_links" id="memb_dash_div">
			            <!-- Individual Member Option -->
			            <a href="http://www.ctxphc.com/membership-renewal">Renewal</a>

			            <!-- Individual + Child(ren) Member Option -->
			            <a href="http://www.ctxphc.com/logout">Logout</a>

			            <!-- Couple Member Option -->
			            <a href="http://www.ctxphc.com/edit-member">Profile</a>
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