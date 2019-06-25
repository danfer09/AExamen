<html>
<head>
	<title>AExamen Exámenes</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
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
			echo "<h2> Examenes </h2>";
			//incluimos los archivos que contienen una serie de funciones que vamos a usar
			// include "examenesProcesamiento.php";
			// include 'funcionesServidor.php';
			//Mostramos mensajes de exito si se ha realizado alguna accion correctamente
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
			} else if (isset($_SESSION['error_generar_examen'])&& $_SESSION['error_generar_examen']) {
	      echo '<div class="alert alert-success alert_success" role="alert">
	          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	            Error al generar examen. El examen seleccionado no contiene preguntas.
	            </div>';
							$_SESSION['error_generar_examen'] = false;
	    }
		?>
		<br>
		<div class="row" id="filtros">
			<div class="form-inline col-lg-3">
				<label for="selAsignatura">Asignatura: </label>
				<select class="form-control" id="selAsignatura" onchange="location = this.value;">
					<?php
						//Con el valor que nos pasen por parametros pondremos el filtro en
						//un estado o en otro
						if ($_GET['asignatura'] == "todas") {
							echo '<option value="/examenes/index?asignatura=todas&autor='.$_GET['autor'].'$" selected>Todas</option>';
						} else {
							echo '<option value="/examenes/index?asignatura=todas&autor='.$_GET['autor'].'">Todas</option>';
						}
						//Mostramos las siglas que hemos cargado de BBDD, la que este
						//seleccionada la ponemos en ese estado en el formulario
						if ($siglas == null){
							echo 'No hay siglas';
						} else if (!$siglas){
							echo '<br><div class="alert alert-danger" role="alert">
								    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								      Error con la BBDD, contacte con el administrador
								    </div>';
						} else {
							foreach ($siglas as $pos => $valor) {
								if ($_GET['asignatura'] == $valor['siglas']) {
									echo '<option value="/examenes/index?asignatura='.$valor['siglas'].'&autor='.$_GET['autor'].'" selected>'.$valor['siglas'].'</option>';
								} else {
									echo '<option value="/examenes/index?asignatura='.$valor['siglas'].'&autor='.$_GET['autor'].'">'.$valor['siglas'].'</option>';
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
						//Cargamos los profesores de la asignatura que estemos mostrando
						if ($_GET['autor'] == "todos") {
							echo '<option value="/examenes/index?asignatura='.$_GET['asignatura'].'&autor=todos" selected>Todos</option>';
						} else {
							echo '<option value="/examenes/index?asignatura='.$_GET['asignatura'].'&autor=todos">Todos</option>';
						}
						//Mostramos a los profesores que hemos cargado de la BBDD en el
						//formulario
						if ($autores == null){
							echo 'No hay nombres de profesores';
						} else if (!$autores){
							echo '<br><div class="alert alert-danger" role="alert">
									    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									      Error con la BBDD, contacte con el administrador
									    </div>';
						} else {
							foreach ($autores as $pos => $valor) {
								if ($_GET['autor'] == $valor['email']) {
									echo '<option value="/examenes/index?asignatura='.$_GET['asignatura'].'&autor='.$valor['email'].'" selected>'.$valor['email'].' ('.$valor['nombre'].')</option>';
								} else {
									echo '<option value="/examenes/index?asignatura='.$_GET['asignatura'].'&autor='.$valor['email'].'">'.$valor['email'].' ('.$valor['nombre'].')</option>';
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
			//Si es administrador le impedimos crear examen
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
						//cargamos las siglas de los examenes que puede crear un profesor
						//en el formulario
						if ($siglas == null){
							echo 'No hay siglas';
						} else if (!$siglas){
							echo '<br><div class="alert alert-danger" role="alert">
									    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									      Error con la BBDD, contacte con el administrador
									    </div>';
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
						print('<a class="fas fa-plus-circle fa-2x masExamenes" hidden id="boton_modalAñadir" href="/crearexamenes/index?asignatura='.$_GET["asignatura"].'&idAsignatura='.$idAsig.'"></a>');
					} else {
						print('<a class="fas fa-plus-circle fa-2x masExamenes" id="boton_modalAñadir" href="/crearexamenes/index?asignatura='.$_GET["asignatura"].'&idAsignatura='.$idAsig.'"></a>');
					}

				?>
			</div>
		</div>
		<?php
			}
		?>
		<br>
		<!-- tabla en la que mostramos los examenes-->
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
			//Si no hay examenes o hubo un error con la BBDD los mostramos
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
				//Mostramos los examenes que hemos cargado de la BBDD
				foreach ($examenes as $pos => $valor) {
					echo '<tr class="item">';
					echo '<td> <i class="fas fa-file-invoice fa-fw fa-lg"></i> '.$valor['asignaturas']['asignatura'].' </td>';
					echo '<td>'.$valor["e1"]['titulo'].'</td>';
					echo '<td>'.$valor["p1"]['creador'].'</td>';
					echo '<td hidden=true;>'.$valor["e1"]['fecha_creado_raw'].'</td>';
					echo '<td hidden=true;>'.$valor["e1"]['fecha_modificado_raw'].'</td>';
					echo '<td>'.$valor["e1"]['fecha_creado'].'</td>';
					echo '<td>'.$valor["e1"]['fecha_modificado'].'</td>';
					echo '<td>'.$valor["p2"]['ultimo_modificador'].'</td>';
					echo '<td id="opciones">
							<a class="btn btn-primary btn-sm" id="idDetallesExam" href="/examenes/detalle_examen?id='.$valor["e1"]['id'].'" role="button">Detalles</a>';
					if (!$_SESSION['administrador']) {
						echo '<a class="btn btn-primary btn-sm" href="/examenes/generar_un_examen?examen='.$valor['e1']['titulo'].'" role="button">Generar</a>';
						echo '<a id="boton_modalEditar" idExamen="'.$valor["e1"]['id'].'" href="/crearexamenes/index?asignatura='.$valor["asignaturas"]['asignatura'].'&idAsignatura='.$valor["asignaturas"]['idAsignatura'].'&editar=1&id='.$valor["e1"]['id'].'"><i class="fas fa-pencil-alt fa-fw fa-lg"></i></a>';
					}
					echo '<a id="boton_modalBorrar" idExamen="'.$valor["e1"]['id'].'"><i class="fas fa-trash-alt fa-fw fa-lg"></i></a> </td>';
					echo '</tr>';
				}
			}
		?>
			</tbody>
		</table>
		<!-- Modal de borrar examen-->
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
			    </div>
			  </div>
			</div>
		</div>

	</div>

  <?php
    echo $this->Html->script('examenes');
  ?>

</body>
</html>
