<?php

//echo getDistance(-5.6424285,43.5350005,-5.6751519,43.5288867);

function getDistance($lat1, $long1, $lat2, $long2)
{
$earth = 6371000; //km change accordingly
//$earth = 3960; //miles

//Point 1 cords
$lat1 = deg2rad($lat1);
$long1= deg2rad($long1);

//Point 2 cords
$lat2 = deg2rad($lat2);
$long2= deg2rad($long2);

//Haversine Formula
$dlong=$long2-$long1;
$dlat=$lat2-$lat1;

$sinlat=sin($dlat/2);
$sinlong=sin($dlong/2);

$a=($sinlat*$sinlat)+cos($lat1)*cos($lat2)*($sinlong*$sinlong);

$c=2*asin(min(1,sqrt($a)));

$d=round($earth*$c);

return $d;
}

// pull cords out of database


?>