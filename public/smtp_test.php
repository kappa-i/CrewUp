<?php

// Charger PHPMailer depuis TA structure
require __DIR__ . '/../src/PHPMailer/PHPMailer.php';
require __DIR__ . '/../src/PHPMailer/SMTP.php';
require __DIR__ . '/../src/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<pre>TEST SMTP…\n";

$mail = new PHPMailer(true);

try {
    // Debug max
    $mail->SMTPDebug = 3;
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

    $mail->setFrom('contact@crewup.ch', 'CrewUp SMTP Test');
    $mail->addAddress('contact@crewup.ch');

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP';
    $mail->Body = '<b>Test OK si tu reçois ça.</b>';

    echo "Envoi...\n";
    $mail->send();
    echo "*** MAIL ENVOYÉ ***";

} catch (Exception $e) {
    echo "ERREUR : " . $e->getMessage() . "\n";
    echo "Mailer Error : " . $mail->ErrorInfo . "\n";
}

echo "\nFin du test</pre>";
