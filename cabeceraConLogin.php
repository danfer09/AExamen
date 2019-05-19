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
?>
<head>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/cabeceraLogin.css">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<header id="header">
	<nav class="links" style="--items:1;">
		<div class="row">
			<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1"></div>
			<!-- Boton de home para acceder a la página principal-->
			<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1">
				<a href="paginaPrincipalProf.php" class="btn btn-primary" id="buttonHome"><i class="fas fa-home fa-2x"></i></a>
			</div>
			<div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"></div>
			<!-- Nombre de la pagina clicable para acceder a la pagina principal-->
			<div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
				<a id="logoCentral" href="paginaPrincipalProf.php"><h1>AExamen!</h1></a>
			</div>
			<div class="col-2 col-sm-1 col-md-2 col-lg-2 col-xl-2"></div>
			<!-- Botones para ir al perfil propio y para cerrar sesion-->
			<div class="col-3 col-sm-4 col-md-3 col-lg-3 col-xl-3">
				<a class="btn btn-primary" href="perfilPropioProf.php"><i class="fas fa-user-circle fa-2x"></i></a>
				<a class="btn btn-primary" href="cerrarSesion.php" role="button"><i class="fas fa-sign-out-alt fa-2x"></i></a>
			</div>
		</div>
	</nav>
</header>
