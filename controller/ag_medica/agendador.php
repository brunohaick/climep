<?php

$idAgendamento = $_POST['kid'];
$clienteAgendamento = agendamentoById($idAgendamento);
$FulanoDeTal = buscaUsuarioPorId($clienteAgendamento['agendador_id']);

if(!empty($FulanoDeTal)) {
	echo $FulanoDeTal;
}
