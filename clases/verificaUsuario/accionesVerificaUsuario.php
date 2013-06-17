<?
/*
pasarela de datos para la verificacion del usuario
*/	
	include("verificaUsuario.php");
	$objVerificaUsuario=new verificaUsuario();
	switch($_POST["action"]){
		case "verificaUsuario":
			//print_r($_POST);
			$usuarioV=$_POST["usuarioV"];
			$passV=$_POST["passMod"];
			session_start();
			$_SESSION["verificaUsuario"]=$usuarioV;
			$_SESSION["verificaPassV"]=$passV;
			//$objVerificaUsuario->verificaUsuarioSistema($usuarioV,$passV);	
		break;
	}
?>