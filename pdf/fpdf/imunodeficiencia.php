<?php
require('fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF
{
	function Certificado($dados)
	{
		$cliente = $_SESSION['laudoimu']['cliente'];
		$titular = $_SESSION['laudoimu']['titular'];
		$hist = $_SESSION['laudoimu']['hist'];

		// posicao vertical no caso -1.. e o limite da margem
		$this->SetY("-1");
		$this->SetAutoPageBreak(1,1);

		//escreve no pdf largura,altura,conteudo,borda,quebra de linha,alinhamento
		$this->Cell(0,5,'',0,1,'R');
		$this->Ln(14.8);

		//endereco da imagem,posicao X(horizontal),posicao Y(vertical), tamanho
		$this->Image("pdf/fpdf/imunodeficiencia.png", 0,2,210,130);

		$dia = date('d');
		$mes = mostraMes(date('m'));
		$ano = date('Y');

		$dados['nome_mae'] = $titular['nome']." ".$titular['sobrenome'];
		$dados['matricula_climep'] = $titular['matricula']."-".$cliente['membro'];
		$dados['data_coleta'] = $_SESSION['laudoimu']['dataColeta'];
		$dados['nome_filho'] =  $cliente['nome']." ".$cliente['sobrenome'];
		$dados['tipo_amostra'] = 'Cartão de Coleta Neonatal';
		$dados['data_ensaio'] = $_SESSION['laudoimu']['dataEnsaio'];
		$dados['trecs_sangue'] = $_SESSION['laudoimu']['valor'];
		$dados['conclusao'] = 'RESULTADO DE EXAME DENTRO DO PADRÃO DE NORMALIDADE';
		$dados['data'] = "São Paulo, $dia de $mes de $ano.";

		$this->Ln(25);
		$this->Cell(7,5,'',0,0,'L');
		$this->Cell(123,5,utf8_decode($dados['nome_mae']),0,0,'L');
		$this->Cell(2,5,'',0,0,'L');
		$this->Cell(35,5,utf8_decode($dados['matricula_climep']),0,0,'C');
		$this->Cell(2,5,'',0,0,'L');
		$this->Cell(35,5,utf8_decode($dados['data_coleta']),0,0,'C');

		$this->Ln(11);
		$this->Cell(7,5,'',0,0,'L');
		$this->Cell(123,5,utf8_decode($dados['nome_filho']),0,0,'L');
		$this->Cell(2,5,'',0,0,'L');
		$this->Cell(72,5,utf8_decode($dados['tipo_amostra']),0,0,'C');


		$this->Ln(22);
		$this->Cell(7,5,'',0,0,'L');
		$this->Cell(64,5,utf8_decode($dados['data_ensaio']),0,0,'C');
		$this->Cell(2,5,'',0,0,'L');
		$this->Cell(64,5,utf8_decode($dados['trecs_sangue']),0,0,'C');

		$this->Ln(11);
		$this->Cell(7,5,'',0,0,'L');
		$this->Cell(197,5,utf8_decode($dados['conclusao']),0,0,'L');


		$this->Ln(19);
		$this->Cell(7,5,'',0,0,'L');
		$this->Cell(64,5,utf8_decode($dados['data']),0,0,'L');

	}
}

unset($_SESSION['laudoimu']);
$pdf = new PDF();
$pdf->SetFont('Arial','',10);
$pdf->SetMargins(0,0,0);
$dados = Array();
$pdf->Certificado($dados);
$pdf->Output("imunodeficiencia.pdf","I");

?>
