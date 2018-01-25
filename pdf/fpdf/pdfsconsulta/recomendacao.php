<?php

session_start();
require('../fpdf.php');
//require('./Bootstrap.php');
//conectar();

class PDF extends FPDF {

	function recomendacao($dados) {

		//$this->SetY("-1");
//		$this->SetAutoPageBreak(1, 1);

		$dados = "O diretor da diretoria  bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla i\n  bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla ba bla bla bla bla b\n";
		$this->AddPage();
		$this->MultiCell(0,4,utf8_decode($dados),0,'J');
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(40, 10, 20);
$pdf->recomendacao($_SESSION['editaPessoaClienteDados']);
$pdf->Output("fichaimuno.pdf", "I");
?>
