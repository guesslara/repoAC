<?
	session_start();
	include("../../includes/cabecera.php");
	$proceso="";	
	/*if(!isset($_SESSION['id_usuario_nx'])){
		echo "<script type='text/javascript'> alert('Su sesion ha terminado por inactividad'); window.location.href='../mod_login/index.php'; </script>";
		exit;
	}else{
		if($proceso != ""){			
			$sqlProc="SELECT * FROM cat_procesos WHERE descripcion='".$proceso."'";
			$resProc=mysql_query($sqlProc,conectarBd());
			$rowProc=mysql_fetch_array($resProc);
			$proceso=$rowProc['id_proc'];
		}
	}*/	
	function conectarBd(){
		require("../../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
		}				
	}
?>
<script type="text/javascript" src="js/funcionesEnsamble.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<!--se incluyen los recursos para el grid-->
<script type="text/javascript" src="../../recursos/grid/grid.js"></script>
<link rel="stylesheet" type="text/css" href="../../recursos/grid/grid.css" />
<!--fin inclusion grid-->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-green.css"  title="win2k-cold-1" />
<link rel="stylesheet" type="text/css" media="all" href="css/estilos.css" />  
<script type="text/javascript" src="js/calendar.js"></script><!-- librería principal del calendario -->  
<script type="text/javascript" src="js/calendar-es.js"></script><!-- librería para cargar el lenguaje deseado -->   
<script type="text/javascript" src="js/calendar-setup.js"></script><!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();		
	});	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();
		var altoCuerpo=altoDiv-52;
		$("#detalleEmpaque").css("height",altoCuerpo+"px");
		$("#ventanaEnsambleContenido2").css("height",altoCuerpo+"px");
		$("#detalleEmpaque").css("width",(anchoDiv-280)+"px");
		$("#ventanaEnsambleContenido2").css("width",(anchoDiv-200)+"px");
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}	
	window.onresize=redimensionar;	
</script>
<!--<div id="cargadorEmpaque" class="cargadorEmpaque">Cargando...</div>-->
<input type="hidden" name="txtProcesoEmpaque" id="txtProcesoEmpaque" value="<?=$proceso;?>" />
<input type="hidden" name="txtIdUsuarioEmpaque" id="txtIdUsuarioEmpaque" value="<?=$_SESSION['id_usuario_nx'];?>" />
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">
		<div id="barraOpcionesEnsamble">
			<div class="opcionesEnsamble" onclick="" title="">Opcion 1</div>
			<div class="opcionesEnsamble" onclick="" title="">Opcion 2</div>			
			<div id="cargadorEmpaque" style="float:right;width:200px;height:20px;padding:5px;background:#FFF;border:1px solid #CCC;font-size:13px;text-align:center;">Administraci&oacute;n Sistema</div>
		</div>
		<div id="infoEnsamble3">			
			<div id="listadoEmpaque" style="border:1px solid #e1e1e1;background:#fff; height:99%;width:97%;font-size:12px;margin:3px;overflow: auto;">
				<!--contenido Nuevo-->
				Buscar Usuario:
				<input type="text" name="txtBuscar" id="txtBuscar" onkeyup="Buscador()" style="width:150px; font-size:14px; color:#000;"  /><br />
				<input type="radio" name="rdbBusqueda" id="rdbBusqueda" value="nombre" checked="checked" />Por Nombre
				<input type="radio" name="rdbBusqueda" id="rdbBusqueda" value="usuario" />Por usuario
				<div style="height: 15px;padding: 5px;border: 1px solid #CCC;background: #CCC;">Usuarios:</div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:nuevoUsuario()" style="text-decoration: none;color: #333;">Agregar Usuario</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:consultarUsuarios('act','nombre')" style="text-decoration: none;color: #333;">Usuarios Activos</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:consultarUsuarios('ina','nombre')" style="text-decoration: none;color: #333;">Usuarios Inactivos</a></div>				
				<div style="height: 15px;padding: 5px;border: 1px solid #CCC;background: #CCC;">Grupos:</div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:addGrupo()" style="text-decoration: none;color: #333;">Agregar Grupo</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:consultaGrupos()" style="text-decoration: none;color: #333;">Consultar Grupos</a></div>
				<!--<div style="height: 15px;padding: 3px;"><a href="javascript:nuevaFuncionalidad()">Agregar Men&uacute;</a></div>
				<div style="height: 15px;padding: 3px;margin-left: 10px;"><a href="javascript:agregarSubMenu()">Men&uacute;s:</a></div>-->
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:mostrarOpcionesMenu()" style="text-decoration: none;color: #333;">Men&uacute;s:</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:verModulos()" style="text-decoration: none;color: #333;">Ver Modulos:</a></div>
				<div style="height: 15px;padding: 5px;border: 1px solid #CCC;background: #CCC;">Configuraci&oacute;n:</div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:manttoSistema('sitio_desactivado')" style="text-decoration: none;color: #333;">Mantenimiento del Sistema</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:controlCambios()" style="text-decoration: none;color: #333;">Agregar Actualizaciones</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:consultaAct()" style="text-decoration: none;color: #333;">Listar Actualizaciones</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:agregarConfiguracion()" style="text-decoration: none;color: #333;">Agregar Configuracion</a></div>
				<div style="height: 18px;padding: 5px;margin: 5px;border: 1px solid #CCC;width: 92%;"><a href="javascript:configuracionesGlobales()" style="text-decoration: none;color: #333;">Configuraciones globales</a></div>							
				<!--fin contenido Nuevo-->
			</div>			
		</div>
		<div id="detalleEmpaque" class="ventanaEnsambleContenido"></div>
		<div id="ventanaEnsambleContenido2" class="ventanaEnsambleContenido" style="display:none;"></div>
		<div style="clear:both;"></div>
		<!--<div id="barraInferiorEnsamble">			
			<div id="erroresCaptura"></div>
			<div id="opcionCancelar"><input type="button" onclick="cancelarCaptura()" value="Cancelar" style=" width:100px; height:30px;padding:5px;background:#FF0000;color:#FFF;border:1px solid #FF0000;font-weight:bold;" /></div>
		</div>-->
	</div>
</div>
<div id="transparenciaGeneral1" class="transparenciaGeneral" style="display:none;">
	<div id="divMensajeCaptura" class="ventanaDialogo">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n<div id="btnCerrarVentanaDialogo"><a href="#" onclick="cerrarVentana('divMensajeCaptura')" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueValidacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
	</div>
</div>	
<?
include ("../../includes/pie.php");
?>