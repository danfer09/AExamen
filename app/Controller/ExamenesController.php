<?php
App::uses('AppController', 'Controller');

class ExamenesController extends AppController {
  public function index(){
    //Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos al login, en caso contrario le damos la bienvenida*/
    if (!$logeado) {
      return $this->redirect('/');
    }

    //Si es administrador mostramos en el filtro todas las asignaturas
    //en caso de que sea un profesor solo mostramos las asignaturas que
    //tenga asignadas
    $this->loadModel('Examen');
    if ($_SESSION['administrador']) {
      $siglas = $this->Examen->selectAllSiglasAsignaturas();
    } else {
      $siglas = $this->Examen->selectAllSiglasAsignaturasProfesor($_SESSION['id']);
    }

    $autores = $this->Examen->selectAllMailsProfesoresSiglas($_GET['asignatura']);

    if ($_GET['asignatura'] == "todas" && $_GET['autor'] == "todos") {
      $examenes = $this->Examen->selectAllExamenesCompleto();
    } else {
      $examenes = $this->Examen->selectAllExamenesFiltrado($_GET['asignatura'], $_GET['autor']);
    }
    $this->set('siglas', $siglas);
    $this->set('autores', $autores);
    $this->set('examenes', $examenes);
  }

  public function detalle_examen() {
    $logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
    /*En caso de no este logeado redirigimos al login, en caso contrario le damos la bienvenida*/
    if (!$logeado) {
      return $this->redirect('/');
    }
    $this->loadModel('Examen');

    $examen = $this->Examen->cargaUnicoExamenInfo($_GET['id']);

    $idAsignatura = $examen['id_asig'];
    $acceso = $_SESSION['administrador']? true:$this->Examen->comprobarAcceso($idAsignatura);
    if (!$acceso) {
      return $this->redirect('/');
    }
    $autorExamen = $this->Examen->cargaAutorExamen($examen['id']);
    $fechaCreacionExamen = $this->Examen->formateoDateTime($examen['fecha_creado']);
    $preguntas = $this->Examen->cargaUnicoExamenPreguntas($_GET['id']);
    $historial = $this->Examen->cargaHistorialExamen($_GET['id']);

    $this->set('examen', $examen);
    $this->set('autorExamen', $autorExamen);
    $this->set('fechaCreacionExamen', $fechaCreacionExamen);
    $this->set('preguntas', $preguntas);
    $this->set('historial', $historial);
  }
  public function ajaxExamenes(){
    $this->loadModel('Examen');
    $this->layout= 'ajax';
    $this->render(false);
    $funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
  	$idExamen = isset($_POST['id_examen'])? $_POST['id_examen']: null;
  	if($funcion =="borrarExamen"){
      $borrarExamen = $this->Examen->borrarExamen($idExamen);
      echo $borrarExamen;
  	}
  }
}
