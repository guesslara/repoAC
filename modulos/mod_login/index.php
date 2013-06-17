<?
	include("../../includes/txtApp.php");
	include("../../clases/about.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Acceso Intranet</title>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/funcionesLogin.js"></script>
<script> 
	$(document).ready(function(){
		document.getElementById("txtUsuario").focus();
	});
</script>
<style type="text/css">
html,body{height:100%; margin:0px; background:#FFF; overflow:hidden; font-family:Verdana, Geneva, sans-serif;}
#contenedor{height:100%; width:100%; border:1px solid #CCC; background:#FFF;background:url(../../img/default1.jpg); }/**/
#logIn{border:1px solid #F1F1F1;background-color:#F1F1F1; background:url(../../img/desv.png) repeat;height:100px;width:400px;position:absolute;left:50%;top:50%;margin-left:-200px;	margin-top:-50px;	z-index:3;	}
#msgLogIn{border-top:1px solid #F1F1F1;border-left:1px solid #F1F1F1;border-right:1px solid #F1F1F1;background-color:#F1F1F1;background:url(../../img/desv.png) repeat;height:30px;width:400px;position:absolute;left:50%;top:45%;margin-left:-201px;margin-top:-82px;text-align:center;padding-top:5px; font-weight:bold; font-size:14px;}
#imgLogIn{width:80px; height:80px; float:left;}
#accesoLogIn{width:76%; height:80px; float:left; margin-left:5px; font-size:10px;}
#datosLogIn{margin:5px 10px 10px 15px;}
#boton{width:100px; background:#F1F1F1; background:url(../../img/desv.png);}
#contenedorLogIn{height:100%; overflow:hidden; margin:10px;}
#divPassword{float:left; width:150px;}
#divBoton{float:left; width:80px; margin-left:10px;}
#pieLogIn{position:absolute;border:1px solid #F1F1F1;background-color:#FFF;background:url(../../img/desv.png);height:50px;width:100%;position:absolute;left:0%;top:100%;margin-left:auto;margin-top:-50px;z-index:20;font-size:12px; font-weight:bold;}
#infoPieAbout{color:#000;float:left; margin:20px 5px 5px 5px; font-style:normal;font-weight:bold;}
#infoPieLogIn{color:#00;float:right; margin:20px 5px 5px 5px; font-style:italic;font-weight:bold;}
#infoApp{float:left; color:#F00; margin:20px 5px 5px 5px;}
#aboutApp{position:absolute;bottom:50px;border:1px solid #F1F1F1; background:#CCC; height:20%; width:30%; opacity:.5;}
#msgCargador{border:1px solid #CCC;background-color:#FFF;height:50px;width:50px;position:absolute;left:50%;top:50%;margin-left:-25px;margin-top:-25px;z-index:4;}
#mostrarActualizaciones{height:20px; padding:5px;background:url(../../img/desv.png);font-size:12px;text-align:right;}
</style>
</head>

<body>
<div id="contenedor">	
	<div id="mostrarActualizaciones"><a href="../mod_controlCambios/index.php" target="_blank" title="Mostrar Actualizaciones" style="text-decoration:none;color:#000;">Mostrar Actualizaciones</a></div>
    <div id="logIn">
    	<form name="frmAccesoIntranet" id="frmAccesoIntranet" method="post" action="controladorLogin.php">
        <input type="hidden" name="action" id="action" value="datosIniciales" />
        <div id="msgLogIn"><?=$txtApp['login']['tituloAppPrincipal'];?></div>
    	<div id="contenedorLogIn">
        	<div id="imgLogIn"><img src="../../img/Finder.png" width="80" height="80" border="0" /></div>
            <div id="accesoLogIn">
            	<div id="datosLogIn">
                    <div><?=$txtApp['login']['tituloUsuario'];?></div>
                    <div><input type="text" id="txtUsuario" name="txtUsuario" style="width:150px;" /></div>
                    <div><?=$txtApp['login']['tituloPass'];?></div>
                    <div id="divPassword"><input type="password" id="txtPassword" name="txtPassword" style="width:150px;" /></div>
                    <div id="divBoton"><input type="submit" value="<?=$txtApp['login']['btnApp']?>" id="boton" /></div>           
                </div>
            </div>            
        </div>
        </form>
    </div>
    
    <div id="pieLogIn">
    	<div id="infoApp"><? if($_GET['error']=="0") echo "Acceso No Autorizado";?></div>
        <div id="infoPieAbout"><a href="#" onclick="about()" style="color:#000;font-weight:bold; text-decoration:none;"><?=$txtApp['login']['tituloAbout'];?></a></div>
    	<div id="infoPieLogIn"><?=$txtApp['login']['pieLogin'];?></div>
    </div>    
</div>
<div id="aboutApp" style="display:none;"></div>
<div id="cargando" style=" display:none;position: absolute; left: 0; top: 0; width: 100%; height: 100%; background:url(../../../../img/desv.png) repeat">
	<div id="msgCargador"><div style="padding:10px;"><img src="../../../../img/cargador.gif" border="0" /></div></div>
</div>
</body>
</html>