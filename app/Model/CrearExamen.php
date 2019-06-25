<?php
App::uses('AppModel', 'Model');

class CrearExamen extends AppModel {
  public $useTable = 'examenes';

  /*Función que nos devuelve toda la informacion de un examen.
	*
	*Funcion que dado un identificador de examen nos devuelve un array con
	*toda la información de este.
	*
	* @param int $idExamen identificador de un examen
	* @return $fila array con la informacion del examen*/
	public function getExamen($idExamen){
		$sql = "SELECT * FROM `examenes` WHERE id=".$idExamen;
    $consulta=$this->query($sql);
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[] = $consulta[$count]['examenes'];
             $count++;
		    }
        $puntosPreguntas=json_decode($resultado[0]['puntosPregunta'],true);
        //Recorremos todas las preguntas del examen para obtener su título y cuerpo y pasarlo al controlador
        foreach ($puntosPreguntas['preguntas'] as $key => $value) {
          foreach ($value as $preguntasTema) {
            $datosPregunta = $this->cargaUnicaPregunta($preguntasTema['id']);
            $resultado[0]['preguntas'][$key][$preguntasTema['id']]['titulo'] = $datosPregunta['titulo'];
            $resultado[0]['preguntas'][$key][$preguntasTema['id']]['cuerpo'] = $datosPregunta['cuerpo'];
          }
        }
    }
		return $resultado[0];
	}

  /*Función que nos devuelve una pregunta
	*
	*Función que dado un identificador de una pregunta nos devuelve toda la
	*información de dicha pregunta
	*
	* @param int $idPregunta identificador de una pregunta
	* @return $fila array con toda la informacion de la pregunta*/
	public function cargaUnicaPregunta($idPregunta){
		$sql = "SELECT * FROM `preguntas` WHERE id=".$idPregunta;
		$consulta=$this->query($sql);
    $resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[] = $consulta[$count]['preguntas'];
             $count++;
		    }
    }

    return $resultado[0];
	}

  /*Función que nos devuelve los puntos correspondientes a cada tema de una asignatura
	*
	* Función que dado un id de una asignatura, devuelve un json con la correspondencia
	* entre tema-puntos establecido por el coordinador de la asignatura
	*
	* @param int $idAsignatura identificador de la asignatura
	* @return $fila['puntos_tema'] json con los puntos por cada tema
  */
	public function cargaPuntosTema($idAsignatura){
		$sql = "SELECT puntos_tema FROM `asignaturas` WHERE id=".$idAsignatura;
		$consulta=$this->query($sql);
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[] = $consulta[$count]['asignaturas'];
             $count++;
		    }
    }
		return $resultado[0]['puntos_tema'];
	}

  /*Función que nos devuelve el número total de temas para una asignatura
	*
	* Función que dado un id de una asignatura, devuelve el número total de temas
	* en dicha asignatura
	*
	* @param int $idAsignatura identificador de la asignatura
	* @return $jsonUsable['numeroTemas'] int, número de temas
    */
	public function getNumTemas($idAsignatura){
		$jsonNumeroTemas = $this->cargaPuntosTema($idAsignatura);
		$jsonUsable = json_decode($jsonNumeroTemas,true);
		return $jsonUsable['numeroTemas'];
	}

  /*Función que nos devuelve las preguntas de un tema de una asignatura
	*
	* Función que dado un id de una asignatura y un tema, devuelve todas las preguntas
	* existentes para ese tema de esa asignatura
	*
	* @param int $idAsignatura identificador de la asignatura
	* @param int $tema número de tema
	* @return $preguntas (en forma AJAX) json con las preguntas
    */
	public function getPregAsigTema($idAsignatura,$tema){
		$sql ="SELECT * FROM `preguntas` WHERE asignatura=".$idAsignatura." AND tema=".$tema;
		$consulta=$this->query($sql);
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[] = $consulta[$count]['preguntas'];
             $count++;
		    }
    }
		return $resultado;
	}

  /*Función que actualiza las preguntas del examen en sesión y devuelve todas ellas
	*
	* Función que dados unos id de preguntas, obtiene de BBDD toda la información de las preguntas
	* para introducirla en la variable de sesión correspondiente mediante la llamada a insertarPreguntaJSON
	*
	* @param array $preguntas ids de preguntas
	* @return $filas (en forma AJAX) json con las preguntas añadidas
    */
	public function aniadirPreguntas($preguntas){
		$total = count($preguntas);
	  for($i=0; $i < $total; $i++){
		  $sql ="SELECT * FROM `preguntas` WHERE id=".$preguntas[$i];
		  $consulta=$this->query($sql);
			$filas[$i]=$consulta[0]['preguntas'];

			$this->insertarPreguntaJSON($filas[$i]['tema'], $filas[$i]['id'], 1);
      $datos = $this->cargaUnicaPregunta($filas[$i]['id']);
      if (!$_SESSION['editar']) {
  			$_SESSION[$_SESSION['nombreAsignatura'].'datos']["tema".$filas[$i]['tema']][$filas[$i]['id']]['titulo'] = $datos['titulo'];
        $_SESSION[$_SESSION['nombreAsignatura'].'datos']["tema".$filas[$i]['tema']][$filas[$i]['id']]['cuerpo'] = $datos['cuerpo'];
  		} else {
  			$_SESSION[$_SESSION['nombreExamenEditar'].'datos']["tema".$filas[$i]['tema']][$filas[$i]['id']]['titulo'] = $datos['titulo'];
        $_SESSION[$_SESSION['nombreExamenEditar'].'datos']["tema".$filas[$i]['tema']][$filas[$i]['id']]['cuerpo'] = $datos['cuerpo'];
  		}
		}
		return $filas;
	}

  /*Función que inserta una pregunta (cuando es seleccionada y añadida) en la variable de sesión el examen actual
	*
	* Función que dado un número de tema, un id de pregunta y un valor de puntos para una pregunta
	* inserta en la variable de sesión correspondiente una pregunta
	*
	* @param int $numTema número de tema
	* @param int $idPregunta id de pregunta
	* @param int $puntosPregunta puntos que vale la pregunta
	* @return {void}
    */
	public function insertarPreguntaJSON($numTema,$idPregunta,$puntosPregunta){
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
			$preguntasEditar =  isset($_SESSION['editarExamenCambios'])? json_decode($_SESSION['editarExamenCambios'],true): null;
		}
		if($preguntas){
			$tema="tema".$numTema;
			//Se crea esta variable para que tanto el id como el puntos se guarden en la misma pos del array, pues si lo ponemos directamente en el[] se ponen en diferentes posiciones
			$ultimaPos= isset($preguntas['preguntas'][$tema])? count($preguntas['preguntas'][$tema]):0;
			$preguntas['preguntas'][$tema][$ultimaPos]["id"] = $idPregunta;
			$preguntas['preguntas'][$tema][$ultimaPos]["puntos"] = $puntosPregunta;

		}

		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
			$preguntasEditar[$idPregunta] = isset($preguntasEditar[$idPregunta])? null: true;
			$_SESSION['editarExamenCambios'] = json_encode($preguntasEditar);
		}
	}

  /*Función que elimina una pregunta del examen en sesión
	*
	* Función que dado un id de pregunta y un tema, elimina la pregunta del examen
	*
	* @param int $idPregunta id de pregunta
	* @param int $tema tema al que pertenece la pregunta
	* @return {void}
    */
	public function eliminarPregunta($idPregunta, $tema) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
      unset($_SESSION[$_SESSION['nombreAsignatura'].'datos']['tema'.$tema][$idPregunta]);
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
			$preguntasEditar =  isset($_SESSION['editarExamenCambios'])? json_decode($_SESSION['editarExamenCambios'],true): null;
      unset($_SESSION[$_SESSION['nombreExamenEditar'].'datos']['tema'.$tema][$idPregunta]);
		}

		$temaNombre="tema".$tema;
		if($preguntas){
				$preguntasTema = isset($preguntas['preguntas'][$temaNombre])? $preguntas['preguntas'][$temaNombre]: null;
				if ($preguntasTema) {
					$i = 0;
					foreach ($preguntasTema as $pregunta) {
						if ($pregunta['id']==$idPregunta) {
							array_splice($preguntas['preguntas'][$temaNombre],$i,1);
						}
						$i++;
					}
				}
		}
		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
      return $_SESSION[$_SESSION['nombreAsignatura']];
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
			$preguntasEditar[$idPregunta] = isset($preguntasEditar[$idPregunta])? null: false;
			$_SESSION['editarExamenCambios'] = json_encode($preguntasEditar);
      return $_SESSION[$_SESSION['nombreExamenEditar']];
		}
	}

  /*Función que actualiza los puntos de una pregunta de un examen en variable de sesión
	*
	* Función que dado un id de pregunta, un valor de puntos y un tema, actualiza dicha pregunta y recarga el examen de variable de sesión
	* para el examen actual
	*
	* @param int $idPregunta id de pregunta
	* @param int $puntos puntos que vale la pregunta
	* @param int $tema tema al que pertenece la pregunta
	* @return {void}
    */
	public function cambiarPuntosPregunta($idPregunta, $puntos, $tema) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
		}

		$temaNombre="tema".$tema;
		if($preguntas){
				$preguntasTema = isset($preguntas['preguntas'][$temaNombre])? $preguntas['preguntas'][$temaNombre]: null;
				if ($preguntasTema) {
					$i = 0;
					foreach ($preguntasTema as $pregunta) {
						if ($pregunta['id']==$idPregunta) {
							$preguntas['preguntas'][$temaNombre][$i]["puntos"] = $puntos;
						}
						$i++;
					}
				}
		}

		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
		}
    return true;
	}

  /*Función que guarda un examen en la base de datos
	*
	* Función que dado un nombre de examen se encarga de guardar el examen almacenado en sesión en cada tabla correspondiente
	* de manera que queda guardado en la base de datos correctamente.
	*
	* @param string $nombreExamen nombre del examen
	* @return $mensaje (formato AJAX) string con el mensaje de éxito o fracaso guardando el examen
    */
	public function guardarExamen ($nombreExamen) {
		$puntosPregunta = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		$preguntasSesion = $puntosPregunta;
		$puntosPregunta['nombreExamen'] = $nombreExamen;
		$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($puntosPregunta);
		$mensaje = array();

		$puntosPregunta = $_SESSION[$_SESSION['nombreAsignatura']];
		$date = date('Y-m-d H:i:s', time());

		$sqlExamen = "INSERT INTO `examenes`(`titulo`, `id`, `creador`, `fecha_creado`, `fecha_modificado`, `ultimo_modificador`, `id_asig`, `puntosPregunta`) VALUES ('".$nombreExamen."','',".$_SESSION['id'].",'".$date."','".$date."',".$_SESSION['id'].",".$_SESSION['idAsignatura'].",'".$puntosPregunta."')";
    $consulta=$this->query($sqlExamen);

		$numTemas = $this->getNumTemas($_SESSION['idAsignatura']);
		$preguntasSesion = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;

    $sqlNuevoID='SELECT id FROM `examenes` ORDER BY `examenes`.`id` DESC LIMIT 1';
    $consulta = $this->query($sqlNuevoID);
		$idExamenNuevo = $consulta[0]['examenes']['id'];

		//Añadimos al log la creación del nuevo examen
		$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].', '.$_SESSION['email'].
		        " | ACTION --> Nuevo examen de ".$_SESSION['nombreAsignatura']." creado con id ".$idExamenNuevo.PHP_EOL.
		        "-----------------------------------------------------------------".PHP_EOL;
		file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

		for ($i = 1; $i <= $numTemas; $i++) {
			$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;

			if ($preguntasTema) {

				foreach ($preguntasTema as $pregunta) {
					$sqlExam_Preg = "INSERT INTO exam_preg (`id_examen`, `id_pregunta`, `id`) VALUES (".$idExamenNuevo.",".$pregunta['id'].",'')";
					$consulta=$this->query($sqlExam_Preg);

					$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` + 1 WHERE id=".$pregunta['id'];
					$consulta=$this->query($sqlReferencia);
				}
			}
		}

		$sql = "INSERT INTO `examenes_historial`(`id`, `idExamen`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idExamenNuevo.",".$_SESSION['id'].",'".$date."')";
		$consulta=$this->query($sql);

		$mensaje['Message'] = "Examen guardado";


		$_SESSION[$_SESSION['nombreAsignatura']] = '{
					"nombreExamen":"",
					"preguntas":{
					}
				}';
		return $mensaje;
	}

  /*Función que guarda un examen editado en la base de datos
	*
	* Función que dado un nombre de examen se encarga de actualizar el examen almacenado en sesión en cada tabla correspondiente
	* de manera que queda actualizado en la base de datos correctamente.
	*
	* @param string $nombreExamen nombre del examen
	* @return {void}
    */
	function guardarModificarExamen ($nombreExamen) {
		$date = date('Y-m-d H:i:s', time());
		$nombreExamenEditar=$_SESSION['nombreExamenEditar'];

		$preguntasJsonArray = isset($_SESSION[$nombreExamenEditar])? json_decode($_SESSION[$nombreExamenEditar],true): null;
		$preguntasJsonArray['nombreExamen'] = $nombreExamen;
		$_SESSION[$nombreExamen] = json_encode($preguntasJsonArray);

		$preguntasJsonArray=$_SESSION[$nombreExamen];
		$idExamen=$_SESSION['idExamen'];

		$sqlExamen="UPDATE `examenes` SET `titulo`='".$nombreExamen."'"." ,`fecha_modificado`='".$date."',`ultimo_modificador`=".$_SESSION['id'].",`puntosPregunta`='".$preguntasJsonArray."' WHERE id=".$idExamen;
    $consulta = $this->query($sqlExamen);

		$numTemas = $this->getNumTemasModificar($_SESSION['idAsignatura']);
		$preguntasSesion = isset($preguntasJsonArray)? json_decode($preguntasJsonArray,true): null;

		$sqlDelete= "DELETE FROM `exam_preg` WHERE id_examen=".$idExamen;
    $consulta = $this->query($sqlDelete);
		for ($i = 1; $i <= $numTemas; $i++) {
			$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
			if ($preguntasTema) {
				foreach ($preguntasTema as $pregunta) {
					$sqlExam_Preg = "INSERT INTO exam_preg (`id_examen`, `id_pregunta`, `id`) VALUES (".$idExamen.",".$pregunta['id'].",'')";

					$consulta = $this->query($sqlExam_Preg);
				}
			}
		}
		$preguntasEditadasAhora = isset($_SESSION['editarExamenCambios'])? json_decode($_SESSION['editarExamenCambios'],true): null;
			if ($preguntasEditadasAhora) {
				foreach ($preguntasEditadasAhora as $id => $value) {
					if($value){
						$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` + 1 WHERE id=".$id;
						$consulta = $this->query($sqlReferencia);
					}
					else if(($value!==null)){
						$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` - 1 WHERE id=".$id;
						$consulta = $this->query($sqlReferencia);
					}
				}
			}
			$sql = "INSERT INTO `examenes_historial`(`id`, `idExamen`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idExamen.",".$_SESSION['id'].",'".$date."')";
			$consulta=$this->query($sql);
			$_SESSION['editarExamenCambios'] = "{}";
      return true;
	}

  /*Funcion que devuleve el numero de temas de una asignatura
	*
	*Funcion que dado un identificador de una asignatura nos devuelve un entero
	*con el numero de temas
	*
	* @param int $idAsignatura identificador de una asignatura
	* @return int $jsonUsable['numeroTemas'] numero de temas de esa asignatura  */
	function getNumTemasModificar($idAsignatura){
		$jsonNumeroTemas = $this->cargaPuntosTemaModificar($idAsignatura);
		$jsonUsable = json_decode($jsonNumeroTemas,true);
		return $jsonUsable['numeroTemas'];
	}

  /*Función que dada una asignatura nos devuelve los puntos por tema que tiene
	*en su examen por defecto
	*
	*Funcion que dado un identificador de una asignatura, nos devuelve un string
	*en formato json con la cantidad de puntos por tema que tiene un examen suyo por defecto
	*
	* @param int $idAsignatura identificador de una asignatura
	* @return string $fila string con formato json que tiene la cantidad de puntos
	* por cada tema */
	function cargaPuntosTemaModificar($idAsignatura){
		$sql = "SELECT puntos_tema FROM `asignaturas` WHERE id=".$idAsignatura;
		$consulta=$this->query($sql);
    $resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[] = $consulta[$count]['asignaturas'];
             $count++;
		    }
    }

		return $resultado[0]['puntos_tema'];
	}


  /*Función que guarda el nombre del examen actual en sesión
	*
	* Función que dado un nombre de examen guarda dicho nombre para el examen en sesión
	*
	* @param int $nombreExamen nombre del examen
	* @return {void}
    */
	public function guardarNombreExamenJSON($nombreExamen) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
		}

		$preguntas['nombreExamen'] = $nombreExamen;

		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
		}
	}

}
