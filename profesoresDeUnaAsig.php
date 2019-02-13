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
		<h1>Pagina de Coordinador</h1>
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
			$idAsignatura = $_GET['idAsig'];
			$nombreAsignatura = $_GET['nombreAsig'];
			echo "<h2 idAsig=".'"'.$idAsignatura.'"'."> Profesores de ".$nombreAsignatura." </h2>";
			include "profesoresDeUnaAsigProcesamiento.php";
			include 'funcionesServidor.php';
			
			$esCoordinador = esCoordinador($idAsignatura, $_SESSION['id']);
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if (!$esCoordinador) {
				header('Location: index.php');
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
			<input oninput="w3.filterHTML('#tabla_profesores', '.item', this.value)" class="w3-input col-12" placeholder="Buscar...">
		</div>
		<br>
		<div class="row" id="aniadir">
			<div class="form-inline col-lg-4">
				<label for="selAniadir">Añadir profesor a la asignatura: </label>
				<?php
					print('<a class="fas fa-plus-circle" style="text-decoration: none; cursor: pointer;" id="boton_modalAñadir"></a>');					
				?>
			</div>
		</div>


		<table id="tabla_profesores" class="table table-hover">
		    <thead>
		      <tr>
		      	<th>	</th>
		        <th onclick="w3.sortHTML('#tabla_profesores', '.item', 'td:nth-child(2)')" style="cursor:pointer;">Nombre</th>
		        <th onclick="w3.sortHTML('#tabla_profesores', '.item', 'td:nth-child(3)')" style="cursor:pointer;">Apellidos</th>
		        <th onclick="w3.sortHTML('#tabla_profesores', '.item', 'td:nth-child(4)')" style="cursor:pointer;">Correo</th>
		        <th>Borrar</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php
			$profesores = profesoresAsignatura($idAsignatura);

			if ($profesores == null){
				echo'<div class="alert alert-warning">';
				echo'<p>No hay profesores en esta asignatura</p>';
				echo'</div>';
			} else if (!$profesores){
				echo 'Error con la BBDD, contacte con el administrador';
			}
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
					    <!--<h1 name="borrarExamen">Añadir preguntas</h1>-->
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
			      <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>-->
			    </div>
			    
			  </div>
			</div>
		</div>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script type="text/javascript" src="js/profesoresDeUnaAsig.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>

