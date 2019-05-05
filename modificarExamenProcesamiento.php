<?php
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
	if (!$logeado) {
		header('Location: index.php');
	}

	//Cargamos los parametros post en variables y según lo que valgan llamamos a una función
	$nombreExamen = isset($_POST['nombreExamen'])? $_POST['nombreExamen']: null;
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	if($funcion == "guardarModificarExamen")
		guardarModificarExamen($nombreExamen);

	/*Función que nos devuelve toda la informacion de un examen.
	*
	*Funcion que dado un identificador de examen nos devuelve un array con
	*toda la información de este.
	*
	* @param int $idExamen identificador de un examen
	* @return $fila array con la informacion del examen*/
	function getExamen($idExamen){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT * FROM `examenes` WHERE id=".$idExamen;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: crearExamen.php');
		}
		mysqli_close($db);
		return $fila;
	}

	/*Función que guarda un examen editado en la base de datos
	*
	* Función que dado un nombre de examen se encarga de actualizar el examen almacenado en sesión en cada tabla correspondiente 
	* de manera que queda actualizado en la base de datos correctamente.
	*
	* @param string $nombreExamen nombre del examen
	* @return {void}
    */
	function guardarModificarExamen ($nombreExamen) {
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		$date = date('Y-m-d H:i:s', time());
		$nombreExamenEditar=$_SESSION['nombreExamenEditar'];

		$preguntasJsonArray = isset($_SESSION[$nombreExamenEditar])? json_decode($_SESSION[$nombreExamenEditar],true): null;
		$preguntasJsonArray['nombreExamen'] = $nombreExamen;
		$_SESSION[$nombreExamen] = json_encode($preguntasJsonArray);

		$preguntasJsonArray=$_SESSION[$nombreExamen];
		$idExamen=$_SESSION['idExamen'];

		$sqlExamen="UPDATE `examenes` SET `titulo`='".$nombreExamen."'"." ,`fecha_modificado`='".$date."',`ultimo_modificador`=".$_SESSION['id'].",`puntosPregunta`='".$preguntasJsonArray."' WHERE id=".$idExamen;

		if (mysqli_query($db,$sqlExamen)) {
			$numTemas = getNumTemasModificar($_SESSION['idAsignatura']);
			$preguntasSesion = isset($preguntasJsonArray)? json_decode($preguntasJsonArray,true): null;

			$sqlDelete= "DELETE FROM `exam_preg` WHERE id_examen=".$idExamen;

			if (mysqli_query($db,$sqlDelete)) {
				for ($i = 1; $i <= $numTemas; $i++) {
					$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
					if ($preguntasTema) {
						foreach ($preguntasTema as $pregunta) {
							$sqlExam_Preg = "INSERT INTO exam_preg (`id_examen`, `id_pregunta`, `id`) VALUES (".$idExamen.",".$pregunta['id'].",'')";

							if(!mysqli_query($db,$sqlExam_Preg))
								$_SESSION['error1'] = "Error: " . $sqlDelete .' '. mysqli_error($db);
						}
					}
				}
				$preguntasEditadasAhora = isset($_SESSION['editarExamenCambios'])? json_decode($_SESSION['editarExamenCambios'],true): null;
					if ($preguntasEditadasAhora) {
						foreach ($preguntasEditadasAhora as $id => $value) {
							if($value){
								$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` + 1 WHERE id=".$id;
								mysqli_query($db,$sqlReferencia);
							}
							else if(($value!==null)){
								$sqlReferencia = "UPDATE `preguntas` SET `referencias` = `referencias` - 1 WHERE id=".$id;
								mysqli_query($db,$sqlReferencia);
							}
						}
					}
			}
			$sql = "INSERT INTO `examenes_historial`(`id`, `idExamen`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idExamen.",".$_SESSION['id'].",'".$date."')";
			$consulta=mysqli_query($db,$sql);
			$_SESSION['editarExamenCambios'] = "{}";
		}
	}

	/*Función que dada una asignatura nos devuelve los puntos por tema que tiene
	*en su examen por defecto
	*
	*Funcion que dado un identificador de una asignatura, nos devuelve un string
	*en formato json con la cantidad de puntos por tema que tiene un examen suyo por defecto
	*
	* @param int $idAsignatura identificador de una asignatura
	* @return string $fila string con formato json que tiene la cantidad de puntos
	* por cada tema */
	function cargaPuntosTemaModificar($idAsignatura){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	/*Funcion que devuleve el numero de temas de una asignatura
	*
	*Funcion que dado un identificador de una asignatura nos devuelve un entero
	*con el numero de temas
	*
	* @param int $idAsignatura identificador de una asignatura
	* @return int $jsonUsable['numeroTemas'] numero de temas de esa asignatura  */
	function getNumTemasModificar($idAsignatura){
		$jsonNumeroTemas = cargaPuntosTemaModificar($idAsignatura);
		$jsonUsable = json_decode($jsonNumeroTemas,true);
		return $jsonUsable['numeroTemas'];
	}

?>
