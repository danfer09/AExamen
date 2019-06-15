<?php
App::uses('AppModel', 'Model');

class Asignatura extends AppModel {
  public $useTable = 'asignaturas';
  /*Función que nos devuelve las assignaturas de un profesor.
	*
	*Funcion que dado un id de un profesor, devuelve un array con las asignaturas que
	*tiene ese profesor. En caso de que haya un error se los pasamos por la variables
	*Session a la vista para que lo muestre en consideracion
	*
	* @param int $idProfesor identificador del profesor
	* @return $asignaturas array con las asignaturas que tiene el profesor */
	public function cargaAsignaturas($idProfesor){
		$sql = "SELECT prof_asig_coord.coordinador AS coordinador, profesores.nombre AS nombre_profesor, asignaturas.nombre AS nombre_asignatura, asignaturas.siglas AS siglas_asignatura, asignaturas.id AS id_asignatura FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE id_profesor=".$idProfesor;
    $asignaturas=$this->query($sql);
		/*En caso de que no haya ninguna asignatura, lo señalamos en
		la variable session que controla ese error*/
		if(count($asignaturas)==0){
			$_SESSION['error_ningunaAsignatura']=true;
		}
		return $asignaturas;
	}

  /*Funcion que nos devuelve si un profesor es o no es coordinador de una asignatura
  *
  *Funcion que dado el id de una asignatura y el id de un profesor nos devuelve un true si
  *el profesor es coordinador de la asignatura y false en caso contrario
  *
  * @param int $idAsig identificador de la asignatura
  * @return boolean $result true si es coordinador y false en caso contrario*/
  public function esCoordinador($idAsig, $idProfesor){
    $result=false;
    $sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
    $esCoordinador=$this->query($sql);
    return $esCoordinador[0]["prof_asig_coord"]["coordinador"];
  }
}
