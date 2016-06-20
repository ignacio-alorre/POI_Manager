<?

session_start();
	
if(!isset($_SESSION['usuario_id'])){

	require_once('funciones_login.php');
	
	$url=url_absoluta('login.php');
	
	header("Location: $url");
	
	exit();
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

<html>



<form enctype="multipart/form-data" action="crearRutas.php" method="post">
	
<input type="hidden" name="MAX_FILE_SIZE" value="524288">	

<fieldset><legend>Introduce la informacion correspondiente a la nueva Ruta Temática:</legend>
	
<p><b>Nombre de la ruta tematica:</b> <input type="text" name="nombre" size="20" maxlength="40" /></p>

<p><b>Ciudad:</b> <input type="text" name="ciudad" size="20" maxlength="40" /></p>


<fieldset><legend>Puntos de Interes que van a pertenecer a la Ruta Tématica:</legend>
<?
			require_once('../mysql_conexion.php');
	
			$q="SELECT nombre,poi_id FROM POI";
			$r=@mysqli_query($dbc, $q);
			
			$cont=0;
			while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){
						
			echo '<INPUT TYPE="CHECKBOX" NAME="'.$cont.'" value="'.$row[poi_id].'">'.$row[nombre].'                     |';

			if($cont%4==0){echo '<BR>';}
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

</html>