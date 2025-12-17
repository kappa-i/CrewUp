<?php

function loadTranslation($lang) {
    $lang_file = __DIR__ . "/translations/{$lang}.php";

    //utilisation de la langue standard si pas selectionné
    if (!file_exists($lang_file)) {
        $lang_file = __DIR__ . "/translations/fr.php";
    }

    return require $lang_file;
}