<?php
	/*Funcion que nos devuelve si un profesor es o no es coordinador de una asignatura
	*
	*Funcion que dado el id de una asignatura y el id de un profesor nos devuelve un true si
	*el profesor tiene es coordinador de la asignatura y false en caso contrario
	*
	* @access public
	* @param int $idAsig identificador de la asignatura
	* @return boolean $result true si es coordinador y false en caso contrario*/
	public function esCoordinador($idAsig, $idProfesor){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$result=false;
		if($db){
			$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
			$consulta = mysqli_query($db,$sql);
			$result= mysqli_fetch_assoc($consulta);
		}
		return $result['coordinador'];
	}
?>
