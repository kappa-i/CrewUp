<?php



// Charge les classes automatiquement
spl_autoload_register(function ($class) {
   
    $relativePath = str_replace('\\', '/', $class);

    $file = __DIR__ . '/../Classes/' . $relativePath . '.php';
    
    if (!file_exists($file)) {
        $file = __DIR__ . '/../' . $relativePath . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});