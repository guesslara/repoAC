<?php
	//echo "<br><p style='text-align:center;'>El sistema de inventarios IQ, estara detenido por mantenimiento de la Base de Datos. <br>Para cualquier duda y aclaracion consulte su administrador del sistema.</p>";
	//exit;	
	//configuracion de usuario MYSQL para primer base de datos
        include("../../includes/config.inc.php");
	$host=$host;
	$usuario=$usuario;
	$pass=$pass;
	
	$sql_tabla=$tabla_usuarios;
	$usuarios_sesion="autentificator";	
	
	//base de datos y datos del almacen...
	$sql_db=$db;
	$dbAlmacen=$db;
	$idalm=1;
	$nalm0="General";
	$calm0="a_1_General";
	$cexi0="exist_1";
	$ctra0="trans_1";
	
	$dbcompras="2013_iqe_com"; // Base de Datos de Compras...
	
	// ============================================================================
	$link=@mysql_connect($host,$usuario,$pass) or die("No se pudo conectar al servidor.<br>");
	if($link)
		mysql_select_db($sql_db);
	else
		echo "Error al seleccionar la Base de Datos";	
?>
