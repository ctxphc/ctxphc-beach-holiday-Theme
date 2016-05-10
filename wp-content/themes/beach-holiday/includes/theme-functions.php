<?php
function templatelite_get_postthumb($postid,$width,$height,$return='img',$class=''){
	//$return=img,url
	global $tpinfo;
	
	if($tpinfo[$tpinfo['tb_prefix'].'_postthumb_show']!='true')	return '';

	$thumb_id=get_post_thumbnail_id($postid); // @since 2.9.0
	if($thumb_id){
		$thumb_url=wp_get_attachment_url($thumb_id);
	}else{
		if($tpinfo[$tpinfo['tb_prefix'].'_postthumb_default']!='true') return '';
		$thumb_url=get_bloginfo('template_url')."/images/thumbnail.png";
	}

//clean http:// prevent mod_security problem	
	$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
	$regex = "/^((ht|f)tp(s|):\/\/)(www\.|)" . $host . "/i";
	$thumb_url = preg_replace ($regex, '', $thumb_url);

	if($return=='img'){
		return '<img class="'.$class.'" src="'.get_bloginfo('template_url').'/includes/timthumb.php?src='.$thumb_url.'&amp;w='.$width.'&amp;h='.$height.'&amp;zc=1" alt=""/>';
	}else{ //return URL
		return $thumb_url;
	}
}

function templatelite_excerpt($displaylink='',$excerpt_more="...",$more_left='',$more_right=''){
	global $tpinfo;
	$text= has_excerpt() ? get_the_excerpt() : get_the_content('');
	$text = strip_shortcodes( $text );
	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = strip_tags($text,"<a><b><u><i><strong>");
		
	$excerpt_length = $tpinfo[$tpinfo['tb_prefix'].'_postexcerpt_words'];
	

	if(!empty($displaylink)){
		$excerpt_more=$more_left.'<a href="'.get_permalink().'">'.$excerpt_more.'</a>'.$more_right;
	}else{
		$excerpt_more=$more_left.$excerpt_more.$more_right;
	}
	
	$words = explode(' ', $text, $excerpt_length + 1);
	if (count($words) > $excerpt_length) {
		array_pop($words);
		$text = implode(' ', $words);
		$text = $text . $excerpt_more;
	}
	echo $text;
}
?>