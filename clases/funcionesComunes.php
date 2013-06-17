<?php
	/*
	Clase con algunas funciones comunes en la aplicacion necesarias y que son repetitivas
	Autor:Gerardo Lara Perez
	*/
	include("conexion/conexion.php");
	class funcionesComunes{
		
		public function buscarImeiVsModelo($imei,$modelo){
			$this->conexionBd();
			$sqlImei="select * from equipos where imei='".$imei."' and id_modelo ='".$modelo."'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		public function buscarImeiActualizadoNoEmpacado($imei){
			$this->conexionBd();
			$sqlImei="select * from equipos where (imei='".$imei."' and statusEmpaque ='EMPACADO') and status='Empaque'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		public function buscarImeiScrap($imei){
			$this->conexionBd();
			$sqlImei="select * from equipos where imei='".$imei."' and status='SCRAP'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		public function buscarImeiEmpacado($imei){
			$this->conexionBd();
			$sqlImei="select * from empaque_items where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		/*public function buscarImeiEnviado($imei){
			$this->conexionBd();
			$sqlImei="select status from equipos where imei='".$imei."' and status IN ('ENVIADO','RETENCION')";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion*/
		
		public function buscarImeiEnviado($imei){
			$this->conexionBd();
			$sqlImei="select * from equipos_enviados where (imei='".$imei."' and status IN ('ENVIADO','RETENCION')) and activo='0'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		public function buscarSerialEnviado($serial){
			$this->conexionBd();
			$sqlImei="select * from equipos_enviados where (serial='".$serial."' and status IN ('ENVIADO','RETENCION')) and activo='0'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		public function buscarSimEnviada($sim){
			$this->conexionBd();
			$sqlImei="select * from equipos_enviados where (sim='".$sim."' and status IN ('ENVIADO','RETENCION')) and activo='0'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			return $noRegs;
		}//fin funcion
		
		public function buscarImei($imei){
			$this->conexionBd();
			$sqlImei="select * from equipos where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conexion);
			$noRegs=mysql_num_rows($resImei);
			if($noRegs==0){
				//se procede a buscar en los equipos enviados
				$sqlBuscaEnviadoSiExiste="select * from equipos_enviados where (imei='".$imei."' and status IN ('ENVIADO','RETENCION')) and activo='0'";
				$resBuscaEnviadoSiExiste=mysql_query($sqlBuscaEnviadoSiExiste,$this->conexion);
				$noRegs=mysql_num_rows($resBuscaEnviadoSiExiste);
			}
			return $noRegs;
		}//fin funcion
		
		public function buscarSim($sim){
			$this->conexionBd();
			$sqlSim="select * from equipos where sim='".$sim."' and status='ENVIADO'";
			$resSim=mysql_query($sqlSim,$this->conexion);
			$noRegs=mysql_num_rows($resSim);
			return $noRegs;
		}
		
		public function buscarNoEnviar($imei){
			$this->conexionBd();
			$sqlNoEnviar="select * from equipos_no_enviar where imei='".$imei."'";
			$resNoEnviar=mysql_query($sqlNoEnviar,$this->conexion);
			$noRegs=mysql_num_rows($resNoEnviar);
			return $noRegs;
		}//fin funcion
		
		public function buscarSerieNoEnviar($serial){
			$this->conexionBd();
			$sqlSerieNoEnviar="select * from equipos_no_enviar where serial='".$serial."'";
			$resSerieNoEnviar=mysql_query($sqlSerieNoEnviar,$this->conexion);
			$noRegs=mysql_num_rows($resSerieNoEnviar);
			return $noRegs;
		}//fin funcion
		
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
				echo "<p>Detalle guardado</p>";
			}else{
				echo "<p>Error al guardar el detalle</p>";
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
?>