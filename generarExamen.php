<?php
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

	//Comprobamos los distitos session que controlan los diversos errores, si existen los volcamos en unas variables para que sea mas manejable
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_generar = isset($_SESSION['error_no_existen_preguntas'])? $_SESSION['error_no_existen_preguntas']: false;

	if($error_BBDD) {
		echo "Error al conectar con la base de datos";
		$error_BBDD=false;
	}
	else if($error_campoVacio){
		echo "Error campos vacíos";
		$error_campoVacio=false;
	}
	else if ($error_generar) {
		echo "Este examen no tiene preguntas asignadas";
		$error_generar = false;
	}
		
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Generar Examen</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/tempusdominus-bootstrap-4.min.css">

	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
		<h1>Generar examen</h1>
		<?php 
			include 'generarExamenProcesamiento.php';

			echo "<h2> Examen: ". $_GET['examen'] . "</h2>";
			echo "<h2> Parámetros: </h2>";

			$parametrosDefecto = getDefaultParameters($_GET['examen']);
			$_SESSION['asignaturaExamenGenerado'] = $parametrosDefecto['nombre'];
			$_SESSION['nombreExamenGenerado'] = $_GET['examen'];
			$_SESSION['idExamenGenerado'] = $parametrosDefecto['idExamen'];
		?>
	  	
	  	<!-- Formulario para definir las preferencias al generar un examen -->
	  	<form class="form-horizontal" action="generarExamenProcesamiento.php" method="post" id="form-generar">
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
				<textarea class="form-control" name="pautas" id="pautas"><?php echo $parametrosDefecto['texto_inicial'];	?> </textarea>
		
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


	
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/es.js"></script>
	<script type="text/javascript" src="js/tempusdominus-bootstrap-4.min.js"></script>
	
	
	<script type="text/javascript" src="js/generarExamen.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
</body>
</html>