<?php
/**
* @file  index2.php
* @brief Este arquivo é o ponto de entrada do modulo de impressão
* @version 0.01
* @date  Criação: 20/3/2013
*
**/

ob_start();
session_start();

//print_r($_SESSION);

$module = isset($_GET['module']) ? $_GET['module'] : '';

switch ($module) {

	case 'comprovante':
		include('view/pagespdf/comprovante.phtml');
		break;
	case 'extrato':
		include('view/pagespdf/extrato.phtml');
		break;
	case 'fichavacina':
		include('fichaVacina.phtml');
		//include('view/pagespdf/fichaVacina.phtml');
		break;
	case 'certificadovacina':
		include('pdf/fpdf/certificadovacinacao.php');
		break;
	case 'declaracao':
		include('pdf/fpdf/declaracaocomparecimento.php');
		break;
	case 'controlenovo':
		include('pdf/fpdf/controle.php');
		break;
}
