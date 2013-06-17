function ajaxApp(divDestino,url,parametros,metodo){
	var buscador="detalleUsuarios";
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		if(divDestino != "detalleUsuarios1"){
			$("#cargando").show();		
		}
	},
	success:function(datos){
		$("#cargando").hide();
		if(divDestino == "detalleUsuarios1"){
			$("#"+buscador).show().html(datos);
		}else{
			$("#"+divDestino).show().html(datos);
		}
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}
function nuevoUsuario(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=nuevoUsuarioForm";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);	
}
function validacion(){
	//se recuperan los datos del formulario
	var validar=true;
	
	var nombre=document.getElementById("txtNombre").value;
	var apaterno=document.getElementById("txtPaterno").value;
	var usuario=document.getElementById("txtUsuario").value;
	var pass1=document.getElementById("txtPass").value;
	var pass2=document.getElementById("txtPass1").value;
	var nivel_acceso=document.getElementById("nivelAcceso").options[document.getElementById("nivelAcceso").selectedIndex].value;
	var sexo=document.getElementById("lstSexo").options[document.getElementById("lstSexo").selectedIndex].value;
	/*nuevos datos*/
	//var tipo=document.getElementById("idTipoUsuario").value;
	//var idNoNominaUsuario=document.getElementById("idNoNominaUsuario").value;
	var grupo=document.getElementById("cboGrupoUsuario").options[document.getElementById("cboGrupoUsuario").selectedIndex].value;
	var grupo2=document.getElementById("cboGrupoUsuario2").options[document.getElementById("cboGrupoUsuario2").selectedIndex].value;
	var obs=document.getElementById("txtObservaciones").value;
	var nomina=document.getElementById("txtNomina").value;
	
	/**/
	if(nombre==""){ alert('Especifique el Nombre del Usuario'); validar=false;	}
	if(apaterno==""){ alert('Especifique el Apellido del Usuario'); validar=false;	}
	if(usuario==""){ alert('Especifique el nombre de usuario para el Sistema');	validar=false;}
	if(nivel_acceso=="--"){ alert('Especifique un nivel de Usuario'); validar=false; }
	if(sexo=="--"){ alert('Especifique el sexo del usuario (Masculino / Femenino)'); validar=false; }
	
	//if(tipo==""){ alert('Especifique el Tipo de Empleado'); validar=false;}
	//if(idNoNominaUsuario==""){ alert('Especifique el No de Nomina del EMpleado'); validar=false;}
	if(grupo==""){ alert('Seleccione el Grupo al que pertenece el Usuario'); validar=false;}
	if(grupo2==""){ alert('Seleccione el Grupo al que pertenece el Usuario'); validar=false;}
	
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

	//return validar;
	
	if(validar==true){
		//alert(usuario);
		div="detalleUsuarios";
		url="controladorUsuarios.php";
		parametros="action=guardarUsuario&nombre="+nombre+"&apaterno="+apaterno+"&usuario="+usuario+"&pass1="+pass1+"&pass2="+pass2+"&nivel_acceso="+nivel_acceso+"&sexo="+sexo+"&grupo="+grupo+"&grupo2="+grupo2+"&obs="+obs+"&nomina="+nomina;
		metodo="POST";
		//alert(parametros);
		ajaxApp(div,url,parametros,metodo);
		//consultarUsuarios("act");
	}	
}

/***********************************************/
var anterior;
function consultarUsuarios(param,orden){
	//consultaUsuarios
	if(param=="act"){
		param="1";
	}else if(param=="ina"){
		param="0";
	}
	
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=consultaUsuarios&param="+param+"&orden="+orden;
	metodo="GET";	
	ajaxApp(div,url,parametros,metodo);
}
function modificaUsuario(id_usr){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=modificaUsuario&id_usr="+id_usr;
	metodo="GET";
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
}
function nip(id_usr){
	div="nip";
	url="controladorUsuarios.php";
	parametros="action=nipUsuario&id_usr="+id_usr;
	metodo="GET";
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
	
}
function resetPass(id_usr,username){
	if(confirm('Esta seguro de hacer un reset en el password del usuario: '+username)){
		//se ejecuta el procedimiento
		div="detalleUsuarios";
		url="controladorUsuarios.php";
		parametros="action=resetPass&id_usr="+id_usr;
		metodo="GET";
		ajaxApp(div,url,parametros,metodo);
	}else{
		alert('Accion Cancelada');
	}
}
function eliminaUsuario(id_usr,user){
	if(confirm('Esta seguro de Borrar al usuario: '+user)){
		//se ejecuta el procedimiento
		div="eliminaUsuario";
		url="controladorUsuarios.php";
		parametros="action=borrarUsuario&id_usr="+id_usr;
		metodo="GET";
		ajaxApp(div,url,parametros,metodo);
		
		cerrarDivUsuarios();
		consultarUsuarios();
	}else{
		alert('Accion Cancelada');
	}
}
function cerrarDivUsuarios(){
	$("#consultaUsuarios").hide(); 
}
function cerrarDivModifica(){
	$("#msgModificaUsuarios").hide(); 
}
function cerrarDivNip(){
	$("#nip").hide(); 
}
function cierraMsgReset(){	
	$("#msgResetPass").hide(); 
}
function cierraMsgDel(){
	$("#msgResetPass").hide(); 	
}
function seleeccionCaptura(){
	var seleccionCaptura=document.getElementById("seleccionCapturaUsuario").options[document.getElementById("seleccionCapturaUsuario").selectedIndex].value;
	alert(seleccionCaptura);
	if(seleccionCaptura=="usrSistema"){
		document.getElementById("datosUsuarioSistemaP").style.display="block";
		document.getElementById("datosAdicionales").style.display="block";
		document.getElementById("datosUsuarioPersonales").style.display="block";		
		document.getElementById("datosAdicionales1").style.display="none";
	}else if(seleccionCaptura=="usrPersona"){
		document.getElementById("datosUsuarioPersonales").style.display="block";		
		document.getElementById("datosAdicionales").style.display="block";
		document.getElementById("datosAdicionales1").style.display="block";
		document.getElementById("datosUsuarioSistemaP").style.display="none";
	}
}
function addGrupo(){	
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=addGrupo";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);
}
function actualizaDatosUsuario(){
//se recuperan los datos del formulario
	cerrarDivModifica();
	var validar=true;
	
	var nombre=document.getElementById("txtNombreUsuario").value;
	var apaterno=document.getElementById("txtApellidoUsuario").value;
	var usuario=document.getElementById("txtUserName").value;
	var nivel_acceso=document.getElementById("lstNivelAcceso").options[document.getElementById("lstNivelAcceso").selectedIndex].value;
	//var cambioPass=document.getElementById("lstCambioPass").options[document.getElementById("lstCambioPass").selectedIndex].value;
	var sexo=document.getElementById("lstSexo").options[document.getElementById("lstSexo").selectedIndex].value;
	/*nuevos datos*/
	//var directorioUsuario=document.getElementById("txtDirectorioUsuario").value;
	//var tipo=document.getElementById("txtTipoUsuario").value;
	//var idNoNominaUsuario=document.getElementById("txtNominaUsuario").value;
	var grupo=document.getElementById("cboGrupoUsuario").options[document.getElementById("cboGrupoUsuario").selectedIndex].value;
	var grupo2=document.getElementById("cboGrupoUsuario2").options[document.getElementById("cboGrupoUsuario2").selectedIndex].value;
	var activo=document.getElementById("cboActivoUsuario").options[document.getElementById("cboActivoUsuario").selectedIndex].value;
	var nomina=document.getElementById("txtNomina").value;
	var idUsuarioAct=document.getElementById("idUsuarioAct").value;
	
	if(validar==true){
		div="detalleUsuarios";
		url="controladorUsuarios.php";
		parametros="action=actualizaDatosUsuario&nombre="+nombre+"&apaterno="+apaterno+"&usuario="+usuario+"&nivel_acceso="+nivel_acceso+"&sexo="+sexo+"&grupo="+grupo+"&grupo2="+grupo2+"&activo="+activo+"&idUsuarioAct="+idUsuarioAct+"&nomina="+nomina;
		metodo="POST";
		//alert(parametros);
		ajaxApp(div,url,parametros,metodo);
	}
	consultarUsuarios('act','nombre');
}
function guardaGrupo(){
	try{
		var nombreGrupo=document.getElementById("nombreGrupo").value;
		if(nombreGrupo!=""){
			var claves2="";
			for (var i=0;i<document.crearGrupo.elements.length;i++){
				if (document.crearGrupo.elements[i].type=="checkbox"){
					if (document.crearGrupo.elements[i].checked){
						if (claves2=="")
							claves2=claves2+document.crearGrupo.elements[i].value;
						else
							claves2=claves2+","+document.crearGrupo.elements[i].value;
					}
				}
			}
			if(claves2==""){
				alert("Verifique la informacion para poder crear el grupo");
			}else{
				alert(claves2);
				
				var filtro = $('[name="grupo"]:checked').val();
				if(filtro=="area"){
					nombreGrupo="Depto_"+nombreGrupo;				
				}
				div="detalleUsuarios";
				url="controladorUsuarios.php";
				parametros="action=guardaGrupo&nombreGrupo="+nombreGrupo+"&permisos="+claves2;
				metodo="POST";
				//ajax
				ajaxApp(div,url,parametros,metodo);
			}		
		}else{
			alert("Capture el nombre del grupo para poder continuar");
		}
		
	}catch(e){
		alert("Verifique la informacion para poder crear el grupo");
	}
}
function consultaGrupos(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametro="action=consultarGrupos";
	metodo="GET";
	ajaxApp(div,url,parametro,metodo);
}
function modificaGrupo(idGrupo){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametro="action=modificaGrupo&idGrupo="+idGrupo;
	metodo="GET";
	ajaxApp(div,url,parametro,metodo);
}
function actualizaGrupo(idGrupo){
	
	try{
		var claves2="";
		for (var i=0;i<document.frmModificaGrupo.elements.length;i++){
			if (document.frmModificaGrupo.elements[i].type=="checkbox"){
				if (document.frmModificaGrupo.elements[i].checked){
					if (claves2=="")
						claves2=claves2+document.frmModificaGrupo.elements[i].value;
					else
						claves2=claves2+","+document.frmModificaGrupo.elements[i].value;
				}
			}
		}
		if(claves2==""){
			alert("Verifique la informacion para poder crear el grupo");
		}else{
			//alert(claves2);
			div="detalleUsuarios";
			url="controladorUsuarios.php";				
			parametros="action=actualizaGrupo&permisos="+claves2+"&idGrupo="+idGrupo;
			metodo="POST";			
			ajaxApp(div,url,parametros,metodo);
		}
	}catch(e){
		alert("Verifique la informacion para poder modificar el grupo");
	}
}
function nuevaFuncionalidad(){
	div="divSubMenu";
	url="controladorUsuarios.php";
	parametros="action=nuevaFuncionForm";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);	
}
function guardaFuncion(){
	try{
		var txtModulo=document.getElementById("txtModulo").value;
		var txtPer=document.getElementById("txtPer").value;
		var txtMenu=document.getElementById("txtMenu").value;
		//var txtRuta=document.getElementById("txtRuta").value;
		//var txtImagen=document.getElementById("txtImagen").value;
		if((txtModulo=="")){
			alert("Error:\nCampo Obligatorio.");
		}else{	
			div="divSubMenu";
			url=="controladorUsuarios.php";
			//parametros="action=guardaRegFuncion&txtModulo="+txtModulo+"&txtPer="+txtPer+"&txtMenu="+txtMenu+"&txtRuta="+txtRuta+"&txtImagen="+txtImagen;
			parametros="action=guardaRegFuncion&txtModulo="+txtModulo+"&txtPer="+txtPer+"&txtMenu="+txtMenu;
			metodo="POST";
			ajaxApp(div,url,parametros,metodo);	
		}
	}catch(e){ alert("Error en la funcion")}
}

function manttoSistema(nombreConf){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=manttoSistema&sitio="+nombreConf;
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);
}
function guardaMantto(){
	//se recupera el valor del radio
	//var valor = $("input[@name=rdbSitio]:checked").val();
	var valor = $("input[name='rdbSitio']:checked").val(); 
	var comentario=document.getElementById("obsSitio").value;
	var sitio=document.getElementById("sitio").value;
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=guardarMantto&valor="+valor+"&comentario="+comentario+"&sitio="+sitio;
	metodo="POST";	
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
}
function controlCambios(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=controlCambios";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);
}
function guardaActualizaciones(){
	var titulo=document.getElementById("txtTitulo").value;
	var status=document.getElementById("cboStatus").options[document.getElementById("cboStatus").selectedIndex].value;
	var texto=document.getElementById("obsAct").value;
	var fecha=document.getElementById("fechaAct").value;
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=guardaControlCambios&titulo="+titulo+"&status="+status+"&texto="+texto+"&fecha="+fecha;
	metodo="POST";
	//alert(parametros);
	ajaxApp(div,url,parametros,metodo);
}
function consultaAct(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametro="action=consultarAct";
	metodo="GET";
	ajaxApp(div,url,parametro,metodo);
}
function cambioStatusAct(idReg,status_actual){
	var status_descripcion;
	(status_actual=='Nueva')?status_descripcion='Terminada':status_descripcion='Nueva';
		if(status_actual=='Nueva'){
			if(confirm(" ¿ Desea cambiar el status a : "+status_descripcion+" ?")){
				var datos_url="action=cambioStatusAct&idReg="+idReg+"&status="+status_descripcion;
				ajaxApp('detalleUsuarios','controladorUsuarios.php',datos_url,'GET');
			}	
		}else{
			if(confirm(" ¿ Desea cambiar el status a : "+status_descripcion+" ?")){
				var datos_url="action=cambioStatusAct&idReg="+idReg+"&status="+status_descripcion;
				ajaxApp('detalleUsuarios','controladorUsuarios.php',datos_url,'GET');
			}		
		}
}
function listarModulos(){
	ajaxApp("listadomodulos","controladorUsuarios.php","action=listarModulos","GET");
}
function listarImagen(){
	ajaxApp("listadoimagen","controladorUsuarios.php","action=listarImagen","GET");
}
function cierraDiv(div){
	$("#"+div).hide();
}
function filtrarNombres(opt){
	var regs=parseInt(document.getElementById("ndregistros").value)-1;
	if(opt=="filaGrupo"){
		for(var i=0;i<regs;i++){
			$("#filaGrupo"+i).show();	
		}	
		$("#Depto").hide();
	}else if(opt=="Depto"){
		for(var i=0;i<regs;i++){
			$("#filaGrupo"+i).hide();	
		}		
		$("#Depto").show();
	}
}
function ordenarListado(param,orden){
	//consultaUsuarios
	if(param=="act"){
		param="1";
	}else if(param=="ina"){
		param="0";
	}
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=consultaUsuarios&param="+param+"&orden="+orden;
	metodo="GET";	
	ajaxApp(div,url,parametros,metodo);
}
function Buscador(){
	var txtBuscar=document.getElementById("txtBuscar").value;
	var filtro = $('[name="rdbBusqueda"]:checked').val();
	var param=3;
	var orden="asc";
	//alert(filtro);
	
	div="detalleUsuarios1";
	url="controladorUsuarios.php";
	parametros="action=consultaUsuarios&txtBusca="+txtBuscar+"&filtro="+filtro+"&param="+param+"&orden="+orden;
	metodo="GET";	
	ajaxApp(div,url,parametros,metodo);
	
}
function consultarUsuarios(param,orden){
	//consultaUsuarios
	if(param=="act"){
		param="1";
	}else if(param=="ina"){
		param="0";
	}
	
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=consultaUsuarios&param="+param+"&orden="+orden;
	metodo="GET";	
	ajaxApp(div,url,parametros,metodo);
}
function nuevoProceso(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=nuevoProcesoForm";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);	
}
function consulta(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametro="action=consultarProcesos";
	metodo="GET";
	ajaxApp(div,url,parametro,metodo);
}
function nuevoModelo(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=nuevoModeloForm";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);	
}
function consultaModelo(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametro="action=consultarModelo";
	metodo="GET";
	ajaxApp(div,url,parametro,metodo);
}
function nuevafalla(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametros="action=nuevaFallaForm";
	metodo="GET";
	ajaxApp(div,url,parametros,metodo);	
}
function consultafalla(){
	div="detalleUsuarios";
	url="controladorUsuarios.php";
	parametro="action=consultarFalla";
	metodo="GET";
	ajaxApp(div,url,parametro,metodo);
}
function modProcesos(id_proc){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=modProc&id_proc="+id_proc,"POST");
}
function modModelo(id_modelo){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=modModeloMod&id_modelo="+id_modelo,"POST");
}
function muestraMod(id_falla){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=muestraMod&id_falla="+id_falla,"POST");
}
function guardaProceso(){
	var txtProceso=document.getElementById("txtProceso").value;
		if((txtProceso=="")){
			alert("Error:\nCampo Obligatorio.");
		}else{	
			div="detalleUsuarios";
			url=="controladorUsuarios.php";
			parametros="action=guardaRegistro&txtProceso="+txtProceso;
			metodo="POST";
			//alert(parametros);
			ajaxApp(div,url,parametros,metodo);
		}
}
function guardaModelo(){
	try{
		var txtModelo=document.getElementById("txtModelo").value;
		var txtObs=document.getElementById("txtObs").value;
			if((txtModelo=="")){
				alert("Error:\nCampo Obligatorio.");
			}else{	
				div="detalleUsuarios";
				url=="controladorUsuarios.php";
				parametros="action=guardaRegistroModelo&txtModelo="+txtModelo+"&txtObs="+txtObs;
				metodo="POST";
				//alert(parametros);
				ajaxApp(div,url,parametros,metodo);	
			}
	}catch(e){ alert("Error en la funcion")}
}
function guardaFalla(){
	try{
		var txtDes=document.getElementById("txtDes").value;
		var txtObs=document.getElementById("txtObs").value;
		var txtCodigo=document.getElementById("txtCodigo").value;
			if((txtDes=="")||(txtCodigo=="")){
				alert("Error:\nCampo Obligatorio.");
			}else{	
				div="detalleUsuarios";
				url=="controladorUsuarios.php";
				parametros="action=guardaRegistroFalla&txtDes="+txtDes+"&txtObs="+txtObs+"&txtCodigo="+txtCodigo;
				metodo="POST";
				//alert(parametros);
				ajaxApp(div,url,parametros,metodo);	
			}
	}catch(e){ alert("Error en la funcion")}
}
function guardaProcMod(id_proc){
	try{
		var txtDes=document.getElementById("txtDesProc").value;
		var id_proc=document.getElementById("id_proc").value;
				div="detalleUsuarios";
				url=="controladorUsuarios.php";
				parametros="action=guardaModProc&txtDes="+txtDes+"&id_proc="+id_proc;
				metodo="POST";
				//alert(parametros);
				ajaxApp(div,url,parametros,metodo);	
	}catch(e){ alert("Error en la funcion")}
}
function modModeloGuarda(id_modelo){
	try{
		var txtMod=document.getElementById("txtMod").value;
		var txtObs=document.getElementById("txtObsMod").value;
		var id_modelo=document.getElementById("id_modelo").value;
				div="detalleUsuarios";
				url=="controladorUsuarios.php";
				parametros="action=guardaModModelo&txtMod="+txtMod+"&txtObs="+txtObs+"&id_modelo="+id_modelo;
				metodo="POST";
				//alert(parametros);
				ajaxApp(div,url,parametros,metodo);	
	}catch(e){ alert("Error en la funcion")}
}
function guardaFallaMod(id_falla){
	try{
		var txtDes=document.getElementById("txtDes1").value;
		var txtObs=document.getElementById("txtObs1").value;
		var txtCodigo=document.getElementById("txtCodigo1").value;
		var id_falla=document.getElementById("id_falla").value;
				div="detalleUsuarios";
				url=="controladorUsuarios.php";
				parametros="action=guardaRegMod&txtDes="+txtDes+"&txtObs="+txtObs+"&txtCodigo="+txtCodigo+"&id_falla="+id_falla;
				metodo="POST";
				//alert(parametros);
				ajaxApp(div,url,parametros,metodo);	
	}catch(e){ alert("Error en la funcion")}
}
function cambioStatus(idReg,status_actual){
	var status_descripcion;
	(status_actual==1)?status_descripcion='Activo':status_descripcion='Inactivo';
		if(status_actual==1){
			if(confirm(" ¿ Desea cambiar el status a : "+status_descripcion+" ?")){
				var datos_url="action=cambioStatus&idReg="+idReg+"&status="+status_actual;
				ajaxApp('detalleUsuarios','controladorUsuarios.php',datos_url,'GET');
			}	
		}else{
			if(confirm(" ¿ Desea cambiar el status a : "+status_descripcion+" ?")){
				var datos_url="action=cambioStatus&idReg="+idReg+"&status="+status_actual;
				ajaxApp('detalleUsuarios','controladorUsuarios.php',datos_url,'GET');
			}		
		}
}
function configuracionesGlobales(){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=mostrarConfiguracionesGlobales","POST");	
}
function modificarValorConf(nombreConf,valor,id){
	if(confirm("Realmente desea cambiar la configuracion de: "+nombreConf)){
		nvoValor=prompt("Introduzca el nuevo valor para la configuracion");
		if(nvoValor=="" || nvoValor==null){
			alert("Error: Se debe de colocar una cantidad para la configuracion");
		}else{
			ajaxApp("detalleUsuarios","controladorUsuarios.php","action=modificarValorConf&id="+id+"&nvoValor="+nvoValor,"POST");
		}
	}
}
function eliminarValorConf(nombreConf,valor,id){
	if(confirm("Realmente desea eliminar la configuracion de: "+nombreConf)){		
		ajaxApp("detalleUsuarios","controladorUsuarios.php","action=eliminarValorConf&id="+id,"POST");		
	}
}
function agregarConfiguracion(){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=formAgergarConf","POST");	
}
function guardarConfiguracionGlobal(){
	nombreConf=$("#txtNombreConfiguracion").val();
	valor=$("#txtValorConfiguracion").val();
	descripcion=$("#txtDescripcion").val();
	if(nombreConf=="" || valor=="" || descripcion==""){
		alert("Debe llenar los campos para poder generar la nueva configuracion");
	}else{
		ajaxApp("detalleUsuarios","controladorUsuarios.php","action=guardarNuevaConf&nombreConf="+nombreConf+"&valor="+valor+"&descripcion="+descripcion,"POST");
	}
}
function agregarSubMenu(){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=agregarSubMenu","POST");	
}
function agregarItemSubMenu(idElemento){
	ajaxApp("divSubMenu","controladorUsuarios.php","action=agregarItemSubMenu&idElemento="+idElemento,"POST");	
}
function guardarSubMenu(){
	txtIdElemento=$("#txtIdElemento").val();
	txtNombreSubMenu=$("#txtNombreSubMenu").val();
	txtRuta=$("#txtRuta").val();
	cboStatusSubmenu=$("#cboStatusSubmenu").val();
	if(txtIdElemento=="" || txtNombreSubMenu=="" || txtRuta=="" || cboStatusSubmenu==""){
		alert("Error: Verifique que no existan espacios en blanco");
	}else{
		ajaxApp("divGuardadoSubMenu","controladorUsuarios.php","action=guardarSubMenu&idElemento="+txtIdElemento+"&txtNombreSubMenu="+txtNombreSubMenu+"&txtRuta="+txtRuta+"&cboStatusSubmenu="+cboStatusSubmenu,"POST");
	}
}
function modificarSubmenu(id){	
	ajaxApp("divSubMenu","controladorUsuarios.php","action=modificarSubMenu&id="+id,"POST");
}
function guardarSubMenuActualizacion(){
	txtIdElementoAct=$("#txtIdElementoAct").val();
	txtNombreSubMenuAct=$("#txtNombreSubMenuAct").val();
	txtRutaAct=$("#txtRutaAct").val();
	cboStatusSubmenuAct=$("#cboStatusSubmenuAct").val();
	if(txtIdElementoAct=="" || txtNombreSubMenuAct=="" || txtRutaAct=="" || cboStatusSubmenuAct==""){
		alert("Error: Verifique que no existan espacios en blanco");
	}else{
		ajaxApp("divGuardadoSubMenu","controladorUsuarios.php","action=guardarSubMenuAct&idElementoAct="+txtIdElementoAct+"&txtNombreSubMenuAct="+txtNombreSubMenuAct+"&txtRutaAct="+txtRutaAct+"&cboStatusSubmenuAct="+cboStatusSubmenuAct,"POST");
	}
}
function seleccionarMenuCompleto(nRegMenu,idMenu){	
	for(i=0;i<nRegMenu;i++){
		comboActual="cbo"+idMenu+i;		
		$("#"+comboActual).attr("checked","true");
	}
}
function quitarSeleccionMenuCompleto(nRegMenu,idMenu){
	for(i=0;i<nRegMenu;i++){
		comboActual="cbo"+idMenu+i;		
		$("#"+comboActual).removeAttr("checked","false");
	}
}
function mostrarOpcionesMenu(){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=mostrarOpcionesMenu","POST");
}
function modificarMenuTitulo(idMenuTitulo){
	ajaxApp("divSubMenu","controladorUsuarios.php","action=modificarMenuTitulo&idMenuTitulo="+idMenuTitulo,"POST")
}
function guardarMenuTituloActualizacion(){
	txtNombreMenuAct=$("#txtNombreMenuAct").val();
	numeroMenuAct=$("#txtNumeroMenuAct").val();
	idElementoAct=$("#txtIdElementoMenuTitulo").val();
	if(txtNombreMenuAct=="" || numeroMenuAct==""){
		alert("No deje espacios en blanco");
	}else{
		if(!isNaN(numeroMenuAct)){
			ajaxApp("divSubMenu","controladorUsuarios.php","action=guardarModificarMenuTitulo&nombreMenuTitulo="+txtNombreMenuAct+"&numeroMenuAct="+numeroMenuAct+"&idElementoAct="+idElementoAct,"POST");
		}else{
			alert("Error el numero de menu debe ser un numero");
		}
	}
}
function verModulos(){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=verModulosSistema","POST")
}
function leerArchivo(archivo){
	ajaxApp("contenidoArchivo","controladorUsuarios.php","action=verArchivo&archivo="+archivo,"POST")
}
function eliminaSubmenu(idSubMenu,nombreSubMenu){
	if(confirm("Esta realmente seguro de borrar el Submenu: "+nombreSubMenu)){
		ajaxApp("divSubMenu","controladorUsuarios.php","action=eliminaSubMenu&idSubMenu="+idSubMenu,"POST");		
	}
}
function eliminarMenu(id,modulo){
	if(confirm("Esta realmente seguro de borrar el Menu: "+modulo)){
		ajaxApp("divSubMenu","controladorUsuarios.php","action=eliminaMenu&idMenu="+id,"POST");		
	}
}
function listarBugs(){
	ajaxApp("detalleUsuarios","controladorUsuarios.php","action=listarBugs","POST");
}