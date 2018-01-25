<?php
require('fpdf.php');
require('Bootstrap.php');

//print_r($_SESSION['declaracao']);exit;
class PDF extends FPDF
{
	function Certificado_Header()
	{
		$session = $_SESSION['declaracao'];
		
		$data['titulo'] = "DECLARAÇÃO DE COMPARECIMENTO";
		$dados['nome'] = trim($session['nomeCliente']);
		$nomeCliente = $dados['nome'];
		$dados['nomeAcompanhante'] = trim($session['nomeAcompanhante']);

		$procedimentos = $session['strvacs'];
		$nomeAcompanhante = $dados['nomeAcompanhante'];

		$data['texto'] = "Declaramos para os devidos fins que $nomeCliente compareceu à esta Clínica no dia de hoje, acompanhada de $nomeAcompanhante, para a realização de: $procedimentos";

		$this->SetFont('Arial','B',12);
		$this->MultiCell(0,5,utf8_decode($data['titulo']),0,'C');
		$this->Ln(12);
		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode($data['texto']),0,'J');
		$this->Ln(15);
	}

	function Certificado_Footer() {

		$dia = date('d');
		$mes = mostraMes(date('m'));
		$ano = date('Y');
		$diadasemana = mostraSemana(date('w'));

		$data['data'] = "Belém, $diadasemana, $dia de $mes de $ano";
		$data['usuario'] = $_SESSION['declaracao']['usuario'];

		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode($data['data']),0,'J');
		$this->Ln(4);

		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode($data['usuario']),0,'J');
		$this->Ln(4);
	}

}

$pdf = new PDF();
// Column headings
$header = array('Controle','Matricula','Nome','FP', 'Valor','Parcelas','Data','Total');
// Data loading
$pdf->SetFont('Arial','',10);
$pdf->SetMargins(20,20,20);
$pdf->AddPage();
$dados = Array();
$pdf->Certificado_Header();
$pdf->Certificado_Footer();
$pdf->Output();
$pdf->Output("DeclaracaoComparecimento.pdf","I");
unset($_SESSION['declaracao']);
?>
