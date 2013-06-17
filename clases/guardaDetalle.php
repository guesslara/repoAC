<?php
	/*
	Clase para poder guardar el detalle de las operaciones en el sistema
	*/
	include("conexion/conexion.php");
	
	class guardaDetalle{
		
		function __construct(){
			
		}//fin construct
		
		public function guardaDetalleSistema($proceso,$usuarioSistema,$imei){
			$this->conexionBd();
			//se extrae el id del radio
			$sqlRadio="SELECT id_radio FROM equipos WHERE imei='".$imei."'";
			$resRadio=mysql_query($sqlRadio,$this->conexion);
			$rowRadio=mysql_fetch_array($resRadio);
			$idRadio=$rowRadio['id_radio'];
			//se hace la insercion en la tabla del detalle de ingenieria
			$sqlDetalle="INSERT INTO detalle_ing (id_proc,id_personal,id_radio,f_registro,h_registro) VALUES ('".$proceso."','".$usuarioSistema."','".$idRadio."','".date("Y-m-d")."','".date("H:i:s")."')";
			$resDetalle=@mysql_query($sqlDetalle,$this->conexion)or die(mysql_error());
			if($resDetalle){
				echo "<br><br>Detalle guardado<br>";
			}else{
				echo "<br><br>Error al guardar el detalle<br>";
			}
			return $resDetalle;
		}
				
		private function conexionBd(){
			try{
				include("../../includes/config.inc.php");
				$conn = new Conexion();
				$this->conexion = $conn->getConexion($host,$usuario,$pass,$db);
				
			}catch(Exception $e){
				echo "Ha ocurrido un error en la aplicaci&oacute;n.";
			}

		}//fin de la conexion		
		
	}//fin de la clase
	
	
	/*$objPermisos = new permisosUsuario();	
	echo $menuUsuario=$objPermisos->construyeMenu(1);*/
?>