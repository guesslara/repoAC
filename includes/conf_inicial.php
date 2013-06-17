<?php
	/*
	revisa las configuraciones iniciales
	*/
	include("../../clases/conexion/conexion.php");
	function conexionBd(){
		include("config.inc.php");
		$conn = new Conexion();
		$conexion = $conn->getConexion($host,$usuario,$pass,$db);
		return $conexion;
	}
	//Mantenimiento Sistema
	function verificaMantto(){
		include("conectarbase.php");
		$sqlSitio="SELECT valor,descripcion FROM configuracionglobal WHERE nombreConf='sitio_desactivado'";
		$resSitio=mysql_query($sqlSitio,conexionBd());
		$filaSitio=mysql_fetch_array($resSitio);
		$sitioActivo[0]=$filaSitio['valor'];
		$sitioActivo[1]=$filaSitio['descripcion'];
		mysql_close();
		return $sitioActivo;
	}
	//Mantenimiento Requisiciones
	function verificaManttoReq(){
		include("conectarbase.php");
		$sqlSitio="SELECT valor,descripcion FROM configuracionglobal WHERE nombreConf='sitio_desactivado_Req'";
		$resSitio=mysql_db_query($db,$sqlSitio);
		$filaSitio=mysql_fetch_array($resSitio);
		$sitioActivo[0]=$filaSitio['valor'];
		$sitioActivo[1]=$filaSitio['descripcion'];
		mysql_close();
		return $sitioActivo;
	}
	//Actualizaciones Compras
	function verificaActCompras(){
		include("conectarbase.php");
		$sqlSitio="SELECT valor,descripcion FROM configuracionglobal WHERE nombreConf='act_compras'";
		$resSitio=mysql_db_query($db,$sqlSitio);
		$filaSitio=mysql_fetch_array($resSitio);
		$actCompras[0]=$filaSitio['valor'];
		$actCompras[1]=$filaSitio['descripcion'];
		mysql_close();
		return $actCompras;	
	}
	//Actualizaciones Requisiciones
	function verificaActReq(){
		include("conectarbase.php");
		$sqlSitio="SELECT valor,descripcion FROM configuracionglobal WHERE nombreConf='act_Requisiciones'";
		$resSitio=mysql_db_query($db,$sqlSitio);
		$filaSitio=mysql_fetch_array($resSitio);
		$actReq[0]=$filaSitio['valor'];
		$actReq[1]=$filaSitio['descripcion'];
		mysql_close();
		return $actReq;	
	}
	function msgActualizacion($mensaje){
?>
		<div style="z-index:10;">
        	<div id="desv1">
        		<div id="msg1">
        			<div style=" color:#FFF;border:#000 solid thin; background-color:#000; height:19px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"><div style="float:left;">IQe Sisco Modificacion al Sistema</div><div style="float:right;"></div></div>
        				<div style=" color:#000;font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-left:5px; margin-right:5px;">
        				<br /><span style="font-size:16px;">Informaci&oacute;n...</span><br />
        				<br /><br />Actualizaci&oacute;n Modulo Compras.<br /><br />
        				Si tiene problemas con esta actualizaci&oacute;n notifiquelo al Depto. de Sistemas.<br />
        				<br />Para cerrar esta ventana presione el bot&oacute;n <i>Cerrar Ventana</i> situado en la parte inferior.<br /><br /><br /><br />
        				<span style="font-size:16px;">Actualizaciones...</span><br /><br />
        				<div style="margin-left:5px; height:100px;"><p><?=$mensaje;?></p></div>
        				<p><br /><br /><a href="../mod_controlCambios/index.php" title="Mostrar Actualizaciones" style="color:red; text-decoration:none;" target="_blank">Haga click Aqui para Mostrar las Actualizaciones</a></p>
        				<div style="text-align:center; margin-top:50px;"><input name="btnCancel" onclick="cierraMensaje()" type="button" value="Cerrar Ventana" style="width:200px; border:#000 solid thin; background-color:#CCC;" onmouseover="this.style.backgroundColor='#FFFFFF';" onmouseout="this.style.backgroundColor='#CCCCCC';" /></div>
        			</div>        
        		</div>
        </div>
<?	
	}
	function msgActualizacionReq($mensaje){
?>
		<div style="z-index:100;">
    		<div id="desv1">
    			<div id="msg1">
    				<div style=" color:#FFF;border:#000 solid thin; background-color:#000; height:19px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"><div style="float:left;">IQe Sisco Modificacion al Sistema</div><div style="float:right;"></div></div>
                    <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-left:5px; margin-right:5px;">
                    <br /><span style="font-size:16px;">Informaci&oacute;n...</span><br />
                    <br /><br />Actualizaci&oacute;n Modulo Cuentas por Pagar.<br /><br />
                    Si tiene problemas con esta actualizaci&oacute;n notifiquelo al Depto. de Sistemas.<br />
                    <br />Para cerrar esta ventana presione el bot&oacute;n <i>Cerrar Ventana</i> situado en la parte inferior.<br /><br /><br /><br />
                    <span style="font-size:16px;">Actualizaciones...</span><br /><br />
                    <p><img src="../../img/check1.jpg" width="16" height="15" />Actualizaci&oacute;n e inclusi&oacute;n de una empresa m&aacute;s.</p>
                    <p><img src="../../img/check1.jpg" width="16" height="15" />Actualizaci&oacute;n General y mantenimiento de la Base de Datos.</p>
                    <p><br /><br /><a href="../mod_controlCambios/index.php" title="Mostrar Actualizaciones" style="color:red; text-decoration:none;" target="_blank">Haga click Aqui para Mostrar las Actualizaciones</a></p>
                    <div style="text-align:center; margin-top:50px;"><input name="btnCancel" onclick="cierraMensaje()" type="button" value="Cerrar Ventana" style="width:200px; border:#000 solid thin; background-color:#CCC;" onmouseover="this.style.backgroundColor='#FFFFFF';" onmouseout="this.style.backgroundColor='#CCCCCC';" /></div>
    			</div>        
    		</div>
    	</div>
<?	
	}
?>