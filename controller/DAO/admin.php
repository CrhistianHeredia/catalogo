<?php
require_once __DIR__ . '/Connection.php';

class Admin {

    private ?PDO $db = null;

    /**
     * @param PDO|null $pdo Optional PDO instance. If null, opens a new connection.
     */
    public function __construct(?PDO $pdo = null) {
        $this->db = $pdo;
    }

    private function db(): PDO {
        if ($this->db === null) {
            $this->db = openConnection();
        }
        return $this->db;
    }

    /**
     * Find an admin by username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername($username) {
        $db = $this->db();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
