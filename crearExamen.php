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
	<div class="container-fluid">
		<?php 
			session_start();
			include "examenesProcesamiento.php";
			include "preguntasProcesamiento.php";
			include "crearExamenProcesamiento.php";
			include 'servidor.php';
			echo "<h1>Crear examen de : ". $_GET["asignatura"]. "</h1>";
			
		?>

		<br>
		<div class="row">

			<div class="col-1"></div>
			<div class="col-7"><input class="w3-input col-lg-5" placeholder="Buscar..."></div>
			<div class="col-2"><span>Total(3/10)</span></div>
			<div class="col-2"><button><i class="fas fa-save"></i></button></div>
		
		</div>

		<div class="row">
			<div class="col-1"><buttom><i class="fas fa-arrow-left"></i></buttom></div>
			<div class="col-11"><hr /></div>
		</div>

		<div class="row">
			<div class="col-5">
				<span id="openNav"><i class="fas fa-bars"></i></span>
				<div id="mySidenav" class="sidenav">
				  <a href="javascript:void(0)" class="closebtn" id="closeNav">&times;</a>
				  <a href="#">Tema 1</a>
				  <a href="#">Tema 2</a>
				  <a href="#">Tema 3</a>
				  <a href="#">Tema 4</a>
				</div>
			</div>
		</div>		



		<?php
			$numTemas = getNumTemas($_GET["idAsignatura"]);
			for ($i = 1; $i <= $numTemas; $i++) {
			   
			    echo '<div class="row">';
					echo'<div class="col-5">';
						echo'<span>Tema'.$i.'</span>';
						echo '<a class="fas fa-plus-circle" id="boton_aniadirPregunta" tema ="'.$i.'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>';
					echo'</div>';
				echo'</div>';
				echo'<div class="row" id="preguntasTema'.$i.'">';
				echo'<div class="col-5">';
					echo'</div>';
				echo'</div>';
			}				
		?>
		<div class="row">
			<div class="col-5">

				
			</div>
		</div>

		<div class="modal" id="modal_aniadirPreguntas">
			<div class="modal-dialog">
			  <div class="modal-content">
			  
			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Añadir preguntas</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="" class="form-container" method="post" id="form_delete">
					    <h1 name="borrarExamen">Añadir preguntas</h1>
					    	<div class="table-wrapper-scroll-y">
				    			<table class="table table-hover" id="tabla">	
									<thead>
								      <tr>
								        <th>Titulo</th>
								        <th>Cuerpo</th>
								        <th>Tema</th>
								      </tr>
								    </thead>			
								    <tbody id="table_añadirPreguntas">
							 		</tbody>
									  	

								</table>
							</div>
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

		
    </div>
		
	<script src="jquery-3.3.1.min.js"></script>
	<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="crearExamen.js"></script>
	<script type="text/javascript" src="cabeceraConLogin.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

