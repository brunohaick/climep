$(document).ready(function() {

	var tipoForm = "convenio_busca";

	$.post("index.php?module=consultaconvenio&tmp=1",{tipoForm:tipoForm},function(result){

		/* Campos do formulário de lancamento de convenios */

		$('input:[name=insereconv-empresa]').typeahead({source: result.empresas, items:1115});
		$('input:[name=insereconv-convenio]').typeahead({source: result.convenios, items:1115});

		/* Campos do formulário de consulta de convenios */

		//$('input:[name=conv-consulta-fatura-empresa]').typeahead({source: result.consulta_empresas, items:1115});
		$('input:[name=nomeConvenio]').typeahead({source: result.consulta_convenios, items:1115});
		$('input:[name=consultaconv-convenio]').typeahead({source: result.consulta_convenios, items:1115});
	},"json");

});

function novaLancamentoconveniado(){
	$("div#consultaconvenio").hide("slide", function(){
		$("div#lancamentoconveniado").css("display","block");
	});
}

function consultaconveniado(){
	$("div#lancamentoconveniado").hide("slide", function(){
		$("div#consultaconvenio").show();
	});
}

function ida(){
	$("div#consultaconvenio").hide("slide", function(){
		$("div#consultaconvenio").css("display","hide");
		$("div#consultaconveniotabela").css("display","block");
	});
}

function volta(){
	$("div#consultaconveniotabela").hide("slide", function(){
		$("div#consultaconveniotabela").css("display","hide");
		$("div#consultaconvenio").css("display","block");
	});
}

function buscaConsultaConvenioPorCodigo(flag, form) {

	var tipoForm = "busca_por_codigo";
	var name = "";
	var cod = "";

	if(form == "insere_convenio") {
		name = "insereconv";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();

		if(cod == "0" || cod == "00")
			return false;

	} else if(form == "consulta_convenio") {
		name = "consultaconv";
		cod = $('input:[name=' + name + '-cod_'+ flag +']').val();

		if(cod == "0" || cod == "00") {
			$('input:[name=' + name + '-'+ flag + ']').val('TODOS');
			return false;
		}

	} else {
		return false;
	}

	$.post("index.php?module=consultaconvenio&tmp=1",{tipoForm:tipoForm,flag:flag,cod:cod},function(result){
		$('input:[name=' + name + '-'+ flag + ']').val(result);
	},"json");
	
}

function limparConsultaConvenio() {
    $('input:[name=consultaconv-convenio]').val("");
    $('input:[name=consultaconv-empresa]').val("");
    $('select:[name=consultaconv-tipoLancamento]').val("");
    $('select:[name=consultaconv-situacaoConsulta]').val("");
    $('select:[name=consultaconv-selecionado]').val("");
    $('select:[name=consultaconv-ordenado]').val("");
    $('select:[name=consultaconv-usuario]').val("");
    $('input:[name=consultaconv-data_inicio]').val("");
    $('input:[name=consultaconv-data_fim]').val("");
}

function consultaConvenio() {

	var nomeConvenio = $('input:[name=consultaconv-convenio]').val();
	var nomeEmpresa = $('input:[name=consultaconv-empresa]').val();
	var tipoLancamento = $('select:[name=consultaconv-tipoLancamento]').val();
	var situacaoConsulta = $('select:[name=consultaconv-situacaoConsulta]').val();
	var selecionado = $('select:[name=consultaconv-selecionado]').val();
	var ordenado = $('select:[name=consultaconv-ordenado]').val();
	var usuario = $('select:[name=consultaconv-usuario]').val();
	var data_inicio = $('input:[name=consultaconv-data_inicio]').val();
	var data_fim = $('input:[name=consultaconv-data_fim]').val();
	var tipoForm = "localizaTerceiros";

	if(ordenado == 1) {
//		order = "p.codigo, gp.data";
		order = "codigo";
	} else if(ordenado == 2) {
//		order = "conv.nome";
		order = "convenio";
	} else if(ordenado == 3) {
//		order = "med.nome";
		order = "medico";
	} else if(ordenado == 4) {
//		order = "gp.data";
		order = "data";
	} else if(ordenado == 5) {
//		order = "dep.nome, gp.data";
		order = "depnome";
	} else if(ordenado == 6) {
//		order = "conv.nome, p.codigo";
		order = "ceonvenio";
	} else if(ordenado == 7) {
//		order = "med.nome, dep.nome";
		order = "medico";
	} else if(ordenado == 8) {
//		order = "gp.valor";
		order = "valor";
	}

	if(nomeConvenio == "") {
		alert("Por Favor, informe o Convenio!");
		return false;
	} else if(data_inicio == "") {
		alert("Por Favor, informe a Data Inicial!");
		return false;
	} else if(data_fim == "") {
		alert("Por Favor, informe a Data Final!");
		return false;
	}

	$.post('index.php?module=consultaconvenio&tmp=1', {
                tipoForm:tipoForm,
                nomeConvenio:nomeConvenio,
                nomeEmpresa:nomeEmpresa,
                tipoLancamento:tipoLancamento,
                situacaoConsulta:situacaoConsulta,
                selecionado:selecionado,
                ordenado:ordenado,
                usuario:usuario,
                data_inicio:data_inicio,
                data_fim:data_fim
	},function(resultado){
		console.log(resultado);
		if(resultado['lista'] == null && resultado['subtotal'] == null) {
			alert("Não há registros !");
			return false;
		}

		var strhtml = '';
		var aux = '';
		var k = 0;
		var i = 0;
		result = resultado.lista;
		subtotal = resultado.subtotal;
		$("tbody#tbody-consulta").html("");
		while(i < result.length) {
			if(i == 0) {
				aux = result[i][order];
			}

			if(aux != result[i][order]) {
				console.log('total == '+aux);
				
				strhtml += "<tr style=\'font-weight: bold\' name='table-color' class='dayhead '>";
					strhtml += "<th> ";
						strhtml += '';
					strhtml += "</th>";
					strhtml += "<th> ";
						if (ordenado == 2 || ordenado == 6) {
							strhtml += subtotal[k]['nome']; //CONVENIO
						} else {
							strhtml += '';
						}
					strhtml += "</th>";
					strhtml += "<th> ";
						strhtml += '';
					strhtml += "</th>";
					strhtml += "<th> ";
						if (ordenado == 5 || ordenado == 7) {
							strhtml += subtotal[k]['nome']; //DEPENDENTE
						} else {
							strhtml += '';
						}
					strhtml += "</th>";
					strhtml += "<th> ";
						strhtml += '';
					strhtml += "</th>";
					strhtml += "<th>";
						strhtml += '';
						strhtml += "</th>";
					strhtml += "<th> ";
						if (ordenado == 1 || ordenado == 4 || ordenado == 5) {
							strhtml += subtotal[k]['data'];
						} else {
							strhtml += '';
						}
					strhtml += "</th>";
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += number_format(subtotal[k]['valor'], 2, ',', '.');
					strhtml += '</td>';
					strhtml += "<th> ";
						if (ordenado == 3 || ordenado == 7) {
							strhtml += subtotal[k]['medico'];
						} else {
							strhtml += '';
						}
					strhtml += "</th>";
					strhtml += "<th> ";
						strhtml += '';
					strhtml += "</th>";
				strhtml += "</tr>";

				aux = result[i][order];
				k++;
			} else {
				strhtml += "<tr>";
					strhtml += "<th> " + result[i]['numero_da_guia'] + " </th>";
					strhtml += "<th> " + result[i]['convenio'] + " </th>";
					strhtml += "<th> " + result[i]['matricula'] + " </th>";
					strhtml += "<th> " + result[i]['depnome'] +"</th>";
					strhtml += "<th> " + result[i]['servico'] +" </th>";
					strhtml += "<th> CLIMEP </th>";
					strhtml += "<th> " + result[i]['data'] + " </th>";
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += number_format(result[i]['valor'], 2, ',', '.');
					strhtml += '</td>';
					strhtml += "<th> " + result[i]['medico'] + " </th>";
					strhtml += "<th> " + result[i]['usuario'] + " </th>";
				strhtml += "</tr>";

				i++;
			}
		}

		strhtml += "<tr style=\'font-weight: bold\' name='table-color' class='dayhead '>";
			strhtml += "<th> ";
				strhtml += '';
			strhtml += "</th>";
			strhtml += "<th> ";
				if (ordenado == 2 || ordenado == 6) {
					strhtml += subtotal[k]['nome']; //CONVENIO
				} else {
					strhtml += '';
				}
			strhtml += "</th>";
			strhtml += "<th> ";
				strhtml += '';
			strhtml += "</th>";
			strhtml += "<th> ";
				if (ordenado == 5 || ordenado == 7) {
					strhtml += subtotal[k]['nome']; //DEPENDENTE
				} else {
					strhtml += '';
				}
			strhtml += "</th>";
			strhtml += "<th> ";
				strhtml += '';
			strhtml += "</th>";
			strhtml += "<th>";
				strhtml += '';
				strhtml += "</th>";
			strhtml += "<th> ";
				if (ordenado == 1 || ordenado == 4 || ordenado == 5) {
					strhtml += subtotal[k]['data'];
				} else {
					strhtml += '';
				}
			strhtml += "</th>";
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(subtotal[k]['valor'], 2, ',', '.');
			strhtml += '</td>';
			strhtml += "<th> ";
				if (ordenado == 3 || ordenado == 7) {
					strhtml += subtotal[k]['medico'];
				} else {
					strhtml += '';
				}
			strhtml += "</th>";
			strhtml += "<th> ";
				strhtml += '';
			strhtml += "</th>";
		strhtml += "</tr>";

		$("tbody#tbody-consulta").html(strhtml);
		ida();
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

function imprimirLancamentosInclusos(){
    window.open('index2.php?module=relatoriolancamentosinclusos', '_blank');
}