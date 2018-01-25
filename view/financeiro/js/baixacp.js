$(document).ready(function() {

    var tipoForm = "baixa-duplicata_busca";

    $.post("index.php?module=baixacp&tmp=1", {tipoForm: tipoForm}, function(result) {

	/* Campos do formulário de busca para baixa de duplicatas */

	$('input:[name=baixa-dup-fornecedor]').typeahead({source: result.fornecedores, items: 1115});
	$('input:[name=baixa-dup-empresa]').typeahead({source: result.empresas, items: 1115});
	$('input:[name=baixa-dup-tipoDoc]').typeahead({source: result.tipo_doc, items: 1115});
	$('input:[name=baixa-dup-modal-tipo_operacao]').typeahead({source: result.tipos, items: 1115});
	$('input:[name=baixa-dup-modal-conta_corrente]').typeahead({source: result.contas, items: 1115});

    }, "json");

});

function localizarBaixaduplicata() {
    $("div#baixaconsultarduplicatas").hide("slide", function() {
	$("div#baixaduplicataslocalizar").css("display", "block");
	$("#baixaduplicata").css("display", "none");
    });
}

function avancarConsultaBaixaDuplicata() {
    $("div#baixaconsultarduplicata").hide("slide", function() {
	$("div#baixaduplicata").css("display", "none");
	$("div#baixaduplicataslocalizar").css("display", "block");
    });
}


function voltarConsultaduplicata() {
    $("div#baixaduplicataslocalizar").hide("slide", function() {
	$("div#baixaconsultarduplicatas").css("display", "block");
    });
}

function avancarListaBaixaCp() {
    $("#baixaduplicataslocalizar").hide('slider', function() {
	$("#baixaduplicata").show('slider');
    });
}

function voltarListaBaixaCp() {
    $("#baixaduplicata").hide('slider', function() {
	$("#baixaduplicataslocalizar").show('slider');
    });
}

function cleanFormConsultaBaixaCp() {

    $('input:[name=baixa-dup-fornecedor]').val("");
    $('input:[name=baixa-dup-cod_fornecedor]').val("");
    $('input:[name=baixa-dup-empresa]').val("");
    $('input:[name=baixa-dup-cod_empresa]').val("");
    $('input:[name=baixa-dup-tipoDoc]').val("");
    $('input:[name=baixa-dup-cod_tipoDoc]').val("");
    $('input:[name=baixa-dup-data_inicio]').val("");
    $('input:[name=baixa-dup-data_fim]').val("");

    $("select:[name=baixa-dup-moeda] option[value='0']").prop('selected', true);
    $("select:[name=baixa-dup-status] option[value='0']").prop('selected', true);
    $("select:[name=baixa-dup-ordenado_por] option[value='1']").prop('selected', true);
    $("select:[name=baixa-dup-selecionado_por] option[value='1']").prop('selected', true);

}

function cleanFormConsultaBaixaCpModal() {

    $('input[name=baixa-dup-modal-data_operacao]').val("");
    $('input[name=baixa-dup-modal-conta_corrente]').val("");
    $('input[name=baixa-dup-modal-valor]').val("");
    $('input[name=baixa-dup-modal-tipo_operacao]').val("");
    $('input[name=baixa-dup-modal-cod_tipo_operacao]').val("");
    $('input[name=baixa-dup-modal-nominal]').val("");
    $('input[name=baixa-dup-modal-numero_documento]').val("");
    $('input[name=baixa-dup-modal-hidden_parcela_id]').val("");
    $('input[name=baixa-dup-modal-numero_documento]').val("");
    $('input[name=baixa-dup-modal-compensacao]').val("");
    $('input[name=baixa-dup-modal-data_baixa]').val("");
    $('input[name=baixa-dup-modal-numero_cheque]').val("");
    $('textarea[name=baixa-dup-modal-historico]').val("");

}

function buscaPorCodigoCP(flag, form) {

    var tipoForm = "busca_por_codigo";
    var name = "";

    if (form == "baixa") {
	name = "baixa-dup";
    } else if (form == "baixa-modal") {
	name = "baixa-dup-modal";
    }

    var cod = $('input:[name=' + name + '-cod_' + flag + ']').val();

    $.post("index.php?module=baixacp&tmp=1", {tipoForm: tipoForm, flag: flag, cod: cod}, function(result) {
	$('input:[name=' + name + '-' + flag + ']').val(result);
    }, "json");

}

function criaListaBaixaDuplicatas() {

    var fornecedor = $('input:[name=baixa-dup-fornecedor]').val();
    var empresa = $('input:[name=baixa-dup-empresa]').val();
    var tipoDoc = $('input:[name=baixa-dup-tipoDoc]').val();
    var moeda = $('select:[name=baixa-dup-moeda]').val();
    var status = $('select:[name=baixa-dup-status]').val();
    var selecionado = $('select:[name=baixa-dup-selecionado_por]').val();
    var data_inicio = $('input:[name=baixa-dup-data_inicio]').val();
    var data_fim = $('input:[name=baixa-dup-data_fim]').val();
    var ordenado = $('select:[name=baixa-dup-ordenado_por]').val();
    var order = '';

    if (ordenado == 1) {
	order = "id_duplicata";
    } else if (ordenado == 2) {
	order = "nome_fornecedor";
    } else if (ordenado == 3) {
	order = "nome_empresa";
    } else if (ordenado == 4) {
	order = "data_emissao";
    } else if (ordenado == 5) {
	order = "data_vencimento";
    } else if (ordenado == 6) {
	order = "data_baixa";
    } else if (ordenado == 7) {
	order = "nome_moeda";
    } else if (ordenado == 8) {
	order = "nome_banco";
    }

    if (fornecedor == "") {
	alert("Por Favor, informe o Fornecedor!");
	return false;
    } else if (empresa == "") {
	alert("Por Favor, informe a Empresa!");
	return false;
    } else if (tipoDoc == "") {
	alert("Por Favor, o tipo de Documento!");
	return false;
    } else if (data_inicio == "") {
	alert("Por Favor, informe a Data Inicial!");
	return false;
    } else if (data_fim == "") {
	alert("Por Favor, informe a Data Final!");
	return false;
    }

    var tipoForm = "baixa_lista_duplicatas";

    $.post("index.php?module=baixacp&tmp=1", {
	tipoForm: tipoForm,
	fornecedor: fornecedor,
	empresa: empresa,
	tipoDoc: tipoDoc,
	moeda: moeda,
	status: status,
	selecionado: selecionado,
	data_inicio: data_inicio,
	data_fim: data_fim,
	ordenado: ordenado
    }, function(resultado) {
	console.log(resultado);
	if (resultado == null) {
	    alert("Não há registros !");
	    return false;
	}

	var strhtml = '';
	var aux = '';
	var k = 0;
	var i = 0;
	result = resultado.lista;
	subtotal = resultado.subtotal;
	while (i < result.length) {

	    if (i == 0) {
		aux = result[i][order];
	    }

	    if (aux != result[i][order]) {

		strhtml += '<tr style=\'font-weight: bold\' name="consulta-dup-lista_duplicatas-tr">';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 2) {
		    strhtml += subtotal[k]['nome']; //FORNECEDOR
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 3) {
		    strhtml += subtotal[k]['nome']; //EMPRESA
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 4) {
		    strhtml += subtotal[k]['data_emissao'];
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 5) {
		    strhtml += subtotal[k]['data_vencimento'];
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(subtotal[k]['total'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(subtotal[k]['total_desconto'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(subtotal[k]['total_multa'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(subtotal[k]['total_juros'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(subtotal[k]['total_pago'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(subtotal[k]['total_a_pagar'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 7) {
		    strhtml += subtotal[k]['nome']; //MOEDA
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 8) {
		    strhtml += subtotal[k]['nome']; //BANCO
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		if (ordenado == 6) {
		    strhtml += subtotal[k]['data_baixa'];
		} else {
		    strhtml += '';
		}
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += '';
		strhtml += '</td>';
		strhtml += '</tr>';
		aux = result[i][order];
		k++;
	    } else {
		var idparc = result[i]['id'];
		var stat = result[i]['nome_status'];

		strhtml += '<tr name="consulta-dup-lista_duplicatas-tr" ondblclick="criaListaConsultaDuplicatasParcelasModal(' + idparc + ',\'' + stat + '\');">';
		strhtml += '<td>';
		strhtml += result[i]['nome_status'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nota_fiscal'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['numero_parcela'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['codigo_plano_contas'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_fornecedor'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_empresa'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['data_emissao'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['data_vencimento'];
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(result[i]['valor_parcela'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(result[i]['desconto'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(result[i]['multa'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(result[i]['juros'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(result[i]['valor_pago'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td style=\'text-align:right;\'>';
		strhtml += 'R$ ' + number_format(result[i]['valor_a_pagar'], 2, ',', '.');
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_moeda'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_banco'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['codigo_barras'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_tipo_doc'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_plano_contas'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['data_baixa'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['nome_pessoa'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['observacao'];
		strhtml += '</td>';
		strhtml += '<td>';
		strhtml += result[i]['dias_atraso'];
		strhtml += '</td>';
		strhtml += '</tr>';

		i++;
	    }
	}

	strhtml += '<tr style=\'font-weight: bold\' name="consulta-dup-lista_duplicatas-tr">';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 2) {
	    strhtml += subtotal[k]['nome']; //FORNECEDOR
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 3) {
	    strhtml += subtotal[k]['nome']; //EMPRESA
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 4) {
	    strhtml += subtotal[k]['data_emissao'];
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 5) {
	    strhtml += subtotal[k]['data_vencimento'];
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(subtotal[k]['total'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(subtotal[k]['total_desconto'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(subtotal[k]['total_multa'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(subtotal[k]['total_juros'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(subtotal[k]['total_pago'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(subtotal[k]['total_a_pagar'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 7) {
	    strhtml += subtotal[k]['nome']; //MOEDA
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 8) {
	    strhtml += subtotal[k]['nome']; //BANCO
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	if (ordenado == 6) {
	    strhtml += subtotal[k]['data_baixa'];
	} else {
	    strhtml += '';
	}
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += '';
	strhtml += '</td>';
	strhtml += '</tr>';

	$('#baixa-dup-lista_parcelas').html(strhtml);	
	hoverizar('#baixa-dup-lista_parcelas');
	localizarBaixaduplicata();

	$('#imprimirConsultaduplicatas').unbind().click(function() {
	    window.open('index2.php?module=relatoriocontaspagar', '_blank');
	});	

    }, "json");    
}

function imprimeComprovante() {

    var tipoForm = "comprovante_parcela";
    var confirma = confirm('Confirma o Estorno desta Duplicata ?');

    if (confirma) {

	var id = $('input[name=baixa-dup-modal-hidden_parcela_id]').val();

	$.post("index.php?module=baixacp&tmp=1", {tipoForm: tipoForm, id: id}, function(result) {
	    window.open('index2.php?module=comprovante', '_blank');
	}, "json");
    }

}

function estornoParcelaDuplicata() {

    var tipoForm = "estorno_parcela";
    var confirma = confirm('Confirma o Estorno desta Duplicata ?');

    if (confirma) {

	var id = $('input[name=baixa-dup-modal-hidden_parcela_id]').val();

	$.post("index.php?module=baixacp&tmp=1", {tipoForm: tipoForm, id: id}, function(result) {

	    if (result == 0) {
		alert("Operação executada com sucesso");
	    } else {
		alert("Erro ao realizar a operacao");
	    }

	    cleanFormConsultaBaixaCpModal();
	    fechar_modal('baixaduplicata');
	    criaListaBaixaDuplicatas();
	}, "json");
    }

}


function baixaDuplicataParcela() {

    var tipoForm = "baixa_duplicata_parcela";
    var confirma = confirm('Confirma a Baixa deste Título ?');

    if (confirma) {

	var data_operacao = $('input[name=baixa-dup-modal-data_operacao]').val();
	var conta_corrente = $('input[name=baixa-dup-modal-conta_corrente]').val();
	var valor = $('input[name=baixa-dup-modal-valor]').val();
	var tipo_operacao = $('input[name=baixa-dup-modal-tipo_operacao]').val();
	var numero_documento = $('input[name=baixa-dup-modal-numero_documento]').val();
	var data_baixa = $('input[name=baixa-dup-modal-data_baixa]').val();
	var nominal = $('input[name=baixa-dup-modal-nominal]').val();
	var cheque = $('input[name=baixa-dup-modal-numero_cheque]').val();
	var historico = $('textarea[name=baixa-dup-modal-historico]').val();

	if (data_operacao == "") {
	    alert("Por Favor, informe a Data de Operação!");
	    return false;
	} else if (conta_corrente == "") {
	    alert("Por Favor, informe a Conta Corrente!");
	    return false;
	} else if (valor == "") {
	    alert("Por Favor, informe o Valor!");
	    return false;
	} else if (tipo_operacao == "") {
	    alert("Por Favor, informe o Tipo de Operação!");
	    return false;
	} else if (numero_documento == "") {
	    alert("Por Favor, informe o Número do Documento!");
	    return false;
	} else if (data_baixa == "") {
	    alert("Por Favor, informe a Data da baixa!");
	    return false;
	} else if (nominal == "") {
	    alert("Por Favor, informe o Campo Nominal A!");
	    return false;
	}
	//else if(cheque == "") {
	//	alert("Por Favor, informe o numero do Cheque!");
	//	return false;
	//}

	var id = $('input[name=baixa-dup-modal-hidden_parcela_id]').val();

	$.post("index.php?module=baixacp&tmp=1", {tipoForm: tipoForm, id: id, conta_corrente: conta_corrente, cheque: cheque, data_baixa: data_baixa, historico: historico}, function(result) {

	    alert("Operação executada com sucesso");
	    cleanFormConsultaBaixaCpModal();
	    fechar_modal('baixaduplicata');
	    criaListaBaixaDuplicatas();
	}, "json");
    }

}

function criaListaConsultaDuplicatasParcelasModal(id, stat) {

    var tipoForm = "busca_baixa_duplicata_por_id";
    var strhtml = '';
    var titulo = 'CTPG - Baixa de Duplicata';

    if (stat == 'B') {
	var flag = 'estorno';
	var titulo = 'CTPG - Estorno de Duplicata';
    }

    $.post("index.php?module=baixacp&tmp=1", {tipoForm: tipoForm, id: id, flag: flag}, function(result) {

	strhtml += '<td>';
	strhtml += result['numero'];
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += result['nome_fornecedor'];
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += result['nome_empresa'];
	strhtml += '</td>';
	strhtml += '<td>';
	strhtml += result['data_vencimento'];
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(result['valor'], 2, ',', '.');
	strhtml += '</td>';
	strhtml += '<td style=\'text-align:right;\'>';
	strhtml += 'R$ ' + number_format(result['valor'], 2, ',', '.');
	strhtml += '</td>';

	if (flag == 'estorno') {
	    $('input[name=baixa-dup-modal-conta_corrente]').val(result.nome_conta_corrente);
	    $('input[name=baixa-dup-modal-numero_documento]').val(result.numero_fatura);
	    $('input[name=baixa-dup-modal-data_baixa]').val(result.data_baixa);
	    $('input[name=baixa-dup-modal-numero_cheque]').val(result.numero_cheque);
	    $('textarea[name=baixa-dup-modal-historico]').val(result.historico);
	    $('a#baixa-dup-modal-estorno').show();
	    $('a#baixa-dup-modal-comprovante').show();
	    $('a#baixa-dup-modal-baixa').hide();
	} else {
	    $('a#baixa-dup-modal-estorno').hide();
	    $('a#baixa-dup-modal-baixa').show();
	}

	$('input[name=baixa-dup-modal-data_operacao]').val(result.data_emissao);
	$('input[name=baixa-dup-modal-valor]').val(result.valor);
	$('input[name=baixa-dup-modal-tipo_operacao]').val(result.nome_operacao);
	$('input[name=baixa-dup-modal-nominal]').val(result.nome_fornecedor);
	//$('input[name=baixa-dup-modal-cod_tipo_operacao]').val(result.cod_fornecedor);
	//$('input[name=baixa-dup-modal-numero_documento]').val(result.cod_fornecedor);
	$('input[name=baixa-dup-modal-hidden_parcela_id]').val(id);
	$('tbody[name=baixa-dup-modal-tbody_lista_parcelas]').html(strhtml);

	$('h2#baixacp-dup-modal-titulo').html(titulo);

	avancarListaBaixaCp();

    }, "json");
}

function escolherOperacaoDup(nome) {

    $('input:[name=baixa-dup-modal-tipo_operacao]').val(nome);
    fechar_modal('duplicata-tipo_operacao_modal');
}

function imprimirBaixacp() {
    window.open('index2.php?module=relatoriobaixacp', '_blank');
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
	    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	    s = '',
	    toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
	s[1] = s[1] || '';
	s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
;