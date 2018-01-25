<?php

$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "baixa-duplicata_busca") {

	$arr['fornecedores'] = buscaNomeFornecedores();
	$arr['empresas'] = buscaNomeEmpresas();
	$arr['tipo_doc'] = buscaNomeTipoDoc();
	$arr['bancos'] = buscaNomeBancoAtivo();
	$arr['contas'] = buscaNomeContasCorrente();
	$arr['tipos'] = buscaNomeTipoOperacao('S');

	$arr['fornecedores'][] = "TODOS";
	$arr['empresas'][] = "TODOS";
	$arr['tipo_doc'][] = "TODOS";

	echo saidaJson($arr);

} else if($tipoForm == "busca_por_codigo") {

	$flag = _INPUT('flag','string','post');
	$cod = _INPUT('cod','int','post');

	if($flag == 'fornecedor') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeFornecedorPorCodigo($cod);
		}
	} else if ($flag == 'tipo_operacao') {
		$nome = buscaNomeTiposOperacaoPorCodigo($cod,'S');
	} else if ($flag == 'empresa') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeEmpresaPorCodigo($cod);
		}
	} else if ($flag == 'tipoDoc') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeTipoDocPorCodigo($cod);
		}
	}


	echo saidaJson($nome);

} else if($tipoForm == "baixa_lista_duplicatas") {

	$nomeFornecedor = _INPUT('fornecedor','string','post');
	$nomeEmpresa = _INPUT('empresa','string','post');
	$nomeTipoDoc = _INPUT('tipoDoc','string','post');

	if($nomeFornecedor == "TODOS") {
		$fornecedorId = '00';
	} else {
		$fornecedorId = buscaIdFornecedorPorNome($nomeFornecedor);
	}

	if($nomeEmpresa == "TODOS") {
		$empresaId = '00';
	} else {
		$empresaId = buscaIdEmpresaPorNome($nomeEmpresa);
	}

	if($nomeTipoDoc == "TODOS") {
		$tipoDocId = '00';
	} else {
		$tipoDocId = buscaIdTipoDocPorNome($nomeTipoDoc);
	}

	$moedaId = _INPUT('moeda','string','post');
	$statusId = _INPUT('status','string','post');
	$selecionado = _INPUT('selecionado','string','post');
	$data_inicio = converteData(_INPUT('data_inicio','string','post'));
	$data_fim = converteData(_INPUT('data_fim','string','post'));
	$ordenado = _INPUT('ordenado','string','post');

	$listaDuplicatas = buscaDuplicatasBaixaPorPeriodo($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);
	$listaDuplicatasSubtotal = buscaDuplicatasBaixaPorPeriodoSubTotal($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);

	$lista['lista'] = $listaDuplicatas;
	$lista['subtotal'] = $listaDuplicatasSubtotal;
	
	unset($cabecalho);
	$cabecalho['fornecedor'] = $nomeFornecedor;
	$cabecalho['empresa'] = $nomeEmpresa;
	$cabecalho['tipodoc'] = $nomeTipoDoc;
	$cabecalho['data_inicio'] = $data_inicio;
        $cabecalho['data_fim'] = $data_fim;
	$cabecalho['selecionado'] = $selecionado;
	$cabecalho['status'] = $statusId;
	$cabecalho['moeda'] = $moedaId;
	$cabecalho['ordenado'] = $ordenado;
        
	$_SESSION['Mapabaixaduplicatas']['subtotal'] = $listaDuplicatasSubtotal;
	$_SESSION['Mapabaixaduplicatas']['lista'] = $listaDuplicatas;
	$_SESSION['Mapabaixaduplicatas']['cabecalho'] = $cabecalho;
        
	echo saidaJson($lista);
} else if($tipoForm == "busca_baixa_duplicata_por_id") {

	$flag = _INPUT('flag','string','post');
	$id = _INPUT('id','string','post');

	if($flag == 'estorno') {
		$parc = buscaParcelasEstornoPorId($id);
		$parcela = $parc;
		$parcela['data_baixa'] = converteData($parc['data_baixa']);
	} else {
		$parc = buscaParcelasPorId($id);
		$parcela = $parc;
	}

	$parcela['data_vencimento'] = converteData($parc['data_vencimento']);
	$parcela['data_emissao'] = converteData($parc['data_emissao']);
	$parcela['id'] = $id;

	echo saidaJson($parcela);

} else if($tipoForm == "baixa_duplicata_parcela") {

	$idParcela = _INPUT('id','string','post');

	$conta_corrente = _INPUT('conta_corrente','string','post');

	$dadosParcela['conta_corrente_id'] = buscaIdContaCorrentePorNome($conta_corrente);
	$dadosParcela['data_baixa'] = converteData(_INPUT('data_baixa','string','post'));
	$dadosParcela['numero_cheque'] = _INPUT('cheque','string','post');
	$dadosParcela['historico'] = _INPUT('historico','string','post');
	$dadosParcela['status_id'] = buscaIdStatusPorNome("Baixado");
	$dadosParcela['usuario_baixou_id'] = $_SESSION['usuario']['id'];

	if(updateDuplicataParcelaStatusBaixa($dadosParcela, $idParcela)) {

		$idDuplicata = buscaDuplicataPorParcela($idParcela);
		$qtd = buscaQtdParcelasAberto($idDuplicata);

		if($qtd > 0) {
			$dados['status_id'] = statusIdPorNome("Baixa Parcial", "duplicata");
		} else if($qtd == 0) {
			$dados['status_id'] = statusIdPorNome("Baixado", "duplicata");
		}

		atualizaStatusDuplicata($dados,$idDuplicata);
		echo saidaJson("mensagem de ok");
	} else {
		echo saidaJson("Erro ao Inserir Registro");
	}

} else if($tipoForm == "estorno_parcela") {

	$idParcela = _INPUT('id','string','post');

	$idStatus = buscaIdStatusPorNome("Em Aberto");

	if(updateDuplicataParcelaStatusEstorno($idStatus,$idParcela)) {

		$idDuplicata = buscaDuplicataPorParcela($idParcela);
		$qtd = buscaQtdParcelasAberto($idDuplicata);
		$qtdParc = buscaQtdParcelas($idDuplicata);

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

		atualizaStatusDuplicata($dados,$idDuplicata);
		echo saidaJson(0);
	} else {
		echo saidaJson(1);
	}
} else if($tipoForm == "comprovante_parcela") {

	$idParcela = _INPUT('id','string','post');
	$duplicata = buscaDuplicataComprovante($idParcela);

	$duplicata['valor_extenso'] = valorPorExtenso($duplicata['valor']);
	$duplicata['valor'] = formata_dinheiro($duplicata['valor']);

	$_SESSION['comprovante'] = $duplicata;
} else {
include('view/financeiro/baixacp.phtml');
}

?>
