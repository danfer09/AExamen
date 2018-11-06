<?php
	session_start();
	$nombreTemp = isset($_SESSION['nombreTemp'])? $_SESSION['nombreTemp']: null;
	$apellidoTemp = isset($_SESSION['apellidoTemp'])? $_SESSION['apellidoTemp']: null;
	$emailTemp = isset($_SESSION['emailTemp'])? $_SESSION['emailTemp']: null;

	$error_password_diferente = isset($_SESSION['password_diferente'])? $_SESSION['password_diferente']: false;

	if (!isset($_GET['authenticate']) || !$_GET['authenticate']) {
		header('Location: loginFormulario.php');
	} else {
		$_SESSION['confirmado'] = true;
	}

	if ($_SESSION['logeado']) {
		header('Location: paginaPrincipalProf.php');
	}

	if (!$_SESSION['emailTemp']) {
		header('Location: registrarseFormulario.php')
	}

	if($error_password_diferente){
		echo "Error: las contraseñas deben ser iguales";
		$error_password_diferente=false;
	}
?>
<!DOCTYPE html>
<html>
<body>

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

</body>
</html>