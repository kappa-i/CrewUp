<?php
require_once __DIR__ . '/../src/utils/autoloader.php';

use I18n\LanguageManager;

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
            <div class="cta-buttons">
                <a href="/annonces.php" class="orange-btn">
                    <?php echo $lang->t('home_cta'); ?>
                </a>
                <a href="/account/create.php" class="purple-btn">
                    <?php echo $lang->t('create_announcement_btn'); ?>
                </a>
            </div>
            <img src="assets/img/home_bg.png" alt="">
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>