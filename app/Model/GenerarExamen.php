<?php
App::uses('AppModel', 'Model');
require('FPDF/fpdf.php');



class GenerarExamen extends AppModel {
  public $useTable = 'examenes';

  /*Función que devuelve todas las preguntas de un examen
	*
	* Función que dado un id de un examen devuelve todas las preguntas (puntos, títulos y cuerpos) del examen
	*
	* @param int $idExamen id del examen

	* @return array $resultado devuelve el array con las preguntas
	*/
	public function getPreguntasExamen ($idExamen, $examenEntero, $preguntasSesion, $numTemas) {
		$resultado = [];

		for ($i = 1; $i <= $numTemas; $i++) {
			$preguntasTema = isset($preguntasSesion['preguntas']['tema'.$i])? $preguntasSesion['preguntas']['tema'.$i]: null;
			$sumaTema = 0;
			if ($preguntasTema) {
				foreach ($preguntasTema as $pregunta) {
					$resultado[] = json_decode('{"puntos": '.$pregunta["puntos"].', "pregunta": ""}', true);
				}
			}
		}

		$n = count($resultado);
		$i = 0;
		$sql = "SELECT preguntas.cuerpo FROM (preguntas INNER JOIN exam_preg ON preguntas.id=exam_preg.id_pregunta) INNER JOIN examenes on examenes.id=exam_preg.id_examen WHERE examenes.id='".$idExamen."'";
		$consulta=$this->query($sql);
    $count = 0;
    if((count($consulta) < 0)) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
             $resultado[$count]['pregunta'] = $consulta[$count]['preguntas']['cuerpo'];
             $count++;
		    }
    }

		return $resultado;
	}

  /*Función que devuelve los parámetros por defecto para generar un examen
	*
	* Función que dado un título de un examen devuelve los valores de los parámetros (espaciado, texto_inicial,
	* siglas, etc) de la asignatura para generar un examen
	*
	* @param string $tituloExamen titulo de un examen de la asignatura

	* @return array mysqli_fetch_assoc($consulta) devuelve el array con los parámetros
	*/
	public function getDefaultParameters ($tituloExamen) {
		$sql = "SELECT asignaturas.espaciado_defecto, asignaturas.texto_inicial, asignaturas.siglas, asignaturas.nombre, examenes.id as idExamen FROM asignaturas INNER JOIN examenes ON asignaturas.id=examenes.id_asig WHERE examenes.titulo='".$tituloExamen."'";
		$consulta=$this->query($sql);
    $count = 0;
    $resultado = [];
    if(count($consulta) < 0) {
			$resultado = null;
		}
    else{
		    while(count($consulta) > $count ){
             $resultado[] = $consulta[$count];
             $count++;
		    }
    }
    return $resultado[0];
	}

  public function construirPDF ($postInfo, $preguntasExamen) {
    $pdf = new PDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',11);
		$pdf->AliasNbPages();
		$pdf->cabecera();
		$pdf->SetTitle($_SESSION['nombreExamenGenerado'].".pdf");
		$pdf->SetFont('Arial','B',11);
	    $pdf->MultiCell(0,5,$postInfo['pautas'],1,'L');
	    $pdf->Ln(10);
	    if ($preguntasExamen) {
	    	$i=1;
	    	foreach ($preguntasExamen as $pregunta) {
	    		$pdf->Cell(5,5,$i.")","",0,'R');
	    		$pdf->MultiCell(0,5,"(".intval($pregunta['puntos'])." puntos) ".utf8_decode($pregunta['pregunta']));
	    		if ($postInfo['espaciado'] == 2) {
	    			$pdf->Ln(30);
	    		} else if ($postInfo['espaciado'] == 10) {
	    			$pdf->Ln(70);
	    		} else {
	    			$pdf->Ln(200);
	    		}


	    		$i++;
	    	}

	    } else {
	    	$_SESSION['error_no_existen_preguntas'] = true;
	    	header('Location: generarExamen.php?examen='.$_SESSION['nombreExamenGenerado']);
	    }


		$pdf->Output("I",$_SESSION['nombreExamenGenerado'].".pdf");
  }

}

/*
	* Definimos la clase PDF que nos sirve para establecer los parámetros de impresión del pdf a generar
	*/
	class PDF extends FPDF
	{
		function cabecera() {
			if (!isset($_POST['escudoFacultad'])) {
				$this->Cell(25,28,$this->Image('logosFacultades/ucm.png',13,13,20),"TLRB",0,'C');
			} else {
				$this->Cell(25,28,$this->Image('logosFacultades/'.$_POST['escudoFacultad'].'',13,13,20),"TLRB",0,'C');
			}
			$this->Cell(165,6,$_SESSION['asignaturaExamenGenerado'],"TR",0,'C');
			$this->Ln();
			$this->Cell(25,28," ","",0,'C');
			if (!isset($_POST['cuatrimestre']))
				$this->Cell(70,5,"","B",0,'R');
			else if ($_POST['cuatrimestre'] == 1)
				$this->Cell(70,5,"Parcial","B",0,'R');
			else if ($_POST['cuatrimestre'] == 2)
				$this->Cell(70,5,"Final","B",0,'R');

			$this->Cell(20,5,"Fecha:","B",0,'R');
			$this->SetFont('Arial','',11);
			if ($_POST['fecha']=="")
				$this->Cell(75,5,"_______________","RB",0,'L');
			else
				$this->Cell(75,5,$_POST['fecha'],"RB",0,'L');

			$this->SetFont('Arial','',11);
			$this->Ln();
			$this->Cell(25,28," ","",0,'C');
			if (isset($_POST['dni'])) {
				$this->Cell(165,9,"Nombre_______________________________________________  DNI_______________","R",0,'C');
			} else {
				$this->Cell(165,9,"Nombre__________________________________________________________________","R",0,'C');
			}
			$this->Ln();
			$this->Cell(25,28," ","",0,'C');
			$str = "Apellidos____________________________________________  ";
			$this->Cell(118,8,"   Apellidos____________________________________________","B",0,'L');
			if (!isset($_POST['grupo'])){
				$this->Cell(22,8,"Grupo_____","B",0,'L');
			}
			else {
				$this->Cell(10,8,"Grupo","B",0,'L');
				$this->SetFont('Arial','U',11);
				$this->Cell(12,8,"   ".utf8_decode($_POST['grupo'])."   ","B",0,'L');
				$this->SetFont('Arial','',11);
			}
			if (!isset($_POST['letra'])){
				$this->Cell(25,8,"Letra_____","RB",0,'L');
			}
			else {
				$this->Cell(10,8,"Letra","B",0,'L');
				$this->SetFont('Arial','U',11);
				$this->Cell(15,8,"    ".utf8_decode($_POST['letra'])."    ","RB",0,'L');
				$this->SetFont('Arial','',11);
			}
			$this->Ln(15);
		}

		function Footer() {
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
		}
	}
