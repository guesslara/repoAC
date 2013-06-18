<?php
    session_start();
    //print_r($_SESSION);
    $rutaExplorar="../../documentos/";
    /*if($_SERVER["HTTP_REFERER"]==""){
        echo "Acceso incorrecto";
        exit;
    }else{
        if(!isset($_SESSION["usuario_nivel"])){
            echo "Ingrese de nuevo al Sistema";
            exit;
        }
    }*/
?>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script language="javascript">
    raizDirectorio="<?=$rutaExplorar;?>";
    $(document).ready(function (){
	redimensionarPag();
        abrirDirectorio('<?=$rutaExplorar;?>','browserArchivos');
	$('#btnAtras').hide();
    });
    
    function redimensionarPag(){
            var altoDiv=$("#contenedorNavegadorArchivos").height();
            var anchoDiv=$("#contenedorNavegadorArchivos").width();		
            var altoCuerpo=altoDiv-75;
            var anchoCuerpo=anchoDiv-13;
            $("#browserArchivos").css("height",altoCuerpo+"px");            	
            $("#browserArchivos").css("width",(anchoCuerpo)+"px");
	    $("#vistaPreviaArchivo").css("height",altoCuerpo+"px");            	
            $("#vistaPreviaArchivo").css("width",(anchoCuerpo)+"px"); 
    }
    
    window.onresize=redimensionarPag;
    
    function abrirDirectorio(path){        
        $("#ubicacionDirectorios").html(path);
	$("#hdnRutaActual").attr("value",path);
        ajaxApp("browserArchivos","auxVisor.php","action=abrirDirectorio&path="+path,"POST");
	$('#btnAtras').show();
    }
    
    function actualizarDirectorio(path){
	ocultarVistaPrevia();
        $("#ubicacionDirectorios").html(path);
	$("#hdnRutaActual").attr("value",path);
        ajaxApp("browserArchivos","auxVisor.php","action=abrirDirectorio&path="+path,"POST");	
    }
    
    function cerrarVistaPrevia(){
	pathActual=$("#hdnRutaActual").val();//se recupera la ruta actual	
	actualizarDirectorio(pathActual);
    }
    
    function crearDirectorio(){
	var directorio=prompt("Introduzca el nombre del Directorio a Crear");
	if(directorio=="" || directorio == null || directorio==undefined){
	    alert("Verifique la informacion proporcionada e intentelo de nuevo");
	}else{
	    pathActual=$("#hdnRutaActual").val();
	    //alert("action=crearDir&nombreDir="+directorio+"&path="+pathActual);
	    ajaxApp("browserArchivos","auxVisor.php","action=crearDir&nombreDir="+directorio+"&path="+pathActual,"POST");
	}
	return false;
    }
    
    function retrocederDirectorio(){
	rutaActual=$("#hdnRutaActual").val();
	//alert(rutaActual);
	ajaxApp("browserArchivos","auxVisor.php","action=retrocederDirectorio&raizDirectorio="+raizDirectorio+"&rutaActual="+rutaActual,"POST");
    }
    
    function eliminaDirectorio(directorio){
	if(confirm("Realmente desea ELIMINAR: "+directorio)){
	    rutaActual=$("#hdnRutaActual").val();
	    ajaxApp("browserArchivos","auxVisor.php","action=eliminaDir&directorioEliminar="+directorio+"&rutaActual="+rutaActual,"POST");
	}else{
	    alert("Opcion invalida");
	}
    }
    
    function renombrarDirectorio(directorio,idInput,idEnlace){
	$("#"+idEnlace).hide();
	$("#"+idInput).show();
	$("#"+idInput).focus();
    }
    
    function guardarNuevoNombreDir(directorio,idInput,idEnlace,evento){
	if(evento.which==13){
	    //se recupera el nuevo nombre
	    nuevoNombre=$("#"+idInput).val();
	    rutaActual=$("#hdnRutaActual").val();
	    opciones="action=renombrarDirectorio&directorio="+directorio+"&idInput="+idInput+"&idEnlace="+idEnlace+"&nuevoNombre="+nuevoNombre+"&rutaActual="+rutaActual;
	    ajaxApp("browserArchivos","auxVisor.php",opciones,"POST");
	}
    }
    
    function editarContenido(){
	cantEditar=parseInt($("#hdnCantElementos").val());
	for(i=0;i<cantEditar;i++){
	    divEditar="#divEditar"+i;
	    $(divEditar).show();
	}
	$("#btnEditar1").hide();
	$("#btnEditar2").show();        
    }
    
    function finEditarContenido(){
        cantEditar=parseInt($("#hdnCantElementos").val());
	for(i=0;i<cantEditar;i++){
	    divEditar="#divEditar"+i;
	    $(divEditar).hide();
	}
        $("#btnEditar2").hide();        
        $("#btnEditar1").show();	
    }
    
    function mostrarFormSubirArchivos(){
        rutaActual=$("#hdnRutaActual").val();
        $("#subirArchivos").show();
        ajaxApp("detalleSubirArchivos","auxVisor.php","action=mostrarFormArchivos&rutaActual="+rutaActual,"POST");
    }
    
    function eliminarArchivo(archivo){
	if(confirm("Realmente desea ELIMINAR: "+archivo)){
	    rutaActual=$("#hdnRutaActual").val();
	    ajaxApp("browserArchivos","auxVisor.php","action=eliminaFile&archivoEliminar="+archivo+"&rutaActual="+rutaActual,"POST");
	}else{
	    alert("Opcion invalida");
	}
    }
    
    function mostrarArchivo(path){        
	$("#browserArchivos").hide();
	$("#vistaPreviaArchivo").show();
	$("#vistaPreviaArchivo").attr("src",path);
	$("#btnVistaPrevia").show();
    }
    
    function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#cargadorAcciones").show().html("<p>Cargando...</p>"); 
	},
	success:function(datos){	
		$("#cargadorAcciones").hide();
		$("#"+divDestino).show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
    }
    
    function cerrarVentanaSubirArchivos(){
        rutaActual=$("#hdnRutaActual").val();
        $("#subirArchivos").hide();
        actualizarDirectorio(rutaActual);
    }
    function ocultarVistaPrevia(){
	$("#browserArchivos").show();
	$("#vistaPreviaArchivo").hide();
    }
</script>
<style>
    body{margin: 0px;font-family:Verdana, Geneva, sans-serif;font-size: 12px;height: 100%;overflow: hidden;}
    .estiloListadoDirectorios{font-size: 10px;border-bottom: 1px solid #f0f0f0;width: auto;margin: 4px;cursor: pointer;}
    .estiloListadoDirectorios , a {text-decoration: none;color:blue;}
    .estiloListadoDirectorios:hover{background: #CED8F6;}
    .estiloNuevaCarpeta{float: left;width:90px;background: #f0f0f0;height: 15px;padding: 5px;margin-left: 4px;border: 1px solid #666;padding: 5px;font-weight: bold;text-align: center;font-size: 10px;}
    .estiloNuevaCarpeta:hover{background: #FFF;cursor: pointer;}
    #cargadorAcciones{font-weight: bold;z-index: 200;background: #F2F5B7;border: 1px solid #FF4000;top: 50%;left: 50%;margin-left: -100px;margin-top: -15px;position: absolute;width: 200px;height: 20px;padding: 10px;}
    .estiloAtras{float: left;width:50px;background: #f0f0f0;height: 15px;padding: 5px;margin-left: 4px;border: 1px solid #666;padding: 5px;font-weight: bold;text-align: center;font-size: 10px;}
    .estiloAtras:hover{background: #FFF;cursor: pointer;}
    .estiloEditar{float: left;width:90px;background: #f0f0f0;height: 15px;padding: 5px;margin-left: 4px;border: 1px solid #666;padding: 5px;font-weight: bold;text-align: center;font-size: 10px;}
    .estiloEditar:hover{background: #FFF;cursor: pointer;}
    .estiloEditarActivo{float: left;width:90px;background: #ff0000;height: 15px;padding: 5px;margin-left: 4px;border: 1px solid #666;padding: 5px;font-weight: bold;text-align: center;font-size: 10px;color: #fFF;display: none;}
    .estiloEditarActivo:hover{background: #FFF;color: #000;cursor: pointer;}
    .estiloDivHerr{display: none;float: left;margin: 3px;border-bottom: 1px solid #CCC;width: 80px;height: 20px;text-align: center;}
    .estiloSubirArchivo{float: left;width:90px;background: #f0f0f0;height: 15px;padding: 5px;margin-left: 4px;border: 1px solid #666;padding: 5px;font-weight: bold;text-align: center;font-size: 10px;}
    .estiloSubirArchivo:hover{background: #FFF;cursor: pointer;}
    .estiloCerrarVistaPrevia{display: none;float: left;width:90px;color: #FFF;background: #ff0000;height: 15px;padding: 5px;margin-left: 4px;border: 1px solid #666;padding: 5px;font-weight: bold;text-align: center;font-size: 10px;}
    .estiloCerrarVistaPrevia:hover{background: #FFF;color: #000;}
</style>
<input type="hidden" name="hdnRutaActual" id="hdnRutaActual" value="" />
<input type="hidden" name="hdnCantElementos" id="hdnCantElementos" value="" />
<div id="cargadorAcciones">Aplicando cambios...</div>
<div id="contenedorNavegadorArchivos" style="width: 99.5%;height: 98.5%;border: 1px solid #666;background: #CCC;margin: 2px;position:absolute;">
    <div id="barraNavegacion" style="width: 99.2%;height: 26px; border: 1px solid #666;background: #FFF;margin: 5px 5px 1px 5px;">
        <div style="float: left;width:80px;background: #e1e1e1;height: 15px;padding: 5px;margin: 0;padding: 5px;font-weight: bold;">Explorando:</div>
        <div id="ubicacionDirectorios" style="float: left;width:auto;background: #fff;height: 15px;padding: 5px;margin: 0;padding: 5px;font-size: 10px;"></div>
    </div>
    <div id="barraBotones" style="width: 99%;height: 26px; border: 1px solid #666;background: #e1e1e1;margin: 0px 5px 2px 5px;padding: 2px;">
        <div id="btnAtras" class="estiloAtras" onclick="retrocederDirectorio()">&laquo;&laquo;Atras</div>
<?
    if($_SESSION["usuario_nivel"]==0 || $_SESSION["usuario_nivel"]==1){
?>
        <div class="estiloNuevaCarpeta" onclick="crearDirectorio()">Nueva Carpeta</div>
	<div class="estiloSubirArchivo" onclick="mostrarFormSubirArchivos()">Subir Archivos</div>
	<div id="btnEditar1" class="estiloEditar" onclick="editarContenido()">Editar</div>
        <div id="btnEditar2" class="estiloEditarActivo" onclick="finEditarContenido()">Fin Edicion</div>
<?
    }
?>
	<div id="btnVistaPrevia" class="estiloCerrarVistaPrevia" style="width: auto;" onclick="cerrarVistaPrevia()">Cerrar Vista Previa</div>
    </div>
    <div id="browserArchivos" style="margin: 0px 5px 5px 5px;width: 99.2%;border: 1px solid #666;background: #FFF;position: relative;overflow-x: auto;"></div>
    <iframe id="vistaPreviaArchivo" style="display: none;margin: 0px 5px 5px 5px;width: 99.2%;border: 1px solid #666;background: #F0F0F0;position: relative;overflow-x: auto;"></iframe>
</div>
<div id="subirArchivos" style="display: none;background: url(../../img/desv.png) repeat;position: absolute;width: 100%;height: 100%;">
    <div style="width: 500px;height: 400px;position: absolute;left: 50%;top: 50%;margin-left: -250px;margin-top: -200px;border: 1px solid #000;z-index: 10;background: #fff;">
        <div style="height: 17px;padding: 7px;border: 1px solid #000;width: 485px;background: #000;color: #fff;">Subir archivos...</div>
        <div id="detalleSubirArchivos" style="border: 0px solid #ff0000;width: 498px;height: 330px; overflow:hidden;"></div>
        <div style="border: 1px solid #ccc;background: #f0f0f0;width: 488px;height: 25px;padding: 5px;text-align: right;"><input type="button" value="Cerrar Ventana" onclick="cerrarVentanaSubirArchivos()"></div>
    </div>
</div>