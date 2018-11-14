<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

</head>
<body>
	<div class="container">
		<?php 
			//error_reporting(0); // Disable all errors.

			session_start();
			echo "<h1>Preguntas de ". $_GET['nombreAsignatura']. "</h1>";
			$_SESSION['idAsignatura']=$_GET['idAsignatura'];
			include "preguntasProcesamiento.php";
			include "servidor.php";
		?>

		<br>
		<p>
			<input oninput="w3.filterHTML('#tabla_preguntas', '.item', this.value)" class="w3-input" placeholder="Buscar...">
		</p>
		<table class="table table-hover" id="tabla_preguntas">
		    <thead>
		      <tr>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(1)')" style="cursor:pointer;">Titulo</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(2)')" style="cursor:pointer;">Tema</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(3)')" style="cursor:pointer;">Autor</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(4)')" style="cursor:pointer;">Fecha creación</th>
		        <th onclick="w3.sortHTML('#tabla_preguntas', '.item', 'td:nth-child(5)')" style="cursor:pointer;">Últ. modificación</th>
		      </tr>
		    </thead>
		    <tbody>
		<?php

			$asignaturas=cargaPreguntas($_GET['idAsignatura']);

			$error_ningunaPregunta = isset($_SESSION['error_ningunaPregunta'])? $_SESSION['error_ningunaPregunta']: false;
			$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;

			if($error_ningunaPregunta){
				echo 'Esta asignatura no tienen ninguna pregunta';
			}

			else if($error_BBDD){
				echo 'Error con la BBDD, contacte con el administrador';
			}
			else{
				foreach ($asignaturas as $pos => $valor) {
					echo '<tr class="item" style="cursor:pointer;">';
					echo '<td><a href="'.$valor['id_preguntas'].'"></a>'.$valor['titulo'].'</td>';
					echo '<td>'.$valor['tema'].'</td>';
					echo '<td>'.$valor['autor'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_creacion'].'</td>';
					echo '<td hidden=true;>'.$valor['fecha_modificado'].'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_creacion']).'</td>';
					echo '<td>'.formateoDateTime($valor['fecha_modificado']).'</td>';
					echo '</tr>';
					echo '<td id="opciones"><a class="fas fa-edit" id="boton_pregunta"></a><a class="fas fa-trash-alt" id="boton_pregunta"></a><a class="fas fa-plus-circle" id="boton_pregunta"></a></td>';
					
				}
			}
		?>
			</tbody>
		</table>


		<!-- Modal de añadir pregunta -->
		<div class="modal" id="modal_aniadirPregunta" funcion="aniadirPregunta">
			<div class="modal-dialog">
			  <div class="modal-content">
			  
			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Añadir pregunta</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="" class="form-container" method="post" id="form_add">
					    <h1 name="aniadirPregunta">Añadir pregunta</h1>

					    <input type="text" placeholder="Introduzca el titulo" name="titulo" id="titulo">
					    <input type="text" placeholder="Introduzca el cuerpo" name="cuerpo" id="cuerpo">
					    <input type="text" placeholder="Introduzca el tema" name="tema" id="tema">

					    <button type="submit" class="btn" id="boton_añadir" name="boton_añadir">Cambiar</button>
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

	<script src="jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="preguntas.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>-->
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

