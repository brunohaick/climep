<?php
$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "busca_data_parcelas") {

	$data = _INPUT('data_venc','string','post');
	$num_parcelas = _INPUT('num_parcelas','string','post');
	$intervalo = _INPUT('intervalo_parcelas','string','post');
	$tipo = 'dia';
	$qtd = 0;

	$arrData[] = somarData($data, $qtd, $tipo);

	for($i = 1; $i < $num_parcelas; $i++) {

		$qtd = $i * $intervalo;
		$arrData[] = somarData($data, $qtd, $tipo);
	}

	echo saidaJson($arrData);

} else if($tipoForm == "edita_campo") {

	$flag = _INPUT('flag','string','post');

	if($flag == "data") {
		$dados['data_vencimento'] = converteData(_INPUT('texto','string','post'));
	} else if($flag == "valor") {
		$dados['valor'] = trataValorMoeda(_INPUT('texto','string','post'));
	}

	$id = _INPUT('idParcela','int','post');

	atualizaDataParcelaDuplicata($dados,$id);

} else if($tipoForm == "busca_parcelas_modal_consulta") {

	$id = _INPUT('id','string','post');

	$parcelas = buscaParcelasPorDuplicata($id);

	for($i = 0; $i < count($parcelas); $i++) {

		$parcelas[$i]['data_vencimento'] = converteData($parcelas[$i]['data_vencimento']);

		if($parcelas[$i]['nome_status'] == "Em Aberto") {
			$parcelas[$i]['nome_status'] = "A";
		} else if($parcelas[$i]['nome_status'] == "Baixado") {
			$parcelas[$i]['nome_status'] = "B";
		} else if($parcelas[$i]['nome_status'] == "Baixa Parcial") {
			$parcelas[$i]['nome_status'] = "BP";
		}
	}

	echo saidaJson($parcelas);

} else if($tipoForm == "consulta_lista_duplicatas") {

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

	$statusId = _INPUT('status','string','post');
	$selecionado = _INPUT('selecionado','string','post');
	$data_inicio = converteData(_INPUT('data_inicio','string','post'));
	$data_fim = converteData(_INPUT('data_fim','string','post'));
	$ordenado = _INPUT('ordenado','string','post');

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
        
	$listaDuplicatas['cabecalho'] = $cabecalho;
	$listaDuplicatas['lista'] = buscaDuplicatasPorPeriodo($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);
	$listaDuplicatas['subtotal'] = buscaDuplicatasPorPeriodoSubtotalPorGrupo($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);

	$_SESSION['listaDuplicatas'] = $listaDuplicatas;

	echo saidaJson($listaDuplicatas);

} else if($tipoForm == "insere_duplicatas") {
	
	$numero_dup = _INPUT('numero_dup','string','post');
	$numero_nota = _INPUT('numero_nota','string','post');
	$fornecedor = _INPUT('fornecedor','string','post');
	$empresa = _INPUT('empresa','string','post');
	$tipoDoc = _INPUT('tipoDoc','string','post');
	$banco = _INPUT('banco','string','post');
	$moeda = _INPUT('moeda','string','post');
	$plano_contas = _INPUT('plano_contas','string','post');
	$cod_barras = _INPUT('cod_barras','string','post');
	$num_parcelas = _INPUT('num_parcelas','string','post');
	$data_emissao = converteData(_INPUT('data_emissao','string','post'));
	$data_vencimento = converteData(_INPUT('data_vencimento','string','post'));
	$intervalo_parcelas = _INPUT('intervalo_parcelas','string','post');
	$obs = _INPUT('obs','string','post');
	$parcelas = _INPUT('parcelas','string','post');

	$dadosDuplicata['fornecedores_id'] = buscaIdFornecedorPorNome($fornecedor);
	$dadosDuplicata['empresa_id'] = buscaIdEmpresaPorNome($empresa);
	$dadosDuplicata['tipo_doc_id'] = buscaIdTipoDocPorNome($tipoDoc);
	$dadosDuplicata['banco_id'] = buscaIdBancoPorNome($banco);
	$dadosDuplicata['moeda_id'] = buscaIdMoedaPorNome($moeda);
	$dadosDuplicata['plano_contas_id'] = buscaIdPlanoContasPorNome($plano_contas);
	$dadosDuplicata['data_lancamento'] = date('Y-m-d');
	$dadosDuplicata['data_emissao'] = $data_emissao;
	$dadosDuplicata['observacao'] = $obs;
	$dadosDuplicata['codigo_barras'] = $cod_barras;
	$dadosDuplicata['status_id'] = buscaIdStatusPorNome("Em Aberto");
	$dadosDuplicata['usuario_id'] = $_SESSION['usuario']['id'];
	$dadosDuplicata['nota_fiscal'] = $numero_nota;
	$dadosDuplicata['numero'] = $numero_dup;

	if(insereDuplicata($dadosDuplicata)) {

		$dadosDuplicataParcela['duplicata_id'] = mysqli_insert_id(banco::$connection);
		$dadosDuplicataParcela['status_id'] = $dadosDuplicata['status_id'];

		foreach($parcelas as $parcela) {
			$dadosDuplicataParcela['data_vencimento'] = converteData($parcela[1]);
			$dadosDuplicataParcela['valor'] = $parcela[3];
			$dadosDuplicataParcela['numero'] = $parcela[0];

			insereDuplicataParcelas($dadosDuplicataParcela);
		}
		echo saidaJson(1); // 1 - siginifica que ocorreu tudo bem
	}

} else if($tipoForm == "duplicata_busca") {

	$arr['fornecedores'] = buscaNomeFornecedores();
	$arr['empresas'] = buscaNomeEmpresas();
	$arr['tipo_doc'] = buscaNomeTipoDoc();
	$arr['bancos'] = buscaNomeBanco();
	$arr['moedas'] = buscaNomeMoedas();
	$arr['plano_contas'] = buscaNomePlanoContas();

	$arr['consulta_fornecedores'] = $arr['fornecedores'];
	$arr['consulta_empresas'] = $arr['empresas'];
	$arr['consulta_tipo_doc'] = $arr['tipo_doc'];

	$arr['consulta_fornecedores'][] = "TODOS";
	$arr['consulta_empresas'][] = "TODOS";
	$arr['consulta_tipo_doc'][] = "TODOS";

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
	} else if ($flag == 'banco') {
		$nome = buscaNomeBancoPorCodigo($cod);
	} else if ($flag == 'moeda') {
		$nome = buscaNomeMoedaPorCodigo($cod);
	} else if ($flag == 'plano_contas') {
		$nome = buscaNomePlanoContasPorCodigo($cod);
	}

	echo saidaJson($nome);


} else if($tipoForm == "busca_por_codigo_fornecedor") {

	$cod = _INPUT('cod','int','post');

	$nome['nome_fornecedor'] = buscaNomeFornecedorPorCodigo($cod);
	$nome['nome_plano'] = buscaPlanoContasPorFornecedor($nome['nome_fornecedor']);

	echo saidaJson($nome);

} else if($tipoForm == "busca_plano_contas") {

	$fornecedor = _INPUT('fornecedor','string','post');
	$plano_contas = buscaPlanoContasPorFornecedor($fornecedor);

	echo saidaJson($plano_contas);

} else {
	include('view/financeiro/duplicatas.phtml');
}
?>
