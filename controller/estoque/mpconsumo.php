<?php
$nomeMaterial = (isset($_POST['material'])) ? _INPUT('material','string','post') : 'vacina'; // nome do tipo de material
$dataFim = (isset($_POST['dataFim'])) ? converteData(_INPUT('dataFim','string','post')) : date('Y-m-d');

$dataInicio = date("Y-m-d",strtotime("$dataFim - 6 days"));

$materiais = buscaMaterialMPConsumo($nomeMaterial, $dataInicio, $dataFim);

$diaSemana = date("N",strtotime($dataFim));
$dia_semana[$diaSemana];

switch($nomeMaterial) {
	case 'vacina':
		$selectVacina = "SELECTED";
		break;
	case 'triagem' :
		$selectTriagem = "SELECTED";
		break;
	case 'imunoterapia':
		$selectImuno = "SELECTED";
		break;
	default:
		$selectTodos = "SELECTED";
		break;
}

$_SESSION['impressaoMpConsumo']['dataInicio'] = $dataInicio;
$_SESSION['impressaoMpConsumo']['dataFim'] = $dataFim;

require('view/estoque/mpconsumo.phtml');

