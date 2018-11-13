<?php 
//HACER COMPROBACION DE QUE EL USUARIO ESTA LOGEADO, SI NO LO ESTA REDIRIGIRLO A OTRA PÁGINA
	
	//Comprobamos que el método empleado es POST
	function cargaAsignaturas($idAsignatura){
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
			$sql = "SELECT asignaturas.siglas AS siglasAsignatura,  profesores.nombre AS autor, preguntas.titulo AS titulo, preguntas.cuerpo AS cuerpo, preguntas.tema AS tema, preguntas.fecha_creacion AS fecha_creacion, preguntas.fecha_modificado AS fecha_modificado
				FROM ((preguntas INNER JOIN asignaturas ON asignaturas.id =".$idAsignatura.") INNER JOIN profesores ON preguntas.creador=profesores.id)
				WHERE preguntas.asignatura=".$idAsignatura;
			$consulta=mysqli_query($db,$sql);
			$fila=mysqli_fetch_assoc($consulta);

			while($fila){
				$preguntas[$i]=$fila;
				$i++;
				$fila=mysqli_fetch_assoc($consulta);
			}
			if($i==0){
				$_SESSION['error_ningunaAsignatura']=true;
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
//SELECT prof_asig_coord.coordinador AS coordinador, profesores.nombre AS nombre_profesor, asignaturas.nombre AS nombre_asignatura FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE id_profesor='4'
?>
