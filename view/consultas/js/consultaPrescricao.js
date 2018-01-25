textareaPrescricao = new function() {

    this.nome;
    this.matricula;
    this.data;
    this.endereco;
    this.prescricao;

    this.setNomePrescricao = function(nome) {
        this.nome = nome;
        return this;
    }
    this.getNomePrescricao = function() {
        return this.nome;
    }
    this.setMatriculaPrescricao = function(matricula) {
        this.matricula = matricula;
        return this;
    }
    this.getMatriculaPrescricao = function() {
        return this.matricula;
    }
    this.setDataPrescricao = function(data) {
        this.data = data;
        return this;
    }
    this.getDataPrescricao = function() {
        return this.data;
    }
    this.setEnderecoPrescricao = function(endereco) {
        this.endereco = endereco;
        return this;
    }
    this.getEnderecoPrescricao = function() {
        return this.endereco;
    }
    this.setTextoPrescricao = function(prescricao) {
        this.prescricao = prescricao;
        return this;
    }
    this.getTextPrescricao = function() {
        return this.prescricao;
    }

    var $modal;
    var $textArea;
    var $btnSalvar, $btnImprimir;
    var $btnCancelar;
    var prescricaoArray;

    this.get = function() {
        return $textArea.html();
    };

    this.set = function(texto) {
        $textArea.html(texto);
        return this;
    };

    var loadVars = function() {
        $modal = $('div#modalPrescricao');
        $textArea = $('div#textareaPrescricao');
        $btnSalvar = $('a#salvarPrescricao');
        $btnCancelar = $('button#fecharmodalPrescricao');
        $btnImprimir = $('a#imprimirPrescricao');
    };

    var gerarHtmlDoModal = function() {
        loadVars();
        if ($modal.length === 0) {
            $('body').append('\
			<div id="modalPrescricao" class="modal hide fade" style="width:700px;">\
                        	<div class="modal-header">\
					Editor de texto\
					<button id="fecharmodalPrescricao" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
				</div>\
			<div class="modal-body" style="height:450px" id ="textareaPrescricao"></div>\
				<div class="modal-footer">\
                                    <a id="imprimirPrescricao" class="btn btn-primary">Imprimir</a>\
                                    <a id="salvarPrescricao" class="btn btn-primary">Salvar mudanças</a>\
                                </div>\
			</div>');
        }

        tinymce.init({
            selector: "div#textareaPrescricao",
            inline: true,
            menubar: false,
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: 'div',
            toolbar: "bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"

        });
    };

    this.show = function(Salvar, Cancelar) {
        gerarHtmlDoModal();
        loadVars();

        $btnImprimir.unbind();
        $btnImprimir.click(function() {
            $.ajax({
                url: 'index.php?module=consultas&tmp=1',
                type: 'POST',
                data: {
                    flag: 'getDadosPessoaPorId',
                    id: consultasTela.SelectedClient.getClienteId()
                },
                success: function(resposta) {
                    resposta = $.parseJSON(resposta);
                    textareaPrescricao.setNomePrescricao(consultasTela.SelectedClient.nome);
                    textareaPrescricao.setMatriculaPrescricao(consultasTela.SelectedClient.matricula);
                    textareaPrescricao.setDataPrescricao(getDataFormatada(''));
                    textareaPrescricao.setEnderecoPrescricao(resposta['endereco'] + " " + resposta['numero'] + " - " + resposta['cep'] +
                            " " + resposta['cidade'] + ", " + resposta['estado'] + " " + resposta['tel_residencial']);
                    textareaPrescricao.setTextoPrescricao(textareaPrescricao.get().split('<br>'));                    
                    prescricaoArray = JSON.stringify(textareaPrescricao);                     
                    $.ajax({
                        url: 'index.php?module=consultas&tmp=1',
                        type: 'POST',
                        data: {
                            flag: 'imprimirResultadoPrescricao',
                            prescricao: prescricaoArray
                        },
                        success: function(resposta) {                                
                            resposta = $.parseJSON(resposta);                                                        
                            window.open('index2.php?module=prescricao', '_blank');
                        }
                    });
                    
                }
            });

        });

        $btnSalvar.unbind();
        $btnSalvar.click(function() {
            Salvar(textareaPrescricao.get());
            textareaPrescricao.set('');
            fechar_modal($modal);
        });

        $btnCancelar.unbind();
        $btnCancelar.click(function() {
            textareaPrescricao.set('');
            fechar_modal($modal);
        });


        abrir_modal($modal);
        return this;
    };

}

