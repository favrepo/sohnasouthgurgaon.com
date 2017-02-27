<?php

require_once("class.phpmailer.php");
define('GUSER', 'ravi.chhillar@gmail.com'); // Gmail username
define('GPWD', 'chhillar123'); // Gmail password



function smtpmailer($to, $subject, $body,$addcc = '') {
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'smtp.gmail.com';		$mail->Mailer = "smtp";
	$mail->Port = 465;
	$mail->Username = GUSER;
	$mail->Password = GPWD;
	$mail->From = GUSER;	$mail->Fromname = "Ravi Chhillar";	
	//$mail->AddCC($addcc,'web admin');
	//$mail->AddCC('avanesh@favista.com','Avanesh Singh');
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->IsHTML();
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo;
		return false;
	} else {
		$error = 'Message sent!';
		return true;
	}
}
?>