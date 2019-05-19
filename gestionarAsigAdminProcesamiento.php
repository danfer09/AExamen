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
	//Comprobamos que el usuario sea un administrador
	$administrador = isset($_SESSION['administrador'])? $_SESSION['administrador']: false;
	/*En caso de que no sea un aadministrador lo redirigimos a la pagina principal*/
	if (!$administrador) {
		header('Location: index.php');
	}

	//Volcamos los parametros a variables y los tratamos
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
	$idProfSelect = isset($_POST['idProfSelect'])? $_POST['idProfSelect']: null;
	$idProfNoSelect = isset($_POST['idProfNoSelect'])? $_POST['idProfNoSelect']: null;
	if($funcion == "getProfesoresAdmin")
		getProfesoresAdmin($idAsig);
	else if ($funcion == "setCoordinadores")
		setCoordinadores($idAsig, $idProfSelect, $idProfNoSelect);

	/*Funcion que modifica los coordinadores que hay en una asignatura
	*
	*Funcion que dado un identificador de una asignatura modifica los profesores que
	*son cooordinadores y los profesores que no son coordinadores de la asignatura.
	*Estos profesores vienen definidos en dos listas que les pasamos por parametro
	*
	* @param int $idAsig identificador de la asignatura
	* @param string $idProfSelect string en formato json con los profesores que
	*	son coordinadores de la asignatura
	* @param string $idProfSelect string en formato json con los profesores que
	*	no son coordinadores de la asignatura
	* @return boolean $success devuelve true en caso de que la modificación se
	* haya llevado con exito y false en caso contrario */
	function setCoordinadores($idAsig, $idProfSelect, $idProfNoSelect){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$arrayIdProfSelect = json_decode($idProfSelect);
			$arrayIdProfNoSelect = json_decode($idProfNoSelect);

			for($i=0; $i < count($arrayIdProfSelect); $i++) {
				$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_profesor`='.$arrayIdProfSelect[$i].' and`id_asignatura`='.$idAsig;
				$consulta=mysqli_query($db,$sql);
				$fila=mysqli_fetch_assoc($consulta);
				if($fila['existe']){
					$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=1 WHERE `id_profesor`='.$arrayIdProfSelect[$i].' and`id_asignatura`='.$idAsig;
					$consulta=mysqli_query($db,$sql);
				}else{
					$sql = "INSERT INTO `prof_asig_coord`(`id_profesor`, `id_asignatura`, `coordinador`, `id`) VALUES (".$arrayIdProfSelect[$i].",".$idAsig.",1,'')";
					$consulta=mysqli_query($db,$sql);
				}
			}
			$arrayIdProfNoSelect = json_decode($idProfNoSelect);

			for($i=0; $i < count($arrayIdProfNoSelect); $i++) {
				$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_profesor`='.$arrayIdProfNoSelect[$i].' and`id_asignatura`='.$idAsig;
				$consulta=mysqli_query($db,$sql);
				$fila=mysqli_fetch_assoc($consulta);
				if($fila['existe']){
					$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=0 WHERE `id_profesor`='.$arrayIdProfNoSelect[$i].' and`id_asignatura`='.$idAsig;
					$consulta=mysqli_query($db,$sql);
				}
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			//header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		echo true;
	}



	/*Funcion que devuelve todas las asignaturas de la plataforma
	*
	*Funcion que nos devuelve un array con todas las asignaturas de la plataforma
	*
	* @return array con todas las asignaturas de la plataforma */
	function cargaTodasAsignaturas(){
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
			$sql = "SELECT * FROM `asignaturas`";
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

	/*Funcion que nos devuelve el numero de profesores de una asignatura
	*
	*Funcion que devuelve el numero de profesores que tiene la asignatura que le
	*pasamos por parametro. Le pasamos el id de la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @return int $lista numero de profesores de la asignatura*/
	function getNumeroProfesoresAsig($idAsig){
		/*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT COUNT(*) AS `numero_profesores` FROM `prof_asig_coord` WHERE id_asignatura=".$idAsig;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila;
	}

	/*Funcion que nos devuelve los profesores que coordinan una asignatura
	*
	*Funcion que dado el id de una asignatura devuelve el/los profesor/es que
	*coordinan la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @return $profesores_coord array con los profesores que coordinan la asignatura*/
	function getCoordinadores($idAsig){
		/*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_ningun_coord']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$i=0;
		$profesores_coord=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS nombre, profesores.apellidos AS apellidos, profesores.id AS id, profesores.email as email FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE asignaturas.id=".$idAsig." AND prof_asig_coord.coordinador=1";
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			/*Recorremos la consulta y vamos guardando sus resultados en un array*/
			while($fila){
				$profesores_coord[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
			/*En caso de que no haya ninguna asignatura, lo señalamos en
			la variable session que controla ese error*/
			if($i==0){
				$_SESSION['error_ningun_coord']=true;
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $profesores_coord;
	}

	/*Funcion que nos devuelve si un profesor es coordinador de una asignatura
	*
	*Funcion que dado el identificador de una asignatura y el identificador de
	*un profesor nos devuelve si ese profesor es coordinador de una asignatura
	*
	* @param	int $idAsig identificador de una asignatura
	* @param	int $idProfesor identificador de un profesor
	* @return boolean $resultado true en caso de que sea coordinador y false en caso
	*	contrario*/
	function esCoordinador($idAsig, $idProfesor){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$result=false;
		if($db){
			$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
			$consulta = mysqli_query($db,$sql);
			$result= mysqli_fetch_assoc($consulta);
		}
		return $result['coordinador'];
	}
	/*Funcion que nos devuelve los profesores que coordinan y que no coordinan
	*una asignatura
	*
  *Funcion que dado un identificador de una asignatura nos devuelve los profesores
	*que coordinan esa asignatura y los que no la coordinan
	*
	* @param	int $idAsig identificador de una asignatura
	* @return $resultado un array de dos posiciones, en la posicion 'profSiCoord'
	* los profesores que coordinan y en 'profNoCoord' los profesores no coordinan
	* la asignatura */
	function getProfesoresAdmin($idAsig) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;

		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT `nombre`, `apellidos`, `email`, `id` FROM `profesores`';
			$consulta=mysqli_query($db,$sql);
			$profNoCoord = [];
			$profSiCoord = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					if(esCoordinador($idAsig, $fila['id']))
						$profSiCoord[] = $fila;
					else
						$profNoCoord[] = $fila;
				}
			} else {
				$resultado = null;
			}
			mysqli_close($db);
			$resultado['profSiCoord']= $profSiCoord;
			$resultado['profNoCoord']= $profNoCoord;
			echo json_encode($resultado);
		} else {
			echo "Conexión fallida";
			$_SESSION['error_BBDD']=true;
			echo false;
		}
	}


?>
