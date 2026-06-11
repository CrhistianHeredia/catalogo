<?php

require_once __DIR__ . '/Connection.php';

class Usuario {

    private ?PDO $db = null;
    private $id = null; 
    private $nombre = null;
    private $name = null;
    private $email = null;
    private $phone = null;

    /**
     * @param PDO|null $pdo Optional PDO instance. If null, opens a new connection.
     */
    public function __construct(?PDO $pdo = null) {
        $this->db = $pdo;
    }

    /**
     * Get (or lazily open) the PDO connection.
     */
    private function db(): PDO {
        if ($this->db === null) {
            $this->db = openConnection();
        }
        return $this->db;
    }

    public function setIdUser($arg) {
        $this->id = $arg;
    }

    public function setEmail($arg) {
        $this->email = $arg;
    }
    
    public function setName($arg) {
        $this->name = $arg;
    }

    public function setPhone($arg) {
        $this->phone = $arg;
    }
    
    public function getIdUser() {
        return intval($this->id);
    }

    public function getEmail() {
        return $this->email;
    }
        
    public function getName() {
        return $this->name;
    }

    public function getPhone() {
        return $this->phone;
    }
    
    public function consultar() {
        $db = $this->db();
        $query = "SELECT * FROM users";
        $statement = $db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregar() {
        $db = $this->db();
        $query = "INSERT INTO `prueba`.`users` (`user_name`,`phone`,`email`) VALUES (:username,:phone,:useremail)";
        $statement = $db->prepare($query);
        $statement->bindParam('username', $this->name);
        $statement->bindParam('phone', $this->phone);
        $statement->bindParam('useremail', $this->email);
        $statement->execute();
    }

    public function modificar() {
        $db = $this->db();
        $query = "UPDATE `prueba`.`users` SET `user_name` = :username, `phone` = :phone, `email` = :useremail WHERE `id_user` = :id";
        $statement = $db->prepare($query);
        $statement->bindParam('username', $this->name);
        $statement->bindParam('phone', $this->phone);
        $statement->bindParam('useremail', $this->email);
        $statement->bindParam('id', $this->id);
        $statement->execute();
    }

    public function eliminar() {
        $db = $this->db();
        $query = "DELETE FROM `prueba`.`users` WHERE `id_user` = :id";
        $statement = $db->prepare($query);
        $statement->bindParam('id', $this->id);
        $statement->execute();
    }
}
