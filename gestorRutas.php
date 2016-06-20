<?

session_start();

$categoria=$_SESSION['administrador'];
$us=$_SESSION['usuario_id'];
	
if((!isset($_SESSION['usuario_id']))&&($categoria)){

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

	if(isset($_POST['submitted'])){
	
	
	if(empty($_POST['nombre'])){
		
		$errores[]='Olvido introducir el nombre de la ruta';
		}else{
				$nb=trim($_POST['nombre']);
		}
	if(empty($_POST['ciudad'])){
		
		$errores[]='Olvido introducir el nombre de la ciudad';
		}else{
				$ci=trim($_POST['ciudad']);
		}
	if(empty($_POST['descripcion'])){
		
		$errores[]='Olvido introducir la descripcion de la ruta';
		}else{
				$ds=trim($_POST['descripcion']);
		}

	if(isset($_FILES['upload'])){
			//validate the type. Should be JPEG or PNG
			
			$allowed=array('image/pjpeg', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
			
			if(in_array($_FILES['upload']['type'],$allowed)){
			
			if(move_uploaded_file($_FILES['upload']['tmp_name'],"../uploads/{$_FILES['upload']['name']}")){
			
			
			//esta parte corresponde a como almacenar en la BBDD la imagen subida.

			
			$image=imagecreatefromjpeg("../uploads/{$_FILES['upload']['name']}");
			
			ob_start();
			imagejpeg($image);
			$jpg=ob_get_contents();
			ob_end_clean();

			
			$jpg=str_replace('##','##',mysql_escape_string($jpg));
			

			}
		
		}
		
		 }else{
				
				//$image=imagecreatefromjpeg("../imagenes/rutas.jpg}"); break;
				
			}
			/*ob_start();
			imagejpeg($image);
			$jpg=ob_get_contents();
			ob_end_clean();

			
			$jpg=str_replace('##','##',mysql_escape_string($jpg));*/
		


		
		
		 
	$us=$_SESSION['usuario_id'];

	
	if(empty($errores)){


$num=count($_POST);
$num-=3;

require_once('../mysql_conexion.php');

$aux1=array();
$aux2=array();
$aux3=array();

echo "<br>";

$i=0;
foreach ($_POST as $key => $val) {

	if(($i<$num) && ($i>2))
	{$q="SELECT latitud,longitud FROM POI WHERE poi_id='".$val."'";
	$r=@mysqli_query($dbc, $q);
	$row=mysqli_fetch_array($r, MYSQLI_ASSOC);
	
	$aux1[$i]=$val;
	$aux3[$i]=$row[latitud];
	$aux2[$i]=$row[longitud];
	
	}

	$i+=1;
} 




natsort($aux3);

$pois="";
$j=0;

foreach ($aux3 as $key => $val) {
$pois.= $aux1[$key].",";
$longitudes [$j] = $aux2[$key];
$latitudes[$j]=$val;
$j+=1;
} 

$pois= substr ($pois, 0, -1);




$max=count($latitudes);
$linea="";

if($max>2){
for($j=0; $j<$max; $j++)
{

	if($j==0){$linea.=$latitudes[$j].",".$longitudes[$j].",[";}
	else{
	if($j==$max-1){$linea.="],".$latitudes[$j].",".$longitudes[$j];
	}else{	
	if($j==$max-2){$linea.=$latitudes[$j].",".$longitudes[$j];}
	else{$linea.=$latitudes[$j].",".$longitudes[$j].",";}
	}}

}
}else{

$linea.=$latitudes[0].",".$longitudes[0].",".$latitudes[1].",".$longitudes[1];

}

$us="7";////////////////////quitar esto

$APIKEY="9m0u42fpby29y0021a4qv";
$parametros['position']=$linea;
$parametros['route_type']="foot.js";
$servicio="routing";

require_once('REST.php');
$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);


$respuesta = json_decode($request->GetResponseBody());

$respuesta=$respuesta->{'route_geometry'};

$linea="";
foreach ($respuesta as $key => $val) {

	$linea.=$respuesta[$key][0].",".$respuesta[$key][1].";";

}

$linea = substr ($linea, 0, -1);


require_once('../mysql_conexion.php');
	
$q="INSERT INTO tematicas(nombre, ciudad, creador, descripcion, ruta_poi, ruta_coord, imagen) VALUES ('$nb', '$ci', '$us', '$ds', '$pois','$linea', '$jpg')";
$r=@mysqli_query($dbc, $q);

if(!$r){echo "<br>Algo salio mal<br>";
echo '<p>'.mysqli_error($dbc).'<br /><br />Query:'.$q.'</p>';
}else{echo "todo bien";}


exit();

}else{

		echo '<h1>Error en el sistema </h1>
		<p class="error">No se ha podido registrar debido a un error en el sistema. Disculepn las molestias</p>';
	
	//depuramos el mensaje de error
	
	echo '<p>'.mysqli_error($dbc).'<br /><br />Query:'.$q.'</p>';
	
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
      <!-- **** INSERT LINKS HERE **** -->
     <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="center"id="logo">
<h1><FONT SIZE=6>GUIA TURISTICA</FONT></h1>
</div>
    <div id="menu">
      <ul>
         <li><a href="index.html">Principal</a></li>
        <li><a href="servicios.php">Servicios</a></li>
	<li><a href="gestorPOI.php">Gestor de POI</a></li>
        <li><a id="selected" href="gestorRutas.php">Gestor de Rutas</a></li>       
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
        <h1>Gestor de rutas temáticas</h1>
        
	<h2><a href="crearRutas.php">Crear una nueva ruta temática</a></h2>
	<p><br>
	Al estar registrado en el sistema y tener categoría de administrador 
	tienes la opción de crear una ruta temática. El objetivo de estas rutas
	es que el usuario pueda	visitar una serie de puntos de interés proximos 
	entre sí y que ofrezcan algun tipo de servicio o producto similar, por ejemplo
	rutas de los vinos, rutas de la cerveza, ruta de tapas...
	<br><br>
	Para poder crear la ruta tienes a tu disposición todos los Puntos de Interes
	registrados en el sistema, así como la oportunidad de poder acceder a su información.
	Vete seleccionando los que consideres oportunos y rellena los demás campos del
	formulario para generar la nueva ruta.	
	</p><br>


	<h2>Lista de Rutas Temáticas previamente creadas por el usuario</h2>
	
	<fieldset><legend>Rutas creadas por el usuario:</legend>
<?
			require_once('../mysql_conexion.php');
	
			$q="SELECT nombre,ruta_id,imagen,ciudad FROM RUTAS WHERE creador='".$_SESSION['usuario_id']."'";
			$r=@mysqli_query($dbc, $q);
			
			$cont=0;
			while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
							
			echo '<span class="center"><B>'.$row[nombre].'</B></span><br>';
			echo '<span class="center"><B>'.$row[ciudad].'</B></span><br>';	
			$var=$row['imagen'];
			$var=base64_encode($var);
			echo '<span class="center"><img ALIGN="CENTER"HEIGHT="150" WIDHT="170" src="data:image/jpeg;base64,'.$var.'" /></span>';
			echo '<span class="center"><h2><a href="eliminarRuta.php?id='.$row[ruta_id].'">Eliminar la ruta</a></h2></span>';
			echo '<br><br>';
												

			$cont+=1;
			}		

?>


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

