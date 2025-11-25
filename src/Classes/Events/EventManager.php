<?php

namespace Events;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

class EventManager implements EventManagerInterface
{
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    /**
     * Récupère tous les événements
     */
    public function getEvents(): array
    {
        // Définition de la requête SQL pour récupérer tous les événements
        $sql = "SELECT * FROM events ORDER BY date ASC, time ASC";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de tous les événements
        $events = $stmt->fetchAll();

        // Transformation des tableaux associatifs en objets Event
        $events = array_map(function ($eventData) {
            return new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['sport'],
                $eventData['location'],
                $eventData['date'],
                $eventData['time'],
                $eventData['capacity'],
                $eventData['filled'],
                $eventData['description'],
                $eventData['image_url'],
                $eventData['user_id'],
                $eventData['created_at']
            );
        }, $events);

        // Retour de tous les événements
        return $events;
    }

    /**
     * Récupère un événement par son ID
     */
    public function getEventById(int $id): ?Event
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $eventData = $stmt->fetch();
        if (!$eventData) {
            return null;
        }

        return new Event(
            $eventData['id'],
            $eventData['title'],
            $eventData['sport'],
            $eventData['location'],
            $eventData['date'],
            $eventData['time'],
            $eventData['capacity'],
            $eventData['filled'],
            $eventData['description'],
            $eventData['image_url'],
            $eventData['user_id'],
            $eventData['created_at']
        );
    }

    public function getEventsByUserId(int $userId): array
    {
        $sql = "SELECT * FROM events WHERE user_id = :user_id ORDER BY date ASC, time ASC";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();

        $events = $stmt->fetchAll();

        return array_map(function ($eventData) {
            return new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['sport'],
                $eventData['location'],
                $eventData['date'],
                $eventData['time'],
                $eventData['capacity'],
                $eventData['filled'],
                $eventData['description'],
                $eventData['image_url'],
                $eventData['user_id'],
                $eventData['created_at']
            );
        }, $events);
    }

    /**
     * Ajoute un nouvel événement
     */
    public function addEvent(Event $event): int
    {
        // Définition de la requête SQL pour ajouter un événement
        $sql = "INSERT INTO events (
            title,
            sport,
            location,
            date,
            time,
            capacity,
            filled,
            description,
            image_url,
            user_id
        ) VALUES (
            :title,
            :sport,
            :location,
            :date,
            :time,
            :capacity,
            :filled,
            :description,
            :image_url,
            :user_id
        )";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':title', $event->getTitle());
        $stmt->bindValue(':sport', $event->getSport());
        $stmt->bindValue(':location', $event->getLocation());
        $stmt->bindValue(':date', $event->getDate());
        $stmt->bindValue(':time', $event->getTime());
        $stmt->bindValue(':capacity', $event->getCapacity());
        $stmt->bindValue(':filled', $event->getFilled());
        $stmt->bindValue(':description', $event->getDescription());
        $stmt->bindValue(':image_url', $event->getImageUrl());
        $stmt->bindValue(':user_id', $event->getUserId());

        // Exécution de la requête SQL pour ajouter un événement
        $stmt->execute();

        // Récupération de l'identifiant de l'événement ajouté
        $eventId = $this->database->getPdo()->lastInsertId();

        // Retour de l'identifiant de l'événement ajouté
        return $eventId;
    }

    /**
     * Met à jour un événement existant
     */
    public function updateEvent(Event $event): bool
    {
        // Définition de la requête SQL pour mettre à jour un événement
        $sql = "UPDATE events SET
            title = :title,
            sport = :sport,
            location = :location,
            date = :date,
            time = :time,
            capacity = :capacity,
            filled = :filled,
            description = :description,
            image_url = :image_url
        WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':id', $event->getId());
        $stmt->bindValue(':title', $event->getTitle());
        $stmt->bindValue(':sport', $event->getSport());
        $stmt->bindValue(':location', $event->getLocation());
        $stmt->bindValue(':date', $event->getDate());
        $stmt->bindValue(':time', $event->getTime());
        $stmt->bindValue(':capacity', $event->getCapacity());
        $stmt->bindValue(':filled', $event->getFilled());
        $stmt->bindValue(':description', $event->getDescription());
        $stmt->bindValue(':image_url', $event->getImageUrl());

        // Exécution de la requête SQL pour mettre à jour l'événement
        return $stmt->execute();
    }

    /**
     * Supprime un événement
     */
    public function removeEvent(int $id): bool
    {
        // Définition de la requête SQL pour supprimer un événement
        $sql = "DELETE FROM events WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL pour supprimer un événement
        return $stmt->execute();
    }

    /**
     * Récupère les événements par sport
     */
    public function getEventsBySport(string $sport): array
    {
        $sql = "SELECT * FROM events WHERE sport = :sport ORDER BY date ASC, time ASC";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':sport', $sport);
        $stmt->execute();

        $events = $stmt->fetchAll();

        return array_map(function ($eventData) {
            return new Event(
                $eventData['id'],
                $eventData['title'],
                $eventData['sport'],
                $eventData['location'],
                $eventData['date'],
                $eventData['time'],
                $eventData['capacity'],
                $eventData['filled'],
                $eventData['description'],
                $eventData['image_url'],
                $eventData['user_id'],
                $eventData['created_at']
            );
        }, $events);
    }

    /**
     * Incrémente le nombre de participants
     */
    public function incrementFilled(int $eventId): bool
    {
        $sql = "UPDATE events SET filled = filled + 1 WHERE id = :id AND filled < capacity";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $eventId);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    /**
     * Décrémente le nombre de participants
     */
    public function decrementFilled(int $eventId): bool
    {
        $sql = "UPDATE events SET filled = filled - 1 WHERE id = :id AND filled > 0";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $eventId);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}
