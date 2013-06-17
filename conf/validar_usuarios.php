<?php 
if (!$_SESSION) { echo "<h3 align='center'>Error: La sesion ha caducado.<br><br><a href='index.php' style='text-decoration:none;'>ir a login &rarr; </a></h3>"; exit; } 
if (!$_SESSION['sistema']=="inventarios_iq") { header("Location: ../index.php"); exit; }
//print_r($_SESSION);		
function validar_usuarios()
{
    
	$num_args = func_num_args();
    for ($i=0;$i<$num_args;$i++) {
    	$arg=func_get_arg($i);
		$validado=false;
		if ($_SESSION['usuario_nivel']==$arg){
			$validado=true;
			break;
		}
	}	
	if (!$validado)
	{
		echo "<center><br><br><img src='../img/stop.png'><br>Acceso no autorizado para el usuario: ".$_SESSION['nombre']."<br>Para mayores informes consulte a su Administrador del Sistema.<br><br><a href='javascript:history.back();'>Volver</a></center>";
		exit();	
	}
}
?>
