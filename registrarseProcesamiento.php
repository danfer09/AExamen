<?php
	include 'funcionesServidor.php';
	error_reporting(0); // Disable all errors.
	session_start();

	$_SESSION['error_campoVacio']=false;
	$_SESSION['error_BBDD']=false;
	$_SESSION['error_usuario_existente']=false;
	//Comprobamos que el método empleado es POST
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$email = isset($_POST['email'])? $_POST['email']: null;
		$nombre = isset($_POST['nombre'])? $_POST['nombre']: null;
		$apellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
		//Comprobamos que ninguna de las variables este a null
		if($email!=null && $apellidos!=null && $nombre!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "SELECT * FROM profesores WHERE email='".$email."'";
				$consulta=mysqli_query($db,$sql);
				if($consulta->num_rows > 0) {
					$_SESSION['error_usuario_existente']=true;
					header('Location: registrarseFormulario.php');
					exit();
				} else {
					if (smtpmailer($email, $credentials['webMail']['mail'], 'AExamen Web', 'Confirme su email', 'mailRegistro.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
						$_SESSION['confirmado'] = false;
						$_SESSION['emailTemp'] = $email;
						$_SESSION['nombreTemp'] = $nombre;
						$_SESSION['apellidosTemp'] = $apellidos;
						echo "Debug: perfil temporal creado";
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