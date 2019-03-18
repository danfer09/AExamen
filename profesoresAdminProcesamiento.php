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

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;

	if($funcion == "borrarProfesor")
		borrarProfesor($idProfesor);

	function borrarProfesor($id) {
		$_SESSION['error_no_poder_borrar'] = false;
		$funciona=false;
		$admin = $_SESSION['administrador'];
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos

		if($db){
			if ($admin) {
				$sql = 'DELETE FROM profesores WHERE id='.$id;
				$consulta=mysqli_query($db,$sql);

				$fila=mysqli_fetch_assoc($consulta);
				$funciona = true;
			} else {
				$_SESSION['error_no_poder_borrar'] = true;
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);

		echo $funciona;
	}

	function getProfesoresAdmin() {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id as id FROM `profesores`';
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