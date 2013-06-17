/*Codigo*/
function crearInstancia(){
	XMLHttp=false;
	if(window.XMLHttpRequest){
		return new XMLHttpRequest();
	}else{
		var versiones=["Msxml2.XMLHTTP.7.0","Msxml2.XMLHTTP.6.0","Msxml2.XMLHTTP.5.0","Msxml2.XMLHTTP.4.0","Msxml2.XMLHTTP.3.0","Msxml2.XMLHTTP","Microsoft.XMLHTTP"];
		for(var i=0;i<versiones.length;i++){
			try{
				XMLHttp=new ActiveXObject(versiones[i]);
				if(XMLHttp){
					return XMLHttp;
					break;
				}
			}catch(e){};
		}
	}
}
function primerElemento(clave,c){
	//alert(clave);
	XMLHttp=crearInstancia();
	if(XMLHttp){
		c=clave;
		var url="asistenteProd.php?action=clave1&clave="+clave+"&p=tec&c="+c;
		//alert(url);
		XMLHttp.open("GET",url,true);
		XMLHttp.onreadystatechange=cambiaEstado1;
		XMLHttp.send(null);		
	}else{
		alert('No se pudo crear la instancia');
	}	
}
function segundoElemento(clave,c,marca){
	//alert(clave);
	XMLHttp=crearInstancia();
	if(XMLHttp){
		c=c+clave;
		var url="asistenteProd.php?action=clave2&clave="+clave+"&p=marca&c="+c+"&marca1="+marca;
		//alert(url);
		XMLHttp.open("GET",url,true);
		XMLHttp.onreadystatechange=cambiaEstado3;
		XMLHttp.send(null);		
	}else{
		alert('No se pudo crear la instancia');
	}	
}
function tercerElemento(clave,c,des){
	//alert(clave);
	XMLHttp=crearInstancia();
	if(XMLHttp){
		c=c+clave;
		var url="asistenteProd.php?action=clave3&clave="+clave+"&p=tipo&c="+c+"&des1="+des;
		//alert(url);
		XMLHttp.open("GET",url,true);
		XMLHttp.onreadystatechange=cambiaEstado6;
		XMLHttp.send(null);
	}else{
		alert('No se pudo crear la instancia');
	}	
}
/*VENTANAS EMERGENTES*/
var win1var; 
var n;	
function abreAsistente(){
    win1= window.open("asistenteProd.php?action=nuevo","Catalogo","width=450,height=200,scrollbars=yes,top=50,left=600") 
    win1.focus()
}
function abreVentanaTec(){
    //window.open("tec_list1.php","Catalogo","width=300,height=300,scrollbars=yes,top=50,left=600") 
    //win2.focus()
	XMLHttp=crearInstancia();
	if(XMLHttp){
		var txt=document.getElementById("Clave").value;
		if(txt==""){
			txt='0';
		}
		var url="tec_list1.php?c="+txt;
		//alert(url);
		XMLHttp.open("GET",url,true);
		XMLHttp.onreadystatechange=cambiaEstado;
		XMLHttp.send(null);		
	}else{
		alert('No se pudo crear la instancia');
	}
} 
function abreVentanaMarcas(){
    /*win3= window.open("marc_list2.php","Catalogo","width=300,height=300,scrollbars=yes,top=50,left=600") 
    win3.focus()*/
	XMLHttp=crearInstancia();
	if(XMLHttp){
		var txt=document.getElementById("Clave").value;
		var cant=txt.length;
		if(cant==2){
			var url="marc_list2.php?c="+txt;
			//alert(url);
			XMLHttp.open("GET",url,true);
			XMLHttp.onreadystatechange=cambiaEstado2;
			XMLHttp.send(null);
		}else{
			alert('Error: debe capturar la linea del producto');
			abreVentanaTec();
		}
	}else{
		alert('No se pudo crear la instancia');
	}
} 
function abreVentanaTipo(){
	/*n=this.document.form.clavetec.value
    win4= window.open("tipo_list2.php?n="+n,"Catalogo","width=300,height=300,scrollbars=yes,top=50,left=600") 
    win4.focus()*/
	XMLHttp=crearInstancia();
	if(XMLHttp){
		/*clave de tecnologia*/
		var txt=document.getElementById("clavetec").value;
		/*clave producto*/
		var clavep=document.getElementById("Clave").value;
		var cant=clavep.length;
		if(cant==5){
			var url="tipo_list2.php?n="+txt+"&clavep="+clavep;
			//alert(url);
			XMLHttp.open("GET",url,true);
			XMLHttp.onreadystatechange=cambiaEstado4;
			XMLHttp.send(null);		
		}else{
			alert('Error: debe capturar la Marca del producto');
			abreVentanaMarcas();
		}
	}else{
		alert('No se pudo crear la instancia');
	}
	
}
/*PARA DESTRUIR LAS VARIABLES*/
function destruirValores(){
	XMLHttp=crearInstancia();
	if(XMLHttp){
		var url="asistenteProd.php?action=destruir";
		//alert(url);
		XMLHttp.open("GET",url,true);
		XMLHttp.onreadystatechange=cambiaEstado5;
		XMLHttp.send(null);	
	}else{
		alert('No se pudo crear la instancia');
	}
}
/**************************************************************/
function cambiaEstado(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
function cambiaEstado1(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
function cambiaEstado2(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
function cambiaEstado3(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
function cambiaEstado4(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
function cambiaEstado5(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
function cambiaEstado6(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"		
	}else{
		document.getElementById("texto").innerHTML="<table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td bgcolor='#FFFFCC' width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b></div></td></tr></table>"
	}
}
/*funcion para colocar los datos en el formulario*/
function colocarClave(){
	//se recuperan los datos del formulario
	clave=document.getElementById("Clave").value;
	claveTec=document.getElementById("clavetec").value;
	marca=document.getElementById("marca").value;
	tipoprod=document.getElementById("tipoprod").value;
	des=document.getElementById("descr").value;
	/*alert(clave);
	alert(claveTec);
	alert(marca);
	alert(tipoprod);
	alert(des);*/
	opener.document.form1.id_prod.value = clave;
	opener.document.form1.marca.value = marca;
	opener.document.form1.linea.value = claveTec;
	//opener.document.form1.id_prod.value = clave;
	opener.document.form1.descripgral.value = des;
	window.close();
}