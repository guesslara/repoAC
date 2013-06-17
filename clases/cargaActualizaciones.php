<?php
    /*
     *La clase verifica si existen actualizaciones nuevas en el sistema y las muestra en pantalla a modo de ventana modal
     *Fecha 10 Noviembre 2011
     *Autor: Gerardo Lara
     *    
    */
    class verificaActualizaciones{
        
        private function conectarBd(){
	    require("../includes/config.inc.php");
	    $link=mysql_connect($host,$usuario,$pass);
	    if($link==false){
		echo "Error en la conexion a la base de datos";
	    }else{
		mysql_select_db($db);
		return $link;
	    }				
	}
	
	public function verificaActualizacionesSistema(){            
            $sql="SELECT * FROM cambiossistema WHERE status='Nueva'";
	    $res=mysql_query($sql,$this->conectarBd());
	    if(mysql_num_rows($res)!=0){		
		$texto="";
		while($row=mysql_fetch_array($res)){
		    $texto=$texto."<p style='border-bottom:1px solid #CCC;'>&nbsp;&raquo;&nbsp;".$row["descripcion"]."</p><br>";
		}
		$this->msgActualizacionesDisponibles($texto);
	    }
	}//fin funcion
        
        public function msgActualizacionesDisponibles($mensaje){
            echo "<style type='text/css'>#transparenciaGeneralActualizaciones{background: url(../../../img/desv.png) repeat;position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index:20;}
                  #barraTitulo1{ height:20px; padding:5px; color:#FFF; font-size:12px; background:#000;}
                  #msgActualizaciones{border:1px solid #000;background:#f0f0f0;height:450px;width:800px;position:absolute;left:50%;top:50%;margin-left:-400px;margin-top:-225px;z-index:3;}
                  </style>";
            echo "<script> function cierraActualizaciones(){ $(\"#transparenciaGeneralActualizaciones\").hide(); $(\"#msgActualizaciones\").hide(); } </script>";      
	    echo "<div id='transparenciaGeneralActualizaciones'>
			<div id='msgActualizaciones'><div id='barraTitulo1'>Informaci&oacute;n...</div>
				<div style='margin:15px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;background:#F0F0F0;'>
				<p style='font-weight:bold;font-size:12px;'>Bienvenid@:</p>
				<p><hr style='background:#CCC;' /></p>
				<p style='font-weight:bold;font-size:12px;'>Actualizaciones nuevas...</p><br />
				<div style='height:250px;border:1px solid #CCC;background:#FFF; width:98%;overflow:auto;'>
				<p align='justify' style='margin:4px;'>".$mensaje."</p>
				</div>				
				<p>Si tiene preguntas sobre esta actualizaci&oacute;n notifiquela al &aacute;rea de Sistemas.</p>
				<p>&nbsp;</p>
				<p align='center'><input type='button' value='Aceptar' style='height:45px;' onclick='cierraActualizaciones()' /></p>
				</div>
			</div>
	    </div>";
	}
    }//fin de la clase    
?>