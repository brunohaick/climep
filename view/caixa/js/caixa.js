$(document).ready(function() {

	$('tr:even[name=table-color]').css('background', '#D3D6FF');
	$('tr:odd[name=table-color]').css('background', '#FFF');

	valor = 0;

	$('body').keydown(function(event) {
		if (event.which == 113) {
			alert('Apertou F2');
		}
		if (event.which == 114) {
			alert('Apertou F3');
		}
	});

});


// funcao utilizada quando o usuario clica em um item na fila de espera
function carregaDadosFilaEspera(id) {
	var matricula = $("#fila-caixa-" + id + "-mat").html();
	var flag1 = "buscaDadosRecibo";
	var flag2 = "preencheServicos";




	//console.log("#fila-caixa-" + id + "-valor");
	$.post(
			'index.php?module=editar&tmp=1', {id: matricula, flag: flag1},
	function(result) {
		$("#id-fila-espera-atual").val(id);
		//	$("#convenio-caixa option[value='" + result['convenio_id'] + "']").prop('selected', true);
		$("#nome-cliente-recibo").val(result['nome']);
		$("#cpf-cliente-recibo").val(result['nome']);
		$("#cep-cliente-recibo").val(result['cep']);
		$("#endereco-cliente-recibo").val(result['endereco']);
		$("#bairro-cliente-recibo").val(result['bairro']);
		$("#cidade-cliente-recibo").val(result['cidade']);
		$("#email-cliente-recibo").val(result['email']);
	}, "json"
			);


	$.post('index.php?module=caixa&tmp=1', {id: id, flag: flag2},
	function(result) {
		servicos = result['servicos'];

		formas_pagamento = result['formas_pagamento'];

		// o id da forma de pagamento é referente ao id da 'atividade do
		// servico' prestado
		for (var i = 0; i < result['servicos'].length; i++) {
			select = "<select class='select_form_pag' id='select_form_pag_" + servicos[i]['controle_material'] /* controle_material = idServico??? */ + "' onChange=\"alteracaoFormaPagamento('" + servicos[i]['controle_material'] + "','" + result['servicos'].length + "')\">";
			for (j = 0; j < formas_pagamento.length; j++) {
				if (formas_pagamento[j]['id'] == 5) {
					select += "<option selected value='" + formas_pagamento[j]['id'] + "'>" + formas_pagamento[j]['nome'] + "</option>";
				} else {
					select += "<option value='" + formas_pagamento[j]['id'] + "'>" + formas_pagamento[j]['nome'] + "</option>";
				}
			}
			select += "</select>";

//				 $("#select_form_pag_"+servicos[i]['controle_material']+" option[value='" + servicos[i]['forma_pagamento_id'] + "']").prop('selected', true);
			html = "<tr name='table-color' class='dayhead '>";
			html += "<th align='center' >" + servicos[i]['controle_status'] /* antigo Flag*/ + "</th>";
			html += "<th align='center' >" + servicos[i]['cliente_membro'] /* antigo id */ + "</th>";
			html += "<th align='center' >" + servicos[i]['cliente_nome'] /* antigo nomePessoa */ + "</th>";
			html += "<th align='center' >" + servicos[i]['cliente_nascimento'] /* antigo data_nascimento */ + "</th>";
			html += "<th align='center' id='servico_serv_realiz_" + servicos[i]['controle_material'] /* antigo controle_material */ + "'>" + servicos[i]['material_nome'] /* antigo nome */ + "</th>";
			html += "<th align='center' class='valor_serv_realiz' id='valor_serv_realiz_" + servicos[i]['controle_material'] + "' >" + servicos[i]['material_preco_cartao'] + "</th>";
			html += "<th align='center' >" + select + "</th>";
			html += "<th style='display:none'  id='referencia_serv_realiz_" + servicos[i]['controle_material'] + "'>" + servicos[i]['id_referencia'] /* antigo id_referencia */ + "</th>";
			html += "</tr>";


			valor += parseFloat(servicos[i]['material_preco_cartao'].substr(2).replace(".", "").replace(",", "."));
			if (i == 0) {
				$("#tbody-CaixaServicosArealizar").html(html);
			} else {
				$("#tbody-CaixaServicosArealizar").append(html);
			}
		}


		atualizaDadosDoPagamento();

	}, "json");

	$("#valorNota-recibo").val($("#fila-caixa-" + id + "-valor").html());
	$("#nome-completo").html($("#fila-caixa-" + id + "-nome").html());
	$("#nome-matricula").html($("#fila-caixa-" + id + "-mat").html() + "<br/> R$" + valor.toString());
	//valorys

}

// funcao utilizada quando um usuario altera a forma de pagamento na aba de um item a
// ser realizado agora
function alteracaoFormaPagamento(indiceLinha) {
	var tmp = parseFloat(0);

	var addValor = "";


	var idOptionSelect = $("#select_form_pag_" + indiceLinha).val();
	// caso seja do tipo procedimento, nao teremos alteração no valor
	if ($("#servico_serv_realiz_" + indiceLinha).html() == "procedimento") {

	} else if (idOptionSelect == 1 || idOptionSelect == 2 || idOptionSelect == 6) {
		//Avista (dinheiro / cheque-dia / cartao de debito )
		var flag = "buscaPreco";
		var tipo = 1; // avista
		var categoriaVenda = $("#servico_serv_realiz_" + indiceLinha).html();
		var idReferencia = $("#referencia_serv_realiz_" + indiceLinha).html();
		$.post(
				'index.php?module=caixa&tmp=1',
				{flag: flag, tipo: tipo, categoriaVenda: categoriaVenda, idReferencia: idReferencia},
		function(data) {
			addValor = "R$" + data;
			$("#valor_serv_realiz_" + indiceLinha).html(addValor);

		}, "json"
				);

		// cartao de crédito / cheque-pre
	} else if (idOptionSelect == 5 || idOptionSelect == 4) {
		var flag = "buscaPreco";
		var tipo = 2; // aprazo
		var categoriaVenda = $("#servico_serv_realiz_" + indiceLinha).html();
		var idReferencia = $("#referencia_serv_realiz_" + indiceLinha).html();
		$.post(
				'index.php?module=caixa&tmp=1',
				{flag: flag, tipo: tipo, categoriaVenda: categoriaVenda, idReferencia: idReferencia},
		function(data) {
			addValor = "R$" + data;
			//alert("Valor" + addValor);
			$("#valor_serv_realiz_" + indiceLinha).html(addValor);
		}, "json"
				);

		// convenio
	} else if (idOptionSelect == 7) {
		var flag = "buscaPreco";
		var tipo = 2; // aprazo
		var categoriaVenda = $("#servico_serv_realiz_" + indiceLinha).html();
		var idReferencia = $("#referencia_serv_realiz_" + indiceLinha).html();
		$.post(
				'index.php?module=caixa&tmp=1',
				{flag: flag, tipo: tipo, categoriaVenda: categoriaVenda, idReferencia: idReferencia},
		function(data) {
			addValor = "R$" + data;
			//alert("Valor" + addValor);
			$("#valor_serv_realiz_" + indiceLinha).html(addValor);
		}, "json"
				);

		//cortesia
	} else if (idOptionSelect == 8) {
		addValor = "R$0,00";
		//alert("Valor" + addValor);
		$("#valor_serv_realiz_" + indiceLinha).html(addValor);

		//desconto
	} else if (idOptionSelect == 10) {
		var flag = "buscaPreco";
		var tipo = 2; // aprazo
		var categoriaVenda = $("#servico_serv_realiz_" + indiceLinha).html();
		var idReferencia = $("#referencia_serv_realiz_" + indiceLinha).html();
		$.post(
				'index.php?module=caixa&tmp=1',
				{flag: flag, tipo: tipo, categoriaVenda: categoriaVenda, idReferencia: idReferencia},
		function(data) {
			addValor = "R$" + data;
			$("#valor_serv_realiz_" + indiceLinha).html(addValor);
		}, "json"
				);

	}


	atualizaDadosDoPagamento();

}

function atualizaDadosDoPagamento() {
	var prev1 = parseFloat(0);
	var prev2 = parseFloat(0);
	var prev3 = parseFloat(0);
	var prev4 = parseFloat(0);
	var prev5 = parseFloat(0);
	var prev6 = parseFloat(0);
	var prev7 = parseFloat(0);
	var prev8 = parseFloat(0);
	var prev9 = parseFloat(0);
	var prev10 = parseFloat(0);

	$(".select_form_pag").each(function() {
		esc = $(this).attr('id');
		esc = esc.split("_");
		esc = esc.pop();

		var tmp = $("#valor_serv_realiz_" + esc).html();
		tmp = tmp.substr(2);
		tmp = tmp.replace(",", ".");
		tmp = parseFloat(tmp);

		var x = $("#select_form_pag_" + esc).val();

		//alert(tmp);

		switch (x) {
			case '1':
				prev1 += tmp + prev1;
				prev1 = parseFloat(prev1);
				break;
			case '2':
				prev2 = tmp + prev2;
				prev2 = parseFloat(prev2);
				break;
			case '3':
				prev3 = tmp + prev3;
				prev3 = parseFloat(prev3);
				break;
			case '4':
				prev4 = tmp + prev4;
				prev4 = parseFloat(prev4);
				break;
			case '5':
				prev5 = tmp + prev5;
				prev5 = parseFloat(prev5);
				break;
			case '6':
				prev6 = tmp + prev6;
				prev6 = parseFloat(prev6);
				break;
			case '7':
				prev7 = tmp + prev7;
				prev7 = parseFloat(prev7);
				break;
			case '8':
				prev8 = tmp + prev8;
				prev8 = parseFloat(prev8);
				break;
			case '9':
				prev9 = tmp + prev9;
				prev9 = parseFloat(prev9);
				break;
			case '10':
				prev10 = tmp + prev10;
				prev10 = parseFloat(prev10);
				break;
		}
	});


	$("#dadoPagamento-previsto_1").html(prev1);
	$("#dadoPagamento-previsto_2").html(prev2);
	$("#dadoPagamento-previsto_3").html(prev3);
	$("#dadoPagamento-previsto_4").html(prev4);
	$("#dadoPagamento-previsto_5").html(prev5);
	$("#dadoPagamento-previsto_6").html(prev6);
	$("#dadoPagamento-previsto_7").html(prev7);
	$("#dadoPagamento-previsto_8").html(prev8);
	$("#dadoPagamento-previsto_9").html(prev9);
	$("#dadoPagamento-previsto_10").html(prev10);
	$("input:text[name='dadoPagamento-definido_1']").val(prev1);
	$("input:text[name='dadoPagamento-definido_2']").val(prev2);
	$("input:text[name='dadoPagamento-definido_3']").val(prev3);
	$("input:text[name='dadoPagamento-definido_4']").val(prev4);
	$("input:text[name='dadoPagamento-definido_5']").val(prev5);
	$("input:text[name='dadoPagamento-definido_6']").val(prev6);
	$("input:text[name='dadoPagamento-definido_7']").val(prev7);
	$("input:text[name='dadoPagamento-definido_8']").val(prev8);
	$("input:text[name='dadoPagamento-definido_9']").val(prev9);
	$("input:text[name='dadoPagamento-definido_10']").val(prev10);
}

function carregaAgendamento(idMedico) {

	if ($("#medico option:selected").val() == "") {
		return false;
	} else if ($("#agenda-calendario").val() == "") {
		return false;
	}

	id = $("#medico-agenda option:selected").val();
	data = $("#agenda-calendario").val();

	$.post("index.php?module=ag_medica&tmp=1", {
		id: id,
		data: data
	}, function(result) {
		$("#conteudo").html(result);
	});

}

function confirmaDadosPagamento() {

	var idFilaEspera = $("#id-fila-espera-atual").val();
	var convenio = $("#convenio-caixa").val();

	var cartao_1 = new Array();
	cartao_1[0] = $("#selectBandeiras_1").val();
	cartao_1[1] = $("#valor-cartao_1").val();
	cartao_1[2] = $("#num_parcelas_cartao_1").val();
	cartao_1[3] = $("#autoriz-cartao_1").val();
	var cartao_2 = new Array();
	cartao_2[0] = $("#selectBandeiras_2").val();
	cartao_2[1] = $("#valor-cartao_2").val();
	cartao_2[2] = $("#num_parcelas_cartao_2").val();
	cartao_2[3] = $("#autoriz-cartao_2").val();
	var cartao_3 = new Array();
	cartao_3[0] = $("#selectBandeiras_3").val();
	cartao_3[1] = $("#valor-cartao_3").val();
	cartao_3[2] = $("#num_parcelas_cartao_3").val();
	cartao_3[3] = $("#autoriz-cartao_3").val();
	var forma_pagamento = new Array();
	forma_pagamento[0] = $("input:text[name='dadoPagamento-definido_1']").val();
	forma_pagamento[1] = $("input:text[name='dadoPagamento-definido_2']").val();
	forma_pagamento[2] = $("input:text[name='dadoPagamento-definido_3']").val();
	forma_pagamento[3] = $("input:text[name='dadoPagamento-definido_4']").val();
	forma_pagamento[4] = $("input:text[name='dadoPagamento-definido_5']").val();
	forma_pagamento[5] = $("input:text[name='dadoPagamento-definido_6']").val();
	forma_pagamento[6] = $("input:text[name='dadoPagamento-definido_7']").val();
	forma_pagamento[7] = $("input:text[name='dadoPagamento-definido_8']").val();
	forma_pagamento[8] = $("input:text[name='dadoPagamento-definido_9']").val();
	forma_pagamento[9] = $("input:text[name='dadoPagamento-definido_10']").val();
	var soma_pagamento = parseFloat(0);
	for (i = 0; i < forma_pagamento.length; i++) {
		soma_pagamento += parseFloat(forma_pagamento[i]);
	}

	var soma_previsto = parseFloat(0);
	for (i = 1; i <= forma_pagamento.length; i++) {
		soma_previsto += parseFloat($("#dadoPagamento-previsto_" + i).html());
	}


	var valor_cartao_1 = parseFloat($("#valor-cartao_1").val());
	if (!valor_cartao_1) {
		valor_cartao_1 = parseFloat(0);
	}
	var valor_cartao_2 = parseFloat($("#valor-cartao_2").val());
	if (!valor_cartao_2) {
		valor_cartao_2 = parseFloat(0);
	}
	var valor_cartao_3 = parseFloat($("#valor-cartao_3").val());
	if (!valor_cartao_3) {
		valor_cartao_3 = parseFloat(0);
	}

	var soma_cartao_credito_recebido = parseFloat(0);
	var soma_cartao_debito_recebido = parseFloat(0);
	var soma_cartao_debito = parseFloat(0);
	var soma_cartao_credito = parseFloat(0);

	if (cartao_1[0] == 1 || cartao_1[0] == 2) {
		soma_cartao_debito += valor_cartao_1;
	} else {
		soma_cartao_credito += valor_cartao_1;
	}
	if (cartao_2[0] == 1 || cartao_2[0] == 2) {
		soma_cartao_debito += valor_cartao_2;
	} else {
		soma_cartao_credito += valor_cartao_2;
	}

	if (cartao_3[0] == 1 || cartao_3[0] == 2) {
		soma_cartao_debito += valor_cartao_3;
	} else {
		soma_cartao_credito += valor_cartao_3;
	}

	soma_cartao_credito_recebido = parseFloat(forma_pagamento[4]);
	soma_cartao_debito_recebido = parseFloat(forma_pagamento[5]);

	if (soma_cartao_credito_recebido == soma_cartao_credito) {
		if (soma_cartao_debito_recebido == soma_cartao_debito) {

			if (soma_previsto == soma_pagamento) {

				$.post(
						'index.php?module=caixa&tmp=1',
						{flag: "confirmaDadosPagamento", idFilaEspera: idFilaEspera, convenio: convenio, cartao_1: cartao_1, cartao_2: cartao_2, cartao_3: cartao_3, forma_pagamento: forma_pagamento},
				function(result) {
					alert("inserido no banco com sucesso");

					//     $("#xxxx").html(result);
				}
				);
			} else {
				alert("Valores Previstos e Valor Recebido estão diferentes!!")
			}
		} else {
			alert("Valores dos cartoes de debito estao diferentes!!")
		}
	} else {
		alert("Valores dos cartoes de credito estao diferentes!!")
	}
}

/*
 * TRANSFERIDO PARA A NOVA MODEL
 */
function carregaResumoCaixa() {
	data_inicio = $("#data-inicio-resumo-caixa").val();
	data_fim = $("#data-fim-resumo-caixa").val();
	$.post('index.php?module=caixa&tmp=1',
		{flag: "carregaResumoCaixa", data_inicio: data_inicio, data_fim: data_fim},
	function(result) {
		var html = "";
		console.log(result);
		if (result) {
			for (var i = 0; i < result.length; i++) {
				html += "<tr name=\"table-color\" class=\"dayhead\">";
				html += "<th align=\"center\">" + result[i]['data'] + "</th>";
				html += "<th align=\"center\">" + result[i]['controle'] + "</th>";
				html += "<th align=\"center\">" + result[i]['matricula'] + "</th>";
				html += "<th align=\"center\">" + result[i]['responsavel'] + "</th>";
				html += "<th align=\"center\">" + result[i]['operador'] + "</th>";
				if (typeof result[i]['dinheiro'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['dinheiro'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
				if (typeof result[i]['cheque'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['cheque'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
				if (typeof result[i]['cartao'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['cartao'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
				if (typeof result[i]['convenio'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['convenio'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
				if (typeof result[i]['cortesia'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['cortesia'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
                                 console.log("Desconto::")
                                console.log(result[i]['desconto']);
				if (typeof result[i]['desconto'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['desconto'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
				if (typeof result[i]['debito'] != 'undefined') {
					html += "<th align=\"center\">" + result[i]['debito'] + "</th>";
				} else {
					html += "<th align=\"center\"></th>";
				}
				html += "<th align=\"center\">" + result[i]['valor_total'] + "</th>";
				html += "<th align=\"center\">" + result[i]['nota_fiscal'] + "</th>";
				html += "</tr>";
			}
		}
		$("#tbody-resumo-caixa").html(html);
	}, "json");
}
function insereNovaLinhaBandeira() {
//	html = "";
//<tr name="table-color" class='dayhead '>
//<th align="center"> 
//<select onChange="insereNovaLinhaBandeira()" id="selectBandeiras" >
//<option></option>
//<?php foreach(listar('cartao_bandeiras','*') as $bandeira) { ?>
//<option> <?php echo $bandeira['nome'] ?> </option>
//<?php } ?>
//</select>
//</th>
//<th align="center">
//<input type="text" name="valor-cartao" id="valor-cartao" />
//</th>
//<th align="center"> 
//<select >
//<option></option>
//<?php for($i=1; $i<12; $i++) { ?>
//<option> <?php echo $i ?> </option>
//<?php } ?>
//</select>
//</th>
//<th align="center">
//<input type="text" name="valor-cartao" id="autoriz-cartao" />
//</th>
//</tr>

//	 $("#selectBandeiras").each(function () {
//		 alert($(this).val());
//	 });
//	$("tbody#tabela-bandeiras").append(html);
}

//function chamaModal() {
//    var isd = "aaa";
//    //e.preventDefault();
//    $('#teste').reveal({
//        animation: 'fadeAndPop',                   //fade, fadeAndPop, none
//        animationspeed: 300,                       //how fast animtions are
//        closeonbackgroundclick: true,              //if you click background will modal close?
//        dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
//
//    });
//
//}

function carregaPDF() {
	data_inicio = $("#data-inicio-resumo-caixa").val();
	data_fim = $("#data-fim-resumo-caixa").val();
	$.post(
			'index.php?module=caixa&tmp=1',
			{flag: "ResumoCaixaPDF", data_inicio: data_inicio, data_fim: data_fim},
	function(result) {
	}, "json"
			);
}

/*
 * TRANSFERIDO PARA A NOVA MODEL
 */
function carregaNotaFiscal() {
	flag = "buscaNotasNaoEmitidas";
	$.post(
			'index.php?module=caixa&tmp=1',
			{flag: flag},
	function(data) {
		$("tbody#tbody-nfse").html("");
		for (var i = 0; i < data.length; i++) {
			html = "<tr name='table-color' class='dayhead '>";
			html += "<th align='center'> <input  type='checkbox' value='" + data[i]['fila_espera_caixa_id'] + "' /> </th>";
			html += "<th align='center'> " + data[i]['fila_espera_caixa_id'] + " </th>";
			html += "<th align='center'> " + data[i]['numero_nfse'] + " </th>";
			html += "<th align='center'> " + data[i]['cliente_cliente_id'] + " </th>";
			html += "<th align='center'> " + data[i]['nomeTomador'] + " </th>";
			html += "<th align='center'> " + data[i]['ValorTotalServicos'] + " </th>";
			html += "</tr>";

			$("tbody#tbody-nfse").append(html);

		}

	}, "json"
			);

	abrir_modal('boxes-emissaonfs');
}

/*
 * TRANSFERIDO PARA A NOVA MODEL
 */
function consultaNotaFiscal() {

	var flag = "buscaNotasEmitidas";
	$.post(
			'index.php?module=caixa&tmp=1',
			{flag: flag},
	function(data) {
		$("tbody#tbody-nfse").html("");
		for (var i = 0; i < data.length; i++) {
			html = "<tr name='table-color' class='dayhead '>";
			html += "<th align='center'> <input  type='checkbox' value='" + data[i]['fila_espera_caixa_id'] + "' /> </th>";
			html += "<th align='center'> " + data[i]['fila_espera_caixa_id'] + " </th>";
			html += "<th align='center'> " + data[i]['numero_nfse'] + " </th>";
			html += "<th align='center'> " + data[i]['cliente_cliente_id'] + " </th>";
			html += "<th align='center'> " + data[i]['nomeTomador'] + " </th>";
			html += "<th align='center'> " + data[i]['ValorTotalServicos'] + " </th>";
			html += "</tr>";

			$("tbody#tbody-nfse").append(html);

		}

	}, "json"
			);

}

/*
 * TRANSFERIDO PARA A NOVA MODEL
 */
function processarNFE() {
	var nfe = new Array();
	$("input[type='checkbox']:checked").each(function(i) {
		nfe.push($(this).val());
	});
	var flag = 'enviaNFE';
	$.post(
			'index.php?module=caixa&tmp=1',
			{flag: flag, nfe: nfe},
	function(data) {
		$.post(
				'lib/nfe/index.php',
				{data: data, tipo_xml: 'enviaNFE'},
		function(resultado) {
			alert(resultado);
		}
		);

	}
	);
}
