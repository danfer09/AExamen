<?php
App::uses('AppController', 'Controller');

class ExamenesController extends AppController {
  public function index(){
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
}
