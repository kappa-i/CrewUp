<?php
require_once __DIR__ . '/../src/utils/autoloader.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';

use Events\EventManager;

// Constantes
const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// ===== AUTHENTIFICATION =====
// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;
$isAuthenticated = $userId !== null;
$role = $_SESSION['role'];

// L'utilisateur est authentifié
if ($isAuthenticated) {
    // Récupère les autres informations de l'utilisateur
    $username = $_SESSION['username'];
}
// ===== FIN AUTHENTIFICATION =====

// Déterminer la langue
$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
$t = loadTranslation($lang);

$eventManager = new EventManager();

// On vérifie si l'ID de l'événement est passé dans l'URL
if (!isset($_GET["id"])) {
    header("Location: /annonces.php");
    exit();
}

$eventId = $_GET["id"];
$event = $eventManager->getEventById($eventId);

// Si l'événement n'existe pas, on redirige vers la page des annonces
if (!$event) {
    header("Location: /annonces.php");
    exit();
}

$isParticipating = false;
if ($isAuthenticated) {
    $database = new Database();
    $pdo = $database->getPdo();
    $stmt = $pdo->prepare('SELECT * FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
    $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
    $isParticipating = (bool)$stmt->fetch();
}

// Liste des sports pour affichage (traduits)
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
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($event->getTitle()) ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/event-detail.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main class="event-detail-container">
        <p style="text-align: center; margin-bottom: 20px;">
            <a href="/annonces.php" class="back-link"><?= htmlspecialchars($t['back_to_announcements']) ?></a>
        </p>

        <div class="event-header">
            <img class="event-image"
                src="<?= htmlspecialchars($event->getImageUrl() ?? 'https://media.istockphoto.com/id/533861572/fr/photo/football-au-coucher-du-soleil.jpg?s=612x612&w=0&k=20&c=6qnC4x39vZ2wEUkTh1e6QJsqIKfxW6jo15aSCPjsITk=') ?>"
                alt="<?= htmlspecialchars($event->getTitle()) ?>">

            <div class="event-content">
                <h1 class="event-title"><?= htmlspecialchars($event->getTitle()) ?></h1>

                <span class="event-sport">
                    <?= htmlspecialchars($sports[$event->getSport()] ?? $event->getSport()) ?>
                </span>

                <?php if ($event->isAvailable()): ?>
                    <span class="availability-badge badge-available">
                        <?= htmlspecialchars($t['places_available']) ?>
                    </span>
                <?php else: ?>
                    <span class="availability-badge badge-full">
                        <?= htmlspecialchars($t['event_full']) ?>
                    </span>
                <?php endif; ?>

                <div class="event-info-grid">
                    <div class="info-item">
                        <div class="info-label">📍 <?= htmlspecialchars($t['location_label']) ?></div>
                        <div class="info-value"><?= htmlspecialchars($event->getLocation()) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">📅 <?= htmlspecialchars($t['date_label']) ?></div>
                        <div class="info-value"><?= htmlspecialchars($event->getFormattedDate()) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">🕐 <?= htmlspecialchars($t['time_label']) ?></div>
                        <div class="info-value"><?= htmlspecialchars(substr($event->getTime(), 0, 5)) ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">👥 <?= htmlspecialchars($t['participants']) ?></div>
                        <div class="info-value">
                            <?= $event->getFilled() ?> / <?= $event->getCapacity() ?>
                        </div>
                        <div class="participants-bar">
                            <div class="participants-fill"
                                style="width: <?= ($event->getFilled() / $event->getCapacity()) * 100 ?>%">
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($event->getDescription()): ?>
                    <div class="event-description">
                        <h3>📝 <?= htmlspecialchars($t['description_label']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($event->getDescription())) ?></p>
                    </div>
                <?php endif; ?>

                <div class="event-actions">
                    <a href="/annonces.php" class="btn-action btn-back">
                        <?= htmlspecialchars($t['back']) ?>
                    </a>

                    <?php if ($isAuthenticated): ?>
                        <?php if ($isParticipating): ?>
                            <a href="/api/join_event.php?event_id=<?= $event->getId() ?>&action=leave" class="btn-action btn-leave">
                                ❌ <?= htmlspecialchars($t['leave_event'] ?? 'Quitter l\'événement') ?>
                            </a>
                        <?php else: ?>
                            <?php if ($event->isAvailable()): ?>
                                <a href="/api/join_event.php?event_id=<?= $event->getId() ?>&action=join" class="btn-action btn-join">
                                    <?= htmlspecialchars($t['join_event']) ?>
                                </a>
                            <?php else: ?>
                                <button class="btn-action btn-join" disabled>
                                    <?= htmlspecialchars($t['event_full_btn']) ?>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($isAuthenticated && $event->getUserId() === $userId || $role === 'admin'): ?>
                            <a href="/account/update.php?id=<?= $event->getId() ?>" class="btn-action btn-edit">
                                <?= htmlspecialchars($t['modify']) ?>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn-action btn-join" disabled title="Connexion requise">
                            🔒 <?= htmlspecialchars($t['join_event']) ?>
                        </button>
                        <button class="btn-action btn-edit" disabled title="Connexion requise" style="background: rgba(255, 255, 255, 0.05); cursor: not-allowed;">
                            🔒 <?= htmlspecialchars($t['modify']) ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>