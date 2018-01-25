function ModelPagamento() {
    this.campo = "campo_valor_total";
    this.id = "valor_total";
    this.parcela = -1;
    this.valor = 0;
    this.valor_vista = 0;
    this.valor_cartao = 0;
    this.seleciona = function Seleciona(id) {
		console.log("SELECIONA == " + id.indexOf('campo'));
        if (id.indexOf('campo') === -1) {
            $('#' + this.id).attr('style', 'background-color:;');
            $('#' + this.campo).attr('style', 'background-color:;');
            this.id = id;
            this.campo = "campo_" + id;
            this.valor = $('#' + id).html();
            this.a = $('#' + id).attr('parcela');

            $('#' + this.id).attr('style', 'background-color:blue;');
            $('#' + this.campo).attr('style', 'background-color:blue;');
        } else {
            $('#' + this.id).attr('style', 'background-color:;');
            $('#' + this.campo).attr('style', 'background-color:;');
            this.campo = id;
            this.id = id.substring(6, id.length);
            ;
            this.valor = $('#' + this.id).html();
            this.parcela = $('#' + this.id).attr('parcela');
            $('#' + this.id).attr('style', 'background-color:blue;');
            $('#' + this.campo).attr('style', 'background-color:blue;');

        }
    };
    this.atualizar = function Atualizar() {
        this.valor = $('#' + this.id).html();
    };
}

function listadeVacinas() {
    this.nome;
    this.preco;

    this.updateLista = function() {

        $.ajax({
            url: 'index.php?module=modulos&look=listavacina&tmp=0',
            type: 'POST',
			async: false,
            data: {vacinas: controle.linhas},
            success: function(data) {
                var arrayi = data.split('|');
                for (var i = 1; i < arrayi.length; i++) {
                    $('#listaVacinasDisponiveis').append(new Option(arrayi[i], arrayi[i]));
                }
            }
        });
    };

}

function SelecionaDependente() {
    this.nome;
    this.sobrenome;
    this.clienteid = 0;
    this.membroid = 0;
    this.setNome = function(nome) {
        this.nome = nome;
    };
    this.setSobrenome = function(sobrenome) {
        this.sobrenome = sobrenome;
    };
    this.setClienteid = function(id) {
        this.clienteid = id;
    };

    this.SelecionaDependente = function(id, nome, sobrenome, membro) {
        this.clienteid = id;
        this.nome = nome;
        this.sobrenome = sobrenome;
        this.membroid = membro;
        $("ul.ULDependenteModulos > li > label[clienteid = " + this.clienteid + "].ClassDependenteModulos ").attr('style', 'background-color:blue');
        $("ul.ULDependenteModulos > li > label[clienteid != " + this.clienteid + "].ClassDependenteModulos ").attr('style', 'background-color');
    };

}

function calculoDinamico() {
    this.BCGmarcado = false;
    this.Medicomarcado = false;
    this.HPVmarcado = false;
    this.antesdoDescontoVista = 0.00;
    this.antesdoDescontoPrograma = 0.00;
    this.antesdoDescontoBCG = 0.00;

    this.MarkMedico = function Medico() {
        if ($("#descontoMedico").is(":checked")) {
            this.Medicomarcado = true;
        } else {
            this.Medicomarcado = false;
        }
        controle.recalcular();
    };
    this.MarkHPV = function Hpv() {
        if ($('#descontoHPV').is(":checked")) {
            this.HPVmarcado = true;
        } else {
            this.HPVmarcado = false;
        }
        controle.recalcular();
    };
    this.subtrairVista = function subtrairtotal(subtrair) {
        var atual = $('#desconto_vista').html();
        atual = parseFloat(atual) - subtrair;
        $('#desconto_vista').html(atual);
    };
    this.somaVista = function somatotal(somar) {
        var atual = $('#desconto_vista').html();
        atual = parseFloat(atual) + somar;
        $('#desconto_vista').html(atual);
    };
    this.subtrairTotal = function subtrairtotal(subtrair) {
        var atual = $('#valor_total').html();
        atual = parseFloat(atual) - subtrair;
        $('#valor_total').html(atual);
    };
    this.somaTotal = function somatotal(somar) {
        var atual = $('#valor_total').html();
        atual = parseFloat(atual) + somar;
        $('#valor_total').html(atual);
    };
    this.subtrairPrograma = function subtrairPrograma(subtrair) {
        var atual = $('#valor_programa').html();
        atual = parseFloat(atual) - subtrair;
        $('#valor_programa').html((atual.toFixed(2)));
        $('#parcelado_1x').html((atual.toFixed(2)));
        $('#parcelado_2x').html(((atual) / 2).toFixed(2));
        $('#parcelado_3x').html(((atual) / 3).toFixed(2));
        $('#parcelado_4x').html(((atual) / 4).toFixed(2));
        $('#parcelado_5x').html(((atual) / 5).toFixed(2));
        $('#parcelado_6x').html(((atual) / 6).toFixed(2));
        $('#parcelado_7x').html(((atual) / 7).toFixed(2));
        $('#parcelado_8x').html(((atual) / 8).toFixed(2));
        $('#parcelado_9x').html(((atual) / 9).toFixed(2));
        $('#parcelado_10x').html(((atual) / 10).toFixed(2));
        $('#parcelado_11x').html(((atual) / 11).toFixed(2));
        $('#parcelado_12x').html(((atual) / 12).toFixed(2));
    };
    this.recalcularProgramabaseTotal = function recalcPrograma() {
        var atual = $('#valor_total').html();
        if (this.Medicomarcado) {
            $('#valor_programa').html((atual.toFixed(2)));
            $('#parcelado_1x').html((atual.toFixed(2)));
            $('#parcelado_2x').html(((atual) / 2).toFixed(2));
            $('#parcelado_3x').html(((atual) / 3).toFixed(2));
            $('#parcelado_4x').html(((atual) / 4).toFixed(2));
            $('#parcelado_5x').html(((atual) / 5).toFixed(2));
            $('#parcelado_6x').html(((atual) / 6).toFixed(2));
            $('#parcelado_7x').html(((atual) / 7).toFixed(2));
            $('#parcelado_8x').html(((atual) / 8).toFixed(2));
            $('#parcelado_9x').html(((atual) / 9).toFixed(2));
            $('#parcelado_10x').html(((atual) / 10).toFixed(2));
            $('#parcelado_11x').html(((atual) / 11).toFixed(2));
            $('#parcelado_12x').html(((atual) / 12).toFixed(2));
        } else {
            atual = atual * 0.94;
            $('#valor_programa').html((atual.toFixed(2)));
            $('#parcelado_1x').html((atual.toFixed(2)));
            $('#parcelado_2x').html(((atual) / 2).toFixed(2));
            $('#parcelado_3x').html(((atual) / 3).toFixed(2));
            $('#parcelado_4x').html(((atual) / 4).toFixed(2));
            $('#parcelado_5x').html(((atual) / 5).toFixed(2));
            $('#parcelado_6x').html(((atual) / 6).toFixed(2));
            $('#parcelado_7x').html(((atual) / 7).toFixed(2));
            $('#parcelado_8x').html(((atual) / 8).toFixed(2));
            $('#parcelado_9x').html(((atual) / 9).toFixed(2));
            $('#parcelado_10x').html(((atual) / 10).toFixed(2));
            $('#parcelado_11x').html(((atual) / 11).toFixed(2));
            $('#parcelado_12x').html(((atual) / 12).toFixed(2));
        }
    };
    this.somaPrograma = function somaPrograma(somar) {
        var atual = $('#valor_programa').html();
        atual = atual + subtrair;
        $('#valor_programa').html((atual).toFixed(2));
        $('#parcelado_1x').html((atual).toFixed(2));
        $('#parcelado_2x').html(((atual) / 2).toFixed(2));
        $('#parcelado_3x').html(((atual) / 3).toFixed(2));
        $('#parcelado_4x').html(((atual) / 4).toFixed(2));
        $('#parcelado_5x').html(((atual) / 5).toFixed(2));
        $('#parcelado_6x').html(((atual) / 6).toFixed(2));
        $('#parcelado_7x').html(((atual) / 7).toFixed(2));
        $('#parcelado_8x').html(((atual) / 8).toFixed(2));
        $('#parcelado_9x').html(((atual) / 9).toFixed(2));
        $('#parcelado_10x').html(((atual) / 10).toFixed(2));
        $('#parcelado_11x').html(((atual) / 11).toFixed(2));
        $('#parcelado_12x').html(((atual) / 12).toFixed(2));
    };
    this.contaMarcacoesLinha = function ContaMarcacoesLinha(linha) {
        return parseInt($("#Cadastro_tabelaDinamica [linhaatual = " + linha + "][colunaatual = 22]").html());
    };
    this.procuraTituloLinha = function TituloparaLinha(titulo) {
        var linha = -1;
        $("#Cadastro_tabelaDinamica > tr [colunaatual = 0]").each(function() {
            if ($(this).html() === titulo) {
                linha = $(this).attr("linhaatual");
//				alert("titulo:"+titulo+"/nLinha:"+linha);
            }
        });

        return linha;
    };
    this.descontoBCG = function BCG() {
        if ($("#descontoBCG").is(":checked")) {
            this.BCGmarcado = true;
        } else {
            this.BCGmarcado = false;
        }
        controle.recalcular();
    };
    this.descontoPromocao = 0;

}
;

function ControleDinamico() {
    moduloid = -1;
    this.linhas = ["BCG id", "Hepatite B- inf", "Rotavírus MSD", "HEXAc", "PENTAc", "PNEUMO 13", "MENINGO C conj", "Gripe Inf TRI", "TETRAVIRAL", "TRIVIRAL", "Varicela", "Febre Amarela", "TP Master", "T. Orelhinha", "Teste do Olhinho", "DTPa"];
    this.valoreAvista = [];
    this.valorPrazo = [];
    this.novasLinhas = [];
    this.deslocamentoVertical = 0;
    this.deslocamentoHorizontal = 0;
    this.Construtor = function ControleDinamico() {
        for (var i = 0; i < this.linhas.length; i++) {
            var $toADD = $("<tr/>")
                    .attr('class', 'white')
                    .attr('linhaAtual', i);
            var preco_vacina_prazo = 0;
            var preco_vacina_vista = 0;
            $.ajax({
                url: 'index.php?module=modulos&look=preco_geral&tmp=0',
                type: 'POST',
                dataType: 'json',
                data: {vacinas: this.linhas},
                success: function(resposta) {
//					alert(resposta);
                    for (var k = 0; k < resposta.length; k++) {
                        controle.valoreAvista[k] = resposta[k][0];
                        controle.valorPrazo[k] = resposta[k][1];
                    }
                }
            });
//			$.ajax({
//				url: 'index.php?module=modulos&look=preco_vista&tmp=0',
//				type: 'GET',
//				async: false,
//				data: {vacina: this.linhas[i]},
//				success: function(valor_vista) {
//					preco_vacina_vista = valor_vista;
//				}});
            var $th = $("<th/>")
                    .attr('class', 'titulo')
                    .attr('align', 'center')
                    .attr('linhaAtual', i)
                    .attr('colunaAtual', 0)
                    .attr('valorUNprazo', parseFloat(this.valorPrazo[i]))
                    .attr('valorUNvista', parseFloat(this.valoreAvista[i]))
                    .html(this.linhas[i]);


            $toADD.append($th);
            for (var j = 1; j < 24; j++) {
                var SimouNao;
                if (j === 20) {

                    if (i === 0 || i === 1 || i === 2 || i === 9 || i === 11 || i === 16) {
                        SimouNao = 'sim';
                    } else if (i === 3 || i === 8) {
                        SimouNao = 'nao';
                    } else {
                        SimouNao = '';
                    }

                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i)
                            .attr('colunaAtual', j)
                            .html(SimouNao);
                    $toADD.append($th);
                }
                else if (j === 21) {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i)
                            .attr('colunaAtual', j)
                            .html('sim');
                    $toADD.append($th);

                }
                else if (j === 22) {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i)
                            .attr('colunaAtual', j)
                            .html('0');
                    $toADD.append($th);
                } else if (j === 23) {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i)
                            .attr('colunaAtual', j)
                            .attr('style', 'color:black')
                            .html('0.00');
                    $toADD.append($th);
                }

                else {
                    //Aqui
                    //Quadrados Amarelos
                    //Inicio de marcação de laranja na tabela
                    if (i === 0 && j === 1) {
                        var $th = $("<th/>")
                                .attr('class', 'titulo cell-color_1')
                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                .attr('linhaAtual', i)
                                .attr('colunaAtual', j)
                                .html('');
                        $toADD.append($th);
                    } else {
                        if (i === 1 && j === 1) {
                            var $th = $("<th/>")
                                    .attr('class', 'titulo cell-color_1')
                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                    .attr('linhaAtual', i)
                                    .attr('colunaAtual', j)
                                    .html('');
                            $toADD.append($th);
                        } else {
                            if (i === 1 && j === 3) {
                                var $th = $("<th/>")
                                        .attr('class', 'titulo cell-color_1')
                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                        .attr('linhaAtual', i)
                                        .attr('colunaAtual', j)
                                        .html('');
                                $toADD.append($th);
                            } else {
                                if (i === 2 && j === 3) {
                                    var $th = $("<th/>")
                                            .attr('class', 'titulo cell-color_1')
                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                            .attr('linhaAtual', i)
                                            .attr('colunaAtual', j)
                                            .html('');
                                    $toADD.append($th);
                                } else {
                                    if (i === 3 && j === 3) {
                                        var $th = $("<th/>")
                                                .attr('class', 'titulo cell-color_1')
                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                .attr('linhaAtual', i)
                                                .attr('colunaAtual', j)
                                                .html('');
                                        $toADD.append($th);
                                    } else {
                                        if (i === 4 && j === 3) {
                                            var $th = $("<th/>")
                                                    .attr('class', 'titulo cell-color_1')
                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                    .attr('linhaAtual', i)
                                                    .attr('colunaAtual', j)
                                                    .html('');
                                            $toADD.append($th);
                                        } else {
                                            if (i === 5 && j === 3) {
                                                var $th = $("<th/>")
                                                        .attr('class', 'titulo cell-color_1')
                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                        .attr('linhaAtual', i)
                                                        .attr('colunaAtual', j)
                                                        .html('');
                                                $toADD.append($th);
                                            } else {
                                                if (i === 6 && j === 4) {
                                                    var $th = $("<th/>")
                                                            .attr('class', 'titulo cell-color_1')
                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                            .attr('linhaAtual', i)
                                                            .attr('colunaAtual', j)
                                                            .html('');
                                                    $toADD.append($th);
                                                } else {
                                                    if (i === 5 && j === 5) {
                                                        var $th = $("<th/>")
                                                                .attr('class', 'titulo cell-color_1')
                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                .attr('linhaAtual', i)
                                                                .attr('colunaAtual', j)
                                                                .html('');
                                                        $toADD.append($th);
                                                    } else {
                                                        if (i === 4 && j === 5) {
                                                            var $th = $("<th/>")
                                                                    .attr('class', 'titulo cell-color_1')
                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                    .attr('linhaAtual', i)
                                                                    .attr('colunaAtual', j)
                                                                    .html('');
                                                            $toADD.append($th);
                                                        } else {
                                                            if (i === 3 && j === 5) {
                                                                var $th = $("<th/>")
                                                                        .attr('class', 'titulo cell-color_1')
                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                        .attr('linhaAtual', i)
                                                                        .attr('colunaAtual', j)
                                                                        .html('');
                                                                $toADD.append($th);
                                                            } else {
                                                                if (i === 2 && j === 5) {
                                                                    var $th = $("<th/>")
                                                                            .attr('class', 'titulo cell-color_1')
                                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                            .attr('linhaAtual', i)
                                                                            .attr('colunaAtual', j)
                                                                            .html('');
                                                                    $toADD.append($th);
                                                                } else {
                                                                    if (i === 6 && j === 6) {
                                                                        var $th = $("<th/>")
                                                                                .attr('class', 'titulo cell-color_1')
                                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                .attr('linhaAtual', i)
                                                                                .attr('colunaAtual', j)
                                                                                .html('');
                                                                        $toADD.append($th);
                                                                    } else {
                                                                        if (i === 5 && j === 7) {
                                                                            var $th = $("<th/>")
                                                                                    .attr('class', 'titulo cell-color_1')
                                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                    .attr('linhaAtual', i)
                                                                                    .attr('colunaAtual', j)
                                                                                    .html('');
                                                                            $toADD.append($th);
                                                                        } else {
                                                                            if (i === 4 && j === 7) {
                                                                                var $th = $("<th/>")
                                                                                        .attr('class', 'titulo cell-color_1')
                                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                        .attr('linhaAtual', i)
                                                                                        .attr('colunaAtual', j)
                                                                                        .html('');
                                                                                $toADD.append($th);
                                                                            } else {
                                                                                if (i === 3 && j === 7) {
                                                                                    var $th = $("<th/>")
                                                                                            .attr('class', 'titulo cell-color_1')
                                                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                            .attr('linhaAtual', i)
                                                                                            .attr('colunaAtual', j)
                                                                                            .html('');
                                                                                    $toADD.append($th);
                                                                                } else {
                                                                                    if (i === 2 && j === 7) {
                                                                                        var $th = $("<th/>")
                                                                                                .attr('class', 'titulo cell-color_1')
                                                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                .attr('linhaAtual', i)
                                                                                                .attr('colunaAtual', j)
                                                                                                .html('');
                                                                                        $toADD.append($th);
                                                                                    } else {
                                                                                        if (i === 1 && j === 7) {
                                                                                            var $th = $("<th/>")
                                                                                                    .attr('class', 'titulo cell-color_1')
                                                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                    .attr('linhaAtual', i)
                                                                                                    .attr('colunaAtual', j)
                                                                                                    .html('');
                                                                                            $toADD.append($th);
                                                                                        } else {
                                                                                            if (i === 11 && j === 10) {
                                                                                                var $th = $("<th/>")
                                                                                                        .attr('class', 'titulo cell-color_1')
                                                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                        .attr('linhaAtual', i)
                                                                                                        .attr('colunaAtual', j)
                                                                                                        .html('');
                                                                                                $toADD.append($th);
                                                                                            } else {
                                                                                                if (i === 14 && j === 1) {
                                                                                                    var $th = $("<th/>")
                                                                                                            .attr('class', 'titulo cell-color_1')
                                                                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                            .attr('linhaAtual', i)
                                                                                                            .attr('colunaAtual', j)
                                                                                                            .html('');
                                                                                                    $toADD.append($th);
                                                                                                } else {
                                                                                                    if (i === 13 && j === 1) {
                                                                                                        var $th = $("<th/>")
                                                                                                                .attr('class', 'titulo cell-color_1')
                                                                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                .attr('linhaAtual', i)
                                                                                                                .attr('colunaAtual', j)
                                                                                                                .html('');
                                                                                                        $toADD.append($th);
                                                                                                    } else {
                                                                                                        if (i === 12 && j === 1) {
                                                                                                            var $th = $("<th/>")
                                                                                                                    .attr('class', 'titulo cell-color_1')
                                                                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                    .attr('linhaAtual', i)
                                                                                                                    .attr('colunaAtual', j)
                                                                                                                    .html('');
                                                                                                            $toADD.append($th);
                                                                                                        } else {
                                                                                                            if (i === 15 && j === 3) {
                                                                                                                var $th = $("<th/>")
                                                                                                                        .attr('class', 'titulo cell-color_1')
                                                                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                        .attr('linhaAtual', i)
                                                                                                                        .attr('colunaAtual', j)
                                                                                                                        .html('');
                                                                                                                $toADD.append($th);
                                                                                                            } else {
                                                                                                                if (i === 15 && j === 5) {
                                                                                                                    var $th = $("<th/>")
                                                                                                                            .attr('class', 'titulo cell-color_1')
                                                                                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                            .attr('linhaAtual', i)
                                                                                                                            .attr('colunaAtual', j)
                                                                                                                            .html('');
                                                                                                                    $toADD.append($th);
                                                                                                                } else {
                                                                                                                    if (i === 15 && j === 7) {
                                                                                                                        var $th = $("<th/>")
                                                                                                                                .attr('class', 'titulo cell-color_1')
                                                                                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                .attr('linhaAtual', i)
                                                                                                                                .attr('colunaAtual', j)
                                                                                                                                .html('');
                                                                                                                        $toADD.append($th);
                                                                                                                    } else {
                                                                                                                        if (i === 5 && j === 13) {
                                                                                                                            var $th = $("<th/>")
                                                                                                                                    .attr('class', 'titulo cell-color_1')
                                                                                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                    .attr('linhaAtual', i)
                                                                                                                                    .attr('colunaAtual', j)
                                                                                                                                    .html('');
                                                                                                                            $toADD.append($th);
                                                                                                                        } else {
                                                                                                                            if (i === 6 && j === 14) {
                                                                                                                                var $th = $("<th/>")
                                                                                                                                        .attr('class', 'titulo cell-color_1')
                                                                                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                        .attr('linhaAtual', i)
                                                                                                                                        .attr('colunaAtual', j)
                                                                                                                                        .html('');
                                                                                                                                $toADD.append($th);
                                                                                                                            } else {
                                                                                                                                if (i === 7 && j === 14) {
                                                                                                                                    var $th = $("<th/>")
                                                                                                                                            .attr('class', 'titulo cell-color_1')
                                                                                                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                            .attr('linhaAtual', i)
                                                                                                                                            .attr('colunaAtual', j)
                                                                                                                                            .html('');
                                                                                                                                    $toADD.append($th);
                                                                                                                                } else {
                                                                                                                                    if (i === 8 && j === 14) {
                                                                                                                                        var $th = $("<th/>")
                                                                                                                                                .attr('class', 'titulo cell-color_1')
                                                                                                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                .attr('linhaAtual', i)
                                                                                                                                                .attr('colunaAtual', j)
                                                                                                                                                .html('');
                                                                                                                                        $toADD.append($th);
                                                                                                                                    } else {
                                                                                                                                        if (i === 9 && j === 14) {
                                                                                                                                            var $th = $("<th/>")
                                                                                                                                                    .attr('class', 'titulo cell-color_1')
                                                                                                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                    .attr('linhaAtual', i)
                                                                                                                                                    .attr('colunaAtual', j)
                                                                                                                                                    .html('');
                                                                                                                                            $toADD.append($th);
                                                                                                                                        } else {
                                                                                                                                            if (i === 10 && j === 14) {
                                                                                                                                                var $th = $("<th/>")
                                                                                                                                                        .attr('class', 'titulo cell-color_1')
                                                                                                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                        .attr('linhaAtual', i)
                                                                                                                                                        .attr('colunaAtual', j)
                                                                                                                                                        .html('');
                                                                                                                                                $toADD.append($th);
                                                                                                                                            } else {
                                                                                                                                                if (i === 3 && j === 15) {
                                                                                                                                                    var $th = $("<th/>")
                                                                                                                                                            .attr('class', 'titulo cell-color_1')
                                                                                                                                                            .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                            .attr('linhaAtual', i)
                                                                                                                                                            .attr('colunaAtual', j)
                                                                                                                                                            .html('');
                                                                                                                                                    $toADD.append($th);
                                                                                                                                                } else {
                                                                                                                                                    if (i === 4 && j === 15) {
                                                                                                                                                        var $th = $("<th/>")
                                                                                                                                                                .attr('class', 'titulo cell-color_1')
                                                                                                                                                                .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                                .attr('linhaAtual', i)
                                                                                                                                                                .attr('colunaAtual', j)
                                                                                                                                                                .html('');
                                                                                                                                                        $toADD.append($th);
                                                                                                                                                    } else {
                                                                                                                                                        if (i === 15 && j === 15) {
                                                                                                                                                            var $th = $("<th/>")
                                                                                                                                                                    .attr('class', 'titulo cell-color_1')
                                                                                                                                                                    .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                                    .attr('linhaAtual', i)
                                                                                                                                                                    .attr('colunaAtual', j)
                                                                                                                                                                    .html('');
                                                                                                                                                            $toADD.append($th);
                                                                                                                                                        } else {
                                                                                                                                                            if (i === 8 && j === 18) {
                                                                                                                                                                var $th = $("<th/>")
                                                                                                                                                                        .attr('class', 'titulo cell-color_1')
                                                                                                                                                                        .attr('align', 'center')
//								.attr('style', 'background-color:yellow;')
                                                                                                                                                                        .attr('linhaAtual', i)
                                                                                                                                                                        .attr('colunaAtual', j)
                                                                                                                                                                        .html('');
                                                                                                                                                                $toADD.append($th);
                                                                                                                                                            } else {
                                                                                                                                                                var $th = $("<th/>")
                                                                                                                                                                        .attr('class', 'titulo')
                                                                                                                                                                        .attr('align', 'center')
                                                                                                                                                                        .attr('linhaAtual', i)
                                                                                                                                                                        .attr('colunaAtual', j);
                                                                                                                                                                $toADD.append($th);
                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
                this.deslocamentoHorizontal++;
            }
            $("#Cadastro_tabelaDinamica").append($toADD);

            this.deslocamentoVertical++;
        }
        $('#Cadastro_tabelaDinamica th[colunaAtual !=0 ][colunaAtual != 20][colunaAtual != 21][colunaAtual != 22][colunaAtual != 23].titulo').bind('click', function() {
            var temp = $(this);
            if (temp.attr('style') === 'background-color:blue') {
                temp.attr('style', 'background-color:');
            } else {
                $('#Cadastro_tabelaDinamica th[colunaAtual !=0 ][colunaAtual != 20][colunaAtual != 21][colunaAtual != 22][colunaAtual != 23][style = "background-color:blue"].titulo').attr('style', 'background-color:');
                temp.attr('style', 'background-color:blue');
            }
        });

		$('#Cadastro_tabelaDinamica th[colunaAtual !=0 ][colunaAtual != 20][colunaAtual != 21][colunaAtual != 22][colunaAtual != 23].titulo').bind('dblclick', function() {
            var temp = $(this);
            var i = temp.attr('linhaAtual');
            var j = temp.attr('colunaAtual');
            var vacina = $('#Cadastro_tabelaDinamica  th[linhaAtual=' + i + '][colunaAtual=0]').html();
            if (temp.html() === 'X') {
                $.ajax({
                    url: 'index.php?module=modulos&look=preco_cartao&tmp=0',
                    type: 'GET',
                    async: false,
                    data: {vacina: vacina},
                    success: function(resposta) {
                        var coluna21 = $('#Cadastro_tabelaDinamica th[colunaAtual = 22][linhaAtual = ' + i + '].titulo');
                        coluna21.html(parseFloat(coluna21.html()) - 1);
                        var coluna22 = $('#Cadastro_tabelaDinamica th[colunaAtual = 23][linhaAtual = ' + i + '].titulo');
                        coluna22.html((parseFloat(coluna22.html()) - parseFloat(resposta)).toFixed(2));
                    }
                });
                temp.html('');
                $.ajax({
                    url: 'index.php?module=modulos&look=sessaoKill&tmp=0',
                    type: 'POST',
					async: false,
                    data: {posicaoVertical: temp.attr("linhaatual"),
                        posicaoHorizontal: temp.attr("colunaatual")},
                    success: function(resposta) {
                    }
                });
                temp.html('');
            } else {
                $.ajax({
                    url: 'index.php?module=modulos&look=preco_cartao&tmp=0',
                    type: 'GET',
                    async: false,
                    data: {vacina: vacina},
                    success: function(resposta) {
                        var coluna21 = $('#Cadastro_tabelaDinamica th[colunaAtual = 22][linhaAtual = ' + i + '].titulo');
                        coluna21.html(parseFloat(coluna21.html()) + 1);
                        var coluna22 = $('#Cadastro_tabelaDinamica th[colunaAtual = 23][linhaAtual = ' + i + '].titulo');
                        coluna22.html((parseFloat(coluna22.html()) + parseFloat(resposta)).toFixed(2));
                    }
                });
                temp.html('X');//Check!
                $.ajax({
                    url: 'index.php?module=modulos&look=sessao&tmp=0',
                    type: 'POST',
					async: false,
                    data: {vacina: vacina,
                        posicaoVertical: temp.attr("linhaatual"),
                        posicaoHorizontal: temp.attr("colunaatual")},
                    success: function(resposta) {
                    }
                });//Check!
            }
            controle.recalcular();
        });
    };
    this.Contagem = 0;
    this.recalcular = function() {
		
        var Soma_Total_prazo = 0;
        var Soma_Total = 0;
        var Soma_Total_vista = 0;
        Contagem = 0;
        var linhas = controle.countLine();
        for (var i = 0; i < linhas; i++) {
            var pesquisa = $('#Cadastro_tabelaDinamica th[colunaAtual = 0][linhaAtual = ' + i + '].titulo');
            var Linha_valor_prazo = parseFloat(pesquisa.attr('valorUNprazo'));
			//console.log("Linha["+i+"]:"+Linha_valor_prazo);
            var valorfixo = Linha_valor_prazo;
            descontoseisporcento = true;
            var valor_vista = parseFloat(pesquisa.attr('valorUNvista'));
            var vacina_nome = $('#Cadastro_tabelaDinamica th[colunaAtual = 0][linhaAtual = ' + i + '].titulo').html();
            var Linha_count = $('#Cadastro_tabelaDinamica th[colunaAtual = 22][linhaAtual = ' + i + '].titulo').html();
            Contagem = Contagem + parseInt(Linha_count);
            var valor_programa = 0;
            if (calculo.BCGmarcado && (vacina_nome === "BCG id" || vacina_nome === "BCG pc")) {
                if (calculo.Medicomarcado) {
                    valor_vista = 89 * 0.80;
                    Linha_valor_prazo = 89 * 0.85;
                    valorfixo = 89;
                } else {
                    valor_vista = 81;
                    Linha_valor_prazo = 89 * 0.94;
                    valorfixo = 89;
                }
                descontoseisporcento = false;
            } else {
                if (calculo.HPVmarcado && vacina_nome === "HPV MSD" && parseFloat(Linha_count) > 2) {
                    if (calculo.Medicomarcado) {
                        valor_vista = 380 * 0.80;
                        Linha_valor_prazo = 380 * 0.85;
                        valorfixo = 380;
                    } else {
                        valor_vista = 350;
                        Linha_valor_prazo = 380;
                        valorfixo = 380;
                    }
                    descontoseisporcento = false;
                } else {
                    if (calculo.Medicomarcado) {
                        valor_vista = parseFloat(Linha_valor_prazo * 0.80);
                        Linha_valor_prazo = parseFloat(Linha_valor_prazo * 0.85);
                        descontoseisporcento = false;
                    } else {
                        valor_vista = parseFloat(valor_vista);
                        Linha_valor_prazo = parseFloat(Linha_valor_prazo);
                    }
                }
            }
            Soma_Total = Soma_Total + (parseFloat(Linha_count) * parseFloat(valorfixo));
            Soma_Total_prazo = parseFloat(Soma_Total_prazo) + (parseFloat(Linha_count) * parseFloat(Linha_valor_prazo));
            Soma_Total_vista = parseFloat(Soma_Total_vista) + (parseFloat(valor_vista) * parseFloat(Linha_count));
			
            if (Contagem > 3 && (vacina_nome !== "BCG id" || vacina_nome !== "BCG pc") && !calculo.BCGmarcado && !calculo.Medicomarcado) {
                if (descontoseisporcento) {
                    valor_programa = Soma_Total_prazo * 0.94;
                }else{
                    valor_programa = Soma_Total_prazo;
                }
            } else {
                valor_programa = parseFloat(Soma_Total_prazo);
            }
			
            $('#valor_programa').html(parseFloat(valor_programa).toFixed(2));
            $('#parcelado_1x').html(parseFloat(valor_programa).toFixed(2));
            $('#parcelado_2x').html((parseFloat(valor_programa) / 2).toFixed(2));
            $('#parcelado_3x').html((parseFloat(valor_programa) / 3).toFixed(2));
            $('#parcelado_4x').html((parseFloat(valor_programa) / 4).toFixed(2));
            $('#parcelado_5x').html((parseFloat(valor_programa) / 5).toFixed(2));
            $('#parcelado_6x').html((parseFloat(valor_programa) / 6).toFixed(2));
            $('#parcelado_7x').html((parseFloat(valor_programa) / 7).toFixed(2));
            $('#parcelado_8x').html((parseFloat(valor_programa) / 8).toFixed(2));
            $('#parcelado_9x').html((parseFloat(valor_programa) / 9).toFixed(2));
            $('#parcelado_10x').html((parseFloat(valor_programa) / 10).toFixed(2));
            $('#parcelado_11x').html((parseFloat(valor_programa) / 11).toFixed(2));
            $('#parcelado_12x').html((parseFloat(valor_programa) / 12).toFixed(2));
        }
        $('#valor_total').html(parseFloat(Soma_Total).toFixed(2));
        if(descontoseisporcento){
            $('#desconto_vista').html(parseFloat(Soma_Total_vista ).toFixed(2));
        }else{
            $('#desconto_vista').html(parseFloat(Soma_Total_vista ).toFixed(2));
        }
    };
    this.valor_vista = 0;
    this.NovaLinha = function addLinha(linha) {
        this.novasLinhas.push(linha);
    };
    this.repaint = function Repaint(data) {
        for (var i = 0; i < this.novasLinhas.length; i++) {
            var $toADD = $("<tr />")
                    .attr('class', 'white');
            var preco_vacina_prazo = 0, preco_vacina_vista = 0;
            $.ajax({
                url: 'index.php?module=modulos&look=preco_cartao&tmp=0',
                type: 'GET',
                async: false,
                data: {vacina: this.novasLinhas[i]},
                success: function(resposta) {
                    preco_vacina_prazo = resposta;
                }
            });
            $.ajax({
                url: 'index.php?module=modulos&look=preco_vista&tmp=0',
                type: 'GET',
                async: false,
                data: {vacina: this.novasLinhas[i]},
                success: function(valor_vista) {
                    preco_vacina_vista = valor_vista;
                }});
            var $th = $("<th/>")
                    .attr('class', 'titulo')
                    .attr('align', 'center')
                    .attr('linhaAtual', i + this.deslocamentoVertical)
                    .attr('colunaAtual', 0)
                    .attr('valorUNprazo', preco_vacina_prazo)
                    .attr('valorUNvista', preco_vacina_vista)
                    .html(this.novasLinhas[i]);
            $toADD.append($th);
            for (var j = 1; j < 24; j++) {
                var SimouNao;
                if (j === 20) {
                    SimouNao = '';
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i + this.deslocamentoVertical)
                            .attr('colunaAtual', j)
                            .html(SimouNao);
                    $toADD.append($th);
                }
                else if (j === 21) {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i + this.deslocamentoVertical)
                            .attr('colunaAtual', j)
                            .html('sim');
                    $toADD.append($th);

                }
                else if (j === 22) {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i + this.deslocamentoVertical)
                            .attr('colunaAtual', j)
                            .html('0');
                    $toADD.append($th);
                } else if (j === 23) {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i + this.deslocamentoVertical)
                            .attr('colunaAtual', j)
                            .attr('style', 'color:black')
                            .html('0.00');
                    $toADD.append($th);
                } else {
                    var $th = $("<th/>")
                            .attr('class', 'titulo')
                            .attr('align', 'center')
                            .attr('linhaAtual', i + this.deslocamentoVertical)
                            .attr('colunaAtual', j);
                    $toADD.append($th);
                }

            }
            $("#Cadastro_tabelaDinamica").append($toADD);
        }
        this.deslocamentoVertical = this.deslocamentoVertical + this.novasLinhas.length;
        $('#Cadastro_tabelaDinamica th[colunaAtual != 0].titulo').unbind('click');
        $('#Cadastro_tabelaDinamica th[colunaAtual != 0].titulo').unbind('dblclick');
        $('#Cadastro_tabelaDinamica th[colunaAtual != 0].titulo').bind('click', function() {
            var temp = $(this);
            if (temp.attr('style') === 'background-color:blue') {
                temp.attr('style', 'background-color:');
            } else {
                $('#Cadastro_tabelaDinamica th[colunaAtual !=0 ][colunaAtual != 20][colunaAtual != 21][colunaAtual != 22][colunaAtual != 23][style = "background-color:blue"].titulo').attr('style', 'background-color:');
                temp.attr('style', 'background-color:blue');
            }
        });
        $('#Cadastro_tabelaDinamica th[colunaAtual != 0].titulo').bind('dblclick', function() {
            var temp = $(this);
            var i = temp.attr('linhaAtual');
            var j = temp.attr('colunaAtual');
            vacina = $('#Cadastro_tabelaDinamica  th[linhaAtual=' + i + '][colunaAtual=0]').html();
            if (temp.html() === 'X') {
                $.ajax({
                    url: 'index.php?module=modulos&look=preco_cartao&tmp=0',
                    type: 'GET',
                    async: false,
                    data: {vacina: vacina},
                    success: function(resposta) {
                        var coluna21 = $('#Cadastro_tabelaDinamica th[colunaAtual = 22][linhaAtual = ' + i + '].titulo');
                        coluna21.html(parseFloat(coluna21.html()) - 1);
                        var coluna22 = $('#Cadastro_tabelaDinamica th[colunaAtual = 23][linhaAtual = ' + i + '].titulo');
                        coluna22.html((parseFloat(coluna22.html()) - parseFloat(resposta)).toFixed(2));
                    }
                });
                temp.html('');
                $.ajax({
                    url: 'index.php?module=modulos&look=sessaoKill&tmp=0',
                    type: 'POST',
                    data: {
                        posicaoVertical: temp.attr("linhaatual"),
                        posicaoHorizontal: temp.attr("colunaatual")},
                    success: function(resposta) {
                    }
                });
            } else {
                $.ajax({
                    url: 'index.php?module=modulos&look=preco_cartao&tmp=0',
                    type: 'GET',
                    async: false,
                    data: {vacina: vacina},
                    success: function(resposta) {

                        var coluna21 = $('#Cadastro_tabelaDinamica th[colunaAtual = 22][linhaAtual = ' + i + '].titulo');
                        coluna21.html(parseFloat(coluna21.html()) + 1);
                        var coluna22 = $('#Cadastro_tabelaDinamica th[colunaAtual = 23][linhaAtual = ' + i + '].titulo');
                        coluna22.html((parseFloat(coluna22.html()) + parseFloat(resposta)).toFixed(2));
                    }
                });
                temp.html('X');
                var colunavalor = $('#Cadastro_tabelaDinamica th[colunaAtual = 23][linhaAtual = ' + i + '].titulo');
                var colunaNumero = $('#Cadastro_tabelaDinamica th[colunaAtual = 22][linhaAtual = ' + i + '].titulo');
                resultado = (parseFloat(colunavalor) / parseFloat(colunaNumero)).toFixed(2);
                $.ajax({
                    url: 'index.php?module=modulos&look=sessao&tmp=0',
                    type: 'POST',
                    data: {vacina: vacina,
                        posicaoVertical: temp.attr("linhaatual"),
                        posicaoHorizontal: temp.attr("colunaatual"),
                        valor: resultado},
                    success: function(resposta) {

                    }
                });
            }
            controle.recalcular();
        });
        this.linhas.concat(this.novasLinhas);
//		alert("DeslocamentoAnterior:"+this.deslocamentoVertical);
//		this.deslocamentoVertical = this.linhas.length + this.novasLinhas.length+1;
//		alert("DeslocamentoPosterior:"+this.deslocamentoVertical);
        this.novasLinhas = [];
    };
    this.mark = function Mark() {
        var selecionado = $("#listaVacinasDisponiveis :selected").attr('value');
        this.novasLinhas = this.novasLinhas.concat(selecionado);
        this.repaint();
    };
    this.countLine = function countLiner() {
        var numeroLinhas = 0;
        $("#Cadastro_tabelaDinamica > tr [colunaatual = 0]").each(function() {
            numeroLinhas = numeroLinhas + 1;
        });
        return numeroLinhas;
    };
    this.sessionPrint = function print() {
        if (moduloid === -1) {
            alert('Salve Antes seu modulo.');
        } else {
            var valorDaParcela = $('#parcelado_' + pagamento.parcela + 'x').html();
            var valorTotal = $('#valor_total').html();
            var valorDoPrograma = $('#valor_programa').html();
            var valorDesContoVista = $('#desconto_vista').html();
            console.log("modulo id = " + moduloid);
            window.open('index2.php?module=orcamento&modulo_id=' + moduloid + '&parcela=' + pagamento.parcela + '&valorDaParcela=' + valorDaParcela + '&valorTotal=' + valorTotal + '&valorDoPrograma=' + valorDoPrograma + '&valorDesContoVista=' + valorDesContoVista);
        }

    };
    this.sessionSave = function SessionSave() {
        pagamento.atualizar();
        if (pagamento.parcela !== -1 && dependente.membroid !== 0 && (($('.SelectList[estilo = "Midias"] :selected').val() !== '0' || $('.SelectList[estilo = "Medico"] :selected').val() !== '0' || $('.SelectList[estilo = "Climep"] :selected').val() !== '0'))) {
            $.ajax({
                url: 'index.php?module=modulos&look=sessaoSave&tmp=0',
                type: 'POST',
                data: {
                    clienteid: dependente.clienteid,
                    midiaid: $('.SelectList[estilo = "Midias"] :selected').val(),
                    medicoid: $('.SelectList[estilo = "Medico"] :selected').val(),
                    climepid: $('.SelectList[estilo = "Climep"] :selected').val(),
                    parcela: pagamento.parcela,
                    precomodulo: parseFloat(pagamento.valor),
                    precomodulo_cartao: parseFloat($('#valor_programa').html()),
                    precomodulo_vista: parseFloat($('#desconto_vista').html()),
                    descontoBCG: $('#descontoBCG').is(':checked'),
                    descontoMedico: $('#descontoMedico').is(':checked'),
                    descontoPromocional: $('#descontoHPV').is(':checked')},
                success: function(resposta) {
                    moduloid = resposta;
                    if (moduloid !== -1 && (moduloid !== 0 || moduloid !== '0')) {
                        alert("Gravação bem sucedida");
                    } else {
                        alert("Falha ao gravar");
                    }
                }
            });
        } else {
            alert("Verifique se todos os campos obrigatorios estão marcados e/ou selecionados");
        }
    };
    this.limpa = function Limpa() {
        this.deslocamentoHorizontal = 0;
        this.deslocamentoVertical = 0;
        $("#Cadastro_tabelaDinamica").html("");
        this.linhas = ["BCG id", "Hepatite B- inf", "Rotavírus MSD", "HEXAc", "PENTAc", "PNEUMO 13", "MENINGO C conj", "Gripe Inf TRI", "TETRAVIRAL", "TRIVIRAL", "Varicela", "Febre Amarela", "TP Master", "T. Orelhinha", "Teste do Olhinho", "DTPa"];
        $.ajax({
            url: 'index.php?module=modulos&look=sessaoKillAll&tmp=0',
            type: 'POST',
            data: {},
            success: function(resposta) {
            }
        });
        this.Construtor();
        var atual = 0.00;
        atual = atual.toFixed(2);
        $('#valor_total').html(atual);
        $('#valor_programa').html((atual * 0.94).toFixed(2));
        $('#desconto_vista').html((atual * 0.94).toFixed(2));
        $('#parcelado_1x').html((atual * 0.94).toFixed(2));
        $('#parcelado_2x').html(((atual * 0.94) / 2).toFixed(2));
        $('#parcelado_3x').html(((atual * 0.94) / 3).toFixed(2));
        $('#parcelado_4x').html(((atual * 0.94) / 4).toFixed(2));
        $('#parcelado_5x').html(((atual * 0.94) / 5).toFixed(2));
        $('#parcelado_6x').html(((atual * 0.94) / 6).toFixed(2));
        $('#parcelado_7x').html(((atual * 0.94) / 7).toFixed(2));
        $('#parcelado_8x').html(((atual * 0.94) / 8).toFixed(2));
        $('#parcelado_9x').html(((atual * 0.94) / 9).toFixed(2));
        $('#parcelado_10x').html(((atual * 0.94) / 10).toFixed(2));
        $('#parcelado_11x').html(((atual * 0.94) / 11).toFixed(2));
        $('#parcelado_12x').html(((atual * 0.94) / 12).toFixed(2));
		
		$("ul.ULDependenteModulos > li > .ClassDependenteModulos ").attr('style', 'background-color');
		$("tr > .titulo").attr('style', 'background-color');
		$('#descontoMedico').prop('checked', false);
		$('#descontoBCG').prop('checked', false);
		$('#descontoHPV').prop('checked', false);
		$('#listaVacinasDisponiveis').val('');
		$('#SelectListUsuarioIndicacao').val('');
		$('#ListaOrigemCliente').val('');
		$('#SelectList').val('');
		$('#SelectList[estilo = "Medico"]').attr('style', 'display: none');
		$('#SelectList[estilo = "Climep"]').attr('style', 'display: none');
		$('#SelectList[estilo = "Midias"]').attr('style', 'display: none');
		this.medico = false;
		this.climep = false;
		this.midias = true;

    };
}

function clientes() {
    this.medico = false;
    this.midias = false;
    this.climep = false;
    this.showlist = function showlist() {
        var temp = $('#ListaOrigemCliente :selected').val();
        if (temp === "Medico") {
            $('#SelectList[estilo = "Climep"]').attr('style', 'display: none');
            $('#SelectList[estilo = "Midias"]').attr('style', 'display: none');
            $('#SelectList[estilo = "Medico"]').attr('style', 'display: block');
            this.medico = true;
            this.climep = false;
            this.midias = false;
        } else if (temp === "Climep") {
            $('#SelectList[estilo = "Medico"]').attr('style', 'display: none');
            $('#SelectList[estilo = "Midias"]').attr('style', 'display: none');
            $('#SelectList[estilo = "Climep"]').attr('style', 'display: block');
            this.medico = false;
            this.climep = true;
            this.midias = false;
        } else if (temp === "Midias") {
            $('#SelectList[estilo = "Medico"]').attr('style', 'display: none');
            $('#SelectList[estilo = "Climep"]').attr('style', 'display: none');
            $('#SelectList[estilo = "Midias"]').attr('style', 'display: block');
            this.medico = false;
            this.climep = false;
            this.midias = true;
        }
    };
}
$(document).ready(function() {
    cliente = new clientes();
    controle = new ControleDinamico();
    dependente = new SelecionaDependente();
    listavacinas = new listadeVacinas();
    listavacinas.updateLista();
    calculo = new calculoDinamico();
    controle.Construtor();
    pagamento = new ModelPagamento();
});