<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

    function AfastaCell($espacamento) {
        $this->Cell($espacamento, 0, '', '');
    }

    function filtroModulo($serv) {
//		var_dump($serv);die();
        $valorVista = $serv['servico_preco'];
        $valorCartao = $serv['servico_preco_cartao'];
        if ($serv['descontoBCG'] == 1 && (stripos($serv['nome'], 'BCG id') !== false || stripos($serv['nome'], 'BCG pc') !== false)) {
            if ($serv['descontoMedico'] == 1) {
                $valorVista = 89 * 0.80;
                $valorCartao = 89 * 0.85;
            } else {
                $valorVista = 81;
                $valorCartao = 89 * 0.94;
            }
        } else {
            if ($serv['descontoPromocional'] == 1 && stripos($serv['nome'], "HPV MSD") !== false) {
                if ($serv['descontoMedico'] == 1) {
                    $valorVista = 380 * 0.80;
                    $valorCartao = 380 * 0.85;
                } else {
                    $valorVista = 350;
                    $valorCartao = 380;
                }
            } else {
                if ($serv['descontoMedico'] == 1) {
                    $valorVista = floatval($valorVista * 0.80);
                    $valorCartao = floatval($valorCartao * 0.85);
                } else {
                    $valorVista = floatval($valorVista);
                    $valorCartao = floatval($valorCartao);
                }
            }
        }
        $valor['valorVista'] = $valorVista;
        $valor['valorCartao'] = $valorCartao;
        return $valor;
    }

    function RelatorioResumoCaixa($header, $data, $data_inicio, $data_fim) {
//         var_dump(print_r($data));
        $dados['total'] = $data['total'];
        $dados['cartoes'] = $data['cartoes'];
        unset($data['total']);
        unset($data['cartoes']);
        $fonte = 9;
        $totalDescontos = 0;
        $totalCartaoDeb = 0;
        $vacinacaocasa = 0;

        $w = array(16, 16, 80, 18, 17, 8, 20, 18);

        $this->SetFont('Arial', 'B', 12);
        // Header
        $this->Cell(110, 5, utf8_decode("CLIMEP - RELATÓRIO DE CAIXA"), 0, 0, 'L');
        $this->SetFont('Arial', '', $fonte);

	$diff_dias = diferenca_data_dias(converteData($data_inicio), converteData($data_fim));

	if($diff_dias > 0) {
        	$this->Cell(30, 5, $data_inicio . " A " . $data_fim, 0, 0, 'R');
	} else if($diff_dias == 0 ) {
        	$this->Cell(30, 5, $data_inicio, 0, 0, 'R');
	}

        $this->Cell(50, 5, "Unidade Braz", 0, 0, 'R');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial', 'U', $fonte);
        $this->Cell(17, 5, "Controle", 0, 0, 'L');
        $this->Cell(115, 5, utf8_decode("Matrícula"), 0, 0, 'L');
        $this->Cell(15, 5, "Valor", 0, 0, 'L');
        $this->Cell(10, 5, "Parc", 0, 0, 'L');
        $this->Ln();

        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', $fonte);
        // Data
        $fill = false;
        // for($i=0;$i<count($header);$i++)
        //     $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
        // $this->Ln();        

        $relatorio = array();
        $totalpagamento = 0;
        foreach ($data as $row) {
            $forma_pagamento = '1'; //Default Dinheiro
            $totalValorDesconto = 0;
            $totalServicos = 0;
            $totaldeCredito =0;
            $rectsize = 0;
            $this->MultiCell(0, 5, '', 0, 'L');

            $date = explode('/', $row['data']);
            $date = $date[0] . '/' . $date[1] . '/' . substr($date[2], 2);

            $this->Cell($w[0], 6, $row['controle'], 0, 0, 'C', $fill);
            $this->Cell($w[1], 6, $row['matricula'], 0, 0, 'C', $fill);
            $this->Cell(59, 6, encurtar2($row['responsavel'], 28), 0, 0, 'L', $fill);
            $tmpx = $this->GetX();
            $tmpy = $this->GetY();
            $this->Ln(3);
            $this->SetFont('Arial', 'I', 8);
            $this->AfastaCell(25);
            $this->Cell(30, 7, $row['categoria'], 0, 0, 'C', $fill);
            $this->SetFont('Arial', '', $fonte);
            $this->SetX($tmpx);
            $this->SetY($tmpy);
            $this->AfastaCell(92);
            $tmpx = $this->GetX();
            $tmpy = $this->GetY();
            $this->SetFont('Arial', '', 8);
            $this->Cell(80, 6, $date, 0, 0, 'R', $fill);
            $this->Cell(18, 6, $row['nota_fiscal'], 0, 0, 'R', $fill);
            $this->SetFont('Arial', '', $fonte);
            $this->SetX($tmpx);
            $this->SetFont('Arial', '', 8);
            $nlinha = 0;
            $first = true;
            $aux = 0;
            foreach ($row['nome_forma_pagamento'] as $key => $forma) {
                if ($forma['valor'] != 0) {
					$valorForma = str_replace ( ",", ".", $forma['valor']);
                    $relatorio[$key] += $valorForma;
                    if ($key !== 'Dinheiro' && $key !== 'Desconto' && $key !== 'ELETRO-VISA' && $key !== 'MAESTRO' && stripos($key, 'Elo d') === false) {
                        $forma_pagamento = '0';
//                        if($key == 'AMEX'){
//                         die('Cartao');   
//                        }
                    }
                    if ($key !== 'Util. de Credito') {
                        $totalpagamento += $valorForma;
                    }  else {
                        $totaldeCredito += $valorForma;
                    }
                    $rectsize += 5.2;
                    if ($first) {
//                    $this->AfastaCell(3);
                        $first = false;
                    } else {
                        $this->AfastaCell(92);
                    }
                    $this->Cell(32, 4, utf8_decode($key), 0, 0, 'L', $fill);
                    $this->AfastaCell(1);
                    $this->Cell(17, 4, number_format($valorForma, 2, ',', '.'), 0, 0, 'R', $fill);
                    $this->AfastaCell(7);

                    $this->Cell(7, 4, $forma['parcela'] . 'x', 0, 0, 'R', $fill);
                    $this->Ln();
                    if ($key !== 'Util. de Credito') {
                        $aux = $aux + $valorForma;
                    }
                }
            }
            foreach ($row['servicos'] as $rolling) {
                if ($rolling['Modulo'] == 1) {
                    $filtro = $this->filtroModulo($rolling);
                    $totalValorDesconto += ($forma_pagamento == '1') ? $filtro['valorVista'] : $filtro['valorCartao'];
                    $totalServicos += ($forma_pagamento == '1') ? $rolling['servico_preco'] : $rolling['servico_preco_cartao'];
                }
            }
            if ($aux == 0) {
                $this->Ln(5);
            }

//			if ($row['desconto'] > 0) {
//				$totalDescontos +=$row['desconto'];
//				$this->AfastaCell(92);
//				$this->Cell(32, 6, 'Desconto', 0, 0, 'L', $fill);
//				$this->AfastaCell(1);
//				$this->Cell(17, 6, '' . number_format(($row['desconto']), 2, ',', '.'), 0, 0, 'R', $fill);
//				$this->AfastaCell(7);
//				$this->Cell(7, 6, '', 0, 0, 'R', $fill);
//				$this->Ln();
//				$rectsize += 3;
//			}

            if ($rectsize == 0) {
                $rectsize = 5;
            }
            $this->Rect($tmpx, $tmpy, 64, $rectsize + 1);

            $sumServ = 0;
            foreach ($row['servicos'] as $rolling) {
                if ($rolling['Modulo'] == 1) {
                    $filtro = $this->filtroModulo($rolling);
                    $sumServ += $filtro['valorCartao'];
                } else {
                    $sumServ += ($forma_pagamento == '1') ? $rolling['servico_preco'] : $rolling['servico_preco_cartao'];
                }
            }
            $this->SetFont('Arial', 'B', $fonte);
            $this->AfastaCell(160);
            $this->Cell(30, 6, number_format($aux, 2, ',', '.'), 0, 0, 'R', $fill);
            $this->SetFont('Arial', '', $fonte);

            $totalServicosModulo = 0;
            foreach ($row['servicos'] as $rolling) {
                $modulo = $rolling['Modulo'];
                $this->Ln(5);
                $this->AfastaCell(10);
                $this->Cell(5, 4, $rolling['membro'], 0, 0, 'L', $fill);
                $this->Cell(76, 4, utf8_decode($rolling['Nome Dependente']), 0, 0, 'L', $fill);
                $this->Cell(31, 4, $rolling['nome'], 0, 0, 'L', $fill);

                if($rolling['nome'] == 'vacina em casa 1') {
			$vacinacaocasa = 30;
			$relatorio['Visitas'] += $vacinacaocasa;
                }

                if($rolling['nome'] == 'vacina em casa 2') {
			$vacinacaocasa = 40;
			$relatorio['Visitas'] += $vacinacaocasa;
                }

                if ($modulo == 0) {
                    if ($rolling['statusNome'] == 'Pago e Tomado') {
                        $this->Cell(20, 4, number_format(0, 2, ',', '.'), 0, 0, 'R', $fill);
                    } else {
                        //$rolling['forma_pagamento']
                        $this->Cell(20, 4, number_format(($forma_pagamento == '1') ? $rolling['servico_preco'] : $rolling ['servico_preco_cartao'], 2, ',', '.'), 0, 0, 'R', $fill);
                    }
                } else {
                    $filtro = $this->filtroModulo($rolling);
                    $this->Cell(20, 4, number_format($rolling['servico_preco_cartao'], 2, ',', '.'), 0, 0, 'R', $fill);
                }
                $this->AfastaCell(3);
                if ($rolling['statusNome'] == 'Pago e Tomado') {
                    $rolling['statusNome'] = 'Ja Pago';
                }
                if ($modulo == 1) {
                    $totalServicosModulo += $rolling['servico_preco_cartao'];
                    $rolling['statusNome'] = 'Modulo';
                }
                $this->Cell(25, 4, $rolling['statusNome'], 0, 0, 'L');
                $this->AfastaCell(2);
//                $this->Cell(23, 6, $rolling['statusNome'], 0, 0, 'L');                
            }

            if ($modulo == 1) {
                $this->Ln(5);
                $this->AfastaCell(10);
                $this->Cell(5, 6, '', 0, 0, 'L', $fill);
                $this->Cell(76, 6, '', 0, 0, 'L', $fill);
                $this->Cell(31, 6, 'Desconto Climep: ', 0, 0, 'L', $fill);
                $this->Cell(20, 6, '-' . number_format(($totalServicosModulo - $aux) - $totaldeCredito - $vacinacaocasa, 2, ',', '.'), 0, 0, 'R', $fill);
                $this->AfastaCell(3);
                $this->Cell(25, 6, 'Desc. Modulo', 0, 0, 'L');
                $this->AfastaCell(2);
            }
		$this->Ln();
            $this->MultiCell(0, 5, '', 0, 'L');
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln();
        $this->MultiCell(0, 5, '', 0, 'L');
        $this->MultiCell(0, 5, '', 0, 'L');
        $this->MultiCell(0, 5, '', 0, 'L');

        /* 
	 * RELATORIOS FINAIS - CARTAO E FORMA DE PAGAMENTO
	 */

	/*
	 * Calculando Total de Pagamentos, subtraindo Vacinas em Casa, Descontos...
	 * Bruno Haick - 17/05/2015
	 */
	$totalpagamento  = $totalpagamento - $relatorio['Visitas'] - $relatorio['Desconto'];
	$abatimentosautorizados += $relatorio['Desconto'];

	$tmpx = $this->GetX();
        $tmpy = $this->GetY();

        $this->Cell(40, 6, "Dinheiro: ", 'TLR', 0, 'R', $fill);
        $this->Cell(28, 6, number_format($relatorio['Dinheiro'], 2, ',', '.'), 'TLR', 0, 'R', $fill);

        $this->Ln();
        $this->Cell(40, 6, "Visitas: ", 'LR', 0, 'R', $fill);
        $this->Cell(28, 6, number_format("-" . $relatorio['Visitas'], 2, ',', '.'), 'LR', 0, 'R', $fill);

        $this->Ln();
        //Somar cartoes de debito:
        $totalCartaoDeb = 0;
        foreach ($relatorio as $key => $valor) {
            if ($key === 'ELETRO-VISA' || $key == 'MAESTRO' || stripos($key, 'Elo d') !== false) {
                $totalCartaoDeb = $totalCartaoDeb + $valor;
            }
        }

        $this->Cell(40, 6, utf8_decode("Cartão Deb: "), 'LR', 0, 'R', $fill);
        $this->Cell(28, 6, number_format($totalCartaoDeb, 2, ',', '.'), 'LR', 0, 'R', $fill);

        $this->Ln();
        $this->Cell(40, 6, utf8_decode("Abatimentos autorizados: "), 'LR', 0, 'R', $fill);
        $this->Cell(28, 6, number_format($abatimentosautorizados, 2, ',', '.'), 'LR', 0, 'R', $fill);

        $this->Ln();
        $this->Cell(40, 6, "Descontos concedidos: ", 'LR', 0, 'R', $fill);
        $this->Cell(28, 6, number_format("-" . $totalDescontos, 2, ',', '.'), 'LR', 0, 'R', $fill);

        $this->Ln();
        $this->Cell(40, 6, "Total Pagamentos: ", 'LRB', 0, 'R', $fill);
        $this->Cell(28, 6, number_format($totalpagamento, 2, ' ,', '.'), 'LRB', 0, 'R', $fill); //Somar 

        $this->SetFont('Arial', 'B', 10);
        $this->Ln();

        $tmpvalor = array();
        foreach ($relatorio as $key => $valor) {
            if (stripos($key, 'Visa') !== false && $key !== 'ELETRO-VISA') {
                $tmpvalor ['Visa'] += $valor;
            } else if (stripos($key, 'Elo') !== false) {
                $tmpvalor['Elo'] += $valor;
            } else if (stripos($key, 'Credicard') !== false) {
                $tmpvalor['Credicard'] += $valor;
            } else if (stripos($key, 'Dinners') !== false) {
                $tmpvalor['Dinners'] += $valor;
            } else if (stripos($key, 'Amex') !== false) {
                $tmpvalor['Amex'] += $valor;
            } else {
                $tmpvalor[$key] += $valor;
            }
        }

        $this->Ln();
        foreach ($tmpvalor as $key => $valor) {
            $this->Cell(28, 4, $key . ": ", '', 0, 'R', $fill);
            $this->Cell(28, 4, number_format($valor, 2, ',', '.'), '', 0, 'R', $fill);
            $this->Ln();
        } $this->Ln();

        $this->SetFont('Arial', '', $fonte);
        $tmpx = $this->GetX();
        $tmpy = $this->GetY();
        $this->SetY($tmpy);
//        $this->AfastaCell(88);
        $this->Cell(40, 6, utf8_decode("Cartão crédito"), '', 0, 'L', $fill);
        $this->Ln();
        $rectsizeCredito = 0;
        foreach ($relatorio as $key => $valor) {
            if ($key != 'Cheque-Dia' && $key != 'Convenio' && $key != 'Dinheiro' && $key != 'Visitas' && $key != 'Cartão débito' && $key != 'Abatimentos autorizados' && $key != 'Descontos concedidos' && $key != 'Total Pagamentos') {
                $rectsizeCredito+=5;
//                $this->AfastaCell(88);
                $this->Cell(40, 4, $key . " ", 1, 0, 'R', $fill);
                $this->Cell(27, 4, 'R$ ' . number_format($valor, 2, ',', '.'), 1, 0, 'L', $fill);
                $this->Ln();
            }
        }
//        $this->Rect($tmpx, $tmpy, 70, $rectsizeCredito, '');
        $this->Ln();

        $this->AfastaCell(80);
        $this->Cell(12, 6, 'Caixa:', 0, 0, 'L', $fill);
        $this->Cell(50, 5, '___________________________________', '', 0, 'L', $fill);
        $this->Ln();
        $this->AfastaCell(80);
        $this->Cell(20, 6, 'Financeiro:', 0, 0, 'L', $fill);
        $this->Cell(50, 5, '_______________________________', '', 0, 'L', $fill);
    }

    function Footer() {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print centered page number
        $this->Cell(0, 10, utf8_decode("Pág: ") . $this->PageNo(), 0, 0, 'C');
    }

}

$pdf = new PDF();
// Column headings
$header = array('Controle', 'Matricula', 'Nome', 'FP', 'Valor', 'Parcelas', 'Data', 'Total');
// Data loading
$dados = $_SESSION['resumoCaixa']['dados'];
$data_inicio = $_SESSION['resumoCaixa']['data_inicio'];
$data_fim = $_SESSION['resumoCaixa']['data_fim'];

//die(print_r($dados));
//$dados = ResumoCaixaPDF();
$pdf->SetFont('Arial', '', 10);
$pdf->AddPage();
$pdf->RelatorioResumoCaixa($header, $dados, $data_inicio, $data_fim);
$pdf->Output();
$pdf->Output("ResumoCaixa.pdf", "I");
