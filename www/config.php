<?php

$config = array(
	'host' => '66.197.164.251',
	'banco' => 'climep',
	'usuario' => 'climep',
	'senha' => 'climep',
);

$msgs = array(
	'ERRO_GERAL'					=> 'Ocorreu algum Erro !',
	'ERRO_LOGIN'					=> 'Dados informados estão incorretos !',
	'ERRO_CLIENTE_CADASTRADO'		=> 'Cliente já Cadastrado !',
	'ERRO_PREENCHA_DADOS'			=> 'Preencha todos os campos obrigatórios !',
	'ERRO_CADASTRAR_PESSOA'			=> 'Erro ao Cadastrar uma Pessoa !',
	'SUCESSO_CLIENTE_CADASTRADO'	=> 'Cliente Cadastrado !',
	'SUCESSO_DEPENDENTE_CADASTRADO'	=> 'Dependente Cadastrado !',
);

/* Minimo numero de linhas para a fomracao da tabela de vacinas */
define('VACINA_MIN_NUM_LINHAS',				6);

$ArrVacina = array(
	"0"=>array("BCG 40mg","BCG id","BCG id (10mg)","BCG pc","Febre Amarela"),
	"1"=>array("Hepatite A ad","Hepatite A inf","Hepatite AB","Hepatite B ad","Hepatite B ad x2","Hepatite B- inf","DTPHibHB + Vip","HEXAc","DTPHibHB"),
	"2"=>array(
		"DTPHibHB + Vip","HEXAc","DTPHibHB","DTP","dTpa","DTPa","DUPLA ad","Tétano","DTP+VOP","dTpaVip","DTPaHib+VOP","DTPHib+VOP","PENTAc",
		"PENTAct-Hib","DTPaHib","DTPHib"
	),
	"3"=>array("DTPHibHB + Vip","HEXAc","DTP+VOP","dTpaVip","DTPaHib+VOP","DTPHib+VOP","PENTAc","PENTAct-Hib","Vip","VOP"),
	"4"=>array("DTPHibHB + Vip","HEXAc","DTPHibHB","DTPaHib+VOP","DTPHib+VOP","PENTAc","PENTAct-Hib","DTPaHib","DTPHib","Hemófilos b","MENINGO Hib"),
	"5"=>array("PNEUMO 10","PNEUMO 13","PNEUMO 23","PNEUMO 7"),
	"6"=>array("MENINGO Hib","MENINGO AC","MENINGO ACWY.c","MENINGO C conj"),
	"7"=>array("Caxumba","DUPLAVIRAL","Rubéola","Sarampo","TETRAVIRAL","TRIVIRAL","Varicela"),
	"8"=>array(
		"3ª Soro(IgG/IgM)","Gripe Ad ID","Gripe Ad TRI","Gripe DUPLA","Gripe Inf TRI (F)","Gripe Inf TRI",
		"Gripe MONO","Gripe TRI (g)","Gripe TRI (GSK)","Rotavírus GSK","Rotavírus MSD",
	),
	"9"=>array(
		"TP Master","HIV I e II","TP Plus","Imunodeficiência","TP Ampliado","T. Orelhinha","Teste do Olhinho",
		"T. Linguinha","T. Coraçãozinho","Reflexo Vermelho","HPV MSD","HPV GSK","IP anti-RH 300","Raiva",
		"BonViva EV","Aclasta","Mantoux","Mantoux leit","IP tétano","Mitsuda","Gesto Vacinal","HPV EMP.",
		"Injeção","TCA Imunidade Celular"
	),
);

$ArrProcsUnimed = array(
	"0"=>'4.03.16.548', //54-8
	"1"=>'4.03.16.521', //52-1
	"2"=>'4.03.16.01-7',
	"3"=>'4.03.01.24-9',
	"4"=>'4.03.01.81-8',
	"5"=>'40301672', //4.03.01.67-2
	"6"=>'4.03.07.83-2',
	"7"=>'4.03.01.974', //97-4
	"8"=>'40306488', //4.03.06.48-8
	"9"=>'4.03.02.059', //05-9
	"10"=>'4.03.04.353', //35-3
	"11"=>'4.03.07.735', //73-5
	"12"=>'40306623', //4.03.06.62-3
	"13"=>'4.03.06.674', //67-4
	"14"=>'4.03.07.700', //70-0
	"15"=>'4.03.07.182'
);

$ArrProcsUnimedOrel = array(
	"0"=>'4.01.03.463', //46-3
);
