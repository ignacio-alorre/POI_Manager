<?php # Script 2.2 -Manejador_formulario.php
	
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

	
	
	if(isset($_POST['submitted'])){
	
	
	if(!empty($_POST['nombre'])){
		
		
				$nb=trim($_POST['nombre']);
		}
	
	if(!empty($_POST['tipo'])){
		

				$tp=trim($_POST['tipo']);
		}
	
	if(!empty($_POST['ciudad'])){
		

				$ci=trim($_POST['ciudad']);
		}
	
	if(!empty($_POST['calle'])){
		

				$ca=trim($_POST['calle']);
		}
	
	if(!empty($_POST['numero'])){
		

				$num=trim($_POST['numero']);
		}

	if(!empty($_POST['descripcion'])){
		

				$desc=trim($_POST['descripcion']);
		}

	
	if(empty($errores)){
	

	require_once('../mysql_conexion.php');
	
	$q="UPDATE POI SET nombre='$nb', tipo='$tp', ciudad='$ci', calle='$ca', numero='$num', descripcion='$desc'  WHERE nombre='$nb' ";
	
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
	
	}else{
	
	echo '<h1>Error!</h1><p class="error">Han ocurrido los siguientes errores:<br />';
	foreach($errores as $msg){
	
	echo "-$msg<br />\n";
	
	}
	
	echo '</p><p>Porfavor intentelo de nuevo.</p><p><br /></p>';
	
	
	}//fin de empty(errores)
	
	exit();
	
	}//fin del isset($_POST['submitted'])
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
  <title>template: page 2</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

  <!-- **** layout stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="style/style.css" />

  <!-- **** colour scheme stylesheet **** -->
  <link rel="stylesheet" type="text/css" href="style/colour.css" />

</head>

<body>
  <div id="main">
    <div id="links">

      <a href="logout.php">Cerrar Sesión</a>
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
        <h1>Modificar Punto de Interes</h1>

		<form action="modificarPOI.php" method="post">
	
	
	
	<fieldset><legend>Introduce la informacion correspondiente al nuevo punto de interes:</legend>
	
	<p><b>Nombre:</b> <input type="text" name="nombre" size="20" maxlength="40" value="<?php echo $_GET['val'];?>"/></p>
	
	<p><b>Tipo:</b>
	<select name="tipo">
	
	<option value="Restaurante">Restaurante </option>
	
	<option value="Hotel">Hotel</option>
		
	<option value="Pub">Pub</option>
	
	<option value="Bar">Bar</option>
	

	
	</select></p>
		
	<fieldset ><legend>Localizacion:</legend>
	
	<p><b>Ciudad:</b> <input type="text" name="nombre" size="40" value="<?php require_once('../mysql_conexion.php');  $q="SELECT nombre FROM POI WHERE nombre='".$_GET['val']."' "; $r=@mysqli_query($dbc, $q);  $row=mysqli_fetch_array($r,MYSQLI_ASSOC);  echo $row['nombre'];  ?>" /></p>
	
	<p><b>Ciudad:</b> <input type="text" name="ciudad" size="40" value="<?php require_once('../mysql_conexion.php');  $q="SELECT ciudad FROM POI WHERE nombre='".$_GET['val']."' "; $r=@mysqli_query($dbc, $q);  $row=mysqli_fetch_array($r,MYSQLI_ASSOC);  echo $row['ciudad'];  ?>" /></p>
		
	<p><b>Calle:</b> <input type="text" name="calle" size="40" value="<?php require_once('../mysql_conexion.php');  $q="SELECT calle FROM POI WHERE nombre='".$_GET['val']."' "; $r=@mysqli_query($dbc, $q);  $row=mysqli_fetch_array($r,MYSQLI_ASSOC);  echo $row['calle'];  ?>" /></p>
	
	<p><b>Numero:</b> <input type="text" name="numero" size="40" value="<?php require_once('../mysql_conexion.php'); $q="SELECT numero FROM POI WHERE nombre='".$_GET['val']."' ";	 $r=@mysqli_query($dbc, $q);  $row=mysqli_fetch_array($r,MYSQLI_ASSOC);  echo $row['numero']; ?>"/></p>
	
	<p><b>Descripcion:</b> <input type="text" name="descripcion" size="400" value="<?php require_once('../mysql_conexion.php'); $q="SELECT descripcion FROM POI WHERE nombre='".$_GET['val']."' ";	 $r=@mysqli_query($dbc, $q);  $row=mysqli_fetch_array($r,MYSQLI_ASSOC);  echo $row['descripcion']; ?>"/></p>
	
	
	</fieldset>
	</fieldset>
	
	<div align="center"><input type="submit" name="submit" value="Modificar punto de Interes"/></div>
	
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

	
	