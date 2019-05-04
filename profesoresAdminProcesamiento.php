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

	include 'funcionesServidor.php';

	//Cargamos en variables todos los parametros que nos hayan llegado por POST
	$nombre = isset($_POST['nombre'])? $_POST['nombre']: null;
	$apellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
	$email = isset($_POST['email'])? $_POST['email']: null;
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;
	$idAsigSelect = isset($_POST['idAsigSelect'])? $_POST['idAsigSelect']: null;
	$idAsigNoSelect = isset($_POST['idAsigNoSelect'])? $_POST['idAsigNoSelect']: null;
	$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;

	//comprobamos los valores de las variables y en consecuencia llamamos a las
	//diferentes funciones
	if ($email && $funcion==null) {
		invitarProfesor($email);
	}

	if($funcion == "borrarProfesor")
		borrarProfesor($idProfesor);
	else if($funcion == "editarProfesor")
		editarProfesor($nombre,$apellidos,$email,$idProfesor);
	else if($funcion == "getAsignaturas")
		getAsignaturas($idProfesor);
	else if($funcion == "setCoordinadores"){
		setCoordinadores($idProfesor, $idAsigSelect, $idAsigNoSelect);
	}
	else if($funcion == "isAsigWithCoord"){
		isAsigWithCoord($idAsig, $idProfesor);
	}

	/*Función que invita a un profesor a la aplicación
	*
	*Funcion que dado un email, invita a ese usuario a la aplicacion
	*
	* @param string $email email valido */
	function invitarProfesor($email) {
		$_SESSION['error_envio_mail'] = false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		if (smtpmailer($email, $credentials['webMail']['mail'], 'AExamen Web', 'Invitación AExamen', 'invitacion.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
			header('Location: profesoresAdmin.php');
		} else {
			$_SESSION['error_envio_mail'] = true;
			header('Location: profesoresAdmin.php');
		}
	}

	/*Función que define dado un profesor define que asignaturas coordina y cuales no.
	*
	*Funcion que dado el identificador de un profesor, un array con los identifiadores
	*de las asignaturas que coordina y otro con las que no coordina define que
	*asignaturas coordina ese profesor
	*
	* @param int $idProf identificador del profesor
	* @param $idAsigSelect identificadores de asignaturas seleccionadas
	* @param $idAsigNoSelect identificadores de asignaturas no seleccionadas
	*/
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
		}
		mysqli_close($db);
		echo "correct";
	}

	/*Función que nos devuelve las asignaturas que coordina y que no coordina
	* un profesor.
	*
	*Funcion que dado un id de un profesor, nos devuelve un array con las asignaturas
	*que coordina y que no coordina un profesor
	*
	* @param int $idProfesor identificador del profesor
	* @return $resultado array con las asignaturas que coordina y
	* que no coordina un profesor */
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

	/*Función que nos devuelve si cuantos coordinadores hay ademas que el profesor
	* que le pasamos por parametro
	*
	*Funcion que pasandole el id de una asignartura y el id de un profesor nos
	*devuelve 0 si no hay ningun coordinador en esa asignatura salvo el profesor
	*que le pasamos por parametro o mas de 0 en caso de que haya más coordinadores
	*para esta asignatura ademas de el que le pasamos por parametro
	*
	* @param int $idAsig identificador de la asignatura
	* @param int $idProfesor identificador del profesor
	* @return int $resultado numero de profesores que coodinan la asignatura, false
	* en caso de que haya fallado la conexion con la BBDD*/
	function isAsigWithCoord($idAsig, $idProfesor) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$_SESSION['error_BBDD']=false;

		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = 'SELECT id_asignatura, coordinador, COUNT(coordinador) AS number_coord
					FROM `prof_asig_coord`
					WHERE id_asignatura = '.$idAsig.' AND id_profesor <> '.$idProfesor.'
					GROUP BY coordinador, id_asignatura
					HAVING coordinador = 1';
			$consulta=mysqli_query($db,$sql);
			$row_cnt = mysqli_num_rows($consulta);
			echo $row_cnt;
			die();
			mysqli_close($db);
			echo json_encode($resultado);
		} else {
			echo "Conexión fallida";
			$_SESSION['error_BBDD']=true;
			echo false;
		}
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

	/*Función que dado un profesor lo borra de la aplicacion
	*
	*Función que dado el identificador de un profesor lo borra de la aplicación
	*
	* @param int $id identificador de la profesor
	* @return boolean $funciona true en caso de que se haya borrado correctamente
	* y false en caso contrario*/
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

	/*Funcion que edita los campos de un profesor
	*
	*Función que dado un nombre, unos apellidos, un email y un identificador de
	*profesor edita los datos de ese profesor
	*
	* @param string $nombre nombre que queremos establecer al profesor
	* @param string $apellidos apellidos que queremos establecer al profesor
	* @param string $email email que queremos establecer al profesor
	* @param int $idProfesor identificador de un profesor
	* @return boolean $funciona true en caso de que se haya editado correctamente
	* y false en caso contrario*/
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

	/*Funcion que nos devuelve todos los profesores de la aplicacion
	*
	*Función que nos devuelve todos los profesores de la aplicacion en un array,
	*null en caso de que no haya profesores y false en caso de que haya habido un
	*fallo con la conexion de la BBDD
	*
	* @return  $resultado array con todos los profesores de la aplicacion, null
	* en caso de que no haya profesores y false en caso de que haya habido un
	* fallo con la conexion de la BBDD*/
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
