<?
session_start();
require("aut_config.inc.php");
require("config.inc.php");
// chequear página que lo llama para devolver errores a dicha página.
$url = explode("?",$_SERVER['HTTP_REFERER']);
$pag_referida=$url[0];
$redir=$pag_referida;
// chequear si se llama directo al script.
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
// Chequeamos si se está autentificandose un usuario por medio del formulario
if (isset($_POST['user']) && isset($_POST['pass'])) {
	//print_r($_POST);
	$db_conexion=@mysql_connect($sql_host,$sql_usuario,$sql_pass) or die(header ("Location:$redir?error_login=0"));
	mysql_select_db("$db");
	// realizamos la consulta a la BD para chequear datos del Usuario.
	$usuario_consulta = mysql_query("SELECT * FROM $sql_tabla WHERE usuario='".$_POST['user']."'") or die(header ("Location:  $redir?error_login=1"));

 	// miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
 	if (mysql_num_rows($usuario_consulta) > 0) {
	    // eliminamos barras invertidas y dobles en sencillas
    	$login = stripslashes($_POST['user']);
    	// encriptamos el password en formato md5 irreversible.
    	$password = md5($_POST['pass']);

    	// almacenamos datos del Usuario en un array para empezar a chequear.
 		$usuario_datos = mysql_fetch_array($usuario_consulta);
  
    	// liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
    	mysql_free_result($usuario_consulta);
    	// cerramos la Base de dtos.
    	mysql_close($db_conexion);
    
    	// chequeamos el nombre del usuario otra vez contrastandolo con la BD
    	// esta vez sin barras invertidas, etc ...
    	// si no es correcto, salimos del script con error 4 y redireccionamos a la
    	// página de error.
    	if ($login != $usuario_datos['usuario']) {
       		header ("Location: $redir?error_login=4");
			exit;
		}

    	// si el password no es correcto ..
    	// salimos del script con error 3 y redireccinamos hacia la página de error
    	if ($password != $usuario_datos['pass']) {
        	header ("Location: $redir?error_login=3");
	    	exit;
		}

    	// Paranoia: destruimos las variables login y password usadas
    	unset($login);
   		unset ($password);

    	// En este punto, el usuario ya esta validado.
    	// Grabamos los datos del usuario en una sesion.
    
     	// le damos un mobre a la sesion.
    	session_name($usuarios_sesion);
     	// incia sessiones
    	session_start();

	    // Paranoia: decimos al navegador que no "cachee" esta página.
    	session_cache_limiter('nocache,private');
    
    	// Asignamos variables de sesión con datos del Usuario para el uso en el
    	// resto de páginas autentificadas.

    	// definimos usuarios_id como IDentificador del usuario en nuestra BD de usuarios
    	$_SESSION['usuario_id']=$usuario_datos['ID'];
    
    	// definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
    	$_SESSION['usuario_nivel']=$usuario_datos['nivel_acceso'];
    
    	//definimos usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
    	$_SESSION['usuario_login']=$usuario_datos['usuario'];

    	//definimos usuario_password con el password del usuario de la sesión actual (formato md5 encriptado)
    	$_SESSION['usuario_password']=$usuario_datos['pass'];
    	$_SESSION['usuario']=$usuario_datos['usuario'];
		$_SESSION['sistema']="inventarios_iq";
		$_SESSION['array']=array();
		$_SESSION['array2']=array();

    	// Hacemos una llamada a si mismo (scritp) para que queden disponibles
    	// las variables de session en el array asociado $HTTP_...
    	$pag=$_SERVER['PHP_SELF'];
    	header ("Location: $pag?");
    	exit;
	} else {
      	// si no esta el nombre de usuario en la BD o el password ..
      	// se devuelve a pagina q lo llamo con error
      	header ("Location: $redir?error_login=2");
      	exit;
	}
} else {
	// -------- Chequear sesión existe -------
	// usamos la sesion de nombre definido.
	session_name($usuarios_sesion);
	// Iniciamos el uso de sesiones
	session_start();

	// Chequeamos si estan creadas las variables de sesión de identificación del usuario,
	// El caso mas comun es el de una vez "matado" la sesion se intenta volver hacia atras
	// con el navegador.

	if (!isset($_SESSION['usuario_login']) && !isset($_SESSION['usuario_password'])){
		// Borramos la sesion creada por el inicio de session anterior
		session_destroy();
		die ("Error cod.: 2 - Acceso incorrecto! ");
		exit;
	}
}
?>
