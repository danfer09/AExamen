<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	//Comprobamos los distitos session que controlan los diversos errores, si existen los volcamos en unas variables para que sea más manejable
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_usuario_existente = isset($_SESSION['error_usuario_existente'])? $_SESSION['error_usuario_existente']: false;

?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Registro</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="container">
		<h1>Registrarse</h1>
    <?php
    if($error_campoVacio){
      echo '<div class="alert alert-danger alert_login" role="alert">
          <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
            Error, ha dejado campos vacíos
          </div>';
      $_SESSION['error_campoVacio']=false;
    }
    elseif($error_BBDD) {
      echo '<div class="alert alert-danger alert_login" role="alert">
          <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
            Error al conectar con la base de datos, contacte con el administrador
          </div>';
      $_SESSION['error_BBDD']=false;
    }
    elseif($error_usuario_existente){
      echo '<div class="alert alert-danger alert_login" role="alert">
          <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
            Usuario existente
          </div>';
      $_SESSION['error_autenticar']=false;
    }
    ?>

		<form action="index" id="formulario_registrarse" method="post">
			<span>Nombre:*</span><br>
			<input type="text" class="form-control" name="nombre" id="nombre" required>
			<br>
			<span>Apellidos:</span><br>
			<input type="text" class="form-control" name="apellidos" id="apellidos">
			<br>
			<span>Email:*</span><br>
			<input type="email" class="form-control" name="email" id="email" required>
			<br>
			<span>Contraseña:*</span><br>
			<input type="password" class="form-control" name="clave" id="clave" required>
			<br>
			<span>Repetir la contraseña:*</span><br>
			<input type="password" class="form-control" name="repetirClave" id="repetirClave" required>
			<br>
			<span>Observaciones (opcional):</span><br>
			<textarea cols="50" class="form-control" rows="4" name="texto" id="texto"></textarea>
			<br><br>

			<span>*Usted recibirá un correo de confirmación en cuanto el administrador acepte su solicitud</span>
			<br><br>

			<input type="submit" class="btn btn-primary" value="Registrarse" id="logear" name="logear">
		</form>
		<br>
		<p><a href="/logins/index" class="linkLogin">Volver a Iniciar sesión</a></p>
	</div>


</body>
</html>
