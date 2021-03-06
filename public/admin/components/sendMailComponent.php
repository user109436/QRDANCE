<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('../../../private/config.php');
isAdmin();

include('PHPMailer/PHPMailer.php');
include('PHPMailer/SMTP.php');
include('PHPMailer/Exception.php');
if (isset($_POST['email']) && (int)$_POST['email']) {

    $id = $_POST['email'];
    $credentials = findAll('accountlist', $id);
    $hostName = getDataFromTable(1, 'domain_name', 'settings');
    $position = strpos($hostName, '/log.php?id=');
    $link = substr_replace($hostName, "", $position);
    $link = "<a href=" . $link . ">Log In Here</a>";

    if ($credentials && strpos($credentials[0]['email'], '@gmail.com')) {
        $credential = $credentials[0];
        $message = 'Username:' . $credential['username'] . "<br>";
        $message .= 'Password:' . $credential['password'] . "<br>";
        if ($credential['active']) {
            $active = "True";
        } else {
            $active = "False";
        }
        $message .= 'Active:' . $active . "<br>";
        $message .= $link;
        try {
            //Server settings
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                       //Enable verbose debug output
            $mail->isSMTP();                                                //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                           //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                       //Enable SMTP authentication
            $mail->Username   = 'tsukoyomi243@gmail.com';                   //SMTP username
            $mail->Password   = 'AlphaOmega07G';                             //SMTP password
            $mail->SMTPSecure = 'tls';                                      //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                        //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('qrdance@company.com', 'QRDANCE');
            $mail->AddReplyTo('noreply@gmail.com', 'This is Computer Generated');
            $mail->addAddress($credential['email']);

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Login Credentials';
            $mail->Body    = $message;
            $mail->AltBody = $message;

            if ($mail->send()) {
                exit(message("Account Credentials Sent to " . $credential['email'], 1));
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo message("Invalid ID for sending email");
        exit;
    }
}
