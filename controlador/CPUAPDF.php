<?php
require_once "../ini.php";
require "../lib/fpdf/fpdf.php";

$datosCpu = Computadoras::getConSectorById($_GET['id_computadora']);
$nombreYApellido = Usuarios::getNombreDePila($datosCpu['id_usuario']);
$descripcion_area = Usuarios::dame_descripcion_area($datosCpu['id_usuario']);
$area = Areas::getNombre($datosCpu['id_sector']);
$area = HTML_ENTITIES_DECODE::text_to_pdf_decode($area);
unset($_GET['id_computadora']);
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
$pdf->Cell(0, 0, 'Ref./ Entrega de Equipamiento', 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(30);
$pdf->SetFont('Arial', '', 11);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode('Por la presente dejo constancia de la remisión de los ítems que a continuación se detallan:');
$pdf->Cell(0, 0, $texto, 0, 1);
$i = 0;
$y += 15;
foreach ($_GET as $key => $value)
{
	$datos[$i] = Vinculos::getByID($value);
	$pdf->SetY($y);
	$pdf->SetX(35);
	$pdf->SetFont('Arial', '', 11);
	switch ($datos[$i]['producto'])
	{
		case 'Computadora':
			$tipos = Tipos_Computadoras::get_rel_campos();
			$clase = $datos[$i]['clase'];
			$tipo_producto = array_search($clase, $tipos);
			$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode($tipo_producto.": ".$datos[$i]['marca']." ".$datos[$i]['modelo']."   Serie N° ".$datos[$i]['num_serie']);
			$pdf->Cell(0, 0, $texto, 0, 1);
			break;
		case 'Monitor':
			$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode($datos[$i]['producto'].": ".$datos[$i]['marca']." ".$datos[$i]['modelo']."   Serie N° ".$datos[$i]['num_serie']);
			$pdf->Cell(0, 0, $texto, 0, 1);
			break;
		case 'Disco':
			$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode($datos[$i]['producto'].": ".$datos[$i]['marca']."  ".$datos[$i]['capacidad'].$datos[$i]['unidad']);
			$pdf->Cell(0, 0, $texto, 0, 1);
			break;
		case 'Memoria':
			$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode($datos[$i]['producto'].": ".$datos[$i]['marca']."  ".$datos[$i]['tipo']."  ".$datos[$i]['capacidad'].$datos[$i]['unidad']);
			$pdf->Cell(0, 0, $texto, 0, 1);
			break;

		default:
			# code...
			break;
	}
	$y += 5;
}
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$pdf->Cell(0, 0, "Total: ".count($_GET), 0, 1);
$y += 10;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("También  se  deja  constancia  de  haber  recibido  las  partes en perfecto estado de");
$pdf->Cell(0, 0, $texto, 0, 1);

if($area == "SESION"){
	$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("funcionamiento. No obstante, en caso de algún fallo de fabricación me contactaré con");
$pdf->Cell(0, 0, $texto, 0, 1);
	$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("la garantía correspondiente. En el caso de ser equipamiento fuera de garantía, me");
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("responsabilizo enteramente de su cuidado y manutención.");
$pdf->Cell(0, 0, $texto, 0, 1);
}
else{
	$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("funcionamiento y me responsabilizo de su cuidado. Y por cualquier tipo de cambio o");
$pdf->Cell(0, 0, $texto, 0, 1);
$y += 5;
$pdf->SetY($y);
$pdf->SetX(35);
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("defecto me contactaré con el Área de Sistemas Informáticos.");
$pdf->Cell(0, 0, $texto, 0, 1);	
}

$y += 10;
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
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("ACLARACION:  ".$nombreYApellido);
$pdf->Cell(0, 0, $texto, 0, 1);
$pdf->Output();
?>
