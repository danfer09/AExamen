<?php
require('../fpdf.php');

class PDF extends FPDF
{
// Cargar los datos
function LoadData($file)
{
	// Leer las l�neas del fichero
	$lines = file($file);
	$data = array();
	foreach($lines as $line)
		$data[] = explode(';',trim($line));
	return $data;
}

// Tabla simple
function BasicTable($header, $data)
{
	// Cabecera
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	// Datos
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

// Una tabla m�s completa
function ImprovedTable($header, $data)
{
	// Anchuras de las columnas
	// Cabeceras
	$this->Cell(25,28,$this->Image('../../logosFacultades/ucm.png',13,13,20),"TLRB",0,'C');
	$this->SetFont('Arial','B',11);
	$this->Cell(165,6,"Asignatura","TR",0,'C');
	$this->Ln();
	$this->Cell(25,28," ","",0,'C');
	$this->Cell(165,5,"Cuatrimestre","RB",0,'C');
	$this->SetFont('Arial','',11);
	$this->Ln();
	$this->Cell(25,28," ","",0,'C');
	//$this->Cell(165,8,"Nombre________________________________________________________________","R",0,'C');
	$this->Cell(165,9,"Nombre_______________________________________________  DNI______________","R",0,'C');
	$this->Ln();
	$this->Cell(25,28," ","",0,'C');
	$this->Cell(165,8,"Apellidos____________________________________________  Grupo_____  Letra_____","RB",0,'C');
	
	//$this->Ln();
	// Datos
	/*foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR');
		$this->Cell($w[1],6,$row[1],'LR');
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		$this->Ln();
	}*/
	// L�nea de cierre
	//$this->Cell(array_sum($w),0,'','T');
}

// Tabla coloreada
function FancyTable($header, $data)
{
	// Colores, ancho de l�nea y fuente en negrita
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	// Cabecera
	$w = array(40, 35, 45, 40);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	// Restauraci�n de colores y fuentes
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	// Datos
	$fill = false;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
		$this->Ln();
		$fill = !$fill;
	}
	// L�nea de cierre
	$this->Cell(array_sum($w),0,'','T');
}
}

$pdf = new PDF();
// T�tulos de las columnas
$header = array('Pa�s', 'Capital', 'Superficie (km2)', 'Pobl. (en miles)');
// Carga de datos
$data = $pdf->LoadData('paises.txt');
$pdf->SetFont('Arial','',14);
//$pdf->AddPage();
//$pdf->BasicTable($header,$data);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);
$pdf->AddPage();
$pdf->FancyTable($header,$data);
$pdf->Output();
?>
