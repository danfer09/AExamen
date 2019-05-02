<?php
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	/*Podemos todos los session de control de errores a false, para reiniciarlos y que no tengan errores de anteriores ejecuciones*/
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

			//Comprobamos si se ha conectado a la base de datos
			if($db){
				$sql = "SELECT * FROM profesores";
				$consulta=mysqli_query($db,$sql);
				$fila=mysqli_fetch_assoc($consulta);
				$encontrado=false;
				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontrado && $fila){
					if($email==$fila['email']){
						$encontrado=true;
					}
					else{
						$fila=mysqli_fetch_assoc($consulta);
					}
				}
				$sql = "SELECT * FROM administradores";
				$consulta=mysqli_query($db,$sql);
				$filaAdmin=mysqli_fetch_assoc($consulta);
				$encontradoAdmin=false;
				/*Buscamos un correo que coincida con el que nos a introducido el usuario, en caso de que no se encuentre sale con la variable $encontrado a false*/
				while(!$encontradoAdmin && $filaAdmin){
					if($email==$filaAdmin['email']){
						$encontradoAdmin=true;
					}
					else{
						$filaAdmin=mysqli_fetch_assoc($consulta);
					}
				}
				/*Si no encotramos el nombre del usario ponemos a true la variable de error al autenticar y redirigimos a loginFormulario.php donde la tratamos*/

				if(!$encontrado && !$encontradoAdmin){
					$_SESSION['error_autenticar']=true;
					header('Location: loginFormulario.php');
				}
				else{
					//Verificamos la clave con esta funcion ya que en la BBDD esta encriptada, en caso de que se verifique, declaramos e inicializamos todas las variables de session de usuario.
					$datos = ($encontrado) ? $fila : $filaAdmin;
					if(password_verify($clave, $datos['clave'])){
						echo "sesion iniciada correctamente";
						$_SESSION['logeado']=true;
						$_SESSION["email"] = $email;
						$_SESSION["nombre"]=$datos['nombre'];
						$_SESSION['apellidos']=$datos['apellidos'];
						$_SESSION['id']=$datos['id'];
						$_SESSION['administrador'] = $encontradoAdmin;

						//Registramos este login en el log
						$log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
						        " | ACTION --> Inicio de sesión ".' de '.$_SESSION['email'].PHP_EOL.
						        "-----------------------------------------------------------------".PHP_EOL;
						file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

						header('Location: paginaPrincipalProf.php');
					}
					/*En caso de que la clave no coincida con el usuairo ponemos a true la variable de error al autentiacar y redirigimos a loginFormulario.php donde la tratamos*/
					else{
						$_SESSION['error_autenticar']=true;
						header('Location: loginFormulario.php');
					}
				}
			}
			/*Error al conectarse a la BBDD*/
			else{
				$_SESSION['error_BBDD']=true;
				header('Location: loginFormulario.php');
			}
		}
		/*Error cuando el usuario deja un campo vacío*/
		else{
			$_SESSION['error_campoVacio']=true;
			header('Location: loginFormulario.php');
		}
		/*Cerrar la BBDD*/
		mysqli_close($db);
	}
	/*En caso de que no sea un metodo POST o un usuario quiera acceder a este php poniendo su ruta en el navegador, los redirigimos a loginFormulario.php*/
	else{
		header('Location: loginFormulario.php');
	}
?>
