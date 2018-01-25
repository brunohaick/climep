<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function triagem_coracaozinho() {

//		die(print_r($_SESSION['triagem']));
		$coracaoArr = $_SESSION['triagem']['coracaoArr'];
		$cliente = $_SESSION['triagem']['coracaoArr']['cliente'];
		$corArr = $_SESSION['triagem']['coracaoArr']['consulta'];

		$coracaoArr['gestacao'] = $corArr[0];
		$coracaoArr['parto'] = $corArr[1];
		$coracaoArr['idadeGestacional'] = $corArr[2];
		$coracaoArr['apgar'] = $corArr[3];
		$coracaoArr['pesoNascimento'] = $corArr[4];

		$dados['nome'] = $cliente['nome'];
		$dados['nascimento'] = $cliente['nascimento'];
		$dados['idade'] = $cliente['Idade'];

		if ($coracaoArr['gestacao'] == 1) {
			$dados['gestacao_normal'] = "X";
		} else if ($coracaoArr['gestacao'] == 2) {
			$dados['gestacao_alterada'] = "X";
		}

		if ($coracaoArr['parto'] == 1) {
			$dados['parto_normal'] = "X";
		} else if ($coracaoArr['parto'] == 2) {
			$dados['parto_cesariano'] = "X";
		}

		$medico = $_SESSION['triagem']['coracaoArr']['medico'];
		
		$dados['cdnasc_idade'] = $coracaoArr['idadeGestacional'];
		$dados['cdnasc_peso'] = $coracaoArr['pesoNascimento'];
		$dados['cdnasc_apgar'] = $coracaoArr['apgar'];
		$dados['mao_direita'] = $coracaoArr['percMaoDireita'] . ' %';
		$dados['pe'] = $coracaoArr['percPe'] . ' %';
		$dados['diferenca'] = $coracaoArr['percDiferenca'] . ' %';
		$dados['resultado'] = $coracaoArr['conclusaoTeste'];
		$dados['medico'] = $medico['nome'] . ' ' . $medico['sobrenome'];
		$dados['dadosmedico'] = "CRM " . $medico['crm'];
		$dados['conclusoes'] = $coracaoArr['observacao'];

		$strqp = implode($_SESSION['triagem']['coracaoArr']['coracao_anot_qp'], ', ');
		$strhf = implode($_SESSION['triagem']['coracaoArr']['coracao_anot_hf'], ', ');

		$dados['anotacao_qp'] = "";
		$dados['anotacao_hf'] = "";

		if(!empty($strqp)) {
			$dados['anotacao_qp'] = "QP: " . $strqp;
		}

		if(!empty($strhf)) {
			$dados['anotacao_hf'] = "HF: " . $strhf;
		}

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);
		$this->SetFont('Arial', 'B', 14);
		$this->AddPage();
		$this->Image("pdf/fpdf/pdfsconsulta/triagem-coracaozinho.png", 0, 0, 210, 130);
		$this->Ln(8);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->Cell(170, 5, utf8_decode(strtoupper($dados['nome'])), 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->Ln(26.5);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->Cell(19, 5, utf8_decode($dados['gestacao_normal']), 0, 0, 'C');
		$this->Cell(18, 5, utf8_decode($dados['gestacao_alterada']), 0, 0, 'C');
		$this->Cell(19, 5, utf8_decode($dados['parto_normal']), 0, 0, 'C');
		$this->Cell(20, 5, utf8_decode($dados['parto_cesariano']), 0, 0, 'C');
		$this->Cell(28, 5, utf8_decode($dados['cdnasc_idade']), 0, 0, 'C');
		$this->Cell(27, 5, utf8_decode($dados['cdnasc_peso']), 0, 0, 'C');
		$this->Cell(21, 5, utf8_decode($dados['cdnasc_apgar']), 0, 0, 'C');
		$this->Cell(23, 5, utf8_decode($dados['nascimento']), 0, 0, 'C');
		$this->Cell(21, 5, utf8_decode($dados['idade']), 0, 0, 'C');

		$this->Ln(8);

		$this->SetFont('Arial', '', 10);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->MultiCell(197, 4, utf8_decode($dados['anotacao_qp']), 0, 'J');
		$this->Ln(2);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->MultiCell(197, 4, utf8_decode($dados['anotacao_hf']), 0, 'J');
		$this->SetFont('Arial', 'B', 10);

		/**
		 * Verifica se a string "anotacao_hf" é maior que 119 caracteres,
		 * pois se passar deste tamanho, a string passapara a oróxima linha.
		 * Se a string ocupar somente uma linha, deve-se adicionar uma linha
		 * em branco para não prejudicar a apresentação dos dados no pdf.
		 * 
		 */
		if(isset($dados['anotacao_hf'][119])) {
			$this->Ln(20);
		} else {
			$this->Ln(24);
		}

		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->Cell(38, 7, utf8_decode(date('d/m/Y')), 0, 0, 'C');
		$this->Cell(25, 7, utf8_decode($dados['mao_direita']), 0, 0, 'C');
		$this->Cell(19, 7, utf8_decode($dados['pe']), 0, 0, 'C');
		$this->Cell(22, 7, utf8_decode($dados['diferenca']), 0, 0, 'C');
		$this->Cell(70, 7, utf8_decode($dados['resultado']), 0, 0, 'C');

		$this->Ln(24);

		$this->SetFont('Arial', '', 10);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->MultiCell(152, 4, utf8_decode($dados['conclusoes']), 0, 'J');
		$this->SetY("100.5");
		$this->Cell(158, 5, '', 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->MultiCell(44, 5, utf8_decode($dados['medico']), 0, 'J');
		$this->SetFont('Arial', '', 10);
		$this->Cell(158, 5, '', 0, 0, 'L');
		$this->MultiCell(44, 5, utf8_decode($dados['dadosmedico']), 0, 'J');
		
		unset($_SESSION['triagem']);
	}
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->triagem_coracaozinho();
$pdf->Output("fichaimuno.pdf", "I");