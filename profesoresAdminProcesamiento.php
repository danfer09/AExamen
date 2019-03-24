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

	$nombre = isset($_POST['nombre'])? $_POST['nombre']: null;
	$apellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
	$email = isset($_POST['email'])? $_POST['email']: null;
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;
	$idAsigSelect = isset($_POST['idAsigSelect'])? $_POST['idAsigSelect']: null;
	$idAsigNoSelect = isset($_POST['idAsigNoSelect'])? $_POST['idAsigNoSelect']: null;

	

	if($funcion == "borrarProfesor")
		borrarProfesor($idProfesor);
	else if($funcion == "editarProfesor")
		editarProfesor($nombre,$apellidos,$email,$idProfesor);
	else if($funcion == "getAsignaturas")
		getAsignaturas($idProfesor);
	else if($funcion == "setCoordinadores")
		setCoordinadores($idProfesor, $idAsigSelect, $idAsigNoSelect);




	function setCoordinadores($idProf, $idAsigSelect, $idAsigNoSelect){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			

			$arrayIdAsigSelect = json_decode($idAsigSelect);
			$arrayIdAsigNoSelect = json_decode($idAsigNoSelect);


			for($i=0; $i < count($arrayIdAsigSelect); $i++) {
				$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_asignatura`='.$arrayIdAsigSelect[$i].' and`id_profesor`='.$idProf;
				$consulta=mysqli_query($db,$sql);
				$fila=mysqli_fetch_assoc($consulta);
				if($fila['existe']){
					$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=1 WHERE `id_asignatura`='.$arrayIdAsigSelect[$i].' and`id_profesor`='.$idProf;
					$consulta=mysqli_query($db,$sql);	
				}else{
					$sql = "INSERT INTO `prof_asig_coord`(`id_asignatura`, `id_profesor`, `coordinador`, `id`) VALUES (".$arrayIdAsigSelect[$i].",".$idProf.",1,'')";
					$consulta=mysqli_query($db,$sql);
				}
				
			}

			$arrayIdAsigNoSelect = json_decode($idAsigNoSelect);

			for($i=0; $i < count($arrayIdAsigNoSelect); $i++) {
				$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_asignatura`='.$arrayIdAsigNoSelect[$i].' and`id_profesor`='.$idProf;
				$consulta=mysqli_query($db,$sql);
				$fila=mysqli_fetch_assoc($consulta);
				if($fila['existe']){
					$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=0 WHERE `id_asignatura`='.$arrayIdAsigNoSelect[$i].' and`id_profesor`='.$idProf;
					$consulta=mysqli_query($db,$sql);	
				}				
			}

		}
		else{
			$_SESSION['error_BBDD']=true;
			//header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		echo "guay";
	}

	function getAsignaturas($idProfesor) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;
		
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT `nombre`, `siglas`, `id` FROM `asignaturas`';
			$consulta=mysqli_query($db,$sql);
			$asigNoCoord = [];
			$asigSiCoord = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					if(esCoordinador($idProfesor, $fila['id']))
						$asigSiCoord[] = $fila;
					else
						$asigNoCoord[] = $fila;
				}
			} else {
				$resultado = null;
			}
			mysqli_close($db);
			$resultado['asigSiCoord']= $asigSiCoord;
			$resultado['asigNoCoord']= $asigNoCoord;
			echo json_encode($resultado);
		} else {
			echo "Conexión fallida";
			$_SESSION['error_BBDD']=true;
			echo false;
		}
	}

	function esCoordinador($idProfesor, $idAsig){
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

	function editarProfesor($nombre, $apellidos, $email, $idProfesor) {
		$admin = $_SESSION['administrador'];
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos

		if($db){
			if ($admin) {
				$sql = "UPDATE `profesores` SET `nombre`='".$nombre."',`apellidos`='".$apellidos."',`email`='".$email."' WHERE id=".$idProfesor;
				$consulta=mysqli_query($db,$sql);
				$funciona = true;
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