<?php
	/*
	 *Clase para la verificacion del usuario en la base de datos t variables de sesion en el sistema
	 *Fecha de creacion: 10 - Junio - 2010
	 *Autor: Gerardo Lara
	 *-------------------------------------------------------------------------------------------------------
	 *Modificacion para incluir las variables con un archivo de configuracion externo
	 *Fecha: 6- Noviembre - 2012
	 *Autor: Gerardo Lara
	*/
	include("../../clases/conexion/conexion.php");
	
	class modeloLogin{
		
		function verificaInfo($usuarioEntrante,$passEntrante){						
			include("../../includes/conectarbase.php");
			include("../../includes/txtApp.php");
			$sqlVerifica="SELECT * FROM $tabla_usuarios WHERE usuario='".$usuarioEntrante."'";
			$resVerifica=mysql_query($sqlVerifica,$this->conexionBd());
			$resultados=mysql_num_rows($resVerifica);
			if($resultados !=0){
				$rowVerifica=mysql_fetch_array($resVerifica);
				$id_usuario=$rowVerifica['ID'];
				$usuario=$rowVerifica['usuario'];
				$password_t=$rowVerifica['pass'];
				$nombre=$rowVerifica['nombre'];
				$apaterno=$rowVerifica['apaterno'];
				$nivel=$rowVerifica['nivel_acceso'];
				$colocaPass=$rowVerifica['cambiarPass'];
				$sexo=$rowVerifica['sexo'];
				$nomina=$rowVerifica['nomina'];				
				$password = md5($passEntrante);				
				if ($usuarioEntrante != $usuario){
					header("Location:index.php?error=0");
					exit;
				}				
				if ($password != $password_t){
					header("Location:index.php?error=0");
					exit;
				}				
				// incia sessiones
				session_start();				
				session_name($txtApp['session']['name']);
				//session_register($txtApp['session']['register']);				
				session_cache_limiter('nocache,private');// definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios				
				$_SESSION[$txtApp['session']['nivelUsuario']]=$nivel;				
				$_SESSION[$txtApp['session']['loginUsuario']]=$usuario;				
				$_SESSION[$txtApp['session']['passwordUsuario']]=$password;				
				$_SESSION[$txtApp['session']['idUsuario']]=$id_usuario;
				$_SESSION[$txtApp['session']['nombreUsuario']]=$nombre;
				$_SESSION[$txtApp['session']['apellidoUsuario']]=$apaterno;
				$_SESSION[$txtApp['session']['origenSistemaUsuario']]=$txtApp['session']['origenSistemaUsuarioNombre'];
				$_SESSION[$txtApp['session']['cambiarPassUsuario']]=$colocaPass;
				$_SESSION[$txtApp['session']['sexoUsuario']]=$sexo;
				$_SESSION[$txtApp['session']['nominaUsuario']]=$nomina;				
				//header('Location:../../inicio.php?='.$SID.'');
				header('Location:../../modulos/main-4.php');
				//header('Location:../../modulos/mod_inicio/index.php?='.$SID.'');
				exit;
			}else{
				session_start();
				session_destroy();
				echo "Acceso no Autorizado";
				exit;
			}
		}
		
		private function conexionBd(){
			include("../../includes/config.inc.php");
			$conn = new Conexion();
			$conexion = $conn->getConexion($host,$usuario,$pass,$db);
			return $conexion;
		}
	}//fin de la clase
?>