<?php
class PDF extends FPDF {
// Cabecera de página
	function Header()
	{
		// Logo
		$this->Image('../images/cabecera_memo.png', 0, 0, 215);
		// Arial bold 15
		$this->SetFont('Arial', 'BI', 9);
		$this->SetTextColor(120, 120, 120);
		// Movernos a la derecha
		$this->Cell(90);
		// Título
		$texto = HTML_ENTITIES_DECODE::text_to_pdf_decode('2015  – “Año del bicentenario del Congreso de los pueblos libres”.
	');
		$this->Cell(10, 37, $texto, 0, 1);
		// Salto de línea
		$this->Ln(20);
	}

// Pie de página
	function Footer()
	{
		$this->SetFont('Arial', '', 10);
		$footer = HTML_ENTITIES_DECODE::text_to_pdf_decode("Área Sistemas Informáticos Programa Sumar
			Ministerio de Salud de la Nación");
		$this->SetY(-50);
		$this->SetX(75);
		$this->MultiCell(60, 4, $footer, 0, 'C');
		$this->SetY(-52);
		$this->SetX(20);
		$this->SetDrawColor(224, 224, 224);
		$this->Cell(170, 17, '', 1, 0, 'C');
		$this->SetFont('Arial', '', 8);
		$footer = HTML_ENTITIES_DECODE::text_to_pdf_decode("Ministerio de Salud de la Nación
			Av. 9 de Julio 1925 - Piso 12 – Ciudad Autónoma de Buenos Aires – C1073ABA
			Tel/Fax: 011-4344-4300 ");
		$this->SetY(-20);
		$this->SetX(40);
		$this->MultiCell(130, 4, $footer, 0, 'C');

		// Posición: a 1,5 cm del final
		$this->SetY(-11);
		$this->SetX(-15);
		// Arial italic 8
		$this->SetFont('Arial', 'I', 8);
		// Número de página
		$this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
	}

	//Tabla simple
	function TablaSimple($header)
	{
		$this->SetFont('Arial', 'B', 10);
		$this->SetDrawColor(120, 120, 120);
		$this->Cell(30, 7, $header[0], 1, 0, 'C');
		$this->Cell(6, 7, $header[1], 1, 0, 'C');
		$this->SetFont('Arial', 'B', 9);
		$this->Cell(30, 7, $header[2], 1, 0, 'C');
		$this->Ln();
		$this->SetX(130);
		$this->Cell(30, 5, "", 1, 0, 'C');
		$this->Cell(6, 5, "", 1, 0, 'C');
		$this->Cell(30, 5, "", 1, 0, 'C');
		$this->Ln();
		$this->SetX(130);
		$this->Cell(30, 5, "Buenos Aires", 1, 0, 'C');
		$this->Cell(6, 5, "", 1, 0, 'C');
		$this->Cell(30, 5, date('d-m-y'), 1, 0, 'C');
	}

	//Tabla coloreada
	function TablaColores($header)
	{
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(255, 0, 0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(.3);
		$this->SetFont('', 'B');
		//Cabecera
		for ($i = 0; $i < count($header); $i++)
		{
			$this->Cell(40, 7, $header[$i], 1, 0, 'C', 1);
		}

		$this->Ln();
		//Restauración de colores y fuentes
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Datos
		$fill = false;
		$this->Cell(40, 6, "hola", 'LR', 0, 'L', $fill);
		$this->Cell(40, 6, "hola2", 'LR', 0, 'L', $fill);
		$this->Cell(40, 6, "hola3", 'LR', 0, 'R', $fill);
		$this->Cell(40, 6, "hola4", 'LR', 0, 'R', $fill);
		$this->Ln();
		$fill =  ! $fill;
		$this->Cell(40, 6, "col", 'LR', 0, 'L', $fill);
		$this->Cell(40, 6, "col2", 'LR', 0, 'L', $fill);
		$this->Cell(40, 6, "col3", 'LR', 0, 'R', $fill);
		$this->Cell(40, 6, "col4", 'LR', 0, 'R', $fill);
		$fill = true;
		$this->Ln();
		$this->Cell(160, 0, '', 'T');
	}
}
?>