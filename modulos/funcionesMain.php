<?php
	if($_GET['action']=="verificaMantto"){
		verificaMantto();
	}
	if($_GET['action']=="mostrarFormBug"){
		mostrarFormBug();
	}
	if($_POST['action']=="guardarFormBug"){
		$mensaje=$_POST["mensaje"];
		guardarInfoBug($mensaje);
	}
	if($_POST["action"]=="verificaActNuevas"){
		verificaActNuevas();	
	}
	
	if($_POST["action"]=="verPerfil"){
		echo "<script type='text/javascript'> contenedorVentana.location.href='mod_profile/index.php'; </script>";
	}
	
	
	function guardarInfoBug($mensaje){		
		$sqlBug="INSERT INTO errores (fecha,hora,des) values ('".date("Y-m-d")."','".date("H:i:s")."','".$mensaje."')";
		$resBug=mysql_query($sqlBug,conectarBd());
		if($resBug){
			echo "<script type='text/javascript'> alert('Informacion enviada Satisfactoriamente.'); cerrarFormbug(); </script>";
			//se incluye la clase para el envio de correo electronico
			include("../clases/class.smtp.inc");
			include("../includes/txtApp.php");
			$origen_nombre='Desarrollo';
			$origen_mail="soporte@iqelectronics.com.mx";
			$password_mail="123456";
			$subject="Nuevo Bug Enviado";
			$fecha = date ("d F Y");
			
			$params['host'] = 'iqelectronics.com.mx';	// Cambiar por su nombre de dominio
			$params['port'] = 9025;			// The smtp server port
			$params['helo'] = 'iqelectronics.com.mx';	// Cambiar por su nombre de dominio
			$params['auth'] = TRUE;			// Whether to use basic authentication or not
			$params['user'] = $origen_mail;	// Correo que utilizara para enviar los correos (no usar el de webmaster por seguridad)
			$params['pass'] = $password_mail;	// Password de la cta de correo. Necesaria para la autenticacion
			//$destino="glara@iqelectronics.net;uvelez@iqelectronics.net;sistemas@iqelectronics.com.mx;drjuarez@iqelectronics.com.mx;hgmontoya@iqelectronics.com.mx";//$_POST['emailprueba'];consejomexicanodeendodoncia@yahoo.com.mx
			$destino="glara@iqelectronics.net";//$_POST['emailprueba'];consejomexicanodeendodoncia@yahoo.com.mx
			$message.='Mensaje Enviado el '.date("d/m/y")." a las ".date("H:i")."<br><br>";
			$message.='Se ha reportado un BUG en el sistema: '.$txtApp['session']['origenSistemaUsuarioNombre'].'<br><br>';
			$message.="Descripci&oacute;n<br><br>";
			$message.='Mensaje: '.$mensaje."<br><br>";
			$message.="Correo generado Autom&aacute;ticamente<br><br>";
			$send_params['recipients'] = array("glara@iqelectronics.net"); // The recipients (can be multiple), separados por coma.
			$send_params['headers']	   = array(
							'Content-Type: text/html;',
							'From: "'.$origen_nombre.'" <soporte@iqelectronics.com.mx>',	// Headers
							//'To: '.$destino,
							'To: '.$destino,
							'Subject: '.$subject,
							//'Disposition-Notification-To: contacto@odontologos.com.mx',
							//'Disposition-Notification-To: '.$origen_mail,
							//'Return-Receipt-To: '.$origen_mail,		
							'Date: '.date(DATE_RFC822),
							'X-Mailer: PHP/' . phpversion(),
							'MIME-Version: 1.0',
							//'Reply-To: '.$origen_mail'\r\n',
							'Return-Path: '.$origen_nombre.'" <sistema Interno TVO>',
							'Envelope-To:'.$destino 
							);
	
			$send_params['from']		= $origen_mail;	// This is used as in the MAIL FROM: cmd
																							
			$send_params['body']		= $message;	//Message							// The body of the email
	
			if(is_object($smtp = smtp::connect($params)) AND $smtp->send($send_params)){
				echo "";//
				// Any recipients that failed (relaying denied for example) will be logged in the errors variable.
				//print_r($smtp->errors);
			}else {
				//echo " - NO se envio";
				//echo "<script type='text/javascript'> alert('".$smtp->errors."'); </script>";
			}
			//fin de la inclusion del nuevo codigo de envio de mail
		}else{
			echo "Ha ocurrido un error al enviar los datos.";
		}
	}
	
	function mostrarFormBug(){
?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">                  
                  <tr>
			<td style="font-size:14px;background:#CCC;color:#000;height:25px;padding:5px;">Sisco - Feedback</td>
		  </tr>
		  <tr>
			<td style="padding:5px;">Sisco - Feedback permite enviar sugerencias o informes de problemas que ocurran en la aplicaci&oacute;n </td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td style="padding:5px;">Escriba una breve descripci&oacute;n</td>
                  </tr>
                  <tr>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td><textarea name="txtDes" id="txtDes" cols="45" rows="5" style="width:97%;"></textarea></td>
                  </tr>
                  <tr>
			<td align="right">
				<input type="button" value="Cerrar" onclick="cerrarFormbug()" style="font-size:12px;width:130px;height:25px;border:1px solid #CCC;background:#f0f0f0;color: #000;">
				<input type="button" name="button" id="button" value="Enviar Informacion" onClick="enviarInfo()" style="font-size:12px;width:130px;height:25px;border:1px solid #CCC;background:#f0f0f0;color: #000;">
			</td>
                  </tr>
                </table>
<?
	}
	
	function verificaActNuevas(){
		$sqlActNuevas="SELECT COUNT(*) AS totalActualizaciones FROM cambiossistema WHERE status='Nueva'";
		$resActNuevas=mysql_query($sqlActNuevas,conectarBd());
		$rowActNuevas=mysql_fetch_array($resActNuevas);		
		echo $rowActNuevas["totalActualizaciones"];
	}
	
	function verificaMantto(){
		include("../includes/conectarbase.php");
		$sqlSitio="SELECT valor,descripcion FROM configuracionglobal WHERE nombreConf='sitio_desactivado'";
		$resSitio=mysql_query($sqlSitio,conectarBd());
		$filaSitio=mysql_fetch_array($resSitio);
		$sitioActivo[0]=$filaSitio['valor'];
		$sitioActivo[1]=$filaSitio['descripcion'];
		if($sitioActivo[0]=="Si"){
?>
		<div id="desv">
			<div id="msgManttoProg">            
				<div style="border: 0px solid #000;margin:10px; padding:30px; font-size:14px;height: 118px;">
					<div style="border: 0px solid #000;width: 65px;height: 90px;padding: 5px;float: left;"><img src="../img/Alert1.png" border="0"></div>
					<div style="border: 0px solid #000;width: 75%;height: 40px;padding: 30px;float: left;"><?=$sitioActivo[1];?></div>
				</div>
			</div>
		</div>
<?		
		}		
	}
	
	function conectarBd(){
		require("../includes/config.inc.php");
		$link=mysql_connect($host,$usuario,$pass);
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db($db);
			return $link;
		}				
	}
?>