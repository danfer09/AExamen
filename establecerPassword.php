<?php
	session_start();
	$nombreTemp = isset($_SESSION['nombreTemp'])? $_SESSION['nombreTemp']: null;
	$apellidoTemp = isset($_SESSION['apellidoTemp'])? $_SESSION['apellidoTemp']: null;
	$emailTemp = isset($_SESSION['emailTemp'])? $_SESSION['emailTemp']: null;

	$error_password_diferente = isset($_SESSION['password_diferente'])? $_SESSION['password_diferente']: false;

	$_SESSION['confirmado'] = true;
	session_write_close();

	if (isset($_SESSION['logeado']) && $_SESSION['logeado']) {
		echo "if logeado";
		header('Location: paginaPrincipalProf.php');
		exit();
	}

	if (!isset($_SESSION['emailTemp']) || $_SESSION['emailTemp'] == null) {
		var_dump($_SESSION);
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