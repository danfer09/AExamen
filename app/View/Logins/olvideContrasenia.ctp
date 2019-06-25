<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	//Comprobamos los distitos session que controlan los diversos errores, si existen los volcamos en unas variables para que sea mas manejable
	$error_usuario_no_existente = isset($_SESSION['error_usuario_no_existente'])? $_SESSION['error_usuario_no_existente']: false;
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Olvido Contraseña</title>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="container">
		<h1>Restablecer contraseña</h1>
		<?php
			if($error_usuario_no_existente){
				echo '<div class="alert alert-warning">
						<a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
				    	Error, el correo electrónico no coincide con ninguno ya registrado
					  </div>';
				$_SESSION['error_usuario_no_existente']=false;
			}

		?>

		<p>Introduce tu correo electrónico para poder restablecer la contraseña mediante el correo que te enviaremos: </p>

		<form action="/logins/olvidecontrasenia" id="formulario_olvido" method="post">
			Email <b>*</b>:<br>
			<input type="text" placeholder="email@example.com" class="form-control" name="email" id="email" required>
			<br>
			<input type="submit" class="btn btn-primary" value="Enviar" id="olvido" name="olvido">
		</form>
		<br><br>
		<p><a href="/logins/index" class="linkLogin">Iniciar sesión</a></p>
		<p><a href="/registros/index" class="linkLogin">Registrarse</a></p>

	</div>
</body>
</html>
