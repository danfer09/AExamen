<?php
//ip del servidor, nombre de usr de bbdd, contraseña de usuario, nombre de base de datos
	/*$db =@mysqli_connect('', '', '',"");
	if($db){
		echo 'Connected successfully';
		$sql="SELECT * FROM profesores";
		$consulta=mysqli_query($db,$sql);
		//$fila=mysqli_fetch_assoc($consulta);
		$matches = mysqli_fetch_assoc($consulta);
		echo "<option value=".$matches['nombre'].">".$matches['nombre']."</option>";
		
		@mysqli_close($db);
	}*/

	/*
		Devuelve el resultado del select para todos los profesores
	*/
	function selectAllProfesores($db) {
		if($db){
			$sql = "SELECT * FROM profesores";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay profesores";
				$resultado = null;
			}
			return $resultado;
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Devuelve el resultado del select para todas las asignaturas
	*/
	function selectAllAsignaturas($db) {
		if($db){
			$sql = "SELECT * FROM asignaturas";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay profesores";
				$resultado = null;
			}
			return $resultado;
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Devuelve el resultado del select para todos los examenes
	*/
	function selectAllExamenes($db) {
		if($db){
			$sql = "SELECT * FROM examenes";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay profesores";
				$resultado = null;
			}
			return $resultado;
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Devuelve el resultado del select para todas las preguntas
	*/
	function selectAllPreguntas($db) {
		if($db){
			$sql = "SELECT * FROM preguntas";
			$consulta=mysqli_query($db,$sql);
			$resultado = [];
			if($consulta->num_rows > 0){
				while ($fila=mysqli_fetch_assoc($consulta)){
					$resultado[] = $fila;
				}
			} else {
				echo "No hay profesores";
				$resultado = null;
			}
			return $resultado;
		} else {
			echo "conexión fallida";
		}
	}

	/*
		Elimina el profesor con el $id pasado por parámetro a la función
	*/
	function deleteProfesor($db, $email) {
		if($db && $email){
			$sql = "SELECT * FROM profesores WHERE email='".$email."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM profesores WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				return true;
			} else {
				echo "No existe el profesor con email ".$email." o está repetido";
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Elimina la asignatura con el $id pasado por parámetro a la función
	*/
	function deleteAsignatura($db, $siglas) {
		if($db && $siglas){
			$sql = "SELECT * FROM asignaturas WHERE siglas='".$siglas."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM asignaturas WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				return true;
			} else {
				echo "No existe la asignatura con siglas ".$siglas." o está repetida";
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Elimina el examen con el $id pasado por parámetro a la función
	*/
	function deleteExamen($db, $id) {
		if($db && $id){
			$sql = "SELECT * FROM examenes WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM examenes WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				return true;
			} else {
				echo "No existe el examen con id ".$id;
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Elimina la pregunta con el $id pasado por parámetro a la función
	*/
	function deletePreguntas($db, $id) {
		if($db && $id){
			$sql = "SELECT * FROM preguntas WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows == 1){
				$fila=mysqli_fetch_assoc($consulta);
				$sql = "DELETE FROM preguntas WHERE id='".$fila['id'];
				$consulta = mysqli_query($db, $sql);
				return true;
			} else {
				echo "No existe la pregunta con id ".$id;
				return false;
			}
		} else {
			echo "Conexión fallida";
		}
	}

	/*
		Comprueba si un determinado profesor con el $id pasado por parámetro es coordinador de alguna asignatura
	*/
	function esCoordinador ($db, $id) {
		if($db){
			$sql = "SELECT * FROM profesores WHERE id='".$id."'";
			$consulta=mysqli_query($db,$sql);
			if($consulta->num_rows > 0){
				$fila=mysqli_fetch_assoc($consulta);
				return $fila['coordinador'];
			} else {
				echo "No existe el profesor con id ".$id;
				return null;
			}
		} else {
			echo "Conexión fallida";
		}
	}

?>