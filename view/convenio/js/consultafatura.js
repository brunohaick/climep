$(document).ready(function() {

	var tipoForm = "fatura_busca";

	$.post("index.php?module=consultafatura&tmp=1",{tipoForm:tipoForm},function(result){

		/* Campos do formulário de lancamento de duplicatas */

		$('input:[name=conv-insere-fatura-empresa]').typeahead({source: result.empresas, items:1115});
		$('input:[name=conv-insere-fatura-convenio]').typeahead({source: result.convenios, items:1115});
		$('input:[name=conv-fatura-modal-conta_corrente]').typeahead({source: result.contas, items:1115});
		$('input:[name=conv-fatura-modal-tipo_operacao]').typeahead({source: result.tipo_operacao, items:1115});

		/* Campos do formulário de consulta de duplicatas */

		$('input:[name=conv-consulta-fatura-empresa]').typeahead({source: result.consulta_empresas, items:1115});
		$('input:[name=conv-consulta-fatura-convenio]').typeahead({source: result.consulta_convenios, items:1115});
	},"json");

});

function selectRadio(classe) {
	var classe = classe.attr('class');
	var tt = $('.' + classe).children('th').children('input').attr('id');
	$("#" + tt).attr("checked", true);
}

function novaConfeccaofaturas() {
	$("div#consultafaturas").hide("slide", function() {
		$("div#confeccaofaturas").css("display", "block");
	});
}

function voltaConfeccaoFatura() {
	$("div#confeccaofaturas").hide("slide", function() {
		$("div#consultafaturas").css("display", "block");
	});
}

function buscaconsultafatura() {
	$("div#consultafaturas").hide("slide", function() {
		$("div#buscaconsultafatura").css("display", "block");
	});
}

function voltaConsultaFatura() {
	$("div#buscaconsultafatura").hide("slide", function() {
		$("div#consultafaturas").css("display", "block");
	});
}

function buscaFaturaConvenioPorCodigo(flag, form) {

	var tipoForm = "busca_por_codigo";
	var name = "";
	var cod = "";

	if(form == "insere_fatura") {
		name = "conv-insere-fatura";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();

		if(cod == "0" || cod == "00")
			return false;

	} else if(form == "consulta_fatura") {
		name = "conv-consulta-fatura";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();

	} else if(form == "consulta_fatura_modal") {
		name = "conv-fatura-modal";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();
	} else {
		return false;
	}

	$.post("index.php?module=consultafatura&tmp=1",{tipoForm:tipoForm,flag:flag,cod:cod},function(result){
		$('input:[name=' + name + '-'+ flag + ']').val(result);
	},"json");
	
}

function insereFatura() {

	var tipoForm = "insere_faturas";

	var empresa = $('input:[name=conv-insere-fatura-empresa]').val();
	var convenio = $('input:[name=conv-insere-fatura-convenio]').val();
	var data_vencimento = $('input:[name=conv-insere-fatura-data_vencimento]').val();
	var data_inicio = $('input:[name=conv-insere-fatura-data_inicio]').val();
	var data_fim = $('input:[name=conv-insere-fatura-data_fim]').val();

	if(empresa == "") {
		alert("Por Favor, informe a Empresa!");
		return false;
	} else if(convenio == "") {
		alert("Por Favor, informe o Convenio!");
		return false;
	} else if(data_vencimento == "") {
		alert("Por Favor, informe a Data de Vencimento!");
		return false;
	} else if(data_inicio == "") {
		alert("Por Favor, informe a Data Inicial!");
		return false;
	} else if(data_fim == "") {
		alert("Por Favor, informe a Data Final!");
		return false;
	}

	$.post("index.php?module=consultafatura&tmp=1",{
            tipoForm:tipoForm,
            empresa:empresa,
            convenio:convenio,
            data_vencimento:data_vencimento,
            data_inicio:data_inicio,
            data_fim:data_fim
        },function(result){
		if(result == 1) {
			alert("Operação Realizada com sucesso !");
//			cleanFormInsereFaturas();
		} else {
			alert("Ocorreu um ERRO ao realizar a Operação !");
		}
		//voltaInsereFatura();
	},"json");
}

function radioBaixaEstorno(status) {

	if(status == 'A'){
		$('a#conv--modal-estorno').show();
		$('a#baixacr-modal-baixa').hide();
	} else if(status == 'B') {
		$('a#baixacr-modal-estorno').hide();
		$('a#baixacr-modal-baixa').show();
	}

}

function consultaFatura() {

	var tipoForm = "lista_faturas";

	var empresa = $('input:[name=conv-consulta-fatura-empresa]').val();
	var convenio = $('input:[name=conv-consulta-fatura-convenio]').val();
	var data_inicio = $('input:[name=conv-consulta-fatura-data_inicio]').val();
	var data_fim = $('input:[name=conv-consulta-fatura-data_fim]').val();
	var status = $('select:[name=conv-consulta-fatura-status]').val();
	var selecionado = $('select:[name=conv-consulta-fatura-selecionado]').val();
	var ordenado = $('select:[name=conv-consulta-fatura-ordenado]').val();

	if(empresa == "") {
		alert("Por Favor, informe a Empresa!");
		return false;
	} else if(convenio == "") {
		alert("Por Favor, informe o Convenio!");
		return false;
	} else if(data_inicio == "") {
		alert("Por Favor, informe a Data Inicial!");
		return false;
	} else if(data_fim == "") {
		alert("Por Favor, informe a Data Final!");
		return false;
	}

	$.post("index.php?module=consultafatura&tmp=1",{tipoForm:tipoForm,empresa:empresa,convenio:convenio,data_inicio:data_inicio,data_fim:data_fim,status:status,selecionado:selecionado,ordenado:ordenado},function(result){

		var strhtml = '';

		for(var i = 0; i < result.length; i++){

			strhtml += '<tr name="consulta">';

				strhtml += '<td>';
					strhtml += '<input type="radio" onchange="radioBaixaEstorno(\''+ result[i]['nome_status'] +'\');" name="conv-consulta-fatura-option" value="' + result[i]['id']  + '"/>';
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['id'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['numero_nota'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['nome_convenio'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['nome_empresa'];
				strhtml += '</td>';
				//strhtml += '<td>';
				//	strhtml += '';
				//strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['data_faturamento'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['data_inicio'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['data_fim'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['taxa'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['faturado'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['glosa'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['valor_a_pagar'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['valor_pago'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['desconto'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['ajuste'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['liquido'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['data_vencimento'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['nome_pessoa'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['data_baixa'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['usuario_baixou'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['nome_status'];
				strhtml += '</td>';

			strhtml += '</tr>';
		}

		$('#conv-fatura-lista_faturas').html(strhtml);
		buscaconsultafatura();

	},"json");
}

function imprimeCapa() {

	var id = $('input:radio[name=conv-consulta-fatura-option]:checked').val();
	var convenio = $($($('input:radio[name=conv-consulta-fatura-option]:checked').parent().parent()).children()[3]).text();
	var valor = $($($('input:radio[name=conv-consulta-fatura-option]:checked').parent().parent()).children()[12]).text();

	if(id == null) {
		alert("Por favor selecione um item abaixo.");
		return false;
	}
	
	$.post("index.php?module=consultafatura&tmp=1",{
               id:id,
	       nomeConvenio:convenio,
	       valorPago:valor,
               tipoForm:'definirIdFaturaReciboConvenio'}
        ,function(result){
		window.open('index2.php?module=reciboconvenio', '_blank');
	},"json");
}

function nfseModal() {

	var id = $('input:radio[name=conv-consulta-fatura-option]:checked').val();

	if(id == null) {
		alert("Por favor selecione um item abaixo.");
		return false;
	}

	abrir_modal('emissaonotafiscal');

}

function baixaFaturaModal() {

	var tipoForm = "dados_fatura";

	var id = $('input:radio[name=conv-consulta-fatura-option]:checked').val();

	if(id == null) {
		alert("Por favor selecione um item abaixo.");
		return false;
	}

	$.post("index.php?module=consultafatura&tmp=1",{tipoForm:tipoForm,id:id},function(result){
		
		$('input:[name=conv-fatura-modal-numero_fatura]').val(result['id']);
		$('input:[name=conv-fatura-modal-faturado]').val(result['faturado']);
		$('input:[name=conv-fatura-modal-valor_a_pagar]').val(result['valor_a_pagar']);
		$('input:[name=conv-fatura-modal-valor_pago]').val(result['zero']);
		$('input:[name=conv-fatura-modal-desconto]').val(result['desconto']);
		$('input:[name=conv-fatura-modal-ajustes]').val(result['zero']);
		$('input:[name=conv-fatura-modal-liquido]').val(result['valor_a_pagar']);
		$('input:[name=conv-fatura-modal-data_baixa]').val(result['data_baixa']);
		$('input:[name=conv-fatura-modal-csll]').val(result['zero']);
		$('input:[name=conv-fatura-modal-csll2]').val(result['zero']);
		$('input:[name=conv-fatura-modal-cofins]').val(result['zero']);
		$('input:[name=conv-fatura-modal-cofins2]').val(result['zero']);
		$('input:[name=conv-fatura-modal-pis]').val(result['zero']);
		$('input:[name=conv-fatura-modal-pis2]').val(result['zero']);
		$('input:[name=conv-fatura-modal-ir]').val(result['zero']);
		$('input:[name=conv-fatura-modal-ir2]').val(result['zero']);
		$('input:[name=conv-fatura-modal-iss]').val(result['zero']);
		$('input:[name=conv-fatura-modal-iss2]').val(result['zero']);

		abrir_modal('modalbaixafatura');
	},"json");
}

function emitirNotaFiscal(){
	var id = $('input:radio[name=conv-consulta-fatura-option]:checked').val();
	var tipoForm = "enviarNFE";
        $.post("index.php?module=consultafatura&tmp=1",{
               id:id,
               tipoForm:tipoForm}
        ,function(result){
		if(result == 1)
			alert("Operação Realizada com Sucesso.");
		else
			alert("Ocorreu um erro ao executar a tarefa.");

		fechar_modal('modalbaixafatura');
		consultaFatura();
	},"json");
}

function baixaFaturaConvenio() {

	var tipoForm = "baixar_fatura";

	var id = $('input:radio[name=conv-consulta-fatura-option]:checked').val();

	var conta_corrente  = $('input:[name=conv-fatura-modal-conta_corrente]').val();
	var tipo_operacao = $('input:[name=conv-fatura-modal-tipo_operacao]').val();
	var faturado = $('input:[name=conv-fatura-modal-faturado]').val();
	var valor_a_pagar = $('input:[name=conv-fatura-modal-valor_a_pagar]').val();
	var valor_pago = $('input:[name=conv-fatura-modal-valor_pago]').val();
	var desconto = $('input:[name=conv-fatura-modal-desconto]').val();
	var ajustes = $('input:[name=conv-fatura-modal-ajustes]').val();
	var liquido = $('input:[name=conv-fatura-modal-liquido]').val();
	var data_baixa = $('input:[name=conv-fatura-modal-data_baixa]').val();
	var csll = $('input:[name=conv-fatura-modal-csll]').val();
	var csll2 = $('input:[name=conv-fatura-modal-csll2]').val();
	var cofins = $('input:[name=conv-fatura-modal-cofins]').val();
	var cofins2 = $('input:[name=conv-fatura-modal-cofins2]').val();
	var pis = $('input:[name=conv-fatura-modal-pis]').val();
	var pis2 = $('input:[name=conv-fatura-modal-pis2]').val();
	var ir = $('input:[name=conv-fatura-modal-ir]').val();
	var ir2= $('input:[name=conv-fatura-modal-ir2]').val();
	var iss = $('input:[name=conv-fatura-modal-iss]').val();
	var iss2= $('input:[name=conv-fatura-modal-iss2]').val();

	if(conta_corrente == "") {
		alert("Por Favor, informe a Conta Corrente!");
		return false;
	} else if(tipo_operacao == "") {
		alert("Por Favor, informe o Tipo de Operacao!");
		return false;
	} else if(faturado == "") {
		alert("Por Favor, informe o Valor Faturado!");
		return false;
	} else if(valor_a_pagar == "") {
		alert("Por Favor, informe o Valor a Pagar!");
		return false;
	} else if(valor_pago == "") {
		alert("Por Favor, informe o Valor Pago!");
		return false;
	} else if(desconto == "") {
		alert("Por Favor, informe o Valor de Desconto!");
		return false;
	} else if(ajustes == "") {
		alert("Por Favor, informe o Valor de Ajuste!");
		return false;
	} else if(liquido == "") {
		alert("Por Favor, informe o Valor Líquido!");
		return false;
	} else if(data_baixa == "") {
		alert("Por Favor, informe a Data da Baixa!");
		return false;
	}

	$.post("index.php?module=consultafatura&tmp=1",{
            tipoForm:tipoForm,
            id:id,
            conta_corrente:conta_corrente,
            tipo_operacao:tipo_operacao,
            faturado:faturado,
            valor_a_pagar:valor_a_pagar,
            valor_pago:valor_pago,
            desconto:desconto,
            ajustes:ajustes,
            liquido:liquido,
            data_baixa:data_baixa
        },function(result){
            if(result == 1)
                alert("Operação Realizada com Sucesso.");
            else
                alert("Ocorreu um erro ao executar a tarefa.");

            fechar_modal('modalbaixafatura');
            consultaFatura();
	},"json");

}
