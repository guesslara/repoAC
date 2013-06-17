<!--<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="grid.js"></script>
<script type="text/javascript">
    //variables iniciales
    var contadorFilas=1;
    var contadorColumnas=0;
    var contadorCajas=0;
    //variables para las columnas
    var columna1="txt_columna_1_2";
    var columna2="txt_columna_2_2";
    
    //variables para la recuperacion de datos
    var valor1="";
    var valor2="";
    //metodo para la recuperacion
    
    //se arma el nombre e id de la caja de texto
    //var nvaCajaTexto="txt_columna_"+contadorFilas+"_"+contadorColumnas;
    //$("#"+nvaCajaTexto).focus();
    
    function recuperaValor(id,valor,evento){
        if(evento.which==9 || evento.which==13){
            alert("Id: "+id+"\n\nvalor="+valor);
            ++contadorCajas;
            $("#txt_"+contadorCajas).focus();
            agregarFila();
        }
    }
    
    function agregarFila(){
        var cajaTexto="";
        var cadena="";
        cajaTexto="<input type='text' name='txt_columna_"+contadorCajas+"' id='txt_columna_"+contadorCajas+"' value='txt_columna_"+contadorCajas+"' /><input type='text' name='txt_columna_"+contadorCajas+"' id='txt_columna_"+contadorCajas+"' value='txt_columna_"+contadorCajas+"' /><input type='text' name='txtResultado_"+contadorCajas+"' id='txtResultado_"+contadorCajas+"' value='txtResultado_"+contadorCajas+"' />";
        $("#gridCapturaInformacion").append(cajaTexto);
    }
    
    function verificaColumna1(caja,valor,evento){       
        //alert("1");        
        if(evento.which==9 || evento.which==13){
            //se recupera el primer valor
            valor1=$("#"+caja).val();
            //se manda el foco a la siguiente caja de texto
            if(contadorFilas==1 && contadorColumnas==0){
                $("#txt_columna_0_1").focus();                
            }else{
                $("#"+caja).focus();
            }
        }
    }
    
    function verificaColumna2(caja,valor,evento){
        //alert("2");
        if(evento.which==9 || evento.which==13){
            //se recupera el segundo valor
            valor2=$("#"+caja).val();
            //se manda el foco a la siguiente caja de texto
            if(contadorFilas==0 && contadorColumnas==0){
                $("#txt_columna_0_1").focus();
                contadorFilas+=1;
                contadorColumnas+=1;
            }else{
                //se tienen que crear las cajas dinamicas para posteriormente mandar el focus                
                var cajaTexto=""; var cadena="";
                for(contadorColumnas=0;contadorColumnas<3;contadorColumnas++){                    
                    if(contadorColumnas==2){
                        cadena="txtResultado_"+contadorFilas+"_"+contadorColumnas;
                        cajaTexto=cajaTexto+"<input type='text' name='"+cadena+"' id='"+cadena+"' value='"+cadena+"' />";
                    }else{
                        cadena="txt_columna_"+contadorFilas+"_"+contadorColumnas;                        
                        cajaTexto=cajaTexto+"<input type='text' name='"+cadena+"' id='"+cadena+"' value='"+cadena+"' onkeypress='verificaColumna"+(contadorColumnas+1)+"(this.id,this.value,event)' />";
                    }
                    if(contadorColumnas==0){
                        var nvaCajaTexto=cadena;
                    }
                }                
                $("#gridCapturaInformacion").append(cajaTexto);                
                $("#"+nvaCajaTexto).focus();
                contadorFilas+=1;
            }
        }
    }
</script>-->
<style type="text/css">
.bordesFilaTablaFallas{border: 1px solid #CCC;text-align: right;}
.divCatFallas{height: 200px; width: 300px;overflow: auto;}
.divSeparador{border: 1px dotted #000;}
</style>
<table border="0" width="400" cellpadding="1" cellspacing="1">
    <tr>
        <td valign="middle" class="bordesFilaTablaFallas"><input type="text" name="" id="" /></td>
        <td valign="top" class="bordesFilaTablaFallas"><div id="listadoCatalogoFallas" class="divCatFallas">&nbsp;</div></td>
        <td valign="top" class="bordesFilaTablaFallas"><input type="text" name="" id="" /><br><input type="button" value="Guardar"></td>
    </tr>
</table>
<div class="divSeparador"></div>
<div id="cajasIniciales">
    <input type="checkbox" name="cboGrid" id="cboGrid" value="txt_0|txt_1" checked="checked" />
    <input type="text" name="txt_0" id="txt_0" value="txt_0" onkeypress="recuperaValor(this.id,this.value,event)" /><!--onkeypress="verificaColumna1('txtcolumna_0_0',this.value,event)"-->
    <input type="text" name="txt_1" id="txt_1" value="txt_1" onkeypress="recuperaValor(this.id,this.value,event)"/><!--onkeypress="verificaColumna2('txtcolumna_0_1',this.value,event)"-->
    <input type="text" name="txtResultado_2" id="txtResultado_2" value="txtResultado_2"/>
</div>
<div id="gridCapturaInformacion"></div>