<?php

App::uses('AppController', 'Controller');


class PerfilesController extends AppController {
  public function index(){
    if (session_status() == PHP_SESSION_NONE) {
  	    session_start();
  	}
  }


  public function cambioDatos() {
    $this->loadModel('Perfil');
    $this->autoRender = false;
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  		//Cogemos el valor que han puesto en el formulario, si el valor no existe, cargamos en la variable null
  		$nuevoNombre = isset($_POST['nombre'])? $_POST['nombre']: null;
  		$nuevoApellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
  		$nuevoClave = isset($_POST['clave'])? $_POST['clave']: null;
  		$nuevoCorreo = isset($_POST['correo'])? $_POST['correo']: null;
      if($nuevoNombre!=null){
  			$success = $this->Perfil->cambioNombre($nuevoNombre);
        $_SESSION['error_actualizar'] = !$success;
  		}
  		//Si nuevoApellidos no esta a null es que el usuario quiere cambiar el apellido, por lo tanto procedemos a cambiarlo
  		elseif($nuevoApellidos!=null){
  			$success = $this->Perfil->cambioApellidos($nuevoApellidos);
        $_SESSION['error_actualizar'] = !$success;
  		}
  		//Si nuevoClave no esta a null es que el usuario quiere cambiar el clave, por lo tanto procedemos a cambiarlo
  		elseif($nuevoClave!=null){
        $success = $this->Perfil->cambioClave($nuevoClave);
        $_SESSION['error_actualizar'] = !$success;
  		}
  		//Si $nuevoCorreo no esta a null es que el usuario quiere cambiar el clave, por lo tanto procedemos a cambiarlo
  		elseif($nuevoCorreo!=null){
        $success = $this->Perfil->cambioCorreo($nuevoCorreo);
        $_SESSION['error_actualizar'] = !$success;
  		}
    }
    return $this->redirect('/perfiles/index');
  }

}
