<script type="text/javascript" src="../clases/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
    function validarDatos(){
        var arrayCliente = new Array();
	var arrayIq = new Array()
		
	for (var i=0;i<document.frmResultadosNextel.elements.length;i++){
            if (document.frmResultadosNextel.elements[i].type=="text"){
		idElemento=document.frmResultadosNextel.elements[i].id;				
		idElemento=idElemento.substring(3,15);
                //idElemento=idElemento;
		arrayCliente[i]=idElemento;								
            }
	}
		
	for (var i=0;i<document.frmResultadosIq.elements.length;i++){
            if (document.frmResultadosIq.elements[i].type=="text"){
		idElementoL=document.frmResultadosIq.elements[i].id;
                idElementoL=idElementoL.substr(3,15);
		//idElementoL="#"+idElementoL;
		arrayIq[i]=idElementoL;
            }
	}
        
        var arrayClienteOrdenado = ordenaArray(arrayCliente);
	var arrayIqOrdenado = ordenaArray(arrayIq);
        
        for(i=0;i<arrayClienteOrdenado.length;i++){
            $("#div").append(arrayClienteOrdenado[i]+"<br>");
            if(arrayClienteOrdenado[i]==arrayIqOrdenado[i]){
                elementonx="#nx_"+arrayClienteOrdenado[i];
                elementoiq="#iq_"+arrayIqOrdenado[i];                
                $(elementonx).css("background-color","green");
                $(elementonx).css("color","white");
                $(elementoiq).css("background-color","green");
                $(elementoiq).css("color","white");
            }else{
                elementonx="#nx_"+arrayClienteOrdenado[i];
                elementoiq="#iq_"+arrayIqOrdenado[i];                
                $(elementonx).css("background-color","red");
                $(elementonx).css("color","white");
                $(elementoiq).css("background-color","red");
                $(elementoiq).css("color","white");
            }
        }
        
        for(j=0;j<arrayIqOrdenado.length;j++){
            $("#div1").append(arrayIqOrdenado[j]+"<br>");
        }
        
    }

    function ordenaArray(Vector){
	for (var i=1; i<Vector.length;i++){
		for(var j=0;j<Vector.length-1;j++){
			if (Vector[j] > Vector[j+1]){
				var temp = Vector[j];
				Vector[j]= Vector[j+1];
				Vector[j+1]= temp;
			}
		}
	}
	return Vector;
    }
</script>
<form name="frmResultadosNextel" id="frmResultadosNextel">
    <input type="text" name="nx_987654321456" id="nx_987654321456" value="987654321456" /><br>
    <!--<input type="text" name="nx_846521932552" id="nx_846521932552" value="846521932552" /><br>
    <input type="text" name="nx_159387469846" id="nx_159387469846" value="159387469846" /><br>-->
    <input type="text" name="nx_178966897785" id="nx_178966897785" value="178966897785" /><br>
    <input type="text" name="nx_001487587485" id="nx_001487587485" value="001487587485" /><br>
</form>

<form name="frmResultadosIq" id="frmResultadosIq">
    <input type="text" name="iq_159387469846" id="iq_159387469846" value="159387469846" /><br>
    <input type="text" name="iq_178966897785" id="iq_178966897785" value="178966897785" /><br>
    <input type="text" name="iq_987654321456" id="iq_987654321456" value="987654321456" /><br>
    <input type="text" name="iq_001487587485" id="iq_001487587485" value="001487587485" /><br>
    <!--<input type="text" name="iq_846521932552" id="iq_846521932552" value="846521932552" /><br>-->
</form>

<div id="div" style="border:1px solid #990000;height:150px;"></div><br><br><div id="div1" style="border:1px solid #990000;height:150px;"></div>

<a href="#" onclick="validarDatos()">Validar</a>