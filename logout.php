<?php

session_start();

if(!isset($_SESSION['usuario_id'])){

	require_once('funciones_login.php');
	
	$url=url_absoluta('login.php');
	
	header("Location: $url");
	
	exit();
	}else{
	
	$_SESSION=array();
	
	session_destroy();
	
	setcookie('PHPSESSID', '', time()-3600, '/', '', 0,0);

	echo "<h1>Logged Out!</h1>
	
	<p>Has cerrado la sesion, {$_SESSION['usuario_id']}!</p>";

	require_once('funciones_login.php');
	
	$url=url_absoluta('login.php');
	
	header("Location: $url");
	
	exit();


	}

	
	
?>