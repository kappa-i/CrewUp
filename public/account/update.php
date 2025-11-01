<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;
use Events\Event;
use I18n\LanguageManager;

$eventManager = new EventManager();
$lang = new LanguageManager();

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

// On vérifie si l'ID de l'événement est passé dans l'URL
if (isset($_GET["id"])) {
    // On récupère l'ID de l'événement de la superglobale `$_GET`
    $eventId = $_GET["id"];

    // On récupère l'événement correspondant à l'ID
    $event = $eventManager->getEventById($eventId);

    // Si l'événement n'existe pas, on redirige vers la page des annonces
    if (!$event) {
        header("Location: /annonces.php");
        exit();
    } else {
        // Sinon, on initialise les variables
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
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gère la soumission du formulaire

    // Récupération des données du formulaire
    $id = $_POST["id"];
    $title = $_POST["title"];
    $sport = $_POST["sport"];
    $location = $_POST["location"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $capacity = $_POST["capacity"];
    $filled = $_POST["filled"];
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

    if ($filled < 0) {
        $errors[] = "Le nombre de participants doit être positif ou nul.";
    }

    if ($filled > $capacity) {
        $errors[] = "Le nombre de participants ne peut pas dépasser la capacité.";
    }

    // S'il n'y a pas d'erreurs, on met à jour l'événement
    if (empty($errors)) {
        try {
            // On crée un objet Event avec les nouvelles données
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
                1 // TODO: Utiliser l'ID de l'utilisateur connecté
            );

            // On met à jour l'événement dans la base de données
            $success = $eventManager->updateEvent($event);

            // On vérifie si la mise à jour a réussi
            if ($success) {
                // On redirige vers la page des annonces
                header("Location: /annonces.php");
                exit();
            } else {
                // Si la mise à jour a échoué, on affiche un message d'erreur
                $errors[] = "La mise à jour a échoué.";
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
} else {
    // Si l'ID n'est pas passé dans l'URL, on redirige vers la page des annonces
    header("Location: /annonces.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CHANGEMENT: Traduire le titre -->
    <title>CrewUp - <?php echo $lang->t('edit_announcement'); ?></title>
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
        <!-- CHANGEMENT: Traduire le titre -->
        <h1 class="hello"><?php echo $lang->t('edit_announcement'); ?></h1>
        <p style="text-align: center;">
            <!-- CHANGEMENT: Traduire le lien -->
            <a href="dashboard.php" class="back-link"><?php echo $lang->t('back_to_dashboard'); ?></a>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($errors)): ?>
                <div class="success-message">
                    <!-- CHANGEMENT: Traduire le message -->
                    <p><?php echo $lang->t('success_updated'); ?></p>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <!-- CHANGEMENT: Traduire le message -->
                    <p><?php echo $lang->t('error_form'); ?></p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />

            <!-- CHANGEMENT: Traduire tous les labels -->
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

            <label for="filled"><?php echo $lang->t('registered_participants_label'); ?> <span style="color: #ff6b6b;"><?php echo $lang->t('required_field'); ?></span></label>
            <input type="number" id="filled" name="filled" 
                   value="<?php echo isset($filled) ? htmlspecialchars($filled) : ''; ?>" 
                   required min="0" 
                   placeholder="Ex: 5">

            <label for="description"><?php echo $lang->t('description_label'); ?></label>
            <textarea id="description" name="description" 
                      placeholder="<?php echo $lang->t('placeholder_description'); ?>"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>

            <label for="image_url"><?php echo $lang->t('image_url_label'); ?></label>
            <input type="url" id="image_url" name="image_url" 
                   value="<?php echo isset($imageUrl) ? htmlspecialchars($imageUrl) : ''; ?>" 
                   placeholder="<?php echo $lang->t('placeholder_image'); ?>">

            <div class="form-buttons">
                <!-- CHANGEMENT: Traduire les boutons et le message de confirmation -->
                <a href="delete.php?id=<?php echo htmlspecialchars($id); ?>" 
                   onclick="return confirm('<?php echo $lang->t('delete_confirm'); ?>')"
                   style="flex: 1; text-decoration: none;">
                    <button type="button" class="btn-reset" style="width: 100%;"><?php echo $lang->t('btn_delete'); ?></button>
                </a>
                <button type="submit" class="btn-submit"><?php echo $lang->t('btn_update'); ?></button>
                <button type="reset" class="btn-reset"><?php echo $lang->t('btn_reset'); ?></button>
            </div>
        </form>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>