<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<script type="text/javascript" src="formularioNombre.js"></script>
</head>
<body>

<h1>Mi perfil</h1>
<?php 
	session_start();
	echo "<h2> Bienvenido ". $_SESSION['nombre'] . "</h2>";
	echo "<p>Nombre: " . $_SESSION['nombre'] . '<button type="button" class="btn" id="btn_cambiarNombre">Cambiar</button> </p>';
	echo "<p>Apellidos: " . $_SESSION['apellidos'] . "<a href='formularioApellido.php'>cambiar</a> </p>";
	echo "<p>correo: " . $_SESSION['email'] . "<div>cambiar</div> </p>";
	echo "<a href='formularioclave.php'>Cambiar contraseña</a>"
?>
<button type="button" class="btn cancel" id="btn_a">Aaaaaaaaaaaaaaaaaaa</button>
<div class="form-popup" id="formularioNombre">
  <form action="perfPropProfProcesamiento.php" class="form-container">
    <h1>Login</h1>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Introduzca el nombre" name="nombre" id="nombre">

    <button type="submit" class="btn" id="boton_cambiar" name="boton_cambiar">Cambiar</button>
    <button type="button" class="btn cancel">Cerrar</button>
  </form>
</div>


</body>
</html>