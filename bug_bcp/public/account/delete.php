<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;

// Création d'une instance de EventManager
$eventManager = new EventManager();

// Vérification si l'ID de l'événement est passé dans l'URL
if (isset($_GET["id"])) {
    // Récupération de l'ID de l'événement de la superglobale `$_GET`
    $eventId = $_GET["id"];
    
    // Suppression de l'événement correspondant à l'ID
    $eventManager->removeEvent($eventId);
    
    header("Location: /annonces.php");
    exit();
} else {
    // Si l'ID n'est pas passé dans l'URL, redirection vers la page des annonces
    header("Location: /annonces.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Suppression</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="../assets/js/global.js"></script>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/../menus/header.php'; ?>

    <main>
        <h1 class="hello">Supprimer une annonce</h1>
        <ul class="account-menu" style="margin-bottom: 400px;">
            <li><a href="dashboard.php">• Retour</a></li>
            <li><a href="create.php">• Créer</a></li>
            <li><a href="update.php">• Éditer</a></li>
        </ul>
    </main>

    <?php require __DIR__ . '/../menus/footer.php'; ?>
</body>

</html>