<?php
namespace Classes\Events;

use Classes\Events\Events as Event; // alias vers ta classe "Events"
use Classes\Database;               // ta DB namespacée
use PDO;

class EventManager implements EventManagerInterface
{
    private Database $database;

    public function __construct()
    {
        // Si ta Database lit la config en interne, on garde l'instanciation simple
        $this->database = new Database();
    }

    /**
     * Récupère tous les événements
     */
    public function getEvents(): array
    {
        $sql  = "SELECT * FROM events ORDER BY date ASC, time ASC";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrateEvent'], $rows);
    }

    /**
     * Récupère un événement par son ID
     */
    public function getEventById(int $id): ?Event
    {
        $sql  = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrateEvent($row) : null;
    }

    /**
     * Ajoute un nouvel événement
     * @return int ID inséré
     */
    public function addEvent(Event $event): int
    {
        $sql = "INSERT INTO events (
            title, sport, location, date, time, capacity, filled, description, image_url, user_id
        ) VALUES (
            :title, :sport, :location, :date, :time, :capacity, :filled, :description, :image_url, :user_id
        )";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':title',    $event->getTitle());
        $stmt->bindValue(':sport',    $event->getSport());
        $stmt->bindValue(':location', $event->getLocation());
        $stmt->bindValue(':date',     $event->getDate());
        $stmt->bindValue(':time',     $event->getTime());
        $stmt->bindValue(':capacity', $event->getCapacity(), PDO::PARAM_INT);
        $stmt->bindValue(':filled',   $event->getFilled(),   PDO::PARAM_INT);

        // Conserver correctement NULL en DB
        $desc = $event->getDescription();
        $img  = $event->getImageUrl();
        $stmt->bindValue(':description', $desc, $desc === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':image_url',   $img,  $img  === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':user_id',  $event->getUserId(), PDO::PARAM_INT);
        $stmt->execute();

        return (int)$this->database->getPdo()->lastInsertId();
    }

    /**
     * Met à jour un événement existant
     */
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

        $stmt->bindValue(':id',       $event->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':title',    $event->getTitle());
        $stmt->bindValue(':sport',    $event->getSport());
        $stmt->bindValue(':location', $event->getLocation());
        $stmt->bindValue(':date',     $event->getDate());
        $stmt->bindValue(':time',     $event->getTime());
        $stmt->bindValue(':capacity', $event->getCapacity(), PDO::PARAM_INT);
        $stmt->bindValue(':filled',   $event->getFilled(),   PDO::PARAM_INT);

        $desc = $event->getDescription();
        $img  = $event->getImageUrl();
        $stmt->bindValue(':description', $desc, $desc === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':image_url',   $img,  $img  === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Supprime un événement
     */
    public function removeEvent(int $id): bool
    {
        $sql  = "DELETE FROM events WHERE id = :id";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Récupère les événements par sport
     */
    public function getEventsBySport(string $sport): array
    {
        $sql  = "SELECT * FROM events WHERE sport = :sport ORDER BY date ASC, time ASC";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':sport', $sport);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'hydrateEvent'], $rows);
    }

    /**
     * Incrémente remplissage si dispo
     */
    public function incrementFilled(int $eventId): bool
    {
        $sql  = "UPDATE events SET filled = filled + 1 WHERE id = :id AND filled < capacity";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $eventId, PDO::PARAM_INT);
        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    /**
     * Décrémente remplissage si > 0
     */
    public function decrementFilled(int $eventId): bool
    {
        $sql  = "UPDATE events SET filled = filled - 1 WHERE id = :id AND filled > 0";
        $stmt = $this->database->getPdo()->prepare($sql);
        $stmt->bindValue(':id', $eventId, PDO::PARAM_INT);
        return $stmt->execute() && $stmt->rowCount() > 0;
    }

    /**
     * Hydrate un Event depuis une ligne DB
     */
    private function hydrateEvent(array $e): Event
    {
        return new Event(
            (int)$e['id'],
            (string)$e['title'],
            (string)$e['sport'],
            (string)$e['location'],
            (string)$e['date'],
            (string)$e['time'],
            (int)$e['capacity'],
            (int)($e['filled'] ?? 0),
            $e['description'] ?? null,
            $e['image_url'] ?? null,
            (int)$e['user_id'],
            (string)$e['created_at']
        );
    }
}
