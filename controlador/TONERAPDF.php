<?php
require_once "../ini.php";
require "../lib/fpdf/fpdf.php";
$data = Toners::getDataMemorandumById($_GET['id_toner']);
$area = HTML_ENTITIES_DECODE::text_to_pdf_decode($data['area']);
unset($_GET['id_toner']);
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

$pdf->SetY($y);
$pdf->SetX(138);
$pdf->SetFont('Arial', 'I', 11);
$pdf->Cell(0, 0, 'Ref./ Entrega de insumos', 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(30);
$pdf->SetFont('Arial', '', 11);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode('Por la presente dejo constancia de la remisión de los ítems que a continuación se detallan:');
$pdf->Cell(0, 0, $texto, 0, 1);
$i = 0;
$y += 15;
$pdf->SetY($y);
$pdf->SetX(35);
$pdf->SetFont('Arial', '', 11);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Toners: Para la impresora " .$data['impresora'].".");
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("Cantidad: ".$data['cantidad']);
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
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
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("ACLARACION: ");
$pdf->Cell(0, 0, $texto, 0, 1);
if($area == "SESION") {
    $y += 5;
    $pdf->SetY($y);
    $pdf->SetX(25);
    $texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("DNI: ");
    $pdf->Cell(0, 0, $texto, 0, 1);
}
$pdf->Output();
?>
