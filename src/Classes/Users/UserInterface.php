<?php

namespace Users;

interface UsersInterface {
    public function getId(): ?int;
    public function getUsername(): string;
    public function getEmail(): string;
    public function getPassword(): string;
    public function getRole(): string;

    public function setId(int $id): void;
    public function setUsername(string $username): void;
    public function setEmail(string $email): void;
    public function setPassword(string $password): void;
    public function setRole(string $role): void;

    public function isAdmin(): bool;
    public function verifyPassword(string $password): bool;
}