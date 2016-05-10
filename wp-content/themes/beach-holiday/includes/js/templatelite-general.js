jQuery(document).ready(function(){
//drop-down menu
    jQuery("#menu li").hover(function(){
      jQuery(this).addClass("hover");
		jQuery('ul:first',this).css('visibility', 'visible');
    }, function(){
        jQuery(this).removeClass("hover");
        jQuery('ul:first',this).css('visibility', 'hidden');
    });
    jQuery("#menu li ul li:has(ul)").find("a:first").append(" &raquo;");
    jQuery("#menu>li:gt(0)").before("<li class=\"separator\">&bull;</li>");
});