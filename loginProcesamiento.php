<?php

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	$_SESSION['error_campoVacio']=false;
	$_SESSION['error_BBDD']=false;
	$_SESSION['error_autenticar']=false;
	//Comprobamos que el método empleado es POST
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$email = isset($_POST['email'])? $_POST['email']: null;
		$clave = isset($_POST['clave'])? $_POST['clave']: null;
		//Comprobamos que ninguna de las variables este a null
		if($email!=null && $clave!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			//comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "SELECT * FROM profesores";
				$consulta=mysqli_query($db,$sql);
				$fila=mysqli_fetch_assoc($consulta);
				$encontrado=false;

				while(!$encontrado && $fila){
					if($email==$fila['email']){
						$encontrado=true;
					}
					else{
						$fila=mysqli_fetch_assoc($consulta);
					}
				}
				if(!$encontrado){
					$_SESSION['error_autenticar']=true;
					header('Location: loginFormulario.php');
				}
				else{
					//ENCRIPTAR LA CLAVE, NO DEJARLA EN TEXTO PLANO EN LA BASE DE DATOS
					if(password_verify($clave, $fila['clave'])){
					//if($clave==$fila["clave"]){
						echo "sesion iniciada correctamente";
						$_SESSION['logeado']=true;
						$_SESSION["email"] = $email;
						$_SESSION["nombre"]=$fila['nombre'];
						$_SESSION['apellidos']=$fila['apellidos'];
						$_SESSION['id']=$fila['id'];
						$_SESSION['coordinador']=$fila["coordinador"];
						header('Location: paginaPrincipalProf.php');
					}
					else{					
						$_SESSION['error_autenticar']=true;
						header('Location: loginFormulario.php');
					}
				}				
			}
			else{
				$_SESSION['error_BBDD']=true;
				header('Location: loginFormulario.php');
			}
		}
		else{
			$_SESSION['error_campoVacio']=true;
			header('Location: loginFormulario.php');
		}
		mysqli_close($db);	
	}
?>