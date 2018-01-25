<?php
$tipoForm = _INPUT('tipoForm', 'string', 'post');
if ($tipoForm == "preencheTabMapaPorTipo") {
    $data_inicio = converteData($_POST['data_inicio']);
    $data_fim = converteData($_POST['data_fim']);
    $periodo = DiffDatetoArray($data_inicio, $data_fim);
//    die(print_r($periodo));
    if ($_POST['tipo'] == 2) { // Padrão
        $planoContas['cabecalho'][0] = "Plano";
        $planoContas['cabecalho'][1] = "Descrição";
        foreach ($periodo as $ano_mes) {
            $arr = split('-', $ano_mes);
            $a = $arr[0];
            $m = strtoupper(substr(mostraMes($arr[1]), 0, 3));
            $planoContas['cabecalho'][] = $m . ' / ' . $a;
            $planoContas['cabecalho'][] = "%";
        }
        $planoContas['cabecalho'][] = "Média";
        $planoContas['cabecalho'][] = "%";
        /* Total de Receitas - Faturas, Convenio */
        $totalPorMesFin = buscaTotalPlanoContasPADRAO($data_inicio, $data_fim);
//		$totalPorMesConv = buscaTotalPlanoContasPorMesPorPeriodoConvenio($data_inicio, $data_fim);
//		$totalPorMesLancEntrada = buscaTotalPlanoContasPorMesPorPeriodoLancamentos($data_inicio, $data_fim, 'E');
        /* Total de Despesas - Duplicatas */
        $totalPorMesDup = buscaTotalPlanoContasDuplicataPADRAO($data_inicio, $data_fim);
        //$totalPorMesLancSaida = buscaTotalPlanoContasPorMesPorPeriodoLancamentos($data_inicio, $data_fim, 'S');
        /*
         * RECEITA
         *
         * Calculando o array totalPorMes para Receitas, ou seja, todos os registros
         * contidos nas tabelas fatura_parcelas, fatura_convenio
         *
         * Percorrendo os dois arrays e somando os valores de cada mes e formando um
         * array com essa soma, representando o total por mes de receitas
         *
         * */
        for ($i = 0; $i < count($periodo); $i++) {
            $tmp['valor'] = 0;
            for ($j = 0; $j < count($totalPorMesFin); $j++) {
                if ($totalPorMesFin[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcadd($totalPorMesFin[$j]['valor'], $tmp['valor'], 2);
                }
            }
            $tmp['mes'] = $periodo[$i];
            $tmp['taxa'] = '100.00';
            $totalPorMes[] = $tmp;
            unset($tmp);
        }

        /*
         * MEDIA DE RECEITA
         *
         * Neste loop calcula-se a media de Receitas, de acordo com o periodo informado.
         *
         * */
        for ($i = 0; $i < count($totalPorMes); $i++) {
            $soma = bcadd($soma, $totalPorMes[$i]['valor'], 2);
        }

        $aux['taxa'] = '100.00';
        if ($soma == 0) {
            $aux['valor'] = '0.00';
            $aux['media'] = '0.00';
        } else {
            $aux['valor'] = bcdiv($soma, count($totalPorMes), 2);
            $aux['media'] = bcdiv($soma, count($totalPorMes), 2);
        }

        $totalPorMes[] = $aux;
        unset($aux);
        unset($soma);

        /*
         * DESPESA
         *
         * Calculando o array totalPorMesDup para Despesas, ou seja, todos os registros
         * contidos nas tabelas duplicata_parcelas e Lancamentos com tipoOperação Saida,
         * ou seja, plano contas com codigo 2.*;
         *
         * Percorrendo o array e calculando a taxa percentual tendo como referencia o 
         * valor total de despesas por mês
         *
         * */
        for ($i = 0; $i < count($periodo); $i++) {
            $tmp['valor'] = 0;

            for ($j = 0; $j < count($totalPorMesDup); $j++) {
                if ($totalPorMesDup[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcadd($totalPorMesDup[$j]['valor'], $tmp['valor'], 2);
                }
            }
            $tmp['mes'] = $periodo[$i];
            $tmp['taxa'] = '100.00';
            $totalPorMesDespesa[] = $tmp;
            unset($tmp);
        }

        /*
         * MEDIA DE DESPESA
         *
         * Neste loop calcula-se a media de despesas, de acordo com o periodo informado.
         *
         * */
        for ($i = 0; $i < count($totalPorMesDespesa); $i++) {
            $soma = bcadd($soma, $totalPorMesDespesa[$i]['valor'], 2);
        }
        
        $aux['taxa'] = '100.00';
        if ($soma == 0) {
            $aux['valor'] = '0.00';
            $aux['media'] = '0.00';
        } else {
            $a = count($totalPorMes) - 1;
            $aux['valor'] = bcdiv($soma, count($totalPorMesDespesa), 2);
            $aux['media'] = bcdiv($soma, count($totalPorMesDespesa), 2);
        }
//        die(print_r(count($totalPorMesDespesa)));
        $totalPorMesDespesa[] = $aux;
        unset($aux);
        unset($soma);

        /*
         * RESULTADO = RECEITA - DESPESA
         *
         * Calculando o array totalPorMesResultado que é a diferença entre receitas e
         * despesas.
         *
         * Percorrendo os dois arrays e subtraindo os valores de cada mes e formando um
         * array com a diferença, representando o total de dinheiro existente em caixa
         *
         * */
        for ($i = 0; $i < count($periodo); $i++) {
            $tmp['valor'] = 0;
            for ($j = 0; $j < count($totalPorMes); $j++) {
                if ($totalPorMes[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcadd($totalPorMes[$j]['valor'], $tmp['valor'], 2);
                }
            }
//            die(print_r($totalPorMes));

            for ($j = 0; $j < count($totalPorMesDespesa); $j++) {
                if ($totalPorMesDespesa[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcsub($tmp['valor'], $totalPorMesDespesa[$j]['valor'], 2);
                }
            }

            for ($j = 0; $j < count($totalPorMes); $j++) {
                if ($totalPorMes[$j]['mes'] == $periodo[$i]) {
                    $valor = $totalPorMes[$j]['valor'];
                }
            }
            $tmp['mes'] = $periodo[$i];

            if ($tmp['valor'] != 0) {
                $tmp['taxa'] = bcdiv(($tmp['valor'] * 100), $valor, 2);
            } else {
                $tmp['taxa'] = '0.00';
            }
            $totalPorMesResultado[] = $tmp;
            unset($tmp);
        }

        /*
         * MEDIA DE RESULTADO
         *
         * Neste loop calcula-se a media de Resultado (RECEITAS - DESPESAS), de acordo com o periodo
         * informado.
         *
         * */
        for ($i = 0; $i < count($totalPorMesResultado); $i++) {
            $soma = bcadd($soma, $totalPorMesResultado[$i]['valor'], 2);
        }

        $a = count($totalPorMes) - 1;

        $aux['valor'] = bcdiv($soma, count($totalPorMesResultado), 2); //media
        $aux['taxa'] = bcdiv(($aux['valor'] * 100), $totalPorMes[$a]['valor'], 2); //porcentagem referente a media de receitas
        $totalPorMesResultado[] = $aux;
        unset($aux);
        unset($soma);

        for ($x = 1; $x <= 2; $x++) {
            $planos = buscaPlanoContas($x);

            if ($x == 1) { /* RECEITAS */
                foreach ($planos as $plano) {
                    $codigo = $plano['codigo'];
                    $qtdStr = substr_count($codigo, '.');
                    $codigo_arr = explode(".", $codigo);

                    if ($qtdStr > 0) {

                        if ($codigo_arr[1] == "04") {
                            $arrTotal = buscaTotalPorPlanoContasPorPeriodoConvenio($data_inicio, $data_fim, $codigo);
                        } else {
                            $arrTotal = buscaTotalPorPlanoContasPorPeriodoPadrao($data_inicio, $data_fim, $codigo);
                        }
                        $z = count($arrTotal);
                        
                        /*
                         * Aqui se calcula a representação em porcentagem do valor de
                         * cada plano de contas por mês, inclusive porcentagem da media
                         * destes planos.
                         *
                         * */
                        for ($i = 0; $i < count($totalPorMes); $i++) {
                            for ($j = 0; $j < $z; $j++) {
                                if ($totalPorMes[$i]['mes'] == $arrTotal[$j]['mes']) {
                                    $arrTotal[$j]['taxa'] = bcdiv(($arrTotal[$j]['valor'] * 100), $totalPorMes[$i]['valor'], 2);
                                }
                            }

                            /*
                             * Este bloco só é executado quando o array totalPorMes está
                             * no seu ultimo indice oque indica que está se calculando
                             * representação para a média mensal, desta forma cria-se um
                             * indice novo para calcular a media geral por mes de cada
                             * plano de contas.
                             *
                             * */
                            if ($i == count($totalPorMes) - 1) {
                                if ($z > 0) {
                                    for ($w = 0; $w < $z; $w++) {
                                        $somaMediaMes = bcadd($arrTotal[$w]['media'], $somaMediaMes, 2);
                                    }

                                    $arrTotal[$z]['media'] = bcdiv($somaMediaMes, count($periodo), 2);
                                    $arrTotal[$z]['taxa'] = bcdiv(($arrTotal[$z]['media'] * 100), $totalPorMes[$i]['valor'], 2);
                                    unset($somaMediaMes);
                                }
                            }
                        }

                        $plano['valor'] = $arrTotal;
                    } else if ($qtdStr == 0) {
                        $plano['valor'] = $totalPorMes;
                    }

                    $planoContas1[] = $plano;
                }
//                    die(print_r($planoContas1));
            } else if ($x == 2) {
                foreach ($planos as $plano) {
                    $codigo = $plano['codigo'];
                    $qtdStr = substr_count($codigo, '.');

                    $codigo_arr = explode(".", $codigo);

                    if ($qtdStr > 0) {
                        $arrTotal = buscaTotalPorPlanoContasPorPeriodoDuplicata($data_inicio, $data_fim, $codigo);
                        $z = count($arrTotal);

                        /*
                         * Aqui se calcula a representação em porcentagem do valor de
                         * cada plano de contas por mês, inclusive porcentagem da media
                         * destes planos.
                         *
                         * */
                        for ($i = 0; $i < count($totalPorMesDespesa); $i++) {
                            for ($j = 0; $j < $z; $j++) {
                                if ($totalPorMesDespesa[$i]['mes'] == $arrTotal[$j]['mes']) {
                                    $arrTotal[$j]['taxa'] = bcdiv(($arrTotal[$j]['valor'] * 100), $totalPorMesDespesa[$i]['valor'], 2);
                                }
                            }

                            /*
                             * Este bloco só é executado quando o array totalPorMes está
                             * no seu ultimo indice oque indica que está se calculando
                             * representação para a média mensal, desta forma cria-se um
                             * indice novo para calcular a media geral por mes de cada
                             * plano de contas.
                             *
                             * */
                            if ($i == count($totalPorMesDespesa) - 1) {
                                if ($z > 0) {
                                    for ($w = 0; $w < $z; $w++) {
                                        $somaMediaMes = bcadd($arrTotal[$w]['media'], $somaMediaMes, 2);
                                    }
                                    $arrTotal[$z]['media'] = bcdiv($somaMediaMes, count($periodo), 2);
                                    $arrTotal[$z]['taxa'] = bcdiv(($arrTotal[$z]['media'] * 100), $totalPorMesDespesa[$i]['valor'], 2);
                                    unset($somaMediaMes);
                                }
                            }
                        }

                        $plano['valor'] = $arrTotal;
                    } else if ($qtdStr == 0) {
                        $plano['valor'] = $totalPorMesDespesa;
                    }

                    $planoContas2[] = $plano;
                }
            }
        }
        $planoContas['dados'] = $planoContas1;
        $planoContas['despesas'] = $planoContas2;
        $planoContas['total'] = $totalPorMesResultado;
        $_SESSION['Mapapadrao'] = $planoContas;
        $_SESSION['Mapapadrao']['datainicio'] = $data_inicio;
        $_SESSION['Mapapadrao']['datafim'] = $data_fim;
        //echo "PLANO COM TUDO ";
        die(saidaJson($planoContas));
    } else if ($_POST['tipo'] == 9) { // forma de pagamento
        $planoContas['cabecalho'][0] = "Plano";
        $planoContas['cabecalho'][1] = "Descrição";
        foreach ($periodo as $ano_mes) {
            $arr = split('-', $ano_mes);
            $a = $arr[0];
            $m = strtoupper(substr(mostraMes($arr[1]), 0, 3));
            $planoContas['cabecalho'][] = $m . ' / ' . $a;
            $planoContas['cabecalho'][] = "%";
        }
        $planoContas['cabecalho'][] = "Média";
        $planoContas['cabecalho'][] = "%";

		/* Total de Receitas - Faturas, Convenio */
        $totalPorMesFin = buscaTotalPlanoContasPorMesPorPeriodoFat($data_inicio, $data_fim);
        //die(print_r($totalPorMesFin));
        /* Total de Despesas - Duplicatas */
        $totalPorMesDup = buscaTotalPlanoContasPorMesPorPeriodoDuplicata($data_inicio, $data_fim);

        /*
         * RECEITA
         *
         * Calculando o array totalPorMes para Receitas, ou seja, todos os registros
         * contidos nas tabelas fatura_parcelas, fatura_convenio
         *
         * Percorrendo os dois arrays e somando os valores de cada mes e formando um
         * array com essa soma, representando o total por mes de receitas
         *
         * */
        for ($i = 0; $i < count($periodo); $i++) {

            $tmp['valor'] = 0;

            for ($j = 0; $j < count($totalPorMesFin); $j++) {
                if ($totalPorMesFin[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcadd($totalPorMesFin[$j]['valor'], $tmp['valor'], 2);
                }
            }

            $tmp['mes'] = $periodo[$i];
            $tmp['taxa'] = '100.00';

            $totalPorMes[] = $tmp;
            unset($tmp);
        }

        /*
         * MEDIA DE RECEITA
         *
         * Neste loop calcula-se a media de Receitas, de acordo com o periodo informado.
         *
         * */
        for ($i = 0; $i < count($totalPorMes); $i++) {
            $soma = bcadd($soma, $totalPorMes[$i]['valor'], 2);
        }

        $aux['taxa'] = '100.00';
        if ($soma == 0) {
            $aux['valor'] = '0.00';
            $aux['media'] = '0.00';
        } else {
            $aux['valor'] = bcdiv($soma, count($totalPorMes), 2);
            $aux['media'] = bcdiv($soma, count($totalPorMes), 2);
        }

        $totalPorMes[] = $aux;
        unset($aux);
        unset($soma);

        /*
         * DESPESA
         *
         * Calculando o array totalPorMesDup para Despesas, ou seja, todos os registros
         * contidos nas tabelas duplicata_parcelas e Lancamentos com tipoOperação Saida,
         * ou seja, plano contas com codigo 2.*;
         *
         * Percorrendo o array e calculando a taxa percentual tendo como referencia o 
         * valor total de despesas por mês
         *
         * */
        for ($i = 0; $i < count($periodo); $i++) {
            $tmp['valor'] = 0;

            for ($j = 0; $j < count($totalPorMesDup); $j++) {
                if ($totalPorMesDup[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcadd($totalPorMesDup[$j]['valor'], $tmp['valor'], 2);
                }
            }
            $tmp['mes'] = $periodo[$i];

            $tmp['taxa'] = '100.00';
            $totalPorMesDespesa[] = $tmp;
            unset($tmp);
        }

        /*
         * MEDIA DE DESPESA
         *
         * Neste loop calcula-se a media de despesas, de acordo com o periodo informado.
         *
         * */
        for ($i = 0; $i < count($totalPorMesDespesa); $i++) {
            $soma = bcadd($soma, $totalPorMesDespesa[$i]['valor'], 2);
        }

        $aux['taxa'] = '100.00';
        if ($soma == 0) {
            $aux['valor'] = '0.00';
            $aux['media'] = '0.00';
        } else {
            $a = count($totalPorMes) - 1;
            $aux['valor'] = bcdiv($soma, count($totalPorMesDespesa), 2);
            $aux['media'] = bcdiv($soma, count($totalPorMesDespesa), 2);
        }

        $totalPorMesDespesa[] = $aux;
        unset($aux);
        unset($soma);

        /*
         * RESULTADO = RECEITA - DESPESA
         *
         * Calculando o array totalPorMesResultado que é a diferença entre receitas e
         * despesas.
         *
         * Percorrendo os dois arrays e subtraindo os valores de cada mes e formando um
         * array com a diferença, representando o total de dinheiro existente em caixa
         *
         * */
        for ($i = 0; $i < count($periodo); $i++) {

            $tmp['valor'] = 0;

            for ($j = 0; $j < count($totalPorMes); $j++) {
                if ($totalPorMes[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcadd($totalPorMes[$j]['valor'], $tmp['valor'], 2);
                }
            }

            for ($j = 0; $j < count($totalPorMesDespesa); $j++) {
                if ($totalPorMesDespesa[$j]['mes'] == $periodo[$i]) {
                    $tmp['valor'] = bcsub($tmp['valor'], $totalPorMesDespesa[$j]['valor'], 2);
                }
            }

            for ($j = 0; $j < count($totalPorMes); $j++) {
                if ($totalPorMes[$j]['mes'] == $periodo[$i]) {
                    $valor = $totalPorMes[$j]['valor'];
                }
            }
            $tmp['mes'] = $periodo[$i];

            if ($tmp['valor'] != 0) {
                $tmp['taxa'] = bcdiv(($tmp['valor'] * 100), $valor, 2);
            } else {
                $tmp['taxa'] = '0.00';
            }
            $totalPorMesResultado[] = $tmp;
            unset($tmp);
        }

        /*
         * MEDIA DE RESULTADO
         *
         * Neste loop calcula-se a media de Resultado (RECEITAS - DESPESAS), de acordo com o periodo
         * informado.
         *
         * */
        for ($i = 0; $i < count($totalPorMesResultado); $i++) {
            $soma = bcadd($soma, $totalPorMesResultado[$i]['valor'], 2);
        }

        $a = count($totalPorMes) - 1;

        $aux['valor'] = bcdiv($soma, count($totalPorMesResultado), 2); //media
        $aux['taxa'] = bcdiv(($aux['valor'] * 100), $totalPorMes[$a]['valor'], 2); //porcentagem referente a media de receitas
        $totalPorMesResultado[] = $aux;
        unset($aux);
        unset($soma);

		/*
		 * RECEITAS
		 * Neste relatório só é usado as receitas para exibir na tela.
		 */
		$planos = buscaPlanoContasDistintos(1);

		foreach ($planos as $plano) {
			$codigo = $plano['codigo'];
			$qtdStr = substr_count($codigo, '.');
			$codigo_arr = explode(".", $codigo);

			if ($qtdStr > 0) {

				if ($codigo_arr[1] == "04") {
					$arrTotal = buscaTotalPorPlanoContasPorPeriodoConvenio($data_inicio, $data_fim, $codigo);
				} else {
					$arrTotal = buscaTotalPorPlanoContasPorPeriodo($data_inicio, $data_fim, $codigo);
				}
				$z = count($arrTotal);

                                /*
				 * Aqui se calcula a representação em porcentagem do valor de
				 * cada plano de contas por mês, inclusive porcentagem da media
				 * destes planos.
				 *
				 * */
				for ($i = 0; $i < count($totalPorMes); $i++) {
					for ($j = 0; $j < $z; $j++) {
						if ($totalPorMes[$i]['mes'] == $arrTotal[$j]['mes']) {
							$arrTotal[$j]['taxa'] = bcdiv(($arrTotal[$j]['valor'] * 100), $totalPorMes[$i]['valor'], 2);
						}
					}

					/*
					 * Este bloco só é executado quando o array totalPorMes está
					 * no seu ultimo indice oque indica que está se calculando
					 * representação para a média mensal, desta forma cria-se um
					 * indice novo para calcular a media geral por mes de cada
					 * plano de contas.
					 *
					 * */
					if ($i == count($totalPorMes) - 1) {
						if ($z > 0) {
							for ($w = 0; $w < $z; $w++) {
								$somaMediaMes = bcadd($arrTotal[$w]['media'], $somaMediaMes, 2);
							}

							$arrTotal[$z]['media'] = bcdiv($somaMediaMes, count($periodo), 2);
							$arrTotal[$z]['taxa'] = bcdiv(($arrTotal[$z]['media'] * 100), $totalPorMes[$i]['valor'], 2);
							unset($somaMediaMes);
						}
					}
				}
//                         die(print_r($somaMediaMes));
				$plano['valor'] = $arrTotal;
			} else if ($qtdStr == 0) {
				$plano['valor'] = $totalPorMes;
			}

			$planoContas1[] = $plano;
		}
		

        $planoContas['total'] = $planoContas1[0]['valor'];
        $planoContas['dados'] = array_slice($planoContas1, 1);
//        die(print_r($planoContas));
        $_SESSION['Mapapagamento'] = $planoContas;
        $_SESSION['Mapapagamento']['datainicio'] = $data_inicio;
        $_SESSION['Mapapagamento']['datafim'] = $data_fim;
        //echo "PLANO COM TUDO ";
        die(saidaJson($planoContas));
    } else if ($_POST['tipo'] == 3) {
        /*
	 * VacinasPagas
         * loop para variar os tipos de material onde:
         *      1 - vacinas
         *      2 - imunoterapias
         *      3 - testes (Quando for 3 va pegar os registros do tipo teste e do tipo procedimento) 
         */
//        die("AQUI");
        for ($k = 0; $k < 3; $k++) {
			/**
			 * Montando Cabecalho do Relatorio
			 */
            $data['cabecalho'][] = "Plano";
            $data['cabecalho'][] = "Descrição";
            $periodo = DiffDatetoArray($data_inicio, $data_fim);
            foreach ($periodo as $ano_mes) {
                $arr = split('-', $ano_mes);
                $a = $arr[0];
                $m = strtoupper(substr(mostraMes($arr[1]), 0, 3));
                $data['cabecalho'][] = $m . ' / ' . $a;
                $data['cabecalho'][] = "%";
                $data['cabecalho'][] = "QTD";
            }
            $data['cabecalho'][] = "Média";
            $data['cabecalho'][] = "%";
            $data['cabecalho'][] = "QTD";
			
			/**
			 * 
			 */
			
			$dados = pegaVacinasPagasCaixa($data_inicio, $data_fim, ($k+1));
            $total = pegaVacinasPagasCaixaTotal($data_inicio, $data_fim, ($k + 1));
            $data['dados'] = $dados;
            $ncol = count($dados);
            $nlin = count($dados[0]);
            $totalMedia = 0;
            for ($i = 0; $i < $nlin; $i++) {
                for ($j = 0; $j < $ncol; $j++) {
                    $data['dados'][$ncol][$i]['media'] = bcadd($data['dados'][$j][$i]['valor'], $data['dados'][$ncol][$i]['media'], 2);
                    $data['dados'][$ncol][$i]['qtd'] = bcadd($data['dados'][$j][$i]['qtd'], $data['dados'][$ncol][$i]['qtd'], 2);
                    if ($data['dados'][$j][$i]['valor'] != 0 && $data['dados'][$j][$i]['valor'] != NULL) {
                        $data['dados'][$j][$i]['porc'] = bcdiv(($data['dados'][$j][$i]['valor'] * 100), $total[$j]['valor'], 2);
                    } else {
                        $data['dados'][$j][$i]['porc'] = 0;
                    }
                    $data['totalgeral'][$j]['valor'] = bcadd($data['dados'][$j][$i]['valor'], $data['totalgeral'][$j]['valor'], 2);
                    $data['totalgeral'][$j]['qtd'] = bcadd($data['dados'][$j][$i]['qtd'], $data['totalgeral'][$j]['qtd'], 2);
                    $data['totalgeral'][$j]['porc'] = bcadd($data['dados'][$j][$i]['porc'], $data['totalgeral'][$j]['porc'], 2);
                }
                $data['dados'][$ncol][$i]['media'] = bcdiv($data['dados'][$ncol][$i]['media'], $ncol, 2);
                $totalMedia = bcadd($data['dados'][$ncol][$i]['media'], $totalMedia, 2);
                $data['totalgeral'][$ncol]['valor'] = bcadd($totalMedia, $data['totalgeral'][$ncol]['media'], 2);
                $data['totalgeral'][$ncol]['qtd'] = bcadd($data['dados'][$ncol][$i]['qtd'], $data['totalgeral'][$ncol]['qtd'], 2);
            }

			for ($i = 0; $i < $nlin; $i++) {
                $data['dados'][$ncol][$i]['porc'] = bcdiv(($data['dados'][$ncol][$i]['media'] * 100), $totalMedia, 2);
                $data['totalgeral'][$ncol]['porc'] = bcadd($data['dados'][$ncol][$i]['porc'], $data['totalgeral'][$ncol]['porc'], 2);
            }

            $_SESSION['Mapavacinaspagas'][$k] = $data;
            $_SESSION['Mapavacinaspagas']['cabecalho'] = $data['cabecalho'];
            unset($data);
        }
        unset($totalGeral);        
        for ($k = 0; $k < 3; $k++) {
            $data = $_SESSION['Mapavacinaspagas'][$k];
            foreach ($data['totalgeral'] as $key => $valor) {
                $totalGeral[$key] = bcadd($valor['valor'], $totalGeral[$key], 2);
            }
        }
        $_SESSION['Mapavacinaspagas']['totalgeral2'] = $totalGeral;
        $_SESSION['Mapavacinaspagas']['datainicio'] = $data_inicio;
        $_SESSION['Mapavacinaspagas']['datafim'] = $data_fim;
//        die('AQUI');
//        die(print_r($_SESSION['Mapavacinaspagas']));
        die(saidaJson($_SESSION['Mapavacinaspagas']));
    } else if ($_POST['tipo'] == 4) {

         /*
	 * Vacinas Realizadas
	 * 
         * loop para variar os tipos de material onde:
         *      1 - vacinas
         *      2 - imunoterapias
         *      3 - testes
         */
        for ($k = 0; $k < 3; $k++) {
            $dados = pegaVacinasRealizadas($data_inicio, $data_fim, $k + 1);
//            die(print_r($dados));
            $data['cabecalho'][] = "Plano";
            $data['cabecalho'][] = "Descrição";

            $periodo = DiffDatetoArray($data_inicio, $data_fim);

            foreach ($periodo as $ano_mes) {
                $arr = split('-', $ano_mes);
                $a = $arr[0];
                $m = strtoupper(substr(mostraMes($arr[1]), 0, 3));
                $data['cabecalho'][] = $m . ' / ' . $a;
                $data['cabecalho'][] = "%";
                $data['cabecalho'][] = "QTD";
            }

            $data['cabecalho'][] = "Média";
            $data['cabecalho'][] = "%";
            $data['cabecalho'][] = "QTD";

            $total = pegaVacinasRealizadasTotal($data_inicio, $data_fim, $k + 1);
            $data['dados'] = $dados;
            $ncol = count($dados);
            $nlin = count($dados[0]);

            $totalMedia = 0;
            for ($i = 0; $i < $nlin; $i++) {
                for ($j = 0; $j < $ncol; $j++) {
                    $data['dados'][$ncol][$i]['media'] = bcadd($data['dados'][$j][$i]['valor'], $data['dados'][$ncol][$i]['media'], 2);
                    $data['dados'][$ncol][$i]['qtd'] = bcadd($data['dados'][$j][$i]['qtd'], $data['dados'][$ncol][$i]['qtd'], 2);

                    if ($data['dados'][$j][$i]['valor'] != 0 && $data['dados'][$j][$i]['valor'] != NULL) {
                        $porcentagem = bcdiv(($data['dados'][$j][$i]['valor'] * 100), $total[$j]['valor'], 2);
                        $data['dados'][$j][$i]['porc'] = $porcentagem;
                    } else {
                        $data['dados'][$j][$i]['porc'] = 0;
                    }

                    $data['totalgeral'][$j]['valor'] = bcadd($data['dados'][$j][$i]['valor'], $data['totalgeral'][$j]['valor'], 2);
                    $data['totalgeral'][$j]['qtd'] = bcadd($data['dados'][$j][$i]['qtd'], $data['totalgeral'][$j]['qtd'], 2);
                    $data['totalgeral'][$j]['porc'] = bcadd($data['dados'][$j][$i]['porc'], $data['totalgeral'][$j]['porc'], 2);
                }
                $data['dados'][$ncol][$i]['media'] = bcdiv($data['dados'][$ncol][$i]['media'], $ncol, 2);
                $totalMedia = bcadd($data['dados'][$ncol][$i]['media'], $totalMedia, 2);

                $data['totalgeral'][$ncol]['valor'] = bcadd($totalMedia, $data['totalgeral'][$ncol]['media'], 2);
                $data['totalgeral'][$ncol]['qtd'] = bcadd($data['dados'][$ncol][$i]['qtd'], $data['totalgeral'][$ncol]['qtd'], 2);
            }

            for ($i = 0; $i < $nlin; $i++) {
                $data['dados'][$ncol][$i]['porc'] = bcdiv(($data['dados'][$ncol][$i]['media'] * 100), $totalMedia, 2);
                $data['totalgeral'][$ncol]['porc'] = bcadd($data['dados'][$ncol][$i]['porc'], $data['totalgeral'][$ncol]['porc'], 2);
            }



            $_SESSION['Mapavacinasrealizadas'][$k] = $data;
            $_SESSION['Mapavacinasrealizadas']['cabecalho'] = $data['cabecalho'];
            unset($data);
        }
        unset($totalGeral);        
        for ($k = 0; $k < 3; $k++) {
            $data = $_SESSION['Mapavacinasrealizadas'][$k];
            foreach ($data['totalgeral'] as $key => $valor) {
                $totalGeral[$key] = bcadd($valor['valor'], $totalGeral[$key], 2);
            }
        }

        $_SESSION['Mapavacinasrealizadas']['totalgeral2'] = $totalGeral;

        $_SESSION['Mapavacinasrealizadas']['datainicio'] = $data_inicio;
        $_SESSION['Mapavacinasrealizadas']['datafim'] = $data_fim;

        die(saidaJson($_SESSION['Mapavacinasrealizadas']));
    }

    echo saidaJson($data);
} else {
    include('view/financeiro/mapasform.phtml');
}
