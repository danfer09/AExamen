<html>
<body>

<h1>Welcome to my home page!</h1>
<?php 
	session_start();
	$_SESSION['host'] = 'localhost';
	if (!isset($_SESSION['logeado']) && !$_SESSION['logeado']) {
		header('Location: loginFormulario.php');
		exit();
	}
	else{
		header('Location: paginaPrincipalProf.php');
		exit();
	}
?>

</body>
</html>