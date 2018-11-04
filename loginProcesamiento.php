<?php
	include 'servidor.php';

	session_start();
	//Comprobamos que el método empleado es POST
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
		$email = isset($_POST['email'])? $_POST['email']: null;
		$clave = isset($_POST['clave'])? $_POST['clave']: null;
		//Comprobamos que ninguna de las variables este a null
		if($email!=null && $clave!=null){
			//Conectamos la base de datos
			$credentialsStr = file_get_contents('credentials.json');
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
					echo "El correo no es correcto o no coincide con su contraseña";
				}
				else{
					//ENCRIPTAR LA CLAVE, NO DEJARLA EN TEXTO PLANO EN LA BASE DE DATOS
					if($clave==$fila["clave"]){
						echo "sesion iniciada correctamente";
						$_SESSION["email"] = $email;
						$_SESSION["nombre"]=$fila['nombre'];
						$_SESSION['apellidos']=$fila['apellidos'];
						$_SESSION['id']=$fila['id'];
						$_SESSION['coordinador']=$fila["coordinador"];
					}
					else{
						echo "El correo no es correcto o no coincide con su contraseña";
					}
				}				
			}
			else{
				echo "Error al conectar con la base de datos";
			}
		}
		else{
			echo "Hay campos que se han dejado vacíos";
		}
		mysqli_close($db);	
	}
?>