<?php
require_once __DIR__ . '/../src/utils/autoloader.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';

use Events\EventManager;


session_start();

$userId = $_SESSION['user_id'] ?? null;
$isAuthenticated = $userId !== null;

if ($isAuthenticated) {
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
}

const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
$t = loadTranslation($lang);

$eventManager = new EventManager();

$events = $eventManager->getEvents();

$joinedEventSet = [];
if ($isAuthenticated) {
    try {
        $database = new Database();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare('SELECT event_id FROM event_participants WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);

        $joinedEventSet = array_fill_keys($stmt->fetchAll(PDO::FETCH_COLUMN), true);
    } catch (\Throwable $e) {
        $joinedEventSet = [];
    }
}

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['nav_announcements']) ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body data-page="annonces">
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="art-header"><?= htmlspecialchars($t['announcements_title']) ?></h1>
        <ul class="account-menu">
            <?php if ($isAuthenticated): ?>
                <li class="create-annonce-li">
                    <a href="/account/create.php" class="create-annonce-btn">
                        <?= htmlspecialchars($t['create']) ?? 'Créer une annonce' ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div id="events" class="events-grid" style="margin: 40px 0 60px 0;">
            <?php if (empty($events)): ?>
                <p style="color: white; text-align: center; grid-column: 1 / -1; font-size: 1.2rem;">
                    <?= htmlspecialchars($t['no_events']) ?>
                    <a href="/account/create.php" style="color: #6b29ff; text-decoration: underline;">
                        <?= htmlspecialchars($t['create_first']) ?>
                    </a>
                </p>
            <?php else: ?>
                <?php foreach ($events as $event): ?>

                    <?php $isJoined = $isAuthenticated && isset($joinedEventSet[$event->getId()]);
                    $isPast = strtotime($event->getDate()) < time();
                    ?>

                    <article class="card<?= $isJoined ? ' is-joined' : '' ?><?= $isPast ? ' is-past' : '' ?>">

                        <?php if ($isJoined): ?>
                            <div class="joined-tag"><?= htmlspecialchars($t['joined_tag']) ?></div>
                        <?php endif; ?>

                        <?php if ($isPast): ?>
                            <div class="past-tag"><?= htmlspecialchars($t['past_tag'] ?? 'Terminé') ?></div>
                        <?php endif; ?>

                        <img class="card_img" src="<?= htmlspecialchars($event->getImageUrl() ?? 'https://media.istockphoto.com/id/533861572/fr/photo/football-au-coucher-du-soleil.jpg?s=612x612&w=0&k=20&c=6qnC4x39vZ2wEUkTh1e6QJsqIKfxW6jo15aSCPjsITk=') ?>" alt="<?= htmlspecialchars($event->getTitle()) ?>">

                        <div class="card_info">
                            <div class="card_left_col">
                                <h1 class="card_title"><?= htmlspecialchars($event->getTitle()) ?></h1>
                                <h3 class="card_place"><?= htmlspecialchars($event->getLocation()) ?></h3>
                                <h3 class="card_date"><?= htmlspecialchars($event->getFormattedDate()) ?></h3>
                            </div>

                            <div class="card_right_col">
                                <span class="card_ppl">
                                    <span class="card_filled"><?= $event->getFilled() ?></span>/<span class="card_capacity"><?= $event->getCapacity() ?></span>
                                </span>
                                <a class="card_link" href="event_detail.php?id=<?= $event->getId() ?>">›</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>