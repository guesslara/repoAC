<?php
    /*
     *Clase para las funciones de la interfaz principal de usuario
     *Fecha: 12 de Noviembre de 2012
     *Autor: Gerardo Lara
    */
    class funcionesInterfazPrincipal{
        
        public function dameNombreProyecto($idProyecto){	    
	    $sql="select * from proyecto where id_proyecto='".$idProyecto."'";
	    $res=mysql_query($sql,$this->conectarBdGUI());
	    $row=mysql_fetch_array($res);
	    return $row["nombre_proyecto"];
	}
        
        public function buscaActualizacionesNuevas(){
            $sqlActNuevas="SELECT COUNT(*) AS totalActualizaciones FROM cambiossistema WHERE status='Nueva'";
            $resActNuevas=mysql_query($sqlActNuevas,$this->conectarBdGUI());
            $rowActNuevas=mysql_fetch_array($resActNuevas);
            return $rowActNuevas["totalActualizaciones"];
        }
        
        private function conectarBdGUI(){
	    require("../includes/config.inc.php");
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