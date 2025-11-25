<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

use Events\EventManager;

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: /auth/login.php');
    exit();
}

// Récupération des infos utilisateur
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Constantes
const COOKIE_NAME = 'lang';
const DEFAULT_LANG = 'fr';

// Déterminer la langue
$lang = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANG;
$t = loadTranslation($lang);

$eventManager = new EventManager();
$events = $eventManager->getEventsByUserId($userId);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['nav_dashboard']) ?></title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/account.css">
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

        <div class="role-container">
            <span class="role-badge <?= htmlspecialchars($roleClass) ?>">
                <?= htmlspecialchars($roleName) ?>
            </span>
        </div>

        <h1 class="hello"><?= htmlspecialchars($t['dashboard_title']) ?></h1>

        <ul class="account-menu" style="margin-bottom: 40px;">
            <li><a href="create.php"><?= htmlspecialchars($t['dashboard_create']) ?></a></li>
            <li><a href="update.php"><?= htmlspecialchars($t['dashboard_edit']) ?></a></li>
            <li><a href="delete.php"><?= htmlspecialchars($t['dashboard_delete']) ?></a></li>
            <li><a href="/auth/logout.php" style="color: #ff6b6b;"><?= htmlspecialchars($t['btn_logout']) ?></a></li>
        </ul>

        <div id="eventsD" class="events-grid" style="margin: 40px 0 60px 0;">
            <?php if (empty($events)): ?>
                <p style="color: white; text-align: center; grid-column: 1 / -1; font-size: 1.2rem;">
                    <?= htmlspecialchars($t['no_events']) ?>
                    <a href="/account/create.php" style="color: #6b29ff; text-decoration: underline;">
                        <?= htmlspecialchars($t['create_first']) ?>
                    </a>
                </p>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <article class="card">
                        <img class="card_img"
                            src="<?= htmlspecialchars($event->getImageUrl() ?? 'https://media.istockphoto.com/id/533861572/fr/photo/football-au-coucher-du-soleil.jpg?s=612x612&w=0&k=20&c=6qnC4x39vZ2wEUkTh1e6QJsqIKfxW6jo15aSCPjsITk=') ?>"
                            alt="<?= htmlspecialchars($event->getTitle()) ?>">

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

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>