<?php

$dataInicio = isset($_POST['dataInicio']) ? converteData(_INPUT('dataInicio','string','post')) :  date("Y-m-d"); 
$dataFim = isset($_POST['dataFim']) ? converteData(_INPUT('dataFim','string','post')) :  date("Y-m-d"); 
$materialPorLote = movimentacaoPorLoteEntrada($dataInicio,$dataFim);

$data_explode = explode('-',$dataFim);
$num_dias_mes = cal_days_in_month(CAL_GREGORIAN, $data_explode[1], $data_explode[0]);
$dataInicioMes = $data_explode[0]."-".$data_explode[1]."-01";
$dataFimMes = $data_explode[0]."-".$data_explode[1]."-".$num_dias_mes;

if(isset($_POST['submit'])) {  
	$idMaterial = _INPUT('idMaterial','string','post');
	$idLote = _INPUT('idLote','string','post');
	$consumoLote = estoqueMaterialPorLote($idMaterial,$idLote,$dataInicioMes,$dataFimMes,'consumoArray');
}

include('view/estoque/detalhamentolote.phtml');
