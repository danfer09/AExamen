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
	<div class="header" id="header">

	</div>
	<div class="container">
		<?php 
			//error_reporting(0); // Disable all errors.

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

			//echo $_SESSION['coordinador'];

			echo "<h1>Preguntas de ". $_GET['nombreAsignatura']. "</h1>";
			$_SESSION['idAsignatura']=$_GET['idAsignatura'];
			include "preguntasProcesamiento.php";
			include 'examenesProcesamiento.php';
			include 'funcionesServidor.php';
		?>
		<br>
		<div class="row" id="filtros">
			<div class="col-lg-1"></div>
			<div class="form-inline col-lg-4">
				<label for="sel1">Autor </label>
				<select class="form-control" id="sel1" onchange="location = this.value;">
					<?php
						
						$autores = selectAllMailsProfesores();
						if ($_GET['autor'] == "todos") {
							echo '<option value="preguntas.php?nombreAsignatura='. $_GET['nombreAsignatura'].'&idAsignatura='.$_GET['idAsignatura'].'&autor=todos" selected>Todos</option>';
						} else {
							echo '<option value="preguntas.php?nombreAsignatura='. $_GET['nombreAsignatura'].'&idAsignatura='.$_GET['idAsignatura'].'&autor=todos">Todos</option>';
						}

						if ($autores == null){
							echo 'No hay nombres de profesores';
						} else if (!$autores){
							echo 'Error con la BBDD, contacte con el administrador';
						} else {
							foreach ($autores as $pos => $valor) {
								if ($_GET['autor'] == $valor['email']) {
									echo '<option value="preguntas.php?nombreAsignatura='. $_GET['nombreAsignatura'].'&idAsignatura='.$_GET['idAsignatura'].'&autor='.$valor['email'].'" selected>'.$valor['email'].' ('.$valor['nombre'].')</option>';
								} else {
									echo '<option value="preguntas.php?nombreAsignatura='. $_GET['nombreAsignatura'].'&idAsignatura='.$_GET['idAsignatura'].'&autor='.$valor['email'].'">'.$valor['email'].' ('.$valor['nombre'].')</option>';
								}
							}
						}
					?>
				</select>
			</div>
			<input oninput="w3.filterHTML('#tabla_preguntas', '.item', this.value)" class="w3-input col-lg-7" placeholder="Buscar...">
		</div>
		<br>
		<?php
			echo (!$_SESSION['administrador'])?'<a class="fas fa-plus-circle" id="boton_modalAñadir"></a>':'';
		?>
		<table class="table table-hover" id="tabla_preguntas">
		    <thead>
		      <tr>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(1)')" style="cursor:pointer;">Titulo</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(2)')" style="cursor:pointer;">Tema</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(3)')" style="cursor:pointer;">Autor</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(4)')" style="cursor:pointer;">Fecha creación</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(5)')" style="cursor:pointer;">Últ. modificación</th>
		        <th> </th>
		      </tr>
		    </thead>
		    <tbody>
		<?php
			$preguntas=cargaPreguntas($_GET['idAsignatura'], $_GET['autor']);

			$error_ningunaPregunta = isset($_SESSION['error_ningunaPregunta'])? $_SESSION['error_ningunaPregunta']: false;
			$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
			$error_BorrarNoCreador = isset($_SESSION['error_BorrarNoCreador'])? $_SESSION['error_BorrarNoCreador']: false;
			$error_noPoderBorrar = isset($_SESSION['error_no_poder_borrar'])? $_SESSION['error_no_poder_borrar']: false;
			if($error_BorrarNoCreador){
				?>
				<div class="alert alert-warning">
				    <p>No se ha podido borrar la pregunta. Solo los autores pueden borrar sus preguntas.</p>
				 </div>
				<?php
				$_SESSION['error_BorrarNoCreador'] = false;
			}

			if($error_noPoderBorrar){
				?>
				<div class="alert alert-warning">
				    <p>No se ha podido borrar la pregunta. Esta pregunta está en uno o más exámenes. Para borrarla quítala primero de el/los exámenes.</p>
				 </div>
				<?php
				$_SESSION['error_no_poder_borrar'] = false;
			}
			
			if($error_ningunaPregunta){
				?>
				<div class="alert alert-warning">
				    <p>Esta asignatura no tiene ninguna pregunta para ti.</p>
				 </div>
				<?php
				$_SESSION['error_ningunaPregunta'] = false;
			}
			else if($error_BBDD){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			else{
				foreach ($preguntas as $pos => $valor) {
					echo '<tr class="item" >';
					//Esto es para que muestre los detalles cuando se pulsa en al fila,pero si se activa, no funcionan los demás botones
					//echo '<td><a href="'.$valor['id_preguntas'].'"></a>'.$valor['titulo'].'</td>';
					echo '<td>'.$valor['titulo'].'</td>';
					echo '<td>'.$valor['tema'].'</td>';
					echo '<td>'.$valor['autor'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_creacion'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_modificado'].'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_creacion']).'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_modificado']).'</td>';
					echo '<td id="opciones">';
						echo '<a class="btn btn-primary" href="detallePregunta.php?id='.$valor['id_preguntas'].'" role="button">Detalles</a>';
						echo (!$_SESSION['administrador'])? '<a class="fas fa-edit" id="boton_modalEditar" idPreguntas="'.$valor['id_preguntas'].'"></a>': '';
						echo '<a class="fas fa-trash-alt" id="boton_modalBorrar" idPreguntas="'.$valor['id_preguntas'].'"></a>';
					echo '</td>';
					echo '</tr>';
					
				}
			}
		?>
			</tbody>
		</table>


		<!-- Modal de añadir pregunta -->
		<div class="modal" id="modal_aniadirPregunta">
			<div class="modal-dialog">
			  <div class="modal-content">
			  
			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Añadir pregunta</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="preguntasProcesamiento.php" class="form-container" method="post" id="form_add">
					    <h1 name="aniadirPregunta">Añadir pregunta</h1>

					    <input type="text" placeholder="Introduzca el titulo" name="titulo" id="titulo">
					    <br>
					    <input type="text" placeholder="Introduzca el cuerpo" name="cuerpo" id="cuerpo">
					    <br>
					    <input type="text" placeholder="Introduzca el tema" name="tema" id="tema">
					    <br>
					    <button type="submit" class="btn" id="boton_añadir" name="boton_añadir">Insertar</button>
					  </form>
			    </div>
			    
			    <!-- Modal footer -->
			    <div class="modal-footer">
			      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			    </div>
			    
			  </div>
			</div>
		</div>

		<!-- Modal de borrar pregunta -->
		<div class="modal" id="modal_borrarPregunta">
			<div class="modal-dialog">
			  <div class="modal-content">
			  
			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Borrar pregunta</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="preguntasProcesamiento.php" class="form-container" method="post" id="form_delete">
					    <h1 name="borrarPregunta">Borrar pregunta</h1>
					    <button type="submit" class="btn btn-primary" id="boton_borrar" name="boton_borrar">Si</button>
					    <button type="button" class="btn btn-danger" id="boton_noBorrar" name="boton_noBorrar" data-dismiss="modal">No</button>					  
					  </form>
			    </div>
			    
			    <!-- Modal footer -->
			    <div class="modal-footer">
			      <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>-->
			    </div>
			    
			  </div>
			</div>
		</div>

		<!-- Modal de editar pregunta -->
		<div class="modal" id="modal_editarPregunta">
			<div class="modal-dialog">
			  <div class="modal-content">
			  
			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Editar pregunta</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="preguntasProcesamiento.php" class="form-container" method="post" id="form_mod">
					    <h1">Editar pregunta</h1>
					    <p id="infoParaEditar">Los campos que deje vacíos mantendrán su valor actual</p>
					    <input type="text" placeholder="Introduzca el titulo" name="titulo" id="titulo">
					    <br>
					    <input type="text" placeholder="Introduzca el cuerpo" name="cuerpo" id="cuerpo">
					    <br>
					    <input type="text" placeholder="Introduzca el tema" name="tema" id="tema">
					    <br>
					    <button type="submit" class="btn" id="boton_editar" name="boton_editar">Editar</button>
					  </form>
			    </div>
			    
			    <!-- Modal footer -->
			    <div class="modal-footer">
			      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			    </div>
			    
			  </div>
			</div>
		</div>
		
	</div>

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/preguntas.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>

