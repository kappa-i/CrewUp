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
        $sql = "SELECT * FROM events ORDER BY date ASC, time ASC";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->execute();

        $events = $stmt->fetchAll();

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

        return $events;
    }

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

    public function addEvent(Event $event): int
    {
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

        $stmt = $this->database->getPdo()->prepare($sql);

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

        $stmt->execute();

        $eventId = $this->database->getPdo()->lastInsertId();

        return $eventId;
    }

    public function updateEvent(Event $event): bool
    {
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

        $stmt = $this->database->getPdo()->prepare($sql);

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

        return $stmt->execute();
    }

    public function removeEvent(int $id): bool
    {
        $sql = "DELETE FROM events WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

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

    public function incrementFilled(int $eventId): bool
    {
        $sql = "UPDATE events SET filled = filled + 1 WHERE id = :id AND filled < capacity";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $eventId);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    public function decrementFilled(int $eventId): bool
    {
        $sql = "UPDATE events SET filled = filled - 1 WHERE id = :id AND filled > 0";

        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $eventId);

        return $stmt->execute() && $stmt->rowCount() > 0;
    }
}
