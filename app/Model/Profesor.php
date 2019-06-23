<?php
App::uses('AppModel', 'Model');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Profesor extends AppModel {
  public $useTable = 'profesores';
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
		$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id as id FROM `profesores`';
    $consulta=$this->query($sql);
		$resultado = [];
		if(count($consulta) > 0){
      $count = 0;
			while (count($consulta) > $count){
				$resultado[] = $consulta[$count]["profesores"];
        $count++;
			}
		} else {
			$resultado = null;
		}
		return $resultado;
	}

  /*Función que dado un profesor lo borra de la aplicacion
	*
	*Función que dado el identificador de un profesor lo borra de la aplicación
	*
	* @param int $id identificador de la profesor
	* @return boolean $funciona true en caso de que se haya borrado correctamente
	* y false en caso contrario*/
	function borrarProfesor($id) {
		$funciona=false;
		$admin = $_SESSION['administrador'];
		if ($admin) {
			$sql = 'DELETE FROM profesores WHERE id='.$id;
      $consulta=$this->query($sql);
			$funciona = true;
		} else {
			$_SESSION['error_no_poder_borrar'] = true;
		}
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
		if ($admin) {
			$sql = "UPDATE `profesores` SET `nombre`='".$nombre."',`apellidos`='".$apellidos."',`email`='".$email."' WHERE id=".$idProfesor;
      $consulta=$this->query($sql);
			$funciona = true;
		}
		echo $funciona;
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
		$sql = 'SELECT `nombre`, `siglas`, `id` FROM `asignaturas`';
    $consulta = $this->query($sql);
		$asigNoCoord = [];
		$asigSiCoord = [];
		if(count($consulta) > 0){
      $count = 0;
      $countConsulta = count($consulta);
			while ($countConsulta > $count){
				if($this->esCoordinador($consulta[$count]['asignaturas']['id'], $idProfesor)){
					$asigSiCoord[] = $consulta[$count]['asignaturas'];
        }
				else{
					$asigNoCoord[] = $consulta[$count]['asignaturas'];
        }
        $count++;
			}
		} else {
			$resultado = null;
		}
		$resultado['asigSiCoord']= $asigSiCoord;
		$resultado['asigNoCoord']= $asigNoCoord;
    echo json_encode($resultado);
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
	function esCoordinador($idAsig, $idProfesor){
		$result=false;
		$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
		$consulta = $this->query($sql);
    if(isset($consulta[0]["prof_asig_coord"]['coordinador'])){
      return $consulta[0]["prof_asig_coord"]['coordinador'];
    }
    else {
      return null;
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
		$sql = 'SELECT id_asignatura, coordinador, COUNT(coordinador) AS number_coord
				FROM `prof_asig_coord`
				WHERE id_asignatura = '.$idAsig.' AND id_profesor <> '.$idProfesor.'
				GROUP BY coordinador, id_asignatura
				HAVING coordinador = 1';
    $consulta = $this->query($sql);
    echo count($consulta);
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
		$arrayIdAsigSelect = json_decode($idAsigSelect);
		$arrayIdAsigNoSelect = json_decode($idAsigNoSelect);
		for($i=0; $i < count($arrayIdAsigSelect); $i++) {
			$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_asignatura`='.$arrayIdAsigSelect[$i].' and`id_profesor`='.$idProf;
      $consulta = $this->query($sql);
			if($consulta[0][0]['existe']){
				$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=1 WHERE `id_asignatura`='.$arrayIdAsigSelect[$i].' and`id_profesor`='.$idProf;
        $this->query($sql);
			}else{
				$sql = "INSERT INTO `prof_asig_coord`(`id_asignatura`, `id_profesor`, `coordinador`, `id`) VALUES (".$arrayIdAsigSelect[$i].",".$idProf.",1,'')";
        $this->query($sql);
			}

		}
		$arrayIdAsigNoSelect = json_decode($idAsigNoSelect);
		for($i=0; $i < count($arrayIdAsigNoSelect); $i++) {
			$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_asignatura`='.$arrayIdAsigNoSelect[$i].' and`id_profesor`='.$idProf;
      $consulta = $this->query($sql);
			if($consulta[0][0]['existe']){
				$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=0 WHERE `id_asignatura`='.$arrayIdAsigNoSelect[$i].' and`id_profesor`='.$idProf;
        $this->query($sql);
			}
		}
		echo "correct";
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
    $success = $this->smtpmailer($email, $credentials['webMail']['mail'], 'AExamen Web', 'Invitación AExamen', 'invitacion.html', $credentials['webMail']['mail'], $credentials['webMail']['password']);
		if (!$success) {
      $_SESSION['error_envio_mail'] = true;
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
}
