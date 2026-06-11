<?php
require_once("BD.php");

class Admin extends DB {

    /**
     * Find an admin by username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername($username) {
        $db = $this->openDB();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->closeDB($db);
        return $row ?: null;
    }
}
