<?php

require_once('../mysql_conexion.php');

$q="DELETE FROM POI WHERE poi_id=".$_GET['id'];

$r=@mysqli_query($dbc, $q);


require_once('funciones_login.php');
	
	$url=url_absoluta('gestorPOI.php');
	
	header("Location: $url");
	
	exit();

?>