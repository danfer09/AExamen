
<?php
	//Comprobamos si el usuario esta logeado
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*En caso de no este logeado redirigimos a index.php*/
	if (!$logeado) {
		header('Location: index.php');
	}
	include "definirParametrosExamProcesamiento.php";
?>

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
<div class="header" id="header"></div>
<div class="container">
<?php
	echo'<h1 idAsig="'.$_GET['idAsig'].'" >Pagina de coordinador</h1>';
	$idAsig=$_GET['idAsig'];
	$paramExam = selectParametrosAsig($idAsig);
	$puntosTema = json_decode($paramExam['puntos_tema'], true);
	

	var_dump($_SESSION['pruebaParam']);

	var_dump($_SESSION['pruebaParam2']);


?>
		<form class="form-container" method="post" id="formParametros">
			<div class="row">
				<div class="form-group col-11"></div>
				<div class="form-group col-1">
					<button type="submit" class="btn btn-primary" id="botonGuardar">Guardar</button>
				</div>
			</div>
			<div class="panel-group">
				<!--Formulario para meter puntos por tema-->
				<div class="panel panel-primary">
					<div class="panel-heading"><h5>Puntos por tema</h5></div>
					<span id='mensajePuntosPorTema'></span><br>
					<div class="panel-body">
						<div class="row">
							<?php
							foreach ($puntosTema as $pos => $valor) {
								if($pos=="numeroTemas"){
									echo'<div class="form-group col-4">';
									  echo'<label>Numero total de temas:</label>';
									  echo'<input type="number" class="form-control" id="'.$pos.'" value="'.$valor.'">';
									echo'</div>';
								}
								else if($pos=="maximoPuntos"){
									echo'<div class="form-group col-4">';
									  echo'<label>Puntos por exámen:</label>';
									  echo'<input type="number" class="form-control" id="'.$pos.'" value="'.$valor.'">';
									echo'</div>';
								}
								else{
									echo'<div class="form-group col-4">';
									  echo'<label>Tema '.$pos[4].':</label>';
									  echo'<input type="number" class="form-control puntosTemaForm" id="'.$pos.'" value="'.$valor.'">';
									echo'</div>';
								}
							}
							?>
						</div>
					</div>
				</div>
				<br>
				<!-- Formulario para definir cabecera del examen-->
				<div class="panel panel-primary">
					<div class="panel-heading"><h5>Cabecera de exámen</h5></div>
				  <div class="panel-body">
						<div class="row">
							<div class="form-group col-6">
							  <div class="custom-file">
							  	<label>Logo de la Facultad</label>
							     <select class="form-control" id="exampleFormControlSelect1">
							      <option>Universidad Complutense</option>
							      <option>Facultad de Informática</option>
							    </select>
							  </div>
							</div>
							<div class="form-group col-6">
								<div class="form-group">
							    <label>Cuatrimestre</label>
							    <select class="form-control" id="exampleFormControlSelect1">
							      <option>Primer Cuatrimetre</option>
							      <option>Segundo Cuatrimestre</option>
							    </select>
							  </div>
						  </div>
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
								  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
								  <label class="form-check-label" for="exampleRadios1">
								    Pequeño
								  </label>
								</div>
							</div>
							<div class="form-group col-4">
								<div class="form-check">
								  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1">
								  <label class="form-check-label" for="exampleRadios1">
								    Medio
								  </label>
								</div>
							</div>
							<div class="form-group col-4">
								<div class="form-check">
								  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1">
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
							    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"placeholder="Escribe aquí"></textarea>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

<!--Librerias externas-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/w3.js"></script>

<!--Javascripts propios-->
<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
<script type="text/javascript" src="js/definirParametrosExam.js"></script>

</body>
</html>