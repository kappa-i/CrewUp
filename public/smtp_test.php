<?php
// Test SMTP Infomaniak isolé

require_once __DIR__ . '../src/utils/autoloader.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<pre>Début du test SMTP...\n";

$mail = new PHPMailer(true);

try {
    // DEBUG à fond
    $mail->SMTPDebug  = 3;
    $mail->Debugoutput = 'html';

    // CONFIG SMTP INFOMANIAK
    $mail->isSMTP();
    $mail->Host       = 'mail.infomaniak.com';
    $mail->Port       = 465;
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@crewup.ch';
    $mail->Password   = 'v#10_w!HX-6tLVw';
    $mail->CharSet    = 'UTF-8';
    $mail->Encoding   = 'base64';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL sur 465
    $mail->SMTPAutoTLS = false;

    // EXPÉDITEUR / DESTINATAIRE
    $mail->setFrom('contact@crewup.ch', 'CrewUp SMTP Test');
    $mail->addAddress('contact@crewup.ch', 'Test');

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP Infomaniak';
    $mail->Body    = '<b>Si tu vois ce mail, le SMTP marche.</b>';
    $mail->AltBody = 'Si tu vois ce mail, le SMTP marche.';

    echo "Envoi en cours...\n";
    $ok = $mail->send();
    echo "Résultat send(): " . ($ok ? 'OK' : 'FAIL') . "\n";

} catch (Exception $e) {
    echo "Exception PHPMailer: " . $e->getMessage() . "\n";
    echo "Mailer Error: " . $mail->ErrorInfo . "\n";
}

echo "\nFin du test.\n</pre>";
