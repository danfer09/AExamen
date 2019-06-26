<?php
App::uses('AppModel', 'Model');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Login extends AppModel {
  public $useTable = 'profesores';

  public function acceso($email, $clave){
				$sql = "SELECT * FROM profesores";
				$fila=$this->query($sql);
				$encontrado=false;
        $i=0;
				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontrado && $fila[$i]['profesores']){
					if($email==$fila[$i]['profesores']['email']){
						$encontrado=true;
            $filaEncontradaUser = $fila[$i]['profesores'];
					}
          else{
            $i++;
          }
				}

				$sql = "SELECT * FROM administradores";
        $fila=$this->query($sql);
				$encontradoAdmin=false;
        $i=0;

				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontradoAdmin && $fila[$i]['administradores']){
					if($email==$fila[$i]['administradores']['email']){
						$encontradoAdmin=true;
            $filaEncontradaAdmin = $fila[$i]['administradores'];
					}
					else{
            $i++;
					}
				}
				/*Si no encotramos el nombre del usario ponemos a true la variable de error al autenticar y redirigimos a loginFormulario.php donde la tratamos*/
				if(!$encontrado && !$encontradoAdmin){
					$_SESSION['error_autenticar']=true;
					return false;
				}
				else{
					//Verificamos la clave con esta funcion ya que en la BBDD esta encriptada, en caso de que se verifique, declaramos e inicializamos todas las variables de session de usuario.
          $datos = ($encontrado) ? $filaEncontradaUser : $filaEncontradaAdmin;
					if(password_verify($clave, $datos['clave'])){
						$_SESSION['logeado']=true;
						$_SESSION["email"] = $email;
						$_SESSION["nombre"]=$datos['nombre'];
						$_SESSION['apellidos']=$datos['apellidos'];
						$_SESSION['id']=$datos['id'];
						$_SESSION['administrador'] = $encontradoAdmin;
						date_default_timezone_set("Europe/Madrid");
						//Registramos este login en el log
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> Inicio de sesión ".' de '.$_SESSION['email'].PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						file_put_contents('log/log_AExamen.log', utf8_decode($log), FILE_APPEND);


            return true;
					}
					/*En caso de que la clave no coincida con el usuairo ponemos a true la variable de error al autentiacar y redirigimos a loginFormulario.php donde la tratamos*/
					else{
						$_SESSION['error_autenticar']=true;
            return false;
          }
				}
    }

    public function olvideContrasenia($email){
      $sql = "SELECT * FROM profesores WHERE email='".$email."'";
      $fila=$this->query($sql);
      if(count($fila) <= 0) {
        $_SESSION['error_usuario_no_existente']=true;
        return false;
      } else if (count($fila) == 1) {
        $codigo = password_hash($email, PASSWORD_BCRYPT);
        $credentialsStr = file_get_contents('json/credentials.json');
  			$credentials = json_decode($credentialsStr, true);
        //Envío del email que permitirá al usuario reestablecer su contraseña olvidada
        if ($this->smtpmailerRaw($email, $credentials['webMail']['mail'], 'AExamen Web', 'Reestablecer la contraseña', '<!DOCTYPE html>
<html>
<head>
<title>Reestablecer contraseña</title>
<!--css externos-->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<meta charset="UTF-8">
</head>
<body>
<div class="row">
  <h2 class="col-lg-12">Haga click sobre el siguiente enlace para reestablecer su contraseña:</h2>
  <span class="col-lg-2"></span>
  <a class="col-lg-4" href="http://aexamencakephp.epizy.com/logins/restablecer_contrasenia?authenticate='.$codigo.'">REESTABLECER</a>
  <span class="col-lg-6"></span>
  <p class="col-lg-12">¡Gracias!</p>
</div>
</body>
</html>', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
          $_SESSION['confirmado'] = false;
          $_SESSION['emailTemp'] = $email;
          $_SESSION['emailTempClave'] = $codigo;
          return true;
        }
      }

  }

  public function smtpmailerRaw($to, $from, $fromName, $subject, $body, $googleUser, $googlePassword) {
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
    $mail->Body = $body;
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

  /*Función que actualiza la clave de un profesor en base de datos
	*
	* Función que dado un email de profesor y la contraseña hasheada
	* actualiza en base de datos la contraseña de este profesor
	*
	* @param string $email correo electrónico del profesor
	* @param string $hash contraseña hasheada del profesor
	*
	* @return string (AJAX) mensaje de éxito o fallo en la operación de actualización
	*/
	function updateClaveProfesor($email, $hash) {
		$sql = "UPDATE profesores SET clave='".$hash."' WHERE email='".$email."'";
    $consulta = $this->query($sql);
		return true;
	}

}
