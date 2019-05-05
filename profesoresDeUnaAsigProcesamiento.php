<?php

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php*/
	if (!$logeado) {
		header('Location: index.php');
	}

	//Cargamos en variables todos los parametros que nos hayan llegado por POST

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;
	$idProfesores = isset($_POST['idProfesores'])? $_POST['idProfesores']: null;
	$profesor = isset($_POST['profesor'])? $_POST['profesor']: null;
	$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;

	//comprobamos los valores de las variables y en consecuencia llamamos a las
	//diferentes funciones
	if($funcion == "borrarProfesorDeAsig")
		borrarProfesorDeAsig($idProfesor, $idAsig);
	else if($funcion == "getProfesoresFueraAsig"){
		getProfesoresFueraAsig($idAsig, $idProfesores);
	} else if ($funcion == "aniadirProfesor") {
		aniadirProfesor($profesor, $idAsig);
	}

	/*Función que dada una asignatura nos devuelve todos los profesores de esta.
	*
	*Funcion que dado el identificador de una asignatura nos devuelve en un array
	*todos los profesores que tiene esa asignatura y null en caso de que no tenga
	*
	* @param int $idAsig identificador de la asignatura
	* @return $resultado array con los profesores de la asignatura, null si la
	* asignatura no tiene profesores y false en caso de que haya un fallo con la
	* BBDD */
	function profesoresAsignatura($idAsig) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id as id FROM `profesores` INNER JOIN `prof_asig_coord` ON profesores.id=prof_asig_coord.id_profesor WHERE prof_asig_coord.id_asignatura='.$idAsig.' and prof_asig_coord.coordinador = 0';
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
			return false;
		}
	}

	/*Función que borra un profesor de una asignatura
	*
	*Funcion que dado un id de un profesor y el de una asignatura borra dicho
	*profesor de la asignatura
	*
	* @param int $idProfesor identificador de un profesor
	* @param int $idAsig identificador de una asignatura
	* @return boolean $funciona vale true si se borra con exito y false en caso
	* contrario */
	function borrarProfesorDeAsig($idProfesor, $idAsig){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "DELETE FROM `prof_asig_coord` WHERE id_profesor=".$idProfesor." and id_asignatura=".$idAsig;
			$consulta=mysqli_query($db,$sql);
			$funciona=true;
		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);
		echo $funciona;
	}

	/*Función que nos devuelve los profesores que no estan en una asignatura
	*
	*Funcion que dado un id de una asignarua y un array con los identificadores de
	*los profesores que hay en la asigntrua nos devuelve un array con los profesores
	*que no estan en la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @param $idProfesores array con los  identificadores de los profesores que
	* estan en la asignatura
	* @return $resultado array con los identificadores de los profesores que no
	* estan en la asignatura o false en caso de que haya habido algun error con
	* la conexin con la BBDD */
	function getProfesoresFueraAsig($idAsig, $idProfesores){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if ($idProfesores != null) {
			$idProfesores = explode(',', $idProfesores);
		} else {
			$idProfesores = array();
		}

		$idProfesores[] = $_SESSION['id'];
		$ids = implode (",", $idProfesores);
		if($db){
			$sql = 'SELECT nombre, apellidos, email, profesores.id as id
					FROM
					`profesores` LEFT JOIN `prof_asig_coord` ON profesores.id=prof_asig_coord.id_profesor

					 WHERE
					 	profesores.id not in ('.$ids.')
					 	and
					 	(prof_asig_coord.id_asignatura<>'.$idAsig.' OR prof_asig_coord.id_asignatura is null)';
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			}
			mysqli_close($db);
			echo json_encode($resultado);
		} else {
			echo "Conexión fallida";
			return false;
		}
	}

	/*Función que añade un profesor a una asignarura
	*
	*Funcion que dado un id de un profesor y un id de una asignatura, añade ese
	*profesor a esa asignatura
	*
	* @param int $idProfesor identificador del profesor
	* @param int $idAsig identificador de la asignatura*/
	function aniadirProfesor($idProfesor, $idAsig) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
		    $sql ='INSERT INTO `prof_asig_coord`(`id_profesor`, `id_asignatura`, `coordinador`, `id`) VALUES ('.$idProfesor.','.$idAsig.',0,'."''".')';
		    if(mysqli_query($db,$sql)) {
		    	echo json_encode(array("Insertado correctamente"));
		    } else {
				echo "Error: " . $sql . "<br>" . mysqli_error($db);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
		}
		mysqli_close($db);
	}

	/*Función que dado un profesor y una asignatura, nos devuelve si dicho profesores
	* es coordinador o no
	*
	*Función que dado el identificador de un profesor y el de una asignatura nos
	*devuleve si dicho profesor es o no un coordinador de la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @param int $idProfesor identificador de la profesor
	* @return boolean $result['coordinador'] true en caso de que la sea coordinador
	* y false en caso contrario*/
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
