<?php
//Stylesheets Reader
$alt_stylesheets = array();
if ( is_dir(TEMPLATEPATH . '/styles/') ) {
    if ($alt_stylesheet_dir = opendir(TEMPLATEPATH . '/styles/') ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

$tp_options=array(
	array(
		"desc" => "<h3>General Setting</h3>",
		"type" => "heading"),
	array(
		"name" => 'Twitter Icon',
		"desc" => ' Check to display Twitter icon in the Header',
		"id" => $tpinfo['prefix']."_twitter_show",
		"std" => "true",
		"type" => "checkbox"),

	array(
		"name" => 'Twitter Username',
		"desc" => '<br/>Your Twitter username. Leave blank to hide Twitter icon.',
		"id" => $tpinfo['prefix']."_twitter_username",
		"std" => "templatelite",
		"options" =>array("size"=>"35"),
		"type" => "text"),
	array(	
		"name" => 'Feed URL',
		"desc" => "<br/> Your feed URL. e.g. http://feeds2.feedburner.com/username<br/> Leave blank to use Wordpress default feed URL; i.e., ".get_bloginfo('rss2_url'),
		"id" => $tpinfo['prefix']."_feedurl",
		"std" => "",
		"options" =>array("size"=>"35"),
		"type" => "text"),

	array(
		"name" => 'Tracking Code',
		"desc" => "Paste your Google Analytics code (or other tracking code) in the box above.",
		"id" => $tpinfo['prefix']."_analytics",
		"std" => "",
		"type" => "textarea",
		"options" => array(	"rows" => "5","cols" => "70")),		
		
	array(
		"name" => 'IE6 Warning',
		"desc" => " Would you like to take part in the IE6 Warning Campaign? This will add a notification sign for IE6 web users to upgrade their browser to a newer version or other \"better\" browsers.",
		"id" => $tpinfo['prefix']."_ie6warning",
		"std" => "false",
		"type" => "checkbox"),
				
	array(
		"desc" => "<h3>Layout Setting</h3>",
		"type" => "heading"),		

		array( "name" => "Theme Style",
		"desc" => ' Select your theme style. The folder can be found under /styles 
		            <br/>&curren; default.css - The default style
		            <br/>&curren; sub-russia.css - The Russian style
		',
		
		"id" => $tpinfo['tb_prefix']."_stylesheet",
		"std" => "default.css",
		"type" => "select",
		"options" => $alt_stylesheets),
	array(
		"name" => 'Blog Title and Tagline',
		"desc" => ' Check to show the blog title and tagline in text format as defined in "Settings -> General".',
		"id" => $tpinfo['tb_prefix']."_blogtitle",
		"std" => "true",
		"type" => "checkbox"),
	array(
		"name" => 'Home Link',
		"desc" => ' Display "Home" link in top navigation',
		"id" => $tpinfo['tb_prefix']."_homelink",
		"std" => "false",
		"type" => "checkbox"),


	array(
		"desc" => "<h3>Post Layout</h3>",
		"type" => "heading"),
	array(
		"name" => 'Post Thumbnail',
		"desc" => ' Check to display thumbnails.'.
		          '<br/>&curren; The thumbnail images are defined in the admin\'s individual post pages'.
		          '<br/>&curren; For Home, Search and Category page etc',
		"id" => $tpinfo['tb_prefix']."_postthumb_show",
		"std" => "false",
		"type" => "checkbox"),

	array(
		"name" => 'Post Thumbnail Width',
		"desc" => ' px',
		"id" => $tpinfo['tb_prefix']."_postthumb_width",
		"std" => "150",
		"options" =>array("size"=>"3"),
		"type" => "text"),
	array(
		"name" => 'Post Thumbnail Height',
		"desc" => ' px',
		"id" => $tpinfo['tb_prefix']."_postthumb_height",
		"std" => "150",
		"options" =>array("size"=>"3"),
		"type" => "text"),
	array(
		"name" => 'Default Thumbnail',
		"desc" => ' Check to display the default thumbnail if individual post\'s thumbnail is not defined'.
					 '<br/>&curren; Only works when "Post Thumbnail" above is checked.'.
					 '<br/>&curren; The default thumbnail image can be <a href="'.get_bloginfo("template_directory").'/images/thumbnail.png">found here</a>. Follow the path to replace using FTP program.',
		"id" => $tpinfo['tb_prefix']."_postthumb_default",
		"std" => "false",
		"type" => "checkbox"),

	array(
		"name" => 'Post Excerpt', //0=unlimited
		"desc" => ' Insert number of word to display as excerpt (e.g. 100)'.
					 '<br/>&curren; Works when "Post Thumbnail" above is checked.',
		"id" => $tpinfo['tb_prefix']."_postexcerpt_words",
		"std" => "100",
		"options" =>array("size"=>"6"),
		"type" => "text"),

);

foreach ($tp_options as $value){ /*allows the theme to get info from the theme options*/
	if($value['type']!='heading'){
		if(get_option($value['id']) === FALSE) { 
			$tpinfo[$value['id']]=$value['std'];
		}else{
			$tpinfo[$value['id']]=get_option($value['id']);
		}
	}
}
?>