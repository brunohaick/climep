<?php

$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "baixa_fatura_busca") {

	$arr['empresas'] = buscaNomeEmpresas();
	$arr['bancos'] = buscaNomeBancoAtivo();
	$arr['clientes'] = buscaNomeClientesCnpj();
	$arr['contas'] = buscaNomeContasCorrente();
	$arr['tipo_operacao'] = buscaNomeTipoOperacao('E');

	$arr['empresas'][] = "TODOS";
	$arr['bancos'][] = "TODOS";
	$arr['clientes'][] = "TODOS";

	echo saidaJson($arr);

} else if($tipoForm == "busca_por_codigo") {

	$flag = _INPUT('flag','string','post');
	$cod = _INPUT('cod','int','post');

	if($flag == 'cliente') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeClientePorCodigo($cod);
		}
	} else if ($flag == 'empresa') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeEmpresaPorCodigo($cod);
		}
	} else if ($flag == 'banco') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeBancoPorCodigo($cod);
		}
	} else if ($flag == 'tipo_operacao') {
		$nome = buscaNomeTipoOperacaoPorCodigo($cod);
	}

	echo saidaJson($nome);

} else if($tipoForm == "baixacr_lista_faturas") {
	$nomeCliente = _INPUT('cliente','string','post');
	$nomeEmpresa = _INPUT('empresa','string','post');
	$nomeBanco = _INPUT('banco','string','post');
	if($nomeCliente == "TODOS") {
		$clienteId = '00';
	} else {
		$clienteId = buscaIdClienteCnpjPorNome($nomeCliente);
	}

	if($nomeEmpresa == "TODOS") {
		$empresaId = '00';
	} else {
		$empresaId = buscaIdEmpresaPorNome($nomeEmpresa);
	}

	if($nomeBanco == "TODOS") {
		$bancoId = '00';
	} else {
		$bancoId = buscaIdBancoPorNome($nomeBanco);
	}
	$moedaId = _INPUT('moeda','string','post');
	$statusId = _INPUT('status','string','post');
	$selecionado = _INPUT('selecionado','string','post');
	$data_inicio = converteData(_INPUT('data_inicio','string','post'));
	$data_fim = converteData(_INPUT('data_fim','string','post'));
	$ordenado = _INPUT('ordenado','string','post');
	$cartao = _INPUT('cartao','string','post');
	$numero_fatura = isset($_POST['fatura']) ? $_POST['fatura'] : '0';
    unset($cabecalho);
	$cabecalho['cliente'] = $nomeCliente;
	$cabecalho['empresa'] = $nomeEmpresa;	
	$cabecalho['data_inicio'] = $data_inicio;
        $cabecalho['data_fim'] = $data_fim;
	$cabecalho['selecionado'] = $selecionado;
	$cabecalho['status'] = $statusId;
	$cabecalho['moeda'] = $moedaId;
	$cabecalho['ordenado'] = $ordenado;

	$listaFaturasParcelas['lista'] = buscaDuplicatasBaixaCRPorPeriodo($clienteId, $empresaId, $bancoId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado,$cartao,$numero_fatura);
	$listaFaturasParcelas['subtotal'] = buscaDuplicatasBaixaCRPorPeriodoTotal($clienteId, $empresaId, $bancoId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado,$cartao);
        $listaFaturasParcelas['cabecalho'] = $cabecalho;

	$_SESSION['lista_faturas_parcelas'] = $listaFaturasParcelas;
	echo saidaJson($listaFaturasParcelas);

} else if($tipoForm == "busca_baixacr_por_id") {

	$flag = _INPUT('flag','string','post');
	$id = _INPUT('id','string','post');

	if($flag == 'estorno') {
		$parc = buscaParcelasFaturaEstornoPorId($id);
		$parcela = $parc;
		$parcela['data_baixa'] = converteData($parc['data_baixa']);
	} else {
		$parc = buscaParcelasFaturaPorId($id);
		$parcela = $parc;
	}

	$parcela['data_vencimento'] = converteData($parc['data_vencimento']);
	$parcela['data_emissao'] = converteData($parc['data_emissao']);
	$parcela['id'] = $id;

	echo saidaJson($parcela);

} else if($tipoForm == "baixacr_parcela") {

	$idParcela = _INPUT('id','string','post');
	$conta_corrente = _INPUT('conta_corrente','string','post');
	$tipo_operacao = _INPUT('tipo_operacao','string','post');

	$dadosParcela['conta_corrente_id'] = buscaIdContaCorrentePorNome($conta_corrente);
	$dadosParcela['tipo_operacao_id'] = buscaIdTipoOperacaoPorNome($tipo_operacao);
	$dadosParcela['data_baixa'] = converteData(_INPUT('data_baixa','string','post'));
	$dadosParcela['status_id'] = buscaIdStatusPorNome("Baixado");
	$dadosParcela['usuario_baixou_id'] = $_SESSION['usuario']['id'];

	if(updateFaturaParcelaStatusBaixa($dadosParcela,$idParcela)) {

		$idFatura = buscaFaturaPorParcela($idParcela);
		$qtd = buscaQtdParcelasFaturaAberto($idFatura);

		if($qtd > 0) {
			$dados['status_id'] = statusIdPorNome("Baixa Parcial", "duplicata");
		} else if($qtd == 0) {
			$dados['status_id'] = statusIdPorNome("Baixado", "duplicata");
		}

		atualizaStatusFatura($dados, $idFatura);
		echo saidaJson("mensagem de ok");
	}

} else if($tipoForm == "estorno_parcela") {

	$idParcela = _INPUT('id','string','post');

	$idStatus = buscaIdStatusPorNome("Em Aberto");

	if(updateFaturaParcelaStatusEstorno($idStatus,$idParcela)) {

		$idFatura = buscaFaturaPorParcela($idParcela);
		$qtd = buscaQtdParcelasFaturaAberto($idFatura);
		$qtdParc = buscaQtdParcelasFatura($idFatura);

		/*
		 * A quantidade de parcelas aberta pode ser igual ao numero de parcelas 
		 * nesse caso o fatura está baixada, se numero de parcelas em aberto for 
		 * igual ao numero total de parcelas volta-se para o estado em aberto e 
		 * se as parcelas em aberto forem entre um e outro, fica parcialmente 
		 * baixada.
		 * */
		if($qtd == $qtdParc) {
			$dados['status_id'] = statusIdPorNome("Em Aberto", "duplicata");
		} else if($qtd > 0) { 
			$dados['status_id'] = statusIdPorNome("Baixa Parcial", "duplicata");
		} else if($qtd == 0) {
			$dados['status_id'] = statusIdPorNome("Baixado", "duplicata");
		}

		atualizaStatusFatura($dados,$idFatura);
		echo saidaJson("mensagem de ok");
	}
} else {
	include('view/financeiro/baixacr.phtml');
}
?>
