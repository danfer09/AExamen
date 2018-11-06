<?php
	include 'servidor.php';
	session_start();

	$_SESSION['password_diferente'] = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$pass1 = isset($_POST['pass1'])? $_POST['pass1']: null;
		$pass2 = isset($_POST['pass2'])? $_POST['pass2']: null;

		if ( ($pass1 != null && $pass2 != null) && $pass1 === $pass2) {
			$_SESSION['logeado']=true;
			$_SESSION["email"] = $_SESSION['emailTemp'];
			$_SESSION["nombre"]= $_SESSION['nombreTemp'];
			$_SESSION['apellidos']= $_SESSION['apellidosTemp'];

			$_SESSION['emailTemp'] = null;
			$_SESSION['nombreTemp'] = null;
			$_SESSION['apellidosTemp'] = null;

			$credentialsStr = file_get_contents('credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			insertProfesor($db, $_SESSION["nombre"], $_SESSION["apellidos"], $_SESSION["email"], $pass1);
			header('Location: paginaPrincipalProf.php');
		} else {
			$_SESSION['password_diferente'] = true;
			header('Location: establecerPassword.php');
		}
	}

?>