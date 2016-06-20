<?php # Script 2.2 -Manejador_formulario.php
	
if(isset($_POST['submitted'])) {

	if(empty($_POST['ciudad'])){
		
		$errores[]='Olvido introducir la ciudad';
		}else{
				$nb=trim($_POST['ciudad']);
		}
	if(empty($_POST['calle'])){
		
		$errores[]='Olvido introducir la calle';
		}else{
				$nb=trim($_POST['calle']);
		}
	if(empty($_POST['numero'])){
		
		$errores[]='Olvido introducir el numero';
		}else{
				$nb=trim($_POST['numero']);
		}
	


	if(empty($errores)){

	$ca=trim($_POST['calle']);
	$num=trim($_POST['numero']);
	$ci=trim($_POST['ciudad']);
	
	$tipo=$_POST['tipo'];
	$radio=$_POST['radio'];
	$perimetro=$_POST['perimetro'];
	$info=$_POST['info'];
	$limite=$_POST['limite'];
	$nombre=$_POST['nombre'];
	
	
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
	$direccion=$request->getDireccion();
	
	$pos=$lat.",".$lon;
	
	//depurar
	$sur=$lat-$radio;
	$oeste=$lon-$radio;
	$norte=$lat+$radio;
	$este=$lon+$radio;
	$bbox=$sur.",".$oeste.",".$norte.",".$este;

	$param="";
	
	if($perimetro=="circular"){
	$param.="around=".$pos."&distance=".$radio;
	
	$param.="&object_type=".$tipo;
	
	if((!empty($nombre))&&(!empty($limite))){
	$param.="&query=".$nombre."&result=".$limite;
	}else{
	if(!empty($limite)){
	$param.="&result=".$limite;
	}
	if(!empty($nombre)){
	$param.="&query=".$nombre;
	}
	}
	
	}else{
	$param.="bbox=".$lat-$radio.",".$lon-$radio.",".$lat+$radio.",".$lon+$radio;
	$param.="&object_type=".$tipo;
	
	if((!empty($nombre))&&($limite>0)){
	$param.="&query=".$nombre."&result=".$limite;
	}else{
	if($limite>0){
	$param.="&result=".$limite;
	}
	if(!empty($nombre)){
	$param.="&query=".$nombre;
	}
	
	}

	}


	if($info==2)
	{$INFO="EXTRA";}
	else{$INFO="INFO";}
	
	$url="http://turismopfc09.webatu.com/ServicioWeb.php/9m0u42fpby29y0021a4qv/geocoding/".$INFO."/get.js?".$param;

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



	<div id="column2">
        <h1>Probar servicio Geocoding</h1>

	<?php
	if(isset($_POST['submitted'])&&(empty($errores))) {

	$respuesta=json_decode($respuesta);
	
	$aux=$respuesta->{'Usuario'};
	$aux2=$aux->{'Posicion'};
	$Latitud=$aux2->{'Latitud'};
	$Longitud=$aux2->{'Longitud'};
	$Encontrados=$respuesta->{'Encontrados'};
	$Pois=$respuesta->{'POIs'};

	echo 'La posicion del Usuario es:<br><br>-Longitud:'.$Latitud.'<br>-Latitud:'.$Longitud.'<br><br>';
	echo 'Se han encontrado '.$Encontrados.' puntos de interes que siguen las condiciones introducidas por el usuario<br><br>';

	foreach($Pois as $value)
	{
		$Datos=$value->{'Datos del POI'};
		$valoracion=$value->{'valoracion de los usuarios'};
		$nombre=$Datos->{'Nombre'};
		$Identificador=$Datos->{'POI_id'};
		$latitud=$Datos->{'Latitud'};
		$longitud=$Datos->{'Longitud'};
		$direccion=utf8_decode($Datos->{'Direccion'});
		$distancia=$Datos->{'Distancia'};
		$descripcion=$Datos->{'Descripcion'};
		$imagen=$Datos->{'Imagen'};
	
		echo 'Los datos del Punto de Interés son:<br><br>-Nombre: '.$nombre.'<br>-Identificador: '.$Identificador.'<br>-Latitud: '.$latitud.'<br>-Longitud: '.$longitud.'<br>-Direccion: '.$direccion.'<br>-Distancia: '.$distancia.'<br>-Descripcion: '.$descripcion.'<br>-Imagen: ';
		
		echo '<span class="center"><img ALIGN="CENTER"HEIGHT="150" WIDHT="170" src="data:image/jpeg;base64,'.$imagen.'" /></span><br><br>';

		$votos=$valoracion->{'votos'};
		$empleados=$valoracion->{'empleados'};
		$emplazamiento_comunicacion=$valoracion->{'emplazamiento_comunicacion'};
		$factor_diversion=$valoracion->{'factor_diversion'};
		$calidad_precio=$valoracion->{'calidad_precio'};
		$limpieza=$valoracion->{'limpieza'};
		$puntuacion=$valoracion->{'puntuacion'};
		
		echo 'Las valoraciones que el Punto de Interés ha recibido por parte de otro usuarios son:<br><br>-Votos: '.$votos.'<br>-Empleados: '.$empleados.'<br>-Emplazamiento/comunicacion: '.$emplazamiento_comunicacion.'<br>-Factor Diversion: '.$factor_diversion.'<br>-Calidad/Precio: '.$calidad_precio.'<br>-Limpieza: '.$limpieza.'<br>-Puntuacion: '.$puntuacion.'<br><br>';


	}
	

	exit();

	}


	?>

	
	<form enctype="multipart/form-data" action="servicioGeo.php" method="post">
		
	<fieldset><legend>Introducir la informacion de la posicion del supuesto termianl movil</legend>
	<br>
	<legend>Tipo del Punto de Interés:</legend><br>
	<select name="tipo">
	
	<option value="Restaurante">Restaurante </option>
	
	<option value="Hotel">Hotel</option>
	
	<option value="Bar">Bar</option>
	
	<option value="Pub">Pub</option>
	
	</select><br><br>

	<fieldset ><legend>Localizacion:</legend>

	<p><b>Ciudad:</b> <input type="text" name="ciudad" size="40" value="gijon" /></p>
	
	<p><b>Calle:</b> <input type="text" name="calle" size="40" value="ezcurdia"/></p>
	
	<p><b>Numero:</b> <input type="text" name="numero" size="40" value="12"/></p>
	
	</fieldset><br>
	
	<p><b>**Nombre o parte del nombre:</b> <input type="text" name="nombre" size="40" /></p>
	
	
	<legend>Distancia que esta dispueto a recorrer el usuario para llegar hasta un Punto de Interés desde su posición actual:</legend><br>
	<select name="radio">
	
	<option value="200">200 metros </option>
	
	<option value="500">500 metros</option>
	
	<option value="1000">1 kilometros</option>
	
	<option value="2000">2 kilometros</option>
	
	<option value="4000">4 kilometros</option>
	
	
	</select></p>	
	<br><br>

	<legend>**Restringir el número de Puntos de Interés devuelto por el servicio a:</legend><br>
	<select name="limite">
	
	<option value="0">Devolver todos los Puntos de Interés que estén dentro del perímetro de busqueda</option>
	
	<option value="1">1 Punto de Interés </option>
	
	<option value="2">2 Puntos de Interés</option>
	
	<option value="3">3 Puntos de Interés</option>
	
	<option value="4">4 Puntos de Interés</option>
	
	
	
	</select></p><br><br>
	
	<legend>Seleccionar la forma que tendrá el perímetro de busqueda alrededor de la posición del usuario:</legend><br>
	<select name="perimetro">
	
	<option value="circular">Perimetro de búsqueda circular</option>
	
	<option value="cuadrado">Perimetro de búsqueda cuadrado</option>

	</select></p><br><br>	


		<legend>Seleccione si desea recibir solo la informacion de los Puntos de Interés o también las imganese de estos:</legend><br>	
	<select name="info">
	
	<option value="1">Solo la información</option>
	
	<option value="2">La información y las imágenes</option>
	
	</select></p><br><br>
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