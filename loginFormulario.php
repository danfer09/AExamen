<?php
	session_start();
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_autenticar = isset($_SESSION['error_autenticar'])? $_SESSION['error_autenticar']: false;
	if($error_campoVacio){
		echo "Error campos vacíos";
		$error_campoVacio=false;
	}
	elseif($error_BBDD) {
		echo "Error al conectar con la base de datos";
		$error_BBDD=false;
	}
	elseif($error_autenticar){
		echo"Error al autenticar";
		$error_autenticar=false;
	}

?>
<!DOCTYPE html>
<html>
<body>

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

</body>
</html>
