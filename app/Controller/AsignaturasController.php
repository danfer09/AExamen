<?php
App::uses('AppController', 'Controller');

class AsignaturasController extends AppController {
  public function index(){
    /*Ponemos las variables session con las que comprobamos los
		errores a false. Por si tienen algun valor de una ejecucción
		anterior*/
		$_SESSION['error_ningunaAsignatura']=false;
		$_SESSION['error_BBDD']=false;
    $asignaturas = $this->Asignatura->cargaAsignaturas($_SESSION['id']);
    $this->set('asignaturas', $asignaturas);
  }
  public function unaAsignatura(){
    $idAsignatura=$_GET['id'];
    $idProfesor=$_SESSION['id'];
    $esCoordinador= $this->Asignatura->esCoordinador($idAsignatura,$idProfesor);
    $this->set('esCoordinador', $esCoordinador);
  }
}
