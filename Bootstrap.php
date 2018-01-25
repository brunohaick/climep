<?php
/**
* @file  Bootstrap.php
* @brief Este arquivo é um parametro inicial de configurações e inclusões gerais do sistema
* @version 0.01
* @date  Criação: 05/09/2012
* @date  Alteração: 05/09/2012
* @todo 
*	@li retirar o array com o nome dos meses e dias da semana do arquivo
* 
**/

header("content-type:text/html; charset=utf-8");
ini_set('default_charset','UTF-8'); // Para o charset das páginas e

$dia_semana[7] = "Domingo";
$dia_semana[1] = "Segunda-Feira";
$dia_semana[2] = "Terça-Feira";
$dia_semana[3] = "Quarta-Feira";
$dia_semana[4] = "Quinta-Feira";
$dia_semana[5] = "Sexta-Feira";
$dia_semana[6] = "Sábado";
$mes_ano["Jan"] = "Janeiro";
$mes_ano["Feb"] = "Fevereiro";
$mes_ano["Mar"] = "Março";
$mes_ano["Apr"] = "Abril";
$mes_ano["May"] = "Maio";
$mes_ano["Jun"] = "Junho";
$mes_ano["Jul"] = "Julho";
$mes_ano["Aug"] = "Agosto";
$mes_ano["Sep"] = "Setembro";
$mes_ano["Oct"] = "Outubro";
$mes_ano["Nov"] = "Novembro";
$mes_ano["Dec"] = "Dezembro";


require('config.php');
require('model/banco.php');
require('model/funcGerais.php');
require('model/funcAlerta.php');
require('model/funcPDF.php');
require('model/utils.php');
/*
 * Carregando helper -> Mario Chapela
 */
require_once ('./helpers/loadHelpers.inc.php');
?>