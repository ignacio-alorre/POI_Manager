<?php # Script 2.2 -Manejador_formulario.php
	
if(isset($_POST['submitted'])) {

		
	if(empty($_POST['ciudad'])){
		
		$errores[]='Olvido introducir la ciudad de origen';
				
		}
	if(empty($_POST['calle'])){
		
		$errores[]='Olvido introducir la calle de origen';
	
		}
	if(empty($_POST['numero'])){
		
		$errores[]='Olvido introducir el numero de origen';
						
		}
	
	if(empty($_POST['ciudad2'])){
		
		$errores[]='Olvido introducir la ciudad de destino';
				
		}
	if(empty($_POST['calle2'])){
		
		$errores[]='Olvido introducir la calle de destino';
				
		}
	if(empty($_POST['numero2'])){
		
		$errores[]='Olvido introducir el numero de destino';

		}


	if(empty($errores)){
	

	
	$ca=trim($_POST['calle']);
	$num=trim($_POST['numero']);
	$ci=trim($_POST['ciudad']);
	
	$ca2=trim($_POST['calle2']);
	$num2=trim($_POST['numero2']);
	$ci2=trim($_POST['ciudad2']);

	
	$info=$_POST['info'];
	$medio=$_POST['medio'];

	
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
	$code=$request->getCodigo();

	

	$direccion2=$ca2." ".$num2;
	$ciudad2=$ci2;	
	$servicio="GoogleM";	

	require_once('REST.php');
	
	$parametros['direccion']=$direccion2;
	$parametros['ciudad']=$ciudad2;
	$parametros['servicio']=$servicio;
	$servicio="geosearch";	


	$request=new RestRequest(null, null,$servicio, $parametros);
	$code=$request->getCodigo();
	
	$lat2=$request->getLatitud();
	$lon2=$request->getLongitud();
	$code2=$request->getCodigo();



	$pos=$lat.",".$lon;
	$pos2=$lat2.",".$lon2;

	


	if($info==2)
	{$INFO="EXTRA";}
	else{$INFO="INFO";}
	
	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/routing/".$INFO."/".$pos.",".$pos2."/".$medio.".js?lang=es";

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
        <h1>Probar servicio Geocoding</h1>

	<?php
	if(isset($_POST['submitted'])&&(empty($errores))) {

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

	
	<form enctype="multipart/form-data" action="servicioRou.php" method="post">
		
	<fieldset><legend>Introducir la informacion de la posicion del supuesto termianl movil</legend>
	<br>
	

	<fieldset ><legend>Posición inicial:</legend>

	<p><b>Ciudad:</b> <input type="text" name="ciudad" size="40" value="gijon"/></p>
	
	<p><b>Calle:</b> <input type="text" name="calle" size="40" value="calle de los moros"/></p>
	
	<p><b>Numero:</b> <input type="text" name="numero" size="40" value="10"/></p>
	
	</fieldset><br>
	
	<fieldset ><legend>Posición final:</legend>

	<p><b>Ciudad:</b> <input type="text" name="ciudad2" size="40" value="gijon"/></p>
	
	<p><b>Calle:</b> <input type="text" name="calle2" size="40" value="Marques de casa valdes"/></p>
	
	<p><b>Numero:</b> <input type="text" name="numero2" size="40" value="14"/></p>
	
	</fieldset><br>

	
	
	<legend>Medio que se empleará para recorrer la ruta:</legend><br>
	<select name="medio">
	
	<option value="car">En coche </option>
	
	<option value="foot">A pie </option>
	
	<option value="bicycle">en bicicleta </option>	
	</select></p>	
	<br><br>
	

	
	<div align="center"><input type="submit" name="submit" value="Probar Servicio Geocoding" /></div>
	<input type="hidden" name="submitted" value="1" />
	
	<br>
	<legend>Los campos marcados con un ** indican que son opcionales</legend>
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