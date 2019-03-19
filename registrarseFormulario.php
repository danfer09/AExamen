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
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
	<div class="container">
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
			<br>
			Texto (opcional):<br>
			<textarea cols="50" rows="4" name="texto" id="texto"></textarea>
			<br><br>

			<span>*Usted recibirá un correo de validación para establecer su contraseña en cuanto el administrador acepte su solicitud</span>
			<br><br>

			<input type="submit" value="Registrarse" id="logear" name="logear">
		</form> 
		<p><a href="loginFormulario.php">Volver a Iniciar sesión</a></p>
	</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>
