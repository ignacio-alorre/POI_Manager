<?

	session_start();

if(!isset($_SESSION['usuario_id'])){

	require_once('funciones_login.php');
	
	$url=url_absoluta('login.php');
	
	header("Location: $url");
	
	exit();
	}


require_once('../mysql_conexion.php');

$q="SELECT nombre, POI_id FROM POI WHERE usuario='".$_SESSION['usuario_id']."'";

$r=@mysqli_query($dbc, $q);

$tipo=$_GET['val'];

if($tipo==1)
{

while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){

echo '<tr><td align="left"><a href="modificarPOI.php?val=' .$row['nombre']. '">'.$row['nombre'].'</a></td></tr><br>';}

mysqli_close($dbc);

}else{
while ($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){

echo '<tr><td align="left"><a href="eliminarPOI.php?val=' .$row['nombre']. '&id='.$row['POI_id'].'">'.$row['nombre'].'</a></td></tr><br>';}

mysqli_close($dbc);

}

echo '<tr><td align="left"><a href="menu.php">Volver al menu</a></td></tr><br>';


	
?>