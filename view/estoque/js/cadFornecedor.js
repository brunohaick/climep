$(document).ready(function() {

	var tipoForm = "busca_plano_contas";

	$.post("index.php?module=cadfornecedor&tmp=1",{tipoForm:tipoForm},function(result){

		/* Campos do formulário de cadastro de fornecedores */

		$('input:[name=cad-for-plano_contas]').typeahead({source: result.plano_contas, items:1115});
	},"json");

});

function inserirFornecedor() {

	var nome = $('input[name=cad-for-nome]').val();
	var cnpj = $('input[name=cad-for-cnpj]').val();
	var descricao = $('textarea[name=cad-for-descricao]').val();
	var endereco = $('input[name=cad-for-endereco]').val();
	var cidade = $('input[name=cad-for-cidade]').val();
	var estado = $('input[name=cad-for-estado]').val();
	var pais = $('input[name=cad-for-pais]').val();
	var telefone = $('input[name=cad-for-telefone]').val();
	var fax = $('input[name=cad-for-fax]').val();
	var contato = $('input[name=cad-for-contato]').val();
	var email = $('input[name=cad-for-email]').val();
	var site = $('input[name=cad-for-site]').val();
	var plano_contas = $('input[name=cad-for-plano_contas]').val();
	var tipoForm = "insere-fornecedor";

	if(nome == "") {
		alert("Por Favor, informe o Nome do Fornecedor que deseja Cadastrar!");
		return false;
	} else if(cnpj  == "") {
		alert("Por Favor, informe o CNPJ!");
		return false;
	} else if(descricao == "") {
		alert("Por Favor, informe uma Descrição!");
		return false;
	} else if(endereco == "") {
		alert("Por Favor, Informe um Endereço Válido!");
		return false;
	} else if(cidade == "") {
		alert("Por Favor, Informe a Cidade!");
		return false;
	} else if(estado == "") {
		alert("Por Favor, informe o Estado!");
		return false;
	} else if(pais == "") {
		alert("Por Favor, informe o País!");
		return false;
	} else if(telefone == "") {
		alert("Por Favor, informe um telefone!");
		return false;
	} else if(email == "") {
		alert("Por Favor, informe um Email!");
		return false;
	} else if(plano_contas == "") {
		alert("Por Favor, Informe o Plano de Contas!");
		return false;
	}

	if(!IsEmail(email)) {
		alert("Insira um email Válido!");
		return false;
	}

	$.post("index.php?module=cadfornecedor&tmp=1",{tipoForm:tipoForm,nome:nome,cnpj:cnpj,descricao:descricao,endereco:endereco,cidade:cidade,estado:estado,pais:pais,telefone:telefone,fax:fax,contato:contato,email:email,site:site,plano_contas:plano_contas},function(data) {
		if(data == 1) {
			alert("Operação Realizada com sucesso !");
			cleanCadFornecedoresForm();
		} else {
			alert("Ocorreu um erro ao realizar a Operação !");
		}
		cleanCadFornecedoresForm();
	});

}

function buscaPlanoContasPorCodigo() {

	var tipoForm = "busca_por_codigo";
	var cod = $('input:[name=cad-for-cod_plano_contas]').val();

	$.post("index.php?module=cadfornecedor&tmp=1",{tipoForm:tipoForm,cod:cod},function(result){
		$('input:[name=cad-for-plano_contas]').val(result);
	},"json");
	
}

function cleanCadFornecedoresForm() {

	$('input[name=cad-for-nome]').val("");
	$('input[name=cad-for-cnpj]').val("");
	$('textarea[name=cad-for-descricao]').val("");
	$('input[name=cad-for-endereco]').val("");
	$('input[name=cad-for-cidade]').val("");
	$('input[name=cad-for-estado]').val("");
	$('input[name=cad-for-pais]').val("");
	$('input[name=cad-for-telefone]').val("");
	$('input[name=cad-for-fax]').val("");
	$('input[name=cad-for-contato]').val("");
	$('input[name=cad-for-email]').val("");
	$('input[name=cad-for-site]').val("");
	$('input[name=cad-for-plano_contas]').val("");
}
