<?php	
	/*function cargaUnicoExamenPreguntas($idExamen){
		$puntosTemaStr = file_get_contents('json/puntostema.json');
		$puntosTema = json_decode($puntosTemaStr, true);
		$numTemas=$puntosTema['numeroTemas'];
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
	}
	UPDATE `asignaturas` SET `puntos_tema`='"numeroTemas": 3, "tema1": 2, "tema2": 3, "tema3": 2 ' WHERE 1
*/

	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	include "modificarExamenProcesamiento.php";
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php*/
	if (!$logeado) {
		header('Location: index.php');
	}
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$nombreExamen = isset($_POST['nombreExamen'])? $_POST['nombreExamen']: null;
	$idExamen = isset($_POST['idExamen'])? $_POST['idExamen']: null;
	$idAsignatura = isset($_POST['idAsignatura'])? $_POST['idAsignatura']: null;
	$tema = isset($_POST['tema'])? $_POST['tema']: null;
	$preguntas = isset($_POST['preguntas'])? $_POST['preguntas']: null;
	$idPregunta = isset($_POST['idPregunta'])? $_POST['idPregunta']: null;
	$puntos = isset($_POST['puntos'])? $_POST['puntos']: null;
	$tema = isset($_POST['tema'])? $_POST['tema']: null;
	if($funcion == "getPregAsigTema")
		getPregAsigTema($idAsignatura,$tema);
	else if ($funcion =="aniadirPreguntas")
		aniadirPreguntas($preguntas);
	else if ($funcion == "guardarExamen")
		guardarExamen($nombreExamen);
	else if ($funcion == "cambiarNombreExamen")
		cambiarNombreExamen($nombreExamen);
	else if ($funcion == "cambiarPuntosPregunta")
		cambiarPuntosPregunta($idPregunta, $puntos, $tema);
	else if ($funcion == "eliminarPregunta")
		eliminarPregunta($idPregunta, $tema);
	else if ($funcion == 'guardarNombreExamenJSON')
		guardarNombreExamenJSON($nombreExamen, $idExamen);
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
				
				insertarPreguntaJSON($filas[$i]['tema'], $filas[$i]['id'], 1);
				//¿Llamar aquí a guardar examen cada vez que se inserte una nueva pregunta?
				/*if (!$_SESSION['editar']) {
					guardarExamen($_SESSION['nombreAsignatura']);
				} else {
					guardarModificarExamen($_SESSION['nombreExamenEditar']);
				}*/
				
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			//header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		echo json_encode($filas);
	}

	function insertarPreguntaJSON($numTema,$idPregunta,$puntosPregunta){
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
		}

		if($preguntas){
			$tema="tema".$numTema;
			//Se crea esta variable para que tanto el id como el puntos se guartden en la misma pos del array, pues si lo ponemos directamente en el[] se ponen en diferentes
			$ultimaPos=count($preguntas['preguntas'][$tema]);
			$preguntas['preguntas'][$tema][$ultimaPos]["id"] = $idPregunta;
			$preguntas['preguntas'][$tema][$ultimaPos]["puntos"] = $puntosPregunta;
		}
		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
		}
		//$preguntasSesion = $preguntas;
		//return $_SESSION[$nombreAsignatura];
	}

	function guardarExamen ($nombreExamen) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		$puntosPregunta = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		$preguntasSesion = $puntosPregunta;
		$puntosPregunta['nombreExamen'] = $nombreExamen;
		$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($puntosPregunta);

		$puntosPregunta = $_SESSION[$_SESSION['nombreAsignatura']];
		$date = date('Y-m-d H:i:s', time());

		$sqlExamen = "INSERT INTO `examenes`(`titulo`, `id`, `creador`, `fecha_creado`, `fecha_modificado`, `ultimo_modificador`, `id_asig`, `puntosPregunta`) VALUES ('".$nombreExamen."','',".$_SESSION['id'].",'".$date."','".$date."',".$_SESSION['id'].",".$_SESSION['idAsignatura'].",'".$puntosPregunta."')";//" ON DUPLICATE KEY UPDATE ";
		
		if (mysqli_query($db,$sqlExamen)) {
			//echo "Nuevo examen añadido";

			$_SESSION[$_SESSION['nombreAsignatura']] = '{
					"nombreExamen":"",
					"preguntas":{
					}
				}';

			$numTemas = getNumTemas($_SESSION['idAsignatura']);
			//$arrayPuntosTema =cargaPuntosTema($_SESSION['idAsignatura']);
			//$jsonPuntosTema = json_decode($arrayPuntosTema,true);
			$preguntasSesion = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
			$idExamenNuevo = mysqli_insert_id($db);

			for ($i = 1; $i <= $numTemas; $i++) {
				$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
				if ($preguntasTema) {
					foreach ($preguntasTema as $pregunta) {
						$sqlExam_Preg = "INSERT INTO exam_preg (`id_examen`, `id_pregunta`, `id`) VALUES (".$idExamenNuevo.",".$pregunta['id'].",'')";
						mysqli_query($db,$sqlExam_Preg);

						$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` + 1 WHERE id=".$pregunta['id'];
						mysqli_query($db,$sqlReferencia);
					}
				}
			}

			$sql = "INSERT INTO `examenes_historial`(`id`, `idExamen`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idExamenNuevo.",".$_SESSION['id'].",'".$date."')";
			$consulta=mysqli_query($db,$sql);
			//$fila=mysqli_fetch_assoc($consulta);

		} else {
			echo "Error: " . $sqlExamen . "<br>" . mysqli_error($db);
		}
		$mensaje = array();
		$mensaje['Message'] = "Examen guardado";
		echo json_encode($mensaje);	
	}

	function cambiarPuntosPregunta($idPregunta, $puntos, $tema) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			//$_SESSION['prueba'] = true;
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
		}
		
		$temaNombre="tema".$tema;
		if($preguntas){
				$preguntasTema = isset($preguntas['preguntas'][$temaNombre])? $preguntas['preguntas'][$temaNombre]: null;
				if ($preguntasTema) {
					$i = 0;
					foreach ($preguntasTema as $pregunta) {
						if ($pregunta['id']==$idPregunta) {
							$preguntas['preguntas'][$temaNombre][$i]["puntos"] = $puntos;
						}
						$i++;
					}
				}	
		}

		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
		}
		
	}

	function eliminarPregunta($idPregunta, $tema) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
		}

		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		if ($db) {
			$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` - 1 WHERE id=".$idPregunta;
			mysqli_query($db,$sqlReferencia);
		}
		
		$temaNombre="tema".$tema;
		if($preguntas){
				$preguntasTema = isset($preguntas['preguntas'][$temaNombre])? $preguntas['preguntas'][$temaNombre]: null;
				if ($preguntasTema) {
					$i = 0;
					foreach ($preguntasTema as $pregunta) {
						if ($pregunta['id']==$idPregunta) {
							array_splice($preguntas['preguntas'][$temaNombre],$i,1);
						}
						$i++;
					}
				}	
				//echo implode(" ",$preguntas['preguntas'][$temaNombre][0]);
			//$preguntas['preguntas'][$tema][$ultimaPos]["puntos"] = $puntosPregunta;
		}
		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
		}		
	}

	function guardarNombreExamenJSON($nombreExamen, $idExamen) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
		}

		$preguntas['nombreExamen'] = $nombreExamen;

		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
		}	
	}

?>
