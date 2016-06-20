<?php # Script 2.2 -Manejador_formulario.php
	
if(!empty($_GET['eleccion'])) {

	$eleccion=$_GET['eleccion'];
	
	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/tematicas/INFO/get.js?eleccion=".$eleccion;

	$ch = curl_init();
	$tipo="JSON";
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: '.$tipo));
	
	$respuesta = curl_exec($ch);

	}

if((isset($_POST['submitted']))&&(empty($_GET['eleccion']))){

	
	if(empty($_POST['ciudad']))
	{
		$errores[]='Olvido introducir la ciudad';
	}
	
	if(empty($errores)){

	$ciudad=trim($_POST['ciudad']);
	$info=$_POST['info'];
	
	if($info==2)
	{$INFO="EXTRA";}
	else{$INFO="INFO";}
	
	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/tematicas/".$INFO."/get.js?ciudad=".$ciudad;

$ch = curl_init();
$tipo="JSON";
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: '.$tipo));

$respuesta = curl_exec($ch);
echo $respuesta;
$info=curl_getinfo($ch);

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
     <a href="logout.php">Cerrar sesión</a>
    </div>
    <div class="center"id="logo">
<h1><FONT SIZE=6>GUIA TURISTICA</FONT></h1>
</div>
    <div id="menu">
      <ul>
        <li><a href="index.html">Principal</a></li>
        <li><a id="selected" href="servicios.php">Servicios</a></li>
	<li><a href="software.php">Softwares</a></li>      
</ul>
    </div>
    <div id="content">
      <div id="column2">
        <h1>Probar servicio TEMATICAS</h1>
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

	<?php

	if((isset($_POST['submitted']))&&(!isset($_GET['eleccion']))&&(empty($errores))) {

	$respuesta=json_decode($respuesta);
	
	$resp=$respuesta->{'Lista de rutas'};
	
	foreach($resp as $value)
	{
		$id=$value->{'Identificador de la ruta'};
		echo '<b><a href="servicioTem.php?eleccion='.$id.'">'.$value->{"Nombre"}.'</a></b><br>';	
		echo $value->{'Descripcion'}."<br><br><br>";
		
	}
	
	exit();
	}

	if(!empty($_GET['eleccion'])&&(empty($errores))) {

	$respuesta=json_decode($respuesta);
	
	$nombre=$respuesta->{'Nombre'};
	$ciudad=$respuesta->{'Ciudad'};
	$descripcion=$respuesta->{'Descripcion'};
	$Coordenada=$respuesta->{'Coordenadas por las que pasa la ruta'};
	$POIs=$respuesta->{'POIs en la ruta'};
		
	echo "Nombre de la ruta: ".$nombre."<br>";
	echo "Ciudad en la que está la ruta: ".$ciudad."<br>";
	echo "Descripción de la ruta: ".$descripcion."<br>";
	echo "<br>Las coordenadas por las que pasa la ruta:<br><br>";
	
	foreach($Coordenada as $value)
	{
		echo "Latitud: ".$value->{'Latitud'}."<br>";
		echo "Longitud: ".$value->{'Longitud'}."<br>";

	}

	echo "<br>Los Puntos de Interés por las que pasa la ruta:<br><br>";

	foreach($POIs as $value)
	{
		echo "Nombre del POI: ".$value->{'Nombre del POI'}."<br>";
		echo "Descripción: ".$value->{'Descripcion'}."<br>";
		echo "Puntuación: ".$value->{'Puntuacion'}."<br>";
		

	}

	exit();
	}

	?>

	
	<form enctype="multipart/form-data" action="servicioTem.php" method="post">
		
	<fieldset><legend>Introducir la puntuación que se le dará al Punto de Interes</legend>
	<br>

	<p><b>Ciudad:</b></p><input type="text" name="ciudad" size="40" value="gijon"/>
						
	<br>

	
	<div align="center"><input type="submit" name="submit" value="Probar Servicio Geocoding" /></div>
	<input type="hidden" name="submitted" value="1" />
	
	<br><br>
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