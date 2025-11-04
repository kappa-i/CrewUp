<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

// Charger les traductions si pas déjà fait
if (!isset($t)) {
    require_once __DIR__ . '/../../src/i18n/load-translation.php';
    
    if (!defined('COOKIE_NAME')) {
        define('COOKIE_NAME', 'lang');
        define('DEFAULT_LANG', 'fr');
    }
    
    $lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
    $t = loadTranslation($lang);
}
?>
<nav class="nav" aria-label="Navigation principale">
  <h1 class="logo"><a href="/">CrewUp</a></h1>

  <ul class="main-menu">
    <li><a href="/"><?= htmlspecialchars($t['nav_home']) ?></a></li>
    <li><a href="/annonces.php"><?= htmlspecialchars($t['nav_announcements']) ?></a></li>
  </ul>

  <a class="btn-inscription" href="/account/dashboard.php">
    <?= htmlspecialchars($t['nav_signup']) ?>
  </a>
</nav>