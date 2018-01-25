<?php
require('fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF
{
	function Certificado($dados)
	{
		$teste = $_SESSION['laudotca']['teste'];
		$nome = $_SESSION['laudotca']['nomePessoa'];

		// posicao vertical no caso -1.. e o limite da margem
		$this->SetY("-1");
		$this->SetAutoPageBreak(1,1);

		//escreve no pdf largura,altura,conteudo,borda,quebra de linha,alinhamento
		$this->Cell(0,0.1,'',0,1,'R');

		//endereco da imagem,posicao X(horizontal),posicao Y(vertical), tamanho
		$this->Image("pdf/fpdf/icelular_1.png",60,4,130,55);
		$this->Image("pdf/fpdf/icelular_11.png",31,56,158,54);

		$dados['nome'] = $nome;

		$this->Ln(25);
		$this->SetFont('Arial','',12);
		$this->Cell(45,5,'',0,0,'L');
		$this->Cell(152,5,utf8_decode('de   '.$dados['nome']),0,1,'C');

		$this->SetFont('Arial','',10);
		$this->Ln(42);
		$this->Cell(75,5,'',0,0,'L');
		$this->Cell(36.5,5,converteData($teste[0]['data_aplicacao']),0,0,'C');
		$this->Cell(39.5,5,converteData($teste[0]['data_leit']),0,0,'C');
		$this->Cell(35.5,5,$teste[0]['valor'].' mm',0,0,'C');
		$this->Ln(5);

		$this->Cell(75,4,'',0,0,'L');
		$this->Cell(36.5,4,converteData($teste[1]['data_aplicacao']),0,0,'C');
		$this->Cell(39.5,4,converteData($teste[1]['data_leit']),0,0,'C');
		$this->Cell(35.5,4,$teste[1]['valor'].' mm',0,0,'C');
		$this->Ln(4);

		$this->Cell(75,5,'',0,0,'L');
		$this->Cell(36.5,5,converteData($teste[2]['data_aplicacao']),0,0,'C');
		$this->Cell(39.5,5,converteData($teste[2]['data_leit']),0,0,'C');
		$this->Cell(35.5,5,$teste[2]['valor'].' mm',0,0,'C');
		$this->Ln(5);

		$this->Cell(75,4,'',0,0,'L');
		$this->Cell(36.5,4,converteData($teste[1]['data_aplicacao']),0,0,'C');
		$this->Cell(39.5,4,converteData($teste[1]['data_leit']),0,0,'C');
		$this->Cell(35.5,4,$teste[1]['valor'].' mm',0,1,'C');

	}
}

$pdf = new PDF();
$pdf->SetFont('Arial','',10);
$pdf->SetMargins(0,0,0);
$dados = Array();
$pdf->Certificado($dados);
$pdf->Output("icelular.pdf","I");

?>
