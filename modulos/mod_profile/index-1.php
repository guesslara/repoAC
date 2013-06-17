<?
	session_start();
	if(!isset($_SESSION['nomina_req'])){
		header("Location: ../../acceso.php");
		exit;
	}
	include("../../includes/txtApp.php");
	include("../../includes/config.inc.php");
	//se evalua la lonfgitud del no nomina
	$foto=strlen($_SESSION['nomina_req']);
	switch($foto){
		case 1:
			$foto="000".$foto;
		break;
		case 2:
			$foto="00".$foto;
		break;
		case 3:
			$foto="0".$foto;
		break;
		default:
			$foto=$_SESSION['nomina_req'];
		break;
	}
	//path completo de la imagen
	$pathImg=$dirImgPath.$foto.".jpg";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../mod_requisiciones_3x/css/estilos.css" />
<script type="text/javascript" src="js/funcionesProfile.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<title><?=$txtApp['appProfile']['tituloPagina'];?></title>
<style type="text/css">

</style>
</head>

<body>
	<div id="contenedorPerfil">
    	<div id="col1Perfil">
        	<div id="titulosPerfil"><?=$txtApp['appProfile']['tituloModulo'];?></div>
            <hr color="#CCC" /><br />
        	<div id="contenedorImagen">
        		<div class="imagenPerfil"><img src="<?=$pathImg;?>" height="143" width="192" border="0" /></div>
        	</div>
            <hr color="#CCC" /><br />
            <div id="titulosPerfil">
            	<div class="enlacesPerfil" onclick="verPerfil('<?=$_SESSION['id_usuario_req'];?>')">Ver informaci&oacute;n</div>
	        	<!--<div class="enlacesPerfil" onclick="cambiarImagen('<?=$_SESSION['id_usuario_req'];?>')">Cambiar mi Imagen</div>-->
				<div class="enlacesPerfil" onclick="cambiarPass('<?=$_SESSION['id_usuario_req'];?>')">Cambiar mi Contrase&ntilde;a</div>
        	</div>
            <br /><hr color="#CCC" /><br />
        </div>
        <div id="col3Perfil">
	        <div id="capaPanel">Contenido</div>
        </div>
	</div>
</body>
</html>