<?php
require_once "../ini.php";
require "../lib/fpdf/fpdf.php";

$datosCpu = Computadoras::getConSectorById($_GET['id_computadora']);
$nombreYApellido = Usuarios::getNombreDePila($datosCpu['id_usuario']);
$descripcion_area = Usuarios::dame_descripcion_area($datosCpu['id_usuario']);
$descripcionBaja = $datosCpu['descripcion'];
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
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode('Por la presente dejo constancia de la devolucion de los ítems que a continuación se detallan:');
$pdf->Cell(0, 0, $texto, 0, 1);
$i = 0;
$y += 15;foreach ($_GET as $key => $value)
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
$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode("ACLARACION:  ".$nombreYApellido);
$pdf->Cell(0, 0, $texto, 0, 1);
$pdf->Output();
?>
