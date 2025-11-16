<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/account.css">

<?php
// Démarre la session
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit();
}

// Initialise les variables
$error = '';
$success = '';

// Traiter le formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation des données
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (strlen($username) < 3) {
        $error = 'Le nom d\'utilisateur doit contenir au moins 3 caractères.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse e-mail invalide.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    } else {
        try {
            // Connexion à la base de données MySQL
            $pdo = new PDO(
                'mysql:host=h09pj7.myd.infomaniak.com;port=3306;dbname=h09pj7_db_crewup;charset=utf8mb4',
                'h09pj7_gabcappai',
                'muG9Wd27@_ Y$'
            );

            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                $error = 'Cet e-mail est déjà pris.';
            } else {
                // Vérifier si le nom d'utilisateur existe déjà
                $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
                $stmt->execute(['username' => $username]);
                $existingUsername = $stmt->fetch();

                if ($existingUsername) {
                    $error = 'Ce nom d\'utilisateur est déjà pris.';
                } else {
                    // Hacher le mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Insérer le nouvel utilisateur
                    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
                    $stmt->execute([
                        'username' => $username,
                        'email' => $email,
                        'password' => $hashedPassword,
                        'role' => 'user'
                    ]);

                    $success = 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de la création du compte : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrewUp - Créer un compte</title>
    <link rel="stylesheet" href="https://use.typekit.net/ooh3jgp.css">
    <link rel="icon" href="https://crewup.ch/favicon.ico?v=6" sizes="any">
</head>

<body>
    <main>
        <h1>Créer un compte</h1>

        <?php if ($error): ?>
            <p style="color: red;"><strong>Erreur :</strong> <?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color: green;"><strong>Succès :</strong> <?= htmlspecialchars($success) ?></p>
            <p><a href="login.php">Se connecter maintenant</a></p>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST">
                <label for="username">Nom d'utilisateur *</label>
                <input type="text" id="username" name="username" 
                       value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" 
                       required minlength="3" autofocus>

                <label for="email">Adresse e-mail *</label>
                <input type="email" id="email" name="email" 
                       value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" 
                       required>

                <label for="password">Mot de passe * (min. 8 caractères)</label>
                <input type="password" id="password" name="password" required minlength="8">

                <label for="confirm_password">Confirmer le mot de passe *</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">

                <button type="submit">Créer mon compte</button>
            </form>

            <p>Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
        <?php endif; ?>

        <p><a href="/index.php">← Retour à l'accueil</a></p>
    </main>
</body>

</html>