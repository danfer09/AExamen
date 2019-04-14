<?php 
//error_reporting(0); // Disable all errors.

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
	/*else{
		return false;
	}*/

	//Comprobamos que el método empleado es POST
	function cargaPreguntas($idAsignatura, $emailAutor){
		$_SESSION['error_ningunaPregunta']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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
				//header('Location: asignaturasProfesor.php');
			}	
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);
		return $preguntas;
	}

	function cargaUnicaPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	function cargaHistorialPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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
	function cargaAutorPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	function cargaNombreApellidosAutor($idUsuario){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT `nombre`,`apellidos` FROM `profesores` WHERE id=".$idUsuario;
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
	function cargaModificadorPregunta($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
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

	function aniadirPregunta($titulo,$cuerpo,$tema){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos

		if($db){
			date_default_timezone_set('Europe/Berlin');
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
			//Something to write to txt log
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> ".$_SESSION['email']. " creo una nueva pregunta en la asignatura ".$fila['nombre'].PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						//Save string to log, use FILE_APPEND to append.
						file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);
		}
		else{
			$_SESSION['error_BBDD']=true;
			$funciona=false;
		}
		mysqli_close($db);

		echo $funciona;
		//INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','Titulo pregunta insertada','Cuerpo pregunta insertada','3','3','','3','','2')
	}

	function borrarPregunta($idPregunta){
		$_SESSION['error_no_poder_borrar'] = false;
		$idUsuario = $_SESSION['id'];
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos

		if($db){
			$sql = "SELECT `referencias`,`asignatura` FROM `preguntas` WHERE id=".$idPregunta;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$numRef = $fila['referencias'];
			$asignatura = $fila['asignatura'];

			$sql = "SELECT `nombre` FROM `asignaturas` a INNER JOIN `preguntas` p on a.id = p.asignatura WHERE p.id=".$idPregunta;
					echo $sql;
					$consulta=mysqli_query($db,$sql);
					$fila=mysqli_fetch_assoc($consulta);

			if (esCoordinador($asignatura, $idUsuario) || $_SESSION['administrador']) {
				if ($numRef == 0) {
					$sql = "DELETE FROM `preguntas` WHERE id=".$idPregunta;
					$consulta=mysqli_query($db,$sql);

					//$fila=mysqli_fetch_assoc($consulta);
					$funciona=true;

					//Something to write to txt log
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> ".$_SESSION['email']. " borró una pregunta de la asignatura ".$fila['nombre']." como coordinador o como administrador ". PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						//Save string to log, use FILE_APPEND to append.
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
					//$fila=mysqli_fetch_assoc($consulta);
					$funciona=true;
					//Something to write to txt log
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> ".$_SESSION['email']. " borró una pregunta de la asignatura ".$fila['nombre']. PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						//Save string to log, use FILE_APPEND to append.
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
		//INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','Titulo pregunta insertada','Cuerpo pregunta insertada','3','3','','3','','2')
	}

	function esCoordinador($idAsig, $idProfesor){
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

	function editarPregunta($titulo,$cuerpo,$tema,$idPregunta){
		$funciona=false;
		$credentialsStr = file_get_contents('json/credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos

		if($db){
			date_default_timezone_set('Europe/Berlin');
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
		//INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','Titulo pregunta insertada','Cuerpo pregunta insertada','3','3','','3','','2')
	}



//SELECT prof_asig_coord.coordinador AS coordinador, profesores.nombre AS nombre_profesor, asignaturas.nombre AS nombre_asignatura FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE id_profesor='4'
?>
