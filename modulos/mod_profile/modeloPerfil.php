<?php
	include("../../clases/clase_mysql.php");

	class modeloPerfil{
		
		public function cambiarImagen($idUsuario){
			$path="../../../images/ui/img_profiles/";
			$directorio=dir($path);
			
			
?>
			<div style="margin:10px;">
				<div style="font-size:16px; color:#06F; margin:10px 10px 20px 10px;">Cambiar Imagen de Perfil</div>
				<hr color="#CCCCCC" /><br />
				<div style="font-size:16px; color:#06F; margin:10px 10px 20px 10px;">Imagenes disponibles:</div>
				<div style="width:450px; height:200px; overflow:auto; border:1px solid #CCCCCC;">
<?
				while ($archivo = $directorio->read()){
		    		if(($archivo==".") || ($archivo=="..")){					
					}else{
?>
						<div class="contenedorMarcador" title="<?=$archivo;?>"><img src="<?=$path.$archivo;?>" border="0" /><div class="fuenteTituloMarcador"><?=substr($archivo,0,8);?></div></div>
<?					
					}
				}
				$directorio->close();
?>
				</div>
				<div style="font-size:16px; color:#06F; margin:10px 10px 20px 10px;">Subir imagen:</div>
			</div>
<?		
		}
		
		public function actualizaPass($idUsuario,$passNuevo,$passNuevo1,$passAnt){
			include("../../includes/config.inc.php");
			$mysql=new DB_mysql;
			$mysql->conectar($db,$host,$usuario,$pass);						
			$sqlUsuario="SELECT * FROM ".$tabla_usuarios." WHERE ID='".$idUsuario."'";
			$mysql->consulta($sqlUsuario);
			$resUsuario=$mysql->registroUnico();
			$passNuevo=md5($passNuevo);
			$passNuevo1=md5($passNuevo1);
			$passAnt=md5($passAnt);
			if($passAnt != $resUsuario['pass']){
				echo "<script type='text/javascript'> alert('Error verifique la informacion introducida');</script>";
				exit;
			}else if($passNuevo != $passNuevo1){
				echo "<script type='text/javascript'> alert('Error verifique la informacion introducida');</script>";
				exit;
			}else{
				$sqlActualiza="UPDATE ".$tabla_usuarios." set pass='".$passNuevo."',cambiarPass='1' WHERE ID='".$idUsuario."'";
				$mysql->consulta($sqlActualiza);
				$filasAfectadas=$mysql->regsAfectados();
				if($filasAfectadas >= 1){
					echo "<script type='text/javascript'> alert('Cambios realizados satisfactoriamente');</script>";
				}else{
					echo "<script type='text/javascript'> alert('Error al realizar los cambios en el Usuario');</script>";
				}
			}
		}
		
		public function cambiarPass($idUsuario){
			include("../../includes/config.inc.php");
			$mysql=new DB_mysql;
			$mysql->conectar($db,$host,$usuario,$pass);						
			$sqlPerfil="SELECT pass FROM ".$tabla_usuarios." WHERE ID='".$idReg."'";	
			$mysql->consulta($sqlPerfil);
			$resPerfil=$mysql->registroUnico();
?>
			<div style="margin:10px;">
				<div style="font-size:16px; color:#06F; margin:10px 10px 20px 10px;">Cambiar Password ...</div>
				<hr color="#CCCCCC" /><br /><input type="hidden" name="txtIdUsuario" id="txtIdUsuario" value="<?=$idUsuario;?>" />
				<table width="511" border="0" cellpadding="1" cellspacing="1" style="font-size:10px;">
					<tr>
						<td width="186" style="font-size:10px; text-align:right;">Password anterior:</td>
						<td width="312">&nbsp;<input type="password" name="txtPassAnterior" id="txtPassAnterior" style="font-size:12px;" /></td>
					</tr>
					<tr>
						<td style="font-size:10px; text-align:right;">Nuevo Password:</td>
						<td>&nbsp;<input type="password" name="txtPass" id="txtPass" style="font-size:12px;" /></td>
					</tr>
					<tr>
						<td style="font-size:10px; text-align:right;">Reescriba su Password:</td>
						<td>&nbsp;<input type="password" name="txtPass1" id="txtPass1" style="font-size:12px;" /></td>
					</tr>
					<tr>
						<td colspan="2"><hr color="#CCCCCC" /></td>						
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="button" value="Actualizar" onclick="actualizaPass()" /></td>
					</tr>
			  </table>				
			</div>
<?			
		}
		
		public function verPerfil($idReg){	
			include("../../includes/config.inc.php");
			$mysql=new DB_mysql;
			$mysql->conectar($db,$host,$usuario,$pass);						
			$sqlPerfil="SELECT * FROM ".$tabla_usuarios." WHERE ID='".$idReg."'";	
			$mysql->consulta($sqlPerfil);
			$resPerfil=$mysql->registroUnico();
			if($resPerfil['activo']=="1"){
				$activo="Activo";
			}else{
				$activo="Inactivo";
			}			
			($resPerfil['sexo']=="M") ? $sexo="Masculino" : $sexo="Femenino";
				
			$sqlGrupo="SELECT * FROM grupos";
			$mysql->consulta($sqlGrupo);
			$resGrupo=$mysql->registrosConsulta();
			$i=0;
			while($rowGrupo=mysql_fetch_array($resGrupo)){
				$nombreGrupo[$i]=$rowGrupo['nombre'];
				$i+=1;
			}
?>			
			<div style="margin:10px;">
				<div style="font-size:16px; color:#06F; margin:10px 10px 20px 10px; font-weight:bold;"><?=$resPerfil['nombre']." ".$resPerfil['apaterno'];?></div>
				<hr color="#CCCCCC" /><br />
				<fieldset class="estiloFieldet" style="width: 450px;background: #f0f0f0;border: 1px solid #CCC;"><legend class="tituloDatosPerfil">Personal</legend>
				<!--<div class="tituloDetallePerfil">Nombre / Apellidos</div>
					<div class="detalleTextoPerfil"><?=$resPerfil['nombre']." ".$resPerfil['apaterno'];?></div>-->
					<div class="tituloDetallePerfil">No. Empleado</div>
					<div class="detalleTextoPerfil"><?=$resPerfil['nomina'];?></div>
					<div class="tituloDetallePerfil">Sexo</div>
					<div class="detalleTextoPerfil"><?=$sexo;?></div>	
				</fieldset>
				<fieldset class="estiloFieldet" style="width: 450px;background: #f0f0f0;border: 1px solid #CCC;"><legend class="tituloDatosPerfil">Sistema</legend>
					<div class="tituloDetallePerfil">Usuario en el Sistema</div>
					<div class="detalleTextoPerfil"><?=$resPerfil['usuario'];?></div>
					<div class="tituloDetallePerfil">Grupo 1</div>
					<div class="detalleTextoPerfil"><?=$nombreGrupo[$resPerfil['grupo']-1];?></div>
					<div class="tituloDetallePerfil">Grupo 2</div>
					<div class="detalleTextoPerfil"><?=$nombreGrupo[$resPerfil['grupo2']-1];?></div>
				</fieldset>
				<fieldset class="estiloFieldet" style="width: 450px;background: #f0f0f0;border: 1px solid #CCC;"><legend class="tituloDatosPerfil">Status</legend>
					<div class="tituloDetallePerfil">Status</div>
					<div class="detalleTextoPerfil"><?=$activo;?></div>
					<div class="tituloDetallePerfil">Observaciones</div>
					<div class="detalleTextoPerfil"><?=$resPerfil['obs'];?></div>
				</fieldset>							
			</div>
<?		
		}

	}//fin de la clase
?>