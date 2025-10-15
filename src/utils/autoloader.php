<?php
spl_autoload_register(function ($class) {
    // On charge seulement le namespace 'Classes\'
    $prefix  = 'Classes\\';
    $baseDir = __DIR__ . '/../Classes/'; // => /src/Classes

    $len = strlen($prefix);
    if (strncmp($class, $prefix, $len) !== 0) return;

    $relative = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

    // Fallback si ton entity s'appelle 'Events.php' (classe 'Events') mais on l’alias en 'Event'
    if (!is_file($file) && str_ends_with($file, '/Event.php')) {
        $file = substr($file, 0, -9) . 'Events.php'; // Event.php -> Events.php
    }

    if (is_file($file)) require $file;
});
