<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;
use Events\Event;
use I18n\LanguageManager;
$lang = new LanguageManager();

$eventManager = new EventManager();


// Liste des sports disponibles
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
            // On crée un nouvel objet Event
            // TODO: Remplacer user_id par l'ID de l'utilisateur connecté
            $event = new Event(
                null,
                $title,
                $sport,
                $location,
                $date,
                $time,
                (int)$capacity,
                0, // filled = 0 par défaut
                $description,
                $imageUrl,
                1 // TODO: Utiliser l'ID de l'utilisateur connecté
            );

            // On ajoute l'événement à la base de données
            $eventId = $eventManager->addEvent($event);

            // On redirige vers la page des annonces
            header("Location: /annonces.php");
            exit();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - <?php echo $lang->t('create_announcement'); ?></title>
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
        <h1 class="hello"><?php echo $lang->t('create_announcement'); ?></h1>
        <p style="text-align: center;">
$            <a href="dashboard.php" class="back-link"><?php echo $lang->t('back_to_dashboard'); ?></a>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($errors)): ?>
                <div class="success-message">
$                    <p><?php echo $lang->t('success_created'); ?></p>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <p><?php echo $lang->t('error_form'); ?></p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="create.php" method="POST">
            <label for="title"><?php echo $lang->t('event_title_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <input type="text" id="title" name="title" 
                   value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" 
                   required minlength="3" 
                   placeholder="<?php echo $lang->t('placeholder_title'); ?>">

            <label for="sport"><?php echo $lang->t('sport_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <select id="sport" name="sport" required>
                <option value=""><?php echo $lang->t('choose_sport'); ?></option>
                <?php foreach ($sports as $key => $value): ?>
                    <option value="<?= $key ?>" <?php echo (isset($sport) && $sport == $key) ? 'selected' : ''; ?>>
                        <?= $value ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="location"><?php echo $lang->t('location_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <input type="text" id="location" name="location" 
                   value="<?php echo isset($location) ? htmlspecialchars($location) : ''; ?>" 
                   required 
                   placeholder="<?php echo $lang->t('placeholder_location'); ?>">

            <label for="date"><?php echo $lang->t('date_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <input type="date" id="date" name="date" 
                   value="<?php echo isset($date) ? htmlspecialchars($date) : ''; ?>" 
                   required>

            <label for="time"><?php echo $lang->t('time_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <input type="time" id="time" name="time" 
                   value="<?php echo isset($time) ? htmlspecialchars($time) : ''; ?>" 
                   required>

            <label for="capacity"><?php echo $lang->t('max_participants_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <input type="number" id="capacity" name="capacity" 
                   value="<?php echo isset($capacity) ? htmlspecialchars($capacity) : ''; ?>" 
                   required min="2" 
                   placeholder="<?php echo $lang->t('placeholder_capacity'); ?>">

            <label for="description"><?php echo $lang->t('description_label'); ?></label>
            <textarea id="description" name="description" 
                      placeholder="<?php echo $lang->t('placeholder_description'); ?>"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>

            <label for="image_url"><?php echo $lang->t('image_url_label'); ?></label>
            <input type="url" id="image_url" name="image_url" 
                   value="<?php echo isset($imageUrl) ? htmlspecialchars($imageUrl) : ''; ?>" 
                   placeholder="<?php echo $lang->t('placeholder_image'); ?>">

            <div class="form-buttons">
                <button type="submit" class="btn-submit"><?php echo $lang->t('btn_create'); ?></button>
                <button type="reset" class="btn-reset"><?php echo $lang->t('btn_reset'); ?></button>
            </div>
        </form>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>