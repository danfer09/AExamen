<html>
<head>
	<title>AExamen Parámetros</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
<div class="header" id="header"></div>
<div class="container">
<?php
	echo'<h1 class="idAsignatura" idAsig="'.$idAsignatura.'" >Parámetros de '.$nombreAsignatura.'</h1>';
?>
		<form class="form-container" method="post" id="formParametros">
			<div class="row">
				<div class="form-group col-10" id="message"></div>
				<div class="form-group col-2">
					<button type="button" class="btn btn-info" id="botonRestablecer">Restablecer valores</button>
				</div>
			</div>
			<div class="panel-group">
				<!--Formulario para meter puntos por tema-->
				<div class="panel panel-primary">
					<div class="panel-heading"><h5>Puntos por tema</h5></div>
					<span id='mensajePuntosPorTema'></span><br>
					<div class="panel-body">
						<div id="filaPuntosPorTema" class="row">
							<?php
							foreach ($puntosTema as $pos => $valor) {
								if($pos=="numeroTemas"){
									echo'<div class="form-group col-4">';
									  echo'<label>Numero total de temas:</label>';
									  echo'<input type="number" class="form-control numTemasForm" id="'.$pos.'" value="'.$valor.'" min="0">';
									echo'</div>';
								}
								else if($pos=="maximoPuntos"){
									echo'<div class="form-group col-4">';
									  echo'<label>Puntos por exámen:</label>';
									  echo'<input type="number" class="form-control puntosExamenTotal" id="'.$pos.'" value="'.$valor.'" min="0">';
									echo'</div>';
								}
								else{
									echo'<div id="div_'.$pos.'" class="form-group col-4">';
									  echo'<label>Tema '.substr($pos,4).':</label>';
									  echo'<input type="number" class="form-control puntosTemaForm" id="'.$pos.'" value="'.$valor.'" min="0">';
									echo'</div>';
								}
							}
							?>
						</div>
					</div>
				</div>
				<br>
				<!-- Formulario para definir espacio entre preguntas-->
				<div class="panel panel-primary">
					<div class="panel-heading"><h5>Espaciado entre preguntas por defecto</h5></div>
				  <div class="panel-body">
						<div class="row">
							<div class="form-group col-4">
								<div class="form-check">
								  <input class="form-check-input espaciado" type="radio" name="exampleRadios" id="exampleRadios1" value="pequenio"
								  <?php if($espaciado == 2) echo("checked") ?>>
								  <label class="form-check-label" for="exampleRadios1">
								    Pequeño
								  </label>
								</div>
							</div>
							<div class="form-group col-4">
								<div class="form-check">
								  <input class="form-check-input espaciado" type="radio" name="exampleRadios" id="exampleRadios1" value="medio" <?php if($espaciado == 10) echo("checked") ?>>
								  <label class="form-check-label" for="exampleRadios1">
								    Medio
								  </label>
								</div>
							</div>
							<div class="form-group col-4">
								<div class="form-check">
								  <input class="form-check-input espaciado" type="radio" name="exampleRadios" id="exampleRadios1" value="grande"<?php if($espaciado == 15) echo("checked") ?>>
								  <label class="form-check-label" for="exampleRadios1">
								    Grande
								  </label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<!-- Formulario para definir texto de cabecera-->
				<div class="panel panel-primary">
					<div class="panel-heading"><h5>Texto breve inicial(consejos, normas, etc.)</h5></div>
				  <div class="panel-body">
						<div class="row">
							<div class="form-group col-12">
								<div class="form-group">
							    <textarea class="form-control" id="textoInicialForm" rows="3"placeholder="Escribe aquí" ><?php echo($textoInicial)?></textarea>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-11"></div>
				<div class="form-group col-1">
					<button type="submit" class="btn btn-primary" id="botonGuardar">Guardar</button>
				</div>
			</div>
		</form>
	</div>

  <?php
    echo $this->Html->script('definirParametrosExam');
  ?>
</body>
</html>
