<?php
require_once __DIR__ . '/../src/i18n/load-translation.php';

// Constantes
const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// Déterminer la langue
$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
$t = loadTranslation($lang);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['fields_title']) ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body data-page="terrains">
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="art-header"><?= htmlspecialchars($t['fields_title']) ?></h1>
        <ul class="account-menu">
            <li><?= htmlspecialchars($t['filters']) ?></li>
            <li><a href="#"><?= htmlspecialchars($t['filter_sport']) ?></a></li>
            <li><a href="#"><?= htmlspecialchars($t['filter_location']) ?></a></li>
            <li><a href="#"><?= htmlspecialchars($t['filter_date']) ?></a></li>
        </ul>
        <div id="events" class="events-grid" style="margin: 40px 0 60px 0;">
        <!--PLACEHOLDER -> Migrer dans assets/cards.php-->
            <article class="card">
                <img class="card_img" src="https://www.anniviers.org/data/images/contents/3_Vie_locale/220912_TerrainfootdeVissoie.jpg">
                <div class="card_info">
                    <div class="card_left_col">
                        <h1 class="card_title">Terrain de Foot d'Ecublens</h1>
                        <h3 class="card_place">Centre sportif, Écublens</h3>
                    </div>
                    <div class="card_right_col">
                        <a class="card_link" href="#">›</a>
                    </div>
                </div>
            </article>
        <!---->
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>