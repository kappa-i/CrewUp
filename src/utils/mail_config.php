<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// On charge PHPMailer comme dans smtp_test.php, mais depuis /src/utils
require_once __DIR__ . '/../Classes/PHPMailer/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../Classes/PHPMailer/PHPMailer/SMTP.php';
require_once __DIR__ . '/../Classes/PHPMailer/PHPMailer/Exception.php';

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../config/mail.ini';

/**
 * Envoie l'email de bienvenue CrewUp au nouvel utilisateur.
 */
function sendWelcomeEmail(string $email, string $username): void
{
    // Lecture de la config SMTP
    $config = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

    if (!$config) {
        throw new \Exception(
            "Erreur lors de la lecture du fichier de configuration : " . MAIL_CONFIGURATION_FILE
        );
    }

    // Extraction
    $host           = $config['host'];
    $port           = filter_var($config['port'], FILTER_VALIDATE_INT);
    $authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
    $smtpUser       = $config['username'];   // login SMTP
    $smtpPass       = $config['password'];
    $from_email     = $config['from_email'];
    $from_name      = $config['from_name'];

    $mail = new PHPMailer(true);

    try {
        // Même config que dans smtp_test.php
        // $mail->SMTPDebug  = 0;           // laisse à 0 en prod
        // $mail->Debugoutput = 'html';

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

        // Expéditeur et destinataire
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($email, $username);

        // Contenu
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
        // pas d'echo ici, on laisse register.php afficher son message

    } catch (Exception $e) {
        // Si tu veux loguer :
        // error_log('Mailer Error: ' . $mail->ErrorInfo);
        // mais on ne casse pas l’inscription si le mail foire
    }
}
