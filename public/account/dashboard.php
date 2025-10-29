<?php
require_once __DIR__ . '/../src/utils/autoloader.php';

use I18n\LanguageManager;

// Initialise le gestionnaire de langue
$lang = new LanguageManager();

// Reste de votre code...
?>
<!DOCTYPE html>
<h1><?php echo $lang->t('key_name'); ?></h1>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Dashboard</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main>
        <h1 class="hello">Mon compte</h1>
        <ul class="account-menu" style="margin-bottom: 400px;">
            <li><a href="create.php">• Créer</a></li>
            <li><a href="update.php">• Éditer</a></li>
            <li><a href="delete.php">• Supprimer</a></li>
        </ul>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>