<?php

App::uses('AppController', 'Controller');


class LoginsController extends AppController {


  public function index(){
    /*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
  	if (session_status() == PHP_SESSION_NONE) {
  	    session_start();
  	}

    //Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos al login, en caso contrario le damos la bienvenida*/
    if ($logeado) {
      return $this->redirect('/paginasprincipales');
    }

  	/*Podemos todos los session de control de errores a false, para reiniciarlos y que no tengan errores de anteriores ejecuciones*/
  	$_SESSION['error_campoVacio']=false;
  	$_SESSION['error_BBDD']=false;
  	$_SESSION['error_autenticar']=false;
  	//Comprobamos que el método empleado es POST
  	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
  		$email = isset($_POST['email'])? $_POST['email']: null;
  		$clave = isset($_POST['clave'])? $_POST['clave']: null;
  		//Comprobamos que ninguna de las variables este a null
  		if($email!=null && $clave!=null){
  		    $success = $this->Login->acceso($email, $clave);
          if($success){
            return $this->redirect(array('controller'=>'paginasprincipales','action' => 'index'));
          }
          else{
            return $this->redirect(array('controller'=>'logins','action' => 'index', '?' => array(
                'error_autenticar' => true
            )));
          }
  		}
  		/*Error cuando el usuario deja un campo vacío*/
  		else{
  			$_SESSION['error_campoVacio']=true;
  		}
  	}
  	/*En caso de que no sea un metodo POST o un usuario quiera acceder a este php poniendo su ruta en el navegador, los redirigimos a loginFormulario.php*/
  	else{
      if(isset($this->request->query['error_autenticar'])){
        $_SESSION['error_autenticar'] = $this->request->query['error_autenticar'];
      }
  	}
  }


  public function olvideContrasenia(){
  	if (session_status() == PHP_SESSION_NONE) {
  	    session_start();
  	}
  	$_SESSION['error_usuario_no_existente']=false;
  	//Comprobamos que el método empleado es POST
  	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  		//Cogemos los valores que han puesto en el formulario, si el valor no existe, cargamos en la variable null
  		$email = isset($_POST['email'])? $_POST['email']: null;

  		//Comprobamos que ninguna de las variables este a null
  		if($email!=null){
        if($this->Login->olvideContrasenia($email)){
          $this->Session->setFlash("Correo enviado correctamente", 'default', array(), 'success');
          return $this->redirect(array('controller'=>'logins','action' => 'index'));
        }
  		}
	   }
  }
  public function test(){
  }
}
?>
