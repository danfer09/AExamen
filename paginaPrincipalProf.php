<html>
<body>

<h1>Pagina principal del profesor</h1>
<?php 
	session_start();
	echo "<h2> Bienvenido ". $_SESSION['nombre']. "</h2>";
?>
<a href="perfilPropioProf.php"> Editar perfil </a>
<a href=""> Ver mis asignaturas</a>
<br>
<a href="cerrarSesion.php">Salir</a>


</body>
</html>