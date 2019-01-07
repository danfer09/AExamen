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
			include "modificarExamenProcesamiento.php";
			
			$_SESSION['nombreAsignatura'] = $nombreAsignatura = $_GET["asignatura"];
			$_SESSION['idAsignatura'] = $_GET["idAsignatura"];
			$editar=isset($_GET["editar"])? $_GET["editar"] : 0;
			$_SESSION['editar'] = $editar;

			//Llamamos a la variable Session igual que la asignatura, asi nos permitirá tener guardado un examen de cada asignatura en la sesion, 
			//además de que evitaremos errores a la hora de cargar el examen de otra asignatura.
			if(!$editar){
				echo "<h1>Crear examen de ". $_GET["asignatura"]. "</h1>";
				$_SESSION[$nombreAsignatura] = (isset($_SESSION[$nombreAsignatura])&& $_SESSION[$nombreAsignatura]!=null )? $_SESSION[$nombreAsignatura]:'{
					"nombreExamen":"",
					"preguntas":{
					}
				}';
				$preguntasSesion = isset($_SESSION[$nombreAsignatura])? json_decode($_SESSION[$nombreAsignatura],true): null;
				
				$nombreExamen = isset($preguntasSesion['nombreExamen'])? $preguntasSesion['nombreExamen']: null;
				$botonGuardar= "guardarNuevoExamen";
				//var_dump($_SESSION[$nombreAsignatura]."<br>");
			}
			else{
				echo "<h1>Editar examen de ". $_GET["asignatura"]. "</h1>";
			
				$idExamen = isset($_GET['id'])? $_GET['id']: null;

				$examenEntero=getExamen($idExamen);	
				if (!isset($_SESSION[$examenEntero['titulo']])) {
					$preguntasSesion=json_decode($examenEntero['puntosPregunta'],true);
				} else {
					$preguntasSesion=json_decode($_SESSION[$examenEntero['titulo']],true);
				}
				$nombreExamen = $preguntasSesion['nombreExamen'];
				$_SESSION['nombreExamenEditar']=$nombreExamen;
				$_SESSION[$nombreExamen]= json_encode($preguntasSesion);
				$_SESSION['idExamen']=$idExamen;

				$botonGuardar= "guardarModificarExamen";
				//var_dump($_SESSION[$nombreExamen]."<br>");
			}
			
			//array_splice($preguntasSesion['preguntas']['tema1'],0,1);
			//var_dump($preguntasSesion);
			//var_dump("editar: ".$_SESSION['editar'])
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
					echo '<span id="numeradorTotal">';
					$preguntas = isset($preguntasSesion['preguntas'])? $preguntasSesion['preguntas']: null;
					$suma = 0;
					if($preguntas){
						foreach ($preguntas as $tema) {
							foreach ($tema as $preguntasTema) {
								$suma+=$preguntasTema['puntos'];
								
							}
						}
					}
					echo $suma;
					echo '</span><span>/</span>';
					echo '<span id="denominadorTotal">'.$jsonPuntosTema['maximoPuntos'].')</span>'
				?>

			</div>
			<div class="col-2"><button id=<?php echo '"'.$botonGuardar.'"'?> class="btn">Guardar <i class="fas fa-save"></i></button></div>
		
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
					echo'<div class="divTema col-12" id="tema'.$i.'">';
						echo'<span>Tema'.$i.'</span>';
						echo'<span>(</span><span id="numeradorTema'.$i.'">';
						$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
						$sumaTema = 0;
						if ($preguntasTema) {
							foreach ($preguntasTema as $pregunta) {
								$sumaTema += $pregunta['puntos'];	
							}
						}
						echo $sumaTema.'</span><span>/</span><span id="denominadorTema'.$i.'">'.$jsonPuntosTema["tema".$i].'</span><span>)</span>';
						echo '<a class="fas fa-plus-circle" id="boton_aniadirPregunta" tema ="'.$i.'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>';
					echo'</div>';
				echo'</div>';
				echo '<div class="row">';
				echo '<div class="col-12"><hr /></div>';
				echo '</div>';
				echo'<div class="row" id="preguntasTema'.$i.'">';
					if ($preguntasTema) {
						foreach ($preguntasTema as $pregunta) {
							$datos = cargaUnicaPregunta($pregunta['id']);
							echo '<div class="col-12 preguntaTema'.$i.'" puntos="'.$pregunta['puntos'].'"  id="'.$pregunta['id'].'">
									<b>'.$datos['titulo'].'</b> 
									<br>
									'.$datos['cuerpo'].
									'<br>
									<div class="row botonesPregunta">
										<div class="col-1 puntosPregunta" id="puntosPregunta'.$pregunta['id'].'">'.
											'<a class="fas fa-chevron-left" id="menosPuntosPregunta'.$pregunta['id'].'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>
											<span class="puntos"><b>'.$pregunta['puntos'].'</b></span><span> p </span>'.
											'<a class="fas fa-chevron-right" id="masPuntosPregunta'.$pregunta['id'].'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>'.
										'</div>'.
										'<div >'.
											'<a class="fas  fa-times boton-eliminar" pregunta="'.$pregunta['id'].'" id="boton-eliminar" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>'.
										'</div>'.
									'</div>'.
								'</div>';
						}
					}
				echo'</div>';
				echo '<div class="row">';
				echo '<div class="col-12"><hr /></div>';
				echo '</div>';
			}				
		?>
		<div class="row">
			<div class="col-5">

				
			</div>
		</div>

		<div class="modal" id="modal_aniadirPreguntas">
			<div class="modal-dialog modal-lg">
			  <div class="modal-content">
			  
			    <!-- Modal Header -->
			    <div class="modal-header">
			      <h4 class="modal-title">Añadir pregunta</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body -->
			    <div class="modal-body">
					  <form action="#" class="form-container" method="post" id="form_aniadirPregunta">
					    <!--<h1 name="borrarExamen">Añadir preguntas</h1>-->
					    	<div id="info_aniadirPreg_vacio" class="badge badge-pill badge-danger">No hay ninguna pregunta de este tema</div>
					    	<div id="info_aniadirPreg_limite" class="badge badge-pill badge-warning">Se ha alcanzado el límite de puntos para este tema</div>
					    	<div id="info_aniadirPreg_todas" class="badge badge-pill badge-info">Ya están todas las preguntas de este tema añadidas</div>
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
								    <tbody id="table_aniadirPreguntas">
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
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/crearExamen.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script src="js/w3.js"></script>
</body>
</html>

