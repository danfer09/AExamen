<?php
App::uses('AppController', 'Controller');


class ProfesoresController extends AppController {

  public function index(){
    $this->loadModel('Profesor');
    $profesores = $this->Profesor->getProfesoresAdmin();

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

  	//comprobamos los valores de las variables y en consecuencia llamamos a las
  	//diferentes funciones
  	if ($email && $funcion==null) {
  		invitarProfesor($email);
  	}

  	if($funcion == "borrarProfesor"){
  		$borrarProfesor = $this->Profesor->borrarProfesor($idProfesor);
      echo $borrarProfesor;
    }
  	else if($funcion == "editarProfesor"){
  		$editarProfesor = $this->Profesor->editarProfesor($nombre,$apellidos,$email,$idProfesor);
      echo $editarProfesor;
    }
  	else if($funcion == "getAsignaturas")
  		getAsignaturas($idProfesor);
  	else if($funcion == "setCoordinadores"){
  		setCoordinadores($idProfesor, $idAsigSelect, $idAsigNoSelect);
  	}
  	else if($funcion == "isAsigWithCoord"){
  		isAsigWithCoord($idAsig, $idProfesor);
  	}
  }
}
