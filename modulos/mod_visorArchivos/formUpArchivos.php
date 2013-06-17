<? $rutaActual=$_GET["rutaActual"]; ?>
<script type="text/javascript">
    var numero = 0; //Esta es una variable de control para mantener nombres
                //diferentes de cada campo creado dinamicamente.
    evento = function (evt) { //esta funcion nos devuelve el tipo de evento disparado
       return (!evt) ? event : evt;
    }
    
    //Aqui se hace lamagia... jejeje, esta funcion crea dinamicamente los nuevos campos file
    addCampo = function () { 
    //Creamos un nuevo div para que contenga el nuevo campo
       nDiv = document.createElement('div');
    //con esto se establece la clase de la div
       nDiv.className = 'archivo';
    //este es el id de la div, aqui la utilidad de la variable numero
    //nos permite darle un id unico
       nDiv.id = 'file' + (++numero);
    //creamos el input para el formulario:
       nCampo = document.createElement('input');
    //le damos un nombre, es importante que lo nombren como vector, pues todos los campos
    //compartiran el nombre en un arreglo, asi es mas facil procesar posteriormente con php
       nCampo.name = 'archivos[]';
    //Establecemos el tipo de campo
       nCampo.type = 'file';
    //Ahora creamos un link para poder eliminar un campo que ya no deseemos
       a = document.createElement('a');
    //El link debe tener el mismo nombre de la div padre, para efectos de localizarla y eliminarla
       a.name = nDiv.id;
    //Este link no debe ir a ningun lado
       a.href = '#';
    //Establecemos que dispare esta funcion en click
       a.onclick = elimCamp;
    //Con esto ponemos el texto del link
       a.innerHTML = 'Eliminar';
    //Bien es el momento de integrar lo que hemos creado al documento,
    //primero usamos la función appendChild para adicionar el campo file nuevo
       nDiv.appendChild(nCampo);
    //Adicionamos el Link
       nDiv.appendChild(a);
    //Ahora si recuerdan, en el html hay una div cuyo id es 'adjuntos', bien
    //con esta función obtenemos una referencia a ella para usar de nuevo appendChild
    //y adicionar la div que hemos creado, la cual contiene el campo file con su link de eliminación:
       container = document.getElementById('adjuntos');
       container.appendChild(nDiv);
    }
    //con esta función eliminamos el campo cuyo link de eliminación sea presionado
    elimCamp = function (evt){
       evt = evento(evt);
       nCampo = rObj(evt);
       div = document.getElementById(nCampo.name);
       div.parentNode.removeChild(div);
    }
    //con esta función recuperamos una instancia del objeto que disparo el evento
    rObj = function (evt) { 
       return evt.srcElement ?  evt.srcElement : evt.target;
    }       
</script>
<div style="margin: 5px;">
    <form name="formu" id="formu" action="upload2.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="hdnRutaActual" id="hdnRutaActual" value="<?=$rutaActual;?>">
        <div style="border: 1px solid #CCC;height: 48px;width: 440px;padding: 5px;margin: 3px;font-size: 10px;font-family: Verdana;">
            <strong>Archivos a Subir al Servidor</strong><br><br>
            Directorio a subir:&nbsp;<?=$rutaActual;?>
        </div>
        <div style="border: 1px solid #CCC;background: #f0f0f0;height: 33px;padding: 5px;width: 440px;margin: 3px;font-size: 10px;font-family: Verdana;">
            <div style="float: left;margin-top: 8px;"><a href="#" onClick="addCampo()" title="Colocar otro campo y seleccionar otro archivo">Subir otro archivo</a></div>
            <div style="float: right;"><input type="submit" value="Cargar Archivos" id="envia" name="envia" style="padding: 5px;" /></div>
        </div>
        <div style="border: 1px solid #CCC;background: #f0f0f0;height: auto;width: 440px;padding: 5px;margin: 3px;font-size: 10px;font-family: Verdana;">
            <div id="adjuntos">
            <!-- Hay que prestar atención a esto, el nombre de este campo debe siempre terminar en []
            como un vector, y ademas debe coincidir con el nombre que se da a los campos nuevos 
            en el script -->
            <input type="file" name="archivos[]" /><br />
            </div>
        </div>                                  
    </form>
</div>