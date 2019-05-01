<?php
	
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$nombreTemp = isset($_SESSION['nombreTemp'])? $_SESSION['nombreTemp']: null;
	$apellidosTemp = isset($_SESSION['apellidosTemp'])? $_SESSION['apellidosTemp']: null;
	$emailTemp = isset($_SESSION['emailTemp'])? $_SESSION['emailTemp']: null;


	$error_password_diferente = isset($_SESSION['password_diferente'])? $_SESSION['password_diferente']: false;

	$_SESSION['confirmado'] = true;
	//session_write_close();

	if (isset($_SESSION['logeado']) && $_SESSION['logeado']) {
		header('Location: paginaPrincipalProf.php');
		exit();
	}

	if (!isset($_SESSION['emailTemp']) || $_SESSION['emailTemp'] == null) {
		header('Location: registrarseFormulario.php');
		exit();
	}

	if($error_password_diferente){
		echo "Error: las contraseñas deben ser iguales";
		$error_password_diferente=false;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Establecer Contraseña</title>
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="container">
		<h1>Establecer contraseña</h1>

		<span>Escriba su nueva contraseña, <?php echo($_SESSION['nombreTemp']); ?></span>
		<form action="establecerPasswordProcesamiento.php" id="formulario_establecer_password" method="post">
		  Contraseña:<br>
		  <input type="password" name="pass1" id="pass1">
		  <br>
		  Escriba de nuevo la contraseña:<br>
		  <input type="password" name="pass2" id="pass2">
		  <br><br>
		  <input type="submit" value="Confirmar" id="establecerPasswordSubmit" name="establecerPasswordSubmit">
		</form> 
	</div>




	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>