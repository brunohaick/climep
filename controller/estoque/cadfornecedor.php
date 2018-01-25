<?php

$tipoForm = _INPUT('tipoForm','String','post');

if($tipoForm == "insere-fornecedor") {

	$dadosFornecedor['nome'] = _INPUT('nome','String','post');
	$dadosFornecedor['cnpj'] = _INPUT('cnpj','String','post');
	$dadosFornecedor['descricao'] = _INPUT('descricao','String','post');
	$dadosFornecedor['endereco'] = _INPUT('endereco','String','post');
	$dadosFornecedor['cidade'] = _INPUT('cidade','String','post');
	$dadosFornecedor['estado'] = _INPUT('estado','String','post');
	$dadosFornecedor['pais'] = _INPUT('pais','String','post');
	$dadosFornecedor['fone'] = _INPUT('telefone','String','post');
	$dadosFornecedor['fax'] = _INPUT('fax','String','post');
	$dadosFornecedor['contato'] = _INPUT('contato','String','post');
	$dadosFornecedor['email'] = _INPUT('email','String','post');
	$dadosFornecedor['site'] = _INPUT('site','String','post');
	$dadosFornecedor['plano_contas_id'] = buscaIdPlanoContasPorNome(_INPUT('plano_contas','String','post'));

	if(insereFornecedor($dadosFornecedor)) {
		echo saidaJson(1);
	}

} else if($tipoForm == "busca_por_codigo") {

	$cod = _INPUT('cod','int','post');
	$nome = buscaNomePlanoContasPorCodigo($cod);
	echo saidaJson($nome);

} else if($tipoForm == "busca_plano_contas") {

	$arr['plano_contas'] = buscaNomePlanoContas();
	echo saidaJson($arr);

} else {
	include('view/estoque/cadfornecedor.phtml');
}
