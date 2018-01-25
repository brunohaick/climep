<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function triagem_olhinho($dados) {

		$olhoArr = $_SESSION['triagem']['olhoArr'];
		$cliente = $_SESSION['triagem']['olhoArr']['cliente'];
		$olhArr = $_SESSION['triagem']['olhoArr']['consulta'];

		$medico = $_SESSION['triagem']['orelha2Arr']['medico'];
		$dados['medico'] = $medico['nome'] . ' ' . $medico['sobrenome'];
		$dados['crm'] = "\nCRM " . $medico['crm'];

		$olhoArr['gestacao'] = $olhArr[0];
		$olhoArr['parto'] = $olhArr[1];
		$olhoArr['idadeGestacional'] = $olhArr[2];
		$olhoArr['apgar'] = $olhArr[3];
		$olhoArr['pesoNascimento'] = $olhArr[4];

		$dados['nome'] = $cliente['nome'];
		$dados['nascimento'] = $cliente['nascimento'];
		$dados['idade'] = $cliente['Idade'];
		$dados['cdnasc_idade'] = $olhoArr['idadeGestacional'];
		$dados['cdnasc_peso'] = $olhoArr['pesoNascimento'];
		$dados['cdnasc_apgar'] = $olhoArr['apgar'];

		if ($olhoArr['gestacao'] == 1) {
			$dados['gestacao_normal'] = "X";
		} else if ($olhoArr['gestacao'] == 2) {
			$dados['gestacao_alterada'] = "X";
		}

		if ($olhoArr['parto'] == 1) {
			$dados['parto_normal'] = "X";
		} else if ($olhoArr['parto'] == 2) {
			$dados['parto_cesariano'] = "X";
		}

		if ($olhoArr['resultadoNormalOd'] == 1) {
			$dados['normal_OD'] = "X";
		}
		if ($olhoArr['resultadoNormalOe'] == 1) {
			$dados['normal_OE'] = "X";
		}
		if ($olhoArr['resultadoSuspeitoOd'] == 1) {
			$dados['suspeito_OD'] = "X";
		}
		if ($olhoArr['resultadoSuspeitoOe'] == 1) {
			$dados['suspeito_OE'] = "X";
		}
		if ($olhoArr['resultadoLeucoriaOd'] == 1) {
			$dados['leucoria_OD'] = "X";
		}
		if ($olhoArr['resultadoLeucoriaOe'] == 1) {
			$dados['leucoria_OE'] = "X";
		}

		$normal = "";
		if ($olhoArr['visualNormal'] == 1) {
			$normal = "normal";
		} else if ($olhoArr['visualNormal'] == 2) {
			$normal = "Anormal";
		}

		$dados['conclusoes'] = 'Sugestiva de funcção visual ' . $normal;
		$dados['conclusoes'] .= "\n" . $olhoArr['observacao'];

		$dados['outros_exames'] = $olhoArr['outrosExames'];
		$dados['anotacaoQp'] = "QP: " . $olhoArr['olho_anot_qp'];
		$dados['anotacaoHf'] = "HF: " . $olhoArr['olho_anot_hf'];

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		$this->SetFont('Arial', 'B', 14);
		$this->AddPage();
		$this->Image("pdf/fpdf/pdfsconsulta/triagem-olhinho.png",0,0,210,130);
		$this->Ln(7);
		$this->Cell(5,5,'', 0, 0, 'L');
		$this->Cell(170,5,utf8_decode(strtoupper($dados['nome'])), 0, 0, 'L');
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

		$this->Ln(8);

		$this->SetXY(6, 42);
		$this->SetFont('Arial', '', 9);
		$this->MultiCell(197,3.5,utf8_decode($dados['anotacaoQp']),0,'J');
		$this->SetXY(6, 53);
		$this->MultiCell(197,3.5,utf8_decode($dados['anotacaoHf']),0,'J');

		$this->SetFont('Arial', 'B', 10);
		$this->SetXY(6, 82);
		$this->Cell(38,4,utf8_decode(date('d/m/Y')),0,0, 'C');
		
		$this->Cell(11,4,utf8_decode($dados['normal_OD']),0,0, 'C');
		$this->Cell(11,4,utf8_decode($dados['normal_OE']),0,0, 'C');
		$this->Cell(11,4,utf8_decode($dados['suspeito_OD']),0,0, 'C');
		$this->Cell(11,4,utf8_decode($dados['suspeito_OE']),0,0, 'C');
		$this->Cell(11,4,utf8_decode($dados['leucoria_OD']),0,0, 'C');
		$this->Cell(11,4,utf8_decode($dados['leucoria_OE']),0,0, 'C');
		$this->SetXY(110, 77.5);
		$this->SetFont('Arial', '', 9);
		$this->MultiCell(94,2.8,utf8_decode($dados['outros_exames']),0,'J');

		$this->SetY("102");
		$this->SetFont('Arial', '', 9);
		$this->Cell(6,5,'', 0, 0, 'L');
		$this->MultiCell(153,4,utf8_decode($dados['conclusoes']),0,'J');
		$this->SetY("101.5");
		$this->Cell(159,5,'', 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(44,4,utf8_decode($dados['medico']),0,1,'J');
		$this->SetFont('Arial', '', 10);
		$this->Cell(159,5,'', 0, 0, 'L');
		$this->MultiCell(44,4,utf8_decode($dados['crm']),0,'J');
		
		unset($_SESSION['triagem']);
	}
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->triagem_olhinho();
$pdf->Output("fichaimuno.pdf", "I");