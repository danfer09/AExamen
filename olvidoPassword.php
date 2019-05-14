<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	//Comprobamos los distitos session que controlan los diversos errores, si existen los volcamos en unas variables para que sea mas manejable
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_usuario_no_existente = isset($_SESSION['error_usuario_no_existente'])? $_SESSION['error_usuario_no_existente']: false;
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Olvido Contraseña</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="container">
		<h1>Reestablecer contraseña</h1>

		<?php

			if($error_campoVacio){
				echo '<div class="alert alert-warning">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				    	Error, introduzca un email.
					  </div>';
				$_SESSION['error_campoVacio']=false;
			}
			elseif($error_BBDD) {
				echo '<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				    	Error al conectar con la base de datos
					  </div>';
				$_SESSION['error_BBDD']=false;
			}
			elseif($error_usuario_no_existente){
				echo '<div class="alert alert-warning">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				    	Error, el correo electrónico no coincide con ninguno ya registrado
					  </div>';
				$_SESSION['error_usuario_no_existente']=false;
			}

		?>

		<p>Introduce tu correo electrónico para poder restablecer la contraseña mediante el correo que te enviaremos: </p>

		<form action="olvidoPasswordProcesamiento.php" id="formulario_olvido" method="post">
			Email:<br>
			<input type="text" placeholder="email@example.com" class="form-control" name="email" id="email" required>
			<br>
			<input type="submit" class="btn btn-primary" value="Enviar" id="olvido" name="olvido">
		</form> 
		<br><br>
		<p><a href="loginFormulario.php">Iniciar sesión</a></p>
		<p><a href="registrarseFormulario.php">Registrarse</a></p>

	</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>


</body>
</html>
