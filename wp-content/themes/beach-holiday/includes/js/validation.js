//global vars  
var form = $("#reg_form");  
var memType = $("#mem_type");

//Primary Members Info
var fname = $("#fname");  
var lname = $("#lname");  
var email = $("#email");  
var phone = $("#phone");
var bdayday = $("#day");
var bdaymonth = $("month");
var bdayyear = $("year");
var occu = $("occu");
var address = $("add1");
var city = $("city");
var state = $("state");
var zip = $("zip");

//Partner/Spouse Info
var sfname = $("sfname");
var slname = $("slname");
var semail = $("semail");
var sphone = $("sphone");
var sbdayday = $("sday");
var sbdaymonth = $("smonth");
var sbdayyear = $("syear");
var srelationship = $("srelationship");

//Family Members Info
var f1name = $("f1name");
var f1relationship = $("f1relationship");
var f1bdayday = $("f1day");
var f1bdaymonth = $("f1month");
var f1bdayyear = $("f1year");
var f1email = $("f1email");

var f2name = $("f2name");
var f2relationship = $("f2relationship");
var f2bdayday = $("f2day");
var f2bdaymonth = $("f2month");
var f2bdayyear = $("f2year");
var f2email = $("f2email");

var f3name = $("f3name");
var f3relationship = $("f3relationship");
var f3bdayday = $("f3day");
var f3bdaymonth = $("f3month");
var f3bdayyear = $("f3year");
var f3email = $("f3email");

var profile = $("profile");

function validateForm()
{
	if(fname.val().length < 2)
	{
    alert ("Arr, your First name much have 2 or more letters!");
    return false;
	}

  if(lname.val().length < 2)
	{
    alert ("Arr, your Last name must also contain 2 or more letters!");
    return false;
  }

}


function validate_fname(){  
	//if it's NOT valid  
	if(fname.val().length < 2){  
		alert ("Arr, looks like you have left your first name blank.  Please correct this.");
		return false;  
	}  
}  

function validate_lname(){  
	//if it's NOT valid  
	if(lname.val().length < 1){  
		alert ("Arr, looks like you have left your last name blank.  Please correct this.");
		return false;  
	}  
}  


function validateName(){  
	//if it's NOT valid  
	if(name.val().length < 4){  
		name.addClass("error");  
		nameInfo.text("We want names with more than 3 letters!");  
		nameInfo.addClass("error");  
		return false;  
	}  
	//if it's valid  
	else{  
		name.removeClass("error");  
		nameInfo.text("What's your name?");  
		nameInfo.removeClass("error");  
		return true;  
	}  
}  


//On blur  
fname.blur(validatefname);  
lname.blur(validatelname);  
email.blur(validateEmail);  

//On key press  
name.keyup(validateName);  
pass1.keyup(validatePass1);  
pass2.keyup(validatePass2);  
message.keyup(validateMessage);  

//On Submitting  
form.submit(function(){  
	if( validatefname() && validateEmail() ){  
            return true; 
        } else {
            return false;
        }
});  
