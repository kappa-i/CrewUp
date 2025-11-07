<?php

namespace Users;

interface UsersManagerInterface {
    public function getUsers(): array;
    public function getUserById(int $id): ?User;
    public function getUserByUsername(string $username): ?User;
    public function getUserByEmail(string $email): ?User;
    public function addUser(User $user): int;
    public function updateUser(User $user): bool;
    public function removeUser(int $id): bool;
    public function authenticate(string $username, string $password): ?User;
    public function usernameExists(string $username): bool;
    public function emailExists(string $email): bool;
}