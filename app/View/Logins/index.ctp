<?php
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	// if(isset($success)&& $success){
	// 	debug("entra vista");
	// }


	/*Volcamos a variables los session de control de errores que se inicializan en loginProcesamiento.php */
	$error_campoVacio = isset($_SESSION['error_campoVacio'])? $_SESSION['error_campoVacio']: false;
	$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
	$error_autenticar = isset($_SESSION['error_autenticar'])? $_SESSION['error_autenticar']: false;


	/*Volcamos a una variable el valor de la session logeado. Si vale true es que ya estamos logeados, en caso contrario es que no estamos logeados*/
	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
	/*Si estamos logeados redirigimos a paginaPrincipaProf.php*/
	if($logeado){
		header('Location: paginaPrincipalProf.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>AExamen Login</title>
	<!--css propio -->
	<link rel="stylesheet" type="text/css" href="css/loginFormulario.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	<!--css externos-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
  <div id="service" class="section-padding">
    <div class="container">

        <div class="page-title text-center">
          <h1>Login</h1>

          <?php
          	/*Comprobamos las variables donde hemos volcado los session y realizamos las acciones que correspondan*/
			if($error_campoVacio){
				echo '<div class="alert alert-danger alert_login" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  		Error, ha dejado campos vacíos
					  </div>';
				$_SESSION['error_campoVacio']=false;
			}
			elseif($error_BBDD) {
				echo '<div class="alert alert-danger alert_login" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  		Error al conectar con la base de datos, contacte con el administrador
					  </div>';
				$_SESSION['error_BBDD']=false;
			}
			elseif($error_autenticar){
				echo '<div class="alert alert-danger alert_login" role="alert">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  		Usuario y/o contraseña incorrectos
					  </div>';
				$_SESSION['error_autenticar']=false;
			}
          ?>
          <hr class="pg-titl-bdr-btm"></hr>
    <form action="index" id="formulario_login" method="post">
		  Email:<br>
		  <input type="text" name="email" id="email">
		  <br>
		  Contraseña:<br>
		  <input type="password" name="clave" id="clave">
		  <br><br>
		  <input type="submit" value="Acceder" id="logear" name="logear" class="btn btn-primary">
		</form>
		<br>
		<p><a href="registrarseFormulario.php">Registrarse</a></p>
		<p><a href="olvidoPassword.php">Olvidé mi contraseña</a></p>
		</div>
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
	<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>
