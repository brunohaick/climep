<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function listaMedicos() {

		$this->SetY("-1");
		$this->SetFont('Arial', '', 13);
		$this->Cell(0, 5, '', 0, 1, 'C');
		$this->Ln(15);
		$this->Cell(0, 5, utf8_decode("Médicos parceiros da CLIMEP"), 0, 1, 'C');

		$this->SetFont('Arial', '', 9);
		$this->Ln(20);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(160,4,utf8_decode("6430 - SHEYLA SANTOS MENDES - TV. RUI BARBOSA, 1180 NAZARÉ - 8111-3200 / 3183-4300"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("2510 - MARIA DA GRAÇA SINIMBÚ DE LIMA FONSECA - R BERNAL DO COUTO, 326 UMARIZAL - 3212-0258 / 3224-2819"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("5081 - LEILA CAMPOS MUTRAN - TV APINAGÉS, 569 - 3230-3108 / 8153-9769"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("6367 - ANNA VALERIA VERAS FONSECA - RUA FERREIRA CANTÃO, 421 - 3033-1707 / 3033-1707"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("2687 - VANIA MARIA PALETO COLARES - AV. JOSÉ BONIFÁCIO, 1035 - 9114-3134 / 3223-2060"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("3720 - REJANE SILVA CAVALCANTE - TV. DOM ROMUALDO DE SEIXAS, 606 - 4008-9535 / 8134-9906"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("3981 - ELAINE XAVIER PRESTES - RUA ANTONIO BARRETO, 130 SL 801 - 9982-3980 - 884 / 3241-3056"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("5556 - ALINE TAVARES BANDEIRA LOPES - AV. CONSELHEIRO FURTADO 2391/101 - 3229-0353 / 9983-3175"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("3050 - CRISTINA MARIA F. ALVES DA SILVA - TV NOVE DE JANEIRO 2110 SL 504/505 - 8134-9954 / 3229-5203"), 0, 0, 'L');
		$this->Ln(7);
		$this->Cell(10,4,'', 0, 0, 'C');
		$this->Cell(64, 5, utf8_decode("9419 - NADIA MARIA OLIVEIRA HOLLANDA CECIM - RUA DOS MUNDURUCUS, 3959 - 3344-6700 / 33447-6700"), 0, 0, 'L');
	}
}

$pdf = new PDF();
$pdf->SetMargins(0, 0, 0);
$pdf->listaMedicos();
$pdf->Output("listagem_medicos_coracao.pdf", "I");