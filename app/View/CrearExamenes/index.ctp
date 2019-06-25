<html>
<head>
	<title>AExamen Crear Examen</title>

	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container" id="crearExamenContainer">
		<?php

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
        $nombreExamen = '';
				$botonGuardar= "guardarNuevoExamen";

			}
			else{
				echo "<h1>Editar examen de ". $_GET["asignatura"]. "</h1>";

				$_SESSION["editarExamenCambios"] = (isset($_SESSION["editarExamenCambios"]))? $_SESSION["editarExamenCambios"]:'{}';

				$botonGuardar= "guardarModificarExamen";
			}


		?>

		<br>
		<div class="row">
			<div class="col-1">
				<span id="openNav"><i class="fas fa-bars"></i></span>
				<div id="mySidenav" class="sidenav">
				  <?php
				  for ($i = 1; $i <= $numTemas; $i++) {
				  	echo '<a href="#">Tema'.$i.'</a>';
				  }
				  ?>
          <br>
          <a href="javascript:void(0)" class="closebtn" id="closeNav">&times;</a>
				</div>
			</div>
			<div class="col-7"><input siglas="<?php echo $_GET["asignatura"]; ?>" id="nombreExamen" class="w3-input col-8" placeholder=<?php echo '"Nombre del examen... e.g.  '.$_GET["asignatura"].' [Parcial/Final] [Año]" value="'.$nombreExamen.'"'?>>

			</div>
			<div id="containerPuntosTotal" class="col-2">
				<?php
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
			<div class="col-2"><button id="<?php echo $botonGuardar?>" class="btn">Guardar <i class="fas fa-save"></i></button></div>

		</div>
		<br>
		<?php
		  for ($i = 1; $i <= $numTemas; $i++) {
			    echo '<div class="row">';
					echo'<div class="divTema col-12" id="tema'.$i.'">';
						echo'<span>Tema'.$i.'</span>';
						echo'<span> (</span><span id="numeradorTema'.$i.'">';
						$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
						$sumaTema = 0;
						if ($preguntasTema) {
							foreach ($preguntasTema as $pregunta) {
								$sumaTema += $pregunta['puntos'];
							}
						}
						echo $sumaTema.'</span><span>/</span><span id="denominadorTema'.$i.'">'.$jsonPuntosTema["tema".$i].'</span><span>) </span>';
						echo '<a class="fas fa-plus-circle" id="boton_aniadirPregunta" tema ="'.$i.'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>';
					echo'</div>';
				echo'</div>';
				echo '<div class="row">';
				echo '<div class="col-2"><hr class="separador-dos" /></div>';
        echo '<div class="col-10"></div>';
				echo '</div>';
				echo'<div class="row" id="preguntasTema'.$i.'">';
					if ($preguntasTema) {
						foreach ($preguntasTema as $pregunta) {
              $datos = $datosPreguntasSesion["tema".$i][$pregunta['id']];
							//$datos = cargaUnicaPregunta($pregunta['id']);
							echo '<div class="col-12 preguntaTema'.$i.'" puntos="'.$pregunta['puntos'].'"  id="'.$pregunta['id'].'">
									<b><span class="tituloPreguntas">'.$datos['titulo'].'</span></b>
									<br>
									<span class="cuerpoPreguntas">'.$datos['cuerpo'].
									'</span><br>
									<div class="row botonesPregunta">
										<div class="col-1 puntosPregunta" id="puntosPregunta'.$pregunta['id'].'">'.
											'<a class="fas fa-chevron-left" id="menosPuntosPregunta'.$pregunta['id'].'" asignatura= "'.$_GET["idAsignatura"].'"href="#"></a>
											<span class="puntos">'.$pregunta['puntos'].'</span><span> p </span>'.
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
				echo '<div class="col-12"><hr class="separador-uno" /></div>';
				echo '</div>';
			}
		?>

		<!-- Formulario modal para añadir preguntas -->
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

			  </div>
			</div>
		</div>


    </div>

  <!--Javascripts propios-->
  <?php
    echo $this->Html->script('crearExamen');
  ?>
</body>
</html>
