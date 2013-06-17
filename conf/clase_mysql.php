<?php
class Servidor_Base_Datos
{
	private $servidor;
	private $usuario;
	private $pass;
	private $base_datos;
	private $descriptor;
	private $resultado;
	function __construct($servidor,$usuario,$pass,$base_datos)
	{	
		$this->servidor = $servidor;
		$this->usuario = $usuario;
		$this->pass = $pass;
		$this->base_datos = $base_datos;
		$this->conectar_base_datos();		
	}
	function __destructor()
	{
		mysql_close($this->conectar_base_datos());
	}		
	public function conectar_base_datos()
	{

		if ($this->descriptor = mysql_connect($this->servidor,$this->usuario,$this->pass) and mysql_select_db($this->base_datos,$this->descriptor)) {
			return true;
		}
		else{
			return false;
		}
	}
	public function consulta($consulta)
	{
		if ($this->resultado = mysql_query($consulta,$this->descriptor)) {
			return true;
		} else {
			return false;
		}		
	}
	public function extraer_registro()
	{
		if ($fila = mysql_fetch_array($this->resultado,MYSQL_ASSOC)) {
			return $fila;
		} else {
			return false;
		}
	}	
	public function n_registros()
	{
		return mysql_num_rows($this->resultado);
	}
}
?>