<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

use Events\EventManager;

// Constantes
const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// Déterminer la langue
$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
$t = loadTranslation($lang);

$eventManager = new EventManager();

// Vérification si l'ID de l'événement est passé dans l'URL
if (isset($_GET["id"])) {
    $eventId = $_GET["id"];
    
    // Récupération de l'événement
    $event = $eventManager->getEventById($eventId);
    
    // Si l'événement n'existe pas, redirection vers la page des annonces
    if (!$event) {
        header("Location: /annonces.php");
        exit();
    }
} else {
    // Si l'ID n'est pas passé dans l'URL, redirection vers la page des annonces
    header("Location: /annonces.php");
    exit();
}

// Gestion de la confirmation de suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
    $eventManager->removeEvent($eventId);
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
    <link rel="stylesheet" href="../assets/css/forms.css">
    <link rel="stylesheet" href="../assets/css/delete-confirm.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main class="form-container">
        <h1 class="hello"><?= htmlspecialchars($t['delete_announcement']) ?></h1>


        <div class="delete-confirm-container">
            <div class="delete-warning">
                <h2 class="delete-warning-title"> <?= htmlspecialchars($t['delete_warning_title']) ?></h2>
                <p class="delete-warning-message">
                    <?= htmlspecialchars($t['delete_warning_message']) ?>
                </p>
                <p class="delete-event-title">
                    "<?= htmlspecialchars($event->getTitle()) ?>"
                </p>
            </div>

            <form method="POST" action="deleteConfirm.php?id=<?= htmlspecialchars($eventId) ?>">
                <div class="form-buttons" style="margin-top: 0;">
                    <a href="update.php?id=<?= htmlspecialchars($eventId) ?>" style="flex: 1; text-decoration: none;">
                        <button type="button" class="btn-reset" style="width: 100%;">
                            <?= htmlspecialchars($t['btn_cancel']) ?>
                        </button>
                    </a>
                    <button type="submit" name="confirm_delete" class="btn-submit btn-delete-confirm">
                        <?= htmlspecialchars($t['btn_confirm_delete']) ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>