<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;
use Events\Event;

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
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Édition</title>
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
        <h1 class="hello">Éditer une annonce</h1>
        <p style="text-align: center;">
            <a href="dashboard.php" class="back-link">← Retour au dashboard</a>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($errors)): ?>
                <div class="success-message">
                    <p>L'annonce a été mise à jour avec succès !</p>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <p>Le formulaire contient des erreurs :</p>
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

            <label for="title">Titre de l'événement <span style="color: #ff6b6b;">*</span></label>
            <input type="text" id="title" name="title" 
                   value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" 
                   required minlength="3" 
                   placeholder="Ex: Match de foot amical">

            <label for="sport">Sport <span style="color: #ff6b6b;">*</span></label>
            <select id="sport" name="sport" required>
                <option value="">-- Choisir un sport --</option>
                <?php foreach ($sports as $key => $value): ?>
                    <option value="<?= $key ?>" <?php echo (isset($sport) && $sport == $key) ? 'selected' : ''; ?>>
                        <?= $value ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="location">Lieu <span style="color: #ff6b6b;">*</span></label>
            <input type="text" id="location" name="location" 
                   value="<?php echo isset($location) ? htmlspecialchars($location) : ''; ?>" 
                   required 
                   placeholder="Ex: Centre sportif d'Écublens">

            <label for="date">Date <span style="color: #ff6b6b;">*</span></label>
            <input type="date" id="date" name="date" 
                   value="<?php echo isset($date) ? htmlspecialchars($date) : ''; ?>" 
                   required>

            <label for="time">Heure <span style="color: #ff6b6b;">*</span></label>
            <input type="time" id="time" name="time" 
                   value="<?php echo isset($time) ? htmlspecialchars($time) : ''; ?>" 
                   required>

            <label for="capacity">Nombre de participants maximum <span style="color: #ff6b6b;">*</span></label>
            <input type="number" id="capacity" name="capacity" 
                   value="<?php echo isset($capacity) ? htmlspecialchars($capacity) : ''; ?>" 
                   required min="2" 
                   placeholder="Ex: 10">

            <label for="filled">Nombre de participants inscrits <span style="color: #ff6b6b;">*</span></label>
            <input type="number" id="filled" name="filled" 
                   value="<?php echo isset($filled) ? htmlspecialchars($filled) : ''; ?>" 
                   required min="0" 
                   placeholder="Ex: 5">

            <label for="description">Description (optionnel)</label>
            <textarea id="description" name="description" 
                      placeholder="Décrivez votre événement, le niveau requis, le matériel nécessaire..."><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>

            <label for="image_url">URL de l'image (optionnel)</label>
            <input type="url" id="image_url" name="image_url" 
                   value="<?php echo isset($imageUrl) ? htmlspecialchars($imageUrl) : ''; ?>" 
                   placeholder="https://exemple.com/image.jpg">

            <div class="form-buttons">
                <a href="delete.php?id=<?php echo htmlspecialchars($id); ?>" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')"
                   style="flex: 1; text-decoration: none;">
                    <button type="button" class="btn-reset" style="width: 100%;">Supprimer</button>
                </a>
                <button type="submit" class="btn-submit">Mettre à jour</button>
                <button type="reset" class="btn-reset">Réinitialiser</button>
            </div>
        </form>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

