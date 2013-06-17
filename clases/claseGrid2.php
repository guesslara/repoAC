<?php
	include("conexion/conexion.php");
	/*
	clase para generar un grid con resultados de una consulta
	*/
	class claseGrid2{
		private $paginador="";
		public function colocarDatosGrid($campos,$from,$where,$registrosAMostrar,$titulosColumnas,$camposArray){
			$RegistrosAMostrar=25;
			$i=0;
			if(isset($_POST['pag'])){
			  $RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
			  $PagAct=$_POST['pag'];
			}else{
			  $RegistrosAEmpezar=0;
			  $PagAct=1;
			}			
			if($where==""){
				$sqlConsulta="SELECT ".$campos.$from;
			}else{
				$sqlConsulta="SELECT ".$campos.$from.$where;
			}	
			$limit=" LIMIT ".$RegistrosAEmpezar.",".$RegistrosAMostrar;
			echo "<br>Consulta 1: ".$consulta=$sqlConsulta.$limit; //primer consulta
			echo "<br>Consulta 2: ".$consulta1=$sqlConsulta;  //segunda consulta
			$rs=mysql_query($consulta,$this->conexionBd());  //se ejecutan las consultas
			$rs1=mysql_query($consulta1,$this->conexionBd());
			$NroRegistros=@mysql_num_rows($rs1) or die("Verifique el filtro de Busqueda");
			$PagAnt=$PagAct-1;
			$PagSig=$PagAct+1;
			$PagUlt=$NroRegistros/$RegistrosAMostrar;
			//verificamos residuo para ver si llevará decimales
			$Res=$NroRegistros%$RegistrosAMostrar;
			// si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
			if($Res>0) $PagUlt=floor($PagUlt)+1;
			//se escribe el paginador de resultados
			echo $GLOBALS["paginador"]="<a href=\"javascript:Pagina('1')\" title=\"Primero\" style=\"cursor:pointer; text-decoration:none;\">|Primero</a>";
			echo "<input type='hidden' name='campos' id='campos' value='".$campos."' />
			<input type='hidden' name='from' id='from' value='".$from."' />
			<input type='hidden' name='where' id='where' value='".$where."' />
			<input type='hidden' name='registrosAMostrar' id='registrosAMostrar' value='".$registrosAMostrar."' />
			<input type='hidden' name='titulosColumnas' id='titulosColumnas' value='".$titulosColumnas."' />
			<input type='hidden' name='camposArray' id='camposArray' value='".$camposArray."' />";
?>			
			<script type="text/javascript">
				$("#paginadorResultadosGrid").html("");
				$("#paginadorResultadosGrid").append("<a href=\"javascript:paginadorResultadosClase('1')\" title=\"Primero\" style=\"cursor:pointer; text-decoration:none;\">|&lt;</a>&nbsp;");
			</script>
<?
			if($PagAct>1){ 
?>
			<script type="text/javascript">
				$("#paginadorResultadosGrid").append("<a href=\"javascript:paginadorResultadosClase('<?=$PagAnt;?>')\" title=\"Anterior\" style=\"cursor:pointer; text-decoration:none;\">&lt;&lt;</a>&nbsp;");
			</script>
<?
			}
?>
			<script type="text/javascript">
				$("#paginadorResultadosGrid").append("<strong>"+<?=$PagAct;?>+"/"+<?=$PagUlt;?>+"</strong>");
			</script>
<?
			if($PagAct<$PagUlt){
?>
			<script type="text/javascript">
				$("#paginadorResultadosGrid").append("<a href=\"javascript:paginadorResultadosClase('<?=$PagSig;?>')\" title=\"Siguiente\" style=\"cursor:pointer; text-decoration:none;\">&gt;&gt;</a>&nbsp;");	
			</script>							
<?
			}
?>     
			<script type="text/javascript">				
				$("#paginadorResultadosGrid").append("<a href=\"javascript:paginadorResultadosClase('<?=$PagUlt;?>')\" title=\"Ultimo\" style=\"cursor:pointer; text-decoration:none;\">&gt;|</a>&nbsp;");
			</script>
<?			
			if($NroRegistros==0){//se comparan los resultados
				echo "Sin resultados";				
			}else{
				$i=0;//contador igual a cero				
				while($rowGrid=mysql_fetch_array($rs)){//se comienza a recorrer los resultados por filas
					for($j=0;$j<count($titulosColumnas);$j++){//se recorre por columnas
						$idCaja="#txt_".$i."_".$j;//indice de la caja
?>
						<script type="text/javascript">
							$("<?=$idCaja;?>").attr("value","<?=$rowGrid[$camposArray[$j]];?>");//se asigna el valor a las cajas de texto
						</script>
<?			
					}
					$i+=1;
				}
			}
		}
		
		public function creaGrid($tituloGrid,$titulosColumna,$registrosAMostrar){
			$color="#FFF";
			$mitadcolumnas=count($titulosColumna)-round(count($titulosColumna)/2);
			echo "<div style='height:20px; padding:5px; border:1px solid #CCC; font-weight:bold;'>".$tituloGrid."</div>";
			//se escriben las columnas
			$grid="<table border='0' cellpadding='1' cellspacing='1' width='98%' style='margin:5px; margin-left:10px; border:1px solid #CCC;'><tr>";
			$grid.="<tr><td colspan='".count($titulosColumna)."'><div id='paginadorResultadosGrid' style='height:25px; padding:5px; font-size:12px;background:#F0F0F0;'></div></td></tr>";
			$grid.="<tr><td colspan='".$mitadcolumnas."'><div style='height:25px; padding:5px; font-size:12px;'>Mostrando ".$registrosAMostrar." resultados.</div></td>";
			$grid.="<td colspan='".$mitadcolumnas."' align='right' colspan='".count($titulosColumna)."' style='text-align=right;'>Buscar:<input type='text' name='txtbusquedaGrid' id='txtbusquedaGrid' style='width:150px;' /></td></tr>";
			for($i=0;$i<count($titulosColumna);$i++){				
				$grid.="<td style='background:#000;color:#FFF;text-align:center;'>".$titulosColumna[$i]."</td>";
			}
			$grid.="</tr>";
			for($i=0;$i<$registrosAMostrar;$i++){
				$grid.="<tr>"; 
				for($j=0;$j<count($titulosColumna);$j++){
					if($j==0){
						$tituloSpan1="link_".$i."_".$j;
						$grid.="<td style='background:".$color.";text-align:center;border-bottom:1px solid #666;'><span id='$tituloSpan1;'>A</span></td>";
					}else{
						$idCajaDatosGrid="txt_".$i."_".($j-1);
						$grid.="<td style='background:".$color.";height:25px;text-align:center;border-bottom:1px solid #666;'><input type='text' name='' id='".$idCajaDatosGrid."' style='width:100px; text-align:center;font-size:10px;border:none;background:".$color.";'></td>";
					}
				}
				($color=="#FFF") ? $color="#CCC" : $color="#FFF";
				$grid.="</tr>";
			}
			$grid.="</table>";
			echo $grid;	
		}
		
		public function cargarScriptsClase(){
			echo "<script type='text/javascript' src='jquery-1.3.2.min.js'></script>";
			echo "<script type='text/javascript'>
				function paginadorResultadosClase(pag){
					alert(pag);
					var campos=$('#campos').val();
					var from=$('#from').val();
					var where=$('#where').val();
					var registrosAMostrar=$('#registrosAMostrar').val();
					var titulosColumnas=$('#titulosColumnas').val();
					var camposArray=$('#camposArray').val();
				}
			</script>";
		}
		
		private function conexionBd(){
			include("../includes/config.inc.php");
			$conn = new Conexion();
			$conexion = $conn->getConexion($host,$usuario,$pass,$db);
			return $conexion;
		}
	}
	
	/*prueba de la clase*/
	$lote="01319"; $modelo="24";
	$objGrid=new claseGrid2();
	$titulosColumnas=array("<--->","Modelo","Imei","Serial","MFGDATE","Lote","F_Recibo","Mov.");
	$registrosAMostrar=25;
	$campos="modelo,imei,serial,mfgdate,lote,f_recibo,num_movimiento";
	$camposArray=array("modelo","imei","serial","mfgdate","lote","f_recibo","num_movimiento");
	$from=" FROM equipos inner join cat_modradio on equipos.id_modelo=cat_modradio.id_modelo";
	$where=" where lote='".$lote."' AND equipos.id_modelo='".$modelo."'";
	$objGrid->cargarScriptsClase();
	$objGrid->creaGrid("Resultados encontrados",$titulosColumnas,$registrosAMostrar);
	$objGrid->colocarDatosGrid($campos,$from,$where,$registrosAMostrar,$titulosColumnas,$camposArray);
?>