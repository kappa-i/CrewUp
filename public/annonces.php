<?php
// Chargement de l'autoloader pour charger automatiquement les classes
require_once __DIR__ . '/../src/utils/autoloader.php';

// Importation de la classe EventManager
use Events\EventManager;

// Création d'une instance de EventManager pour accéder aux événements
$eventManager = new EventManager();

// Récupération de tous les événements depuis la base de données
$events = $eventManager->getEvents();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Annonces</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="art-header">Annonces</h1>
        <ul class="account-menu">
            <li>Filtres : </li>
            <li><a href="#">Sport ></a></li>
            <li><a href="#">Lieu ></a></li>
            <li><a href="#">Date ></a></li>
        </ul>

        <div id="events" class="events-grid" style="margin: 40px 0 60px 0;">
            <?php if (empty($events)): ?>
                <!-- Message si aucun événement n'existe -->
                <p style="color: white; text-align: center; grid-column: 1 / -1; font-size: 1.2rem;">
                    Aucune annonce disponible pour le moment.
                    <a href="/account/create.php" style="color: #6b29ff; text-decoration: underline;">Créez la première !</a>
                </p>
            <?php else: ?>
                <!-- Boucle sur tous les événements récupérés -->
                <?php foreach ($events as $event): ?>
                    <article class="card">
                        <!-- Image de l'événement (ou image par défaut) -->
                        <img class="card_img" src="<?php echo htmlspecialchars($event->getImageUrl() ?? 'https://media.istockphoto.com/id/533861572/fr/photo/football-au-coucher-du-soleil.jpg?s=612x612&w=0&k=20&c=6qnC4x39vZ2wEUkTh1e6QJsqIKfxW6jo15aSCPjsITk='); ?>" alt="<?php echo htmlspecialchars($event->getTitle()); ?>">

                        <div class="card_info">
                            <div class="card_left_col">
                                <!-- Titre de l'événement -->
                                <h1 class="card_title"><?php echo htmlspecialchars($event->getTitle()); ?></h1>

                                <!-- Lieu de l'événement -->
                                <h3 class="card_place"><?php echo htmlspecialchars($event->getLocation()); ?></h3>

                                <!-- Date formatée (ex: "Sa, 12.07.26") -->
                                <h3 class="card_date"><?php echo htmlspecialchars($event->getFormattedDate()); ?></h3>
                            </div>

                            <div class="card_right_col">
                                <!-- Nombre de participants / capacité -->
                                <span class="card_ppl">
                                    <span class="card_filled"><?php echo $event->getFilled(); ?></span>/<span class="card_capacity"><?php echo $event->getCapacity(); ?></span>
                                </span>

                                <!-- Lien vers la page de détail (à créer plus tard) -->
                                <a class="card_link" href="event_detail.php?id=<?php echo $event->getId(); ?>">›</a>
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



