<?php

namespace Events;

class Event implements EventInterface
{
    private ?int $id;
    private string $title;
    private string $sport;
    private string $location;
    private string $date;
    private string $time;
    private int $capacity;
    private int $filled;
    private ?string $description;
    private ?string $imageUrl;
    private int $userId;
    private ?string $createdAt;

    // Constructeur pour initialiser l'objet
    public function __construct(
        ?int $id,
        string $title,
        string $sport,
        string $location,
        string $date,
        string $time,
        int $capacity,
        int $filled = 0,
        ?string $description = null,
        ?string $imageUrl = null,
        int $userId = 0,
        ?string $createdAt = null
    ) {
        // Vérification des données
        if (empty($title)) {
            throw new \InvalidArgumentException("Le titre est requis.");
        } else if (strlen($title) < 3) {
            throw new \InvalidArgumentException("Le titre doit contenir au moins 3 caractères.");
        }

        if (empty($sport)) {
            throw new \InvalidArgumentException("Le sport est requis.");
        }

        if (empty($location)) {
            throw new \InvalidArgumentException("Le lieu est requis.");
        }

        if (empty($date)) {
            throw new \InvalidArgumentException("La date est requise.");
        }

        if (empty($time)) {
            throw new \InvalidArgumentException("L'heure est requise.");
        }

        if ($capacity <= 0) {
            throw new \InvalidArgumentException("La capacité doit être un nombre positif.");
        }

        if ($filled < 0) {
            throw new \InvalidArgumentException("Le nombre de participants doit être positif ou nul.");
        }

        if ($filled > $capacity) {
            throw new \InvalidArgumentException("Le nombre de participants ne peut pas dépasser la capacité.");
        }

        // Initialisation des propriétés
        $this->id = $id;
        $this->title = $title;
        $this->sport = $sport;
        $this->location = $location;
        $this->date = $date;
        $this->time = $time;
        $this->capacity = $capacity;
        $this->filled = $filled;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    // Getters pour accéder aux propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSport(): string
    {
        return $this->sport;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getFilled(): int
    {
        return $this->filled;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    // Setters pour modifier les propriétés
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        if (!empty($title) && strlen($title) >= 3) {
            $this->title = $title;
        }
    }

    public function setSport(string $sport): void
    {
        if (!empty($sport)) {
            $this->sport = $sport;
        }
    }

    public function setLocation(string $location): void
    {
        if (!empty($location)) {
            $this->location = $location;
        }
    }

    public function setDate(string $date): void
    {
        if (!empty($date)) {
            $this->date = $date;
        }
    }

    public function setTime(string $time): void
    {
        if (!empty($time)) {
            $this->time = $time;
        }
    }

    public function setCapacity(int $capacity): void
    {
        if ($capacity > 0) {
            $this->capacity = $capacity;
        }
    }

    public function setFilled(int $filled): void
    {
        if ($filled >= 0 && $filled <= $this->capacity) {
            $this->filled = $filled;
        }
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    // Méthodes utilitaires
    public function isAvailable(): bool
    {
        return $this->filled < $this->capacity;
    }

    public function getRemainingSlots(): int
    {
        return $this->capacity - $this->filled;
    }

    public function getFormattedDate(): string
    {
        // Convertir la date en format français (ex: "Sa, 12.07.26")
        $timestamp = strtotime($this->date);
        $days = ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'];
        $dayName = $days[date('w', $timestamp)];
        return $dayName . ', ' . date('d.m.y', $timestamp);
    }
}