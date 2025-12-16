<?php

session_start();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: login.php');
    exit();
}

session_destroy();

header('Location: /index.php');
exit();
?>
