<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<?php
	function cargaUnicoExamenPreguntas($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		$preguntas=array();
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql ="SELECT examenes.titulo AS titulo_examen, preguntas.titulo AS titulo_pregunta, exam_preg.id_examen, exam_preg.id_pregunta, exam_preg.id, examenes.creador AS creador_examen, examenes.fecha_creado AS fecha_creado_examen, examenes.fecha_modificado AS fecha_modificado_examen, examenes.ultimo_modificador AS ultimo_modificador_examen, preguntas.creador AS creador_pregunta,  preguntas.fecha_creacion AS fecha_creado_preguntas, preguntas.ult_modificador AS ultimo_modificador_pregunta, preguntas.fecha_modificado AS fecha_modificado_pregunta, preguntas.cuerpo, preguntas.tema
				FROM ((exam_preg INNER JOIN examenes ON exam_preg.id_examen =examenes.id) INNER JOIN preguntas ON preguntas.id=exam_preg.id_pregunta) WHERE exam_preg.id_examen=".$idExamen;
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
		return $preguntas;
	}

	function cargaAutorExamen($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS autor FROM examenes INNER JOIN profesores ON examenes.creador=profesores.id WHERE examenes.id=".$idExamen;
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
	function cargaModificadorExamen($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT profesores.nombre AS modificador FROM examenes INNER JOIN profesores ON examenes.ultimo_modificador=profesores.id WHERE examenes.id=".$idExamen;
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

	function cargaUnicoExamenInfo($idExamen){
		$_SESSION['error_BBDD']=false;
		//Comprobamos que ninguna de las variables este a null
		//Conectamos la base de datos
		$credentialsStr = file_get_contents('credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
		//comprobamos si se ha conectado a la base de datos
		if($db){
			$sql = "SELECT * FROM `examenes` WHERE id=".$idExamen;
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
?>
