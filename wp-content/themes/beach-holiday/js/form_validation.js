function validateForm()
{
var x=document.forms["reg_form"]["fname"].value;
if (x<2)
  {
  alert("First name must contain 2 or more letters.");
  return false;
  }
}
