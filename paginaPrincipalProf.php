<html>
<head>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Pagina principal del profesor</h1>
		<?php 
			/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/

			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
			$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if ($logeado) {
				echo "<h2> Bienvenido ". $_SESSION['nombre']. "</h2>";
			}
			else{
				header('Location: index.php');
			}


		?>
		<a href="perfilPropioProf.php"> Editar perfil </a>

		<?php
		if($_SESSION['administrador']){
			echo('<a href="gestionarAsigAdmin.php"> Ver todas las asignaturas </a>');
			echo '<a href="profesoresAdmin.php"> Ver profesores </a>';
		}
		else
			echo('<a href="asignaturasProfesor.php"> Ver mis asignaturas </a>');
		?>
		<br>
		<a href="cerrarSesion.php">Salir</a>
	</div>

 	<!--Librerias externas-->
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/w3.js"></script>

	<!--Javascripts propios-->
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>