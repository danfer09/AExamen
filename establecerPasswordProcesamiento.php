<?php
	include 'servidor.php';

	$_SESSION['password_diferente'] = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$pass1 = isset($_POST['pass1'])? $_POST['pass1']: null;
		$pass2 = isset($_POST['pass2'])? $_POST['pass2']: null;

		if ( ($pass1 != null && $pass2 != null) && $pass1 == $pass2) {

			$credentialsStr = file_get_contents('credentials.json');
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
			session_write_close();

			header('Location: paginaPrincipalProf.php');
			exit();
		} else {
			$_SESSION['password_diferente'] = true;
			session_write_close();
			header('Location: establecerPassword.php');
			exit();
		}
	}

?>