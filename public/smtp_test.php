<?php

require __DIR__ . '/../src/PHPMailer/PHPMailer/PHPMailer.php';
require __DIR__ . '/../src/PHPMailer/PHPMailer/SMTP.php';
require __DIR__ . '/../src/PHPMailer/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "<pre>START SMTP TEST\n";

$mail = new PHPMailer(true);

try {

    $mail->SMTPDebug = 3;
    $mail->Debugoutput = 'html';

    $mail->isSMTP();
    $mail->Host = 'mail.infomaniak.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'contact@crewup.ch';
    $mail->Password = 'v#10_w!HX-6tLVw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->setFrom('contact@crewup.ch', 'CrewUp test');
    $mail->addAddress('contact@crewup.ch');

    $mail->Subject = 'SMTP Test OK';
    $mail->Body = 'Si tu lis ça, ça marche enfin.';
    $mail->AltBody = 'SMTP OK';

    $mail->send();

    echo ">>> MAIL ENVOYÉ ✔️";

} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage() . "\n";
    echo "Mailer Error : " . $mail->ErrorInfo;
}

echo "\nEND</pre>";
