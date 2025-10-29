<?php

// Charge les classes automatiquement
spl_autoload_register(function ($class) {
    // Exemple : Users\User devient Users/User
    // Exemple : I18n\LanguageManager devient I18n/LanguageManager
    $relativePath = str_replace('\\', '/', $class);

    // Construit le chemin complet du fichier
    $file = __DIR__ . '/../Classes/' . $relativePath . '.php';
    
    // Si le fichier n'existe pas dans Classes, cherche dans le dossier parent
    if (!file_exists($file)) {
        $file = __DIR__ . '/../' . $relativePath . '.php';
    }

    // Vérifie si le fichier existe avant de l'inclure
    if (file_exists($file)) {
        require_once $file;
    }
});