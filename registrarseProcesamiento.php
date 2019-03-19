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
		$texto = isset($_POST['texto'])? $_POST['texto']: null;
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
					date_default_timezone_set('Europe/Berlin');
					$date = date('Y-m-d H:i:s', time());
					$sql = "INSERT INTO `peticiones_registro`(`id`, `nombre`, `apellidos`, `email`, `fecha`, `texto`) VALUES ('','".$nombre."','".$apellidos."','".$email."','".$date."', '".$texto."')";
					$consulta=mysqli_query($db,$sql);
					$fila=mysqli_fetch_assoc($consulta);
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