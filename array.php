<?php
require_once("distance.php");
ini_set('memory_limit', '512M');
$array=array();
foreach(explode("\n",file_get_contents("cities2.txt")) as $line) {
	$data=explode("\t",$line);
	$distance=distance(60.170000,24.931000,$data[2],$data[1]);
	if($distance<=100) {
		$array[$data[0]]=array($data[2],$data[1]);
		$distance=round($distance,2);
		echo "\n{$data[0]} ({$distance}km)";
	}
}
file_put_contents("100km.serialized",serialize($array));
