<?php
//il faudrait aller dans "mon compte genre Arnold Bennet" et
//la on mettra une option bouton se deconnecter





// Démarrer la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
    header('Location: login.php');
    exit();
}

// Détruit la session
session_destroy();
?>
