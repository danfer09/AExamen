<?php
App::uses('AppModel', 'Model');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Registro extends AppModel {
  public $useTable = 'profesores';

  public function registrarse($email, $nombre, $apellidos, $clave, $texto){
    $sql = "SELECT * FROM profesores WHERE email='".$email."'";
    $consulta=$this->query($sql);

    if(count($consulta) > 0) {
      $_SESSION['error_usuario_existente']=true;
      return false;
    } else {
      $_SESSION['error_envio_mail'] = false;
      $credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
      if (!$this->smtpmailer($email, $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro AExamen', 'registroAexamen-mail.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
        $_SESSION['error_envio_mail'] = true;
      }

      date_default_timezone_set('Europe/Berlin');
      $date = date('Y-m-d H:i:s', time());
      $claveHash = password_hash($clave, PASSWORD_BCRYPT);
      $sql = "INSERT INTO `peticiones_registro`(`id`, `nombre`, `apellidos`, `email`, `fecha`, `texto`, `clave`) VALUES ('','".$nombre."','".$apellidos."','".$email."','".$date."', '".$texto."', '".$claveHash."')";
      $consulta=$this->query($sql);

      return true;
    }
  }

  public function smtpmailer($to, $from, $fromName, $subject, $body, $googleUser, $googlePassword) {
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

}
