<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

// L'utilisateur n'est pas authentifié
if (!$userId) {
    // Redirige vers la page de connexion
    header('Location: /auth/login.php');
    exit();
}

// Sinon, récupère les autres informations de l'utilisateur
$username = $_SESSION['username'];
$role = $_SESSION['role'];

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
    <title>CrewUp - <?= htmlspecialchars($t['nav_dashboard']) ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main>

        <?php
        if ($role === 'admin') {
            $roleName = $t['role_admin'];
            $roleClass = 'role-admin';
        } else {
            $roleName = $t['role_user'];
            $roleClass = 'role-user';
        }
        ?>

        <h1 class="hello"><?= htmlspecialchars($t['dashboard_title']) ?></h1>

        <div class="role-container">
            <span class="role-badge <?= htmlspecialchars($roleClass) ?>">
                <?= htmlspecialchars($roleName) ?>
            </span>
        </div>

        <ul class="account-menu" style="margin-bottom: 400px;">
            <li><a href="create.php"><?= htmlspecialchars($t['dashboard_create']) ?></a></li>
            <li><a href="update.php"><?= htmlspecialchars($t['dashboard_edit']) ?></a></li>
            <li><a href="delete.php"><?= htmlspecialchars($t['dashboard_delete']) ?></a></li>
            <li><a href="/auth/logout.php" style="color: #ff6b6b;"><?= htmlspecialchars($t['btn_logout']) ?></a></li>
        </ul>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>