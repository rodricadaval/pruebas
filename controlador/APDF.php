<?php 
	require_once "../ini.php";
	require "../lib/fpdf/fpdf.php";

 class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../images/cabecera_memo.png',0,0,215);
    // Arial bold 15
    $this->SetFont('Arial','BI',9);
    $this->SetTextColor(120,120,120);
    // Movernos a la derecha
    $this->Cell(25);
    // Título
    $texto = HTML_ENTITIES_DECODE::text_to_pdf_decode('2014  – “Año de Homenaje al Almirante Guillermo Brown, en el Bicentenario del Combate Naval de Montevideo”.
');
    $this->Cell(10,37,$texto,0,1);
    // Salto de línea
    $this->Ln(20);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(40,20,'MEMORANDUM',1,1,'C');
for($i=1;$i<=10;$i++)
    $pdf->Cell(0,10,'Imprimiendo linea numero '.$i,0,1);
$pdf->Output();
?>

 ?>