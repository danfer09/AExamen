<?php
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Generar Examen</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Generar examen</h1>
		<?php

			echo "<h2> Examen: ". $_GET['examen'] . "</h2>";
			echo "<h2> Parámetros: </h2>";


		?>

	  	<!-- Formulario para definir las preferencias al generar un examen -->
	  	<form class="form-horizontal" action="/examenes/generar_un_examen" method="post" id="form-generar">
	  		<div class="form-group">
				<h4><label class="control-label" for="espaciado">Espaciado para responder a las preguntas: </label></h4>
				<select class="form-control" name="espaciado" id="espaciado" >
					<?php

						if ($parametrosDefecto['espaciado_defecto'] == 2) {
							echo '<option value="2" selected>Pequeño</option>';
						} else {
							echo '<option value="2">Pequeño</option>';
						}

						if ($parametrosDefecto['espaciado_defecto'] == 10) {
							echo '<option value="10" selected>Mediano</option>';
						} else {
							echo '<option value="10">Mediano</option>';
						}

						if ($parametrosDefecto['espaciado_defecto'] == 100) {
							echo '<option value="100" selected>Página completa</option>';
						} else {
							echo '<option value="100">Página completa</option>';
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<h4><label class="control-label" for="cuatrimestre">Cuatrimestre:*</label></h4>
				<select class="form-control" name="cuatrimestre" id="cuatrimestre">
					<option value="0" selected disabled></option>
					<option value="1">Primer cuatrimestre</option>
					<option value="2">Segundo cuatrimestre</option>
				</select>
			</div>
			<div class="form-group">
				<h4>Editar cabecera:</h4>
				<label class="control-label" for="pautas">Pautas y consejos para el examen:</label>
				<textarea class="form-control" name="pautas" id="pautas"><?php echo $parametrosDefecto['asignaturas']['texto_inicial'];	?> </textarea>

				<label class="control-label" for="cuatrimestre">Escudo:</label>
				<select class="form-control" name="escudoFacultad" id="escudoFacultad" >
					<option selected disabled value=0>Escudo</option>
				<?php
					$facultadesStr = file_get_contents('json/facultades.json');
					$facultades = json_decode($facultadesStr, true);
					echo '<option value="'.$facultades['universidad'].'">Universidad Complutense de Madrid</option>';
					foreach ($facultades['facultades'] as $row) {
						foreach ($row as $key => $value) {
							echo '<option value="'.$value.'">'.$key.'</option>';
						}
					}
				?>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label" for="grupo">Grupo:</label>
				<select class="form-control col-lg-2" name="grupo" id="grupo" >
					<option selected disabled value="0">Grupo</option>
					<option value="1º">1º</option>
					<option value="2º">2º</option>
					<option value="3º">3º</option>
					<option value="4º">4º</option>
					<option value="5º">5º</option>
				</select>
				<label class="control-label" for="letra">Letra:</label>
				<select class="form-control col-lg-2" name="letra" id="letra" >
					<option selected disabled value="0">Letra</option>
					<option value="A">A</option>
					<option value="B">B</option>
					<option value="C">C</option>
					<option value="D">D</option>
					<option value="E">E</option>
					<option value="F">F</option>
					<option value="G">G</option>
					<option value="H">H</option>
				</select>
				<br>
				<label class="control-label" for="dni">Campo DNI:</label>
			    <input type="checkbox" checked=true id="dni" name="dni" value="Yes">
			</div>
			<div class="form-group">
				<label class="control-label" for="datetimepicker4">Fecha del examen:</label>
                <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                    <input type="text" id="fecha" name="fecha" class="form-control datetimepicker-input col-lg-2" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                    <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>

			<button type="submit" id="botonGenerar" disabled class="btn btn-primary">Generar</button>
		</form>
	</div>


  <?php
    echo $this->Html->script('generarExamen');
  ?>
</body>
</html>
