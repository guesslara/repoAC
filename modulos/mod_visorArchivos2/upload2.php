<?php 
    //Preguntamos si nuetro arreglo 'archivos' fue definido
    if (isset ($_FILES["archivos"])) {        
        //obtenemos la cantidad de elementos que tiene el arreglo archivos
        $tot = count($_FILES["archivos"]["name"]);
        $directorioASubir=$_POST["hdnRutaActual"];
        //este for recorre el arreglo
        for ($i = 0; $i < $tot; $i++){
            //con el indice $i, poemos obtener la propiedad que desemos de cada archivo            
            $tmp_name = $_FILES["archivos"]["tmp_name"][$i];
            $name = $_FILES["archivos"]["name"][$i];
            $tipo = $_FILES["archivos"]["type"][$i];
            $size = $_FILES["archivos"]["size"][$i];
            echo "<div style='width:99%;border:1px solid #CCC;background:#f0f0f0;height:50px;padding:5px;font-size:10px;font-family:Verdana;'>";
            echo("<b>Informacion del Archivo: </b> $key ");
            echo("<br />");
            echo("<b>Nombre Archivo:</b> ");
            echo($name);
            echo("<br />");                        
            echo("<b>tipo de archivo:</b> \n");
            echo($tipo);
            echo("<br />");
            echo("<b>tama&ntilde;o de archivo:</b> \n");
            echo($size." Mb");
            echo("<br />");
            echo "</div>";
            
            //se verifica que el archivo no exista en el directorio especificado            
            if(file_exists($directorioASubir."/".$_FILES["archivos"]["name"][$i])){
                echo "<div style='background:#ff0000;font-size:10px;color:#fff;height:20px;padding:5px;width:99%;'><strong>".$_FILES["archivos"]["name"][$i]."</strong> ya existe.</div>";
            }else{
                try{
                    if(move_uploaded_file($_FILES["archivos"]["tmp_name"][$i],$directorioASubir."/".$_FILES["archivos"]["name"][$i])){
                        echo "<div style='background:green;font-size:10px;color:#fff;height:20px;padding:5px;width:99%;'><strong>Archivo <strong>".$_FILES["archivos"]["name"][$i]."</strong> subido Correctamente.</div>";    
                    }else{
                        echo "Error al subir el archivo";    
                    }                    
                }catch(Exception $e){ echo "Error en la Aplicaci&oacute;n";}
            }
            echo "<div style='clear:both;'>&nbsp;</div>";
        }        
    }      
?>