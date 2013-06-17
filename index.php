<?php	
	if(is_dir("setup")){	
		header("Location: setup/index.php");
		exit;
	}else{
		header("Location:modulos/mod_login/index.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>...::Inicio::...</title>
<style type="text/css">
<!--
body{font-family:Verdana, Geneva, sans-serif; font-size:14px; margin:15px; padding:20px;}
#msgMantenimiento{border:#000 solid thin;background-color:#999;height:250px;width:800px;position:absolute;left:50%;top:50%;margin-left:-400px;margin-top:-125px;z-index:3;}
-->
</style>
</head>

<body>

</body>
</html>