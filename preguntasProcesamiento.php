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
	$titulo = isset($_POST['titulo'])? $_POST['titulo']: null;
	$cuerpo = isset($_POST['cuerpo'])? $_POST['cuerpo']: null;
	$tema = isset($_POST['tema'])? $_POST['tema']: null;
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	$idPregunta = isset($_POST['id_pregunta'])? $_POST['id_pregunta']: null;
	if($funcion == "aniadirPregunta")
		aniadirPregunta($titulo,$cuerpo,$tema);
	else if($funcion =="borrarPregunta"){
		borrarPregunta($idPregunta);
	}
	else if($funcion == "editarPregunta"){
		editarPregunta($titulo,$cuerpo,$tema,$idPregunta);
	}

	/*Funcion que dado una asigntura y un profesor nos devuleve las preguntas de ese
	*profesor de esa asignatura
	*
	*Funcion que dado el identificador de una asignatura y el email de un profesor
	*nos devuelve las preguntas de ese profesor en esa asignatura. En caso de que
	*le pasemos en vez del mail de un profesor el string 'todos', nos devolvera
	*todas las preguntas de una asignatura
	*
	* @param int $idAsignatura identificador de una asignatura
	* @param string $emailAutor email de un profesor. 'todos' si queremos todas
	*	las preguntas de la asignatura
	* @return $preguntas array con las preguntas */
	function cargaPreguntas($idAsignatura, $emailAutor){
		$_SESSION['error_ningunaPregunta']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$i=0;
		$preguntas=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT asignaturas.siglas AS siglasAsignatura,  profesores.nombre AS autor, preguntas.titulo AS titulo, preguntas.cuerpo AS cuerpo, preguntas.tema AS tema, preguntas.fecha_creacion AS fecha_creacion, preguntas.fecha_modificado AS fecha_modificado, preguntas.id AS id_preguntas
				FROM ((preguntas INNER JOIN asignaturas ON asignaturas.id =".$idAsignatura.") INNER JOIN profesores ON preguntas.creador=profesores.id)
				WHERE preguntas.asignatura=".$idAsignatura;

			if ($emailAutor != "todos") {
				$sql = $sql." AND profesores.email='".$emailAutor."'";
			}

			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

			while($fila){
				$preguntas[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
			if($i==0){
				$_SESSION['error_ningunaPregunta']=true;
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
		}
		mysqli_close($db);
		return $preguntas;
	}

	/*Función que nos devuelve una pregunta
	*
	*Función que dado un identificador de una pregunta nos devuelve toda la
	*información de dicha pregunta
	*
	* @param int $idPregunta identificador de una pregunta
	* @return $fila array con toda la informacion de la pregunta*/
	function cargaUnicaPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT * FROM `preguntas` WHERE id=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila;
	}

	/*Función que nos devuelve el historial de modificaciones de una pregunta
	*
	*Función qeu dado un identificador de una pregunta nos devuelve su historial
	*de modificaciones
	*
	* @param int $idPregunta identificador de una pregunta
	* @return $fila array con el historial de modificaciones de la pregunta*/
	function cargaHistorialPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT `id`, `idPregunta`, `idModificador`, `fecha_modificacion` FROM `preguntas_historial` WHERE `idPregunta`=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$historial=array();
			$i=0;
			while($fila){
				$historial[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $historial;
	}

	/*Función que nos devuelve el autor de una pregunta
	*
	*Función que dado el identificador de una pregunta nos devuleve el nombre del
	*autor de dicha pregunta
	*
	* @param int $idPregunta identificador de una pregunta
	* @return string $fila['autor'] nombre del autor de la pregunta*/
	function cargaAutorPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS autor FROM preguntas INNER JOIN profesores ON preguntas.creador=profesores.id WHERE preguntas.id=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila['autor'];

	}

	/*Función que devuelve el nombre y apellidos de un profesor
	*
	*Función que dado el identificador de un profesor nos devuelve sus nombres
	*y apellidos
	*
	* @param int $idProfesor identificador de un profesor
	* @return  $fila nombre y apellidos del profesor*/
	function cargaNombreApellidosAutor($idProfesor){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT `nombre`,`apellidos` FROM `profesores` WHERE id=".$idProfesor;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila;
	}

	/*Función que nos devuleve el ultimo modificador de una pregunta
	*
	*Función que dado un identificador de una pregunta nos devuelve el nombre
	*del ultimo profesor que la ha modificado
	*
	* @param int $idPregunta identificador de una pregunta
	* @return  $fila['modificador'] nombre del profesor que ha modificado la pregunta*/
	function cargaModificadorPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS modificador FROM preguntas INNER JOIN profesores ON preguntas.ult_modificador=profesores.id WHERE preguntas.id=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $fila['modificador'];

	}

	/*Función que añade una pregunta a la asignatura actual
	*
	*Función que dado un titulo, un cuerpo y un tema, añade una pregunta con estos
	*campos a la asignatura en la que nos encontramos actualmente
	*
	* @param int $titulo titulo para la pregunta
	* @param int $cuerpo cuerpo para la pregunta
	* @param int $tema tema de la pregunta
	* @return boolean $funciona true en caso de que la pregunta se haya añadido
	* correctamente y false en caso contrario*/
	function aniadirPregunta($titulo,$cuerpo,$tema){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		date_default_timezone_set("Europe/Madrid");

		//comprobamos si se ha conectado a la base de datos

		if($db){
			$date = date('Y-m-d H:i:s', time());
			$sql = "INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','".$titulo."','".$cuerpo."','".$tema."','".$_SESSION['id']."','".$date."','".$_SESSION['id']."','".$date."','".$_SESSION['idAsignatura']."')";
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

			$idPreguntaNueva = mysqli_insert_id($db);

			$sql = "INSERT INTO `preguntas_historial`(`id`, `idPregunta`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idPreguntaNueva.",".$_SESSION['id'].",'".$date."')";
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

			$sql = "SELECT `nombre` FROM `asignaturas` WHERE id=".$_SESSION['idAsignatura'];
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);


			$funciona=true;
			//Escribimos en el log que usuario ha añadido la pregunta y a que asignatura
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> ".$_SESSION['email']. " creo una nueva pregunta en la asignatura ".$fila['nombre'].PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);
		echo $funciona;
	}
	/*Función que borra una pregunta
	*
	*Función que dado el identificador de una pregunta la borra de la BBDD
	*
	* @param int $idPregunta identificador de la pregunta
	* @return boolean $funciona true en caso de que la pregunta se haya borrado
	* correctamente y false en caso contrario*/
	function borrarPregunta($idPregunta){
		$_SESSION['error_no_poder_borrar'] = false;
		$idUsuario = $_SESSION['id'];
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT `referencias`,`asignatura` FROM `preguntas` WHERE id=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$numRef = $fila['referencias'];
			$asignatura = $fila['asignatura'];

			//Obtenemos los datos de la pregunta que queremos borrar para poder mostrarlos en el log
			$sql = "SELECT `nombre` FROM `asignaturas` a INNER JOIN `preguntas` p on a.id = p.asignatura WHERE p.id=".$idPregunta;
					$consulta=mysqli_query($db,$sql);
					$fila=mysqli_fetch_assoc($consulta);

			//Antes de borrar comprobamos que, o bien sea un coordinador o administrador,
			//o la pregunta sea propia
			if (esCoordinador($asignatura, $idUsuario) || $_SESSION['administrador']) {
				if ($numRef == 0) {
					$sql = "DELETE FROM `preguntas` WHERE id=".$idPregunta;
					$consulta=mysqli_query($db,$sql);
					$funciona=true;

					//Escribirmos en el log que usuario ha borrado la pregunta y que la ha
					//borrado como coordinador o como administrador
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> ".$_SESSION['email']. " borró una pregunta de la asignatura ".$fila['nombre']." como coordinador o como administrador ". PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
				} else {
					$_SESSION['error_no_poder_borrar'] = true;
				}
			} else {
				if ($numRef == 0) {
					$sql = "DELETE FROM `preguntas` WHERE id=".$idPregunta." AND creador=".$idUsuario;
					$consulta=mysqli_query($db,$sql);

					if(mysqli_affected_rows($db)== 0){
						$_SESSION['error_BorrarNoCreador']=true;
					}
					$funciona=true;
					//Escribirmos en el log que usuario ha borrado la pregunta y que la ha
					//borrado porque es propia
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> ".$_SESSION['email']. " borró una pregunta de la asignatura ".$fila['nombre']. PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
				} else {
					$_SESSION['error_no_poder_borrar'] = true;
				}
			}
		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);

		echo $funciona;
	}

	/*Función que dado un profesor y una asignatura, nos devuelve si dicho profesores
	* es coordinador o no
	*
	*Función que dado el identificador de un profesor y el de una asignatura nos
	*devuleve si dicho profesor es o no un coordinador de la asignatura
	*
	* @param int $idAsig identificador de la asignatura
	* @param int $idProfesor identificador de la profesor
	* @return boolean $result['coordinador'] true en caso de que la sea coordinador
	* y false en caso contrario*/
	function esCoordinador($idAsig, $idProfesor){
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$result=false;
		if($db){
			$sql = "SELECT coordinador FROM `prof_asig_coord` WHERE `id_profesor` =".$idProfesor." and `id_asignatura`=".$idAsig;
			$consulta = mysqli_query($db,$sql);
			$result= mysqli_fetch_assoc($consulta);
		}
		return $result['coordinador'];
	}

	/*Funcion que edita los valores de una pregunta
	*
	*Función que dado un titulo, un cuerpo, un tema y un identificador de una
	*pregunta, modifica con esos valores los valores que tenian esos campos en la
	*pregunta
	*
	* @param int $titulo titulo para la pregunta
	* @param int $cuerpo cuerpo para la pregunta
	* @param int $tema tema de la pregunta
	* @param int $idPregunta identificador de la pregunta
	* @return boolean $funciona true en caso de que se haya editado con exito
	* y false en caso contrario*/
	function editarPregunta($titulo,$cuerpo,$tema,$idPregunta){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('sql7.freemysqlhosting.net', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		date_default_timezone_set("Europe/Madrid");

		//comprobamos si se ha conectado a la base de datos
		if($db){
			$date = date('Y-m-d H:i:s', time());
			$sql = "UPDATE `preguntas` SET ";
			if($titulo != ''){
				$sql = $sql."`titulo`='".$titulo."'";
				$entraTitulo = true;
			}
			if($cuerpo != ''){
				$sql = ($entraTitulo)?$sql.",`cuerpo`='".$cuerpo."'": $sql."`cuerpo`='".$cuerpo."'";
				$entraCuerpo=true;
			}
			if($tema != '')
				$sql =  ($entraTitulo||$entraCuerpo)? $sql.",`tema`=".(int)$tema."": $sql."`tema`=".(int)$tema."";

			$sql= $sql.",`ult_modificador`=".$_SESSION['id'].",`fecha_modificado`='".$date."'WHERE id=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$funciona=true;



			$sql = "INSERT INTO `preguntas_historial`(`id`, `idPregunta`, `idModificador`, `fecha_modificacion`) VALUES ('',".$idPregunta.",".$_SESSION['id'].",'".$date."')";
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);

		echo $funciona;
	}

?>
