<?
	/*
	 *Clase para poder enlazar las acciones del usuario con el modulo
	*/
	require_once("../../includes/config.inc.php");
	require_once("../../clases/conexion/conexion.php");
	require_once("../../clases/verificaUsuario/verificaUsuario.php");
	class modeloUsuarios{
		private $conexion;		
		
		function __construct($host,$usuario,$pass,$db){
			try {
				$conn = new Conexion();
				$this->conexion = $conn->getConexion($host,$usuario,$pass,$db);
				if($this->conexion === false){
					echo "Error en la aplicacion (Modelo)";
				}
			} catch(Exception $e){
				echo "Error en la aplicacion (Excepcion)";
			}
		}//fin construct
		
		public function listarBugs(){
			$sqlErrores="SELECT * FROM errores ORDER BY id DESC";
			$resultErrores=mysql_query($sqlErrores,$this->conexion);
			
			if(mysql_num_rows($resultErrores)==0){
				echo " ( 0 ) Registros encontrados en la Base de Datos.";
			}else{
?>
				<br><table width="800" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
					<tr>
						<td colspan="4" style="text-align:left;">Listado de Errores Reportados</td>
					</tr>
					<tr>
						<td width="40" align="center" style="height:30px; background:#000000; color:#FFFFFF;">#</td>
						<td width="50" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Fecha / Hora creaci&oacute;n</td>						
						<td width="133" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Descripci&oacute;n</td>
					</tr>                  
<?			
				while($rowGrupos=mysql_fetch_array($resultErrores)){					
?>
					<tr>
						<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-left:1px solid #CCC;"><?=$rowGrupos['id'];?></td>
						<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowGrupos['fecha']." ".$rowGrupos["hora"];?></td>						
						<td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
						<div style="height:auto; overflow:auto;">
<?
						echo $rowGrupos["des"];
?>
						</div>
						</td>
					</tr>
<?					
				}
?>
			</table>
<?
			}			
		}
		
		function eliminaMenu($idMenu){
			$sqlDelSubMenu="DELETE FROM gruposmods WHERE id = '".$idMenu."'";
			$resDelSubMenu=mysql_query($sqlDelSubMenu,$this->conexion);
			if($resDelSubMenu){
				echo "<script type='text/javascript'> alert('Menu Eliminado'); mostrarOpcionesMenu(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Eliminar el Menu'); </script>";
			}
		}
		
		function eliminaSubMenu($idSubMenu){
			$sqlDelSubMenu="DELETE FROM submenu WHERE id = '".$idSubMenu."'";
			$resDelSubMenu=mysql_query($sqlDelSubMenu,$this->conexion);
			if($resDelSubMenu){
				echo "<script type='text/javascript'> alert('Submenu Eliminado'); mostrarOpcionesMenu(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Eliminar el Submenu'); </script>";
			}
		}
		
		function leer_fichero_completo($nombre_fichero){						
			$pI=""; $pF="";			
			$texto = file_get_contents($nombre_fichero); //Leemos y guardamos en $texto el archivo texto.txt
			
			$pI=strpos($texto,"/*");
			$pF=strpos($texto,"*/");
			//echo $pI."<br>".$pF."<br>";
			if($pI=="" || $pF==""){
				echo "El archivo no contiene las cabeceras de comentarios";
			}else{
				$texto = nl2br($texto); //Reemplazamos $texto con un nuevo $texto, pero cambiando los saltos de linea ( ) por un salto de linea en html (br)
				$texto=str_replace("/*","|",$texto);
				$texto=str_replace("*/","|",$texto);
				$nvoTexto=explode("|",$texto);
				//echo "<pre>".print_r($nvoTexto)."</pre>";
				echo $nvoTexto[1];
			}
			
		}	
		
		public function verModulosSistema(){
			$path="../../modulos"; $path2="../modulos";
			$directorio=dir($path);
			
?>
			<div style="position: absolute;width: 99.3%;height: 99.3%;border: 1px solid #000;margin: 2px;">
				<div style="float: left;width: 55%;height: 98%;margin: 2px;border: 1px solid #ccc;background: #f0f0f0;overflow: auto;">
					<div style="height:25px; padding:5px;">Modulos del Sistema</div>
<?	
			while ($archivo = $directorio->read()){
				if(substr($archivo,0,4)=="mod_"){
					$path3=$path."/".$archivo;
					$directorio1=dir($path3);
?>
					<div style="height: 150px;width: 250px;float: left;padding: 5px;border: 1px solid #CCC;background: #fff;margin: 5px;position: relative;">
<?
					echo "<div style='height:20px;padding:5px;margin-bottom:3px;font-weight:bold;border:1px solid #000;'>->&nbsp;".$path2."/".$archivo."</div>";
					echo "<div style='border:0px solid #ff0000;height:117px;overflow-y: auto;overflow-x: hidden;'>";
					while($archivo1 = $directorio1->read()){
						if($archivo1 !="_notes" && $archivo1 != "." && $archivo1 != ".."){
							echo "<div style='height:auto;margin-left:25px;margin-bottom:5px;color:blue;border:0px solid #ff0000;'>--><a href='#' onclick='leerArchivo(\"".$path3."/".$archivo1."\")' style='color:blue;text-decoration:none;'>".$archivo1."</a></div>";	
						}						
					}
					echo "</div>";
?>
					</div>					
<?				
				}	   
			}
?>			
				</div>
				<div id="contenidoArchivo" style="float: left;width: 42%;height: 98%;margin: 2px;border: 1px solid #ccc;background: #fff;overflow: auto;"></div>
			</div>
<?					
			$directorio->close();
		}
		
		public function guardarModificacionMenuTitulo($nombreMenuTitulo,$numeroMenuAct,$idElementoAct){
			$sqlActMenu="UPDATE gruposmods set modulo='".$nombreMenuTitulo."',numeroMenu='".$numeroMenuAct."' WHERE id='".$idElementoAct."'";
			$resActMenu=mysql_query($sqlActMenu,$this->conexion);
			if(mysql_affected_rows() >= 1){
				echo "<script type='text/javascript'> alert('Informacion actualizada'); mostrarOpcionesMenu(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Ocurrieron errores al actualizar o no se hicieron cambios en la informacion'); mostrarOpcionesMenu();</script>";
			}
		}
		
		public function modificaMenuTitulo($idMenuTitulo){
			$sqlMenuTitulo="SELECT * FROM gruposmods WHERE id='".$idMenuTitulo."'";
			$resMenutitulo=mysql_query($sqlMenuTitulo,$this->conexion);
			$rowMenuTitulo=mysql_fetch_array($resMenutitulo);
?>
			<input type="hidden" name="txtIdElementoMenuTitulo" id="txtIdElementoMenuTitulo" value="<?=$idMenuTitulo;?>">
			<table border="0" cellpadding="1" cellspacing="1" width="480" style="margin: 10px;font-size: 12px;">
				<tr>
					<td colspan="2" style="height: 20px;padding: 5px;background: #000;color: #fff;">Modificar Informaci&oacute;n Men&uacute;:</td>
				</tr>
				<tr>
					<td style="width: 100px;height: 20px;padding: 5px;border: 1px solid #ccc;background: #f0f0f0;">Nombre</td>					
					<td style="width: 300px;"><input type="text" name="txtNombreMenuAct" id="txtNombreMenuAct" value="<?=$rowMenuTitulo["modulo"];?>"></td>
				</tr>
				<tr>
					<td style="height: 20px;padding: 5px;border: 1px solid #ccc;background: #f0f0f0;">Numero de Menu:</td>
					<td><input type="text" name="txtNumeroMenuAct" id="txtNumeroMenuAct" value="<?=$rowMenuTitulo["numeroMenu"];?>"></td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #666;"></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;"><input type="button" value="Guardar" onclick="guardarMenuTituloActualizacion()"></td>
				</tr>
			</table>
<?
		}
		
		public function guardarSubmenuAct($idElementoAct,$txtNombreSubMenuAct,$txtRutaAct,$cboStatusSubmenuAct){
			$sql="UPDATE submenu SET nombreSubMenu='".$txtNombreSubMenuAct."',rutaSubMenu='".$txtRutaAct."',activo='".$cboStatusSubmenuAct."' WHERE id='".$idElementoAct."'";
			$res=mysql_query($sql,$this->conexion);
			if(mysql_affected_rows() >=1 ){
				echo "<script type='text/javascript'> alert('Actualizacion Realizada'); agregarSubMenu(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Advertencia: La actualizacion no se realizo \n\n No se modifico la informacion'); </script>";
			}
		}
		
		public function modificarSubmenu($id){
			$sql="SELECT * FROM submenu WHERE id='".$id."'";
			$res=mysql_query($sql,$this->conexion);
			$row=mysql_fetch_array($res);
			($row["activo"]==1) ? $valor="Activo" : $valor="Inactivo";
?>			
			<input type="hidden" name="txtIdElementoAct" id="txtIdElementoAct" value="<?=$id;?>">
			<table border="1" cellpadding="1" cellspacing="1" width="480" style="margin: 10px;">
				<tr>
					<td colspan="2">Agregar Item Submen&uacute;</td>
				</tr>
				<tr>
					<td style="width: 100px;">Nombre</td>					
					<td style="width: 300px;"><input type="text" name="txtNombreSubMenuAct" id="txtNombreSubMenuAct" value="<?=$row["nombreSubMenu"];?>"></td>
				</tr>
				<tr>
					<td>Ruta</td>
					<td><input type="text" name="txtRutaAct" id="txtRutaAct" value="<?=$row["rutaSubMenu"];?>" style="width:250px; font-size:14px;" />&nbsp;<input type="button" value="Ver Modulos" onclick="listarModulos()" /></td>
				</tr>
				<tr>
					<td colspan="2"><div id="listadomodulos" style=" display:none;height:250px; overflow:auto; border:1px solid #CCC;"></div></td>
				</tr>
				<tr>
					<td>Activo</td>
					<td>
						<select name="cboStatusSubmenu" id="cboStatusSubmenuAct" style="width: 150px;">
							<option value="<?=$row["activo"];?>" selected="selected"><?=$valor;?></option>
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #666;"></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;"><input type="button" value="Guardar" onclick="guardarSubMenuActualizacion()"></td>
				</tr>
			</table><br><br><div id="divGuardadoSubMenu"></div>
<?			
		}
		
		public function guardarSubmenu($idElemento,$txtNombreSubMenu,$txtRuta,$cboStatusSubmenu){
			$sql="INSERT INTO submenu (id_menu,nombreSubMenu,rutaSubMenu,activo) VALUES ('".$idElemento."','".$txtNombreSubMenu."','".$txtRuta."','".$cboStatusSubmenu."')";
			$res=mysql_query($sql,$this->conexion);
			if($res){
				echo "<script type='text/javascript'> alert('Elemento Guardado'); mostrarOpcionesMenu(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar Elemento'); agregarSubMenu(); </script>";
			}
		}
		
		public function agregarItemSubmenu($idElemento){
?>			
			<input type="hidden" name="txtIdElemento" id="txtIdElemento" value="<?=$idElemento;?>">
			<table border="0" cellpadding="1" cellspacing="1" width="480" style="margin: 10px;font-size: 12px;">
				<tr>
					<td colspan="2" style="height: 20px;padding: 5px;background: #000;color: #fff;">Agregar Submen&uacute;</td>
				</tr>
				<tr>
					<td style="width: 100px;height: 20px;padding: 5px;border: 1px solid #ccc;background: #f0f0f0;">Nombre</td>					
					<td style="width: 300px;"><input type="text" name="txtNombreSubMenu" id="txtNombreSubMenu"></td>
				</tr>
				<tr>
					<td style="height: 20px;padding: 5px;border: 1px solid #ccc;background: #f0f0f0;">Ruta</td>
					<td><input type="text" name="txtRuta" id="txtRuta" style="width:250px; font-size:14px;" />&nbsp;<input type="button" value="Ver Modulos" onclick="listarModulos()" /></td>
				</tr>
				<tr>
					<td colspan="2"><div id="listadomodulos" style=" display:none;height:250px; overflow:auto; border:1px solid #CCC;"></div></td>
				</tr>
				<tr>
					<td style="height: 20px;padding: 5px;border: 1px solid #ccc;background: #f0f0f0;">Activo</td>
					<td>
						<select name="cboStatusSubmenu" id="cboStatusSubmenu" style="width: 150px;">
							<option value="" selected="selected">Selecciona...</option>
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #666;"></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;"><input type="button" value="Guardar" onclick="guardarSubMenu()"></td>
				</tr>
			</table><br><br><div id="divGuardadoSubMenu"></div>
<?
		}
		
		public function mostrarOpcionesMenu(){
			$sql="Select * FROM gruposmods WHERE pertenece_a='Menu' Order By numeroMenu";
			$res=mysql_query($sql,$this->conexion);			
?>
			<div style="height: 20px;padding: 5px;background: #f0f0f0;border:1px solid #CCC;"><a href="#" onclick="nuevaFuncionalidad()" style="text-decoration: none;color: blue;">Agregar Men&uacute;</a></div>
			<div style="border: 1px solid #000;height: 94%;width: 99%;margin: 3px;">
				<div style="float: left;width: 47%;height: 99%;border: 1px solid #CCC;margin: 2px;overflow: auto;">
				<table border="0" cellpadding="1" cellspacing="1" width="400" style="margin: 10px;font-size: 12px;">
					<tr>
						<td colspan="2" style="background: #000;color: #fff;height: 23px;padding: 5px;">Agregar Men&uacute; - Submen&uacute;</td>
					</tr>
					<tr>
						<td width="350" style="border: 1px solid #CCC;background: #f0f0f0;height: 20px;padding: 5px;">Nombre</td>
						<td width="50" style="border: 1px solid #CCC;background: #f0f0f0;height: 20px;padding: 5px;">Acci&oacute;n</td>				
					</tr>
<?
			$i=0;
			while($row=mysql_fetch_array($res)){
				$nombreDiv="Submenu".$i;
				//se extraen los submenus si existen
				$sqlSub="SELECT * FROM submenu WHERE id_menu='".$row["id"]."' AND activo='1'";
				$resSub=mysql_query($sqlSub,$this->conexion);
?>
					<tr>
						<td style="text-align: left;background: #f0f0f0;border: 1px solid #CCC;height: 20px;padding: 5px;">
							<a href="#" title="Eliminar Menu" onclick="eliminarMenu('<?=$row["id"];?>','<?=$row["modulo"];?>')" style="color: blue;"><img src="../../img/icon_delete.gif" border="0" /></a>
							=><?=$row["numeroMenu"]." - ";?><a href="#" onclick="modificarMenuTitulo('<?=$row["id"]?>')" title="Modificar Menu" style="color: blue;font-size: 12px;text-decoration: none;"><?=$row["modulo"];?></a>&nbsp;							
						</td>
						<td style="text-align: center;"><a href="#" title="Agregar Submenu" onclick="agregarItemSubMenu('<?=$row["id"];?>')" style="color: blue;"><img src="../../img/add.png" border="0" /></a></td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="<?=$nombreDiv;?>">
<?				
				if(mysql_num_rows($resSub)!=0){
					while($rowsub=mysql_fetch_array($resSub)){
?>
						<div style="height: 15px;padding: 5px;">							
							==><a href="#" title="Eliminar Menu" onclick="eliminaSubmenu('<?=$rowsub['id']?>','<?=$rowsub["nombreSubMenu"];?>')" style="color: blue;"><img src="../../img/icon_delete.gif" border="0" /></a>&nbsp;
							<a href="#" title="Modificar Submen&uacute;" onclick="modificarSubmenu('<?=$rowsub["id"];?>')" style="font-size: 12px;"><?=$rowsub["nombreSubMenu"]?></a>
						</div>
<?
					}
				}
?>							
							</div>
						</td>
					</tr>
<?
				$i+=1;
			}
?>
				</table>	
				</div>
				<div id="divSubMenu" style="float: left;width: 47%;height: 99%;border: 1px solid #CCC;margin: 2px;overflow: auto;"></div>
			</div>
			
<?
		}
		
		public function guardaConfNueva($nombreConf,$valor,$descripcion){
			$sql="INSERT INTO configuracionglobal(nombreConf,valor,descripcion) VALUES ('".$nombreConf."','".$valor."','".$descripcion."')";
			$res=mysql_query($sql,$this->conexion);
			if($res){
				echo "<script type='text/javascript'> alert('Configuracion Guardada'); configuracionesGlobales(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar la Configuracion');</script>";
			}
		}
		
		public function formAgergarConf(){
			//$objVerifica=new verificaUsuario();
			//$objVerifica->cargaArchivosClase();
			//$objVerifica->muestraFormularioUsuario();
?>
			<table align="center" border="0" cellpadding="1" cellspacing="1" width="600" style="margin: 5px;font-size: 12px;border: 1px solid #000;">
				<tr>
					<td colspan="2" style="height: 20px;padding: 5px;border: 1px solid #000;background: #000;color: #fff;">Agregar Configuracion Global para el Sistema</td>
				</tr>
				<tr>
					<td style="height: 20px;padding: 5px;background: #f0f0f0;color: #000;">Nombre Configuraci&oacute;n</td>
					<td><input type="text" name="txtNombreConfiguracion" id="txtNombreConfiguracion"></td>
				</tr>
				<tr>
					<td style="height: 20px;padding: 5px;background: #f0f0f0;color: #000;">Valor</td>
					<td><input type="text" name="txtValorConfiguracion" id="txtValorConfiguracion"></td>
				</tr>
				<tr>
					<td style="height: 20px;padding: 5px;background: #f0f0f0;color: #000;">Descripci&oacute;n</td>
					<td><textarea name="txtDescripcion" id="txtDescripcion" cols="25" rows="3"></textarea></td>
				</tr>
				<tr>
					<td colspan="2"></td>					
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="button" value="Guardar" onclick="guardarConfiguracionGlobal()"></td>					
				</tr>
			</table>	
<?
		}
		
		public function eliminarValorConfiguracion($id,$nvoValor){			
			$sql="DELETE FROM configuracionglobal where id='".$id."'";
			$res=mysql_query($sql,$this->conexion);
			if($res){
				echo "<script type='text/javascript'> alert('Registro Eliminado'); configuracionesGlobales(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al eliminar el Registro Seleccionado'); </script>";
			}
		}
		
		public function modificarValorConfiguracion($id,$nvoValor){
			$sql="UPDATE configuracionglobal set valor='".$nvoValor."' WHERE id='".$id."'";
			$res=mysql_query($sql,$this->conexion);
			if(mysql_affected_rows()>=1){
				echo "<script type='text/javascript'> alert('Actualizacion Realizada'); configuracionesGlobales(); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error, puede deberse a las siguientes causas:\n\n no se modifico el valor de la configuracion \n\nOcurrieron errores al actualizar'); </script>";
			}
		}
		
		public function mostrarConfiguracionesGlobales(){
			//se extaen las configuraciones guardadas
			$sqlC="SELECT * FROM configuracionglobal order by id";
			$resC=mysql_query($sqlC,$this->conexion);
			if(mysql_num_rows($resC)==0){
				echo "No existen configuraciones globales";
			}else{
?>
				<table border="0" cellpadding="1" cellspacing="1" width="650" style="margin: 5px;font-size: 12px;border: 1px solid #000;">
					<tr>
						<td colspan="4" style="height: 20px;padding: 5px;">Configuraciones Globales para el Sistema</td>
					</tr>
					<tr>
						<td style="border: 1px solid #000;background: #000;color: #fff;">&nbsp;</td>
						<td style="border: 1px solid #000;background: #000;color: #fff;height: 20px;padding: 5px;">Nombre Configuracion</td>
						<td style="border: 1px solid #000;background: #000;color: #fff;height: 20px;padding: 5px;">Valor</td>
						<td style="border: 1px solid #000;background: #000;color: #fff;height: 20px;padding: 5px;">Descripci&oacute;n</td>
					</tr>
<?
				while($rowC=mysql_fetch_array($resC)){					
?>
					<tr>
						<td style="text-align: center;border-bottom: 1px solid #000;border-right: 1px solid #000;height: 25px;padding: 5px;">
<?
					if($rowC["nombreConf"]=="sitio_desactivado"){
						echo "N/A";
					}else{
?>
						<a href="#" onclick="modificarValorConf('<?=$rowC["nombreConf"]?>','<?=$rowC["valor"];?>','<?=$rowC["id"]?>')" title="Modificar">Modificar</a>&nbsp;|&nbsp;
						<a href="#" onclick="eliminarValorConf('<?=$rowC["nombreConf"]?>','<?=$rowC["valor"];?>','<?=$rowC["id"]?>')" title="Eliminar">Eliminar</a>
<?
					}
?>							
						</td>
						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;height: 25px;padding: 5px;"><?=$rowC["nombreConf"];?></td>
						<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;height: 25px;padding: 5px;"><?=$rowC["valor"];?></td>
						<td style="border-bottom: 1px solid #000;height: 25px;padding: 5px;">&nbsp;<?=$rowC["descripcion"]?></td>
					</tr>
<?
				}
?>
				</table>
<?
			}			
		}
		
		public function listarImagen(){		
			$path="../../img/imagenes/";
			$directorio=dir($path);
?>
			<table width="99%" border="0" cellspacing="1" cellpadding="1" style="margin:5px;">
				<tr>                
					<td><a href="#" onclick="cierraDiv('listadoimagen')">Cerrar</a></td>
				</tr>
				<tr>
				  <td colspan="2" style="height:25px; padding:5px;">Imagenes del Sistema</td>
				</tr>          
				<tr>
				  <td width="6%">&nbsp;</td>
				  <td width="94%">&nbsp;</td>
				</tr>
<?			
			while ($archivo1 = $directorio->read()){
				if(($archivo1!=".") && ($archivo1!="..")){				
?>
				<tr>
					<td><img src="<?=$path.$archivo1;?>" border="0" /></td>
					<td><input type="text" value="<?=$path.$archivo1;?>" style="width:350px;" /></td>
				</tr>
<?				
		   		}	   
			}	
			$directorio->close();
?>
			</table>
<?		
		
		}
		
		//listado de modulos
		public function listarModulos(){
			$path="../../modulos"; $path2="../modulos";
			$directorio=dir($path);
?>
			<table width="99%" border="0" cellspacing="1" cellpadding="1" style="margin:5px;">
				<tr>                
					<td><a href="#" onclick="cierraDiv('listadomodulos')">Cerrar</a></td>
				</tr>
				<tr>
					<td style="height:25px; padding:5px;">Modulos del Sistema</td>
					<td>&nbsp;</td>
				</tr>          
				<tr>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				</tr>
<?	
			while ($archivo = $directorio->read()){
			   if(substr($archivo,0,4)=="mod_"){				
?>
				<tr>
					<td><input type="text" value="<?=$path2."/".$archivo;?>/index.php" style="width:350px;" /></td>
					<td>&nbsp;</td>
				</tr>
<?				
		   		}	   
			}	
			$directorio->close();
?>
			</table>
<?		
		}
		
		//actualizacion del grupo
		public function actualizaGrupo($permisos,$idGrupo){
			//echo "<br>".
			$sqlActualizaGrupo="UPDATE grupos set opcFuncional='".$permisos."' WHERE id='".$idGrupo."'";
			$resActualizaGrupo=mysql_query($sqlActualizaGrupo,$this->conexion);
			echo "<br>&nbsp;&nbsp;".mysql_affected_rows()." registro(s) afectado(s) en la Base de Datos.";
		}
		//modificacion del grupo
		public function modificaGrupo($idGrupo){
			$sqlGrupoActual="SELECT * FROM grupos WHERE id='".$idGrupo."'";
			$resGrupoActual=mysql_query($sqlGrupoActual,$this->conexion);
			$rowGrupoActual=mysql_fetch_array($resGrupoActual);
			$privilegios=$rowGrupoActual['opcFuncional'];
			$privilegios=explode(",",$privilegios);
			//funcionalidades
			$sqlFuncionalidades="SELECT * FROM gruposmods WHERE activo=1  ORDER BY numeroMenu";
			$result_modulos=mysql_query($sqlFuncionalidades,$this->conexion);
			$regs=mysql_num_rows($result_modulos);
?>			
			<form name="frmModificaGrupo" id="frmModificaGrupo"><br>
				<table width="700" align="center" border="0" cellpadding="1" cellspacing="1" style="font-size:12px;">
					<tr>
						<td colspan="3" style="height:25px; background:#000000; color:#FFFFFF;">Modificar Informacion del Grupo</td>
					</tr>
					<tr>
						<td width="50%" style="height:25px; border:1px solid #999; background:#CCC;">Nombre del Grupo:</td>
					    <td colspan="2" width="50%">&nbsp;<?=$rowGrupoActual['nombre'];?></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>                    
					</tr>					
					<tr>
						<td colspan="2" align="left" style=" background:#CCC; border:1px solid #999;">Modulo / Submenu </td>						
						<td width="104" align="center" style=" background:#CCC; border:1px solid #999;">Permiso</td>						
					</tr>
<?
				if($regs != 0){
					$i=0;
					while($row_modulos=mysql_fetch_array($result_modulos)){
						$cboMnuP="cbo".$i;//nombre del campo oculto
						//se muestran la estructura del menu
						$sqlS="SELECT * FROM submenu where id_menu='".$row_modulos["id"]."'";
						$resS=mysql_query($sqlS,$this->conexion);
						if(mysql_num_rows($resS)!=0){
							$nRegMenu=mysql_num_rows($resS);	
						}else{
							$nRegMenu=0;	
						}
						$valorMnuP=$row_modulos['id']."?";
?>
						<tr>
							<td colspan="2" style="height:25px; border-bottom:1px solid #CCC;background: #f0f0f0;font-weight: bold;"><?=$row_modulos["modulo"];?></td>
							<td style="height:25px; border-bottom:1px solid #CCC;text-align: center;background: #f0f0f0;">&nbsp;
								<input type="hidden" name="<?=$cboMnuP;?>" id="<?=$cboMnuP;?>" value="<?=$nRegMenu?>" />
								<!--<input type="checkbox" name="cb" value="<?=$valorMnuP;?>" onClick="if(this.checked == true){seleccionarMenuCompleto('<?=$nRegMenu;?>','<?=$row_modulos["id"];?>')} else{quitarSeleccionMenuCompleto('<?=$nRegMenu;?>','<?=$row_modulos["id"];?>')}" />-->
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<table border="0" cellpadding="1" cellspacing="1" width="610" style="font-size: 12px;">
<?
						$j=0;
						while($rowS=mysql_fetch_array($resS)){
							$cbo="cbo".$row_modulos["id"].$j;
							//echo $rowS["id"];
							if(in_array($rowS["id"],$privilegios)){								
							echo "<tr>
								<td width='480' style='height:25px; border-bottom:1px solid #CCC;'>--&nbsp;".$rowS["nombreSubMenu"]."</td>
								<td width='120' style='height:25px; border-bottom:1px solid #CCC;'><input type='checkbox' name='cb' id='".$cbo."' checked='checked' value='".$rowS['id']."' /></td>
							      </tr>";								
							}else{							
							echo "<tr>
								<td width='480' style='height:25px; border-bottom:1px solid #CCC;'>--&nbsp;".$rowS["nombreSubMenu"]."</td>
								<td width='120' style='height:25px; border-bottom:1px solid #CCC;'><input type='checkbox' name='cb' id='".$cbo."' value='".$rowS['id']."' /></td>
							      </tr>";
							}
							$j+=1;
						}
?>
								</table>
							</td>
						</tr>
<?
						$i+=1;

					}
				}else{
?>
					<tr>
						<td colspan="6">No hay Modulos Activos</td>
					</tr>
<?			
				}
?>					
					<tr>
						<td colspan="3"><hr style="color:#CCC;" /></td>                    
					</tr>
					<tr>
						<td colspan="3" align="right"><input type="button" value="Guardar Cambios" onclick="actualizaGrupo('<?=$idGrupo;?>')" /></td>                    
					</tr>
				</table>
			</form>
<?			
		}
		//consulta de grupos
		public function consultaGrupos(){
			$sqlGrupos="SELECT * FROM grupos";
			$resultGrupos=mysql_query($sqlGrupos,$this->conexion);
			
			if(mysql_num_rows($resultGrupos)==0){
				echo " ( 0 ) Registros encontrados en la Base de Datos.";
			}else{
?>
				<table width="800" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
					<tr>
						<td width="228" align="center" style="height:30px; background:#000000; color:#FFFFFF;">Nombre</td>
						<td width="350" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Fecha / Hora creaci&oacute;n</td>
						<td width="76" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Activo</td>
						<td width="133" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Funcionalidades</td>
					</tr>                  
<?			
				while($rowGrupos=mysql_fetch_array($resultGrupos)){
					$valores=explode(",",$rowGrupos['opcFuncional']);
?>
					<tr>
						<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC; border-left:1px solid #CCC;"><a href="javascript:modificaGrupo('<?=$rowGrupos['id'];?>')" title="Modificar los privilegiios de este grupo"><?=$rowGrupos['nombre'];?></a></td>
						<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowGrupos['fecha_hora_creacion'];?></td>
						<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowGrupos['activo'];?></td>
						<td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
						<div style="height:auto; overflow:auto;">
<?
					for($i=0;$i<count($valores);$i++){
						//$sqlModulos="SELECT modulo FROM gruposmods WHERE id='".$valores[$i]."'";
						$sqlModulos="SELECT nombreSubMenu FROM submenu WHERE id='".$valores[$i]."'";
						$resultModulos=mysql_query($sqlModulos,$this->conexion);
						$rowModulos=mysql_fetch_array($resultModulos);
						echo $rowModulos['nombreSubMenu']."<br>";
					}
?>
						</div>
						</td>
					</tr>
<?					
				}
?>
			</table>
<?
			}
		}
		//guarda Grupo
		function guardaGrupo($nombreGrupo,$permisos){
			$horaCreacion=date("Y-m-d")."/".date("H:i:s");
			$sqlGuardaGrupo="INSERT INTO grupos (fecha_hora_creacion,nombre,opcFuncional) values('".$horaCreacion."','".$nombreGrupo."','".$permisos."')";
			$resultGuardaGrupo=mysql_query($sqlGuardaGrupo,$this->conexion);
			if($resultGuardaGrupo==true){
				echo "<br>Se ha creado el Grupo satisfactoriamente.<br>";
			}else{
				echo "<br>Se ha creado el Grupo satisfactoriamente.<br>";
			}
		}
		
		//agrega grupo
		function addGrupo(){		
			include("../../includes/conectarbase.php");
			//$sql_modulos="SELECT * FROM gruposmods WHERE activo='1'";
			//$sql_modulos="SELECT * FROM submenu WHERE activo='1'";
			$sql_modulos="SELECT * FROM gruposmods WHERE activo = '1' ORDER BY numeroMenu";
			$result_modulos=mysql_query($sql_modulos,$this->conexion);
			$regs=mysql_num_rows($result_modulos);
?>
			<form name="crearGrupo" id="crearGrupo"><br />
				<input type="hidden" name="ndregistros" id="ndregistros" value="<?=$regs;?>" />
				<table width="700" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
					<tr>
						<td colspan="6" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Agregar Nuevo Grupo</td>
					</tr>
					<tr>
						<td width="138">Nombre del Grupo</td>
						<td colspan="2"><input type="text" name="nombreGrupo" id="nombreGrupo" style="width:250px; font-size:14px;" />
							<input type="radio" name="grupo" id="grupo2" value="grupos" checked="checked" onchange="filtrarNombres('filaGrupo')" />Grupos
							<input type="radio" name="grupo" id="grupo" value="area" onchange="filtrarNombres('Depto')" />Area       
						</td>					
					</tr>
					<tr>
						<td colspan="3" style="height:25px;">Seleccione los Privilegios del grupo en el Sistema</td>
					</tr>
					<tr>
						<td colspan="2" align="left" style=" background:#CCC; border:1px solid #999;">Modulo / Submenu </td>						
						<td width="104" align="center" style=" background:#CCC; border:1px solid #999;">Permiso</td>						
					</tr>
<?
				if($regs != 0){
					$i=0;
					while($row_modulos=mysql_fetch_array($result_modulos)){
						$cboMnuP="cbo".$i;//nombre del campo oculto
						//se muestran la estructura del menu
						$sqlS="SELECT * FROM submenu where id_menu='".$row_modulos["id"]."'";
						$resS=mysql_query($sqlS,$this->conexion);
						if(mysql_num_rows($resS)!=0){
							$nRegMenu=mysql_num_rows($resS);	
						}else{
							$nRegMenu=0;	
						}
						$valorMnuP=$row_modulos['id']."?";
?>
						<tr>
							<td colspan="2" style="height:25px; border-bottom:1px solid #CCC;background: #f0f0f0;font-weight: bold;"><?=$row_modulos["modulo"];?></td>
							<td style="height:25px; border-bottom:1px solid #CCC;text-align: center;background: #f0f0f0;">&nbsp;
								<input type="hidden" name="<?=$cboMnuP;?>" id="<?=$cboMnuP;?>" value="<?=$nRegMenu?>" />
								<!--<input type="checkbox" name="cb" value="<?=$valorMnuP;?>" onClick="if(this.checked == true){seleccionarMenuCompleto('<?=$nRegMenu;?>','<?=$row_modulos["id"];?>')} else{quitarSeleccionMenuCompleto('<?=$nRegMenu;?>','<?=$row_modulos["id"];?>')}" />-->
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<table border="0" cellpadding="1" cellspacing="1" width="610" style="font-size: 12px;">
<?
						$j=0;
						while($rowS=mysql_fetch_array($resS)){
							$cbo="cbo".$row_modulos["id"].$j;
							echo "<tr>
								<td width='480' style='height:25px; border-bottom:1px solid #CCC;'>--&nbsp;".$rowS["nombreSubMenu"]."</td>
								<td width='120' style='height:25px; border-bottom:1px solid #CCC;'><input type='checkbox' name='cb' id='".$cbo."' value='".$rowS['id']."' /></td>
							      </tr>";
							$j+=1;
						}
?>
								</table>
							</td>
						</tr>
<?
						$i+=1;

					}
				}else{
?>
					<tr>
						<td colspan="6">No hay Modulos Activos</td>
					</tr>
<?			
				}
?>
				<tr>
					<td colspan="6"><hr color="#CCCCCC" /></td>
				</tr>
				<tr>
					<td colspan="6" align="right"><input type="button" value="Guardar Grupo" onclick="guardaGrupo()" /></td>
				</tr>            
	</table></form><br />
	<?			
		}
		//reset pass
		public function resetPass($id_usr){
			include("../../../includes/config.inc.php");
			include("../../../includes/conectarbase.php");
			$pass=$strP;
			$sql_reset="UPDATE $tabla_usuarios set pass='".$strP."',cambiarPass='0' WHERE ID='".$id_usr."'";
			$result_reset=mysql_query($sql_reset,$this->conexion);
			if($result_reset==true){
				$this->mensajes(2);
			}else{
				$this->maneja_error(8);
			}
		}
		//datos a modificar
		public function datosActualizados($nombre,$apaterno,$usr,$nivel_acceso,$sexo,$grupo,$grupo2,$activo,$idUsuarioAct,$nomina){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			
			$sql_updateUsr="UPDATE ".$tabla_usuarios." SET usuario='".$usr."',nombre='".$nombre."',apaterno='".$apaterno."',nivel_acceso='".$nivel_acceso."',sexo='".$sexo."',grupo='".$grupo."',grupo2='".$grupo2."',activo='".$activo."',nomina='".$nomina."' WHERE Id='".$idUsuarioAct."'";
			$result_datosAct=mysql_query($sql_updateUsr,$this->conexion);
			if($result_datosAct==true){
				$this->mensajes(1);
			}else{
				$this->maneja_error(7);
			}
		}//fin datos a modificar
		
		//guarda usuario
		public function guardaUsuario($nombre,$apaterno,$usuarioR,$pass1,$pass2,$nivel_acceso,$sexo,$grupo,$grupo2,$obs,$nomina){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			//se forza a una segunda validacion
			if ($pass1=="" or $pass2=="" or $usuarioR=="" or $nivel_acceso==""){ maneja_error(1); }
			if ($pass1 != $pass2){ maneja_error(2); }
			
			//se verifica que el nombre de usuario no exista en la base de datos
			$usuarioR=stripslashes($usuarioR);
			$sql_verificaUser="select * from $tabla_usuarios where usuario='".$usuarioR."'";
			$result_verificaUser=mysql_query($sql_verificaUser,$this->conexion);
			$total_encontrados = mysql_num_rows ($result_verificaUser);
			if ($total_encontrados != 0) {
				$this->maneja_error(4);
			}else{
				$pass1 = md5($pass1);
				//se inserta el registro en la base de datos
				$sql_1="insert into $tabla_usuarios (nombre,apaterno,usuario,pass,nivel_acceso,sexo,grupo,grupo2,obs,nomina)";
				//$sql_2="values ('".$nombre."','".$apellido."','".$usuarioNuevo."','".$pass1."','".$nivel."','".$sexo."','".$no_empleado."','".$grupoUsuario."','".date('Y-m-d')."','".date('H:i:s')."','".$obs."')";
				$sql_2="values ('".$nombre."','".$apaterno."','".$usuarioR."','".$pass1."','".$nivel_acceso."','".$sexo."','".$grupo."','".$grupo2."','".$obs."','".$nomina."')";
				echo "<br>".$sql_inserta=$sql_1.$sql_2;
				$result_inserta=mysql_query($sql_inserta,$this->conexion);
				if($result_inserta==true){
					$this->mensajes(0);
				}else{
					$this->maneja_error(6);
				}
			}		
		}//fin guarda usuario
		
		//nuevo usuario
		public function nuevoUsuarioForm(){
			include("../../includes/conectarbase.php");
			$sql_grupos="SELECT * FROM grupos WHERE activo=1 ORDER BY nombre";
			$result_grupos=mysql_query($sql_grupos,$this->conexion);				
			$result_grupos2=mysql_query($sql_grupos,$this->conexion);
			$regs_grupos=mysql_num_rows($result_grupos);
			$regs_grupos2=mysql_num_rows($result_grupos2);
			$pagActual=$_SERVER['PHP_SELF'];
			/*************/
			
		
?>
        <div style="padding:10px;">
            <form method="post">
            	<input type="hidden" id="seleccionUsuario" name="seleccionUsuario" />
                <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Registro de nuevo Usuario</td>
                  </tr>
                  <!--<tr>
                  	<td colspan="2" align="left" style="height:25px;">Selecciona:                    
                    	<select name="seleccionCapturaUsuario" id="seleccionCapturaUsuario" onchange="seleeccionCaptura()">
                        	<option value="">Selecciona</option>
                            <option value="usrSistema">Usuario del Sistema</option>
                            <option value="usrPersona">Personal en General</option>
                        </select>
                    </td>
                  </tr>-->
              </table>
                  <div id="datosUsuarioPersonales" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
                      <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Nombre</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtNombre" id="txtNombre" style="width:250px; font-size:14px;" /></td>
                      </tr>
                      <tr>
                        <td class="bordesTitulos" style="height:25px;">Apellido Paterno</td>
                        <td class="bordesContenido" style="height:25px;"><input type="text" name="txtPaterno" id="txtPaterno" style="width:250px;" /></td>
                      </tr>
                      <tr>
                      	<td class="bordesTitulos" style="height:25px;">No. de Nomina</td>
                        <td class="bordesContenido" style="height:25px;"><input type="text" name="txtNomina" id="txtNomina" style="width:250px;" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                  </table>
                  </div>
                  <div id="datosUsuarioSistemaP" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
                      <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Nombre de Usuario</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtUsuario" id="txtUsuario" style="width:250px;" /></td>
                        
                      </tr>
                      <tr>
                        <td class="bordesTitulos" style="height:25px;">Password:</td>
                        <td class="bordesContenido" style="height:25px;"><input type="password" name="txtPass" id="txtPass" style="width:250px;" /></td>
                      </tr>
                      <tr>
                        <td class="bordesTitulos" style="height:25px;">Password repitalo:</td>
                        <td class="bordesContenido" style="height:25px;"><input type="password" name="txtPass1" id="txtPass1" style="width:250px;" /></td>
                      </tr>
                  </table>
                  </div>
                  <!--<div id="datosAdicionales1" style="display:block; margin:5px;">
	                <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      
                      <tr>
                      	<td class="bordesTitulos">No Nomina</td>
                        <td class="bordesContenido"><input type="text" name="idNoNominaUsuario" id="idNoNominaUsuario" /> </td>
                      </tr>
                    </table>  
                </div>-->
              <div id="datosAdicionales" style="display:block; margin:5px;">
                <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="156" class="bordesTitulos" style="height:25px;">Nivel de Acceso</td>
                    <td width="350" class="bordesContenido" style="height:25px;"><select name="nivelAcceso" id="nivelAcceso" style="width:250px;">
                        <option value="--" selected="selected">Selecciona...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="bordesTitulos" style="height:25px;">Sexo:</td>
                    <td class="bordesContenido" style="height:25px;"><select name="lstSexo" id="lstSexo" style="width:250px;">
                        <option value="--" selected="selected">Selecciona...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="bordesTitulos" style="height:25px;">Grupo 1:</td>
                    <td class="bordesContenido" style="height:25px;"><span class="bordesContenido" style="height:25px;">
                      <?
						if($regs_grupos != 0){
?>
                    </span>
                      <select name="cboGrupoUsuario" id="cboGrupoUsuario" style="width:250px;">
                <option value="--" selected="selected">Selecciona...</option>
                          <?
								while($row_grupos=mysql_fetch_array($result_grupos)){
									if(substr($row_grupos['nombre'],0,5)=="Depto"){
?>
									<option value="<?=$row_grupos['id'];?>"><?=substr($row_grupos['nombre'],6);?></option>
<?										
									}
								
								}
?>
                        </select>
                        <?							
						}else{
							echo "No hay Grupos definidos todavia.";
						}
?>
                      &nbsp;</td>
                  </tr>
                  
                  
                  
                   <tr>
                    <td class="bordesTitulos" style="height:25px;">Grupo 2:</td>
                    <td class="bordesContenido" style="height:25px;"><?
						if($regs_grupos2 != 0){
?>
                        <select name="cboGrupoUsuario2" id="cboGrupoUsuario2" style="width:250px;">
                          <option value="--" selected="selected">Selecciona...</option>
                          <?
								while($row_grupos2=mysql_fetch_array($result_grupos2)){
									if(substr($row_grupos2['nombre'],0,5)!="Depto"){
?>
									<option value="<?=$row_grupos2['id'];?>"><?=$row_grupos2['nombre'];?></option>
<?										
									}
							
								}
?>
                        </select>
                        <?							
						}else{
							echo "No hay Grupos definidos todavia.";
						}
?>
                      &nbsp;</td>
                  </tr>
                  
				  <tr>
                <td class="bordesTitulos">Observaciones</td>
                <td class="tblDato"><textarea cols="25" rows="3" name="txtObservaciones" id="txtObservaciones"><?php echo $regsEquipo["obs"]; ?></textarea></td>                
              </tr>

                  <tr>
                    <td colspan="2" style="height:25px;" align="right">&nbsp;
                        <input name="button" type="button" onclick="validacion()" value="Guardar Informaci&oacute;n" /></td>
                  </tr>
                </table>
              </div>                
            </form>
        </div>
<?
		}//fin nuevo usuario
		
		//borrar usuario
		public function borrarUsuario($id_usr){
			include("../../../includes/config.inc.php");
			include("../../../includes/conectarbase.php");
			$sql_elimina="UPDATE userdbnextel SET activo=0,fecha_eliminacion='".date('Y-m-d')."',hora_eliminacion='".date('H:i:s')."' WHERE ID='".$id_usr."'";
			$result_del1=mysql_query($sql_elimina,$this->conexion);
			if($result_del1==true){	
?>
				<div id="msgResetPass">
					<div style="margin:5px; font-size:12px; background:#FFF; height:190px; text-align:center;"><img src="../../img/clean.png" /> Se ha eliminado el usuario, exitosamente.<br /><br /><br /><input type="button" value="Cerrar" onclick="cierraMsgDel()" /></div>
				</div>
<?			
			}else{
?>
				<div id="msgResetPass">
					<div style="margin:5px; font-size:12px; background:#FFF; height:190px; text-align:center;"><img src="../../img/alert.png" /> Error al eliminar el usuario.<br /><br /><br /><input type="button" value="Cerrar" onclick="cierraMsgDel()" /></div>
				</div>
<?			
			}
		}//fin borrar usuario
		
		//listar los usuarios
		public function listarUsuarios($param,$orden,$txtBuscaInicio,$filtro){			
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			
			$resultBuscador=mysql_query($sqlParam,$this->conexion);
			//$Buscador=mysql_fetch_array($resultBuscador);
			
			//$sql_usuarios="select * from userdbnextel where nivel_acceso <>0 order by nombre asc";
			if($param==1){
				$sql_usuarios="SELECT * FROM $tabla_usuarios WHERE activo=1 ORDER BY  ".$orden."";	
			}else if($param==0){
				$sql_usuarios="SELECT * FROM $tabla_usuarios WHERE activo=0 ORDER BY ".$orden."";
			}else if($param=3){
				$sql_usuarios="SELECT * FROM $tabla_usuarios WHERE ".$filtro." LIKE '%".$txtBuscaInicio."%'";
			}
			
			//echo "<br>".$sql_usuarios;
			$result_usuarios=mysql_query($sql_usuarios,$this->conexion);
			$total_usuariosPer=mysql_num_rows($result_usuarios);
			

?>
				<!--<div id="desv">
					<div id="msgListaUsuarios">-->
					<div style="height:30px; color:#FFFFFF; background:#000000; font-size:12px;">
						<!--<div style="float:left;">Listado de Usuarios</div>&nbsp;&nbsp;&nbsp;
						<input type="text" name="txtBuscar" id="txtBuscar" onkeyup="Buscador()" style="width:250px; font-size:14px;"  />
						<input type="radio" name="rdbBusqueda" id="rdbBusqueda" value="nombre" checked="checked" />
						Por Nombre <input type="radio" name="rdbBusqueda" id="rdbBusqueda" value="usuario" />Por usuario-->
                        <input type="hidden" name="txtParam" id="txtParam" value="<?=$param;?>" />
                        <input type="hidden" name="txtOrden" id="txtOrden" value="<?=$orden;?>" />
                       <?php 
					   echo "<br>Resultados encontrados: ".($total_usuariosPer)."<br><br>";
					   ?>
                        
					  <div style="float:right;"><a href="javascript:cerrarDivUsuarios()"></a></div>
					</div>
					<!--<div style="margin:4px; height:auto; background:#FFFFFF;"><br />-->
<?
			if($total_usuariosPer != 0){		
?>
				<table width="800" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:10px;">
				  <tr>
					<td width="140" align="left" style="height:30px; background:#000000; color:#FFFFFF;"><a href="#" onclick="ordenarListado('<?=$param;?>','nombre')" style="text-decoration:none; color:#FFF;">Nombre</a></td>
					<td width="143" align="left" style="height:30px;background:#000000; color:#FFFFFF;"><a href="#" onclick="ordenarListado('<?=$param;?>','apaterno')" style="text-decoration:none; color:#FFF;">Apellido</a></td>
					<td width="107" align="center" style="height:30px;background:#000000; color:#FFFFFF;"><a href="#" onclick="ordenarListado('<?=$param;?>','usuario')" style="text-decoration:none; color:#FFF;">Usuario</a></td>
                    <td width="107" align="center" style="height:30px;background:#000000; color:#FFFFFF;">NIP</td>					
                    <td width="112" align="center" style="height:30px;background:#000000; color:#FFFFFF;"><a href="#" onclick="ordenarListado('<?=$param;?>','grupo')" style="text-decoration:none; color:#FFF;">Grupo1</a></td>
                    <td width="110" align="center" style="height:30px;background:#000000; color:#FFFFFF;"><a href="#" onclick="ordenarListado('<?=$param;?>','grupo2')" style="text-decoration:none; color:#FFF;">Grupo2</a></td>
					<td width="129" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Acciones</td>
				  </tr>
<?		
			$color="#f0f0f0";
			
			while($fila_usr=mysql_fetch_array($result_usuarios)){
				$sql_nip1="select id_usuario from userautoriza where id_usuario='".$fila_usr['ID']."'";
				$result_nip=mysql_query($sql_nip1,$this->conexion);
				$n_nip=mysql_num_rows($result_nip);
				($n_nip==0) ? $nip2="No" : $nip2="S&iacute;";
				/*consulta del nombre del grupo*/
				
				
				$sqlGrupo="SELECT nombre FROM grupos where id='".$fila_usr['grupo']."'"; 
			    $resultGrupo=mysql_query($sqlGrupo,$this->conexion);
				$grupo1=mysql_fetch_array($resultGrupo);
				$sqlGrupo1="SELECT nombre FROM grupos where id='".$fila_usr['grupo2']."'"; 
			    $resultGrupo1=mysql_query($sqlGrupo1,$this->conexion);
				$grupo2=mysql_fetch_array($resultGrupo1);
			//////////
			
?>
				  <tr style="background-color:<?=$color;?>" onmouseover="anterior=this.style.backgroundColor;this.style.backgroundColor='#D5EAFF'" onmouseout="this.style.backgroundColor=anterior">
					<td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$fila_usr['nombre'];?></td>
					<td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$fila_usr['apaterno'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$fila_usr['usuario'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$nip2;?></td>					
                    <td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$grupo1['nombre'];?></td>
                    <td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$grupo2['nombre'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC;">&nbsp;<a href="javascript:modificaUsuario('<?=$fila_usr['ID'];?>')">Modificar</a> | <a href="javascript:nip('<?=$fila_usr['ID'];?>')">NIP</a>  </td>
				  </tr>
<?			
				($color=="#f0f0f0") ? $color="#FFFFFF" : $color="#f0f0f0" ;
				
			}
?>
				</table><br />
<?
			}else{
				echo "<br><strong>&nbsp;No se encontraron registros en la Base de Datos.</strong><br>";
			}
?>
					<!--</div>-->
<!--					</div>
				</div>-->
<?		
		}//fin listar usuarios
		
		function maneja_error($numError){
			$error_accion_ms[0]= "No se puede borrar el Usuario, debe existir por lo menos uno.<br>Si desea borrarlo, primero cree uno nuevo.";
			$error_accion_ms[1]= "Faltan Datos.";
			$error_accion_ms[2]= "Passwords no coinciden.";
			$error_accion_ms[3]= "El Nivel de Acceso ha de ser numerico.";
			$error_accion_ms[4]= "El Nombre de Usuario ya existe en la Base de Datos.";
			$error_accion_ms[5]= "El NIP debe ser numerico.";
			$error_accion_ms[6]= "Error al Guardar la informaci&oacute;n del Usuario Actual.";
			$error_accion_ms[7]= "Error al Actualizar la informaci&oacute;n del Usuario Actual.";
			$error_accion_ms[8]= "Error al Actualizar el Password del Usuario Actual.";
		
			$error_cod = $numError;
			echo "<div align='center'><br><br>$error_accion_ms[$error_cod]<br><br></div>";		
		}
		
		function mensajes($numMensaje){
			//echo $numMensaje;
			$accion_ms[0]= "Registro Guardado Satisfactoriamente.";	
			$accion_ms[1]= "Datos Actualizados Satisfactoriamente.";
			$accion_ms[2]= "Password Actualizado..";
			$accion_ms[3]= "No se puede asociar el nip porque el usuario no se ha capturado un numero de nomina ";
		
			$msg_cod = $numMensaje;
			echo "<div align='center'>$accion_ms[$msg_cod]<br><br><a href='index.php'>Regresar al Men&uacute;</a></div>";					
		}
		
		
		public function nuevaFuncionForm(){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");

			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sql_fun="SELECT * FROM gruposmods";
			$result_fun=mysql_query($sql_fun,$var);			
?>

			<div style="padding:10px;">           
			<form method="get">			
			<div id="datosProceso" style="display:block; margin:5px;">
			<table width="495" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
				<tr>
					<td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">A&ntilde;adir Men&uacute;</td>
				</tr>
				<tr>
					<td width="156" class="bordesTitulos" style="height:25px;">Nombre del Men&uacute;</td>
					<td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtModulo" id="txtModulo" style="width:200px; font-size:14px;" /></td>
				</tr>
				<tr>
					<td width="156" class="bordesTitulos" style="height:25px;">Pertenece a</td>
					<td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtPer" id="txtPer" style="width:200px; font-size:14px;" readonly="readonly" value="Menu" /></td>
				</tr>
				<tr>
					<td width="156" class="bordesTitulos" style="height:25px;">No. Men&uacute;</td>
					<td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtMenu" id="txtMenu" style="width:200px; font-size:14px;" /></td>
				</tr>				
				<tr>
					<td colspan="2"><div id="listadoimagen" style=" display:none;height:250px; overflow:auto; border:1px solid #CCC;"></div></td>
				</tr>                     
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="guardaFuncion()" /><input type="reset"  value="Cancelar" class="elementosForm"  ></td>            
				</tr>  
			</table>
			</div></form>
<?php				  
		}//fin funcion nuevaFuncionForm

		public function guardarFuncion($txtModulo,$txtPer,$txtMenu){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			//echo "<br>".
			//$sqlGuarda="INSERT INTO gruposmods (modulo,pertenece_a,numeroMenu,ruta,rutaimg) values('".$txtModulo."','".$txtPer."','".$txtMenu."','".$txtRuta."','".$txtImagen."')";
			$sqlGuarda="INSERT INTO gruposmods (modulo,pertenece_a,numeroMenu) values('".$txtModulo."','".$txtPer."','".$txtMenu."')";
			$resultGuarda=mysql_query($sqlGuarda,$var);
			if($resultGuarda==true){
				echo "<script type='text/javascript'> alert('Registro Agregado'); mostrarOpcionesMenu(); </script>";				
			}else{
				echo "<script type='text/javascript' > alert('Error al Guardar.'); </script>";
				
			}
		}
		
		public function manttoSistema($sitio){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			$sql_sitio="select valor from configuracionglobal where nombreConf='".$sitio."'";
			$result_sitio=mysql_query($sql_sitio,$this->conexion);
			$fila_sitio=mysql_fetch_array($result_sitio);	
?>			
			<div style="margin:4px; background:#FFFFFF; overflow:auto;"><br />
				<form name="frmSitioGlobal" id="frmSitioGlobal">
					<input type="hidden" name="sitio" id="sitio" value="<?=$sitio;?>" />
					<table width="678" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
						<tr>
						  <td colspan="2" align="left" style="height:30px; background:#000000; color:#FFFFFF;">Configuraci&oacute;n del Sitio</td>
						</tr>
						<tr>
						      <td colspan="2" style="height:30px; border:1px solid #CCCCCC; background:#f0f0f0;"><strong>Valor actual: <?=$fila_sitio['valor'];?></strong></td>
						</tr>
						<tr>
						  <td width="201" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Sitio desactivado</td>
						  <td width="464"><input type="radio" value="No" name="rdbSitio" id="rdbSitio" />No <input type="radio" value="Si" name="rdbSitio" id="rdbSitio" />Si</td>
						</tr>
						<tr>
						  <td style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Mensaje de Sitio desactivado</td>
						  <td><textarea name="obsSitio" id="obsSitio" cols="40" rows="2"></textarea></td>
						</tr>
						<tr>
						      <td colspan="2"><hr style="background:#CCC;" /></td>
						</tr>
						<tr>
						      <td colspan="2" align="right"><input type="button" value="Guardar" onclick="guardaMantto()" /></td>
						</tr>
					</table>
				</form>
			</div>
<?php			
		}//fin de la funcion
public function guardarMantto($valor,$comentario,$sitio){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			$sql_sitioAct="UPDATE configuracionglobal set valor='".$valor."',descripcion='".$comentario."' WHERE nombreConf='".$sitio."'";
			$result_sitioAct=mysql_query($sql_sitioAct,$this->conexion);
			
			if(mysql_affected_rows() >= 1){
				echo "<script type='text/javascript'>alert('Informacion Actualizada Correctamente');</script>";
			}else{
				echo "<script type='text/javascript'>alert('No se Han Realizado Cambios');</script>";
			}
		}

	function controlCambios(){
?>
		<form name="frmSitioControlCambios" id="frmSitioControlCambios"><br>
			<table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size: 10px;">
				<tr>
					<td colspan="2" style="height:30px; background:#000000; color:#FFFFFF;">Control de Cambios Sistema<input type="hidden" name="fechaAct" id="fechaAct" value="<?=date("Y-m-d")." / ".date("H:i:s");?>" /></td>
				</tr>
				<tr>
					<td colspan="2" style="height:30px; border:1px solid #CCCCCC; background:#f0f0f0;">Descripci&oacute;n de las actualizaciones:</td>
				</tr>
				<tr>
				    <td style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Titulo de la Actualizaci&oacute;n</td>
				    <td><input type="text" name="txtTitulo" id="txtTitulo" style="width:125px;" /></td>
				</tr>
				<tr>
				    <td style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Status</td>
				    <td>
				    <select name="cboStatus" id="cboStatus" style="width:125px;">
					<option value="--" selected="selected">Selecciona</option>
					<option value="Nueva">Nueva</option>
					<option value="Terminada">Terminada</option>
				    </select>
				    </td>
				</tr>
				<tr>
				    <td style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Actualizaciones</td>
				    <td><textarea name="obsAct" id="obsAct" cols="60" rows="5"></textarea></td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background:#CCC;" /></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="button" value="Guardar" onclick="guardaActualizaciones()" /></td>
				</tr>
			</table>
		</form> 
<?		
	}
	
	function guardaControlCambios($titulo,$status,$obs,$fecha){
		//include("../includes/config.inc.php");
		//include("../includes/conectarbase.php");
		include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
		$sql_sitioAct="INSERT INTO cambiossistema (titulo,fecha,status,descripcion) values ('".$titulo."','".$fecha."','".$status."','".$obs."')";
		$result_sitioAct=mysql_query($sql_sitioAct,$var);
		
		if(mysql_affected_rows() >= 1){
			echo "<script type='text/javascript'>alert('Informacion Actualizada Correctamente');</script>";
		}else{
			echo "<script type='text/javascript'>alert('No se Han Realizado Cambios');</script>";
		}
	}
		
	public function consultaAct(){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlAct="SELECT * FROM cambiossistema ";
			$resultAct=mysql_query($sqlAct,$var);
			
			if(mysql_num_rows($resultAct)==0){
				echo " ( 0 ) Registros encontrados en la Base de Datos.";
			}else{
?>
			<table width="900" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
				<tr>
					<td width="100" align="center" style="height:30px; background:#000000; color:#FFFFFF;">ID</td>
					<td width="200" align="center" style="height:30px;background:#000000; color:#FFFFFF;">T&iacute;tulo</td>
					<td width="80" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Fecha/Hora</td>
					<td width="80" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Status</td>
					<td width="400" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Descripci&oacute;n</td>
				</tr>                  
<?			
				while($rowAct=mysql_fetch_array($resultAct)){
?>
				<tr>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowAct['id'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowAct['titulo'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowAct['fecha'];?></td>		
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><a href="#" onclick="cambioStatusAct('<?=$rowAct['id']?>','<?=$rowAct['status'];?>')" style=" font-size:12px; padding:5px;"><?=$rowAct['status'];?></a></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowAct['descripcion'];?></td>		
				</tr>
<?
					}
?>
			</table>
<?	
				}		
		}
		
		public function activarStatusAct($idReg,$status){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
					$sql="UPDATE cambiossistema SET status='".$status."' WHERE id='".$idReg."'";
					$result=mysql_query($sql,$var);
					if($result==true){
					?>
					<script type="text/javascript" >alert("Registro modificado"); consultaAct();</script>
					<?
					}else{
					?>
					<script type="text/javascript" > alert("Error al Guardar."); consultaAct();</script>
					<?
					}
		}
		
		public function nuevoProcesoForm(){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sql_proc="SELECT * FROM cat_procesos";
			$result_proc=mysql_query($sql_proc,$var);			
?>
        <div style="padding:10px;">
            <form method="get">
                <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Registro de nuevo Proceso</td>
                  </tr>                  
				</table>
                  <div id="datosProceso" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
                      <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Proceso</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtProceso" id="txtProceso" style="width:250px; font-size:14px;" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
					  <tr>
                        <td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="guardaProceso()" /><input type="reset" value="Cancelar" class="elementosForm"  ></td>            
                      </tr>  
                  </table>
		</div>
        </form>
<?php				  
		
		}
		
	public function consultaProcesos(){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlProceso="SELECT * FROM cat_procesos ";
			$resultProcesos=mysql_query($sqlProceso,$var);
			
			if(mysql_num_rows($resultProcesos)==0){
				echo " ( 0 ) Registros encontrados en la Base de Datos.";
			}else{
?>
			<table width="600" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
				<tr>
					<td width="100" align="center" style="height:30px; background:#000000; color:#FFFFFF;">ID</td>
					<td width="200" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Descripci&oacute;n</td>
					<td width="80" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Status</td>
					<td width="150" align="center" style="height:30px;background:#000000; color:#FFFFFF;">&nbsp;</td>
				</tr>                  
<?			
				while($rowProcesos=mysql_fetch_array($resultProcesos)){
?>
				<tr>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><a href="#" onclick="modProcesos('<?=$rowProcesos['id_proc'];?>')" title="Modificar registro: <?=$rowProcesos['id_proc'];?>"><?=$rowProcesos['id_proc'];?></a></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowProcesos['descripcion'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
<?	
						if($rowProcesos['status']==1){
							echo $statusProceso="Activo";
							$status="0";	
						}else{
							echo $statusProceso="Inactivo";
							$status="1";
						}					
?>
					</td>		
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
						<a href="#" onclick="cambioStatus('<?=$rowProcesos['id_proc']?>','<?=$status;?>')" style=" font-size:12px; padding:5px;">Cambiar status</a>
					</td>		
				</tr>
<?
					}
?>
			</table>
<?	
				}		
		}
		
	public function nuevoModeloForm(){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sql_modelo="SELECT * FROM cat_modradio";
			$result_modelo=mysql_query($sql_modelo,$var);			
?>
			<div style="padding:10px;">           
			<form method="get">
                <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Registro de nuevo Modelo de Radio</td>
                  </tr>                  
				</table>
                  <div id="datosProceso" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
                      <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Modelo</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtModelo" id="txtModelo" style="width:250px; font-size:14px;" /></td>
                      </tr>
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Observaci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtDes" id="txtObs" style="width:250px; font-size:14px;" /></td>
                      </tr>

                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
					  <tr>
                        <td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="guardaModelo()" /><input type="reset" value="Cancelar" class="elementosForm"  ></td>            
                      </tr>  
                  </table>
		</div>
        </form>
<?php				  
		}
		public function consultaModelo(){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlModelo="SELECT * FROM cat_modradio ";
			$resultModelo=mysql_query($sqlModelo,$var);
			
			if(mysql_num_rows($resultModelo)==0){
				echo " ( 0 ) Registros encontrados en la Base de Datos.";
			}else{
?>
			<table width="600" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
				<tr>
					<td width="100" align="center" style="height:30px; background:#000000; color:#FFFFFF;">ID</td>
					<td width="200" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Modelo</td>
					<td width="80" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Observaci&oacute;n</td>
				</tr>                  
<?			
				while($rowModelo=mysql_fetch_array($resultModelo)){
?>
				<tr>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><a href="#" onclick="modModelo('<?=$rowModelo['id_modelo'];?>')" title="Modificar registro: <?=$rowModelo['id_modelo'];?>"><?=$rowModelo['id_modelo'];?></a></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowModelo['modelo'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowModelo['observacion'];?></td>			
				</tr>
<?
					}
?>
			</table>
<?	
				}		
		}
		
		public function nuevaFallaForm(){
			include("../../includes/config.inc.php");
			include("../../includes/conectarbase.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sql_falla="SELECT * FROM cat_falla";
			$result_falla=mysql_query($sql_falla,$var);			
?>
			<div style="padding:10px;">           
			<form method="get">
                <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Registro de nueva Falla</td>
                  </tr>                  
				</table>
                  <div id="datosProceso" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Descripci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtDes" id="txtDes" style="width:250px; font-size:14px;" /></td>
                      </tr>
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Observaci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtObs" id="txtObs" style="width:250px; font-size:14px;" /></td>
                      </tr>
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">C&oacute;digo</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtCodigo" id="txtCodigo" style="width:250px; font-size:14px;" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
					  <tr>
                        <td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="guardaFalla()" /><input type="reset" value="Cancelar" class="elementosForm" ></td>            
                      </tr>  
                  </table>
                  
		</div>
        </form>
<?php				  
		}
		
	public function consultaFalla(){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlFalla="SELECT * FROM cat_falla ";
			$resultFalla=mysql_query($sqlFalla,$var);
			
			if(mysql_num_rows($resultFalla)==0){
				echo " ( 0 ) Registros encontrados en la Base de Datos.";
			}else{
?>
			<table width="600" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
				<tr>
					<td width="100" align="center" style="height:30px; background:#000000; color:#FFFFFF;">ID</td>
					<td width="200" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Descripci&oacute;n</td>
					<td width="80" align="center" style="height:30px;background:#000000; color:#FFFFFF;">Observaci&oacute;n</td>
					<td width="80" align="center" style="height:30px;background:#000000; color:#FFFFFF;">C&oacute;digo</td>
				</tr>                  
<?			
				while($rowFalla=mysql_fetch_array($resultFalla)){
?>
				<tr>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><a href="#" onclick="muestraMod('<?=$rowFalla['id_falla'];?>')" title="Modificar registro: <?=$rowFalla['id_falla'];?>"><?=$rowFalla['id_falla'];?></a></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowFalla['descripcion'];?></td>
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowFalla['observaciones'];?></td>		
					<td align="center" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><?=$rowFalla['codigo'];?></td>		
				</tr>
<?
					}
?>
			</table>
<?	
				}		
		}
	public function modProceso($id_proc){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlProc="SELECT * FROM cat_procesos WHERE id_proc='".$id_proc."' ";
			$resultProc=mysql_query($sqlProc,$var);
?>
			<form name="" id="">
			<input type="hidden" name="id_proc" id="id_proc" value="<?=$id_proc;?>" />
<?
		while($rowProc=mysql_fetch_array($resultProc)){
?>
			<table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Modificaci&oacute;n del Proceso</td>
                  </tr>                  
				</table>
                  <div id="datosProceso" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Descripci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtDesProc" id="txtDesProc" style="width:250px; font-size:14px;" value="<?=$rowProc['descripcion'];?>" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
					  <tr>
                        <td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="guardaProcMod()" /><input type="button" value="Cancelar" class="elementosForm" onClick="consulta()" ></td>            
                      </tr>  
                  </table>
		</div>
<?
		}
?>
		</form>
<?		
	  }	
	 public function modModelos($id_modelo){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlModelo="SELECT * FROM cat_modradio WHERE id_modelo='".$id_modelo."' ";
			$resultModelo=mysql_query($sqlModelo,$var);
?>
			<form name="" id="">
			<input type="hidden" name="id_modelo" id="id_modelo" value="<?=$id_modelo;?>" />
<?
		while($rowModelo=mysql_fetch_array($resultModelo)){
?>
			<table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Modificaci&oacute;n de Modelo</td>
                  </tr>                  
				</table>
                  <div id="datosProceso" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Modelo</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtMod" id="txtMod" style="width:250px; font-size:14px;" value="<?=$rowModelo['modelo'];?>" /></td>
                      </tr>
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Observaci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtObsMod" id="txtObsMod" style="width:250px; font-size:14px;" value="<?=$rowModelo['observacion'];?>" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
					  <tr>
                        <td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="modModeloGuarda()" /><input type="button" value="Cancelar" class="elementosForm" onClick="consultaModelo()" ></td>            
                      </tr>  
                  </table>
		</div>
<?
		}
?>
		</form>
<?		
	  }	
	 public function muestraMod($id_falla){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			$sqlFalla="SELECT * FROM cat_falla WHERE id_falla='".$id_falla."' ";
			$resultFalla=mysql_query($sqlFalla,$var);
?>
			<form name="" id="">
			<input type="hidden" name="id_falla" id="id_falla" value="<?=$id_falla;?>" />
<?
		while($rowFalla=mysql_fetch_array($resultFalla)){
?>
			<table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px;">
                  <tr>
                    <td colspan="2" style="height:25px; margin-top:5px; background:#000; color:#FFF;">Modificaci&oacute;n de Falla</td>
                  </tr>                  
				</table>
                  <div id="datosProceso" style="display:block; margin:5px;">
                  <table width="600" border="0" cellspacing="1" cellpadding="1" align="center" style="font-size:12px; border:1px solid #666;">
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Descripci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtDes1" id="txtDes1" style="width:250px; font-size:14px;" value="<?=$rowFalla['descripcion'];?>" /></td>
                      </tr>
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">Observaci&oacute;n</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtObs1" id="txtObs1" style="width:250px; font-size:14px;" value="<?=$rowFalla['observaciones'];?>" /></td>
                      </tr>
					  <tr>
                        <td width="156" class="bordesTitulos" style="height:25px;">C&oacute;digo</td>
                        <td width="350" class="bordesContenido" style="height:25px;"><input type="text" name="txtCodigo1" id="txtCodigo1" style="width:250px; font-size:14px;" value="<?=$rowFalla['codigo'];?>"/></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
					  <tr>
                        <td colspan="2" style="height:25px;" align="right">&nbsp;<input type="button" value="Guardar Informaci&oacute;n" onclick="guardaFallaMod()" /><input type="button" value="Cancelar" class="elementosForm" onClick="consultafalla()" ></td>            
                      </tr>  
                  </table>
                  
		</div>
<?
		}
?>
			</form>
<?		
	  }
	 public function guardarRegistro($txtProceso){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			//echo "<br>".
			$sqlGuardaGrupo="INSERT INTO cat_procesos (descripcion) values('".$txtProceso."')";
			$resultGuardaGrupo=mysql_query($sqlGuardaGrupo,$var);
			if($resultGuardaGrupo==true){
				//echo "<br>Registro Agregado.<br>";
				?>
					<script type="text/javascript" >alert("Registro Agregado"); consulta();</script>
				<?
			}else{
				//echo "<br>Error al Guardar.<br>";
				?>
					<script type="text/javascript" > alert("Error al Guardar."); nuevoProceso();</script>
				<?
			}
		}
		public function guardarRegistroModelo($txtModelo,$txtObs){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			//echo "<br>".
			$sqlGuarda="INSERT INTO cat_modradio (modelo,observacion) values('".$txtModelo."','".$txtObs."')";
			$resultGuarda=mysql_query($sqlGuarda,$var);
			$id = mysql_insert_id(); 
			echo "$id";
			if($resultGuarda==true){
				echo "<br>Registro Agregado.<br>";
			$sqlModelo="INSERT INTO reg_inventario (id_modelo) values('".$id."')";
			$resultModelo=mysql_query($sqlModelo,$var);
			
			}else{
				echo "<br>Error al Guardar.<br>";
			}
		}
		
	public function guardarRegistroFalla($txtDes,$txtObs,$txtCodigo){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
			//echo "<br>".
			$sqlGuarda="INSERT INTO cat_falla (descripcion,observaciones,codigo) values('".$txtDes."','".$txtObs."','".$txtCodigo."')";
			$resultGuarda=mysql_query($sqlGuarda,$var);
			
			if($resultGuarda==true){
				//echo "<br>Registro Agregado.<br>";
				?>
					<script type="text/javascript" >alert("Registro Agregado"); consultafalla();</script>
				<?
			}else{
				//echo "<br>Error al Guardar.<br>";
				?>
					<script type="text/javascript" > alert("Error al Guardar."); consultafalla();</script>
					<?
			}
		}
	
 public function modificaUsuario($id_usr){
		include("../../includes/config.inc.php");
		include("../../includes/conectarbase.php");
		echo $sql_datosUsuario="select * from $tabla_usuarios where ID='".$id_usr."'";
		$result_datosUsuario=mysql_query($sql_datosUsuario,$this->conexion);
		$fila_datosUsuario=mysql_fetch_array($result_datosUsuario);
		//grupos
		$sql_grupos="SELECT * FROM grupos WHERE activo=1";
		$result_grupos=mysql_query($sql_grupos,$this->conexion);
		$result_grupos2=mysql_query($sql_grupos,$this->conexion);
		/////////////
		$sqlGrupo="SELECT nombre FROM grupos where id='".$fila_datosUsuario['grupo']."'"; 
		$resultGrupo=mysql_query($sqlGrupo,$this->conexion);
		$grupo1=mysql_fetch_array($resultGrupo);
		$sqlGrupo1="SELECT nombre FROM grupos where id='".$fila_datosUsuario['grupo2']."'"; 
		$resultGrupo1=mysql_query($sqlGrupo1,$this->conexion);
		$grupo2=mysql_fetch_array($resultGrupo1);
?>

		<div id="desv">
        <div id="msgModificaUsuarios">
        	<div style="height:20px; color:#FFFFFF; background:#000000; font-size:12px;">Listado de Usuarios</div>
            <div style="margin:4px; background:#FFFFFF; overflow:auto; height:370px;"><br />
        <form name="" id="" method="post" action="">
        	<input type="hidden" name="idUsuarioAct" id="idUsuarioAct" value="<?=$id_usr;?>" />
        <table width="700" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
          <tr>
            <td colspan="2" style=" background:#000000; padding:5px;"><a href="javascript:resetPass('<?=$id_usr;?>','<?=$fila_datosUsuario['usuario'];?>')" style="color:#FFFFFF; font-size:14px;">Reset Password</a></td>
          </tr>
          <tr>
            <td width="136" align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Nombre</td>
            <td width="551" align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><input type="text" name="txtNombreUsuario" id="txtNombreUsuario" style="width:200px;" value="<?=$fila_datosUsuario['nombre'];?>" /></td>
          </tr>
          <tr>
            <td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Apellido</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><input type="text" name="txtApellidoUsuario" id="txtApellidoUsuario" style="width:200px;" value="<?=$fila_datosUsuario['apaterno'];?>" /></td>
          </tr>
          <tr>
            <td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Usuario</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><input type="text" name="txtUserName" id="txtUserName" style="width:200px;" value="<?=$fila_datosUsuario['usuario'];?>" /></td>
          </tr>
           <tr>
            <td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">No. de Nomina</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><input type="text" name="txtNomina" id="txtNomina" style="width:200px;" value="<?=$fila_datosUsuario['nomina'];?>" /></td>
          </tr>
          <tr>
            <td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Nivel Acceso</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><select name="lstNivelAcceso" id="lstNivelAcceso" style="width:200px;">
              <option value="<?=$fila_datosUsuario['nivel_acceso'];?>" selected="selected"><?=$fila_datosUsuario['nivel_acceso'];?></option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
            </select>            </td>
          </tr>
          <tr>
            <td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Sexo</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;"><select name="lstSexo" id="lstSexo" style="width:200px;">
              <option value="<?=$fila_datosUsuario['sexo'];?>" selected="selected"><?=$fila_datosUsuario['sexo'];?></option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>            </td>
          </tr>
          <tr>
          	<td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Grupo 1</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
            <select name="cboGrupoUsuario" id="cboGrupoUsuario" style="width:200px;">
            	<option value="<?=$fila_datosUsuario['grupo'];?>" selected="selected"><?=$grupo1['nombre'];?></option>
<?
			while($row_grupos=mysql_fetch_array($result_grupos)){
				if(substr($row_grupos['nombre'],0,5)=="Depto"){
?>
				<option value="<?=$row_grupos['id'];?>"><?=substr($row_grupos['nombre'],6);?></option>
<?										
				}			
			}
?>
            </select>
            </td>
          </tr>
          
           <tr>
          	<td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Grupo 2</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
            <select name="cboGrupoUsuario2" id="cboGrupoUsuario2" style="width:200px;">
            	<option value="<?=$fila_datosUsuario['grupo2'];?>" selected="selected"><?=$grupo2['nombre'];?></option>
<?
			while($row_grupos2=mysql_fetch_array($result_grupos2)){
				if(substr($row_grupos2['nombre'],0,5)!="Depto"){
?>
				<option value="<?=$row_grupos2['id'];?>"><?=$row_grupos2['nombre'];?></option>
<?										
				}				
			}
?>
            </select>
            </td>
          </tr>
         
          <tr>
          	<td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Activo</td>
            <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">
            <select name="cboActivoUsuario" id="cboActivoUsuario" style="width:200px;">
            	<option value="<?=$fila_datosUsuario['activo'];?>" selected="selected"><?=$fila_datosUsuario['activo'];?></option>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="right" style="height:25px;">
            	<input type="button" name="button" id="button" value="Cancelar" onclick="cerrarDivModifica()" />
            	<input type="button" name="button2" id="button2" value="Actualizar" onclick="actualizaDatosUsuario()" />
            </td>
          </tr>                                                                      
        </table>
		</form><br />
        	</div>
        </div>
        </div>
<?		
	}	
	
public function guardarModProc($txtDes,$id_proc){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
					$sql="UPDATE cat_procesos SET descripcion='".$txtDes."' WHERE id_proc='".$id_proc."'";
					$result=mysql_query($sql,$var);
					if($result==true){
					?>
					<script type="text/javascript" >alert("Registro modificado"); consulta();</script>
					<?
					}else{
					?>
					<script type="text/javascript" > alert("Error al Guardar."); consulta();</script>
					<?
					}
		}
		
		
	public function modGuardaModelo($txtMod,$txtObs,$id_modelo){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
					$sql="UPDATE cat_modradio SET modelo='".$txtMod."', observacion='".$txtObs."' WHERE id_modelo='".$id_modelo."'";
					$result=mysql_query($sql,$var);
					if($result==true){
					?>
					<script type="text/javascript" >alert("Registro modificado"); consultaModelo();</script>
					<?
					}else{
					?>
					<script type="text/javascript" > alert("Error al Guardar."); consultaModelo();</script>
					<?
					}
		}
		
	  public function guardarRegMod($txtDes,$txtObs,$txtCodigo,$id_falla){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
					$sql="UPDATE cat_falla SET descripcion='".$txtDes."', observaciones='".$txtObs."', codigo='".$txtCodigo."' WHERE id_falla='".$id_falla."'";
					$result=mysql_query($sql,$var);
					if($result==true){
					?>
					<script type="text/javascript" >alert("Registro modificado"); consultafalla();</script>
					<?
					}else{
					?>
					<script type="text/javascript" > alert("Error al Guardar."); consultafalla();</script>
					<?
					}
		}
	
	
	
	public function generaNip($id_usr){
	
		include("../../includes/config.inc.php");
		include("../../includes/conectarbase.php");
		$id_usr=$_GET['id_usr'];
		$nvo_nip=$_POST['nip'];
		if ($nvo_nip==""){
			$this->maneja_error(1);
		}else if (!is_numeric($nvo_nip)){
			$this->maneja_error(5);
		}else{
			
			$sqlnomina="select nomina from $tabla_usuarios  where ID='".$id_usr."'"; 
			$resultnomina=mysql_query($sqlnomina,$this->conexion);
			$filanomina=mysql_fetch_array($resultnomina);
			$nomina=$filanomina["nomina"];
			if ($nomina!="0"){
			
				$nip=$nomina.$nvo_nip;
				//echo "<br>";
				$nen=md5($nip);
				// ....... Se revisa si existe ya el usuario en la tabla 'userautoriza', sino se inserta ......
				$sql_yaexiste="select id_usuario from userautoriza where id_usuario='".$id_usr."'";
				$result_existe=mysql_db_query($db,$sql_yaexiste);
				$ndr_yaexiste=mysql_num_rows($result_existe);
				if ($ndr_yaexiste>0){
					mysql_query("UPDATE userautoriza SET nip='$nen' WHERE id_usuario='$id_usr' ") or die(mysql_error());
					mysql_close ();			
				}else{
					mysql_query("INSERT INTO userautoriza (id,id_usuario,nip) values (null,'$id_usr','$nen') ") or die(mysql_error());
					mysql_close ();		
				}
				$this->mensajes(0);
			}else{
				$this->mensajes(3);
				
			}
		}
	}
	

	public function nip($id_usr){
		include("../../includes/config.inc.php");
		include("../../includes/conectarbase.php");
	    $sql_usuario="select usuario from $tabla_usuarios where ID='".$id_usr."'";
		$result_usuario=mysql_query($sql_usuario,$this->conexion);
		$fila_usuario=mysql_fetch_array($result_usuario);
?>

		<div id="msgNipUsuario">
        <div style="margin:5px; font-size:12px; background:#FFF; height:190px; text-align:center;">
        <form name="" id="" method="post" action="controladorUsuarios.php?action=generaNip&id_usr=<?=$id_usr?>"><br /><br /><br />
        <table width="348" border="0" cellpadding="1" cellspacing="1" align="center" style="font-size:12px;">
        	<tr>
            	<td colspan="2" style="height:25px; background:#000; color:#FFF; font-size:14px;">Confidencial</td>
            </tr>
        	<tr>
            	<td align="left" width="112" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">Usuario</td>
                <td align="left" width="223" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<?=$fila_usuario['usuario'];?></td>
            </tr>
        	<tr>
            	<td align="left" style="height:25px; border:1px solid #CCCCCC; background:#f0f0f0;">NIP</td>
                <td align="left" style="height:25px; border-bottom:1px solid #CCCCCC; border-right:1px solid #CCCCCC;">&nbsp;<input type="password" name="nip" id="nip" size="4" maxlength="4" /></td>
            </tr>
            <tr>
            	<td colspan="2" align="right"><input type="button" value="Cancelar" onclick="cerrarDivNip()" /><input type="submit" value="Actualizar" /></td>
            </tr>                                
        </table><br />
        </form></div></div>
<?	
	}
	
	public function activarStatus($idReg,$status){
			include("../../includes/config.inc.php");
			$conn=new Conexion();
			$var=$conn->getConexion($host,$usuario,$pass,$db);
					$sql="UPDATE cat_procesos SET status='".$status."' WHERE id_proc='".$idReg."'";
					$result=mysql_query($sql,$var);
					if($result==true){
					?>
					<script type="text/javascript" >alert("Registro modificado"); consulta();</script>
					<?
					}else{
					?>
					<script type="text/javascript" > alert("Error al Guardar."); consulta();</script>
					<?
					}
		}


	}//final de clase
//$objModeloUsuarios=new modeloUsuarios($host,$usuario,$pass,$db);
?>	