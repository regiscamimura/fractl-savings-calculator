<?php

$json = file_get_contents('data/_data.json');
$json = json_decode($json, TRUE);


$data = [];
$locations = [];

foreach ($json as $row) {
	$copy = [];
	foreach ($row as $k=>$v) {
		$key = slug($k);
		$copy[$key] = $v;
	}
	$copy['hires'] = 1;
	$copy['hours'] = 40;
	$copy['occupation_slug'] = slug($copy['occupation']);
	$data[$row['Area Code']][] = $copy;
	$locations[$row['Area Code']] = ['area_code' => $row['Area Code'], 'state_and_msa'=>$row['State and MSA']];
}

$arr_locations = [];
foreach ($locations as $row) {
	$arr_locations[] = $row;
}
file_put_contents("data/locations.json", json_encode($arr_locations));
foreach($data as $code=>$row) {
	file_put_contents("data/$code.json", json_encode($row));
}

$json = file_get_contents('data/_data2.json');
$json = json_decode($json, TRUE);


$data = [];

foreach ($json as $row) {
	$copy = [];
	foreach ($row as $k=>$v) {
		$key = slug($k);
		if (substr($v, 0, 1) == "$") $v = floatval(str_replace(["$", ","], "", $v)); 
		$copy[$key] = $v;
	}
	$copy['occupation_slug'] = slug($copy['occupation']);
	
	$data[$copy['occupation_slug']] = $copy;
}

file_put_contents("data/national.json", json_encode($data));

function slug($url) {
	$url = strtolower(str_replace(array(" ", "&", "-"), array("_", "_and_", "_"), $url));
	$url = str_replace(array("(", ")", ".", "@", "#", "$", "%", "¨", "*", "{", "[", "}", "]", "\"", "'", "=", "+", "§", "ª", "º", ",", "/", "\\", "~", "^"), "", $url);

	while (strpos($url, "__") !== FALSE) $url = str_replace("__", "_", str_replace("__", "_", $url));
	if (substr($url, -1) == "_") $url = substr($url, 0, -1);

	$url = strip_accents($url);
	$url = urlencode(preg_replace("/[^A-Za-z0-9\_]/", "", $url));
	
	return $url;
}
function strip_accents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}
