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
	*Funcion que nos devuelve la siglas y el identificador
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
	public function selectAllMailsProfesoresSiglas($asignaturaSiglas) {
    if ($asignaturaSiglas != 'todas') {
      $sql = "SELECT DISTINCT profesores.id as id, profesores.email as email, profesores.nombre as nombre, profesores.apellidos as apellidos FROM (profesores inner join prof_asig_coord on profesores.id=prof_asig_coord.id_profesor) inner join asignaturas on asignaturas.id=prof_asig_coord.id_asignatura WHERE asignaturas.siglas='".$asignaturaSiglas."'";
    } else {
      $sql = "SELECT DISTINCT profesores.id as id, profesores.email as email, profesores.nombre as nombre, profesores.apellidos as apellidos FROM (profesores inner join prof_asig_coord on profesores.id=prof_asig_coord.id_profesor) inner join asignaturas on asignaturas.id=prof_asig_coord.id_asignatura";
    }
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
  /*Devuelve todos los examenes de un autor y de una asignatura determinada
	*
	*Funcion que pasandole un enlace a la BBDD, las siglas de la asignatura y
	*el mail del autor nos devuelve un array con todos la informacion de todos los
	*examenes de esa asignatura y de ese autor
	*
	* @param $db conexion con la BBDD
	* @param string $asignaturaSiglas siglas de la asignatura
	* @param string $autorMail mail del autor
	* @return $resultado array con los examenes y su respectiva informacion */
	public function selectAllExamenesFiltrado($asignaturaSiglas, $autorMail) {
		if (!$_SESSION['administrador']) {
			$sql = "SELECT DISTINCT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura AND pac.id_profesor=".$_SESSION['id'];
		} else {
			$sql = "SELECT DISTINCT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura";
		}
		if ($asignaturaSiglas != "todas") {
			$sql = $sql." AND asignaturas.siglas='".$asignaturaSiglas."' ";
		}
		if ($autorMail != "todos") {
			$sql = $sql." AND p1.email='".$autorMail."' ";
		}
    /*-------Antigua--------*/
		// $consulta=mysqli_query($db,$sql);
		// $resultado = [];
		// if($consulta->num_rows > 0){
		// 	while ($fila=mysqli_fetch_assoc($consulta)){
		// 		$resultado[] = $fila;
		// 	}
		// } else {
		// 	$resultado = null;
		// }
		// mysqli_close($db);
		// return $resultado;
/*-----------------nueva----------------------*/
    $consulta=$this->query($sql);
    if((count($consulta) < 0)) {
      return null;
    }
    for ($i=0; $i < count($consulta); $i++) {
      $consulta[$i]["e1"]['fecha_creado'] = $this->formateoDateTime($consulta[$i]["e1"]['fecha_creado']);
      $consulta[$i]["e1"]['fecha_modificado'] = $this->formateoDateTime($consulta[$i]["e1"]['fecha_modificado']);
    }

    return $consulta;
	}

  /*Devuelve todos los examenes del sistema con la informacion de cada uno de ellos
*
*Funcion que pasandole un enlace a la BBDD nos todos la información de todos los
*examenes que hay en el sistema
*
* @param $db conexion con la BBDD
* @return $resultado array con los examenes de todo el sistema y su respectiva informacion */
public function selectAllExamenesCompleto() {
  if (!$_SESSION['administrador']) {
      $sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) INNER JOIN prof_asig_coord pac WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig AND asignaturas.id=pac.id_asignatura AND pac.id_profesor=".$_SESSION['id'];
    } else {
      $sql = "SELECT e1.titulo, p1.nombre as creador, p2.nombre as ultimo_modificador, e1.id as id, e1.fecha_creado, e1.fecha_modificado, asignaturas.nombre as nombreAsignatura, asignaturas.siglas as asignatura, asignaturas.id as idAsignatura FROM (((examenes e1 INNER JOIN profesores p1) INNER JOIN (profesores p2)) INNER JOIN (asignaturas)) WHERE e1.creador=p1.id and e1.ultimo_modificador=p2.id AND asignaturas.id=e1.id_asig";
    }
    /*------------------*/
    // $consulta=mysqli_query($db,$sql);
    // $resultado = [];
    // if($consulta->num_rows > 0){
    //   while ($fila=mysqli_fetch_assoc($consulta)){
    //     $resultado[] = $fila;
    //   }
    // } else {
    //   $resultado = null;
    // }
    // mysqli_close($db);
    // return $resultado;
    /*------------------*/
    $consulta=$this->query($sql);
    if((count($consulta) < 0)) {
      return null;
    }
    for ($i=0; $i < count($consulta); $i++) {
      $consulta[$i]["e1"]['fecha_creado'] = $this->formateoDateTime($consulta[$i]["e1"]['fecha_creado']);
      $consulta[$i]["e1"]['fecha_modificado'] = $this->formateoDateTime($consulta[$i]["e1"]['fecha_modificado']);
    }

    return $consulta;
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
		if ((date("d")==date('d',$time))) {
			return date('H:i',$time);
		}

		$newformat = date('H:i - d/m/Y',$time);

		return $newformat;
	}

}
