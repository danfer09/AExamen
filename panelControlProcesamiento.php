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

	/*
	*Funcion que elimina el archivo de log existente y crea uno nuevo, escribiendo en él que se ha eliminado el log correctamente
	*/
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

	/*
	*Funcion que reinicia el archivo de log existente sobreescribiendo en él que se ha reiniciado el log correctamente
	*/
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

	/*
	*Funcion que acepta una petición de registro a AExamen, da de alta al nuevo profesor en la tabla correspondiente, lo anota en el log y envia un email al usuario informando que ya puede iniciar sesión
	* @param int $id identificador de la peticion
	* @return boolean $resultado true si se ha aceptado correctamente y false en caso contrario
	*/
	function aceptarPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$peticion = getPeticionReturn($id);
			$sql = 'DELETE FROM `peticiones_registro` WHERE id='.$id;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			
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

	/*
	*Funcion que deniega una petición de registro a AExamen, lo anota en el log y envia un email al usuario informando que su petición ha sido denegada
	* @param int $id identificador de la peticion
	* @return boolean $resultado true si se ha denegado correctamente y false en caso contrario
	*/
	function borrarPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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
			if (smtpmailer($peticion['email'], $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro denegada (AExamen)', 'solicitudDenegada.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
			} else {
				$_SESSION['error_envio_mail'] = true;
			}

			$resultado = true;
		} else {
			$resultado = false;
		}
		echo json_encode($resultado);
	}

	/*
	* Funcion que devuelve/muestra (para AJAX) una peticion en concreto
	* @param int $id identificador de la peticion
	* @return array $resultado con la peticion con identificador id
	*/
	function getPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	/*
	* Funcion que devuelve (para PHP) una peticion en concreto
	* @param int $id identificador de la peticion
	* @return array $resultado con la peticion con identificador id
	*/
	function getPeticionReturn($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	/*
	* Funcion que devuelve todasl las peticiones pendientes
	* @return array $resultado con las peticiones pendientes existentes
	*/
	function getPeticiones() {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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