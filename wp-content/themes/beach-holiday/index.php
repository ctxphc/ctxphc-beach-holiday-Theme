<?php get_header(); ?>
<div id="content">
<?php
	if(is_home() && function_exists('get_a_post')){//detect if FCG is active
		//include (ABSPATH . '/wp-content/plugins/featured-content-gallery/gallery.php');
	}

	if (have_posts()) :
		$post = $posts[0]; // Hack. Set $post so that the_date() works.
		if(is_category()){
			echo '<h3 class="archivetitle">Archive for the Category &raquo;'.single_cat_title('',FALSE).' &laquo;</h3>';
		}elseif(is_day()){
			echo '<h3 class="archivetitle">Archive for &raquo; '.get_the_time('F jS, Y').'&laquo;</h3>';
		}elseif(is_month()){
			echo '<h3 class="archivetitle">Archive for &raquo; '.get_the_time('F, Y').' &laquo;</h3>';
		}elseif(is_year()){
			echo '<h3 class="archivetitle">Archive for &raquo; '.get_the_time('Y').' &laquo;</h3>';
		} elseif(is_search()){
			echo '<h3 class="archivetitle">Search Results</h3>';
		}elseif(is_author()){
			echo '<h3 class="archivetitle">Author Archive</h3>';
		}elseif(is_tag()){
			echo '<h3 class="archivetitle">Tag-Archive for &raquo; '.single_tag_title('',FALSE).' &laquo; </h3>';
		}elseif((is_home()||is_front_page()) && $paged>1){ // If this is a paged archive
			echo '<h3 class="archivetitle">Blog Archives</h3>';
		}else{
			echo '<div class="spacer">&nbsp;</div>';
		}	

		while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post_title">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<span class="post_author">Author: <?php the_author_posts_link('nickname'); ?><?php edit_post_link(' Edit ',' &raquo;','&laquo;'); ?></span>
					<span class="post_date_m"><?php the_time('M');?></span>
					<span class="post_date_d"><?php the_time('d');?></span>
				</div>
				<div class="clear"></div>
				<div class="entry">
					<?php echo templatelite_get_postthumb($post->ID,$tpinfo[$tpinfo['tb_prefix'].'_postthumb_width'],$tpinfo[$tpinfo['tb_prefix'].'_postthumb_height'],'img','post_thumb');?>
					<?php
						if($tpinfo[$tpinfo['tb_prefix'].'_postthumb_show']=='true' || is_search()){
							templatelite_excerpt('TRUE',"Read more..."," [ "," ] ");
						}else{
							the_content(" more &raquo;");
						}
					?>
				</div>
				<div class="clear"></div>
				<div class="info">
					<span class="info_category">Category: <?php the_category(', ') ?></span>
					<?php the_tags('&nbsp;<span class="info_tag">Tags: ', ', ', '</span>'); ?>
					&nbsp;<span class="info_comment"><?php comments_popup_link('Leave a Comment','One Comment', '% Comments', '','Comments off'); ?></span>
				</div>
			</div>
<?php 
		endwhile; 
		if($wp_query->max_num_pages > 1):		
?>
			<div class="navigation">
				<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
			</div>
<?php
		endif;
	else : //if have posts
?>
		<h3 class="archivetitle">Not found</h3>
		<p class="sorry">"Sorry, but you are looking for something that isn't here. Try something else.</p>
<?php 
	endif; 
?>
</div><!-- #content -->
<?php get_sidebar(); ?>
<?php get_footer();?>