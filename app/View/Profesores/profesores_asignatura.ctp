<html>
<head>
	<title>AExamen Profesores</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Pagina de Coordinador</h1>
		<?php
			echo "<h2 idAsig=".'"'.$idAsignatura.'"'."> Profesores de ".$nombreAsignatura." </h2>";
		?>
		<br>
		<div class="row" id="filtros">
			<input oninput="w3.filterHTML('#tabla_profesores', '.item', this.value)" class="w3-input col-12" placeholder="Buscar...">
		</div>
		<br>
		<div class="row" id="aniadir">
			<div class="form-inline col-lg-4">
				<label for="selAniadir">Añadir profesor </label>
				<?php
					print('<a class="fas fa-plus-circle fa-2x masExamenes" href="#" id="boton_modalAniadir"></a>');
				?>
			</div>
		</div>

		<!--Tabla donde se van a mostrar los profesores de la asignatura -->
		<table id="tabla_profesores" class="table table-hover">
		    <thead>
		      <tr>
		      	<th>	</th>
		        <th onclick="w3.sortHTML('#tabla_profesores', '.item', 'td:nth-child(2)')" class="cabeceraTabla">Nombre</th>
		        <th onclick="w3.sortHTML('#tabla_profesores', '.item', 'td:nth-child(3)')" class="cabeceraTabla">Apellidos</th>
		        <th onclick="w3.sortHTML('#tabla_profesores', '.item', 'td:nth-child(4)')" class="cabeceraTabla">Correo</th>
		        <th>Borrar</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php
			//Tratamos los errores que se hayan podido producir
			if ($profesores == null){
				echo '<br><div class="alert alert-warning" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  		No hay profesores en esta asignatura
					  </div>';
			} else if (!$profesores){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			//Si no hubo errores se muestran todos los profesores de la asignatura
			else{
				foreach ($profesores as $pos => $valor) {
					echo '<tr class="item">';
					echo '<td> <i class="fas fa-user fa-fw fa-lg"></i> </td>';
					echo '<td id="idProfesor" hidden="true">'.$valor['id'].'</td>';
					echo '<td>'.$valor['nombre'].'</td>';
					echo '<td>'.$valor['apellidos'].'</td>';
					echo '<td>'.$valor['email'].'</td>';
					echo '<td id="opciones">
						<a id="boton_modalBorrar" idProfesor="'.$valor['id'].'"><i class="fas fa-trash-alt fa-fw fa-lg"></i></a> </td>';
					echo '</tr>';
				}
			}
		?>
			</tbody>
		</table>

		<!-- Modal de borrar un profesor -->
		<div class="modal" id="modal_borrarProfesor">
			<div class="modal-dialog">
			  <div class="modal-content">

			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">¿Borrar profesor de la asignatura?</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>

			    <!-- Modal body -->
			    <div class="modal-body" id="modal_borrarProfesor_body">
					  <form action="#" class="form-container" method="post" id="form_delete">
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

	<!-- Modal de añadir un profesor -->
	<div class="modal" id="modalAniadirProfesor">
			<div class="modal-dialog modal-lg">
			  <div class="modal-content">

			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Añadir profesor</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>

			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="#" class="form-container" method="post" id="formAniadirProfesor">
					    	<div id="infoTodosProfAdd" class="badge badge-pill badge-info">Ya están todos los profesores añadidos</div>
					    	<div class="table-wrapper-scroll-y">
				    			<table class="table table-hover" id="tabla">
									<thead>
								      <tr>
								      	<th>#</th>
								        <th>Nombre</th>
								        <th>Apellidos</th>
								        <th>Correo</th>
								      </tr>
								    </thead>
								    <tbody id="tableAniadirProfesor">
							 		</tbody>

								</table>
							</div>
					    <button type="submit" class="btn btn-primary" id="boton_aniadir" name="boton_aniadir">Añadir</button>
					    <button type="button" class="btn btn-danger" id="boton_noAniadir" name="boton_noAniadir" data-dismiss="modal">Cancelar</button>
					  </form>
			    </div>

			    <!-- Modal footer -->
			    <div class="modal-footer">
			    </div>
			  </div>
			</div>
		</div>
  <?php
    echo $this->Html->script('profesoresDeUnaAsig');
  ?>

</body>
</html>
