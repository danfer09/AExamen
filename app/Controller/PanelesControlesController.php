<?php
App::uses('AppController', 'Controller');


class PanelesControlesController extends AppController {
  public function index(){
    $this->loadModel('Examen');
    $this->loadModel('PanelControl');
    /*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos a index.php*/
    if (!$logeado) {
    	header('Location: index.php');
    }
    $peticiones = $this->PanelControl->getPeticiones();
    $ultimaModificacion = $this->Examen->formateoDateTime(date("Y-m-d H:i:s", filemtime('./log/log_AExamen.log')));

    $this->set('peticiones', $peticiones);
    $this->set('ultimaModificacion', $ultimaModificacion);
  }

  public function funcionesAjaxPanelControl(){
    $this->loadModel('PanelControl');
    $this->layout= 'ajax';
    $this->render(false);
    $funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
    $idPeticion = isset($_POST['idPeticion'])? $_POST['idPeticion']: null;
    if($funcion == "getPeticion")
    	echo $this->PanelControl->getPeticion($idPeticion);
    else if($funcion == "borrarPeticion")
      echo 	$this->PanelControl->borrarPeticion($idPeticion);
    else if($funcion == "aceptarPeticion")
    	echo $this->PanelControl->aceptarPeticion($idPeticion);
    else if($funcion == "reiniciarLog")
    	echo $this->PanelControl->reiniciarLog();
    else if($funcion == "eliminarLog")
    	echo $this->PanelControl->eliminarLog();
  }
  public function descargarLog(){
    $this->loadModel('PanelControl');
    $this->PanelControl->descargarLog();
  }
}
