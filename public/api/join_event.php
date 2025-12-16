<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;

session_start();

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: /auth/login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header('Location: /annonces.php');
    exit();
}

$eventId = $_GET['event_id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$eventId || !$action) {
    header('Location: /annonces.php');
    exit();
}

try {
    $eventManager = new EventManager();
    $database = new Database();
    $pdo = $database->getPdo();
    
    $event = $eventManager->getEventById($eventId);
    
    if (!$event) {
        header('Location: /annonces.php');
        exit();
    }
    
    if ($action === 'join') {

        if ($event->isAvailable()) {

            $stmt = $pdo->prepare('SELECT * FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
            $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
            
            if (!$stmt->fetch()) {

                $stmt = $pdo->prepare('INSERT INTO event_participants (event_id, user_id) VALUES (:event_id, :user_id)');
                $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
                
                $eventManager->incrementFilled($eventId);
            }
        }
    } else if ($action === 'leave') {

        $stmt = $pdo->prepare('DELETE FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        
        $eventManager->decrementFilled($eventId);
    }
    
    header("Location: /event_detail.php?id=$eventId");
    exit();
    
} catch (\Exception $e) {
    header("Location: /event_detail.php?id=$eventId");
    exit();
}