<?php
//incluindo o arquivo do fpdf
require_once("fpdf.php");

include('Bootstrap.php');


//defininfo a fonte !
//define('FPDF_FONTPATH','font/');

//instancia a classe.. P=Retrato, mm =tipo de medida utilizada no casso milimetros, tipo de folha =A4
$pdf= new FPDF("L","mm","A4");

//define a fonte a ser usada
$pdf->SetFont('arial','',10);
$pdf->SetMargins(0,0,0);

function shortstr ($string, $limit = 13)
{
	$string = (strlen($string) > $limit)
		? substr($string, 0, $limit)
		: $string;
	return $string;
}


//define o titulo
//$pdf->SetTitle("Testando PDF com PHP !");


//assunto
//$pdf->SetSubject("assunto deste artigo!");

// posicao vertical no caso -1.. e o limite da margem
$pdf->SetY("-1");
$pdf->SetAutoPageBreak(1,1);
$guiaTiss = $_SESSION['GuiaTiss'];

$procedimentos = $guiaTiss['procedimentos'];
//$titulo="Titulo do Artigo";
$numeroDeProcedimentos = sizeof($procedimentos);
$numpag = intval($numeroDeProcedimentos / 5) + 1;
$countProcedimento = 0;
$countProcedimento2 = 0;
for($i = 0 ; $i < $numpag ; $i++) {
	$pdf->AddPage();

	$pdf->Image("./pdf/fpdf/guiatiss.png", 0,0,298,210);
	$pdf->Ln(9);
	$pdf->SetFont('Arial','',16);
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(30,5,$guiaTiss['guia-tiss-plano']/*$guiaTiss['guia-tiss-registro_ans']*/,0,0,'L');
	$pdf->SetFont('Arial','',14);
	$pdf->Cell(206,5,'',0,0,'L');
	$pdf->Cell(30,5,$guiaTiss['guia-tiss-senha'],0,0,'L');
	$pdf->Ln(11);

	$pdf->SetFont('Arial','',10);
	//$pdf->Cell(20,5,'',0,1,'L');

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(35,5,$guiaTiss['guia-tiss-registro_ans'],0,0,'L'); // ANS 
	$pdf->Cell(89,5,$guiaTiss['guia-tiss-guia_principal'],0,0,'L');
	$pdf->Cell(33,5,$guiaTiss['guia-tiss-data_autorizacao'],0,0,'L');
	$pdf->Cell(63,5,$guiaTiss['guia-tiss-senha'],0,0,'L');
	$pdf->Cell(33,5,$guiaTiss['guia-tiss-data_validade_senha'],0,0,'L');
	$pdf->Cell(33,5,$guiaTiss['guia-tiss-data_emissao_senha'],0,0,'L');
	$pdf->Ln(11);
	// Fim da primeira linha

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(70,5,$guiaTiss['guia-tiss-numero_carteira'],0,0,'L');
	$pdf->Cell(22,5,'',0,0,'L');
	$pdf->Cell(18,5,$guiaTiss['guia-tiss-plano'],0,0,'L');
	$pdf->Cell(33,5,$guiaTiss['guia-tiss-data_validade_carteira'],0,0,'L');
	$pdf->Cell(71,5, utf8_decode($guiaTiss['guia-tiss-nome']),0,0,'L');
	$pdf->Cell(72,5,$guiaTiss['guia-tiss-numero_cns'],0,0,'L');

	$pdf->Ln(10.5);
	// Fim da segunda linha

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(62,5,$guiaTiss['guia-tiss-codigo_operadora'],0,0,'L');
	$pdf->Cell(173,5,$guiaTiss['guia-tiss-nome_contratado'],0,0,'L');
	$pdf->Cell(51,5,$guiaTiss['guia-tiss-codigo_cnes'],0,0,'L');
	$pdf->Ln(8);

	// Fim da 3 linha

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL 
	$pdf->Cell(118,5,$guiaTiss['guia-tiss-nome_profissional_solicitante'],0,0,'L');
	$pdf->Cell(50,5,$guiaTiss['guia-tiss-conselho_profissional'],0,0,'L');
	$pdf->Cell(51,5,$guiaTiss['guia-tiss-numero_conselho'],0,0,'L');
	$pdf->Cell(16,5,$guiaTiss['guia-tiss-estado'],0,0,'L');
	$pdf->Cell(51,5,$guiaTiss['guia-tiss-codigo_cbos'],0,0,'L');
	$pdf->Ln(11);
	// Fim da 4 linha

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL 
	$pdf->Cell(56,5,$guiaTiss['guia-tiss-data_hora_solicitacao'],0,0,'L');
	$pdf->Cell(1,5,'',0,0,'L'); // espaço 
	$pdf->Cell(5,5,$guiaTiss['guia-tiss-carater_solicitacao'],0,0,'L');
	$pdf->Cell(38,5,'',0,0,'L'); // espaço em branco
	$pdf->Cell(23,5,$guiaTiss['guia-tiss-cid'],0,0,'L');
	$pdf->Cell(163,5,$guiaTiss['guia-tiss-indicacao_clinica'],0,0,'L');
	$pdf->Ln(7);
	// FIM 5 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL 
	$pdf->Cell(12,5,($numeroDeProcedimentos> $countProcedimento) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'C');
	$pdf->Cell(36,5,$procedimentos[$countProcedimento]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(204,5,  utf8_decode($procedimentos[$countProcedimento]['descrisao']),0,0,'L');
	$pdf->Cell(12,5,$procedimentos[$countProcedimento++]['quantidade'],0,0,'C');
	$pdf->Cell(12,5,'',0,0,'L');
	$pdf->Ln(5);
	//FIM 6 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(12,5,($numeroDeProcedimentos> $countProcedimento) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'C');
	$pdf->Cell(36,5,$procedimentos[$countProcedimento]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(204,5,  utf8_decode($procedimentos[$countProcedimento]['descrisao']),0,0,'L');
	$pdf->Cell(12,5,$procedimentos[$countProcedimento++]['quantidade'],0,0,'C');
	$pdf->Cell(12,5,'',0,0,'L');
	$pdf->Ln(5);

	//FIM 7 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(12,5,($numeroDeProcedimentos> $countProcedimento) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'C');
	$pdf->Cell(36,5,$procedimentos[$countProcedimento]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(204,5,  utf8_decode($procedimentos[$countProcedimento]['descrisao']),0,0,'L');
	$pdf->Cell(12,5,$procedimentos[$countProcedimento++]['quantidade'],0,0,'C');
	$pdf->Cell(12,5,'',0,0,'L');
	$pdf->Ln(5);
	//FIM 8 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(12,5,($numeroDeProcedimentos> $countProcedimento) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'C');
	$pdf->Cell(36,5,$procedimentos[$countProcedimento]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(204,5,  utf8_decode($procedimentos[$countProcedimento]['descrisao']),0,0,'L');
	$pdf->Cell(12,5,$procedimentos[$countProcedimento++]['quantidade'],0,0,'C');
	$pdf->Cell(12,5,'',0,0,'L');
	$pdf->Ln(5);
	//FIM 9 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(12,5,($numeroDeProcedimentos> $countProcedimento) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'C');
	$pdf->Cell(36,5,$procedimentos[$countProcedimento]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(204,5,  utf8_decode($procedimentos[$countProcedimento]['descrisao']),0,0,'L');
	$pdf->Cell(12,5,$procedimentos[$countProcedimento++]['quantidade'],0,0,'C');
	$pdf->Cell(12,5,'',0,0,'L');
	$pdf->Ln(10);
	//FIM 10 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(62,5,$guiaTiss['guia-tiss-codigo_executante'],0,0,'L');
	$pdf->Cell(78,5,$guiaTiss['guia-tiss-nome_executante'],0,0,'L');
	$pdf->Cell(10,5,$guiaTiss['guia-tiss-tl'],0,0,'L');
	$pdf->Cell(46,5,shortstr($guiaTiss['guia-tiss-logradouro'], 23),0,0,'L');
	$pdf->Cell(29,5,utf8_decode($guiaTiss['guia-tiss-municipio']),0,0,'L');
	$pdf->Cell(8,5,$guiaTiss['guia-tiss-estado2'],0,0,'L');
	$pdf->Cell(16,5,$guiaTiss['guia-tiss-codigo_ibge'],0,0,'L');
	$pdf->Cell(18,5, shortstr($guiaTiss['guia-tiss-cep'],7),0,0,'L');
	$pdf->Cell(23,5,$guiaTiss['guia-tiss-cnes'],0,0,'L');
	$pdf->Ln(8);
	//FIM 11 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(62,5,$guiaTiss['guia-tiss-codigo_executante'],0,0,'L');
	$pdf->Cell(78,5,$guiaTiss['guia-tiss-nome_profissional_executante'],0,0,'L');
	$pdf->Cell(43,5,$guiaTiss['guia-tiss-conselho_profissional2'],0,0,'L');
	$pdf->Cell(34,5,$guiaTiss['guia-tiss-numero_conselho2'],0,0,'L');
	$pdf->Cell(14,5,$guiaTiss['guia-tiss-estado3'],0,0,'L');
	$pdf->Cell(22,5,$guiaTiss['guia-tiss-codigo_cbos2'],0,0,'L');
	$pdf->Cell(33,5,$guiaTiss['guia-tiss-grau_particoes'],0,0,'L');
	$pdf->Ln(11);
	//FIM 12 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(10,5,$guiaTiss['guia-tiss-tipo_atendimento'],0,0,'L');
	$pdf->Cell(112,5,'',0,0,'L');
	$pdf->Cell(5,5,$guiaTiss['guia-tiss-indicacao_acidente'],0,0,'L');
	$pdf->Cell(69,5,'',0,0,'L');
	$pdf->Cell(6,5,$guiaTiss['guia-tiss-tipo_saida'],0,0,'L');
	$pdf->Ln(10.5);
	//FIM 13 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(6,5,$guiaTiss['guia-tiss-tipo_doenca'],0,0,'L');
	$pdf->Cell(127,5,'',0,0,'L');
	$pdf->Cell(16,5,$guiaTiss['guia-tiss-tempo_doenca'],0,0,'L');
	$pdf->Ln(9);
	//FIM 14 LINHA
	
	$valorTotal = 0;

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(33,5,($numeroDeProcedimentos> $countProcedimento2) ?date('d/m/Y'):'',0,0,'L');
	$pdf->Cell(44,5,'',0,0,'L');
	$pdf->Cell(10,5,($numeroDeProcedimentos> $countProcedimento2) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'L');
	$pdf->Cell(35,5,$procedimentos[$countProcedimento2]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(35,5,shortstr(utf8_decode($procedimentos[$countProcedimento2]['descrisao'])),0,0,'L');
	$pdf->Cell(10,5,$procedimentos[$countProcedimento2]['quantidade'],0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(26,5,'',0,0,'L');
	$valoUnidade = $procedimentos[$countProcedimento2]['preco'] / $procedimentos[$countProcedimento2]['quantidade'];
	$pdf->Cell(36,5,($valoUnidade != 0)?number_format($valoUnidade, 2, ',', '.'):'',0,0,'L');
	$valorTotal += $procedimentos[$countProcedimento2]['preco'];
	$pdf->Cell(35,5,($procedimentos[$countProcedimento2]['preco'] != 0)?number_format($procedimentos[$countProcedimento2++]['preco'], 2, ',', '.'):'',0,0,'L');
	$pdf->Ln(5);
	//FIM 15 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(33,5,($numeroDeProcedimentos> $countProcedimento2) ?date('d/m/Y'):'',0,0,'L');
	$pdf->Cell(44,5,'',0,0,'L');
	$pdf->Cell(10,5,($numeroDeProcedimentos> $countProcedimento2) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'L');
	$pdf->Cell(35,5,$procedimentos[$countProcedimento2]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(35,5,shortstr(utf8_decode($procedimentos[$countProcedimento2]['descrisao'])),0,0,'L');
	$pdf->Cell(10,5,$procedimentos[$countProcedimento2]['quantidade'],0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(26,5,'',0,0,'L');
	$valoUnidade = $procedimentos[$countProcedimento2]['preco'] / $procedimentos[$countProcedimento2]['quantidade'];
	$pdf->Cell(36,5,($valoUnidade != 0)?number_format($valoUnidade, 2, ',', '.'):'',0,0,'L');
	$valorTotal += $procedimentos[$countProcedimento2]['preco'];
	$pdf->Cell(35,5,($procedimentos[$countProcedimento2]['preco'] != 0)?number_format($procedimentos[$countProcedimento2++]['preco'], 2, ',', '.'):'',0,0,'L');
	$pdf->Ln(5);
	//FIM 16 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(33,5,($numeroDeProcedimentos> $countProcedimento2) ?date('d/m/Y'):'',0,0,'L');
	$pdf->Cell(44,5,'',0,0,'L');
	$pdf->Cell(10,5,($numeroDeProcedimentos> $countProcedimento2) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'L');
	$pdf->Cell(35,5,$procedimentos[$countProcedimento2]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(35,5,shortstr(utf8_decode($procedimentos[$countProcedimento2]['descrisao'])),0,0,'L');
	$pdf->Cell(10,5,$procedimentos[$countProcedimento2]['quantidade'],0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(26,5,'',0,0,'L');
	$valoUnidade= $procedimentos[$countProcedimento2]['preco'] / $procedimentos[$countProcedimento2]['quantidade'];
	$pdf->Cell(36,5,($valoUnidade != 0)?number_format($valoUnidade, 2, ',', '.'):'',0,0,'L');
	$valorTotal += $procedimentos[$countProcedimento2]['preco'];
	$pdf->Cell(35,5,($procedimentos[$countProcedimento2]['preco'] != 0)?number_format($procedimentos[$countProcedimento2++]['preco'], 2, ',', '.'):'',0,0,'L');
	$pdf->Ln(5);
	//FIM 17 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(33,5,($numeroDeProcedimentos> $countProcedimento2) ?date('d/m/Y'):'',0,0,'L');
	$pdf->Cell(44,5,'',0,0,'L');
	$pdf->Cell(10,5,($numeroDeProcedimentos> $countProcedimento2) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'L');
	$pdf->Cell(35,5,$procedimentos[$countProcedimento2]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(35,5,shortstr(utf8_decode($procedimentos[$countProcedimento2]['descrisao'])),0,0,'L');
	$pdf->Cell(10,5,$procedimentos[$countProcedimento2]['quantidade'],0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(26,5,'',0,0,'L');
	$valoUnidade= $procedimentos[$countProcedimento2]['preco'] / $procedimentos[$countProcedimento2]['quantidade'];
	$pdf->Cell(36,5,($valoUnidade != 0)?number_format($valoUnidade, 2, ',', '.'):'',0,0,'L');
	$valorTotal += $procedimentos[$countProcedimento2]['preco'];
	$pdf->Cell(35,5,($procedimentos[$countProcedimento2]['preco'] != 0)?number_format($procedimentos[$countProcedimento2++]['preco'], 2, ',', '.'):'',0,0,'L');
	$pdf->Ln(5);
	//FIM 18 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(33,5,($numeroDeProcedimentos> $countProcedimento2) ?date('d/m/Y'):'',0,0,'L');
	$pdf->Cell(44,5,'',0,0,'L');
	$pdf->Cell(10,5,($numeroDeProcedimentos> $countProcedimento2) ?$guiaTiss['guia-tiss-tabela'] : '',0,0,'L');
	$pdf->Cell(35,5,$procedimentos[$countProcedimento2]['codigo'],0,0,'L');
	$pdf->Cell(10,5,'',0,0,'L');
	$pdf->Cell(35,5,shortstr(utf8_decode($procedimentos[$countProcedimento2]['descrisao'])),0,0,'L');
	$pdf->Cell(10,5,$procedimentos[$countProcedimento2]['quantidade'],0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(7,5,'',0,0,'L');
	$pdf->Cell(26,5,'',0,0,'L');
	$valoUnidade= $procedimentos[$countProcedimento2]['preco'] / $procedimentos[$countProcedimento2]['quantidade'];
	$pdf->Cell(36,5,($valoUnidade != 0)?number_format($valoUnidade, 2, ',', '.'):'',0,0,'L');
	$valorTotal += $procedimentos[$countProcedimento2]['preco'];
	$pdf->Cell(35,5,($procedimentos[$countProcedimento2]['preco'] != 0)?number_format($procedimentos[$countProcedimento2++]['preco'], 2, ',', '.'):'',0,0,'L');
	$pdf->Ln(8);
	//FIM 19 LINHA

	$pdf->Cell(8,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(55,5,'',0,0,'L');
	$pdf->Cell(3,5,'',0,0,'L');
	$pdf->Cell(52,5,'',0,0,'L');
	$pdf->Cell(4,5,'',0,0,'L');
	$pdf->Cell(52,5,'',0,0,'L');
	$pdf->Cell(4,5,'',0,0,'L');
	$pdf->Cell(54,5,'',0,0,'L');
	$pdf->Cell(5,5,'',0,0,'L');
	$pdf->Cell(55,5,'',0,0,'L');
	$pdf->Ln(5);
	//FIM 20 LINHA

	$pdf->Cell(8,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(55,5,'',0,0,'L');
	$pdf->Cell(3,5,'',0,0,'L');
	$pdf->Cell(52,5,'',0,0,'L');
	$pdf->Cell(4,5,'',0,0,'L');
	$pdf->Cell(52,5,'',0,0,'L');
	$pdf->Cell(4,5,'',0,0,'L');
	$pdf->Cell(54,5,'',0,0,'L');
	$pdf->Cell(5,5,'',0,0,'L');
	$pdf->Cell(55,5,'',0,0,'L');
	$pdf->Ln(7);
	//FIM 21 LINHA


	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(286,5,$guiaTiss['guia-tiss-observacoes'],0,0,'L');
	$pdf->Ln(5);
	//FIM 22 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(286,5,'',0,0,'L');
	$pdf->Ln(7);
	//FIM 23 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(38,5,/*number_format($guiaTiss['guia-tiss-total_procedimentos'], 2, ',', '.')*/'',0,0,'L');
	$pdf->Cell(40,5,/*number_format($guiaTiss['guia-tiss-total_taxas_alugueis'], 2, ',', '.')*/'',0,0,'L');
	$pdf->Cell(40,5,/*number_format($guiaTiss['guia-tiss-total_materiais'], 2, ',', '.')*/'',0,0,'L');
	$pdf->Cell(41,5,/*number_format($guiaTiss['guia-tiss-total_medicamento'], 2, ',', '.')*/'',0,0,'L');
	$pdf->Cell(41,5,'',0,0,'L');
	$pdf->Cell(42,5,'',0,0,'L');
	$pdf->Cell(46,5,number_format(/*$guiaTiss['guia-tiss-total_geral_guia']*/$valorTotal, 2, ',', '.'),0,0,'L');
	$pdf->Ln(9);
	//FIM 24 LINHA

	$pdf->Cell(6,5,'',0,0,'L'); // ESPAÇO INICIAL
	$pdf->Cell(70,5,$guiaTiss['guia-tiss-data_solicitante'],0,0,'L');
	$pdf->Cell(73,5,$guiaTiss['guia-tiss-data_responsavel_autorizacao'],0,0,'L');
	$pdf->Cell(69,5,$guiaTiss['guia-tiss-data_beneficio_responsavel'],0,0,'L');
	$pdf->Cell(74,5,$guiaTiss['guia-tiss-data_prestador_executante'],0,0,'L');
	$pdf->Ln(7);
	//FIM 25 LINHA
}
//imprime a saida do arquivo..
$pdf->Output("guiatiss.pdf","I");

?>
