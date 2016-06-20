<?php # Funciones_login.inc.php

//Vamos a definir dos funciones para el proceso de login/logout

//funcion para conseguir la url absoluta de la pagina a la que queremos acceder

function url_absoluta($page='index.php'){

$url='http://' .$_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

$url=rtrim($url, '/\\');

$url.= '/' . $page;

//echo $url;//proceso de revision

return $url;

}

//funcion para contrastar los datos introducidos por el usuairo con los almacenados en al base de datos

function check_login($dbc, $usuario='', $clave=''){

$errores=array();

if(empty($usuario)){

	$errores[]='Usted olvido introducir el nombre de usuario.';

	}else{

	$us=mysqli_real_escape_string($dbc,trim($usuario));

				}

if(empty($clave)){

	$errores[]='Usted olvido introducir la clave.';

	}else{

	$cl=mysqli_real_escape_string($dbc,trim($clave));

				}
				
		if(empty($errores)){
		
		$q="SELECT gestor_id, administrador, nombre FROM GESTORES WHERE clave='$cl' AND usuario='$us'";
		
		$r=@mysqli_query($dbc,$q);
		
		if(mysqli_num_rows($r)==1){
		
		$row=mysqli_fetch_array($r,MYSQLI_ASSOC);
		
		return array(true, $row);
		
		}else{
		
		$errores[]='El usuario y la clave no concuerdan con los de ningun usuario registrado.';
		
		}
		
		}
		
		return array(false, $errores);
		
		}
		
?>