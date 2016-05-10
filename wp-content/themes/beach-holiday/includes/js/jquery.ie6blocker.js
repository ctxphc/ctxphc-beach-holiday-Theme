var IE6 = (navigator.userAgent.indexOf("MSIE 6")>=0) ? true : false;
if(IE6){
	jQuery(function(){
		jQuery("<div>")
			.css({
				'position': 'absolute',
				'top': '0px',
				'left': '0px',
				backgroundColor: 'black',
				'opacity': '0.75',
				'width': '100%',
				'height': jQuery("body").height(),
				zIndex: 5000
			})
			.appendTo("body");
			
		jQuery("<div style='padding:10px;'><img src='"+template_directory+"/images/no-ie6.png' alt='' style='float: left;'/><br/><br/><h4>Sorry! This page doesn't support Internet Explorer 6.</h4><br/>If you'd like to read our content please upgrade your IE or use other browsers like <a href='http://getfirefox.org'>Firefox</a>, <a href='http://getfirefox.org'>Chrome</a>, <a href='http://getfirefox.org'>Opera</a>.")
			.css({
				backgroundColor: 'white',
				'top': '50%',
				'left': '50%',
				marginLeft: -210,
				marginTop: -100,
				width: 410,
				paddingRight: 10,
				height: 200,
				'position': 'absolute',
				zIndex: 6000
			})
			.appendTo("body");
	});		
}