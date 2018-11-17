<?php
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	

	if($error_BBDD) {
		echo "Error al conectar con la base de datos";
		$error_BBDD=false;
	}
	elseif($error_campoVacio){
		echo "Error campos vacíos";
		$error_campoVacio=false;
	}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Generar examen</h1>
		<?php 
			session_start();
			include 'generarExamenProcesamiento.php';

			$_GET['examen'] = "IS Parcial 2017";
			echo "<h2> Examen: ". $_GET['examen'] . "</h2>";
			echo "<h2> Parámetros: </h2>";
		?>
	  	
	  	<form class="form-horizontal" action="generarExamenProcesamiento.php">
	  		<div class="form-group">
				<h4><label class="control-label" for="espaciado">Espaciado para responder a las preguntas: </label></h4>
				<select class="form-control" id="espaciado" >
					<?php
						$parametrosDefecto = getDefaultParameters($_GET['examen']);
						
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
				<h4><label class="control-label" for="cuatrimestre">Cuatrimestre:</label></h4>
				<select class="form-control" id="cuatrimestre">
					<option value="0" selected disabled>Cuatrimestre</option>
					<option value="1">Primer cuatrimestre</option>
					<option value="2">Segundo cuatrimestre</option>
				</select>
			</div>
			<div class="form-group">
				<h4>Editar cabecera:</h4>
				<label class="control-label" for="pautas">Pautas y consejos para el examen:</label>
				<textarea class="form-control" id="pautas"><?php echo $parametrosDefecto['texto_inicial'];	?></textarea>
		
				<label class="control-label" for="cuatrimestre">Escudo:</label>
				<select class="form-control" id="escudoFacultad" >
					<option selected disabled value="0">Escudo</option>
				<?php
					$facultadesStr = file_get_contents('facultades.json');
					$facultades = json_decode($facultadesStr, true);
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
				<select class="form-control col-lg-2" id="grupo" >
					<option selected disabled value="0">Grupo</option>
					<option value="1">1º</option>
					<option value="2">2º</option>
					<option value="3">3º</option>
					<option value="4">4º</option>
					<option value="5">5º</option>
				</select>
				<label class="control-label" for="letra">Letra:</label>
				<select class="form-control col-lg-2" id="letra" >
					<option selected disabled value="0">Letra</option>
					<option value="A">A</option>
					<option value="A">B</option>
					<option value="A">C</option>
					<option value="A">D</option>
					<option value="A">E</option>
					<option value="A">F</option>
					<option value="A">G</option>
					<option value="A">H</option>
				</select>
				<label class="control-label" for="dni">Campo DNI</label>
			    <input type="checkbox" value="dni">
			</div>

			<button type="submit" class="btn btn-primary">Generar</button>
		</form>
	</div>


	
	<script src="jquery-3.3.1.min.js"></script>
	<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="formularioNombre.js"></script>
	
	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://www.w3schools.com/lib/w3.js"></script>
	<script type="text/javascript" src="cabeceraConLogin.js"></script>


	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
	


</body>
</html>