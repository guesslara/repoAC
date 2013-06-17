    /*
    Funciones para el manejo del grid en la interfaz de usuario
    Las funciones se acompañan del uso de la libreria jquery
    Las peticiones ajax hacia el servidor se realizan tambien con jquery
    Autor: Gerardo Lara
    Fecha: 29-enero-2012
    */
    
    /*++++++++++++++++++++++++++++++++*/
    /**Configuracion inicial del Grid**/
    /*++++++++++++++++++++++++++++++++*/
    
    noColumnas=0;//se fija el contador inicial para las columnas; 3
    gridContenedor="";
    filas=0;//se fija el valor inicial del contador
    contadorNombre=0;//contador para los indices enlos nombres
    contadorFocus=0;//contador para los focus
    cajasAnt="";//acumulador de nombres de cajas de texto
    contadorRenglones=1;//contador lateral del Grid
    //nombres de las columnas
    //nombresCols=new Array("Imei","Sim","Serial","MFGDATE","Mensaje");
    nombresCols="";
    //variables para que la funcion con ajax funcione
    urlPeticion="";//url de la peticion
    parametrosPeticion="";//valores para la peticion ajax
    //div para los posibles errores en las peticiones
    divError="";
    
    function resetDatosScriptGrid(){
	contadorNombre=0;
	contadorFocus=0;
	cajasAnt="";
	contadorRenglones=1;
	urlPeticion="";
	parametrosPeticion="";
	divError="";
    }
    
    function cargaInicial(noColumnasDefinidas,divContenedor,urlPeticionUsuario,parametrosPeticionUsuario,divErrores,nombresColumnas){
        resetDatosScriptGrid();
	noColumnas=noColumnasDefinidas;//se definen las columnas
        gridContenedor=divContenedor;//se establece el nombre del div
        urlPeticion=urlPeticionUsuario;//se especifica la url donde se dirigira para la peticion
        parametrosPeticion=parametrosPeticionUsuario;//parametros extra para la funcion ajax
        divError=divErrores;//div para los errores y mensajes
	nombresCols=nombresColumnas;
        /*$("#txt_0").focus();
        $("#txt_0").removeClass("datoListado");
        $("#txt_0").addClass("elementoFocus");*/
    }
    
    function inicio(){
	var arrayColumnas="<div class='cuadroGrid'>&nbsp;</div>";
        if(contadorNombre==0 && contadorFocus==0){	    
	    for(var i=0;i<nombresCols.length;i++){		
		if(i==nombresCols.length-1){
		    arrayColumnas=arrayColumnas+"<div class='resultadoGuardadoCol'>"+nombresCols[i]+"</div>";    
		}else{
		    arrayColumnas=arrayColumnas+"<div class='cabeceraColumna'>"+nombresCols[i]+"</div>";    
		}
	    }
	    arrayColumnas=arrayColumnas+"<div class='elementoCab3'></div><div class='retornoCarro'></div>";
            //$("#"+gridContenedor).append("<div class='cuadroGrid'>&nbsp;</div>
	    //<div class='cabeceraColumna'>"+nombresCols[0]+"</div>
	    //<div class='cabeceraColumna'>"+nombresCols[1]+"</div>
	    //<div class='cabeceraColumna'>"+nombresCols[2]+"</div>
	    //<div class='cabeceraColumna'>"+nombresCols[3]+"</div>
	    //<div class='resultadoGuardadoCol'>"+nombresCols[4]+"</div>
	    //<div class='elementoCab3'></div>
	    //<div class='retornoCarro'></div>");
	    $("#"+gridContenedor).append(arrayColumnas);
        }
        var cajas="";//variable para las cajas
        for(filas=filas;filas<noColumnas;filas++){
            if(filas == (noColumnas-1)){//se compara si el numero de filas es igual a las columnas -1
                cajas=cajas+"<input type='text' id='Resultado"+contadorNombre+"' readonly='readonly' class='resultadoGuardado' />";//se concatena a la cadena con su id contenedor de la respuesta
            }else{
                cajas=cajas+"<input type='text' id='txt_"+contadorNombre+"' onkeypress='tecla(this.id,this.value,event)' class='datoListado' />";//se concatenan las cajas necesarias con sus id's
            }
            contadorNombre+=1;//se aumenta el contador para los nombres de las cajas
        }
        filas=0;//se regresa el contador de columnas a 0
        $("#"+gridContenedor).append("<div><div class='cuadroGrid'>"+contadorRenglones+"</div>"+cajas+"</div>");//se añaden al div contendedor
        contadorRenglones+=1;//se aumenta en 1 el contador de renglones
    }

    function tecla(id,valor,evento){
        if(evento.which==9 || evento.which==13){
            valor=id.split("_")//alert("Valor 1 "+valor[0]+"\n"+"Valor 2 "+valor[1]);
            contadorFocus+=1;//se incrementa el contador para colocar el focus
            if(cajasAnt==""){//se verifica la cadena para concatenar los ids de las cajas
                cajasAnt=$("#"+id).val();;
            }else{
                cajasAnt=cajasAnt+","+$("#"+id).val();
            }
            var cajaAnterior="txt_"+(parseFloat(valor[1]));
            if(contadorFocus==(noColumnas-1)){
                cajaResultado="Resultado"+(contadorNombre-1);//caja para obtener el resultado
                inicio();//se agrega una nueva fila
                contadorFocus=0;//se regresa el contador del focus
                $("#"+cajaAnterior).removeClass("elementoFocus");
                $("#"+cajaAnterior).addClass("datoListado");
                $("#txt_"+(contadorNombre-noColumnas)).removeClass("datoListado");
                $("#txt_"+(contadorNombre-noColumnas)).addClass("elementoFocus");
                $("#txt_"+(contadorNombre-noColumnas)).focus();//se manda el focus a la siguiente caja
                enviaDatosServidor(cajasAnt,cajaResultado);//se envian los datos a la funcion que lo procesará
                cajasAnt="";//la variable para concatenar los ids de las cajas se reinicia
            }else{
                var nvaCaja="txt_"+(parseFloat(valor[1])+1);//se calcula la siguiente caja de texto
                $("#"+cajaAnterior).removeClass("elementoFocus");
                $("#"+cajaAnterior).addClass("datoListado");
                $("#"+nvaCaja).focus();//se manda el focus a la nueva caja de texto
                $("#"+nvaCaja).removeClass("datoListado");
                $("#"+nvaCaja).addClass("elementoFocus");
            }
        }
    }

    function enviaDatosServidor(cajas,cajaResultado){
        //se envia la peticion al servidor con los valores obtenidos y el resultado a la caja cajaResultado
        $("#"+cajaResultado).attr("value","Validando...");
	$("#"+cajaResultado).css("background","orange");
	$("#"+cajaResultado).css("color","black");
        //a los valores de peticion se le concatena la caja de resultado
        parametrosPeticion=parametrosPeticion+"&idElemento="+cajaResultado+"&valores="+cajas;
        //se mandan llamar las variables que contienen la peticion ajax
        ajaxAppGrid(divError,urlPeticion,parametrosPeticion);
    }
    
    function ajaxAppGrid(divDestino,url,parametros){	
	$.ajax({
	async:true,
	type: "POST",
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#"+divDestino).show().html("Cargando datos..."); 
	},
	success:function(datos){ 
		$("#cargando").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
    }