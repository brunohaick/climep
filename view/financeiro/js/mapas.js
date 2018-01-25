function limparConsultaMapa() {
    $('#tbody-mapa').html('');
    $('#thead-mapa').html('');
}

function localizaMapa() {
    data_inicio = $("#consulta-map-data_inicio").val();
    data_fim = $("#consulta-map-data_fim").val();
    tipo = $("#consulta-map-tipo").val();
    tipoForm = "preencheTabMapaPorTipo";
    $("#thead-mapa").html('Localizando...');
    $("#tbody-mapa").html('');
    $.post('index.php?module=mapas&tmp=1', {
        tipoForm: tipoForm,
        data_inicio: data_inicio,
        data_fim: data_fim,
        tipo: tipo
    }, function(data) {
        if (data) {
            if (tipo != 3 && tipo != 4) {
                console.log(data);
                qtdIndiceData = data['dados'].length;
                qtdIndiceCab = data['cabecalho'].length;
                html_cab = "<tr class=\"white\">";
                for (var i = 0; i < qtdIndiceCab; i++) {
                    html_cab += "<th > " + data['cabecalho'][i] + " </th>";
                }
                html_cab += "</tr>";
                $("#thead-mapa").html(html_cab);
                html_body = "";
                console.log(data);
                /* RECEITAS */
                var tmpcodigo;
                var anterior = data['dados'][0].codigo;
                anterior = anterior.split('.');
                anterior = anterior[1];

                for (var j = 0; j < qtdIndiceData; j++) {
                    sum_val = 0;
                    sum_qtd = 0;

                    if (data['dados'][j].valor[data['dados'][j].valor.length - 1].media !== '0.00') {
                        tmpcodigo = data['dados'][j].codigo;
                        tmpcodigo = tmpcodigo.split('.');
                        tmpcodigo = tmpcodigo[1];
                        if (anterior != tmpcodigo) {
                            anterior = tmpcodigo;
                            html_body += "<tr class='dayhead' style='height: 20px;'><td></td></tr>";
                        }
                        html_body += "<tr class='dayhead'>";
                        html_body += "<th style=\'text-align:right;\'> " + data['dados'][j].codigo + " </th>";
                        html_body += "<th style=\'text-align:left;\'> " + data['dados'][j].nome + " </th>";
                        for (var i = 0; i < data['dados'][j].valor.length; i++) {
                            if (i < (data['dados'][j].valor.length - 1)) {
                                var result = 0;
                                var taxa = 0;
                                result = number_format(parseFloat(data['dados'][j].valor[i].valor), 2, ',', '.');
                                taxa = number_format(data['dados'][j].valor[i].taxa, 2, ',', '.');
                                html_body += "<th style=\'text-align:right;\'> " + result + " </th>";
                                html_body += "<th style=\'text-align:right;\'> " + taxa + " </th>";
                            } else if (i === (data['dados'][j].valor.length - 1)) {
                                var result = 0;
                                var taxa = 0;
                                result = number_format(data['dados'][j].valor[i].media, 2, ',', '.');
                                taxa = number_format(data['dados'][j].valor[i].taxa, 2, ',', '.');
                                html_body += "<th style=\'text-align:right;\'> " + result + " </th>";
                                html_body += "<th style=\'text-align:right;\'> " + taxa + " </th>";
                            }else{
                                html_body += "<th style=\'text-align:right;\'> </th>";
                                html_body += "<th style=\'text-align:right;\'>  </th>";
                            }
                        }
                        html_body += "</tr>";
                    }
                }
                //LINHA 
                /* DESPESAS */
                if (data['despesas'] !== null && data['despesas'] !== undefined) {
                    html_body += "<tr class='dayhead' style='height: 20px;'><td></td></tr>";
                    var anterior = data['despesas'][0].codigo;
                    anterior = anterior.split('.');
                    anterior = anterior[1];
                    for (var j = 0; j < data['despesas'].length; j++) {
                        sum_val = 0;
                        sum_qtd = 0;

                        if (data['despesas'][j].valor[data['despesas'][j].valor.length - 1].media !== '0.00') {
                            tmpcodigo = data['despesas'][j].codigo;
                            tmpcodigo = tmpcodigo.split('.');
                            tmpcodigo = tmpcodigo[1];
                            if (anterior != tmpcodigo) {
                                anterior = tmpcodigo;
                                html_body += "<tr class='dayhead' style='height: 20px;'><td></td></tr>";
                            }
                            html_body += "<tr class='dayhead'>";
                            html_body += "<th style=\'text-align:right;\'> " + data['despesas'][j].codigo + " </th>";
                            html_body += "<th style=\'text-align:left;\'> " + data['despesas'][j].nome + " </th>";
                            for (var i = 0; i < data['despesas'][j].valor.length; i++) {
                                if (i < (data['despesas'][j].valor.length - 1)) {
                                    var result = 0;
                                    var taxa = 0;
                                    result = number_format(parseFloat(data['despesas'][j].valor[i].valor), 2, ',', '.');
                                    taxa = number_format(data['despesas'][j].valor[i].taxa, 2, ',', '.');
                                    html_body += "<th style=\'text-align:right;\'> " + result + " </th>";
                                    html_body += "<th style=\'text-align:right;\'> " + taxa + " </th>";
                                } else if (i === (data['despesas'][j].valor.length - 1)) {
                                    var result = 0;
                                    var taxa = 0;
                                    result = number_format(data['despesas'][j].valor[i].media, 2, ',', '.');
                                    taxa = number_format(data['despesas'][j].valor[i].taxa, 2, ',', '.');
                                    html_body += "<th style=\'text-align:right;\'> " + result + " </th>";
                                    html_body += "<th style=\'text-align:right;\'> " + taxa + " </th>";
                                }
                            }
                            html_body += "</tr>";
                        }
                    }
                }
                //TOTAL
                if (data['total'] !== null && data['total'] !== undefined) {
                    html_body += "<tr class='dayhead' style='height: 20px;'><td></td></tr>";
                    html_body += "<tr name=\"table-color\" class='dayhead'>";
                    html_body += "<th></th>";
                    html_body += "<th style=\'text-align:left;\'> TOTAL GERAL:</th>";
                    for (var i = 0; i < data['total'].length; i++) {
                        var taxa = number_format(data['total'][i].taxa, 2, ',', '.');
                        var valor = number_format(data['total'][i].valor, 2, ',', '.');
                        html_body += "<th style=\'text-align:right;\'> " + valor + " </th>";
                        html_body += "<th style=\'text-align:right;\'> " + taxa + " </th>";
                    }
                    html_body += "</tr>";
                }
                $("#tbody-mapa").html(html_body);
            } else {
			
                /**
                 * Mapas de Vacinas Pagas e Realizadas.
                 */
                qtdIndiceCab = data['cabecalho'].length;
                html_cab = "<tr class=\"white\">";
                for (var i = 0; i < qtdIndiceCab; i++) {
                    html_cab += "<th > " + data[0]['cabecalho'][i] + " </th>";
                }
                html_cab += "</tr>";
                $("#thead-mapa").html(html_cab);
                html_body = "";
                /* VACINAS */
//                console.log("Aqui no JS");
                for(var k = 0; k < 4; k++) {
                    if(k == 3 && data[3] === undefined)
                        break;

                    ncol = data[k]['dados'].length; // qtd colunas
                    nlin = data[k]['dados'][0].length;
                    var dados = data[k];

                    if(k == 0)
                            var titulo = "VACINAÇÕES";
                    else if(k == 1)
                            var titulo = "IMUNOTERAPIA";
                    else if(k == 2)
                            var titulo = "TESTES DE TRIAGEM";
                    else if(k == 3)
                            var titulo = "DIVERSOS";

                    html_body += "<tr>";
                    for (var j = 0; j < ncol; j++) {
                            if (j == 0) {
                                    html_body += "<th style=\'text-align:right;\'> </th>";
                                    html_body += "<th style=\'text-align:left;\'> " + titulo + " </th>";
                            }
                            html_body += "<th style=\'text-align:right;\'> " + number_format(data[k]['totalgeral'][j]['valor'], 2, ',', '.') + " </th>";
                            html_body += "<th style=\'text-align:right;\'> " + number_format(data[k]['totalgeral'][j]['porc'], 2, ',', '.') + " </th>";
                            html_body += "<th style=\'text-align:right;\'> " + number_format(data[k]['totalgeral'][j]['qtd'], 2, ',', '.') + " </th>";
                    }
                    html_body += "</tr>";

                    for (var i = 0; i < nlin; i++) {
                            if (dados['dados'][ncol - 1][i]['media'] == '0.00') {
                                    continue;
                            }
                            html_body += "<tr>";
                            for (var j = 0; j < ncol; j++) {
                                    if (j == 0) {
                                            html_body += "<th style=\'text-align:right;\'> " + dados['dados'][j][i]['codigo'] + " </th>";
                                            html_body += "<th style=\'text-align:left;\'> " + dados['dados'][j][i]['nome'] + " </th>";
                                    }
                                    if (j < ncol - 1) {
                                            html_body += "<th style=\'text-align:right;\'> " + number_format(dados['dados'][j][i]['valor'], 2, ',', '.') + " </th>";
                                    } else {
                                            html_body += "<th style=\'text-align:right;\'> " + number_format(dados['dados'][j][i]['media'], 2, ',', '.') + " </th>";
                                    }
                                    html_body += "<th style=\'text-align:right;\'> " + number_format(dados['dados'][j][i]['porc'], 2, ',', '.') + " </th>";
                                    html_body += "<th style=\'text-align:right;\'> " + number_format(dados['dados'][j][i]['qtd'], 2, ',', '.') + " </th>";
                            }
                            html_body += "</tr>";
                    }

                    html_body += "<tr style='height: 15px'>";
                    for (var j = 0; j < ncol; j++) {
                            if (j == 0) {
                                    html_body += "<th style=\'text-align:right;\'></th>";
                                    html_body += "<th style=\'text-align:left;\'></th>";
                            }
                            html_body += "<th style=\'text-align:right;\'></th>";
                            html_body += "<th style=\'text-align:right;\'></th>";
                            html_body += "<th style=\'text-align:right;\'></th>";
                    }
                    html_body += "</tr>";

                    if(k == 2) {
                            html_body += "<tr>";
                            for (var j = 0; j < ncol; j++) {
                                    if (j == 0) {
                                            html_body += "<th style=\'text-align:right;\'> </th>";
                                            html_body += "<th style=\'text-align:left;\'> TOTAL GERAL: </th>";
                                    }
                                    html_body += "<th style=\'text-align:right;\'> " + number_format(data['totalgeral2'][j], 2, ',', '.') + " </th>";
                                    html_body += "<th style=\'text-align:right;\'></th>";
                                    html_body += "<th style=\'text-align:right;\'></th>";
                            }
                            html_body += "</tr>";
                    }
                }
                $("#tbody-mapa").html(html_body);
            }
        } else {
            $("#tbody-mapa").html('Não houveram resultados');
        }
    }, "json");

    $('#btn_imprimirmapas').unbind().click(function() {
        imprimirMapa($('#consulta-map-tipo option:selected').val());
    });
}

function imprimirMapa(num) {
    switch (num) {
        case '2':
            window.open('index2.php?module=relatoriopadrao', '_blank');
            break;
        case '3':
            window.open('index2.php?module=relatoriovacinaspagas', '_blank');
            break;
        case '4':
            window.open('index2.php?module=relatoriovacinasrealizadas', '_blank');
            break;
        case '9':
            window.open('index2.php?module=relatorioformapagamento', '_blank');
            break;
    }
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