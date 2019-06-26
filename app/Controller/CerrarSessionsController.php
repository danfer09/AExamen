<?php
App::uses('AppController', 'Controller');


class CerrarsessionsController extends AppController {

  public function index(){
    $this->autoRender = false;
    /*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    //Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
    if (!$logeado) {
    	return $this->redirect(array('controller'=>'logins','action' => 'index'));
    }

    date_default_timezone_set("Europe/Madrid");

    //Escribimos en el log que el usuario ha cerrado sesion
    $log  = '['.date("d/m/Y - H:i:s").'] : '."USER --> id ".$_SESSION['id'].' - '.$_SESSION['apellidos'].', '.$_SESSION['nombre'].
            " | ACTION --> Cierre de sesión ".' de '.$_SESSION['email'].PHP_EOL.
            "-----------------------------------------------------------------".PHP_EOL;
    file_put_contents('./log/log_AExamen.log', utf8_decode($log), FILE_APPEND);

    //Borramos los valores de la session
    $_SESSION = array();


    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    //Destruimos la session y redirigimos a formulario de login
    session_destroy();
    return $this->redirect(array('controller'=>'logins','action' => 'index'));
  }
  public function test(){
  }
}
