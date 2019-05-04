<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	//Comprobamos los distitos session que controlan los diversos errores, si existen los volcamos en unas variables para que sea mas manejable
	$nombreTemp = isset($_SESSION['nombreTemp'])? $_SESSION['nombreTemp']: null;
	$apellidoTemp = isset($_SESSION['apellidoTemp'])? $_SESSION['apellidoTemp']: null;
	$emailTemp = isset($_SESSION['emailTemp'])? $_SESSION['emailTemp']: null;

	$error_password_diferente = isset($_SESSION['password_diferente'])? $_SESSION['password_diferente']: false;
	$error_campo_vacio = isset($_SESSION['campo_vacio'])? $_SESSION['campo_vacio']: false;

	$_SESSION['confirmado'] = true;
	session_write_close();

	if (isset($_SESSION['logeado']) && $_SESSION['logeado']) {
		header('Location: paginaPrincipalProf.php');
		exit();
	}

	if (!isset($_SESSION['emailTemp']) || $_SESSION['emailTemp'] == null) {
		header('Location: registrarseFormulario.php');
		exit();
	} else if (!isset($_GET['authenticate']) || ($_GET['authenticate'] != $_SESSION['emailTempClave'])) {
		header('Location: registrarseFormulario.php');
		exit();
	}

	if ($error_campo_vacio) {
		echo "Error: rellene todos los campos";
		$error_campo_vacio = false;
	}

	if($error_password_diferente){
		echo "Error: las contraseñas deben ser iguales";
		$error_password_diferente=false;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Reestablecer Contraseña</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="container">
		<h1>Reestablecer contraseña</h1>

		<span>Reestablezca la contraseña para el email <?php echo $_SESSION['emailTemp']; ?></span>
		<form action="reestablecerPasswordProcesamiento.php" id="formulario_establecer_password" method="post">
		  Nueva contraseña:<br>
		  <input type="password" class="form-control" name="pass1" id="pass1" required>
		  <br>
		  Escriba de nuevo la contraseña:<br>
		  <input type="password" class="form-control" name="pass2" id="pass2" required>
		  <br><br>
		  <input type="submit" class="btn btn-primary" value="Confirmar" id="reestablecerPasswordSubmit" name="reestablecerPasswordSubmit">
		</form> 
	</div>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/reestablecerPassword.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>