<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

function sendAccountMail($mail, $mailToSend, $activationHash)
{
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = '';                    //SMTP username
    $mail->Password   = '';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('thomasmrt200@gmail.com', 'Valider votre compte');
    $mail->addAddress($mailToSend, 'User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <a href="http://localhost:8080/activateAccount.php?token='.$activationHash.'">activer votre compte.</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (empty($mail->Username) || empty($mail->Password)) {
        return ("identifiants SMTP manquants.");
    } else {
        $mail->send();
        return ("Veuillez valider votre email pour activer votre compte");
    }


}
