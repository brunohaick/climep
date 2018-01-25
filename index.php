<?php

if (!function_exists('bcadd')) {
    echo "A biblioteca bcmath nao esta habilitada.<br />\n";
    die();
}
error_reporting(E_ALL);
//error_reporting(E_ALL); //E_ERROR E_ALL
/**
 * @file  index.php
 * @brief Este arquivo é o ponto de entrada do programa
 * @version 0.01
 * @date  Criação: 05/09/2012
 * @todo 
 * 	@li documentar o software
 * 	@li separar o sistema por modulos
 * 
 * */
ob_start();
session_start();

include('Bootstrap.php');
$con = conectar();

$module = isset($_GET['module']) ? $_GET['module'] : '';

if (!isset($_GET['tmp'])) {
    include_once 'template/header.phtml';
}

/**
 * Será habilitado assim que o sistema tiver completo! Enquanto estiver em fase 
 * de desenvolvimento permanecerá desabilitado.
 * @author : Marcus Dias
 * @date : 13/09/2012
 */
/*
  if(!isset($_SESSION['usuario'])) {
  ?>
  <script type="text/javascript" src="view/recepcao/js/logar.js"></script>
  <!--
  /*
 * Esse script utils.js deve ser incluso 
 * em algum mas não aki no index, já que 
 * o mesmo atrapalha o modulo de 
 * imunoterapia.
 *
  -->

  <!-- <script type="text/javascript" src="view/js/utils.js"></script> -->


  <?php
  $module = 'login';
  }
 */
//$array = ACL($_SESSION['usuario']['id']);
//$permissaoConcedida = 0;
//foreach($array as $permissao){
//	if($permissao['modulo'] == $module){
//		$permissaoConcedida = 1;
//	}
//}
//if($module == 'login' || $module == '' || $module == 'home'){ // modulos que podem ser acessados sem estar logado
//		$permissaoConcedida = 1;
//
//}
//if($permissaoConcedida == 0){
//	$module = 'home';
//	echo "Sem Permissão";
//}

switch ($module) {

    case 'kk':
        include('cheque.phtml');
        break;

    case 'mudarsenha':
        include('controller/mudarSenha.php');
        break;
    case 'modulos':
        include('controller/modulos/modulos.php');
        break;
    case 'imunoterapia':
        include('controller/recepcao/imunoterapia.php');
        break;
    case 'ag_medica':
        include('controller/ag_medica/index.php');
        break;
    case 'consultas':
    case 'buscaTextoCID' :

        include('controller/consultas/consultas.php');
        break;
    case 'caixa':
        include('controller/caixa/caixa.php');
        break;
    case 'agendamento':
        include('controller/ag_medica/agendamento.php');
        break;
    case 'agendador':
        include('controller/ag_medica/agendador.php');
        break;
    case 'bloqueio':
        include('controller/ag_medica/bloqueio.php');
        break;
    case 'sala_espera':
        include('controller/recepcao/salaEspera.php');
        break;
    case 'entradanf':
        include('controller/estoque/entradanf.php');
        break;
    case 'mpconsumo':
        include('controller/estoque/mpconsumo.php');
        break;
    case 'ultentradas':
        include('controller/estoque/ultentradas.php');
        break;
    case 'saldoestoque':
        include('controller/estoque/saldoestoque.php');
        break;
    case 'transfvacina':
        include('controller/estoque/transferenciavacina.php');
        break;
    case 'cadfornecedor':
        include('controller/estoque/cadfornecedor.php');
        break;
    case 'insere_procedimento':
        include('controller/recepcao/insere-procedimento.php');
        break;
    case 'guia_tiss':
        include('controller/recepcao/guiatiss.php');
        break;
    case 'cadvacina':
        include('controller/estoque/cadvacina.php');
        break;
    case 'historicoestoque':
        include('controller/estoque/historicoestoque.php');
        break;
    case 'historicoentrada':
        include('controller/estoque/historicoentrada.php');
        break;
    case 'detalhamentolote':
        include('controller/estoque/detalhamentolote.php');
        break;
    case 'testesRecepcao':
        include('controller/recepcao/testes.php');
        break;

    /*
     * Cadastros
     */
    case 'cadastro':
        include('controller/recepcao/cadastroPessoa.php');
        break;
    case 'encaminhamentopro':
        include('controller/recepcao/encaminhamentopro.php');
        break;
    case 'cadastro_medico':
        include('controller/recepcao/cadastroMedico.php');
        break;
    case 'cadastro_medico_assistente':
        include('controller/recepcao/cadastroMedicoAssistente.php');
        break;
    case 'cadastro_exame':
        include('controller/recepcao/cadastroExame.php');
        break;
    case 'cadastro_convenio':
        include('controller/recepcao/cadastroConvenio.php');
        break;
    case 'cadastro_teste':
        include('controller/recepcao/cadastroTeste.php');
        break;
    case 'cadastro_especialidade':
        include('controller/recepcao/cadastroEspecialidade.php');
        break;

    /*
     * Edits
     */
    case 'editar':
        include('controller/recepcao/editaPessoa.php');
        break;
    case 'vacina':
        include('controller/recepcao/fichaVacina.php');
        break;
    case 'vacinaEditar':
        include('controller/recepcao/fichaVacinaEditar.php');
        break;
    case 'controle_imunoterapia':
        include('controller/recepcao/controleImunoterapia.php');
        break;
    case 'login':
        include('controller/login.php');
        break;
    case 'logout':
        include('controller/logout.php');
        break;
    case 'pesquisa':
        include('controller/recepcao/pesquisa.php');
        break;
    case 'cadastro_dependente':
        include('controller/recepcao/cadastroDependente.php');
        break;
    case 'edita_dependente':
        include('controller/recepcao/editaDependente.php');
        break;

    /* filtro de campos */
    case 'filtro_select_tv':
        include('view/estoque/filtroSelectTransfVacina.phtml');
        break;
    case 'filtro_select_agmedica':
        include('view/ag_medica/filtroSelect.phtml');
        break;
    case 'filtro_select_guiatiss':
        include('view/recepcao/filtroSelect.phtml');
        break;
    case 'filtroPrescricoes':
        include('view/recepcao/filtroSelect.phtml');
        break;

    /* modulo financeiro */

    case 'duplicata':
        include('controller/financeiro/duplicatas.php');
        break;
    case 'duplicatas':
        include('view/financeiro/duplicatas.phtml');
        break;
    case 'baixacp':
        include('controller/financeiro/baixacp.php');
        break;
    case 'cheque':
        include('controller/financeiro/cheque.php');
        break;
    case 'faturas':
        include('controller/financeiro/faturas.php');
        break;
    case 'custodia':
        include('view/financeiro/custodia.phtml');
        break;
    case 'baixacr':
        include('controller/financeiro/baixacr.php');
        break;
    case 'contabil':
        include('view/financeiro/contabil.phtml');
        break;
    case 'extrato':
        include('controller/financeiro/extrato.php');
        break;
    case 'menufinanceiro':
        include('view/financeiro/menufinanceiro.phtml');
        break;
    case 'mapas':
        include('controller/financeiro/mapas.php');
        break;
    case 'lancamentos':
        include('controller/financeiro/lancamentos.php');
        break;

    /* modulo convenio */

    case 'menuconvenio':
        include('view/convenio/menuconvenio.phtml');
        break;

    case 'consultafatura':
        include('controller/convenio/consultafatura.php');
        break;

    case 'producaotiss':
        include('controller/convenio/producaotiss.php');
        break;
    
    case 'envioxmltiss':
        include('controller/convenio/envioxmltiss.php');
        break;

    case 'consultaconvenio':
        include('controller/convenio/consultaconvenio.php');
        break;

    case 'analiseconvenio':
        include('view/convenio/analiseconvenio.phtml');
        break;

    case 'consultarecebimento':
        include('view/convenio/consultarecebimento.phtml');
        break;

    case 'gerarxml':
        include('view/convenio/gerarxml.phtml');
        break;

    /* modulo de Impressao */

    case 'impressao':
        include('controller/impressao/impressao.php');
        break;

    /* modulo template */
    case 'fila_espera_vacina' :
        include('controller/template/fila_espera.php');
        break;

    /* pesquisa */
    case 'pesquisaMatriculaTitular' :
        include('controller/recepcao/pesquisa.php');
        break;
    default:

        include('template/home.phtml');
        break;
}

if (!isset($_GET['tmp'])) {
    include('template/footer.phtml');
}

desconectar($con);
