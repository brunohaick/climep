<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function triagem_coracaozinho() {

//		die(print_r($_SESSION['triagem']));

		$orelha2Arr = $_SESSION['triagem']['orelha2Arr'];
		$cliente = $_SESSION['triagem']['orelha2Arr']['cliente'];
		$orel2Arr = $_SESSION['triagem']['orelha2Arr']['consulta'];

		$medico = $_SESSION['triagem']['orelha2Arr']['medico'];
		$dados['medico'] = $medico['nome'] . ' ' . $medico['sobrenome'];
		$dados['crm'] = "\nCRM " . $medico['crm'];

		$orelha2Arr['gestacao'] = $orel2Arr[0];
		$orelha2Arr['parto'] = $orel2Arr[1];
		$orelha2Arr['idadeGestacional'] = $orel2Arr[2];
		$orelha2Arr['apgar'] = $orel2Arr[3];
		$orelha2Arr['pesoNascimento'] = $orel2Arr[4];

		$dados['nome'] = $cliente['nome'];
		$dados['nascimento'] = $cliente['nascimento'];
		$dados['idade'] = $cliente['Idade'];
		$dados['cdnasc_idade'] = $orelha2Arr['idadeGestacional'];
		$dados['cdnasc_peso'] = $orelha2Arr['pesoNascimento'];
		$dados['cdnasc_apgar'] = $orelha2Arr['apgar'];

		if ($orelha2Arr['gestacao'] == 1) {
			$dados['gestacao_normal'] = "X";
		} else if ($orelha2Arr['gestacao'] == 2) {
			$dados['gestacao_alterada'] = "X";
		}

		if ($orelha2Arr['parto'] == 1) {
			$dados['parto_normal'] = "X";
		} else if ($orelha2Arr['parto'] == 2) {
			$dados['parto_cesariano'] = "X";
		}

		$meatoOd = "";
		if ($orelha2Arr['obstrucaoMeatoOd'] == 0) {
			$meatoOd = "com";
		} else if ($orelha2Arr['obstrucaoMeatoOd'] == 1) {
			$meatoOd = "sem";
		}

		$meatoOe = "";
		if ($orelha2Arr['obstrucaoMeatoOe'] == 0) {
			$meatoOe = "com";
		} else if ($orelha2Arr['obstrucaoMeatoOe'] == 1) {
			$meatoOe = "sem";
		}

		$resultadoCocleo = "";
		if ($orelha2Arr['conclusaoOd'] == $orelha2Arr['conclusaoOe']) {
			$resultadoCocleo = "Emissões Evocadas Transientes " . strtoupper($orelha2Arr['conclusaoOd']) . " bilateralmente.";
			if($orelha2Arr['conclusaoOd'] == "Presentes") {
				$resultadoCocleo .= " Sugestivo de função coclear normal bilateralmente (células ciliadas externas).";
			}
		} else if ($orelha2Arr['conclusaoOd'] != $orelha2Arr['conclusaoOe']) {
			$resultadoCocleo = "Emissões Evocadas Transientes " . strtoupper($orelha2Arr['conclusaoOd']) . " na orelha direta e "
				. strtoupper($orelha2Arr['conclusaoOe']) . " na orelha esquerda.";
			if($orelha2Arr['conclusaoOd'] == "Presentes") {
				$resultadoCocleo .= " Sugestivo de função coclear normal na orelha direita (células ciliadas externas).";
			} else if($orelha2Arr['conclusaoOe'] == "Presentes") {
				$resultadoCocleo .= " Sugestivo de função coclear normal na orelha esquerda (células ciliadas externas).";				
			}
		}

		$funcaoCoclear = "";
		if ($orelha2Arr['funcaoCoclear'] == 1) {
			$funcaoCoclear = "Sugestivo de função coclear presente bilateralmente";
		}

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		$this->SetFont('Arial', 'B', 14);
		$this->AddPage();
		$this->Image("pdf/fpdf/pdfsconsulta/triagem-orelhinha.png", 0, 0, 210, 143);
		$this->Ln(7);
		$this->Cell(5, 5, '', 0, 0, 'L');
		$this->Cell(170, 5, utf8_decode(strtoupper($dados['nome'])), 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->Ln(27);
		$this->Cell(5, 4, '', 0, 0, 'L');
		$this->Cell(19, 4, utf8_decode($dados['gestacao_normal']), 0, 0, 'C');
		$this->Cell(19, 4, utf8_decode($dados['gestacao_alterada']), 0, 0, 'C');
		$this->Cell(19, 4, utf8_decode($dados['parto_normal']), 0, 0, 'C');
		$this->Cell(19, 4, utf8_decode($dados['parto_cesariano']), 0, 0, 'C');
		$this->Cell(28, 4, utf8_decode($dados['cdnasc_idade']), 0, 0, 'C');
		$this->Cell(28, 4, utf8_decode($dados['cdnasc_peso']), 0, 0, 'C');
		$this->Cell(21, 4, utf8_decode($dados['cdnasc_apgar']), 0, 0, 'C');
		$this->Cell(23, 4, utf8_decode($dados['nascimento']), 0, 0, 'C');
		$this->Cell(21, 4, utf8_decode($dados['idade']), 0, 0, 'C');

		$this->Ln(74.5);

		$y = $this->GetY();
		$this->SetXY(6, 48);
		$this->SetFont('Arial', '', 9);
		$this->MultiCell(111, 4, utf8_decode("Meato Acústico Externo:" . "\n" . "orelha direita " . $meatoOd . " obstrução, e "
						. "orelha esquerda " . $meatoOe . " obstrução"), 0, 'J');
		$this->SetXY(118, 48);
		$this->SetFont('Arial', '', 8.9);
		$this->MultiCell(87, 4, utf8_decode("Equipamento utilizado: " . $orelha2Arr['equipamentoTeste']), 0, 'J');

		$this->SetXY(118, 56);
		$this->MultiCell(85, 4, utf8_decode("Relação Sinal/Ruído maior ou igual a 03 dB"), 0, 'J');

		$this->SetXY(118, 64);
		$this->MultiCell(87, 4, utf8_decode("Exame realizado com o paciente em estado de alerta e tranquilo."), 0, 'J');

		$this->SetXY(118, 74);
		$this->MultiCell(87, 4, utf8_decode("Emissões    Otoacústicas    Evocadas    TE/DP    abrangendo  o  espectro  de  frequências  de  1.500Hz a 4.000Hz / 2.000 a 5.000 Hz."), 0, 'J');

		$this->SetXY(6, 55);
		$this->SetFont('Arial', '', 9);
		$this->MultiCell(111, 4, utf8_decode("\n" . "Reflexo cócleo palpebral:\n" .$orelha2Arr['resultadoCocleo'] . ", mediante "
						. "estímulos acústicos"), 0, 'J');

		$this->SetXY(6, 65);
		$this->SetFont('Arial', 'BU', 9);
		$this->MultiCell(30, 3, utf8_decode("\n" . "Conclusão:"), 0, 'J');
		
		$this->SetXY(6, 73);
		$this->SetFont('Arial', '', 9);
		$this->MultiCell(111, 4, utf8_decode($resultadoCocleo), 0, 'J');
		
		$this->SetXY(6, 109);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(121, 5, '', 0, 0, 'L');
		$this->MultiCell(20, 4, utf8_decode(date("d/m/Y")), 0, 'J');
		$this->SetFont('Arial', '', 10);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->MultiCell(150, 4, utf8_decode($funcaoCoclear), 0, 'J');

		$this->SetY("112.5");
		$this->Cell(159, 5, '', 0, 0, 'L');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(44, 5, utf8_decode($dados['medico']), 0, 1, 'J');
		$this->SetFont('Arial', '', 10);
		$this->Cell(159, 5, '', 0, 0, 'L');
		$this->MultiCell(44, 5, utf8_decode($dados['crm']), 0, 'J');

		$this->SetFont('Arial', 'B', 10);
		$this->Ln(35);

		unset($_SESSION['triagem']);
	}
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->triagem_coracaozinho();
$pdf->Output("fichaimuno.pdf", "I");
