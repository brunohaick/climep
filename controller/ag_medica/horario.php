<?php

if(isset($_POST['data'])) {
	$data = explode("/",$_POST['data']);
	$diaInicio = $data[0];
	$mesInicio = $data[1];
	$anoInicio = $data[2];
	$dataAgenda = _INPUT('data','int','post');


} else {
	$diaInicio = date('d');
	$mesInicio = date('m');
	$anoInicio = date('Y');
	$dataAgenda = date('d/m/Y');

}

$intervalo_hora_data = "15";
$hora_inicio_cal = "08:00";
$hora_fim_cal = "20:00";
$qtd_dias_cal = "1";

$hora_data = $hora_inicio_cal." ".$anoInicio."-".$mesInicio."-".$diaInicio;
