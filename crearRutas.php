<?

session_start();

$categoria=$_SESSION['administrador'];
$us=$_SESSION['usuario_id'];

	
if((!isset($_SESSION['usuario_id']))&& ($categoria)){

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
				
				
				$image=imagecreatefromjpeg("../imagenes/bar.jpg"); break;
				
			}
			ob_start();
			imagejpeg($image);
			$jpg=ob_get_contents();
			ob_end_clean();

			
			$jpg=str_replace('##','##',mysql_escape_string($jpg));
		
		
		 
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
	
$q="INSERT INTO RUTAS(nombre, ciudad, creador, descripcion, ruta_poi, ruta_coord, imagen) VALUES ('$nb', '$ci', '$us', '$ds', '$pois','$linea', '$jpg')";
$r=@mysqli_query($dbc, $q);

if(!$r){echo "<br>Algo salio mal<br>";
echo '<p>'.mysqli_error($dbc).'<br /><br />Query:'.$q.'</p>';
}else{
	require_once('funciones_login.php');
	$url=url_absoluta('gestorRutas.php');
	
	header("Location: $url");	
	
	exit();
	

}





	
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
        <h1>Crear de ruta temática</h1>

	<form enctype="multipart/form-data" action="crearRutas.php" method="post">
	
<input type="hidden" name="MAX_FILE_SIZE" value="524288">	

<fieldset><legend>Introduce la informacion correspondiente a la nueva Ruta Temática:</legend>
	
<p><b>Nombre de la ruta tematica:</b> <input type="text" name="nombre" size="20" maxlength="40" /></p>

<p><b>Ciudad:</b> <input type="text" name="ciudad" size="20" maxlength="40" /></p>


<fieldset><legend>Puntos de Interes que van a pertenecer a la Ruta Tématica:</legend>
<?
			require_once('../mysql_conexion.php');
	
			$q="SELECT nombre,poi_id,imagen, tipo, ciudad FROM POI";
			$r=@mysqli_query($dbc, $q);
			
			$cont=0;
			while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
						
			echo '<B><INPUT ALIGN="left" TYPE="CHECKBOX" NAME="'.$cont.'" value="'.$row[poi_id].'"></B><BR>';	
			echo '<span class="center"><B>'.$row[nombre].'</B></span><br>';
			echo '<span class="center"><B>'.$row[ciudad].'</B></span><br>';
			echo '<span class="center"><B>'.$row[tipo].'</B></span><br>';	
			$var=$row['imagen'];
			$var=base64_encode($var);
			echo '<span class="center"><img ALIGN="CENTER"HEIGHT="150" WIDHT="170" src="data:image/jpeg;base64,'.$var.'" /></span>';
			echo '<br><br>';
							
					

			$cont+=1;
			}		

?>

</fieldset>


<fieldset>
<p><b>Descripcion:</b> <input type="text" name="descripcion" size="400" /></p>
	
</fieldset>
<fieldset><legend>Selecciona una imagen JPEG or PNG con un tamaño menor o igual a 512KB:</legend>
		 
 <p><b>File:</b><input type="file" name="upload"/></p>

	
</fieldset>


<div align="center"><input type="submit" name="submit" value="Crear nueva ruta" /></div>
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






