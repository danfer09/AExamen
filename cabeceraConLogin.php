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
<div class="row">
	<div class="col-5"></div>
	<div class="col-2">
		<a id="logoCentral" href="paginaPrincipalProf.php"><h1>AExamen!</h1></a>
	</div>
	<div class="col-3"></div>
	<div class="col-2">
		<a class="btn btn-primary" href="perfilPropioProf.php"><i class="fas fa-user-circle fa-2x"></i></a>
		<a class="btn btn-primary" href="cerrarSesion.php" role="button">CerrarSesion</a>
	</div>
</div>
