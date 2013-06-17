<?
	session_start();
	include("../../clases/funcionesComunes.php");
	class modeloEnsamble{

		private function conectarBd(){
			require("../../includes/config.inc.php");
			$link=mysql_connect($host,$usuario,$pass);
			if($link==false){
				echo "Error en la conexion a la base de datos";
			}else{
				mysql_select_db($db);
				return $link;
			}				
		}

		public function agregaCajaCapturaFinal($idEntregaInterna,$idValidacion,$cantidadEntrega){
			//se extrae la cantidad de equipos por caja
			$sqlCantEquipos="SELECT valor FROM configuracionglobal WHERE nombreConf='cantidadCajas'";
			$resCantEquipos=mysql_query($sqlCantEquipos,$this->conectarBd());
			$rowCantEquipos=mysql_fetch_array($resCantEquipos);
			//se hace la operacion para saber el numero de cajas a capturar
			$totalCajas=$cantidadEntrega/$rowCantEquipos["valor"];
			$totalCajas=ceil($totalCajas);
			if($totalCajas != 0){
				for($i=1;$i<=$totalCajas;$i++){
					$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna) VALUES ('".$i."','$idEntregaInterna')";
					$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
				}
?>
			<script type="text/javascript"> alert('Cajas generadas'); verDetalleValidacion('<?=$idValidacion;?>');</script>
<?
			}
		}
		
		public function verificarPO($idEntregaInterna,$conceptoEntrega,$modelo,$usrEmpaque,$idElemento,$valores,$numeroCaja,$cantidadCapturada,$idModeloCaptura,$poAValidar){
			$sqlId="SELECT id_radio,status,lote FROM equipos WHERE imei='".$valores."' AND id_modelo='".$idModeloCaptura."' AND status in('Validando') AND statusProceso in ('Empaque','Empacado','Validacion')";
			$resId=mysql_query($sqlId,$this->conectarBd());
			$rowId=mysql_fetch_array($resId);
			//se comiensa la validacion de la OCA o PO contra los folios en la base de datos
			$foliosAnt=array("01176","01177","01178","01184","01185","01193","01195","01198","01199","01204","01207","01208","01212","01213","01218","01219","01226","01230","01235","01236","01239","01242","01245","01249","01255","01258","01260","01263","01267","01274","01275","01276","01284","01290","01291","01296","01300","01303","01309","01311","01312","01319","01324","01331","01344","01345","01353","01354","01355");
			//se busca el folio obtenido buscandolo en el array
			echo $encontrado=in_array($rowId["lote"],$foliosAnt);
			echo "<br>".$poAValidar;
			echo "<br>".$rowId["lote"];
			if($poAValidar=="120113619" && $encontrado==true){
				echo "Correcto";
			}else if($poAValidar=="120113619" && $encontrado==false){
				echo "El equipo no peretence a esta P.O.";
			}else if($poAValidar!="120113619" && $encontrado==true){
				echo "Error verifique la PO";
			}
		}
		
		public function guardaEquiposEmpaqueFinal($idEntregaInterna,$conceptoEntrega,$modelo,$usrEmpaque,$idElemento,$valores,$numeroCaja,$cantidadCapturada,$idModeloCaptura,$poAValidar){			
			//echo "<br>".$idModeloCaptura;
			//se extrae el id del equipo			
			$sqlId="SELECT id_radio,status,lote FROM equipos WHERE imei='".$valores."' AND id_modelo='".$idModeloCaptura."' AND status in('Validando') AND statusProceso in ('Empaque','Empacado','Validacion')";
			$resId=mysql_query($sqlId,$this->conectarBd());
			$rowId=mysql_fetch_array($resId);						
				if(mysql_num_rows($resId)==0){
					$msgCaja="Verifique informacion del IMEI";
					$color="red";
					$fuente="white";
				}else{
					if($rowId["status"]=="WIP" || $rowId["status"]=="Validando"){
						//se extrae el limite de la caja					
						$sqlLimiteCapturaActual="SELECT limiteDeCapturaCaja FROM entregas_nextel_cajas WHERE id_entregaInterna='".$idEntregaInterna."' AND numeroCaja='".$numeroCaja."'";
						$resLimiteCapturaActual=mysql_query($sqlLimiteCapturaActual,$this->conectarBd());
						$rowLimiteCapturaActual=mysql_fetch_array($resLimiteCapturaActual);
						$limiteCapturaActual=$rowLimiteCapturaActual["limiteDeCapturaCaja"];
						//se extrae la cantidad capturada que esta guardada
						//echo "<br>".$sqlCantidadCapturada="SELECT COUNT(*) AS cantidadCapturada FROM entregas_nextel INNER JOIN entregas_nextel_items WHERE  entregas_nextel.id='".$idEntregaInterna."' AND numeroCajaFinal='".$numeroCaja."'";
						$sqlCantidadCapturada="SELECT COUNT( * ) AS cantidadCapturada FROM entregas_nextel INNER JOIN entregas_nextel_items ON entregas_nextel.id = entregas_nextel_items.id_entrega WHERE entregas_nextel.id = '".$idEntregaInterna."' AND numeroCajaFinal = '".$numeroCaja."'";
						$resCantidadCapturada=mysql_query($sqlCantidadCapturada,$this->conectarBd());
						$rowCantidadCapturada=mysql_fetch_array($resCantidadCapturada);
						$cantidadCapturadaBd=$rowCantidadCapturada["cantidadCapturada"];					
						//se aumenta el contador en 1 y se actualiza la base de datos
						$cantidadCapturadaBd+=1;
						if($cantidadCapturadaBd <= $limiteCapturaActual){
							//consulta para la insercion en la tabla
							$sqlEF="INSERT INTO entregas_nextel_items (id_entrega,id_radio,numeroCajaFinal) VALUES ('".$idEntregaInterna."','".$rowId["id_radio"]."','".$numeroCaja."')";
							$resEF=mysql_query($sqlEF,$this->conectarBd());
							if($resEF){
								$msgCaja="Equipo Guardado";
								$color="green";
								$fuente="white";							
								//se actualiza el campo en la BAse de Datos
								$sqlCant1="UPDATE entregas_nextel set cantidadCapturada='".$cantidadCapturadaBd."' where id='".$idEntregaInterna."'";
								$resCant1=mysql_query($sqlCant1,$this->conectarBd());
								if($resCant1){
									echo "<br>Equipo Agregado";
								}else{
									echo "<br>Error al actualizar el registro de empaque";
								}														
								echo "<script type='text/javascript'>document.getElementById('agregadoCajaCapturaFinalEnCaja').value='".$cantidadCapturadaBd."'; </script>";
							}else{
								$msgCaja="Error al Guardar";
								$color="red";
								$fuente="white";				
							}
							//se escribe el resultado en el elemento indicado
							echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
							echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
							echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
						}else{
							echo "<strong><p style='color:#FF0000;font-weight:bold;'>Ya no se pueden agregar equipos a la Caja</p></strong>";
							$msgCaja="Cantidad de Equipos Excedida";
							$color="red";
							$fuente="white";						
						}
					}else{
						$msgCaja="Verifique el Status del equipo";
						$color="red";
						$fuente="white";				
					}
				}
			
			//se escribe el resultado en el elemento indicado
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
		}
		
		public function eliminarEntrega($idEntregaInterna,$cantidadEntrega,$txtIdUsuarioEmpaque,$idValidacion){			
			//se procede a la eliminacion de la entrega
			echo $sqlElimina="DELETE FROM entregas_nextel where id='".$idEntregaInterna."'";
			$resElimina=mysql_query($sqlElimina,$this->conectarBd());
			if($resElimina){
				//se buscan las cajas asociadas y se procede a eliminar las cajas relacionadas a la entrega interna
				$sqlEliminaCajas="DELETE FROM entregas_nextel_cajas where id_entregaInterna='".$idEntregaInterna."'";
				$resEliminaCajas=mysql_query($sqlEliminaCajas,$this->conectarBd());
				//se eliminan los items de las cajas relacionadas a las entregas y las cajas
				$sqlEliminaItems="DELETE FROM  entregas_nextel_items WHERE id_entrega='".$idEntregaInterna."'";
				$resEliminaItems=mysql_query($sqlEliminaItems,$this->conectarBd());
?>
				<script type="text/javascript"> alert('Todo lo relacionado con la Entrega se ha Eliminado'); $("#notificaciones").hide(); verDetalleValidacion('<?=$idValidacion;?>');</script>
<?
			}else{
?>
				<script type="text/javascript> alert('Error al eliminar la Entrega');</script>
<?				
			}
		}
		
		public function formularioEntregasModificar($idModelo,$idEntregaInterna,$cantidadEquiposEmpacados,$idValidacion,$entregaModificar){
			$sqlEntrega="SELECT entregas_nextel.id AS id, po, releaseEntrega, fecha, concepto, entregas_nextel.destino AS idDestino, cantidad, id_modelo, id_entrega_interna, cat_destinos.destino AS destinoNombre FROM entregas_nextel INNER JOIN cat_destinos ON entregas_nextel.destino = cat_destinos.id WHERE entregas_nextel.id = '".$entregaModificar."'";
			$resEntrega=mysql_query($sqlEntrega,$this->conectarBd());
			$rowEntrega=mysql_fetch_array($resEntrega);
			$sqlDestino="select * from cat_destinos";
			$resDestino=mysql_query($sqlDestino,$this->conectarBd());
			$sqlModelo="select * from cat_modradio where id_modelo='".$idModelo."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);
?>
			<input type="hidden" name="txtIdModeloEntregaMod" id="txtIdModeloEntregaMod" value="<?=$idModelo;?>" />
			<input type="hidden" name="txtIdEntregaInternaMod" id="txtIdEntregaInternaMod" value="<?=$idEntregaInterna;?>" />
			<input type="hidden" name="txtCantidadEquiposEmpacadosMod" id="txtCantidadEquiposEmpacadosMod" value="<?=$cantidadEquiposEmpacados;?>" />
			<input type="hidden" name="txtIdValidacionMod" id="txtIdValidacionMod" value="<?=$idValidacion;?>" />
			<input type="hidden" name="txtCantidadEntregaModBd" id="txtCantidadEntregaModBd" value="<?=$rowEntrega["cantidad"];?>" />
			<table width="375" border="0" cellpadding="1" cellspacing="1" style="margin: 5px;">
				<tr>
					<td colspan="2" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Modificar Entrega</td>					
				</tr>
				<tr>
					<td colspan="2" style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Equipos Disponibles para Capturar: <?=$cantidadEquiposEmpacados;?></td>					
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">P.O.:</td>
					<td><input type="text" name="poEntregaMod" id="poEntregaMod" value="<?=$rowEntrega["po"];?>"></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Release:</td>
					<td><input type="text" name="txtReleaseEntregaMod" id="txtReleaseEntregaMod" value="<?=$rowEntrega["releaseEntrega"];?>" style="width: 50px;"> - <?=$rowModelo["modelo"];?></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Fecha:</td>
					<td>
						<input type="text" name="Fecha1Mod" id="Fecha1Mod" readonly="readonly" value="<?=$rowEntrega["fecha"];?>"/>
						<input type="button" id="lanzador1" value="..." />
						<script type="text/javascript">					
						Calendar.setup({
							inputField     :    "Fecha1Mod",      // id del campo de texto
							ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
							button         :    "lanzador1"   // el id del botón que lanzará el calendario
						});										
									
						</script>
					</td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Concepto:</td>
					<td><input type="text" name="conceptoEntregaMod" id="conceptoEntregaMod" value="<?=$rowEntrega["concepto"];?>"></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Cantidad:</td>
					<td><input type="text" name="cantidadEntregaMod" id="cantidadEntregaMod" value="<?=$rowEntrega["cantidad"];?>"></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Destino:</td>
					<td>
<?
			if(mysql_num_rows($resDestino)==0){
				echo "No hay Destinos Capturados en el Catalogo";
			}else{
?>
					<select name="txtDestinoEntregaMod" id="txtDestinoEntregaMod">
						<option value="<?=$rowEntrega["idDestino"];?>" selected="selected"><?=$rowEntrega["destinoNombre"];?></option>
<?
				while($rowDestino=mysql_fetch_array($resDestino)){
?>
						<option value="<?=$rowDestino["id"];?>"><?=$rowDestino["destino"];?></option>
<?
				}
?>
					</select>
<?
			}
?>						
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #CCC;"</td>					
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;"><input type="button" value="Cancelar" /><input type="button" value="Guardar" onclick="modificarEntregaFormulario()" /></td>					
				</tr>				
			</table>
<?
		}
		
		public function guardaFormEntrega($idModeloEntrega,$poEntrega,$releaseEntrega,$fechaEntrega,$conceptoEntrega,$cantidadEntrega,$destinoEntrega,$idEntregaInterna,$cantidadPorEntregar,$txtIdValidacion){
			//echo "Guardar entrega";
			$sqlGuardaEntrega="INSERT INTO entregas_nextel (po,releaseEntrega,fecha,concepto,destino,cantidad,id_modelo,id_entrega_interna) VALUES ('".$poEntrega."','".$releaseEntrega."','".$fechaEntrega."','".strtoupper($conceptoEntrega)."','".$destinoEntrega."','".$cantidadEntrega."','".$idModeloEntrega."','".$idEntregaInterna."')";
			$resGuardaEntrega=mysql_query($sqlGuardaEntrega,$this->conectarBd());
			if($resGuardaEntrega){
				//se extrae el ultimo id insertado
				$sqlId="SELECT DISTINCT LAST_INSERT_ID() AS id_entregaInternaInsertada FROM entregas_nextel";
				$resId=mysql_query($sqlId,$this->conectarBd());
				$rowId=mysql_fetch_array($resId);
				//se extrae la cantidad de equipos por caja
				$sqlCantEquipos="SELECT valor FROM configuracionglobal WHERE nombreConf='cantidadCajas'";
				$resCantEquipos=mysql_query($sqlCantEquipos,$this->conectarBd());
				$rowCantEquipos=mysql_fetch_array($resCantEquipos);
				//se hacen los calculos para colocar los limites en las cajas a capturar
				$limiteCajas=$rowCantEquipos["valor"];
				$cajaInicial=1;
				if($cantidadEntrega % $limiteCajas ==0){//todas las cajas tienen un limite de 90
					//se hace la operacion para saber el numero de cajas a capturar
					$totalCajas=$cantidadEntrega/$rowCantEquipos["valor"];
					if($totalCajas != 0){
						for($i=$cajaInicial;$i<=$totalCajas;$i++){
							$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna,limiteDeCapturaCaja) VALUES ('".$i."','".$rowId['id_entregaInternaInsertada']."','90')";
							$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
						}
					}
				}else{
					//se obtienen las cajas con el restante
					$cajas90=floor($cantidadEntrega/$rowCantEquipos["valor"]);					
					//echo "<script type='text/javascript'> alert(".$cajas90."); </script>";
					if($cajas90 != 0){
						for($i=$cajaInicial;$i<=$cajas90;$i++){
							echo "<br>en el FOR";
							$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna,limiteDeCapturaCaja) VALUES ('".$i."','".$rowId['id_entregaInternaInsertada']."','90')";
							$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
							$cajaInicial=$i;
						}
						//se averigua el faltante para las cajas
						$totalLimiteParcialCajas=$cajas90*$limiteCajas;
						//echo "<script type='text/javascript'> alert(".$totalLimiteParcialCajas."); </script>";
						$limiteRestanteCajas=$cantidadEntrega-$totalLimiteParcialCajas;
						//echo "<script type='text/javascript'> alert(".$limiteRestanteCajas."); </script>";
						$cajaInicial+=1;
						if( $limiteRestanteCajas != 0 ){
							$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna,limiteDeCapturaCaja) VALUES ('".$cajaInicial."','".$rowId['id_entregaInternaInsertada']."','".$limiteRestanteCajas."')";
							$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
						}
					}else if($cajas90 == 0){//si es cero
						$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna,limiteDeCapturaCaja) VALUES ('".$cajaInicial."','".$rowId['id_entregaInternaInsertada']."','".$cantidadEntrega."')";
						$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
					}
				}
				
				/*
				//se hace la operacion para saber el numero de cajas a capturar
				$totalCajas=floor($cantidadEntrega/$limiteCajas);				
				if($totalCajas != 0){
					for($i=1;$i<=$totalCajas;$i++){
						$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna.limiteDeCapturaCaja) VALUES ('".$i."','".$rowId['id_entregaInternaInsertada']."','90')";
						$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
					}
					if($cantidadEntrega % $limiteCajas != 0){//todas las cajas tienen un limite de 90
						//se averigua el faltante para las cajas
						$totalLimiteParcialCajas=$totalCajas*$limiteCajas;
						$limiteRestanteCajas=$cantidadEntrega-$totalLimiteParcialCajas;
						$sqlCapturaCaja="INSERT INTO entregas_nextel_cajas (numeroCaja,id_entregaInterna,limiteDeCapturaCaja) VALUES ('".$i."','".$rowId['id_entregaInternaInsertada']."','".$limiteRestanteCajas."')";
						$resCapturaCaja=mysql_query($sqlCapturaCaja,$this->conectarBd());
					}
				}*/
?>
				<script type="text/javascript"> alert("Entrega y Cajas Generadas"); ventanaDialogoVerificacionEquipoEntrega(); verDetalleValidacion('<?=$txtIdValidacion;?>'); </script>
<?
			}else{
?>
				<script type="text/javascript"> alert("Error al guardar la Entrega"); </script>
<?
			}
		}
		
		public function formularioEntregas($idModelo,$idEntregaInterna,$cantidadEquiposEmpacados,$idValidacion){
			$sqlDestino="select * from cat_destinos";
			$resDestino=mysql_query($sqlDestino,$this->conectarBd());
			$sqlModelo="select * from cat_modradio where id_modelo='".$idModelo."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);
?>
			<input type="hidden" name="txtIdModeloEntrega" id="txtIdModeloEntrega" value="<?=$idModelo;?>" />
			<input type="hidden" name="txtIdEntregaInterna" id="txtIdEntregaInterna" value="<?=$idEntregaInterna;?>" />
			<input type="hidden" name="txtCantidadEquiposEmpacados" id="txtCantidadEquiposEmpacados" value="<?=$cantidadEquiposEmpacados;?>" />
			<input type="hidden" name="txtIdValidacion" id="txtIdValidacion" value="<?=$idValidacion;?>" />
			<table width="375" border="0" cellpadding="1" cellspacing="1" style="margin: 5px;">
				<tr>
					<td colspan="2" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Entregas</td>					
				</tr>
				<tr>
					<td colspan="2" style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Equipos Disponibles para Capturar: <?=$cantidadEquiposEmpacados;?></td>					
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">P.O.:</td>
					<td><input type="text" name="poEntrega" id="poEntrega"></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Release:</td>
					<td><input type="text" name="txtReleaseEntrega" id="txtReleaseEntrega" style="width: 50px;"> - <?=$rowModelo["modelo"];?></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Fecha:</td>
					<td>
						<input type="text" name="Fecha1" id="Fecha1" readonly="readonly"/>
						<input type="button" id="lanzador1" value="..." />
						<script type="text/javascript">					
						Calendar.setup({
							inputField     :    "Fecha1",      // id del campo de texto
							ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
							button         :    "lanzador1"   // el id del botón que lanzará el calendario
						});										
									
						</script>
					</td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Concepto:</td>
					<td><input type="text" name="conceptoEntrega" id="conceptoEntrega"></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Cantidad:</td>
					<td><input type="text" name="cantidadEntrega" id="cantidadEntrega"></td>
				</tr>
				<tr>
					<td style="height:25px; text-align:left; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Destino:</td>
					<td>
<?
			if(mysql_num_rows($resDestino)==0){
				echo "No hay Destinos Capturados en el Catalogo";
			}else{
?>
					<select name="txtDestinoEntrega" id="txtDestinoEntrega">
						<option value="">Selecciona...</option>
<?
				while($rowDestino=mysql_fetch_array($resDestino)){
?>
						<option value="<?=$rowDestino["id"];?>"><?=$rowDestino["destino"];?></option>
<?
				}
?>
					</select>
<?
			}
?>						
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #CCC;"</td>					
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;"><input type="button" value="Cancelar" /><input type="button" value="Guardar" onclick="guardarEntrega()" /></td>					
				</tr>				
			</table>
<?
		}
		
		public function mostrarListadoImeisARevisar($imei){
			$sqlListadoActivos="Select * from equipos where imei='".$imei."'";
			$sqlListadoInactivos="Select * from equipos_enviados where imei='".$imei."'";
			$resImeiListadoActivos=mysql_query($sqlListadoActivos,$this->conectarBd());
			$resImeiListadoInactivos=mysql_query($sqlListadoInactivos,$this->conectarBd());
?>			
			<table width="96%" align="left" border="0" cellpadding="1" cellspacing="0" style="background:#FFF; margin-left:5px;">
				<tr>
					<td colspan="7" style="font-size:12px; font-weight:bold;">Resultados encontrados:</td>
				</tr>
				<tr>
					<td colspan="7">Equipos Activos</td>
				</tr>
				<tr>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Imei</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Sim</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Serie</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Lote</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Status</td>
				</tr>				
<?
			while($rowActivos=mysql_fetch_array($resImeiListadoActivos)){
?>
				<tr>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowActivos["imei"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowActivos["sim"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowActivos["serial"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowActivos["lote"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowActivos["status"];?></td>
				</tr>
<?				
			}
?>
				<tr>
					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7">Equipos Inactivos</td>
				</tr>
				<tr>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Imei</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Sim</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Serie</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Lote</td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Status</td>
				</tr>
<?
			while($rowInactivos=mysql_fetch_array($resImeiListadoInactivos)){
?>
				<tr>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowInactivos["imei"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowInactivos["sim"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowInactivos["serial"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowInactivos["lote"];?></td>
					<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowInactivos["status"];?></td>
				</tr>
<?				
			}
?>
			</table>
<?						
		}
		
		public function validarEnviados($idEmpaque){			
			$objFunciones= new funcionesComunes();
			$sqlListado="SELECT * FROM empaque_items where id_empaque='".$idEmpaque."'";
			$resListado=mysql_query($sqlListado,$this->conectarBd());
			if(mysql_num_rows($resListado)==0){
				echo "<br><h2>No hay equipos en este entrega</h2>";
			}else{
?>
				<table width="96%" align="left" border="0" cellpadding="1" cellspacing="0" style="background:#FFF; margin-left:5px;">
					<tr>
						<td colspan="7" style="font-size:12px; font-weight:bold;">Listado de Equipos en el Empaque:</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Imei</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Sim</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Serie</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Mfgdate</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Caja</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Fecha</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Imei</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Sim</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Serie</td>						
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Acciones</td>
					</tr>
<?
				$color="#E1E1E1";
				//se comienza la validacion de la sim contra los imeis capturados
				while($rowListado=mysql_fetch_array($resListado)){
					$sqlValidar="Select imei,serial,sim from equipos_enviados where imei='".$rowListado["imei"]."'";
					$resValidar=mysql_query($sqlValidar,$this->conectarBd());
					$rowValidar=mysql_fetch_array($resValidar);
					if(mysql_num_rows($resValidar)==0){
?>						
						<tr>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["imei"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["sim"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["serial"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["mfgdate"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["id_caja"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["fecha"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="validaNok" colspan="7">Error, Verifique la informaci&oacute;n.</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;">&nbsp;</td>
						</tr>
<?						
					}else{
						$retirar=false;
						//se procede a la validacion de los imeis del empaque contra los enviados
						$imeiEnviado=$objFunciones->buscarImeiEnviado($rowListado["imei"]);
						//se valida el imei capturado contra el de la tabla equipos
						if($imeiEnviado != 1){
							$msgImei="Ok"; $colorImei="validaOk";
						}else{
							$msgImei="NOK"; $colorImei="validaNok"; $retirar=true;
						}
						//se valida el numero de serie contra los enviados
						$serialEnviado=$objFunciones->buscarSerialEnviado($rowListado["serial"]);
						if($serialEnviado != 1){
							$msgSerial="Ok"; $colorSerial="validaOk";
						}else{
							$msgSerial="NOK"; $colorSerial="validaNok"; $retirar=true;
						}
						//se valida el numero de la sim
						$simEnviada=$objFunciones->buscarSimEnviada($rowListado["sim"]);
						if($simEnviada != 1){
							$msgSim="Ok"; $colorSim="validaOk";
						}else{
							$msgSim="NOK"; $colorSim="validaNok"; $retirar=true;
						}
?>
						<tr>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["imei"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["sim"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["serial"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["mfgdate"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["id_caja"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["fecha"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorImei;?>"><?=$msgImei;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorSim;?>"><?=$msgSim;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorSerial;?>"><?=$msgSerial;?></td>							
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;">
<?
								if($retirar){
?>									
									<a href="#" onclick="verificarInformacionEnviada('<?=$rowListado["imei"];?>')" title="Verificar Informacion del Equipo" style="color:#FF0000;font-weight:bold;">Verificar</a>
<?									
								}else{
									echo "<strong>Validado</strong>";
								}
?>
							</td>
						</tr>
<?
					}
					($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
				}	
?>
				</table>
<?
			}
		}
		
		public function verDetalleValidaciones($id){			
			$sqlValidacion="select * from empaque_validaciones where id='".$id."'";
			$resValidacion=mysql_query($sqlValidacion,$this->conectarBd());
			if(mysql_num_rows($resValidacion)==0){
				echo "Error interno verifique la informacion.";
			}else{
				//se extrae la informaion para el di de las entregas
				$rowValidacion=mysql_fetch_array($resValidacion);
				$ids=$rowValidacion["id_entregas"];
				$ids=explode(",",$ids);
				//se extraen los ids y se colocan en forma de listado separando las diferentes entregas
				$contadorTotal=0;//href=""onclick="actualizarValidacionDetalleValidaciones('<?=$id;>')"
?>
					<div id="barraOpcionesEmpaque" style="width:99%;border:1px solid #CCC;background:#e1e1e1;height:20px;padding:5px;">
						<a href="#" onclick="verDetalleValidacion('<?=$id;?>')" title="Actualizar el Panel" style="color:blue; font-size:10px;text-decoration: none;"> Actualizar Info </a> |
						<a href="exportarValidacionAgrupada.php?id_validacion=<?=$id;?>" target="_blank" title="Exportar Validacion" style="color:blue; font-size:10px;text-decoration: none;"> Exportar Archivo </a> |
						<!--<a href="../mod_scripts/captura_archivo_validacion.php?id_validacion=<?=$id;?>" target="_blank" title="Agregar Archivo de Validacion" style="color:blue; font-size:10px;text-decoration: none;"> Agregar Archivo de Validaci&oacute;n </a> |-->
						<a href="#" onclick="actualizarValidacionDetalleValidaciones('<?=$id?>')" title="Agregar Archivo de Validacion" style="color:blue; font-size:10px;text-decoration: none;"> Agregar Archivo de Validaci&oacute;n </a>
						<div class="opcionesEnsambleFinalizar" onclick="finalizarEnviosEntregasFinal('<?=$id;?>')" style="">Finalizar Entregas</div>
					</div>
					<table border="0" cellpadding="1" cellspacing="0" width="700" style="margin:5px;font-size:10px;border:1px solid #CCC;">
						<tr>
							<td colspan="6" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Resumen del acumulado de Entregas con numero <?=$id;?></td>
						</tr>
						<tr>
							<td width="100" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Modelo</td>
							<td width="100" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Cantidad</td>
							<td width="100" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Entregables</td>
							<td width="100" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Inconsistencias</td>
							<td width="100" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Equipos disponibles para Capturar</td>
							<td width="150" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Acciones</td>
						</tr>
<?
				//se efectua el resumen
				$color1="#F0F0F0";
				for($j=0;$j<count($ids);$j++){
					//se ejecutan las consultas
					$sqlResumenPrevio="select count(*) as Filas,empaque.modelo AS idModelo,cat_modradio.modelo as modeloRadio from (empaque inner join empaque_items on empaque.id=empaque_items.id_empaque) inner join cat_modradio on empaque.modelo=cat_modradio.id_modelo where empaque.id='".$ids[$j]."' group by empaque.modelo";
					$resResumenPrevio=mysql_query($sqlResumenPrevio,$this->conectarBd());
					$rowResumenPrevio=mysql_fetch_array($resResumenPrevio);
					
					$sqlEntregables="select count(*) as Filas,empaque.modelo,cat_modradio.modelo as modeloRadio from (empaque inner join empaque_items on empaque.id=empaque_items.id_empaque) inner join cat_modradio on empaque.modelo=cat_modradio.id_modelo where empaque.id='".$ids[$j]."' AND statusEntrega='OK' group by empaque.modelo";
					$resEntregables=mysql_query($sqlEntregables,$this->conectarBd());
					$entregablesOk=mysql_fetch_array($resEntregables);
					
					$sqlEntregablesNok="select count(*) as Filas,empaque.modelo,cat_modradio.modelo as modeloRadio from (empaque inner join empaque_items on empaque.id=empaque_items.id_empaque) inner join cat_modradio on empaque.modelo=cat_modradio.id_modelo where empaque.id='".$ids[$j]."' AND statusEntrega='NOK' group by empaque.modelo";
					$resEntregablesNok=mysql_query($sqlEntregablesNok,$this->conectarBd());
					$entregablesNok=mysql_fetch_array($resEntregablesNok);
					//se verifican las entregas
					$sqlEquiposEmpacados="select sum(cantidad) as cantidad from entregas_nextel where id_entrega_interna='".$ids[$j]."'";
					$resEquiposEmpacados=mysql_query($sqlEquiposEmpacados,$this->conectarBd());
					$rowEquiposEmpacados=mysql_fetch_array($resEquiposEmpacados);
					//se enlistan las entregas disponibles
					$sqlEntregasporEntrega="Select entregas_nextel.id AS id,concepto,po,fecha,cantidad,cat_destinos.destino as destino from entregas_nextel inner join cat_destinos on entregas_nextel.destino=cat_destinos.id  where id_entrega_interna='".$ids[$j]."'";
					$resEntregasporEntrega=mysql_query($sqlEntregasporEntrega,$this->conectarBd());
					$contadorTotal+=$rowResumenPrevio["Filas"];					
					
					$entregablesOk1=0;
					$entregablesNok1=0;
					$equiposEmpacados1=0;
					
					if($entregablesOk["Filas"]==0){
						$entregablesOk1=0;
					}else{
						$entregablesOk1=$entregablesOk["Filas"];
					}
					
					if($entregablesNok["Filas"]==0){
						$entregablesNok1=0;
					}else{
						$entregablesNok1=$entregablesNok["Filas"];
					}
					
					if(mysql_num_rows($resEquiposEmpacados)==0){
						$equiposEmpacados1=$entregablesOk1;
					}else{
						$equiposEmpacados1=$entregablesOk["Filas"]-$rowEquiposEmpacados["cantidad"];
					}
					
					$contadorEntregablesOk+=$entregablesOk1;
					$contadorEntregablesNok+=$entregablesNok1;
					$contadorEquiposEmpacados+=$equiposEmpacados1;
?>
						<tr>
							<td style="background:<?=$color1;?>;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowResumenPrevio["modeloRadio"];?></td>
							<td style="background:<?=$color1;?>;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowResumenPrevio["Filas"];?></td>
							<td style="background:<?=$color1;?>;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$entregablesOk1;?></td>
							<td style="background:<?=$color1;?>;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$entregablesNok1;?></td>
							<td style="background:<?=$color1;?>;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$equiposEmpacados1;?></td>
							<td style="background:<?=$color1;?>;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;">
<?
						if($equiposEmpacados1 != 0){
?>
							<a href="#" onclick="capturarEntregas('<?=$rowResumenPrevio["idModelo"];?>','<?=$ids[$j]?>','<?=$equiposEmpacados1;?>','<?=$id;?>')" title="Capturar Equipos" style="color: blue;text-decoration: none;">A&ntilde;adir Entrega</a>
<?
						}else{
							echo "N/A";
						}
?>
								
							</td>
						</tr>
						<tr>
							<td colspan="6">							
<?
						if(mysql_num_rows($resEntregasporEntrega)==0){
							echo "<span style='font-size:12px;color:red;'>No se han capturado entregas.</span>";
						}else{
?>
							<table border="0" cellpading="1" cellspacing="1" width="96%" style="margin-left: 30px;">
								<tr>
									<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Entrega</td>
									<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">OCA</td>
									<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Fecha</td>
									<td width="30%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Destino</td>
									<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Cantidad</td>									
									<td width="40%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Acciones</td>
								</tr>
<?
							while($rowEntregaporEntrega=mysql_fetch_array($resEntregasporEntrega)){								
								//se verifica si se cuenta con cajas en la captura
								$sqlCajasCaptura="SELECT * from entregas_nextel_cajas where id_entregaInterna='".$rowEntregaporEntrega["id"]."'";
								$resCajasCaptura=mysql_query($sqlCajasCaptura,$this->conectarBd());
?>
								<tr class="resultadosEntregas" style="background: #A9BCF5;">
									<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;border-left:1px solid #666;"><?=$rowEntregaporEntrega["concepto"];?></td>
									<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;border-left:1px solid #666;"><?=$rowEntregaporEntrega["po"];?></td>
									<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowEntregaporEntrega["fecha"];?></td>
									<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowEntregaporEntrega["destino"];?></td>
									<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowEntregaporEntrega["cantidad"];?></td>									
									<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;">
									<!--<a href="#" onclick="modificarEntrega('<?=$rowEntregaporEntrega["id"];?>','<?=$rowResumenPrevio["idModelo"];?>','<?=$ids[$j]?>','<?=$equiposEmpacados1;?>','<?=$id;?>')" style="color:blue;">Modificar</a> |-->
									<a href="impresionSalida.php?n=<?=$rowEntregaporEntrega["id"];?>" target="_blank" style="color:#FFF;text-decoration: none;" title="Imprimir Salida"><img src="../../img/print-icon.png" border="0"></a> |
									<a href="exportarSalida.php?n=<?=$rowEntregaporEntrega["id"];?>" target="_blank" style="color:#FFF;text-decoration: none;" title="Exportar Salida a Excel"><img src="../../img/excel_export.png" border="0"></a> |
									<a href="#" onclick="eliminarEntrega('<?=$rowEntregaporEntrega["id"];?>','<?=$rowEntregaporEntrega["cantidad"];?>','<?=$id;?>')" style="color:#FFF;text-decoration: none;" title="Eliminar Entrega"><img src="../../img/icon_delete.gif" border="0"></a>
									</td>
								</tr>
								<tr>
									<td colspan="6" style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;">
<?
									if(mysql_num_rows($resCajasCaptura)==0){
										echo "La Entrega no cuenta con cajas disponibles";
									}else{
?>
										<table border="0" cellpading="1" cellspacing="1" width="96%" style="margin-left: 30px;">
											<tr>
												<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Caja</td>
												<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Cantidad</td>
												<td width="10%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Limite</td>
												<td width="30%" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Opciones</td>												
											</tr>
<?
										while($rowCajasCaptura=mysql_fetch_array($resCajasCaptura)){
											$sqlCapturadosEntrega="select count(*) as total from entregas_nextel_items where id_entrega='".$rowEntregaporEntrega["id"]."' and numeroCajaFinal='".$rowCajasCaptura["numeroCaja"]."'";
											$resCapturadosEntrega=mysql_query($sqlCapturadosEntrega,$this->conectarBd());
											$rowCapturadosEntrega=mysql_fetch_array($resCapturadosEntrega);
											$sqlLimitesCajas="select limiteDeCapturaCaja FROM entregas_nextel_cajas WHERE id_entregaInterna='".$rowEntregaporEntrega["id"]."' and numeroCaja='".$rowCajasCaptura["numeroCaja"]."'";
											$resLimitesCajas=mysql_query($sqlLimitesCajas,$this->conectarBd());
											$rowLimitesCajas=mysql_fetch_array($resLimitesCajas);
?>
											<tr class="resultadosEntregasCajas">
												<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;border-left:1px solid #666;"><?=$rowCajasCaptura["numeroCaja"];?></td>
												<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowCapturadosEntrega["total"];?></td>
												<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;"><?=$rowLimitesCajas["limiteDeCapturaCaja"];?></td>
												<td style="text-align: center;height:15px;padding:5px;text-align:center;border-bottom:1px solid #666;">
<?
										if($rowCapturadosEntrega["total"] != $rowLimitesCajas["limiteDeCapturaCaja"]){
											if($rowCapturadosEntrega["total"]==0){//se abre el formulario para una captura inicial
?>
											<a href="#" onclick="capturarEntrega('<?=$rowEntregaporEntrega["id"];?>','<?=$rowEntregaporEntrega["concepto"]?>','<?=$rowResumenPrevio["modeloRadio"]?>','<?=$id?>','<?=$rowCajasCaptura["numeroCaja"];?>','<?=$rowCapturadosEntrega["total"];?>','<?=$rowResumenPrevio["idModelo"];?>','<?=$rowEntregaporEntrega["po"];?>')" style="color:blue;">Capturar Caja</a>
<?
											}else if($rowCapturadosEntrega["total"] !=0){//se continua con la captura previamente realizada para una nueva
?>
											<a href="#" onclick="capturarEntrega('<?=$rowEntregaporEntrega["id"];?>','<?=$rowEntregaporEntrega["concepto"]?>','<?=$rowResumenPrevio["modeloRadio"]?>','<?=$id?>','<?=$rowCajasCaptura["numeroCaja"];?>','<?=$rowCapturadosEntrega["total"];?>','<?=$rowResumenPrevio["idModelo"];?>','<?=$rowEntregaporEntrega["po"];?>')" style="color:blue;">Usar Caja Actual</a>
<?
											}
										}else{
											echo "Caja Capturada en su Totalidad";
										}
?>
												</td>
											</tr>
<?
										}
?>
										</table>
<?
									}
?>
									</td>
								</tr>
<?
							}
?>
							</table><br>
<?
						}
?>
							
							</td>
						</tr>
<?
					//($color1=="#FFF") ? $color1="#f0f0f0" : $color1="#FFF";
				}
?>
						<tr>
							<td style="text-align:center;font-weight:bold;height:15px;padding:5px; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Total</td>
							<td style="text-align:center;font-weight:bold;height:15px;padding:5px; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;"><?=$contadorTotal;?></td>
							<td style="text-align:center;font-weight:bold;height:15px;padding:5px; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;"><?=$contadorEntregablesOk;?></td>
							<td style="text-align:center;font-weight:bold;height:15px;padding:5px; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;"><?=$contadorEntregablesNok;?></td>
							<td style="text-align:center;font-weight:bold;height:15px;padding:5px; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;"><?=$contadorEquiposEmpacados;?></td>
							<td style="text-align:center;font-weight:bold;height:15px;padding:5px; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">&nbsp;</td>
						</tr>
					</table>
<?
				exit;
				for($i=0;$i<count($ids);$i++){					
					//se extrae la informacion de las entregas para poder mostrar el resumen de lo filtrado
					$sqlResumen="select cat_modradio.modelo as modelo,empaque_items.imei as imei,empaque_items.sim as sim,empaque_items.serial as serial,empaque_items.mfgdate as mfgdate,empaque_items.id_caja,equipos.status as status,statusValidacion,statusEntrega
					from ((empaque inner join empaque_items on empaque.id=empaque_items.id_empaque) inner join cat_modradio on empaque.modelo=cat_modradio.id_modelo) inner join equipos on empaque_items.imei=equipos.imei
					where empaque.id='".$ids[$i]."'";
					$resResumen=mysql_query($sqlResumen,$this->conectarBd());
					//estructura
?>
					<table border="0" cellpadding="1" cellspacing="1" width="98%" style="margin:5px;font-size:10px;">
						<tr>
							<td colspan="9" style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Resumen - Total agrupados <?=mysql_num_rows($resResumen);?></td>
						</tr>
						<tr>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Modelo</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Imei</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Sim</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Serial</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">MfgDate</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Caja</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Status</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Status Entrega</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#CCC;">Status Validacion</td>
						</tr>
<?
					$color="#f0f0f0";
					while($rowResumen=mysql_fetch_array($resResumen)){
						if($rowResumen["statusEntrega"]=="OK"){
							$fondo="green"; $fuente="white";
						}else{
							$fondo="red"; $fuente="white";
						}
?>
						<tr>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["modelo"];?></td>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["imei"];?></td>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["sim"];?></td>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["serial"];?></td>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["mfgdate"];?></td>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["id_caja"];?></td>
							<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["status"];?></td>
							<td style="background:<?=$fondo;?>;color:<?=$fuente;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["statusEntrega"];?></td>
							<td style="background:<?=$fondo;?>;color:<?=$fuente;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowResumen["statusValidacion"];?></td>
						</tr>
<?
						($color=="#f0f0f0") ? $color="#fff" : $color="#f0f0f0";
					}
?>						
					</table>
<?
				}
			}
		}
		
		public function moverEntregasAValidar($entregas){
			$id_entregas=$entregas;
			$entregas=explode(",",$entregas); 
			for($i=0;$i<count($entregas);$i++){
				$equiposActualizados=0; $equiposNoActualizados=0;
				//se actualiza el status de cada entrega seleccionada
				$sqlActualizaEntrega="UPDATE empaque set status='En Validacion' where id='".$entregas[$i]."'";
				$resActualizacionEntrega=mysql_query($sqlActualizaEntrega,$this->conectarBd());
				if(mysql_affected_rows() >= 1){
					//echo "Entregas Actualizadas.";
					//se actualizan los equipos de la entrega a Validacion
					echo $sqlActEquipos="select imei from empaque_items where id_empaque='".$entregas[$i]."'";
					$resActEquipos=mysql_query($sqlActEquipos,$this->conectarBd());
					while($rowActEquipos=mysql_fetch_array($resActEquipos)){
						//se actualiza cada imei para que se marque como Validando
						$sqlEquipoAct="UPDATE equipos set status='Validando',statusProceso='Validacion',statusEmpaque='Validando' WHERE imei='".$rowActEquipos["imei"]."'";
						$resEquipoAct=mysql_query($sqlEquipoAct,$this->conectarBd());
						if(mysql_affected_rows()>=1){
							$equiposActualizados+=1;
						}else{
							$equiposNoActualizados+=1;
						}
					}
					echo "<script type='text/javascript'> alert('Equipos Actualizados: '".$equiposActualizados."' \n\n Equipos NO Actualizados: '".$equiposNoActualizados."'); </script>";
				}else{
					echo "Error al Actualizar las Entregas";
				}
			}
			//se insertan los valores en la nueva instancia para visualizar el packina validar
			echo "<br>".$sqlInsertaEntregas="INSERT INTO empaque_validaciones (fecha,hora,id_entregas) VALUES ('".date("Y-m-d")."','".date("H:i:s")."','".$id_entregas."')";
			$resinsertaEntregas=mysql_query($sqlInsertaEntregas,$this->conectarBd());
			if($resinsertaEntregas){
				echo "<script type='text/javascript'> alert('Entregas Agrupadas con Exito'); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Agrupar las Entregas \n\n Verifique la informacion'); </script>";
			}
		}
		
		public function retirarimeiEmpaque($idEmpaque,$imei){
			//se procede a retirar el imei del empaque
			$sqlRetirar="DELETE FROM empaque_items where imei='".$imei."' AND id_empaque='".$idEmpaque."'";
			$resRetirar=mysql_query($sqlRetirar,$this->conectarBd());
			if(mysql_affected_rows() >= 1){
				echo "Imei (".$imei.") retirado.<br><a href='#' onclick='cerrarMensajeNotificacion()' title='Cerrar Mensaje' style='color:blue;'>Cerrar</a>";
			}else{
				echo "Error al retirar el Imei";
			}
		}
		
		public function validarSims($idEmpaque){
			$objFunciones= new funcionesComunes();
			$sqlListado="SELECT * FROM empaque_items where id_empaque='".$idEmpaque."'";
			$resListado=mysql_query($sqlListado,$this->conectarBd());
			if(mysql_num_rows($resListado)==0){
				echo "<br><h2>No hay equipos en este entrega</h2>";
			}else{
?>
					<table width="96%" align="left" border="0" cellpadding="1" cellspacing="0" style="background:#FFF; margin-left:5px;">
						<tr>
							<td colspan="7" style="font-size:12px; font-weight:bold;">Listado de Equipos en el Empaque:</td>
						</tr>
						<tr>
							<td colspan="7">&nbsp;</td>
						</tr>
						<tr>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Imei</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Sim</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Serie</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Mfgdate</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Caja</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Fecha</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Imei</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Sim</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Serie</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Enviado</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Imei No Enviar</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Serie No Enviar</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Scrap</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Val. Sim Env</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Acciones</td>
						</tr>
<?
				$color="#E1E1E1";
				//se comienza la validacion de la sim contra los imeis capturados
				while($rowListado=mysql_fetch_array($resListado)){
					$sqlValidar="Select imei,serial,sim from equipos where imei='".$rowListado["imei"]."'";
					$resValidar=mysql_query($sqlValidar,$this->conectarBd());
					$rowValidar=mysql_fetch_array($resValidar);
					if(mysql_num_rows($resValidar)==0){
?>						
						<tr>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["imei"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["sim"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["serial"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["mfgdate"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["id_caja"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;"><?=$rowListado["fecha"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="validaNok" colspan="7">Error, Verifique la informaci&oacute;n.</td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;">&nbsp;</td>
						</tr>
<?						
					}else{
						$retirar=false;
						//se valida el imei capturado contra el de la tabla equipos
						if($rowListado["imei"]==$rowValidar["imei"]){
							$msgImei="Ok"; $colorImei="validaOk";
						}else{
							$msgImei="NOK"; $colorImei="validaNok"; $retirar=true;
						}
						//se valida la sim capturada contra la capturada en la linea
						if($rowListado["sim"]==$rowValidar["sim"]){
							$msgSim="Ok"; $colorSim="validaOk";
						}else{
							$msgSim="NOK"; $colorSim="validaNok"; $retirar=true;
						}
						//se valida el numero de serie
						if(strtoupper($rowListado["serial"])==strtoupper($rowValidar["serial"])){
							$msgSerial="Ok"; $colorSerial="validaOk";
						}else{
							$msgSerial="NOK"; $colorSerial="validaNok"; $retirar=true;
						}
						//se valida si ya esta enviado
						$enviado=$objFunciones->buscarImeiEnviado($rowListado["imei"]);
						if($enviado==1){
							$msgEnviado="NOK"; $colorEnviado="validaNok"; $retirar=true;
						}else{
							$msgEnviado="Ok"; $colorEnviado="validaOk";
						}
						//se valida si el imei esta como no enviar
						$imeiNoEnviar=$objFunciones->buscarNoEnviar($rowListado["imei"]);
						if($imeiNoEnviar==1){
							$msgImeiNoEnviar="NOK"; $colorImeiNoEnviar="validaNok"; $retirar=true;
						}else{
							$msgImeiNoEnviar="Ok"; $colorImeiNoEnviar="validaOk";
						}
						//se valida el numero de serie
						$serieNoEnviar=$objFunciones->buscarSerieNoEnviar($rowListado["serial"]);
						if($serieNoEnviar==1){
							$msgSerieNoEnviar="NOK"; $colorSerieNoEnviar="validaNok"; $retirar=true;
						}else{
							$msgSerieNoEnviar="Ok"; $colorSerieNoEnviar="validaOk";
						}
						//se valida que no este clasificado como SCRAP
						$esScrap=$objFunciones->buscarImeiScrap($rowListado["imei"]);
						if($esScrap==1){
							$msgScrap="NOK"; $colorScrap="validaNok"; $retirar=true;
						}else{
							$msgScrap="Ok"; $colorScrap="validaOk";
						}
						$simBase=$objFunciones->buscarSim($rowListado["sim"]);
						if($simBase==1){
							$msgSimEnviada="NOK"; $colorSimEnviada="validaNok"; $retirar=true;
						}else{
							$msgSimEnviada="Ok"; $colorSimEnviada="validaOk";
						}
?>
						<tr>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["imei"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["sim"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["serial"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["mfgdate"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["id_caja"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;"><?=$rowListado["fecha"];?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorImei;?>"><?=$msgImei;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorSim;?>"><?=$msgSim;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorSerial;?>"><?=$msgSerial;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorEnviado;?>"><?=$msgEnviado;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorImeiNoEnviar;?>"><?=$msgImeiNoEnviar;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorSerieNoEnviar;?>"><?=$msgSerieNoEnviar;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorScrap;?>"><?=$msgScrap;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC;" class="<?=$colorSimEnviada;?>"><?=$msgSimEnviada;?></td>
							<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;border-right:1px solid #CCC; background:<?=$color;?>;">
<?
								if($retirar){
?>									
									<a href="#" onclick="retirarEquipoEntrega('<?=$idEmpaque;?>','<?=$rowListado["imei"];?>')" title="Retirar Equipo de la entrega" style="color:#FF0000;font-weight:bold;">Retirar Equipo</a>
<?									
								}else{
									echo "<strong>Validado</strong>";
								}
?>
							</td>
						</tr>
<?
					}//fin if
					($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
				}//fin while
?>
						<tr>
							<td colspan="7">&nbsp;</td>
						</tr>
					</table>
<?
			}
		}
		
		public function verFormatoListaEmpaque($idEmpaque){
			$sqlListado="SELECT * FROM empaque_items where id_empaque='".$idEmpaque."'";
			$resListado=mysql_query($sqlListado,$this->conectarBd());
			if(mysql_num_rows($resListado)==0){
				echo "<br><h3>No hay equipos en este entrega</h3>";
			}else{
?>
				<table width="98%" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#FFF;">
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8">Listado de la Captura</td>
					</tr>
					<tr>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">&nbsp;</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Imei</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Sim</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Serial</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">MFGDATE</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Caja</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Fecha</td>
						<td style="height:25px; text-align:center; border-bottom:1px solid #CCC;font-weight:bold; border:1px solid #CCC; background:#F0F0F0;">Hora</td>
					</tr>
<?
				$color="#E1E1E1";
				while($rowListado=mysql_fetch_array($resListado)){
?>
					<tr>
						<td style="background:<?=$color;?>;">&nbsp;</td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["imei"];?></td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["sim"];?></td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["serial"];?></td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["mfgdate"];?></td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["id_caja"];?></td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["fecha"];?></td>
						<td style="background:<?=$color;?>;height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowListado["hora"];?></td>
					</tr>
<?
					($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
				}
?>
					<tr>
						<td colspan="8"><hr style="background:#000;"></td>
					</tr>
				</table>
<?
			}
		}
		
		public function guardarEquipoEmpaqueItems($idEmpaque,$idCaja,$valores,$idElemento,$modelo,$procesoSistema,$usrEmpaque){
			//se instancia el objeto
			$objEquipo=new funcionesComunes();
			//echo "<br>".$idElemento="#".$idElemento;
			echo "<br>".$idElemento=$idElemento;
			$valores=explode(",",$valores);
			echo "<br>Imei: ".$valores[0];
			echo "<br>Sim: ".$valores[2];
			$estaEnviado=$objEquipo->buscarImeiEnviado($valores[0]);
			$esNoEnviar=$objEquipo->buscarNoEnviar($valores[0]);
			$estaEnBd=$objEquipo->buscarImei($valores[0]);
			$estaEmpacado=$objEquipo->buscarImeiEmpacado($valores[0]);
			$esScrap=$objEquipo->buscarImeiScrap($valores[0]);
			$estaActualizado=$objEquipo->buscarImeiActualizadoNoEmpacado($valores[0]);
			$escorrectoModelo=$objEquipo->buscarImeiVsModelo($valores[0],$modelo);
			if($estaEnBd==0){
				$msgCaja="EQUIPO no existe en Base de Datos";
				$color="red";
				$fuente="white";
				//return;
			}else if($estaEnviado==1){//
				$msgCaja="Equipo enviado";
				$color="red";
				$fuente="white";
				//return;
			}else if($esNoEnviar==1){
				$msgCaja="Equipo no Enviar";
				$color="red";
				$fuente="white";
				//return;
			}else if($estaEmpacado==1){
				$msgCaja="Equipo empacado";
				$color="red";
				$fuente="white";
				//return;
			}else if($esScrap==1){
				$msgCaja="Equipo marcado como Scrap";
				$color="red";
				$fuente="white";
			}else if($estaActualizado==1){
				$msgCaja="Equipo actualizado No Empacado";
				$color="orange";
				$fuente="white";
			}else if($escorrectoModelo==0){
				$msgCaja="Modelo Incorrecto del Equipo";
				$color="red";
				$fuente="white";
			}else{
				//se extrae la informacion del equipo
				echo "<br>".$sqlEquipo="SELECT * from equipos where imei='".$valores[0]."'";
				$resEquipo=mysql_query($sqlEquipo,$this->conectarBd());
				$rowRadio=mysql_fetch_array($resEquipo);
				if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusIngenieria"]=="ING_OK" && $rowRadio["statusEmpaque"]=="N/A"){
					//se inserta en el detalle de la caja y se actualiza el equipo
					echo "<br>".$sqlActualiza="update equipos set statusEmpaque='EMPACADO',statusProceso='Empacado' WHERE imei='".$valores[0]."'";
					//se ejecutan las consultas
					$resActualizaEquipo=mysql_query($sqlActualiza,$this->conectarBd());
					//consulta de insercion
					echo "<br>".$sqlItems="INSERT INTO empaque_items (id_empaque,imei,sim,serial,mfgdate,id_caja,fecha,hora) values('".$idEmpaque."','".$valores[0]."','".$valores[2]."','".$valores[1]."','".$valores[3]."','".$idCaja."','".date("Y-m-d")."','".date("h:i:s")."')";	
					if($resActualizaEquipo){
						//se guarda el detalle del radio
						$objEquipo->guardaDetalleSistema($procesoSistema,$usrEmpaque,$valores[0]);
						//se procede a insertar el item del empaque
						$resItemEmpaque=mysql_query($sqlItems,$this->conectarBd());
						if($resItemEmpaque){
							$msgCaja="Guardado";
							$color="green";
							$fuente="white";
						}else{
							$msgCaja="Actualizado / Error";
							$color="red";
							$fuente="white";
						}
					}else{
						$msgCaja="No Actualizado";
						$color="red";
						$fuente="white";
					}
				}else if($rowRadio["status"]=="WIP" && $rowRadio["statusProceso"]=="Empaque" && $rowRadio["statusEmpaque"]=="EMPACADO"){
					$msgCaja="Equipo empacado en otro envio";
					$color="red";
					$fuente="white";
				}else{
					$msgCaja="Verifique el Equipo";
					$color="orange";
					$fuente="black";
				}
				
				//se escribe el resultado en el elemento indicado
				echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
				echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
				echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
			}
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').value='".$msgCaja."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.background='".$color."'; </script>";
			echo "<script type='text/javascript'>document.getElementById('".$idElemento."').style.color='".$fuente."'; </script>";
		}
		
		public function nuevaEntrega(){
?>
			<table width="600" align="center" border="0" cellpadding="1" cellspacing="0" style="background:#FFF; border:1px solid #CCC;margin-top: 10px;font-size: 12px;">
				<tr>
					<td colspan="4" style="background:#000; color:#FFF; height:25px; font-weight:bold;">Empaque - Nextel</td>
				</tr>
				<tr>
					<td width="84" style="background:#f0f0f0; border:1px solid #CCC;height: 20px;padding: 5px;">Fecha</td>
					<td width="242"><input type="text" name="txtFecha" id="txtFecha" value="<?=date("Y-m-d");?>" readonly="readonly" /></td>
					<td colspan="2">&nbsp;</td> 
				</tr>
				<tr>
					<td style="background:#f0f0f0; border:1px solid #CCC;height: 20px;padding: 5px;">T&eacute;cnico</td>
					<td colspan="3"><input type="text" name="txtTecnico" id="txtTecnico" value="<?=$_SESSION['nombre_nx']." ".$_SESSION['apellido_nx'];?>" readonly="readonly" style="width:300px;" /></td>
				</tr>
				<tr>
					<td style="background:#f0f0f0; border:1px solid #CCC;height: 20px;padding: 5px;">Entrega</td>
					<td><input type="text" name="txtEntrega" id="txtEntrega" /></td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td style="background:#f0f0f0; border:1px solid #CCC;height: 20px;padding: 5px;">Modelo</td>
					<td>
						<select name="cboModelo" id="cboModelo">
						<option value="" selected="selected">Selecciona...</option>
<?
				//se extrae el catalogo de modelos
				$sqlModelo="select * from cat_modradio";
				$resModelo=mysql_query($sqlModelo,$this->conectarBd());
				if(mysql_num_rows($resModelo)==0){
					echo "No hay modelos en la Base de Datos";
				}else{
					while($rowModelo=mysql_fetch_array($resModelo)){
?>
				<option value="<?=$rowModelo['id_modelo'];?>"><?=$rowModelo['modelo'];?></option>
<?				
					}
				}
?>				
						</select>
					</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4"><hr style="background:#999999;" /></td>
				</tr>      
				<tr>
					<td colspan="3">&nbsp;</td>
					<td width="265" align="right"><input type="button" value="Guardar Informaci&oacute;n" onclick="guardaMovimiento()" /></td>
				</tr>
			</table><br /><br />
<?		
		}
		
		
		public function consultarCajasItems($idEmpaque,$idCaja){
			$sqlListar="select * from empaque_items where id_empaque='".$idEmpaque."' and id_caja='".$idCaja."'";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			if(mysql_num_rows($resListar)==0){
				echo "<br><h3>La caja esta vacia.</h3>";
			}else{
?>
				<table border="0" cellpadding="1" cellspacing="1" width="98%" style=" background:#FFF;margin:5px; border:1px solid #000;">					
					<tr>
						<td colspan="3" style="font-size:12px; font-weight:bold; height:25px; padding:5px;">Contenido de la Caja <?=$idCaja;?> | [ <a href="#" onclick="actualizaSimNextel()" style="color:#0033FF;">Actualizar Informaci&oacute;n</a> ]</td>
					</tr>
					<tr>
						<td width="20%" align="center" style="background:#000; color:#FFF;">Imei</td>
						<td width="24%" align="center" style="background:#000; color:#FFF;">Sim</td>
						<td width="24%" align="center" style="background:#000; color:#FFF;">Serial</td>
						<td width="23%" align="center" style="background:#000; color:#FFF;">Lote</td>
						<td width="9%" align="center" style="background:#000; color:#FFF;">Acciones</td>
					</tr>
<?				
				$i=0;
				while($rowItems=mysql_fetch_array($resListar)){
					$divInfo="divInfo".$i;
					$sqlDatos="select * from equipos where imei='".$rowItems['imei']."'";
					$resDatos=mysql_query($sqlDatos,$this->conectarBd());
					if(mysql_num_rows($resDatos)==0){
						$serial="N/A";
						$lote="N/A";
					}else{
						$rowDatos=mysql_fetch_array($resDatos);
						$serial=$rowDatos['serial'];
						$lote=$rowDatos['lote'];
					}
?>
					<tr>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowItems['imei'];?></td>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$rowItems['sim'];?></td>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$serial;?></td>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;"><?=$lote;?></td>
						<td style="font-size:12px; height:25px; text-align:center; border-bottom:1px solid #CCC;">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5"><div id=""></div></td>
					</tr>
<?					
				}
?>
				</table><br />
<?				
			}		
		}

		public function guardaCaja($caja,$idEmpaque){
			echo "<br>".$sqlListar="INSERT INTO caja_empaque (id_empaque,caja) VALUES ('".$idEmpaque."','".$caja."')";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			if($resListar){
?>
				<script type="text/javascript">
					verMas('<?=$idEmpaque;?>');
				</script>
<?
			}else{
?>
				<script type="text/javascript">alert('Error al guardar la caja');</script>
<?
			}
		}

		public function verDetalleEmpaque($idEmpaque){
			$sqlListar="select * from empaque where id='".$idEmpaque."'";
			$resListar=mysql_query($sqlListar,$this->conectarBd());
			$rowListar=mysql_fetch_array($resListar);
			/**************************************************************************/
			$sqlCajas="Select * from caja_empaque where id_empaque='".$idEmpaque."'";
			$resCajas=mysql_query($sqlCajas,$this->conectarBd());
			/**************************************************************************/
			$sqlModelo="select * from cat_modradio where id_modelo='".$rowListar['modelo']."'";
			$resModelo=mysql_query($sqlModelo,$this->conectarBd());
			$rowModelo=mysql_fetch_array($resModelo);
			$modelo=$rowModelo["modelo"];
?>
			<div style="height:25px; padding:7px; background:#F0F0F0; border:1px solid #CCCCCC; font-size:10px;">
				<a onclick="exportarArchivoValidacion('<?=$rowListar['id'];?>')" href="#" title="Exportar Archivo de Validacion" style="color:#03F;">Exportar Archivo de Validaci&oacute;n</a> |
				<a onclick="verFormatoLista('<?=$rowListar['id'];?>')" href="#" title="Ver Estilo Lista" style="color:#03F;">Ver en Forma de Lista</a> |
				<a onclick="validarSims('<?=$rowListar['id'];?>')" href="#" title="Validar Datos" style="color:#03F;">Validar Datos</a> |
				<a onclick="validarEnviados('<?=$rowListar['id'];?>')" href="#" title="Validar Enviados" style="color:#03F;">Validar Enviados</a>
			</div>
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="650" style="margin:25px; font-size:12px; font-family:Verdana, Geneva, sans-serif;">            	
				<tr>
					<td width="173" style="font-size:14px;background:#000; color:#FFF; height:25px; padding:5px;">Empaque interno:</td>
					<td colspan="3" style="font-size:14px;background:#000; color:#FFF; height:25px; padding:5px;"><?=$rowListar['id'];?></td>
					<td width="112" style="font-size:14px;background:#000; color:#FFF; height:25px; padding:5px; text-align:center; font-weight:bold;">Modelo</td>
				</tr>
				<tr>
					<td style="font-size:14px; background:#CCC; height:25px;">Detalle de la entrega:</td>
					<td colspan="3" style="font-size:14px; background:#CCC; height:25px;"><?=$rowListar['entrega'];?></td>
					<td rowspan="3" valign="middle" style="font-size:28px; background:#CCC; height:25px; text-align:center; font-weight:bold;"><?=$modelo;?></td>
				</tr>
				<tr>
					<td align="center" style="background:#CCC; height:25px; padding:5px; font-weight:bold;">Fecha</td>
					<td width="113" align="center" style="background:#CCC; height:25px; padding:5px; font-weight:bold;">Entrega</td>
					<td colspan="2" align="center" style="background:#CCC; height:25px; padding:5px; font-weight:bold;">Usuario</td>
				</tr>
				<tr>
					<td align="center" style="border-bottom:1px solid #CCC; height:25px; padding:5px;">&nbsp;<?=$rowListar['fecha'];?></td>
					<td align="center" style="border-bottom:1px solid #CCC; height:25px; padding:5px;"><?=$rowListar['entrega'];?></td>
					<td colspan="2" align="center" style="border-bottom:1px solid #CCC; height:25px; padding:5px;"></td>
				</tr>
				<tr>
					<td colspan="5" align="right">
					<input type="hidden" name="txtEmpaque" id="txtEmpaque" value="<?=$idEmpaque;?>" />
					<input type="button" value="Nueva caja" onclick="nuevaCaja()" style=" width:120px;height:30px; border:1px solid #000; font-size:10px; background:#06F; color:#FFF; font-weight:bold;" />
				    </td>
				</tr>
			</table><br /><br />
			
			<div id="listadoEmpaqueCajas" style="border:1px solid #CCC; margin:4px; font-size:10px; width:99%; overflow:auto; display:none;"></div>
			<table border="0" cellpadding="1" cellspacing="1" width="650" style="margin-left:25px; font-size:10px; font-family:Verdana, Geneva, sans-serif;">
				<tr>
					<td colspan="3" style="height:25px; padding:5px; font-weight:bold; font-size:12px;">Cajas en esta entrega:</td>
				</tr>
				<tr>
					<td colspan="3"><hr style="background:#666666;" /></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
<?
			if(mysql_num_rows($resCajas)==0){
?>
				<tr>
					<td colspan="3" style="height:25px; padding:5px; font-weight:bold; font-size:12px; color:#F00;">No hay cajas asociadas a esta entrega</td>
				</tr>
<?				
			}else{
?>
				<tr>
					<td width="9%" align="center" style="background:#000; color:#FFF; height:25px; padding:5px; font-weight:bold;">Caja</td>
					<td width="25%" align="center" style="background:#000; color:#FFF; height:25px; padding:5px; font-weight:bold;">Cant Equipos</td>
					<td width="66%" align="center" style="background:#000; color:#FFF; height:25px; padding:5px; font-weight:bold;">Acciones</td>
				</tr>
<?			
				$i=0;
				$color="#E1E1E1";
				while($rowCajas=mysql_fetch_array($resCajas)){
					$idInfoCaja="divCaja".$i;
					$sqlEquiposCaja="SELECT count( * ) as total FROM `empaque_items` WHERE id_caja = '".$rowCajas['caja']."' and id_empaque='".$idEmpaque."' ";
					$resEquiposCaja=mysql_query($sqlEquiposCaja,$this->conectarBd());
					$rowEquiposCaja=mysql_fetch_array($resEquiposCaja);
?>
				<tr>
					<td align="center" style="height:25px; padding:5px; background:<?=$color;?>;font-weight:bold; font-size:14px;"><?=$rowCajas['caja'];?></td>
					<td align="left" style="height:25px; font-size:14px; text-align:center; font-weight:bold; background:<?=$color;?>;"><?=$rowEquiposCaja["total"];?></td>
					<td align="center" style="height:25px; font-size:12px; background:<?=$color;?>; height:25px; padding:5px;">&nbsp;
						<a href="#" onclick="infoCaja('<?=$idEmpaque;?>','<?=$rowCajas['caja'];?>','<?=$idInfoCaja;?>')" style="text-decoration:none; color:#0066FF;">Ver caja</a> | 
						<a href="#" onclick="capturarDetalleCaja('<?=$rowListar['fecha'];?>','<?=$_SESSION['id_usuario_nx'];?>','<?=$rowListar['entrega']?>','<?=$rowCajas['caja'];?>','<?=$rowListar['modelo']?>','<?=$idEmpaque;?>','<?=$modelo;?>')" style="text-decoration:none; color:#0066FF;">Capturar equipos en esta Caja</a>
					</td>
				</tr>
				<tr>
					<td colspan="5" style="background:#999;"><div id="<?=$idInfoCaja;?>"></div></td>
				</tr>
<?				
					($color=="#E1E1E1") ? $color="#FFF" : $color="#E1E1E1";
					$i+=1;
				}	
			}
?>			
			</table>
<?			
		}

		public function listarCapturas($filtro){
			$RegistrosAMostrar=20;
			//estos valores los recibo por GET
			if(isset($_POST['pag'])){
			  $RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
			  $PagAct=$_POST['pag'];
			//caso contrario los iniciamos
			}else{
			  $RegistrosAEmpezar=0;
			  $PagAct=1;
			}
			if($filtro=="capturas"){
				$sqlListar="select * from empaque where status='Captura' ORDER BY id DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlListar1="select * from empaque where status='Captura' ORDER BY id DESC";
			}else if($filtro=="validaciones"){
				$sqlListar="select * from empaque_validaciones where status='En Validacion' ORDER BY id DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
				$sqlListar1="select * from empaque_validaciones where status='En Validacion' ORDER BY id DESC";
			}
			/*echo $sqlListar;
			echo "<br>".$sqlListar1;*/
			$rs=mysql_query($sqlListar,$this->conectarBd());
			$rs1=mysql_query($sqlListar1,$this->conectarBd());
			
			//******--------determinar las páginas---------******//
			$NroRegistros=mysql_num_rows($rs1);
			$PagAnt=$PagAct-1;
			$PagSig=$PagAct+1;
			$PagUlt=$NroRegistros/$RegistrosAMostrar;
			
			//verificamos residuo para ver si llevará decimales
			$Res=$NroRegistros%$RegistrosAMostrar;
			// si hay residuo usamos funcion floor para que me devuelva la parte entera, SIN REDONDEAR, y le sumamos una unidad para obtener la ultima pagina
			if($Res>0) $PagUlt=floor($PagUlt)+1;
			
			if($NroRegistros==0){
				echo "<br>Sin registros.<br>";
			}else{
?>
			<form name="frmListadoCapturasEmpaque" id="frmListadoCapturasEmpaque">
				<div style="text-align:center; height:10px; padding:5px;font-size:10px;">
				   <a href="javascript:PaginaListadoCapturasEmpaque('1','<?=$filtro;?>')" title="Primero" style="cursor:pointer; text-decoration:none;">|&lt;</a>&nbsp;
<?
			 if($PagAct>1){ 
?>
				 <a href="javascript:PaginaListadoCapturasEmpaque('<?=$PagAnt;?>','<?=$filtro;?>')" title="Anterior" style="cursor:pointer; text-decoration:none;">&lt;&lt;</a>&nbsp;
<?
			  }
			 echo "<strong>".$PagAct."/".$PagUlt."</strong>";
			 if($PagAct<$PagUlt){
?>
				  <a href="javascript:PaginaListadoCapturasEmpaque('<?=$PagSig;?>','<?=$filtro;?>')" title="Siguiente" style="cursor:pointer; text-decoration:none;">&gt;&gt;</a>&nbsp;
<?
			 }
?>     
				  <a href="javascript:PaginaListadoCapturasEmpaque('<?=$PagUlt;?>','<?=$filtro;?>')" title="Ultimo" style="cursor:pointer; text-decoration:none;">&gt;|</a>&nbsp;        
		             </div> 
				<table border="0" cellpadding="1" cellspacing="1" width="99%" style="margin:3px; font-size:10px;background:#FFF;">
<?
				$color="#e1e1e1";
				while($rowListar=mysql_fetch_array($rs)){
					//modelo
					$sqlModelo="select * from cat_modradio where id_modelo='".$rowListar['modelo']."'";
					$resModelo=mysql_query($sqlModelo,$this->conectarBd());
					$rowModelo=mysql_fetch_array($resModelo);
					if($filtro=="capturas"){
						$sqlCantEquiposEntrega="SELECT count( * ) as filas FROM empaque_items WHERE id_empaque = '".$rowListar["id"]."'";
						$resCantEquiposEntrega=mysql_query($sqlCantEquiposEntrega,$this->conectarBd());
						$rowCantEquiposEntrega=mysql_fetch_array($resCantEquiposEntrega);
?>
					<tr>
						<td colspan="6">
							<div style="background:<?=$color;?>;" class="resultadosListaEmpaque">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td width="5%;" rowspan="3" valign="middle"><input type='checkbox' name="" id="" value="<?=$rowListar['id'];?>" /></td>
									  <td width="30%;" style="font-weight:bold;">#<?=$rowListar['id'];?></td>
									  <td width="50%" style="font-weight:bold;font-size:12px;"><?=$rowModelo['modelo'];?></td>
									  <td width="10%" rowspan="3" valign="middle"><a href="#" onclick="verMas('<?=$rowListar['id'];?>')">Ver</a></td>
									</tr>
									<tr>
									  <td width="30%"><?=$rowListar['fecha'];?></td>
									  <td width="50%"><?=$rowListar['entrega'];?></td>
									</tr>
									<tr>
									  <td width="30%" style="font-weight:bold;">Cantidad</td>
									  <td width="50%" style="font-weight:bold;"><?=$rowCantEquiposEntrega["filas"];?></td>
									</tr>
							        </table>			
							</div>
						</td>
					</tr>
<?					
					}else if($filtro=="validaciones"){
						$sqlCantEquiposValidaciones="SELECT count( * ) as filas FROM empaque_items WHERE id_empaque IN ( ".$rowListar['id_entregas']." ) ";
						$resCantEquiposValidaciones=mysql_query($sqlCantEquiposValidaciones,$this->conectarBd());
						$rowCantEquiposValidaciones=mysql_fetch_array($resCantEquiposValidaciones);
?>
					<tr>
						<td colspan="6">
							<div style="background:<?=$color;?>;" class="resultadosListaEmpaqueValidaciones">
								<table width="100%" border="0" cellspacing="1" cellpadding="1">
									<tr>
									  <td width="5%" rowspan="3" valign="middle"><input type='checkbox' name="" id="" value="<?=$rowListar['id'];?>" /></td>
									  <td width="20%" style="font-weight:bold;">#<?=$rowListar['id'];?></td>
									  <td width="70%" colspan="2"><?=$rowListar['fecha']." / ".$rowListar['hora'];?></td>
									  <td width="5%" rowspan="3" valign="middle"><a href="#" onclick="verDetalleValidacion('<?=$rowListar['id'];?>')">Ver</a></td>
									</tr>
									<tr>
									  <td colspan="2">Id's Internos: <?=substr($rowListar['id_entregas'],0,20)."...";?></td>									  
									</tr>
									<tr>
									  <td width="20%" style="font-weight:bold;">Cantidad</td>
									  <td width="70%" style="font-weight:bold;"><?=$rowCantEquiposValidaciones["filas"];?></td>
									</tr>
							        </table>
							</div>
						</td>
					</tr>
<?
					}
					($color=="#e1e1e1") ? $color="#FFF" : $color="#e1e1e1";
				}
?>
			</table>
			</form>
<?				
			}
		}
		
		public function capturaEquiposCajaItems($imei,$sim,$id_empaque,$id_caja){
			$validarStatus=$this->validarStatus($imei);
			if($validarStatus==true){	
				echo "<br>".$sqlItems="INSERT INTO empaque_items (id_empaque,imei,sim,id_caja) values('".$id_empaque."','".$imei."','".$sim."','".$id_caja."')";
				$resItems=mysql_query($sqlItems,$this->conectarBd());
				if($resItems){
					echo "<br>Equipo con Imei ($imei) y Sim ($sim) guardado.";
?>					
				<script type="text/javascript">
					armarGridCaptura('<?=$imei;?>','<?=$sim;?>');
				</script>
<?
				}else{
					echo "<br>Error al guardar la informacion del equipo";
				}
			}else{
				echo "<br><br><strong style='color:#F00;font-size:16px;'>Verifique el status del imei ($imei) y/o Tarjeta.</strong>";
				echo "<script type='text/javascript'>limpiaCajas();</script>";
			}
		}
		
		public function validarStatus($imei){
			$sqlImei="SELECT * from equipos where imei='".$imei."'";
			$resImei=mysql_query($sqlImei,$this->conectarBd());
			$rowImei=mysql_fetch_array($resImei);
			if($rowImei['statusProceso']=="Empaque" && $rowImei['statusDesensamble']=="OK" && $rowImei['statusIngenieria']=="ING_OK"){
				$validacion=true;
			}else{
				$validacion=false;
			}
			return $validacion;
		}
		
		public function capturaEquiposCaja($fecha,$txtTecnico,$txtEntrega,$modelo){
			//se inserta en la tabala de empaque para informacion
			echo "<br>".$sqlEmpaque="INSERT INTO empaque (fecha,tecnico,entrega,modelo) values ('".$fecha."','".$txtTecnico."','".$txtEntrega."','".$modelo."')";
			$resEmpaque=mysql_query($sqlEmpaque,$this->conectarBd());
			if($resEmpaque){
				echo "<br><br><strong>Registro guardado, continuando con la captura de equipos....</strong>";
				//se recupera el id de empaque y se coloca en un campo oculto
				$sql_id = "SELECT LAST_INSERT_ID() as id FROM empaque";
				$res_id=mysql_query($sql_id,$this->conectarBd());
				$row_id=mysql_fetch_array($res_id);
				echo "<script type='text/javascript'> alert('Empaque interno No: ".$row_id['id']."'); listarCapturas('capturas');</script>";
				echo "<script type='text/javascript'> $('#detalleEmpaque').html(''); </script>";
			}else{
				echo "Error al ejecutar la consulta, la caja no pudo ser guardada";
			}
		}
	}//fin de la clase
	
	
	//$objP=new modeloEnsamble();
	//$objP->prueba();
?>