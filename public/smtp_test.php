<?php

// Chemins EXACTS (avec C majuscule à Classes)
require_once __DIR__ . '/../src/Classes/PHPMailer/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../src/Classes/PHPMailer/PHPMailer/SMTP.php';
require_once __DIR__ . '/../src/Classes/PHPMailer/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<pre>== SMTP TEST START ==\n";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug  = 3;            // Debug verbeux
    $mail->Debugoutput = 'html';

    // CONFIG SMTP INFOMANIAK
    $mail->isSMTP();
    $mail->Host       = 'mail.infomaniak.com';
    $mail->Port       = 465;
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@crewup.ch';
    $mail->Password   = 'v#10_w!HX-6tLVw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->CharSet    = 'UTF-8';

    // EXPÉDITEUR / DESTINATAIRE
    $mail->setFrom('contact@crewup.ch', 'CrewUp SMTP Test');
    $mail->addAddress('contact@crewup.ch', 'CrewUp');

    // CONTENU
    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP Infomaniak';
    $mail->Body    = '<b>Si tu vois ce mail, c\'est bon.</b>';
    $mail->AltBody = 'Si tu vois ce mail, c’est bon.';

    echo "Envoi...\n";
    $mail->send();
    echo "\n*** MAIL ENVOYÉ ✔️ ***\n";

} catch (Exception $e) {
    echo "\nERREUR : " . $e->getMessage() . "\n";
    echo "Mailer Error : " . $mail->ErrorInfo . "\n";
}

echo "\n== END ==</pre>";
