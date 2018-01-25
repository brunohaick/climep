<?php
session_start();
require('fpdf.php');
require('./Bootstrap.php');

class PDF extends FPDF {

	function Carta($dados) {
		$dados['nome'] = $dados['nome']." ".$dados['sobrenome'];
		$this->Ln(60);
		$this->SetFont('Arial','',15);
		$this->Cell(140,5,'',0,0,'L');
		$this->Cell(70,8,utf8_decode(strtoupper($dados['nome'])),0,1,'L');
		$this->SetFont('Arial','',10);
		$this->Cell(140,8,'',0,0,'L');
		$this->Cell(70,8,utf8_decode($dados['endereco'].', '.$dados['numero'].' - '.$dados['bairro']),0,1,'L');
		$this->Cell(140,8,'',0,0,'L');
		$this->Cell(70,8,utf8_decode($dados['cidade'].' - '.$dados['estado'].' - '.$dados['cep']),0,1,'L');
		$this->Ln(25);
		$this->SetFont('Arial','',8);
		$this->Cell(0,5,utf8_decode($dados['matricula']),0,1,'C');
	}
}

$pdf = new PDF();
// Column headings
$pdf->SetFont('Arial','',10);
//$pdf->SetMargins(50,50,50);
$pdf->AddPage("L","A4");
$dados = $_SESSION['editaPessoaClienteDados'];
//var_dump($dados);
$pdf->Carta($dados);
$pdf->Output("Carta.pdf","I");