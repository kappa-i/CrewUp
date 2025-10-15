<?php

// Charge les classes automatiquement
spl_autoload_register(function ($class) {
    // Exemple : Users\User devient Users/User
    $relativePath = str_replace('\\', '/', $class);

    // Construit le chemin complet du fichier
    // __DIR__ est 'src/utils', donc '../Classes' pointe vers 'src/Classes'
    $file = __DIR__ . '/../Classes/' . $relativePath . '.php';

    // Vérifie si le fichier existe avant de l'inclure
    if (file_exists($file)) {
        // Inclut le fichier de classe
        require_once $file;
    }
});