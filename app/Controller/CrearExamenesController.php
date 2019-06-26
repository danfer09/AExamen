<?php
App::uses('AppController', 'Controller');

class CrearexamenesController extends AppController {
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
    $_SESSION['nombreAsignatura'] = $nombreAsignatura = $_GET["asignatura"];
    $_SESSION['idAsignatura'] = $_GET["idAsignatura"];
    $_SESSION['editar'] = $editar;
    $idExamen = isset($_GET["id"])? $_GET["id"] : null;
    if ($editar) {
      $examenEntero = $this->CrearExamen->getExamen($idExamen);

      if (!isset($_SESSION[$examenEntero['titulo']]) || $_SESSION[$examenEntero['titulo']] == null) {
        $preguntasSesion=json_decode($examenEntero['puntosPregunta'],true);
      } else {
        $preguntasSesion=json_decode($_SESSION[$examenEntero['titulo']],true);
      }

      $nombreExamen = $preguntasSesion['nombreExamen'];
      $_SESSION['nombreExamenEditar']=$nombreExamen;
      $_SESSION[$nombreExamen]= json_encode($preguntasSesion);
      $_SESSION['idExamen']=$idExamen;

      $this->set('preguntasSesion', $preguntasSesion);
      $this->set('nombreExamen', $nombreExamen);
      $this->set('examenEntero', $examenEntero);

      if (isset($_SESSION[$_SESSION['nombreExamenEditar'].'datos'])) {
        $datosPreguntasSesion = $_SESSION[$_SESSION['nombreExamenEditar'].'datos'];
      } else {
        $datosPreguntasSesion = $examenEntero['preguntas'];
        $_SESSION[$_SESSION['nombreExamenEditar'].'datos'] = $examenEntero['preguntas'];
      }
    } else {
      if (!isset($_SESSION[$_SESSION['nombreAsignatura'].'datos'])) {
        $_SESSION[$_SESSION['nombreAsignatura'].'datos'] = [];
      }
      $datosPreguntasSesion = $_SESSION[$_SESSION['nombreAsignatura'].'datos'];
    }

    $arrayPuntosTema = $this->CrearExamen->cargaPuntosTema($_GET["idAsignatura"]);
    $numTemas = $this->CrearExamen->getNumTemas($_GET["idAsignatura"]);

    $this->set('datosPreguntasSesion', $datosPreguntasSesion);
    $this->set('editar', $editar);
    $this->set('numTemas', $numTemas);
    $this->set('arrayPuntosTema', $arrayPuntosTema);
    $this->set('nombreAsignatura', $nombreAsignatura);

  }

  public function ajaxCrearExamenes(){
    $this->loadModel('CrearExamen');
    $this->layout= 'ajax';
    $this->render(false);
    $funcion = isset($_POST['funcion'])? $_POST['funcion']: null;
    $nombreExamen = isset($_POST['nombreExamen'])? $_POST['nombreExamen']: null;
  	$idExamen = isset($_POST['idExamen'])? $_POST['idExamen']: null;
  	$idAsignatura = isset($_POST['idAsignatura'])? $_POST['idAsignatura']: null;
  	$tema = isset($_POST['tema'])? $_POST['tema']: null;
  	$preguntas = isset($_POST['preguntas'])? $_POST['preguntas']: null;
  	$idPregunta = isset($_POST['idPregunta'])? $_POST['idPregunta']: null;
  	$puntos = isset($_POST['puntos'])? $_POST['puntos']: null;
  	$tema = isset($_POST['tema'])? $_POST['tema']: null;

    if($funcion == "getPregAsigTema"){
  		$pregAsigTema = $this->CrearExamen->getPregAsigTema($idAsignatura,$tema);
      echo json_encode($pregAsigTema);
    }
  	else if ($funcion =="aniadirPreguntas"){
  		$preguntasJSON = $this->CrearExamen->aniadirPreguntas($preguntas);
      echo json_encode($preguntasJSON);
    }
    else if ($funcion == "eliminarPregunta"){
  		$eliminarPregunta = $this->CrearExamen->eliminarPregunta($idPregunta, $tema);
      echo json_encode($eliminarPregunta);
    }
    else if ($funcion == "cambiarPuntosPregunta"){
  		$cambioPuntosPregunta = $this->CrearExamen->cambiarPuntosPregunta($idPregunta, $puntos, $tema);
      echo $cambioPuntosPregunta;
    }
  	else if ($funcion == "guardarExamen"){
  		$guardarExamen = $this->CrearExamen->guardarExamen($nombreExamen);
      echo $guardarExamen;
    }
    else if ($funcion == "guardarModificarExamen"){
  		$guardarExamen = $this->CrearExamen->guardarModificarExamen($nombreExamen);
      echo $guardarExamen;
    }


  }

}
