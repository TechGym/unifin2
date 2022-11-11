<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = "smtp.titan.email";
    $mail->Port = 587;
    $mail->Username = "support@unifin.cc";
    $mail->Password = "Dead_Alnix...";
    $mail->setFrom('support@unifin.cc', 'unifin.cc');
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $message;
    if (!$mail->send()) {
        return "error";
    } else {
        return "sent";
    }
}

function sendSupportEmail($from, $message, $subject) {
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Host = "smtp.titan.email";
    $mail->Port = 587;
    $mail->Username = "support@unifin.cc";
    $mail->Password = "Dead_Alnix...";
    $mail->clearReplyTos();
    $mail->addReplyTo($from);
    $mail->setFrom('support@unifin.cc', 'unifin.cc');
    $mail->addAddress("support@unifin.cc");
    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body = $message;
    if (!$mail->send()) {
        return "error";
    } else {
        return "sent";
    }
}
function contactSupport($from, $path,  $subject, $user, $organization, $email, $heading, $content) {
    $message = file_get_contents($path);
    $message = str_replace("{user}", $user, $message);
    $message = str_replace("{organization}", $organization, $message);
    $message = str_replace("{email}", $email, $message);
    $message = str_replace("{heading}", $heading, $message);
    $message = str_replace("{message}", $content, $message);
    return sendSupportEmail($from, $message, $subject);
}
function emailVerificationMail($to, $email_ref, $path) {

    $subject = 'Verify your email address';
    $link = "https://unifin.cc/backdoor/verifications.php?type=verifyemail&email=" . $to . "&key=" . $email_ref;
    $message = file_get_contents($path);
    $message = str_replace("{LINK}", $link, $message);
    $status = sendEmail($to, $subject, $message);
    return $status;
}
function sendSubscriptionMail($to, $path, $key) {
    $subject = "Newsletter subscription successful";
    $message = file_get_contents($path);
    $message = str_replace("{ADDR}", $key, $message);
    $status = sendEmail($to, $subject, $message);
    return $status;
}
//  emailEditMail is used in both reset password and emailEdit
function emailEditMail($to, $code, $path, $user) {
    $subject = "Email Confirmation ";
    $message = file_get_contents($path);
    $message = str_replace("{CODE}", $code, $message);
    $message = str_replace("{user}", $user, $message);
    $status = sendEmail($to, $subject, $message);
    return $status;
}
function detailChangeMail($to, $path, $type, $user, $ip, $date) {
    $subject = "Details Change Notice";
    $message = file_get_contents($path);
    $message = str_replace("{type}", $type, $message);
    $message = str_replace("{user}", $user, $message);
    $message = str_replace("{ip}", $ip, $message);
    $message = str_replace("{date}", $date, $message);
    $status = sendEmail($to, $subject, $message);
    return $status;
}

function sendPasswordResetMail($to, $firstname, $ip, $date, $path) {
    $subject = "Password Reset Notification";
    $message = file_get_contents($path);
    $message = str_replace("{user}", $firstname, $message);
    $message = str_replace("{ip}", $ip, $message);
    $message = str_replace("{date}", $date, $message);
    $status = sendEmail($to, $subject, $message);
    return $status;
}
