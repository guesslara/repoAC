/*
 *Funciones javascript necesarias
 *
*/
$(document).ready(function (){
	redimensionarPag();
        //abrirDirectorio('<?=$rutaExplorar;?>','browserArchivos');
	
	$('#btnAtras').hide();
	
	// Example 1.1: A single sortable list
	$('#example-1-1 .sortable-list').sortable();

	// Example 1.2: Sortable and connectable lists
	$('#carpetas .sortable-list').sortable({
		connectWith: '#carpetas .sortable-list'
	});

	// Example 1.3: Sortable and connectable lists with visual helper
	$('#example-1-3 .sortable-list').sortable({
		connectWith: '#example-1-3 .sortable-list',
		placeholder: 'placeholder',
	});

	// Example 1.4: Sortable and connectable lists (within containment)
	$('#example-1-4 .sortable-list').sortable({
		connectWith: '#example-1-4 .sortable-list',
		containment: '#containment'
	});
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
	    finEditarContenido();
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
	    inEditar="#inputEditar"+i;
	    $(divEditar).hide();
	    $(inEditar).hide();
	}
        $("#btnEditar2").hide();        
        $("#btnEditar1").show();
	cerrarVistaPrevia();
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
	$("#btnVistaPrevia").hide();
    }
