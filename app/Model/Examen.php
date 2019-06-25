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
      $consulta[$i]["e1"]['fecha_creado_raw'] = $consulta[$i]["e1"]['fecha_creado'];
      $consulta[$i]["e1"]['fecha_modificado_raw'] = $consulta[$i]["e1"]['fecha_modificado'];
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
      $consulta[$i]["e1"]['fecha_creado_raw'] = $consulta[$i]["e1"]['fecha_creado'];
      $consulta[$i]["e1"]['fecha_modificado_raw'] = $consulta[$i]["e1"]['fecha_modificado'];
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
		if ((date("d")==date('d',$time)) && (date("m")==date('m',$time)) && (date("Y")==date('Y',$time))) {
			return date('H:i',$time);
		}

		$newformat = date('H:i - d/m/Y',$time);

		return $newformat;
	}

  /*Función que carga la información de un examen dado
	*
	*Funcion que, dado un identificador de un examen, carga la informacion básica de este
	*
	* @param int $idExamen identificador de examen
	* @return $fila array con la información del examen */
	public function cargaUnicoExamenInfo($idExamen){
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
    }

    return $resultado[0];
	}

  /*Función que comprueba si el profesor puede acceder a un examen
	*
	* Funcion que, dado un identificador de una asignatura a la que pertenece un examen, comprueba si puede visualizarlo o no
	*
	* @param int $idAsignatura identificador de asignatura
	* @return $acceso boolean true si puede acceder, false en caso contrario
	*/
	public function comprobarAcceso($idAsignatura) {
		$asignaturas = array();
		$sql = "SELECT asignaturas.id as id FROM prof_asig_coord INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id WHERE id_profesor=".$_SESSION['id'];
		$consulta=$this->query($sql);
		$acceso = false;
		$i=0;

    if((count($consulta) < 0)) {
      $acceso = null;
    } else {
  		while(count($consulta) > $i ){
  			$asignaturas[$i]=$consulta[$i]['asignaturas']['id'];
  			if ($consulta[$i]['asignaturas']['id']==$idAsignatura) {
  				$acceso = true;
  			}
  			$i++;
  		}
    }
    return $acceso;
	}

  /*Función que carga el autor de un examen
	*
	*Funcion que dado el identificador de un examen nos de un examen
	*nos devuelve el autor del examnen
	*
	* @param int $idExamen identificador de examen
	* @return $fila['autor'] identificardor del profesor que ha creado el examen*/
	public function cargaAutorExamen($idExamen){
		$sql = "SELECT profesores.nombre AS autor FROM examenes INNER JOIN profesores ON examenes.creador=profesores.id WHERE examenes.id=".$idExamen;
		$consulta = $this->query($sql);
    $resultado = [];
    $count = 0;

    if((count($consulta) < 0)) {
      $resultado = null;
    } else {
      while(count($consulta) > $count ){
           $resultado[] = $consulta[$count]['profesores']['autor'];
           $count++;
      }
    }

		return $resultado[0];
	}

  /*Función que carga las preguntas de un examen dado
	*
	*Funcion que dado un identificador de examen nos devuelve las preguntas
	*que tiene ese examen en forma de array
	*
	* @param int $idExamen identificador de examen
	* @return $preguntas array con las preguntas del examen */
	public function cargaUnicoExamenPreguntas($idExamen, $cargaExamen=null){
		$sql ="SELECT examenes.titulo AS titulo_examen, preguntas.titulo AS titulo_pregunta, exam_preg.id_examen, exam_preg.id_pregunta AS id_pregunta, exam_preg.id, examenes.creador AS creador_examen, examenes.fecha_creado AS fecha_creado_examen, examenes.fecha_modificado AS fecha_modificado_examen, examenes.ultimo_modificador AS ultimo_modificador_examen, preguntas.creador AS creador_pregunta,  preguntas.fecha_creacion AS fecha_creado_preguntas, preguntas.ult_modificador AS ultimo_modificador_pregunta, preguntas.fecha_modificado AS fecha_modificado_pregunta, preguntas.cuerpo, preguntas.tema				FROM ((exam_preg INNER JOIN examenes ON exam_preg.id_examen =examenes.id) INNER JOIN preguntas ON preguntas.id=exam_preg.id_pregunta) WHERE exam_preg.id_examen=".$idExamen;
		$consulta = $this->query($sql);
    $resultado = [];
		$count=0;

    if((count($consulta) < 0)) {
      $resultado = null;
    } else {
      if(!isset($cargaExamen)){
        while(count($consulta) > $count ){
             $resultado[] = $consulta[$count]['preguntas'];
             $count++;
        }
      }
      else{
        while(count($consulta) > $count ){
             $resultado[] = $consulta[$count]['exam_preg'];
             $count++;
        }
      }
    }
		return $resultado;
	}

  /*Carga el historial de un examen indicado
	*
	*Funcion que dado un identificador de un examen nos devuelve el historial
	*de modificaciones de dicho examen
	*
	* @param int $idExamen identificador del examen
	* @return $historial historial de modificaciones del examen */
	public function cargaHistorialExamen($idExamen) {
		$sql = "SELECT `id`, `idExamen`, `idModificador`, `fecha_modificacion` FROM `examenes_historial` WHERE `idExamen`=".$idExamen;
    $consulta = $this->query($sql);
		$count=0;
		$historial=[];
		$i=0;

    if((count($consulta) < 0)) {
      $resultado = null;
    } else {
  		while(count($consulta) > $count ){
        $datosAutor = $this->cargaNombreApellidosAutor($consulta[$count]['examenes_historial']['idModificador']);
        $historial[$count]['historial'] = $consulta[$count]['examenes_historial'];
        $historial[$count]['fechaModificado'] = $this->formateoDateTime($consulta[$count]['examenes_historial']['fecha_modificacion']);
        $historial[$count]['nombreAutor'] = $datosAutor['nombre'];
        $historial[$count]['apellidosAutor'] = $datosAutor['apellidos'];
        $count++;
  		}
    }
		return $historial;
	}

  /*Función que devuelve el nombre y apellidos de un profesor
	*
	*Función que dado el identificador de un profesor nos devuelve sus nombres
	*y apellidos
	*
	* @param int $idProfesor identificador de un profesor
	* @return  $fila nombre y apellidos del profesor*/
	public function cargaNombreApellidosAutor($idProfesor){
		$sql = "SELECT `nombre`,`apellidos` FROM `profesores` WHERE id=".$idProfesor;
		$consulta = $this->query($sql);
		$count=0;
		$respuesta=[];
		$i=0;
    if((count($consulta) < 0)) {
      $respuesta = null;
    } else {
  		while(count($consulta) > $count ){
        $respuesta[] = $consulta[$count]['profesores'];
        $count++;
  		}
    }
		return $respuesta[0];
	}

  /*Borra el examen que le indicamos por parametro
  *
  * Funcion que dado el identificador de un examen borra dicho examen del sistema
  *devolviendo true en caso de que se haya borrado correctamente y false en caso
  *contrario
  *
  * @param int $idExamen identificador del examen
  * @return boolean $funciona true si se ha borrado el examen con exito y false en
  *caso contrario */
  public function borrarExamen($idExamen){
    $funciona=false;
    //comprobamos si se ha conectado a la base de datos
    $preguntas = $this->cargaUnicoExamenPreguntas($idExamen, true);
    foreach ($preguntas as $pregunta) {
      $sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` - 1 WHERE id=".$pregunta['id_pregunta'];
      $this->query($sqlReferencia);
    }
    $sql = "DELETE FROM examenes WHERE id=".$idExamen;
    $this->query($sql);
    $funciona=true;
    echo $funciona;
  }

}
