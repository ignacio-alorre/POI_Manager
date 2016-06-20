<?php

class RestRequest

{

	protected $servicio;

	protected $row;
	
	protected $url;

	protected $APIKEY;

	protected $responseBody;

	protected $responseInfo;

	protected $errores;
	
	protected $imagen;
	
	protected $pois;

	protected $acceptType;
	
	protected $posicion_final;

	protected $dbc;

	public function __construct ($APIKEY=null, $dbc=null, $servicio = null, $parametros=null,  $extra=FALSE)

	{
		
		$this->dbc		=$dbc;
		
		$this->responseBody     ="";

		$this->servicio		=$servicio;

		$this->APIKEY		=$APIKEY;		

		$this->acceptType       ="JSON";
		
		switch($this->servicio)

			{

				case 'geocoding':

				$this->object_type	=$parametros['object_type'];

				$this->around		=$parametros['around'];

				$this->distance		=$parametros['distance'];

				$this->query		=$parametros['query'];

				$this->result		=$parametros['result'];

				$this->bbox		=$parametros['bbox'];
				
				$this->imagen		=$extra;

						

				$this->GEOCODING();				
					
				break;
				
				
				case 'routing':
				
				$this->position			=$parametros['position'];
				
				$this->route_type		=$parametros['route_type'];
				
				$this->route_type_modifier 	=$parametros['route_type_modifier'];
				
				$this->pois			=$extra;
				
				$this->ROUTING();
			
				
				break;
				
				
				case 'evaluation':
				
				$this->eleccion			=$parametros['eleccion'];

				$this->empleados		=$parametros['empleados'];
				
				$this->emplazamiento_comunicacion		=$parametros['emplazamiento_comunicacion'];
				
				$this->factor_diversion		=$parametros['factor_diversion'];
				
				$this->calidad_precio		=$parametros['calidad_precio'];
				
				$this->limpieza			=$parametros['limpieza'];

				$this->EVALUATION();
				
				break;
				
				
				case 'tematicas':
				
				$this->eleccion 	=$parametros['eleccion'];
					
				$this->ciudad		=$parametros['ciudad'];
				
				$this->posicion		=$parametros['posicion'];
				
				$this->imagen      	 =$extra;
				
				$this->TEMATICAS();
				
				break;
				
				case 'path':
				
				$this->eleccion 	=$parametros['eleccion'];
				
				$this->nombre 		=$parametros['nombre'];
				
				$this->posicion_final 	=$parametros['posicion_final'];
				
				$this->imagen		=$extra;
				
				$this->PATH();
				
				break;
				
				case 'geosearch':
				
				
				$this->direccion	=$parametros['direccion'];
				
				$this->ciudad		=$parametros['ciudad'];
				
				$this->servicio		=$parametros['servicio'];
				
				$this->codigo		=null;
				
				$this->latitud		=null;
				
				$this->longitud		=null;
				
				
				switch (strtoupper($this->servicio))

			{

				case 'CLOUDM':

					$this->URLCloudM();
					$this->doExecute();
					
					break;

				case 'YAHOO':

					$this->URLYahoo();
					$this->doExecute();
					$this->RespuestaYahoo();
					break;

				case 'GOOGLEM':

					$this->URLGoogleM();
					$this->doExecute();
					$this->RespuestaGoogleM();
					break;


			}


		}



		/*$direccion=utf8_encode($direccion); // te lo deja en formato universal, sin e?es ni cosas asi


		

		//formamos correctamente las URL para el servicio solicitado asi como el tipo de dato
		

		
			*/

	}



	public function flush()

	{

		$this->url			= null;

 		$this->service			= null;

		$this->acceptType		= null;

		$this->responseBody		= null;

		$this->responseInfo		= null;

		$this->longitud			= null;

		$this->latitud			= null;

		$this->Direccion		= null;

		$this->codigo			= null;	





	}

	
		protected function GEOCODING()

	{

		if(empty($this->bbox)&&(empty($this->around)||empty($this->distance)))

		{

			$errores[]="No pueden estar vacios los campos bbox y around o distance";

		}else{



		if(!empty($this->bbox))

		{

			$bbox=$this->bbox;

		}else{

			$around=$this->around;

			$distance=$this->distance;

		}

		

		}

		if(!empty($this->object_type))

		{	

			$object_type=$this->object_type;

		}else{

			$errores[]="No puede estar vacio el campo object_type"; 

		}

		if(!empty($this->result))

		{

			$result=$this->result;

		}

		if(!empty($this->query))

		{

			$query=$this->query;

		}

		

		//Preparamos los limites de busqueda

		if(!empty($bbox)){

		

		$coordenadas=explode(",",$bbox);

		$sur=$coordenadas[0];

		$oeste=$coordenadas[1];

		$norte=$coordenadas[2];

		$este=$coordenadas[3];

		//para hallar la posicion del cliente, que se supone en el centro del cuadrado creado por el BBOX

		$coef1=$norte-$sur; 

		$coef2=$oeste-$este;

		$latitud=$norte-($coef1/2);

		$longitud=$oeste-($coef2/2);	

		$posicion='{"Latitud":'.$latitud.',"Longitud":'.$longitud.'}';

		

		}else{

		

		$distance=$this->distance;

		$around=$this->around;

		$posicion_us=explode(",",$around);

		$latitud=$posicion_us[0];

		$longitud=$posicion_us[1];				

		$posicion='{"Latitud":'.$latitud.',"Longitud":'.$longitud.'}';

		

		}

		

		



		if(!empty($this->query)){

			$q="SELECT longitud,latitud,poi_id FROM POI WHERE nombre REGEXP '".$query."'";

		}else{

			

			$q="SELECT longitud,latitud,poi_id FROM POI WHERE tipo='".$object_type."'";
				

		}
		
	
			require_once('../mysql_conexion.php');
			$r=@mysqli_query($this->dbc, $q);
			$likequery=array();
			$contador=0;
	
			
		while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){



			$likequery[$contador][0]=$row['poi_id'];

			$likequery[$contador][1]=$row['latitud'];

			$likequery[$contador][2]=$row['longitud'];

			$contador+=1;

		}

		$distancias=array();

		$cont=$contador=0;



		foreach($likequery as $key){



		$latitudP=$likequery[$cont][1];

		$longitudP=$likequery[$cont][2];



		require_once('distancia.php');

		$limite=getDistance($latitud,$longitud,$latitudP,$longitudP);
		

		if(($limite<$distance)||((abs($norte)>=abs($latitudP))AND(abs($oeste)>=abs($longitudP))AND(abs($este)<=abs($longitudP))AND(abs($sur)<=abs($latitudP))))

		{	

	
				$distancias[$contador][0]=$likequery[$cont][0];

				$distancias[$contador][1]=$limite;

				$contador+=1;	
						

		}

			

		$cont+=1;

	

	}

	$num=count($distancias);

		

		//Aki tenemos en el vector distancias los POI que nos interesan.

		//Ahora vamos a ver si tenemos que devolver la informacion de todos los POI o solo la de parte de ellos, en funcion del valor result
		

	if(!empty($result)){ //IMPLICA UN NUMERO DE RESULTADOS LIMITADO	


	if($result<=$num){

		$max=$result;

	}else{

		$max=$num;

	}

	}else{

		$max=$num;

	}	

		for($i=0;$i<$max;$i++){ //lmitamos los datos enviados a "X",valor introducido por el usuario


		require_once('../mysql_conexion.php');
	
		$q="SELECT longitud,latitud,nombre,direccion,descripcion,poi_id,votos,empleados,emplazamiento_comunicacion,factor_diversion,calidad_precio,limpieza,puntuacion FROM POI WHERE poi_id='".$distancias[$i][0]."'";
		$r=@mysqli_query($this->dbc, $q);
		$row=mysqli_fetch_array($r, MYSQLI_ASSOC);

	//sino meterlo despues, a eleccion del usuario
		if($this->imagen){
		require_once('../mysql_conexion.php');
		$q="SELECT imagen FROM POI WHERE poi_id='".$distancias[$i][0]."'";
		$r=@mysqli_query($this->dbc, $q);
		$row2=mysqli_fetch_array($r, MYSQLI_ASSOC);

		$var=$row2['imagen'];
		$imagen=base64_encode($var);}

	
	$subdatos='{"Nombre":"'.$row[nombre].'","POI_id":"'.$row[poi_id].'","Latitud":'.$row[longitud].',"Longitud":'.$row[latitud].',"Direccion":"'.utf8_encode($row[direccion]).'","Distancia":'.$distancias[$i][1].',"Descripcion":"'.utf8_encode($row[descripcion]).'"';
	if($this->imagen){$subdatos.=', "Imagen":"'.$imagen.'"';}
	$subdatos.='}';
	$valoracion='{"votos":'.$row[votos].',"empleados":'.$row[empleados].',"emplazamiento_comunicacion":'.$row[emplazamiento_comunicacion].',"factor_diversion":'.$row[factor_diversion].',"calidad_precio":'.$row[calidad_precio].',"limpieza":'.$row[limpieza].',"puntuacion":'.$row[puntuacion].'}';
	
	
	if($i==0)
	{$datos.='{"Datos del POI":'.$subdatos.',"valoracion de los usuarios":'.$valoracion.'}';
	}
	else
	{$datos.=',{"Datos del POI":'.$subdatos.',"valoracion de los usuarios":'.$valoracion.'}';
	}
		}

	$datos="[".$datos."]";


		// PARTE COMUN PARA COMPONER LA RESPUESTA QUE SE LE DEVOLVERA AL USUARIO

		$usuario='{"Posicion":'.$posicion.',"Id":"'.$this->APIkey.'"}';

		$this->responseBody='{"Usuario":'.$usuario.',"Encontrados":'.$max.',"POIs":'.$datos.'}';


	}



	
	protected function ROUTING()

	{
	
	
	$this->url="http://routes.cloudmade.com/45b0e07e44de5be4a0cc7faf7442aa7d/api/0.3/".$this->position."/".$this->route_type;
	
	

	if(!empty($this->route_type_modifier))
	{
	$this->url.="/".$this->route_type_modifier;
	}else{
	//$this->url.=".js";
	}

	$this->doExecute();

$respuesta=$this->RespuestaRouting();


if($this->pois){

//consideremos de interes cualquier POI que se encuentre a menos de 70m de la ruta
$dis=70;

$lat_inicio=$respuesta[0][0];
$lon_inicio=$respuesta[0][1];
$lat_final=$respuesta[$max-1][0];
$lon_final=$respuesta[$max-1][1];
require_once('distancia.php');
$radio=getDistance($lat_inicio,$lon_inicio,$lat_final,$lon_final)*(3/4);

//Extraer todos los POI de la BBDD para ver cuales pueden estar relativamente cerca de la ruta:

	require_once('../mysql_conexion.php');
	$q="SELECT longitud,latitud,poi_id FROM POI";
	
	$r=@mysqli_query($this->dbc, $q);
	$likequery=array();
	$contador=0;
	while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){

		$likequery[$contador][0]=$row['poi_id'];
		$likequery[$contador][1]=$row['latitud'];
		$likequery[$contador][2]=$row['longitud'];
		$contador+=1;
	}//tenemos en likequery todos los POI de la BBDD ahora vamos a ver cuales estan cercanos a los puntos inicial y final de la ruta
	/*print_r($likequery);*/

	$candidatos=array();
	$cont=0;
	$contador=0;
	foreach($likequery as $key){

	$latitudP=$likequery[$cont][1];
	$longitudP=$likequery[$cont][2];

	require_once('distancia.php');
	$limite1=getDistance($lat_inicio,$lon_inicio,$latitudP,$longitudP);
	$limite2=getDistance($lat_final,$lon_final,$latitudP,$longitudP);

	
	if($limite1<$radio || $limite2<$radio)
		{
		$candidatos[$contador][0]=$likequery[$cont][0];
		$candidatos[$contador][1]=$likequery[$cont][1];
		$candidatos[$contador][2]=$likequery[$cont][2];		
		$contador+=1;	
	
		}
	$cont+=1;
	}

$max=count($candidatos);
	

//tenemos en $candidatos todos los POI que pueden ser candidatos a estar dentro de la ruta


//Para cada uno de los POI
$cont=0;
$contador=0;
$EnRuta=array();

foreach($candidatos as $key)
{


for($i=0; $i<$max-1; $i++)
{

	$latA=$respuesta[$i][0];
	$lonA=$respuesta[$i][1];
	$latB=$respuesta[$i+1][0];
	$lonB=$respuesta[$i+1][1];
	require_once('distancia.php');
	$dist=getDistance($latA,$lonA,$latB,$lonB);
	$hipt_max=sqrt(($dist*$dist)+100);
	$hipt_max+=$dis;
	
	$latPOI=$candidatos[$cont][1];
	$lonPOI=$candidatos[$cont][2];
	require_once('distancia.php');	
	$cat1=$dist=getDistance($latA,$lonA,$latPOI,$lonPOI);
	require_once('distancia.php');
	$cat2=$dist=getDistance($latB,$lonB,$latPOI,$lonPOI);

	$suma=$cat1+cat2;
	
	
	if($suma<$hipt_max)
	{
		$EnRuta[$contador]=$candidatos[$cont][0];
		$i=$max;
		$contador+=1;
	}

}

$cont+=1;

}



$max=count($EnRuta);

for($i=0;$i<$max;$i++){ 

	require_once('../mysql_conexion.php');
	
	$q="SELECT longitud,latitud,nombre,direccion,descripcion,poi_id, puntuacion FROM POI WHERE poi_id='".$EnRuta[$i]."'";
	$r=@mysqli_query($this->dbc, $q);
	$row=mysqli_fetch_array($r, MYSQLI_ASSOC);
	
	$subdatos='{"Nombre":"'.$row[nombre].'","POI_id":"'.$row[poi_id].'","Latitud":'.$row[longitud].',"Longitud":'.$row[latitud].',"Direccion":"'.utf8_encode($row[direccion]).'","Descripcion":"'.utf8_encode($row[descripcion]).'","Puntuacion":'.$row[puntuacion].'}';	
	
	
	if($i==0)
	{$datos.=$subdatos;}
	else
	{$datos.=",".$subdatos;}
	}
	$datos="[".$datos."]";
	
	
	
$lineaAux='{"Ruta":'.$this->responseBody.',"POI en la ruta":'.$datos.'}';
$this->responseBody=$lineaAux;
	
		
	}
	
	
}


	

	protected function EVALUATION()
	
	{

	require_once('../mysql_conexion.php');
	
	$q="SELECT nombre, empleados, emplazamiento_comunicacion, factor_diversion, calidad_precio, limpieza, puntuacion, votos FROM POI WHERE poi_id='".$this->eleccion."'";
	$r=@mysqli_query($this->dbc, $q);
	$row=mysqli_fetch_array($r, MYSQLI_ASSOC);
	
	$votos=$row['votos']+1;
	$nombre=$row['nombre'];

	function incrementar($nuevo,$anterior,$votos)
	{
	$aux=$votos-1;
	$valor=(($anterior*$aux)+$nuevo)/$votos;
	
	return $valor;
	}	

	$empleados=incrementar($this->empleados,$row['empleados'],$votos);
	$emplazamiento_comunicacion=incrementar($this->emplazamiento_comunicacion,$row['emplazamiento_comunicacion'],$votos);
	$factor_diversion=incrementar($this->factor_diversion,$row['factor_diversion'],$votos);
	$calidad_precio=incrementar($this->calidad_precio,$row['calidad_precio'],$votos);
	$limpieza=incrementar($this->limpieza,$row['limpieza'],$votos);
	$puntuacion=($limpieza+$calidad_precio+$factor_diversion+$emplazamiento_comunicacion+$empleados)/5;
	
	
require_once('../mysql_conexion.php');
	
$q="UPDATE POI SET votos='".$votos."', empleados='".$empleados."', emplazamiento_comunicacion='".$emplazamiento_comunicacion."',factor_diversion='".$factor_diversion."',calidad_precio='".$calidad_precio."',limpieza='".$limpieza."',puntuacion='".$puntuacion."' WHERE poi_id='$this->eleccion' ";	
$r=@mysqli_query($this->dbc, $q);

mysqli_close($this->dbc);


$this->responseBody='{"Nombre":"'.utf8_encode($nombre).'","numero de votos":'.$votos.',"valoraciones":{"empleados":'.$empleados.',"emplazamiento-comunicacion":'.$emplazamiento_comunicacion.',"factor_diversion":'.$factor_diversion.',"calidad-precio":'.$calidad_precio.',"limpieza":'.$limpieza.',"puntuacion":'.$puntuacion.'}}';


	
	
	}

	
	protected function TEMATICAS()
	
	{
		if(empty($this->eleccion))
			{
			
			require_once('../mysql_conexion.php');
	
			$q="SELECT nombre,ruta_id,descripcion,imagen FROM RUTAS WHERE ciudad='".$this->ciudad."'";
			$r=@mysqli_query($this->dbc, $q);
			$i=0;
			$respuesta="";
			while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){

			$datos='{"Nombre":"'.$row[nombre].'","Identificador de la ruta":"'.$row[ruta_id].'", "Descripcion":"'.$row[descripcion].'"';
			if($this->imagen){$datos.=', "Imagen":"'.$row[imagen].'"';}
			$datos.="}";
			if($i==0)
			{$respuesta.=$datos;}
			else
			{$respuesta.=",".$datos;}
			$i+=1;
			}
			$this->responseBody='{"Lista de rutas":['.$respuesta.']}';

			
		}else{

			$eleccion=$_GET['eleccion'];
			
			require_once('../mysql_conexion.php');
	
			$q="SELECT nombre,descripcion,ruta_poi,ruta_coord, ciudad FROM RUTAS WHERE ruta_id='".$this->eleccion."'";
			$r=@mysqli_query($this->dbc, $q);
			$row=mysqli_fetch_array($r, MYSQLI_ASSOC);			
			
			
			$response='{"Nombre":"'.$row[nombre].'","Ciudad":"'.$row[ciudad].'","Descripcion":"'.$row[descripcion].'","Coordenadas por las que pasa la ruta":[';
			

			$chopped=explode(";",$row[ruta_coord]);
			$num=count($chopped);
			$route="";
			for($i=0; $i<$num; $i++)
			{
				$chopped2=explode(",",$chopped[$i]);
				if($i==0){$route.='{"Latitud":'.$chopped2[1].',"Longitud":'.$chopped2[0].'}';
				}else{$route.=',{"Latitud":'.$chopped2[1].',"Longitud":'.$chopped2[0].'}';
				}
			}

			$response.=$route.'],"POIs en la ruta":';			
	
			$chopped=explode(",",$row[ruta_poi]);
			
			$num=count($chopped);
			$pois="";

			for($i=0; $i<$num; $i++)
			{
				
				$q="SELECT nombre,descripcion,puntuacion FROM POI WHERE poi_id='".$chopped[$i]."'";
				$r=@mysqli_query($this->dbc, $q);
				$row=mysqli_fetch_array($r, MYSQLI_ASSOC);

				if($i==0){$pois.='{"Nombre del POI":"'.$row[nombre].'", "Descripcion":"'.$row[descripcion].'", "Puntuacion":"'.$row[puntuacion].'"}';
				}else{ $pois.=',{"Nombre del POI":"'.$row[nombre].'", "Descripcion":"'.$row[descripcion].'", "Puntuacion":"'.$row[puntuacion].'"}';
				}


			}

			$response.='['.$pois.']}';		


			$this->responseBody=$response;



		}
	
	}
	

	protected function PATH()

	{
		
	if(empty($this->eleccion))
	{

	$q="SELECT poi_id,nombre,imagen FROM POI WHERE nombre REGEXP '".$this->nombre."'";
	
	$r=mysqli_query($this->dbc, $q); 
	$i=0;	

	while($row=mysqli_fetch_array($r, MYSQLI_ASSOC)){

	$datos='{"Nombre":"'.$row[nombre].'","Identificador del POI":"'.$row[poi_id].'"';
	if($this->imagen){$datos.=', "Imagen en base64":"'.base64_encode($row[imagen]).'"';}
	$datos.='}';	

	if($i==0)
	{$respuesta.=$datos;}
	else
	{$respuesta.=",".$datos;}
	$i+=1;
	}
	
	$this->responseBody='{"Lista de POIs":['.$respuesta.']}';

	}else{
			
	
	$q="SELECT nombre,longitud,latitud FROM POI WHERE poi_id='".$this->eleccion."'";
	$r=mysqli_query($this->dbc, $q);
	$row=mysqli_fetch_array($r, MYSQLI_ASSOC);
	$this->posicion_final=$row['latitud'].','.$row['longitud'];


	}
	
	}


	

	

	

	protected function URLCloudM()

	{

		$formato="html";

		$key="45b0e07e44de5be4a0cc7faf7442aa7d";

		$url="http://geocoding.cloudmade.com/".$key."/geocoding/v2/find.".$formato."?";

		

		$url.="&query=street:".$this->direccion.";city:".$this->ciudad; //no se si se podra hacer esto

		

		$this->url="http://geocoding.cloudmade.com/45b0e07e44de5be4a0cc7faf7442aa7d/geocoding/v2/find.html?query=house:4;street:calle Corrida;city:gijon;country:Spain";//$url;

		$this->acceptType=$formato;

	}

	

	

	protected function URLYahoo()

	{

		$formato="php"; //por defecto

		$key="w97wctnV34G7orovZ5xpT20gMLZcg4kJl9Ye7UoBsZo4bE7ILIJvSFnAazDluMYYOc4-";

		$url="http://local.yahooapis.com/MapsService/V1/geocode?appid=".$key;

		

		$linea=$this->direccion;

		

		$trozos=explode(" ",$linea);



			foreach($trozos as $key=>$value)

			{			

				$direccion.=$value.'+';
			

			}
		

		$url.="&street=".$direccion."&city=".$this->ciudad."&output=".$formato; 

		

				

		$this->url=$url;

		$this->acceptType=$formato;		

		

		

	}




	protected function URLGoogleM()

	{

		$formato="json";

		$key="ABQIAAAAalXxSC0-Sfe7VLcdYTIjaxTe_Lsu3R_udk7ET3fZvF_yUPHz7hRyHWK3ODuYuy6QZSD-gclHtt6eFg";

		$url="http://maps.google.com/maps/geo?";



		$linea=$this->direccion;		



		$trozos=explode(" ",$linea);



			foreach($trozos as $key=>$value)

			{			

				$direccion.=$value.'+';

			

			}

			$direccion.=$this->ciudad;



		$url.="q=".$direccion."&output=".$formato."&key=".$key;	


		$this->url=$url;

		$this->acceptType=$formato;





	}



	//Funciones para extraer los datos de la respuesta dada por el servicio web



	public function RespuestaGoogleM()

	{


	$responseBody2=json_decode($this->responseBody);	

	$auxiliar=$responseBody2->{'Placemark'};

	$codigo=$responseBody2->{'Status'};

	$auxiliar=$auxiliar[0];

	$Direccion=$auxiliar->{'address'};

	$auxiliar=$auxiliar->{'Point'};

	$auxiliar=$auxiliar->{'coordinates'};

	$this->codigo=$codigo->{'code'};

	//print_r($auxiliar);

	$this->longitud=$auxiliar[0];

	$this->latitud=$auxiliar[1];

	$this->Direccion=utf8_decode($Direccion);





	}

	

	public function RespuestaYahoo()

	{


	$responseBody2=unserialize($this->responseBody);

	$responseInfo2=$this->responseInfo;	

	$this->codigo=$responseInfo2['http_code'];



	$auxiliar=$responseBody2['ResultSet'];

	$auxiliar=$auxiliar['Result'];

	$this->latitud=$auxiliar['Latitude'];

	$this->longitud=$auxiliar['Longitude'];

	$this->Direccion=utf8_decode($auxiliar['Address']).", ".utf8_decode($auxiliar['City']);

	



	}



	public function RespuestaRouting()

	{
		$respuesta = json_decode($this->responseBody);
		return $respuesta->{'route_geometry'};

		
	
	}



	public function doExecute()

	{
	
		$ch = curl_init();

		$this->setCurlOpts($ch);

		$this->responseBody = curl_exec($ch);

		$this->responseInfo = curl_getinfo($ch);

		curl_close($ch);

	}

	

	protected function setCurlOpts (&$curlHandle)

	{

		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);

		curl_setopt($curlHandle, CURLOPT_URL, $this->url);

		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array ('Accept: ' . $this->acceptType));

	}

	public function GetPosicion()
	{
		return $this->posicion_final;
	}

	public function GetLatitud()
	{
		return $this->latitud;
	}

	public function GetLongitud()
	{
		return $this->longitud;
	}

	public function GetDireccion()
	{
		return $this->Direccion;
	}

	public function GetCodigo()
	{
		return $this->codigo;
	}

	public function GetResponseBody()
	{
		return $this->responseBody;
	}

	
	public function CHECK()

	{
	
	$q="SELECT APIkey FROM USUARIOS";
	$r=@mysqli_query($this->dbc, $q);
	
	While($row=mysqli_fetch_array($r,MYSQLI_ASSOC))
	{
		if($row[APIkey]==$this->APIKEY)
		{
			return TRUE;
		}
	}
	return FALSE;

	}


}
?>