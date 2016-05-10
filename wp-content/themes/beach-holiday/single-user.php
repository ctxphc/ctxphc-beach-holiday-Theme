<?php get_header(); ?>
	<div id="content">
		<div class="spacer"></div>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_title">
					<h1 class="single_user_title">
						<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php
						the_title_attribute(); ?>">
							<?php the_title(); ?></a>
					</h1>
				</div>
				<div class="clear"></div>
				<div class="entry single-user-entry">
					<?php the_content( 'more...' ); ?>
					<div class="clear"></div>
					<?php wp_link_pages( array(
						'before'         => '<div><strong class="aligncenter">Pages: ',
						'after'          => '</strong></div>',
						'next_or_number' => 'number',
					) ); ?>
				</div>
				<div class="info">
					<span class="info_category">Category: <?php the_category( ', ' ) ?></span>
					<?php the_tags( '&nbsp;<span class="info_tag">Tags: ', ', ', '</span>' ); ?>
				</div>
			</div>

			<div id="postmetadata">
				edit_post_link('Edit this entry','(',')');
			</div>
			<?php
		endwhile;
			?>
			<div class="navigation">
				<div class="alignleft"><?php previous_post_link( '&laquo; %link' ) ?></div>
				<div class="alignright"><?php next_post_link( '%link &raquo;' ) ?></div>
			</div>
			<?php
		else : ?>
			<h3 class="archivetitle">Not found</h3>
			<p class="sorry">Sorry, but you are looking for something that isn't here. Try something else.</p>
			<?php
		endif;
		?>

	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>