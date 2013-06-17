<?
	session_start();
	/*if(!isset($_SESSION['nombre_req'])){
		header("Location: ../../acceso.php");
		exit;
	}*/
	include("../../includes/txtApp.php");
	$img_profileUser="../../../images/ui/img_profiles/".$_SESSION['img_profileS'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/estilos.css" />
<script type="text/javascript" src="js/funcionesProfile.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<title><?=$txtApp['appProfile']['tituloPagina'];?></title>
<style type="text/css">
.enlacesPerfil{font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#06F; margin:10px; width:90%; text-align:right;}
.enlacesPerfil:hover{ color:#0099FF; cursor:pointer;}
.tituloDetallePerfil{font-size:14px; font-weight:bold; margin-bottom:10px; margin-left:25px;}
.detalleTextoPerfil{font-size:12px; margin:5px 5px 10px 45px;}
</style>
</head>

<body>
<div style="margin:5px; background:#FFF; height:98%; width:99%;">
	<div style="position: absolute; margin:10px; left: 0; top: 0; width: 20%; height: 96%; background:#F0F0F0;">
    	<div style="font-family:Verdana, Geneva, sans-serif; font-size:16px; color:#06F; margin:10px; width:90%; text-align:right;"><?=$txtApp['appProfile']['tituloModulo'];?></div>
        <hr color="#CCC" /><br />
        <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:#06F; height:90px; width:97%;">
        	<div style="height:64px; width:64px; border:1px solid #CCC; text-align:center; float:right; margin:10px;"><img src="../../img/Man2-64.png" border="0" /></div>
        </div>
        <div class="enlacesPerfil" onclick="verPerfil('<?=$_SESSION['id_usuario_req'];?>')">Ver informaci&oacute;n</div>
        <!--<div class="enlacesPerfil" onclick="cambiarImagen('<?=$_SESSION['id_usuario_req'];?>')">Cambiar mi Imagen</div>-->
		<div class="enlacesPerfil" onclick="cambiarPass('<?=$_SESSION['id_usuario_req'];?>')">Cambiar mi Contraseña</div>
        <br /><hr color="#CCC" /><br />
        <!--<div class="enlacesPerfil">Avanzadas</div>-->
    </div>
    <div id="detallePerfil" style="position: absolute; margin:10px; left: 20.5%; top: 0; width: 60%; height: 96%; background:#FFF; border:1px solid #F0F0F0; font-size:12px; overflow:auto;"></div>
</div>
</body>
</html>