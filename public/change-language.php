<?php
const COOKIE_NAME = 'lang';
const COOKIE_LIFETIME = 2592000; //equivalent a 30 jours normalement
const DEFAULT_LANG = 'fr';

//pour changer sa langue
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    $lang = $_GET['lang'];
    setcookie(COOKIE_NAME, $lang, time() + COOKIE_LIFETIME, '/');
}

//redirige vers la page accueil
$referer = $_SERVER['HTTP_REFERER'] ?? '/';
header("Location: $referer");
exit;