<?php
/**
 * Template Name: Member Mapping
 *
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 1/19/2016
 * Time: 7:27 AM
 */
?>
<script>
  function initialize() {
	  var mapCanvas = document.getElementById('map');
	  var mapOptions = {
		  center: new google.maps.LatLng(44.5403, -78.5463),
		  zoom: 8,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
	  }
	  var map = new google.maps.Map(mapCanvas);
  }
</script>
<?php
get_header(); ?>

	<div id="content"><div class="spacer"></div>
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_title">
					<h1><a href="<?php the_permalink() ?>" rel="bookmark"
					       title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
						<span
							class="post_author">Author: <?php the_author_posts_link( 'nickname' ); ?><?php edit_post_link( ' Edit ', ' &raquo;', '&laquo;' ); ?></span>
					<span class="post_date_m"><?php the_time( 'M' ); ?></span>
					<span class="post_date_d"><?php the_time( 'd' ); ?></span>
				</div> <!-- post_title -->
				<div class="clear"></div>
				<div class="entry">
					<?php the_content( 'more...' ); ?>
					<div class="clear"></div>
					<?php wp_link_pages( array( 'before'         => '<div><strong><center>Pages: ',
					                            'after'          => '</center></strong></div>',
					                            'next_or_number' => 'number',
					) ); ?>
					<div id="ggl_memb_map"></div>
					<div class="clear"></div>
				</div> <!-- entry -->
			</div> <!-- post -->
			<?php
		endwhile;
		?>
	</div><!-- #content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>