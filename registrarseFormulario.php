<?php
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_usuario_existente = isset($_SESSION['error_usuario_existente'])? $_SESSION['error_usuario_existente']: false;
	if($error_campoVacio){
		echo "Error campos vacíos";
		$error_campoVacio=false;
	}
	elseif($error_BBDD) {
		echo "Error al conectar con la base de datos";
		$error_BBDD=false;
	}
	elseif($error_usuario_existente){
		echo"Error, usuario ya existente";
		$error_usuario_existente=false;
	}
?>
<!DOCTYPE html>
<html>
<body>

	<h1>Registrarse</h1>

	<form action="registrarseProcesamiento.php" id="formulario_registrarse" method="post">
		Nombre:<br>
		<input type="text" name="nombre" id="nombre">
		<br>
		Apellidos:<br>
		<input type="text" name="apellidos" id="apellidos">
		<br>
		Email:<br>
		<input type="text" name="email" id="email">
		<br><br>

		<span>*Usted recibirá un correo de validación para establecer su contraseña</span>
		<br><br>

		<input type="submit" value="Registrarse" id="logear" name="logear">
	</form> 
	<p><a href="loginFormulario.php">Iniciar sesión</a></p>
</body>
</html>
