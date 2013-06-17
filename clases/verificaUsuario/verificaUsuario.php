<?php
    /*
        La clase sirve para mostrar la interfaz solicitando nombre de usuario
        y password y hace una verificacion en la Base de Datos y regresa el
        resultado de dicha verificacion
        
        Los Estilos necesarios para esta clase ya se encuentran cargados en el
        CSS de la aplicacion
        
        Autor: Gerardo Lara
    */
    //include("../conexion/conexion.php");
    class verificaUsuario{
		public $verificado="Definicion";
		public $usuarioModifica="N/A";
		public $passModifica="N/A";		
        
		public function resultadoVerificacion(){
			return $this->$verificado;
		}
		
        public function verificaUsuarioSistema($usuarioV,$passV){//metodo para la verificaion del usuario en la Base de datos			
			$sqlVer="Select * from userdbnextel where usuario='".$usuarioV."' and pass='".md5($passV)."'";
			$resVer=mysql_query($sqlVer,$this->conectarBaseVerifica());
			$rowVer=mysql_fetch_array($resVer);
			if(mysql_num_rows($resVer)==0){
				echo "Informaci&oacute;n Incorrecta";
				$this->$verificado="error";
			}else{
				echo "<script type='text/javascript'>document.getElementById('txtUsuario').value='".$usuarioV."'; </script>";
				echo "<script type='text/javascript'> $(\"#ventanaDialogo\").hide(); </script>";
				echo "<script type='text/javascript'> $(\"#transparenciaGeneral\").hide(); </script>";
				//se manda la informacion a una caja de texto en el formulario para su uso
				echo "<script type='text/javascript'> document.getElementById('txtNombreModifico').value='".$rowVer["nombre"]." ".$rowVer["apaterno"]."'; </script>";
				$this->$verificado="verificado";
			}			
		}
		
		public function muestraFormularioUsuario(){//metodo para mostrar la interfaz en modo flotante            
            $form="<div id='transparenciaGeneral'>";
			$form.="<div id='ventanaDialogo' class='ventanaDialogo'>";
            $form.="<div id='barraTitulo1VentanaDialogo'>IQe Sisco Verificaci&oacute;n...<div id='btnCerrarVentanaDialogo'><a href='#' onclick=\"accionesVentana('ventanaDialogo','0')\" title='Cerrar Ventana Dialogo'><img src='../../img/close.gif' border='0' /></a></div></div>";
            $form.="<div id='msgVentanaDialogo'></div>";
            $form.="<br><form name='frmVerificaUsuario' id='frmVerificaUsuario' action='' method='post'><table border='0' width='98%' cellpading='1' cellspacing='1'><tr><td align='right'><span style='color:#000;'>Usuario:</span></td><td align='center'><input type='text' name='txtUsuarioMod' id='txtUsuarioMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td align='right'><span style='color:#000;'>Password:</span></td><td align='center'><input type='password' name='txtPassMod' id='txtPassMod' /></td></tr><tr><td colspan='2'>&nbsp;<div id='verificacionUsuario' class='div'>&nbsp;</div></td></tr><tr><td colspan='2' align='center'><input type='button' value='<< Continuar >>' onclick='verificaUsuario()'></td></tr></table></form>";
            $form.="</div></div>";
            $form.="<script>$(\"#txtUsuarioMod\").focus();</script>";
            echo $form;
        }
        
        public function cargaArchivosClase(){
			echo "<style type='text/css'>
			#transparenciaGeneral{background:url(../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
			#barraTitulo1VentanaDialogo{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000; font-family:Verdana, Geneva, sans-serif;}
			#btnCerrarVentanaDialogo{ float:right;}
			.ventanaDialogo{
				border:1px solid #000;
				background-color:#FFF;
				height:200px;
				width:300px;
				position:absolute;
				left:50%;
				top:50%;
				margin-left:-150px;
				margin-top:-100px;        
				/*z-index:1;*/
				/*sombra*/
				-webkit-box-shadow:10px 10px 5px #CCC;
				-moz-box-shadow:10px 10px 5px #CCC;
				filter: shadow(color=#CCC, direction=135,strength=2);
			}
			.div{ font-size:12px; color:#F00; font-weight:bold; text-align:center; font-family:Verdana, Geneva, sans-serif;}
			</style>";
			echo "<script type='text/javascript'>
			function ajaxVerificaUsuario(divDestino,url,parametros,metodo){	
				$.ajax({
				async:true,
				type: metodo,
				dataType: 'html',
				contentType: 'application/x-www-form-urlencoded',
				url:url,
				data:parametros,
				beforeSend:function(){ 						
					$('#'+divDestino).show().html('Verificando...'); 
				},
				success:function(datos){ 
					$('#'+divDestino).show().html(datos);		
				},
				timeout:90000000,
				error:function() { $('#'+divDestino).show().html('<center>Error: Por favor intente mas tarde. </center>'); }
				});
			}
			function accionesVentana(div,opc){
				if(opc=='0'){
					$('#'+div).hide();
					$('#transparenciaGeneral').hide();
				}
			}
			function verificaUsuario(){	
				var usuarioMod=document.getElementById('txtUsuarioMod').value;
				var passmod=document.getElementById('txtPassMod').value;
				if((usuarioMod=='') || (usuarioMod==null) || (passmod=='')){
					alert('Escriba su nombre de usuario y password para poder continuar');
				}else{					
					//ajaxVerificaUsuario('verificacionUsuario','../../clases/verificaUsuario/accionesVerificaUsuario.php','action=verificaUsuario&usuarioV='+usuarioMod+'&passMod='+passmod,'POST');
				}
			}
			</script>";
			
            //echo "<script type='text/javascript' src='verificaUsuario.js'><script>";
            //echo "<link rel='stylesheet' type='text/css' href='verificaUsuario.css'>";
            //se carga el archivo jquery para poder mostrar u ocultar las ventanas
            //echo "<script type='text/javascript' src='../jquery-1.3.2.min.js'><script>";
        }
		
		private function conectarBaseVerifica(){
			require("../../includes/config.inc.php");
			$link=mysql_connect($host,$usuario,$pass);
			if($link==false){
				echo "Error en la conexion a la base de datos";
			}else{
				mysql_select_db($db);
				return $link;
			}				
		}
    }//fin de la clase
    
    //$objVerifica=new verificaUsuario();
    //$objVerifica->cargaArchivosClase();
    //$objVerifica->muestraFormularioUsuario();
?>