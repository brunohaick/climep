function avancarExtrato() {
    $("div#extrato_form").hide("slide", function() {
        $("div#extrato").show();
    });
}

function voltarExtrato() {
    $("div#extrato").hide("slide", function() {
        $("div#extrato_form").show();
    });
}

function extratoFinanceiro() {
    var conta_corrente = $("select[name='extrato-conta_corrente']").val();
    var data_inicio = $('input:[name=extrato-data_inicio]').val();
    var data_fim = $('input:[name=extrato-data_fim]').val();
    if (data_inicio == "") {
        alert("Por Favor, informe a Data Inicial!");
        return false;
    } else if (data_fim == "") {
        alert("Por Favor, informe a Data Final!");
        return false;
    }

    var tipoForm = "extrato_financeiro";
    $.post("index.php?module=extrato&tmp=1",
	{
		tipoForm: tipoForm,
		conta_corrente: conta_corrente,
		data_inicio: data_inicio,
		data_fim: data_fim
	},function(result) {

        var strhtml = '';
        if (result == null) {
            alert("Não Há Registros");
            return false;
        }

        strhtml += '<table>';
        strhtml += '<thead>';
        strhtml += '</thead>';
        strhtml += '<tbody id="extrato-lista_parcelas">';
//                strhtml += '<tr>';
//                strhtml += '<th> DATA </th>';
//                strhtml += '<th> DOCUMENTO </th>';
//                strhtml += '<th> OPERAÇÃO </th>';
//                strhtml += '<th> DEBITO </th>';
//                strhtml += '<th> CREDITO </th>';
//                strhtml += '<th> SALDO </th>';
//                strhtml += '</tr>';
        console.log(result);
        strhtml += '<tr>';
			strhtml += '<td>';
				strhtml += result['fatura'][0]['data'];
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += 'Saldo Anterior (' + result['fatura'][0]['data'] + ')';
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(0, 2, ',', '.');
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(0, 2, ',', '.');
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(result['saldo'], 2, ',', '.');
			strhtml += '</td>';
        strhtml += '</tr>';
        var saldo = result['saldo'];
        var subtotalCredito = 0;
        var totalCredito = 0;
        var subtotalDebito = 0;
        var totalDebito = 0;
        for (var j = 0; j < result['fatura'].length; j++) {
            //Instancia das variaveis nescessarias
            var valor = result['fatura'][j]['valor'];
            var data = result['fatura'][j]['data'];
            var documento = result['fatura'][j]['documento'];
            var fornecedor = result['fatura'][j]['fornecedor'];
            var io = result['fatura'][j]['I/O'];
            if (io === "E") {
                saldo = parseFloat(saldo) + parseFloat(valor);
                subtotalCredito = subtotalCredito + parseFloat(valor);
                totalCredito = totalCredito+ parseFloat(valor);
            } else {
                saldo = parseFloat(saldo) - parseFloat(valor);
                subtotalDebito = subtotalDebito + parseFloat(valor);
                totalDebito = totalDebito+ parseFloat(valor);
            }
            strhtml += '<tr>';
				strhtml += '<td>';
					strhtml += data;
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += documento;
				strhtml += '</td>';
				strhtml += '<td>';
					strhtml += fornecedor;
				strhtml += '</td>';
            if (io === "E") {
				strhtml += '<td style=\'text-align:right;\'>';
					strhtml += number_format(0, 2, ',', '.');
				strhtml += '</td>';
				strhtml += '<td style=\'text-align:right;\'>';
					strhtml += number_format(valor, 2, ',', '.');
				strhtml += '</td>';

//                strhtml += '<td>';
//                strhtml += parseFloat(valor).toFixed(2);
//                strhtml += '</td>';
            }else{
				strhtml += '<td style=\'text-align:right;\'>';
					strhtml += number_format(valor, 2, ',', '.');
				strhtml += '</td>';
//                strhtml += '<td>';
//                strhtml += parseFloat(valor).toFixed(2);
//                strhtml += '</td>';
				strhtml += '<td style=\'text-align:right;\'>';
					strhtml += number_format(0, 2, ',', '.');
				strhtml += '</td>';
            }
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(saldo, 2, ',', '.');
			strhtml += '</td>';

//            strhtml += '<td>';
//            strhtml += parseFloat(saldo).toFixed(2);
//            strhtml += '</td>';
            strhtml += '</tr>';
            if (j < (result['fatura'].length - 1) && result['fatura'][j + 1]['data'] !== null && result['fatura'][j + 1]['data'] !== undefined && data != result['fatura'][j + 1]['data']) {
                //Precisa da linha do subtotal do dia
                strhtml += '<tr style=\'font-weight: bold\'>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += 'Sub Total ('+data+') :';
					strhtml += '</td>';
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += number_format(subtotalDebito, 2, ',', '.');
					strhtml += '</td>';
					strhtml += '<td style=\'text-align:right;\'>';
						strhtml += number_format(subtotalCredito, 2, ',', '.');
					strhtml += '</td>';

//					strhtml += '<td>';
//					strhtml += parseFloat(subtotalDebito).toFixed(2);
//					strhtml += '</td>';
//					strhtml += '<td>';
//					strhtml += parseFloat(subtotalCredito).toFixed(2);
//					strhtml += '</td>';
					strhtml += '<td>';
						strhtml += '';
					strhtml += '</td>';
				strhtml += '</tr>';
					//linha em branco
				strhtml += '<tr style="height: 15px">';
						strhtml += '<td> </td>';
						strhtml += '<td> </td>';
						strhtml += '<td> </td>';
						strhtml += '<td> </td>';
						strhtml += '<td> </td>';
						strhtml += '<td> </td>';
                strhtml += '</tr>';
                subtotalCredito = 0;
                subtotalDebito = 0;
            }
        }

        strhtml += '<tr style=\'font-weight: bold\'>';
			strhtml += '<td>';
				strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
				strhtml += 'Total Geral :';
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(totalDebito, 2, ',', '.');
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(totalCredito, 2, ',', '.');
			strhtml += '</td>';

//        strhtml += '<td>';
//        strhtml += totalDebito.toFixed(2);
//        strhtml += '</td>';
//        strhtml += '<td>';
//        strhtml += totalCredito.toFixed(2);
//        strhtml += '</td>';
			strhtml += '<td>';
				strhtml += '';
			strhtml += '</td>';
		strhtml += '</tr>';
		strhtml += '<tr>';
			strhtml += '<td>.</td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
        strhtml += '</tr>';
			//Total Geral
			strhtml += '<tr style=\'font-weight: bold\'>';
			strhtml += '<td>';
			strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
			strhtml += '';
			strhtml += '</td>';
			strhtml += '<td>';
			strhtml += 'Total Final :';
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(totalDebito, 2, ',', '.');
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(totalCredito, 2, ',', '.');
			strhtml += '</td>';
			strhtml += '<td style=\'text-align:right;\'>';
				strhtml += number_format(saldo, 2, ',', '.');
			strhtml += '</td>';


//        strhtml += '<td>';
//        strhtml += totalDebito.toFixed(2);;
//        strhtml += '</td>';
//        strhtml += '<td>';
//        strhtml += totalCredito.toFixed(2);
//        strhtml += '</td>';
//        strhtml += '<td>';
//        strhtml += saldo.toFixed(2) + ',00';
//        strhtml += '</td>';
        strhtml += '</tr>';
        strhtml += '<tr>';
			strhtml += '<td>.</td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
			strhtml += '<td></td>';
        strhtml += '</tr>';
        strhtml += '</tbody>';
        strhtml += '</table>';
//    
//    tipoForm = "imprime_extrato";
//    $.post("index.php?module=extrato&tmp=1", {tipoForm: tipoForm, strhtml: strhtml}, function(result) {
//    });
        //window.open('index2.php?module=extrato','_blank');

        $('#extrato-lista_parcelas').html(strhtml);
        avancarExtrato();
        //cleanFormConsultaBaixaCrModal();
    }
    , "json");
    
}

function imprimirExtrato() {
    window.open('index2.php?module=relatoriofluxocaixa', '_blank');
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
