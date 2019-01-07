<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';

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