<script type="text/javascript" src="../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function (){
	inicio();
        $("#txt_0").focus();
    });
    
    /*++++++++++++++++++++++++++++++++*/
    /**Configuracion inicial del Grid**/
    /*++++++++++++++++++++++++++++++++*/
    noColumnas=3;//se fija el contador inicial para las columnas;
    filas=0;//se fija el valor inicial del contador
    contadorNombre=0;//contador para los indices enlos nombres
    contadorFocus=0;//contador para los focus
    cajasAnt="";//acumulador de nombres de cajas de texto
    contadorRenglones=1;//contador lateral del Grid
    //nombres de las columnas
    nombresCols=new Array("Imei","Sim","Mensaje")
    
    function inicio(){
        if(contadorNombre==0 && contadorFocus==0){
            $("#gridCapturaInformacion").append("<div class='cuadroGrid'>&nbsp;</div><div class='cabeceraColumna'>"+nombresCols[0]+"</div><div class='cabeceraColumna'>"+nombresCols[1]+"</div><div class='resultadoGuardadoCol'>"+nombresCols[2]+"</div><div class='elementoCab3'></div><div class='retornoCarro'></div>");
        }
        var cajas="";//variable para las cajas
        for(filas=filas;filas<noColumnas;filas++){
            if(filas == (noColumnas-1)){//se compara si el numero de filas es igual a las columnas -1
                cajas=cajas+"<input type='text' id='Resultado"+contadorNombre+"' readonly='readonly' class='resultadoGuardado' />";//se concatena a la cadena con su id contenedor de la respuesta
            }else{
                cajas=cajas+"<input type='text' id='txt_"+contadorNombre+"' onkeypress='tecla(this.id,this.value,event)' class='datoListado' readonly='readonly' />";//se concatenan las cajas necesarias con sus id's
            }
            contadorNombre+=1;//se aumenta el contador para los nombres de las cajas
        }
        filas=0;//se regresa el contador de columnas a 0
        $("#gridCapturaInformacion").append("<div><div class='cuadroGrid'>"+contadorRenglones+"</div>"+cajas+"</div>");//se añaden al div contendedor
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
            if(contadorFocus==(noColumnas-1)){
                cajaResultado="Resultado"+(contadorNombre-1);//caja para obtener el resultado
                inicio();//se agrega una nueva fila
                contadorFocus=0;//se regresa el contador del focus
                $("#txt_"+(contadorNombre-noColumnas)).focus();//se manda el focus a la siguiente caja
                enviaDatosServidor(cajasAnt,cajaResultado);//se envian los datos a la funcion que lo procesará
                cajasAnt="";//la variable para concatenar los ids de las cajas se reinicia
            }else{
                var nvaCaja="txt_"+(parseFloat(valor[1])+1);//se calcula la siguiente caja de texto
                $("#"+nvaCaja).focus();//se manda el focus a la nueva caja de texto
            }
        }
    }

    function enviaDatosServidor(cajas,cajaResultado){
        //se envia la peticion al servidor con los valores obtenidos y el resultado a la caja cajaResultado
        $("#"+cajaResultado).attr("value",cajas);
    }
</script>
<style type="text/css">
.imeiListado{ border:1px solid #CCC; width:105px; font-size:12px;}
.resultadoGuardado{background:#FFC; border:1px solid #CCC;width:200px; font-weight:bold;}
.cuadroGrid{ background:#CCC; border-bottom:1px solid #000; border-right:1px solid #000; height:18px; width:20px;float:left; font-weight:bold; text-align:center; font-size:11px;}
.elemento{float:left;}
.retornoCarro{ clear:both;}
.cuadroListadoCol{ background:#CCC; border-bottom:1px solid #000; border-right:1px solid #000; height:18px; float:left; width:22px; font-size:12px; font-weight:bold; text-align:center;}
.imeiListadoCol{ background:#CCC; border-bottom:1px solid #000; border-right:1px solid #000; height:18px; float:left; width:105px; font-size:12px; font-weight:bold; text-align:center;}
.resultadoGuardadoCol{background:#CCC; border-bottom:1px solid #000; border-right:1px solid #000; height:18px; float:left; width:200px; font-size:12px; font-weight:bold; text-align:center;}    
/**/
.datoListado{ border:1px solid #CCC; width:110px; font-size:12px;}
.cabeceraColumna{ background:#CCC; border-bottom:1px solid #000; border-right:1px solid #000; height:18px; float:left; width:110px; font-size:12px; font-weight:bold; text-align:center;}
</style>
<div id="gridCapturaInformacion"></div>