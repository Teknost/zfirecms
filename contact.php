<?php 
/* ****************************************************************************
 ZCMS 0.2
 2016.12.09 
 Greg Zapf
 
 Simple Contact Form Email Processor
 
 This is an example of how to provide live PHP processing for a ZCMS website.  
 No special considerations are required, and "best of class" functionality can 
 be added as desired, or your existing custom form processors can be used.
 
 This is called by the form found in zcms_content/form_contact.php

**************************************************************************** */


function clean_input($data, $oneline=1, $length = 200) {
	// Remove potential nasty form inclusions.
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = ($oneline) ? str_replace(array("\n", "\r"), "", $data): $data;
	$data = substr($data, 0, $length);
	return $data;
}

// Scrub incoming fields.
	$url = clean_input($_POST['url']);
	$message = clean_input($_POST['message'], 0, 1000);
	$name = clean_input($_POST['name']);
	$email = clean_input($_POST['email']);
	$referer = clean_input($_SERVER['HTTP_REFERER']);

// if the url field is empty we proceed to email.  The url field is a trap for spam bots.
if(isset($_POST['url']) && $url == ''){

	// Skip emailing if the user did not provide a message and a legit email address.  Tune to your taste.
    if($message != "" && filter_var($email, FILTER_VALIDATE_EMAIL)){
		// put your email address here     
		$youremail = 'zfirecms@example.com';

		$headers = "From: \"$name\" <$email>";     

		if(isset($_SERVER['HTTP_REFERER'])) {
			$message .= "\n\n\n\nCalling Page:\t" . $referer;
		}
		if(isset($_SERVER['REMOTE_ADDR'])) {
			$message .= "\n\nIP Address:\t" . $_SERVER['REMOTE_ADDR'];
		}
		
		// Send the message.
		mail($youremail, "ZCMS Contact" , $message, $headers ); 
    }
} 

// Regardless of success (to avoid tipping spammers), send the user to Thanks page.
header('Location: thanks.html');
exit('Redirecting to thanks.html');

?>