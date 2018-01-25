<?php

if(isset($_POST['gravar'])) {

	$id = _INPUT('id','int','post');
	$dadosCliente['observacao'] = _INPUT('obs', 'string', 'post');
	$dadosCliente['imunodeficiente'] = 0;
	$dadosPessoa['data_nascimento'] = converteData(_INPUT('data_nascimento', 'string', 'post'));
	$dadosTitular['categoria_id'] = _INPUT('categoria_id', 'int', 'post');
	if(isset($_POST['imunodeficiente'])) {
		$dadosCliente['imunodeficiente'] = 1;
	}
	$dadosCliente['peso_nascimento'] = _INPUT('peso', 'string', 'post');
	$dadosCliente['parto_id'] = _INPUT('parto', 'int', 'post');
	$dadosCliente['gestacao_id'] = _INPUT('gestacao', 'int', 'post');
	$condicoesNascimento = _INPUT('condicoesNascimento', 'string', 'post');
	$alergias = _INPUT('alergias', 'string', 'post');
	$antecedenteMorbido = _INPUT('antMorbido', 'string', 'post');

	$idTitular = $id;
	if(buscaTipoCliente($id) === 'dependente'){
		$titularDados = buscaTitularPorDependente($id);
		$idTitular = $titularDados['id'];
	}

//	buscaTitularPorDependente($idDependente)

	/* CHECKBOXES */
	deleteClienteAntMorbido($id);
	deleteClienteAlergias($id);
	deleteClienteCondNascimento($id);
	foreach($antecedenteMorbido as $id_AM) {

			$dadosAM['cliente_cliente_id'] = $id;
			$dadosAM['antecedente_morbido_id'] = $id_AM;
			insereClienteAntMorbido($dadosAM);
	}
	foreach($alergias as $id_alergias) {

			$dadosAlergias['cliente_cliente_id'] = $id;
			$dadosAlergias['alergias_id'] = $id_alergias;
			insereClienteAlergias($dadosAlergias);
	}
	foreach($condicoesNascimento as $id_CN) {

			$dadosCN['cliente_cliente_id'] = $id;
			$dadosCN['condicoes_nascimento_id'] = $id_CN;
			insereClienteCN($dadosCN);
	}

	if (editarPessoa($dadosPessoa,$id) && editarCliente($dadosCliente,$id) && editarTitular($dadosTitular,$idTitular)) {
			saidaJson(1);
			die();
	} else {
		saidaJson(0);
		die();
	}
} else {
	saidaJson(2);
	die();
}