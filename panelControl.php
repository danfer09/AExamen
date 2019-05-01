<html>
<head>
	<title>AExamen Panel de Control</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/w3.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container" id="container-panel-control">
		<h1>Panel de control</h1>

		<?php
			/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			
			/*Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.*/
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
		?>

		<div class="row filaPanelControl">
			<div class="col-lg-12">
				<h2><b>Log</b></h2>
			</div>
			<div class="col-lg-6">
				<h3>Últ. vez modificado: <u><?php echo formateoDateTime(date("Y-m-d H:i:s", filemtime('./log/log_AExamen.log'))); ?></u></h3>
			</div>
			<div class="col-lg-6">
				<h3>Últ. vez eliminado: <u><?php echo formateoDateTime(date("Y-m-d H:i:s", filectime('./log/log_AExamen.log'))); ?></u></h3>
			</div>
			<br><br><br>
			<div class="col-lg-4 centrar">
				<a href="downloadLog.php?file=log_AExamen.log"><button class="btn btn-success" id="descargarLog"><i class="fas fa-download"></i> Descargar .log (<?php echo round(floatval(filesize('./log/log_AExamen.log')/(1024)),3).' KB'; ?>)</button></a>
				<a target="_blank" href="./log/log_AExamen.log"><button class="btn btn-success"><i class="fas fa-eye"></i> Ver</button></a>
			</div>
			<div class="col-lg-4 centrar">
				<button class="btn" id="reiniciarLog"><i class="fas fa-redo-alt"></i> Reiniciar</button>
			</div>
			<div class="col-lg-4 centrar">
				<button class="btn btn-danger" id="eliminarLog"><i class="fas fa-trash-alt"></i> Eliminar</button>
			</div>
		</div>

		<br><hr>

		<div class="row filaPeticionesRegistro">
			<div class="col-lg-10">
				<h2><b>Peticiones de registro</b></h2>
			</div>
			<div class="col-lg-2 centrar" id="recargarPanelControl">
				<a class="fas fa-sync-alt" onclick="location.reload();" id="recargaPeticiones"></a>
			</div>
			<hr>
			<!-- PETICIONES -->
			<?php
				$peticiones = getPeticiones();
				if ($peticiones == null) {
					echo 'No hay peticiones.';
				} else {
					$i=0;
					foreach ($peticiones as $peticion) {
						if ($i%2 == 0) {
							echo '<div class="col-lg-12 row peticionPanelControlPar" idPeticion="'.$peticion['id'].'">';
						} else {
							echo '<div class="col-lg-12 row peticionPanelControlImpar" id="peticion'.$peticion['id'].'" >';
						}
						echo '
							<div class="col-4">'.$peticion['nombre'].' '.$peticion['apellidos'].'</div>
							<div class="col-4">'.$peticion['email'].'</div>
							<div class="col-2"><button fechaPeticion="'.formateoDateTime($peticion['fecha']).'" idPeticion="'.$peticion['id'].'" type="button" class="btn btn-info masInfo" data-toggle="modal" data-target="#infoPeticion"><i class="fas fa-info"></i></button></div>
							<div class="opciones" class="col-2 derecha" >
								<button aceptar="1" idPeticion="'.$peticion['id'].'" class="btn btn-success"><i class="fas fa-check"></i></button>
								<button aceptar="0" idPeticion="'.$peticion['id'].'" class="btn btn-danger"><i class="fas fa-times"></i></button>
							</div>
						</div>
						<br>';
						$i++;
					}
				}
			?>

			<!-- Modal -->
			<div id="infoPeticion" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			      	<h4 class="modal-title">Información de petición</h4>
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
			      <div class="modal-body centrar" >
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

		</div>

	</div>



	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
	<script type="text/javascript" src="js/panelControl.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>

