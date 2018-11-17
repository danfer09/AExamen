<?php	
	$puntosTemaStr = file_get_contents('puntostema.json');
	$puntosTema = json_decode($puntosTemaStr, true);
	$numTemas=$puntosTema;
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

?>
