<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php //echo $cakeDescription ?>
		<?php //echo $this->fetch('title');
		 				echo "AExamen";?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		//echo $this->Html->css('cake.generic');
		echo $this->Html->css('all');
		echo $this->Html->css('cabeceraLogin');
		echo $this->Html->css('estilo');
		echo $this->Html->css('font-awesome.min');
		echo $this->Html->css('loginFormulario');
		echo $this->Html->css('slick-team-slider');
		echo $this->Html->css('style');
		echo $this->Html->css('tempusdominus-bootstrap-4.min');
		echo $this->Html->css('w3');
		echo $this->Html->css('bootstrap.min');

		echo $this->Html->script('jquery-3.3.1.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('slick.min');
		echo $this->Html->script('moment.min');
		echo $this->Html->script('custom');
		echo $this->Html->script('es');
		// echo $this->Html->script('jquery-3.3.1.slim.min');
		// echo $this->Html->script('jquery.easing.min');
		// echo $this->Html->script('jquery.min');
		echo $this->Html->script('popper.min');
		echo $this->Html->script('tempusdominus-bootstrap-4.min');
		echo $this->Html->script('w3');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<?php
/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
if (session_status() == PHP_SESSION_NONE) {
		session_start();
}

/*Volcamos a una variable el valor de la session logeado. Si vale true es que ya estamos logeados, en caso contrario es que no estamos logeados*/
$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
/*Si estamos logeados redirigimos a paginaPrincipaProf.php*/
if($logeado){
?>
	<header id="header">
	<nav class="links" style="--items:1;">
		<div class="row">
			<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1"></div>
			<!-- Boton de home para acceder a la página principal-->
			<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1">
				<a href="/paginasprincipales" class="btn btn-primary" id="buttonHome"><i class="fas fa-home fa-2x"></i></a>
			</div>
			<div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"></div>
			<!-- Nombre de la pagina clicable para acceder a la pagina principal-->
			<div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
				<a id="logoCentral" href="/paginasprincipales"><h1>AExamen!</h1></a>
			</div>
			<div class="col-2 col-sm-1 col-md-2 col-lg-2 col-xl-2"></div>
			<!-- Botones para ir al perfil propio y para cerrar sesion-->
			<div class="col-3 col-sm-4 col-md-3 col-lg-3 col-xl-3">
				<a class="btn btn-primary" href="/perfiles"><i class="fas fa-user-circle fa-2x"></i></a>
				<a class="btn btn-primary" href="/cerrarsessions/index" role="button"><i class="fas fa-sign-out-alt fa-2x"></i></a>
			</div>
		</div>
	</nav>
</header>
<?php } ?>
<body>
	<div id="container">
		<!-- <div id="header">
		</div> -->
		<div id="content">

			<?php echo $this->Flash->render(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>
