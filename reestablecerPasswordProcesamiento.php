<?php
	include 'servidor.php';

	$_SESSION['password_diferente'] = false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$pass1 = isset($_POST['pass1'])? $_POST['pass1']: null;
		$pass2 = isset($_POST['pass2'])? $_POST['pass2']: null;
		$passOld = isset($_POST['passOld'])? $_POST['passOld']: null;

		if ( ($pass1 != null && $pass2 != null) && $pass1 == $pass2) {
			if ($passOld != null) {
				$credentialsStr = file_get_contents('credentials.json');
				$credentials = json_decode($credentialsStr, true);
				$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

				//$hashed_clave = password_hash($passOld, PASSWORD_BCRYPT);
				$anterior = anteriorPasswordCorrecta($db, $_SESSION['emailTemp'], $passOld);
				
				if ($anterior) {					
					$hashed_clave = password_hash($pass1, PASSWORD_BCRYPT);
					if (updateClaveProfesor($db, $_SESSION["emailTemp"], $hashed_clave)){
						$_SESSION['emailTemp'] = null;
						$_SESSION['error_autenticar'] = false;
						header('Location: loginFormulario.php');
						exit();
					}
				} else if (!$anterior) {
					$_SESSION['password_anterior'] = true;
					session_write_close();
					header('Location: reestablecerPassword.php');
					exit();
				}				
			} else {
				$_SESSION['campo_vacio'] = true;
				session_write_close();
				header('Location: reestablecerPassword.php');
				exit();
			}
		} else {
			$_SESSION['password_diferente'] = true;
			session_write_close();
			header('Location: reestablecerPassword.php');
			exit();
		}
	}

?>