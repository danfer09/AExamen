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
}
