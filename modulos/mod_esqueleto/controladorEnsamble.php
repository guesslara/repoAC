<?
	include("modeloEnsamble.php");
	$objEnsamble=new modeloEnsamble();
	//print_r($_POST);
	switch($_POST['action']){
		case "nuevaEntrega":
			$objEnsamble->nuevaEntrega();
		break;
		case "actualizaDatos":
			print_r($_POST);
			$equipos=$_POST['equipos'];
			$objEnsamble->actualizaDatos($equipos,$_POST['proceso'],$_POST['id_usuarioEnsamble']);
		break;
		case "guardarEmpaque":
			print_r($_POST);
			$objEnsamble->capturaEquiposCaja($_POST['fecha'],$_POST['txtTecnico'],$_POST['txtEntrega'],$_POST['modelo']);
		break;
		case "guardaItemsEmpaque":
			print_r($_POST);
			$objEnsamble->capturaEquiposCajaItems($_POST['imei'],$_POST['sim'],$_POST['id_empaque'],$_POST['id_caja']);
		break;
		case "listarCapturas":
			$objEnsamble->listarCapturas($_POST["filtro"]);
		break;
		case "verDetalleEmpaque":
			$objEnsamble->verDetalleEmpaque($_POST['idEmpaque']);
		break;
		case "guardarCaja":
			//print_r($_POST);
			$objEnsamble->guardaCaja($_POST['caja'],$_POST['idEmpaque']);
		break;
		case "muestraInfoCaja":
			$objEnsamble->consultarCajasItems($_POST['idEmpaque'],$_POST['idCaja']);
		break;
		case "guardaEquipoEmpaque":
			print_r($_POST);	
			$objEnsamble->guardarEquipoEmpaqueItems($_POST["idEmpaque"],$_POST["idCaja"],$_POST["valores"],$_POST["idElemento"],$_POST["modelo"],$_POST["proceso"],$_POST["usrEmpaque"]);
		break;
		case "verFormatoListaEmpaque":
			//print_r($_POST);
			$objEnsamble->verFormatoListaEmpaque($_POST["idEmpaque"]);
		break;
		case "validarSims":
			//print_r($_POST);
			$objEnsamble->validarSims($_POST["idEmpaque"]);
		break;
		case "retirarimeiEmpaque":
			$objEnsamble->retirarimeiEmpaque($_POST["idEmpaque"],$_POST["imei"]);
		break;
		case "moverEntregasAValidar":
			$objEnsamble->moverEntregasAValidar($_POST["entregas"]);
		break;
		case "verDetalleValidaciones":
			$objEnsamble->verDetalleValidaciones($_POST["id"]);
		break;
		case "validarEnviados":
			$objEnsamble->validarEnviados($_POST["idEmpaque"]);	
		break;
		case "verificarInfoEnviado":
			$objEnsamble->mostrarListadoImeisARevisar($_POST["imei"]);
		break;
		case "verEntrega":
			//print_r($_POST);
			$objEnsamble->formularioEntregas($_POST["id_modelo"],$_POST["idEntregaInterna"],$_POST["cantidadEquiposEmpacados"],$_POST["idValidacion"]);
		break;
		case "guardarEntregaEmpaque":
			//print_r($_POST);			
			$objEnsamble->guardaFormEntrega($_POST["idModeloEntrega"],$_POST["poEntrega"],$_POST["releaseEntrega"],$_POST["fechaEntrega"],$_POST["conceptoEntrega"],$_POST["cantidadEntrega"],$_POST["destinoEntrega"],$_POST["idEntregaInterna"],$_POST["cantidadPorEntregar"],$_POST["txtIdValidacion"]);
		break;
		case "modificarEntrega":
			//print_r($_POST);
			$id_modelo=$_POST["id_modelo"];
			$idEntregaInterna=$_POST["idEntregaInterna"];
			$cantidadEquiposEmpacados=$_POST["cantidadEquiposEmpacados"];
			$idValidacion=$_POST["idValidacion"];
			$entregaModificar=$_POST["entregaModificar"];
			$objEnsamble->formularioEntregasModificar($id_modelo,$idEntregaInterna,$cantidadEquiposEmpacados,$idValidacion,$entregaModificar);
		break;
		case "eliminarEntrega":
			//print_r($_POST);
			$objEnsamble->eliminarEntrega($_POST["idEntregaInterna"],$_POST["cantidadEntrega"],$_POST["txtIdUsuarioEmpaque"],$_POST["idValidacion"]);
		break;
		case "guardaEquipoEmpaqueFinal":
			//print_r($_POST);
			$idEntregaInterna=$_POST["idEntregaInterna"];
			$conceptoEntrega=$_POST["conceptoEntrega"];
			$modelo=$_POST["modelo"];
			$usrEmpaque=$_POST["usrEmpaque"];
			$idElemento=$_POST["idElemento"];
			$valores=$_POST["valores"];
			$numeroCaja=$_POST["numeroCaja"];
			$cantidadCapturada=$_POST["cantidadCapturada"];
			$idModeloCaptura=$_POST["idModeloCaptura"];
			$poAValidar=$_POST["poAValidar"];
			//$objEnsamble->guardaEquiposEmpaqueFinal($idEntregaInterna,$conceptoEntrega,$modelo,$usrEmpaque,$idElemento,$valores,$numeroCaja,$cantidadCapturada,$idModeloCaptura,$poAValidar);
			$objEnsamble->verificarPO($idEntregaInterna,$conceptoEntrega,$modelo,$usrEmpaque,$idElemento,$valores,$numeroCaja,$cantidadCapturada,$idModeloCaptura,$poAValidar);
		break;
		case "agregarCajaCaptura":
			//print_r($_POST);
			$objEnsamble->agregaCajaCapturaFinal($_POST["idEntregaInterna"],$_POST["idValidacion"],$_POST["cantidadEntrega"]);
		break;
		case "finalizarEquiposFinal":
			//print_r($_POST);
			include("finalizarEquipos.php");
			$objFinalizarEquipos=new finalizarEquipos();
			$objFinalizarEquipos->finalizarEquiposBD($_POST["idEntregaInterna"]);
		break;
		case "exportarValidacion":
?>
			<script type="text/javascript">
				window.location.href="exportarValidacion.php?id_entrega=<?=$_POST['id_empaque'];?>";
			</script>
<?			
		break;
		case "exportarValidacionAgrupada":
?>
			<script type="text/javascript">
				window.location.href="exportarValidacionAgrupada.php?id_entrega=<?=$_POST['id'];?>";
			</script>
<?	
		break;
}
?>