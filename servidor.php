<?php
//Get credentials
$credentialsStr = file_get_contents('credentials.json');
$credentials = json_decode($credentialsStr, true);
var_dump($credentials['database']['user']);

// Conectando, seleccionando la base de datos
$link = mysqli_connect('localhost', $credentials['database']['user'], $credentials['database']['user'])
    or die('No se pudo conectar: ' . mysqli_error());
echo 'Connected successfully';
mysqli_select_db('my_database') or die('No se pudo seleccionar la base de datos');

// Realizar una consulta MySQL
$query = 'SELECT * FROM profesores';
$result = mysqli_query($query) or die('Consulta fallida: ' . mysqli_error());

// Imprimir los resultados en HTML
echo "<table>\n";
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

// Liberar resultados
mysql_free_result($result);

// Cerrar la conexión
mysql_close($link);
?>