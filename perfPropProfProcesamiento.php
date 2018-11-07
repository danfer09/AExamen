<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
	session_start();
	echo "<script>console.log('A solis ortus cárdine')</script>";
	//Ponemos los errores que controlamos en este php a false antes de empezar
	$_SESSION['error_ejecuccionConsulta']=false;
	$_SESSION['error_noFilasConCondicion']=false;
	$_SESSION['error_BBDD']=false;
	$_SESSION['error_campoVacio']=false;

	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos el valor que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$nuevoNombre = isset($_POST['nombre'])? $_POST['nombre']: null;
		$nuevoApellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
		//Comprobamos que la variable no este a null
		if($nuevoNombre!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "UPDATE profesores SET nombre= '".$nuevoNombre."' WHERE id=".$_SESSION['id'];
				$consulta=mysqli_query($db,$sql);

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
		elseif($nuevoApellidos!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "UPDATE profesores SET apellidos= '".$nuevoApellidos."' WHERE id=".$_SESSION['id'];
				$consulta=mysqli_query($db,$sql);

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

		else{
			$_SESSION['error_campoVacio']=true;
			header('Location: perfilPropioProf.php');
		}
		mysqli_close($db);	
	}
?>

</body>
</html>