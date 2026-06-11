<?php

declare(strict_types=1);

require_once __DIR__ . '/Connection.php';

class Usuario {

    private ?PDO $db = null;
    private int|null|string $id = null; 
    private ?string $nombre = null;
    private ?string $name = null;
    private ?string $email = null;
    private ?string $phone = null;

    public function __construct(?PDO $pdo = null) {
        $this->db = $pdo;
    }

    private function db(): PDO {
        if ($this->db === null) {
            $this->db = openConnection();
        }
        return $this->db;
    }

    public function setIdUser(int|string $arg): void {
        $this->id = $arg;
    }

    public function setEmail(string $arg): void {
        $this->email = $arg;
    }
    
    public function setName(string $arg): void {
        $this->name = $arg;
    }

    public function setPhone(string $arg): void {
        $this->phone = $arg;
    }
    
    public function getIdUser(): int {
        return intval($this->id);
    }

    public function getEmail(): ?string {
        return $this->email;
    }
        
    public function getName(): ?string {
        return $this->name;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }
    
    public function consultar(): array|false {
        $db = $this->db();
        $query = "SELECT * FROM users";
        $statement = $db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregar(): void {
        $db = $this->db();
        $query = "INSERT INTO `prueba`.`users` (`user_name`,`phone`,`email`) VALUES (:username,:phone,:useremail)";
        $statement = $db->prepare($query);
        $statement->bindParam('username', $this->name);
        $statement->bindParam('phone', $this->phone);
        $statement->bindParam('useremail', $this->email);
        $statement->execute();
    }

    public function modificar(): void {
        $db = $this->db();
        $query = "UPDATE `prueba`.`users` SET `user_name` = :username, `phone` = :phone, `email` = :useremail WHERE `id_user` = :id";
        $statement = $db->prepare($query);
        $statement->bindParam('username', $this->name);
        $statement->bindParam('phone', $this->phone);
        $statement->bindParam('useremail', $this->email);
        $statement->bindParam('id', $this->id);
        $statement->execute();
    }

    public function eliminar(): void {
        $db = $this->db();
        $query = "DELETE FROM `prueba`.`users` WHERE `id_user` = :id";
        $statement = $db->prepare($query);
        $statement->bindParam('id', $this->id);
        $statement->execute();
    }
}
