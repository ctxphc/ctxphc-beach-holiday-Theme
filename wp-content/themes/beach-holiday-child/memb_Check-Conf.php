<?php
/*
Template Name: Check_Conrirmation
*/
?>

<?php
global $wpdb;
$membRec = $wpdb->get_row("SELECT * FROM ctxphc_ctxphc_members WHERE memb_user = '{$membID}'");
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
					<div> This is the Order Confirmation Page.  This is where you will return on sucessful printing of your of membership form.</div>
					<div>Once you have mailed your check and we have received it.  Youw will receive an email from our Membership Director with your username and a link to set your password.</div>
				<?php
				$email_to = "kaptkaos@gmail.com";
				$email_subject = "Membership Check Processing";
				$email_message = "A check payment has been printed.  I still need to figure out how to get the members info to you, but that will be coming soon.";
				// create email headers
				$headers = 'From: ctxphc@ctxphc.com\r\n Reply-To: membership@ctxphc.com\r\n X-Mailer: PHP/' . phpversion();
				@mail($email_to, $email_subject, $email_message, $headers);  
				?>
				 
				 
				Thank you for joining our phun team! We will be in touch with you very soon.


               <div class="clear"></div>
            </div> <!-- entry -->
         </div> <!-- post -->

<?php
      endwhile;  //have_posts
   endif;  //have_posts
?>
</div> <!-- content -->
<?php get_sidebar(); ?>
<!-- start footer -->
<?php get_footer();?>
<!-- end footer -->

