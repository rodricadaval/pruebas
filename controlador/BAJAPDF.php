<?php
require_once "../ini.php";
require "../lib/fpdf/fpdf.php";

$datosTablet = Tablets::getDataMemorandumById($_GET['id_tablet']);
$descripcionBaja = $datosTablet['descripcion'];
$descripcion_area = Usuarios::dame_descripcion_area($datosTablet['id_usuario']);
$area = HTML_ENTITIES_DECODE::text_to_pdf_decode($datosTablet['sector']);
unset($_GET['id_tablet']);
$pdf = new PDF();
//Títulos de las columnas
$columnas = array('MEMORANDUM', '', 'SISTEMAS');
$pdf->AliasNbPages();
//Primera página
$pdf->AddPage();
$y = 45;
$pdf->SetY($y);
$pdf->SetX(130);
$pdf->TablaSimple($columnas);
$pdf->SetFont('Arial', 'B', 11);
$y += 30;
$pdf->SetY($y);
$pdf->SetX(25);
$pdf->Cell(0, 0, "AREA ".$area, 0, 1);
$y += 10;

if($area == "SESION"){
	$pdf->SetY($y);
	$pdf->SetX(25);
	$pdf->SetFont('Arial', 'I', 11);
	$pdf->Cell(0, 0, "Destino: ".$descripcion_area, 0, 1);
	$y += 10;		
}

$pdf->SetY($y);
$pdf->SetX(138);
$pdf->SetFont('Arial', 'I', 11);
$pdf->Cell(0, 0, 'Ref./ Devolucion de Equipamiento', 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(30);
$pdf->SetFont('Arial', '', 11);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode('Por la presente dejo constancia de la devolucion de los ítems que a continuación se detallan:');
$pdf->Cell(0, 0, $texto, 0, 1);
$i = 0;
$y += 15;
$pdf->SetY($y);
$pdf->SetX(35);
$pdf->SetFont('Arial', '', 11);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Tablet: ".$datosTablet['marca']." ".$datosTablet['modelo']."   Serie N° ".$datosTablet['num_serie']);
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$pdf->SetFont('Arial', '', 11);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Detalles: Procesador: ".$datosTablet['procesador']." Memoria ".$datosTablet['memoria']."   Disco ".$datosTablet['disco']);
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Se deja constancia de que los items presentan fallas/desperfectos en su");
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("funcionamiento y/o por otros motivos descriptos.");
$pdf->Cell(0, 0, $texto, 0, 1);

if($descripcionBaja != ""){
	$y += 10;
	$pdf->SetY($y);
	$pdf->SetX(35);
	$pdf->SetFont('Arial', 'B', 11);
	$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Motivo: ".$descripcionBaja);
	$pdf->Cell(0, 0, $texto, 0, 1);
}

$y += 10;
$pdf->SetY($y);
$pdf->SetX(35);
$pdf->SetFont('Arial', '', 11);

$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Sin otro motivo saludo atentamente.");
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(25);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("RECIBIO / FIRMA:");
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(25);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("ACLARACION:  ".$datosTablet['nombre_apellido']);
$pdf->Cell(0, 0, $texto, 0, 1);
if($area == "SESION"){
	$y += 5;
	$pdf->SetY($y);
	$pdf->SetX(35);
	$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("DNI: ");
	$pdf->Cell(0, 0, $texto, 0, 1);
}
$pdf->Output();
?>
