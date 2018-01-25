<?php

$data = isset($_POST['data']) ? converteData($_POST['data']) : date("Y-m-d");
$medicoID = isset($_GET['medico']) ? _INPUT('medico', 'int', 'get') : _INPUT('medico', 'int', 'post');
$SemanaDiaMesAno = explode('-', $data);
$SDMA = explode('-', date("N-M", mktime(0, 0, 0, $SemanaDiaMesAno[1], $SemanaDiaMesAno[2], $SemanaDiaMesAno[0])));

if (isset($_GET['PegaHorario'])) {
	$busca = buscaFilaDeEsperaDataMedico($data, $medicoID);
	foreach ($busca as $index => $valor) {
		$busca[$index]['cliente_nome'] = buscaNomeCliente($valor['cliente_id']);
		$busca[$index]['recepcionista_id'] = buscaUsuarioPorId($valor['recepcionista_id']);
	}
	die(saidaJson($busca));
} else if (isset($_GET['AtendeUsuario'])) {
	if ($_POST['atendido'] === 'true'){
		desatendeFilaDeEsperaMedico($_POST['idDaFila']);
		die('atendido');
	}
	else {
		$hora = date('H:i:s');
		atendeFileDeEsperaMedico($_POST['idDaFila'], $hora);
	}
	exit;
}

include('view/recepcao/salaEspera.phtml');

