$(document).ready(function() {

	var tipoForm = "cheque_busca";

	$.post("index.php?module=cheque&tmp=1",{tipoForm:tipoForm},function(result){

		/* Campos do formulário de Re-Impressão de Cheques */
		$('input:[name=cheque-dup-fornecedor]').typeahead({source: result.fornecedores, items:1115});

	},"json");

});

function buscaFornecedorChequePorCodigo() {

	var tipoForm = "busca_por_codigo";
	var cod = $('input:[name=cheque-dup-cod_fornecedor]').val();

	$.post("index.php?module=cheque&tmp=1",{tipoForm:tipoForm,cod:cod},function(result){
		$('input:[name=cheque-dup-fornecedor]').val(result);
	},"json");
	
}
