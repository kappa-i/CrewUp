<?php

require_once __DIR__ . '/../src/utils/autoloader.php';

use I18n\LanguageManager;

// Vérifie si une langue est passée en paramètre
if (isset($_GET['lang'])) {
    $languageManager = new LanguageManager();
    
    // Tente de changer la langue
    if ($languageManager->setLanguage($_GET['lang'])) {
        // Redirige vers la page précédente ou l'accueil
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $redirect");
        exit();
    }
}

// Si erreur, redirige vers l'accueil
header("Location: /");
exit();