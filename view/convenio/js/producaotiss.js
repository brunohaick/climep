$(document).ready(function() {

    var tipoForm = "convenio_busca";

    $.post("index.php?module=consultaconvenio&tmp=1", {tipoForm: tipoForm}, function(result) {
        $('#producaotiss_convenio_nome').typeahead({source: result.consulta_convenios, items: 1115});
    }, "json");

});

function avancaLista() {
    $("div#producaotiss").hide("slide", function() {
        $("div#producaotiss").css("display", "hide");
        $("div#producaotiss_lista").css("display", "block");
    });
}

function voltaLocaliza() {
    $("div#producaotiss_lista").hide("slide", function() {
        $("div#producaotiss_lista").css("display", "hide");
        $("div#producaotiss").css("display", "block");
    });
}

function buscaPorCodigo() {
    var idConvenio = $('input:[name=idConvenio]').val();
    var flag = "buscaConvenio";

    if (idConvenio == "0" || idConvenio == "00") {
        $('input:[name=nomeConvenio]').val("TODOS");
    } else {
        $.post(
                'index.php?module=producaotiss&tmp=1',
                {flag: flag, idConvenio: idConvenio},
        function(data) {
            $('input:[name=nomeConvenio]').val(data);
        }, "json"
                );
    }
}

function preencheProducao() {

    var convenio = $('#producaotiss_convenio_nome').val();
    var dataInicio = $('#producaotiss_data_inicio').val();
    var dataFim = $('#producaotiss_data_fim').val();
    var tipo = $('#producaotiss_tipo_procedimento option:selected').val();
    var ordenado = $('#producaotiss_ordenado option:selected').val();
    var flag = "buscaProducao";
    $.post('index.php?module=producaotiss&tmp=1', {
        flag: flag,
        tipo: tipo,
        dataFim: dataFim,
        dataInicio: dataInicio,
        convenio: convenio,
        ordenado: ordenado
    }, function(data) {
        strhtml = "";
        for (var i = 0; i < data.length; i++) {
            strhtml += "<tr class='white'>";
                strhtml += "<th style='text-align:left;'>" + data[i]['numero_da_guia'] + " </th>";
                strhtml += "<th style='text-align:left;'>" + data[i]['data_autorizacao'] + "</th>";
                strhtml += "<th style='text-align:left;'>" + data[i]['convenio'] + "</th>";
                strhtml += "<th style='text-align:left;'>" + data[i]['matricula'] + " </th>";
                strhtml += "<th style='text-align:left;'>" + data[i]['depnome'] + " </th>";
                strhtml += "<th style='text-align:left;'>" + data[i]['servico'] + " </th>";
                strhtml += "<th style='text-align:left;'> CLIMEP </th>";
                strhtml += "<th style='text-align:right;'>" + number_format(data[i]['valor'], 2, ',', '.') + " </th>";
            strhtml += "</tr>";
        }
        $("tbody#tbody-mapa").html(strhtml);
        avancaLista();
    }, "json");
}

function fechar() {
    document.location.href = "index.php?module=menuconvenio";
}

function geraXml() {

    var dataInicio = $('#geraxml_data_inicio').val();
    var dataFim = $('#geraxml_data_fim').val();
    var nlote = $('#geraxml_numero_lote').val();
    var ans = $('#geraxml_ans').val();
    var versao = $('#geraxml_versao').val();
    var codop = $('#geraxml_cod_op').val();
    var convenio = $('#geraxml_convenio').val();
    var nomeConvenio = $('#geraxml_convenio option:selected').text();
    var tipo = $("input[type='radio'][name='geraxml-radio']:checked").val();

    $.post('index.php?module=envioxmltiss&tmp=1', {
        flag: 'gerarxml',
        dataInicio: dataInicio,
        dataFim: dataFim,
        nlote: nlote,
        ans: ans,
        versao: versao,
        codop: codop,
        convenio: convenio,
	nomeConvenio: nomeConvenio,
        tipo: tipo
    }, function(data) {
        if (data) {
            var mywindow = window.open('', '_blank');
            mywindow.document.write("<pre>" + data.toString() + "</pre>");
            mywindow.document.close();
        }
    }, "json");

    //var numero_documento = $('input[name=baixacr-modal-numero_documento]').val();
    //var data_baixa = $('input[name=baixacr-modal-data_baixa]').val();
    ////var = $('input[baixacr-modal-compensacao]').val("");

    //if(data_operacao == "") {
    //	alert("Por Favor, informe a Data de Operação!");
    //	return false;
    //} else if(conta_corrente == "") {
    //	alert("Por Favor, informe a Conta Corrente!");
    //	return false; 

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