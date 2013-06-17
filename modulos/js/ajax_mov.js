// Documento ajax
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

function guardaItem(NumMov,mov,idtipomov,idasociado){
	var cantidad=document.getElementById("ca1").value;
	var clave=document.getElementById("cl1").value;
	var des=document.getElementById("ds1").value;
	var costo=document.getElementById("cu1").value;
	
	var id_almacen=document.getElementById("id_almacen2").value;
	var almacen=document.getElementById("almacen2").value;
	var asociado=document.getElementById("asociado2").value;
	/*alert(cantidad);
	alert(clave);
	alert(des);
	alert(costo);
	alert(NumMov);
	alert(mov);*/
	//alert(idtipomov);
	//alert(idasociado);
if (cantidad!==''&&clave!==''&&des!==''&&costo!==''&&cantidad!=='0')	
{
	XMLHttp=crearInstancia();
	if(XMLHttp){
		var url="guardaMovItem.php?action=guardaItem&NumMov="+NumMov+"&mov2="+mov+"&idtipomov="+idtipomov+"&idasociado="+idasociado+"&ca1="+cantidad+"&cl1="+clave+"&ds1="+des+"&cu1="+costo+"&id_alm="+id_almacen+"&alm="+almacen+"&asociado="+asociado;
		XMLHttp.open("GET",url,true);
		XMLHttp.onreadystatechange=cambiaEstado;
		XMLHttp.send(null);
	}else{
		alert('No se pudo crear la instancia');
	}   
} else { alert("Error: Introduce todos los datos."); } 
}

function vaciar(){
	document.getElementById("ca1").value="";
	document.getElementById("cl1").value="";
	document.getElementById("ds1").value="";
	document.getElementById("cu1").value="";
}
function cambiaEstado(){
	if(XMLHttp.readyState==4){
		document.getElementById("texto").innerHTML="<strong>"+XMLHttp.responseText+"</strong>"
		// .......................
		var obj = document.getElementById('todo');
		var cantidad=document.getElementById("ca1").value;
		var clave=document.getElementById("cl1").value;
		var des=document.getElementById("ds1").value;
			if (obj.style.display == "none") {
    		obj.style.display = ""; //mostrar fila 
 			}
			agregar_fila(clave,des,cantidad); 
		// ........................
		vaciar();
	}else{
		document.getElementById("texto").innerHTML="<br /><br /><br /><table width='400' border='1' cellpadding='1' cellspacing='0' bordercolor='#990000' align='center'><tr><td width='244' align='center'><div align='center'><b>Espere un momento. Cargando...</b><img src='img/indicator.gif' /></div></td></tr></table>"
	}
}

/* .......................... Codigo de JGRS ......................................... */
function objetoAjax(){
	var xmlhttp2=false;
	try {
		xmlhttp2 = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp2 = false;
  		}
	}

	if (!xmlhttp2 && typeof XMLHttpRequest!='undefined') {
		xmlhttp2 = new XMLHttpRequest();
	}
	return xmlhttp2;
}

function traspaso(NumMov,mov,idtipomov,idasociado,idalmacen,almacen,asociado){
	var cantidad=document.getElementById("ca1").value;
	var clave=document.getElementById("cl1").value;
	var des=document.getElementById("ds1").value;
	var costo=document.getElementById("cu1").value;
	/*alert("Tranferencia de Productos: ");
	alert('Cantidad: '+cantidad);
	alert('clave: '+clave);
	alert('descripcion: '+des);
	alert('costo unitario: '+costo);
	
	alert('# Movim: '+NumMov);
	alert('Movimiento: '+mov);
	alert('Id tipo de Mov: '+idtipomov);
	alert('Id asociado: '+idasociado);
	alert('Almacen: '+almacen);
	alert('Alm asociado: '+asociado);*/
if (cantidad!==''&&clave!==''&&des!==''&&costo!==''&&cantidad!=='0')	
{
	divstatus = document.getElementById("status");
	ajax=objetoAjax();
	ajax.open("POST", "clase_traspaso.php");
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{
			divstatus.innerHTML = ajax.responseText;
			if (document.frm1.resultado_traspaso.value==1)
			{
				var obj = document.getElementById('todo');
				if (obj.style.display == "none") {
    			obj.style.display = ""; //mostrar fila 
 				}
				agregar_fila(clave,des,cantidad);  
				vaciar();
			} else {
			alert("Error: No se realizo el traspaso.");	
			}
		} else {
			divstatus.innerHTML = "Loading...";
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("action=traspaso&NumMov="+NumMov+"&mov="+mov+"&idtipomov="+idtipomov+"&idasociado="+idasociado+"&can="+cantidad+"&clave="+clave+"&des="+des+"&cu="+costo+"&idalmacen="+idalmacen+"&almacen="+almacen+"&asociado="+asociado);
} else { alert("Error: Introduce todos los datos."); } 
}

//var i=2; 
function agregar_fila(a,b,c) 
{ 
var todo=document.getElementById('todo').innerHTML; 
todo+="<div class=\"a\">"+a+"</div><div class=\"b\">"+b+"</div><div class=\"c\">"+c+"</div>"; 
document.getElementById('todo').innerHTML=todo; 
}





