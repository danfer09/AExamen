<?php


	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	function getDefaultParameters ($tituloExamen) {
		$credentialsStr = file_get_contents('credentials.json');
		$credentials = json_decode($credentialsStr, true);
		$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

		$sql = "SELECT asignaturas.espaciado_defecto, asignaturas.texto_inicial, asignaturas.siglas, asignaturas.nombre FROM asignaturas INNER JOIN examenes ON asignaturas.id=examenes.id_asig WHERE examenes.titulo='".$tituloExamen."'";
		$consulta=mysqli_query($db,$sql);
		if($consulta->num_rows > 0){
			return mysqli_fetch_assoc($consulta);
		}

	}

?>