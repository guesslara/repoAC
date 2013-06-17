<?php
    /*
        La clase sirve para mostrar la interfaz solicitando nombre de usuario
        y password y hace una verificacion en la Base de Datos y regresa el
        resultado de dicha verificacion
        
        Los Estilos necesarios para esta clase ya se encuentran cargados en el
        CSS de la aplicacion
        
        Autor: Gerardo Lara
    */
    
    class verificaUsuario{
        
        public function muestraFormularioUsuario(){//metodo para mostrar la interfaz en modo flotante
            $form="<div id='ventanaDialogo' class='ventanaDialogo'>";
            $form.="<div id='barraTitulo1VentanaDialogo'>IQe Sisco Informaci&oacute;n...<div id='btnCerrarVentanaDialogo'><a href='#' onclick=\"accionesVentana('ventanaDialogo','0')\" title='Cerrar Ventana Dialogo'><img src='../img/close.gif' border='0' /></a></div></div>";
            $form.="<div id='msgVentanaDialogo'></div>";
            $form.="<br><table border='0' width='98%' cellpading='1' cellspacing='1'><tr><td align='right'>Usuario:</td><td align='center'><input type='text' name='txtUsuarioMod' id='txtUsuarioMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td align='right'>Password:</td><td align='center'><input type='password' name='txtPassMod' id='txtPassMod' /></td></tr><tr><td colspan='2'>&nbsp;</td></tr><tr><td colspan='2' align='center'><input type='button' value='<< Continuar >>' onclick='verificaUsuario()'></td></tr></table>";
            $form.="</div>";
            echo $form;
        }
    }//fin de la clase
    
    $objVerifica=new verificaUsuario();
    $objVerifica->muestraFormularioUsuario();
?>