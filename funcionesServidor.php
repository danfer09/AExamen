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
	* Función para enviar mail a través de GMail con cuerpo simple
	*/
	function smtpmailer($to, $from, $fromName, $subject, $body, $googleUser, $googlePassword) {
		global $error;
		$mail = new PHPMailer();  // creamos el objeto
		$mail->IsSMTP(); // activa SMTP
		$mail->SMTPDebug = 0;  // debugeo: 1 = errores y mensajes, 2 = sólo mensajes
		$mail->SMTPAuth = true;  // requerir autenticación
		$mail->SMTPSecure = 'ssl'; // transferencia segura activada OBLIGATORIO para GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = $googleUser;
		$mail->Password = $googlePassword;
		$mail->SetFrom($from, $fromName);
		$mail->Subject = $subject;
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

	/*
	* Función para enviar mail a través de GMail con cuerpo HTML
	*/
	function smtpmailerRaw($to, $from, $fromName, $subject, $body, $googleUser, $googlePassword) {
		global $error;
		$mail = new PHPMailer();   // creamos el objeto
		$mail->IsSMTP(); // activa SMTP
		$mail->SMTPDebug = 0;  // debugeo: 1 = errores y mensajes, 2 = sólo mensajes
		$mail->SMTPAuth = true;  // requerir autenticación
		$mail->SMTPSecure = 'ssl'; // transferencia segura activada OBLIGATORIO para GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->Username = $googleUser;
		$mail->Password = $googlePassword;
		$mail->SetFrom($from, $fromName);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		$mail->CharSet = 'ISO-8859';
		$mail->isHTML(true);
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo;
			return false;
		} else {
			$error = "\nMessage sent to ".$to."!";
			return true;
		}
	}

	/*
	* Función para formatear una fecha de formato estándar string a diferentes formatos según cuanto tiempo haya pasado hasta la actualidad
	*/
	function formateoDateTime ($fecha) {
		$time = strtotime($fecha);

		date_default_timezone_set("Europe/Madrid");

		$diff = (time() - $time)*1000; // la diferencia en milisegundos

		if ($diff < 1000) { // menos de un segundo
			return 'ahora mismo';
		}

		$sec = floor($diff / 1000); // diferencia en segundos

		if ($sec < 60) { // menos de un minuto
			return 'hace '.$sec.' seg.';
		}

		$min = floor($diff / 60000); // diferencia en minutos

		if ($min < 60) { // menos de una hora
			return 'hace '.$min.' min.';
		}

		//si el día coincide
		if ((date("d")==date('d',$time))) {
			return date('H:i',$time);
		}

		$newformat = date('H:i - d/m/Y',$time);

		return $newformat;
	}
?>
