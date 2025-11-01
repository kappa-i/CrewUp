<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use I18n\LanguageManager;

// Initialise le gestionnaire de langue
$lang = new LanguageManager();

?>
<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?php echo $lang->t('nav_dashboard'); ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main>
        <h1 class="hello"><?php echo $lang->t('dashboard_title'); ?></h1>
        <ul class="account-menu" style="margin-bottom: 400px;">
            <li><a href="create.php"><?php echo $lang->t('dashboard_create'); ?></a></li>
            <li><a href="update.php"><?php echo $lang->t('dashboard_edit'); ?></a></li>
            <li><a href="delete.php"><?php echo $lang->t('dashboard_delete'); ?></a></li>
        </ul>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>