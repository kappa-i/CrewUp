<?php

namespace Users;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

class UsersManager implements UsersManagerInterface {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    public function getUsers(): array {
        $sql = "SELECT * FROM users";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->execute();

        $users = $stmt->fetchAll();

        $users = array_map(function ($userData) {
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['email'],
                $userData['password'],
                $userData['role']
            );
        }, $users);

        return $users;
    }

    public function getUserById(int $id): ?User {
        $sql = "SELECT * FROM users WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        return new User(
            $userData['id'],
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
    }

    public function getUserByUsername(string $username): ?User {
        $sql = "SELECT * FROM users WHERE username = :username";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':username', $username);

        $stmt->execute();

        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        return new User(
            $userData['id'],
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
    }

    public function getUserByEmail(string $email): ?User {
        $sql = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        return new User(
            $userData['id'],
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
    }

    public function addUser(User $user): int {
        $sql = "INSERT INTO users (
            username,
            email,
            password,
            role
        ) VALUES (
            :username,
            :email,
            :password,
            :role
        )";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role', $user->getRole());

        $stmt->execute();

        $userId = $this->database->getPdo()->lastInsertId();

        return $userId;
    }

    public function updateUser(User $user): bool {
        $sql = "UPDATE users SET
            username = :username,
            email = :email,
            password = :password,
            role = :role
        WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':id', $user->getId());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role', $user->getRole());

        return $stmt->execute();
    }

    public function removeUser(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";

        $stmt = $this->database->getPdo()->prepare($sql);

        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function authenticate(string $username, string $password): ?User {
        $user = $this->getUserByUsername($username);

        if (!$user) {
            return null;
        }

        if ($user->verifyPassword($password)) {
            return $user;
        }

        return null;
    }

    public function usernameExists(string $username): bool {
        return $this->getUserByUsername($username) !== null;
    }

    public function emailExists(string $email): bool {
        return $this->getUserByEmail($email) !== null;
    }
}