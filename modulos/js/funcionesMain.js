// JavaScript Document
//var elemMenu;
var herrMenu;

function contenedorPrincipal(){
	var altoDoc=$(document).height();
	//alert(altoDoc);
	document.getElementById("contenedorPrincipal").style.height=(altoDoc-10)+"px";
	document.getElementById("contenedorVentana").style.height=(altoDoc-58)+"px";
}
function verificaDiv(div){
	if ($("#"+div).length > 0) {
		llamaFuncionesTec(div);
	}	
}
function llamaFuncionesTec(div){
	switch(div){
		case "msgListaRadios":
			buscarListadoEquiposRecibo();		
		break;
	}
}
function ventanaDesensamble(){
	$("#btnRecibo").hide();
	$.ajax({
	async:true,
	type: "GET",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"mod_des/index.php",
	data:"",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('Procesando informacion, espere un momento.<img src="../../img/gif/LOADING1.GIF">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#contenedorVentana").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#contenedorVentana").show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function vMantto(){
	$("#desv").show();
	$.ajax({
	async:true,
	type: "GET",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"funcionesMain.php",
	data:"action=verificaMantto",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('<img src="../img/cargador (2).gif">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#verificaMantto").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#verificaMantto").show().html('Error: El sistema no puede localizar los archivos necesarios.'); }
	});
}
function vActNuevas(){
	$("#desv").show();
	$.ajax({
	async:true,
	type: "POST",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"funcionesMain.php",
	data:"action=verificaActNuevas",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('<img src="../img/cargador (2).gif">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#numeroActualizacionesActuales").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#verificaMantto").show().html('Error: El sistema no puede localizar los archivos necesarios.'); }
	});
}
function vActSistema(){
	/*div="msgNuevasReqs";
	url="funcionesMostrar2.php";
	parametros="action=buscarNuevas";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);*/
	$("#desv").show();
	$.ajax({
	async:true,
	type: "POST",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:"funcionesMostrar2.php",
	data:"action=buscarNuevas",
	beforeSend:function(){ 
		$("#cargadorApp").show().html('<img src="../img/cargador (2).gif">'); 
	},
	success:function(datos){
		$("#cargadorApp").html("Listo");
		$("#divActSistema").show().html(datos);
	},
	timeout:90000000,
	error:function() { $("#divActSistema").show().html('Error: El sistema no puede localizar los archivos necesarios.'); }
	});
}
function verificarScriptsApp(elemento){
	var path=""; var path1=""; var path2="";
	switch(elemento){
		case "Inicio":
			path="mod_inicio/js/funcionesInicio.js";
			cargarScript(path);
		break;
		case "Almacen":
			path="mod_almacen/js/ajax.js";
			cargarScript(path);
			path="mod_almacen/js/inventario.js";
			cargarScript(path);
		break;
		case "Recibo":
			path="mod_recibo2/js/funcionesRecibo2.js";
			cargarScript(path);
		break;
		case "Desensamble":
			path="mod_des/js/funcionesDesensamble.js";
			cargarScript(path);
		break;
		case "Ensamble":
			path="mod_ensamble/js/funcionesEnsamble.js";
			cargarScript(path);
		break;
		case "Admin":
			path="mod_admon/js/usuarios.js";
			cargarScript(path);
			path1="mod_admon/js/admon.js";
			cargarScript(path1);
			path2="mod_conf/funcionesMod.js";
			cargarScript(path2);
		break;
	}
}
function cargarScript(path){
	//elemento del DOM
	var script=document.createElement("script");
	//atributos
	script.type="text/javascript";
	script.src=path;
	//se agrega al DOM
	document.getElementsByTagName("head")[0].appendChild(script);
}
function mostrarCalendarios(calendario){
	$("#"+calendario).show();
	if(calendario=="calendario2"){
		$("#tecnicosDesensambleReporte").html("");
		div="tecnicosDesensambleReporte";
		url="mod_des/controlador.php";
		parametros="action=mostrarTecnicosDes";
		metodo="GET";
		ajaxAppVentanas(div,url,parametros,metodo);
	}else if(calendario=="calendario3"){
		$("#opcionesBusquedaRecibo").html("");
		div="opcionesBusquedaRecibo";
		url="mod_recibo2/controladorRecibo.php";
		parametros="action=opcionesBusquedaRecibo";
		metodo="GET";
		//alert(parametros);
		//ajaxAppVentana(div,url,parametros,metodo);
	}
}
function ocultaCalendarios(calendario){
	$("#"+calendario).hide();
}
function verificaTeclaImeiBusquedaPrincipal(evento){
	if(evento.which==13){
		var imei=$("#txtBusquedaImeiPrincipal").attr("value");
		$("#txtBusquedaImeiPrincipal").attr("value","");
		$("#divBusquedaPrincipal").show();
		var filtro=$("input[name='filtroBusqueda']:checked").val();
		//alert(filtro);
		ajaxApp("divResultadosBusquedaPrincipal","mod_busqueda/controladorEnsamble.php","action=buscarEquipo&imei="+imei+"&filtro="+filtro,"POST");		
	}
}
function cerrarBusquedaPrincipal(){
	//divVentanaFlotanteFuncional
	$("#divBusquedaPrincipal").hide();
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
		$("#cargando").show(); 
	},
	success:function(datos){ 
		$("#cargando").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function mostrarFiltros(){
	$("#filtrosBusqueda").show();	
}
function cerrarFiltros(){
	$("#filtrosBusqueda").hide();
}
function abrirFormBug(){
	$("#frmContenedorBug").show();
	ajaxApp("divFormularioBug","funcionesMain.php","action=mostrarFormBug","GET");
}
function cerrarFormbug(){
	$("#frmContenedorBug").hide	();
}
function enviarInfo(){
	var mensaje=$("#txtDes").val();
	if(mensaje != ""){
		ajaxApp("divFormularioBug","funcionesMain.php","action=guardarFormBug&mensaje="+mensaje,"POST");	
	}else{
		alert("Escriba una descripcion breve de su problema.");	
	}	
}
function mostrarPerfilUsuario(){
	//alert("Perfil");
	ajaxApp("cargaPerfil","funcionesMain.php","action=verPerfil","POST");
}