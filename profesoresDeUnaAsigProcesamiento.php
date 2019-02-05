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

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;
	$idProfesores = isset($_POST['idProfesores'])? $_POST['idProfesores']: null;
	$profesor = isset($_POST['profesor'])? $_POST['profesor']: null;
	$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
	if($funcion == "borrarProfesorDeAsig")
		borrarProfesorDeAsig($idProfesor, $idAsig);
	else if($funcion == "getProfesoresFueraAsig"){
		getProfesoresFueraAsig($idAsig, $idProfesores);
	} else if ($funcion == "aniadirProfesor") {
		aniadirProfesor($profesor, $idAsig);
	}

	function profesoresAsignatura($idAsig) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	function borrarProfesorDeAsig($idProfesor, $idAsig){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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
	//No funciona esta funcion, ya que la query no esta bien hecha
	function getProfesoresFueraAsig($idAsig, $idProfesores){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$idProfesores = explode(',', $idProfesores);
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
			//$_SESSION['prueba1'] = $sql;
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

	function aniadirProfesor($profesor, $idAsig) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//$_SESSION['prueba2'] = $profesor;
		if($db){
		    $sql ='INSERT INTO `prof_asig_coord`(`id_profesor`, `id_asignatura`, `coordinador`, `id`) VALUES ('.$profesor.','.$idAsig.',0,'."''".')';
		    if(mysqli_query($db,$sql)) {
		    	echo json_encode(array("Insertado correctamente"));
		    } else {
				echo "Error: " . $sql . "<br>" . mysqli_error($db);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			//header('Location: loginFormulario.php');
		}
		mysqli_close($db);
	}



?>