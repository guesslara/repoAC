<?php
class catalogo{
	var $prefijo="cat_";
	var $exepciones_tablas=array("userdbnextel");
	var $exepciones_campos=array("");
	
	public function __destruct(){
		//echo "<br>Objeto destruido.";
	}	
	
	public function estilosCatalogos(){
		echo "<style type='text/css'>
		.tabla1{ margin-left:20px; border:#cccccc 1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; margin-top:10px; margin-bottom:10px; background-color:#FFFFFF; }
		.tabla1 td{ height:20px; border-right:#cccccc 1px solid; padding:5px; }
		.tabla_campos{ text-align:center; font-weight:bold; background-color:#cccccc; color:#333; height:20px; /*border-bottom:#333333 2px solid;*/ }
		.tabla_zebra0{ background-color:#ffffff; }
		.tabla_zebra1{ background-color:#efefef; /*font-weight:bold;*/ }
		</style>";
	}
	
	public function menu_opciones(){
		$this->estilosCatalogos();
		$largo_prefijo=strlen($this->prefijo);
		$matriz_tablas=array(); 
		//MOSTRAMOS TODAS LAS TABLAS  
		$Sql ="SHOW TABLES";  
		if ($result = mysql_query($Sql,$this->conectarBd())){
			while($Rs = mysql_fetch_array($result)) {  
				//echo "<br>";	print_r($Rs);
				if (substr($Rs[0],0,$largo_prefijo)==$this->prefijo){
				 	// Agrego la tabla al arreglo.
					array_push($matriz_tablas,$Rs[0]);
				}
			}
		}else{
			echo "<br>Error SQL [".mysql_error($link)."].";
			exit;
		}
		/*$filas=5;
		$cols=4;
		echo "<table border='1'>";
		for($i=0;$i<$filas;$i++){
			echo "<tr>";
			for($j=0;$j<$cols;$j++){
				echo "<td>&nbsp;</td>";
			}
			echo "</tr>";
		}
		echo "</table>";*/		
?>
		<div>
			<table width="98%" cellpadding="1" cellspacing="1" border="1">
				<tr>
					<td width="30%;" valign="top">
						<!--tabla con los catalogos-->
						<table class="tabla1" cellspacing="0" cellpadding="3">
							<tr>
								<td colspan="3" class="tabla_campos">Cat&aacute;logos</td>
							</tr>
<?php 
						$clase_css="tabla_zebra0";
						foreach($matriz_tablas As $t){ $ta=str_replace($this->prefijo,"",$t);
?>
							<tr class="<?=$clase_css?>">
								<td width="243">&nbsp;<?=strtoupper($ta);?></td>
								<td width="20">
<?php
							if (!in_array($t,$this->exepciones_tablas)){
?>
								<a href="#" onclick="ajax('accionesCatalogos','ac=cdm_catalogox_listar&c=<?=$t?>');" title="Listar"><img src="img/listar.png" border="0" width="17" height="17"></a>
<?php 							}else{
								echo "&nbsp;";
							}
?>
								</td>
								<td width="21">
<?php
							if (!in_array($t,$this->exepciones_tablas)){ ?>
								<a href="#" onclick="ajax('accionesCatalogos','ac=cdm_catalogox_agregar&c=<?=$t?>');" title="Agregar registro"><img src="img/agregar.png" border="0" width="17" height="17"></a>
<?php
							}else{
								echo "&nbsp;";
							}
?>
								</td>
							</tr>
<?php 
						($clase_css=="tabla_zebra0")? $clase_css="tabla_zebra1" : $clase_css="tabla_zebra0";
						}
?>
						</table>						
						<!--fin de los catalogos-->
					</td>
					<td width="78%" valign="top"><div id="accionesCatalogos"></div></td>
				</tr>
			</table>			
		</div>
<?php
	}
	public function listar($p){
		echo "<br>p=[$p]<br>";
		$this->listar_tablas();
	}
	private function listar_tablas(){		
		$matriz_tablas=array(); 
		 //MOSTRAMOS TODAS LAS TABLAS  
		 echo "<br>".$Sql ="SHOW TABLES";  
		 if ($result = mysql_query($Sql,$this->conectarBd())){
			 while($Rs = mysql_fetch_array($result)) {  
				 echo "<br>";	print_r($Rs);
				 if (substr($Rs[0],0,9)==$prefijo){
				 	// Agrego la tabla al arreglo.
					array_push($matriz_tablas,$Rs[0]);
				 
					 // PARA CADA TABLA DESCRIBIMOS LOS CAMPOS  
					 $Sql2 ="DESCRIBE ".$Rs[0];  
					 $result2 = mysql_db_query($db_actual,$Sql2,$link) or die("No se puede ejecutar la consulta: ".mysql_error());  
					 echo '<table width="100%" class="listado_tablas">';  
					 echo '<tr><th colspan="2">'.$Rs[0].'</th></tr>';  
					 //MOSTRAMOS LA INFORMACION DE LOS CAMPOS  
					 while($Rs2 = mysql_fetch_array($result2)) {
						//echo "<br>";	print_r($Rs2);  
						 echo '<tr>';  
						 echo '<td width="55%">'.$Rs2['Field'].'</td>';  
						 echo '<td width="25%">'.$Rs2['Type'].'</td>';  
						 //echo '<td width="10%">'.$Rs2['Null'].'</td>';  
						 //echo '<td width="10%">'.$Rs2['Key'].'</td>';  
						 echo '</tr>';  
					 }  
					 echo '</table>'; 
				}					  
			 }
		} else {
			echo "<br>Error SQL [".mysql_error($link)."].";
			exit;			
		}
		//$matriz_tablas	   
		echo "<br>M Tablas=";	print_r($matriz_tablas); 
	}
	
	public function catalogo_listar($c){
		include ("../../includes/conexion.php");
		//echo "<br>p=[$c]";
		$ta=str_replace($this->prefijo,"",$c);
		$sql_orden="";
		
		$Sql2 ="DESCRIBE ".$c;  
		$result2 = mysql_db_query($db_actual,$Sql2,$link) or die("No se puede ejecutar la consulta: ".mysql_error());  
		echo '<br><div class="subtitulos2">CATALOGO "'.strtoupper(str_replace($this->prefijo,"",$c)).'"</div><table align="left" class="tabla1" cellspacing="0"><tr class="tabla_campos">';
		while($Rs2 = mysql_fetch_array($result2)) {
			
			//echo "<br><br>";	print_r($Rs2);
			echo "<td>".$Rs2["Field"];
			if ($Rs2['Key']=='PRI'){ $sql_orden=" ORDER BY ".$Rs2['Field']; }
			//print_r($Rs2);
			echo "</td>";
		}
		echo "</tr>";
		//echo "<BR>".
		$sql3="SELECT * FROM $c $sql_orden";
		if ($res3=mysql_db_query($db_actual,$sql3,$link)){
			 $ndr3=mysql_num_rows($res3);
			 if ($ndr3>0){
				 //echo "<br>OK el registro se lee ($ndr3 resultados)";
				 $clase_css="tabla_zebra0";
				 while($reg3=mysql_fetch_array($res3)){
					$ndc_respuesta=count($reg3)/2;
					
					if ($ndc_respuesta>0){
						echo "<tr class='$clase_css'>";
						for ($i=0;$i<$ndc_respuesta;$i++){
							//echo "<br><br>[$ndc_respuesta] ";	print_r($reg3);
							echo "<td>".$reg3[$i]."</td>";
						}
						echo "</tr>";
						($clase_css=="tabla_zebra0")? $clase_css="tabla_zebra1" : $clase_css="tabla_zebra0";	
					}
				 }
				 
			 } 		
		} else {
			echo "<br>Error SQL [".mysql_error($link)."].";
			exit;			
		}		
		//echo "<tr></tr>";
		echo '</table>';			
	}
	
	
	
		
	public function catalogos_agregar($c){
		include ("../conf/conexion.php");
		//echo "<br>p=[$c]";
		$ta=str_replace($this->prefijo,"",$c);
		$sql_orden="";
		
		//echo "<br><br>".
		$Sql2 ="DESCRIBE ".$c;  
		$result2 = mysql_db_query($db_actual,$Sql2,$link) or die("No se puede ejecutar la consulta: ".mysql_error());  
		?>
		<script language="javascript">
			function validar_catalogo_formulario(catalogo){
				var campos=new Array();
				var valores=new Array();
				
				var sql_valores="";
				var cadena_valores="ac=cdm_catalogo_insertar&tabla="+catalogo;
				
				for (var i=0;i<$("form input").length;i++){
					campos.push($("form input")[i].id);
					valores.push($("form input")[i].value);
				}
				
				for (var i2=0;i2<campos.length;i2++){
					//alert(campos[i2]+" ("+$("#"+campos[i2]).attr("class")+") : "+valores[i2]);
					if ($("#"+campos[i2]).attr("class")=="campo_obligatorio"&&(valores[i2]==""||valores[i2]==undefined||valores[i2]==null)){
						alert("Error: El campo ("+campos[i2]+") es obligatorio."); return;
					}
					if (sql_valores==""){
						sql_valores=campos[i2]+"|||"+valores[i2];
					} else {
						sql_valores+="@@@"+campos[i2]+"|||"+valores[i2];
					}	
				}
				
				//alert(cadena_valores+"\n"+sql_campos+"\n"+sql_valores);
				if (confirm("¿Desea guardar el registro?")){
					ajax('catalogo_insertar_resultado',cadena_valores+'&campo_valor='+sql_valores);
				}
			}
		</script>
		<div class="subtitulos2"><?=strtoupper(str_replace($this->prefijo,"",$c));?></div>
		<form name="frm_catalogo_nuevo_<?=$c?>" id="frm_catalogo_nuevo_<?=$c?>">
		<table align="center" class="tabla1" cellspacing="0">
		<tr><td colspan="2" class="tabla_campos">Insertar nuevo registro</td></tr>
		<?php
		$clase_css="tabla_zebra0";
		while($Rs2 = mysql_fetch_array($result2)) {
			//echo "<br><br>";	print_r($Rs2);
			$field=$Rs2["Field"];
			$type=$Rs2["Type"];
			$null=$Rs2["Null"];
			$key=$Rs2["Key"];
			$default=$Rs2["Default"];
			$extra=$Rs2["Extra"];
			//echo " Come=".$comentario=$Rs2["Comment"];
			$texto="";
			$sol="";
			if (!$default==""){ $texto=$default; $sol=" readonly='1' "; } 
			if ($extra=="auto_increment"){ $texto="NULL"; $sol=" readonly='1' "; } 
			if ($null=="NO"){ $clase_obligaria="campo_obligatorio"; } else { $clase_obligaria="campo_opcional"; }
			//$sol="";
			?>
			<tr class="campo_vertical,<?=$clase_css?>">
				<td><?=$Rs2["Field"]?></td>
				<td>&nbsp;<label><input type="text" name="txt_<?=$Rs2["Field"]?>" id="txt_<?=$Rs2["Field"]?>" value="<?=$texto?>" <?=$sol?> class="<?=$clase_obligaria?>" /></label></td>
			</tr>
			<?php
			($clase_css=="tabla_zebra0")? $clase_css="tabla_zebra1" : $clase_css="tabla_zebra0";
		}
		?></table>
		<div class="subtitulos2"><a href="javascript:validar_catalogo_formulario('<?=$c?>');" class="boton">&nbsp;&nbsp;Guardar&nbsp;&nbsp;</a></div>
		</form>
		<div id="catalogo_insertar_resultado"></div>
		<?php			
	}	
		
	public function catalogo_insertar($t,$cv){
		//echo "<hr><br>t ($t) cv ($cv)";
		$sql_campos="";
		$sql_valores="";
		$separar_campos=explode("@@@",trim($cv));
		//echo "<br>";	print_r($separar_campos);
		foreach ($separar_campos as $cam){
			$separar_campos2=explode("|||",trim($cam));
			$campoX=str_replace("txt_","",trim($separar_campos2[0]));
			$valorX=trim($separar_campos2[1]);
			($sql_campos=="")? $sql_campos=$campoX : $sql_campos.=",".$campoX;
			($sql_valores=="")? $sql_valores=$valorX : $sql_valores.=",'".$valorX."'";
		}
		//echo "<br>CAMPOS=($sql_campos) VALORES ($sql_valores)";
		include ("../conf/conexion.php");
		//echo "<br>".
		$sql_insertar="INSERT INTO $t($sql_campos) VALUES ($sql_valores);";
		if (mysql_db_query($db_actual,$sql_insertar,$link)){
			echo "<br><b>&nbsp;Registro Insertado Correctamente.</b>";
		} else {
			echo "<br>&nbsp;Error SQL (".mysql_error($link).")<br><br><b>&nbsp;El Registro NO se Inserto.</b>";
		}
	}
	
	public function conectarBd(){
		require("../../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
			//echo "conectado";
		}				
	}
}


	//se instancia la clase
	$cat=new catalogo();
	//$cat->listar("cat_modradio");
	$cat->menu_opciones();
?>