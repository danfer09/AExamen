<html>
<head>
	<title>AExamen Asignatura</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
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
			if (!$logeado) {
				header('Location: index.php');
			}
			//Obtenemos los parametros que nos pasan por GET
			// $idAsignatura=$_GET['id'];
			// $idProfesor=$_SESSION['id'];
			$nombreAsig=$_GET["nombre"];

			echo '<h1>Asignatura: '. $_GET["nombre"]. '</h1>';
			//Mostramos los dos botones principales
			?>
			<div id="portfolio">
			    <div class="container">

			      <div class="row" id="portfolio-wrapper">
			      	<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>PREGUNTAS</h3>
			          <a <?php echo 'href="preguntas.php?nombreAsignatura='.$_GET["nombre"].'&idAsignatura='.$_GET["id"].'&autor=todos"' ?>>
			            <img src="/img/question-solid.png" alt="">
			            <h3>PREGUNTAS</h3>
			            <div class="details">
			              <h4>PREGUNTAS</h4>
			              <span>Ver preguntas</span>
			            </div>
			          </a>
			        </div>
			      	<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>EXÁMENES</h3>
			          <a <?php echo 'href="/examenes/index?asignatura='.$_GET['siglas'].'&autor=todos"' ?>>
			            <img src="/img/examenes-document.png" alt="">
			            <h3>EXÁMENES</h3>
			            <div class="details">
			              <h4>EXÁMENES</h4>
			              <span>Ver exámenes</span>
			            </div>
			          </a>
			        </div>
			<?php
			//Si es coordinador mostramos las demás opciones
			if($esCoordinador){
				?>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>PROFESORES</h3>
			          <a <?php echo 'href="profesoresDeUnaAsig.php?idAsig='.$_GET['id'].'&nombreAsig='.$nombreAsig.'"' ?>>
			            <img src="/img/profesores-users.png" alt="">
			            <h3>PROFESORES</h3>
			            <div class="details">
			              <h4>PROFESORES</h4>
			              <span>Gestionar profesores</span>
			            </div>
			          </a>
			        </div>

			        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-3 portfolio-item filter-app">
			          <h3>PARÁMETROS</h3>
			          <a <?php echo 'href="definirParametrosExam.php?idAsig='.$_GET['id'].'"' ?>>
			            <img src="/img/parametros-examen.png" alt="">
			            <h3>PARÁMETROS</h3>
			            <div class="details">
			              <h4>PARÁMETROS DE EXAMEN</h4>
			              <span>Definir parámetros de exámenes</span>
			            </div>
			          </a>
			        </div>
			<?php
			}
			?>
			      </div>
			    </div>
			  </div>
	</div>
</body>
</html>
