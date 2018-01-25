$(document).ready(function() {

    var tipoForm = "baixa_fatura_busca";

    $.post("index.php?module=baixacr&tmp=1",{ tipoForm:tipoForm },function(result){

        /* Campos do formulário de busca para baixa de faturas */

		$('input:[name=baixacr-cliente]').typeahead({source: result.clientes, items:1115});
		$('input:[name=baixacr-empresa]').typeahead({source: result.empresas, items:1115});
		$('input:[name=baixacr-banco]').typeahead({source: result.bancos, items:1115});
		$('input:[name=baixacr-modal-conta_corrente]').typeahead({source: result.contas, items:1115});
		$('input:[name=baixacr-modal-tipo_operacao]').typeahead({source: result.tipo_operacao, items:1115});

    },"json");

});

function showinput(){
	if ($("input#showinput").is(':checked')){
		$("div#campotexto").show();
	}else{
		$("div#campotexto").hide();
	}
}

function showlist(){
	if ($("input#showlist").is(':checked')){
		$("div#showlistcheck").show();
	}else{
		$("div#showlistcheck").hide();
	}
}

function localizarBaixarc(){
	$("div#baixacrconsultarduplicatas").hide("slide", function(){
		$("div#baixarclocalizar").show();
	});
}

function voltarConsultabaixarc(){
	$("div#baixarclocalizar").hide("slide", function(){
		$("div#baixacrconsultarduplicatas").show();
	});

}

function showbaixarc(){
	$("div#baixarclocalizar").hide("slide", function(){
		$("div#pesquisar_baixarc").show();
	});
}

function hidebaixarc(){
	$("div#pesquisar_baixarc").hide("slide", function(){
		$("div#baixarclocalizar").show();
	});
}
function cleanFormConsultaBaixaCr() {

    $('input:[name=baixacr-cliente]').val("");
    $('input:[name=baixacr-cod_cliente]').val("");
    $('input:[name=baixacr-empresa]').val("");
    $('input:[name=baixacr-cod_empresa]').val("");
    $('input:[name=baixacr-banco]').val("");
    $('input:[name=baixacr-cod_banco]').val("");
    $('input:[name=baixacr-data_inicio]').val("");
    $('input:[name=baixacr-data_fim]').val("");
	$('input:[name=baixacr-numero_controle]').val("");

//    $("select:[name=baixacr-tipo_cliente] option[value='0']").prop('selected', true);
    $("select:[name=baixacr-moeda] option[value='0']").prop('selected', true);
    $("select:[name=baixacr-status] option[value='0']").prop('selected', true);
    $("select:[name=baixacr-ordenado_por] option[value='1']").prop('selected', true);
    $("select:[name=baixacr-selecionado_por] option[value='1']").prop('selected', true);

}

function cleanFormConsultaBaixaCrModal() {

	$('input[name=baixacr-modal-data_operacao]').val("");
	$('input[name=baixacr-modal-conta_corrente]').val("");
	$('input[name=baixacr-modal-valor]').val("");
	$('input[name=baixacr-modal-tipo_operacao]').val("");
	$('input[name=baixacr-modal-cod_tipo_operacao]').val("");
	$('input[name=baixacr-modal-numero_documento]').val("");
	$('input[name=baixacr-modal-hidden_parcela_id]').val("");
	$('input[name=baixacr-modal-data_baixa]').val("");
	//$('input[baixacr-modal-compensacao]').val("");

}

function buscaPorCodigoCR(flag, form) {

    var tipoForm = "busca_por_codigo";
    var name = "";

    if(form == "baixa") {
        name = "baixacr";
    } else if(form == "baixa-modal") {
		name = "baixacr-modal";
	}

    var cod = $('input:[name=' + name + '-cod_'+ flag +']').val();

    $.post("index.php?module=baixacr&tmp=1",{ tipoForm:tipoForm, flag:flag, cod:cod },function(result){
        $('input:[name=' + name + '-'+ flag + ']').val(result);
    },"json");
	
}

function criaListaBaixaCR() {

//	var tipo_cliente = $('select:[name=baixacr-tipo_cliente]').val();
	var cliente = $('input:[name=baixacr-cliente]').val();
	var empresa = $('input:[name=baixacr-empresa]').val();
	var banco = $('input:[name=baixacr-banco]').val();
	var moeda = $('select:[name=baixacr-moeda]').val();
	var status = $('select:[name=baixacr-status]').val();
	var selecionado = $('select:[name=baixacr-selecionado_por]').val();
	var data_inicio = $('input:[name=baixacr-data_inicio]').val();
	var data_fim = $('input:[name=baixacr-data_fim]').val();
	var ordenado = $('select:[name=baixacr-ordenado_por]').val();
	var fatura_numero = $('#numero_fatura').val();
	var cartao = new Array();
	var order = '';

    if (ordenado == 1) {
        order = "nome_status";
    } else if (ordenado == 2) {
        order = "nome_cliente";
    } else if (ordenado == 3) {
        order = "data_emissao";
    } else if (ordenado == 4) {
        order = "data_vencimento";
    } else if (ordenado == 5) {
        order = "nome_moeda";
    } else if (ordenado == 6) {
        order = "nome_banco";
    } else if (ordenado == 7) {
        order = "nome_tipo_doc";
    }
        
	$("input:[type=checkbox][name='baixacr-cartao[]']:checked").each(function(i){
		cartao[i] = $(this).val();
	});

	var tipoForm = "baixacr_lista_faturas";

	if(cliente == "") {
		alert("Por Favor, informe o Cliente!");
		return false;
	} else if(empresa == "") {
		alert("Por Favor, informe a Empresa!");
		return false;
	} else if(banco == "") {
		alert("Por Favor, o Banco!");
		return false;
	} else if(data_inicio == "") {
		alert("Por Favor, informe a Data Inicial!");
		return false;
	} else if(data_fim == "") {
		alert("Por Favor, informe a Data Final!");
		return false;
	}

	$.post("index.php?module=baixacr&tmp=1",{
		tipoForm:tipoForm,
		fatura: fatura_numero,
		cliente:cliente,
		empresa:empresa,
		banco:banco,
		moeda:moeda,
		status:status,
		selecionado:selecionado,
		data_inicio:data_inicio,
		data_fim:data_fim,
		ordenado:ordenado,
		cartao:cartao
	},function(resultado){
		//console.log(resultado);
		if(resultado == null) {
			alert("Não há registros !");
			return false;
		}

		var strhtml = '';
		var aux = '';
		var k = 0;
		var i = 0;
		result = resultado.lista;
		subtotal = resultado.subtotal;
                //console.log(resultado);
		while(i < result.length ) {
			if(result[i]['conta_corrente'] == null) {
				result[i]['conta_corrente'] = '';
			}
			if(i == 0) {
				aux = result[i][order];
			}

			if(aux != result[i][order]) {

				strhtml += '<tr style=\'font-weight: bold\' name="consulta-dup-lista_duplicatas-tr">';
					strhtml += '<td>';
						if (ordenado == 1) {
							strhtml += subtotal[k]['nome']; //status
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
						if (ordenado == 2) {
							strhtml += subtotal[k]['nome']; //cliente
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 3) {
							strhtml += subtotal[k]['data_emissao']; //cliente
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 4) {
							strhtml += subtotal[k]['data_vencimento']; //cliente
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += 'R$ ' + number_format(subtotal[k]['total_parcela'], 2, ',', '.');
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
						if (ordenado == 5) {
							strhtml += subtotal[k]['nome']; //moeda
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 6) {
							strhtml += subtotal[k]['nome']; //banco
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 7) {
							strhtml += subtotal[k]['nome']; //tipo documento
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
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
				strhtml += '</tr>';
				
				aux = result[i][order];
				k++;
			} else {

				var idparc = result[i]['id_parcela'];
				var stat = result[i]['nome_status'];

				strhtml += '<tr name="consulta-dup-lista_duplicatas-tr" ondblclick="criaListaConsultaCrParcelasModal(' + idparc + ',\'' + stat + '\');" >';
					strhtml += '<td>';
						strhtml += result[i]['nome_status'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['numero_fatura'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['numero_parcela'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['nome_cliente'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['nome_tipo_cliente'];
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
						strhtml += 'R$  ' + number_format(result[i]['valor_pago'], 2, ',', '.');
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
						strhtml += result[i]['dias_atraso'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['conta_baixada'];
					strhtml += '</td>';
				strhtml += '</tr>';
				
				i++;
			}
        }     
		strhtml += '<tr style=\'font-weight: bold\' name="consulta-dup-lista_duplicatas-tr">';
			strhtml += '<td>';
				if (ordenado == 1) {
					strhtml += subtotal[k]['nome']; //status
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
				if (ordenado == 2) {
					strhtml += subtotal[k]['nome']; //cliente
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 3) {
					strhtml += subtotal[k]['data_emissao']; //cliente
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 4) {
					strhtml += subtotal[k]['data_vencimento']; //cliente
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += 'R$ ' + number_format(subtotal[k]['total_parcela'], 2, ',', '.');
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
				if (ordenado == 5) {
					strhtml += subtotal[k]['nome']; //moeda
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 6) {
					strhtml += subtotal[k]['nome']; //banco
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 7) {
					strhtml += subtotal[k]['nome']; //tipo documento
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
				strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += '';
			strhtml += '</td>';
		strhtml += '</tr>';

        $('#baixarc').html(strhtml);
		localizarBaixarc();

    },"json");

}

function estornoParcelaFatura() {

	var tipoForm = "estorno_parcela";
	var confirma = confirm('Confirma o Estorno desta Fatura ?');

	if(confirma) {

		var id = $('input[name=baixacr-modal-hidden_parcela_id]').val();

		$.post("index.php?module=baixacr&tmp=1",{tipoForm:tipoForm,id:id},function(result){
			
			alert("Operação executada com sucesso");
			cleanFormConsultaBaixaCrModal();
			fechar_modal('pesquisar_baixarc');
			criaListaBaixaCR();
		},"json");
	}

}

function baixaCrParcela() {

	var tipoForm = "baixacr_parcela";
	var confirma = confirm('Confirma a Baixa desta Fatura ?');

	if(confirma) {

		var data_operacao = $('input[name=baixacr-modal-data_operacao]').val();
		var conta_corrente = $('input[name=baixacr-modal-conta_corrente]').val();
		var valor = $('input[name=baixacr-modal-valor]').val();
		var tipo_operacao = $('input[name=baixacr-modal-tipo_operacao]').val();
		var numero_documento = $('input[name=baixacr-modal-numero_documento]').val();
		var data_baixa = $('input[name=baixacr-modal-data_baixa]').val();
		//var = $('input[baixacr-modal-compensacao]').val("");

		if(data_operacao == "") {
			alert("Por Favor, informe a Data de Operação!");
			return false;
		} else if(conta_corrente == "") {
			alert("Por Favor, informe a Conta Corrente!");
			return false;
		} else if(valor == "") {
			alert("Por Favor, informe o Valor!");
			return false;
		} else if(tipo_operacao == "") {
			alert("Por Favor, informe o Tipo de Operação!");
			return false;
		} else if(numero_documento == "") {
			alert("Por Favor, informe o Número do Documento!");
			return false;
		} else if(data_baixa == "") {
			alert("Por Favor, informe a Data da baixa!");
			return false;
		}

		var id = $('input[name=baixacr-modal-hidden_parcela_id]').val();

		$.post("index.php?module=baixacr&tmp=1",{
			tipoForm:tipoForm,
			id:id,
			data_baixa:data_baixa,
			conta_corrente:conta_corrente,
			tipo_operacao:tipo_operacao
		},function(result){
			alert("Operação executada com sucesso");
			cleanFormConsultaBaixaCrModal();
			fechar_modal('pesquisar_baixarc');
			criaListaBaixaCR();
		},"json");
	}

}

function criaListaConsultaCrParcelasModal(id,stat) {

	var tipoForm = "busca_baixacr_por_id";
	var strhtml = '';
	var titulo = 'CTPG - Baixa de Fatura';

	if(stat == 'B') {
		var flag = 'estorno';
	}

	$.post("index.php?module=baixacr&tmp=1",{
		tipoForm:tipoForm,
		id:id,
		flag:flag
	},function(result){
		strhtml += '<td>';
			strhtml += result['numero_fatura'];
		strhtml += '</td>';
		strhtml += '<td>';
			strhtml += result['nome_cliente'];
		strhtml += '</td>';
		strhtml += '<td>';
			strhtml += result['nome_tipo_cliente'];
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

		if(flag == 'estorno') {
			$('input[name=baixacr-modal-conta_corrente]').val(result.nome_conta_corrente);
			$('input[name=baixacr-modal-tipo_operacao]').val(result.nome_tipo_operacao);
			$('input[name=baixacr-modal-data_baixa]').val(result.data_baixa);
			$('a#baixacr-modal-estorno').show();
			$('a#baixacr-modal-baixa').hide();
		} else {
			$('a#baixacr-modal-estorno').hide();
			$('a#baixacr-modal-baixa').show();
		}

		$('input[name=baixacr-modal-data_operacao]').val(result.data_emissao);
		$('input[name=baixacr-modal-valor]').val(result.valor);
		$('input[name=baixacr-modal-numero_documento]').val(result.numero_fatura);
		$('input[name=baixacr-modal-hidden_parcela_id]').val(id);
		$('tbody[name=baixacr-modal-tbody_lista_parcelas]').html(strhtml);
		$('h2#baixacr-modal-titulo').html(titulo);

		showbaixarc();
	},"json");
}

function escolherOperacaoCr(nome) {
	$('input:[name=baixacr-modal-tipo_operacao]').val(nome);
	fechar_modal('baixacr-tipo_operacao_modal');
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
};

function imprimirBaixacr(){
    window.open('index2.php?module=relatoriobaixacr', '_blank');
}