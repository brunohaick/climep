$(document).ready(function() {
//	$('tr:even[name=table-color]').css('background', '#D3D6FF');
//	$('tr:odd[name=table-color]').css('background', '#FFF');
    $('.inputmask').inputmask();

    Pesquisa = function(callBack, comMatriculaDoTitular) {

        var pagina = 0;
        var lockDeSeguranca = false;
        var totalDePaginas = 0;

        var pesquisa = function() {
            if (lockDeSeguranca === true) {
                alert('Espere a ultima pesquisa terminar isso pode demorar alguns segundos');
                return;
            }
            lockDeSeguranca = true;
            var nome = $("#nome").val();
            var nascimento = $("#nascimento").val();
            var outro = $("#outro").val();
            var tipo = $('div.controls input:radio[name=opcao-tipo]:checked').val();
            var opcao = $('div.span11.radio-list input:radio[name=opcao]:checked').val();
            if (opcao === 'cpf') {
                outro = $("input#outrocpf").val();
                while (outro.indexOf('-') !== - 1)
                    outro = outro.replace('-', '');
            } else if (opcao === 'telefone') {
                outro = '0';
                outro += $("input#outrotelefone").val();
                outro = outro.replace('(', '');
                outro = outro.replace(')', '');
                outro = outro.replace('-', '');
            }
            var procurar = "procurar";

            var arquivo = $('div.span3 checkbox-option label input#ArquivoMorto').is('checked');

            if (outro != "" && opcao == 'email') {
                if (!IsEmail(outro)) {
                    alert("Por favor, Digite um email válido!");
                    return;
                }
            }
            var Return = false;
            $.ajax({//ipo 	Matrícula 	Nome 	Email 	Telefone Residencial
                url: "index.php?module=pesquisa&tmp=1",
                type: "POST",
                data: {nome: nome, nascimento: nascimento, outro: outro, tipo: tipo, opcao: opcao, procurar: procurar, pagina: pagina, arquivoMorto: arquivo},
                async: false,
                success: function(result) {
                    var resultado = $.parseJSON(result);
                    if (resultado !== null || resultado !== undefined) {
                        var $div1 = $('<div class="table" id="tabela_busca"/>');
                        var $div2 = $('<div class="active">');

                        var $table = $('<table cellpadding="0" cellspacing="0" border="0"  class="table table-striped table-bordered table-condensed">');

                        $table.append('<thead><tr><th>tipo</th><th>Nome</th><th>email</th><th>Membro</th><th>Telefone Residencial</th></tr></thead>');

                        var $tbody = $('<tbody/>');

                        for (var pessoa in resultado) {
                            pessoa = resultado[pessoa];
                            var $tr = $('<tr/>')
                                    .attr('id', 'tr_pesquisa')
                                    .data('info', pessoa)
                                    .attr('class', 'pointer-cursor');
                            $tr.append($('<td/>').attr('class', 'dataTables_empty').attr('aling', 'center').html(((pessoa['flag'] === 'D' ? 'Dependente' : 'Titular'))));
                            $tr.append($('<td/>').attr('class', 'dataTables_empty').attr('aling', 'center').html(pessoa['nome'] + ((pessoa['sobrenome'] === null) ? '' : " " + pessoa['sobrenome'])));
                            $tr.append($('<td/>').attr('class', 'dataTables_empty').attr('aling', 'center').html(pessoa['email']));
                            $tr.append($('<td/>').attr('class', 'dataTables_empty').attr('aling', 'center').html(pessoa['membro']));
                            $tr.append($('<td/>').attr('class', 'dataTables_empty').attr('aling', 'center').html(pessoa['tel_residencial']));
                            $tr.dblclick(function() {
                                var call = $(this).data('info');
                                if (comMatriculaDoTitular) {
                                    $.ajax({
                                        url: 'index.php?module=pesquisaMatriculaTitular&tmp',
                                        type: 'POST',
                                        data: {flag: 'PegaMatriculaTitular', cliente_id: call.cliente_id},
                                        async: false,
                                        success: function(response) {
                                            call.matricula = $.parseJSON(response);
                                        }
                                    });
                                }
                                callBack(call);
                                fechar_modal('boxes-busca');
                            });
                            $tr.hover(function(event) {
                                if (event !== null)
                                    if (event.type === 'mouseenter') {
                                        $(this).css('background-color', '#c0d1e7');
                                    } else if (event.type === 'mouseleave') {
                                        $(this).css('background-color', '');
                                    }
                            });
                            $tbody.append($tr);
                        }

                        $table.append($tbody);
                        $div2.append($table);
                        $div1.append($div2);
                        $("#tabela_busca").html($div1);
                        Return = true;
                    }
                    montaPaginas();
                    lockDeSeguranca = false;
                }
            });
            return Return;
        };
        $("input#submit_busca").click(function() {
            if (lockDeSeguranca === true) {
                alert('Espere a ultima pesquisa terminar isso pode demorar alguns segundos');
                return;
            }
            pagina = 0;
            var nome = $("#nome").val();
            var nascimento = $("#nascimento").val();
            var outro = $("#outro").val();
            var tipo = $('input:radio[name=opcao-tipo]:checked').val();
            var opcao = $('div.span11.radio-list input:radio[name=opcao]:checked').val();
            if (opcao === 'cpf') {
                outro = $("input#outrocpf").val();
                while (outro.indexOf('-') !== - 1)
                    outro = outro.replace('-', '');
            } else if (opcao === 'telefone') {
                outro = '0';
                outro += $("input#outrotelefone").val();
                outro = outro.replace('(', '');
                outro = outro.replace(')', '');
                outro = outro.replace('-', '');
            }
            var procurar = "procurar";
            var arquivo = $('div.span3 checkbox-option label input#ArquivoMorto').is('checked');

            $.ajax({
                url: "index.php?module=pesquisa&tmp=1",
                type: "POST",
                data: {flag: 'CountPesquisa', nome: nome, nascimento: nascimento, outro: outro, tipo: tipo, opcao: opcao, procurar: procurar, pagina: pagina, arquivoMorto: arquivo},
                success: function(result) {
                    result = $.parseJSON(result);
                    totalDePaginas = parseInt(parseFloat(result) / 100) + 1;
                    $('#modalProcurarRegistrosEncontrados').html('Foram encontrados (' + result + ') registro(s)');
                }
            });
            pesquisa();
        });

        var hotKeyEnter = function(key) {
            if (key.which === 13 || key.which === 10)
                $("input#submit_busca").click();
        };

        $('input#nome').keyup(hotKeyEnter);
        $('input#outro').keyup(hotKeyEnter);
        $('input#outrotelefone').keyup(hotKeyEnter);
        $('input#nascimento').keyup(hotKeyEnter);
        $('input#outrocpf').keyup(hotKeyEnter);

        var montaPaginas = function() {
            var $div = $('<div/>').attr('class', 'pagination pagination-centered');
            var $ul = $('<ul/>');

            var $liPrev = $('<li/>');
            $liPrev.append($('<a/>').attr('href', '#').html('Anterior'));
            $liPrev.click(function() {
                if (pagina === 0) {
                    alert('você já está na primeira página');
                    return;
                }
                pagina--;
                montaPaginas();
                pesquisa();
                mudarPagina();
            });
            $ul.append($liPrev);

            var count = 1;
            if (pagina > 2)
                count = pagina - 2;

            while (count <= totalDePaginas) {
                if (count === pagina + 5) {
                    break;
                }
                var $li = $('<li/>');
                $li.append($('<a/>').attr('href', '#').html(count)).attr('pagina', count);
                $li.click(function() {
                    pagina = parseInt($(this).attr('pagina')) - 1;
                    montaPaginas();
                    pesquisa();
                    mudarPagina();
                });
                if (count - 1 === pagina) {
                    $li.attr('class', 'disabled');
                }
                $ul.append($li);
                count++;
            }

            var $liNext = $('<li/>');
            $liNext.append($('<a/>').attr('href', '#').html('Proximo'));
            $liNext.click(function() {
                if (pagina + 1 >= totalDePaginas) {
                    alert('você está na ultima página');
                    return;
                }
                pagina++;
                montaPaginas();
                pesquisa();
                mudarPagina();
            });
            $ul.append($liNext);

            $div.append($ul);

            $('div#tabela_busca_pagnacao').html($div);
        };


        var mudarPagina = function() {
            $('#tabela_busca_pagina_atual').html((pagina + 1));
        };

        $("#tabela_busca_btn_voltar").click(function() {
            if (pagina === 0) {
                alert('você já está na primeira página');
                return;
            }
            pagina--;
            montaPaginas();
            pesquisa();
            mudarPagina();
        });
        $("#tabela_busca_btn_avancar").click(function() {
            if (pagina >= totalDePaginas) {
                alert('você está na ultima página');
                return;
            }
            pagina++;
            montaPaginas();
            pesquisa();
            mudarPagina();
        });
        abrir_modal('boxes-busca');
    };
    TextAreaMCE = new function() {
        var $modal;
        var $textArea, $textAreaTitle;
        var $btnSalvar, $btnImprimir;
        var $btnCancelar;
        var isLoaded = false;
        var editor;

        this.get = function() {
            return $textArea.html();
        };

        this.get2 = function() {
            atualAtestado.titAtestado = $($textAreaTitle).val();
            atualAtestado.texAtestado = $($textArea).val();
//                        console.log("GET")
//                        console.log(atualAtestado);
            return atualAtestado;
        };

        this.set = function(texto) {
            $textArea.html(texto);
            return this;
        };

        this.set2 = function(atualAtestado) {
//                        console.log("SET");
//                        console.log(atualAtestado);                        
            $textArea.val(atualAtestado.texAtestado);
            $textAreaTitle.val(atualAtestado.titAtestado);
            return this;
        };

        var gerarHtmlDoModal = function() {
            loadVars();
            if ($modal.length === 0) {

                $('body').append('\
					<div id="ModalTinyMCE" class="modal hide fade" style="width:700px;">\
						<div class="modal-header">\
							Editor de texto\
							<button id="BotaoFecharTinyMCE" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
						</div>\
						<div class="modal-body" style="height:450px" id ="TextAreaTinyMce"></div>\
						<div class="modal-footer">\
                                                    <a id="BotaoImprimirTinyMCE" class="btn btn-primary">Imprimir</a>\
                                                    <a id="BotaoSalvarTinyMCE" class="btn btn-primary">Salvar mudanças</a>\
						</div>\
				</div>');

            }

            tinymce.init({
                selector: "div#TextAreaTinyMce",
                inline: true,
                menubar: false,
                force_br_newlines: true,
                force_p_newlines: false,
                forced_root_block: 'div',
//                            plugins: [
//                                "advlist autolink lists link image charmap print preview anchor",
//                                "searchreplace visualblocks code fullscreen",
//                                "insertdatetime media table contextmenu paste",                                
//                            ],
                toolbar: "bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"

            });
        };

        var gerarHtmlDoModal2 = function() {
            loadVars2();
            if ($modal.length === 0) {

                $('body').append('\
					<div id="ModalTinyMCE" class="modal hide fade" style="width:700px;">\
						<div class="modal-header">\
                                                    <button id="BotaoFecharTinyMCE" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
						    <textarea id="TextAreaTitle" style="height:20px;" placeholder="Novo Atestado"></textarea>\
                                                    <textarea class="modal-body" style="height:450px; width:640px;" id ="TextAreaTinyMce"></textarea>\
						</div>\
						<div class="modal-footer">\
                                                    <a id="BotaoSalvarTinyMCE" class="btn btn-primary">Salvar mudanças</a>\
						</div>\
				</div>');

            }
        };

        var loadVars = function() {
            $modal = $('div#ModalTinyMCE');
            $textArea = $('div#TextAreaTinyMce');
            $btnSalvar = $('a#BotaoSalvarTinyMCE');
            $btnCancelar = $('button#BotaoFecharTinyMCE');
            $btnImprimir = $('a#BotaoImprimirTinyMCE');
        };
        var loadVars2 = function() {
            $modal = $('div#ModalTinyMCE');
            $textAreaTitle = $('textarea#TextAreaTitle');
            $textArea = $('textarea#TextAreaTinyMce');
            $btnSalvar = $('a#BotaoSalvarTinyMCE');
            $btnCancelar = $('button#BotaoFecharTinyMCE');
            $btnImprimir = $('a#BotaoImprimirTinyMCE');
        };

        this.imprimir = function(text) {
            imprimirHTML($textArea);
        }

        this.show = function(Salvar, Cancelar) {
            gerarHtmlDoModal();
            loadVars();

            $btnImprimir.unbind();
            $btnImprimir.click(function() {
                imprimirHTML($textArea);
            });

            if (Salvar) {
                $btnSalvar.unbind();
                $btnSalvar.click(function() {
                    Salvar(TextAreaMCE.get());
                    TextAreaMCE.set('');
                    fechar_modal($modal);
                });
            }
            if (Cancelar) {
                $btnCancelar.unbind();
                $btnCancelar.click(function() {
                    TextAreaMCE.set('');
                    fechar_modal($modal);
                });
            }

            isLoaded = true;
            abrir_modal($modal);
            return this;
        };

        this.show2 = function(Salvar, Cancelar) {
            gerarHtmlDoModal2();
            loadVars2();

            if (Salvar) {
                $btnSalvar.unbind();
                $btnSalvar.click(function() {
                    if ($textArea.val() == "" || $textAreaTitle.val() == "") {
                        alert("Preencha todos os campos");
                    } else {
                        Salvar(TextAreaMCE.get2());
                        TextAreaMCE.set2('');
                        fechar_modal($modal);
                    }
                });
            }
            if (Cancelar) {
                $btnCancelar.unbind();
                $btnCancelar.click(function() {
                    TextAreaMCE.set2('');
                    fechar_modal($modal);
                });
            }

            isLoaded = true;
            abrir_modal($modal);
            return this;
        };

    };
});

/**
 * Recebe uma String ou um Jquery object, que tenha uma data no estilo xx/xx/xxxx
 * e auto completa
 * <br/>
 * veja na wiki para mais info : http://10.1.1.254/redmine/projects/climep/wiki/DateComplete_
 * 
 * @param {String, jQuery} date
 * @returns {String} data formatada
 */
function dateComplete(date) {
    if (typeof date === 'string') {
        var data = date.split("/");
    } else if (date instanceof $) {
        var data = date.val().split("/");
    }
    if (data[2].indexOf("____") !== -1 || data[2].indexOf("___") !== -1) {
        data[2] = new Date().getFullYear();
    } else if (data[2].indexOf("__") !== -1) {
        data[2] = "20" + data[2].split("__")[0];
    } else if (data[2].indexOf("_") !== -1) {
        data[2] = new Date().getFullYear();
    }

    var Return = (data[0] + "/" + data[1] + "/" + data[2]);
    if (typeof date === 'string') {
        return Return;
    } else if (date instanceof $) {
        date.val(Return);
    }
}


function imprimirTexto(text) {
    if (text.attr('name') == 'con-atestado') {
        text = text.val();
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'imprimirAtestado',
                text: text
            },
            success: function(resposta) {
                resposta = $.parseJSON(resposta);
                window.open('index2.php?module=atestado', '_blank');
            }
        });
    }
    if (text.attr('id') == 'prestserv') {
        var dados = printTag.attr('class').split(' ')[3].split('-');
        var idCliente = dados[0];
        var idPrestador = dados[1];
        var data = dados[2] + "-" + dados[3] + "-" + dados[4];
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                idCliente: idCliente,
                idPrestador: idPrestador,
                data: data,
                nomeCliente: consultasTela.SelectedClient.nome,
                flag: 'buscaReqServicoImpressao'
            },
            success: function(resposta) {
                resposta = $.parseJSON(resposta);
                text = JSON.stringify(resposta);
                $.ajax({
                    url: 'index.php?module=consultas&tmp=1',
                    type: 'POST',
                    data: {
                        flag: 'imprimirPrestServ',
                        prestadorServico: text
                    },
                    success: function(resposta) {
                        resposta = $.parseJSON(resposta);
                        window.open('index2.php?module=prestserv', '_blank');
                    }
                });
            }
        });
    }
    if (text.attr('class').toString().split(' ')[3] == 'teste-cutaneo') {
        var tcversao = $(printTag).attr('id');
        result = '';
        $('#testesCutaneosConfirmados').children().each(function() {
            if ($(this).attr('id') == tcversao) {
                result += $($(this).children()[3]).text();
            }
        });
        if (tcversao == 1) {
            $.ajax({
                url: 'index.php?module=consultas&tmp=1',
                type: 'POST',
                data: {
                    flag: 'imprimirTesteCutaneo',
                    tcversao: tcversao,
                    nome: consultasTela.SelectedClient.nome,
                    testeCutaneo: result
                },
                success: function(resposta) {
                    resposta = $.parseJSON(resposta);
                    window.open('index2.php?module=testecutaneoAntigo', '_blank');
                }
            });
        } else {
            $.ajax({
                url: 'index.php?module=consultas&tmp=1',
                type: 'POST',
                data: {
                    flag: 'imprimirTesteCutaneo',
                    tcversao: tcversao,
                    nome: consultasTela.SelectedClient.nome,
                    testeCutaneo: result
                },
                success: function(resposta) {
                    resposta = $.parseJSON(resposta);
                    window.open('index2.php?module=testecutaneoNovo', '_blank');
                }
            });
        }
    }
}
function imprimirHTML(componente) {
    var text = $(componente).html();
    var imprimir = "<html><title>Imprimir</title><body>" + text + "</body></html>";
    var w = window.open('', '', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');
    w.document.write(imprimir);
    w.print();
    w.document.close();
    return false;
}

function removerTags(html) {
    var texto = html;
    if (texto != null) {
        texto = texto.replace(/(<br([^>]+)>)/ig, '\n');
        texto = texto.replace(/(<([^>]+)>)/ig, '');
        return texto;
    } else
        return html;
}

function removerAcento(string) {
    var acentos = {a1: 'á', a2: 'à', a3: 'â', a4: 'ã', A1: 'Á', A2: 'À', A3: 'Â', A4: 'Ã',
        e1: 'é', e2: 'è', e3: 'ê', e4: 'ẽ', E1: 'É', E2: 'È', E3: 'Ê', E4: 'Ẽ',
        i1: 'í', i2: 'ì', i3: 'î', i4: 'ĩ', I1: 'Í', I2: 'Ì', I3: 'Î', I4: 'Ĩ',
        o1: 'ó', o2: 'ò', o3: 'ô', o4: 'õ', O1: 'Ó', O2: 'Ò', O3: 'Ô', O4: 'Õ',
        u1: 'ú', u2: 'ù', u3: 'û', u4: 'ũ', U1: 'Ú', U2: 'Ù', U3: 'Û', U4: 'Ũ'};
    for (key in acentos) {
        string = string.replace(new RegExp(acentos[key], "g"), JSON.stringify(key).substr(1, 1));
    }
    return string;
}

function removerCaracterEspecial(string){    
    string = string.replace(/[`~ !@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/g, '');    
    return string;
}
