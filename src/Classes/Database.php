<?php

class Database implements DatabaseInterface
{
    const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../config/database.ini';

    private $pdo;

    public function __construct()
    {
        $config = parse_ini_file(self::DATABASE_CONFIGURATION_FILE, true);

        if (!$config) {
            throw new \Exception("Erreur lors de la lecture du fichier de configuration : " . self::DATABASE_CONFIGURATION_FILE);
        }

        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        $this->pdo = new \PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);

        $sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Sélection de la base de données
        $sql = "USE `$database`;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'user'
        );";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            sport VARCHAR(100) NOT NULL,
            location VARCHAR(255) NOT NULL,
            date DATE NOT NULL,
            time TIME NOT NULL,
            capacity INT NOT NULL,
            filled INT DEFAULT 0,
            description TEXT,
            image_url VARCHAR(500),
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS event_participants (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_id INT NOT NULL,
            user_id INT NOT NULL,
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_participation (event_id, user_id)
        );";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}