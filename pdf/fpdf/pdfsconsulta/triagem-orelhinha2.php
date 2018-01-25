<?php

session_start();
require('../fpdf.php');
//require('./Bootstrap.php');
//conectar();

class PDF extends FPDF {

	function triagem_coracaozinho($dados) {

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);


		$dados['nome'] = "Marcus Vinicius de Oliveira Dias";
		$dados['nascimento'] = "03mar75";
		$dados['idade'] = "38a 6m";
		$this->SetFont('Arial', '', 12);
		$this->AddPage();
		$this->Image("triagem-orelhinha2.png",0,0,210,298);


		$this->Ln(54.5);
		$this->Cell(30,5,'', 0, 0, 'L');
		$this->Cell(0,5,utf8_decode(strtoupper($dados['nome'])), 0, 0, 'L');

		$this->Ln(7);
		$this->Cell(27,5,'', 0, 0, 'L');
		$this->Cell(62,5,date('d/m/Y'), 0, 0, 'L');
		$this->Cell(70,5,utf8_decode(strtoupper($dados['idade'])), 0, 0, 'L');

		//$dados['conclusoes'] = 'conclusao';
		//$this->SetFont('Arial', '', 10);
		//$this->MultiCell(153,4,utf8_decode($dados['conclusoes']),0,'J');

	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->triagem_coracaozinho($_SESSION['editaPessoaClienteDados']);
$pdf->Output("fichaimuno.pdf", "I");
?>
