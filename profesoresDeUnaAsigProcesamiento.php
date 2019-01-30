<?php


$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;
$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
if($funcion == "borrarProfesorDeAsig")
	borrarProfesorDeAsig($idProfesor, $idAsig);
else if($funcion == "getProfesoresFueraAsig"){
	getProfesoresFueraAsig($idAsig);
}

function profesoresAsignatura($idAsig) {
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	if($db){
		$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id FROM `profesores` INNER JOIN `prof_asig_coord` ON profesores.id=prof_asig_coord.id_profesor WHERE prof_asig_coord.id_asignatura='.$idAsig.' and prof_asig_coord.coordinador = 0';
		$consulta=mysqli_query($db,$sql);
		$resultado = [];
		if($consulta->num_rows > 0){
			while ($fila=mysqli_fetch_assoc($consulta)){
				$resultado[] = $fila;
			}
		} else {
			$resultado = null;
		}
		mysqli_close($db);
		return $resultado;
	} else {
		echo "Conexión fallida";
		return false;
	}
}

function borrarProfesorDeAsig($idProfesor, $idAsig){
	$funciona=false;
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	//comprobamos si se ha conectado a la base de datos

	if($db){
		$sql = "DELETE FROM `prof_asig_coord` WHERE id_profesor=".$idProfesor." and id_asignatura=".$idAsig;
		$consulta=mysqli_query($db,$sql);
		$funciona=true;
	}
	else{
		$_SESSION['error_BBDD']=true;
		$funciona=false;
	}
	mysqli_close($db);

	echo $funciona;
}
//No funciona esta funcion, ya que la query no esta bien hecha
function getProfesoresFueraAsig($idAsig){
	$credentialsStr = file_get_contents('json/credentials.json');
	$credentials = json_decode($credentialsStr, true);
	$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

	if($db){
		$sql = 'SELECT `nombre`, `apellidos`, `email`, profesores.id FROM (`profesores` INNER JOIN `prof_asig_coord` ON profesores.id=prof_asig_coord.id_profesor) JOIN (SELECT p1.nombre, p1.apellidos, p1.email,  p1.id FROM profesores p1 INNER JOIN prof_asig_coord p2 ON p1.id=p2.id_profesor WHERE p2.id_asignatura=1 and p2.coordinador = 0) p_in WHERE prof_asig_coord.id_asignatura<>1 and prof_asig_coord.coordinador = 0 AND p_in.id<>profesores.id';
		$consulta=mysqli_query($db,$sql);
		$resultado = [];
		if($consulta->num_rows > 0){
			while ($fila=mysqli_fetch_assoc($consulta)){
				$resultado[] = $fila;
			}
		} else {
			$resultado = null;
		}
		mysqli_close($db);
		return $resultado;
	} else {
		echo "Conexión fallida";
		return false;
	}
}



?>