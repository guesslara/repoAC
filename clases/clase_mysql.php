<?php
	//clase para conectar hacia una base de datos
	class DB_mysql{
	
		/*variables de conexion*/
		var $BaseDatos;
		var $Servidor;
		var $Usuario;
		var $Clave;
		
		/*identificador de conexion y consulta*/
		var $Conexion_ID=0;
		var $Consulta_ID=0;
		
		/*numero de error y texto error*/
		var $Errno=0;
		var $Error="";
		
		/*Metodo constructor*/
		function DB_mysql($bd="", $host="localhost", $user="nobody",$pass=""){
			$this->BaseDatos=$bd;
			$this->Servidor=$host;
			$this->Usuario=$user;
			$this->Clave=$pass;
		}
		
		/*Conexion a la base de datos*/
		function conectar($bd,$host,$user,$pass){
			if($bd != ""){
				$this->BaseDatos=$bd;
			}
			if($host != ""){
				$this->Servidor=$host;
			}
			if($user != ""){
				$this->Usuario=$user;
			}
			if($pass != ""){
				$this->Clave=$pass;
			}
			
			//conectamos al servidor
			$this->Conexion_ID=mysql_connect($this->Servidor,$this->Usuario,$this->Clave);
			if(!$this->Conexion_ID){
				$this->Error="Ha fallado la Conexión.";
				return 0;
			}
			
			//seleccionamos la base de datos
			if(!@mysql_select_db($this->BaseDatos,$this->Conexion_ID)){
				$this->Error="Imposible abrir ".$this->BaseDatos;
				return 0;
			}
			
			//Si tiene exito la conexion, devuelve el identificador de la conexion, sino devuelve 0
			return $this->Conexion_ID;
		}//fin funcion
		
		/*Ejecuta una consulta*/
		function consulta($sql = ""){
			if($sql == ""){
				$this->Error="No ha especificado una consulta SQL";
				return 0;
			}
			
			//ejecutamos la consulta
			$this->Consulta_ID=@mysql_query($sql,$this->Conexion_ID);
			
			if(!$this->Consulta_ID){
				$this->Errno=mysql_errno();
				$this->Error=mysql_error();
			}
			/*Si se tiene exito en la consulta devuelve el identificador de la consulta sino devuelve 0*/
			return $this->Consulta_ID;
		}//fin funcion
		
		/*Devuelve el numero de campos afectados en la base de datos*/
		function regsAfectados(){
			return mysql_affected_rows();
		}
		/*Devuelve el numero de campos de una consulta*/
		function numregistros(){
			return mysql_num_rows($this->Consulta_ID);
		}//fin funcion
		/*Devuelve el array de un solo elemento*/
		function registroUnico(){
			return mysql_fetch_array($this->Consulta_ID);
		}
		/*Devuelve el nombre de un campo de una consulta*/
		function nombrecampo($numcampo){
			return mysql_field_name($this->Consulta_ID,$numcampo);
		}//fin funcion
	
		/*Devuelve el array de resultados*/
		function registrosConsulta(){
			return $this->Consulta_ID;
		}
	
		/*Muestra los datos de una consulta*/
		function verconsulta(){
			echo "<table border=1>\n<tr>";
			
			//mostramos los nombres de los campos
			for($i=0;$i<$this->numregistros();$i++){
				echo "<td><b>".$this->nombrecampo($i)."</b></td>\n";
			}
			echo "</tr>\n";
			
			while($row=mysql_fetch_row($this->Consulta_ID)){
				echo "<tr> \n";
				for($i=0;$i<$this->numregistros();$i++){
					echo "<td>".$row[$i]."&nbsp;</td>\n";
				}
				echo "</tr>\n";
			}
		}//fin funcion
	
	}//fin de la clase
?>