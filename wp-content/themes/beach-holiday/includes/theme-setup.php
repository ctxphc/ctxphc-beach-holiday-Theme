<?php
function style_tag_cloud($tags){
	return '<div style="padding:0 10px 0px 10px;">'.$tags.'</div>';
}
add_action('wp_tag_cloud', 'style_tag_cloud');

function templatelite_wp_head(){
	global $tpinfo;
	$stylesheet=!empty($_REQUEST['style'])? $_REQUEST['style'].".css":$tpinfo[$tpinfo['tb_prefix'].'_stylesheet'];
	
	//if(!empty($stylesheet) && $stylesheet!='default.css'){
	if(!empty($stylesheet)){
		echo '<link href="'. get_bloginfo('template_directory') .'/styles/'. $stylesheet .'" rel="stylesheet" type="text/css" />'."\n";
	}

	if($tpinfo['templatelite_ie6warning']=='true'){
		echo "<!--[if lte IE 6]>";
		echo "<script type='text/javascript'>var template_directory='".get_bloginfo("template_directory")."';</script>";
		echo "<script type='text/javascript' src='".get_bloginfo("template_directory")."/includes/js/jquery.ie6blocker.js'></script>";
		echo "<![endif]-->";
	}
}

add_action('wp_head', 'templatelite_wp_head');

add_theme_support('post-thumbnails',array( 'post', 'page'));
?>