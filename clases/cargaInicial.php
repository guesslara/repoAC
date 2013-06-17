<?php
    /*
     *La clase verifica si el usuario ha personalizado su password en el sistema y si este esta trabajando con el password por default
     *Fecha 10 Noviembre 2011
     *Autor: Gerardo Lara
     *---------------------------------------------------------
     *Modificacion para encontrar el nombre del proyecto que se selecciona
     *Fecha 7 Noviembre 2012
     *Autor:Gerardo Lara
    */
    class verificaCargaInicial{
        
        public function verificaPassword($variable){            
            if($variable==0){
		$this->msgCambiarPassword();
            }
	}//fin funcion
        
	public function dameNombreProyecto($idProyecto){	    
	    $sql="select * from proyecto where id_proyecto='".$idProyecto."'";
	    $res=mysql_query($sql,$this->conectarBdVerificaCargaInicial());
	    $row=mysql_fetch_array($res);
	    return $row["nombre_proyecto"];
	}
	
	public function verificaNuevasActualizaciones(){
	    $sql="";
	}
	
        public function msgCambiarPassword(){
            echo "<style type='text/css'>#transparenciaGeneral{background: url(../../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
                  #barraTitulo1{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
                  #msgCambiarPassword{border:1px solid #000;background:#fff;height:400px;width:800px;position:absolute;left:50%;top:50%;margin-left:-400px;margin-top:-200px;z-index:3;}
                  </style>";
            echo "<script> function cierraAdvertencia(){ $(\"#transparenciaGeneral\").hide(); $(\"#msgMantenimiento\").hide(); } </script>";      
	    echo "<div id='transparenciaGeneral'>
			<div id='msgCambiarPassword'><div id='barraTitulo1'>Informaci&oacute;n...</div>
				<div style='margin:15px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;'>
				<p>Bienvenid@:</p>
				<p><hr style='background:#CCC;' /></p>
				<p>Antes de comenzar...</p><br />
				<p align='justify'>Se ha verificado que el password de su cuenta aun no se ha personalizado, para poder realizar esta tarea, solo de un clic en su nombre de usuario situado en la parte inferior,
				mostrar&aacute; una pantalla con el Perfil de usuario as&iacute; como una opci&oacute;n para poder cambiar el mismo.
				</p><br />
				<p>Si tiene preguntas sobre esta actualizaci&oacute;n notifiquela al &aacute;rea de Sistemas.</p>
				<p>&nbsp;</p>
				<p align='center'><input type='button' value='Aceptar' style='height:45px;' onclick='cierraAdvertencia()' /></p>
				</div>
			</div>
	    </div>";
	}
	
	private function conectarBdVerificaCargaInicial(){
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
?>    