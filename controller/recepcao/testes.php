<?php
if($_POST['flag'] == 'buscaClientes') {
	$idTitular = _INPUT('idTitular','int','post');
	$dep = buscaNomeDependentesPorTitular($idTitular);
	$titular = buscaNomeTitular($idTitular);

	$clientes[] = $titular;

	foreach($dep as $depIm) {
		$clientes[] = $depIm;
	}
	echo saidaJson($clientes);

} else if( $_POST['flag'] == "buscarTesteVisual" ) {
	$idCliente = $_POST['idCliente'];
	$cliente = buscaClienteById($idCliente);
	$dados['data-nascimento'] = converteData($cliente['data_nascimento']);

	$dados['parto'] = $cliente['parto_id'];
	$dados['gestacao'] = $cliente['gestacao_id'];
	$dados['idade_gestacional'] = $cliente['idade_gestacional'];
	$dados['peso_nascimento'] = $cliente['peso_nascimento'];
	$dados['apgar'] = $cliente['apgar'];

	$triagemOlhinho = buscaTriagemOlhinhoCliente($idCliente,'ult_consulta');
	$dados['olhinho-existe'] = 0;
	if(!empty($triagemOlhinho)) {
		$dados['olhinho-existe'] = 1;
	}
	$dados['olhinho-resultado-od'] = $triagemOlhinho['resultado_od'];
	$dados['olhinho-resultado-oe'] = $triagemOlhinho['resultado_oe'];
	$dados['olhinho-anotacoes-op'] = $triagemOlhinho['anotacoes'];
	$dados['olhinho-anotacoes-hf'] = $triagemOlhinho['hf'];
	$dados['olhinho-anotacoes-outros'] = $triagemOlhinho['outros_exames'];
	$dados['olhinho-anotacoes-obs'] = $triagemOlhinho['observacao'];
	$dados['olhinho-reteste-data'] = converteData($triagemOlhinho['data_reteste']);
	$dados['olhinho-reteste-ok'] = $triagemOlhinho['data_reteste'];
	$dados['olhinho-sug-funcao-normal'] = $triagemOlhinho['visual_normal'];
	$dados['olhinho-sug-funcao-anormal'] = $triagemOlhinho['visual_anormal'];

	echo saidaJson($dados);

} else if( $_POST['flag'] == "buscarTesteAuditivo" ) {
	$idCliente = $_POST['idCliente'];
	$dadosCliente = buscaClienteById($idCliente);
	$dados['data-nascimento'] = converteData($dadosCliente['data_nascimento']);
	$dados['medico'] = $dadosCliente['fk_medico_id'];
	$triagemOrelhinha1 = buscaTriagemOrelhinha1Cliente($idCliente,'ult_consulta');
	$dados['orelhinha1-existe'] = 0;
	if(!empty($triagemOrelhinha1)) {
		$dados['orelhinha1-existe'] = 1;
	}
	$dados['O1-OD-TEOAE'] = $triagemOrelhinha1['teoae_od'];
	$dados['O1-OD-NOISE'] = $triagemOrelhinha1['noise_od'];
	$dados['O1-OD-frequencia'] = $triagemOrelhinha1['frequencia_od'];
	$dados['O1-OE-TEOAE'] = $triagemOrelhinha1['teoae_oe'];
	$dados['O1-OE-NOISE'] = $triagemOrelhinha1['noise_oe'];
	$dados['O1-OE-frequencia'] = $triagemOrelhinha1['frequencia_oe'];
	$dados['O1-meato-OD'] = $triagemOrelhinha1['obstrucao_meato_od'];
	$dados['O1-meato-OE'] = $triagemOrelhinha1['obstrucao_meato_oe'];
	$dados['O1-localizacao'] = $triagemOrelhinha1['localizacao_meato'];
	$dados['O1-observacoes'] = $triagemOrelhinha1['observacao'];
	$dados['O1-reavaliacao'] = converteData($triagemOrelhinha1['reavaliacao']);
	
	echo saidaJson($dados);
}
?>
