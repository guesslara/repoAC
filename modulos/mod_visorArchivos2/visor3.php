<?php
    /**
     *@name		Pantalla principal del explorador
     *@fecha		Mayo 2014
     *@version		2.0.0
     *@author		Gerardo Lara <gerardolara1984@gmail.com>
     */
    
    //se incluye el archivo de configuracion
    include "config.php";
    $rutaExplorar=$config["explorador"]["path"];//se define la ruta a explorar dentro del sitio
    include "cabecera.html";
?>
<script type="text/javascript">
    abrirDirectorio('<?=$rutaExplorar;?>','browserArchivos');
</script>
<?
    include "pie.html";
?>