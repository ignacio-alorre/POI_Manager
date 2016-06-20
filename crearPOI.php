<?php # Script 2.2 -Manejador_formulario.php
	
	session_start();

$categoria=$_SESSION['administrador'];

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


	
	if(isset($_POST['submitted'])){
	
	
	if(empty($_POST['nombre'])){
		
		$errores[]='Olvido introducir el nombre';
		}else{
				$nb=trim($_POST['nombre']);
		}
	
	if(empty($_POST['tipo'])){
		
		$errores[]='Olvido introducir el tipo';
		}else{			
				$tp=trim($_POST['tipo']);
			

			switch($tp){

				case "hotel":
				$servicios="";
				if(!empty($_POST['B&B'])){$servicios.="Bed&Breakfast/";}
				if(!empty($_POST['Parking'])){$servicios.="Parking/";}
				if(!empty($_POST['Wifi'])){$servicios.="Wifi/";}
				if(!empty($_POST['Piscina'])){$servicios.="Piscina/";}
				if(!empty($_POST['Animacion'])){$servicios.="Servicio de animacion/";}
				break;
				case "restaurante":
				$servicios="";
				if(!empty($_POST['ServicioDomicilio'])){$servicios.="Servicio a domicilio/";}
				if(!empty($_POST['PPV'])){$servicios.="PPV/";}
				if(!empty($_POST['Parkingprivado'])){$servicios.="Parking privado/";}
				if(!empty($_POST['Menuinfantil'])){$servicios.="Menu infantil/";}
				if(!empty($_POST['Menuvegetariano'])){$servicios.="Menu vegetariano/";}
				if(!empty($_POST['Bodas'])){$servicios.="Salon especial para bodas/";}
				break;
				case "bar":
				$servicios="";
				if(!empty($_POST['ServicioDomicilio'])){$servicios.="Servicio a domicilio/";}
				if(!empty($_POST['PPV'])){$servicios.="PPV/";}
				if(!empty($_POST['Parkingprivado'])){$servicios.="Parking privado/";}
				if(!empty($_POST['Menudia'])){$servicios.="Menu del dia/";}
				if(!empty($_POST['Sidreria'])){$servicios.="Sidreria/";}
				if(!empty($_POST['TakeAway'])){$servicios.="Menus para llevar/";}
				break;
				case "pub":
				$servicios="";
				if(!empty($_POST['billar'])){$servicios.="Billar/";}
				if(!empty($_POST['Pistabaile'])){$servicios.="Pista de baile/";}
				if(!empty($_POST['cocteles'])){$servicios.="Carta de cocteles/";}
				if(!empty($_POST['chupitos'])){$servicios.="Carta de chupitos/";}
				if(!empty($_POST['karaoke'])){$servicios.="Karaoke/";}
				break;

				}

				if(!empty($servicios))$servicios = substr ($servicios, 0, -1);	
			}

	if(empty($_POST['ciudad'])){
		
		$errores[]='Olvido introducir la ciudad';
		}else{
				$ci=trim($_POST['ciudad']);
		}
	
	if(empty($_POST['calle'])){
		
		$errores[]='Olvido introducir la calle';
		}else{
				$ca=trim($_POST['calle']);
		}
	if(empty($_POST['descripcion'])){
		
		$errores[]='Olvido introducir la descripcion';
		}else{
				$descripcion=trim($_POST['descripcion']);
		}
	
	if(empty($_POST['numero'])){
		
		$num=null;
		}else{
				$num=trim($_POST['numero']);
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
		
			switch($tp){
			
				case 'hotel':$image=imagecreatefromjpeg("../imagenes/hotel.jpg}"); break;
				case 'restaurante':$image=imagecreatefromjpeg("../imagenes/restaurante.jpg"); break;
				case 'pub':$image=imagecreatefromjpeg("../pub.jpg"); break;
				case 'bar':$image=imagecreatefromjpeg("../imagenes/bar.jpg"); break;

			}
			ob_start();
			imagejpeg($image);
			$jpg=ob_get_contents();
			ob_end_clean();

			
			$jpg=str_replace('##','##',mysql_escape_string($jpg));
		


		
		}
		 
		 //Chech for error:
		 
		/* if($_FILES['upload']['error']>0){
		 
			echo '<p class="error">The file could not be uploaded becouse: <strong>';
			
			switch($_FILE['upload']['error']){
			
				case 1:
				$errores[]='The file exceeds the upload_max_filesize setting in php.ini.';				
				break;
				
				case 2:
				$errores[]='The file exceeds the MAX_FILE_SIZE setting in the HTML form.';			
				break;
				
				case 3:				
				$errores[]='The file was only partially uploaded.';			
				break;
				
				case 4:
				$errores[]='No file was uploaded.';
				break;
				
				case 6:				
				$errores[]='Not temporary folder was available';
				break;
				
				case 7:				
				$errores[]='Unable to write to the disk.';				
				break;
				
				case 8:				
				$errores[]='File upload stopped.';
				break;
				
				default:				
				$errores[]='A system error occurred.';
				break;
		 }
		 
		 print '</strong></p>';
		 
		 }
		 
		 if(file_exists($_FILES['upload']['tmp_name'])&&is_file($_FILES['upload']['tmp_name'])){
		 
		 unlink($_FILES['upload']['tmp_name']);
		 
		 }
		*/
		
	$us=$_SESSION['usuario_id'];	
	
	if(empty($errores)){

	//Aqui habra que obtener tanto la longitud como la latitud	

	
	$direccion=$ca." ".$num;
	$ciudad=$ci;
	
	$servicio="GoogleM";

	
	require_once('REST.php');
	
	//este es el objeto con el que iremos trasteando

	$parametros['direccion']=$direccion;
	$parametros['ciudad']=$ciudad;
	$parametros['servicio']=$servicio;
	$servicio="geosearch";

	$request=new RestRequest(null, null,$servicio, $parametros);
	$code=$request->getCodigo();

	if($code!="200")
	{
		$request->flush();	
		$parametros['servicio']="Yahoo";
		require_once('REST.php');
		$request=new RestRequest(null, null,$parametros,$servicio);
		$code=$request->getCodigo();				
	}
	
	if($code!="200")
	{
		$request->flush();	
		$parametros['servicio']="CloudM";
		require_once('REST.php');
		$request=new RestRequest(null, null,$parametros,$servicio);
		$code=$request->getCodigo();	
		
	}
	
	$code=$request->getCodigo();

	
	if($code!="200"){echo "No podemos localizar la posicion de su establecimiento, por favor revise la direccion que ha introducido o introduzca una direccion cercana";}
	else{
	//echo '<pre>'.print_r($request, true).'</pre>';

	//SOLO EJECUTARLO EN CASO DE QUE OBTENGAMOS UN CODIGO 200
	$lat=$request->getLatitud();
	$lon=$request->getLongitud();
	$Direccion=$request->getDireccion();

	
	echo "La direccion del POI que va a ser registrado es:".$Direccion."<br>";

	require_once('../mysql_conexion.php');
	
	$q="INSERT INTO POI(nombre, tipo, ciudad, calle, numero, creador, latitud, longitud, direccion, descripcion, servicios, imagen) VALUES ('$nb', '$tp', '$ci', '$ca', '$num', '$us', '$lat', '$lon', '$Direccion', '$descripcion', '$servicios', '$jpg')";
	$r=@mysqli_query($dbc, $q);

	
	 
	if($r){
	
	require_once('funciones_login.php');
	$url=url_absoluta('gestorPOI.php');
	
	header("Location: $url");	
	
	exit();
	
	
	}else{
		echo '<h1>Error en el sistema </h1>
		<p class="error">No se ha podido registrar debido a un error en el sistema. Disculepn las molestias</p>';
	
	//depuramos el mensaje de error
	
	echo '<p>'.mysqli_error($dbc).'<br /><br />Query:'.$q.'</p>';
	
	}
	
	//cerramos el codigo php con las siguientes instrucciones
	
	//mysqli_close($dbc);
	
	//include('includes/footer.html');
	
	//exit();
		}
	
		
	
	}//fin de empty(errores)
	

	}//fin del isset($_POST['submitted'])
	
	
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
        <li><a href="servicios.php">Servicios</a></li>
	<li><a id="selected" href="gestorPOI.php">Gestor de POI</a></li>
        <?php if($categoria==1)echo '<li><a href="gestorRutas.php">Gestor de Rutas</a></li>'; ?>
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
        <h1>Crear Punto de Interés</h1>

	<form enctype="multipart/form-data" action="crearPOI.php" method="post">
	
	<input type="hidden" name="MAX_FILE_SIZE" value="524288">	

	<fieldset><legend>Introduce la informacion correspondiente al nuevo pude interes:</legend>
	
	<p><b>Nombre:</b> <input type="text" name="nombre" size="20" maxlength="40" /></p>
	
	<fieldset><legend>Tipo de Punto de Interés:</legend>

	
   	<script language="javascript" type="text/javascript">
 
        function showOptions(a,b,c,d){
   
              	document.getElementById(a).style.display = 'block';
   
              	document.getElementById(b).style.display = 'none';
		document.getElementById(c).style.display = 'none';
		document.getElementById(d).style.display = 'none';
 
        }
  
      </script>
 
      </head>
 
      <body>

      <form action="routing.php" method="post">

            <select name="tipo">
  
             <option value="restaurante" onclick="showOptions('restaurante','bar','hotel','pub');">Restaurante</option>
 
             <option value="hotel" onclick="showOptions('hotel','bar','restaurante','pub');">Hotel</option>

	     <option value="bar" onclick="showOptions('bar','restaurante','hotel','pub');">Bar</option>

	     <option value="pub" onclick="showOptions('pub','bar','restaurante','hotel');">Pub</option>
  
            </select>
  
            <div id="hotel" style="display:none;">
         	
	<INPUT TYPE="CHECKBOX" NAME="B&B" value="B&B">Bed&Breakfast<BR>
 	<INPUT TYPE="CHECKBOX" NAME="Parking" value="Parking">Parking privado<BR>
	<INPUT TYPE="CHECKBOX" NAME="Wifi" value="Wifi">Wifi<BR>
	<INPUT TYPE="CHECKBOX" NAME="Piscina" value="Piscina">Piscina<BR>
	<INPUT TYPE="CHECKBOX" NAME="Animacion" value="Animacion">Servicio de Animacion<BR>

            </div>

            <div id="restaurante" style="display:none;">

	
	<INPUT TYPE="CHECKBOX" NAME="ServicioDomicilio" value="ServicioDomicilio">Servicio a domicilio<BR>
 	<INPUT TYPE="CHECKBOX" NAME="PPV" value="PPV">PPV<BR>
	<INPUT TYPE="CHECKBOX" NAME="Parkingprivado" value="Parkingprivado">Parking privado<BR>
        <INPUT TYPE="CHECKBOX" NAME="Menuinfantil" value="Menuinfantil">Menu infantil<BR>
	<INPUT TYPE="CHECKBOX" NAME="Menuvegetariano" value="Menuvegetariano">Menu vegetariano<BR>
	<INPUT TYPE="CHECKBOX" NAME="Bodas" value="Bodas">Salon especial para bodas<BR>

           </div>

	<div id="bar" style="display:none;">

	
	<INPUT TYPE="CHECKBOX" NAME="ServicioDomicilio" value="ServicioDomicilio">Servicio a domicilio<BR>
 	<INPUT TYPE="CHECKBOX" NAME="PPV" value="PPV">PPV<BR>
	<INPUT TYPE="CHECKBOX" NAME="Parkingprivado" value="Parkingprivado">Parking privado<BR>
        <INPUT TYPE="CHECKBOX" NAME="Menudia" value="Menudia">Menu del dia<BR>
	<INPUT TYPE="CHECKBOX" NAME="Sidreria" value="Sidreria">Sidreria<BR>
	<INPUT TYPE="CHECKBOX" NAME="TakeAway" value="TakeAway">Menus para llevar<BR>

           </div>


	<div id="pub" style="display:none;">

	
	<INPUT TYPE="CHECKBOX" NAME="billar" value="billar">Billar<BR>
 	<INPUT TYPE="CHECKBOX" NAME="Pistabaile" value="Pistabaile">Pista de baile<BR>
	<INPUT TYPE="CHECKBOX" NAME="cocteles" value="cocteles">Carta de cocteles<BR>
        <INPUT TYPE="CHECKBOX" NAME="chupitos" value="chupitos">Carta de chupitos<BR>
	<INPUT TYPE="CHECKBOX" NAME="karaoke" value="karaoke">karaoke<BR>

           </div>

	
	</fieldset>
		
	<fieldset ><legend>Localizacion:</legend>
	
	<p><b>Ciudad:</b> <input type="text" name="ciudad" size="40" /></p>
	
	<p><b>Calle:</b> <input type="text" name="calle" size="40" /></p>
	
	<p><b>Numero:</b> <input type="text" name="numero" size="40" /></p>
	
	<p><b>Descripcion:</b> <input type="text" name="descripcion" size="400" /></p>
	
	</fieldset>
	<fieldset><legend>Selecciona una imagen JPEG or PNG con un tamaño menor o igual a 512KB:</legend>
		 
		 <p><b>File:</b><input type="file" name="upload"/></p>

	
	</fieldset>
	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Crear nuevo punto de Interes" /></div>
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

