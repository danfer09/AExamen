<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

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
<head>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">

</head>
<body>
	<div class="container">
		<h1>Registrarse</h1>

		<form action="registrarseProcesamiento.php" id="formulario_registrarse" method="post">
			<span>Nombre:*</span><br>
			<input type="text" name="nombre" id="nombre" required>
			<br>
			<span>Apellidos:</span><br>
			<input type="text" name="apellidos" id="apellidos">
			<br>
			<span>Email:*</span><br>
			<input type="email" name="email" id="email" required>
			<br>
			<span>Contraseña:*</span><br>
			<input type="password" name="clave" id="clave" required>
			<br>
			<span>Repetir la contraseña:*</span><br>
			<input type="password" name="repetirClave" id="repetirClave" required>
			<br>
			<span>Observaciones (opcional):</span><br>
			<textarea cols="50" rows="4" name="texto" id="texto"></textarea>
			<br><br>

			<span>*Usted recibirá un correo de confirmación en cuanto el administrador acepte su solicitud</span>
			<br><br>

			<input type="submit" value="Registrarse" id="logear" name="logear">
		</form> 
		<p><a href="loginFormulario.php">Volver a Iniciar sesión</a></p>
	</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
    <script type="text/javascript" src="js/registrarseFormulario.js"></script>

</body>
</html>
