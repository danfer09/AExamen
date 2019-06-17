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
  public function una_asignatura(){
    $idAsignatura=$_GET['id'];
    $idProfesor=$_SESSION['id'];
    $esCoordinador= $this->Asignatura->esCoordinador($idAsignatura,$idProfesor);
    $this->set('esCoordinador', $esCoordinador);
  }
  public function index_admin(){
    //Si existe $_SESSION['logeado'] volcamos su valor a la variable, si no existe volcamos false. Si vale true es que estamos logeado.
  	$logeado = isset($_SESSION['logeado'])? $_SESSION['logeado']: false;
  	/*En caso de no este logeado redirigimos a index.php*/
  	if (!$logeado) {
  		header('Location: index.php');
  	}
  	//Comprobamos que el usuario sea un administrador
  	$administrador = isset($_SESSION['administrador'])? $_SESSION['administrador']: false;
  	/*En caso de que no sea un aadministrador lo redirigimos a la pagina principal*/
  	if (!$administrador) {
  		header('Location: index.php');
  	}

    //Comprobamos si el usuario esta logeado
  	/*Iniciamos la sesion, pero antes hacemos una comprobacion para evitar errores*/
  	if (session_status() == PHP_SESSION_NONE) {
  	    session_start();
  	}
    $asignaturas=$this->Asignatura->cargaTodasAsignaturas();
    $coordinadores=$this->Asignatura->getCoordinadores();
    $numProfesores=$this->Asignatura->getNumeroProfesoresAsig();


    $this->set('asignaturas', $asignaturas);
    $this->set('coordinadores', $coordinadores);
    $this->set('numProfesores', $numProfesores);
  }

  public function ajaxAsignaturas(){
    $this->layout= 'ajax';
    $this->render(false);
    $funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
  	$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
  	$idProfSelect = isset($_POST['idProfSelect'])? $_POST['idProfSelect']: null;
  	$idProfNoSelect = isset($_POST['idProfNoSelect'])? $_POST['idProfNoSelect']: null;
  	if($funcion == "getProfesoresAdmin"){
  		$profesoresUnaAsig = $this->Asignatura->getProfesoresAdmin($idAsig);
      echo $profesoresUnaAsig;
    }
  	else if ($funcion == "setCoordinadores"){
  		setCoordinadores($idAsig, $idProfSelect, $idProfNoSelect);
    }
  }
}
