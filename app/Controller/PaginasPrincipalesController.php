<?php
App::uses('AppController', 'Controller');


class PaginasprincipalesController extends AppController {

  public function index(){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    //Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos al login, en caso contrario le damos la bienvenida*/
    if (!$logeado) {
      return $this->redirect('/');
    }
  }
}
