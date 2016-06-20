<?php


require_once('../mysql_conexion.php');

$q="DELETE FROM RUTAS WHERE ruta_id=".$_GET['id'];

$r=@mysqli_query($dbc, $q);


require_once('funciones_login.php');
	
	$url=url_absoluta('gestorRutas.php');
	
	header("Location: $url");
	
	exit();




?>