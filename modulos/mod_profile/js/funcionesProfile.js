// JavaScript Document
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
function verPerfil(idUsuario){
	if(idUsuario==""){
		alert("Su sesion ha terminado por inactividad, vuelva a ingresar al Sistema");
		window.location.href="../../acceso.php";
	}else{
		ajaxApp("capaPanel","controladorPerfil.php","action=verPerfilUsuario&idUsuario="+idUsuario,"GET");
	}
}
function cambiarPass(idUsuario){
	if(idUsuario==""){
		alert("Su sesion ha terminado por inactividad, vuelva a ingresar al Sistema");
		window.location.href="../../acceso.php";
	}else{
		ajaxApp("capaPanel","controladorPerfil.php","action=cambiarPass&idUsuario="+idUsuario,"GET");
	}
}
function actualizaPass(){
	var validar=true;
	idUsuario=document.getElementById("txtIdUsuario").value;
	if(idUsuario==""){
		alert("Su sesion ha terminado por inactividad, vuelva a ingresar al Sistema");
		window.location.href="../../acceso.php";
	}else{
		passAnt=document.getElementById("txtPassAnterior").value;
		pass1=document.getElementById("txtPass").value;
		pass2=document.getElementById("txtPass1").value;
		
		if((pass1.lenth)!=(pass2.lenth)){
			alert('La longitud de los passwords introducidos no Coinciden');
			validar=false;
		}else if(pass1 != pass2){
			alert('Los passwords no coinciden, verifiquelos');
			validar=false;
		}else if(pass1==""){
			alert('Introduzaca el Password del Usuario');
			validar=false;
		}else if(pass2==""){
			alert('Introduzca de nuevo el Password');
			validar=false;
		}
		if(validar==true){
			ajaxApp("capaPanel","controladorPerfil.php","action=actualizarPass&idUsuario="+idUsuario+"&pass="+pass1+"&pass1="+pass2+"&passAnt="+passAnt,"POST");
		}
	}
}
function cambiarImagen(idUsuario){
	if(idUsuario==""){
		alert("Su sesion ha terminado por inactividad, vuelva a ingresar al Sistema");
		window.location.href="../../acceso.php";
	}else{
		ajaxApp("capaPanel","controladorPerfil.php","action=cambiarImagen&idUsuario="+idUsuario,"GET");
	}
}
function acercaDe(){
	ajaxApp("capaPanel","controladorPerfil.php","action=acercaDe","POST");
}	