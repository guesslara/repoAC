<?php
	include("modeloPerfil.php");
	include("../../clases/about.php");
	$objPerfil=new modeloPerfil();
	
	switch($_GET['action']){
		case "verPerfilUsuario":
			$idUsuario=$_GET['idUsuario'];
			$objPerfil->verPerfil($idUsuario);
		break;
		case "cambiarPass":
			$idUsuario=$_GET['idUsuario'];
			$objPerfil->cambiarPass($idUsuario);
		break;
		case "cambiarImagen":
			$idUsuario=$_GET['idUsuario'];
			$objPerfil->cambiarImagen($idUsuario);
		break;		
	}
	
	switch($_POST['action']){
		case "actualizarPass":
			$idUsuario=$_POST['idUsuario'];
			$pass=$_POST['pass'];
			$pass1=$_POST['pass1'];
			$passAnt=$_POST['passAnt'];
			$objPerfil->actualizaPass($idUsuario,$pass,$pass1,$passAnt); 
		break;
		case "acercaDe":
			$objAbout=new aboutCompras();
			$objAbout->about();
		break;
	}
?>