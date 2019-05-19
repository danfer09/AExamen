<?php
	include 'funcionesServidor.php';
	error_reporting(0); 
	session_start();

	date_default_timezone_set("Europe/Madrid");

	$_SESSION['error_campoVacio']=false;
	$_SESSION['error_BBDD']=false;
	$_SESSION['error_usuario_existente']=false;
	//Comprobamos que el método empleado es POST
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$email = isset($_POST['email'])? $_POST['email']: null;
		$nombre = isset($_POST['nombre'])? $_POST['nombre']: null;
		$apellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
		$texto = isset($_POST['texto'])? $_POST['texto']: null;
		$clave = isset($_POST['clave'])? $_POST['clave']: null;
		$repetirClave = isset($_POST['repetirClave'])? $_POST['repetirClave']: null;
		//Comprobamos que ninguna de las variables este a null
		if($email!=null && $apellidos!=null && $nombre!=null && $clave!=null && $repetirClave!=null && ($clave == $repetirClave) ){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect($credentials['database']['host'], $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "SELECT * FROM profesores WHERE email='".$email."'";
				$consulta=mysqli_query($db,$sql);
				if($consulta->num_rows > 0) {
					$_SESSION['error_usuario_existente']=true;
					header('Location: registrarseFormulario.php');
					exit();
				} else {
					$_SESSION['error_envio_mail'] = false;
					if (smtpmailer($email, $credentials['webMail']['mail'], 'AExamen Web', 'Solicitud de registro AExamen', 'registroAexamen-mail.html', $credentials['webMail']['mail'], $credentials['webMail']['password'])) {
					} else {
						$_SESSION['error_envio_mail'] = true;
					}

					date_default_timezone_set('Europe/Berlin');
					$date = date('Y-m-d H:i:s', time());
					$claveHash = password_hash($clave, PASSWORD_BCRYPT);
					$sql = "INSERT INTO `peticiones_registro`(`id`, `nombre`, `apellidos`, `email`, `fecha`, `texto`, `clave`) VALUES ('','".$nombre."','".$apellidos."','".$email."','".$date."', '".$texto."', '".$claveHash."')";
					$consulta=mysqli_query($db,$sql);
					$fila=mysqli_fetch_assoc($consulta);

					header('Location: registroAexamen-pagina.html');
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