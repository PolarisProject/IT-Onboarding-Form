<?php

require 'PHPMailerAutoload.php';
include_once('class.phpmailer.php');
require_once('class.smtp.php');

//Configuration
$fields = array('fname' => 'First Name', 'lname' => 'Last Name', 'sdate' => 'Start Date', 'role'=>'Role', 'manager' => 'Manager', 'department' => 'Department', 'pcrequest' => 'PC Request', 'phonerequest'=>'Phone Request', 'vpnaccess' => 'VPN Access', 'licenses' => 'Licenses', 'access'=>'Access'); // array variable name => Text to appear in email
$okMessage = 'Onboarding form form successfully submitted. Thank you, there is a 24 hour response time, and up to two weeks to ensure everything is completed.';
$errorMessage = 'There was an error while submitting the form. Please try again later';


// let's do the sending

try
{
    $emailText = "You have new message from the onboarding form form\n=============================\n";

    foreach ($_POST as $key => $value) {

        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }
    
$mail = new PHPMailer;

$mail->SMTPDebug = False;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.office365.com';                   // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'itserviceacct@dharbor.com';                 // SMTP username
$mail->Password = '$&kgX2dUhRG9L';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to
    
$mail->setFrom('itserviceacct@dharbor.com', 'Onboarding Form');
$mail->addAddress('IT@dharbor.com', 'Hans');     // Add a recipient
$mail->addReplyTo('IT@dharbor.com', 'Hans Knecht');


$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Onboarding Request';
$mail->msgHTML($emailText);
$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

    if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}
else {
    echo $responseArray['message'];
}




?>