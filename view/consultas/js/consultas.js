$(document).ready(function() {
    $('input#filtro-prescricoes').keydown(function(event) {
        if (event.which === 13 || event.which === 10) {
            filtraPrescricoes();
        }
    });
    var $tmp = "";
});

function buscaBoletimImunobiologico(){
    var inicio = $('#imunobiologico_data_inicio').val();
    var fim = $('#imunobiologico_data_fim').val();
    console.log('Chamado POST de imunobiologia')
    $.post("index.php?module=consultas&tmp=1", {
        flag: 'boletimImunobiologico',
        inicio: inicio,
        fim: fim
    }, function(result) {
        result = $.parseJSON(result);
        //reinderizacao
        for(key in result){
             var row = $('<tr></tr>');
             for (i = 0; i < 10; i++) {
                 
             }
        }
    });
    console.log('depois do post imunobiologico, teste de syncronizaç�o');
}

function resetPrint() {
    $(".toprint").unbind().mousedown(function(e) {
        printTag = $(this);
    }).contextMenu({
        menu: 'list-print'
    });
}

function carregaClienteHistoricoConsulta(idCliente, idConsulta) {
    var idCliente = idCliente;
    var idConsulta = idConsulta;

    $.post("index.php?module=consultas&tmp=1", {
        flag: 'HistoricoConsulta',
        idConsulta: idConsulta,
        idCliente: idCliente
    }, function(result) {
        $("#cons-cm").val(result['cm']);
        $("#cons-kg").val(result['kg']);
        $("#cons-PA").val(result['PA']);
        $("#hidden-data").val(result['data']);
        $("textarea[name='text-consulta']").val(result['texto']);
        $("textarea[name='hipotese-diag']").val(result['hipotese_diag']);
        $("textarea[name='prescricao']").val(result['prescricao']);
        $("textarea[name='con-atestado']").val(result['atestado']);
        $("textarea[name='resultados-exames']").val(result['resultado_encaminhamentos']);
        var html = "";
        if (typeof result['prestador'] != 'undefined') {
            var rowId = parseInt($('#hiddenRowId').val());

            for (var j = 0; j < result['prestador'].length; j++) {
                html += "<tr id='prestserv' name=\"table-color\" class=\"dayhead teste-" + rowId + "\">";
                html += "<th onclick=\"removerReqEnc('teste-" + rowId + "','" + result['id'][j] + "');\">X</th>";
                html += "<th>" + result['data2'] + "</th>";
                html += "<th>" + result['prestador'][j] + "</th>";
                html += "</tr>";
                for (var i = 0; i < result['servicos'][j].length; i++) {
                    html += "<tr name=\"table-color\" class=\"dayhead teste-" + rowId + "\">";
                    html += "<th>   </th>";
                    html += "<th> - </th>";
                    html += "<th>" + result['servicos'][j][i] + "</th>";
                    html += "</tr>";

                }
                rowId = parseInt($('#hiddenRowId').val()) + 1;
                $('input#hiddenRowId').val(rowId);
            }
        }
        $("#tbody-prestador-servicos").html(html);
        resetPrint();
    }, "json");
}

function carregaTabelaHistoricoConsulta(idCliente) {
    $.post("index.php?module=consultas&tmp=1", {
        flag: 'TabelaHistoricoConsulta',
        idCliente: idCliente
    }, function(result) {
        $("tbody#hist-consulta").html(result);
    });

}

function carregaClienteFilaEspera(idCliente) {
    var idCliente = idCliente;

    $.post("index.php?module=consultas&tmp=1", {
        flag: 'FilaEspera',
        idCliente: idCliente
    }, function(result) {
        $("#nome-cliente").val(result['nome']);
        $("#medico-cliente").val(result['medico']);
        $("#matricula-cliente").val(result['matricula']);
        $("#idade-cliente").val(result['idade']);
        $("#email-cliente").val(result['email']);
        $("#cep-cliente").val(result['cep']);
        $("#endereco-cliente").val(result['endereco']);
        $("#telefone-cliente").val(result['telefone']);
        $("textarea[name='ant-pessoal']").val(result['antecedente_pessoal']);
        $("textarea[name='ant-familiar']").val(result['antecedente_familiar']);
        $("textarea[name='alergias']").val(result['alergias']);
        $("#peso-nascimento").val(result['peso_nascimento']);
        $("#idade-gestacional").val(result['idade_gestacional']);
        $("#apgar").val(result['apgar']);
        $("#con-gestacao option[value='" + result['gestacao'] + "']").prop('selected', true);
        $("#con-parto option[value='" + result['parto'] + "']").prop('selected', true);
        $("#con-medico-triagem option[value='" + result['medico_id'] + "']").prop('selected', true);

        if (result['coracaozinho-existe'] == 1) {
            for (i = 0; i < result['coracao-qp'].length; i++) {
                $("#coracao-QP-" + result['coracao-qp'][i]).prop('checked', true);
            }
            for (i = 0; i < result['coracao-hf'].length; i++) {
                $("#coracao-HF-" + result['coracao-hf'][i]).prop('checked', true);
            }
            $("#coracao-mao-direita").val(result['coracao-mao-direita']);
            $("#coracao-pe").val(result['coracao-pe']);
            $("#coracao-diferenca").val(result['coracao-diferenca']);
            $("#coracao-outros-exames").val(result['coracao-outros-exames']);
            $("#coracao-observacoes").val(result['coracao-observacoes']);

            if (result['coracao-conclusao'] == "normal") {
                $("#coracao-conclusao2").prop('checked', true);
            } else if (result['coracao-conclusao'] == "anormal") {
                $("#coracao-conclusao1").prop('checked', true);
            }
        }
        if (result['linguinha-existe'] == 1) {
            for (i = 0; i < result['linguinha-qp'].length; i++) {
                $("#linguinha-QP-" + result['linguinha-qp'][i]).prop('checked', true);
            }
            for (i = 0; i < result['linguinha-resultado'].length; i++) {
                $("#linguinha-result-" + result['linguinha-resultado'][i]).prop('checked', true);
            }
            $("#linguinha-outros-exames").val(result['linguinha-outros-exames']);
            $("#linguinha-observacoes").val(result['linguinha-observacoes']);
            if (result['linguinha-reavaliacao'] != '0000-00-00') {
                $("#linguinha-reavaliacao").val(result['linguinha-reavaliacao']);
                $("#linguinha-checkbox-data").prop('checked', true);
            }
            if (result['linguinha-checkbox-cn'] == 1) {
                $("#linguinha-checkbox-cn").prop('checked', true);
            }
            if (result['linguinha-checkbox-ca'] == 1) {
                $("#linguinha-checkbox-ca").prop('checked', true);
            }
        }

        if (result['orelhinha1-existe'] == 1) {
            $("#O1-OD-TEOAE").val(result['O1-OD-TEOAE']);
            $("#O1-OD-NOISE").val(result['O1-OD-NOISE']);
            $("#O1-OD-frequencia option[value='" + result['O1-OD-frequencia'] + "']").prop('selected', true);
            $("#O1-OE-TEOAE").val(result['O1-OE-TEOAE']);
            $("#O1-OE-NOISE").val(result['O1-OE-NOISE']);
            $("#O1-OE-frequencia option[value='" + result['O1-OE-frequencia'] + "']").prop('selected', true);
            $("#O1-meato-OD option[value='" + result['O1-meato-OD'] + "']").prop('selected', true);
            $("#O1-meato-OE option[value='" + result['O1-meato-OE'] + "']").prop('selected', true);
            $("#O1-localizacao option[value='" + result['O1-localizacao'] + "']").prop('selected', true);
            $("#O1-observacoes").val(result['O1-observacoes']);
            if (result['O2-reavaliacao'] != '0000-00-00') {
                $("#O1-reavaliacao").val(result['O1-reavaliacao']);
                $("#O1-check-reavaliacao").prop('checked', true);
            }
        }
        if (result['orelhinha2-existe'] == 1) {
            $("#O2-OD-conclusao option[value='" + result['O2-OD-conclusao'] + "']").prop('selected', true);
            $("#O2-OE-conclusao option[value='" + result['O2-OE-conclusao'] + "']").prop('selected', true);
            $("#O2-equipamento option[value='" + result['O2-equipamento'] + "']").prop('selected', true);
            $("#O2-meato-OD option[value='" + result['O2-meato-OD'] + "']").prop('selected', true);
            $("#O2-meato-OE option[value='" + result['O2-meato-OE'] + "']").prop('selected', true);
            $("#O2-cocleo option[value='" + result['O2-cocleo'] + "']").prop('selected', true);
            $("#O2-observacoes").val(result['O2-observacoes']);
            if (result['O2-reavaliacao'] != '0000-00-00') {
                $("#O2-reavaliacao").val(result['O2-reavaliacao']);
                $("#O2-check-reavaliacao").prop('checked', true);
            }
        }
        if (result['olhinho-existe'] == 1) {
            $("#olhinho-resultado-od option[value='" + result['olhinho-resultado-od'] + "']").prop('selected', true);
            $("#olhinho-resultado-oe option[value='" + result['olhinho-resultado-oe'] + "']").prop('selected', true);
            $("#olhinho-anotacoes-op").val(result['olhinho-anotacoes-op']);
            $("#olhinho-anotacoes-hf").val(result['olhinho-anotacoes-hf']);
            $("#olhinho-anotacoes-outros").val(result['olhinho-anotacoes-outros']);
            $("#olhinho-anotacoes-obs").val(result['olhinho-anotacoes-obs']);

            if (result['olhinho-reteste-data'] != '0000-00-00') {
                $("#olhinho-reteste-data").val(result['olhinho-reteste-data']);
                $("#olhinho-reteste-ok").prop('checked', true);
            }

            if (result['olhinho-sug-funcao-normal'] == 1) {
                $("#olhinho-sug-funcao-normal").prop('checked', true);
            }
            if (result['olhinho-sug-funcao-anormal'] == 1) {
                $("#olhinho-sug-funcao-anormal").prop('checked', true);
            }

        }
    }, "json");
}

function enviaDadosClienteConsulta() {
    var idCliente = $("#matricula-cliente").val();
    /*
     var antPessoal = $("textarea[name='ant-pessoal']").val();
     var antFamiliar = $("textarea[name='ant-familiar']").val();
     var alergias = $("textarea[name='alergias']").val();
     */
    var idadeGestacional = $("#idade-gestacional").val();
    var pesoNascimento = $("#peso-nascimento").val();
    var apgar = $("#apgar").val();
    var data = $("#hidden-data").val();
    var cm = $("#cons-cm").val();
    var kg = $("#cons-kg").val();
    var PA = $("#cons-PA").val();
    var texto = $("textarea[name='text-consulta']").val();
    var hipoteseDiag = $("textarea[name='hipotese-diag']").val();
    var prescricao = $("textarea[name='prescricao']").val();
    var medico = $("#con-medico-triagem").val();
    var gestacao = $("#con-gestacao").val();
    var parto = $("#con-parto").val();
    var atestado = $("textarea[name='con-atestado']").val();
    var resultados_exame = $("textarea[name='resultados-exames']").val();

    $.post("index.php?module=consultas&tmp=1", {
        idCliente: idCliente,
        antecedente_pessoal: antPessoal,
        antecedente_familiar: antFamiliar,
        alergias: alergias,
        idade_gestacional: idadeGestacional,
        peso_nascimento: pesoNascimento,
        data: data,
        apgar: apgar,
        texto: texto,
        prescricao: prescricao,
        hipotese_diag: hipoteseDiag,
        cm: cm,
        kg: kg,
        PA: PA,
        medico: medico,
        gestacao: gestacao,
        parto: parto,
        atestado: atestado,
        resultados_exame: resultados_exame,
        flag: 'consulta'
    }, function(result) {
        //$("div#xxx").html(result);
    });
}

function vacinacao() {

    var idTitular = $("#matricula-cliente").val();

    $.post("index.php?module=vacina&tmp=1", {idTitular: idTitular}, function(result) {
        $("div#ficha-vac-xxx").html(result);
        abrir_modal('ficha-vacina')
    });
}

function carregaHipotese(texto) {
    $("#modal-texto-hipotese").html(texto);
}

function defineHipotese() {
    $("#txareahip").val($("#modal-texto-hipotese").val());

    $.ajax({
        url: 'index.php?module=consultas&tmp=1',
        type: 'POST',
        data: {
            flag: 'salvarHipotese',
            hipotese: $("#modal-texto-hipotese").val(),
            idConsulta: consultasTela.getSelectedConsulta().consultaID
        },
        success: function(resposta) {
            resposta = $.parseJSON(resposta);
        }
    });
    $("#modal-texto-hipotese").val('');
    fechar_modal('boxes-hipotesediagnostica');
}
function cancelaHipoteses() {
    $("input:checkbox[name=hipoteses]:checked").each(function() {
        $(this).attr('checked', false);
    });
    $("#txareahip").html("");
    fechar_modal('boxes-hipotesediagnostica');
}

function limparModalHipoteses() {
    atualHipot.eraseCheck();
    atualHipot.eraseCheckText();
    $("tbody#tabela_HD_doencas").html('');
    $("#modal-texto-hipotese").val('');
}

function limparIdMedicoHipoteses() {
    $("select#medico-hipotese").val('');
}

function limparModalRequisicaoServico() {
    $("#textarea-requisicao").val('');
}

function filtraHipotese() {
    var idMedico = $("#medico-hipotese").val();
    var filtro = $("#filtro-hipotese").val().trim();
    if (idMedico != '') {
        $.post("index.php?module=consultas&tmp=1", {
            idMedico: idMedico,
            filtro: filtro,
            flag: 'filtroHipoteses'
        }, function(data) {
            $("tbody#tabela_HD_doencas").html('');
            if (data) {
                //console.log(data);
                data = data.replace(/\\/g, '');
                $("tbody#tabela_HD_doencas").html(data);
                atualHipot.resetFunctions();
                var arrayCheck = atualHipot.getCheck();
                for (var i = 0; i < atualHipot.getCheck().length; i++) {
                    $("input#hipoteses[name='" + arrayCheck[i] + "']").prop("checked", true);
                }
            }
        });
    } else {
        $("tbody#tabela_HD_doencas").html('');
        $("#modal-texto-hipotese").val('');
    }
}

consulta = '';
function filtraRecomendacoes() {
    var idMedico = $("#medico-recomendacoes").val();
    var filtro = $("#filtro-recomendacoes").val();
    $.post("index.php?module=consultas&tmp=1", {
        filtro: filtro,
        flag: 'filtroRecomendacoes',
    }, function(result) {
        $('#Tabela_corpo_recomendacoes').html('');
        result = JSON.parse(result);
        consulta = result;
        for (var i = 0; i < result.length; i++) {
            var input = $('<input />').attr('type', 'checkbox');
            input.attr('onchange', 'atualizaTextareaRecomendacoes()');
            input.attr('name', 'recomendacoesDoencas');
            input.val(result[i]['id']);
            var linha = $('<tr class="dayhead linhas-prescricoes" name="table-color" />').html($('<th align="center" />').html(input).append(result[i]['nome']));
            $('#Tabela_corpo_recomendacoes').append(linha);
        }
    });
}

function filtraCID10() {
    var filtro = $("#filtro-cid10").val();
    var consultaID = consultasTela.getSelectedConsulta().consultaID;
    $.post("index.php?module=consultas&tmp=1", {
        consulta_id: consultaID,
        filtro: filtro,
        flag: 'filtroCID'
    }, function(result) {
        $("#tabela-lista-cid10").html("");
        var html = "";
        console.log(result);
        for (var i = 0; i < result.length; i++) {
            html += "<tr name='table-color' class='dayhead linhas-prescricoes' >";
            if (result[i]['marcado'] === '0') {
                html += "<th> <input type='checkbox' name='cids' value='" + result[i]['idCID'] + " - " + result[i]['descricao'] + "' onChange='atualizaCID10(this)' /> ";
            } else {
                html += "<th> <input type='checkbox' name='cids' value='" + result[i]['idCID'] + " - " + result[i]['descricao'] + "' onChange='atualizaCID10(this)' checked/> ";
            }
            html += "" + result[i]['idCID'] + " - " + result[i]['descricao'] + "</th>";
            html += "</tr>";
        }
        $("tbody#tabela-lista-cid10").append(html);
    }, "json");
}
/***@function Created for first aproach to CID10 management.
 * @augments integer consultaID, captured via POST, contains Consult Object ID 
 * @returns {not defined},affect view directly  
 * */
function criaCID10() {
    var consultaID = consultasTela.getSelectedConsulta().consultaID;
    var flag = "buscaTextoCID";
    $.post("index.php?module=consultas&tmp=1", {consulta_id: consultaID, flag: flag}, function(result) {
        $("#tabela-lista-cid10").html("");
        var html = "";
        for (var i = 0; i < result.length; i++) {
            html += "<tr name='table-color' class='dayhead linhas-prescricoes' >";
            if (result[i]['marcado'] === '0') {
                html += "<th> <input type='checkbox' name='cids' value='" + result[i]['idCID'] + " - " + result[i]['descricao'] + "' onChange='atualizaCID10(this)' /> ";
            } else {
                html += "<th> <input type='checkbox' name='cids' value='" + result[i]['idCID'] + " - " + result[i]['descricao'] + "' onChange='atualizaCID10(this)' checked/> ";
            }
            html += "" + result[i]['idCID'] + " - " + result[i]['descricao'] + "</th>";
            html += "</tr>";
        }
        $("tbody#tabela-lista-cid10").append(html);
    }, "json");

}
function filtraPrescricoes() {
    var idMedico = $("#medico-prescricoes").val();
    var filtro = $("#filtro-prescricoes").val();
    $.post("index.php?module=consultas&tmp=1", {
        idMedico: idMedico,
        filtro: filtro,
        flag: 'filtroPrescricoes'
    }, function(result) {
        $(".linhas-prescricoes").remove();
        var html = "";

        if (result != null)
            for (var i = 0; i < result.length; i++) {
                html += "<tr name='table-color' class='dayhead tr_prescricao_" + result[i]['id'] + " linhas-prescricoes' >";
                html += "<th>";
                html += "<input type='checkbox' idbanco='" + result[i]['id'] + "' classetr='tr_prescricao_" + result[i]['id'] + "' name='doencasComPrescricoes' value='" + result[i]['id'] + " - " + result[i]['nome'] + "' onChange='atualizaTextareaPrescricoes()' /> ";
                html += "" + result[i]['nome'];
                html += "</th>";
                html += "</tr>"
            }

        $("tbody#tabela-lista-prescricoes").html(html);
    }, "json");
}
function atualizaCID10(obj) {//Before atualizaTextareaCID10
    $("#modal-texto-cid10").html("");
    var consultaID = consultasTela.getSelectedConsulta().consultaID;
//	$("input:checkbox[name=cids]:checked").each(function() {
    brokenstrings = $(obj).val().split("-");
    brokenstring = brokenstrings[0];
    flag = 'insertCID';
    $.post(
            'index.php?module=consultas&tmp=1',
            {flag: flag, idcid: brokenstring, idconsulta: consultaID},
    function(data) {
        console.log("data save!");
    }, 'json');
//	}
//	);

}
function atualizaTextareaPrescricoes() {
    $("#modal-texto-prescricoes").html("");

    $("input:checkbox[name=doencasComPrescricoes]:checked").each(function() {

        brokenstrings = $(this).val().split(" - ");
        brokenstring = brokenstrings[0];
        flag = 'buscaTextoPrescricao';

        $.post(
                'index.php?module=consultas&tmp=1',
                {flag: flag, idPrescricao: brokenstring},
        function(data) {
            $("#modal-texto-prescricoes").append(data['id'] + " -" + data['nome'] + "\n");
            $("#modal-texto-prescricoes").append(data['texto'] + "\n\n");
        }, "json"
                );
    });

}

function defineRecomendacoes() {

    $("#txtarea-prescricoes").html("Prescrições\n");
    var html = $("#modal-texto-prescricoes").html();
    html = html.replace(/\n/g, '<br />');
    $("#txtarea-prescricoes").append(html);

    var html2 = $("#modal-texto-recomendacoes").html();
    html2 = html2.replace(/\n/g, '<br />');
    $("#txtarea-prescricoes").append("Recomendações\n");
    $("#txtarea-prescricoes").append(html2);

    $.ajax({
        url: 'index.php?module=consultas&tmp=1',
        type: 'POST',
        data: {
            flag: 'salvarPrescricoes',
            prescricao: $("#txtarea-prescricoes").html(),
            idConsulta: consultasTela.getSelectedConsulta().consultaID
        },
        success: function(resposta) {
            resposta = $.parseJSON(resposta);
        }
    });

    fechar_modal('boxes-recomendacoes');
}

function definePrescricoes() {
    $("#txtarea-prescricoes").html("Prescrições\n");
    var html = $("#modal-texto-prescricoes").html();
    html = html.replace(/\n/g, '<br />');
    $("#txtarea-prescricoes").append(html);

    var html2 = $("#modal-texto-recomendacoes").html();
    html2 = html2.replace(/\n/g, '<br />');
    $("#txtarea-prescricoes").append("Recomendações\n");
    $("#txtarea-prescricoes").append(html2);

    $.ajax({
        url: 'index.php?module=consultas&tmp=1',
        type: 'POST',
        data: {
            flag: 'salvarPrescricoes',
            prescricao: $("#txtarea-prescricoes").html(),
            idConsulta: consultasTela.getSelectedConsulta().consultaID
        },
        success: function(resposta) {
            resposta = $.parseJSON(resposta);
        }
    });

    fechar_modal('boxes-prescricoes');
}

function defineCID10() {
    var consultaID = consultasTela.getSelectedConsulta().consultaID;
    var flag = "buscaTextoCID";
    var StringtoAppend = "";
    $.post("index.php?module=consultas&tmp=1", {consulta_id: consultaID, flag: flag}, function(result) {
        $("#txareahip").html("");
        $("#txareahip").append("Recomendações(CIDs)\n");
        var html = "";
        for (var i = 0; i < result.length; i++) {
            if (result[i]['marcado'] === '1') {
                html += " -- " + result[i]['idCID'] + " - " + result[i]['descricao'] + " \r\n";
            }
        }
        $("#txareahip").append(html);
    }, "json");

    fechar_modal('boxes-cid10');
}

function carregaAtestado(textoAtestado) {
    $("#modal-texto-atestado").val(textoAtestado);
}

function defineAtestado() {
    var texto = $("#modal-texto-atestado").val();

    texto = texto.replace(/<data>/g, new Date().format('dd/mm/yyyy'));
    texto = texto.replace(/<nome>/g, consultasTela.SelectedClient.nome);

    $("#con-atestado").val(texto);
    $.ajax({
        url: 'index.php?module=consultas&tmp=1',
        type: 'POST',
        data: {
            flag: 'salvarAtestado',
            atestado: texto,
            idConsulta: consultasTela.getSelectedConsulta().consultaID
        },
        success: function(resposta) {
            resposta = $.parseJSON(resposta);
        }
    });
    fechar_modal('boxes-atestados');
}

function atualizaServicoPrestador() {
    var prestador = new Array();
    $("input[name='prestador']:checked").each(function(i) {
        prestador.push($(this).val());
    });
    var servicos = new Array();
    $("input[name='reqServicos']:checked").each(function(i) {
        servicos.push($(this).val());
    });

    $.post("index.php?module=consultas&tmp=1", {
        idPrestador: prestador[0],
        idServico: servicos[0],
        flag: 'textoReqServico'
    }, function(result) {
        //console.log(result);
        $("#textarea-requisicao").html("");
        var html = "";
        html += "Prestador:" + result['prestador'] + "\nTelefone:" + result['telefone'] + "\nEmail:" + result['email'] + "\nEndereço:" + result['endereco'] + "\nServico:" + result['servico'] + "\nSubtestes:\n" + result['subtestes'];
        atualReqs.subtesteRequisicao = result['subtestes'];
        $("#textarea-requisicao").val(html);
    }, "json");
}

function defineTabelaReqServico() {

    $.post("index.php?module=consultas&tmp=1", {
        idCliente: consultasTela.SelectedClient.getClienteId(),
        flag: 'defTabelaReqServico'
    }, function(result) {
        if (result) {
            var rowId = parseInt($('#hiddenRowId').val());
            $("#tbody-prestador-servicos").html('');
            for (var i = 0; i < result.length; i++) {
                var html = "";
                html += "<tr id='prestserv' name=\"table-color\" class=\"dayhead toprint teste-" + rowId + " " + result[i]['cliente_id'] + "-" + result[i]['prestador_id'] + "-" + result[i]['data'] + "\">";
                html += "<th><a class='btn btn-mini btn-danger mrg-center' onclick=\"removerReqEnc('teste-" + rowId + "','" + result[i]['cliente_id'] + "-" + result[i]['prestador_id'] + "-" + result[i]['data'] + "');\"><i class='icon-trash icon-white'></i></a></th>";
                html += "<th>" + result[i]['data'] + "</th>";
                html += "<th>" + result[i]['nome'] + "</th>";
                html += "</tr>";
                for (var j = 0; j < result[i]['encaminhamento'].length; j++) {
                    html += "<tr name=\"table-color\" class=\"dayhead teste-" + rowId + "\">";
                    html += "<th><a class='btn btn-mini btn-danger mrg-center' onclick=\"removerReqEncLinha('teste-" + rowId + "'," + j + ");\"><i class='icon-trash icon-white'></i></a></th>";
                    html += "<th> - </th>";
                    html += "<th>" + result[i]['encaminhamento'][j] + "</th>";
                    html += "</tr>";

                }
                rowId = parseInt($('#hiddenRowId').val()) + 1;
                $('input#hiddenRowId').val(rowId);

                $("#tbody-prestador-servicos").append(html);
            }
            resetPrint();
        }

    }, "json");
}

function defineReqServico() {

    var prestador = new Array();
    $("input[name='prestador']:checked").each(function(i) {
        prestador.push($(this).val());
    });

    var servicos = new Array();
    $("input[name='reqServicos']:checked").each(function(i) {
        servicos.push($(this).val());
    });

    //var idCliente = $("#matricula-cliente").val();
    var idCliente = consultasTela.SelectedClient.getClienteId();
    //var data = $("#hidden-data").val();    
    var data = new Date().format('yyyy-mm-dd');

    $.post("index.php?module=consultas&tmp=1", {
        prestador: prestador[0],
        servicos: servicos[0],
        idCliente: idCliente,
        data: data,
        requisicao: $("#textarea-requisicao").val(),
        encaminhamento: atualReqs.subtesteRequisicao,
        flag: 'appendReqServico'
    }, function(result) {
//        var rowId = parseInt($('#hiddenRowId').val());
//        consultasTela.haha = result;
//        var html = "";
//        html += "<tr id='prestserv' name=\"table-color\" class=\"dayhead toprint teste-" + rowId + " "+result[i]['cliente_id'] + "-" + result[i]['prestador_id'] + "-" + result[i]['data']+"\">";
//        html += "<th><a class='btn btn-mini btn-danger mrg-center ' onclick=\"removerReqEnc('teste-" + rowId + "','" + result[i]['cliente_id'] + "-" + result[i]['prestador_id'] + "-" + result[i]['data'] + "');\"><i class='icon-trash icon-white'></i></a></th>";
//        html += "<th>" + result['data'] + "</th>";
//        html += "<th class='toprint'>" + result['prestador'] + "</th>";
//        html += "</tr>";
//        for (var i = 0; i < result['servicos'].length; i++) {
//            html += "<tr name=\"table-color\" class=\"dayhead teste-" + rowId + "\">";
//            html += "<th>   </th>";
//            html += "<th> - </th>";
//            html += "<th>" + result['servicos'][i] + "</th>";
//            html += "</tr>";
//
//        }
//        rowId = parseInt($('#hiddenRowId').val()) + 1;
//        $('input#hiddenRowId').val(rowId);

        //$("#tbody-prestador-servicos").append(html);
        resetPrint();
        defineTabelaReqServico();
    }, "json");

    fechar_modal('boxes-requisicoesencaminhamento');
}

function removerReqEnc(parametro, id) {
    var con = confirm("Deseja excluir Requisição?");
    if (con) {
        $.post("index.php?module=consultas&tmp=1", {
            id: id,
            flag: 'deleteReqServico'
        }, function(result) {
            $("tr." + parametro).remove();
        });
    }
}

function removerReqEncLinha(parametro, id) {
    var prestador = $("tr." + parametro).attr('class').split(" ")[3];
    var prestador = prestador.split("-");
    var con = confirm("Deseja excluir Servico?");
    if (con) {
        $.post("index.php?module=consultas&tmp=1", {
            idPrestador: prestador[1],
            idServicos: id,
            idCliente: prestador[0],
            data: prestador[2] + "-" + prestador[3] + "-" + prestador[4],
            flag: 'deleteReqServicoLinha'
        }, function(result) {
            resetPrint();
            defineTabelaReqServico();
        }, "json");
    }
}

function atualizaTextareaHipotese() {
    atualHipot.setIsCheck($("input#hipoteses[name='" + atualHipot.getIDDoenca() + "']").is(":checked"));
    var textareaHipoteses = "";
    var idDoenca = atualHipot.getIDDoenca();
    var checkedtmp = atualHipot.getCheck();
    var checkedtexttmp = atualHipot.getCheckText();
    atualHipot.eraseCheck();
    atualHipot.eraseCheckText();
    if (atualHipot.getIsCheck() == false) {
        var checkedtmp2 = checkedtmp;
        var checkedtexttmp2 = checkedtexttmp;
        checkedtmp = new Array();
        checkedtexttmp = new Array();
        for (var i = 0; i < checkedtmp2.length; i++) {
            if (checkedtmp2[i] != idDoenca) {
                checkedtmp.push(checkedtmp2[i]);
                checkedtexttmp.push(checkedtexttmp2[i]);
            }
        }
    } else {
        var exists = false;
        for (var i = 0; i < checkedtmp.length; i++) {
            if (checkedtmp[i] == idDoenca) {
                exists = true;
                break;
            }
        }
        if (exists == false) {
            checkedtmp.push(idDoenca);
            checkedtexttmp.push($("input#hipoteses[name='" + idDoenca + "']").val());
        }

    }

    for (var i = 0; i < checkedtexttmp.length; i++) {
        textareaHipoteses += checkedtexttmp[i] + "\n";
    }
    atualHipot.setCheck(checkedtmp);
    atualHipot.setCheckText(checkedtexttmp);

    //$("#txareahip").val(textareaHipoteses);
    $("#modal-texto-hipotese").val(textareaHipoteses);
}

function atualizaTextareaRecomendacoes() {
    $("#modal-texto-recomendacoes").html("");
    $("input:checkbox[name=recomendacoesDoencas]:checked").each(function() {

        brokenstrings = $(this).val().split(" - ");
        brokenstring = brokenstrings[0];
        flag = 'buscaTextoRecomendacao';
        $.post(
                'index.php?module=consultas&tmp=1',
                {flag: flag, idPrescricao: brokenstring},
        function(data) {
            $("#modal-texto-recomendacoes").append(data['id'] + " -" + data['nome'] + "\n");
            $("#modal-texto-recomendacoes").append(data['texto'] + "\n\n");
        }, "json"
                );
    });
}
function a() {
    var textareaRecomend = "";
    $("input:checkbox[name=recomendacoesDoencas]:checked").each(function() {
        textareaRecomend += $(this).val() + "\n";
    });
    $("#modal-texto-recomendacoes").html(textareaRecomend);
}
/**
 * @author Luiz Cortinhas
 * @function Reinderizar os check-box selecionados.
 * 
 * */
function atualizaTextareaTestesCutaneos() {
    $(".testesCutaneosSelectionados1").remove();
    var text = "";

    $("input:checkbox[name=testesCutaneos]:checked").each(function() {
        var html = "";
        $(".testescutConf").remove();
        var tcversao = $(this).attr('id');
        htmlSelecionado = "<tr name='table-color' class='dayhead testesCutaneosSelectionados1'>";
        htmlSelecionado += "<th align='center'>" + $(this).val() + "</th>";
        htmlSelecionado += "</tr>";
        $("tbody#testesCutaneosSelecionados").append(htmlSelecionado);
        brokenstrings = $(this).val().split(" - ");
        brokenstring = brokenstrings[0];
        flag = 'buscaAlegenos';
        $.post(
                'index.php?module=consultas&tmp=1',
                {flag: flag, idTeste: brokenstring},
        function(data) {
            var hoje = new Date();
            var dia = hoje.getDate();
            var mes = hoje.getMonth() + 1;
            var ano = hoje.getFullYear();
            console.log(data);
            html += "<tr id='" + tcversao + "' name='table-color' class='dayhead testescutConf toprint teste-cutaneo'>";
            html += "<td align='center'>" + dia + "/" + mes + "/" + ano + " </td>";
            html += "<td align='center'>" + data['nome'] + "</td>";
            html += "<td align='center'>" + data['alergeno'] + "</td>";
            html += "<td align='center' id='resultadoTeste" + data['idTeste'] + "' onClick='modalDefineResultado(\"" + data['idTeste'] + "\")'></td>";
            html += "</tr>";
            $("tbody#testesCutaneosConfirmados").append(html);
            resetPrint();
        }, "json"
                );
    });
}

function testesCutaneosOK() {
    fechar_modal('boxes-testescutaneos')
}

function testesCutaneosPopulate() {
    var tabela = "";
    $.post('index.php?module=consultas&tmp=1', {flag: "criaTabelaTesteCutaneo", idcliente: consultasTela.SelectedClient.getClienteId()},
    function(data) {
        $('#testesCutaneosConfirmados').html(data);
    }, "json");

}

function atualizaTestesCutaneos() {

    flag = 'buscaTodosAlegenosPorCliente';
    $.post(
            'index.php?module=consultas&tmp=1',
            {flag: flag, cliente_id: consultasTela.getSelectedConsulta().cliente.clienteId},
    function(data) {
        var html2 = "";
        for (var i in data) {
//            console.log(data[i]['id']);
//            console.log("Aqui no foreach da funcao (atualizaTestesCutaneos) ");
//            htmlSelecionado = "<tr name='table-color' class='dayhead testesCutaneosSelectionados1'>";
//            htmlSelecionado += "<th align='center'>" + data[i]['id'] + "</th>";
//            htmlSelecionado += "</tr>";
//            $("tbody#testesCutaneosSelecionados").append(htmlSelecionado);
            var dataI = data[i]['data'].split("-");
            var dia = dataI[2];
            var mes = dataI[1];
            var ano = dataI[0];
            html2 += "<tr name='table-color' class='dayhead testescutConf'>";
            html2 += "<td align='center'>" + dia + "/" + mes + "/" + ano + " </td>";
            html2 += "<td align='center'>" + data[i]['nome'] + "</td>";
            html2 += "<td align='center'>" + data[i]['alergeno'] + "</td>";
            html2 += "<td align='center' id='resultadoTeste" + i + "' onClick='modalDefineResultado(\"" + i + "\")'>" + data[i]['resultado'] + "</td>";
            html2 += "</tr>";
            $("tbody#testesCutaneosConfirmados").append(html2);
        }
    }, "json"
            );
}

function testesCutaneosOK() {
    fechar_modal('boxes-testescutaneos')
}

function testesCutaneosPopulate() {
    var tabela = "";
    $.post('index.php?module=consultas&tmp=1', {flag: "criaTabelaTesteCutaneo", idcliente: consultasTela.SelectedClient.getClienteId()},
    function(data) {
        $('#testesCutaneosConfirmados').html(data);
    }, "json");

}



// Modal que abre quando se clica no td de resultado para poder definir o mesmo.
// utilizado na aba de testes cutaneos
function modalDefineResultado(idTeste) {
    var flag = "resultadoTestesCutaneos";
    $(".classResultadoTesteCutaneo").remove();
    $("#idTesteClicado").val(idTeste);
    $.post(
            'index.php?module=consultas&tmp=1',
            {flag: flag, idTeste: idTeste},
    function(data) {
        console.log("Recebido (747 line) ::" + $.parseJSON(data));

        for (var i = 0; i < data.length; i++) {
            html = "<tr name='table-color' class='dayhead classResultadoTesteCutaneo' id='resultadoteste_linha" + data[i][0] + "' >";
            html += "<th align='center' id='nomeResultado" + i + "'>" + data[i]['nome'] + "</th>";
            html += "<th align='center'><input type='text' id='resultadoTeste_" + i + "' value='" + $("#" + idTeste + "_spanResultadoTeste_" + data[i]['nome']).text() + "' /></th>";
            html += "<th align='center'>" + data[i]['descricao'] + "</th>";
            html += "</tr>";
            $("tbody#modal-resultadoTesteCutaneo").append(html);
        }


    }, "json"
            );
    abrir_modal('boxes-resultcutaneos');
}

// funcao utilizada para copiar os resultados para a linha da tabela de testes
// cutaneos correspondentes.
function testesCutaneosResultadorOK() {
    var idTeste = $("#idTesteClicado").val();

    n = 0;
    html = "";
    $('#modal-resultadoTesteCutaneo tr').each(function() {
        n++;
    });

    for (i = 0; i < n; i++) {
        if ($("#resultadoTeste_" + i).val() != '') {
            html += $("#nomeResultado" + i).html();
            html += "(<span class='resultadosTesteSub' id='" + idTeste + "_spanResultadoTeste_" + $("#nomeResultado" + i).html() + "' >" + $("#resultadoTeste_" + i).val() + "</span>),";
        }
    }

    $("#resultadoTeste" + idTeste).html(html);

    fechar_modal('boxes-resultcutaneos')
}

function submeterTesteCutaneo(clientId) {

    if (!confirm("tem certeza que deseja submeter estes testes?")) {
        return;
    }
    var idCliente = consultasTela.SelectedClient.getClienteId();
    var dados = new Array();
    var i = 0;
    $('.resultadosTesteSub').each(function() {

        id = this.id;
        values = id.split("_");
        alergeno = values.pop();
        teste = values.shift();
        resultado = $(this).html();
//        console.log("Id::" + id);
//        console.log("ThisHTML::" + $(this).html());
//        console.log("Values::" + values);
        var dado = new Array();
        dado[0] = teste;
        dado[1] = alergeno;
        dado[2] = resultado;
        console.log("Dados::" + teste + " , " + alergeno + " , " + resultado);
        dados[i] = dado;
        i++;
    });

    var flag = "insereTestesCutaneos";
    console.log("Cliente Teste Cutaneo::" + idCliente);
    $.post('index.php?module=consultas&tmp=1',
            {
                flag: flag,
                dados: dados,
                idCliente: idCliente
            },
    function(data) {
        console.log(data);
        $("#msgInsertTeste").html(data + "<br/>");
        $("#msgInsertTeste").addClass("alert alert-success");
    }, "json");

}

function removerPrescricao() {

    var prescricoesclasse = new Array();
    $("input[name='doencasComPrescricoes']:checked").each(function(i) {
        prescricoesclasse.push($(this).attr('classetr'));
    });

    var prescricoes = new Array();
    $("input[name='doencasComPrescricoes']:checked").each(function(i) {
        prescricoes.push($(this).val());
    });

    if (prescricoes.length == 0) {
        alert("Escolha um item");
        return false;
    }

    $.post("index.php?module=consultas&tmp=1", {prescricoes: prescricoes, flag: 'deletaPrescricao'}, function(result) {
        if (result == 1) {
            alert("Os registros foram removidos com sucesso.");
            $("#modal-texto-prescricoes").html("");
            for (var i = 0; i < prescricoesclasse.length; i++) {
                console.log('tr.' + prescricoesclasse[i]);
                $("tr." + prescricoesclasse[i]).remove();
            }
        } else {
            alert("Ocorreu um erro ao tentar remover os registros selecionados. Por favor tente novamente.");
        }
    });
}

function salvaNovaPrescricao() {

    var idMedico = $("#medico-prescricoes").val();

    if (idMedico == null || idMedico == undefined || idMedico == "") {
        alert("Escolha um médico antes de continuar.");
        return false;
    }

    var nomePrescricao = $("input[name=nome_prescricao_inserir]").val();
    var flag = 'insereNovaPrescricao';

    $.post('index.php?module=consultas&tmp=1', {flag: flag, nomePrescricao: nomePrescricao, idMedico: idMedico}, function(data) {
        if (data == 1) {
            alert('Prescrição Cadastrada com sucesso');
            fechar_modal('boxe-inserir_prescricao');
            filtraPrescricoes();
        } else {
            alert("Ocorreu um erro ao cadastrar uma nova prescrição, por favor tente novamente.");
        }
    }, "json");
}

function editaPrescricaoModal() {

    var idPrescricao = $("input:checkbox[name=doencasComPrescricoes]:checked").attr('idbanco');
    var prescricoes = new Array();
    $("input[name='doencasComPrescricoes']:checked").each(function(i) {
        prescricoes.push($(this).val());
    });

    if (prescricoes.length > 1) {
        alert("Escolha apenas um item");
        return false;
    } else if (prescricoes.length == 0) {
        alert("Escolha um item");
        return false;
    }

    var nome = $("input:checkbox[name=doencasComPrescricoes]:checked").val();

    var arr = nome.split('-');
    var nomeP = $.trim(arr[1]);

    if (arr[2] != null)
        nomeP = nomeP + "-" + $.trim(arr[2]);

    var botao = '<a href="#" onclick="editaPrescricao(\'' + idPrescricao + '\');" class="btn"><i class="icon-ok"></i>Salvar</a>';
    atualizaTextareaPrescricoesEditar()
    $("#presc_botao").html(botao);
    $("#presc_titulo").html(nomeP);
    abrir_modal('boxes-prescricoes-editar');

}

function atualizaTextareaPrescricoesEditar() {

    var idPrescricao = $("input:checkbox[name=doencasComPrescricoes]:checked").attr('idbanco');
    var flag = 'buscaTextoPrescricaoEditar';

    $.post('index.php?module=consultas&tmp=1', {flag: flag, idPrescricao: idPrescricao}, function(data) {
        $("#modal-texto-prescricoes-editar").html(data['texto']);
    }, "json");

}

function editaPrescricao(idPrescricao) {

    var texto = $("#modal-texto-prescricoes-editar").val();
    var flag = 'editaTextoPrescricao';

    $.post('index.php?module=consultas&tmp=1', {flag: flag, texto: texto, idPrescricao: idPrescricao}, function(data) {

        if (data == 1) {
            alert("Texto atualizado com sucesso");
            $("#modal-texto-prescricoes").html(texto);
            fechar_modal('boxes-prescricoes-editar');
        } else {
            alert("Ocorreu um erro ao tentar atualizar o texto da prescrição selecionada. Tente novamente.");
        }
    }, "json");

}

////////ATESTADOS BEGIN

function consultasAtestado(atualAtestado) {
    console.log(atualAtestado);
    var conRm = false;
    var flag = 'atualizaAtestado';

    if (atualAtestado.flag == 1) {
        atualAtestado.idAtestado = null;
        atualAtestado.titAtestado = "";
        atualAtestado.texAtestado = "";
    } else if (atualAtestado.flag == 3) {
        con = confirm("Deseja remover atestado?");
    }

    if (atualAtestado.flag != 3) {

        TextAreaMCE.show2(function(atualAtestado) {
            $.post('index.php?module=consultas&tmp=1', {
                flag: 'atualizaAtestado',
                opcao: atualAtestado.flag, //1 - Novo, 2 - editar, 3 - remover, 0 - default
                id: atualAtestado.idAtestado,
                titulo: atualAtestado.titAtestado,
                texto: atualAtestado.texAtestado
            }, function(data) {
                if (data) {
                    alert("Modificações realizadas com sucesso!");
                    $("tbody#tabela-atestados").html(data);
                    $("#modal-texto-atestado").val(atualAtestado.texAtestado);
                    setIdAtestado();
                } else {
                    alert("Ocorreu um erro ao tentar modificar. Tente novamente.");
                }

            }, "json");
        });
        TextAreaMCE.set2(atualAtestado);

    } else if (con == true) {

        console.log(atualAtestado);
        $.post('index.php?module=consultas&tmp=1', {
            flag: 'atualizaAtestado',
            opcao: atualAtestado.flag, //1 - Novo, 2 - editar, 3 - remover, 0 - default
            id: atualAtestado.idAtestado,
            titulo: atualAtestado.titAtestado,
            texto: atualAtestado.texAtestado
        }, function(data) {
            if (data) {
                alert("Removido com sucesso!");
                $("tbody#tabela-atestados").html(data);
                $("#modal-texto-atestado").val('');
                setIdAtestado();
            } else {
                alert("Ocorreu um erro ao tentar remover. Tente novamente.");
            }

        }, "json");

    }

}

////////ATESTADOS END

function getDataFormatada(separator) {
    var monthNames = ["jan", "fev", "mar", "abr", "mai", "jun",
        "jul", "ago", "set", "oct", "nov", "dez"];
    var date = new Date();
    if (date.getDate().toString().length == 2)
        return date.getDate().toString() + separator + monthNames[date.getMonth() - 1] + separator + date.getFullYear().toString();
    else
        return "0" + date.getDate().toString() + separator + monthNames[date.getMonth() - 1] + separator + date.getFullYear().toString();
}
function converte2DataFormatada(date, separator) { //Input mask YYYY-MM-DD to dd-abv-yyyy
    date = date.split("-");
    var monthNames = ["jan", "fev", "mar", "abr", "mai", "jun",
        "jul", "ago", "set", "oct", "nov", "dez"];
    return date[2] + separator + monthNames[parseInt(date[1]) - 1] + separator + date[0];
}

$(function() {

    $("#pop-1").popover();
});

function construirArray(qtdElementos) {
    this.length = qtdElementos;
}

var arrayDia = new construirArray(7);
arrayDia[0] = "Domingo";
arrayDia[1] = "Segunda-Feira";
arrayDia[2] = "Terça-Feira";
arrayDia[3] = "Quarta-Feira";
arrayDia[4] = "Quinta-Feira";
arrayDia[5] = "Sexta-Feira";
arrayDia[6] = "Sabado";

var arrayMes = new construirArray(12);
arrayMes[0] = "Janeiro";
arrayMes[1] = "Fevereiro";
arrayMes[2] = "Março";
arrayMes[3] = "Abril";
arrayMes[4] = "Maio";
arrayMes[5] = "Junho";
arrayMes[6] = "Julho";
arrayMes[7] = "Agosto";
arrayMes[8] = "Setembro";
arrayMes[9] = "Outubro";
arrayMes[10] = "Novembro";
arrayMes[11] = "Dezembro";

function getMesExtenso(mes) {
    return this.arrayMes[mes];
}


function getDiaExtenso(dia) {
    return this.arrayDia[dia];
}


function mostrarData(diaSemana, dia, mes, ano) {
    retorno = diaSemana + ", " + dia + " de " + mes + " de " + ano;
    $("#consultafinal").val(retorno);
}
function mostrarData2(diaSemana, dia, mes, ano) {
    retorno = diaSemana + ", " + dia + " de " + mes + " de " + ano;
    $("#consultaInicial").val(retorno);
}


function getdate() {
    var data = new XDate($("#consultafinal").val());
    $("#consultafinal").prop('dataFormat', $("#consultafinal").val());
    if (data != "Invalid Date") {
        var d = new Date();
        var diaSemana = getDiaExtenso(data.getDay());
        var dia = data.getDate();
        var mes = getMesExtenso(data.getMonth());
        var ano = data.getFullYear();
        mostrarData(diaSemana, dia, mes, ano);
    }
}

function getdate2() {
    var data = new XDate($("#consultaInicial").val());
    $("#consultaInicial").prop('dataFormat', $("#consultaInicial").val());
    if (data != "Invalid Date") {
        var d = new Date();
        var diaSemana = getDiaExtenso(data.getDay());
        var dia = data.getDate();
        var mes = getMesExtenso(data.getMonth());
        var ano = data.getFullYear();
        mostrarData2(diaSemana, dia, mes, ano);
    }
}    