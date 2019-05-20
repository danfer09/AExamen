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

	/*Función que carga las preguntas de un examen dado
	*
	*Funcion que dado un identificador de examen nos devuelve las preguntas
	*que tiene ese examen en forma de array
	*
	* @param int $idExamen identificador de examen
	* @return $preguntas array con las preguntas del examen */
	function cargaUnicoExamenPreguntas($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$preguntas=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql ="SELECT examenes.titulo AS titulo_examen, preguntas.titulo AS titulo_pregunta, exam_preg.id_examen, exam_preg.id_pregunta AS id_pregunta, exam_preg.id, examenes.creador AS creador_examen, examenes.fecha_creado AS fecha_creado_examen, examenes.fecha_modificado AS fecha_modificado_examen, examenes.ultimo_modificador AS ultimo_modificador_examen, preguntas.creador AS creador_pregunta,  preguntas.fecha_creacion AS fecha_creado_preguntas, preguntas.ult_modificador AS ultimo_modificador_pregunta, preguntas.fecha_modificado AS fecha_modificado_pregunta, preguntas.cuerpo, preguntas.tema
				FROM ((exam_preg INNER JOIN examenes ON exam_preg.id_examen =examenes.id) INNER JOIN preguntas ON preguntas.id=exam_preg.id_pregunta) WHERE exam_preg.id_examen=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$i=0;
			while($fila){
				$preguntas[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $preguntas;
	}

	/*Función que carga el autor de un examen
	*
	*Funcion que dado el identificador de un examen nos de un examen
	*nos devuelve el autor del examnen
	*
	* @param int $idExamen identificador de examen
	* @return $fila['autor'] identificardor del profesor que ha creado el examen*/
	function cargaAutorExamen($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS autor FROM examenes INNER JOIN profesores ON examenes.creador=profesores.id WHERE examenes.id=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila['autor'];

	}

	/*Función que carga el ultimo profesor que ha modificado un examen
	*
	*Funcion que dado un identificador de examen nos devuelve el identificardor
	*del ultimo profesor que lo ha modificado
	*
	* @param int $idExamen identificador de examen
	* @return $fila['modificador'] de el ultimo profesor que ha modificado el examen */
	function cargaModificadorExamen($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS modificador FROM examenes INNER JOIN profesores ON examenes.ultimo_modificador=profesores.id WHERE examenes.id=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila['modificador'];

	}

	/*Función que carga la información de un examen dado
	*
	*Funcion que, dado un identificador de un examen, carga la informacion básica de este
	*
	* @param int $idExamen identificador de examen
	* @return $fila array con la información del examen */
	function cargaUnicoExamenInfo($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT * FROM `examenes` WHERE id=".$idExamen;
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

	/*Función que comprueba si el profesor puede acceder a un examen
	*
	* Funcion que, dado un identificador de una asignatura a la que pertenece un examen, comprueba si puede visualizarlo o no
	*
	* @param int $idAsignatura identificador de asignatura
	* @return $acceso boolean true si puede acceder, false en caso contrario
	*/
	function comprobarAcceso($idAsignatura) {
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$asignaturas = array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT asignaturas.id as id FROM prof_asig_coord INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id WHERE id_profesor=".$_SESSION['id'];
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$acceso = false;
			$i=0;
			while($fila){
				$asignaturas[$i]=$fila;
				if ($fila['id']==$idAsignatura) {
					$acceso = true;
				}
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $acceso;
	}

	/*Funcion que nos devuelve los profesores de una asignatura
	*
	*Funcion que dadas las siglas de una asignatura nos devuelve un array con los
	*profesores que esta tiene
	*
	* @param string $asignaturaSiglas siglas de la asignatura de la que queremos
	*lo profesores
	* @return $resultado array con los profesores que tiene la asignatura, en caso de
	* que la asignatura no tiene profesores devolvemos false  */
	function selectAllMailsProfesoresSiglas($asignaturaSiglas) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = "SELECT distinct profesores.id as id, profesores.email as email, profesores.nombre as nombre, profesores.apellidos as apellidos FROM (profesores inner join prof_asig_coord on profesores.id=prof_asig_coord.id_profesor) inner join asignaturas on asignaturas.id=prof_asig_coord.id_asignatura ";
			if ($asignaturaSiglas != 'todas' ) {
				$sql = $sql."WHERE asignaturas.siglas='".$asignaturaSiglas."'";
			}
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
			return false;
		}
	}

	/*Funcion que nos devuelve los profesores de una asignatura
	*
	*Funcion que dado el identificador de una asignatura nos devuelve un array con los
	*profesores que esta tiene
	*
	* @param string $idAsignatura identificador de la asignatura de la que queremos
	*lo profesores
	* @return $resultado array con los profesores que tiene la asignatura, en caso de
	* que la asignatura no tiene profesores devolvemos false  */
	function selectAllMailsProfesoresId($idAsignatura) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = "SELECT profesores.id as id, profesores.email as email, profesores.nombre as nombre, profesores.apellidos as apellidos FROM profesores inner join prof_asig_coord on profesores.id=prof_asig_coord.id_profesor WHERE prof_asig_coord.id_asignatura=".$idAsignatura;
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
			return false;
		}
	}

	/*Funcion que nos devuelve todas las asignaturas de la plataforma
	*
	*Funcion que pasandole un enlace a la BBDD nos devuelve todas las asignaturas
	*que hay dadas de alta en el sistema
	*
	* @param $db conexion con la BBDD
	* @return $resultado array con las asignaturas que tiene el sistema, en caso
	* de que no haya asignaturas en el sistema devuelve false  */
	function selectAllSiglasAsignaturas($db) {
		if($db){
			$sql = "SELECT siglas, id FROM asignaturas";
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
			return false;
		}
	}

	/*Funcion que nos devuelve las siglas y el identificador de todas las asignaturas
	* de la plataforma
	*
	*Funcion que pasandole un enlace a la BBDD nos devuelve la siglas y el identificador
	*de todas las asignaturas que hay dadas de alta en el sistema
	*
	* @param $db conexion con la BBDD
	* @return $resultado array con las siglas y el identificador de todas las
	* asignaturas que tiene el sistema, en caso de que no haya asignaturas en el
	* sistema devuelve false  */
	function selectAllSiglasAsignaturasProfesor($db, $idProfesor) {
		if($db){
			$sql = "SELECT asignaturas.siglas, asignaturas.id FROM asignaturas INNER JOIN prof_asig_coord on asignaturas.id = prof_asig_coord.id_asignatura WHERE prof_asig_coord.id_profesor=".$idProfesor;
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
			return false;
		}
	}

	/*Devuelve todos los examenes del sistema con la informacion de cada uno de ellos
	*
	*Funcion que pasandole un enlace a la BBDD nos todos la información de todos los
	*examenes que hay en el sistema
	*
	* @param $db conexion con la BBDD
	* @return $resultado array con los examenes de todo el sistema y su respectiva informacion */
	function selectAllExamenesCompleto($db) {
		if($db){
			if (!$_SESSION['administrador']) {
				$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura AND pac.id_profesor=".$_SESSION['id'];
			} else {
				$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig";
			}
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
			return false;
		}
	}

	/*Devuelve todos los examenes de un autor y de una asignatura determinada
	*
	*Funcion que pasandole un enlace a la BBDD, las siglas de la asignatura y
	*el mail del autor nos devuelve un array con todos la informacion de todos los
	*examenes de esa asignatura y de ese autor
	*
	* @param $db conexion con la BBDD
	* @param string $asignaturaSiglas siglas de la asignatura
	* @param string $autorMail mail del autor
	* @return $resultado array con los examenes y su respectiva informacion */
	function selectAllExamenesFiltrado($db, $asignaturaSiglas, $autorMail) {
		if($db){
			if (!$_SESSION['administrador']) {
				$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura AND pac.id_profesor=".$_SESSION['id'];
			} else {
				$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura";
			}
			if ($asignaturaSiglas != "todas") {
				$sql = $sql." AND asignaturas.siglas='".$asignaturaSiglas."' ";
			}
			if ($autorMail != "todos") {
				$sql = $sql." AND p1.email='".$autorMail."' ";
			}
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
			return false;
		}
	}

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idExamen = isset($_POST['id_examen'])? $_POST['id_examen']: null;
	if($funcion =="borrarExamen"){
		borrarExamen($idExamen);
	}

	/*Borra el examen que le indicamos por parametro
	*
 	* Funcion que dado el identificador de un examen borra dicho examen del sistema
	*devolviendo true en caso de que se haya borrado correctamente y false en caso
	*contrario
	*
	* @param int $idExamen identificador del examen
	* @return boolean $funciona true si se ha borrado el examen con exito y false en
	*caso contrario */
	function borrarExamen($idExamen){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		//comprobamos si se ha conectado a la base de datos
		if($db){
			$preguntas = cargaUnicoExamenPreguntas($idExamen);
			foreach ($preguntas as $pregunta) {
				$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` - 1 WHERE id=".$pregunta['id_pregunta'];
				mysqli_query($db,$sqlReferencia);
			}
			$sql = "DELETE FROM examenes WHERE id=".$idExamen;
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
	/*Carga el historial de un examen indicado
	*
	*Funcion que dado un identificador de un examen nos devuelve el historial
	*de modificaciones de dicho examen
	*
	* @param int $idExamen identificador del examen
	* @return $historial historial de modificaciones del examen */
	function cargaHistorialExamen($idExamen) {
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT `id`, `idExamen`, `idModificador`, `fecha_modificacion` FROM `examenes_historial` WHERE `idExamen`=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$historial=array();
			$i=0;
			while($fila){
				$historial[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: detalleExamen.php?id='.$idExamen);
		}
		mysqli_close($db);
		return $historial;
	}

?>
