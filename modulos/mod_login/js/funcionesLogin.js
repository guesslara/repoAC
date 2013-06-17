// JavaScript Document
function ajaxApp(divDestino,url,parametros,metodo){
	$.ajaxSetup({ cache: false });
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
function datosIniciales(){
	//datos
	var usuario=document.getElementById("txtUsuario").value;
	var password=document.getElementById("txtPassword").value;
	if((usuario=="") || (password=="")){
		alert("Error: Introduzca su Nombre de Usuario y Contraseña");
	}else{
		div="infoApp";
		url="controladorLogin.php";
		parametros="action=datosIniciales&usuarioEntrante="+usuario+"&passEntrante="+password;
		metodo="POST";
		ajaxApp(div,url,parametros,metodo);
	}
}
function about(){
	$("#infoPieAbout").html("<a href='#' onclick=\"cerrarAcercaDe()\" style=\"color:#F00; text-decoration:none;\">Cerrar Acerca de...</a>");
	$("#aboutApp").show("fade");
	ajaxApp("aboutApp","controladorLogin.php","action=about","GET");
}
function cerrarAcercaDe(){
	$("#aboutApp").hide("fade");
	$("#infoPieAbout").html("<a href='#' onclick=\"about()\" style=\"color:#000;font-weight:bold; text-decoration:none;\">Acerca de...</a>");
}