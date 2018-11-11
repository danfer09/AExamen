<html>
<body>

<h1>Pagina principal del profesor</h1>
<?php 
	session_start();
	echo "<h2> Asignturas de ". $_SESSION['nombre']. "</h2>";



SELECT prof_asig_coord.coordinador AS coordinador, profesores.nombre AS nombre_profesor, asignaturas.nombre AS nombre_asignatura FROM ((prof_asig_coord INNER JOIN profesores ON prof_asig_coord.id_profesor = profesores.id) INNER JOIN asignaturas ON prof_asig_coord.id_asignatura = asignaturas.id) WHERE id_profesor='4'
?>



</body>
</html>