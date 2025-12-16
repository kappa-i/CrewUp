<?php

namespace Events;

interface EventInterface {
    // Getters
    public function getId(): ?int;
    public function getTitle(): string;
    public function getSport(): string;
    public function getLocation(): string;
    public function getDate(): string;
    public function getTime(): string;
    public function getCapacity(): int;
    public function getFilled(): int;
    public function getDescription(): ?string;
    public function getImageUrl(): ?string;
    public function getUserId(): int;
    public function getCreatedAt(): ?string;

    // Setters
    public function setId(int $id): void;
    public function setTitle(string $title): void;
    public function setSport(string $sport): void;
    public function setLocation(string $location): void;
    public function setDate(string $date): void;
    public function setTime(string $time): void;
    public function setCapacity(int $capacity): void;
    public function setFilled(int $filled): void;
    public function setDescription(?string $description): void;
    public function setImageUrl(?string $imageUrl): void;
    public function setUserId(int $userId): void;

    public function isAvailable(): bool;
    public function getRemainingSlots(): int;
    public function getFormattedDate(): string;
}