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

// Liste des sports disponibles (traduits)
$sports = [
    'football' => $t['sport_football'],
    'basketball' => $t['sport_basketball'],
    'volleyball' => $t['sport_volleyball'],
    'tennis' => $t['sport_tennis'],
    'running' => $t['sport_running'],
    'cycling' => $t['sport_cycling'],
    'swimming' => $t['sport_swimming'],
    'other' => $t['sport_other']
];

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
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main class="form-container">
        <h1 class="hello"><?= htmlspecialchars($t['delete_announcement']) ?></h1>
        <p style="text-align: center;">
            <a href="update.php?id=<?= htmlspecialchars($eventId) ?>" class="back-link"><?= htmlspecialchars($t['back']) ?></a>
        </p>

        <div style="background: rgba(220, 53, 69, 0.15); border: 2px solid rgba(220, 53, 69, 0.5); border-radius: 24px; padding: 40px; margin-top: 30px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="color: #ff6b6b; font-size: 1.5rem; margin-bottom: 10px;">⚠️ <?= htmlspecialchars($t['delete_warning_title']) ?></h2>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.1rem;">
                    <?= htmlspecialchars($t['delete_warning_message']) ?>
                </p>
            </div>

            <!-- Affichage des détails de l'événement -->
            <div style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 16px; padding: 30px; margin-bottom: 30px;">
                <h3 style="color: #fff; margin-top: 0; margin-bottom: 20px; font-size: 1.3rem;">
                    📋 <?= htmlspecialchars($t['event_details']) ?>
                </h3>
                
                <div style="display: grid; gap: 15px;">
                    <div>
                        <strong style="color: rgba(255, 255, 255, 0.6);"><?= htmlspecialchars($t['event_title_label']) ?>:</strong>
                        <span style="color: #fff; margin-left: 10px; font-size: 1.1rem;"><?= htmlspecialchars($event->getTitle()) ?></span>
                    </div>
                    
                    <div>
                        <strong style="color: rgba(255, 255, 255, 0.6);"><?= htmlspecialchars($t['sport_label']) ?>:</strong>
                        <span style="color: #fff; margin-left: 10px;"><?= htmlspecialchars($sports[$event->getSport()] ?? $event->getSport()) ?></span>
                    </div>
                    
                    <div>
                        <strong style="color: rgba(255, 255, 255, 0.6);"><?= htmlspecialchars($t['location_label']) ?>:</strong>
                        <span style="color: #fff; margin-left: 10px;"><?= htmlspecialchars($event->getLocation()) ?></span>
                    </div>
                    
                    <div>
                        <strong style="color: rgba(255, 255, 255, 0.6);"><?= htmlspecialchars($t['date_label']) ?>:</strong>
                        <span style="color: #fff; margin-left: 10px;"><?= htmlspecialchars($event->getFormattedDate()) ?></span>
                    </div>
                    
                    <div>
                        <strong style="color: rgba(255, 255, 255, 0.6);"><?= htmlspecialchars($t['time_label']) ?>:</strong>
                        <span style="color: #fff; margin-left: 10px;"><?= htmlspecialchars(substr($event->getTime(), 0, 5)) ?></span>
                    </div>
                    
                    <div>
                        <strong style="color: rgba(255, 255, 255, 0.6);"><?= htmlspecialchars($t['participants']) ?>:</strong>
                        <span style="color: #fff; margin-left: 10px;"><?= $event->getFilled() ?> / <?= $event->getCapacity() ?></span>
                    </div>
                </div>
            </div>

            <!-- Formulaire de confirmation -->
            <form method="POST" action="deleteConfirm.php?id=<?= htmlspecialchars($eventId) ?>">
                <div class="form-buttons" style="margin-top: 0;">
                    <a href="update.php?id=<?= htmlspecialchars($eventId) ?>" 
                       style="flex: 1; text-decoration: none;">
                        <button type="button" class="btn-reset" style="width: 100%;">
                            <?= htmlspecialchars($t['btn_cancel']) ?>
                        </button>
                    </a>
                    <button type="submit" name="confirm_delete" class="btn-submit" 
                            style="background: #dc3545; flex: 1;">
                        <?= htmlspecialchars($t['btn_confirm_delete']) ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>