<?php
session_start();
require('fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function MapaConsumo($dados) {

		$this->SetFont('Arial','B',15);
		$this->Cell(0,5,utf8_decode('Mapa de Consumo de Estoque'),0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(0,5,date("d/m/Y"),0,1,'R');
		$this->SetFont('Arial','',8);
		$this->Cell(0,5,utf8_decode('Período de '.converteData($dados['dataInicio']).' a '.converteData($dados['dataFim'])),0,0,'C');

		$this->Ln(5);

		$this->SetFont('Arial','B',10);
		$this->Cell(23,5,utf8_decode('Produto'),1,0,'C');
		$this->Cell(23,5,utf8_decode('Lote'),1,0,'C');
		$this->Cell(21,5,utf8_decode('Pacotes'),1,0,'C');
		$this->Cell(21,5,utf8_decode('Total'),1,0,'C');
		$this->Cell(21,5,utf8_decode('Consumo'),1,0,'C');
		$this->Cell(21,5,utf8_decode('Entrada'),1,0,'C');

		for($i=0;$i<=6;$i++){
			$this->Cell(21,5,date("d/m/Y",strtotime($dados['dataInicio']." + $i days")),1,0,'C');
		}
		$this->Ln(5);

		$this->SetFont('Arial','',7);

		foreach($dados['material'] as $material){

			$this->Cell(23,5,utf8_decode($material['nome']),1,0,'C');
			$this->Cell(23,5,utf8_decode($material['lote']),1,0,'C');
			$this->Cell(21,5,utf8_decode($material['pacote']),1,0,'C');
			$this->Cell(21,5,utf8_decode($material['total']),1,0,'C');
			$this->Cell(21,5,utf8_decode($material['consumo']),1,0,'C');
			$this->Cell(21,5,utf8_decode($material['entrada']),1,0,'C');

			for($i=0;$i<=6;$i++){
				$this->Cell(21,5,utf8_decode($material['data'][$i]),1,0,'C');
			}

			$this->Ln(5);
		}
	}
}

$pdf = new PDF();
// Column headings
$pdf->SetFont('Arial','',10);
//$pdf->SetMargins(20,20,20);
$pdf->AddPage("L","A4");
$dados = $_SESSION['impressaoMpConsumo'];
$pdf->MapaConsumo($dados);
$pdf->Output("MapaConsumo.pdf","I");