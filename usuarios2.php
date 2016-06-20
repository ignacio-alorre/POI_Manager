<?php # Script 2.2 -Manejador_formulario.php
	
	//Creamos las variables para gestionar el formulario
	
if(isset($_POST['submitted'])){
	
	
	if(empty($_POST['nombre'])){
		
		$errores[]='Olvido introducir el nombre';
		}else{
				$nb=trim($_POST['nombre']);
		}
		
	if(empty($_POST['apellido1'])){
		
		$errores[]='Olvido introducir el apellido1';
		}else{
				$ap1=trim($_POST['apellido1']);
		}
	
	if(empty($_POST['apellido2'])){
		
		$errores[]='Olvido introducir el apellido2';
		}else{
				$ap2=trim($_POST['apellido2']);
		}
	
	
	if(empty($_POST['telefono'])){
		
		$errores[]='Olvido introducir la telefono';
		}else{
				$tel=trim($_POST['telefono']);
		}
	
	if(empty($_POST['email'])){
		
		$errores[]='Olvido introducir el email';
		}else{
				$em=trim($_POST['email']);
		}
	
	if(empty($errores)){
	
	$key=crear_clave();
	
	require_once('../mysql_conexion.php');
	
	$q="INSERT INTO USUARIOS(nombre, apellido1, apellido2, telefono, APIkey, email) VALUES ('$nb', '$ap1', '$ap2', '$tel', '$key', '$em')";
	
	$r=@mysqli_query($dbc, $q);

	
	if($r){

	$to=$em;
	$subject="APIKEY";
	$body="\nHola ".$nb.",\n\nTe has registrados correctamente como usuario del sistema\n Tu APIKEY es".$key;
	$header="From: GuiaTuristica@gmail.com\r\n";

	mail($to,$subject,$body,$header);



	$objeto='{"APIKEY":".$key."}';
	echo $objeto;

	//Aqui irian los comentarios y demas historias en caso de que todo haya ido bien despues de actualizar la base de datos
		
	require_once('funciones_login.php');
	
	$url=url_absoluta('index.html');
	
	header("Location: $url");
	
	exit();


	
	}
	
	}//fin del isset($_POST['submitted'])
	
	}


function crear_clave(){

function crear_semilla() {
list($usec, $sec) = explode(" ", microtime());
return (float) $sec + ((float) $usec * 100000);
}
srand(crear_semilla());

// Generamos la clave
$clave="";
//$max_chars = round(rand(7,10)); // tendrá entre 7 y 10 caracteres
$chars = array();
for ($i="a"; $i<"z"; $i++) $chars[] = $i; // creamos vector de letras
$chars[] = "z";
for ($i=0; $i<21; $i++) {
$letra = round(rand(0, 1)); // primero escogemos entre letra y número
if ($letra) // es letra
$clave .= $chars[round(rand(0, count($chars)-1))];
else // es numero
$clave .= round(rand(0, 9));
}


}


	
	
	
	
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <title>template: page 2</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

  <!-- **** layout stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="style/style.css" />

  <!-- **** colour scheme stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="style/colour.css" />

</head>

<body>
  <div id="main">
    <div id="links">
      <!-- **** INSERT LINKS HERE **** -->
    </div>
    <div class="center"id="logo">
<h1><FONT SIZE=6>GUIA TURISTICA</FONT></h1>
</div>
    <div id="menu">
      <ul>
        <li><a href="index.html">Principal</a></li>
        <li><a href="servicios.php">Servicios</a></li>
	<li><a href="software.php">Softwares</a></li>
      </ul>
    </div>
    <div id="content">
      <div id="column2">
        <h1>Registrarse como usuario del Sistema</h1>

	<?php	

	if(isset($_POST['submitted'])){

	echo '<h2>Error!</h2><p class="error">Han ocurrido los siguientes errores:<br />';
	echo "<br>";
	foreach($errores as $msg){
	
	echo "<font color='red'>-$msg<br /></font>\n";

	
	}

	}	

	?>

	<form action="usuarios2.php" method="post">
	
	
	<fieldset><legend>introduce tu información en el siguiente formulario:</legend>
	
	<p><b>Nombre                         :</b> <input type="text" name="nombre" size="20" maxlength="40" /></p>	
	
	<p><b>Primer Apellido                :</b> <input type="text" name="apellido1" size="20" maxlength="40" /></p>
	
	<p><b>Segundo Apellido               :</b> <input type="text" name="apellido2" size="20" maxlength="40" /></p>
	
	<p><b>Telefono de contacto           :</b> <input type="text" name="telefono" size="20" maxlength="40" /></p>
	
	<p><b>Direccion de correo electronico:</b> <input type="text" name="email" size="20" maxlength="40" /></p>
	
	
	</fieldset>	
	<div align="center"><input type="submit" name="submit" value="Registrarse" /></div>
	<input type="hidden" name="submitted" value="1" />
	
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