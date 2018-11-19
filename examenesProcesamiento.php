<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	function cargaUnicoExamenPreguntas($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$preguntas=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql ="SELECT examenes.titulo AS titulo_examen, preguntas.titulo AS titulo_pregunta, exam_preg.id_examen, exam_preg.id_pregunta, exam_preg.id, examenes.creador AS creador_examen, examenes.fecha_creado AS fecha_creado_examen, examenes.fecha_modificado AS fecha_modificado_examen, examenes.ultimo_modificador AS ultimo_modificador_examen, preguntas.creador AS creador_pregunta,  preguntas.fecha_creacion AS fecha_creado_preguntas, preguntas.ult_modificador AS ultimo_modificador_pregunta, preguntas.fecha_modificado AS fecha_modificado_pregunta, preguntas.cuerpo, preguntas.tema
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

	function cargaAutorExamen($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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
	function cargaModificadorExamen($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	function cargaUnicoExamenInfo($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	/*
		Devuelve el resultado del select para todos los mails de profesores
	*/
	function selectAllMailsProfesores() {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if($db){
			$sql = "SELECT id, email, nombre, apellidos FROM profesores";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay profesores";
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado;
		} else {
			echo "Conexión fallida";
			return false;
		}
	}

	/*
		Devuelve el resultado del select para todas las siglas de las asignaturas
	*/
	function selectAllSiglasAsignaturas($db) {
		if($db){
			$sql = "SELECT siglas FROM asignaturas";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay asignaturas";
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado;
		} else {
			echo "Conexión fallida";
			return false;
		}
	}

	/*
		Devuelve el resultado del select para todos los examenes junto con creador, modificador y asignatura relacionados
	*/
	function selectAllExamenesCompleto($db) {
		if($db){
			$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura AND pac.id_profesor=".$_SESSION['id'];
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay exámenes";
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado;
		} else {
			echo "Conexión fallida";
			return false;
		}
	}

	/*
		Devuelve el resultado del select para ciertos examenes junto con creador, modificador y asignatura relacionados
	*/
	function selectAllExamenesFiltrado($db, $asignaturaSiglas, $autorMail) {
		if($db){
			$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura AND pac.id_profesor=".$_SESSION['id'];
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
				//echo "No hay exámenes";
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado;
		} else {
			echo "Conexión fallida";
			return false;
		}
	}

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idExamen = isset($_POST['id_examen'])? $_POST['id_examen']: null;
	if($funcion =="borrarExamen"){
		borrarExamen($idExamen);
	}

	/*
		Elimina el examen con el $id pasado por parámetro a la función
	*/
	function borrarExamen($idExamen){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos

		if($db){
			$sql = "DELETE FROM examenes WHERE id=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$funciona=true;
		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);

		echo $funciona;
		//INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','Titulo pregunta insertada','Cuerpo pregunta insertada','3','3','','3','','2')
	}

?>
