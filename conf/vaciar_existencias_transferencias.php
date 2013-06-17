<?php
include("../conf/conectarbase.php");
$sql="SELECT id FROM catprod";
$res=mysql_db_query($sql_db,$sql);
while($reg=mysql_fetch_array($res)){
	echo "<br>";	print_r($reg);

	$sql2="SELECT * FROM tipoalmacen";
	$res2=mysql_db_query($sql_db,$sql2);
	while($reg2=mysql_fetch_array($res2)){
		$ce="exist_".$reg2["id_almacen"];
		$ct="trans_".$reg2["id_almacen"];
		$sql_a="UPDATE catprod SET $ce=0, $ct=0 WHERE id=".$reg["id"];
		echo "<br>$sql_a";	//print_r($reg);
		mysql_db_query($sql_db,$sql_a);
	}
	echo "<hr>";
}
?>