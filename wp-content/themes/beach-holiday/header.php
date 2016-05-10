<?php global $tpinfo;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>" charset="<?php bloginfo('charset');?>"/>
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php echo (!empty($tpinfo['templatelite_feedurl']))? $tpinfo['templatelite_feedurl'] : bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_get_archives('type=yearly&format=link'); ?>
	<title>
	<?php
		wp_title(' &raquo; ',true,'right');
		bloginfo('name');
		if(is_home() && !is_paged()) { echo ' &raquo; '; bloginfo('description'); }
		echo (is_paged() && $paged>1)? " &raquo; Page $paged":"";
	?>
	</title>

     <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-35339766-1', 'auto');
          ga('send', 'pageview');

     </script>

	<?php
		if(is_singular()){
			wp_enqueue_script('comment-reply');
		}
		wp_head();
	?>
	<script type="text/javascript" src="<?php bloginfo("template_directory");?>/includes/js/templatelite-general.js"></script>
</head>
<body <?php body_class();?>>
<div id="bg_top"><div id="bg_btm">
	<div id="base"><div id="base_btm">
		<div id="header">
			<div id="menubar">
				<ul id="menu">
				<?php
					$options = get_option('widget_pages');
					$exclude = empty($options['exclude'] ) ? '' : $options['exclude'];
					if($tpinfo[$tpinfo['tb_prefix'].'_homelink']=='true') echo '<li><a href="'.get_bloginfo('home').'">Home</a></li>';
					wp_list_pages('sort_column=menu_order&title_li=&exclude='.$exclude)
				?>
				</ul>
			</div><!-- #menubar -->
			<?php
				$tmp=(is_single() || is_page())? "div":"h1";
				$tmp2=($tpinfo[$tpinfo['tb_prefix'].'_blogtitle']=='true')? '':' class="indent"';
			?>
			<<?php echo $tmp;?>  id="blog_name"<?php echo $tmp2;?>><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></<?php echo $tmp;?>>
			<div id="blog_desc"<?php echo $tmp2;?>><?php bloginfo('description');?></div>
			<a href="<?php bloginfo('url'); ?>/" title="Home" class="home"></a>
			<form class="mainsearch" action="<?php bloginfo('url'); ?>/" method="get">
				<input class="keyword" type="text" name="s" id="s" value="Search ..." onfocus="if (this.value == 'Search ...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search ...';}"/>
				<input class="submit" value="" type="submit"/>
			</form>

			<?php
			if($tpinfo['templatelite_twitter_show']=='true'){
				echo '<a href="http://www.twitter.com/' . $tpinfo['templatelite_twitter_username'].'" title="Follow me" class="twitter"></a>';
			}
			?>
		</div><!-- #header -->
		<div id="container">
				<a href="<?php echo (!empty($tpinfo['templatelite_feedurl']))? $tpinfo['templatelite_feedurl'] : bloginfo('rss2_url'); ?>" title="RSS Feed" class="rss"></a>
