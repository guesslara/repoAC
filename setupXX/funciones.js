/*Archivo javascript*/
function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#cargadorGeneral").show(); 
	},
	success:function(datos){ 
		$("#cargadorGeneral").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function siguiente(){
    ajaxApp("divDetalleAsistente","funciones.php","action=mostrarDatosEntrada","POST");
}
function siguienteCreacionBase(){
    var hostDatos=$("#txtHost").val();
    var baseDatos=$("#txtBaseDatos").val();
    var usuarioDatos=$("#txtUsuarioDatos").val();
    var passDatos=$("#txtPassDatos").val();
    ajaxApp("divDetalleAsistente","funciones.php","action=crearBase&hostDatos="+hostDatos+"&baseDatos="+baseDatos+"&usuarioDatos="+usuarioDatos+"&passDatos="+passDatos,"POST");
}