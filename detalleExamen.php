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
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/asignaturasProfesor.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

