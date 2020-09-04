<?php

/*
This first bit sets the email address that you want the form to be submitted to.
You will need to change this value to a valid email address that you can access.
*/
$admin_email = "Kwasi546@gmail.com";
/*
This bit sets the URLs of the supporting pages.
If you change the names of any of the pages, you will need to change the values here.
*/
$contact_page="contact.html";
$error_page = "error_message.html";
$thankyou_page = "thank-you.html";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
*/
$email_address = $_REQUEST['myEmail'] ;
$message = $_REQUEST['myMessage'] ;
$name = $_REQUEST['myName'] ;
$email_msg = 
"Name: " . $name . "\r\n" . 
"Email: " . $email_address . "\r\n" . 
"Message: " . $message;

if(!isset($_POST['submit']) && empty($name) && empty($email_address) && empty($message))
{
	//This page should not be accessed directly. Need to submit the form.
	header( "Location: $error_page" );
	exit;
}
//Validate to make sure name, email, and message box are not empty
if(empty($name) || empty($email_address) || empty($message)) 
{
	header( "Location: $error_page" );
	exit;
}

//Validate email for correct format of email
if(!stristr($email_address,"@") || !stristr($email_address,".")) {

echo '<script>alert("Your email address is not in correct format of below: (example@<email_provider>.com)")</script>'; 
header( "Location: $error_page" );
exit;
}

/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
*/
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['myEmail'])) {
header( "Location: $contact_page" );
}
/* 
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
else if ( isInjected($email_address) || isInjected($name)  || isInjected($comments) ) {
header( "Location: $error_page" );
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else {

	mail("$admin_email", "Feedback Form Results", $email_msg );

	header( "Location: $thankyou_page" );
}
?>