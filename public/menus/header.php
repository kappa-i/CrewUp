<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use I18n\LanguageManager;

if (!isset($lang)) {
    $lang = new LanguageManager();
}
?>
<nav class="nav" aria-label="Navigation principale">
  <h1 class="logo"><a href="/">CrewUp</a></h1>

  <ul class="main-menu">
    <li><a href="/"><?php echo $lang->t('nav_home'); ?></a></li>
    <li><a href="/annonces.php"><?php echo $lang->t('nav_announcements'); ?></a></li>
    <!-- <li><a href="/terrains.php"><?php echo $lang->t('nav_fields'); ?></a></li> -->
  </ul>

  <a class="btn-inscription" href="/account/dashboard.php">
    <?php echo $lang->t('nav_signup'); ?>
  </a>
</nav>