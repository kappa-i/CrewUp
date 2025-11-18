<?php

require_once __DIR__ . '/autoloader.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../config/mail.ini';

// Lecture du fichier de configuration
$config = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

if (!$config) {
    throw new Exception(
        "Erreur lors de la lecture du fichier de configuration : " . MAIL_CONFIGURATION_FILE
    );
}

// Extraction des paramètres de configuration
$host           = $config['host'];
$port           = filter_var($config['port'], FILTER_VALIDATE_INT);
$authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
$username       = $config['username'];
$password       = $config['password'];
$from_email     = $config['from_email'];
$from_name      = $config['from_name'];

$mail = new PHPMailer(true);

try {
    // Configuration SMTP
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->Port       = $port;
    $mail->SMTPAuth   = $authentication;
    $mail->Username   = $username;
    $mail->Password   = $password;
    $mail->CharSet    = "UTF-8";
    $mail->Encoding   = "base64";

    // Expéditeur et destinataire
    $mail->setFrom($from_email, $from_name);

    // $email et $username viennent de register.php
    $mail->addAddress($email, $username);

    // Contenu du mail
    $mail->isHTML(true);
    $mail->Subject = 'Bienvenue sur CrewUp';

    $mail->Body = "
    <p>Merci d'avoir créé un compte sur CrewUp.</p>
    <p>Vous pouvez vous connecter en cliquant sur le lien suivant :</p>
    <p><a href=\"https://crewup.ch/auth/login.php\">https://crewup.ch/auth/login.php</a></p>
";

    $mail->AltBody = "Merci d'avoir créé un compte sur CrewUp.\n"
        . "Vous pouvez vous connecter ici : https://crewup.ch/auth/login.php";

    // Envoi
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
