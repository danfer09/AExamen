<!--COMPROBAR QUE EL USUARIO ESTA LOGEADO -->

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container-fluid">
		<?php 
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			include "examenesProcesamiento.php";
			include "preguntasProcesamiento.php";
			include "crearExamenProcesamiento.php";
			echo "<h1>Crear examen de : ". $_GET["asignatura"]. "</h1>";
			$_SESSION['nombreAsignatura'] = $nombreAsignatura = $_GET["asignatura"];
			
			//Llamamos a la variable Session igual que la asignatura, asi nos permitirá tener guardado un examen de cada asignatura en la sesion, 
			//además de que evitaremos errores a la hora de cargar el examen de otra asignatura.
			$_SESSION[$nombreAsignatura] = isset($_SESSION[$nombreAsignatura])? $_SESSION[$nombreAsignatura]:'{
				"nombreExamen":"IS Parcial 2017",
				"preguntas":{
					"tema1":{
						"0":{ 
								"id": 1,
								"puntos": 1
						},
						"1":{ 
								"id": 67,
								"puntos": 1
						}
					},
					"tema2":{
						"0":{
								"id": 2,									
								"puntos": 1
						}
					},
					"tema3": {}
				}
			}';
			$preguntasSesion = isset($_SESSION[$nombreAsignatura])? json_decode($_SESSION[$nombreAsignatura],true): null;
			//var_dump($_SESSION[$nombreAsignatura]);exit();

			//cargarVariablesExamenSesion($preguntasSesion, $nombreAsignatura);

			
			$nombreExamen = isset($preguntasSesion['nombreExamen'])? $preguntasSesion['nombreExamen']: null;
		?>

		<br>
		<div class="row">

			<div class="col-1"></div>
			<div class="col-7"><input id="nombreExamen" class="w3-input col-8" placeholder=<?php echo '"Escriba el nombre... e.g.  '.$_GET["asignatura"].' [Parcial/Final] [Año]" value="'.$nombreExamen.'"'?>>

			</div>
			<div class="col-2">
				<?php
					$arrayPuntosTema =cargaPuntosTema($_GET["idAsignatura"]);
					$jsonPuntosTema = json_decode($arrayPuntosTema,true);
					echo '<span>Total(</span>';
					echo '<span>';
					$preguntas = isset($preguntasSesion['preguntas'])? $preguntasSesion['preguntas']: null;
					$suma = 0;
					if($preguntas){
						foreach ($preguntas as $tema) {
							foreach ($tema as $preguntasTema) {
								$suma+=$preguntasTema['puntos'];
								/*echo($preguntasTema['puntos']);
								echo("-----------------");
								var_dump($preguntasTema);
								echo("------------------");*/
							}
						}
					}
					echo $suma;
					echo '/</span>';
					echo '<span>'.$jsonPuntosTema['maximoPuntos'].')</span>'
				?>

			</div>
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
				  <?php
				  $numTemas = getNumTemas($_GET["idAsignatura"]);
				  for ($i = 1; $i <= $numTemas; $i++) {
				  	echo '<a href="#">Tema'.$i.'</a>';
				  }
				  ?>
				</div>
			</div>
		</div>		



		<?php
			$numTemas = getNumTemas($_GET["idAsignatura"]);
			$arrayPuntosTema =cargaPuntosTema($_GET["idAsignatura"]);
			$jsonPuntosTema = json_decode($arrayPuntosTema,true);
			
			for ($i = 1; $i <= $numTemas; $i++) {
			    echo '<div class="row">';
					echo'<div class="col-12" id="tema'.$i.'">';
						echo'<span>Tema'.$i.'</span>';
						echo'<span>(';
						$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
						$sumaTema = 0;
						if ($preguntasTema) {
							foreach ($preguntasTema as $pregunta) {
								$sumaTema += $pregunta['puntos'];	
							}
						}
						echo $sumaTema.'/'.$jsonPuntosTema["tema".$i].')</span>';
						echo '<a class="fas fa-plus-circle" id="boton_aniadirPregunta" tema ="'.$i.'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>';
					echo'</div>';
				echo'</div>';
				echo'<div class="row" id="preguntasTema'.$i.'">';
					$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
					#¿¿PARA QUE QUEREMOS PONER AQUI ESTA VARIABLE A CERO??
					$sumaTema = 0;
					if ($preguntasTema) {
						foreach ($preguntasTema as $pregunta) {
							$datos = cargaUnicaPregunta($pregunta['id']);
							echo '<div class="col-12">'.$datos['titulo'].' '.$datos['cuerpo'].'</div><br>';
						}
					}
				echo'</div>';
				echo '<div class="row"><div class="col-12"><hr /></div></div>';
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
					  <form action="#" class="form-container" method="post" id="form_aniadirPregunta">
					    <h1 name="borrarExamen">Añadir preguntas</h1>
					    	<div id="info_aniadirPreg" class="badge badge-pill badge-danger">No hay ninguna pregunta de este tema</div>
					    	<div class="table-wrapper-scroll-y">
				    			<table class="table table-hover" id="tabla">	
									<thead>
								      <tr>
								      	<th>#</th>
								        <th>Titulo</th>
								        <th>Cuerpo</th>
								        <th>Tema</th>
								      </tr>
								    </thead>			
								    <tbody id="table_añadirPreguntas">
							 		</tbody>
									  	

								</table>
							</div>
					    <button type="submit" class="btn btn-primary" id="boton_añiadir" name="boton_añiadir">Añadir</button>
					    <button type="button" class="btn btn-danger" id="boton_noAñiadir" name="boton_noAñiadir" data-dismiss="modal">Cancelar</button>					  
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
	<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/crearExamen.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>

</body>
</html>

