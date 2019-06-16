<?php
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
?>
<html>
<head>
	<title>AExamen Asignaturas</title>
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
	<?php
		/*Incluimos asignaturasProfesorProcesamiento.php donde tenemos implementadas
		algunas funciones que usaremos más adelante*/
		echo "<h2> Asignaturas de ". $_SESSION['nombre']. "</h2>";
	?>

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
	        <th class="cabeceraTabla" onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(1)')">Siglas</th>
	        <th class="cabeceraTabla" onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(2)')">Nombre asignatura</th>
	        <th class="cabeceraTabla" onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(3)')">Coordinador</th>
	      </tr>
	    </thead>
	    <tbody>
	<?php
		/*Comprobación de errores que se controla en cargaAsignaturas*/
		$error_ningunaAsignatura = isset($_SESSION['error_ningunaAsignatura'])? $_SESSION['error_ningunaAsignatura']: false;
		$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
		/*Tratamiento de errores en caso de que se haya producido alguno*/
		if($error_ningunaAsignatura){
			echo '<div class="alert alert-warning">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							No tienes ninguna asignatura.
					 	</div>';
		}
		//En caso de que nos de error la BBDD lo mostramos
		else if($error_BBDD){
			echo '<div class="alert alert-warning">
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							Error con la BBDD, contacte con el administrador
					 	</div>';
		}
		/*En caso de que no se haya producido ningún error, mostramos todas las asignaras
		que nos ha devuelto en una tabla*/
		else{
			foreach ($asignaturas as $pos => $valor) {
				echo '<tr class="item filaAsignaturasProfesor">';
				echo '<td><a href="/asignaturas/unaAsignatura?id='.$valor["asignaturas"]['id_asignatura'].'&nombre='.$valor["asignaturas"]['nombre_asignatura'].'&siglas='.$valor["asignaturas"]['siglas_asignatura'].'"></a>'.$valor["asignaturas"]['siglas_asignatura'].'</td>';
				echo '<td>'.$valor["asignaturas"]['nombre_asignatura'].'</td>';
				$coord=($valor["prof_asig_coord"]['coordinador'])?'Si':'No';
				echo '<td>'.$coord.'</td>';
				echo '</tr>';
			}
		}
	?>
		</tbody>
	</table>

</div>
<!--Javascripts propios-->
<?php
  echo $this->Html->script('asignaturasProfesor');
?>
</body>
</html>
