<?php

$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "fatura_data_parcelas") {

} else if($tipoForm == "fatura_busca") {

	$arr['convenios'] = buscaNomeConvenios();
	$arr['empresas'] = buscaNomeEmpresas();
	$arr['contas'] = buscaNomeContasCorrente();
	$arr['tipo_operacao'] = buscaNomeTiposOperacao('E');

	$arr['consulta_convenios'] = $arr['convenios'];
	$arr['consulta_empresas'] = $arr['empresas'];
        
	$arr['consulta_convenios'][] = "TODOS";
	$arr['consulta_empresas'][] = "TODOS";

	echo saidaJson($arr);

} else if($tipoForm == "busca_por_codigo") {

	$flag = _INPUT('flag','string','post');
	$cod = _INPUT('cod','int','post');

	if($flag == 'convenio') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeConvenioPorCodigo($cod);
		}
	} else if ($flag == 'empresa') {
		if($cod == '00') {
			$nome = "TODOS";
		} else {
			$nome = buscaNomeEmpresaPorCodigo($cod);
		}
	} else if ($flag == 'tipo_operacao') {
		$nome = buscaNomeTiposOperacaoPorCodigo($cod,'E');
	} 

	echo saidaJson($nome);

} else if($tipoForm == "insere_faturas") {

	$convenio = _INPUT('convenio','string','post');
	$empresa = _INPUT('empresa','string','post');
	$data_vencimento = converteData(_INPUT('data_vencimento','string','post'));
	$data_inicio = converteData(_INPUT('data_inicio','string','post'));
	$data_fim = converteData(_INPUT('data_fim','string','post'));
	$idConvenio = buscaIdConvenioPorNome($convenio);
	$total = buscaTotalPorConvenioPorPeriodo($idConvenio, $data_inicio, $data_fim);
	$dadosFaturaConvenio['convenio_id'] = $idConvenio;
	$dadosFaturaConvenio['empresa_id'] = buscaIdEmpresaPorNome($empresa);
	$dadosFaturaConvenio['status_id'] = buscaIdStatusPorNome("Em Aberto");
	$dadosFaturaConvenio['data_vencimento'] = $data_vencimento;
	$dadosFaturaConvenio['data_inicio'] = $data_inicio;
	$dadosFaturaConvenio['data_fim'] = $data_fim;
	$dadosFaturaConvenio['data_faturamento'] = date('Y-m-d');
	$dadosFaturaConvenio['data_emissao'] = date('Y-m-d');
	$dadosFaturaConvenio['usuario_id'] = $_SESSION['usuario']['id'];
	$dadosFaturaConvenio['faturado'] = $total['faturado'];
	$dadosFaturaConvenio['taxa'] = buscaConvenioImposto($idConvenio);

	if(insereFaturaConvenio($dadosFaturaConvenio))
		echo saidaJson('1');
	else
		echo saidaJson('0');
} else if($tipoForm == "lista_faturas") {
	$convenio = _INPUT('convenio','string','post');
	$empresa = _INPUT('empresa','string','post');
	$data_inicio = converteData(_INPUT('data_inicio','string','post'));
	$data_fim = converteData(_INPUT('data_fim','string','post'));
	$statusId = _INPUT('status','string','post');
	$selecionado = _INPUT('selecionado','string','post');
	$ordenado = _INPUT('ordenado','string','post');
	if($empresa == "TODOS") {
		$empresaId = '00';
	} else {
		$empresaId = buscaIdEmpresaPorNome($empresa);
	}

	if($convenio == "TODOS") {
		$convenioId = '00';
	} else {
		$convenioId = buscaIdConvenioPorNome($convenio);
	}                
	$faturas = buscaFaturasConvenioPorPeriodo($convenioId, $empresaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);
        $_SESSION['reciboConvenio'] = $faturas;
//	die(print_r($faturas));
//	die(saidaJson($faturas));
	for($i = 0; $i < count($faturas); $i++) {

		if(empty($faturas[$i]['numero_nota']))
			$faturas[$i]['numero_nota'] = '';

		if(empty($faturas[$i]['valor_a_pagar']))
			$faturas[$i]['valor_a_pagar'] = formata_dinheiro2(0);
		else
			$faturas[$i]['valor_a_pagar'] = formata_dinheiro2($faturas[$i]['valor_a_pagar']);

		if(empty($faturas[$i]['valor_pago']))
			$faturas[$i]['valor_pago'] = formata_dinheiro2(0);
		else
			$faturas[$i]['valor_pago'] = formata_dinheiro2($faturas[$i]['valor_pago']);

		if(empty($faturas[$i]['faturado']))
			$faturas[$i]['faturado'] = formata_dinheiro2(0);
		else
			$faturas[$i]['faturado'] = formata_dinheiro2($faturas[$i]['faturado']);

		if(empty($faturas[$i]['desconto']))
			$faturas[$i]['desconto'] = formata_dinheiro2(0);
		else
			$faturas[$i]['desconto'] = formata_dinheiro2($faturas[$i]['desconto']);

		if(empty($faturas[$i]['liquido']))
			$faturas[$i]['liquido'] = formata_dinheiro2(0);
		else
			$faturas[$i]['liquido'] = formata_dinheiro2($faturas[$i]['liquido']);

		if(empty($faturas[$i]['ajuste']))
			$faturas[$i]['ajuste'] = formata_dinheiro2(0);
		else
			$faturas[$i]['ajuste'] = formata_dinheiro2($faturas[$i]['ajuste']);

		if(empty($faturas[$i]['glosa']))
			$faturas[$i]['glosa'] = formata_dinheiro2(0);
		else
			$faturas[$i]['glosa'] = formata_dinheiro2($faturas[$i]['glosa']);

		if(!empty($faturas[$i]['usuario_baixou_id']))
			$faturas[$i]['usuario_baixou'] = buscaUsuarioPorId($faturas[$i]['usuario_baixou_id']);
		else
			$faturas[$i]['usuario_baixou'] = '';

		if(empty($faturas[$i]['data_baixa']))
			$faturas[$i]['data_baixa'] = '';

		if(empty($faturas[$i]['data_vencimento']))
			$faturas[$i]['data_vencimento'] = '';

		if($faturas[$i]['nome_status'] == "Em Aberto") {
			$faturas[$i]['nome_status'] = "A";
		} else if($faturas[$i]['nome_status'] == "Baixado") {
			$faturas[$i]['nome_status'] = "B";
		} else if($faturas[$i]['nome_status'] == "Baixa Parcial") {
			$faturas[$i]['nome_status'] = "P";
		}

	}
	die(saidaJson($faturas));

} else if($tipoForm == "dados_fatura") {

	$idFatura = _INPUT('id','string','post');

	$fatura = dadosFaturaPorId($idFatura);
	
	$desconto = bcdiv(bcmul($fatura['faturado'],$fatura['taxa']),100);

	$fatura['desconto'] = formata_dinheiro2($desconto);
	$fatura['valor_a_pagar'] = formata_dinheiro2(bcsub($fatura['faturado'],$desconto));
	$fatura['faturado'] = formata_dinheiro2($fatura['faturado']);
	$fatura['zero'] = formata_dinheiro2(0);
	$fatura['data_baixa'] = date('d/m/Y');

	//print_r($fatura);
	echo saidaJson($fatura);


} else if($tipoForm == "baixar_fatura") {

	$id = _INPUT('id','string','post');

	$conta_corrente = _INPUT('conta_corrente','string','post');
	$tipo_operacao = _INPUT('tipo_operacao','string','post');
	$data_baixa = converteData(_INPUT('data_baixa','string','post'));

	$dados['faturado'] = _INPUT('faturado','string','post');
	$dados['valor_a_pagar'] = _INPUT('valor_a_pagar','string','post');
	$dados['valor_pago'] = _INPUT('valor_pago','string','post');
	$dados['valor_total'] = $dados['valor_pago'] + $dados['valor_a_pagar'];
	$dados['desconto'] = _INPUT('desconto','string','post');
	$dados['ajuste'] = _INPUT('ajustes','string','post');
	$dados['liquido'] = _INPUT('liquido','string','post');

	$dados['conta_corrente_id'] = buscaIdContaCorrentePorNome($conta_corrente);
	$dados['tipo_operacao_id'] = buscaIdTipoOperacaoPorNome($tipo_operacao);
	$dados['plano_contas_id'] = buscaIdPlanoContasPorTipoOperacaoId($dados['tipo_operacao_id']);
	$dados['usuario_baixou_id'] = $_SESSION['usuario']['id'];
//	die(var_dump($dados));
	$dados['status_id'] = statusByNome("Baixado");
	$dados['data_baixa'] = $data_baixa;
	
	if(updateFaturaConvenioStatusBaixa($dados,$id)){
		die(saidaJson(1));
	}else{
		die(saidaJson(0));
	}

}else if($tipoForm == "enviarNFE") {
	$idFatura = _INPUT('id','string','post');

	$fatura = dadosFaturaPorId($idFatura);
	
	$desconto = bcdiv(bcmul($fatura['faturado'],$fatura['taxa']),100);

	$fatura['desconto'] = formata_dinheiro2($desconto);
	$fatura['data_entrada'] = date('Y/m/d');
	$faturaNFE['banco_id'] = $fatura['banco'];
	$faturaNFE['usuario_id'] =  $_SESSION['usuario']['id'];
	$faturaNFE['tipo'] = null;
	$faturaNFE['fornecedores_id'] = '531';
	$faturaNFE['frete_id'] = '1';
	$faturaNFE['valor_frete'] = '0';
	$faturaNFE['valor_nota_fiscal'] = $fatura['faturado'];
	$faturaNFE['data_entrada'] = $fatura['data_entrada'];
	$faturaNFE['data_emissao'] =  date('Y/m/d');;
	
	if(insereNotaFiscal($faturaNFE)){ //Mudar para nota fiscal
		die(saidaJson(1));
	}else{
		die(saidaJson(0));
	}

} else if($tipoForm == "definirIdFaturaReciboConvenio") {
	$recibo['id'] = _INPUT('id','string','post');
        $recibo['nomeConvenio'] = _INPUT('nomeConvenio','string','post');
        $recibo['valorPago'] = _INPUT('valorPago','string','post');        
        $_SESSION['FaturaReciboConvenio'] = $recibo;
} 
else {
	include('view/convenio/consultafatura.phtml');
}
