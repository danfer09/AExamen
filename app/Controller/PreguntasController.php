<?php
App::uses('AppController', 'Controller');


class PreguntasController extends AppController {

  public function index(){
    //Comprobamos que ninguna de las variables este a null
    $_SESSION['error_ningunaPregunta']=false;
    $_SESSION['error_BBDD']=false;

    $this->loadModel('Examen');
    $autores = $this->Pregunta->selectAllMailsProfesoresId($_GET['idAsignatura']);
    $preguntas = $this->Pregunta->cargaPreguntas($_GET['idAsignatura'], $_GET['autor']);
    //llamar al formateoDateTime de Examen. mirar como esta hecho en la vista de examenes esa llamada
    $this->set('autores', $autores);
    $this->set('preguntas',$preguntas);
  }

  public function detalle_pregunta(){
    $pregunta = $this->Pregunta->cargaUnicaPregunta($_GET['id']);
    $historial=$this->Pregunta->cargaHistorialPregunta($_GET['id']);

    $this->set('historial',$historial);
    $this->set('pregunta', $pregunta);
  }


  public function funcionesAjaxPreguntas(){
    $_SESSION['error_no_poder_borrar'] = false;
    $this->layout= 'ajax';
    $this->render(false);
    $titulo = isset($_POST['titulo'])? $_POST['titulo']: null;
  	$cuerpo = isset($_POST['cuerpo'])? $_POST['cuerpo']: null;
  	$tema = isset($_POST['tema'])? $_POST['tema']: null;
  	$funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
  	$idPregunta = isset($_POST['id_pregunta'])? $_POST['id_pregunta']: null;
  	if($funcion == "aniadirPregunta"){
  		$aniadirPregunta = $this->Pregunta->aniadirPregunta($titulo,$cuerpo,$tema);
      echo $aniadirPregunta;
    }
  	else if($funcion =="borrarPregunta"){
  		$borrarPregunta = $this->Pregunta->borrarPregunta($idPregunta);
      echo $borrarPregunta;
  	}
  	else if($funcion == "editarPregunta"){
  		$editarPregunta = $this->Pregunta->editarPregunta($titulo,$cuerpo,$tema,$idPregunta);
      echo $editarPregunta;
  	}
  }

}
