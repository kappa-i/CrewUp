<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

use Events\EventManager;
use Events\Event;

session_start();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
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

if (isset($_GET["id"])) {
    $eventId = $_GET["id"];

    $event = $eventManager->getEventById($eventId);
    $actualFilled = $event->getFilled();


    if (!$event) {
        header("Location: /annonces.php");
        exit();
    }

    if ($event->getUserId() !== $userId && $role !== 'admin') {
        header("Location: /annonces.php");
        exit();
    }

    $id = $event->getId();
    $title = $event->getTitle();
    $sport = $event->getSport();
    $location = $event->getLocation();
    $date = $event->getDate();
    $time = $event->getTime();
    $capacity = $event->getCapacity();
    $filled = $event->getFilled();
    $description = $event->getDescription();
    $imageUrl = $event->getImageUrl();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"];

    $event = $eventManager->getEventById($id);
    if (!$event || $event->getUserId() !== $userId && $role !== 'admin') {
        $_SESSION['error_message'] = "Vous n'avez pas la permission de modifier cet événement.";
        header("Location: /annonces.php");
        exit();
    }

    $title = $_POST["title"];
    $sport = $_POST["sport"];
    $location = $_POST["location"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $capacity = $_POST["capacity"];
    $filled = $event->getFilled();
    $description = $_POST["description"] ?? null;
    $imageUrl = $_POST["image_url"] ?? null;

    $errors = [];

    if (empty($title) || strlen($title) < 3) {
        $errors[] = "Le titre doit contenir au moins 3 caractères.";
    }

    if (empty($sport)) {
        $errors[] = "Le sport est requis.";
    }

    if (empty($location)) {
        $errors[] = "Le lieu est requis.";
    }

    if (empty($date)) {
        $errors[] = "La date est requise.";
    } else {
        $today = date('Y-m-d');
        if ($date < $today) {
            $errors[] = "La date ne peut pas être antérieure à la date actuelle.";
        }
    }

    if (empty($time)) {
        $errors[] = "L'heure est requise.";
    }

    if ($capacity <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

   if ($capacity < $filled) {
        $errors[] = "La capacité ne peut pas être inférieure au nombre de participants déjà inscrits.";
    }

    if (empty($errors)) {
        try {

            $event = new Event(
                $id,
                $title,
                $sport,
                $location,
                $date,
                $time,
                (int)$capacity,
                (int)$filled,
                $description,
                $imageUrl,
                $userId
            );

            $success = $eventManager->updateEvent($event);

            if ($success) {
                header("Location: /annonces.php");
                exit();
            } else {
                $errors[] = "La mise à jour a échoué.";
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
} else {
    header("Location: /annonces.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['edit_announcement']) ?></title>
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
        <h1 class="hello"><?= htmlspecialchars($t['edit_announcement']) ?></h1>
        <p style="text-align: center;">
            <a href="dashboard.php" class="back-link"><?= htmlspecialchars($t['back_to_dashboard']) ?></a>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($errors)): ?>
                <div class="success-message">
                    <p><?= htmlspecialchars($t['success_updated']) ?></p>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <p><?= htmlspecialchars($t['error_form']) ?></p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>" />

            <label for="title"><?= htmlspecialchars($t['event_title_label']) ?> <span style="color: #ff6b6b;"><?= htmlspecialchars($t['required_field']) ?></span></label>
            <input type="text" id="title" name="title"
                value="<?= isset($title) ? htmlspecialchars($title) : '' ?>"
                required minlength="3"
                placeholder="<?= htmlspecialchars($t['placeholder_title']) ?>">

            <label for="sport"><?= htmlspecialchars($t['sport_label']) ?> <span style="color: #ff6b6b;"><?= htmlspecialchars($t['required_field']) ?></span></label>
            <select id="sport" name="sport" required>
                <option value=""><?= htmlspecialchars($t['choose_sport']) ?></option>
                <?php foreach ($sports as $key => $value): ?>
                    <option value="<?= $key ?>" <?= (isset($sport) && $sport == $key) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($value) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="location"><?= htmlspecialchars($t['location_label']) ?> <span style="color: #ff6b6b;"><?= htmlspecialchars($t['required_field']) ?></span></label>
            <input type="text" id="location" name="location"
                value="<?= isset($location) ? htmlspecialchars($location) : '' ?>"
                required
                placeholder="<?= htmlspecialchars($t['placeholder_location']) ?>">

            <label for="date"><?= htmlspecialchars($t['date_label']) ?> <span style="color: #ff6b6b;"><?= htmlspecialchars($t['required_field']) ?></span></label>
            <input type="date" id="date" name="date"
                value="<?= isset($date) ? htmlspecialchars($date) : '' ?>"
                required>

            <label for="time"><?= htmlspecialchars($t['time_label']) ?> <span style="color: #ff6b6b;"><?= htmlspecialchars($t['required_field']) ?></span></label>
            <input type="time" id="time" name="time"
                value="<?= isset($time) ? htmlspecialchars($time) : '' ?>"
                required>

            <label for="capacity"><?= htmlspecialchars($t['max_participants_label']) ?> <span style="color: #ff6b6b;"><?= htmlspecialchars($t['required_field']) ?></span></label>
           <input type="number" id="capacity" name="capacity" 
                   value="<?= isset($capacity) ? htmlspecialchars($capacity) : '' ?>" 
                   required min="<?= isset($filled) ? $filled : 2 ?>" 
                   placeholder="<?= htmlspecialchars($t['placeholder_capacity']) ?>">
            <?php if (isset($filled) && $filled > 0): ?>
                <small style="color: #ffa500; display: block; margin-top: 5px;">
                    ⚠️ <?= $filled ?> participant(s) déjà inscrit(s). La capacité ne peut pas être inférieure à ce nombre.
                </small>
            <?php endif; ?>

            <label for="description"><?= htmlspecialchars($t['description_label']) ?></label>
            <textarea id="description" name="description"
                placeholder="<?= htmlspecialchars($t['placeholder_description']) ?>"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>

            <label for="image_url"><?= htmlspecialchars($t['image_url_label']) ?></label>
            <input type="url" id="image_url" name="image_url"
                value="<?= isset($imageUrl) ? htmlspecialchars($imageUrl) : '' ?>"
                placeholder="<?= htmlspecialchars($t['placeholder_image']) ?>">

            <div class="form-buttons">
                <a href="deleteConfirm.php?id=<?= htmlspecialchars($id) ?>"
                    style="flex: 1; text-decoration: none;">
                    <button type="button" class="btn-reset" style="width: 100%;"><?= htmlspecialchars($t['btn_delete']) ?></button>
                </a>
                <button type="submit" class="btn-submit"><?= htmlspecialchars($t['btn_update']) ?></button>
                <button type="reset" class="btn-reset"><?= htmlspecialchars($t['btn_reset']) ?></button>
            </div>
        </form>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>