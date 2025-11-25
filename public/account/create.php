<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';
require_once __DIR__ . '/../../src/i18n/load-translation.php';

use Events\EventManager;
use Events\Event;

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

// Gère la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $title = $_POST["title"] ?? '';
    $sport = $_POST["sport"] ?? '';
    $location = $_POST["location"] ?? '';
    $date = $_POST["date"] ?? '';
    $time = $_POST["time"] ?? '';
    $capacity = $_POST["capacity"] ?? 0;
    $description = $_POST["description"] ?? null;
    $imageUrl = $_POST["image_url"] ?? null;

    // Validation des données
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

    // S'il n'y a pas d'erreurs, on ajoute l'événement
    if (empty($errors)) {
    try {
        // Création de l'objet Event
        $event = new Event(
            null,
            $title,
            $sport,
            $location,
            $date,
            $time,
            (int)$capacity,
            1,
            $description,
            $imageUrl,
            $userId
        );

        // Ajoute l'événement dans la base
        $eventId = $eventManager->addEvent($event);

        $db = new Database();
        $pdo = $db->getPdo();

        $stmt = $pdo->prepare(
            'INSERT INTO event_participants (event_id, user_id) VALUES (:event_id, :user_id)'
        );
        $stmt->execute([
            'event_id' => $eventId,
            'user_id' => $userId
        ]);

        header("Location: /annonces.php");
        exit();

    } catch (\Exception $e) {
        $errors[] = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?= htmlspecialchars($t['create_announcement']) ?></title>
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
        <h1 class="hello"><?= htmlspecialchars($t['create_announcement']) ?></h1>
        <p style="text-align: center;">
            <a href="dashboard.php" class="back-link"><?= htmlspecialchars($t['back_to_dashboard']) ?></a>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($errors)): ?>
                <div class="success-message">
                    <p><?= htmlspecialchars($t['success_created']) ?></p>
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

        <form action="create.php" method="POST">
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
                required min="2"
                placeholder="<?= htmlspecialchars($t['placeholder_capacity']) ?>">

            <label for="description"><?= htmlspecialchars($t['description_label']) ?></label>
            <textarea id="description" name="description"
                placeholder="<?= htmlspecialchars($t['placeholder_description']) ?>"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>

            <label for="image_url"><?= htmlspecialchars($t['image_url_label']) ?></label>
            <input type="url" id="image_url" name="image_url"
                value="<?= isset($imageUrl) ? htmlspecialchars($imageUrl) : '' ?>"
                placeholder="<?= htmlspecialchars($t['placeholder_image']) ?>">

            <div class="form-buttons">
                <button type="submit" class="btn-submit"><?= htmlspecialchars($t['btn_create']) ?></button>
                <button type="reset" class="btn-reset"><?= htmlspecialchars($t['btn_reset']) ?></button>
            </div>
        </form>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>