<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<title>AExamen Exámenes</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
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
			//var_dump($_SESSION['error1']);
			//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
			$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if (!$logeado) {
				header('Location: index.php');
			}
			echo "<h2> Examenes </h2>";
			include "examenesProcesamiento.php";
			include 'funcionesServidor.php';
			if (isset($_GET['successCreate'])&& $_GET['successCreate'])  {
				echo '<div class="alert alert-success alert_success" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  				Examen creado con éxito
			  		  </div>';
			}
			else if (isset($_GET['successEdit'])&& $_GET['successEdit']) {
				echo '<div class="alert alert-success alert_success" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  				Examen editado con éxito
			  		  </div>';
			}


			/*echo "<br>prueba: ".$_SESSION['prueba'];
			$_SESSION['prueba'] = "inicializadoooooo";

			echo "<br>prueba1: ";
			print_r($_SESSION['prueba1']);
			//var_dump($_SESSION['prueba1']);
			$_SESSION['prueba1'] = "inicializadoo11111";

			echo "<br>prueba2: ".$_SESSION['prueba2'];
			$_SESSION['prueba2'] = "inicializadooo222";*/
		?>
		<br>
		<div class="row" id="filtros">
			<div class="form-inline col-lg-3">
				<label for="selAsignatura">Asignatura: </label>
				<select class="form-control" id="selAsignatura" onchange="location = this.value;">
					<?php
						$credentialsStr = file_get_contents('json/credentials.json');
						$credentials = json_decode($credentialsStr, true);
						$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);
						if ($_SESSION['administrador']) {
							$siglas = selectAllSiglasAsignaturas($db);
						} else {
							$siglas = selectAllSiglasAsignaturasProfesor($db, $_SESSION['id']);
						}
						
						if ($_GET['asignatura'] == "todas") {
							echo '<option value="examenes.php?asignatura=todas&autor='.$_GET['autor'].'$" selected>Todas</option>';
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
				<label for="selAutor">Autor: </label>
				<select class="form-control" id="selAutor" onchange="location = this.value;">
					<?php
						$credentialsStr = file_get_contents('json/credentials.json');
						$credentials = json_decode($credentialsStr, true);
						$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

						$autores = selectAllMailsProfesoresSiglas($_GET['asignatura']);
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
									echo '<option value="examenes.php?asignatura='.$_GET['asignatura'].'&autor='.$valor['email'].'" selected>'.$valor['email'].' ('.$valor['nombre'].')</option>';
								} else {
									echo '<option value="examenes.php?asignatura='.$_GET['asignatura'].'&autor='.$valor['email'].'">'.$valor['email'].' ('.$valor['nombre'].')</option>';
								}
							}
						}
					?>
				</select>
			</div>
			<input oninput="w3.filterHTML('#tabla_examenes', '.item', this.value)" class="w3-input col-lg-5" placeholder="Buscar...">
		</div>
		<br>
		<?php
			if (!$_SESSION['administrador']) {
		?>
		<div class="row" id="generar">
			<div class="form-inline col-lg-4">
				<label for="selGenera">Crear examen de asignatura: </label>

					<?php

						echo '<select class="form-control" id="selGenera" onchange="cambiarLinkGenerarExamen(this.value);">';

						$hidden = false;
						if ($_GET['asignatura'] == "todas") {
							echo '<option id="opcionTodas" value="-" selected>-</option>';
							$idAsig = null;
							$hidden = true;
						} else {
							echo '<option id="opcionTodas" value="-">-</option>';
						}

						if ($siglas == null){
							echo 'No hay siglas';
						} else if (!$siglas){
							echo 'Error con la BBDD, contacte con el administrador';
						} else {
							foreach ($siglas as $pos => $valor) {
								if ($_GET['asignatura'] == $valor['siglas']) {
									echo '<option id="opcion'.$valor["siglas"].'" value="'.$valor["siglas"].','.$valor["id"].'" selected>'.$valor['siglas'].'</option>';
									$idAsig = $valor["id"];
								} else {
									echo '<option id="opcion'.$valor["siglas"].'" value="'.$valor["siglas"].','.$valor["id"].'">'.$valor['siglas'].'</option>';
								}
							}
						}
					?>
				</select>
				<?php

					if ($hidden) {
						print('<a class="fas fa-plus-circle fa-2x masExamenes" hidden id="boton_modalAñadir" href="crearExamen.php?asignatura='.$_GET["asignatura"].'&idAsignatura='.$idAsig.'"></a>');
					} else {
						print('<a class="fas fa-plus-circle fa-2x masExamenes" id="boton_modalAñadir" href="crearExamen.php?asignatura='.$_GET["asignatura"].'&idAsignatura='.$idAsig.'"></a>');
					}

				?>
			</div>
		</div>
		<?php
			}
		?>

		<table id="tabla_examenes" class="table table-hover">
		    <thead>
		      <tr>
		      	<th>	</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(2)')" class="cabeceraTabla">Título</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(3)')" class="cabeceraTabla">Creado por</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(4)')" class="cabeceraTabla">Fecha creación</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(5)')" class="cabeceraTabla">Últ. modificación</th>
		        <th onclick="w3.sortHTML('#tabla_examenes', '.item', 'td:nth-child(8)')" class="cabeceraTabla">Modificado por</th>
		      	<th>	</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php
			$credentialsStr = file_get_contents('json/credentials.json');
			$credentials = json_decode($credentialsStr, true);
			$db = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['password'], $credentials['database']['dbname']);

			if ($_GET['asignatura'] == "todas" && $_GET['autor'] == "todos") {
				$examenes=selectAllExamenesCompleto($db);
			} else {
				$examenes = selectAllExamenesFiltrado($db, $_GET['asignatura'], $_GET['autor']);
			}

			if ($examenes == null){
				echo '<br><div class="alert alert-warning" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  		No hay exámenes
					  </div>';
			} else if (!$examenes){
				echo '<br><div class="alert alert-danger" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  		Error con la BBDD, contacte con el administrador
					  </div>';
			}
			else{
				foreach ($examenes as $pos => $valor) {
					echo '<tr class="item">';
					echo '<td> <i class="fas fa-file-invoice fa-fw fa-lg"></i> '.$valor['asignatura'].' </td>';
					echo '<td>'.$valor['titulo'].'</td>';
					echo '<td>'.$valor['creador'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_creado'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_modificado'].'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_creado']).'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_modificado']).'</td>';
					echo '<td>'.$valor['ultimo_modificador'].'</td>';
					echo '<td id="opciones">
							<a class="btn btn-primary btn-sm" id="idDetallesExam" href="detalleExamen.php?id='.$valor['id'].'" role="button">Detalles</a>';
					if (!$_SESSION['administrador']) {
						echo '<a class="btn btn-primary btn-sm" href="generarExamen.php?examen='.$valor['titulo'].'" role="button">Generar</a>';
						echo '<a id="boton_modalEditar" idExamen="'.$valor['id'].'" href="crearExamen.php?asignatura='.$valor['asignatura'].'&idAsignatura='.$valor['idAsignatura'].'&editar=1&id='.$valor['id'].'"><i class="fas fa-pencil-alt fa-fw fa-lg"></i></a>';
					}
					echo '<a id="boton_modalBorrar" idExamen="'.$valor['id'].'"><i class="fas fa-trash-alt fa-fw fa-lg"></i></a> </td>';
					echo '</tr>';
				}
			}
		?>
			</tbody>
		</table>

		<div class="modal" id="modal_borrarExamen">
			<div class="modal-dialog">
			  <div class="modal-content">

			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">¿Borrar examen?</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>

			    <!-- Modal body -->
			    <div class="modal-body" id="modal_borrarExamen_body">
					  <form action="servidor.php" class="form-container" method="post" id="form_delete">
					    <button type="submit" class="btn btn-danger btn-lg" id="boton_borrar" name="boton_borrar">Sí</button>
					    <button type="button" class="btn btn-secondary btn-lg" id="boton_noBorrar" name="boton_noBorrar" data-dismiss="modal">No</button>
					  </form>
			    </div>

			    <!-- Modal footer -->
			    <div class="modal-footer">
			      <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>-->
			    </div>

			  </div>
			</div>
		</div>

	</div>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script type="text/javascript" src="js/examenes.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>

