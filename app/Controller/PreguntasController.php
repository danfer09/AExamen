<?php
App::uses('AppController', 'Controller');


class PreguntasController extends AppController {

  public function index(){
    $autores = $this->Pregunta->selectAllMailsProfesoresId($_GET['idAsignatura']);
    $this->set('autores', $autores);
  }
}
