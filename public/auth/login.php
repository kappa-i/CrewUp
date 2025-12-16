<link rel="stylesheet" href="../assets/css/global.css">
<link rel="stylesheet" href="../assets/css/account.css">

<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        try {
            $database = new Database();
            $pdo = $database->getPdo();

            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: /index.php');
                exit();
            } else {
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
        <h1><?= htmlspecialchars($t['auth_login_title']) ?></h1>

        <?php if ($error): ?>
            <p style="color: red;">
                <strong><?= htmlspecialchars($t['auth_error_label']) ?></strong>
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">
                <?= htmlspecialchars($t['auth_username_label']) ?>
                <?= htmlspecialchars($t['required_field']) ?>
            </label>
            <input type="text" id="username" name="username"
                value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                required autofocus>

            <label for="password">
                <?= htmlspecialchars($t['auth_password_simple_label']) ?>
                <?= htmlspecialchars($t['required_field']) ?>
            </label>
            <input type="password" id="password" name="password" required>

            <button type="submit"><?= htmlspecialchars($t['auth_login_btn']) ?></button>
        </form>

        <p>
            <?= htmlspecialchars($t['auth_no_account']) ?>
            <a href="register.php"><?= htmlspecialchars($t['auth_register_link']) ?></a>
        </p>

        <p>
            <a href="/index.php"><?= htmlspecialchars($t['auth_back_home']) ?></a>
        </p>
    </main>
</body>

</html>