//javascript para la clase verificaUsuario
function ajaxVerificaUsuario(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
			alert("envio");
		$("#"+divDestino).show().html("Verificando..."); 
	},
	success:function(datos){ 
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: Por favor intente mas tarde. </center>'); }
	});
}
function accionesVentana(div,opc){
    if(opc=="1"){
        $("#"+div).show();
        $("#msgVentanaDialogo").html("");
    	$("#msgVentanaDialogo").html("<br><table border='0' width='98%' cellpading='1' cellspacing='1'><tr><td align='right'>Usuario:</td><td align='center'><input type='text' name='txtUsuarioMod' id='txtUsuarioMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td align='right'>Password:</td><td align='center'><input type='password' name='txtPassMod' id='txtPassMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td colspan='2' align='center'><input type='button' value='<< Continuar >>' onclick='verificaUsuario()'></td></tr></table>");
		document.getElementById("txtUsuarioMod").focus();
    }else{
		$("#"+div).hide();
		$("#transparenciaGeneral").hide();
    }
}
function verificaUsuario(){	
    var usuarioMod=document.getElementById("txtUsuarioMod").value;
    var passmod=document.getElementById("txtPassMod").value;
    
    //$("#ventanaDialogo").hide();
    if((usuarioMod=="") || (usuarioMod==null) || (passmod=="")){
        alert("Escriba su nombre de usuario y password para poder continuar");
    }else{
        ajaxVerificaUsuario("verificacionUsuario","../../clases/verificaUsuario/accionesVerificaUsuario.php","action=verificaUsuario&usuarioV="+usuarioMod+"&passMod="+passmod,"POST");
    }
}