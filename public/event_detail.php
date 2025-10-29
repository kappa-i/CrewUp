<?php
require_once __DIR__ . '/../src/utils/autoloader.php';
use I18n\LanguageManager;
use Events\EventManager;

$eventManager = new EventManager();
$lang = new LanguageManager();
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

// Liste des sports pour affichage
$sports = [
    'football' => 'Football',
    'basketball' => 'Basketball',
    'volleyball' => 'Volleyball',
    'tennis' => 'Tennis',
    'running' => 'Course à pied',
    'cycling' => 'Cyclisme',
    'swimming' => 'Natation',
    'other' => 'Autre'
];
?>

<!DOCTYPE html>
<h1><?php echo $lang->t('key_name'); ?></h1>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?php echo htmlspecialchars($event->getTitle()); ?></title>
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
            <a href="/annonces.php" class="back-link">← Retour aux annonces</a>
        </p>

        <div class="event-header">
            <img class="event-image" 
                 src="<?php echo htmlspecialchars($event->getImageUrl() ?? 'https://media.istockphoto.com/id/533861572/fr/photo/football-au-coucher-du-soleil.jpg?s=612x612&w=0&k=20&c=6qnC4x39vZ2wEUkTh1e6QJsqIKfxW6jo15aSCPjsITk='); ?>" 
                 alt="<?php echo htmlspecialchars($event->getTitle()); ?>">
            
            <div class="event-content">
                <h1 class="event-title"><?php echo htmlspecialchars($event->getTitle()); ?></h1>
                
                <span class="event-sport">
                    <?php echo htmlspecialchars($sports[$event->getSport()] ?? $event->getSport()); ?>
                </span>

                <?php if ($event->isAvailable()): ?>
                    <span class="availability-badge badge-available">
                        ✓ Places disponibles
                    </span>
                <?php else: ?>
                    <span class="availability-badge badge-full">
                        ✗ Complet
                    </span>
                <?php endif; ?>

                <div class="event-info-grid">
                    <div class="info-item">
                        <div class="info-label">📍 Lieu</div>
                        <div class="info-value"><?php echo htmlspecialchars($event->getLocation()); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">📅 Date</div>
                        <div class="info-value"><?php echo htmlspecialchars($event->getFormattedDate()); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">🕐 Heure</div>
                        <div class="info-value"><?php echo htmlspecialchars(substr($event->getTime(), 0, 5)); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">👥 Participants</div>
                        <div class="info-value">
                            <?php echo $event->getFilled(); ?> / <?php echo $event->getCapacity(); ?>
                        </div>
                        <div class="participants-bar">
                            <div class="participants-fill" 
                                 style="width: <?php echo ($event->getFilled() / $event->getCapacity()) * 100; ?>%">
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($event->getDescription()): ?>
                    <div class="event-description">
                        <h3>📝 Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($event->getDescription())); ?></p>
                    </div>
                <?php endif; ?>

                <div class="event-actions">
                    <a href="/annonces.php" class="btn-action btn-back">
                        ← Retour
                    </a>
                    
                    <?php if ($event->isAvailable()): ?>
                        <button class="btn-action btn-join">
                            Rejoindre l'événement
                        </button>
                    <?php else: ?>
                        <button class="btn-action btn-join" disabled>
                            Événement complet
                        </button>
                    <?php endif; ?>

                    <a href="/account/update.php?id=<?php echo $event->getId(); ?>" class="btn-action btn-edit">
                        ✏️ Modifier
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>