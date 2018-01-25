<?php
//include('controller/ag_medica/horario.php');
//$idAgendamento = $_POST['kid'];
//$clienteAgendamento = agendamentoById($idAgendamento);
//$varButton = "_editar";
//
//
//	$dadosAgendamento['nome_ag'] = _INPUT('nome','string','post');
//	$dadosAgendamento['resp_ag'] = _INPUT('responsavel','string','post');
//	$dadosAgendamento['contato_ag'] = _INPUT('contato','int','post');
//	$dadosAgendamento['celular_ag'] = _INPUT('celular','int','post');
//	$dadosAgendamento['cliente_id'] = _INPUT('id','int','post');
//	$dadosAgendamento['medico_id'] = _INPUT('medico_id','int','post');
//	$dadosAgendamento['observacao'] = _INPUT('obs', 'string', 'post');
//	$dadosAgendamento['status_id'] = _INPUT('status_ag','int','post');
//	$dadosAgendamento['motivo_status'] = _INPUT('motivo', 'string', 'post');
//	$dadosAgendamento['quantidade'] = _INPUT('quantidade', 'int', 'post');
//	$dadosAgendamento['hora_chegada'] = _INPUT('hr_chegada', 'string', 'post');
//	$dadosAgendamento['hora_atendimento'] = _INPUT('hr_atendimento', 'string', 'post');
//	$dadosAgendamento['data_agendamento'] = date("Y-m-d");
//	$dadosAgendamento['agendador_id'] = $_SESSION['usuario']['id'];
//	//$dadosAgendamento['data_agendamento'] = converteData($dataAgenda);
//
//	$dadosAgendamento['convenio_has_procedimento_has_tabela_convenio_id'] = _INPUT('convenio', 'int', 'post');
//	$dadosAgendamento['convenio_has_procedimento_has_tabela_tabela_id'] = _INPUT('tabela', 'int', 'post');
//	$dadosAgendamento['convenio_has_procedimento_has_tabela_procedimento_id'] = _INPUT('procedimento', 'int', 'post');
//
//if(isset($_POST['gravar_cad'])) {
//
//	$tmpStatus = statusById(confereStatus($dadosAgendamento));
//
//	if($tmpStatus == "Cancelado" ) {
//		if(insereAgendamento($dadosAgendamento)) {
//			alertaSucesso( "Cadastro feito com sucesso!");
//		} else {
//			alertaErro("Ocorreu algum erro! ");
//		}
//	} else {
//		if(confereExistenciaAgendamento($dadosAgendamento)) {
//				alertaErro("Horario de Agendamento ja existe! ");
//		} else {
//			if(insereAgendamento($dadosAgendamento)) {
//				alertaSucesso( "Cadastro feito com sucesso!");
//			} else {
//				alertaErro("Ocorreu algum erro! ");
//			}
//		}
//	}
//} else if(isset($_POST['gravar_edi'])) {
//
//	if(editarAgendamento($dadosAgendamento,$idAgendamento)) {
//		alertaSucesso( "Edição feita com sucesso!");
//	} else {
//		alertaErro("Ocorreu algum erro! ");
//	}
//} else {
//	include('view/ag_medica/agendamento.phtml');
//}
