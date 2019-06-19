<?php
App::uses('AppController', 'Controller');

class CrearExamenesController extends AppController {
  public function index(){
    /*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos al login, en caso contrario le damos la bienvenida*/
    if (!$logeado) {
      return $this->redirect('/');
    }

    $this->loadModel('CrearExamen');

    $editar=isset($_GET["editar"])? $_GET["editar"] : 0;
    if ($editar) {
      $examenEntero = $this->CrearExamen->getExamen($idExamen);
    }
    debug($_GET["idAsignatura"]);
    die;
    $arrayPuntosTema = $this->CrearExamen->cargaPuntosTema($_GET["idAsignatura"]);
    $numTemas = $this->CrearExamen->getNumTemas($_GET["idAsignatura"]);

    $this->set('editar', $editar);
    $this->set('numTemas', $numTemas);
    $this->set('examenEntero', $examenEntero);
    $this->set('$arrayPuntosTema', $arrayPuntosTema);

  }
}
