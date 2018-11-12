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
			return $resultado;
		} else {
			echo "Conexión fallida";
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
			return $resultado;
		} else {
			echo "Conexión fallida";
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
			$sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre FROM ((examenes e1 JOIN profesores p1) JOIN (profesores p2)) JOIN (asignaturas) WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig";
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
				return true;
			} else {
				echo "No existe el profesor con email ".$email." o está repetido";
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
				return true;
			} else {
				echo "No existe la asignatura con siglas ".$siglas." o está repetida";
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Elimina el examen con el $id pasado por parámetro a la función
	*/
	function deleteExamen($db, $id) {
		if($db && $id){
			$sql = "SELECT * FROM examenes WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM examenes WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				return true;
			} else {
				echo "No existe el examen con id ".$id;
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
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
		$mail->msgHTML(file_get_contents('mailRegistro.html'), __DIR__);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return false;
		} else {
			$error = 'Message sent!';
			return true;
		}
	}

?>