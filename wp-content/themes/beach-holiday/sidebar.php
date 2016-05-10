<div id="sidebar">
<ul>
    <li><h4>Phlock Access</h4>
	<ul>
		<li><a id="side-login-in" href=<?php echo get_site_url() . '/login'; ?>>Renew Here!!</a></li>  <!-- is now redirecting to wp-login.php -->
		<li><a href=<?php echo admin_url(); ?>>BOD Login</a></li>
	</ul>
    </li>

	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) :
	?>
		<?php wp_list_pages('sort_column=menu_order&title_li=<h4>Pages</h4>'); ?>
		<?php wp_list_categories('show_count=1&title_li=<h4>Categories</h4>'); ?>


<?php endif; ?>

<?php //wp_list_pages('sort_column=menu_order&title_li=<h4>Pages</h4>'); ?>
<?php wp_list_categories('show_count=1&title_li=<h4>Categories</h4>'); ?>

<?php theme_sb_credit();?>
</ul>
</div>