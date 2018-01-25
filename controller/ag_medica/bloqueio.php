<?php

include('controller/ag_medica/horario.php');

if (isset($_POST['desbloqueio'])) {

	echo $button = "ag-desbloquear";
	echo $nomeButton = "Desbloquear";
	echo $nomeTitulo = "Desbloqueio";

	require('view/ag_medica/bloqueio.phtml');
}

$flag = (isset($_POST['flag'])) ? $_POST['flag'] : '';

if ($flag === 'Bloqueia') {
	$dadosBloqueio['medico_id'] = $_POST['medico_id'];
	$dadosBloqueio['agendador_id'] = $_SESSION['usuario']['id'];
	$dadosBloqueio['data_agendamento'] = converteData($_POST['data']);
	$dadosBloqueio['hora_chegada'] = $_POST['hora'];
	$dadosBloqueio['status_id'] = 10;
	$dadosBloqueio['convenio_has_procedimento_has_tabela_convenio_id'] = 1;
	$dadosBloqueio['convenio_has_procedimento_has_tabela_tabela_id'] = 2;
	$dadosBloqueio['convenio_has_procedimento_has_tabela_procedimento_id'] = 41;
	$antigo = confereExistenciaAgendamento($dadosBloqueio);
	if ($antigo == null) {
//		var_dump($dadosBloqueio);die;
		insereAgendamento($dadosBloqueio);
	} else {
		mysqli_set_charset(banco::$connection, 'utf8');
		$sql = "DELETE FROM agendamento WHERE
				hora_chegada = '" . $dadosBloqueio['hora_chegada'] . "'
				AND medico_id = '" . $dadosBloqueio['medico_id'] . "'";

		$query = mysqli_query($sql);

		$linha = mysqli_fetch_assoc($query);
	}
	var_dump($antigo);
} else if ($flag === 'Desbloqueia') {
	$dadosBloqueio['medico_id'] = $_POST['medico_id'];
	$dadosBloqueio['agendador_id'] = $_SESSION['usuario']['id'];
	$dadosBloqueio['data_agendamento'] = converteData($_POST['data']);
	$dadosBloqueio['hora_chegada'] = $_POST['hora'];
	$dadosBloqueio['status_id'] = 21;
	$dadosBloqueio['convenio_has_procedimento_has_tabela_convenio_id'] = 1;
	$dadosBloqueio['convenio_has_procedimento_has_tabela_tabela_id'] = 2;
	$dadosBloqueio['convenio_has_procedimento_has_tabela_procedimento_id'] = 41;
	$antigo = confereExistenciaAgendamento($dadosBloqueio);
	if ($antigo == null) {
		insereAgendamento($dadosBloqueio);
	} else {
		mysqli_set_charset(banco::$connection, 'utf8');
		$sql = "DELETE FROM agendamento WHERE
				hora_chegada = '" . $dadosBloqueio['hora_chegada'] . "'
				AND medico_id = '" . $dadosBloqueio['medico_id'] . "'";

		$query = mysqli_query($sql);

		$linha = mysqli_fetch_assoc($query);
	}
}



//	$horaInicio = _INPUT('hora_inicio','string','post');
//	$horaTermino = _INPUT('hora_termino','string','post');
//	$intervalo_hora_data = "15";
//	$hora_data = $horaInicio." ".$anoInicio."-".$mesInicio."-".$diaInicio;
//
//	$dadosBloqueio['medico_id'] = $_POST['medico_id'];
//	$dadosBloqueio['agendador_id'] = $_SESSION['usuario']['id'];
//	$dadosBloqueio['data_agendamento'] = converteData($dataAgenda);
//	$dadosBloqueio['status_id'] = 14;
//	$dadosBloqueio['convenio_has_procedimento_has_tabela_convenio_id'] = 1; 
//	$dadosBloqueio['convenio_has_procedimento_has_tabela_tabela_id'] = 2;
//	$dadosBloqueio['convenio_has_procedimento_has_tabela_procedimento_id'] = 41;
//
//if(isset($_POST['bloquear'])) {
//		for ($j = 0;; $j++) {
//			if (strtotime($horaInicio . " + " . ($intervalo_hora_data * $j) . "minutes") > strtotime($horaTermino)) {
//				break;
//			}
//			$dadosBloqueio['hora_chegada'] = date("H:i:s", strtotime($hora_data . " + " . ($intervalo_hora_data * $j) . "minutes"));
//
//			if(confereExistenciaAgendamento($dadosBloqueio)) {
//				$agExiste = 1;
//			} else {
//				if(insereAgendamento($dadosBloqueio)) {
//					$bloqueio = 1;
//				}
//			}
//			
//		}
//			if(isset($bloqueio)){
//				alertaSucesso( "Bloqueio feito com sucesso!");
//			} else if(isset($agExiste)) {
//				alertaErro("Horario de Agendamento ja existe! ");
//			} else {
//				alertaErro("Ocorreu algum erro ao tentar bloquear horario ! ");
//			}
//}
//
//if(isset($_POST['desbloquear'])) {
//		for ($j = 0;; $j++) {
//			if (strtotime($horaInicio . " + " . ($intervalo_hora_data * $j) . "minutes") > strtotime($horaTermino)) {
//				break;
//			}
//			$dadosBloqueio['hora_chegada'] = date("H:i:s", strtotime($hora_data . " + " . ($intervalo_hora_data * $j) . "minutes"));
//			$tmpStatus = statusById(confereStatus($dadosBloqueio));
//
//			if($tmpStatus == "Bloqueado"){
//				desbloqueiaAgendamento($dadosBloqueio);
//				$desbloqueio = 1;
//			}
//		}
//			if(isset($desbloqueio)) {
//				alertaSucesso( "Desbloqueio feito com sucesso!");
//			} else {
//				alertaErro("Horario não estava bloqueado ! ");
//			}
//}

