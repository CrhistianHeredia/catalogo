<?php
include_once("DAO/usuario.php");

/*
Clase Control para la gestion de Peticiones dependiendo de cada modulo solicitado
usualmente se separa un controlador par acada clase pero para hechos de accesibilidad
a los incluyo en esta clase 

*/

class Control {	

	private $usuarios = null;
	
	function __construct(){
		$this->usuarios = new Usuario();
	}

	public function allUsuarios(){
		$response = null;
		try{
			$response = $this->usuarios->consultar();
		}catch(ModelException $e){
			echo "Error: ".$e;
		}
		return $response;
	}

	public function altaUsuarios($arg){
		try{
			$this->usuarios->setName($arg['name']);
			$this->usuarios->setPhone($arg['phone']);
			$this->usuarios->setEmail($arg['email']);
			$ejecutar = $this->usuarios->agregar();
		}catch(ModelException $e){
			echo "Error: ".$e;
		}
		return json_encode($this->allUsuarios(), true);
	}

	public function editarUsuarios($arg){
		try{
			$this->usuarios->setName($arg['name']);
			$this->usuarios->setPhone($arg['phone']);
			$this->usuarios->setEmail($arg['email']);
			$this->usuarios->setIdUser($arg['id_user']);
			$ejecutar = $this->usuarios->modificar();
		}catch(ModelException $e){
			echo "Error: ".$e;
		}
		return json_encode($this->allUsuarios(), true);
	}

	public function eliminaUsuario($arg){
		try{
			$this->usuarios->setIdUser($arg['id_user']);
			$ejecutar = $this->usuarios->eliminar();
		}catch(ModelException $e){
			echo "Error: ".$e;
		}
		return json_encode($this->allUsuarios(), true);
	}
}

/*
	Script para validad las peticiones de la interface o sea las vistas de la aplicacion para 
	posteriormente gestionar esa peticion al servidor dependiendo del modulo solicitado

*/

if(isset($_REQUEST['request']) && !empty($_REQUEST['request']) && isset($_REQUEST['arg']) && !empty($_REQUEST['arg'])){
	$request = $_REQUEST['request'];
	$arg = json_decode($_REQUEST['arg'],true);
	$control = new Control();
	echo $control->$request($arg);
}

?>