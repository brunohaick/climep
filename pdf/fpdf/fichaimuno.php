<?php

session_start();
require('fpdf.php');
require('./Bootstrap.php');
conectar();

class PDF extends FPDF {

	function fichaimuno($dados) {

		$nomeDotitular = $dados['nome'] . " " . $dados['sobrenome'];
		$dadosMedicoAssitente = dadosMedicoIndPorId($dados['medico_id']);

		if ($_GET['userId'] !== 'undefined') {
			$dependente = buscaDadosDepedente2($_GET['userId']);
			$dados['nome'] = $dependente['nome'];
			$dados['sobrenome'] = $dependente['sobrenome'];
			$dados['sexo'] = $dependente['sexo'];
			$dados['nascimento'] = converteData($dependente['data_nascimento']);
		}

		$dados['nascimento'] = converteData($dados['data_nascimento']);
		$data = diferenca_data($dados['data_nascimento'], date('Y-m-d'));
		$mes = $data['mes'];
		$ano = date($data['ano']);
		$data = $ano . 'a ' . $mes . 'm';
		// posicao vertical no caso -1.. e o limite da margem
		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		//escreve no pdf largura,altura,conteudo,borda,quebra de linha,alinhamento
		$this->Cell(0, 0.1, '', 0, 1, 'R');


		$this->Image("./pdf/fpdf/fichaimuno.png", 5, 10, 210, 295);

		$dados['nome'] = $dados['nome'] . " " . $dados['sobrenome'];
		$dados['idade'] = $data;
//		$dados['sexo'] = 'F';
//		$dados['nascimento'] = '22/11/12';
//		$dados['matricula'] = '309823';

		if ($ano < 18)
			$dados['nome_mae'] = $nomeDotitular;

		$dados['telefone'] = $dados['tel_residencial'] . ' ' . $dados['tel_comercial'];
		$dados['medico'] = $dadosMedicoAssitente['nome'] . " " . $dadosMedicoAssitente['sobrenome'];

		$dados['imuno'] = (isset($_GET['imuno'])) ? $_GET['imuno'] : 'não foi selecionado a imunoterapia';

		$this->SetFont('Arial', '', 10);
		$this->Ln(13);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->Cell(141, 5, utf8_decode($dados['nome'] . ' - ' . $dados['idade']), 0, 0, 'L');
		$this->Cell(8, 5, utf8_decode($dados['sexo']), 0, 0, 'L');
		$this->Cell(21, 5, utf8_decode($dados['nascimento']), 0, 0, 'C');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(28, 5, utf8_decode($dados['matricula']), 0, 0, 'C');
		$this->Ln(8);

		$this->SetFont('Arial', '', 10);
		$this->Cell(6, 5, '', 0, 0, 'L');
		$this->Cell(63.5, 5, utf8_decode($dados['nome_mae']), 0, 0, 'L');
		$this->Cell(77.5, 5, utf8_decode($dados['telefone']), 0, 0, 'L');
		$this->Cell(57, 5, utf8_decode($dados['medico']), 0, 0, 'L');


		$this->SetFont('Arial', 'B', 10);
		$this->Ln(13);
		$this->Cell(25, 5, '', 0, 0, 'L');
		$this->Cell(180, 7, utf8_decode($dados['imuno']), 0, 0, 'L');
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->fichaimuno($_SESSION['editaPessoaClienteDados']);
$pdf->Output("fichaimuno.pdf", "I");
?>
