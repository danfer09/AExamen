<?php	
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

	//Cargamos los parametros post en variables y según lo que valgan llamamos a la función correspondiente
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
	

	/*Función que nos devuelve los puntos correspondientes a cada tema de una asignatura
	*
	* Función que dado un id de una asignatura, devuelve un json con la correspondencia
	* entre tema-puntos establecido por el coordinador de la asignatura
	* 
	* @param int $idAsignatura identificador de la asignatura
	* @return $fila['puntos_tema'] json con los puntos por cada tema
    */
	function cargaPuntosTema($idAsignatura){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql300.epizy.com', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	/*Función que nos devuelve el número total de temas para una asignatura
	*
	* Función que dado un id de una asignatura, devuelve el número total de temas
	* en dicha asignatura
	* 
	* @param int $idAsignatura identificador de la asignatura
	* @return $jsonUsable['numeroTemas'] int, número de temas
    */
	function getNumTemas($idAsignatura){
		$jsonNumeroTemas = cargaPuntosTema($idAsignatura);
		$jsonUsable = json_decode($jsonNumeroTemas,true);
		return $jsonUsable['numeroTemas'];
	}

	/*Función que nos devuelve las preguntas de un tema de una asignatura
	*
	* Función que dado un id de una asignatura y un tema, devuelve todas las preguntas
	* existentes para ese tema de esa asignatura
	* 
	* @param int $idAsignatura identificador de la asignatura
	* @param int $tema número de tema
	* @return $preguntas (en forma AJAX) json con las preguntas
    */
	function getPregAsigTema($idAsignatura,$tema){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql300.epizy.com', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	/*Función que actualiza las preguntas del examen en sesión y devuelve el json con todas ellas
	*
	* Función que dados unos id de preguntas, obtiene de BBDD toda la información de las preguntas 
	* para introducirla en la variable de sesión correspondiente mediante la llamada a insertarPreguntaJSON
	*
	* @param array $preguntas ids de preguntas
	* @return $filas (en forma AJAX) json con las preguntas añadidas
    */
	function aniadirPreguntas($preguntas){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql300.epizy.com', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		
		if($db){
			$total = count($preguntas);
		    for($i=0; $i < $total; $i++){
			    $sql ="SELECT * FROM `preguntas` WHERE id=".$preguntas[$i];
			    $consulta=mysqli_query($db,$sql);
				$filas[$i]=mysqli_fetch_assoc($consulta);
				
				insertarPreguntaJSON($filas[$i]['tema'], $filas[$i]['id'], 1);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
		}
		mysqli_close($db);
		echo json_encode($filas);
	}

	/*Función que inserta una pregunta (cuando es seleccionada y añadida) en la variable de sesión el examen actual
	*
	* Función que dado un número de tema, un id de pregunta y un valor de puntos para una pregunta
	* inserta en la variable de sesión correspondiente una pregunta
	*
	* @param int $numTema número de tema
	* @param int $idPregunta id de pregunta
	* @param int $puntosPregunta puntos que vale la pregunta
	* @return {void}
    */
	function insertarPreguntaJSON($numTema,$idPregunta,$puntosPregunta){
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
			$preguntasEditar =  isset($_SESSION['editarExamenCambios'])? json_decode($_SESSION['editarExamenCambios'],true): null;
		}

		if($preguntas){
			$tema="tema".$numTema;
			//Se crea esta variable para que tanto el id como el puntos se guarden en la misma pos del array, pues si lo ponemos directamente en el[] se ponen en diferentes posiciones
			$ultimaPos=count($preguntas['preguntas'][$tema]);
			$preguntas['preguntas'][$tema][$ultimaPos]["id"] = $idPregunta;
			$preguntas['preguntas'][$tema][$ultimaPos]["puntos"] = $puntosPregunta;
			
		}

		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
			$preguntasEditar[$idPregunta] = isset($preguntasEditar[$idPregunta])? null: true;
			$_SESSION['editarExamenCambios'] = json_encode($preguntasEditar);
		}
	}

	/*Función que guarda un examen en la base de datos
	*
	* Función que dado un nombre de examen se encarga de guardar el examen almacenado en sesión en cada tabla correspondiente 
	* de manera que queda guardado en la base de datos correctamente.
	*
	* @param string $nombreExamen nombre del examen
	* @return $mensaje (formato AJAX) string con el mensaje de éxito o fracaso guardando el examen
    */
	function guardarExamen ($nombreExamen) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql300.epizy.com', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		date_default_timezone_set("Europe/Madrid");

		$puntosPregunta = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		$preguntasSesion = $puntosPregunta;
		$puntosPregunta['nombreExamen'] = $nombreExamen;
		$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($puntosPregunta);
		$mensaje = array();

		$puntosPregunta = $_SESSION[$_SESSION['nombreAsignatura']];
		$date = date('Y-m-d H:i:s', time());

		$sqlExamen = "INSERT INTO `examenes`(`titulo`, `id`, `creador`, `fecha_creado`, `fecha_modificado`, `ultimo_modificador`, `id_asig`, `puntosPregunta`) VALUES ('".$nombreExamen."','',".$_SESSION['id'].",'".$date."','".$date."',".$_SESSION['id'].",".$_SESSION['idAsignatura'].",'".$puntosPregunta."')";
		
		if (mysqli_query($db,$sqlExamen)) {

			$numTemas = getNumTemas($_SESSION['idAsignatura']);
			$preguntasSesion = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
			$idExamenNuevo = mysqli_insert_id($db);

			//Añadimos al log la creación del nuevo examen
			$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].', '.$_SESSION['email'].
			        " | ACTION --> Nuevo examen de ".$_SESSION['nombreAsignatura']." creado con id ".$idExamenNuevo.PHP_EOL.
			        "-----------------------------------------------------------------".PHP_EOL;
			file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

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

			$mensaje['Message'] = "Examen guardado";
		} else {
			$mensaje['Message'] = "Examen no guardado";
			echo "Error: " . $sqlExamen . "<br>" . mysqli_error($db);
		}
		
		
		$_SESSION[$_SESSION['nombreAsignatura']] = '{
					"nombreExamen":"",
					"preguntas":{
					}
				}';
		echo json_encode($mensaje);	
	}

	/*Función que actualiza los puntos de una pregunta de un examen en variable de sesión
	*
	* Función que dado un id de pregunta, un valor de puntos y un tema, actualiza dicha pregunta y recarga el examen de variable de sesión
	* para el examen actual
	*
	* @param int $idPregunta id de pregunta
	* @param int $puntos puntos que vale la pregunta
	* @param int $tema tema al que pertenece la pregunta
	* @return {void}
    */
	function cambiarPuntosPregunta($idPregunta, $puntos, $tema) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
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

	/*Función que elimina una pregunta del examen en sesión
	*
	* Función que dado un id de pregunta y un tema, elimina la pregunta del examen
	*
	* @param int $idPregunta id de pregunta
	* @param int $tema tema al que pertenece la pregunta
	* @return {void}
    */
	function eliminarPregunta($idPregunta, $tema) {
		if (!$_SESSION['editar']) {
			$preguntas = isset($_SESSION[$_SESSION['nombreAsignatura']])? json_decode($_SESSION[$_SESSION['nombreAsignatura']],true): null;
		} else {
			$preguntas = isset($_SESSION[$_SESSION['nombreExamenEditar']])? json_decode($_SESSION[$_SESSION['nombreExamenEditar']],true): null;
			$preguntasEditar =  isset($_SESSION['editarExamenCambios'])? json_decode($_SESSION['editarExamenCambios'],true): null;
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
		}
		if (!$_SESSION['editar']) {
			$_SESSION[$_SESSION['nombreAsignatura']] = json_encode($preguntas);
		} else {
			$_SESSION[$_SESSION['nombreExamenEditar']] = json_encode($preguntas);
			$preguntasEditar[$idPregunta] = isset($preguntasEditar[$idPregunta])? null: false;
			$_SESSION['editarExamenCambios'] = json_encode($preguntasEditar);
		}		
	}

	/*Función que guarda el nombre del examen actual en sesión
	*
	* Función que dado un nombre de examen guarda dicho nombre para el examen en sesión
	*
	* @param int $nombreExamen nombre del examen
	* @return {void}
    */
	function guardarNombreExamenJSON($nombreExamen) {
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
