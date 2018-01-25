<?php

if (isset($_POST['cadastroDependente'])) {

	$matricula = _INPUT('matricula', 'string', 'post');

	$dadosPessoa['nome'] = _INPUT('nome', 'string', 'post');
	$dadosPessoa['sobrenome'] = _INPUT('sobrenome', 'string', 'post');
	$dadosPessoa['data_nascimento'] = converteData(_INPUT('nascimento', 'string', 'post'));
	$dadosPessoa['sexo'] = _INPUT('sexo', 'string', 'post');
	$dadosPessoa['email'] = _INPUT('email', 'string', 'post');
	$dadosPessoa['facebook'] = _INPUT('facebook', 'string', 'post');
	$dadosPessoa['twitter'] = _INPUT('twitter', 'string', 'post');
	$dadosPessoa['ultima_modificacao'] = date("Y-m-d H:i:s");
	$dadosPessoa['pessoa_modificou'] = $_SESSION['usuario']['id'];

	/* TABELA CLIENTE */
	$idTitularx = _INPUT('idTitular', 'int', 'post');

	$dadoscliente['imprimir_lembrete'] = 1;
	if (isset($_POST['lembrete'])) {
		$dadosCliente['imprimir_lembrete'] = 0;
	}

	$dadoscliente['imunodeficiente'] = 0;
	if (isset($_POST['vacina-imuno'])) {
		$dadosCliente['imprimir_lembrete'] = 1;
	}

	$dadosCliente['fk_enfermeiro_id'] = _INPUT('vacinador', 'int', 'post');
	if (empty($dadosCliente['fk_enfermeiro_id']))
		unset($dadosCliente['fk_enfermeiro_id']);

	$dadosCliente['numero_carteira'] = _INPUT('num_carteira', 'int', 'post');
	$dadosCliente['validade_carteira'] = converteData(_INPUT('validade_carteira', 'int', 'post'));
	$dadosCliente['medico_id'] = _INPUT('medico', 'int', 'post');
	$dadosCliente['parto_id'] = 1;
	$dadosCliente['gestacao_id'] = 1;
	$dadosCliente['membro'] = buscaNovoMembro($idTitularx);
	$dadosCliente['peso_nascimento'] = '';
	$dadosCliente['flag'] = "D";

	/* TABELA DEPENDENTE */

	$dadosDependente['vacina_casa'] = 0;
	if (isset($_POST['vacina_casa'])) {
		$dadosDependente['vacina_casa'] = 1;
	}

	$dadosDependente['fk_titular_id'] = $idTitularx;
	$matriculaDependente = _INPUT('matricula', 'int', 'post');

	/* TABELA PESSOA */

	//$cpf = _INPUT('cpf', 'string', 'post');

	$titular = buscaCliente($dadosDependente['fk_titular_id']);

	$dadosPessoa['endereco'] = $titular['endereco'];
	$dadosPessoa['numero'] = $titular['numero'];
	$dadosPessoa['bairro'] = $titular['bairro'];
	$dadosPessoa['cidade'] = $titular['cidade'];
	$dadosPessoa['estado'] = $titular['estado'];
	$dadosPessoa['cep'] = $titular['cep'];
	$dadosPessoa['complemento'] = $titular['complemento'];

	$nome = $dadosPessoa['nome'];
	$sobrenome = $dadosPessoa['sobrenome'];

	if (confereExistenciaPessoaPorNome($nome, $sobrenome)) {
		echo saidaJson(2);
		die();
	} else {
		if (inserePessoa($dadosPessoa)) {
			$dadosCliente['cliente_id'] = mysqli_insert_id(banco::$connection);
			$dadosDependente['dependente_id'] = $dadosCliente['cliente_id'];

			if (insereCliente($dadosCliente) && insereDependente($dadosDependente)) {
				echo saidaJson(1);
				die();
			} else {
				alertaErro($msgs['ERRO_GERAL']);
			}
		} else {
			alertaErro($msgs['ERRO_GERAL']);
		}
	}
}