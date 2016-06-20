<?php

$uri=$_SERVER['REQUEST_URI'];
$chopped=explode("/",$uri);
$APIKEY=$chopped[2];
$servicio=$chopped[3];
$extra=$chopped[4];
	
require_once('../mysql_conexion.php');
	
	$parametros=array();
	$errores=array();

		require_once('REST.php');
		$request= new RestRequest($APIKEY, $dbc);	
		$check=$request->CHECK();

if($check){

switch($servicio){

	case'geocoding':
				
		if(empty($_GET[bbox])&&(empty($_GET[around])||empty($_GET[distance])))
		{

			$errores[]="No pueden estar vacios los campos bbox y around o distance";

		}else{
		
		if(!empty($_GET[bbox]))
		{
			$parametros['bbox']=$_GET['bbox'];
		}else{

			$parametros['around']=$_GET['around'];
			$parametros['distance']=$_GET['distance'];

		}

		}
		
		if(!empty($_GET[object_type]))
		{	

			$parametros['object_type']=$_GET['object_type'];

		}else{

			$errores[]="El campo object_type No puede estar vacio"; 

		}

		if(!empty($_GET['result']))
		{
			$parametros['result']=$_GET['result'];
		}

		if(!empty($_GET['query']))
		{
			$parametros['query']=$_GET['query'];
		}
		
		require_once('REST.php');
		
		if(($extra!="INFO") && ($extra!="EXTRA"))
		{$errores[]="Hay un error en la construccion de la URL, parametro 4 mal introducido";}

		if(empty($errores)){
		
		if($extra=="INFO"){$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		}else{
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros,TRUE);
		}
			
		echo $request->GetResponseBody();

		}else{

			echo "errores";//modificar
		}
	
	break;
	
	case'routing':
	
	if(empty($chopped[5]))
	{
			$errores[]="hay que indicar las coordenadas del punto inicial y final";
	}else{
	
		$parametros['position']=$chopped[5];
	}
	
	if(empty($chopped[6]))
	{
			$errores[]="debes introducir el tipo de ruta";
	}else{
		$parametros['route_type']=$chopped[6];
	}
	
	if(!empty($chopped[7])){$parametros['route_type_modifier']=$chopped[7];}
	
	require_once('REST.php');
		
		if(($extra!="INFO") && ($extra!="EXTRA"))
		{$errores[]="Hay un error en la construccion de la URL, parametro 4 mal introducido";}

		if(empty($errores)){
		
		if($extra=="INFO"){$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		}else{
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros,TRUE);
		}
			
		echo $request->GetResponseBody();

		}else{

			echo "errores";//modificar
		}
	
		

	break;
	
	case'evaluation':
	
	if(!empty($_GET['eleccion']))
		{	

			$parametros['eleccion']=$_GET['eleccion'];

		}else{

			$errores[]="El campo eleccion No puede estar vacio"; 

		}
	if(!empty($_GET['empleados']))
		{	

			$parametros['empleados']=$_GET['empleados'];

		}else{

			$errores[]="El campo empleados No puede estar vacio"; 

		}
	if(!empty($_GET['emplazamiento_comunicacion']))
		{	

			$parametros['emplazamiento_comunicacion']=$_GET['emplazamiento_comunicacion'];

		}else{

			$errores[]="El campo emplazamiento_comunicacion No puede estar vacio"; 

		}
	if(!empty($_GET['factor_diversion']))
		{	

			$parametros['factor_diversion']=$_GET['factor_diversion'];

		}else{

			$errores[]="El campo factor_diversion No puede estar vacio"; 

		}
	if(!empty($_GET['calidad_precio']))
		{	
			$parametros['calidad_precio']=$_GET['calidad_precio'];

		}else{

			$errores[]="El campo calidad_precio No puede estar vacio"; 

		}
	if(!empty($_GET['limpieza']))
		{	

			$parametros['limpieza']=$_GET['limpieza'];

		}else{

			$errores[]="El campo limpieza No puede estar vacio"; 

		}
		
		require_once('REST.php');
		
		if(($extra!="INFO") && ($extra!="EXTRA"))
		{$errores[]="Hay un error en la construccion de la URL, parametro 4 mal introducido";}

		if(empty($errores)){
		
		if($extra=="INFO"){$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		}else{
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros,TRUE);
		}
			
		echo $request->GetResponseBody();

		}else{

			echo "errores";//modificar
		}
	break;
	
	case'tematicas':
	
		if((empty($_GET['ciudad']))&&(empty($_GET['eleccion'])))
		{	
			$errores[]="El campo limpieza No puede estar vacio";
			$parametros['limpieza']=$_GET['limpieza'];

		}else{
		if(!empty($_GET['ciudad'])){
			$parametros['ciudad']=$_GET['ciudad']; 
		}else{
			$parametros['eleccion']=$_GET['eleccion']; 
		}		
		}		
		
		require_once('REST.php');
		
		if(($extra!="INFO") && ($extra!="EXTRA"))
		{$errores[]="Hay un error en la construccion de la URL, parametro 4 mal introducido";}

		if(empty($errores)){
		
		if($extra=="INFO"){$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		}else{
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros,TRUE);
		}
			
		echo $request->GetResponseBody();

		}else{

			echo "errores";//modificar
		}


	break;
	
	case'path':
				
	
		if((empty($_GET['nombre']))&&(empty($_GET['eleccion'])))
		{	
			$errores[]="No puede estar vacios tanto el parametro nombre como eleccion";
		}else{
		if(!empty($_GET['nombre'])){
			$parametros['nombre']=$_GET['nombre']; 
			
		require_once('REST.php');
		
		if(($extra!="INFO") && ($extra!="EXTRA"))
		{$errores[]="Hay un error en la construccion de la URL, parametro 4 mal introducido";}

		if(empty($errores)){
		
		if($extra=="INFO"){$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		}else{
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros,TRUE);
		}
			
		echo $request->GetResponseBody();

		}else{

			echo "errores";//modificar
		}



			
		}if(!empty($_GET['eleccion'])){
		
		$parametros['eleccion']=$_GET['eleccion']; 
			
		if(empty($_GET['posicion_inicial']))
		{
			$errores[]="Es necesario introducir la posicion inicial para obtener la ruta";
		}else{
		$posicion=$_GET['posicion_inicial'];
			
		}
		if(empty($_GET['tipo_ruta']))
		{
			$errores[]="Es necesario introducir el tipo_ruta que desea obetener";
		}else{
		$tipo=$_GET['tipo_ruta'];
			
		}

		require_once('REST.php');
		
		if(($extra!="INFO") && ($extra!="EXTRA"))
		{$errores[]="Hay un error en la construccion de la URL, parametro 4 mal introducido";}

		if(empty($errores)){
		
		if($extra=="INFO"){$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		}else{
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros,TRUE);
		}
			
		echo $request->GetResponseBody();

		
		$posicion_final=$request->GetPosicion();
		$parametros['position']=$posicion.','.$posicion_final;
		$parametros['route_type']=$tipo;
		$parametros['route_type_modifier']="shortest.js";
		$servicio="routing";

		require_once('REST.php');
		$request= new RestRequest($APIKEY, $dbc, $servicio,$parametros);
		echo $request->GetResponseBody();		



		}else{

			var_dump($errores);
			echo "errores";//modificar
		}
			
		





		}		
		}
		
		


			
	break;


}



}else{

echo "cliente no registrado<br>";

}


?>