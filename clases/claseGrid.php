<?php
	/*
	clase para construir el grid de resultados, por medio de una consulta
	*/
	class claseGrid{
	
		public function armaGrid($titulo,$cabeceras,$select,$from,$where,$regsPag){
			include("../includes/conectarbase.php");
			//longitud de la cabecera
			//echo "<br>".
			$cabLong=count($cabeceras);
			$select="SELECT ".$select;
			$from=" FROM ".$from;
			$where=" WHERE ".$where;			
			//nombre de los campos originales
			$camposTabla=$select;			
			
			$RegistrosAMostrar=$regsPag;
			$i=0;
			//valores por GET
			if(isset($_GET['pag'])){
				$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
				$PagAct=$_GET['pag'];
			}else{
				$RegistrosAEmpezar=0;
				$PagAct=1;
			}
			//se arma la clausula limit
			$limit=" LIMIT ".$RegistrosAEmpezar.",".$RegistrosAMostrar;
			echo $sqlGrid=$select.$from.$where.$limit;
			echo "<br>";
			echo $sqlGrid1=$select.$from.$where;
			//se ejecutan las consultas
			$resGrid=mysql_db_query($db,$sqlGrid);
			$resGrid1=mysql_db_query($db,$sqlGrid1);
			//******--------determinar las páginas---------******//
			$NroRegistros=mysql_num_rows($resGrid1);//$rs1->RecordCount();
			$PagAnt=$PagAct-1;
			$PagSig=$PagAct+1;
			$PagUlt=$NroRegistros/$RegistrosAMostrar;			
			//verificamos residuo para ver si llevará decimales
			$Res=$NroRegistros%$RegistrosAMostrar;
			// si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
			if($Res>0) $PagUlt=floor($PagUlt)+1;
			//se muestran los resultados

			echo "<table width='99%' border='1' cellpadding='1' cellspacing='1'>
            	<tr>
                	<td align='center' colspan='".($cabLong+1)."'>".$titulo."</td>
                </tr>                
                <tr>
                	<td align='right' colspan='".($cabLong+1)."'>Total de Resultados: ".$NroRegistros."&nbsp;</td>
                </tr>
                <tr>
                	<td>&nbsp;</td>";

				for($i=0;$i<$cabLong;$i++){
					echo "<td align='center'>".$cabeceras[$i]."</td>";
				}

                echo "</tr>";
				
				while($rowGrid=mysql_fetch_array($resGrid)){
				echo "<tr>
                	<td><a href=\"#\" title='Modificar'>M</a> | <a href=\"#\" title='Eliminar'>E</a></td>";
				
					for($j=0;$j<$cabLong;$j++){				
                    	echo "<td align='center'>".$rowGrid[$j]."&nbsp;</td>";
					}

				echo "</tr>";
				}

                echo "</tr>
            </table>";

		}

	}//fin de la clase



	/******************************************/
	$titulo="Listado de Radios";
	$fecha1="2010-07-01";
	$fecha2="2010-07-31";
	$regsPag=25;
	$cabeceras=array("Id Radio","Modelo","Imei","Serial","BDCode","Lote","MFGDate","Nombre","Apellido","Repeticiones");
	$select="equipos.id_radio,modelo,imei,serial,bdcode,lote,mfgdate,nombre,apaterno,repeticiones";
	$from="(equipos left join cat_modradio on equipos.id_radio=cat_modradio.id_modelo) left join userdbnextel on equipos.id_personal=userdbnextel.ID";
	$where=" equipos.f_recibo BETWEEN '".$fecha1."' AND '".$fecha2."'";
	$objGrid=new claseGrid();
	$objGrid->armaGrid($titulo,$cabeceras,$select,$from,$where,$regsPag);
?>