<html>
<head>
	<title>AExamen Profesores</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Profesores del sistema</h1>
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
			// include 'profesoresAdminProcesamiento.php';
			//Comprobamos que es un administrador, en caso contrario lo redirigimos
			if (!$_SESSION['administrador']){
				header('Location: index.php');
			}

			$error_envio_mail = isset($_SESSION['error_envio_mail'])? $_SESSION['error_envio_mail']: null;
			if ($error_envio_mail) {
				echo 'Error al enviar el email al profesor.';
			}
		?>
		<br>
		<div class="row" id="filtros">
			<input oninput="w3.filterHTML('#tabla_admin_profesores', '.item', this.value)" class="w3-input col-12" placeholder="Buscar...">
		</div>
		<br>
		<div class="row" id="aniadir">
			<div class="form-inline col-lg-2">
				<label for="boton_modalAñadir">Invitar profesor</label>
				<?php
					print('<a class="fas fa-plus-circle fa-2x masExamenes" href="#" id="boton_modalAñadir"></a>');
				?>
			</div>
			<div class="form-inline col-lg-3">
				<label for="boton_peticiones">Peticiones de registro </label>
				<button type="button" id="boton_peticiones" onclick="window.location='/panelescontroles/index';" class="btn btn-primary"><i class="fas fa-external-link-alt"></i></button>
			</div>
		</div>

		<br>
		<!-- Tabla donde se muestran todos los profesores-->
		<table id="tabla_admin_profesores" class="table table-hover">
		    <thead>
		      <tr>
		      	<th>	</th>
		        <th onclick="w3.sortHTML('#tabla_admin_profesores', '.item', 'td:nth-child(2)')" class="cabeceraTabla">Nombre</th>
		        <th onclick="w3.sortHTML('#tabla_admin_profesores', '.item', 'td:nth-child(3)')" class="cabeceraTabla">Apellidos</th>
		        <th onclick="w3.sortHTML('#tabla_admin_profesores', '.item', 'td:nth-child(4)')" class="cabeceraTabla">Correo</th>
		        <th>Opciones</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php
			//Obtenemos los profesores que hay en el sistema

			//Mostramos los diversos errores que se pueden producir
			if ($profesores == null){
				echo'<div class="alert alert-warning">';
				echo'<p>No hay profesores</p>';
				echo'</div>';
			} else if (!$profesores){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			//En caso de que no se haya producido ningún error mostramos todos los
			//profesores
			else{
				foreach ($profesores as $pos => $valor) {
					echo '<tr class="item">';
					echo '<td> <i class="fas fa-user fa-fw fa-lg"></i> </td>';
					echo '<td id="idProfesor" hidden="true">'.$valor['id'].'</td>';
					echo '<td id="nombreProfesor'.$valor['id'].'">'.$valor['nombre'].'</td>';
					echo '<td id="apellidosProfesor'.$valor['id'].'">'.$valor['apellidos'].'</td>';
					echo '<td id="emailProfesor'.$valor['id'].'">'.$valor['email'].'</td>';
					echo '<td id="opciones">
						<a id="boton_modalEditar" href="#" idProfesor="'.$valor['id'].'" ><i class="fas fa-pencil-alt fa-fw fa-lg"></i></a>
						<a id="boton_modalBorrar" href="#" idProfesor="'.$valor['id'].'"><i class="fas fa-trash-alt fa-fw fa-lg"></i></a>
						<button type="button" class="btn btn-primary modalAsignaturas" idProfesor = "'.$valor['id'].'">Asignaturas coordinadas</button></td>';
					echo '</tr>';
				}
			}
		?>
			</tbody>
		</table>

		<!-- Modal de borrar profesor -->
		<div class="modal" id="modal_borrarProfesor">
			<div class="modal-dialog">
			  <div class="modal-content">

			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">¿Borrar profesor del sistema?</h4>
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

		<!-- Modal de editar profesor -->
		<div class="modal" id="modal_editarProfesor">
			<div class="modal-dialog">
			  <div class="modal-content">

			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Editar profesor</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    <span id='mensajeEditar'></span><br>
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="#" class="form-container" method="post" id="form_mod">
					  	<div class="form-group">
					  		<label for="nombre">Nombre</label>
					    	<input type="text" class="form-control" placeholder="Introduzca el nombre" name="nombre" id="nombreForm">
						</div>
						<div class="form-group">
					    	<label for="apellidos">Apellidos</label>
					    	<input type="text" class="form-control" placeholder="Introduzca los apellidos" name="apellidos" id="apellidosForm">
						</div>
						<div class="form-group">
						    <label for="email">Email</label>
						    <input type="email" class="form-control" placeholder="Introduzca el email" name="email" id="emailForm">
						</div>
						<br>
					  </form>
			    </div>

			    <!-- Modal footer -->
			    <div class="modal-footer">
			    	<button type="submit" class="btn btn-primary" id="boton_editar" name="boton_editar">Actualizar</button>
			    	<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			    </div>

			  </div>
			</div>
		</div>

		<div class="modal" id="modalAniadirProfesor">
			<div class="modal-dialog">
			  <div class="modal-content">

			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Enviar invitación a profesor</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>

			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="/profesores/funcionesAjaxProfesores" class="form-container" method="post" id="formAniadirProfesor">
					    <div class="row">
					    	<div class="col-md-8">
					    		<label for="email" >Email:</label>
					    		<input type="email" required class="form-control" name="email" id="email">
					    	</div>
					    </div>
					    <br>
					    <button type="submit" class="btn btn-primary" id="boton_aniadir" name="boton_aniadir">Enviar</button>
					    <button type="button" class="btn btn-danger" id="boton_noAniadir" name="boton_noAniadir" data-dismiss="modal">Cancelar</button>
					  </form>
			    </div>

			    <!-- Modal footer -->
			    <div class="modal-footer">
			    </div>

			  </div>
			</div>
		</div>

		<!-- Modal de definir asignaturas que coordina los profesores -->
		<div class="modal" id="modalAsignaturas">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">

		    <!-- Modal Header -->
		    <div class="modal-header">
		      <h4 class="modal-title">Asignaturas que coordina</h4>
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		    </div>

		    <!-- Modal body -->
		    <div class="modal-body">
				  <form action="#" class="form-container" method="post" id="formAsig">
				    	<div class="table-wrapper-scroll-y">
			    			<table class="table table-hover" id="tabla">
								<thead>
							      <tr>
							      	<th>#</th>
							        <th>Siglas</th>
							        <th>Nombre</th>
							      </tr>
							    </thead>
							    <tbody id="tableAsignaturas">
						 		</tbody>

							</table>
						</div>
				    <button type="submit" class="btn btn-primary" id="boton_aniadir_asig" name="boton_aniadir_asig">Añadir</button>
				    <button type="button" class="btn btn-danger" id="boton_noAniadir" name="boton_noAniadir" data-dismiss="modal">Cancelar</button>
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
    echo $this->Html->script('profesoresAdmin');
  ?>
</body>
</html>
