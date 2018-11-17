<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idExamen = isset($_POST['id_examen'])? $_POST['id_examen']: null;
	if($funcion =="borrarExamen"){
		borrarExamen($idExamen);
	}

	/*
		Devuelve el resultado del select para todos los profesores
	*/
	function selectAllProfesores($db) {
		if($db){
			$sql = "SELECT * FROM profesores";
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
		Devuelve el resultado del select para todos los nombres de profesores
	*/
	function selectAllNombresProfesores($db) {
		if($db){
			$sql = "SELECT id, nombre FROM profesores";
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
		Devuelve el resultado del select para todos los mails de profesores
	*/
	function selectAllMailsProfesores($db) {
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
		Devuelve el resultado del select para todas las asignaturas
	*/
	function selectAllAsignaturas($db) {
		if($db){
			$sql = "SELECT * FROM asignaturas";
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
		Devuelve el resultado del select para todos los examenes
	*/
	function selectAllExamenes($db) {
		if($db){
			$sql = "SELECT * FROM examenes";
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
		Devuelve el resultado del select para todos los examenes junto con creador, modificador y asignatura relacionados
	*/
	function selectAllExamenesCompleto($db) {
		if($db){
			$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre FROM ((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas) WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig";
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
			$sql = "SELECT e1.titulo, p1.nombre as creador, p1.email, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre, asignaturas.siglas FROM ((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas) WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig";
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

	/*
		Devuelve el resultado del select para todas las preguntas
	*/
	function selectAllPreguntas($db) {
		if($db){
			$sql = "SELECT * FROM preguntas";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay preguntas";
				$resultado = null;
			}
			mysqli_close($db);
			return $resultado;
		} else {
			echo "conexión fallida";
		}
	}

	/*
		Elimina el profesor con el $id pasado por parámetro a la función
	*/
	function deleteProfesor($db, $email) {
		if($db && $email){
			$sql = "SELECT * FROM profesores WHERE email='".$email."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM profesores WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				mysqli_close($db);
				return true;
			} else {
				echo "No existe el profesor con email ".$email." o está repetido";
				mysqli_close($db);
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Elimina la asignatura con el $id pasado por parámetro a la función
	*/
	function deleteAsignatura($db, $siglas) {
		if($db && $siglas){
			$sql = "SELECT * FROM asignaturas WHERE siglas='".$siglas."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM asignaturas WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				mysqli_close($db);
				return true;
			} else {
				echo "No existe la asignatura con siglas ".$siglas." o está repetida";
				mysqli_close($db);
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	
	/*function deleteExamen($db, $id) {
		if($db && $id){
			$sql = "SELECT * FROM examenes WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM examenes WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				mysqli_close($db);
				return true;
			} else {
				echo "No existe el examen con id ".$id;
				mysqli_close($db);
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}*/

	/*
		Elimina el examen con el $id pasado por parámetro a la función
	*/
	function borrarExamen($idExamen){
		$funciona=false;
		$credentialsStr = file_get_contents('credentials.json');
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

	/*
		Elimina la pregunta con el $id pasado por parámetro a la función
	*/
	function deletePreguntas($db, $id) {
		if($db && $id) {
			$sql = "SELECT * FROM preguntas WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM preguntas WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				return true;
			} else {
				echo "No existe la pregunta con id ".$id;
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Inserta en la base de datos un profesor nuevo que se acaba de registrar y validar
	*/
	function insertProfesor($db, $nombre, $apellidos, $email, $clave) {
		if($db) {
			$sql = "INSERT INTO profesores (nombre, apellidos, email, id, clave, coordinador) VALUES ('".$nombre."','".$apellidos."','".$email."',null,'".$clave."',false)";
			if (mysqli_query($db,$sql)) {
				echo "Nuevo profesor añadido";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		} else {
			printf("Error message: %s\n", $db->error);
			echo "Conexión fallida";
		}
	}

	function updateClaveProfesor($db, $email, $hash) {
		if($db) {
			$sql = "UPDATE profesores SET clave='".$hash."' WHERE email='".$email."'";
			if (mysqli_query($db,$sql)) {
				echo "Nueva contraseña establecida";
				return true;
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($db);
				return false;
			}
		} else {
			printf("Error message: %s\n", $db->error);
			echo "Conexión fallida";
		}
	}

	/*
		Comprueba si un determinado profesor con el $id pasado por parámetro es coordinador de alguna asignatura
	*/
	function esCoordinador ($db, $id) {
		if($db){
			$sql = "SELECT * FROM profesores WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows > 0){
				$fila=mysqli_fetch_assoc($consulta);
				return $fila['coordinador'];
			} else {
				echo "No existe el profesor con id ".$id;
				return null;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Comprueba, para el mail dado, si esa es su contraseña
	*/
	function anteriorPasswordCorrecta($db, $email, $pass) {
		if($db){
			$sql = "SELECT * FROM profesores WHERE email='".$email."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows > 0){
				$fila=mysqli_fetch_assoc($consulta);
				return (password_verify($pass, $fila['clave']));
			} else {
				echo "No existe el profesor con email ".$email;
				return null;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	function smtpmailer($to, $from, $fromName, $subject, $body, $googleUser, $googlePassword) { 
		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465; 
		$mail->Username = $googleUser;  
		$mail->Password = $googlePassword;           
		$mail->SetFrom($from, $fromName);
		$mail->Subject = $subject;
		//$mail->Body = $body;
		$mail->AddAddress($to);
		$mail->CharSet = 'ISO-8859';
		$mail->msgHTML(file_get_contents($body), __DIR__);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return false;
		} else {
			$error = "\nMessage sent to ".$to."!";
			return true;
		}
	}

	function formateoDateTime ($fecha) {
		$time = strtotime($fecha);

		$diff = (time() - $time)*1000; // the difference in milliseconds
		
		if ($diff < 1000) { // less than 1 second
			return 'ahora mismo';
		}

		$sec = floor($diff / 1000); // convert $diff to seconds

		if ($sec < 60) {
			return 'hace '.$sec.' seg.';
		}

		$min = floor($diff / 60000); // convert $diff to minutes
		if ($min < 60) {
			return 'hace '.$min.' min.';
		}

		if ((date("j")==date('j',$time))) {
			return date('G:i',$time);
		}

		$newformat = date('d/m/Y H:i',$time);
	
		return $newformat;
	}

?>