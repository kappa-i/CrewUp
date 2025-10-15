<?php
// PSR-4 light pour ton projet
spl_autoload_register(function ($class) {
    // Normalise
    $class = ltrim($class, '\\');
    $base  = __DIR__ . '/../Classes/';

    // 1) Namespace "Events\*"  -> src/Classes/Events/...
    if (strncmp($class, 'Events\\', 7) === 0) {
        $relative = substr($class, 7); // enlève "Events\"
        $file = $base . 'Events/' . str_replace('\\', '/', $relative) . '.php';
    } else {
        // 2) Classes globales (sans namespace) -> src/Classes/<Class>.php
        //    ex: \Database -> src/Classes/Database.php
        $file = $base . str_replace('\\', '/', $class) . '.php';
    }

    if (is_file($file)) {
        require_once $file;
    }
});
