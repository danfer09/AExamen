<html>
<head>
	<title>AExamen Examen</title>
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
			echo "<h1>Examen: ". $examen['titulo']. "</h1>";

		?>

		<br>

		<?php
			//Mostramos información principal
			echo "<p>Titulo Examen: ".$examen['titulo']."</p>";
			echo "<p>Autor: ".$autorExamen."</p>";
			echo "<p>Fecha de creacion: ".$fechaCreacionExamen."</p>";
			echo "<p>Preguntas:</p>";



		?>
		<div class="table-wrapper-scroll-y">
	    			<table class="table table-hover" id="tabla_preguntas_examen">
						<thead>
					      <tr>
					        <th>Titulo</th>
					        <th>Cuerpo</th>
					        <th>Tema</th>
					      </tr>
					    </thead>
					    <tbody>


    		<?php
    		//Mostramos preguntas
    			foreach ($preguntas as $pos => $valor) {
    				echo "<tr>";
    				echo "<td>".$valor['titulo_pregunta']."</td>";
    				echo "<td>".$valor['cuerpo']."</td>";
    				echo "<td>".$valor['tema']."</td>";
    				echo "<tr>";
    			}
    		?>
    		 				</tbody>


    					</table>
    		</div>

    		<p>Historial de modificaciones:</p>
    		<div class="table-wrapper-scroll-y">
    	    			<table class="table table-hover" id="tabla_historial_examen">
    						<thead>
    					      <tr>
    					        <th onclick="w3.sortHTML('#tabla_historial_examen', '.item', 'td:nth-child(1)')" class="cabeceraTabla">Nombre</th>
    					        <th onclick="w3.sortHTML('#tabla_historial_examen', '.item', 'td:nth-child(2)')" class="cabeceraTabla">Apellido</th>
    					        <th onclick="w3.sortHTML('#tabla_historial_examen', '.item', 'td:nth-child(4)')" class="cabeceraTabla">Fecha</th>
    					      </tr>
    					    </thead>
    					    <tbody>


    		<?php
    		//Mostramos historial de modificaciones
    			foreach ($historial as $pos => $valor) {
    				echo "<tr class='item'>";
    				echo "<td>".$valor['nombreAutor']."</td>";
    				echo "<td>".$valor['apellidosAutor']."</td>";
    				echo "<td>".$valor['fechaModificado']."</td>";
    				echo '<td hidden=true;>'.$valor['historial']['fecha_modificacion'].'</td>';
    				echo "<tr>";
    			}
    		?>
    		 </tbody>


    	</table>
    	</div>

    </div>




</body>
</html>
