<?php

namespace Users;

require_once __DIR__ . '/../../utils/autoloader.php';

use Database;

class UsersManager implements UsersManagerInterface {
    private $database;

    public function __construct() {
        $this->database = new Database();
    }

    /**
     * Récupère tous les utilisateurs
     */
    public function getUsers(): array {
        // Définition de la requête SQL pour récupérer tous les utilisateurs
        $sql = "SELECT * FROM users";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de tous les utilisateurs
        $users = $stmt->fetchAll();

        // Transformation des tableaux associatifs en objets User
        $users = array_map(function ($userData) {
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['email'],
                $userData['password'],
                $userData['role']
            );
        }, $users);

        // Retour de tous les utilisateurs
        return $users;
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getUserById(int $id): ?User {
        // Définition de la requête SQL
        $sql = "SELECT * FROM users WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de l'utilisateur
        $userData = $stmt->fetch();

        // Si aucun utilisateur trouvé, retourner null
        if (!$userData) {
            return null;
        }

        // Création et retour de l'objet User
        return new User(
            $userData['id'],
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
    }

    /**
     * Récupère un utilisateur par son nom d'utilisateur
     */
    public function getUserByUsername(string $username): ?User {
        // Définition de la requête SQL
        $sql = "SELECT * FROM users WHERE username = :username";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':username', $username);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de l'utilisateur
        $userData = $stmt->fetch();

        // Si aucun utilisateur trouvé, retourner null
        if (!$userData) {
            return null;
        }

        // Création et retour de l'objet User
        return new User(
            $userData['id'],
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function getUserByEmail(string $email): ?User {
        // Définition de la requête SQL
        $sql = "SELECT * FROM users WHERE email = :email";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':email', $email);

        // Exécution de la requête SQL
        $stmt->execute();

        // Récupération de l'utilisateur
        $userData = $stmt->fetch();

        // Si aucun utilisateur trouvé, retourner null
        if (!$userData) {
            return null;
        }

        // Création et retour de l'objet User
        return new User(
            $userData['id'],
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
    }

    /**
     * Ajoute un nouvel utilisateur
     */
    public function addUser(User $user): int {
        // Définition de la requête SQL pour ajouter un utilisateur
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

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role', $user->getRole());

        // Exécution de la requête SQL pour ajouter un utilisateur
        $stmt->execute();

        // Récupération de l'identifiant de l'utilisateur ajouté
        $userId = $this->database->getPdo()->lastInsertId();

        // Retour de l'identifiant de l'utilisateur ajouté
        return $userId;
    }

    /**
     * Met à jour un utilisateur existant
     */
    public function updateUser(User $user): bool {
        // Définition de la requête SQL pour mettre à jour un utilisateur
        $sql = "UPDATE users SET
            username = :username,
            email = :email,
            password = :password,
            role = :role
        WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':id', $user->getId());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role', $user->getRole());

        // Exécution de la requête SQL pour mettre à jour l'utilisateur
        return $stmt->execute();
    }

    /**
     * Supprime un utilisateur
     */
    public function removeUser(int $id): bool {
        // Définition de la requête SQL pour supprimer un utilisateur
        $sql = "DELETE FROM users WHERE id = :id";

        // Préparation de la requête SQL
        $stmt = $this->database->getPdo()->prepare($sql);

        // Lien avec le paramètre
        $stmt->bindValue(':id', $id);

        // Exécution de la requête SQL pour supprimer un utilisateur
        return $stmt->execute();
    }

    /**
     * Authentifie un utilisateur
     */
    public function authenticate(string $username, string $password): ?User {
        // Récupération de l'utilisateur par son nom d'utilisateur
        $user = $this->getUserByUsername($username);

        // Si l'utilisateur n'existe pas, retourner null
        if (!$user) {
            return null;
        }

        // Vérification du mot de passe
        if ($user->verifyPassword($password)) {
            return $user;
        }

        // Mot de passe incorrect
        return null;
    }

    /**
     * Vérifie si un nom d'utilisateur existe déjà
     */
    public function usernameExists(string $username): bool {
        return $this->getUserByUsername($username) !== null;
    }

    /**
     * Vérifie si un email existe déjà
     */
    public function emailExists(string $email): bool {
        return $this->getUserByEmail($email) !== null;
    }
}