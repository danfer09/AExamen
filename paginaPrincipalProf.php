<html>
<head>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/slick-team-slider.css" />
  	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="header" id="header"></div>
	<div class="container">
		<?php
			/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/

			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
			$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if($_SESSION['administrador']){
				echo '<h1>Pagina principal del administrador</h1>';
			} else {
				echo '<h1>Pagina principal del profesor</h1>';
			}

			
			if ($logeado) {
				echo "<h2> Bienvenido ". $_SESSION['nombre']. "</h2>";
			}
			else{
				header('Location: index.php');
			}


		?>
		<!--<a href="perfilPropioProf.php"> Editar perfil </a>-->
		<div id="portfolio">
		    <div class="container">

		      <div class="row" id="portfolio-wrapper">
		<?php
		if($_SESSION['administrador']){
			
			?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
		          <h3>ASIGNATURAS</h3>
		          <a href="gestionarAsigAdmin.php">
		            <img src="img/asignaturas-book.png" alt="">
		            <h3>ASIGNATURAS</h3>
		            <div class="details">
		              <h4>ASIGNATURAS</h4>
		              <span>Gestionar asignaturas</span>
		            </div>
		          </a>
		        </div>

		        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
		          <h3>PROFESORES</h3>
		          <a href="profesoresAdmin.php">
		            <img src="img/profesores-users.png" alt="">
		            <h3>PROFESORES</h3>
		            <div class="details">
		              <h4>PROFESORES</h4>
		              <span>Gestionar profesores</span>
		            </div>
		          </a>
		        </div>

		        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
		          <h3>PANEL DE CONTROL</h3>
		          <a href="panelControl.php">
		            <img src="img/panel-control.png" alt="">
		            <h3>PANEL DE CONTROL</h3>
		            <div class="details">
		              <h4>PANEL DE CONTROL</h4>
		              <span>Ir al panel de control</span>
		            </div>
		          </a>
		        </div>
		<?php	
		}
		else{
		?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
				  <h3>ASIGNATURAS</h3>
		          <a href="asignaturasProfesor.php">
		            <img src="img/asignaturas-book.png" alt="">
		            <h3>ASIGNATURAS</h3>
		            <div class="details">
		              <h4>ASIGNATURAS</h4>
		              <span>Ver mis asignaturas</span>
		            </div>
		          </a>
		        </div>

		        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-web">
		          <h3>EXÁMENES</h3>
		          <a href="examenes.php?asignatura=todas&autor=todos">
		            <img src="img/examenes-document.png" alt="">
		            <h3>EXÁMENES</h3>
		            <div class="details">
		              <h4>EXÁMENES</h4>
		              <span>Ver los exámenes</span>
		            </div>
		          </a>
		        </div>
		<?php } ?>
		      </div>
		    </div>
		  </div>

		<!--<a href="cerrarSesion.php">Salir</a>-->


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

</body>
</html>