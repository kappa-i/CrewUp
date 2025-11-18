<?php

echo "<pre>";
echo "Recherche PHPMailer...\n\n";

// ON SCANNE TOUT RECURSIVEMENT POUR TE RETROUVER LA LIBRAIRIE
$root = realpath(__DIR__ . "/..");

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

foreach ($rii as $file) {
    if (!$file->isDir()) {
        if (strpos($file->getPathname(), "PHPMailer") !== false) {
            echo $file->getPathname() . "\n";
        }
    }
}
