<?php
include_once ('campo.php');

/*
    Clase BD encargada de la comunicacion de la base de datos y clases del sistema
*/

Class DB {
    private $host = host;
    private $database = database;
    private $user = user;
    private $password = password;
    
    function __construct(){
        
    }

	public function openDB(){
        try{
            $conexion = new pdo('mysql:host='.$this->host.';dbname='.$this->database, $this->user, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        }catch(PDOException $e){
            $vec = array('err'=>true, 'message'=> 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: '.$e->getMessage());
            return $vec;
            exit;
        }
	}

    public function closeDB($db){
        return $db = null;
    }
}

?>