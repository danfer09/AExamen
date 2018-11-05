<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<h1>Mi perfil</h1>
		<?php 
			session_start();
			echo "<h2> Bienvenido ". $_SESSION['nombre'] . "</h2>";
			echo '<p>Nombre: ' . $_SESSION['nombre']. ' <button type="button" class="btn btn-primary" data-toggle="modal" id="btn_cambiarNombre">Cambiar nombre</button></p>';		
			echo "<p>Apellidos: " . $_SESSION['apellidos']. "</p>";
			echo "<p>correo: " . $_SESSION['email'] . "</p>";
			echo "<a href='formularioclave.php'>Cambiar contraseña</a>";
		?>
	  

	  <!-- The Modal -->
	  <div class="modal" id="modal_cambiarnombre">
	    <div class="modal-dialog">
	      <div class="modal-content">
	      
	        <!-- Modal Header -->
	        <div class="modal-header">
	          <h4 class="modal-title">Modal Heading</h4>
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	        </div>
	        
	        <!-- Modal body -->
	        <div class="modal-body">
				  <form action="perfPropProfProcesamiento.php" class="form-container" method="post">
				    <h1>Login</h1>

				    <input type="text" placeholder="Introduzca el nombre" name="nombre" id="nombre">

				    <button type="submit" class="btn" id="boton_cambiar" name="boton_cambiar">Cambiar</button>
				  </form>
	        </div>
	        
	        <!-- Modal footer -->
	        <div class="modal-footer">
	          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
	        </div>
	        
	      </div>
	    </div>
	  </div>

	  <!--INTENTO DE VENTANA DE CONFIRMACIÓN UNA VEZ PULSA EN CAMBIAR
	  <div class="modal" id="modal_confirmar">
	    <div class="modal-dialog">
	      <div class="modal-content">
	      
	     
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	        </div>
	        
	   
	        <div class="modal-body">
				  <form action="perfPropProfProcesamiento.php" class="form-container">
				 	<p>¿Está seguro?</p>
				    <button type="submit" class="btn" id="boton_confirmar" name="boton_confirmar">Si</button>
				    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
				  </form>
	        </div>
	        
	               
	      </div>
	    </div>
	  </div>
	-->

	</div>


	
	<script src="jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="formularioNombre.js"></script>


</body>
</html>