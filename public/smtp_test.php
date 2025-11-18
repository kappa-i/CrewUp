<?php

// CHEMINS EXACTS SELON TA STRUCTURE
require_once __DIR__ . '/../src/classes/PHPMailer/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../src/classes/PHPMailer/PHPMailer/SMTP.php';
require_once __DIR__ . '/../src/classes/PHPMailer/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // CONFIG SMTP INFOMANIAK
    $mail->isSMTP();
    $mail->Host       = "mail.infomaniak.com";
    $mail->Port       = 465;
    $mail->SMTPAuth   = true;
    $mail->Username   = "contact@crewup.ch";
    $mail->Password   = "v#10_w!HX-6tLVw";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->CharSet    = "UTF-8";

    // EXPÉDITEUR
    $mail->setFrom("contact@crewup.ch", "CrewUp");

    // DESTINATAIRE (met un vrai mail à toi)
    $mail->addAddress("tonmailperso@gmail.com");

    // CONTENU
    $mail->isHTML(true);
    $mail->Subject = "TEST SMTP – CrewUp";
    $mail->Body    = "<p>Ceci est un test depuis CrewUp.</p>";

    // ENVOI
    $mail->send();
    echo "<h1 style='color: green'>✔ Mail envoyé !</h1>";

} catch (Exception $e) {
    echo "<h1 style='color: red'>❌ Erreur : {$mail->ErrorInfo}</h1>";
}
