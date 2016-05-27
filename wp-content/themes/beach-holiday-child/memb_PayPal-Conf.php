<?php
/*
Template Name: PayPal_Conrirmation
*/
?>

<?php
$debug = FALSE;

if ( $debug ){
	$pp_hostname = "www.sandbox.paypal.com";
} else {
	$pp_hostname = "www.paypal.com";
}

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';
 
$pp_response = send_pp_PDT( $pp_hostname, $req, $debug );

if(!$pp_response){
	//HTTP ERROR
}else{
    
    if ( $debug ){ error_log("!!!!!!  pp_response is valid. Req = $req  !!!!!!!"); } 
    
    $pp_PDT_data = process_pp_PDT_response( $pp_response, $debug );

     //select correct message for payment type submitted.
    $item_number = $pp_PDT_data['item_number'];

    if ( $debug ){ error_log("!!!!!!  item_number is $item_number  !!!!!!!"); } 
    
    $message = select_pay_complete_message( $item_number, $debug );

    //Buyer's info
    $firstname	= $pp_PDT_data['first_name'];
    $lastname	= $pp_PDT_data['last_name'];
    $itemname	= $pp_PDT_data['item_name'];
    $amount		= $pp_PDT_data['payment_gross'];
    $payer_email	= $pp_PDT_data['payer_email'];

    //Sellers Info
    $payment_status = $pp_PDT_data['payment_status'];
    $payment_date	= $pp_PDT_data['payment_date'];
    $custom		= $pp_PDT_data['custom'];
    $trans_id	= $pp_PDT_data['txn_id'];
    $txn_type	= $pp_PDT_data['txn_type'];
    $receiver_email = $pp_PDT_data['receiver_email'];
}    
    
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
				<?php 
				echo "<p><h3>Thank you for your purchase!</h3></p>";
				echo "<b>Payment Details</b><br>";
				echo "<li>Name: $firstname $lastname</li>";
				echo "<li>Item: $itemname</li>";
				echo "<li>Amount: $amount</li>";
				
                                echo "<p>$message";
                                
				?>
				<?php wp_link_pages(array('before' => '<div><strong><center>Pages: ', 'after' => '</center></strong></div>', 'next_or_number' => 'number')); ?>
				<div class="clear"></div>
			</div> <!-- entry -->
		 </div> <!-- post -->

<?php
	  endwhile;
   endif;
?>
</div> <!-- end content -->
<?php get_sidebar(); ?>
<!-- start footer -->
<?php get_footer();?>
<!-- end footer -->
?>