<?php 
//error_reporting(0); // Disable all errors.

//HACER COMPROBACION DE QUE EL USUARIO ESTA LOGEADO, SI NO LO ESTA REDIRIGIRLO A OTRA PÁGINA
	session_start();
	$titulo = isset($_POST['titulo'])? $_POST['titulo']: null;
	$cuerpo = isset($_POST['cuerpo'])? $_POST['cuerpo']: null;
	$tema = isset($_POST['tema'])? $_POST['tema']: null;
	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
	echo "<p>titulo:".$titulo."</p>";
	echo "<p>cuerpo:".$cuerpo."</p>";
	echo "<p>tema:".$tema."</p>";
	echo "<p>funcion:".$funcion."</p>";
	if($funcion == "aniadirPregunta")
		aniadirPregunta($titulo,$cuerpo,$tema);
	/*else{
		return false;
	}*/

	//Comprobamos que el método empleado es POST
	function cargaPreguntas($idAsignatura, $emailAutor){
		$_SESSION['error_ningunaPregunta']=false;
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
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
		$credentialsStr = file_get_contents('credentials.json');
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
	function cargaAutor($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
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
	function cargaModificador($idPregunta){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
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
		$credentialsStr = file_get_contents('credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','".$titulo."','".$cuerpo."','".$tema."','".$_SESSION['id']."','','".$_SESSION['id']."','','".$_SESSION['idAsignatura']."')";
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);
			$funciona=true;
		}
		else{
			$_SESSION['error_BBDD']=true;
			header('Location: loginFormulario.php');
			$funciona=false;
		}
		mysqli_close($db);
		return $funciona;
		//INSERT INTO `preguntas`(`id`, `titulo`, `cuerpo`, `tema`, `creador`, `fecha_creacion`, `ult_modificador`, `fecha_modificado`, `asignatura`) VALUES ('','Titulo pregunta insertada','Cuerpo pregunta insertada','3','3','','3','','2')
	}


//SELECT prof_asig_coord.coordinador AS coordinador, profesores.nombre AS nombre_profesor, asignaturas.nombre AS nombre_asignatura FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE id_profesor='4'
?>
