<?php
//print_r($_POST);
    if(isset($_POST["action"])){
	switch($_POST["action"]){
	    case "abrirDirectorio":
		mostrarContenidoDirectorio($_POST["path"]);
	    break;
	    case "crearDir":
		crearDirectorio($_POST["nombreDir"],$_POST["path"]);
	    break;
	    case "retrocederDirectorio":
		retrocederDirectorio($_POST["raizDirectorio"],$_POST["rutaActual"]);
	    break;
	    case "eliminaDir":		
		eliminarDirectorio($_POST["directorioEliminar"],$_POST["rutaActual"]);
	    break;
	    case "renombrarDirectorio":		
		renombrarDirectorio($_POST["directorio"],$_POST["idInput"],$_POST["idEnlace"],$_POST["nuevoNombre"],$_POST["rutaActual"]);
	    break;
            case "mostrarFormArchivos":
                mostrarFormArchivos($_POST["rutaActual"]);
            break;
            case "eliminaFile":
                eliminarArchivo($_POST["archivoEliminar"],$_POST["rutaActual"]);
            break;
	}
        
    }else{
	echo "Error, accion no permitida";
	exit;
    }
    
    function eliminarArchivo($archivoEliminar,$rutaActual){
        if(unlink($rutaActual."/".$archivoEliminar)){
	    echo "<script type='text/javascript'> alert('Archivo Borrado'); actualizarDirectorio('".$rutaActual."'); </script>";
	}else{
	    echo "<script type='text/javascript'> alert('Error al ejecutar la operacion'); </script>";
	}
    }
    
    function mostrarFormArchivos($rutaActual){
        
?>
        <iframe src="formUpArchivos.php?rutaActual=<?=$rutaActual?>" style="background:#FFF; width:99.5%; height: 99%; overflow:auto;"></iframe>
<?
    }
    
    function renombrarDirectorio($directorio,$idInput,$idEnlace,$nuevoNombre,$rutaActual){
	if(file_exists($rutaActual."/".$nuevoNombre)){
            echo "<script type='text/javascript'> alert('El directorio ya existe'); </script>";
        }else{
            if(rename($rutaActual."/".$directorio,$rutaActual."/".$nuevoNombre)){
                echo "<script type='text/javascript'> actualizarDirectorio('".$rutaActual."'); $('#".$idInput."').hide(); $('#".$idEnlace."').show(); </script>";
            }else{
                echo "<script type='text/javascript'> alert('Error al cambiar el nombre del directorio'); </script>";    
            }
        }
    }
    
    function eliminarDirectorio($directorio,$rutaActual){
	//se escanea el directorio
	$carpeta = @scandir($rutaActual."/".$directorio);
	if (count($carpeta) > 2){
	    echo "El directorio contiene Archivos, verifique la informacion";
	}else{
	    if(rmdir($rutaActual."/".$directorio)){
		echo "<script type='text/javascript'> abrirDirectorio('".$rutaActual."'); </script>";
	    }else{
		echo "<script type='text/javascript'> alert('Error al ejecutar la operacion'); </script>";
	    }
	}
    }
    
    function retrocederDirectorio($raizDirectorio,$rutaActual){	
	$rutaActual=explode("/",$rutaActual);	
	$totalPosiciones=count($rutaActual);
	$nuevaRuta="";
	for($i=0;$i<($totalPosiciones-1);$i++){
	    if($nuevaRuta==""){
		$nuevaRuta=$rutaActual[$i];
	    }else{
		$nuevaRuta=$nuevaRuta."/".$rutaActual[$i];
	    }	    
	}	
	if($nuevaRuta!=$raizDirectorio){
	    echo "<script type='text/javascript'> abrirDirectorio('".$nuevaRuta."'); </script>";
	}else if($nuevaRuta==$raizDirectorio){
	    echo "<script type='text/javascript'> abrirDirectorio('".$raizDirectorio."'); $('#btnAtras').hide();</script>";
	}
    }
    
    function crearDirectorio($nombredir,$path){
	strip_tags($nombredir);
	$directorioNuevo=$path."/".strtoupper($nombredir);
	if (file_exists($directorioNuevo)){
	    echo "<script type='text/javascript'> alert('La carpeta ya existe'); </script>";
	}else{
	    if(mkdir($directorioNuevo, 0777)){
		echo "<script type='text/javascript'> abrirDirectorio('".$path."'); $('#btnAtras').hide();</script>";		
	    }else{
		echo "<script type='text/javascript'> alert('Error al crear el directorio, verifique la informacion'); </script>";
	    }
	}
    }
    
    function mostrarContenidoDirectorio($path){			
	try{
            $directorio=dir($path);
            if(!is_dir($path)){
                    echo "El directorio no existe en la ruta especificada.";
            }else{
		$carpeta = @scandir($path);
		if (count($carpeta) > 2){
		    $i=0; $contenidoDir=array();
		    while ($archivo = $directorio->read()){		    
			if($archivo != "." && $archivo != ".."){
			    $contenidoDir[$i]=$archivo;
			}
			$i+=1;
		    }					
		    $directorio->close();
		    sort($contenidoDir);
?>
			<table border="0" cellpadding="1" cellspacing="1" width="99%" style="margin: 5px;font-size: 10px;">
			    <tr>
				<td style="width: 75%;height: 15px;padding: 5px;border: 1px solid #666;background: #f0f0f0;text-align: left;font-weight: bold;">Nombre</td>
				<td style="width: 10%;height: 15px;padding: 5px;border: 1px solid #666;background: #f0f0f0;text-align: left;font-weight: bold;">Tama&ntilde;o</td>
				<td style="width: 14%;height: 15px;padding: 5px;border: 1px solid #666;background: #f0f0f0;text-align: left;font-weight: bold;">Tipo</td>
			    </tr>
<?
		    $finfo = finfo_open(FILEINFO_MIME_TYPE);
		    for($i=0;$i<count($contenidoDir);$i++){
			$idDivEditar="divEditar".$i;  $idInputEditar="inputEditar".$i; $idEnlace="idEnlace".$i;
			if(is_dir($path."/".$contenidoDir[$i])){			    
?>						
			    <tr style="border-bottom:1px solid #CCC;">
				<td style="border-bottom:1px solid #CCC;"><div style="clear: both;margin-bottom: 5px;margin-top: 5px;"><div id="<?=$idDivEditar;?>" class="estiloDivHerr"><a href="#" onclick="renombrarDirectorio('<?=$contenidoDir[$i];?>','<?=$idInputEditar;?>','<?=$idEnlace;?>')" title="Renombrar Directorio">R</a> | <a href="#" onclick="eliminaDirectorio('<?=$contenidoDir[$i];?>')" title="Eliminar directorio">E</a></div><div style="float: left;margin: 3px;"><a id="<?=$idEnlace;?>" class="estiloListadoDirectorios" href="#" onclick="abrirDirectorio('<?=$path."/".$contenidoDir[$i];?>')" title="Ver Contenido..."><img src="img/folder-closed.gif" border="0" /><?=$contenidoDir[$i];?></a><input type="text" onkeyup="guardarNuevoNombreDir('<?=$contenidoDir[$i];?>','<?=$idInputEditar;?>','<?=$idEnlace;?>',event)" name="<?=$idInputEditar;?>" id="<?=$idInputEditar;?>" value="<?=$contenidoDir[$i];?>" style="font-size: 10px;width: 300px;display: none;"></div></div></td>
				<td style="border-bottom:1px solid #CCC;">&nbsp;</td>
				<td style="border-bottom:1px solid #CCC;">&nbsp;<? echo finfo_file($finfo,$path."/".$contenidoDir[$i]);?></td>
			    </tr>
<?						                           
			}else{
?>						
			    <tr>
				<td style="border-bottom:1px solid #CCC;">
				    <div style="clear: both;margin-bottom: 5px;margin-top: 5px;">
					<div id="<?=$idDivEditar;?>" class="estiloDivHerr">
					    <a href="#" onclick="renombrarDirectorio('<?=$contenidoDir[$i];?>','<?=$idInputEditar;?>','<?=$idEnlace;?>')" title="Renombrar Directorio">R</a> |
					    <a href="#" onclick="eliminarArchivo('<?=$contenidoDir[$i];?>')" title="Eliminar archivo">E</a>
					</div>
					<div style="float: left;margin: 3px;">
					    <a id="<?=$idEnlace;?>" class="estiloListadoDirectorios" href="#" onclick="mostrarArchivo('<?=$path."/".$contenidoDir[$i];?>')" title="Ver Archivo..."><img src="img/file.gif" border="0" /><?=$contenidoDir[$i];?></a>
					    <input type="text" onkeyup="guardarNuevoNombreDir('<?=$contenidoDir[$i];?>','<?=$idInputEditar;?>','<?=$idEnlace;?>',event)" name="<?=$idInputEditar;?>" id="<?=$idInputEditar;?>" value="<?=$contenidoDir[$i];?>" style="font-size: 10px;width: 300px;display:none;">
					</div>
				    </div>
				</td>
				<td style="border-bottom:1px solid #CCC;">&nbsp;<? echo filesize($path."/".$contenidoDir[$i])." bytes";?></td>
				<td style="border-bottom:1px solid #CCC;">&nbsp;<? echo finfo_file($finfo,$path."/".$contenidoDir[$i]);?></td>
			    </tr>
<?						                            
			}
		    }
?>
			</table>
<?
		    echo "<script type='text/javascript'> $('#hdnCantElementos').attr('value','".$i."'); </script>";
		}else{
		    echo "<center><p><h4>El directorio esta vacio</h4></p></center>";
		}
		
            }
        }catch(Exception $e){ echo "Error al leer el directorio."; }
    }
?>