<?php	
	/*function cargaUnicoExamenPreguntas($idExamen){
		$puntosTemaStr = file_get_contents('json/puntostema.json');
		$puntosTema = json_decode($puntosTemaStr, true);
		$numTemas=$puntosTema['numeroTemas'];
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	}
	UPDATE `asignaturas` SET `puntos_tema`='"numeroTemas": 3, "tema1": 2, "tema2": 3, "tema3": 2 ' WHERE 1
*/



	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idAsignatura = isset($_POST['idAsignatura'])? $_POST['idAsignatura']: null;
	$tema = isset($_POST['tema'])? $_POST['tema']: null;
	$preguntas = isset($_POST['preguntas'])? $_POST['preguntas']: null;
	if($funcion == "getPregAsigTema")
		getPregAsigTema($idAsignatura,$tema);
	elseif($funcion =="aniadirPreguntas")
		aniadirPreguntas($preguntas);

	/*else if($funcion ==""){
		borrarPregunta($idPregunta);
	}
	else if($funcion == "editarPregunta")
		//if($titulo)
		editarPregunta($titulo,$cuerpo,$tema,$idPregunta);
	*/

	function cargaPuntosTema($idAsignatura){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT puntos_tema FROM `asignaturas` WHERE id=".$idAsignatura;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila['puntos_tema'];
	}
	function getNumTemas($idAsignatura){
		$jsonNumeroTemas = cargaPuntosTema($idAsignatura);
		$jsonUsable = json_decode($jsonNumeroTemas,true);
		return $jsonUsable['numeroTemas'];
	}

	function getPregAsigTema($idAsignatura,$tema){

		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		$preguntas=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql ="SELECT * FROM `preguntas` WHERE asignatura=".$idAsignatura." AND tema=".$tema;

			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$i=0;
			while($fila){
				$preguntas[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		echo json_encode($preguntas);
	}

	function aniadirPreguntas($preguntas){

		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		
		if($db){
			$total = count($preguntas);
		    for($i=0; $i < $total; $i++){
			    $sql ="SELECT * FROM `preguntas` WHERE id=".$preguntas[$i];
			    $consulta=mysqli_query($db,$sql);
				$filas[$i]=mysqli_fetch_assoc($consulta);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		echo json_encode($filas);
	}

?>
