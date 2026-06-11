<?php

declare(strict_types=1);

include_once("DAO/usuario.php");

class Control {	

	private Usuario $usuarios;
	
	public function __construct(?Usuario $usuario = null) {
		$this->usuarios = $usuario ?? new Usuario();
	}

	public function allUsuarios(): ?array {
		$response = null;
		try{
			$response = $this->usuarios->consultar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return $response;
	}

	public function altaUsuarios(array $arg): string {
		try{
			$this->usuarios->setName($arg['name']);
			$this->usuarios->setPhone($arg['phone']);
			$this->usuarios->setEmail($arg['email']);
			$this->usuarios->agregar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return json_encode($this->allUsuarios(), JSON_PRETTY_PRINT);
	}

	public function editarUsuarios(array $arg): string {
		try{
			$this->usuarios->setName($arg['name']);
			$this->usuarios->setPhone($arg['phone']);
			$this->usuarios->setEmail($arg['email']);
			$this->usuarios->setIdUser($arg['id_user']);
			$this->usuarios->modificar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return json_encode($this->allUsuarios(), JSON_PRETTY_PRINT);
	}

	public function eliminaUsuario(array $arg): string {
		try{
			$this->usuarios->setIdUser($arg['id_user']);
			$this->usuarios->eliminar();
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		return json_encode($this->allUsuarios(), JSON_PRETTY_PRINT);
	}
}
