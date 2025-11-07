<?php
require_once __DIR__ . '/../src/utils/autoloader.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;
$isAuthenticated = $userId !== null;

// L'utilisateur est authentifié
if ($isAuthenticated) {
    // Récupère les autres informations de l'utilisateur
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
}

// Constantes
const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// Déterminer la langue préférée
$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;

// Charger les traductions
$t = loadTranslation($lang);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['nav_home']) ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body data-page="accueil">
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="hello"><?= htmlspecialchars($t['home_title']) ?></h1>
        <p class="hello-sub"><?= htmlspecialchars($t['home_subtitle']) ?></p>

        <div class="cta-section">
            <div class="cta-buttons">
                <a href="/annonces.php" class="orange-btn">
                    <?= htmlspecialchars($t['home_cta']) ?>
                </a>
                <a href="/account/create.php" class="purple-btn">
                    <?= htmlspecialchars($t['create_announcement_btn']) ?>
                </a>
            </div>
            <img src="assets/img/home_bg.png" alt="">
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>