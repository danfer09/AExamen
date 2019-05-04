<?php
	include 'funcionesServidor.php';

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$_SESSION['error_campoVacio']=false;
	$_SESSION['error_BBDD']=false;
	$_SESSION['error_usuario_no_existente']=false;
	//Comprobamos que el método empleado es POST
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$email = isset($_POST['email'])? $_POST['email']: null;

		//Comprobamos que ninguna de las variables este a null
		if($email!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "SELECT * FROM profesores WHERE email='".$email."'";
				$consulta=mysqli_query($db,$sql);
				if($consulta->num_rows <= 0) {
					$_SESSION['error_usuario_no_existente']=true;
					header('Location: olvidoPassword.php');
					exit();
				} else if ($consulta->num_rows == 1) {
					$codigo = password_hash($email, PASSWORD_BCRYPT);

					//Envío del email que permitirá al usuario reestablecer su contraseña olvidada
					if (smtpmailerRaw($email, $credentials['webMail']['mail'], 'AExamen Web', 'Reestablecer la contraseña', '<!DOCTYPE html>
<html>
<head>
	<title>Reestablecer contraseña</title>
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<meta charset="UTF-8">
</head>
<body>
	<div class="row">
		<h2 class="col-lg-12">Haga click sobre el siguiente enlace para reestablecer su contraseña:</h2>
		<span class="col-lg-2"></span>
		<a class="col-lg-4" href="localhost/reestablecerPassword.php?authenticate='.$codigo.'">REESTABLECER</a>
		<span class="col-lg-6"></span>
		<p class="col-lg-12">¡Gracias!</p>
	</div>
</body>
</html>', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
						$_SESSION['confirmado'] = false;
						$_SESSION['emailTemp'] = $email;
						$_SESSION['emailTempClave'] = $codigo;
						echo "<p>Debug: perfil temporal creado \n</p>";
					}
					if (!empty($error)) echo $error;
				}
			}
			else{
				$_SESSION['error_BBDD']=true;
				header('Location: registrarseFormulario.php');
				exit();
			}
		}
		else{
			$_SESSION['error_campoVacio']=true;
			header('Location: registrarseFormulario.php');
			exit();
		}
		mysqli_close($db);	
	}
?>