<?php

/**
 * @file  index2.php
 * @brief Este arquivo é o ponto de entrada do modulo de impressão
 * @version 1.0
 * @date  Criação: 20/03/2013
 *
 * */
ob_start();
session_start();
$module = isset($_GET['module']) ? $_GET['module'] : '';

switch ($module) {

    case 'comprovante':
        require('view/pagespdf/comprovante.phtml');
        break;
    case 'extrato':
        require('view/pagespdf/extrato.phtml');
        break;
    case 'fichavacina':
        require('view/pagespdf/fichaVacina.phtml');
        break;
    case 'certificadovacina':
        require('pdf/fpdf/certificadovacinacao.php');
        break;
    case 'declaracao':
        require('pdf/fpdf/declaracaocomparecimento.php');
        break;
    case 'controlenovo':
        require('pdf/fpdf/controle.php');
        break;
    case 'imunodeficiencia':
        require('pdf/fpdf/imunodeficiencia.php');
        break;
    case 'laudotca':
        require('pdf/fpdf/icelular.php');
        break;
    case 'carta':
        require('pdf/fpdf/carta.php');
        break;
    case 'anamnese':
        require('pdf/fpdf/anamnese.php');
        break;
    case 'tcle':
        require('pdf/fpdf/tcle.php');
        break;
    case 'orcamento':
        require('pdf/fpdf/orcamento.php');
        break;
    case 'imunoterapia':
        require('pdf/fpdf/fichaimuno.php');
        break;
    case 'fichaatendimento':
        require('pdf/fpdf/fichaatendimento.php');
        break;
    case 'guia-tiss':
        require('pdf/fpdf/guiatiss.php');
        break;

    /* Pdfs de consulta */
    case 'resultadocoracaozinho':
        require('pdf/fpdf/pdfsconsulta/triagem-coracaozinho.php');
        break;
    case 'resultadolinguinha':
        require('pdf/fpdf/pdfsconsulta/triagem-linguinha.php');
        break;
    case 'resultadolinho':
        require('pdf/fpdf/pdfsconsulta/triagem-olhinho.php');
        break;
    case 'listagemmedicos':
        require('pdf/fpdf/pdfsconsulta/listagem_medicos_coracao.php');
        break;
    case 'prescricao':
        require('pdf/fpdf/pdfsconsulta/prescricao.php');
        break;
    case 'historico':
        require('pdf/fpdf/pdfsconsulta/historico.php');
        break;
    case 'atestado':
        require('pdf/fpdf/pdfsconsulta/atestado.php');
        break;
    case 'resultadoorelhinha2':
        require('pdf/fpdf/pdfsconsulta/triagem-orelhinha.php');
        break;
    case 'prestserv':
        require('pdf/fpdf/pdfsconsulta/prestserv.php');
        break;
    case 'testecutaneoAntigo':
        require('pdf/fpdf/pdfsconsulta/tca-alerginos.php');
        break;
    case 'testecutaneoNovo':
        require('pdf/fpdf/pdfsconsulta/tc-ng-alerginos.php');
        break;
}