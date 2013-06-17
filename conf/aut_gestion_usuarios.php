<?
session_start();
$color=="#D9FFB3";

include("../conf/conectarbase.php"); // Conexion a la BD
require("aut_verifica.inc.php"); // incluir motor de autentificación.
$nivel_acceso=2; // definir nivel de acceso para esta página.
if (($nivel_acceso < $_SESSION['usuario_nivel']) or ($nivel_acceso == $_SESSION['usuario_nivel']) ){
	header ("Location: $redir?error_login=0");
	exit;
}

require ("aut_config.inc.php"); // incluir configuracion.
$pag=$_SERVER['PHP_SELF'];  // el nombre y ruta de esta misma página.

function cabeceraHTML(){ ?>
<html>
<head>
<title>Usuarios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
 .tabla3{ border:#333333 2px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;}
 .campos3{background-color:#333333; color:#FFFFFF; font-weight:bold; text-align:center;}
 .td1{ padding:1px; /*border-top:#333333 1px solid;*/ border-right:#cccccc 1px solid;}
 .td2{ padding:1px; /*border-top:#333333 1px solid;*/ } 
 .cn{ background-color:#CCCCCC; font-weight:bold;}
 a:link {text-decoration:none;}
 a:hover {text-decoration:none;}


 .style6 {font-size: 12px; color: '#FFFFFF'; font-family: Geneva, Arial, Helvetica, sans-serif;}
 .botones {  color: #FFFFFF; background-color: #333333; font-weight:bold; border-color: #000000 ; border-top-width: 1pix; border-right-width: 1pix; border-bottom-width: 1pix; border-left-width: 1pix}
 .imputbox {  font-size: 10pt; color: #000099; background-color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; border: 1pix #000000 solid; border-color: #000000 solid; font-weight: normal}
 A:VISITED  { font-weight: normal; color: #0000CC; TEXT-DECORATION:none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt}
 A:LINK     { font-weight: normal; color: #0000CC; TEXT-DECORATION:none; font-family: Verdana, Arial, Helvetica, sans-serif; border-color: #33FF33 #66FF66; clip:  rect(   ); font-size: 10pt}
 A:ACTIVE   { font-weight: normal; color: #FF3333; TEXT-DECORATION:none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt}
 A:HOVER    { font-weight: normal; color: #0000CC; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: normal; text-decoration: underline; font-size: 10pt}
-->
</style>
</head>
<body bgcolor="#FFFFFF">
<?php
}


if (isset($_GET['error'])){

$error_accion_ms[0]= "No se puede borrar el Usuario, debe existir por lo menos uno.<br>Si desea borrarlo, primero cree uno nuevo.";
$error_accion_ms[1]= "Faltan Datos.";
$error_accion_ms[2]= "Passwords no coinciden.";
$error_accion_ms[3]= "El Nivel de Acceso ha de ser numérico.";
$error_accion_ms[4]= "El Usuario ya está registrado.";
$error_accion_ms[5]= "Nivel de usuario no permitido.";

$error_cod = $_GET['error'];
echo "<br><br><br /><br /><br /><div align='center'>$error_accion_ms[$error_cod]</div><br>";

}

//$db_conexion= mysql_connect("$sql_host", "$sql_usuario", "$sql_pass") or die("No se pudo conectar a la Base de datos") or die(mysql_error());

mysql_select_db("$sql_db") or die(mysql_error());

if (!isset($_GET['accion'])){

$usuario_consulta = mysql_query("SELECT ID,usuario,nivel_acceso FROM $sql_tabla WHERE nivel_acceso>0 ORDER BY usuario") or die("No se pudo realizar la consulta a la Base de datos");

cabeceraHTML();
?>
<br /><br /><table width="500" cellspacing="0" cellpadding="0"  align="center" class="tabla3">
  <tr>
    <td colspan="4" height="20" class="campos3">Usuarios del Sistema</td>
  </tr>
  <tr  height="20" style="background-color:#cccccc; color:#000000; font-weight:bold; text-align:center;">
    <td width="10%">ID</td>
    <td width="48%">Usuario</td>
    <td width="10%">Nivel</td>
    <td width="32%">
		<a href="<?=$pag;?>?accion=nuevo">Registrar usuario</a>
	</td>
  </tr>
<?php
while($resultados = mysql_fetch_array($usuario_consulta)) {
?>
<tr bgcolor="<?=$color?>" onMouseOver="this.style.background='#cccccc';" onMouseOut="this.style.background='<? echo $color; ?>'" style="cursor:pointer;">
    <td width="10%" height="20" align="center" class="td1"><?=$resultados[ID]-1?></font></div></td>
    <td width="48%" class="td1">&nbsp;<?=$resultados[usuario]?></font></div></td>
    <td width="10%" class="td1" align="center"><?=$resultados[nivel_acceso]?></font></div></td>
    <td width="32%" align="center" class="td2"> 
      <!--<a href="<?=$pag?>?accion=borrar&id=<?=$resultados[ID]?>">Borrar</a> | 
	  <a href="<?=$pag?>?accion=nivel&id=<?=$resultados[ID]?>">Nivel acceso</a> 
	  //-->
	   Borrar | Modificar  </td>
  </tr>
<?php
($color=="#D9FFB3")? $color="#ffffff" : $color="#D9FFB3";
}?>
</table>
<?php
mysql_free_result($usuario_consulta);
mysql_close();
}

if (isset($_GET['id'])){

if ($_GET['accion']=="borrar"){
$usuarios_consulta = mysql_query("SELECT ID FROM $sql_tabla") or die(mysql_error());
$total_registros = mysql_num_rows ($usuarios_consulta);
mysql_free_result($usuarios_consulta);

if ($total_registros == 1){
header ("Location: $pag?error=0");
exit;
}

$id_borrar= $_GET['id'];
mysql_query("DELETE FROM $sql_tabla WHERE id=$id_borrar") or die(mysql_error());
mysql_close();

header ("Location: $pag");
exit;

}

if ($_GET['accion']=="nivel"){

cabeceraHTML();

$id_mod_nivel= $_GET['id'];
$usuario_consulta = mysql_query("SELECT ID,usuario,nivel_acceso FROM $sql_tabla WHERE id=$id_mod_nivel") or die("No se pudo realizar la consulta a la Base de datos");

while($resultados = mysql_fetch_array($usuario_consulta)) {

?>
<form method="post" action="<?=$pag?>?accion=editarnivel">
<input type="hidden" name="id" value="<?=$resultados[ID]?>">
<br /><br /><table width="399"  class="tabla3" cellspacing="0" cellpadding="4" align="center">
    <tr>
      <td colspan="2" height="20" class="campos3">Modificar Nivel Acceso Usuario :</td>
    </tr>
    <tr>
      <td width="185" class="cn">Usuario : </td>
      <td width="192"><?=$resultados[usuario]?></td>
    </tr>
    <tr>
      <td width="185" class="cn">Nivel Acceso actual : </td>
      <td width="192"><?=$resultados[nivel_acceso]?></td>
    </tr>
    <tr>
      <td width="185" class="cn">Nuevo Nivel de Acceso :</td>
      <td width="192">
        <input type="text" name="nuevonivelacceso" class="imputbox" size="4" maxlength="4">
      </td>
    </tr>
    <tr>
      <td colspan="2" height="40" align="center">
          <input type="submit" name="Submit" value="  Actualizar  " class="botones" >
      </td>
    </tr>
  </table>
</form>
<?php
}
mysql_free_result($usuario_consulta);
mysql_close();
}

}

if ($_GET['accion']=="editarnivel"){

$id=$_POST['id'];
$nivelnuevo=$_POST['nuevonivelacceso'];

if ($nivelnuevo==""){
header ("Location: $pag?accion=nivel&id=$id&error=1");
exit;
}

if ($_SESSION['usuario_nivel']!=='0'&&$nivelnuevo=='0'){
	header ("Location: $pag?accion=nuevo&error=5");
	exit;
}

mysql_query("UPDATE $sql_tabla SET nivel_acceso='$nivelnuevo' WHERE ID=$id") or die(mysql_error());
mysql_close ();
header ("Location: $pag");
exit;
}



if ($_GET['accion']=="nuevo"){

cabeceraHTML();
?>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>?accion=hacernuevo">

  <br /><br /><table width="350" class="tabla3" cellspacing="0" cellpadding="4" align="center">
    <tr>
      <td colspan="2"  height="20" class="campos3">
.: Registro de Usuarios :.
      </td>
    </tr>
    <tr>
      <td width="158" class="cn"> Usuario : </td>
      <td width="170">
        <input type="text" name="usuarionombre" class="imputbox" maxlength="15">
      </td>
    </tr>
    <tr>
      <td width="158" class="cn">Password: </td>
      <td width="170">
        <input type="password" name="password1" class="imputbox" maxlength="15">
      </td>
    </tr>
    <tr>
      <td width="158" class="cn">Password (repitalo) : </td>
      <td width="170">
        <input type="password" name="password2" class="imputbox" maxlength="15">
      </td>
    </tr>
    <tr>
      <td width="158" class="cn">Nivel de Acceso :</td>
      <td width="170">
        <input type="text" name="nivelacceso" class="imputbox" size="4" maxlength="4">
      </td>
    </tr>
    <tr>
      <td colspan="2" height="40" align="center">
          <input type="submit" name="Submit" value="  Registrar  " class="botones" >
      </td>
    </tr>
  </table>
</form>
<?php
}

if ($_GET['accion']=="hacernuevo"){

$usuario=$_POST['usuarionombre'];
$pass1=$_POST['password1'];
$pass2=$_POST['password2'];
$nivel=$_POST['nivelacceso'];

if ($_SESSION['usuario_nivel']!=='0'&&$nivel=='0'){
	header ("Location: $pag?accion=nuevo&error=5");
	exit;
} else {

if ($pass1=="" or $pass2=="" or $usuario=="" or $nivel=="") {
header ("Location: $pag?accion=nuevo&error=1");
exit;
}

if ($pass1 != $pass2){
header ("Location: $pag?accion=nuevo&error=2");
exit;
}

if (!eregi("[0-9]",$nivel)){
header ("Location: $pag?accion=nuevo&error=3");
exit;
}
} // $nivel ....
$usuarios_consulta = mysql_query("SELECT ID FROM $sql_tabla WHERE usuario='$usuario'") or die(mysql_error());
$total_encontrados = mysql_num_rows ($usuarios_consulta);
mysql_free_result($usuarios_consulta);

if ($total_encontrados != 0) {
header ("Location: $pag?accion=nuevo&error=4");
exit;
}

$usuario=stripslashes($usuario);
$pass1 = md5($pass1);
mysql_query("INSERT INTO $sql_tabla values('','$usuario','$pass1','$nivel')") or die(mysql_error());
mysql_close();

header ("Location: $pag");
exit;


}
include("../f.php");
?>
</BODY>
</HTML>

