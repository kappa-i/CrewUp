<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

use Events\EventManager;

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

// Création d'une instance de EventManager
$eventManager = new EventManager();

// Vérification si l'ID de l'événement est passé dans l'URL
if (isset($_GET["id"])) {
    // Récupération de l'ID de l'événement de la superglobale `$_GET`
    $eventId = $_GET["id"];

    $event = $eventManager->getEventById($eventId);

    if (!$event) {
    header("Location: /annonces.php");
    exit();
    }

    if ($event->getUserId() !== $userId && $role !== 'admin') {
    header("Location: /annonces.php");
    exit();
    }
    
    // Suppression de l'événement correspondant à l'ID
    $eventManager->removeEvent($eventId);
    
    header("Location: /annonces.php");
    exit();
} else {
    // Si l'ID n'est pas passé dans l'URL, redirection vers la page des annonces
    header("Location: /annonces.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['delete_announcement']) ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main>
        <h1 class="hello"><?= htmlspecialchars($t['delete_announcement']) ?></h1>
        <ul class="account-menu" style="margin-bottom: 400px;">
            <li><a href="dashboard.php"><?= htmlspecialchars($t['back']) ?></a></li>
            <li><a href="create.php"><?= htmlspecialchars($t['dashboard_create']) ?></a></li>
            <li><a href="update.php"><?= htmlspecialchars($t['dashboard_edit']) ?></a></li>
        </ul>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>