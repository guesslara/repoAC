<?
	session_start();
	include("../../includes/txtApp.php");
	include("../../includes/config.inc.php");
	/*echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
	exit;*/
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		header("Location: ../../acceso.php");
		exit;
	}
	
	//se evalua la lonfgitud del no nomina
	$nomina=$_SESSION[$txtApp['session']['nominaUsuario']];
	/*$foto=strlen($_SESSION[$txtApp['session']['nominaUsuario']]);
	switch($foto){
		case 1:
			$foto="000".$nomina;
		break;
		case 2:
			$foto="00".$nomina;
		break;
		case 3:
			$foto="0".$nomina;
		break;
		default:
			$foto=$_SESSION['nomina_req'];
		break;
	}*/
	$foto=$nomina;
	//path completo de la imagen
	$pathImg=$dirImgPath.$foto.".jpg";
	//se comprueba si existe el archivo
	if (!file_exists($pathImg)){
	   $pathImg=$dirImgAlternativa;
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/funcionesProfile.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<title><?=$txtApp['appProfile']['tituloPagina'];?></title>
<style type="text/css">
/*interfaz del perfil de usuario*/
html,document,body{margin:0;height:100%; width:100%;position:absolute;}
#titulosPerfil{font-size:16px; color:#06F; margin:10px 0px 10px 5px; width:97%; text-align:right;}
#titulosPerfil  .enlacesPerfil{font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#06F; margin:10px; width:90%; text-align:right;}
#titulosPerfil  .enlacesPerfil:hover{ color:#0099FF; cursor:pointer;}
#contenedorImagen{height:260px; width:200px; border:1px solid #CCC; background:#FFF; text-align:center;}
#contenedorImagen .imagenPerfil{margin-top:9px;}
.tituloDetallePerfil{font-size:12px; margin-bottom:10px; margin-left:25px;}
.detalleTextoPerfil{font-size:10px; margin:5px 5px 10px 45px;font-weight:bold;}
#contenedorPerfil{
	width:99%;            
	text-align:left;
	margin:5px;            
	height:98.5%;;
	background:#FFF;
}
#col1Perfil{
	width:200px;
	float:left;
	font-size:0.75em;
	border:1px solid #CCC;
	background:#F0F0F0;
	padding:5px;
	height:97%;
	margin:2px;
}
#col2Perfil{
	width:310px;
	float:right;
	font-size:0.875em;
	margin-left:30px;
}
#col3Perfil{
	margin-left:217px;
	margin-right:330px;
	margin-top:5px;
	width:82%;
	font-size:0.75em;
	height:99%;
	border:1px solid #CCC;
}
#capaPanel{
	/*border:1px solid #FF0000;*/
	width:99%;
	height:99.5%;
	overflow:auto;
	margin:2px 5px 2px 0;
}
</style>
</head>

<body>
	<div id="contenedorPerfil">
    	<div id="col1Perfil">
        	<div id="titulosPerfil"><?=$txtApp['appProfile']['tituloModulo'];?></div>
            <hr color="#CCC" /><br />
        	<div id="contenedorImagen">
        		<div class="imagenPerfil"><img src="<?=$pathImg;?>" height="250" width="192" border="0" /></div>
        	</div>
            <br />
            <div id="titulosPerfil">
            	<div class="enlacesPerfil" onclick="verPerfil('<?=$_SESSION[$txtApp['session']['idUsuario']];?>')">Ver informaci&oacute;n &raquo;</div>
	        	<!--<div class="enlacesPerfil" onclick="cambiarImagen('<?=$_SESSION['id_usuario_req'];?>')">Cambiar mi Imagen</div>-->
				<div class="enlacesPerfil" onclick="cambiarPass('<?=$_SESSION[$txtApp['session']['idUsuario']];?>')">Cambiar contrase&ntilde;a &raquo;</div>
        	</div>
            <br /><hr color="#CCC" /><br />
            <div id="titulosPerfil">
            	<div class="enlacesPerfil" onclick="acercaDe()">Acerca de... &raquo;</div>
                <div class="enlacesPerfil">Versi&oacute;n 1.1.6 &raquo;</div>
        	</div>
        </div>
        <div id="col3Perfil">
	        <div id="capaPanel"><div style="padding-top:25%; text-align:center; font-size:14px; font-style:italic;">De click en una opci&oacute;n del lado derecho para continuar.</div></div>
        </div>
	</div>
</body>
</html>