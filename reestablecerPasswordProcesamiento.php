<?php

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$_SESSION['password_diferente'] = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$pass1 = isset($_POST['pass1'])? $_POST['pass1']: null;
		$pass2 = isset($_POST['pass2'])? $_POST['pass2']: null;
		$passOld = isset($_POST['passOld'])? $_POST['passOld']: null;

		if ( $pass1 != null && $pass2 != null && $pass1 == $pass2) {
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
						
			$hashed_clave = password_hash($pass1, PASSWORD_BCRYPT);
			if (updateClaveProfesor($db, $_SESSION["emailTemp"], $hashed_clave)){
				$_SESSION['emailTemp'] = null;
				$_SESSION['error_autenticar'] = false;
				header('Location: loginFormulario.php');
				exit();
			}
		} else {
			$_SESSION['password_diferente'] = true;
			header('Location: reestablecerPassword.php');
			exit();
		}
	}

	/*Función que actualiza la clave de un profesor en base de datos
	*
	* Función que dado objeto de bbdd, un email de profesor y la contraseña hasheada 
	* actualiza en base de datos la contraseña de este profesor
	*
	* @param Object $db objeto para acceder a base de datos
	* @param string $email correo electrónico del profesor
	* @param string $hash contraseña hasheada del profesor
	* 
	* @return string (AJAX) mensaje de éxito o fallo en la operación de actualización 
	*/
	function updateClaveProfesor($db, $email, $hash) {
		if($db) {
			$sql = "UPDATE profesores SET clave='".$hash."' WHERE email='".$email."'";
			if (mysqli_query($db,$sql)) {
				echo "Nueva contraseña establecida";
				return true;
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($db);
				return false;
			}
		} else {
			printf("Error message: %s\n", $db->error);
			echo "Conexión fallida";
		}
	}

?>