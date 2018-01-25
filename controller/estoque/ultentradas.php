<?php

$idMaterial = _INPUT('id','int','post');
$dataInicio = converteData(_INPUT('dataInicio','string','post'));
$dataFim = converteData(_INPUT('dataFim','string','post'));
$soVacina = _INPUT('soVacina','string','post');
$option = _INPUT('option','string','post');
$selectOption = _INPUT('selectOption','string','post');

switch($option){
	case 'entrada':
		$checkEntrada = "CHECKED";
		$materiais = ultEntradasEntrada($idMaterial,$dataInicio,$dataFim);
		break;
	case 'ult6entradas':
		$check6entradas = "CHECKED";
		$auxMateriais = ultEntradasUlt6Entradas($idMaterial,$dataInicio,$dataFim,'aux');
		foreach($auxMateriais as $produto){
			$materiais[] = ultEntradasUlt6Entradas($produto['id_material'],$dataInicio,$dataFim);
		}
		break;
	case 'fluxo-vacinas':
		$checkFluxo = "CHECKED";
		if($idMaterial != '') {
			$materiais = ultEntradasFluxoVacinas($idMaterial,$dataInicio,$dataFim);
		} else {
			$materiais = ultEntradasFluxoVacinas($idMaterial,$dataInicio,$dataFim,$selectOption);
		}
		break;
	default:
		$checkEntrada = "CHECKED";
		break;
}
//print_r($materiais);
switch($selectOption){
	case 'vacina':
		$selectVacina = "SELECTED";
		break;
	case 'imuno':
		$selectImuno = "SELECTED";
		break;
	case 'triagem':
		$selectTriagem = "SELECTED";
		break;
	case 'imunidade':
		$selectImunidade = "SELECTED";
		break;
}

include('view/estoque/ultentradas.phtml');
