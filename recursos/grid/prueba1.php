<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="grid.js"></script>
<link rel="stylesheet" type="text/css" href="grid.css" />
<script type="text/javascript">
    $(document).ready(function (){
	//se define el array para el nombre de las columnas
	nombresColumnas=new Array("Imei","Serial","Sim","MFGDATE","Mensaje")
	cargaInicial(5,"gridCapturaInformacion","controladorEnsamble.php","","errores",nombresColumnas);
	inicio();
	$("#txt_0").focus();
        $("#txt_0").removeClass("datoListado");
        $("#txt_0").addClass("elementoFocus");
    });
</script>
<div id="gridCapturaInformacion"></div>
<div id="errores"></div>