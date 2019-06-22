<?php
App::uses('AppModel', 'Model');

class Profesor extends AppModel {
  public $useTable = 'profesores';
  /*Funcion que nos devuelve todos los profesores de la aplicacion
	*
	*Función que nos devuelve todos los profesores de la aplicacion en un array,
	*null en caso de que no haya profesores y false en caso de que haya habido un
	*fallo con la conexion de la BBDD
	*
	* @return  $resultado array con todos los profesores de la aplicacion, null
	* en caso de que no haya profesores y false en caso de que haya habido un
	* fallo con la conexion de la BBDD*/
	function getProfesoresAdmin() {
		$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id as id FROM `profesores`';
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

  /*Función que dado un profesor lo borra de la aplicacion
	*
	*Función que dado el identificador de un profesor lo borra de la aplicación
	*
	* @param int $id identificador de la profesor
	* @return boolean $funciona true en caso de que se haya borrado correctamente
	* y false en caso contrario*/
	function borrarProfesor($id) {
		$funciona=false;
		$admin = $_SESSION['administrador'];
		if ($admin) {
			$sql = 'DELETE FROM profesores WHERE id='.$id;
      $consulta=$this->query($sql);
			$funciona = true;
		} else {
			$_SESSION['error_no_poder_borrar'] = true;
		}
		echo $funciona;
	}

  /*Funcion que edita los campos de un profesor
	*
	*Función que dado un nombre, unos apellidos, un email y un identificador de
	*profesor edita los datos de ese profesor
	*
	* @param string $nombre nombre que queremos establecer al profesor
	* @param string $apellidos apellidos que queremos establecer al profesor
	* @param string $email email que queremos establecer al profesor
	* @param int $idProfesor identificador de un profesor
	* @return boolean $funciona true en caso de que se haya editado correctamente
	* y false en caso contrario*/
	function editarProfesor($nombre, $apellidos, $email, $idProfesor) {
		$admin = $_SESSION['administrador'];
		if ($admin) {
			$sql = "UPDATE `profesores` SET `nombre`='".$nombre."',`apellidos`='".$apellidos."',`email`='".$email."' WHERE id=".$idProfesor;
      $consulta=$this->query($sql);
			$funciona = true;
		}
		echo $funciona;
	}
}
