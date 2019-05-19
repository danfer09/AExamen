<?php
	//Comprobamos si el usuario esta logeado
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php*/
	if (!$logeado) {
		header('Location: index.php');
	}

	/*Función que nos devuelve las assignaturas de un profesor.
	*
	*Funcion que dado un id de un profesor, devuelve un array con las asignaturas que
	*tiene ese profesor. En caso de que haya un error se los pasamos por la variables
	*Session a la vista para que lo muestre en consideracion
	*
	* @param int $idProfesor identificador del profesor
	* @return $asignaturas array con las asignaturas que tiene el profesor */
	function cargaAsignaturas($idProfesor){
		/*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_ningunaAsignatura']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$i=0;
		$asignaturas=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT prof_asig_coord.coordinador AS coordinador, profesores.nombre AS nombre_profesor, asignaturas.nombre AS nombre_asignatura, asignaturas.siglas AS siglas_asignatura, asignaturas.id AS id_asignatura FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE id_profesor=".$idProfesor;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			/*Recorremos la consulta y vamos guardando sus resultados en un array*/
			while($fila){
				$asignaturas[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
			/*En caso de que no haya ninguna asignatura, lo señalamos en
			la variable session que controla ese error*/
			if($i==0){
				$_SESSION['error_ningunaAsignatura']=true;
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $asignaturas;
	}
?>
