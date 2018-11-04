<?php
//ip del servidor, nombre de usr de bbdd, contraseña de usuario, nombre de base de datos
$db =@mysqli_connect('', '', '',"");
if($db){
	echo 'Connected successfully';
	$sql="SELECT * FROM profesores";
	$consulta=mysqli_query($db,$sql);
	//$fila=mysqli_fetch_assoc($consulta);
	$matches = mysqli_fetch_assoc($consulta);
	echo "<option value=".$matches['nombre'].">".$matches['nombre']."</option>";
	
	@mysqli_close($db);
}

?>