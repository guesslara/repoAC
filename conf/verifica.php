<?
	$usuarios_sesion="usuarios";
	$url=explode("?",$_SERVER['HTTP_REFERER']);
	$pagRef=$url[0];
	//se incluye configuracion de la clase
	include("../clases/adob/adodb.inc.php");
	$adodb = ADONewConnection('mysql');
	//archivo de configuracion
	include("config.inc.php");	
	if(!$adodb->Connect($host,$usuario,$pass,$db)){
		header('Location:'.$pagRef.'?error=0');
		exit;
	}else{
		//se pudo realizar la conexion a la base de datos
		$user=$_POST['user'];
		$pass=$_POST['pass'];
		if (($user!="") && ($pass!="")){
			$sql="SELECT ID,usuario,pass,nombre,apaterno,nivel_acceso FROM userdbcompras WHERE usuario='".$user."'";
			$rs = $adodb->Execute($sql); 
			//echo 'Se ha extraido '.$rs->RecordCount().' registro.';
			if($rs->RecordCount()!= 0){
				//se encontro al usuario en la base de datos
				/***********extraemos los datos del usuario**********/
				while($fila=$rs->FetchNextObject()){
					$id_usuario=$fila->ID;
					$usuario=$fila->USUARIO;
					$password_t=$fila->PASS;
					$nombre=$fila->NOMBRE;
					$apaterno=$fila->APATERNO;
					$nivel=$fila->NIVEL_ACCESO;
				}
				/****************************************************/
				// eliminamos barras invertidas y dobles en sencillas
				$login = stripslashes($_POST['user']);
				//se transforma en mayusculas para comparar con los registros en la BD
				$login=strtoupper($login);
				$nombre_usuario=$login;
				// encriptamos el password en formato md5 irreversible.
				$password = md5($_POST['pass']);
				/*****************************************************/
				// chequeamos el nombre del usuario otra vez contrastandolo con la BD
				// esta vez sin barras invertidas, etc ...
				// si no es correcto, salimos del script con error 4 y redireccionamos a la
				// p᧩na de error.
				if ($login != $nombre_usuario) 
				{
					header("Location:".$pagRef."?error=2");
					exit;
				}
				if ($password != $password_t)
				{
					header("Location:".$pagRef."?error=2");
					exit;
				}
				//enviamos una cookie
				setcookie("usuario","$nombre.$apaterno",time()+43200);
				setcookie("nivel","$nivel",time()+43200);
				// incia sessiones
				session_start();
				session_name("usuario");
				session_register("usuario");
				// Paranoia: decimos al navegador que no "cachee" esta p᧩na.
				session_cache_limiter('nocache,private');
				// Asignamos variables de sesi󮠣on datos del Usuario para el uso en el
				// resto de p᧩nas autentificadas.
				// definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
				$_SESSION['id_usuario']=$id_usuario;
				$_SESSION['usuario_nivel']=$nivel;
				//definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
				//$_SESSION['usuario_login']=$usuario_datos['usuario'];
				$_SESSION['usuario_login']=$usuario;
				//definimos usuario_password con el password del usuario de la sesi󮠡ctual (formato md5 encriptado)
				$_SESSION['usuario_password']=$password;
				//otras variables
				$_SESSION['nombre']=$nombre;
				$_SESSION['apellido']=$apaterno;				
				// Hacemos una llamada a si mismo (scritp) para que queden disponibles
				//si los datos han sido correctos se redirecciona a la pagina principal
				header('Location:../modulos/main.php?='.$SID.'');
			}else{
				/*si por alguna razon no se encuentra en la BD se redirecciona con un error*/
				header("Location:".$pagRef."?error=2");
				//exit;
			}
		}else{
			// -------- Chequear sesion si existe -------
			// usamos la sesion de nombre definido.
			session_name($usuarios_sesion);
			// Iniciamos el uso de sesiones
			session_start();
			// Chequeamos si estan creadas las variables de sesi󮠤e identificaci󮠤el usuario,
			// El caso mas comun es el de una vez "matado" la sesion se intenta volver hacia atras
			// con el navegador.
			if (!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password']))
			{
				// Borramos la sesion creada por el inicio de session anterior
				session_destroy();
				die ("Error cod.: 2 - Acceso incorrecto!");
				exit;
			}
		}
	}//fin if conexion	
?>