<?php
require('fpdf.php');

class PDF extends FPDF
{
	function FichaAtendimento($dados)
	{
	//	$this->SetFont('Arial','',10);
	//	$this->MultiCell(0,5,utf8_decode($data[$language]['data']),0,'C');
	//	$this->Ln(4);
	//	$this->Cell(48,15,'',0,0);

		$data['ordem'] = $_GET['numeroDaOrdem'];
		$data['matricula'] = $_GET['matricula'];
		$data['paciente'] = $_GET['nomeDoMenbro'];
		$data['medico'] = 'climep';
		$this->SetFont('Arial','B',14);
		$this->MultiCell(0,5,utf8_decode('Ficha Atendimento'),0,'L');
		$this->SetFont('Arial','',10);
		$this->Cell(15,5,'Ordem:',0,0);
		$this->Cell(15,5,utf8_decode($data['ordem']),0,0);
		$this->Cell(18,5,'Matricula:',0,0);
		$this->Cell(15,5,utf8_decode($data['matricula']),0,1);
		$this->Cell(30,5,'do dia:',0,0,'R');
		$this->Cell(15,5,date('d/m/Y'),0,1);
		$this->Cell(15,5,utf8_decode('Médico:'),0,0);
		$this->Cell(0,5,utf8_decode(strtoupper($data['medico'])),0,1);
		$this->Cell(16,5,'Paciente:',0,0);
		$this->Cell(0,5,utf8_decode(strtoupper($data['paciente'])),0,1);

		$this->Cell(12,5,'Altura:',0,0);
		$this->Cell(2,5,'',0,0);
		$this->Cell(10,5,'','B',0);
		$this->Cell(2,5,'',0,0);

		$this->Cell(11,5,'Peso:',0,0);
		$this->Cell(2,5,'',0,0);
		$this->Cell(10,5,'','B',0);
		$this->Cell(2,5,'',0,0);

		$this->Cell(8,5,'PA:',0,0);
		$this->Cell(2,5,'',0,0);
		$this->Cell(10,5,'','B',1);

		$this->SetFont('Arial','',6);
		$this->Cell(65,5,utf8_decode('Procedimentos'),'B',1);

		$this->SetFont('Arial','',10);
		
		foreach($_GET['nomes'] as $valor){
			$this->Cell(65,5,utf8_decode(strtoupper($valor)),0,1);
		}
		
		$this->SetFont('Arial','',6);
		$this->Cell(65,5,utf8_decode('Clinica de Medicina Preventiva - Climep'),'T',0,'C');
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial','',10);
//$pdf->SetMargins(20,20,20);
$pdf->AddPage();
$dados = Array();
$pdf->FichaAtendimento($dados);
$pdf->Output("FichaAtendimento.pdf","I");
?>
