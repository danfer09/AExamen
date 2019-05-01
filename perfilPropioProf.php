<?php
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

	//Comprobamos los distitos session que controlan los diversos errores, si existen los volcamos en unas variables para que sea mas manejable
	$error_ejecuccionConsulta = isset($_SESSION['error_ejecuccionConsulta'])? $_SESSION['error_ejecuccionConsulta']: false;
	$error_noFilasConCondicion = isset($_SESSION['error_noFilasConCondicion'])? $_SESSION['error_noFilasConCondicion']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	


?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Perfil</title>
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
	<div class="container">
		<h1>Mi perfil</h1>
		<?php 
			//Mostramos los datos del usuario y los botones para que pueda modificar los datos
			echo "<h2> Bienvenido ". $_SESSION['nombre'] . "</h2>";

			//Comprobamos las variables en las que hemos volcado los distintos session de errores y realizamos la accion adecauda para cada error
			if($error_ejecuccionConsulta){
				echo"Error en la ejecución de la consulta";
				$error_ejecuccionConsulta=false;
				$_SESSION['error_ejecuccionConsulta']=false;
			}
			elseif($error_noFilasConCondicion){
				$error_noFilasConCondicion=false;
				$_SESSION['error_noFilasConCondicion'] = false;
				echo '<div class="alert alert-warning">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						    No se ha podido completar la acción correctamente
						 </div>';
			}
			elseif($error_BBDD) {
				echo "Error al conectar con la base de datos";
				$error_BBDD=false;
				$_SESSION['error_BBDD'] = false;
				echo '<div class="alert alert-warning">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						    No se ha podido conectar con la base de datos
						 </div>';
			}
			elseif($error_campoVacio){
				echo "Error campos vacíos";
				$error_campoVacio=false;
				$_SESSION['error_campoVacio'];
				echo '<div class="alert alert-warning">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						    Algún campo está vacío, inténtelo de nuevo
						 </div>';
			}

			echo '<p>Nombre: ' . $_SESSION['nombre']. ' <button type="button" class="btn btn-primary" data-toggle="modal" id="btn_cambiarNombre">Cambiar nombre</button></p>';		
			echo "<p>Apellidos: " . $_SESSION['apellidos']. ' <button type="button" class="btn btn-primary" data-toggle="modal" id="btn_cambiarApellidos">Cambiar apellidos</button></p>';

			echo "<p>correo: " . $_SESSION['email'];
			echo ($_SESSION['administrador']) ?"<button type='button' class='btn btn-primary' data-toggle='modal' id='btn_cambiarCorreo'>Cambiar correo </button></p>" : "</p>";
			echo "<p><button type='button' class='btn btn-primary' data-toggle='modal' id='btn_cambiarClave'>Cambiar contraseña</button></p>";
		?>
	  

		<!-- Modal de Nombre -->
		<div class="modal" id="modal_cambiarNombre">
			<div class="modal-dialog">
			  <div class="modal-content">

			    <!-- Modal Header de Nombre -->
			    <div class="modal-header">
			      <h4 class="modal-title">Cambiar nombre</h4>
			      <button type="button" class="close" data-dismiss="modal">&times;</button>
			    </div>
			    
			    <!-- Modal body de Nombre -->
			    <div class="modal-body">
					  <form action="perfPropProfProcesamiento.php" class="form-container" method="post" id="form_cambiarNombre">

					    <input type="text" placeholder="Introduzca el nombre" name="nombre" id="nombre">

					    <button type="submit" class="btn" id="boton_cambiarNombre" name="boton_cambiarNombre">Cambiar</button>
					  </form>
			    </div>
			    
			    <!-- Modal footer de Nombre -->
			    <div class="modal-footer">
			      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			    </div>
			    
			  </div>
			</div>
		</div>

		<!-- Modal de Apellidos -->
		<div class="modal" id="modal_cambiarApellidos">
		    <div class="modal-dialog">
		      <div class="modal-content">
		      
		        <!-- Modal Header de Apellidos -->
		        <div class="modal-header">
		          <h4 class="modal-title">Cambiar apellidos</h4>
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
		        
		        <!-- Modal body de Apellidos -->
		        <div class="modal-body">
					  <form action="perfPropProfProcesamiento.php" class="form-container" method="post" id="form_cambiarApellidos">

					    <input type="text" placeholder="Introduzca los apellidos" name="apellidos" id="apellidos">

					    <button type="submit" class="btn" id="boton_cambiarApellidos" name="boton_cambiarApellidos">Cambiar</button>
					  </form>
		        </div>
		        
		        <!-- Modal footer de Apellidos -->
		        <div class="modal-footer">
		          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
		        </div>
		        
		      </div>
		    </div>
	  	</div>
	  	
		<!-- Modal de Clave -->
		<div class="modal" id="modal_cambiarClave">
		    <div class="modal-dialog">
		      <div class="modal-content">
		      
		        <!-- Modal Header de Clave -->
		        <div class="modal-header">
		          <h4 class="modal-title">Cambiar contraseña</h4>
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
		        
		        <!-- Modal body de Clave -->
		        <div class="modal-body">
					  <form action="perfPropProfProcesamiento.php" class="form-container" method="post" id="form_cambiarClave">

					    <input type="text" placeholder="Introduzca la clave" name="clave" id="clave">
					    <input type="text" placeholder="Repita la clave" name="repitaClave" id="repitaClave">

					    <button type="submit" class="btn" id="boton_cambiarClave" name="boton_cambiarClave">Cambiar</button>
					  </form>
		        </div>
		        
		        <!-- Modal footer de Clave -->
		        <div class="modal-footer">
		          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
		        </div>
		        
		      </div>
		    </div>
	  	</div>

	  	<!-- Modal de Correo -->
		<div class="modal" id="modal_cambiarCorreo">
		    <div class="modal-dialog">
		      <div class="modal-content">
		      
		        <!-- Modal Header de Correo -->
		        <div class="modal-header">
		          <h4 class="modal-title">Cambiar correo</h4>
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
		        <span id='mensajeEditarCorreo'></span><br>
		        <!-- Modal body de Correo -->
		        <div class="modal-body">
					  <form action="perfPropProfProcesamiento.php" class="form-container" method="post" id="form_cambiarCorreo">

					    <input type="email" placeholder="Introduzca el nuevo correo" name="correo" id="correo">

					    <input type="submit" class="btn" id="boton_cambiarCorreo" name="boton_cambiarCorreo" value="Cambiar">
					  </form>
		        </div>
		        
		        <!-- Modal footer de Correo -->
		        <div class="modal-footer">
		          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
		        </div>
		        
		      </div>
		    </div>
	  	</div>
	</div>

	<!--Librerias externas-->
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/w3.js"></script>

	<!--Javascripts propios-->
	<script type="text/javascript" src="js/perfilPropioProf.js"></script>
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>
</body>
</html>