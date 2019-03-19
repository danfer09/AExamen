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
		<h1>Panel de control</h1>
		
		<?php 
			/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			//var_dump($_SESSION['error1']);
			//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
			$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
			/*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
			if (!$logeado) {
				header('Location: index.php');
			}
			
			
			if (!$_SESSION['administrador']){
				header('Location: index.php');
			}
			
			include 'funcionesServidor.php';
			include 'panelControlProcesamiento.php';

			/*echo "<br>prueba: ".$_SESSION['prueba'];
			$_SESSION['prueba'] = "inicializadoooooo";

			echo "<br>prueba1: ";
			print_r($_SESSION['prueba1']);
			//var_dump($_SESSION['prueba1']);
			$_SESSION['prueba1'] = "inicializadoo11111";

			echo "<br>prueba2: ".$_SESSION['prueba2'];
			$_SESSION['prueba2'] = "inicializadooo222";*/
		?>

		<div>
			<h2>Log</h2>
		</div>

		<br><hr><br>

		<div class="row" style="border-width: 1px; border-style: solid; border-color: black; padding: 5px;">
			<div class="col-lg-10">
				<h2>Peticiones de registro</h2>
			</div>
			<div class="col-lg-2" style="padding-top: 1%; text-align: center;">
				<a class="fas fa-sync-alt" onclick="location.reload()" style="text-decoration: none; cursor: pointer;" id="recargaPeticiones"></a>
			</div>
			<hr>
			<!-- PETICIONES -->
			<?php
				$peticiones = getPeticiones();
				if ($peticiones == null) {
					echo 'No hay peticiones.';
				} else {
					foreach ($peticiones as $peticion) {
						if ($peticion['id']%2 == 0) {
							echo '<div class="col-lg-12 row" idPeticion="'.$peticion['id'].'" style="padding-top: 5px; padding-bottom: 5px; margin-top: 5px; margin-left: 0px; background-color: lightgrey;">';
						} else {
							echo '<div class="col-lg-12 row" id="peticion'.$peticion['id'].'" style="padding-top: 5px; padding-bottom: 5px; margin-top: 5px; margin-left: 0px;">';
						}
						echo '
							<div class="col-4">'.$peticion['nombre'].' '.$peticion['apellidos'].'</div>
							<div class="col-4">'.$peticion['email'].'</div>
							<div class="col-2"><button idPeticion="'.$peticion['id'].'" type="button" class="btn btn-info masInfo" data-toggle="modal" data-target="#infoPeticion">Más info.</button></div>
							<div class="col-2" style="text-align: right;">
								<button class="btn btn-success"><i class="fas fa-check"></i></button>
								<button class="btn btn-danger"><i class="fas fa-times"></i></button>
							</div>
						</div>
						<br>';
					}
				}
			?>

			<!-- Modal <i class="fas fa-info-clock"></i>'.formateoDateTime($peticion['fecha']).' -->
			<div id="infoPeticion" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			      	<h4 class="modal-title">Información de petición</h4>
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
			      <div class="modal-body" style="text-align: center;">
			        <div id="modalFecha">
			        	
			        </div>
			        <br>
			        <div id="modalTexto">
			        	
			        </div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
			    </div>

			  </div>
			</div>
			<!--<div class="col-lg-12 row" id="peticion5" style="padding-top: 5px; padding-bottom: 5px; margin-top: 5px; margin-left: 0px; background-color: lightgrey;">
				<div class="col-4">Andrés García Rubio</div>
				<div class="col-4">agarrub@gmail.com</div>
				<div class="col-2"><i class="fas fa-info-clock"></i></div>
				<div class="col-2" style="text-align: right;">
					<button class="btn btn-success"><i class="fas fa-check"></i></button>
					<button class="btn btn-danger"><i class="fas fa-times"></i></button>
				</div>
			</div>
			<br>-->
		</div>

	</div>

	

	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script type="text/javascript" src="js/panelControl.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>

