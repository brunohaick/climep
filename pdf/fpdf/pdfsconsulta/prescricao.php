<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');
//conectar();

class PDF extends FPDF {
        
	function prescricao_header() {

		//$this->SetY("-1");
//		$this->SetAutoPageBreak(1, 1);
                $prescricaoArray = $_SESSION['prescricao']['prescricaoArray'];
                		
                
		$this->SetFont('Arial', 'B', 10);
		$this->AddPage();
		$this->Cell(95,5,utf8_decode(strtoupper($prescricaoArray['nome'])), 0, 0, 'L');
		$this->Cell(35,5,utf8_decode($prescricaoArray['matricula']), 0, 0, 'C');
		$this->Cell(0,5,utf8_decode($prescricaoArray['data']), 0, 0, 'R');
		$this->Ln();
                $this->Cell(95,5,utf8_decode(strtoupper($prescricaoArray['endereco'])), 0, 0, 'L');
                $this->Ln();
		$this->Cell(0,5,'', 'B', 1, 'R');
		$this->Ln(5);

		$this->SetFont('Arial', '', 10);
	}

	function prescricao() {

		$this->prescricao_header();
		$b = 0;

		$prescricaoArray = $_SESSION['prescricao']['prescricaoArray'];                
                $prescricoes = $prescricaoArray['prescricao'];
		foreach($prescricoes as $prescricao) {

			if($a == 0){

				if($b == 1) {
					$this->Cell(80,5,'', 0, 0, 'R');
					$this->MultiCell(70,4,utf8_decode($prescricao),0,'J');

				} else {
					$this->MultiCell(70,4,utf8_decode($prescricao),0,'J');
				}


				if($this->GetY() > 250) {
					$this->SetY("23");
					if($b == 1) {
						$b = 0;
						$a = 1;
					}
					$b=1;
				}

			} else {
				$this->prescricao_header();
				$this->MultiCell(70,4,utf8_decode($prescricao),0,'J');
				$b = 0;
				$a = 0;

			}
			$this->Ln(2);
		}
	}

}

$pdf = new PDF();
$pdf->SetMargins(40, 10, 20);
$pdf->prescricao();
$pdf->Output("prescricoes.pdf", "I");