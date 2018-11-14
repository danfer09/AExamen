<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>

<h1>Pagina principal del profesor</h1>
	<div class="container">
		<?php 
			session_start();
			echo "<h2> Examenes </h2>";
			include "servidor.php";

		?>
		<br>
		<div class="row" id="filtros">
			<div class="form-inline col-lg-2">
				<label for="sel1">Asignatura </label>
				<select class="form-control" id="sel1" onchange="location = this.value;">
					<?php
						$credentialsStr = file_get_contents('credentials.json');
						$credentials = json_decode($credentialsStr, true);
						$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
						
						$siglas = selectAllSiglasAsignaturas($db);
						if ($_GET['asignatura'] == "todas") {
							echo '<option value="examenes.php?asignatura=todas&autor='.$_GET['autor'].'" selected>Todas</option>';
						} else {
							echo '<option value="examenes.php?asignatura=todas&autor='.$_GET['autor'].'">Todas</option>';
						}

						if ($siglas == null){
							echo 'No hay siglas';
						} else if (!$siglas){
							echo 'Error con la BBDD, contacte con el administrador';
						} else {
							foreach ($siglas as $pos => $valor) {
								if ($_GET['asignatura'] == $valor['siglas']) {
									echo '<option value="examenes.php?asignatura='.$valor['siglas'].'&autor='.$_GET['autor'].'" selected>'.$valor['siglas'].'</option>';
								} else {
									echo '<option value="examenes.php?asignatura='.$valor['siglas'].'&autor='.$_GET['autor'].'">'.$valor['siglas'].'</option>';
								}
							}
						}
					?>
				</select>
			</div>
			<div class="form-inline col-lg-4">
				<label for="sel1">Autor </label>
				<select class="form-control" id="sel1" onchange="location = this.value;">
					<?php
						$autores = selectAllMailsProfesores($db);
						if ($_GET['autor'] == "todos") {
							echo '<option value="examenes.php?asignatura='.$_GET['asignatura'].'&autor=todos" selected>Todos</option>';
						} else {
							echo '<option value="examenes.php?asignatura='.$_GET['asignatura'].'&autor=todos">Todos</option>';
						}

						if ($autores == null){
							echo 'No hay nombres de profesores';
						} else if (!$autores){
							echo 'Error con la BBDD, contacte con el administrador';
						} else {
							foreach ($autores as $pos => $valor) {
								if ($_GET['autor'] == $valor['email']) {
									echo '<option value="examenes.php?asignatura='.$_GET['asignatura'].'&autor='.$valor['email'].'" selected>'.$valor['email'].'</option>';
								} else {
									echo '<option value="examenes.php?asignatura='.$_GET['asignatura'].'&autor='.$valor['email'].'">'.$valor['email'].'</option>';
								}
							}
						}
					?>
				</select>
			</div>
			<input oninput="w3.filterHTML('#tabla_examenes', '.item', this.value)" class="w3-input col-lg-6" placeholder="Buscar...">
		</div>
		<br>
		<table id="tabla_examenes" class="table table-hover">
		    <thead>
		      <tr>
		      	<th>	</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(2)')" style="cursor:pointer;">Título</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(3)')" style="cursor:pointer;">Creado por</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(4)')" style="cursor:pointer;">Fecha creación</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(5)')" style="cursor:pointer;">Últ. modificación</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(8)')" style="cursor:pointer;">Modificado por</th>
		      	<th>	</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php
			if ($_GET['asignatura'] == "todas" && $_GET['autor'] == "todos") {
				$examenes=selectAllExamenesCompleto($db);
			} else {
				$examenes = selectAllExamenesFiltrado($db, $_GET['asignatura'], $_GET['autor']);
			}

			if ($examenes == null){
				echo 'No hay exámenes';
			} else if (!$examenes){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			else{
				foreach ($examenes as $pos => $valor) {
					echo '<tr class="item">';
					echo '<td> <i class="fas fa-file-invoice fa-fw fa-lg"></i> </td>';
					echo '<td>'.$valor['titulo'].'</td>';
					echo '<td>'.$valor['creador'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_creado'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_modificado'].'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_creado']).'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_modificado']).'</td>';
					echo '<td>'.$valor['ultimo_modificador'].'</td>';
					echo '<td> <i class="fas fa-pencil-alt fa-fw fa-lg"></i>  <a id="'.$valor['id'].'" href="" onclick="console.log('.$valor['id'].'); return true;"><i style="color: red;" class="fas fa-trash-alt fa-fw fa-lg"></i></a> </td>';
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
	<script type="text/javascript" src="formularioNombre.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

