<?php

session_start();
require('fpdf.php');
require('./Bootstrap.php');
conectar();

class PDF extends FPDF {

    function anamnese($dados) {
		if ($_GET['userId'] !== 'undefined') {
			$dependente = buscaDadosDepedente2($_GET['userId']);
			$dados['nome'] = $dependente['nome'];
			$dados['sobrenome'] = $dependente['sobrenome'];
		}

		$data = diferenca_data($dados['data_nascimento'], date('Y-m-d'));
		$mes = $data['mes'];
		$ano = date($data['ano']);
		$data = $ano . 'a ' . $mes . 'm';
		// posicao vertical no caso -1.. e o limite da margem
		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		//escreve no pdf largura,altura,conteudo,borda,quebra de linha,alinhamento
		$this->Cell(0, 0.1, '', 0, 1, 'R');


		$this->Image("./pdf/fpdf/anamnese.png", 0, 0, 210, 295);

		$this->Ln(35);
		$this->SetFont('Arial', '', 8);
		$this->Cell(5, 5, '', 0, 0, 'L');
		$this->Cell(111, 5, utf8_decode(strtoupper($dados['nome'] . " " . $dados['sobrenome'] . ' -' . $data)), 0, 0, 'L');
		$this->Cell(16, 5, $dados['matricula'], 0, 0, 'L');
		$this->Cell(16, 5, $data, 0, 0, 'L');
		$this->Cell(18, 5, date('d/m/Y'), 0, 0, 'L');
		$this->Cell(38, 5, '', 0, 0, 'L');
    }

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$dados = $_SESSION['editaPessoaClienteDados'];
$pdf->anamnese($dados);
$pdf->Output("anamnese.pdf", "I");
