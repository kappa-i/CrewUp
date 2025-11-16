<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: /auth/login.php');
    exit();
}

// Vérifie la méthode GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header('Location: /annonces.php');
    exit();
}

// Récupère les paramètres
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
    
    // Récupère l'événement
    $event = $eventManager->getEventById($eventId);
    
    if (!$event) {
        header('Location: /annonces.php');
        exit();
    }
    
    if ($action === 'join') {
        // Vérifie si l'événement n'est pas complet
        if ($event->isAvailable()) {
            // Vérifie si l'utilisateur n'est pas déjà inscrit
            $stmt = $pdo->prepare('SELECT * FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
            $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
            
            if (!$stmt->fetch()) {
                // Ajoute la participation
                $stmt = $pdo->prepare('INSERT INTO event_participants (event_id, user_id) VALUES (:event_id, :user_id)');
                $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
                
                // Incrémente le compteur
                $eventManager->incrementFilled($eventId);
            }
        }
    } else if ($action === 'leave') {
        // Supprime la participation
        $stmt = $pdo->prepare('DELETE FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        
        // Décrémente le compteur
        $eventManager->decrementFilled($eventId);
    }
    
    // Redirige vers la page de l'événement
    header("Location: /event_detail.php?id=$eventId");
    exit();
    
} catch (\Exception $e) {
    // En cas d'erreur, redirige vers la page de l'événement
    header("Location: /event_detail.php?id=$eventId");
    exit();
}