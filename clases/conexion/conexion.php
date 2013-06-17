<?php
	/*
	*
	*/
	class Conexion {
			
		public function getConexion($host,$usuario,$pass,$db) {
			$this->CONST_SERVER=$host;
			$this->CONST_USER=$usuario;
			$this->CONST_PASS=$pass;
			$this->CONST_DB=$db;
			try {
				$link = @mysql_connect($this->CONST_SERVER, $this->CONST_USER, $this->CONST_PASS) or die('Error de Conexion');
				$base_sel=@mysql_select_db($this->CONST_DB,$link) or die('Error al Seleccionar la Base de datos');
				$db=$link;
			} catch(Exception $e) {
				echo "Error en la aplicacion: \n";
				$db = false;
			}
			return $db;
		}		
	}
	
	//pruebas de la clase
	/*$objConexion=new Conexion();
	$link=$objConexion->getConexion('localhost','root','xampp','db_iqe_nextel');
	if($link)
		echo "se conecto correctamente";
	else
		echo "error al conetar con la base de datos";*/
?>