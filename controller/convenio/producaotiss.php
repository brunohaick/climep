<?php
if($_POST['flag'] == "convenio_busca") {
	$idConvenio = intval($_POST['idConvenio']);
	$convenio = ver('convenio','nome','id='.$idConvenio);

	die(saidaJson($convenio['nome']));
} else if($_POST['flag'] == "buscaProducao") {
	$idConvenio = buscaIdConvenioPorNome(_INPUT('convenio', 'string', 'post'));
	$dataInicio	= converteData($_POST['dataInicio']);
	$dataFim = converteData($_POST['dataFim']);
	$ordenado = $_POST['ordenado'];
	
	$tipo = $_POST['tipo'];

	$producao = buscaProducaoTiss($idConvenio, $dataInicio, $dataFim, $ordenado);
	die(saidaJson($producao));
} else {
	require('view/convenio/producaotiss.phtml');
}