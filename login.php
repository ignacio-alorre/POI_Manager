<?php  # login.php

if(isset($_POST['submitted'])) {

	
	require_once('funciones_login.php');
	
	require_once('../mysql_conexion.php');
	
	list($check, $data)=check_login($dbc,$_POST['usuario'], $_POST['clave']);
	$errores=$data;
	
	
	if($check){
	
	session_start();
	
	$_SESSION['usuario_id']=$data['gestor_id'];
	$_SESSION['administrador']=$data['administrador'];
	$_SESSION['nombre']=$data['nombre'];
		
	$url=url_absoluta('gestorPOI.php');
	
	header("Location: $url");
	
	mysqli_close($dbc);
	
	exit();
	
	}
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <title>template: page 1</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

  <!-- **** layout stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="style/style.css" />

  <!-- **** colour scheme stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="style/colour.css" />

</head>

<body>
  <div id="main">
    <div id="links">
    </div>
    <div class="center"id="logo">
<h1><FONT SIZE=6>GUIA TURISTICA</FONT></h1>
</div>
    <div id="menu">
      <ul>
        <li><a href="index.html">Principal</a></li>
        <li><a href="servicios.php">Servicios</a></li>

      </ul>
    </div>
    <div id="content">
      <div id="column1">
        <div class="sidebaritem">
          <h1><u>Registrarse como Gestor de Puntos de Interes</u></h1>
          
          <p>Para poder acceder al gestor de POI, que te permitira almacenar tus POI en la base de datos del sistema, debes primeramente estar registrado.</p>
          <p><a href="usuarios.php">Registrarse como gestor</a></p>
        </div>

	   <div class="sidebaritem">
          <h1><u>Registrarse como Cliente del sistema</u></h1>
          
          <p>Para poder disfrutar de los servicios de la guia turistica necesitas tener una APIKEY que te identifique dentro del sistema</p>
          <p><a href="usuarios2.php">Registrarte como usuario</a></p>
        </div>
       
      </div>
      <div id="column2">
        <h1>Iniciar sesión como gestor de POIs</h1>

	<?php
	if((!empty($errores))&&(isset($_POST['submitted']))){
	
	echo '<h2>Error!</h2><p class="error">Han ocurrido los siguientes errores:<br />';
	echo "<br>";
	foreach($errores as $msg){
	
	echo "<font color='red'>-$msg<br /></font>\n";

	
	}
	
	echo '</p><p>Por favor vuelva a introducir los datos en el formulario.</p><p><br /></p>';
	
	}//fin de empty(errores)	

	?>	


	<form action="login.php" method="post">
	
	
	<fieldset><legend>introduce tu información en el siguiente formulario:</legend>
	
	<p><b>Nombre del usuario:</b> <input type="text" name="usuario" size="20" maxlength="40" /></p>
	
	<p><b>Clave:</b> <input type="password" name="clave" size="40" /></p>
	
	
	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Iniciar sesion" /></div>

	
	<input type="hidden" name="submitted" value="TRUE" />
</form>



      </div>
    </div>
    <div id="footer">
      copyright &copy; 2006 your name | <a href="#">email@emailaddress</a> | <a href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.dcarter.co.uk">design by dcarter</a>
    </div>
  </div>

<div style="font-size: 0.8em; text-align: center; margin-top: 1.0em; margin-bottom: 1.0em;">
Design downloaded from <a href="http://www.freewebtemplates.com/">Free Templates</a> - your source for free web templates
</div>
</body>
</html>


