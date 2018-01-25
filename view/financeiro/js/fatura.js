$(document).ready(function() {

	var tipoForm = "fatura_busca";

	$.post("index.php?module=faturas&tmp=1",{tipoForm:tipoForm},function(result){

		/* Campos do formulário de lancamento de duplicatas */

		$('input:[name=insere-fatura-cliente]').typeahead({source: result.clientes, items:1115});
		$('input:[name=insere-fatura-empresa]').typeahead({source: result.empresas, items:1115});
		$('input:[name=insere-fatura-tipoDoc]').typeahead({source: result.tipo_doc, items:1115});
		$('input:[name=insere-fatura-banco]').typeahead({source: result.bancos, items:1115});
		$('input:[name=insere-fatura-moeda]').typeahead({source: result.moedas, items:1115});
		$('input:[name=insere-fatura-plano_contas]').typeahead({source: result.plano_contas, items:1115});

		/* Campos do formulário de consulta de duplicatas */

		$('input:[name=fatura-dup-cliente]').typeahead({source: result.consulta_clientes, items:1115});
		$('input:[name=fatura-dup-empresa]').typeahead({source: result.consulta_empresas, items:1115});
		$('input:[name=fatura-dup-tipoDoc]').typeahead({source: result.consulta_tipo_doc, items:1115});
	},"json");

});

function avancarInsereFatura(){
	$("div#faturasemnota").hide("slide", function(){
		$("div#tabelafaturas").css("display","block");
	});
}

function voltaInsereFatura(){
	$("div#tabelafaturas").hide("slide", function(){
		$("div#faturasemnota").css("display","block");
	});
}

function novaFaturaVolta(){
	$("div#faturasemnota").hide("slide", function(){
		$("div#faturaconsultarduplicatas").css("display","block");
	});
}

function novaFatura(){
	$("div#faturaconsultarduplicatas").hide("slide", function(){
		$("div#faturasemnota").css("display","block");
	});
}

function voltarConsultaFatura(){
	$("div#localizarfatura").hide("slide", function(){
		$("div#faturaconsultarduplicatas").css("display","block");
	});
}

function avancarConsultaFatura(){
	$("div#faturaconsultarduplicatas").hide("slide", function(){
		$("div#localizarfatura").css("display","block");
	});
}


function criaListaConsultaFaturas() {

	var tipo_cliente = $('select:[name=fatura-dup-tipo_cliente]').val();
	var cliente = $('input:[name=fatura-dup-cliente]').val();
	var empresa = $('input:[name=fatura-dup-empresa]').val();
	var tipoDoc = $('input:[name=fatura-dup-tipoDoc]').val();
	var moeda = $('select:[name=fatura-dup-moeda]').val();
	var status = $('select:[name=fatura-dup-status]').val();
	var selecionado = $('select:[name=fatura-dup-selecionado_por]').val();
	var data_inicio = $('input:[name=fatura-dup-data_inicio]').val();
	var data_fim = $('input:[name=fatura-dup-data_fim]').val();
	var ordenado = $('select:[name=fatura-dup-ordenado_por]').val();
	var order = '';

    if (ordenado == 1) {
        order = "nome_cliente";
    } else if (ordenado == 2) {
        order = "nome_empresa";
    } else if (ordenado == 3) {
        order = "nome_tipo_doc";
    } else if (ordenado == 4) {
        order = "data_lancamento";
    } else if (ordenado == 5) {
        order = "data_emissao";
    } else if (ordenado == 6) {
        order = "nome_moeda";
    } else if (ordenado == 7) {
        order = "nome_banco";
    }

	var tipoForm = "consulta_lista_faturas";

	if(cliente == "") {
		alert("Por Favor, informe o Cliente!");
		return false;
	} else if(empresa == "") {
		alert("Por Favor, informe a Empresa!");
		return false;
	} else if(tipoDoc == "") {
		alert("Por Favor, o tipo de Documento!");
		return false;
	} else if(data_inicio == "") {
		alert("Por Favor, informe a Data Inicial!");
		return false;
	} else if(data_fim == "") {
		alert("Por Favor, informe a Data Final!");
		return false;
	}

	$.post("index.php?module=faturas&tmp=1",{
		tipoForm:tipoForm,
		cliente:cliente,
		tipo_cliente:tipo_cliente,
		empresa:empresa,
		tipoDoc:tipoDoc,
		moeda:moeda,
		status:status,
		selecionado:selecionado,
		data_inicio:data_inicio,
		data_fim:data_fim,
		ordenado:ordenado
	},function(resultado){
		console.log(resultado);
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
		while(i < result.length) {
			if(i == 0) {
				aux = result[i][order];
			}

			if(result[i]['nome_plano_contas'] == null) {
				result[i]['nome_plano_contas'] = '';
			}
			if(result[i]['data_emissao'] == null) {
				result[i]['data_emissao'] = '';
			}
			if(aux != result[i][order]) {
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
						if (ordenado == 1) {
							strhtml += subtotal[k]['nome']; // cliente_cnpj
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 2) {
							strhtml += subtotal[k]['nome']; //empresa
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 4) {
							strhtml += subtotal[k]['data_lancamento'];
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 5) {
							strhtml += subtotal[k]['data_emissao'];
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += number_format(subtotal[k]['total'], 2, ',', '.');
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 6) {
							strhtml += subtotal[k]['nome']; //moeda
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 7) {
							strhtml += subtotal[k]['nome']; //banco
						} else {
							strhtml += '';
						}
					strhtml += '</td>';
					strhtml += '<td>';
						if (ordenado == 3) {
							strhtml += subtotal[k]['nome']; //nome tipo_doc
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
				
				strhtml += '<tr name="consulta-dup-lista_duplicatas-tr" onclick="criaListaConsultaFaturaParcelasModal('+ result[i]['id'] + ');" >';
					strhtml += '<td>';
						strhtml += result[i]['nome_status'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += 'DSN';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['numero_fatura'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['nome_cliente'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['nome_empresa'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['data_lancamento'];
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['data_emissao'];
					strhtml += '</td>';
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += number_format(result[i]['total'], 2, ',', '.');
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
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += result[i]['nome_pessoa'] + ' ' + result[i]['sobrenome_pessoa'];
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
				if (ordenado == 1) {
					strhtml += subtotal[k]['nome']; // cliente_cnpj
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 2) {
					strhtml += subtotal[k]['nome']; //empresa
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 4) {
					strhtml += subtotal[k]['data_lancamento'];
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 5) {
					strhtml += subtotal[k]['data_emissao'];
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(subtotal[k]['total'], 2, ',', '.');
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 6) {
					strhtml += subtotal[k]['nome']; //moeda
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 7) {
					strhtml += subtotal[k]['nome']; //banco
				} else {
					strhtml += '';
				}
			strhtml += '</td>';
			strhtml += '<td>';
				if (ordenado == 3) {
					strhtml += subtotal[k]['nome']; //nome tipo_doc
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

		$('#fatura-dup-lista_parcelas').html(strhtml);
		avancarConsultaFatura();

	},"json");

}

function criaListaFaturas() {

	var numero_fatura = $('input:[name=insere-fatura-num_fatura]').val();
	var tipo_cliente = $('select:[name=insere-fatura-tipo_cliente]').val();
	var cliente = $('input:[name=insere-fatura-cliente]').val();
	var empresa = $('input:[name=insere-fatura-empresa]').val();
	var tipoDoc = $('input:[name=insere-fatura-tipoDoc]').val();
	var banco = $('input:[name=insere-fatura-banco]').val();
	var moeda = $('input:[name=insere-fatura-moeda]').val();
	var plano_contas = $('input:[name=insere-fatura-plano_contas]').val();
	var valor = $('input:[name=insere-fatura-valor]').val();
	var num_parcelas = $('input:[name=insere-fatura-num_parcelas]').val();
	var data_emissao = $('input:[name=insere-fatura-data_emissao]').val();
	var data_venc = $('input:[name=insere-fatura-data_vencimento]').val();
	var intervalo_parcelas = $('input:[name=insere-fatura-intervalo_parcelas]').val();
	var obs = $('textarea:[name=insere-fatura-obs]').val();

	if(numero_fatura == "") {
		alert("Por Favor, Informe O Número da Fatura!");
		return false;
	} else if(tipo_cliente == "") {
		alert("Por Favor, Informe Informe o Tipo de Cliente!");
		return false;
	} else if(cliente == "") {
		alert("Por Favor, Informe o Cliente!");
		return false;
	} else if(empresa == "") {
		alert("Por Favor, Informe a Empresa!");
		return false;
	} else if(tipoDoc == "") {
		alert("Por Favor, Informe o Tipo de Documento!");
		return false;
	} else if(banco == "") {
		alert("Por Favor, Informe o Banco!");
		return false;
	} else if(moeda == "") {
		alert("Por Favor, Informe a Moeda!");
		return false;
	} else if(plano_contas == "") {
		alert("Por Favor, Informe o Plano de contas!");
		return false;
	} else if(valor == "") {
		alert("Por Favor, Informe o Valor!");
		return false;
	} else if(num_parcelas == "") {
		alert("Por Favor, Informe o Número de Parcelas!");
		return false;
	} else if(data_emissao == "") {
		alert("Por Favor, Informe a Data de Emissão!");
		return false;
	} else if(data_venc == "") {
		alert("Por Favor, Informe a Data de Vencimento!");
		return false;
	} else if(intervalo_parcelas == "") {
		alert("Por Favor, Informe o intervalo entre o vencimento das Parcelas.!");
		return false;
	}

	var tipoForm = "fatura_data_parcelas";

	valor = valor.replace(".","");
	valor = valor.replace(",",".");

	$.post("index.php?module=faturas&tmp=1",{tipoForm:tipoForm,num_parcelas:num_parcelas,intervalo_parcelas:intervalo_parcelas,data_venc:data_venc},function(result){
		var datas = result;
		var strhtml = '';
		var valor_parcela = '';
		var vencimento_parcela = '';

		for(var i = 0; i < num_parcelas; i++){

			valor_parcela = parseFloat(valor).toFixed(2) / parseFloat(num_parcelas);
			valor_parcela =  valor_parcela + ",00";

			vencimento_parcela = data_venc + (i * intervalo_parcelas);

			strhtml += '<tr name="insere-fatura-lista_parcelas-tr" >';

			strhtml += '<td>';
				strhtml += i + 1;
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += datas[i];
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += moeda;
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += valor_parcela;
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += 'A';
			strhtml += '</td>';

			strhtml += '</tr>';
		}

		$('#insere-fatura-lista_parcelas').html(strhtml);
		avancarInsereFatura();

	},"json");

}

function criaListaConsultaFaturaParcelasModal(id) {

	var tipoForm = "busca_parcelas_modal_consulta_fatura";

	$.post("index.php?module=duplicata&tmp=1",{tipoForm:tipoForm,id:id},function(result){

		var strhtml = '';

		for(var i = 0; i < result.length; i++){

			strhtml += '<tr name="consulta-dup-lista_duplicatas_modal-tr">';

				strhtml += '<td>';
					strhtml += result[i]['numero'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += '<span onclick="editaDataParcela($(this),' + result[i]['id'] + ')">';
						strhtml += result[i]['data_vencimento'];
					strhtml += '</span>';
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += '<span onclick="editaValorParcela($(this),' + result[i]['id'] + ')">';
						strhtml += result[i]['valor'];
					strhtml += '</span>';
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += '0.00';
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += '0.00';
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += '0.00';
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['valor'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['valor'];
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += result[i]['nome_status'];
				strhtml += '</td>';

			strhtml += '</tr>';
		}

		$('#consulta-dup-lista_parcelas_modal').html(strhtml);
		abrir_modal('pesquisar_duplicata');

	},"json");
}

function insereFaturas() {

	var tipoForm = "insere_faturas";

	var numero_fatura = $('input:[name=insere-fatura-num_fatura]').val();
	var tipo_cliente = $('select:[name=insere-fatura-tipo_cliente]').val();
	var cliente = $('input:[name=insere-fatura-cliente]').val();
	var empresa = $('input:[name=insere-fatura-empresa]').val();
	var tipoDoc = $('input:[name=insere-fatura-tipoDoc]').val();
	var banco = $('input:[name=insere-fatura-banco]').val();
	var moeda = $('input:[name=insere-fatura-moeda]').val();
	var plano_contas = $('input:[name=insere-fatura-plano_contas]').val();
	var valor = $('input:[name=insere-fatura-valor]').val();
	var num_parcelas = $('input:[name=insere-fatura-num_parcelas]').val();
	var data_emissao = $('input:[name=insere-fatura-data_emissao]').val();
	var data_vencimento = $('input:[name=insere-fatura-data_vencimento]').val();
	var intervalo_parcelas = $('input:[name=insere-fatura-intervalo_parcelas]').val();
	var obs = $('textarea:[name=insere-fatura-obs]').val();

	var data = Array();

	$("table tr:[name=insere-fatura-lista_parcelas-tr]").each(function(i, v){
		data[i] = Array();
		$(this).children('td').each(function(ii, vv){
			data[i][ii] = $(this).text();
		});
	})

	$.post("index.php?module=faturas&tmp=1",{tipoForm:tipoForm,numero_fatura:numero_fatura,tipo_cliente:tipo_cliente,cliente:cliente,empresa:empresa,tipoDoc:tipoDoc,banco:banco,moeda:moeda,plano_contas:plano_contas,valor:valor,num_parcelas:num_parcelas,data_emissao:data_emissao,data_vencimento:data_vencimento,intervalo_parcelas:intervalo_parcelas,obs:obs,parcelas:data},function(result){

		if(result == 1) {
			alert("Operação Realizada com sucesso !");
			cleanFormInsereFaturas();
		} else {
			alert("Ocorreu um ERRO ao realizar a Operação !");
		}

		voltaInsereFatura();

	},"json");
}

function cleanFormInsereFaturas() {

	$('input:[name=insere-fatura-num_fatura]').val("");
	$("select:[name=insere-fatura-tipo_cliente] option[value='0']").prop('selected', true);
	$('input:[name=insere-fatura-cliente]').val("");
	$('input:[name=insere-fatura-cod_cliente]').val("");
	$('input:[name=insere-fatura-empresa]').val("");
	$('input:[name=insere-fatura-cod_empresa]').val("");
	$('input:[name=insere-fatura-tipoDoc]').val("");
	$('input:[name=insere-fatura-cod_tipoDoc]').val("");
	$('input:[name=insere-fatura-banco]').val("");
	$('input:[name=insere-fatura-cod_banco]').val("");
	$('input:[name=insere-fatura-moeda]').val("");
	$('input:[name=insere-fatura-cod_moeda]').val("");
	$('input:[name=insere-fatura-plano_contas]').val("");
	$('input:[name=insere-fatura-cod_plano_contas]').val("");
	$('input:[name=insere-fatura-valor]').val("");
	$('input:[name=insere-fatura-num_parcelas]').val("");
	$('input:[name=insere-fatura-data_emissao]').val("");
	$('input:[name=insere-fatura-data_vencimento]').val("");
	$('input:[name=insere-fatura-intervalo_parcelas]').val("");
	$('textarea:[name=insere-fatura-obs]').val("");
}

function cleanFormConsultaFaturas() {

	$('input:[name=fatura-dup-cliente]').val("");
	$('input:[name=fatura-dup-cod_fornecedor]').val("");
	$('input:[name=fatura-dup-empresa]').val("");
	$('input:[name=fatura-dup-cod_empresa]').val("");
	$('input:[name=fatura-dup-tipoDoc]').val("");
	$('input:[name=fatura-dup-cod_tipoDoc]').val("");
	$('input:[name=fatura-dup-moeda]').val("");
	$('input:[name=fatura-dup-data_inicio]').val("");
	$('input:[name=fatura-dup-data_fim]').val("");

	$("select:[name=fatura-dup-tipo_cliente] option[value='1']").prop('selected', true);
	$("select:[name=fatura-dup-moeda] option[value='0']").prop('selected', true);
	$("select:[name=fatura-dup-status] option[value='0']").prop('selected', true);
	$("select:[name=fatura-dup-ordenado_por] option[value='1']").prop('selected', true);
	$("select:[name=fatura-dup-selecionado_por] option[value='1']").prop('selected', true);

}

function buscaFaturaPorCodigo(flag, form) {

	var tipoForm = "busca_por_codigo";
	var name = "";
	var cod = "";

	if(form == "insere_fatura") {
		name = "insere-fatura";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();
		if(cod == '00')
			return false;
	} else if(form == "consulta_fatura") {
		name = "fatura-dup";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();
	} else {
		return false;
	}

	$.post("index.php?module=faturas&tmp=1",{tipoForm:tipoForm,flag:flag,cod:cod},function(result){
		$('input:[name=' + name + '-'+ flag + ']').val(result);
	},"json");
	
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

function imprimeFatura(){
    window.open('index2.php?module=relatoriofaturas', '_blank');
}

//function buscaPorCodigoFornecedor() {
//
//	var tipoForm = "busca_por_codigo_fornecedor";
//
//	var cod = $('input:[name=insere-dup-cod_fornecedor]').val();
//
//	$.post("index.php?module=duplicata&tmp=1",{tipoForm:tipoForm,cod:cod},function(result){
//		$('input:[name=insere-dup-fornecedor]').val(result.nome_fornecedor);
//		$('input:[name=insere-dup-plano_contas]').val(result.nome_plano);
//	},"json");
//	
//}
//
//function buscaPlanoContas() {
//
//	var tipoForm = "busca_plano_contas";
//	var fornecedor = $('input:[name=insere-dup-fornecedor]').val();
//
//	$.post("index.php?module=duplicata&tmp=1",{tipoForm:tipoForm,fornecedor:fornecedor},function(result){
//		$('input:[name=insere-dup-plano_contas]').val(result);
//	},"json");
//
//}
//
//function editaDataParcelaBlur(field, idParcela) {
//
//	var tipoForm = "edita_campo";
//	var flag = "data";
//
//	var text = field.val();
//	var id = field.attr('id');
//
//	if(text == ""){
//		alert("Campo em branco!");
//	} else {
//		field.parent().html('<span id='+id+' onclick="editaDataParcela($(this),' + idParcela + ')">'+text+'</span>');
//
//		$.post("index.php?module=duplicata&tmp=1",{tipoForm:tipoForm,texto:text,idParcela:idParcela,flag:flag},function(result){
//
//			alert("Data Alterada com Sucesso!");
//		},"json");
//	}
//}
//
//function editaDataParcela(field, idParcela) {
//
//	var text = $.trim(field.text());
//	var id = field.attr('id');
//	var inputbox = "<input id='"+id+"' data-mask='99/99/9999' style='width: 150px;' type='text' onblur='editaDataParcelaBlur($(this)," + idParcela + ")' value=\""+text+"\"/>";
//	field.parent().html(inputbox);
//	$("input#"+id).focus();
//}
//
//function editaValorParcelaBlur(field, idParcela) {
//
//	var tipoForm = "edita_campo";
//	var flag = "valor";
//
//	var text = field.val();
//	var id = field.attr('id');
//
//	if(text == ""){
//		alert("Campo em branco!");
//	} else {
//		field.parent().html('<span id='+id+' onclick="editaValorParcela($(this),' + idParcela + ')">'+text+'</span>');
//
//		$.post("index.php?module=duplicata&tmp=1",{tipoForm:tipoForm,texto:text,idParcela:idParcela,flag:flag},function(result){
//
//			alert("Valor Alterado com Sucesso!");
//		},"json");
//	}
//}
//
//function editaValorParcela(field, idParcela) {
//
//	var text = $.trim(field.text());
//	var id = field.attr('id');
//	var inputbox = "<input id='"+id+"' onKeyPress=\"return(MascaraMoeda(this,'.',',',event))\" style='width: 150px;' type='text' onblur='editaValorParcelaBlur($(this)," + idParcela + ")' value=\""+text+"\"/>";
//	field.parent().html(inputbox);
//	$("input#"+id).focus();
//}

