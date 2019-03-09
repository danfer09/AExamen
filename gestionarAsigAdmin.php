<?php
	//COMPROBAR SI ES O NO UN ADMINISTRADOR 



	//Comprobamos si el usuario esta logeado
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
	include 'gestionarAsigAdminProcesamiento.php'; 



?>

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
<h1>Página administrador</h1>
<h2>Todas las asignaturas</h2>

	<br>
	<p>
		<!--Implementacion del buscador  -->
		<input oninput="w3.filterHTML('#tabla_asignaturas', '.item', this.value)" class="w3-input" placeholder="Buscar...">
	</p>
	<table class="table table-hover" id="tabla_asignaturas">
	    <thead>
	      <tr>
	      	<!-- Implementación de la funcionalidad de ordenar por una columna
	      	pulsado en el nombre de la columna. td:nth-child(2) se refiere a la segunda columna 
	      	de cada fila de la tabla -->
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(1)')" style="cursor:pointer;">Siglas</th>
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(2)')" style="cursor:pointer;">Nombre asignatura</th>
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(3)')" style="cursor:pointer;">Número Profesores</th>
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(4)')" style="cursor:pointer;">Nombre/s coordinador/es</th>
	      </tr>
	    </thead>
	    <tbody>
	<?php
		/*Llama a una función que dado un id de un usuario, en este caso el
		que esta logeado, devuelve las asignaturas que tiene ese usuario*/
		$asignaturas=cargaTodasAsignaturas();
		/*Comprobación de errores que se controla en cargaAsignaturas*/
		$error_ningunaAsignatura = isset($_SESSION['error_ningunaAsignatura'])? $_SESSION['error_ningunaAsignatura']: false;
		$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
		/*Tratamiento de errores en caso de que se haya producido alguno*/
		getCoordinadores(2);
		if($error_ningunaAsignatura){
			echo 'No hay asignaturas dadas de alta en la plataforma';
		}
		else if($error_BBDD){
			echo 'Error con la BBDD, contacte con el administrador';
		}
		/*En caso de que no se haya producido ningún error, mostramos todas las asignaras
		que no ha devuelto en una tabla*/
		else{
			foreach ($asignaturas as $pos => $valor) {
				echo '<tr class="item" style="cursor:pointer;">';
				echo '<td><a href="asignatura.php?id='.$valor['id'].'&nombre='.$valor['nombre'].'&siglas='.$valor['siglas'].'"></a>'.$valor['siglas'].'</td>';
				echo '<td>'.$valor['nombre'].'</td>';
				echo '<td>'.getNumeroProfesoresAsig($valor['id'])['numero_profesores']."</td>";
				//echo '<td>'.foreach (getCoordinadores($valor['id']) as $pos => $valor) $valor.'</td>';
				echo '</tr>';
			}
		}
	?>
		</tbody>
	</table>
		
</div>

<!--Librerias externas-->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/w3.js"></script>

<!--Javascripts propios-->
<script type="text/javascript" src="js/asignaturasProfesor.js"></script><!--CUIDADO, ES UN JS DE OTRO PHP, PODEMOS BASARNOS EN EL-->
<script type="text/javascript" src="js/cabeceraConLogin.js"></script>

</body>
</html>
