// JavaScript Document
   function habilita(r)
   { 
	//alert(r);
	//return false;
	if (r==1)
	{
		document.getElementById("kit").style.visibility='hidden';
		document.getElementById("stock0").style.visibility='visible';
		document.getElementById("stock1").style.visibility='visible';
		document.getElementById("stock2").style.visibility='visible';
		document.getElementById("stock3").style.visibility='visible';
		document.getElementById("stock4").style.visibility='visible';
		document.getElementById("div_ubicacion0").style.visibility='visible';
		document.getElementById("div_ubicacion1").style.visibility='visible';
	}		

	if (r==2)
	{
		document.getElementById("kit").style.visibility='hidden';
		document.getElementById("stock0").style.visibility='hidden';
		document.getElementById("stock1").style.visibility='hidden';
		document.getElementById("stock2").style.visibility='hidden';
		document.getElementById("stock3").style.visibility='hidden';
		document.getElementById("stock4").style.visibility='hidden';
		document.getElementById("div_ubicacion0").style.visibility='hidden';
		document.getElementById("div_ubicacion1").style.visibility='hidden';		
		
		
	}
	if (r==3)
	{
		document.getElementById("kit").style.visibility='visible';
		document.getElementById("stock0").style.visibility='visible';
		document.getElementById("stock1").style.visibility='visible';
		document.getElementById("stock2").style.visibility='visible';
		document.getElementById("stock3").style.visibility='visible';
		document.getElementById("stock4").style.visibility='visible';
		document.getElementById("div_ubicacion0").style.visibility='visible';
		document.getElementById("div_ubicacion1").style.visibility='visible';		
	}		
   } // ------------------------------------------------------------------
   		function agregar_kit()
		{
		/*
		var tex_kit=document.getElementById("kit_array").value;
		alert(tex_kit);
		*/
		var wk=window.open('armar_kit_productos.php','','width=800,height=600,scrollbars=1');
		wk.focus();
		}
// ------------------------------------------------------
var win1var; 
var n;	

function popUp(URL) {
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=400');");
	}
function verificaOS(form){
	var numorder=form.numorder.value;
	if(numorder==""){
		alert("No hay un numero de orden a para ver");
		return false;
	}
	else{
		alert(numorder);
	}
}
function validar(form)
	{
		var numorder=form.numorder.value;
    	var fecha=form.fecha.value;
    	if (numorder=="" ){				//Fecha
			alert("falta Numero de Orden");
			form.numorder.focus();
			return false;}
		if (fecha==""){				//fecha
			alert("Falta fecha de Orden de Servicio");
			return false;}
		//form.submit();
	}
