<?php
include_once("DAO/usuario.php");

/*
Clase Control para la gestion de Peticiones dependiendo de cada modulo solicitado
usualmente se separa un controlador par acada clase pero para hechos de accesibilidad
a los incluyo en esta clase 

*/

class Control {	

	private Usuario $usuarios;
	
	/**
	 * @param Usuario|null $usuario Injected Usuario DAO. Creates a default one if null.
	 */
	public function __construct(?Usuario $usuario = null) {
		$this->usuarios = $usuario ?? new Usuario();
	}

	public function allUsuarios(){
		$response = null;
		try{
			$response = $this->usuarios->consultar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return $response;
	}

	public function altaUsuarios($arg){
		try{
			$this->usuarios->setName($arg['name']);
			$this->usuarios->setPhone($arg['phone']);
			$this->usuarios->setEmail($arg['email']);
			$this->usuarios->agregar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return json_encode($this->allUsuarios(), true);
	}

	public function editarUsuarios($arg){
		try{
			$this->usuarios->setName($arg['name']);
			$this->usuarios->setPhone($arg['phone']);
			$this->usuarios->setEmail($arg['email']);
			$this->usuarios->setIdUser($arg['id_user']);
			$this->usuarios->modificar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return json_encode($this->allUsuarios(), true);
	}

	public function eliminaUsuario($arg){
		try{
			$this->usuarios->setIdUser($arg['id_user']);
			$this->usuarios->eliminar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return json_encode($this->allUsuarios(), true);
	}
}
