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
	//Comprobamos que el usuario sea un administrador
	$administrador = isset($_SESSION['administrador'])? $_SESSION['administrador']: false;
	/*En caso de que no sea un aadministrador lo redirigimos a la pagina principal*/
	if (!$administrador) {
		header('Location: index.php');
	}
	//include 'gestionarAsigAdminProcesamiento.php';
?>

<html>
<head>
	<title>AExamen Gestión Asignaturas</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
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
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(1)')" class="cabeceraTabla">Siglas</th>
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(2)')" class="cabeceraTabla">Nombre asignatura</th>
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(3)')" class="cabeceraTabla">Número Profesores</th>
	        <th onclick="w3.sortHTML('#tabla_asignaturas', '.item', 'td:nth-child(4)')" class="cabeceraTabla">Nombre/s coordinador/es</th>
	        <th>Añadir coordinadores</th>
	      </tr>
	    </thead>
	    <tbody id="tableAsignaturas">
	<?php
		/*Llama a una función que dado un id de un usuario, en este caso el
		que esta logeado, devuelve las asignaturas que tiene ese usuario*/
		/*Comprobación de errores que se controla en cargaAsignaturas*/
		$error_ningunaAsignatura = isset($_SESSION['error_ningunaAsignatura'])? $_SESSION['error_ningunaAsignatura']: false;
		$error_BBDD = isset($_SESSION['error_BBDD'])? $_SESSION['error_BBDD']: false;
		/*Tratamiento de errores en caso de que se haya producido alguno*/
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
				echo '<tr class="item filaGestionarAsigAdmin" >';
				echo '<td class="asigClick" href="/asignaturas/una_asignatura?id='.$valor['id'].'&nombre='.$valor['nombre'].'&siglas='.$valor['siglas'].'">'.$valor['siglas'].'</td>';
				echo '<td class="asigClick" href="/asignaturas/una_asignatura?id='.$valor['id'].'&nombre='.$valor['nombre'].'&siglas='.$valor['siglas'].'">'.$valor['nombre'].'</td>';
				echo '<td class="asigClick" href="/asignaturas/una_asignatura?id='.$valor['id'].'&nombre='.$valor['nombre'].'&siglas='.$valor['siglas'].'">'.$numProfesores[$valor['id']]."</td>";
				echo '<td class="asigClick" href="/asignaturas/una_asignatura?id='.$valor['id'].'&nombre='.$valor['nombre'].'&siglas='.$valor['siglas'].'">';
				echo $coordinadores[$valor['id']];
				echo '</td>';
				echo '<td class="botonCoordinadores" idAsig="'.$valor['id'].'" href="asignatura.php?id='.$valor['id'].'&nombre='.$valor['nombre'].'&siglas='.$valor['siglas'].'"><a class="fas fa-plus-circle botonCoordinadores"  idAsig="'.$valor['id'].'" href="#"></a>';
				echo '</tr>';
			}
		}
	?>
		</tbody>
	</table>
	<div class="modal" id="modalCoordinadorAsig">
		<div class="modal-dialog modal-lg">
		  <div class="modal-content">

		    <!-- Modal Header -->
		    <div class="modal-header">
		      <h4 class="modal-title">Añadir coordinadores</h4>
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		    </div>

		    <!-- Modal body -->
		    <div class="modal-body">
				  <form action="#" class="form-container" method="post" id="formAsigCoord">
				    	<div class="table-wrapper-scroll-y">
							<div id="message"></div>

							<table class="table table-hover" id="tabla">
								<thead>
							      <tr>
							      	<th>#</th>
							        <th>Nombre</th>
							        <th>Apellidos</th>
							       	<th>Correo</th>
							      </tr>
							    </thead>
							    <tbody id="tableCoordinadores">
						 		</tbody>

							</table>
						</div>
				    <button type="submit" class="btn btn-primary" id="boton_aniadir" name="boton_aniadir">Añadir</button>
				    <button type="button" class="btn btn-danger" id="boton_noAniadir" name="boton_noAniadir" data-dismiss="modal">Cancelar</button>
				  </form>
		    </div>

		    <!-- Modal footer -->
		    <div class="modal-footer">
		    </div>

		  </div>
		</div>
	</div>

</div>
<!--Javascripts propios-->
<?php
  echo $this->Html->script('gestionarAsigAdmin');
?>
</body>
</html>
