<?php
require_once __DIR__ . '/../../src/utils/autoloader.php';

use Events\EventManager;

// Headers pour API JSON
header('Content-Type: application/json');

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Vous devez être connecté']);
    exit();
}

// Vérifie la méthode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit();
}

// Récupère les données JSON
$data = json_decode(file_get_contents('php://input'), true);
$eventId = $data['event_id'] ?? null;
$action = $data['action'] ?? 'join'; // 'join' ou 'leave'

if (!$eventId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID événement manquant']);
    exit();
}

try {
    $eventManager = new EventManager();
    $database = new Database();
    $pdo = $database->getPdo();
    
    // Récupère l'événement
    $event = $eventManager->getEventById($eventId);
    
    if (!$event) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Événement non trouvé']);
        exit();
    }
    
    if ($action === 'join') {
        // Vérifie si l'événement n'est pas complet
        if (!$event->isAvailable()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Événement complet']);
            exit();
        }
        
        // Vérifie si l'utilisateur n'est pas déjà inscrit
        $stmt = $pdo->prepare('SELECT * FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Déjà inscrit à cet événement']);
            exit();
        }
        
        // Ajoute la participation
        $stmt = $pdo->prepare('INSERT INTO event_participants (event_id, user_id) VALUES (:event_id, :user_id)');
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        
        // Incrémente le compteur
        $eventManager->incrementFilled($eventId);
        
        // Récupère l'événement mis à jour
        $event = $eventManager->getEventById($eventId);
        
        echo json_encode([
            'success' => true,
            'message' => 'Inscription réussie',
            'filled' => $event->getFilled(),
            'capacity' => $event->getCapacity(),
            'isAvailable' => $event->isAvailable()
        ]);
        
    } else if ($action === 'leave') {
        // Vérifie si l'utilisateur est inscrit
        $stmt = $pdo->prepare('SELECT * FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Vous n\'êtes pas inscrit à cet événement']);
            exit();
        }
        
        // Supprime la participation
        $stmt = $pdo->prepare('DELETE FROM event_participants WHERE event_id = :event_id AND user_id = :user_id');
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        
        // Décrémente le compteur
        $eventManager->decrementFilled($eventId);
        
        // Récupère l'événement mis à jour
        $event = $eventManager->getEventById($eventId);
        
        echo json_encode([
            'success' => true,
            'message' => 'Désinscription réussie',
            'filled' => $event->getFilled(),
            'capacity' => $event->getCapacity(),
            'isAvailable' => $event->isAvailable()
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Action invalide']);
    }
    
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}