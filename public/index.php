<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Site officiel</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <script src="assets/js/global.js"></script>
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
    <link rel="shortcut icon" href="https://crewup.ch/favicon.ico?v=6">

</head>

<body>
    <?php require __DIR__ . '/menus/header.php'; ?>

    <main>
        <h1 class="hello">Bienvenue sur CrewUp!</h1>
        <p class="hello-sub">🔥 Trouve ton équipe, entre sur le terrain.</p>
        <div class="cta-section">
            <img src="https://purepng.com/public/uploads/large/nba-player-zzw.png" alt="basketball-player">
            <button class="orange-btn" type="button" onclick="window.location.href='/annonces.php'">Commencer votre aventure</button>
        </div>
    </main>

    <?php require __DIR__ . '/menus/footer.php'; ?>
</body>

</html>