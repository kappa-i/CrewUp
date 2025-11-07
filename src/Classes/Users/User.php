<?php

namespace Users;

class User implements UsersInterface
{
    private ?int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $role;

    // Constructeur pour initialiser l'objet
    public function __construct(
        ?int $id,
        string $username,
        string $email,
        string $password,
        string $role = 'user'
    ) {
        // Vérification des données
        if (empty($username)) {
            throw new \InvalidArgumentException("Le nom d'utilisateur est requis.");
        } else if (strlen($username) < 3) {
            throw new \InvalidArgumentException("Le nom d'utilisateur doit contenir au moins 3 caractères.");
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Un email valide est requis.");
        }

        if (empty($password)) {
            throw new \InvalidArgumentException("Le mot de passe est requis.");
        }

        if (!in_array($role, ['user', 'admin'])) {
            throw new \InvalidArgumentException("Le rôle doit être 'user' ou 'admin'.");
        }

        // Initialisation des propriétés
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Getters pour accéder aux propriétés
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // Setters pour modifier les propriétés
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        if (!empty($username) && strlen($username) >= 3) {
            $this->username = $username;
        }
    }

    public function setEmail(string $email): void
    {
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
    }

    public function setPassword(string $password): void
    {
        if (!empty($password)) {
            $this->password = $password;
        }
    }

    public function setRole(string $role): void
    {
        if (in_array($role, ['user', 'admin'])) {
            $this->role = $role;
        }
    }

    // Méthodes utilitaires
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}