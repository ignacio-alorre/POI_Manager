<?php # Script 2.2 -Manejador_formulario.php
	
if(isset($_POST['submitted'])) {

	
	if(empty($_POST['ident'])){
		
		$errores[]='Olvido introducir el identificador';

	}


	if(empty($errores)){

	$ident=trim($_POST['ident']);
	$limp=trim($_POST['limp']);
	$ffun=trim($_POST['ffun']);
	$comu=trim($_POST['comu']);
	$empl=trim($_POST['empl']);
	$calp=trim($_POST['calp']);

	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/evaluation/INFO/get.js?eleccion=".$ident."&empleados=".$empl."&emplazamiento_comunicacion=".$comu."&factor_diversion=".$ffun."&calidad_precio=".$calp."&limpieza=".$limp;
	
$ch = curl_init();
$tipo="JSON";
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: '.$tipo));

$respuesta = curl_exec($ch);

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
        <h1>Probar servicio Evaluation</h1>
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
	if(isset($_POST['submitted'])&&empty($errores)) {

	$respuesta=json_decode($respuesta);
	
	echo "Nombre del Punto de Interés: ".$respuesta->{'Nombre'}."<br>";
	echo "Votos recibidos hasta el momento: ".$respuesta->{'numero de votos'}."<br><br>";
	$valoraciones=$respuesta->{'valoraciones'};
	echo "Valoraciones dadas por otros clientes a diferentes características del Punto de Interés<br><br>";

	echo "Valoracion dada a los empleados: ".number_format($valoraciones->{'empleados'},2)."<br>";
	echo "Valoracion dada a la relacion emplezamiento/comunicación: ".number_format($valoraciones->{'emplazamiento-comunicacion'},2)."<br>";
	echo "Valoración dada al factor diversion: ".number_format($valoraciones->{'factor_diversion'},2)."<br>";
	echo "Valoración dada a la relacion calidad/precio: ".number_format($valoraciones->{'calidad-precio'},2)."<br>";
	echo "Valoración dada a la limpieza del Punto de Interés: ".number_format($valoraciones->{'limpieza'},2)."<br>";
	echo "Puntuación global dada al Punto de Interés: ".number_format($valoraciones->{'puntuacion'},2)."<br>";
		

	exit();

	}


	?>

	
	<form enctype="multipart/form-data" action="servicioEva.php" method="post">
		
	<fieldset><legend>Introducir la puntuación que se le dará al Punto de Interes</legend>
	<br>
	


	<p><b>Identificador del Punto de interés evaluado:</p></b><br><input type="text" name="ident" size="40" value="4"/>
			
	<br><br>
	<legend>Empleados:</legend><br>
	<select name="empl">
	
	<option value="1">1 </option>
	<option value="2">2 </option>
	<option value="3">3 </option>
	<option value="4">4 </option>
	<option value="5">5 </option>
		
	</select></p>	
	<br><br>
	<legend>Emplazamiento-comunicación:</legend><br>
	<select name="comu">
	
	<option value="1">1 </option>
	<option value="2">2 </option>
	<option value="3">3 </option>
	<option value="4">4 </option>
	<option value="5">5 </option>
		
	</select></p>	
	<br><br>
	<legend>Factor diversion:</legend><br>
	<select name="ffun">
	
	<option value="1">1 </option>
	<option value="2">2 </option>
	<option value="3">3 </option>
	<option value="4">4 </option>
	<option value="5">5 </option>
		
	</select></p>	
	<br><br>
	<legend>relación calidad precio:</legend><br>
	<select name="calp">
	
	<option value="1">1 </option>
	<option value="2">2 </option>
	<option value="3">3 </option>
	<option value="4">4 </option>
	<option value="5">5 </option>
		
	</select></p>	
	<br><br>
	<legend>Limpieza:</legend><br>
	<select name="limp">
	
	<option value="1">1 </option>
	<option value="2">2 </option>
	<option value="3">3 </option>
	<option value="4">4 </option>
	<option value="5">5 </option>
		
	</select></p>	
	<br><br>
	
	
	
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