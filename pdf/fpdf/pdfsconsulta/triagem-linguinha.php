<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function triagem_linguinha() {

//		die(print_r($_SESSION['triagem']));
		$linguaArr = $_SESSION['triagem']['linguaArr'];
		$cliente = $_SESSION['triagem']['linguaArr']['cliente'];
		$linArr = $_SESSION['triagem']['linguaArr']['consulta'];
		
		$medico = $_SESSION['triagem']['linguaArr']['medico'];
		$dados['medico'] = $medico['nome'] . ' ' . $medico['sobrenome'];
		$dados['crm'] = "\nCRM " . $medico['crm'];
		
		$linguaArr['gestacao'] = $linArr[0];
		$linguaArr['parto'] = $linArr[1];
		$linguaArr['idadeGestacional'] = $linArr[2];
		$linguaArr['apgar'] = $linArr[3];
		$linguaArr['pesoNascimento'] = $linArr[4];

		$dados['nome'] = $cliente['nome'];
		$dados['nascimento'] = $cliente['nascimento'];
		$dados['idade'] = $cliente['Idade'];

		if ($linguaArr['gestacao'] == 1) {
			$dados['gestacao_normal'] = "X";
		} else if ($linguaArr['gestacao'] == 2) {
			$dados['gestacao_alterada'] = "X";
		}

		if ($linguaArr['parto'] == 1) {
			$dados['parto_normal'] = "X";
		} else if ($linguaArr['parto'] == 2) {
			$dados['parto_cesariano'] = "X";
		}		

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		$dados['cdnasc_idade'] = $linguaArr['idadeGestacional'];
		$dados['cdnasc_peso'] = $linguaArr['pesoNascimento'];
		$dados['cdnasc_apgar'] = $linguaArr['apgar'];

		$dados['anotacao'] = "Anatoações \n\n";
		
		$qp0 = $linguaArr['lingua_anot_qp'][0];
		$posicao = 47.5;
		$soma = 5 * count($qp0);
		$posicao = $posicao + $soma;
		
		foreach($qp0 as $anotacao) {
			if(stripos($anotacao, "Dificuldade") !== false) {
				$qp2 = $linguaArr['lingua_anot_qp'][1];
				if(isset($qp2[0])){
					$tmp = implode($qp2, ', ');
				}
				$str_qp2 = $anotacao . ' (' . $tmp . ')';
			} else {
				$str_qp .= $anotacao . " \n ";
			}
		}
		
		if(!empty($str_qp2)) {
			$str_qp .= $str_qp2;
		}
	
		if(!empty($str_qp)) {
			$dados['anotacao'] .= $str_qp;
		}
		
		$dados['resultado'] = "";

		if(!empty($linguaArr['freioLingualNormal'])) {
			$arr[] = $linguaArr['freioLingualNormal'];
		}
		
		if(!empty($linguaArr['freioLingualCurto'])) {
			$arr[] = $linguaArr['freioLingualCurto'];
		}
		
		if(!empty($linguaArr['insercaoAnteriorizada'])) {
			$arr[] = $linguaArr['insercaoAnteriorizada'];
		}
		
		if(!empty($linguaArr['anquiloglossia'])) {
			$arr[] = $linguaArr['anquiloglossia'];
			
		}

		$dados['resultado'] = implode($arr, "\n");
		$dados['outros_exames'] = $linguaArr['outrosExames'];
		$dados['conclusoes'] = $linguaArr['conclusaoTeste'];

		$this->SetFont('Arial', 'B', 14);
		$this->AddPage();
		$this->Image("pdf/fpdf/pdfsconsulta/triagem-linguinha1.png",0,2);
		$this->Image("pdf/fpdf/pdfsconsulta/triagem-linguinha2.png",-0.1,$posicao);
		$this->Ln(7);
		$this->Cell(5,5,'', 0, 0, 'L');
		$this->Cell(170,5,utf8_decode(strtoupper($dados['nome'])), 0, 0, 'L');
//		/* Titulo */
//		$this->SetFont('Helvetica', 'B', 14);
//		$this->Ln(10);
//		$this->Cell(5,4,'', 0, 0, 'L');
//		$this->Cell(197,5,utf8_decode("Dados da gestação, parto e nascimento"), 1, 0, 'J');
//
//		/* gestacao parto condicoe de nascimento */
//		$this->SetFont('Helvetica', 'B', 12);
//		$this->Ln(25);
//		$this->Cell(5,4,'', 0, 0, 'L');
//		$this->Cell(37.5,4.5,utf8_decode("gestação"), 1, 0, 'C');
//		$this->Cell(38.5,4.5,utf8_decode("parto"), 1, 0, 'C');
//		$this->Cell(77,4.5,utf8_decode("condições de nascimento"), 1, 0, 'C');
//		$this->SetFont('Helvetica', 'B', 11);
//		$y = $this->GetY();
//		$this->MultiCell(24,2.9,utf8_decode("data de \n nascimento \n"), 1, 'C');
//		$this->SetXY(182, $y);
//		$this->MultiCell(20,3.88,utf8_decode("idade \n atual \n (dias)"), 1, 'C');
//
//		$this->SetFont('Helvetica', '', 10);
//		$y = $this->GetY();
//		$this->SetXY(0, $y - 7);
//		$this->Cell(5,4,'', 0, 0, 'L');
//		$this->Cell(18.75,4.5,utf8_decode("normal"), 1, 0, 'C');
//		$this->Cell(18.75,4.5,utf8_decode("alterada"), 1, 0, 'C');
//		$this->Cell(19.25,4.5,utf8_decode("normal"), 1, 0, 'C');
//		$this->Cell(19.25,4.5,utf8_decode("cesariano \n"), 1, 0, 'C');
//		
		
		$this->SetFont('Arial', 'B', 10);
		$this->Ln(27);
		$this->Cell(5,4,'', 0, 0, 'L');
		$this->Cell(19,4,utf8_decode($dados['gestacao_normal']), 0, 0, 'C');
		$this->Cell(19,4,utf8_decode($dados['gestacao_alterada']), 0, 0, 'C');
		$this->Cell(19,4,utf8_decode($dados['parto_normal']), 0, 0, 'C');
		$this->Cell(19,4,utf8_decode($dados['parto_cesariano']), 0, 0, 'C');
		$this->Cell(28,4,utf8_decode($dados['cdnasc_idade']), 0, 0, 'C');
		$this->Cell(28,4,utf8_decode($dados['cdnasc_peso']), 0, 0, 'C');
		$this->Cell(21,4,utf8_decode($dados['cdnasc_apgar']), 0, 0, 'C');
		$this->Cell(23,4,utf8_decode($dados['nascimento']), 0, 0, 'C');
		$this->Cell(21,4,utf8_decode($dados['idade']), 0, 0, 'C');

		$this->Ln(4);

		$this->SetFont('Arial', '', 10);
		$this->Cell(5,5,'', 0, 0, 'L');
		$this->MultiCell(197.5,5,utf8_decode($dados['anotacao']), 1, 'J');

		$this->SetFont('Arial', '', 11);
		$this->Ln(10);
		$this->Cell(5,5,'', 0, 0, 'L');
		$y = $this->GetY();
		$this->MultiCell(39,3,utf8_decode("\n" . date('d/m/Y')), 0, 'C');
		$x = $this->GetX();
		$this->SetXY(44, $y);
		$this->SetFont('Arial', '', 10);
		$this->MultiCell(63,3.5,utf8_decode($dados['resultado']), 0, 'J');
		$this->SetXY(110, $y);
		$this->MultiCell(93,3.5,utf8_decode($dados['outros_exames']), 0, 'J');
		$this->SetY("116.5");
		$this->Cell(5,5,'', 0, 0, 'L');
		$this->Cell(155,4,utf8_decode($dados['conclusoes']), 0, 0,'J');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(44,4,utf8_decode($dados['medico']),0,0,'J');
		$this->Ln(6);
		$this->SetFont('Arial', '', 10);
		$this->Cell(160,5,'', 0, 0, 'L');
		$this->Cell(44,3,utf8_decode($dados['crm']), 0, 0, 'J');
		
		unset($_SESSION['triagem']);
	}
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->triagem_linguinha();
$pdf->Output("triagem_lingua.pdf", "I");