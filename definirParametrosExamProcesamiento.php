<?php

/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
	if (!$logeado) {
		header('Location: index.php');
	}

$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
$jsonParametros = isset($_POST['jsonParametros'])? json_decode($_POST['jsonParametros']): null;
$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
if($funcion == "updateParametrosAsig"){
	$_SESSION['pruebaParam'] = $jsonParametros;
	updateParametrosAsig($jsonParametros, $idAsig);
}

function selectParametrosAsig($idAsig) {
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	if($db){
		$sql = "SELECT `puntos_tema`, `texto_inicial`, `espaciado_defecto`, `cabecera` FROM `asignaturas` WHERE id=".$idAsig;
		$consulta=mysqli_query($db,$sql);
		$fila=mysqli_fetch_assoc($consulta);
	} else {
		echo "Conexión fallida";
		return false;
	}
	mysqli_close($db);
	return $fila;
}

function updateParametrosAsig($jsonParametros, $idAsig){
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	//$_SESSION['pruebaParam'] = $jsonParametros;
	$jsonParametrosString = json_encode($jsonParametros);
	$_SESSION['pruebaParam2'] = $jsonParametrosString;
	$_SESSION['pruebaParam2'] = "'".$jsonParametrosString."'";
	$jsonParametrosString = "'".$jsonParametrosString."'";
	if($db){
		$sql = "UPDATE `asignaturas` SET `puntos_tema`= ".$jsonParametrosString." WHERE id=".$idAsig;
		//UPDATE `asignaturas` SET `puntos_tema`='{"numeroTemas":"3","maximoPuntos":"10","tema1":"5","tema2":"3","tema3":"2"}' WHERE id=1
		//UPDATE `asignaturas` SET `puntos_tema`= "{'numeroTemas':'3','maximoPuntos':'10','tema1':'4','tema2':'3','tema3':'3'}" WHERE id=1
		$consulta=mysqli_query($db,$sql);
	} else {
		echo "Conexión fallida";
		return false;
	}
	mysqli_close($db);
	return true;
}

?>