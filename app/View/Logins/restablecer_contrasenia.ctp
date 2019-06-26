<!DOCTYPE html>
<html>
<head>
	<title>AExamen Reestablecer Contraseña</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="container">
		<h1>Reestablecer contraseña</h1>

		<span>Reestablezca la contraseña para el email <?php echo $_SESSION['emailTemp']; ?></span>
		<form action="/logins/restablecer_contrasenia" id="formulario_establecer_password" method="post">
		  Nueva contraseña:<br>
		  <input type="password" class="form-control" name="pass1" id="pass1" required>
		  <br>
		  Escriba de nuevo la contraseña:<br>
		  <input type="password" class="form-control" name="pass2" id="pass2" required>
		  <br><br>
		  <input type="submit" class="btn btn-primary" value="Confirmar" id="reestablecerPasswordSubmit" name="reestablecerPasswordSubmit">
		</form>
	</div>
  <?php
  echo $this->Html->script('reestablecerPassword');
  ?>
</body>
</html>
