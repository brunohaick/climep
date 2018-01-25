<?php

if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "guia-tiss-dados_guia") {

	//$medico_id = _INPUT('medico_id', 'int', 'post');

	/*
	 * O médico escolhido é sempre o CLIMEP,
	 * mesmo que seja escolhido outro médico
	 * no formulário.
	 *
	 * */
	$med = buscaMedicoPorCrm(930); // Medico CLIMEP
	$medico_id = $med['id'];

	$cliente_id = _INPUT('cliente_id', 'int', 'post');
	$convenio_id = _INPUT('convenio_id', 'int', 'post');

	$medicoAssistente = buscaMedicoPorCliente($cliente_id);
	$medico = dadosMedicoPorId($medico_id);
	$nomeCliente = buscaNomeCliente($cliente_id);
	$cliente = buscaCarteiraConvenioCliente($cliente_id);
	$convenio = buscaConvenioPorId($convenio_id);
	$climep_cod_operadora = '05083142000183'; // CNPJ CLIMEP
	$crm_mascara_assistente = $medicoAssistente['crm'];
	$tabela = '02';
	if(stripos($convenio['nome'], "unimed") !== false) {
		$medicoAssistenteTemp = dadosUnimedMedicoPorCrm($medicoAssistente['crm']);
		$climep_cod_operadora = $medico['crm_mascara'].$medico['crm'];
		//print_r($medicoAssistenteTemp);
		$crm_mascara_assistente = $medicoAssistenteTemp['crm_mascara'].$medicoAssistente['crm'].$medicoAssistenteTemp['digito_unimed'];
	} else if(stripos($convenio['nome'], "cassi") !== false) {
		$tabela = '16';
	}
	/*
	 * Dados do médico CLIMEP, que é o 'médico'
	 * cadastrado nas operadoras (Unimed, CASF, ...)
	 *
	 * */
	$dados['medico_escolhido_nome'] = $medico['nome']." ".$medico['sobrenome'];
	$dados['medico_escolhido_estado'] = $medico['estado'];
	$dados['medico_escolhido_cidade'] = $medico['cidade'];
	$dados['medico_escolhido_cep'] = $medico['cep'];
	$dados['medico_escolhido_logradouro'] = $medico['endereco'];
	if(!empty($medico['numero']))
		$dados['medico_escolhido_logradouro'] .= ', '.$medico['numero'];
	$dados['medico_escolhido_crm_mascara'] = $climep_cod_operadora;

	/*
	 * Dados Do médico associado ao Cliente.
	 *
	 * */
	$dados['medico_assistente_nome'] = $medicoAssistente['nome']." ".$medicoAssistente['sobrenome'];
	$dados['medico_assistente_crm_mascara'] = $crm_mascara_assistente;
	$dados['medico_assistente_estado'] = $medicoAssistente['estado'];

	$dados['cliente_nome'] = $nomeCliente;
	$dados['cliente_carteira'] = $cliente['numero_carteira'];
	$dados['cliente_carteira_validade'] = converteData($cliente['validade_carteira']);

	$dados['convenio_registro_ans'] = $convenio['registro_ans'];
	$dados['convenio_nome'] = $convenio['nome'];
	$dados['tabela'] = $tabela;

	echo saidaJson($dados);

}  else if($_POST['tipoForm'] === "colodaDadosNaSessionGuiaTiss") {
	$_SESSION['GuiaTiss'] = $_POST;
} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "modal-guia-tiss") {

	$tipoExame = _INPUT('tipo_proc','int','post');

	if($tipoExame == "Exame") {
		$qtd_procs = _INPUT('qtdProcs','int','post');
		require('view/recepcao/modal-guia-tiss.phtml');
	} else if($tipoExame == "Consulta") {
		require('view/recepcao/modal-guia-tiss-consulta.phtml');
	}

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "guia-tiss-soma_data") {

	$data = _INPUT('data_autorizacao', 'int', 'post');
	echo saidaJson(somarData($data, 1, 'mes'));

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "insere_guia_tiss") {


	$guia = _INPUT('guia', 'String', 'post');

	$dadosGuiaTiss['registro_ans'] = $guia[0];
	$dadosGuiaTiss['num_guia_principal'] = $guia[1];
	$dadosGuiaTiss['data_autorizacao'] = converteData($guia[2]);
	$dadosGuiaTiss['senha'] = $guia[3];
	$dadosGuiaTiss['senha_data_validade'] = converteData($guia[4]);
	$dadosGuiaTiss['senha_data_emissao'] = converteData($guia[5]);
	$dadosGuiaTiss['num_carteira'] = $guia[6];
	$dadosGuiaTiss['plano'] = $guia[7];
	$dadosGuiaTiss['carteira_validade'] = converteData($guia[8]);
	$dadosGuia['nome'] = $guia[9];
	$dadosGuia['numero_cns'] = $guia[10];
	$dadosGuiaTiss['operadora_cod'] = $guia[11];
	$dadosGuia['nome_contratado'] = $guia[12];
	$dadosGuiaTiss['num_cnes'] = $guia[13];
	$dadosGuia['nome_profissional_solicitante'] = $guia[14];
	$dadosGuiaTiss['conselho_profissional'] = $guia[15];
	$dadosGuia['numero_conselho'] = $guia[16];
	$dadosGuia['estado'] = $guia[17];
	$dadosGuiaTiss['codigo_cbos'] = $guia[18];
	$dadosGuiaTiss['data_hora_solicitacao'] = converteData($guia[19]);
	$dadosGuiaTiss['carater_solicitacao_id'] = $guia[20];
	$dadosGuiaTiss['cid_id'] = $guia[21];
	$dadosGuiaTiss['indicacao'] = $guia[22];
	$dadosGuia['codigo_executante'] = $guia[23];
	$dadosGuia['nome_executante'] = $guia[24];
	$dadosGuia['tl'] = $guia[25];
	$dadosGuia['logradouro'] = $guia[26];
	$dadosGuia['municipio'] = $guia[27];
	$dadosGuia['estado2'] = $guia[28];
	$dadosGuia['codigo_ibge'] = $guia[29];
	$dadosGuia['cep'] = $guia[30];
	$dadosGuia['cnes'] = $guia[31];
	$dadosGuia['codigo_executante'] = $guia[32];
	$dadosGuia['nome_profissional_executante'] = $guia[33];
	$dadosGuia['conselho_profissional2'] = $guia[34];
	$dadosGuia['numero_conselho2'] = $guia[35];
	$dadosGuia['estado3'] = $guia[36];
	$dadosGuia['codigo_cbos2'] = $guia[37];
	$dadosGuia['grau_particoes'] = $guia[38];
	$dadosGuiaTiss['tipo_atendimento_id'] = $guia[39];
	$dadosGuiaTiss['indicacao_acidente_id'] = $guia[40];
	$dadosGuiaTiss['tipo_saida_id'] = $guia[41];
	$dadosGuiaTiss['tipo_doenca_id'] = $guia[42];
	$dadosGuiaTiss['tempo_doenca_id'] = $guia[43];
	$dadosGuia['observacoes'] = $guia[44];

	$dadosGuia['total_procedimentos'] = $guia[45];
	$dadosGuia['total_taxas_alugueis'] = $guia[46];
	$dadosGuia['total_materiais'] = $guia[47];
	$dadosGuia['total_medicamento'] = $guia[48];
	$dadosGuia['total_diarias'] = $guia[49];
	$dadosGuia['total_gases_medicinais'] = $guia[50];
	$dadosGuia['total_geral_guia'] = $guia[51];
	$dadosGuia['data_solicitante'] = converteData($guia[52]);
	$dadosGuia['data_responsavel_autorizacao'] = converteData($guia[53]);
	$dadosGuia['data_beneficio_responsavel'] = converteData($guia[54]);
	$dadosGuia['data_prestador_executante'] = converteData($guia[55]);

	if(insereGuiaTiss($dadosGuiaTiss)) {
		$guia_tiss_id = mysqli_insert_id(banco::$connection);
//                die(var_dump($_SESSION['grupo_procedimento']));
		editarGrupoProcedimento($_SESSION['grupo_procedimento'],$guia_tiss_id);
		echo saidaJson(1);
	}

	unset($_SESSION['grupo_procedimento']);
	die();

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "insere_guia_tiss_consulta") {

	$guia = _INPUT('guia', 'String', 'post');

	$dadosGuiaTiss['registro_ans'] = $guia[0];
	$dadosGuiaTiss['num_guia_principal'] = $guia[1];
	$dadosGuiaTiss['data_autorizacao'] = converteData($guia[2]);
	$dadosGuiaTiss['num_carteira'] = $guia[3];
	$dadosGuiaTiss['plano'] = $guia[4];
	$dadosGuiaTiss['carteira_validade'] = $guia[5];
	$dadosGuia['nome'] = $guia[6];
	$dadosGuiaTiss['num_cnes'] = $guia[7];
	$dadosGuia['numero_cns'] = $guia[8];
	$dadosGuiaTiss['operadora_cod'] = $guia[9];
	$dadosGuia['nome_contratado'] = $guia[10];
	$dadosGuia['tl'] = $guia[11];
	$dadosGuia['logradouro'] = $guia[12];
	$dadosGuia['municipio'] = $guia[13];
	$dadosGuia['estado'] = $guia[14];
	$dadosGuia['codigo_ibge'] = $guia[15];
	$dadosGuia['cep'] = $guia[16];
	$dadosGuia['nome_profissional_executante'] = $guia[17];
	$dadosGuia['conselho_profissional'] = $guia[18];
	$dadosGuia['numero_conselho'] = $guia[19];
	$dadosGuia['estado2'] = $guia[20];
	$dadosGuia['codigo_cbos2'] = $guia[21];
	$dadosGuiaTiss['tipo_saida_id'] = $guia[22];
	$dadosGuiaTiss['indicacao_acidente_id'] = $guia[23];
	$dadosGuiaTiss['tipo_doenca_id'] = $guia[24];
	$dadosGuiaTiss['tempo_doenca_id'] = $guia[25];
	$dadosGuia['cid1'] = $guia[26];
	$dadosGuia['cid2'] = $guia[27];
	$dadosGuia['cid2'] = $guia[28];
	$dadosGuia['cid2'] = $guia[29];
	$dadosGuia['data_atendimento'] = converteData($guia[30]);
	$dadosGuia['codigo_tabela'] = $guia[31];
	$dadosGuia['codigo_procedimento'] = $guia[32];
	$dadosGuiaTiss['tipo_atendimento_id'] = $guia[33];
	$dadosGuia['observacoes'] = $guia[34];
	$dadosGuia['data_solicitante'] = converteData($guia[35]);
	$dadosGuia['data_beneficio_responsavel'] = converteData($guia[36]);

	if(insereGuiaTiss($dadosGuiaTiss)) {
		$guia_tiss_id = mysqli_insert_id(banco::$connection);
		editarGrupoProcedimento($_SESSION['grupo_procedimento'],$guia_tiss_id);
		echo saidaJson(1);
	}
	unset($_SESSION['grupo_procedimento']);
	die();

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "guia-tiss-nome-doenca") {

	$proc_id_cid = _INPUT('id_cid','int','post');
	$cid = descricaoCidById($proc_id_cid);

	echo saidaJson($cid);

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "estorno_guia_tiss") {

	$idGrupo = _INPUT('grupo_id','int','post');
	$grupo = buscaGrupoProcedimento($idGrupo);
	$id_guia = $grupo['guia_tiss_id'];
	$medico_id = $grupo['medico_medico_id'];
	$cliente_id = $grupo['cliente_cliente_id'];
	$data = date('Y-m-d');

	deletarHistoricoProcedimentosPorGrupoProcedimento($idGrupo);
	deletarGrupoProcedimento($idGrupo);
	deletarClienteFilaEsperaMedico($medico_id, $cliente_id, $data);
	if(!empty($id_guia))
		deletarGuiaTiss($idGrupo);

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "valida_carteira_unimed_879") {

	$num_carteira = _INPUT('num_carteira','int','post');
	$count = existeNumCarteira($num_carteira);

	echo saidaJson($count);

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "modal-estorno-guia-tiss") {

	$convenio_id = _INPUT('convenio_id','int','post');
	$data = date('Y-m-d');
	$usuario_id = $_SESSION['usuario']['id'];;
	$listaGrupos = listaGruposProcedimentos($usuario_id, $data);

	$i = 0;
	foreach($listaGrupos as $lista) {

		$guiasTiss[$i]['nome_medico'] = buscaNomeMedicoPorGrupoProcedimento($lista['medico_medico_id']);
		$guiasTiss[$i]['nome_cliente'] = buscaNomeClientePorGrupoProcedimento($lista['cliente_cliente_id']);
		$guiasTiss[$i]['nome_convenio'] = buscaNomeConvenioPorGrupoProcedimento($lista['convenio_id']);
		$guiasTiss[$i]['grupo_id'] = $lista['id'];
		$guiasTiss[$i]['data'] = $lista['data'];
		$guiasTiss[$i]['valor'] = $lista['valor'];

		$i++;
	}

	require("view/recepcao/modal-estorno-guia-tiss.phtml");

} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "modal-estorno-guia-tiss2") {

	$convenio_id = _INPUT('convenio_id','int','post');
	$usuario_id = _INPUT('usuario_id','int','post');
	$data = date('Y-m-d');
	$listaGrupos = listaGruposProcedimentos($usuario_id, $data);

	$i = 0;
	foreach($listaGrupos as $lista) {

		$guiasTiss[$i]['nome_medico'] = buscaNomeMedicoPorGrupoProcedimento($lista['medico_medico_id']);
		$guiasTiss[$i]['nome_cliente'] = buscaNomeClientePorGrupoProcedimento($lista['cliente_cliente_id']);
		$guiasTiss[$i]['nome_convenio'] = buscaNomeConvenioPorGrupoProcedimento($lista['convenio_id']);
		$guiasTiss[$i]['grupo_id'] = $lista['id'];
		$guiasTiss[$i]['data'] = converteData($lista['data']);
		$guiasTiss[$i]['valor'] = number_format($lista['valor'],2,',','.');

		$i++;
	}

	echo saidaJson($guiasTiss);

	die();
} else if(isset($_POST['tipoForm']) && $_POST['tipoForm'] == "getProcedimentos") {
	$procedimentos = array();
	mysqli_set_charset("utf8");
	$mysqli_query = mysqli_query('SELECT p.descricao, pes.nome , pes.sobrenome , pes.id
		FROM `grupo_procedimento` as g
		INNER JOIN pessoa as pes ON pes.id = g.cliente_cliente_id
		INNER JOIN historico_procedimento as h ON g.id = h.grupo_procedimento_id
		INNER JOIN procedimento as p ON p.id = h.procedimento_id
		WHERE g.id = ' . $_POST['grupoId']);
	
	while($row = mysqli_fetch_assoc($mysqli_query)){
		$procedimentos[] = $row;
	}
	if(isset($procedimentos[0])) {
		$procedimentos['ID'] = mysqli_fetch_assoc(mysqli_query('SELECT id FROM fila_espera_consulta as fila WHERE fila.data = DATE(NOW()) AND cliente_id = ' . $procedimentos[0]['id']));
	}
	
	$titular = buscaTitularPorDependente($procedimentos[0]['id']);
	$procs['matricula'] = $titular['matricula'];
	$procs['procedimentos'] = $procedimentos;

	die(saidaJson($procs));
}















