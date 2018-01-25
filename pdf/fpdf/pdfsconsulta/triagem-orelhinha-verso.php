<?php

session_start();
require('../fpdf.php');
//require('./Bootstrap.php');
//conectar();

class PDF extends FPDF {

	function triagem_coracaozinho($dados) {

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		$dados['freq_od_1'] = "X";
		$dados['freq_od_2'] = "";
		$dados['freq_od_3'] = "";
		$dados['freq_od_4'] = "";
		$dados['freq_od_5'] = "";

		$dados['freq_oe_1'] = "";
		$dados['freq_oe_2'] = "";
		$dados['freq_oe_3'] = "";
		$dados['freq_oe_4'] = "X";
		$dados['freq_oe_5'] = "";
		$this->SetFont('Arial', '', 10);
		$this->AddPage();
		$this->Image("triagem-orelhinha-verso.png",0,0,210,298);

		$this->Ln(63);
		$this->Cell(62,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_od_1'], 0, 0, 'L');
		$this->Cell(11.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_od_2'], 0, 0, 'L');
		$this->Cell(12,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_od_3'], 0, 0, 'L');
		$this->Cell(10.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_od_4'], 0, 0, 'L');
		$this->Cell(10.2,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_od_5'], 0, 0, 'L');

		$this->Ln(18);

		$this->Cell(62,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_oe_1'], 0, 0, 'L');
		$this->Cell(11.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_oe_2'], 0, 0, 'L');
		$this->Cell(12,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_oe_3'], 0, 0, 'L');
		$this->Cell(10.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_oe_4'], 0, 0, 'L');
		$this->Cell(10.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,$dados['freq_oe_5'], 0, 0, 'L');

		$this->Ln(38.5);
		$this->Cell(44,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(54,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(9);

		$this->Cell(43.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(54.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(34);

		$this->Cell(33.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(38.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(40.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(9);

		$this->Cell(27.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(52.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(9);

		$this->Cell(95.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(13,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(9);

		$this->Cell(58.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(33,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(7);

		$this->Cell(55.5,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(27,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(21,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(20);
		$this->Cell(10,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');

		$this->Ln(7);
		$this->Cell(10,5,'', 0, 0, 'L');
		$this->Cell(4,5,'X', 0, 0, 'L');
		$this->Cell(67,5,'', 0, 0, 'L');
		$this->Cell(4,5,'9', 0, 0, 'L');


	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->triagem_coracaozinho($_SESSION['editaPessoaClienteDados']);
$pdf->Output("fichaimuno.pdf", "I");
?>
