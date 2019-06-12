<?php

App::uses('AppController', 'Controller');


class RegistrosController extends AppController {

  public function index(){

    if (session_status() == PHP_SESSION_NONE) {
  	    session_start();
  	}

  	$_SESSION['error_usuario_existente']=false;
  	//Comprobamos que el método empleado es POST
  	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
  		$email = isset($_POST['email'])? $_POST['email']: null;
  		$nombre = isset($_POST['nombre'])? $_POST['nombre']: null;
  		$apellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
  		$texto = isset($_POST['texto'])? $_POST['texto']: null;
  		$clave = isset($_POST['clave'])? $_POST['clave']: null;
  		$repetirClave = isset($_POST['repetirClave'])? $_POST['repetirClave']: null;
  		//Comprobamos que ninguna de las variables este a null
  		if($email!=null && $apellidos!=null && $nombre!=null && $clave!=null && $repetirClave!=null && ($clave == $repetirClave) ){
  			$success = $this->Registro->registrarse($email, $nombre, $apellidos, $clave, $texto);
        if ($success) {
          return $this->redirect('/registroAexamen-pagina.html');
        } else {
          return $this->redirect(array('controller'=>'registros','action' => 'index', '?' => array(
              'error_usuario_existente' => true
          )));
        }
      }
		}
		else{
      if(isset($this->request->query['error_usuario_existente'])){
        $_SESSION['error_usuario_existente'] = $this->request->query['error_usuario_existente'];
      }
		}

  }
}
?>
