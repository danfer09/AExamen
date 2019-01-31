
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
<h1>Pagina de coordinador</h1>
	<?php
		/*Incluimos asignaturasProfesorProcesamiento.php donde tenemos implementadas
		algunas funciones que usaremos más adelante*/
		include 'asignaturasProfesorProcesamiento.php'; 
		echo "<h2> Asignaturas de ". $_SESSION['nombre']. "</h2>";
	?>
	<div class="border border-secondary">
		<form>
			<div class="row">
				<div class="form-group col-4">
				    <label>Tema 1:</label>
				    <input type="number" class="form-control" id="exampleInputEmail1">
				</div>
				<div class="form-group col-4">
				    <label>Tema 2:</label>
				    <input type="number" class="form-control" id="exampleInputEmail1">
				</div>
				<div class="form-group col-4">
				    <label>Tema 3:</label>
				    <input type="number" class="form-control" id="exampleInputEmail1">
				</div>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
		
</div>

<!--Librerias externas-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/w3.js"></script>

<!--Javascripts propios-->
<script type="text/javascript" src="js/asignaturasProfesor.js"></script>
<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>

