<?php 
	if($_POST['action']=="buscarNuevas"){
		buscarNuevas();
	}

	function buscarNuevas(){
		
		$sql_R="select count(*) as total from rc where asignadaComprador='N/A'";
		$sql_Rev="select count(*) as total1 from items WHERE aut_status='revisar'";
		$sql_Rev1="select count(*) as total2 from oc WHERE status='Recibida'";
		$sql_nDoc="select count(*) as total3 from db_iqe_prd_nuevo WHERE status='Compras'";
		$sql_nProd="select count(*) as total4 from db_iqe_prd_nuevo WHERE status='Almacen'";
		$sql_nReqPen="SELECT COUNT(*) AS total5 FROM rc WHERE (fecha BETWEEN '2011-01-01' AND '".date("Y-m-d")."') AND status in ('Nueva','Cotizando')";
		$sql_tProv="SELECT count(*) as total6 FROM oc WHERE (fecha BETWEEN '2011-01-01' AND '".date("Y-m-d")."' ) AND status in ('Nueva')";
		//SELECT count(*) as total,asignadaComprador FROM rc WHERE (fecha BETWEEN '2010-08-01' AND '".date("Y-m-d")."' ) AND status in ('Nueva','Cotizando','Autorizando','Autorizada') GROUP BY asignadaComprador
		

		$rs=mysql_query($sql_R,conectarBd());
		$rs1=mysql_query($sql_Rev,conectarBd());
		$rs2=mysql_query($sql_Rev1,conectarBd());
		
		$rs3=mysql_query($sql_nReqPen,conectarBd());
		
		$rs4=mysql_query($sql_tProv,conectarBd());
		
		$fila=mysql_fetch_array($rs);
		$total=$fila['total'];
		
		$fila1=mysql_fetch_array($rs1);
		$total1=$fila1['total1'];
		
		$fila2=mysql_fetch_array($rs2);
		$total2=$fila2['total2'];
		
		$resultadoNDoc=mysql_query($sql_nDoc,conectarBdPrdNuevo());
		$filaNdoc=mysql_fetch_array($resultadoNDoc);
		$total3=$filaNdoc['total3'];	
			
		$resultado_nProd=mysql_query($sql_nProd,conectarBdPrdNuevo());
		$fila_nProd=mysql_fetch_array($resultado_nProd);
		
		$fila3=mysql_fetch_array($rs3);
		$fila_reqPend=$fila3['total5'];
		
		$fila4=mysql_fetch_array($rs4);
		$fila_tiempoOc=$fila4['total6'];
?>		
		<div style=" margin-left:3px; margin-right:3px; border:1px solid #CCC; background-color:#F0F0F0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">        	
            <div style="margin-left:3px;">
            <table width="169" border="0" cellpadding="0" cellspacing="0" style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
              <tr>
                <td width="116" style="height:25px;" valign="middle"><a href="req_Lista/rc_buscar.php?<?=$SID;?>" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Requisiciones Nuevas">Req(s). Nueva(s)</a></td>
                <td width="53" style="height:25px;" align="center" valign="middle"><strong><?=$total;?></strong></td>                
              </tr>
              <tr>
                <td style="height:25px;" valign="middle"><a href="cotizacion/cotizacion.php?<?=$SID;?>" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Requisiciones por Revisar">Req(s) x Revisar</a></td>
                <td style="height:25px;" align="center" valign="middle"><strong><?=$total1;?></strong></td>
              </tr>
              <tr>
                <td style="height:25px;" valign="middle"><a href="oCompra/buscaoc.php?<?=$SID;?>" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Ordenes de Compra Recibidas en el Almacen">Recibo Almacen</a></td>
                <td style="height:25px;" valign="middle" align="center"><strong><?=$total2;?></strong></td>
              </tr>
              <tr>
                <td style="height:25px;" valign="middle"><a href="../../../productos_nuevos/DatosCompras.php" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Solicitud de Nuevos Productos">Productos Nuevos</a></td>
                <td style="height:25px;" valign="middle" align="center"><strong><?=$total3;?></strong></td>
              </tr>
              <tr>
              	<td style="height:25px;" valign="middle"><a href="../../../productos_nuevos/DatosAlmacen.php" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Solicitud de Productos Nuevos en el Almacen">Productos Alm.</a></td>
                <td style="height:25px;" valign="middle" align="center"><strong><?=$fila_nProd['total4'];?></strong></td>
              </tr>              
            </table>             
            <br />
            </div>
        </div>
        <div style=" margin:5px 3px 3px 3px; border:1px solid #CCC; background-color:#F0F0F0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">
        	<div style="border:1px solid #999999; background-color:#999999; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#FFFFFF;">Requisiciones</div>
            <div style="margin-left:3px;">
            <table width="169" border="0" cellpadding="0" cellspacing="0" style="font-family:Verdana, Geneva, sans-serif; font-size:12px;">
              <tr>
                <td width="116" style="height:25px;" valign="middle"><a href="contenidoFrame.php?action=pendientes&<?=$SID;?>" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Requisiciones Nuevas">Pendientes</a></td>
                <td width="53" style="height:25px;" align="center" valign="middle"><strong><?=$fila_reqPend;?></strong></td>                
              </tr>
              <tr>
                <td width="116" style="height:25px;" valign="middle"><a href="tiempoOc.php?action=tiempoOC" target="contenedorVentana" style="text-decoration:none; color:#000;" title="Seguimiento Proveedores/Ordenes de Compra">Seguimiento Provs.</a></td>
                <td width="53" style="height:25px;" align="center" valign="middle"><strong><?=$fila_tiempoOc;?></strong></td>                
              </tr>
            </table>
            </div>
		</div>
<?					
		/*$sitioActivo=verificaMantto();
		if($sitioActivo[0]=="Si"){			
			mensaje($sitioActivo[1]);
		}*/
	}

	function mensaje($comentario){
?>
		<div style="margin:3px; border:1px solid #FC0; background-color:#FF9; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">
        	<div style="border:1px solid #999999; background-color:#FC0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#000;">IQe. Sisco Compras</div>
            <div style="margin-left:3px;">
            	<img src="../../img/alert.png" border="0" />&nbsp;<?=$comentario;?><br /><br />
            </div>
        </div>
<?	
	}
	
	function conectarBd(){
		require("../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
		}				
	}
	function conectarBdPrdNuevo(){
		require("../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db_prodNuevos);
			return $link;
		}				
	}
?>