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
    debug($idExamen);
    die;
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
        foreach ($puntosPreguntas['preguntas'] as $key => $value) {
          foreach ($value as $preguntasTema) {
            $datosPregunta = $this->cargaUnicaPregunta($value['id']);
            $resultado[0]['preguntas'][$key][$value['id']]['titulo'] = $datosPregunta['titulo'];
            $resultado[0]['preguntas'][$key][$value['id']]['cuerpo'] = $datosPregunta['cuerpo'];
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
		$jsonNumeroTemas = cargaPuntosTema($idAsignatura);
		$jsonUsable = json_decode($jsonNumeroTemas,true);
		return $jsonUsable['numeroTemas'];
	}

}
