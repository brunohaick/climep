<?php

if (isset($_POST['flag'])) {
	if ($_POST['flag'] === 'setHorarioDeTrabalho') {
		$medico_ID = (isset($_POST['medicoId']) ? $_POST['medicoId'] : '');
		$mInicio = (isset($_POST['mInicio']) ? $_POST['mInicio'] : '');
		$mFim = (isset($_POST['mFim']) ? $_POST['mFim'] : '');
		$tInicio = (isset($_POST['tInicio']) ? $_POST['tInicio'] : '');
		$tFim = (isset($_POST['tFim']) ? $_POST['tFim'] : '');
		
		setHorarioDeTrabalho($medico_ID, $mInicio, $mFim, $tInicio, $tFim);
	} else if ($_POST['flag'] === 'pegaHorariosDeTrabalho') {
		$medico_ID = (isset($_POST['medicoId']) ? $_POST['medicoId'] : '');
		die(saidaJson(pegaHorariosDeTrabalho($medico_ID)));
	} else if ($_POST['flag'] === 'getMensagemPrivada') {
		$medico_ID = (isset($_POST['medicoId']) ? $_POST['medicoId'] : '');
		die(saidaJson(getMensagemPrivada($medico_ID)));
	} else if ($_POST['flag'] === 'setMensagemPrivada') {
		$medico_ID = (isset($_POST['medicoId']) ? $_POST['medicoId'] : '');
		$mensagem = (isset($_POST['mensagem']) ? $_POST['mensagem'] : '');
		setMensagemPrivada($medico_ID, $mensagem);
	} else if ($_POST['flag'] === 'pegaHorarios') {
		if ($_POST['cancelados'] === 'true')
			$cancel = true;
		else
			$cancel = false;
		$dia = converteData($_POST['data']);
		die(saidaJson(pegaHorarios($_POST['medicoId'], $_POST['horaMenor'], $_POST['horaMaior'], $cancel, $dia)));
	} else if ($_POST['flag'] === 'SalvaHorario') {
		mysqli_set_charset(banco::$connection, 'utf8');
		$medico_id = $_POST['medico_id'];
		$covenio_id = $_POST['covenio_id'];
		$tabela_id = $_POST['tabela_id'];
		$procedimento_id = $_POST['procedimento_id'];
		$hora = $_POST['hora'];
		$vobservacao = $_POST['observacao'];
		$nome = $_POST['nome'];
		$responsavel = $_POST['responsavel'];
		$contato = $_POST['contato'];
		$celular = $_POST['celular'];
		$status = $_POST['status'];
		$data = converteData($_POST['data']);
		$atendente_id = $_SESSION['usuario']['id'];

		die(SalvaHorario($medico_id, $covenio_id, $tabela_id, $procedimento_id, $hora, $vobservacao, $nome, $responsavel, $contato, $celular, $status, $data, $atendente_id));
	} else if ($_POST['flag'] === 'UpdateHorario') {
		mysqli_set_charset(banco::$connection, 'utf8');
		$medico_id = $_POST['medico_id'];
		$covenio_id = $_POST['covenio_id'];
		$tabela_id = $_POST['tabela_id'];
		$procedimento_id = $_POST['procedimento_id'];
		$hora = $_POST['hora'];
		$vobservacao = $_POST['observacao'];
		$nome = $_POST['nome'];
		$responsavel = $_POST['responsavel'];
		$contato = $_POST['contato'];
		$celular = $_POST['celular'];
		$status = $_POST['status'];
		$id = $_POST['id'];
		$data = converteData($_POST['data']);
		$atendente_id = $_SESSION['usuario']['id'];
		die(UpdateHorario($medico_id, $covenio_id, $tabela_id, $procedimento_id, $hora, $vobservacao, $nome, $responsavel, $contato, $celular, $status, $data, $atendente_id, $id));
	}
	exit;
}

include('controller/ag_medica/horario.php');

$idMedico = _INPUT('id', 'int', 'post');
if (isset($idMedico)) {
	$nomeMedico = buscaUsuarioPorId($idMedico);
	$agendamentos = buscaAgendamentos($idMedico);
}

include('view/ag_medica/index.phtml');
