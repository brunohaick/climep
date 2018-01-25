<?php

/* * *
 * @author Luiz Cotinhas
 * @description Funç�o criada para procurar em array's de tamanhos variados, como matrizes e multidimensionais.
 */

function recursive_array_search($needle, $haystack, $colum) {
	foreach ($haystack as $key) {
		if ($needle === $key[$colum]) {
			return true;
		}
	}
	return false;
}

if (isset($_POST['flag']) && $_POST['flag'] == "preencheServicos") {
	$id = $_POST['id'];

	$servicos = buscaServicosFilaCaixa($id);
	for ($i = 0; $i < count($servicos); $i++) {
		$x1 = diferenca_data($servicos[$i]['cliente_nascimento'], date("Y-m-d"));
		$servicos[$i]['cliente_nascimento'] = $x1['ano'] . "a" . $x1['mes'] . "m";
		$x2 = diferenca_data($servicos[$i]['responsavel_nascimento'], date("Y-m-d"));
		$servicos[$i]['responsavel_nascimento'] = $x2['ano'] . "a" . $x2['mes'] . "m";

		$servicos[$i]['valor'] = "R$" . String::formatCurrency($servicos[$i]['valor']);
		$servicos[$i]['material_preco_vista'] = "R$" . String::formatCurrency($servicos[$i]['material_preco_vista']);
		$servicos[$i]['material_preco_cartao'] = "R$" . String::formatCurrency($servicos[$i]['material_preco_cartao']);
		$servicos[$i]['material_preco_dose'] = "R$" . String::formatCurrency($servicos[$i]['material_preco_dose']);
		$servicos[$i]['controle_data'] = Time::change($servicos[$i]['controle_data'], 'PT');
		$servicos[$i]['data_guia'] = Time::change($servicos[$i]['"data_guia'], 'PT');
	}

	$formas_pagamento = listar('forma_pagamento', '*');

	$result['servicos'] = $servicos;
	$result['formas_pagamento'] = $formas_pagamento;

	echo saidaJson($result);
//	print_r($servicos);
	exit;
} else if (isset($_POST['flag']) && $_POST['flag'] == "buscaPreco") {

	$idReferencia = $_POST['idReferencia'];
	$categoriaVenda = $_POST['categoriaVenda'];

	if ($_POST['tipo'] == 1) { # caso a busca do preço seja a vista
		if ($categoriaVenda == "imunoterapia" || $categoriaVenda == "vacina") {
			$preco = buscaPrecoVacImu($idReferencia, 'avista');
		}
	} else if ($_POST['tipo'] == 2) { # caso a busca do preço seja a cartao
		if ($categoriaVenda == "imunoterapia" || $categoriaVenda == "vacina") {
			$preco = buscaPrecoVacImu($idReferencia, 'aprazo');
		}
	}

//	print_r($preco);
	$preco = number_format($preco, 2, ',', ' ');
	echo saidaJson($preco);
	exit;
} else if (isset($_POST['flag']) && $_POST['flag'] == "confirmaDadosPagamento") {

	die(print_r(saidaJson($_POST)));

	$idFilaEspera = $_POST['idFilaEspera'];
	if (!empty($_POST['cartao_1'][0]) && !empty($_POST['cartao_1'][1]) && !empty($_POST['cartao_1'][2])) {
		$cartao_1['fila_espera_caixa_id'] = $idFilaEspera;
		$cartao_1['cartao_bandeiras_id'] = $_POST['cartao_1'][0];
		$cartao_1['valor'] = trataValorMoeda($_POST['cartao_1'][1]);
		$cartao_1['num_parcelas'] = $_POST['cartao_1'][2];
		$cartao_1['cod_autorizacao'] = $_POST['cartao_1'][3];
//		print_r($cartao_1);
		inserePagamentoCartao($cartao_1);
	}
	if (!empty($_POST['cartao_2'][0]) && !empty($_POST['cartao_2'][1]) && !empty($_POST['cartao_2'][2])) {
		$cartao_2['fila_espera_caixa_id'] = $idFilaEspera;
		$cartao_2['cartao_bandeiras_id'] = $_POST['cartao_2'][0];
		$cartao_2['valor'] = trataValorMoeda($_POST['cartao_2'][1]);
		$cartao_2['num_parcelas'] = $_POST['cartao_2'][2];
		$cartao_2['cod_autorizacao'] = $_POST['cartao_2'][3];
//		print_r($cartao_2);
		inserePagamentoCartao($cartao_2);
	}
	if (!empty($_POST['cartao_3'][0]) && !empty($_POST['cartao_3'][1]) && !empty($_POST['cartao_3'][2])) {
		$cartao_3['fila_espera_caixa_id'] = $idFilaEspera;
		$cartao_3['cartao_bandeiras_id'] = $_POST['cartao_3'][0];
		$cartao_3['valor'] = trataValorMoeda($_POST['cartao_3'][1]);
		$cartao_3['num_parcelas'] = $_POST['cartao_3'][2];
		$cartao_3['cod_autorizacao'] = $_POST['cartao_3'][3];
//		print_r($cartao_3);
		inserePagamentoCartao($cartao_3);
	}
	$i = 1;
	$dados['fila_espera_caixa_id'] = $idFilaEspera;
	foreach ($_POST['forma_pagamento'] as $formaPagamento) {
		if ($formaPagamento == 0) {
			$i++;
			continue;
		}
		$dados['forma_pagamento_id'] = $i;
		$dados['valor'] = trataValorMoeda($formaPagamento);
		insereFormaPagamento($dados);
		$i++;
	}
	$filaCaixa['finalizado'] = 1;
//	$filaCaixa['convenio_id'] = $_POST['convenio'];
	$filaCaixa['operador_id'] = $_SESSION['usuario']['id'];
	atualizaFilaCaixa($filaCaixa, $idFilaEspera);
} else if (isset($_POST['flag']) && $_POST['flag'] == "imprimeResumoCaixa"){
	$_SESSION['resumoCaixa']['data_inicio'] = $_POST['data_inicio'];
	$_SESSION['resumoCaixa']['data_fim'] = $_POST['data_fim'];
	$dataInicio = converteData($_POST['data_inicio']);
	$dataFim = converteData($_POST['data_fim']);
	$guias = carregaResumoCaixa($dataInicio, $dataFim);
	$guiasOrganizados = NULL;

	/*	 * Agrupamento dos forma_pagamentos por numero_controle 
	 * @author Luiz Cortinhas
	 */
	foreach ($guias as $key) {
		$key['nome_forma_pagamento'] = array();

		foreach (carregaResumoBandeirasPorGuiaId($key['id']) as $arr) {//Nivel 1
			$key['nome_forma_pagamento'][$arr['nome_forma_pagamento']]['valor'] = $arr['valor'];
			$key['nome_forma_pagamento'][$arr['nome_forma_pagamento']]['parcela'] = $arr['parcelas'];
		}
		$guiasOrganizados[] = $key;
	}
	//fim do agrupamento
	$i = 0;
	foreach ($guiasOrganizados as $guia) {
		$dados[$i]['matricula'] = $guia['matricula'];
		$dados[$i]['data'] = converteData($guia['data']);
		$dados[$i]['responsavel'] = $guia['nomeTitular'];
		$dados[$i]['operador'] = $guia['nomeOperador'];
		$dados[$i]['nota_fiscal'] = $guia['numero_nfse'];
		$dados[$i]['controle'] = $guia['numero_controle'];
		$dados[$i]['valor_total'] = 0;
		$dados[$i]['desconto'] = $guia['desconto'];
		$dados[$i]['categoria'] = $guia['categoria'];
		$dados[$i]['forma_pagamento'] = $guia['forma_pagamento'];
		$valor_total_tmp = 0;
		$servicos = carregaServicosResumoCaixaPorGuia($guia['id']);

		$dados[$i]['servicos'] = $servicos;
		$dados[$i]['nome_forma_pagamento'] = $guia['nome_forma_pagamento'];
		/*
		 * Calculando o Total da Guia
		 */
		foreach ($servicos as $servico) {
			$valor_total_tmp += $servico['valor'];
		}
		$dados[$i]['valor_total'] = number_format($valor_total_tmp, 2, ',', '.');
		$i++;
	}
//    print_r($dados);
	$_SESSION['resumoCaixa']['dados'] = $dados;
	$_SESSION['resumoCaixa']['data_inicio'] = $_POST['data_inicio'];
	$_SESSION['resumoCaixa']['data_fim'] = $_POST['data_fim'];

//	echo saidaJson($dados);
} else if (isset($_POST['flag']) && $_POST['flag'] == "carregaResumoCaixa") {
	$_SESSION['resumoCaixa']['data_inicio'] = $_POST['data_inicio'];
	$_SESSION['resumoCaixa']['data_fim'] = $_POST['data_fim'];
	$dataInicio = converteData($_POST['data_inicio']);
	$dataFim = converteData($_POST['data_fim']);
	$array = carregaResumoCaixa($dataInicio, $dataFim);
	/*	 * Agrupamento dos forma_pagamentos por numero_controle 
	 * @author Luiz Cortinhas
	 */
	$guiasOrganizados;
	foreach ($array as $key) {
		$key['nome_forma_pagamento'] = array();
		foreach (carregaResumoBandeirasPorGuiaId($key['id']) as $arr) {//Nivel 1
			$key['nome_forma_pagamento'][] = $arr['nome_forma_pagamento'];
		}
		$guiasOrganizados[] = $key;
	}
	//fim do agrupamento
	$array = $guiasOrganizados;

//    die(print_r($array));
	//fim do agrupamento
	$i = 0;
	foreach ($array as $array2) {
		$dados[$i]['matricula'] = $array2['matricula'];
		$dados[$i]['data'] = converteData($array2['data']);
		$dados[$i]['responsavel'] = $array2['nomeTitular'];
		$dados[$i]['operador'] = $array2['nomeOperador'];
		$dados[$i]['nota_fiscal'] = $array2['numero_nfse'];
		$dados[$i]['controle'] = $array2['numero_controle'];
		$dados[$i]['valor_total'] = 0;
		$valor_total_tmp = 0;
		$arrayzao = carregaResumoCaixa_2($array2['id']);
		$dados[$i]['dinheiro'] = number_format(0, 2, ',', '.');
		$dados[$i]['cheque'] = number_format(0, 2, ',', '.');
		$dados[$i]['outros'] = number_format(0, 2, ',', '.');
		$dados[$i]['cartao'] = number_format(0, 2, ',', '.');
		$dados[$i]['debito'] = number_format(0, 2, ',', '.');
		$dados[$i]['convenio'] = number_format(0, 2, ',', '.');
		$dados[$i]['cortesia'] = number_format(0, 2, ',', '.');
		$dados[$i]['pendente'] = number_format(0, 2, ',', '.');
		$dados[$i]['desconto'] = $array2['desconto'];
//		print_r($array2);
		foreach ($arrayzao as $arrayzinho) {
			$valor_total_tmp += $arrayzinho['valor'];
			//Inicializaç�o das Variaveis do array monstruoso abaixo...ta na minha mira array!
			//by Luiz Cortinhas
			switch ($arrayzinho['forma_pagamento_id']) {
				case '1' :
					$dados[$i]['dinheiro'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '2' :
					$dados[$i]['cheque'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '3' :
					$dados[$i]['outros'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '4' :
					$dados[$i]['cheque'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '5' :
					$dados[$i]['cartao'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '6' :
					$dados[$i]['debito'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '7' :
					$dados[$i]['convenio'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '8' :
					$dados[$i]['cortesia'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '9' :
					$dados[$i]['pendente'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
				case '10' :
					$dados[$i]['desconto'] = number_format($arrayzinho['valor'], 2, ',', '.');
					break;
			}
			$dados[$i]['desconto'] = number_format($array2['desconto'], 2, ',', '.');
		}
		$dados[$i]['valor_total'] = number_format($valor_total_tmp, 2, ',', '.');
		$i++;
	}
	die(saidaJson(arrayUtf8Enconde($dados)));
//    $_SESSION['PDF']['resumoCaixa'] = $dados;
} else if (isset($_POST['flag']) && $_POST['flag'] == "buscaNotasEmitidas") {
	$notas = buscaNotasEnviadas(); //listar('rps','*','enviado=0');
	die(saidaJson($notas));
} else if (isset($_POST['flag']) && $_POST['flag'] == "buscaNotasNaoEmitidas") {
	$notas = buscaNotasNaoEnviadas(); //listar('rps','*','enviado=0');
	//print_r($notas);
	die(saidaJson($notas));
} else if (isset($_POST['flag']) && $_POST['flag'] == "buscaNotasNaoEmitidasPorIdRps") {
	$idrps = $_POST['idrps'];
	$nota = buscaNotasporId($idrps);
	$nota['dataEmissaoRPS'] = converteData($nota['dataEmissaoRPS']);
	die(saidaJson($nota));
} else if (isset($_POST['flag']) && $_POST['flag'] == "imprimeNotaFiscal") {
	$idrps = $_POST['idrps'];
	$nota = buscaNotasporId($idrps);
	$nota['dataEmissaoRPS'] = converteData($nota['dataEmissaoRPS']);
	$nota['valorISS'] = ($nota['AliquotaAtividade'] / 100) * $nota['ValorTotalServicos'];

	$_SESSION['notafiscal']['rps'] = $nota;
//	$_SESSION['nfse']['servicos'] = pegaServicosPorRPS($idrps);
	die();
} else if (isset($_POST['flag']) && $_POST['flag'] == "editaDadosNotaFiscal") {
	$idrps = $_POST['idrps'];
	if (isset($_POST['processar_nota'])) {
		$dadosRps['AliquotaAtividade'] = $_POST['aliquota'];
		$dadosRps['TipoRecolhimento'] = $_POST['tipo_recolhimento'];
		$dadosRps['ValorPIS'] = 0;
		$dadosRps['ValorCOFINS'] = 0;
		$dadosRps['ValorINSS'] = 0;
		$dadosRps['ValorIR'] = 0;
		$dadosRps['ValorCSLL'] = 0;
		$dadosRps['AliquotaPIS'] = 0;
		$dadosRps['AliquotaCOFINS'] = 0;
		$dadosRps['AliquotaINSS'] = 0;
		$dadosRps['AliquotaIR'] = 0;
		$dadosRps['AliquotaCSLL'] = 0;
		$dadosRps['numero_nfse'] = '123011'; //@TODO pegar numero que vem do webservice da prefeitura.
		$dadosRps['enviado'] = '1';
	} else if (isset($_POST['excluir'])) {
		$dadosRps['enviado'] = '0';
	} else {
		$dadosRps['ValorTotalServicos'] = $_POST['valor'];
		$dadosRps['dataEmissaoRPS'] = converteData($_POST['dataEmissaoRPS']);
		$dadosRps['CPFCNPJTomador'] = $_POST['cpf_cnpj'];
		$dadosRps['RazaoSocialTomador'] = $_POST['razao_social'];
		$dadosRps['TipoLogradouroTomador'] = $_POST['tipo_log'];
		$dadosRps['LogradouroTomador'] = $_POST['logradouro'];
		$dadosRps['NumeroEnderecoTomador'] = $_POST['numero_casa'];
		$dadosRps['BairroTomador'] = $_POST['bairro'];
		$dadosRps['CidadeTomador'] = '0000427';
		$dadosRps['CEPTomador'] = $_POST['cep'];
		$dadosRps['EmailTomador'] = $_POST['email'];
		$dadosRps['CidadeTomadorDescricao'] = $_POST['cidade'];
		$dadosRps['DDDTomador'] = $_POST['ddd'];
		$dadosRps['TelefoneTomador'] = $_POST['telefone'];
		$dadosRps['EstadoTomador'] = $_POST['estado'];
	}

	if (atualizarRps($idrps, $dadosRps)) {
		die(saidaJson(true));
	}
} else if (isset($_POST['flag']) && $_POST['flag'] == "enviaNFE") {

	/*
	 * Bruno Haick
	 * 
	 * Bloco para pegar mais de uma guia e enviar em lote.
	 * Por hora descontinuado, até testar mais as coisas.
	 */
//	foreach ($_POST['nfe'] as $idNfe) {
//        $nota = buscaDadosNFE($idNfe);
//        $nota['itens'] = buscaItensNFE($idNfe);
//    }
	$idrps = $_POST['idrps'];
	$nota = buscaNotasporId($idrps);
	$nota['itens'] = pegaServicosPorRPS($idrps);
	$nota['dataEmissaoRPS'] = converteData($nota['dataEmissaoRPS']);
	die(saidaJson($nota));
} else if (isset($_POST['flag']) && $_POST['flag'] == "getControles") {
	$dados = pegaFilaControlePendentesdeHoje();
//        die(var_dump($dados));
	saidaJson($dados);
} else if (isset($_POST['flag']) && $_POST['flag'] == "getServicosPorGuiaControle") {
	$guiaid = isset($_POST['controle_id']) ? $_POST['controle_id'] : 0;
	$dados = pegaServicosPorGuiaControle($guiaid);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "getServicos") {
	die(saidaJson(buscarServicosControle($_POST['controle_id'])));
} else if (isset($_POST['flag']) && $_POST['flag'] == "getModulosConfirmados") {
	$idtitular = isset($_POST['titular_id']) ? $_POST['titular_id'] : 0;
	$dados = buscaModulosConfirmados($idtitular);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "imprimeReciboCaixa") {
//	die(print_r(json_decode($_POST['clienteTitular'])));
	$_SESSION['caixa']['recibo']['clienteTitular'] = json_decode($_POST['clienteTitular'], true);
	$_SESSION['caixa']['recibo']['servicos'] = json_decode($_POST['servicos'], true);
	$_SESSION['caixa']['recibo']['total'] = json_decode($_POST['total'], true);
	$_SESSION['caixa']['recibo']['desconto'] = $_POST['desconto'];
} else if (isset($_POST['flag']) && $_POST['flag'] == "getSomaValorModulo") {
	$idModulo = isset($_POST['idModulo']) ? $_POST['idModulo'] : 0;
	$dados = buscaPrecoModulo($idModulo);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "finalizarGuiaControleporId") {
	$id = isset($_POST['controle_id']) ? $_POST['controle_id'] : 0;
	$desconto = isset($_POST['desconto']) ? $_POST['desconto'] : 0;
	$status_id = isset($_POST['status_id']) ? $_POST['status_id'] : 0;
	$dados = finalizaGuiaControleporID($id, $desconto,$status_id);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "getBandeiraIdByNome") {
	$nome = isset($_POST['nome']) ? $_POST['nome'] : 0;
	$nome = trim($nome);

	$dados = pegaBanderiaIdPorNome($nome);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "insereFormaPagamentoporGuia") {
	$guiaid = isset($_POST['guiaid']) ? $_POST['guiaid'] : 0;
	$idFormaPag = isset($_POST['idforma']) ? $_POST['idforma'] : 0;
	$idBandeira = isset($_POST['idbandeira']) ? $_POST['idbandeira'] : 0;
	$valor = isset($_POST['valor']) ? str_replace ( ",", ".", $_POST['valor']) : 0;
	$parcelas = isset($_POST['parcelas']) ? $_POST['parcelas'] : 0;
	$autorizacao = isset($_POST['autorizacao']) ? $_POST['autorizacao'] : 0;
	$desconto = isset($_POST['desconto']) ? $_POST['desconto'] : 0;
	$tmpnome_bandeira = pegaNomeBanderiaporID($idBandeira);
	$nome_bandeira = $tmpnome_bandeira[0]['nome'];
	$tmpplano_contas = buscaPlanoContasPorNome($nome_bandeira);
	$plano_contas = $tmpplano_contas[0];
	$usuario_id = $_SESSION['usuario']['id'];
//	if($idFormaPag !== '19'){ //Filtro para n�o inserir o modelo de pagamento de desconto
		$dados = insereGuiaControleFormaPagamento($guiaid, $idFormaPag, $idBandeira, $valor, $parcelas,$autorizacao);
//	}
//        die("Here");
	if ($autorizacao > 0) {
		$idFatura = insereGuiaControleFatura($usuario_id, $plano_contas['id'], $autorizacao);
		$desconto = $desconto / $parcelas; //Desconto !! Warning !! em testes.
		for ($par = 0; $par < $parcelas; $par++) {
			$valor_parcela = ($valor / $parcelas);
			$periodo = $par + 1;
			$resultado_parcelas = insereGuiaControleFaturaParcelas($idFatura, $usuario_id, $valor_parcela, $periodo, $desconto,$periodo);
		}
	}
//        die("Here");
        die(var_dump($resultado_parcelas));
//	die(json_encode($resultado_parcelas));
} else if (isset($_POST['flag']) && $_POST['flag'] == "insereRPSporGuia") {
	$guiaid = isset($_POST['guiaid']) ? $_POST['guiaid'] : 0;
	$data = date('Y-m-d');
	$nometomador = isset($_POST['nometomador']) ? $_POST['nometomador'] : 0;
	$dddtomador = isset($_POST['dddtomador']) ? $_POST['dddtomador'] : 0;
	$valortotal = isset($_POST['valortotal']) ? $_POST['valortotal'] : 0;
	$telefonetomador = isset($_POST['telefonetomador']) ? $_POST['telefonetomador'] : 0;
	$cpftomador = isset($_POST['cpftomador']) ? $_POST['cpftomador'] : 0;
	$ceptomador = isset($_POST['ceptomador']) ? $_POST['ceptomador'] : 0;
	$emailtomador = isset($_POST['emailtomador']) ? $_POST['emailtomador'] : 0;
	$estadotomador = isset($_POST['estadotomador']) ? $_POST['estadotomador'] : 0;
	$cidadetomador = isset($_POST['cidadetomador']) ? $_POST['cidadetomador'] : 0;
	$bairrotomador = isset($_POST['bairrotomador']) ? $_POST['bairrotomador'] : 0;
	$enderecotomador = isset($_POST['enderecotomador']) ? $_POST['enderecotomador'] : 0;
	$numerologradourotomador = isset($_POST['numerologradourotomador']) ? $_POST['numerologradourotomador'] : 0;
	$dados = insereGuiaControleRPS($guiaid, $valortotal, $cpftomador, $nometomador, $enderecotomador, $numerologradourotomador, $bairrotomador, $ceptomador, $emailtomador);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "baixadoEstoqueporMaterialID") {
	$materialid = isset($_POST['materialid']) ? $_POST['materialid'] : 0;
	$data = isset($_POST['data']) ? $_POST['data'] : 0;
	$quantidade = isset($_POST['quantidade']) ? $_POST['quantidade'] : 0;
	$usuarioid = $_SESSION['usuario']['id'];
	//WARNING !! Este relacionamento est� aparentemente incorreto, no entanto o modulo do Estoque j� foi homologado.
	//@author Luiz Cortinhas
	//WARNING !! Se algo estiver errado, desconfie da linha imediatamente abaixo.
	$lote = buscaLotebyMaterialId($materialid);
	$lote_id = $lote[0]['LoteID']; // Obtenç�o da numeraç�o do LOTElog do terminarl
	if ($lote > 0) {
		$dados = insereGuiaControleBaixaEstoque($materialid, $lote_id, $quantidade, $data, $usuarioid);
	} else {
		$dados = 2;
	}
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "modificaStatusServicoPagoPorID") {
	$servicoid = isset($_POST['servicoid']) ? $_POST['servicoid'] : 0;
	$formaPagamento = isset($_POST['forma_pagamento']) ? $_POST['forma_pagamento'] : 0;
	$statusid = isset($_POST['status']) ? $_POST['status'] : 0;
	//Linha abaixo inserida para referenciar a forma de pagamento para o controle e serviço.
	modificaControleParaFormadePagamento($servicoid,$formaPagamento);
	$dados = modificaStatusServicoParaPago($servicoid,$statusid);
//	die('1');
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "inseriHistoricoPorServico") {
	$servicoid = isset($_POST['servicoid']) ? $_POST['servicoid'] : 0;
	$ismodulo = isset($_POST['ismodulo']) ? $_POST['ismodulo'] : 0;
	$dados = InsereHistoricoPorServico($servicoid);
	die(saidaJson($dados));
} else if (isset($_POST['flag']) && $_POST['flag'] == "inserirServicoParaGuia") {
	//Função criada por Luiz Cortinhas
	//Nescessaria para o caixar poder inserir os serviços de visita 1 e visita 2
	//Primeiro deve-se criar um serviço, depois um controle.
	$guiaid = isset($_POST['guiaid']) ? $_POST['guiaid'] : 0;
	$controleid = isset($_POST['controleid']) ? $_POST['controleid'] : 0;
	$materialid = isset($_POST['materialid']) ? $_POST['materialid'] : 0;
	$clienteid = isset($_POST['clienteid']) ? $_POST['clienteid'] : 0;
	$preco = isset($_POST['preco']) ? $_POST['preco'] : 0;
	$servicoId = insereServicoDetalhado($guiaid,$controleid,$materialid,$clienteid,$preco);
	$numero_controle = buscaDadosControle($guiaid)[0]['numero_controle'];
	insereControleDetalhado($clienteid,$materialid,$guiaid,$servicoId,$numero_controle);
	die(saidaJson($servicoId));
}else if (isset($_POST['flag']) && $_POST['flag'] == "removerServicoParaGuia") {
	//Função criada por Luiz Cortinhas
	//Primeiro deve-se remover um controle, depois um servico.
	$guiaid = isset($_POST['guiaid']) ? $_POST['guiaid'] : 0;
	$controleid = isset($_POST['controleid']) ? $_POST['controleid'] : 0;
	$servicoId = isset($_POST['servicoid']) ? $_POST['servicoid'] : 0;
	$materialid = isset($_POST['materialid']) ? $_POST['materialid'] : 0;
	removerControleDetalhado($controleid,$materialid); 
	removerServicoDetalhado($servicoId);
	die(saidaJson('1'));
}else {
	include('view/caixa/caixa.phtml');
}
