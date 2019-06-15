<?php
App::uses('AppModel', 'Model');

class Examen extends AppModel {
  public $useTable = 'examenes';
  /*Funcion que nos devuelve todas las asignaturas de la plataforma
	*
	*Funcion que pasandole un enlace a la BBDD nos devuelve todas las asignaturas
	*que hay dadas de alta en el sistema
	*
	* @param $db conexion con la BBDD
	* @return $resultado array con las asignaturas que tiene el sistema, en caso
	* de que no haya asignaturas en el sistema devuelve false  */
	public function selectAllSiglasAsignaturas() {
		$sql = "SELECT siglas, id FROM asignaturas";
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
		return $resultado;
	}

	/*Funcion que nos devuelve las siglas y el identificador de todas las asignaturas
	* de la plataforma
	*
	*Funcion que pasandole un enlace a la BBDD nos devuelve la siglas y el identificador
	*de todas las asignaturas que hay dadas de alta en el sistema
	*
	* @param $db conexion con la BBDD
	* @return $resultado array con las siglas y el identificador de todas las
	* asignaturas que tiene el sistema, en caso de que no haya asignaturas en el
	* sistema devuelve false  */
	public function selectAllSiglasAsignaturasProfesor($idProfesor) {
			$sql = "SELECT asignaturas.siglas, asignaturas.id FROM asignaturas INNER JOIN prof_asig_coord on asignaturas.id = prof_asig_coord.id_asignatura WHERE prof_asig_coord.id_profesor=".$idProfesor;
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
  		return $resultado;
    }


/*Funcion que nos devuelve los profesores de una asignatura
	*
	*Funcion que dadas las siglas de una asignatura nos devuelve un array con los
	*profesores que esta tiene
	*
	* @param string $asignaturaSiglas siglas de la asignatura de la que queremos
	*lo profesores
	* @return $resultado array con los profesores que tiene la asignatura, en caso de
	* que la asignatura no tiene profesores devolvemos false  */
	function selectAllMailsProfesoresSiglas($asignaturaSiglas) {
		$sql = "SELECT profesores.id as id, profesores.email as email, profesores.nombre as nombre, profesores.apellidos as apellidos FROM (profesores inner join prof_asig_coord on profesores.id=prof_asig_coord.id_profesor) inner join asignaturas on asignaturas.id=prof_asig_coord.id_asignatura WHERE asignaturas.siglas='".$asignaturaSiglas."'";
    $consulta=$this->query($sql);
    $resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
      $resultado = null;
    }
    else{
      while(count($consulta) > $count ){
           $resultado[] = $consulta[$count]['profesores'];
           $count++;
      }
    }
    return $resultado;
	}
}
