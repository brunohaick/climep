var consultasTela = new function() {
    var $Historico;
    var $matricula;
    var $medico;
    var $idade;
    var $nome;

    var $antecedentesFamiliares;
    var $antecedentesPessoal;
    var $alergias;

    var $pacienteTamanho;
    var $pacientePeso;
    var $pacientePA;
    var $TextoNome;
    var $Texto;

    var $hipotese;
    var $prescricoes;
    var $atestado;
    var $crm;

    var $apgar;
    var $idadeGestacional;
    var $pesoNascimento;
    var $gestacaoId;
    var $partoId;
    var contadorConsultasporMedicoID = 0;

    this.SelectedClient;
    this.SelectedConsulta;
    this.SelectedCoracaoObj;
    this.SelectedOrelha1Obj;
    this.SelectedOrelha2Obj;
    this.SelectedOlhoObj;
    this.SelectedLinguaObj;

    this.abrirModal = function() {
        abrir_modal('boxe_consultas');
        this.capturaNovaConsultaporMedico(1);
    };

    this.ConsultaAnterior = function() {
        contadorConsultasporMedicoID = contadorConsultasporMedicoID + 1;
        console.log("Indice:" + contadorConsultasporMedicoID);
        this.capturaNovaConsultaporMedico(0);
    };

    this.ConsultaProxima = function() {
        if (contadorConsultasporMedicoID !== 0) {
            contadorConsultasporMedicoID = contadorConsultasporMedicoID - 1;
            console.log("Indice:" + contadorConsultasporMedicoID);
            this.capturaNovaConsultaporMedico(0);
        }
    };
    /***
     * @author Luiz Cortinhas;
     * @description Esta funç�o faz requisiç�o ao php que vai retornar um array contendo todos os pacientes
     * na fila de espera do medico logado.
     * 
     */
    this.CapturaFilaEsperaporMedico = function() {
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'pegaFilaEspera'
            },
            success: function(resposta) {
                console.log($.parseJSON(resposta));
                resposta = $.parseJSON(resposta);
                var table = $("#Corpo_Tabela_Fila_Espera");
                table.html(" ");
                for (var linha = 0; linha < resposta.length; linha++) {
                    var tr = document.createElement('tr');
                    var td_id = document.createElement('td');
                    var td_nome = document.createElement('td');
                    var td_hora = document.createElement('td');
                    var text_id = document.createTextNode(resposta[linha]['id']);
                    var text_nome = document.createTextNode(resposta[linha]['nome']);
                    var text_recepcao = document.createTextNode(resposta[linha]['hora_recepcao']);
                    td_id.appendChild(text_id);
                    td_nome.appendChild(text_nome);
                    td_hora.appendChild(text_recepcao);
                    tr.appendChild(td_id);
                    tr.appendChild(td_nome);
                    tr.appendChild(td_hora);
                    $(tr).attr('id', resposta[linha]['id']);
                    $(tr).height(10);
                    $(tr).hover(
                            function() {
                                $(this).css("background", "#C0D1E7");
                            },
                            function() {
                                $(this).css("background", "");
                            }
                    );                    
                    $(tr).dblclick(function() {
                        $.post("index.php?module=consultas&tmp=1", {                            
                            flag: 'pegaClienteFilaEspera',
                            idFila: $(this).attr('id')
                        }, function(result) {                            
                            consultasTela.getDadosDoCliente(result['cliente_id']);
                        }, "json");
                    });
                    table.append(tr);
                }
//                $("#Corpo_Tabela_Fila_Espera")
            }
        });

    }

    this.capturaNovaConsultaporMedico = function(index) {
        if (!index)
            index = contadorConsultasporMedicoID;
        var THIS = this;
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'pegaConsultaporMedico',
                indexLimit: index
            },
            success: function(resposta) {

                if (resposta.indexOf("0") > 1) {
                    THIS.AtualizaTela(resposta);
                }
//                else {
//                    contadorConsultasporMedicoID = -1;
//                    this.capturaNovaConsultaporMedico();
//                }
            }
        });
    };

    this.capturaClientesporPeriodo = function() {

//        var PeriodoInicial = $("#consultaInicial").prop('dataFormat');
//        var PeriodoFinal = $("#consultafinal").prop('dataFormat');
        var PeriodoInicial = $("#consultaInicial").val();
        var PeriodoFinal = $("#consultafinal").val();
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'pegaClientesporPeriodo',
                PeriodoInicial: PeriodoInicial,
                PeriodoFinal: PeriodoFinal
            },
            success: function(resposta) {
                resposta = $.parseJSON(resposta);
                if (typeof resposta === 'string') {
                    alert(resposta);
                    return;
                }
                var $tbody = $('#tbody-PesquisaClientePorPeriodo');
                $tbody.html('');

                for (var res in resposta) {
                    res = resposta[res];
                    var client = new clientePesquisa(res);
                    var $tr = client.getAsTableLine();
                    $tr.dblclick(function() {
                        consultasTela.getDadosDoCliente($(this).data('pesquisa').getClienteId());
                    });
                    $tbody.append($tr);
                }
            }
        });
    };

    this.AtualizaTela = function(respo) {
        loadVars();
        respo = $.parseJSON(respo);
        if (respo.Erro) {
            if (respo.code !== 4) {
                alert(respo.messagem);
            }
        } else {
            $Historico.html('');
            var first = true;
            var client;
            for (var historico in respo[0]) {

                historico = respo[0][historico];

                if (first) {
                    client = new cliente(historico).addPropertiChangeListener(functionCallBackForClient);
                }
                var consult = new consulta(historico.consultaID)
                        .setMedicoId(historico.medico_id)
                        .setData(historico.data)
                        .setTamanho(historico.altura)
                        .setPA(historico.PA)
                        .setEsp(historico.medico_consulta)
                        .setPeso(historico.peso)
                        .setConsultaTexto(historico.texto_descritivo)
                        .addPropertiChangeListener(functionCallBackForConsult);
                client.addConsulta(consult);
                if (first) {
                    consultasTela.setClienteSelected(client);
                    consultasTela.setConsultaSelected(consult);
                    first = false;
                }
            }
            this.CapturaFilaEsperaporMedico();
            geraHistorico(true);
        }
        //testesCutaneosPopulate();
        criaCID10();
        atualizaTestesCutaneos();
    };

    this.getSelectedConsulta = function() {
        return this.SelectedConsulta;
    };

    /**
     * Esta função irá preencher os campos dos submodais na tela de 
     * Triagem.
     * Para cada submodal existe uma classe que o "representa", desta
     * forma, de acordo com a categoria, esta função cria um objeto,
     * e com este objeto preenche os campos necessários no submodal.
     * 
     * @author Bruno Haick <brunohaick@gmail.com>
     * 
     * @param date $data data da consulta, que só é informada caso seja um edição de consulta.
     * @param integer $id_cat_consulta Id da categoria de consulta.
     * 
     */
    this.AtualizaTelaTriagem = function(idCategoria, data) {

        var tipo = '';
        var strhtml = '';

        if (data == undefined) {
            tipo = 'last';
        } else {
            tipo = 'data';
        }

        var clienteId = this.SelectedClient.getClienteId();

        /*
         * Esta requisição ajax serve para popular a aba de triagem.
         */
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'pegaHistoricoDeAtendimentoTriagem',
                clienteID: clienteId,
                data: data,
                tipo: tipo,
                idCategoria: idCategoria
            },
            success: function(resposta) {
                resposta = $.parseJSON(resposta);
                if (resposta.Erro) {
                    if (resposta.code !== 4) {
                        alert(resposta.messagem);
                    }
                } else {
                    var historico = '';

                    if (idCategoria == 1) { // id = 1 - Categoria Olhinho
                        var olhoObj;
                        if (tipo == 'last') {
                            /*
                             * Se o tipo de requisição for uma nova inserção de
                             * consulta Olho, deve-se limpar a tela, e abrir o modal.
                             */
                            olhoObj = new olho();
                            olhoObj.limpaModalOlhinho();
                            consultasTela.SelectedOlhoObj = olhoObj;
                        } else {
                            historico = resposta['olhinho'][0];
                            olhoObj = new olho(((historico.id) ? historico.id : ''))
                                    .setResultadoNormalOd(((historico.resultado_od_normal) ? historico.resultado_od_normal : ''))
                                    .setResultadoLeucoriaOd(((historico.resultado_od_leucoria) ? historico.resultado_od_leucoria : ''))
                                    .setResultadoSuspeitoOd(((historico.resultado_od_suspeito) ? historico.resultado_od_suspeito : ''))
                                    .setResultadoNormalOe(((historico.resultado_oe_normal) ? historico.resultado_oe_normal : ''))
                                    .setResultadoLeucoriaOe(((historico.resultado_oe_leucoria) ? historico.resultado_oe_leucoria : ''))
                                    .setResultadoSuspeitoOe(((historico.resultado_oe_suspeito) ? historico.resultado_oe_suspeito : ''))
                                    .setVisualNormal(((historico.visual_normal) ? historico.visual_normal : ''))
                                    .setOutrosExames(((historico.outros_exames) ? historico.outros_exames : ''))
                                    .setObservacao(((historico.observacao) ? historico.observacao : ''))
                                    .setOlhoAnotQp(((historico.qp) ? historico.qp : ''))
                                    .setOlhoAnotHf(((historico.hf) ? historico.hf : ''));

                            olhoObj.limpaCamposModalOlhinho();

                            if (olhoObj.getResultadoNormalOd() == 1) {
                                $('#olhinho_resultado_normal_od').prop('checked', 'checked');
                            }

                            if (olhoObj.getResultadoLeucoriaOd() == 1) {
                                $('#olhinho_resultado_leucoria_od').prop('checked', 'checked');
                            }

                            if (olhoObj.getResultadoSuspeitoOd() == 1) {
                                $('#olhinho_resultado_suspeito_od').prop('checked', 'checked');
                            }

                            if (olhoObj.getResultadoNormalOe() == 1) {
                                $('#olhinho_resultado_normal_oe').prop('checked', 'checked');
                            }

                            if (olhoObj.getResultadoLeucoriaOe() == 1) {
                                $('#olhinho_resultado_leucoria_oe').prop('checked', 'checked');
                            }

                            if (olhoObj.getResultadoSuspeitoOe() == 1) {
                                $('#olhinho_resultado_suspeito_oe').prop('checked', 'checked');
                            }

                            $('#olhinho_anotacoes_qp').val(olhoObj.getOlhoAnotQp());
                            $('#olhinho_anotacoes_hf').val(olhoObj.getOlhoAnotHf());
                            $('#olhinho_anotacoes_outros').val(olhoObj.getOutrosExames());
                            $('#olhinho_anotacoes_obs').val(olhoObj.getObservacao());
                            $('input:radio[name=\'olhinho_conclusao_normal\'][value=\'' + olhoObj.getVisualNormal() + '\']').prop('checked', 'checked');

                            /*
                             * Modificando os atributos do botão que pode ser para inserir ou 
                             * atualizar. Neste caso, o botão assume a responsabilidade de
                             * atualizar os dados da consulta.
                             */
                            $('#olho_botao_link').html('<i class="icon-inbox"></i> Atualizar');
                            $('#olho_botao_gravar').unbind();
                            $('#olho_botao_gravar').click(function() {
                                olhoObj.atualizaConsultaOlhoServidor(olhoObj.getConsultaOlhoId(), data);
                            });

                            /*
                             * Adicionando evento de click no botao imprimir.
                             */
                            $('#olhinho-consulta-imprimir').unbind();
                            $('#olhinho-consulta-imprimir').click(function() {
                                olhoObj.imprimeResultadoTesteOlhinho();
                            });

                            /*
                             * Requisição para obter as datas das consultas Olho do
                             * cliente escolhido, para que possa popular o combo box
                             * utilizado para escolher uma consulta para edição.
                             * Após popular as options, seta a option contendo a data
                             * da consulta que foi criada ou editada.
                             */
                            strhtml = '';
                            $.ajax({
                                url: 'index.php?module=consultas&tmp=1',
                                type: 'POST',
                                data: {
                                    flag: 'buscaULtimasConsultasOlhoData',
                                    idCliente: clienteId
                                },
                                success: function(resposta) {
                                    resposta = $.parseJSON(resposta);
                                    strhtml += '<option></option>';
                                    for (var i in resposta) {
                                        strhtml += '<option value=\'' + resposta[i]['consulta_olhinho_id'] + '\'>' + resposta[i]['data'] + '</option>'
                                    }
                                    $('#olho_ultimos_exames').html(strhtml);
                                    $('select#olho_ultimos_exames option[value=\'' + olhoObj.getConsultaOlhoId() + '\']').prop('selected', true);
                                }
                            });

                            consultasTela.SelectedOlhoObj = olhoObj;
                        }
                    } else if (idCategoria == 2) { // id = 2 - Categoria Orelhinha

                        var orelha1Obj;
                        if (tipo == 'last') {
                            /*
                             * Se o tipo de requisição for uma nova inserção de
                             * consulta Orelha1, deve-se limpar a tela, e abrir o modal.
                             */
                            orelha1Obj = new orelha1();
                            orelha1Obj.limpaModalOrelhinha1();
                            consultasTela.SelectedOrelha1Obj = orelha1Obj
                        } else {

                            historico = resposta['orelhinha1'][0];

                            orelha1Obj = new orelha1(((historico.id) ? historico.id : ''))
                                    .setFrequenciaOd(((historico.orelhinha1_frequencia_id_od) ? historico.orelhinha1_frequencia_id_od : ''))
                                    .setFrequenciaOe(((historico.orelhinha1_frequencia_id_oe) ? historico.orelhinha1_frequencia_id_oe : ''))
                                    .setObstrucaoMeatoOd(((historico.obstrucao_meato_od) ? historico.obstrucao_meato_od : ''))
                                    .setObstrucaoMeatoOe(((historico.obstrucao_meato_oe) ? historico.obstrucao_meato_oe : ''))
                                    .setLocalizacaoMeato(((historico.localizacao_meato) ? historico.localizacao_meato : ''))
                                    .setTahoeOd(((historico.teoae_od) ? historico.teoae_od : ''))
                                    .setTahoeOe(((historico.teoae_oe) ? historico.teoae_oe : ''))
                                    .setNoiseOd(((historico.noise_od) ? historico.noise_od : ''))
                                    .setNoiseOe(((historico.noise_oe) ? historico.noise_oe : ''))
                                    .setComportamento1(((historico.comportamento_select1) ? historico.comportamento_select1 : ''))
                                    .setComportamento2(((historico.comportamento_select2) ? historico.comportamento_select2 : ''))
                                    .setComportamento3(((historico.comportamento_select3) ? historico.comportamento_select3 : ''))
                                    .setComportamento4(((historico.comportamento_select4) ? historico.comportamento_select4 : ''))
                                    .setObservacao(((historico.observacao) ? historico.observacao : ''))
                                    .setFuncaoCoclear(((historico.funcao_coclear_presente_bilateral) ? historico.funcao_coclear_presente_bilateral : ''));

                            console.log(orelha1Obj);
                            orelha1Obj.limpaModalOrelhinha1();

                            $('#orelhinha1_tahoe_od').val(orelha1Obj.getTahoeOd());
                            $('#orelhinha1_noise_od').val(orelha1Obj.getNoiseOd());
                            $('select#orelhinha1_frequencia_od option[value=\'' + orelha1Obj.getFrequenciaOd() + '\']').prop('selected', true);
                            $('#orelhinha1_tahoe_oe').val(orelha1Obj.getTahoeOe());
                            $('#orelhinha1_noise_oe').val(orelha1Obj.getNoiseOe());
                            $('select#orelhinha1_frequencia_oe option[value=\'' + orelha1Obj.getFrequenciaOe() + '\']').prop('selected', true);
                            $('select#orelhinha1_meato_od option[value=\'' + orelha1Obj.getObstrucaoMeatoOd() + '\']').prop('selected', true);
                            $('select#orelhinha1_meato_oe option[value=\'' + orelha1Obj.getObstrucaoMeatoOe() + '\']').prop('selected', true);
                            $('select#orelhinha1_localizacao option[value=\'' + orelha1Obj.getLocalizacaoMeato() + '\']').prop('selected', true);
                            $('select#orelhinha1_avaliacao_01 option[value=\'' + orelha1Obj.getComportamento1() + '\']').prop('selected', true);
                            $('select#orelhinha1_avaliacao_02 option[value=\'' + orelha1Obj.getComportamento2() + '\']').prop('selected', true);
                            $('select#orelhinha1_avaliacao_03 option[value=\'' + orelha1Obj.getComportamento3() + '\']').prop('selected', true);
                            $('select#orelhinha1_avaliacao_04 option[value=\'' + orelha1Obj.getComportamento4() + '\']').prop('selected', true);
                            $('#orelhinha1_observacoes').val(orelha1Obj.getObservacao());

                            if (orelha1Obj.getFuncaoCoclear() == 1) {
                                $('input:checkbox#orelhinha1_conclusao').prop('checked', 'checked');
                            }

                            /*
                             * Modificando os atributos do botão que pode ser para inserir ou 
                             * atualizar. Neste caso, o botão assume a responsabilidade de
                             * atualizar os dados da consulta.
                             */
                            $('#orelha1_botao_link').html('<i class="icon-inbox"></i> Atualizar');
                            $('#orelha1_botao_gravar').unbind();
                            $('#orelha1_botao_gravar').click(function() {
                                orelha1Obj.atualizaConsultaOrelha1Servidor(orelha1Obj.getConsultaOrelhaId(), data);
                            });

                            /*
                             * Requisição para obter as datas das consultas Orelha1 do
                             * cliente escolhido, para que possa popular o combo box
                             * utilizado para escolher uma consulta para edição.
                             * Após popular as options, seta a option contendo a data
                             * da consulta que foi criada ou editada.
                             */
                            strhtml = '';
                            $.ajax({
                                url: 'index.php?module=consultas&tmp=1',
                                type: 'POST',
                                data: {
                                    flag: 'buscaULtimasConsultasOrelha1Data',
                                    idCliente: clienteId
                                },
                                success: function(resposta) {
                                    resposta = $.parseJSON(resposta);
                                    strhtml += '<option></option>';
                                    for (var i in resposta) {
                                        strhtml += '<option value=\'' + resposta[i]['consulta_orelhinha1_id'] + '\'>' + resposta[i]['data'] + '</option>'
                                    }
                                    $('#orelha1_ultimos_exames').html(strhtml);
                                    $('select#orelha1_ultimos_exames option[value=\'' + orelha1Obj.getConsultaOrelhaId() + '\']').prop('selected', true);
                                }
                            });
                            consultasTela.SelectedOrelha1Obj = orelha1Obj;
                        }
                    } else if (idCategoria == 3) { // id = 3 - Categoria Orelhinha2

                        var orelha2Obj;
                        if (tipo == 'last') {
                            /*
                             * Se o tipo de requisição for uma nova inserção de
                             * consulta Orelha1, deve-se limpar a tela, e abrir o modal.
                             */
                            orelha2Obj = new orelha2();
                            orelha2Obj.limpaModalOrelhinha2();
                            consultasTela.SelectedOrelha2Obj = orelha2Obj
                        } else {

                            historico = resposta['orelhinha2'][0];

                            var orelha2Obj = new orelha2(((historico.id) ? historico.id : ''))
                                    .setObstrucaoMeatoOd(((historico.obstrucao_meato_od) ? historico.obstrucao_meato_od : ''))
                                    .setObstrucaoMeatoOe(((historico.obstrucao_meato_oe) ? historico.obstrucao_meato_oe : ''))
                                    .setObservacao(((historico.observacao) ? historico.observacao : ''))
                                    .setFuncaoCoclear(((historico.funcao_coclear_presente_bilateral) ? historico.funcao_coclear_presente_bilateral : ''))
                                    .setConclusaoOd(((historico.conclusao_orelhinha_od) ? historico.conclusao_orelhinha_od : ''))
                                    .setConclusaoOe(((historico.conclusao_orelhinha_oe) ? historico.conclusao_orelhinha_oe : ''))
                                    .setEquipamentoTeste(((historico.equipamentos_teste_id) ? historico.equipamentos_teste_id : ''))
                                    .setResultadoCocleo(((historico.orelhinha2_resultado_cocleo_id) ? historico.orelhinha2_resultado_cocleo_id : ''));

                            console.log(orelha2Obj);

                            $('select#orelhinha2_conclusao_od option[value=\'' + orelha2Obj.getConclusaoOd() + '\']').prop('selected', true);
                            $('select#orelhinha2_conclusao_oe option[value=\'' + orelha2Obj.getConclusaoOe() + '\']').prop('selected', true);
                            $('select#orelhinha2_equipamento option[value=\'' + orelha2Obj.getEquipamentoTeste() + '\']').prop('selected', true);
                            $('select#orelhinha2_meato_od option[value=\'' + orelha2Obj.getObstrucaoMeatoOd() + '\']').prop('selected', true);
                            $('select#orelhinha2_meato_oe option[value=\'' + orelha2Obj.getObstrucaoMeatoOe() + '\']').prop('selected', true);
                            $('select#orelhinha2_cocleo option[value=\'' + orelha2Obj.getResultadoCocleo() + '\']').prop('selected', true);
                            $('#orelhinha2_observacoes').val(orelha2Obj.getObservacao());

                            if (orelha2Obj.getFuncaoCoclear() == 1) {
                                $('input:checkbox#orelhinha2_conclusao').prop('checked', 'checked');
                            }
                            /*
                             * Modificando os atributos do botão que pode ser para inserir ou 
                             * atualizar. Neste caso, o botão assume a responsabilidade de
                             * atualizar os dados da consulta.
                             */
                            $('#orelha2_botao_link').html('<i class="icon-inbox"></i> Atualizar');
                            $('#orelha2_botao_gravar').unbind();
                            $('#orelha2_botao_gravar').click(function() {
                                orelha2Obj.atualizaConsultaOrelha2Servidor(orelha2Obj.getConsultaOrelhaId(), data);
                            });

                            /*
                             * Adicionando evento de click no botao imprimir.
                             */
                            $('#orelha2_botao_imprimir').unbind();
                            $('#orelha2_botao_imprimir').click(function() {
                                orelha2Obj.imprimeResultadoTesteOrelinha2();
                            });

                            /*
                             * Requisição para obter as datas das consultas Orelha2 do
                             * cliente escolhido, para que possa popular o combo box
                             * utilizado para escolher uma consulta para edição.
                             * Após popular as options, seta a option contendo a data
                             * da consulta que foi criada ou editada.
                             */
                            strhtml = '';
                            $.ajax({
                                url: 'index.php?module=consultas&tmp=1',
                                type: 'POST',
                                data: {
                                    flag: 'buscaULtimasConsultasOrelha2Data',
                                    idCliente: clienteId
                                },
                                success: function(resposta) {
                                    resposta = $.parseJSON(resposta);
                                    strhtml += '<option></option>';
                                    for (var i in resposta) {
                                        strhtml += '<option value=\'' + resposta[i]['consulta_orelhinha2_id'] + '\'>' + resposta[i]['data'] + '</option>'
                                    }
                                    $('#orelha2_ultimos_exames').html(strhtml);
                                    $('select#orelha2_ultimos_exames option[value=\'' + orelha2Obj.getConsultaOrelhaId() + '\']').prop('selected', true);
                                }
                            });
                            consultasTela.SelectedOrelha2Obj = orelha2Obj;
                        }
                    } else if (idCategoria == 4) { // id = 4 - Categoria Coracaozinho
                        var coracaoObj;
                        if (tipo == 'last') {
                            /*
                             * Se o tipo de requisição for uma nova inserção de
                             * consulta Coracao, deve-se limpar a tela, e abrir o modal.
                             */
                            coracaoObj = new coracao();
                            coracaoObj.limpaModalCoracaozinho();
                            consultasTela.SelectedCoracaoObj = coracaoObj;
                        } else {
                            historico = resposta['coracaozinho'];
                            console.log(historico);
                            /*
                             * criando o objeto coracao, populando o objeto com os dados
                             * vindos do banco de dados referente a consultaCoracao
                             * escolhida para edição.
                             */
                            coracaoObj = new coracao(((historico.id) ? historico.id : ''))
                                    .setPercMaoDireita(((historico.perc_maoDireita) ? historico.perc_maoDireita : ''))
                                    .setPercPe(((historico.perc_pe) ? historico.perc_pe : ''))
                                    .setPercDiferenca(((historico.perc_diferenca) ? historico.perc_diferenca : ''))
                                    .setConclusaoTeste(((historico.conclusao_teste) ? historico.conclusao_teste : ''))
                                    .setOutrosExames(((historico.outros_exames) ? historico.outros_exames : ''))
                                    .setObservacao(((historico.observacao) ? historico.observacao : ''));

                            var historicoCorQp = resposta['coracaozinho']['qp'];
                            var historicoCorHf = resposta['coracaozinho']['hf'];
                            var qp_id = new Array();
                            var hf_id = new Array();

                            for (var i in historicoCorQp) {
                                qp_id.push(historicoCorQp[i]['coracao_anot_qp_id']);
                                $("input:checkbox[name='coracao_qp[]'][value='" + historicoCorQp[i]['coracao_anot_qp_id'] + "']").prop('checked', 'checked');
                            }

                            for (var i in historicoCorHf) {
                                hf_id.push(historicoCorHf[i]['coracao_anot_hf_id']);
                                $('input:checkbox[name=\'coracao_hf[]\'][value=\'' + historicoCorHf[i]['coracao_anot_hf_id'] + '\']').prop('checked', 'checked');
                            }
                            coracaoObj.setCoracaoAnotQp(((qp_id) ? qp_id : ''));
                            coracaoObj.setCoracaoAnotHf(((hf_id) ? hf_id : ''));

                            /*
                             * Populando os campos da tela (modal de triagem) de Consulta Coracao.
                             */
                            $('#coracao-mao-direita').val(coracaoObj.getPercMaoDireita());
                            $('#coracao-pe').val(coracaoObj.getPercPe());
                            $('#coracao-diferenca').val(coracaoObj.getPercDiferenca());
                            $('#coracao_outros_exames').val(coracaoObj.getOutrosExames());
                            $('#coracao-observacoes').val(coracaoObj.getObservacao());
                            $('input:radio[name=\'coracao_conclusao\'][value=\'' + coracaoObj.getConclusaoTeste() + '\']').prop('checked', 'checked');

                            /*
                             * Modificando os atributos do botão que pode ser para inserir ou 
                             * atualizar. Neste caso, o botão assume a responsabilidade de
                             * atualizar os dados da consulta.
                             */
                            $('#coracao_botao_link').html('<i class="icon-inbox"></i> Atualizar');
                            $('#coracao_botao_gravar').unbind();
                            $('#coracao_botao_gravar').click(function() {
                                coracaoObj.atualizaConsultaCoracaoServidor(coracaoObj.getConsultaCoracaoId(), data);
                            });
                            /*
                             * Adicionando evento de click no botao imprimir.
                             */
                            $('#coracao_botao_imprimir').unbind();
                            $('#coracao_botao_imprimir').click(function() {
                                coracaoObj.imprimeResultadoTesteCoracao();
                            });

                            /*
                             * Requisição para obter as datas das consultas Coracao do
                             * cliente escolhido, para que possa popular o combo box
                             * utilizado para escolher uma consulta para edição.
                             * Após popular as options, seta a option contendo a data
                             * da consulta que foi criada ou editada.
                             */
                            strhtml = '';
                            $.ajax({
                                url: 'index.php?module=consultas&tmp=1',
                                type: 'POST',
                                data: {
                                    flag: 'buscaULtimasConsultasCoracaoData',
                                    idCliente: clienteId
                                },
                                success: function(resposta) {
                                    resposta = $.parseJSON(resposta);
                                    strhtml += '<option></option>';
                                    for (var i in resposta) {
                                        strhtml += '<option value=\'' + resposta[i]['consulta_coracaozinho_id'] + '\'>' + resposta[i]['data'] + '</option>'
                                    }
                                    $('#coracao_ultimos_exames').html(strhtml);
                                    console.log('coracao ID == ' + coracaoObj.getConsultaCoracaoId());
                                    $('select#coracao_ultimos_exames option[value=\'' + coracaoObj.getConsultaCoracaoId() + '\']').prop('selected', true);
                                }
                            });
                            consultasTela.SelectedCoracaoObj = coracaoObj;
                        }

                    } else if (idCategoria == 5) { // id = 5 - Categoria Linguinha
                        var linguaObj;
                        if (tipo == 'last') {
                            /*
                             * Se o tipo de requisição for uma nova inserção de
                             * consulta Coracao, deve-se limpar a tela, e abrir o modal.
                             */
                            linguaObj = new lingua();
                            linguaObj.limpaModalLinguinha();
                            consultasTela.SelectedLinguaObj = linguaObj;
                        } else {
                            historico = resposta['linguinha'];

                            linguaObj = new lingua(((historico.id) ? historico.id : ''))
                                    .setOutrosExames(((historico.outros_exames) ? historico.outros_exames : ''))
                                    .setObservacao(((historico.observacao) ? historico.observacao : ''))
                                    .setFreioLingualNormal(((historico.freio_lingual_normal) ? historico.freio_lingual_normal : ''))
                                    .setFreioLingualCurto(((historico.freio_lingual_curto) ? historico.freio_lingual_curto : ''))
                                    .setInsercaoAnteriorizada(((historico.insercao_anteriorizada) ? historico.insercao_anteriorizada : ''))
                                    .setAnquiloglossia(((historico.Anquiloglossia) ? historico.Anquiloglossia : ''))
                                    .setConclusaoTeste(((historico.conclusao_teste) ? historico.conclusao_teste : ''));

                            linguaObj.limpaCamposModalLinguinha();

                            var historicoLingQp = resposta['linguinha']['qp'];
                            var ling_qp_id = new Array();
                            var tmp = '';
                            for (var i in historicoLingQp) {
                                tmp = historicoLingQp[i]['lingua_anot_qp_id'];

                                /**
                                 * Se a opção com id 2 (dificuldade na amamentação)
                                 * esta possui subopções, então este irá habilitar
                                 * estas subopções.
                                 */
                                if (tmp == 2) {
                                    consultasTela.ativaLinguaOpcoes();
                                }

                                ling_qp_id.push(tmp);
                                $('input:checkbox[name=\'linguinha_qp[]\'][value=\'' + tmp + '\']').prop('checked', 'checked');
                            }

                            linguaObj.setLingua_anot_qp(((ling_qp_id) ? ling_qp_id : ''));

                            if (linguaObj.getFreioLingualNormal() == 1) {
                                $('#linguinha_result_freio_ling_normal').prop('checked', 'checked');
                            }

                            if (linguaObj.getFreioLingualCurto() == 1) {
                                $('#linguinha_result_freio_ling_curto').prop('checked', 'checked');
                            }

                            if (linguaObj.getInsercaoAnteriorizada() == 1) {
                                $('#linguinha_result_insercao_anter').prop('checked', 'checked');
                            }

                            if (linguaObj.getAnquiloglossia() == 1) {
                                $('#linguinha_result_anquiloglossia').prop('checked', 'checked');
                            }

                            $('#linguinha_outros_exames').val(linguaObj.getOutrosExames());
                            $('#linguinha_observacoes').val(linguaObj.getObservacao());
                            $('input:radio[name=\'linguinha_conclusao\'][value=\'' + linguaObj.getConclusaoTeste() + '\']').prop('checked', 'checked');

                            /*
                             * Modificando os atributos do botão que pode ser para inserir ou 
                             * atualizar. Neste caso, o botão assume a responsabilidade de
                             * atualizar os dados da consulta.
                             */
                            $('#lingua_botao_link').html('<i class="icon-inbox"></i> Atualizar');
                            $('#lingua_botao_gravar').unbind();
                            $('#lingua_botao_gravar').click(function() {
                                linguaObj.atualizaConsultaLinguaServidor(linguaObj.getConsultaLinguaId(), data);
                            });
                            /*
                             * Adicionando evento de click no botao imprimir.
                             */
                            $('#lingua_botao_imprimir').unbind();
                            $('#lingua_botao_imprimir').click(function() {
                                linguaObj.imprimeResultadoTesteLingua();
                            });

                            /*
                             * Requisição para obter as datas das consultas Lingua do
                             * cliente escolhido, para que possa popular o combo box
                             * utilizado para escolher uma consulta para edição.
                             * Após popular as options, seta a option contendo a data
                             * da consulta que foi criada ou editada.
                             */
                            strhtml = '';

                            $.ajax({
                                url: 'index.php?module=consultas&tmp=1',
                                type: 'POST',
                                data: {
                                    flag: 'buscaULtimasConsultasLinguaData',
                                    idCliente: clienteId
                                },
                                success: function(resultado) {
                                    var resultado = $.parseJSON(resultado);
                                    strhtml += '<option></option>';
                                    for (var i in resultado) {
                                        strhtml += '<option value=\'' + resultado[i]['consulta_linguinha_id'] + '\'>' + resultado[i]['data'] + '</option>'
                                    }
                                    $('#lingua_ultimos_exames').html(strhtml);
                                    $('select#lingua_ultimos_exames option[value=\'' + linguaObj.getConsultaLinguaId() + '\']').prop('selected', true);
                                }
                            });
                            consultasTela.SelectedLinguaObj = linguaObj;
                        } // FIM ELSE
                    }
                }
            }
        });
    };

    this.modalCoracaozinho = function() {
        this.AtualizaTelaTriagem(4);
        abrir_modal('boxes-coracaozinho');
    };

    this.escolheDataCoracaozinho = function() {
        var data = $('#coracao_ultimos_exames option:selected').text();
        this.AtualizaTelaTriagem(4, '\'' + data + '\'');
    };

    this.modalLinguinha = function() {
        this.AtualizaTelaTriagem(5);
        abrir_modal('boxes-linguinha');
    };

    this.escolheDataLinguinha = function() {
        var data = $('#lingua_ultimos_exames option:selected').text();
        this.AtualizaTelaTriagem(5, '\'' + data + '\'');
    };

    this.modalOlhinho = function() {
        this.AtualizaTelaTriagem(1);
        abrir_modal('boxes-olhinho');
    };

    this.escolheDataOlhinho = function() {
        var data = $('#olho_ultimos_exames option:selected').text();
        this.AtualizaTelaTriagem(1, '\'' + data + '\'');
    };

    this.modalOrelhinha1 = function() {
        this.AtualizaTelaTriagem(2);
        abrir_modal('boxes-orelhinha_1');
    };

    this.escolheDataOrelhinha1 = function() {
        var data = $('#orelha1_ultimos_exames option:selected').text();
        this.AtualizaTelaTriagem(2, '\'' + data + '\'');
    };

    this.modalOrelhinha2 = function() {
        this.AtualizaTelaTriagem(3);
        abrir_modal('boxes-orelhinha_2');
    };

    this.escolheDataOrelhinha2 = function() {
        var data = $('#orelha2_ultimos_exames option:selected').text();
        this.AtualizaTelaTriagem(3, '\'' + data + '\'');
    };

    this.setClienteSelected = function(client) {
        if (!client || client.cliente !== 1) {
            throw "NullPointException client não foi declarado para selecionar um cliente a função deve receber um";
        }
        loadVars();

        $nome.html(client.nome);
        $idade.html(client.getIdadeString());
        $matricula.html(client.matricula);
        $medico.html(client.medico);

        $antecedentesFamiliares.html(client.antecedentesFamiliares);
        $antecedentesPessoal.html(client.antecedentesPessoais);
        $alergias.html(client.alergias);

        $apgar.val(client.apgar);
        $idadeGestacional.val(client.idadeGestacional);
        $pesoNascimento.val(client.pesoNasc);
        $gestacaoId.val(client.gestacaoId);
        $partoId.val(client.partoId);
        consultasTela.SelectedClient = client;
    };

    this.setConsultaSelected = function(Consulta) {
        if (!Consulta.consulta) {
            throw "Variavel passada errado, para adicionar uma consulta deve ser uma instancia de consulta";
        }
        loadVars();

        $Texto.html(Consulta.TextoDaConsulta);
        $pacienteTamanho.val(Consulta.tamanho);
        $pacientePeso.val(Consulta.peso);
        $pacientePA.val(Consulta.PA);
        $TextoNome.html('Consulta de: ' + Consulta.getData().format('BR'));
        this.SelectedConsulta = Consulta;
        defineTabelaReqServico();

        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'getDadosConsulta',
                idConsulta: consultasTela.SelectedConsulta.consultaID
            },
            success: function(resposta) {
                resposta = $.parseJSON(resposta);
                if (resposta) {
                    $('#txareahip').val(resposta['hipotese_diag']);
                    $('div#txtarea-prescricoes').html(resposta['prescricao']);
                    $('#con-atestado').val(resposta['atestado']);
                    $.ajax({
                        url: 'index.php?module=consultas&tmp=1',
                        type: 'POST',
                        data: {
                            flag: 'getDadosCliente',
                            idCliente: consultasTela.SelectedClient.getClienteId()
                        },
                        success: function(resposta) {
                            resposta = $.parseJSON(resposta);
                            $('div#areaDeTextoParaAtecedentesFamiliares').html(resposta['antecedente_familiar']);
                            $('div#areaDeTextoParaAntecedentesPessoais').html(resposta['antecedente_pessoal']);
                            $('div#areaDeTextoParaAlergias').html(resposta['alergias']);
                            $.ajax({
                                url: 'index.php?module=consultas&tmp=1',
                                type: 'POST',
                                data: {
                                    flag: 'getDadosMedico',
                                    idMedico: consultasTela.SelectedConsulta.getMedicoId()
                                },
                                success: function(resposta) {
                                    resposta = $.parseJSON(resposta);
                                    consultasTela.setCrmMedico(resposta['crm']);
                                }});
                        }});
                }
            }});
    };

    this.setCrmMedico = function($crm) {
        this.$crm = $crm;
    }

    this.getCrmMedico = function() {
        return this.$crm;
    }

    /**
     * Esta função mostra uma div que está com 'display:none'
     */
    this.ativaLinguaOpcoes = function() {
        $('div#lingua_opcoes').css("display", "block");
    };

    this.pesquisaConsulta = function() {
        Pesquisa(function(client) {
            consultasTela.getDadosDoCliente(client.cliente_id);
        });
    };

    this.setUltimoCliente = function() {
        console.log("Set Last client");
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'getUltimoClienteporMedico',
            },
            success: function(resposta) {
                console.log("Resposta Ultimo CLiente:" + resposta);
                consultasTela.getDadosDoCliente(resposta);
            }});


    }
    this.getDadosDoCliente = function(client) {
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'pegaHistoricoDeAtendimento',
                clienteID: client
            },
            success: function(resposta) {
				console.log(resposta);
                var res = $.parseJSON(resposta);
                if (res.Erro) {
                    if (res.code === 4) {
                        $.ajax({
                            url: 'index.php?module=consultas&tmp=1',
                            type: 'POST',
                            data: {
                                flag: 'pegaHistoricoDeAtendimento',
                                subflag: 2,
                                clienteID: client
                            },
                            success: function(resposta) {
                                var historico = $.parseJSON(resposta)[0];
                                var client = new cliente(historico)
                                        .addPropertiChangeListener(functionCallBackForClient);
                                consultasTela.setClienteSelected(client);
                                geraHistorico(true);
                                consultasTela.imprimirConsultaPadrao(0);
                            }
                        });

                    }
                } else {
                    consultasTela.AtualizaTela(resposta);
                    consultasTela.imprimirConsultaPadrao(0);
                }
            }
        });
    };

    var functionCallBackForConsult = function(nome, valor, consult) {
        loadVars();
        if (nome === 'ConsultaTexto') {
            $Texto.html(valor);
        } else if (nome === 'peso') {
            $pacientePeso.val(valor);
        } else if (nome === 'PA') {
            $pacientePA.val(valor);
        } else if (nome === 'tamanho') {
            $pacienteTamanho.val(valor);
        } else if (nome === 'data') {
            $TextoNome.html('Consulta de: ' + valor.format('BR'));
        }
//        console.log(nome);console.log(valor);console.log(consult);        
        consult.getAsTableRow();
        consult.atualizaConsultaServidor();
    };

    var functionCallBackForClient = function(nome, valor, client) {
        loadVars();
        if (nome === 'matricula') {
            $matricula.html(valor);
        } else if (nome === 'antecedentesPessoais') {
            $antecedentesPessoal.html(valor);
        } else if (nome === 'antecedentesFamiliares') {
            $antecedentesFamiliares.html(valor);
        } else if (nome === 'alergias') {
            $alergias.html(valor);
        } else if (nome === 'medico') {
            $medico.html(valor);
        } else if (nome === 'dataNacimento') {
            $idade.html(client.getIdadeString());
        } else if (nome === 'nome') {
            $nome.html(valor);
        }
        client.atualizaClienteServidor();
    };

    var loadVars = function() {
        $TextoNome = $('#areaDeTextoParaConsultaNome');
        $pacienteTamanho = $('#cons-cm');
        $pacientePeso = $('#cons-kg');
        $pacientePA = $('#cons-PA');
        $Texto = $('#areaDeTextoParaConsulta');
        $Historico = $('#HistoricoDeClientes');
        $antecedentesFamiliares = $('div#areaDeTextoParaAtecedentesFamiliares');
        $antecedentesPessoal = $('div#areaDeTextoParaAntecedentesPessoais');
        $alergias = $('div#areaDeTextoParaAlergias');
        $matricula = $('#clienteIinfo span[name=matricula]');
        $idade = $('#clienteIinfo span[name=idade]');
        $nome = $('#clienteIinfo span[name=nome]');
        $medico = $('#clienteIinfo span[name=medico]');
        $idadeGestacional = $('#idade_gestacional');
        $apgar = $('#apgar');
        $pesoNascimento = $('#peso_nascimento');
        $gestacaoId = $('#con_gestacao');
        $partoId = $('#con_parto');
    };

    var geraHistorico = function(force) {
        var tabela = consultasTela.SelectedClient.getAsTable(force);
        $Historico.html('');
        for (var row in tabela) {
            row = tabela[row];
            $Historico.append(row);
        }
        var $tr = $('<tr/>');
        $tr.append($('<th/>'));
        $tr.append($('<th/>').html('Nova Consulta'));
        $tr.append($('<th/>'));
        $tr.append($('<th/>'));
        $tr.append($('<th/>'));
        $tr.dblclick(function() {

            /*
             * @type consulta
             */
            var consult = new consulta()
                    .addPropertiChangeListener(functionCallBackForConsult);
            $.ajax({
                url: 'index.php?module=consultas&tmp=1',
                type: 'POST',
                data: {flag: 'getLogeddUser'},
                assyc: false,
                success: function(res) {
                    res = $.parseJSON(res);
                    consult.medico_id = res.id;
                    consult.Esp = res.nome;
                    consultasTela.SelectedClient.addConsulta(consult, true);
                    consultasTela.setConsultaSelected(consult);
                    geraHistorico(true);
                }
            });
        });
        $tr.attr('class', 'pointer-cursor');
        $Historico.append($tr);
        resetFunctions();
        if (tabela.length)
            $(tabela[0]).css("background", "#66778D");
    };

    var infoRigthClickConsultaID = '';

    function resetFunctions() {

        $("tbody#HistoricoDeClientes > tr").each(function() {
            $(this).mousedown(function(e) {
                if (e.which == 3) {
                    infoRigthClickConsultaID = $(this).attr('id');
                }
            });
        });

        $("tbody#HistoricoDeClientes").contextMenu({
            menu: 'list-menu-consultaHistorico'
        });

        $("tbody#HistoricoDeClientes > tr").each(function() {
            var cssant = $(this).css("color");
            $(this).hover(
                    function() {
                        cssant = $(this).css("backgroundColor");
                        $(this).css("background", "#C0D1E7");
                    },
                    function() {
                        $(this).css("background", cssant);
                    });
            $(this).dblclick(function() {
                $("tbody#HistoricoDeClientes > tr").each(function() {
                    $(this).css("background", '');
                });
                $(this).css("background", "#66778D");
                cssant = $(this).css("backgroundColor");
            });
        });
    }

    this.imprimirConsultaPadrao = function(val) {
        var hipotesesTmp = new Array();
        var medicoTmp = new Array();
        var crmTmp = new Array();
        $.post("index.php?module=consultas&tmp=1", {
            flag: 'buscarTodasHipotesesCliente',
            idCliente: consultasTela.SelectedClient.getClienteId()
        }, function(resposta) {
            for (var i = resposta.length - 1; i >= 0; i--) {
                hipotesesTmp.push(resposta[i]['hipotese_diag']);
            }
            $.post("index.php?module=consultas&tmp=1", {
                flag: 'buscaNomeMedicoCRM',
                idCliente: consultasTela.SelectedClient.getClienteId()
            }, function(resposta) {

                for (var i = resposta.length - 1; i >= 0; i--) {
                    medicoTmp.push(resposta[i]['login']);
                    crmTmp.push(resposta[i]['crm']);
                }

                var consultasTmp = new Array();
                var consultasDataTmp = new Array();
                for (var i = 0; i < consultasTela.SelectedClient.consultas.length; i++) {
                    consultasTmp.push(removerTags(consultasTela.SelectedClient.consultas[i]['TextoDaConsulta']));
                    consultasDataTmp.push(converte2DataFormatada(consultasTela.SelectedClient.consultas[i]['data'].format('yyyy-mm-dd'), '-'));
                }

                var historicoConsultaArray = {
                    'nomeCliente': consultasTela.SelectedClient.nome,
                    'matricula': consultasTela.SelectedClient.matricula,
                    'medico': medicoTmp,
                    'antecedentesFamiliares': removerTags(consultasTela.SelectedClient.antecedentesFamiliares),
                    'antecedentesPessoal': removerTags(consultasTela.SelectedClient.antecedentesPessoais),
                    'alergias': removerTags(consultasTela.SelectedClient.alergias),
                    'consultas': consultasTmp,
                    'consultasData': consultasDataTmp,
                    'crm': crmTmp,
                    'hipotese': hipotesesTmp,
                    'data': new Date().format('dd/mm/yyyy')
                };
                if (val == 1) {
                    var historicoConsultaArraytmp = JSON.stringify(historicoConsultaArray);

                    $.ajax({
                        url: 'index.php?module=consultas&tmp=1',
                        type: 'POST',
                        data: {
                            flag: 'imprimirHistoricoConsulta',
                            historicoConsulta: historicoConsultaArraytmp
                        },
                        success: function(resposta) {
                            resposta = $.parseJSON(resposta);
                            window.open('index2.php?module=historico', '_blank');
                        }
                    });
                }
            }, "json");

        }, "json");

    }

    this.removerConsultaPadrao = function() {
        var con = confirm("Deseja excluir Consulta?");
        if (con) {
            $.post("index.php?module=consultas&tmp=1", {
                idConsulta: infoRigthClickConsultaID,
                flag: 'removerConsultaHistorico'
            }, function(result) {
                consultasTela.getDadosDoCliente(consultasTela.SelectedClient.getClienteId());
                infoRigthClickConsultaID = null;
            }, "json");
        }
    };

};

consultasTela.setUltimoCliente();
