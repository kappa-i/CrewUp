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

<body data-page="terrains">
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="art-header">Terrains</h1>
        <ul class="account-menu">
            <li>Filtres : </li>
            <li><a href="#">Sport ></a></li>
            <li><a href="#">Lieu ></a></li>
            <li><a href="#">Date ></a></li>
        </ul>
        <div id="events" class="events-grid" style="margin: 40px 0 60px 0;">
        <!--PLACEHOLDER -> Migrer dans assets/cards.php-->
            <article class="card">
                <img class="card_img" src="https://www.anniviers.org/data/images/contents/3_Vie_locale/220912_TerrainfootdeVissoie.jpg">
                <div class="card_info">
                    <div class="card_left_col">
                        <h1 class="card_title">Terrain de Foot d'Ecublens</h1>
                        <h3 class="card_place">Centre sportif, Écublens</h3>
                    </div>
                    <div class="card_right_col">
                        <a class="card_link" href="#">›</a>
                    </div>
                </div>
            </article>
        <!---->
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>