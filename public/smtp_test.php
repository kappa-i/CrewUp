<?php

// Charger PHPMailer SANS autoloader
require __DIR__ . '/../src/PHPMailer/PHPMailer/PHPMailer.php';
require __DIR__ . '/../src/PHPMailer/PHPMailer/SMTP.php';
require __DIR__ . '/../src/PHPMailer/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<pre>TEST SMTP INFOMANIAK...\n";

$mail = new PHPMailer(true);

try {
    // Activer debug complet
    $mail->SMTPDebug  = 3;
    $mail->Debugoutput = 'html';

    // SMTP Infomaniak
    $mail->isSMTP();
    $mail->Host = 'mail.infomaniak.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@crewup.ch';
    $mail->Password = 'v#10_w!HX-6tLVw';

    // SSL
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->SMTPAutoTLS = false;

    // Destinataire / Expéditeur
    $mail->setFrom('contact@crewup.ch', 'CrewUp SMTP Test');
    $mail->addAddress('contact@crewup.ch');

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP Infomaniak';
    $mail->Body    = '<b>Si tu vois ce message, SMTP fonctionne.</b>';

    echo "\nEnvoi...\n";
    $mail->send();
    echo "\n*** MAIL ENVOYÉ AVEC SUCCÈS ***\n";

} catch (Exception $e) {
    echo "\nERREUR : " . $e->getMessage() . "\n";
    echo "PHPMailer Error : " . $mail->ErrorInfo . "\n";
}

echo "\nFIN DU TEST\n</pre>";
