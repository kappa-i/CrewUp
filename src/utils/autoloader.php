<?php
// Autoloader simple & robuste
spl_autoload_register(function ($class) {
    // Normalise
    $class = ltrim($class, '\\');
    $base  = __DIR__ . '/../Classes/';

    // 1) PSR-4 standard: src/Classes/{Namespace}/{Class}.php
    $path = $base . str_replace('\\', '/', $class) . '.php';

    // 2) Fallback pour classes globales (ex: Database)
    if (!is_file($path) && strpos($class, '\\') === false) {
        $path = $base . $class . '.php';
    }

    // 3) Classmap de secours pour les sensibles
    static $classmap = [
        'Events\Event'                 => 'Events/Event.php',
        'Events\EventInterface'        => 'Events/EventInterface.php',
        'Events\EventManager'          => 'Events/EventManager.php',
        'Events\EventManagerInterface' => 'Events/EventManagerInterface.php',
        'Database'                     => 'Database.php',
        'DatabaseInterface'            => 'DatabaseInterface.php',
    ];
    if (!is_file($path) && isset($classmap[$class])) {
        $path = $base . $classmap[$class];
    }

    // 4) Charge si trouvé
    if (is_file($path)) {
        require_once $path;
    }
});

