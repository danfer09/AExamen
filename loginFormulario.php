<?php
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	/*Volcamos a variables los session de control de errores que se inicializan en loginProcesamiento.php */
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_autenticar = isset($_SESSION['error_autenticar'])? $_SESSION['error_autenticar']: false;
	/*Comprobamos las variables donde hemos volcado los session y realizamos las acciones que correspondan*/
	if($error_campoVacio){
		echo "Error campos vacíos";
		$_SESSION['error_campoVacio']=false;
	}
	elseif($error_BBDD) {
		echo "Error al conectar con la base de datos";
		$_SESSION['error_BBDD']=false;
	}
	elseif($error_autenticar){
		echo"Error al autenticar";
		$_SESSION['error_autenticar']=false;
	}

	/*Volcamos a una variable el valor de la session logeado. Si vale true es que ya estamos logeados, en caso contrario es que no estamos logeados*/
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*Si estamos logeados redirigimos a paginaPrincipaProf.php*/
	if($logeado){
		header('Location: paginaPrincipalProf.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<h1>Login</h1>

		<form action="loginProcesamiento.php" id="formulario_login" method="post">
		  Email:<br>
		  <input type="text" name="email" id="email">
		  <br>
		  Contraseña:<br>
		  <input type="password" name="clave" id="clave">
		  <br><br>
		  <input type="submit" value="Acceder" id="logear" name="logear">
		</form> 
		<br>
		<p><a href="registrarseFormulario.php">Registrarse</a></p>
		<p><a href="olvidoPassword.php">Olvidé mi contraseña</a></p>
	</div>

	<!--Librerias externas-->
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/w3.js"></script>

	<!--Javascripts propios-->
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>
