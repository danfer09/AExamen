<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
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
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<meta charset="UTF-8">
</head>
<body>
	<h1>Reestablecer contraseña</h1>

	<span>Reestablezca la contraseña para el email <?php echo $_SESSION['emailTemp']; ?></span>
	<form action="reestablecerPasswordProcesamiento.php" id="formulario_establecer_password" method="post">
	  Nueva contraseña:<br>
	  <input type="password" name="pass1" id="pass1">
	  <br>
	  Escriba de nuevo la contraseña:<br>
	  <input type="password" name="pass2" id="pass2">
	  <br><br>
	  <input type="submit" value="Confirmar" id="reestablecerPasswordSubmit" name="reestablecerPasswordSubmit">
	</form> 

	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>