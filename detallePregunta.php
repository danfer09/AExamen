<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

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
		<?php 
			/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
			$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if (!$logeado) {
				header('Location: index.php');
			}
			include "preguntasProcesamiento.php";
			$pregunta=cargaUnicaPregunta($_GET['id']);
			echo "<h1>Preguntas de ". $pregunta['titulo']. "</h1>";
			
		?>

		<br>
		
		<?php
			echo "<p>Titulo: ".$pregunta['titulo']."</p>";
			echo "<p>Cuerpo: ".$pregunta['cuerpo']."</p>";
			echo "<p>Tema: ".$pregunta['tema']."</p>";
			echo "<p>Autor: ".cargaAutorPregunta($pregunta['id'])."</p>";
			echo "<p>Fecha de creacion: ".$pregunta['fecha_creacion']."</p>";
			echo "<p>Ultimo usuario en modificarla: ".cargaModificadorPregunta($pregunta['id'])."</p>";
			echo "<p>Fecha de ultima modificación: ".$pregunta['fecha_modificado']."</p>";
		?>
		
	</div>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/w3.js"></script>	
	<script type="text/javascript" src="js/asignaturasProfesor.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>

