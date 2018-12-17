<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Pagina principal del profesor</h1>
		<?php 
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			if (!isset($_SESSION['logeado']) && !$_SESSION['logeado']) {
				header('Location: index.php');
			}
			else{
				echo "<h2> Bienvenido ". $_SESSION['nombre']. "</h2>";
			}

		?>
		<a href="perfilPropioProf.php"> Editar perfil </a>
		<a href="asignaturasProfesor.php"> Ver mis asignaturas </a>
		<!--<a href="examenes.php?asignatura=todas&autor=todos"> Exámenes </a>-->
		<br>
		<a href="cerrarSesion.php">Salir</a>
	</div>


	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/w3.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>