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
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Nouvelle annonce</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .form-container form {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 40px;
            margin-top: 30px;
        }

        .form-container label {
            display: block;
            color: #fff;
            font-weight: 600;
            margin-bottom: 8px;
            margin-top: 20px;
        }

        .form-container input[type="text"],
        .form-container input[type="date"],
        .form-container input[type="time"],
        .form-container input[type="number"],
        .form-container input[type="url"],
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #fff;
            font-family: "industry", sans-serif;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-container input:focus,
        .form-container select:focus,
        .form-container textarea:focus {
            outline: none;
            border-color: #6b29ff;
            background: rgba(255, 255, 255, 0.12);
        }

        .form-container textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-buttons {
            display: flex;
            gap: 16px;
            margin-top: 30px;
        }

        .form-buttons button {
            flex: 1;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-family: "industry", sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit {
            background: #6b29ff;
            color: #fff;
        }

        .btn-submit:hover {
            background: #844aff;
            box-shadow: 0 0 20px rgba(107, 41, 255, 0.6);
        }

        .btn-reset {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-reset:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .error-message p {
            color: #ff6b6b;
            font-weight: 600;
            margin: 0 0 10px 0;
        }

        .error-message ul {
            color: #ffb3b3;
            margin: 0;
            padding-left: 20px;
        }

        .success-message {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .success-message p {
            color: #5fff5f;
            font-weight: 600;
            margin: 0;
        }

        .back-link {
            color: #6b29ff;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s ease;
        }

        .back-link:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main class="form-container">
        <h1 class="hello">Créer une annonce</h1>
        <p style="text-align: center;">
            <a href="dashboard.php" class="back-link">← Retour au dashboard</a>
        </p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($errors)): ?>
                <div class="success-message">
                    <p>✓ L'annonce a été créée avec succès !</p>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <p>❌ Le formulaire contient des erreurs :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="create.php" method="POST">
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

            <label for="description">Description (optionnel)</label>
            <textarea id="description" name="description" 
                      placeholder="Décrivez votre événement, le niveau requis, le matériel nécessaire..."><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>

            <label for="image_url">URL de l'image (optionnel)</label>
            <input type="url" id="image_url" name="image_url" 
                   value="<?php echo isset($imageUrl) ? htmlspecialchars($imageUrl) : ''; ?>" 
                   placeholder="https://exemple.com/image.jpg">

            <div class="form-buttons">
                <button type="submit" class="btn-submit">Créer l'annonce</button>
                <button type="reset" class="btn-reset">Réinitialiser</button>
            </div>
        </form>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>