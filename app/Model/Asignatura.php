<?php
App::uses('AppModel', 'Model');

class Asignatura extends AppModel {
  public $useTable = 'asignaturas';
  /*Función que nos devuelve las asignaturas de un profesor.
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
    if(!isset($esCoordinador[0]["prof_asig_coord"]["coordinador"]))
      return false;
    else
      return $esCoordinador[0]["prof_asig_coord"]["coordinador"];
  }

  /*Funcion que devuelve todas las asignaturas de la plataforma
	*
	*Funcion que nos devuelve un array con todas las asignaturas de la plataforma
	*
	* @return array con todas las asignaturas de la plataforma */
	public function cargaTodasAsignaturas(){
		/*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_ningunaAsignatura']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		$i=0;
		$asignaturas=array();
		$sql = "SELECT * FROM `asignaturas`";
    $consulta=$this->query($sql);
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
      $_SESSION['error_ningunaAsignatura']=true;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[] = $consulta[$count]['asignaturas'];
             $count++;
		    }
    }
    return $resultado;

	}

  /*Funcion que nos devuelve los profesores que coordinan una asignatura
	*
	*Funcion que dado el id de una asignatura devuelve el/los profesor/es que
	*coordinan la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @return $profesores_coord array con los profesores que coordinan la asignatura*/
	public function getCoordinadores(){
		/*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_ningun_coord']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		$i=0;
		$profesores_coord=array();
		$sql = "SELECT asignaturas.id AS idAsignatura, profesores.nombre AS nombre, profesores.apellidos AS apellidos, profesores.id AS id, profesores.email as email FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE prof_asig_coord.coordinador=1";
    $consulta=$this->query($sql);
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
      $_SESSION['error_ningunCoordinador']=true;
		}
    else{
		    while(count($consulta) > $count ){
            if(!isset($resultado[$consulta[$count]['asignaturas']["idAsignatura"]])){
              $resultado[$consulta[$count]['asignaturas']["idAsignatura"]] = $consulta[$count]['profesores']['nombre'];
            }
            else {
              $resultado[$consulta[$count]['asignaturas']["idAsignatura"]] .=", ".$consulta[$count]['profesores']['nombre'];
            }
             $count++;
		    }
    }
    return $resultado;
	}


  /*Funcion que nos devuelve el numero de profesores de una asignatura
	*
	*Funcion que devuelve el numero de profesores que tiene la asignatura que le
	*pasamos por parametro. Le pasamos el id de la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @return int $lista numero de profesores de la asignatura*/
	function getNumeroProfesoresAsig(){
		/*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		$sql = "SELECT COUNT(*) AS `numero_profesores`, `id_asignatura` FROM `prof_asig_coord` AS asignaturas GROUP BY `id_asignatura`";
    $consulta=$this->query($sql);
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
      $_SESSION['error_ningunProfesor']=true;
		}
    else{
		    while(count($consulta) > $count ){
			       $resultado[$consulta[$count]['asignaturas']["id_asignatura"]] = $consulta[$count][0]['numero_profesores'];
             $count++;
		    }
    }
    return $resultado;
	}

  /*Funcion que nos devuelve los profesores que coordinan y que no coordinan
	*una asignatura
	*
  *Funcion que dado un identificador de una asignatura nos devuelve los profesores
	*que coordinan esa asignatura y los que no la coordinan
	*
	* @param	int $idAsig identificador de una asignatura
	* @return $resultado un array de dos posiciones, en la posicion 'profSiCoord'
	* los profesores que coordinan y en 'profNoCoord' los profesores no coordinan
	* la asignatura */
	function getProfesoresAdmin($idAsig) {
		$sql = 'SELECT `nombre`, `apellidos`, `email`, `id` FROM `profesores`';
    $consulta=$this->query($sql);
    $profNoCoord = [];
		$profSiCoord = [];
		$resultado = [];
    $count = 0;
    if((count($consulta) < 0)) {
      $resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
          if($this->esCoordinador($idAsig, $consulta[$count]["profesores"]["id"]))
    					$profSiCoord[] = $consulta[$count]["profesores"];
  				else
    					$profNoCoord[] = $consulta[$count]["profesores"];
          $count++;
		    }
    }
    $resultado['profSiCoord']= $profSiCoord;
    $resultado['profNoCoord']= $profNoCoord;
    return $resultado;
	}

  /*Funcion que modifica los coordinadores que hay en una asignatura
	*
	*Funcion que dado un identificador de una asignatura modifica los profesores que
	*son cooordinadores y los profesores que no son coordinadores de la asignatura.
	*Estos profesores vienen definidos en dos listas que les pasamos por parametro
	*
	* @param int $idAsig identificador de la asignatura
	* @param string $idProfSelect string en formato json con los profesores que
	*	son coordinadores de la asignatura
	* @param string $idProfSelect string en formato json con los profesores que
	*	no son coordinadores de la asignatura
	* @return boolean $success devuelve true en caso de que la modificación se
	* haya llevado con exito y false en caso contrario */
	function setCoordinadores($idAsig, $idProfSelect, $idProfNoSelect){
		$arrayIdProfSelect = json_decode($idProfSelect);
		$arrayIdProfNoSelect = json_decode($idProfNoSelect);

		for($i=0; $i < count($arrayIdProfSelect); $i++) {
			$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_profesor`='.$arrayIdProfSelect[$i].' and`id_asignatura`='.$idAsig;
      $consulta=$this->query($sql);
			if($consulta[0][0]['existe']){
				$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=1 WHERE `id_profesor`='.$arrayIdProfSelect[$i].' and`id_asignatura`='.$idAsig;
        $consulta=$this->query($sql);
			}else{
				$sql = "INSERT INTO `prof_asig_coord`(`id_profesor`, `id_asignatura`, `coordinador`, `id`) VALUES (".$arrayIdProfSelect[$i].",".$idAsig.",1,'')";
        $consulta=$this->query($sql);
			}
		}
		$arrayIdProfNoSelect = json_decode($idProfNoSelect);

		for($i=0; $i < count($arrayIdProfNoSelect); $i++) {
			$sql= 'SELECT count(`id_profesor`) as `existe` FROM `prof_asig_coord` WHERE `id_profesor`='.$arrayIdProfNoSelect[$i].' and`id_asignatura`='.$idAsig;
      $consulta=$this->query($sql);
			if($consulta[0][0]['existe']){
				$sql= 'UPDATE `prof_asig_coord` SET `coordinador`=0 WHERE `id_profesor`='.$arrayIdProfNoSelect[$i].' and`id_asignatura`='.$idAsig;
				$consulta=$this->query($sql);
			}
		 }
	   echo true;
	}
}
