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
			include "examenesProcesamiento.php";
			include "preguntasProcesamiento.php";
			$examen=cargaUnicoExamenInfo($_GET['id']);
			echo "<h1>Examen: ". $examen['titulo']. "</h1>";
			
		?>

		<br>
		
		<?php
			echo "<p>Titulo Examen: ".$examen['titulo']."</p>";
			echo "<p>Autor: ".cargaAutorExamen($examen['id'])."</p>";
			echo "<p>Fecha de creacion: ".$examen['fecha_creado']."</p>";
			echo "<p>Ultimo usuario en modificarla: ".cargaModificadorExamen($examen['id'])."</p>";
			echo "<p>Fecha de ultima modificación: ".$examen['fecha_modificado']."</p>";
			echo "<p>Preguntas:</p>";

			$pregunta=cargaUnicoExamenPreguntas($_GET['id']);

		?>
		<div class="table-wrapper-scroll-y">
	    			<table class="table table-hover" id="tabla">	
						<thead>
					      <tr>
					        <th>Titulo</th>
					        <th>Cuerpo</th>
					        <th>Tema</th>
					      </tr>
					    </thead>			
					    <tbody>
					   

		<?php
			foreach ($pregunta as $pos => $valor) {
				echo "<tr>";
				echo "<td>".$valor['titulo_pregunta']."</td>";
				echo "<td>".$valor['cuerpo']."</td>";
				echo "<td>".$valor['tema']."</td>";
				/*echo "<td>Autor: ".cargaAutorPregunta($valor['id_pregunta'])."</td>";
				echo "<td>Fecha de creacion: ".$valor['fecha_creado_preguntas']."</td>";
				echo "<td>Ultimo usuario en modificarla: ".cargaModificadorPregunta($valor['id_pregunta'])."</td>";
				echo "<td>Fecha de ultima modificación: ".$valor['fecha_modificado_pregunta']."</td>";*/
				echo "<tr>";
			}
		?>
		 </tbody>
				  	

	</table>
	</div>
    </div>
		
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

