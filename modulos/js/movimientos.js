// JavaScript Document
function guarda_producto()
{
	var ndm=$("#NumMov").attr("value");
	var idp=$("#cl1").attr("value");
	var can=$("#ca1").attr("value");
	var des=$("#ds1").attr("value");
	var cun=$("#cu1").attr("value");	
	
	var itm=$("#idtipomov2").attr("value");
	var mov=$("#mov2").attr("value");
	var con=$("#concepto2").attr("value");

	var ial=$("#id_almacen2").attr("value");
	var alm=$("#almacen2").attr("value");
	
	var ref=$("#referencia2").attr("value");
	var ias=$("#idasociado2").attr("value");
	var aso=$("#asociado2").attr("value");

/*	alert('['+can+']\n['+idp+']\n['+des+']\n['+cun);
	if (!(can==''||can=='undefined')&&(idp==''||idp=='undefined')&&(des==''||des=='undefined')&&(cun==''||cun=='undefined')){
	alert('OK');
	} else { alert ("Introduzca todos los datos.");}
	return false;*/
	$.ajax({
    async:true,
    type: "POST",
    dataType: "html",
    contentType: "application/x-www-form-urlencoded",
    url:"movimientos.php",
    data:"action=guarda_producto&idp="+idp+"&ndm="+ndm+"&itm="+itm+"&mov="+mov+"&con="+con+"&ial="+ial+"&alm="+alm+"&ref="+ref+"&ias="+ias+"&aso="+aso+"&can="+can+"&des="+des+"&cun="+cun,
    beforeSend:inicio1,
    success:resultado1,
    timeout:1000000,
    error:problemas1
  	});
	
	
	
}

function inicio1()
{
  	$("#resultados").show().html('<img src="../img/barra6.gif">');
}
function resultado1(datos)
{
	$("#resultados").show('slow').html(datos);
	$("#ca1").attr("value","");
	$("#cl1").attr("value","");
	$("#ds1").attr("value","");
	$("#cu1").attr("value","");
}
function problemas1()
{
	$("#resultados").show().html("<div align=center style=\"font-weight:bold;\">Error: El servidor no responde.</div>");
}

// ==================================================================
function elegir_producto()
{
	//alert('Elegir producto');
	//return false;
	var ndm=$("#NumMov").attr("value");
	var itm=$("#idtipomov2").attr("value");
	var tmo=$("#tipomov2").attr("value");
	var mov=$("#mov2").attr("value");
	var con=$("#concepto2").attr("value");
	
	var ial=$("#id_almacen2").attr("value");
	var alm=$("#almacen2").attr("value");
	
	var ida=$("#idasociado2").attr("value");
	var aso=$("#asociado2").attr("value");

	$.ajax({
    async:true,
    type: "POST",
    dataType: "html",
    contentType: "application/x-www-form-urlencoded",
    url:"elegir_producto.php",
    data:"action=elegir_producto&ndm="+ndm+"&itm="+itm+"&tmo="+tmo+"&mov="+mov+"&con="+con+"&ial="+ial+"&alm="+alm+"&ida="+ida+"&aso="+aso,
    beforeSend:inicio2,
    success:resultado2,
    timeout:1000000,
    error:problemas2
  	});	
	
}

function inicio2()
{
  	$('#all').hide();
	$("#catalogosx").show();
	$("#conven").html('<div style="text-align:center;font-weight:bold;margin-top:200px;"><img src="../img/barra6.gif"><br>Cargando...</div>');
}
function resultado2(datos)
{
  	$("#catalogosx").show();
	$("#conven").show('slow').html(datos);
}
function problemas2()
{
  	$("#catalogosx").show();
	$("#conven").show('slow').html("<div align=center style=\"font-weight:bold;\">Error: El servidor no responde.</div>");
}

function coloca_datosx(id,des)
{
	//alert(id+'\n'+des);
	$("#cl1").attr("value",id);
	$("#ds1").attr("value",des);
	cerrarvx();
}
function coloca_datos2x(id,des,cp)
{
	//var des=replace("\"","\\\"");
	//alert('ID='+id+'\nDES='+des+'\nCP='+cp);
	$("#cl1").attr("value",id);
	$("#ds1").attr("value",des);
	$("#cu1").attr("value",cp);
	cerrarvx();
}
// ==================================================================
function seleccionar(action)
{
	//alert(action);
	if(action=="ver_asociado"){
		var n1=$("#tipomov").attr("value");
		var ial=$("#id_almacen").attr("value");
		var alm=$("#almacen").attr("value");
		var tmo=$("#idtipomov").attr("value");
		//alert("n1="+n1+"&action="+action+"&alm_operado="+ial+"&almacen="+alm+"&id_tipomov="+tmo);
		$.ajax({
    async:true,
    type: "POST",
    dataType: "html",
    contentType: "application/x-www-form-urlencoded",
    url:"seleccionar.php",
    data:"n1="+n1+"&action="+action+"&alm_operado="+ial+"&almacen="+alm+"&id_tipomov="+tmo,
    beforeSend:inicio0,
    success:resultado0,
    timeout:1000000,
    error:problemas0
  		});
		//return false;

	}else{
		$.ajax({
    async:true,
    type: "POST",
    dataType: "html",
    contentType: "application/x-www-form-urlencoded",
    url:"seleccionar.php",
    data:"action="+action,
    beforeSend:inicio0,
    success:resultado0,
    timeout:1000000,
    error:problemas0
  		});
		//return false;
	}
// ===============================================================	
/*    data:"action="+action+"&ndm="+ndm+"&itm="+itm+"&tmo="+tmo+"&mov="+mov+"&con="+con+"&ial="+ial+"&alm="+alm+"&ida="+ida+"&aso="+aso,*/
function inicio0()
{
  	$('#all').hide();
	$("#catalogosx2").show();
	$("#conven2").html('<div style="text-align:center;font-weight:bold;margin-top:200px;"><img src="../img/barra6.gif"><br>Cargando...</div>');
}

function problemas0()
{
  	$("#catalogosx2").show();
	$("#conven2").html("<div align=center style=\"font-weight:bold;\">Error: El servidor no responde.</div>");
}
}
function resultado0(datos)
{
  	$("#catalogosx2").show();
	$("#conven2").html(datos);
}
function coloca_datos3(id,asociado,concepto,tipo) // Tipo de movimiento ...
{
	//alert(id+'\n'+des);
	$("#idtipomov").attr("value",id);
	$("#tipomov").attr("value",asociado);
	$("#concepto").attr("value",concepto);
	$("#mov").attr("value",tipo);	
	cerrarvx();
}
function coloca_datos4(id,tipo) // Almacen  operado...
{
	$("#id_almacen").attr("value",id);
	$("#almacen").attr("value",tipo);
	cerrarvx();
}

function coloca_datos5(id,des) // Almacen  operado...
{
	$("#idasociado").attr("value",id);
	$("#asociado").attr("value",des);
	cerrarvx();
}


/*
function ponclave(clave,tipo){
	opener.document.frm.idasociado.value = clave 
	opener.document.frm.asociado.value = tipo 
	window.close() 
}
*/


// ==================================================================


