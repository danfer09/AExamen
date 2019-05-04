<?php

	session_start();

	$_SESSION['password_diferente'] = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$pass1 = isset($_POST['pass1'])? $_POST['pass1']: null;
		$pass2 = isset($_POST['pass2'])? $_POST['pass2']: null;

		if ( ($pass1 != null && $pass2 != null) && $pass1 == $pass2) {

			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			$_SESSION["email"] = $_SESSION['emailTemp'];
			$_SESSION["nombre"]= $_SESSION['nombreTemp'];
			$_SESSION["apellidos"]= $_SESSION['apellidosTemp'];
			$hashed_clave = password_hash($pass1, PASSWORD_BCRYPT);
			
			insertProfesor($db, $_SESSION["nombre"], $_SESSION["apellidos"], $_SESSION["email"], $hashed_clave);

			$_SESSION['logeado']=true;
			$_SESSION['emailTemp'] = null;
			$_SESSION['nombreTemp'] = null;
			$_SESSION['apellidosTemp'] = null;

			header('Location: paginaPrincipalProf.php');
			exit();
		} else {
			$_SESSION['password_diferente'] = true;
			session_write_close();
			header('Location: establecerPassword.php');
			exit();
		}
	}

	/*
		Inserta en la base de datos un profesor nuevo que se acaba de registrar y validar
	*/
	function insertProfesor($db, $nombre, $apellidos, $email, $clave) {
		if($db) {
			$sql = "INSERT INTO profesores (nombre, apellidos, email, id, clave) VALUES ('".$nombre."','".$apellidos."','".$email."',null,'".$clave."')";
			if (mysqli_query($db,$sql)) {
				echo "Nuevo profesor añadido";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		} else {
			printf("Error message: %s\n", $db->error);
			echo "Conexión fallida";
		}
	}

?>