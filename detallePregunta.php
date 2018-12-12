<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

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
		<?php 
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
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

