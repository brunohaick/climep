//$(document).ready(function(){
//
///**
// *Cadastro de Agendamento. Formulario Agendamento.
// *Após o formulário ser submetido, envia-se todos os dados contidos para um
// * script em php para motar a query sql de cadastro de agendamento no banco.
// *
// *@author Marcus Dias
// *@date Criação: 16/09/2012
// *
// * @param
// *
// * @return
// *True em caso de sucesso e False em caso de Falha.
// */
//	$("button#ag-gravar").click(function() {
//
//		var cliente_id = "7";
//		var nome = $("#arc-horario-nome").val();
//		var responsavel = $("#arc-horario-resp").val();
//		var celular = $("#arc-horario-celular").val();
//		var contato = $("#arc-horario-contato").val();
//		var medico_id = $("#arc-horario-medicos option:selected").val();
//		var status_ag = $("#arc-horario-status option:selected").val();
//		var motivo = $("#arc-horario-motivo").val();
//		var tabela = $("#arc-horario-tabela option:selected").val();
//		var convenio = $("#arc-horario-convenio option:selected").val();
//		var procedimento = $("#arc-horario-servicos  option:selected").val();
//		var quantidade = $("#arc-horario-qtd").val();
//		var hr_chegada = $("#arc-horario-chegada option:selected").val();
//		var hr_atendimento = $("#arc-horario-atendimento option:selected").val();
//		var obs = $("#arc-horario-obs").val();
//		var gravar_cad = "Cadastrar";
//
//		$.post("index.php?module=agendamento&tmp=1",{id:cliente_id,nome:nome,responsavel:responsavel,celular:celular,contato:contato,medico_id:medico_id,status_ag:status_ag,motivo:motivo,tabela:tabela,convenio:convenio,procedimento:procedimento,quantidade:quantidade,hr_chegada:hr_chegada,hr_atendimento:hr_atendimento,obs:obs,gravar_cad:gravar_cad},function(result){
//			$("#ag-result").html(result);
//		});
//	});
//
///**
// *Edição de Agendamento. Formulario Agendamento.
// *Após o formulário ser submetido, envia-se todos os dados contidos para um
// * script em php para motar a query sql de edição de agendamento no banco.
// *
// *@author Marcus Dias
// *@date Criação: 16/09/2012
// *
// * @param
// *
// * @return
// *True em caso de sucesso e False em caso de Falha.
// */
//	$("button#ag-gravar_editar").click(function() {
//
//		var cliente_id = "7";
//		var nome = $("#arc-horario-nome").val();
//		var responsavel = $("#arc-horario-resp").val();
//		var celular = $("#arc-horario-celular").val();
//		var contato = $("#arc-horario-contato").val();
//		var medico_id = $("#arc-horario-medicos option:selected").val();
//		var status_ag = $("#arc-horario-status option:selected").val();
//		var motivo = $("#arc-horario-motivo").val();
//		var tabela = $("#arc-horario-tabela option:selected").val();
//		var convenio = $("#arc-horario-convenio option:selected").val();
//		var procedimento = $("#arc-horario-servicos  option:selected").val();
//		var quantidade = $("#arc-horario-qtd").val();
//		var hr_chegada = $("#arc-horario-chegada option:selected").val();
//		var hr_atendimento = $("#arc-horario-atendimento option:selected").val();
//		var obs = $("#arc-horario-obs").val();
//		var gravar_edi = "Editar";
//		//var date = $("#arc-horario-id").val();
//		var kid = $("#arc-horario-id").val();
//
//		$.post("index.php?module=agendamento&tmp=1",{id:cliente_id,kid:kid,nome:nome,responsavel:responsavel,celular:celular,contato:contato,medico_id:medico_id,status_ag:status_ag,motivo:motivo,tabela:tabela,convenio:convenio,procedimento:procedimento,quantidade:quantidade,hr_chegada:hr_chegada,hr_atendimento:hr_atendimento,obs:obs,gravar_edi:gravar_edi},function(result){
//			$("#ag-result").html(result);
//		});
//	});
//
//	
//});
//
//
//
///**
// * Não está sendo utilizado atualmente
// * Função para enviar o ID. 
// * Formulario é submetido sem necessidade de um submit.É usado o método onChange
// * Após o formulário ser submetido, envia-se todos os dados contidos para um
// * script em php para enviar a data da consulta para a página.
// *
// * @author Marcus Dias
// * @date Criação: 17/09/2012
// *
// * @param
// *
// * @return
// *	True em caso de sucesso e False em caso de Falha.
// *
// */
//
//function carregaDados() {
//		 id = $("#arc-horario-id").val();
//		$.post("index.php?module=agendamento&tmp=1",{id:id},function(result){
//		    $("#form-busca-usuario").html(result);
//		});
//}
//
//
///**
// * Função para enviar o ID do Agendamento
// * Formulario é submetido sem necessidade de um submit.É usado o método onClick
// * Após o formulário ser submetido, envia-se todos os dados contidos para um
// * script em php para mostrar os dados de algum agendamento no modal e
// * posteriormente edita-lo.
// *
// * @author Marcus Dias
// * @date Criação: 25/09/2012
// *
// * @param
// *
// * @return
// *	True em caso de sucesso e False em caso de Falha.
// *
// */
//function agendamento(idAgendamento) {
//	
//	var kid = idAgendamento;
//
//	$.post("index.php?module=agendamento&tmp=1",{kid:kid},function(result){
//	    $("#form-edicao-agendamento").html(result);
//		abrir_modal('boxes-acr-horario');
//
//	});
//
//}
//
//function agendador(id) {
//	
////	var kid = id;
////	$.post("index.php?module=agendador&tmp=1",{kid:kid},function(result){
////	    $("#agendador-result").html(result);
////
////	});
//
//}
