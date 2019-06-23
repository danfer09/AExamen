<?php
App::uses('AppModel', 'Model');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class PanelControl extends AppModel {
  public $useTable = 'peticiones_registro';

  /*
	* Funcion que devuelve todasl las peticiones pendientes
	* @return array $resultado con las peticiones pendientes existentes
	*/
	function getPeticiones() {
		$sql = 'SELECT * FROM `peticiones_registro`';
    $consulta=$this->query($sql);
		$resultado = [];
		if(count($consulta) > 0){
      $count = 0;
      $totalConsulta = count($consulta);
			while ($totalConsulta > $count){
        /*-------------------*/
				// $resultado[] = $consulta[$count]["peticiones_registro"];
        // $count++;
        /*-----------------------------*/
        foreach ($consulta[$count]["peticiones_registro"] as $key => $value) {
					$resultado[$count][$key] = $consulta[$count]["peticiones_registro"][$key];
				}
				$resultado[$count]["fecha_raw"] = $this->formateoDateTime($consulta[$count]["peticiones_registro"]["fecha"]);
        $count++;
        /*---------------------*/
			}
		} else {
			$resultado = null;
		}
		return $resultado;
	}

  /*
	*Funcion que elimina el archivo de log existente y crea uno nuevo, escribiendo en él que se ha eliminado el log correctamente
	*/
	function eliminarLog() {
		if (!unlink('./log/log_AExamen.log')){
			echo "Error deleting file!";
		} else {
			$log  = "  ___  _____
 / _ \|  ___|
/ /_\ \ |____  ____ _ _ __ ___   ___ _ __
|  _  |  __\ \/ / _` | '_ ` _ \ / _ \ '_ \
| | | | |___>  < (_| | | | | | |  __/ | | |
\_| |_|____/_/\_\__,_|_| |_| |_|\___|_| |_|
                                           ".PHP_EOL."-------------------------LOG STARTS HERE-------------------------".PHP_EOL.PHP_EOL.
				'['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
		        " | ACTION --> Eliminar log ".PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
			//Save string to log, use FILE_APPEND to append.
			file_put_contents('./log/log_AExamen.log', utf8_decode($log));
			echo "Log eliminado correctamente";
		}
	}

  /*
	*Funcion que reinicia el archivo de log existente sobreescribiendo en él que se ha reiniciado el log correctamente
	*/
	function reiniciarLog() {
		$log  = "  ___  _____
 / _ \|  ___|
/ /_\ \ |____  ____ _ _ __ ___   ___ _ __
|  _  |  __\ \/ / _` | '_ ` _ \ / _ \ '_ \
| | | | |___>  < (_| | | | | | |  __/ | | |
\_| |_|____/_/\_\__,_|_| |_| |_|\___|_| |_|
                                           ".PHP_EOL."-------------------------LOG STARTS HERE-------------------------".PHP_EOL.PHP_EOL.
				'['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
		        " | ACTION --> Reiniciar log ".PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents('./log/log_AExamen.log', utf8_decode($log));
		echo "Log reiniciado correctamente";
	}

  /*
	* Funcion que devuelve/muestra (para AJAX) una peticion en concreto
	* @param int $id identificador de la peticion
	* @return array $resultado con la peticion con identificador id
	*/
	function getPeticion($id) {
			$sql = 'SELECT * FROM `peticiones_registro` WHERE id='.$id;
      $consulta=$this->query($sql);
      $resultado = [];
      if(count($consulta) > 0){
        $count = 0;
        while (count($consulta) > $count){
          $resultado[] = $consulta[$count]["peticiones_registro"];
          $count++;
        }
      } else {
        $resultado = null;
      }
      echo json_encode($resultado);
	}

  /*
	*Funcion que deniega una petición de registro a AExamen, lo anota en el log y envia un email al usuario informando que su petición ha sido denegada
	* @param int $id identificador de la peticion
	* @return boolean $resultado true si se ha denegado correctamente y false en caso contrario
	*/
	function borrarPeticion($id) {
    $credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$peticion = $this->getPeticionReturn($id);
		$sql = 'DELETE FROM `peticiones_registro` WHERE id='.$id;
    $this->query($sql);

		//Something to write to txt log
		$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
		        " | ACTION --> Denegar petición #".$id.' de '.$peticion['email'].' - '.$peticion['apellidos'].', '.$peticion['nombre'].PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

		$_SESSION['error_envio_mail'] = false;
		if ($this->smtpmailer($peticion['email'], $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro denegada (AExamen)', 'solicitudDenegada.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
		} else {
			$_SESSION['error_envio_mail'] = true;
		}

		$resultado = true;
		echo json_encode($resultado);
	}

  /*
	*Funcion que acepta una petición de registro a AExamen, da de alta al nuevo profesor en la tabla correspondiente, lo anota en el log y envia un email al usuario informando que ya puede iniciar sesión
	* @param int $id identificador de la peticion
	* @return boolean $resultado true si se ha aceptado correctamente y false en caso contrario
	*/
	function aceptarPeticion($id) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$peticion = $this->getPeticionReturn($id);
		$sql = 'DELETE FROM `peticiones_registro` WHERE id='.$id;
    $this->query($sql);

		$sql = "INSERT INTO `profesores`(`nombre`, `apellidos`, `email`, `id`, `clave`) VALUES ('".$peticion['nombre']."','".$peticion['apellidos']."','".$peticion['email']."','','".$peticion['clave']."')";
    $this->query($sql);

		//Something to write to txt log
		$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
		        " | ACTION --> Aceptar petición #".$id.' de '.$peticion['email'].' - '.$peticion['apellidos'].', '.$peticion['nombre'].PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
		//Save string to log, use FILE_APPEND to append.
		file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

		$_SESSION['error_envio_mail'] = false;
		if ($this->smtpmailer($peticion['email'], $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro aceptada (AExamen)', 'solicitudAceptada.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
		} else {
			$_SESSION['error_envio_mail'] = true;
		}
		echo true;
	}

  /*
	* Funcion que devuelve (para PHP) una peticion en concreto
	* @param int $id identificador de la peticion
	* @return array $resultado con la peticion con identificador id
	*/
	function getPeticionReturn($id) {
		$sql = 'SELECT * FROM `peticiones_registro` WHERE id='.$id;
    $consulta=$this->query($sql);
    if(count($consulta) > 0){
      $resultado = $consulta[0]["peticiones_registro"];
    } else {
      $resultado = null;
    }
    return $resultado;
	}

  function descargarLog() {
    if(isset($_REQUEST["file"])){
      // Obtenemos parámetros
      $file = urldecode($_REQUEST["file"]); // Decodificación de la url del archivo
      $filepath = "log/" . $file;

      // Proceso de descarga del archivo de log
      if(file_exists($filepath)) {
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($filepath));
          flush();
          readfile($filepath);
          exit;
      }
    }
  }

  /*
	* Función para enviar mail a través de GMail con cuerpo simple
	*/
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

  /*
	* Función para formatear una fecha de formato estándar string a diferentes formatos según cuanto tiempo haya pasado hasta la actualidad
	*/
	public function formateoDateTime ($fecha) {
		date_default_timezone_set("Europe/Madrid");

		$time = strtotime($fecha);

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
		if ((date("d")==date('d',$time)) && (date("m")==date('m',$time)) && (date("Y")==date('Y',$time))) {
			return date('H:i',$time);
		}

		$newformat = date('H:i - d/m/Y',$time);

		return $newformat;
	}

}
