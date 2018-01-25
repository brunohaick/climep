<?php

require('fpdf.php');
require('./Bootstrap.php');
conectar();

class PDF extends FPDF {

	function Orcamento_Header($result) {

		$result_data = split('-', $result['modulo_data']);
		$diasemana = mostraSemana(date("w", mktime(0, 0, 0, $result_data[1], $result_data[2], $result_data[0])));
		$dados['data'] = 'Belém, ' . $diasemana . ', ' . $result_data[2] . ' de ' . mostraMes($result_data[1]) . ' de ' . $result_data[0];

		$genero = $result['generoDaPessoa'];
		$dados['texto_1'] = ($genero === 'F') ? 'Prezada Senhora,' : 'Prezado Senhor,';

		$dados['texto_2'] = 'Conforme nossos entendimentos estamos apresentando orçamento. As vacinações e a água potável constituem-se os recursos que mais previnem doenças e sofrimento em todo o Mundo, representando o mais econômico investimento de saúde, com benefícios para toda a vida.';

		$dados['nome'] = utf8_encode($result['nomeDaPessoa'] . ' ' . $result['sobrenomeDaPessoa']);

		$dados['endereco'] = utf8_encode($result['enderecoDaPessoa']);
		$dados['numero_end'] = utf8_encode($result['numeroDaPessoa']);

		$dados['cep'] = utf8_encode($result['cepDaPessoa']);
		$dados['cidade'] = utf8_encode($result['cidadeDaPessoa']);
		$dados['estado'] = utf8_encode($result['estadoDaPessoa']);

		$dados['apresentacao'] = ($genero === 'F') ? 'Ilma. Sra.' : 'Ilmo. Sr.';

		$this->Cell(0, 5, utf8_decode($dados['data']), 0, 1, 'R');
		$this->Cell(0, 5, utf8_decode($dados['apresentacao']), 0, 1, 'L');
		$this->Cell(0, 5, strtoupper(utf8_decode($dados['nome'])), 0, 0, 'L');
		$this->Cell(0, 5, utf8_decode($result['matricula']), 0, 1, 'R');
		$this->Cell(0, 5, strtoupper(utf8_decode($dados['endereco'] . ' ' . $dados['numero_end'])), 0, 1, 'L');
		$this->Cell(0, 5, strtoupper(utf8_decode($dados['cep'] . ' ' . $dados['cidade'] . ', ' . $dados['estado'])), 0, 1, 'L');
		$this->Ln(7);
		$this->Cell(0, 5, utf8_decode($dados['texto_1']), 0, 1, 'L');
		$this->Ln(3);
		$this->MultiCell(0, 5, utf8_decode($dados['texto_2']), 0, 'J');
		$this->Ln(7);

		//$this->SetFillColor(219,212,228);
		$this->SetFillColor(230);

		$dados['nome_membro'] = utf8_encode($result['depedenteNome'] . $result['depedenteSobrenome']);
		$dados['t_header'] = 'Orçamento de Programa de Vacinação para';

		$this->Cell(71, 5, utf8_decode($dados['t_header']), 'TBL', 0, 'L', true);
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(0, 5, utf8_decode($dados['nome_membro']), 'TBR', 1, 'L', true);
		$this->SetFont('Arial', '', 10);

		$this->Cell(20, 5, '', 'LRT', 0, 'L', true);
		$this->SetFont('Arial', '', 8);
		$this->Cell(10, 5, utf8_decode('Nº'), 'TLR', 0, 'C', true);
		$this->SetFont('Arial', '', 10);
		$this->Cell(135, 5, utf8_decode('Meses'), 1, 0, 'L', true);
		$this->Cell(25, 5, utf8_decode('Valor referente'), 'TLR', 0, 'C', true);
		$this->Ln(5);
		$this->Cell(20, 5, utf8_decode('Vacinação'), 'LRB', 0, 'L', true);
		$this->SetFont('Arial', '', 8);
		$this->Cell(10, 5, utf8_decode('doses'), 'BLR', 0, 'C', true);
		$this->SetFont('Arial', '', 10);
		$this->Cell(7.105, 5, utf8_decode('0º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('1º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('2º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('3º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('4º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('5º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('6º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('7º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('8º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('9º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('10º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('11º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('12º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('13º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('14º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('15º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('16º'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('2a'), 1, 0, 'L', true);
		$this->Cell(7.105, 5, utf8_decode('4a'), 1, 0, 'L', true);
		$this->Cell(25, 5, utf8_decode('a uma dose R$'), 'BRL', 1, 'C', true);
	}

	function Orcamento_Dados($result) {
//		die(print_r($result));
		$contagem = -1;
		$nomeTemp = '';
//		$valorTotalDaVacinaAvulca = 0;
//		$valorTotalDoPrecoAvista = 0;
//		$valorTotalApraso = 0;

//		$Soma_Total_prazo = 0;
//		$Soma_Total = 0;
//		$Soma_Total_vista = 0;
//		$Soma_Total_DoPrograma = 0;
		while (($row = mysqli_fetch_assoc($result)) != null) {
			if ($nomeTemp !== $row['nome']) {
				$contagem++;
				$dados[$contagem]['vacinacao'] = utf8_encode($row['nome']);
				$dados[$contagem]['n_doses'] = utf8_encode($row['count']);
				$nomeTemp = $row['nome'];
				$dados[$contagem]['valor_dose'] = utf8_encode($row['preco_cartao']);

//				$Linha_valor_prazo = $row['preco_cartao'];
//				$valorfixo = $Linha_valor_prazo;
//				$valor_vista = $row['preco_aVista'];
//				$valorDoPrograma = 0;
//
//
//				if ($row['descontoBCG'] == '1' && ($dados[$contagem]['vacinacao'] === "BCG id" || $dados[$contagem]['vacinacao'] === "BCG pc")) {
//					if ($row['descontoMedico'] == '1') {
//						$valor_vista = 89 * 0.80;
//						$Linha_valor_prazo = 89 * 0.85;
//						$valorfixo = 89;
//					} else {
//						$valor_vista = 81;
//						$Linha_valor_prazo = 89 * 0.94;
//						$valorfixo = 89;
//					}
//				} else {
//					if ($row['descontoPromocional'] == '1' && $dados[$contagem]['vacinacao'] === 'HPV MSD' && $row['count'] > 2) {
//						if ($row['descontoMedico'] == '1') {
//							$valor_vista = 380 * 0.80;
//							$Linha_valor_prazo = 380 * 0.85;
//							$valorfixo = 380;
//						} else {
//							$valor_vista = 350;
//							$Linha_valor_prazo = 380;
//							$valorfixo = 380;
//						}
//					} else {
//						if ($row['descontoMedico'] == '1') {
//							$valor_vista = $Linha_valor_prazo * 0.80;
//							$Linha_valor_prazo = $Linha_valo	$Linha_valor_prazo = $row['preco_cartao'];
				$valorfixo = $Linha_valor_prazo;
				$valor_vista = $row['preco_aVista'];
				$valorDoPrograma = 0;


				if ($row['descontoBCG'] == '1' && ($dados[$contagem]['vacinacao'] === "BCG id" || $dados[$contagem]['vacinacao'] === "BCG pc")) {
					if ($row['descontoMedico'] == '1') {
						$valor_vista = 89 * 0.80;
						$Linha_valor_prazo = 89 * 0.85;
						$valorfixo = 89;
					} else {
						$valor_vista = 81;
						$Linha_valor_prazo = 89 * 0.94;
						$valorfixo = 89;
					}
				} else {
					if ($row['descontoPromocional'] == '1' && $dados[$contagem]['vacinacao'] === 'HPV MSD' && $row['count'] > 2) {
						if ($row['descontoMedico'] == '1') {
							$valor_vista = 380 * 0.80;
							$Linha_valor_prazo = 380 * 0.85;
							$valorfixo = 380;
						} else {
							$valor_vista = 350;
							$Linha_valor_prazo = 89 * 0.94;
							$valorfixo = 380;
						}
					} else {
						if ($row['descontoMedico'] == '1') {
							$valor_vista = $Linha_valor_prazo * 0.80;
							$Linha_valor_prazo = $Linha_valor_prazo * 0.85;
						} else {
							$valor_vista = $valor_vista;
							$Linha_valor_prazo = $Linha_valor_prazo;
						}
					}
				}

				$Soma_Total = $Soma_Total + ($row['count'] * $valorfixo);
				$Soma_Total_prazo = $Soma_Total_prazo + ($row['count'] * $Linha_valor_prazo);
				$Soma_Total_vista = $Soma_Total_vista + ($valor_vista * $row['count']);
				$Soma_Total_DoPrograma = $valorDoPrograma;
				$valorTotalDaVacinaAvulca += ($dados[$contagem]['n_doses'] * $dados[$contagem]['valor_dose']);

				$temp = $dados[$contagem]['valor_dose'];
				if (($dados[$contagem]['vacinacao'] === 'BCG id' || $dados[$contagem]['vacinacao'] === 'BCG pc') && $row['descontoBCG'] == '1') {
					$row['preco_aVista'] = '81';
					$temp = '89';
				}
				if ($row['descontoMedico'] == '1') {
					$row['preco_aVista'] -= $row['preco_aVista'] * 0.2;
					$temp -= $dados[$contagem]['valor_dose'] * 0.15;
				}
				if ($dados[$contagem]['vacinacao'] === 'HPV MSD' && $row['descontoPromocional'] == '1') {
					$row['preco_aVista'] = '350';
					$temp = '380';
				}

				$valorTotalApraso += ($dados[$contagem]['n_doses'] * $temp);
				$var_prazo * 0.85;
//						} else {
//							$valor_vista = $valor_vista;
//							$Linha_valor_prazo = $Linha_valor_prazo;
//						}
//					}
//				}
//
//				$Soma_Total = $Soma_Total + ($row['count'] * $valorfixo);
//				$Soma_Total_prazo = $Soma_Total_prazo + ($row['count'] * $Linha_valor_prazo);
//				$Soma_Total_vista = $Soma_Total_vista + ($valor_vista * $row['count']);
//				$Soma_Total_DoPrograma = $valorDoPrograma;
//				$valorTotalDaVacinaAvulca += ($dados[$contagem]['n_doses'] * $dados[$contagem]['valor_dose']);
//
//				$temp = $dados[$contagem]['valor_dose'];
//				if (($dados[$contagem]['vacinacao'] === 'BCG id' || $dados[$contagem]['vacinacao'] === 'BCG pc') && $row['descontoBCG'] == '1') {
//					$row['preco_aVista'] = '81';
//					$temp = '89';
//				}
//				if ($row['descontoMedico'] == '1') {
//					$row['preco_aVista'] -= $row['preco_aVista'] * 0.2;
//					$temp -= $dados[$contagem]['valor_dose'] * 0.15;
//				}
//				if ($dados[$contagem]['vacinacao'] === 'HPV MSD' && $row['descontoPromocional'] == '1') {
//					$row['preco_aVista'] = '350';
//					$temp = '380';
//				}
//
//				$valorTotalApraso += ($dados[$contagem]['n_doses'] * $temp);
//				$valorTotalDoPrecoAvista += ($dados[$contagem]['n_doses'] * $row['preco_aVista']);
			}

			if ($row['posicao_horizontal'] == '18')
				$row['posicao_horizontal'] = '2a';
			else if ($row['posicao_horizontal'] == '19')
				$row['posicao_horizontal'] = '4a';
			else {
				$row['posicao_horizontal'] -= 1;
			}

			$dados[$contagem][$row['posicao_horizontal']] = 'x';
		}

//		if ($contagem >= 2) {//para 3 itens da um desconto de 6%
//			$valorTotalApraso -= $valorTotalApraso * 0.06;
//		}

		foreach ($dados as $vacinacao) {

			$this->SetFont('Arial', '', 8);
			$this->Cell(20, 5, utf8_decode($vacinacao['vacinacao']), 1, 0, 'L');
			$this->SetFont('Arial', '', 10);
			$this->Cell(10, 5, utf8_decode($vacinacao['n_doses']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['0']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['1']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['2']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['3']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['4']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['5']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['6']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['7']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['8']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['9']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['10']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['11']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['12']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['13']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['14']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['15']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['16']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['2a']), 1, 0, 'C');
			$this->Cell(7.105, 5, utf8_decode($vacinacao['4a']), 1, 0, 'C');
			$this->Cell(25, 5, utf8_decode(number_format($vacinacao['valor_dose'], 2, ',', '.')), 1, 1, 'R');
		}

//		var_dump($Soma_Total_prazo);
//		var_dump($Soma_Total);
//		var_dump($Soma_Total_vista);
//		var_dump($Soma_Total_DoPrograma);
		if ($contagem > 3 && ($nomeTemp !== "BCG id" || $nomeTemp !== "BCG pc") && $row['descontoBCG'] != '1' && $row['descontoMedico'] != '1') {
			$valorDoPrograma = $Soma_Total_prazo * 0.94;
		} else {
			$valorDoPrograma = $Soma_Total_prazo;
		}

		$dados['valor_total_avulsa'] = number_format($_GET['valorTotal'], 2, ',', '.');
		$dados['valor_total_programa'] = number_format($_GET['valorDoPrograma'], 2, ',', '.');
		if ($_GET['parcela'] != 0)
			$dados['valor_parcelado'] = number_format(($_GET['valorDaParcela']), 2, ',', '.');
		else
			$dados['valor_parcelado'] = '';

		$dados['valor_total_programa_avista'] = number_format($_GET['valorDesContoVista'], 2, ',', '.');

		$this->Cell(165, 5, utf8_decode('Valor Total da vacinação avulsa'), 1, 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(25, 5, utf8_decode($dados['valor_total_avulsa']), 1, 1, 'R');
		$this->SetFont('Arial', '', 10);

		//$this->Cell(165,5,utf8_decode('Valor total do Programa de Vacinação com desconto para parcelar'),1,0,'R');
		$this->Cell(80, 5, utf8_decode('Valor total do '), 'TBL', 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(40, 5, utf8_decode('Programa de Vacinação'), 'TB', 0, 'R');
		$this->SetFont('Arial', '', 10);
		$this->Cell(45, 5, utf8_decode('com desconto para parcelar'), 'TBR', 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(25, 5, utf8_decode($dados['valor_total_programa']), 1, 1, 'R');
		$this->SetFont('Arial', '', 10);

		//$this->Cell(165,5,utf8_decode('Valor da parcela (12 x) em cartão de crédito'),1,0,'R');
		$this->Cell(119, 5, utf8_decode('Valor da parcela'), 'TBL', 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(12, 5, utf8_decode('( ' . $_GET['parcela'] . ' x )'), 'TB', 0, 'R');
		$this->SetFont('Arial', '', 10);
		$this->Cell(34, 5, utf8_decode('em cartão de crédito'), 'TBR', 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(25, 5, utf8_decode($dados['valor_parcelado']), 1, 1, 'R');
		$this->SetFont('Arial', '', 10);

		//$this->Cell(165,5,utf8_decode('Valor total do Programa de Vacinação com desconto para pagamento à vista'),1,0,'R');
		$this->Cell(64, 5, utf8_decode('Valor total do '), 'TBL', 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(40, 5, utf8_decode('Programa de Vacinação'), 'TB', 0, 'R');
		$this->SetFont('Arial', '', 10);
		$this->Cell(61, 5, utf8_decode('com desconto para pagamento à vista'), 'TBR', 0, 'R');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(25, 5, utf8_decode($dados['valor_total_programa_avista']), 1, 1, 'R');
		$this->SetFont('Arial', '', 10);

		$this->Ln(1);

		$this->Cell(0, 5, utf8_decode('Observações :'), 1, 1, 'L');
		$this->Cell(0, 5, utf8_decode('Validade do orçamento: 3 dias.'), 'TLR', 1, 'L');
		$this->Cell(0, 5, utf8_decode('-'), 'LR', 1, 'L');
		$this->Cell(0, 5, utf8_decode(''), 'BLR', 1, 'L');

		$dados['texto_1'] = 'Apresentando boas-vindas, os médicos, profissionais de enfermagem e todo o pessoal envolvido nas atividades da Climep manifestam sua alegria em poder proporcionar serviços de alta qualidade para o bem-estar de sua família.';
		$dados['texto_2'] = 'Permanecendo à disposição.';
		$dados['texto_3'] = 'Atenciosamente,';
		$dados['texto_4'] = $_SESSION['usuario']['nome'];
		$dados['texto_5'] = 'Facilitadora do Programa de Vacinação';
		$dados['texto_6'] = 'Tel. 3181-1644 / 3181-1613';

		$this->Ln(3);
		$this->MultiCell(0, 5, utf8_decode($dados['texto_1']), 0, 'J');
		$this->Ln(3);
		$this->MultiCell(0, 5, utf8_decode($dados['texto_2']), 0, 'J');
		$this->Ln(3);
		$this->MultiCell(0, 5, utf8_decode($dados['texto_3']), 0, 'J');
		$this->Ln(3);
		$this->SetFont('Arial', 'B', 10);
		$this->MultiCell(0, 5, utf8_decode($dados['texto_4']), 0, 'J');
		$this->SetFont('Arial', '', 10);
		$this->MultiCell(0, 5, utf8_decode($dados['texto_5']), 0, 'J');
		$this->MultiCell(0, 5, utf8_decode($dados['texto_6']), 0, 'J');
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(10, 20, 10);
$pdf->AddPage();

$moduloId = (isset($_GET['modulo_id'])) ? $_GET['modulo_id'] : '';

if ($moduloId === '')
	die('erro modulo id não passado');


$pdf->Orcamento_Header(orcamentoPegaUsuarioInfomacao($moduloId));
$pdf->Orcamento_Dados(orcamentoPegaVacinas($moduloId));
$pdf->Output("Orcamento.pdf", "I");
