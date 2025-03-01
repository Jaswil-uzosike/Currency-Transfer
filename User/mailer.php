<?php
// The code below is for sending the email to the user using PHPMailer.
// The research material are in the links below
// https://github.com/PHPMailer/PHPMailer
// https://www.youtube.com/watch?v=9tD8lA9foxw&t=92s

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';




$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'jaswilhallamprojects@gmail.com';                     
    $mail->Password   = 'ytwkkpyscxzxdteq';                               
    $mail->SMTPSecure = 'ssl';            
    $mail->Port       = 465;                                    

    //Recipients
    $mail->setFrom('jaswilhallamprojects@gmail.com', 'TRANSFERX');
    $mail->addAddress($_SESSION['mail_recipient'], $_SESSION['mail_recipient_fname']. ' '. $_SESSION['mail_recipient_lname']);    

    $mail->addReplyTo($_SESSION['mail_recipient'], $_SESSION['mail_recipient_fname']. ' '. $_SESSION['mail_recipient_lname'] );


    //Content
    $mail->isHTML(true);                                 
    $mail->Subject = 'Password Reset Link';
    $mail->Body    = "<b><a href='http://localhost/Software_Projects/Login/update_password.php'>Click Here</a></b> to reset your password.";
    $mail->AltBody = 'Here is your password reset link: http://localhost/Software_Projects/Login/update_password.php';
    

    $mail->send();
    echo "<script>alert('Mail Sent Successfully. Check inbox or spam'); window.location.href='../index.php';</script>";
    exit; 
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

