<?php
$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "edicaoDependente") {

	$idDependente = _INPUT('idDependente','int','post');
	$tituloDep = _INPUT('titulo','string','post');
	$matTitular = _INPUT('mat','string','post');
	$dependente = buscaDadosDependente($idDependente);
	$dependente['matricula'] = $matTitular;
	$dependente['medico'] = dadosMedicoIndPorId($dependente['medico_id']);;

	die(saidaJson($dependente));

} else if($tipoForm == "busca_medico_por_crm") {

	$crm = _INPUT('crm','string','post');
	$medico = buscaMedicoIndPorCrm($crm);

	echo saidaJson($medico['id']);

} else if($tipoForm == "busca_crm_medico_por_id") {

	$idMedico = _INPUT('id','string','post');
	$medico = dadosMedicoIndPorId($idMedico);

	echo saidaJson($medico['crm']);

} else if($tipoForm == "submit-form-dependente") {

	$matricula = _INPUT('matricula','string','post');
	$idDependente = _INPUT('idDependente','string','post');

	$dadosPessoa['nome'] = _INPUT('nome','string','post');
	$dadosPessoa['sobrenome'] = _INPUT('sobrenome','string','post');
	$dadosPessoa['data_nascimento'] = converteData(_INPUT('nascimento','string','post'));
	$dadosPessoa['sexo'] = _INPUT('sexo','string','post');
	$dadosPessoa['email'] = _INPUT('email','string','post');
	$dadosPessoa['facebook'] = _INPUT('facebook','string','post');
	$dadosPessoa['twitter'] = _INPUT('twitter','string','post');
	$dadosPessoa['ultima_modificacao'] = date("Y-m-d H:i:s");
	$dadosPessoa['pessoa_modificou'] = $_SESSION['usuario']['id'];

	$dadosCliente['medico_id'] = _INPUT('medico', 'int', 'post');
	$dadosCliente['fk_enfermeiro_id'] = _INPUT('vacinador', 'int', 'post');
	$dadosCliente['numero_carteira'] = _INPUT('num_carteira', 'int', 'post');
	$dadosCliente['validade_carteira'] = converteData(_INPUT('validade_carteira', 'int', 'post'));

	if(empty($dadosCliente['fk_enfermeiro_id']))
			unset($dadosCliente['fk_enfermeiro_id']);	

				/* TABELA CLIENTE */

	$dadosCliente['imprimir_lembrete'] = 0;
	if(isset($_POST['lembrete'])) {
		$dadosCliente['imprimir_lembrete'] = 1;
	}

			/* TABELA DEPENDENTE */
	$dadosDependente['vacina_casa'] = 0;
	if(isset($_POST['vacinaCasa'])) {
		$dadosDependente['vacina_casa'] = 1;
	}

	if(editarPessoa($dadosPessoa,$idDependente) && editarCliente($dadosCliente,$idDependente) && editarDependente($dadosDependente,$idDependente)) {
		$msgcode = 1;
		echo saidaJson(1);
		exit;
		alertaSucesso('Dependente cadastrado !');
	} else {
		$msgcode = 2;
		echo saidaJson(2);
		exit;
		alertaErro('Ocorreu algum erro ao Inserir!');
	}

} else {
		alertaErro('Preencha todos os campos obrigatórios !');
}