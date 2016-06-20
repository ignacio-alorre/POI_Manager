
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
      <!-- **** INSERT LINKS HERE **** -->
     <a href="login.php">Iniciar Sesion</a>
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
        <h1>Servicios de la Guia Turistica</h1>

<?php

if($enviado){
echo trim($respuesta);
exit();
}

?>		
		
<h2><a href="servicioGeo.php">Geocoding</a></h2>
<p>
Este servicio permite al usuario encontrar POIs cercanos al lugar 
en el que te encuentras. Para conseguir esto el usuario deberá
primeramente indicar tanto el perímetro de búsqueda como el tipo 
de POIs que desea encontrar.
</p>

<h2><a href="servicioRou.php">Routing</a></h2>
<p>
El servicio de Routing permite al usuario obtener una ruta desde 
un punto determinado hasta otro. Para ello el usuario debe 
introducir las coordenadas de esos puntos inicial y final, así 
como un indicador del medio en el que va a hacer la ruta.
</p>

<h2><a href="servicioEva.php">Evaluation</a></h2>
<p>
Este servicio permite a los usuarios evalar los diferentes POI en 
los que han estado, de tal forma que esta puntuación quede 
reflejada en la base de datos. Gracias a estas evaluaciones otros 
futuros usuarios podrán disponer de información adicional a la hora 
de decidir que POI es el más conveniente para ellos. 
</p>

<h2><a href="servicioTem.php">Tematicas</a></h2>
<p>
Este servicio permite a los usuarios recibir información sobre 
rutas temáticas (ruta de los vinos, ruta de tapas), así como de 
los POIs que forman dichas rutas, de una determinada ciudad.  
</p>

<h2><a href="servicioPat.php">Path</a></h2>
<p>
El servicio Path permite al usuario obtener el camino a seguir 
desde su posición hasta un POI del cual conozca al menos 
parte del nombre
</p>
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
