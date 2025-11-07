<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

// Démarre la session
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit();
}

// Initialise les variables
$error = '';

// Traite le formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validation des données
    if (empty($username) || empty($password)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        try {
            // Connexion à la base de données
            $database = new Database();
            $pdo = $database->getPdo();

            // Récupérer l'utilisateur de la base de données
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            // Vérifier le mot de passe
            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie - stocker les informations dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Rediriger vers la page d'accueil
                header('Location: /index.php');
                exit();
            } else {
                // Authentification échouée
                $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de la connexion : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Se connecter</title>
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <main>
        <h1>Se connecter</h1>

        <?php if ($error): ?>
            <p style="color: red;"><strong>Erreur :</strong> <?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Nom d'utilisateur *</label>
            <input type="text" id="username" name="username"
                value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                required autofocus>

            <label for="password">Mot de passe *</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>

        <p><a href="/index.php">← Retour à l'accueil</a></p>
    </main>
</body>

</html>