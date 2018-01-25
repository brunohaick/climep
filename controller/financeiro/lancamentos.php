<?php
$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "lancamentos-busca") {

	$arr['contas'] = buscaNomeContasCorrente();
	$arr['tipos'] = buscaNomeTiposOperacao();

	$arr['contas'][] = "TODOS";
	$arr['tipos'][] = "TODOS";

	echo saidaJson($arr);

} else if($tipoForm == "busca_por_codigo") {

	$flag = _INPUT('flag','string','post');
	$cod = _INPUT('cod','int','post');

	if ($flag == 'tipo_operacao') {
		$nome = buscaNomeTiposOperacaoPorCodigo($cod);
	} else if ($flag == 'conta_corrente') {
		$nome = buscaNomeContasCorrentePorCodigo($cod);
	}

	echo saidaJson($nome);

} else if($tipoForm == "insere_lancamentos") {
	$data_operacao = _INPUT('data_operacao','string','post');
	$conta_corrente = _INPUT('conta_corrente','string','post');
	$tipo_operacao = _INPUT('tipo_operacao','string','post');
	$numero_documento = _INPUT('numero_documento','string','post');
	$valor = _INPUT('valor','string','post');
	$observacao = _INPUT('observacao','string','post');
	$dados['data'] = converteData($data_operacao);
	$dados['conta_corrente_id'] = buscaIdContaCorrentePorNome($conta_corrente);
	$dados['tipo_operacao_id'] = buscaIdTipoOperacaoPorNome($tipo_operacao);
	$dados['plano_contas_id'] = buscaIdPlanoContasPorTipoOperacaoId($dados['tipo_operacao_id']);
	$dados['numero_documento'] = $numero_documento;
	$dados['valor'] = $valor;
	$dados['observacao'] = $observacao;
	$dados['usuario_id'] = $_SESSION['usuario']['id'];
        
	if(insereLancamento($dados)) {
		die(saidaJson(1));
	}

} else if($tipoForm == "insere_lancamentos_pelo_caixa") {
	$data_operacao = _INPUT('data_operacao','string','post');
	$conta_corrente = _INPUT('conta_corrente','string','post');
	$tipo_operacao = _INPUT('tipo_operacao','string','post');
	$numero_documento = _INPUT('numero_documento','string','post');
	$valor = _INPUT('valor','string','post');
	$observacao = _INPUT('observacao','string','post');
	$dados['data'] = converteData($data_operacao);
	$dados['conta_corrente_id'] = $conta_corrente;
	$dados['tipo_operacao_id'] = $tipo_operacao;
	$dados['plano_contas_id'] = buscaIdPlanoContasPorTipoOperacaoId($dados['tipo_operacao_id']);
	$dados['numero_documento'] = $numero_documento;
	$dados['valor'] = $valor;
	$dados['observacao'] = $observacao;
	$dados['usuario_id'] = $_SESSION['usuario']['id'];
	if(insereLancamento($dados)) {
		die(saidaJson(1));
	}
} else {
	include('view/financeiro/lancamentos.phtml');
}