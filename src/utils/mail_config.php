<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../Classes/PHPMailer/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../Classes/PHPMailer/PHPMailer/SMTP.php';
require_once __DIR__ . '/../Classes/PHPMailer/PHPMailer/Exception.php';

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../config/mail.ini';

function sendWelcomeEmail(string $email, string $username): void
{
    $config = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

    if (!$config) {
        throw new \Exception(
            "Erreur lors de la lecture du fichier de configuration : " . MAIL_CONFIGURATION_FILE
        );
    }

    $host           = $config['host'];
    $port           = filter_var($config['port'], FILTER_VALIDATE_INT);
    $authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
    $smtpUser       = $config['username'];   // login SMTP
    $smtpPass       = $config['password'];
    $from_email     = $config['from_email'];
    $from_name      = $config['from_name'];

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = $authentication;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->CharSet    = "UTF-8";
        $mail->Encoding   = "base64";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAutoTLS = false;

        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Bienvenue sur CrewUp';
        $mail->Body = '
            <p>Merci d\'avoir créé un compte sur CrewUp.</p>
            <p>Vous pouvez vous connecter en cliquant sur le lien suivant :</p>
            <p><a href="https://crewup.ch/auth/login.php">https://crewup.ch/auth/login.php</a></p>
        ';
        $mail->AltBody = "Merci d'avoir créé un compte sur CrewUp.\n"
            . "Vous pouvez vous connecter ici : https://crewup.ch/auth/login.php";

        $mail->send();

    } catch (Exception $e) {
        
    }
}
