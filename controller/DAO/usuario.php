<?php


/*
Crhistian Heredia
Clase Usuarios donde se definen los atributos y las funciones para cada accion del usuario
hereda de BD ya que esta clase requiere de las funciones de conectar para el envio de los datos
corespondoentes para que sea almancenados en la respectiva tabla de la base de datos
*/


require_once("BD.php");

class Usuario extends DB{

	private $id = null; 
	private $nombre = null;
	private $email = null;
	private $phone = null;


	function __construct(){
		parent::__construct();
	}

	public function setIdUser($arg){
		$this->id = $arg;
	}

	public function setEmail($arg){
		$this->email = $arg;
	}
	
	public function setName($arg){
		$this->name =$arg;
	}

	public function setPhone($arg){
		$this->phone = $arg;
	}
	
	public function getIdUser(){
		return intval($this->id);
	}

	public function getEmail(){
		return parse_str($this->email);
	}
		
	public function getName(){
		return parse_str($this->nombre);
	}

	public function getPhone(){
		return $this->phone;
	}
	
	public function consultar(){
		$open = $this->openDB();

		$query = "SELECT * FROM users";
		$statement = $open->prepare($query);
		$statement->execute();
		$response = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $response;
	}

	public function agregar(){
		$open = $this->openDB();
		$query = "INSERT INTO `prueba`.`users` (`user_name`,`phone`,`email`) VALUES (:username,:phone,:useremail)";
		$statement = $open->prepare($query);
		$statement->bindParam('username', $this->name);
        $statement->bindParam('phone', $this->phone);
		$statement->bindParam('useremail', $this->email);
		$statement->execute();
		$this->closeDB($open);
	}

	public function modificar(){
		$open = $this->openDB();
		$query = "UPDATE `prueba`.`users` SET `user_name` = :username, `phone` = :phone, `email` = :useremail WHERE `id_user` = :id";
		$statement = $open->prepare($query);
		$statement->bindParam('username', $this->name);
        $statement->bindParam('phone', $this->phone);
		$statement->bindParam('useremail', $this->email);
		$statement->bindParam('id', $this->id);
		$statement->execute();
		$this->closeDB($open);
	}

	public function eliminar(){
		$open = $this->openDB();
		$query = "DELETE FROM `prueba`.`users` WHERE `id_user` = :id ";
		$statement = $open->prepare($query);
		$statement->bindParam('id', $this->id);
		$statement->execute();
		$this->closeDB($open);
	}
}

?>