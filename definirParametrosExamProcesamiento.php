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
//Obtenemos los parametros que nos pasan por post y los pasamos a la
//funcion indicada
$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
$puntos_tema = isset($_POST['jsonParametros'])? $_POST['jsonParametros']: null;
$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
$espaciado = isset($_POST['espaciado'])? $_POST['espaciado']: null;
$textoInicial = isset($_POST['textoInicial'])? $_POST['textoInicial']: null;
if($funcion == "updateParametrosAsig"){
	$_SESSION['pruebaParam'] = $puntos_tema;
	updateParametrosAsig($puntos_tema, $idAsig, $espaciado, $textoInicial);
}

/*Función que nos devuelve los parametros de un examen de una asignatura.
*
*Funcion que dado el id de una asignatura nos devuelve los parametros para
*un examen de esa asignatura
*
* @param int $idAsig identificador de la asignatura
* @return $fila array con los parametros que tiene definido esa asignatura */
function selectParametrosAsig($idAsig) {
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

/*Función que nos actualiza los parametros para un examen de una asignatura
*
*Funcion que dado unos puntos por tema, un espaciado y un texto inicial
*actualiza esto valores como valores de un examen por defecto de la asignatura
*que le indicamos con el identificador que tambien le pasamos por parametro
*
*
* @param string $puntos_tema puntos definidos por cada tema con forma json
* @param string $idAsig identificador de la asignatura
* @param string $espaciado valor que queremos poner en el espaciado
* @param strint $textoInicial Texto que queremos mostrar al comienzo del examen
* @return boolean $success devuleve true si la modificacion se ha realizado con exito y false en caso contrario */
function updateParametrosAsig($puntos_tema, $idAsig, $espaciado, $textoInicial){
	//Conectamos con la BBDD
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	//hacemos casting para transformarlos en enteros
	$espaciadoInt = (int)$espaciado;
	$idAsigInt = (int)$idAsig;

	$puntos_tema = "'".$puntos_tema."'";
	if($db){
		$sql = "UPDATE `asignaturas` SET`espaciado_defecto`=".$espaciadoInt.", `texto_inicial`= '".$textoInicial."', `puntos_tema`= ".$puntos_tema." WHERE id=".$idAsigInt;
		$consulta=mysqli_query($db,$sql);

		//Apuntamos en el log que usuario a modificado los valores por defecto del examen de la asignatura
			$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].', '.$_SESSION['email'].
			        " | ACTION --> Parámetros de la asignatura con id ".$idAsig." modificados".PHP_EOL.
			        "-----------------------------------------------------------------".PHP_EOL;
			file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
	} else {
		echo false;
	}
	mysqli_close($db);
	echo true;
}
/*Funcion que sirve para saber si un profesor es coordinador de una asignatura
*
*Funcion que dado un identificador de una asignatura y un identificador de un
*profesor nos devuelve true en caso de que el profesor sea coordinador de
*esa asignatura y false en caso contrario
*
*

* @param int $idAsig identificador de la asignatura
* @param int $idProfesor identificador de un profesor
* @return boolean $result['coordinador'] devuelve true en caso de que el profesor sea coordinador
*de esa asignatura y false en caso contrario */
function esCoordinador($idAsig, $idProfesor){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$result=false;
		if($db){
			$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
			$consulta = mysqli_query($db,$sql);
			$result= mysqli_fetch_assoc($consulta);
		}
		return $result['coordinador'];
	}

?>
