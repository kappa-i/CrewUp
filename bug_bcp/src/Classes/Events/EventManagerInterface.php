<?php

namespace Events;

interface EventManagerInterface {
    public function getEvents(): array;
    public function getEventById(int $id): ?Event;
    public function addEvent(Event $event): int;
    public function updateEvent(Event $event): bool;
    public function removeEvent(int $id): bool;
    public function getEventsBySport(string $sport): array;
    public function incrementFilled(int $eventId): bool;
    public function decrementFilled(int $eventId): bool;
}