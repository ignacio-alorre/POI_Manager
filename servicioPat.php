<?php # Script 2.2 -Manejador_formulario.php
	
if(isset($_POST['submitted2'])){

	$eleccion=$_POST['eleccion'];
	
	if(empty($_POST['ciudad']))
	{
		$errores[]='Olvido introducir la ciudad';
	}
	if(empty($_POST['calle']))
	{
		$errores[]='Olvido introducir la calle';
	}
	if(empty($_POST['numero']))
	{
		$errores[]='Olvido introducir el numero';
	}
	
	if(empty($errores)){	

	$ca=trim($_POST['calle']);
	$num=trim($_POST['numero']);
	$ci=trim($_POST['ciudad']);
	$tipo=trim($_POST['tipo']);	

	$direccion=$ca." ".$num;
	$ciudad=$ci;	
	$servicio="GoogleM";	
	
	require_once('REST.php');
	
	$parametros['direccion']=$direccion;
	$parametros['ciudad']=$ciudad;
	$parametros['servicio']=$servicio;
	$servicio="geosearch";	


	$request=new RestRequest(null, null,$servicio, $parametros);
	$code=$request->getCodigo();
	
	$lat=$request->getLatitud();
	$lon=$request->getLongitud();
	$pos=$lat.",".$lon;


	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/path/INFO/get.js?eleccion=".$eleccion."&tipo_ruta=".$tipo."&posicion_inicial=".$pos;


	$ch = curl_init();
	$tipo="JSON";
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: '.$tipo));
	
	$respuesta = curl_exec($ch);
	
		}
	}

if(isset($_POST['submitted'])){

	if(empty($_POST['nombre']))
	{
		$errores[]='Olvido introducir el nombre';
	}
	
	if(empty($errores)){
	
	$nombre=trim($_POST['nombre']);
	$info=$_POST['info'];
	
	if($info==2)
	{$INFO="EXTRA";}
	else{$INFO="INFO";}
	
	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/path/INFO/get.js?nombre=".$nombre;

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
        <h1>Probar servicio PATH</h1>
	<?php
	if((!empty($errores))&&(isset($_POST['submitted'])||isset($_POST['submitted2']))){
	
	echo '<h2>Error!</h2><p class="error">Han ocurrido los siguientes errores:<br />';
	echo "<br>";
	foreach($errores as $msg){
	
	echo "<font color='red'>-$msg<br /></font>\n";

	
	}
	
	echo '</p><p>Por favor vuelva a introducir los datos en el formulario.</p><p><br /></p>';
	
	}//fin de empty(errores)
	
	?>

	<?php
	if((isset($_POST['submitted']))&&(empty($errores))) {

	$respuesta=json_decode($respuesta);
	
	$resp=$respuesta->{'Lista de POIs'};
	
	echo '<form enctype="multipart/form-data" action="servicioPat.php" method="post">';

	foreach($resp as $value)
	{
		$id=$value->{'Identificador del POI'};
		echo '<select name="eleccion">';
		echo '<option value="'.$id.'">'.$value->{"Nombre"}.'</option>';					
		echo '</select></p>';
	}
	

	echo '<legend>Medio que se empleará para recorrer la ruta:</legend><br>';
	echo '<select name="tipo">';
	
	echo '<option value="car">En coche </option>';
	
	echo '<option value="foot">A pie </option>';
	
	echo '<option value="bicycle">en bicicleta </option>';	
	echo '</select></p>';
	echo '<br><br>';


	echo '<fieldset ><legend>Localizacion:</legend>';

	echo '<p><b>Ciudad:</b> <input type="text" name="ciudad" size="40" value="gijon"/></p>';
	
	echo '<p><b>Calle:</b> <input type="text" name="calle" size="40" value="ezcurdia"/></p>';
	
	echo '<p><b>Numero:</b> <input type="text" name="numero" size="40" value="30"/></p>';
	
	echo '</fieldset><br>';

	echo '<div align="center"><input type="submit" name="submit" value="Probar Servicio Geocoding" /></div>';
	echo  '<input type="hidden" name="submitted2" value="1" />';

	echo '	</form>';
	exit();
	}

	?>


	
	

	<?php
	if(!empty($_POST['eleccion'])&&(empty($errores))) {
	
	$respuesta=json_decode($respuesta);
	
	$resumen=$respuesta->{'route_summary'};
	$geometria=$respuesta->{'route_geometry'};
	$instrucciones=$respuesta->{'route_instructions'};

	echo "A continuación procedemos a mostrar la información de la ruta:<br><br>";
	echo "La distancia total de la ruta: ".$resumen->{'total_distance'}." metros<br>";
	echo "El tiempo que lleva recorrer la ruta: ".$resumen->{'total_time'}." segundos<br>";
	echo "El punto inicial: ".$resumen->{'start_point'}."<br>";
	echo "El punto final: ".$resumen->{'end_point'}."<br>";

	echo "<br>";echo "<br>";
	echo "Las coordenadas de los Puntos por los que pasa la ruta son:<br><br>";
	
	foreach($geometria as $value)
	{
	echo "Latitud: ".$value[0];	
	echo " ,Longitud: ".$value[1]."<br>";

	}
	
	echo "Las instrucciones para seguir la ruta son:<br><br>";
	
	foreach($instrucciones as $value)
	{
	$var=utf8_decode($value[0]);
	echo $var." durante ".$value[1]." metros<br>";

	}

		

	exit();

	}

	?>

	<?php
	if((!$_POST['submitted'])||(!empty($errores)))
	{
	
	echo '<form enctype="multipart/form-data" action="servicioPat.php" method="post">';
	echo '<p><b>Nombre o parte del nombre del POI:</b></p><input type="text" name="nombre" size="40" value="pizza"/>';
	echo '<div align="center"><input type="submit" name="submit" value="Probar Servicio Path" /></div>';
	echo  '<input type="hidden" name="submitted" value="1" />';
	echo '</form>';
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