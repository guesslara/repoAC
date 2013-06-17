<?php
	include("verificaUsuario.php");
	$objVUsuario=new verificaUsuario();
	$objVUsuario->cargaArchivosClase();    
	$objVUsuario->muestraFormularioUsuario();
?>
<input type="text" name="txtUsuario" id="txtUsuario">