function inserirVacina() {

	var codigo = $('input[name=cad-vac-codigo]').val();
	var nome = $('input[name=cad-vac-nome]').val();
	var qtd_ml_dose = $('input[name=cad-vac-qtd_ml_dose]').val();
	var descricao = $('textarea[name=cad-vac-descricao]').val();
	var descricao_lembrete = $('textarea[name=cad-vac-descricao_lembrete]').val();
	var descricao_ingles = $('textarea[name=cad-vac-descricao_ingles]').val();
	var valor_dinheiro = $('input[name=cad-vac-valor_dinheiro]').val();
	var valor_cartao = $('input[name=cad-vac-valor_cartao]').val();
	var tipoForm = "insere-material-vacina";

	if(codigo == "") {
		alert("Por Favor, informe o Código da Vacina!");
		return false;
	} else if(nome == "") {
		alert("Por Favor, informe o Nome da Vacina que deseja Cadastrar!");
		return false;
	} else if(qtd_ml_dose  == "") {
		alert("Por Favor, informe a Quantidade de ML por Dose para a referida Vacina!");
		return false;
	} else if(descricao == "") {
		alert("Por Favor, informe a Descrição da Vacina!");
		return false;
	} else if(valor_dinheiro == "") {
		alert("Por Favor, o Valor em Dinheiro!");
		return false;
	} else if(valor_cartao == "") {
		alert("Por Favor, o Valor em Cartão!");
		return false;
	}

	$.post("index.php?module=cadvacina&tmp=1",{tipoForm:tipoForm,codigo:codigo,nome:nome,qtd_ml_dose:qtd_ml_dose,descricao:descricao,descricao_lembrete:descricao_lembrete,descricao_ingles:descricao_ingles,valor_dinheiro:valor_dinheiro,valor_cartao:valor_cartao},function(data) {

		if(data == 1) {
			alert("Operação Realizada com sucesso !");
			document.location.href="index.php?module=cadvacina";
		} else {
			alert("Ocorreu um erro ao realizar a Operação !");
		}

	});

}

/**
 * Inicia um novo Processo de encaminhamento de Procedimentos.
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 */

function botaoFechar() {
	document.location.href="index.php";
}
