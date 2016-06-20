<?php

session_start();

if(!isset($_SESSION['usuario_id'])){

	require_once('funciones_login.php');
	
	$url=url_absoluta('login.php');
	
	header("Location: $url");
	
	exit();
	}

$categoria=$_SESSION['administrador'];
$nombre=$_SESSION['nombre'];
$id=$_SESSION['usuario_id'];

require_once('../mysql_conexion.php');
$q="SELECT poi_id FROM POI WHERE creador='".$_SESSION['usuario_id']."'";
$r=@mysqli_query($dbc, $q);
$cont=0;
while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){$cont+=1;}

if($categoria=="1"){
$q="SELECT ruta_id FROM RUTAS WHERE creador='".$_SESSION['usuario_id']."'";
$r=@mysqli_query($dbc, $q);
$cont2=0;
while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){$cont2+=1;}
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
      <a href="logout.php">Cerrar Sesion</a>
    </div>
    <div class="center"id="logo">
<h1><FONT SIZE=6>GUIA TURISTICA</FONT></h1>
</div>
    <div id="menu">
      <ul>
        <li><a href="index.html">Principal</a></li>
        <li><a href="servicios.php">Servicios</a></li>
	<li><a id="selected" href="gestorPOI.php">Gestor de POI</a></li>
        <?php if($_SESSION['administrador']==1)echo '<li><a href="gestorRutas.php">Gestor de Rutas</a></li>'; ?>
      </ul>
    </div>
    <div id="content">
      <div id="column1">
        <div class="sidebaritem">
          <h1><u>Datos del usuario</u></h1>
		<?php
			echo "<b>Nombre del gestor</b>: $nombre($id)<br><br>";
			echo "<b>Categoria del gestor</b>: ";
			if($categoria==1){echo "Administrador de POIs y rutas temáticas<br><br>";}
			else {echo"Gestor de POIs<br>";}
			echo "<b>Numero de POIs registrados por el gestor</b>: $cont<br><br>";
			if($categoria==1){echo "<b>Numero de rutas registradas por el gestor</b>: $cont2<br><br>";}
		?>          
          <p></p>

        </div>
       
      </div>
      <div id="column2">
        <h1>Gestor de Puntos de Interés</h1>
        
	<h2><a href="crearPOI.php">Crear nuevo Punto de Interés</a></h2>
	<p><br>
	Al estar registrado en el sistema puedes crear un nuevo Punto de Interés 
	que pasará a quedar registrado en la base de datos. Todos los Puntos de 
	Interes que están en la base de datos pueden ser accedidos por los aquellos
	usuarios que desde sus terminales móviles, y usando el software adecuado
	Invoquen alguno de nuestros Servicios Web	
	</p><br>

	<fieldset><legend>Puntos de Interés creados por el usuario:</legend>

	<?
			require_once('../mysql_conexion.php');
	
			$q="SELECT nombre,poi_id,imagen,ciudad,tipo FROM POI WHERE creador='".$_SESSION['usuario_id']."'";
			$r=@mysqli_query($dbc, $q);
			
			$cont=0;
			while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
							
			echo '<span class="center"><B>'.$row[nombre].'</B></span><br>';
			echo '<span class="center"><B>'.$row[ciudad].'</B></span><br>';	
			echo '<span class="center"><B>'.$row[tipo].'</B></span><br>';
			$var=$row['imagen'];
			$var=base64_encode($var);
			echo '<span class="center"><img ALIGN="CENTER"HEIGHT="150" WIDHT="170" src="data:image/jpeg;base64,'.$var.'" /></span>';
			echo '<span class="center"><h2><a href="modificarPOI.php?val='.$row[nombre].'">Modificar un punto de Interes</a></h2></span>';
			echo '<span class="center"><h2><a href="eliminarPOI.php?id='.$row[poi_id].'">Eliminar un punto de Interes</a></h2></span>';								
			echo '<br><br>';

			$cont+=1;
			}		

	?>

		
		
	
	</ul>
        
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