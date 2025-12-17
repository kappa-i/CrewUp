<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

use Events\EventManager;

session_start();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    //renvoi a la page de connexion
    header('Location: /auth/login.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
$t = loadTranslation($lang);

$eventManager = new EventManager();

if (isset($_GET["id"])) {
    $eventId = $_GET["id"];

    $event = $eventManager->getEventById($eventId);

    if (!$event) {
        header("Location: /annonces.php");
        exit();
    }

    if ($event->getUserId() !== $userId && $role !== 'admin') {
        $_SESSION['error_message'] = "Vous n'avez pas la permission de supprimer cet événement.";
        header("Location: /annonces.php");
        exit();
    
    }
    
} else {
    header("Location: /annonces.php");
    exit();
}

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