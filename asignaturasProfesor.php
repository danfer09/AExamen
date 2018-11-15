<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
<div class="header" id="header"></div>
<div class="container">
<h1>Pagina principal del profesor</h1>
	
		<?php 
			session_start();
			echo "<h2> Asignaturas de ". $_SESSION['nombre']. "</h2>";
			include 'asignaturasProfesorProcesamiento.php';
		?>

		<br>
		<p>
			<input oninput="w3.filterHTML('#tabla_asignaturas', '.item', this.value)" class="w3-input" placeholder="Buscar...">
		</p>
		<table class="table table-hover" id="tabla_asignaturas">
		    <thead>
		      <tr>
		      	<!-- td:nth-child(2) se refiere a la segunda columna de cada fila de la tabla -->
		        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(1)')" style="cursor:pointer;">Siglas</th>
		        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(2)')" style="cursor:pointer;">Nombre asignatura</th>
		        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(3)')" style="cursor:pointer;">Coordinador</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php

			$asignaturas=cargaAsignaturas($_SESSION['id']);

			$error_ningunaAsignatura = isset($_SESSION['error_ningunaAsignatura'])? $_SESSION['error_ningunaAsignatura']: false;
			$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;

			if($error_ningunaAsignatura){
				echo 'No tienes ninguna asignatura';
			}

			else if($error_BBDD){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			else{
				foreach ($asignaturas as $pos => $valor) {
					echo '<tr class="item" style="cursor:pointer;">';
					echo '<td><a href="asignatura.php?id='.$valor['id_asignatura'].'&nombre='.$valor['nombre_asignatura'].'&siglas='.$valor['siglas_asignatura'].'"></a>'.$valor['siglas_asignatura'].'</td>';
					echo '<td>'.$valor['nombre_asignatura'].'</td>';
					$coord=($valor['coordinador'])?'Si':'No';
					echo '<td>'.$coord.'</td>';
					echo '</tr>';
				}
			}
		?>
			</tbody>
		</table>
		
	</div>

	<script src="jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="asignaturasProfesor.js"></script>
	<script type="text/javascript" src="cabeceraConLogin.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

