<?php

$tipoForm = _INPUT('tipoForm', 'string', 'post');

if ($tipoForm == "convenio_busca") {

	$arr['convenios'] = buscaNomeConvenios();
	$arr['empresas'] = buscaNomeEmpresas();

	$arr['consulta_convenios'] = $arr['convenios'];
	//$arr['consulta_empresas'] = $arr['empresas'];

	$arr['consulta_convenios'][] = "TODOS";
	//$arr['consulta_empresas'][] = "TODOS";

	echo saidaJson($arr);
} else if ($tipoForm == "busca_por_codigo") {

	$flag = _INPUT('flag', 'string', 'post');
	$cod = _INPUT('cod', 'int', 'post');

	if ($flag == 'convenio') {
		$nome = buscaNomeConvenioPorCodigo($cod);
	} else if ($flag == 'empresa') {
		$nome = buscaNomeEmpresaPorCodigo($cod);
	} else if ($flag == 'tipo_lancamento') {
		$nome = buscaNomeTiposOperacaoPorCodigo($cod, 'E');
	}

	echo saidaJson($nome);
} else if ($tipoForm == "localizaTerceiros") {
	$idConvenio = buscaIdConvenioPorNome(_INPUT('nomeConvenio', 'string', 'post'));
	$nomeConvenio = _INPUT('nomeConvenio', 'string', 'post');
	$ordenado = _INPUT('ordenado', 'string', 'post');
	$selecionado = _INPUT('selecionado', 'string', 'post');
	$dataInicio = converteData(_INPUT('data_inicio', 'string', 'post'));
	$dataFim = converteData(_INPUT('data_fim', 'string', 'post'));
	$nomeEmpresa = _INPUT('nomeEmpresa', 'string', 'post');
	$situacao = _INPUT('situacaoConsulta', 'string', 'post');
	$procedimentos['lista'] = buscaAtendimentosConvenio($idConvenio, $dataInicio, $dataFim, $ordenado, $selecionado);
	$procedimentos['subtotal'] = buscaAtendimentosConvenioSubtotal($idConvenio, $dataInicio, $dataFim, $ordenado, $selecionado);
	unset($cabecalho);
	$cabecalho['idConvenio'] = $idConvenio;
	$cabecalho['nomeEmpresa'] = $nomeEmpresa;
	$cabecalho['nomeConvenio'] = $nomeConvenio;
	$cabecalho['data_inicio'] = $dataInicio;
	$cabecalho['data_fim'] = $dataFim;
	$cabecalho['ordenado'] = $ordenado;
	$_SESSION['listaConvenio'] = $procedimentos;
	$_SESSION['listaConvenio']['cabecalho'] = $cabecalho;

	echo saidaJson($procedimentos);
} else {
	include('view/convenio/consultaconvenio.phtml');
}
