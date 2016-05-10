function validateForm()
{
	var x=document.forms["reg_form"]["fname"].value;
	alert("the value of x is" + x);
	if(x < 2)
	{
		alert("First Name must contain at least 2 letters.");
		return false;
	}

	var y=document.forms["reg_form"]["lname"].value;
	if (y < 2)
	{
		alert("Last Name must contain 2 or more letters.");
		return false;
	}
}

