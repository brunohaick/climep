$(document).ready(function() {

	var tipoForm = "lancamentos-busca";

	$.post("index.php?module=lancamentos&tmp=1",{ tipoForm:tipoForm },function(result){

		/* Campos do formulário de busca para baixa de duplicatas */

		$('input:[name=lancamentos-tipo_operacao]').typeahead({source: result.tipos, items:1115});
		$('input:[name=lancamentos-conta_corrente]').typeahead({source: result.contas, items:1115});

	},"json");

});

function buscaPorCodigoLanc(flag, form) {

	var tipoForm = "busca_por_codigo";
	var name = "";

	if(form == "lancamentos") {
		name = "lancamentos";
	} 
	//else if(form == "baixa-modal") {
	//	name = "baixa-dup-modal";
	//}

	var cod = $('input:[name=' + name + '-cod_'+ flag +']').val();

	$.post("index.php?module=lancamentos&tmp=1",{ tipoForm:tipoForm, flag:flag, cod:cod },function(result){
		$('input:[name=' + name + '-'+ flag + ']').val(result);
	},"json");

}

function insereLancamentos() {

	var tipoForm = "insere_lancamentos";

	var data_operacao = $('input:[name=lancamentos-data_operacao]').val();
	var conta_corrente = $('input:[name=lancamentos-conta_corrente]').val();
	var tipo_operacao = $('input:[name=lancamentos-tipo_operacao]').val();
	var numero_documento = $('input:[name=lancamentos-numero_documento]').val();
	var valor = $('input:[name=lancamentos-valor]').val();
	var observacao = $('input:[name=lancamentos-observacao]').val();

	if(data_operacao == "") {
		alert("Por Favor, informe a Data de Opercação!");
		return false;
	} else if(conta_corrente == "") {
		alert("Por Favor, informe a Conta Corrente!");
		return false;
	} else if(tipo_operacao == "") {
		alert("Por Favor, informe o TIpo de Operecao!");
		return false;
	} else if(numero_documento == "") {
		alert("Por Favor, informe o numero de documento!");
		return false;
	} else if(valor == "") {
		alert("Por Favor, informe o valorr!");
		return false;
	}

	var confirma = confirm('Confirma o Lancamento ?');

	if(confirma) {

		$.post("index.php?module=lancamentos&tmp=1",{tipoForm:tipoForm,data_operacao:data_operacao,conta_corrente:conta_corrente,tipo_operacao:tipo_operacao,numero_documento:numero_documento,valor:valor,observacao:observacao},function(result){

		if(result == 1) {
			alert("Operação Realizada com sucesso !");
			cleanFormInsereLancamentos();
		} else {
			alert("Ocorreu um ERRO ao realizar a Operação !");
		}

		},"json");
	}

}

function cleanFormInsereLancamentos() {

	$('input:[name=lancamentos-data_operacao]').val("");
	$('input:[name=lancamentos-conta_corrente]').val("");
	$('input:[name=lancamentos-cod_conta_corrente]').val("");
	$('input:[name=lancamentos-tipo_operacao]').val("");
	$('input:[name=lancamentos-cod_tipo_operacao]').val("");
	$('input:[name=lancamentos-numero_documento]').val("");
	$('input:[name=lancamentos-valor]').val("");
	$('textarea:[name=lancamentos-observacao]').val("");

}

function escolherOperacao(nome) {

	$('input:[name=lancamentos-tipo_operacao]').val(nome);
	fechar_modal('modalcadastro');
}
