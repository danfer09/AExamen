<?php

	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php*/
	if (!$logeado) {
		header('Location: index.php');
	}

	include_once 'funcionesServidor.php';

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idPeticion = isset($_POST['idPeticion'])? $_POST['idPeticion']: null;
	if($funcion == "getPeticion")
		getPeticion($idPeticion);
	else if($funcion == "borrarPeticion")
		borrarPeticion($idPeticion);
	else if($funcion == "aceptarPeticion")
		aceptarPeticion($idPeticion);
	else if($funcion == "reiniciarLog")
		reiniciarLog();
	else if($funcion == "eliminarLog")
		eliminarLog();
	else if($funcion == "descargarLog")
		descargarLog();


	function eliminarLog() {
		if (!unlink('./log/log_AExamen.log')){
			echo "Error deleting file!";
		} else {
			$log  = "  ___  _____                               
 / _ \|  ___|                              
/ /_\ \ |____  ____ _ _ __ ___   ___ _ __  
|  _  |  __\ \/ / _` | '_ ` _ \ / _ \ '_ \ 
| | | | |___>  < (_| | | | | | |  __/ | | |
\_| |_|____/_/\_\__,_|_| |_| |_|\___|_| |_|
                                           ".PHP_EOL."-------------------------LOG STARTS HERE-------------------------".PHP_EOL.PHP_EOL.
				'['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
		        " | ACTION --> Eliminar log ".PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
			//Save string to log, use FILE_APPEND to append.
			file_put_contents('./log/log_AExamen.log', utf8_decode($log));
			echo "Log eliminado correctamente";
		}
	}

	function reiniciarLog() {
		$log  = "  ___  _____                               
 / _ \|  ___|                              
/ /_\ \ |____  ____ _ _ __ ___   ___ _ __  
|  _  |  __\ \/ / _` | '_ ` _ \ / _ \ '_ \ 
| | | | |___>  < (_| | | | | | |  __/ | | |
\_| |_|____/_/\_\__,_|_| |_| |_|\___|_| |_|
                                           ".PHP_EOL."-------------------------LOG STARTS HERE-------------------------".PHP_EOL.PHP_EOL.
				'['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
		        " | ACTION --> Reiniciar log ".PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents('./log/log_AExamen.log', utf8_decode($log));
		echo "Log reiniciado correctamente";
	}

	function aceptarPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$peticion = getPeticionReturn($id);
			$sql = 'DELETE FROM `peticiones_registro` WHERE id='.$id;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			/*echo "<pre>";
			var_dump($peticion);
			die();*/
			$sql = "INSERT INTO `profesores`(`nombre`, `apellidos`, `email`, `id`, `clave`) VALUES ('".$peticion['nombre']."','".$peticion['apellidos']."','".$peticion['email']."','','".$peticion['clave']."')";
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

			//Something to write to txt log
			$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
			        " | ACTION --> Aceptar petición #".$id.' de '.$peticion['email'].' - '.$peticion['apellidos'].', '.$peticion['nombre'].PHP_EOL.
			        "-----------------------------------------------------------------".PHP_EOL;
			//Save string to log, use FILE_APPEND to append.
			file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

			$_SESSION['error_envio_mail'] = false;
			if (smtpmailer($peticion['email'], $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro aceptada (AExamen)', 'solicitudAceptada.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
			} else {
				$_SESSION['error_envio_mail'] = true;
			}

			$resultado = true;
		} else {
			$resultado = false;
		}
		echo $resultado;
	}

	function borrarPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$peticion = getPeticionReturn($id);
			$sql = 'DELETE FROM `peticiones_registro` WHERE id='.$id;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

			//Something to write to txt log
			$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
			        " | ACTION --> Denegar petición #".$id.' de '.$peticion['email'].' - '.$peticion['apellidos'].', '.$peticion['nombre'].PHP_EOL.
			        "-----------------------------------------------------------------".PHP_EOL;
			//Save string to log, use FILE_APPEND to append.
			file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

			$_SESSION['error_envio_mail'] = false;
			if (smtpmailer($peticion['email'], $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro aceptada (AExamen)', 'solicitudDenegada.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
			} else {
				$_SESSION['error_envio_mail'] = true;
			}

			$resultado = true;
		} else {
			$resultado = false;
		}
		echo json_encode($resultado);
	}

	function getPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT * FROM `peticiones_registro` WHERE id='.$id;
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				$resultado = null;
			}
			mysqli_close($db);
			echo json_encode($resultado);
		} else {
			echo "Conexión fallida";
			$_SESSION['error_BBDD']=true;
			echo false;
		}
	}

	function getPeticionReturn($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT * FROM `peticiones_registro` WHERE id='.$id;
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado[0];
		} else {
			echo "Conexión fallida";
			return false;
		}
	}

	function getPeticiones() {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT * FROM `peticiones_registro`';
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado;
		} else {
			echo "Conexión fallida";
			$_SESSION['error_BBDD']=true;
			return false;
		}
	}

?>