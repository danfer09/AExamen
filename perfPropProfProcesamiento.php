<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php*/
	if (!$logeado) {
		header('Location: index.php');
	}

	//Ponemos los errores que controlamos en este php a false antes de empezar
	$_SESSION['error_ejecuccionConsulta']=false;
	$_SESSION['error_noFilasConCondicion']=false;
	$_SESSION['error_BBDD']=false;
	$_SESSION['error_campoVacio']=false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos el valor que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$nuevoNombre = isset($_POST['nombre'])? $_POST['nombre']: null;
		$nuevoApellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
		$nuevoClave = isset($_POST['clave'])? $_POST['clave']: null;
		//Si nuevoNombre no esta a null es que el usuario quiere cambiar el nombre, por lo tanto procedemos a cambiarlo
		if($nuevoNombre!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "UPDATE profesores SET nombre= '".$nuevoNombre."' WHERE id=".$_SESSION['id'];
				$consulta=mysqli_query($db,$sql);
				//Comprobamos los distintos errores que se pueden producir y ponemos a true los session que corresponden
				if(mysqli_affected_rows($db) == -1){
					$_SESSION['error_ejecuccionConsulta']=true;
				}
				elseif(!(mysqli_affected_rows($db))){
					$_SESSION['error_noFilasConCondicion']=true;
				}
				else{
					$_SESSION['nombre']=$nuevoNombre;
				}
				header('Location: perfilPropioProf.php');				
			}
			else{
				$_SESSION['error_BBDD']=true;
				header('Location: perfilPropioProf.php');
			}
		}
		//Si nuevoApellidos no esta a null es que el usuario quiere cambiar el apellido, por lo tanto procedemos a cambiarlo
		elseif($nuevoApellidos!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "UPDATE profesores SET apellidos= '".$nuevoApellidos."' WHERE id=".$_SESSION['id'];
				$consulta=mysqli_query($db,$sql);
				//Comprobamos los distintos errores que se pueden producir y ponemos a true los session que corresponden
				if(mysqli_affected_rows($db) == -1){
					$_SESSION['error_ejecuccionConsulta']=true;
				}
				elseif(!(mysqli_affected_rows($db))){
					$_SESSION['error_noFilasConCondicion']=true;
				}
				else{
					$_SESSION['apellidos']=$nuevoApellidos;
				}
				header('Location: perfilPropioProf.php');				
			}
			else{
				$_SESSION['error_BBDD']=true;
				header('Location: perfilPropioProf.php');
			}
		}
		//Si nuevoClave no esta a null es que el usuario quiere cambiar el clave, por lo tanto procedemos a cambiarlo
		elseif($nuevoClave!=null){
			//Haseamos la nueva clave, que nos llega en texto plano
			$hashed_clave = password_hash($nuevoClave, PASSWORD_BCRYPT);
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "UPDATE profesores SET clave= '".$hashed_clave."' WHERE id=".$_SESSION['id'];
				$consulta=mysqli_query($db,$sql);
				//Comprobamos los distintos errores que se pueden producir y ponemos a true los session que corresponden
				if(mysqli_affected_rows($db) == -1){
					$_SESSION['error_ejecuccionConsulta']=true;
				}
				elseif(!(mysqli_affected_rows($db))){
					$_SESSION['error_noFilasConCondicion']=true;
				}
				header('Location: perfilPropioProf.php');				
			}
			else{
				$_SESSION['error_BBDD']=true;
				header('Location: perfilPropioProf.php');
			}
		}

		else{
			$_SESSION['error_campoVacio']=true;
			header('Location: perfilPropioProf.php');
		}
		mysqli_close($db);	
	}
?>

</body>
</html>