<?php

declare(strict_types=1);

require_once __DIR__ . '/Connection.php';

class Admin {

    private ?PDO $db = null;

    public function __construct(?PDO $pdo = null) {
        $this->db = $pdo;
    }

    private function db(): PDO {
        if ($this->db === null) {
            $this->db = openConnection();
        }
        return $this->db;
    }

    public function findByUsername(string $username): ?array {
        $db = $this->db();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }
}
