<?php
App::uses('AppController', 'Controller');


class ProfesoresController extends AppController {

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
    //Comprobamos que es un administrador, en caso contrario lo redirigimos
    $admin = isset($_SESSION['administrador'])? $_SESSION['administrador']: false;
		if (!$admin){
			return $this->redirect('/');
		}

    $this->loadModel('Profesor');
    $profesores = $this->Profesor->getProfesoresAdmin();

    $this->set('profesores', $profesores);
  }

  public function profesores_asignatura() {
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

    $this->loadModel('Profesor');
    $idAsignatura = $_GET['idAsig'];
    $nombreAsignatura = $_GET['nombreAsig'];

    $esCoordinador = $this->Profesor->esCoordinador($idAsignatura, $_SESSION['id']);
    /*En caso de no este logeado redirigimos a index.php, en caso contrario le damos la bienvenida*/
    if (!$esCoordinador) {
      return $this->redirect('/asignaturas/index');
    }

    //Se cargan todos los profesores de la asignatura
    $profesores = $this->Profesor->profesoresAsignatura($idAsignatura);


    $this->set('idAsignatura', $idAsignatura);
    $this->set('nombreAsignatura', $nombreAsignatura);
    $this->set('profesores', $profesores);
  }

  public function funcionesAjaxProfesores(){
    $this->loadModel('Profesor');
    $_SESSION['error_no_poder_borrar'] = false;
    $this->layout= 'ajax';
    $this->render(false);
    //Cargamos en variables todos los parametros que nos hayan llegado por POST
  	$nombre = isset($_POST['nombre'])? $_POST['nombre']: null;
  	$apellidos = isset($_POST['apellidos'])? $_POST['apellidos']: null;
  	$email = isset($_POST['email'])? $_POST['email']: null;
  	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
  	$idProfesor = isset($_POST['idProfesor'])? $_POST['idProfesor']: null;
  	$idAsigSelect = isset($_POST['idAsigSelect'])? $_POST['idAsigSelect']: null;
  	$idAsigNoSelect = isset($_POST['idAsigNoSelect'])? $_POST['idAsigNoSelect']: null;
  	$idAsig = isset($_POST['idAsig'])? $_POST['idAsig']: null;
    $profesor = isset($_POST['profesor'])? $_POST['profesor']: null;
    $idProfesores = isset($_POST['idProfesores'])? $_POST['idProfesores']: null;

  	//comprobamos los valores de las variables y en consecuencia llamamos a las
  	//diferentes funciones
  	if ($email && $funcion==null) {
  		$this->Profesor->invitarProfesor($email);
      return $this->redirect('/profesores');
  	}

  	if($funcion == "borrarProfesor"){
  		$borrarProfesor = $this->Profesor->borrarProfesor($idProfesor);
      echo $borrarProfesor;
    }
  	else if($funcion == "editarProfesor"){
  		$editarProfesor = $this->Profesor->editarProfesor($nombre,$apellidos,$email,$idProfesor);
      echo $editarProfesor;
    }
  	else if($funcion == "getAsignaturas"){
  		echo $this->Profesor->getAsignaturas($idProfesor);
    }
  	else if($funcion == "setCoordinadores"){
  		echo $this->Profesor->setCoordinadores($idProfesor, $idAsigSelect, $idAsigNoSelect);
  	}
  	else if($funcion == "isAsigWithCoord"){
  		echo $this->Profesor->isAsigWithCoord($idAsig, $idProfesor);
  	}
    else if ($funcion == 'borrarProfesorDeAsig') {
      echo $this->Profesor->borrarProfesorDeAsig($idProfesor, $idAsig);
    }
    else if($funcion == "getProfesoresFueraAsig"){
		  echo json_encode($this->Profesor->getProfesoresFueraAsig($idAsig, $idProfesores));
    }
    else if ($funcion == "aniadirProfesor") {
      echo $this->Profesor->aniadirProfesor($profesor, $idAsig);
    }
  }
}
