<?php
//session_start();
require('fpdf.php');
require('Bootstrap.php');

//print_r($_SESSION['controle']);
//
//exit;

class PDF extends FPDF
{

	function Controle($dados)
	{
		$controle = $_SESSION['controle'];
		$progs = $_SESSION['controle']['progs'];

		$dados['atendente'] = $controle['usuario'];
		$dados['data'] = date('d/m/y');
		$dados['hora'] = $controle['hora'];
		$dados['id_controle'] = $controle['numeroControle'];
		$dados['matricula_titular'] = $controle['titular']['matricula'];
		$dados['titular'] = $controle['titular']['nome'].' '.$controle['titular']['sobrenome'];
		$dados['pagamento-convenio'] = $controle['titular']['categoria'];

		for($i = 0; $i < count($progs); $i++) {
			$material = $progs[$i]['material'];
			$dados['dependente'][$i]['matricula_dependente'] = $progs[$i]['membro'];
			$dados['dependente'][$i]['nascimento_dependente'] = $progs[$i]['idade'];
			$dados['dependente'][$i]['dependente'] = $progs[$i]['nome'];

			for($j = 0; $j < count($material); $j++) {
				$a = explode("/",$material[$j]);
				$dados['dependente'][$i]['controle'][$j]['vacina'] = $a[0];
				$dados['dependente'][$i]['controle'][$j]['status_pagamento'] = $a[1];
			}
		}

		$this->Cell(50,5,utf8_decode($dados['atendente']),0,0,'J');
		$this->Cell(22,5,utf8_decode($dados['data']),0,0,'J');
		$this->Cell(19,5,utf8_decode($dados['hora']),0,0,'J');
		$this->SetFont('Arial','B',10);
		$this->Cell(61,9,utf8_decode(' CLIMEP - CONTROLE INTERNO Nº'),'TBL',0,'J');
		$this->Cell(0,9,utf8_decode($dados['id_controle']),'TBR',0,'J');
		$this->Ln(15);
		$this->Cell(20,5,utf8_decode($dados['matricula_titular']),0,0,'J');
		$this->SetFont('Arial','',10);
		$this->Cell(100,5,strtoupper(utf8_decode($dados['titular'])),0,0,'J');
		$this->SetFont('Arial','B',16);
		$this->Cell(0,5,strtoupper(utf8_decode($dados['pagamento-convenio'])),0,0,'J');
		$this->Ln(10);

		$this->SetFont('Arial','',10);

		foreach($dados['dependente'] as $dependente){
			$this->SetFont('Arial','B',10);
			$this->Cell(20,5,utf8_decode($dependente['matricula_dependente']),0,0,'R');
			$this->SetFillColor(190,190,190);
			$this->Cell(100,5,strtoupper(utf8_decode($dependente['dependente'])),0,0,'J','true');
			$this->Cell(0,5,utf8_decode($dependente['nascimento_dependente']),0,0,'J');
			$this->Ln(10);

			foreach($dependente['controle'] as $controle) {
			
				$this->SetFont('Arial','B',11);
				$this->Cell(30,5,'',0,0,'J');
				$this->Cell(7,5,utf8_decode('--> '),0,0,'J');
				$this->SetFont('Arial','',10);
				$this->Cell(80,5,utf8_decode($controle['vacina']),0,0,'J');
				$this->Cell(0,5,strtoupper(utf8_decode($controle['status_pagamento'])),0,1,'J');
			
			}

			$this->Ln(5);
			$this->Cell(0,5,'','T',0,'J');
			$this->Ln(5);
		}

		$this->Ln(5);
		$this->Cell(8,6,'',0,0);
		$this->Cell(70,6,'','TLR',0,'C');
		$this->Cell(4,6,'',0,0);
		$this->Cell(70,6,'','TLR',1,'C');


		$this->Cell(8,6,'',0,0);
		$this->Cell(70,6,'','LRB',0,'J');
		$this->Cell(4,6,'',0,0);
		$this->Cell(70,6,'','LRB',0,'J');

	}


}

$pdf = new PDF();
// Column headings
$header = array('Controle','Matricula','Nome','FP', 'Valor','Parcelas','Data','Total');
// Data loading
//$dados = $_SESSION['PDF']['resumoCaixa'];
$dados = ResumoCaixaPDF();
$pdf->SetFont('Arial','',10);
$pdf->SetMargins(20,20,20);
$pdf->AddPage();
$language = 'pt';
$dados = Array();
$pdf->Controle($dados);
$pdf->Output();
$pdf->Output("CertificadoVacinacao.pdf","I");
?>
