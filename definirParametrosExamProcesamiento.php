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
$espaciado = isset($_POST['espaciado'])? $_POST['espaciado']: null;
$textoInicial = isset($_POST['textoInicial'])? $_POST['textoInicial']: null;
if($funcion == "updateParametrosAsig"){
	$_SESSION['pruebaParam'] = $jsonParametros;
	updateParametrosAsig($jsonParametros, $idAsig, $espaciado, $textoInicial);
}

function selectParametrosAsig($idAsig) {
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	if($db){
		$sql = "SELECT `puntos_tema`, `texto_inicial`, `espaciado_defecto` FROM `asignaturas` WHERE id=".$idAsig;
		$consulta=mysqli_query($db,$sql);
		$fila=mysqli_fetch_assoc($consulta);
	} else {
		echo "Conexión fallida";
		return false;
	}
	mysqli_close($db);
	return $fila;
}

function updateParametrosAsig($jsonParametros, $idAsig, $espaciado, $textoInicial){
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	//$_SESSION['pruebaParam'] = $jsonParametros;
	$jsonParametrosString = json_encode($jsonParametros);
	$espaciadoInt = (int)$espaciado;
	$idAsigInt = (int)$idAsig;
	//$_SESSION['pruebaParam'] = $espaciadoInt;
	//$_SESSION['pruebaParam2'] = $textoInicial;
	$jsonParametrosString = "'".$jsonParametrosString."'";
	if($db){
		$sql = "UPDATE `asignaturas` SET`espaciado_defecto`=".$espaciadoInt.", `texto_inicial`= '".$textoInicial."', `puntos_tema`= ".$jsonParametrosString." WHERE id=".$idAsigInt;
		$consulta=mysqli_query($db,$sql);

		//Something to write to txt log
			$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].', '.$_SESSION['email'].
			        " | ACTION --> Parámetros de la asignatura con id ".$idAsig." modificados".PHP_EOL.
			        "-----------------------------------------------------------------".PHP_EOL;
			//Save string to log, use FILE_APPEND to append.
			file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

		//$sql = "UPDATE `asignaturas` SET  WHERE id=".$idAsig;
		//$consulta=mysqli_query($db,$sql);
		//$sql = "UPDATE `asignaturas` SET  WHERE id=".$idAsig;
		//$consulta=mysqli_query($db,$sql);
		//UPDATE `asignaturas` SET `texto_inicial`='asdfasdfa' WHERE id=1
		//UPDATE `asignaturas` SET `puntos_tema`='{"numeroTemas":"3","maximoPuntos":"10","tema1":"5","tema2":"3","tema3":"2"}' WHERE id=1
		//UPDATE `asignaturas` SET `puntos_tema`= "{'numeroTemas':'3','maximoPuntos':'10','tema1':'4','tema2':'3','tema3':'3'}" WHERE id=1
		
	} else {
		//echo "Conexión fallida";
		echo false;
	}
	mysqli_close($db);
	echo true;
}

function esCoordinador($idAsig, $idProfesor){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$result=false;
		if($db){
			$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
			$consulta = mysqli_query($db,$sql);
			$result= mysqli_fetch_assoc($consulta);
		}
		return $result['coordinador'];
	}

?>