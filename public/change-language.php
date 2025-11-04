<?php
// Constantes
const COOKIE_NAME = 'lang';
const COOKIE_LIFETIME = 2592000; // 30 jours
const DEFAULT_LANG = 'fr';

// Changer la langue préférée
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $lang = $_GET['lang'];
    setcookie(COOKIE_NAME, $lang, time() + COOKIE_LIFETIME, '/');
}

// Rediriger vers la page précédente ou l'accueil
$referer = $_SERVER['HTTP_REFERER'] ?? '/';
header("Location: $referer");
exit;