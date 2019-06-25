<html>
<head>
	<title>AExamen Pregunta</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico">
</head>
<body>
	<div class="header" id="header"></div>
	<div class="container">
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
			echo '<h1>Pregunta "'. $pregunta['titulo']. '"</h1>';

		?>
		<br>
		<?php
		// Mostramos la información principal

			echo "<p>Titulo: ".$pregunta['titulo']."</p>";
			echo "<p>Cuerpo: ".$pregunta['cuerpo']."</p>";
			echo "<p>Tema: ".$pregunta['tema']."</p>";
			echo "<p>Autor: ".$pregunta['nombreAutor']."</p>";
			echo "<p>Fecha de creacion: ".$pregunta['fecha_creado_raw']."</p>";


		?>
		<p>Historial de modificaciones:</p>
		<div class="table-wrapper-scroll-y">
	    			<table class="table table-hover" id="tabla_historial">
						<thead>
					      <tr>
					        <th onclick="w3.sortHTML('#tabla_historial', '.item', 'td:nth-child(1)')" class="cabeceraTabla">Nombre</th>
					        <th onclick="w3.sortHTML('#tabla_historial', '.item', 'td:nth-child(2)')" class="cabeceraTabla">Apellido</th>
					        <th onclick="w3.sortHTML('#tabla_historial', '.item', 'td:nth-child(4)')" class="cabeceraTabla">Fecha</th>
					      </tr>
					    </thead>
					    <tbody>


		<?php
		//imprimimos el historial de modificaciones
    if(isset($historial)){
			foreach ($historial as $pos => $valor) {
				echo "<tr class='item'>";
					echo "<td>".$valor['nombreAutor']."</td>";
					echo "<td>".$valor['apellidosAutor']."</td>";
					echo "<td>".$valor['fecha_modificado_raw']."</td>";
					echo '<td hidden=true;>'.$valor['fecha_modificacion'].'</td>';
				echo "<tr>";
			}
    }
		?>
		 </tbody>


	</table>
	</div>
	</div>
</body>
</html>
