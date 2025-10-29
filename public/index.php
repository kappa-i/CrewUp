<?php
require_once __DIR__ . '/../src/utils/autoloader.php';

use I18n\LanguageManager;

// Initialise le gestionnaire de langue
$lang = new LanguageManager();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?php echo $lang->t('nav_home'); ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body data-page="accueil">
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="hello"><?php echo $lang->t('home_title'); ?></h1>
        <p class="hello-sub"><?php echo $lang->t('home_subtitle'); ?></p>

        <div class="cta-section">
            <button class="orange-btn" type="button" onclick="window.location.href='/annonces.php'">
                <?php echo $lang->t('home_cta'); ?>
            </button>
            <img src="assets/img/home_bg.png" alt="basketball-player">
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>