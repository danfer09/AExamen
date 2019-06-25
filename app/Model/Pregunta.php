<?php
App::uses('AppModel', 'Model');

class Pregunta extends AppModel {

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
		$sql = "SELECT profesores.id as id, profesores.email as email, profesores.nombre as nombre, profesores.apellidos as apellidos FROM profesores inner join prof_asig_coord on profesores.id=prof_asig_coord.id_profesor WHERE prof_asig_coord.id_asignatura=".$idAsignatura;
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

	/*Funcion que dado una asigntura y un profesor nos devuleve las preguntas de ese
	*profesor de esa asignatura
	*
	*Funcion que dado el identificador de una asignatura y el email de un profesor
	*nos devuelve las preguntas de ese profesor en esa asignatura. En caso de que
	*le pasemos en vez del mail de un profesor el string 'todos', nos devolvera
	*todas las preguntas de una asignatura
	*
	* @param int $idAsignatura identificador de una asignatura
	* @param string $emailAutor email de un profesor. 'todos' si queremos todas
	*	las preguntas de la asignatura
	* @return $preguntas array con las preguntas */
	function cargaPreguntas($idAsignatura, $emailAutor){
		$sql = "SELECT asignaturas.siglas AS siglasAsignatura,  profesores.nombre AS autor, preguntas.titulo AS titulo, preguntas.cuerpo AS cuerpo, preguntas.tema AS tema, preguntas.fecha_creacion AS fecha_creacion, preguntas.fecha_modificado AS fecha_modificado, preguntas.id AS id_preguntas
			FROM ((preguntas INNER JOIN asignaturas ON asignaturas.id =".$idAsignatura.") INNER JOIN profesores ON preguntas.creador=profesores.id)
			WHERE preguntas.asignatura=".$idAsignatura;

		if ($emailAutor != "todos") {
			$sql = $sql." AND profesores.email='".$emailAutor."'";
		}
		$consulta=$this->query($sql);
		$resultado = [];

		if(count($consulta) > 0){
      $count = 0;
			while (count($consulta) > $count){
				foreach ($consulta[$count]["preguntas"] as $key => $value) {
					$resultado[$count][$key] = $consulta[$count]["preguntas"][$key];
				}
				$resultado[$count]["autor"] = $consulta[$count]["profesores"]["autor"];
				$resultado[$count]["fecha_creado_raw"] = $this->formateoDateTime($consulta[$count]["preguntas"]["fecha_creacion"]);
				$resultado[$count]["fecha_modificado_raw"] = $this->formateoDateTime($consulta[$count]["preguntas"]["fecha_modificado"]);
        $count++;
			}
		} else {
			$resultado = null;
		}
		return $resultado;
	}

	/*Función que borra una pregunta
  *
  *Función que dado el identificador de una pregunta la borra de la BBDD
  *
  * @param int $idPregunta identificador de la pregunta
  * @return boolean $funciona true en caso de que la pregunta se haya borrado
  * correctamente y false en caso contrario*/
  function borrarPregunta($idPregunta){
    $idUsuario = $_SESSION['id'];
    $funciona=false;
    $sql = "SELECT `referencias`,`asignatura` FROM `preguntas` WHERE id=".$idPregunta;
    $consulta=$this->query($sql);
    $numRef = $consulta[0]["preguntas"]['referencias'];
    $asignatura = $consulta[0]["preguntas"]['asignatura'];

    //Obtenemos los datos de la pregunta que queremos borrar para poder mostrarlos en el log
    $sql = "SELECT `nombre`,`creador` FROM `asignaturas` a INNER JOIN `preguntas` p on a.id = p.asignatura WHERE p.id=".$idPregunta;
		$consulta=$this->query($sql);
    $fila['nombre'] = $consulta[0]['a']['nombre'];
		$fila['creador'] = $consulta[0]['p']['creador'];


    //Antes de borrar comprobamos que, o bien sea un coordinador o administrador,
    //o la pregunta sea propia
    if ($this->esCoordinador($asignatura, $idUsuario) || $_SESSION['administrador']) {
      if ($numRef == 0) {
        $sql = "DELETE FROM `preguntas` WHERE id=".$idPregunta;
        $consulta = $this->query($sql);
        $funciona=true;

        //Escribirmos en el log que usuario ha borrado la pregunta y que la ha
        //borrado como coordinador o como administrador
          $log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
                  " | ACTION --> ".$_SESSION['email']. " borró una pregunta de la asignatura ".$fila['nombre']." como coordinador o como administrador ". PHP_EOL.
                  "-----------------------------------------------------------------".PHP_EOL;
          file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
      } else {
        $_SESSION['error_no_poder_borrar'] = true;
      }
    } else {
      if ($numRef == 0) {
				if($fila['creador'] == $idUsuario){
	        $sql = "DELETE FROM `preguntas` WHERE id=".$idPregunta." AND creador=".$idUsuario;
					$this->query($sql);
					$funciona=true;
				}
        else{
          $_SESSION['error_BorrarNoCreador']=true;
        }
        //Escribirmos en el log que usuario ha borrado la pregunta y que la ha
        //borrado porque es propia
          $log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
                  " | ACTION --> ".$_SESSION['email']. " borró una pregunta de la asignatura ".$fila['nombre']. PHP_EOL.
                  "-----------------------------------------------------------------".PHP_EOL;
          file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
      } else {
        $_SESSION['error_no_poder_borrar'] = true;
      }
    }
    echo $funciona;
  }

	/*Funcion que edita los valores de una pregunta
	*
	*Función que dado un titulo, un cuerpo, un tema y un identificador de una
	*pregunta, modifica con esos valores los valores que tenian esos campos en la
	*pregunta
	*
	* @param int $titulo titulo para la pregunta
	* @param int $cuerpo cuerpo para la pregunta
	* @param int $tema tema de la pregunta
	* @param int $idPregunta identificador de la pregunta
	* @return boolean $funciona true en caso de que se haya editado con exito
	* y false en caso contrario*/
	function editarPregunta($titulo,$cuerpo,$tema,$idPregunta){
		$funciona=false;
		$date = date('Y-m-d H:i:s', time());
		$sql = "UPDATE `preguntas` SET ";
		if($titulo != ''){
			$sql = $sql."`titulo`='".$titulo."'";
			$entraTitulo = true;
		}
		if($cuerpo != ''){
			$sql = ($entraTitulo)?$sql.",`cuerpo`='".$cuerpo."'": $sql."`cuerpo`='".$cuerpo."'";
			$entraCuerpo=true;
		}
		if($tema != '')
			$sql =  ($entraTitulo||$entraCuerpo)? $sql.",`tema`=".(int)$tema."": $sql."`tema`=".(int)$tema."";

		$sql= $sql.",`ult_modificador`=".$_SESSION['id'].",`fecha_modificado`='".$date."'WHERE id=".$idPregunta;
		$consulta = $this->query($sql);

		$sql = "INSERT INTO `preguntas_historial`(`id`, `idPregunta`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idPregunta.",".$_SESSION['id'].",'".$date."')";
		$consulta = $this->query($sql);

		$funciona=true;

		echo $funciona;
	}

	/*Función que añade una pregunta a la asignatura actual
	*
	*Función que dado un titulo, un cuerpo y un tema, añade una pregunta con estos
	*campos a la asignatura en la que nos encontramos actualmente
	*
	* @param int $titulo titulo para la pregunta
	* @param int $cuerpo cuerpo para la pregunta
	* @param int $tema tema de la pregunta
	* @return boolean $funciona true en caso de que la pregunta se haya añadido
	* correctamente y false en caso contrario*/
	function aniadirPregunta($titulo,$cuerpo,$tema){
		$funciona=false;
		$date = date('Y-m-d H:i:s', time());
		$sql = "INSERT INTO `preguntas`(`titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`)
		VALUES ('".$titulo."','".$cuerpo."','".$tema."','".$_SESSION['id']."','".$date."','".$_SESSION['id']."','".$date."','".$_SESSION['idAsignatura']."')";
		$consulta = $this->query($sql);

		$sqlNuevoID='SELECT id FROM `preguntas` ORDER BY `preguntas`.`id` DESC LIMIT 1';
    $consulta = $this->query($sqlNuevoID);
		$idPreguntaNueva = $consulta[0]['preguntas']['id'];

		$sql = "INSERT INTO `preguntas_historial`(`id`, `idPregunta`, `idModificador`, `fecha_modificacion`)
		VALUES ('',".$idPreguntaNueva.",".$_SESSION['id'].",'".$date."')";
		$this->query($sql);

		$sql = "SELECT `nombre` FROM `asignaturas` WHERE id=".$_SESSION['idAsignatura'];
	  $cosulta = $this->query($sql);
		$fila=$consulta[0]['asignaturas'];

		$funciona=true;
		//Escribimos en el log que usuario ha añadido la pregunta y a que asignatura
					$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
					        " | ACTION --> ".$_SESSION['email']. " creo una nueva pregunta en la asignatura ".$fila['nombre'].PHP_EOL.
					        "-----------------------------------------------------------------".PHP_EOL;
					file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
		echo $funciona;
	}

	/*Función que nos devuelve una pregunta
	*
	*Función que dado un identificador de una pregunta nos devuelve toda la
	*información de dicha pregunta
	*
	* @param int $idPregunta identificador de una pregunta
	* @return $fila array con toda la informacion de la pregunta*/
	function cargaUnicaPregunta($idPregunta){
		$sql = "SELECT * FROM `preguntas` INNER JOIN profesores ON preguntas.creador=profesores.id WHERE preguntas.id=".$idPregunta;
		$consulta= $this->query($sql);
		$resultado=[];
		$count = 0;

		while (count($consulta) > $count){
			foreach ($consulta[$count]["preguntas"] as $key => $value) {
				$resultado[$count][$key] = $consulta[$count]["preguntas"][$key];
			}
			$resultado[$count]["nombreAutor"] = $consulta[$count]["profesores"]["nombre"];
			$resultado[$count]["fecha_creado_raw"] = $this->formateoDateTime($consulta[$count]["preguntas"]["fecha_creacion"]);
			// $resultado[$count]["fecha_modificado_raw"] = $this->formateoDateTime($consulta[$count]["preguntas"]["fecha_modificado"]);
			$count++;
		}
		return $resultado[0];
	}


	/*Función que devuelve el nombre y apellidos de un profesor
	*
	*Función que dado el identificador de un profesor nos devuelve sus nombres
	*y apellidos
	*
	* @param int $idProfesor identificador de un profesor
	* @return  $fila nombre y apellidos del profesor*/
	function cargaNombreApellidosAutor($idProfesor){
		$sql = "SELECT `nombre`,`apellidos` FROM `profesores` WHERE id=".$idProfesor;
		$consulta= $this->query($sql);
		return $consulta[0];
	}


	/*Función que nos devuelve el autor de una pregunta
	*
	*Función que dado el identificador de una pregunta nos devuleve el nombre del
	*autor de dicha pregunta
	*
	* @param int $idPregunta identificador de una pregunta
	// * @return string $fila['autor'] nombre del autor de la pregunta*/
	// function cargaAutorPregunta($idPregunta){
	// 	$sql = "SELECT profesores.nombre AS autor FROM preguntas INNER JOIN profesores ON preguntas.creador=profesores.id WHERE preguntas.id=".$idPregunta;
	// 	$consulta= $this->query($sql);
	// 	echo "<pre>";
	// 	var_dump($consulta);
	// 	die;
	// 	return $fila['autor'];
	//
	// }

	/*Función que nos devuelve el historial de modificaciones de una pregunta
	*
	*Función qeu dado un identificador de una pregunta nos devuelve su historial
	*de modificaciones
	*
	* @param int $idPregunta identificador de una pregunta
	* @return $fila array con el historial de modificaciones de la pregunta*/
	function cargaHistorialPregunta($idPregunta){
		$sql = "SELECT `id`, `idPregunta`, `idModificador`, `fecha_modificacion` FROM `preguntas_historial` WHERE `idPregunta`=".$idPregunta;
		$consulta=$this->query($sql);
		$resultado = [];
		if(count($consulta) > 0){
      $count = 0;
			while (count($consulta) > $count){
				foreach ($consulta[$count]["preguntas_historial"] as $key => $value) {
					$resultado[$count][$key] = $consulta[$count]["preguntas_historial"][$key];
				}
				$nombreApellidosAutor= $this->cargaNombreApellidosAutor($consulta[$count]["preguntas_historial"]["idModificador"]);
				$resultado[$count]["nombreAutor"] = $nombreApellidosAutor['profesores']['nombre'];
				$resultado[$count]["apellidosAutor"] = $nombreApellidosAutor['profesores']['apellidos'];
				$resultado[$count]["fecha_modificado_raw"] = $this->formateoDateTime($consulta[$count]["preguntas_historial"]["fecha_modificacion"]);
        $count++;
			}
		} else {
			$resultado = null;
		}
		return $resultado;
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
		return $consulta[0]["prof_asig_coord"]['coordinador'];
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
