<html>
<head>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>

<h1>Welcome to my home page!</h1>
<?php 
	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$_SESSION['host'] = 'localhost';

	/*Si el usuario esta logeado lo redirigimos a paginaPrincipalProf.php, en caso de que no este logeado lo redirigimos a loginFormulario.php para que se logee*/
			
	//Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false
	if (!isset($_SESSION['logeado']) && !$_SESSION['logeado']) {
		header('Location: loginFormulario.php');
		exit();
	}
	else{
		header('Location: paginaPrincipalProf.php');
		exit();
	}
?>
	<!--Librerias externas-->
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/w3.js"></script>

</body>
</html>