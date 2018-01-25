<?php

//session_start();
require('fpdf.php');
session_start();
require('./Bootstrap.php');
conectar();

class PDF extends FPDF {

    function TCLE($dados) {
	$dados['data'] = date('d') . ' de ' . mostraMes(date('m')) . ' de ' . date('Y');
	$dados['nome_titular'] = $dados['nome'] . " " . $dados['sobrenome'];
	$dados['rg_titular'] = $_GET['rg'];
	$dados['nome_membro'] = $dados['nome_titular'];
	$dados['nascimento_membro'] = $dados['data_nascimento'];
	if ($_GET['userId'] !== 'undefined') {
	    $dependente = buscaDadosDepedente2($_GET['userId']);
	    $split = split('-', $dependente['data_nascimento']);
	    $dados['nascimento_membro'] = $split[2] . '/' . $split[1] . '/' . $split[0];
	    $dados['nome_membro'] = $dependente['nome'] . " " . $dependente['sobrenome'];
	    ;
	}
	$dados['q1_sim'] = 'X';
	$dados['q1_nao'] = ' ';
	$dados['q2_sim'] = 'X';
	$dados['q2_nao'] = ' ';
	$dados['texto_1'] = 'Seu filho está sendo convidado a participar do presente estudo. O documento abaixo contém todas as informações necessárias sobre a pesquisa que estamos (ou estaremos) fazendo. Leia atentamente. Caso tenha dúvidas, teremos prazer em esclarecê-las. Se concordar, o documento será assinado e só então daremos início ao estudo. Sua colaboração será muito importante para nós. Mas, se quiser desistir a qualquer momento, isto não causará nenhum prejuízo, nem a você, nem ao(à) seu (sua) filho(a).';
	$dados['texto_2'] = 'Eu, ' . $dados['nome_titular'] . ', ' . $dados['rg_titular'] . ', abaixo assinado (a), concordo de livre e espontânea vontade que meu (minha) filho(a) ' . $dados['nome_membro'] . ', nascido(a) em ' . $dados['nascimento_membro'] . ', seja voluntário do estudo "Desenvolvimento de metodologia para detecção de imunodeficiência combinada grave no período neonatal". Declaro que obtive todas as informações necessárias e que todas as minhas dúvidas foram esclarecidas.';
	$dados['texto_4'] = '1. O estudo é necessário para que possamos descobrir um novo método para diagnóstico de imunodeficiência combinada grave. Quando diagnosticadas precocemente, muitas doenças podem ser tratadas antes de apresentar complicações e isto pode melhorar a qualidade de vida da criança;';
	$dados['texto_5'] = '2. A participação neste estudo não tem fins terapêuticos e será sem custo algum para mim;';
	$dados['texto_6'] = '3. Tenho a liberdade de desistir ou interromper a colaboração neste estudo no momento em que desejar, sem necessidade de dar qualquer explicação;';
	$dados['texto_7'] = '4. A desistência não causará nenhum prejuízo a mim, nem (a) meu (minha) filho (a), nem interferirá no atendimento ou tratamento médico a que ele (ela) estiver sendo submetido;';

	$dados['texto_8'] = '5. Os resultados obtidos durante este estudo serão mantidos em sigilo, mas concordo em que sejam divulgados em publicações científicas, desde que nem o meu nome, nem o de meu filho sejam mencionados;';
	$dados['texto_9'] = '6. Caso eu deseje, poderei tomar conhecimento dos resultados ao final deste estudo;';
	$dados['texto_10'] = '7. Poderei contatar a Secretaria da Comissão de Ética em Pesquisa com Seres Humanos - ICB/USP - no Fone 3091.7733 (e-mail: cep@icb.usp.br) ou Dra. Marília Kanegae no fone (11) 3091-7435 ou Dr. Newton Bellesi, nbellesi@climep.com.br, fone (91) 3181-1644 para recursos ou reclamações em relação ao presente estudo.';
	$dados['texto_11'] = '8. Concordo que o material possa ser utilizado em outros projetos desde que autorizado pela Comissão de Ética deste Instituto e pelo responsável por esta pesquisa. Caso minha manifestação seja positiva, poderei retirar essa autorização a qualquer momento sem qualquer prejuízo a mim ou ao meu (minha) filho (a). Assinalar com (x): Sim (' . $dados['q1_sim'] . ') Não (' . $dados['q1_nao'] . ')';
	$dados['texto_12'] = '9. O sujeito de pesquisa ou seu representante, quando for o caso, deverá rubricar todas as folhas do TCLE apondo sua assinatura na última página do referido Termo.';
	$dados['texto_13'] = '10. O pesquisador responsável deverá da mesma forma, rubricar todas as folhas do TCLE apondo sua assinatura na última página do referido Termo.';
	$dados['texto_14'] = '11. Resolução 196/96 - Estou recebendo uma cópia deste Termo de Consentimento Livre e Esclarecido.';
	$dados['texto_15'] = 'Assinalar com (x): Desejo conhecer os resultados desta pesquisa (' . $dados['q2_sim'] . ') Não desejo conhecer os resultados desta pesquisa (' . $dados['q2_nao'] . ')';



	$this->SetFont('Arial', 'B', 10);
	$this->Cell(0, 5, utf8_decode('Universidade de São Paulo'), 0, 1, 'J');
	$this->Cell(0, 5, utf8_decode('Instituto de Ciências Biomédicas'), 'B', 1, 'J');
	$this->Cell(0, 5, utf8_decode('TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO - TCLE'), 'T', 1, 'J');
	$this->SetFont('Arial', '', 10);
	$this->Cell(0, 5, utf8_decode('(menores de 18 anos)'), 0, 1, 'J');
	$this->Ln(3);
	$this->SetFont('Arial', 'B', 10);
	$this->MultiCell(0, 5, utf8_decode('Desenvolvimento de metodologia para detecção de imunodeficiência combinada grave no período neonatal'), 0, 'J');
	$this->Ln(3);
	$this->SetFont('Arial', 'I', 10);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_1']), 0, 'J');
	$this->Ln(3);
	$this->SetFont('Arial', '', 10);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_2']), 0, 'J');
	$this->Ln(3);
	$this->Cell(0, 5, utf8_decode('Estou ciente de que:'), 0, 1, 'J');
	$this->MultiCell(0, 5, utf8_decode($dados['texto_4']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_5']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_6']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_7']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_8']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_9']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_10']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_11']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_12']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_13']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_14']), 0, 'J');
	$this->Ln(1);
	$this->MultiCell(0, 5, utf8_decode($dados['texto_15']), 0, 'J');
	$this->Ln(4);
	$this->Cell(0, 5, utf8_decode('Belém, ' . $dados['data'] . '.'), 0, 1, 'J');
	$this->Ln(4);
	$this->SetFont('Arial', 'B', 10);
	$this->Cell(0, 5, utf8_decode(strtoupper($dados['nome_titular'])), 0, 1, 'J');
	$this->Ln(2);


	$this->SetFont('Arial', '', 10);
	$this->Cell(130, 5, utf8_decode('Testemunhas'), 1, 0, 'J');
	$this->Cell(60, 5, utf8_decode('Responsável pelo Projeto'), 1, 1, 'J');
	$this->Cell(65, 7, '', 'TLR', 0, 'J');
	$this->Cell(65, 7, '', 'TLR', 0, 'J');
	$this->Cell(60, 7, '', 'TLR', 1, 'J');

	$this->Cell(65, 5, '', 'LR', 0, 'J');
	$this->Cell(65, 5, '', 'LR', 0, 'J');
	$this->Cell(60, 5, 'Marilia Pyles Patto Kanegae', 'LR', 1, 'J');

	$this->Cell(65, 5, 'Tel.:(91) 3181-1644', 'BLR', 0, 'R');
	$this->Cell(65, 5, 'Tel.:(91) 3181-1644', 'BLR', 0, 'R');
	$this->Cell(60, 5, 'Tel.:(11) 3091-7435', 'BLR', 1, 'J');
    }

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$dados = $_SESSION['editaPessoaClienteDados'];
$pdf->TCLE($dados);
$pdf->Output("TCLE.pdf", "I");
?>
