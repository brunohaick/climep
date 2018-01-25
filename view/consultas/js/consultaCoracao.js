 function coracao(consultaCoracaoId) {

	this.consultaCoracaoId = consultaCoracaoId;
	this.percMaoDireita;
	this.percPe;
	this.percDiferenca;
	this.conclusaoTeste;
	this.outrosExames;
	this.observacao;
	this.coracao_anot_qp;
	this.coracao_anot_hf;

	var listeners = undefined;

	this.addPropertiChangeListener = function(CallBack) {
		if (!listeners) {
			listeners = new Array();
		}
		if (typeof CallBack === 'function') {
			listeners.push(CallBack);
		}
		return this;
	};

	var executeListener = function(prop, value, client) {
		for (var func in listeners) {
			func = listeners[func];
			func(prop, value, client);
		}
	};

	this.atualizaConsultaCoracaoServidor = function(idConsultaCoracao, data) {
		
		var mao_dir = $('#coracao-mao-direita').val();
		var pe = $('#coracao-pe').val();
		var diff = $('#coracao-diferenca').val();
		var outros = $('#coracao_outros_exames').val();
		var obs = $('#coracao-observacoes').val();
		var conclusao = $('input:radio[name=coracao_conclusao]:checked').val();
		var qp = new Array();
		var hf = new Array();

		$("input:checkbox[name='coracao_qp[]']:checked").each(function() {
			qp.push($(this).val());
		});

		$("input:checkbox[name='coracao_hf[]']:checked").each(function() {
			hf.push($(this).val());
		});

		this.setConsultaCoracaoId(idConsultaCoracao);
		this.setPercMaoDireita(((mao_dir) ? mao_dir : ''));
		this.setPercPe(((pe) ? pe : ''));
		this.setPercDiferenca(((diff) ? diff : ''));
		this.setOutrosExames(((outros) ? outros : ''));
		this.setObservacao(((obs) ? obs : ''));
		this.setConclusaoTeste(((conclusao) ? conclusao : ''));
		this.setCoracaoAnotQp(((qp) ? qp : ''));
		this.setCoracaoAnotHf(((hf) ? hf : ''));

		var coracaoArray = JSON.stringify(this);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'atualizaConsultaCoracaozinho',
				coracao: coracaoArray
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				if(resposta === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(4, '\'' + data + '\'');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	};

	this.insereConsultaCoracaoServidor = function() {
		var mao_dir = $('#coracao-mao-direita').val();
		var pe = $('#coracao-pe').val();
		var diff = $('#coracao-diferenca').val();
		var outros = $('#coracao_outros_exames').val();
		var obs = $('#coracao-observacoes').val();
		var conclusao = $('input:radio[name=coracao_conclusao]:checked').val();
		var qp = new Array();
		var hf = new Array();

		$("input:checkbox[name='coracao_qp[]']:checked").each(function() {
			qp.push($(this).val());
		});

		$("input:checkbox[name='coracao_hf[]']:checked").each(function() {
			hf.push($(this).val());
		});

		this.setConsultaCoracaoId('');
		this.setPercMaoDireita(((mao_dir) ? mao_dir : ''));
		this.setPercPe(((pe) ? pe : ''));
		this.setPercDiferenca(((diff) ? diff : ''));
		this.setOutrosExames(((outros) ? outros : ''));
		this.setObservacao(((obs) ? obs : ''));
		this.setConclusaoTeste(((conclusao) ? conclusao : ''));
		this.setCoracaoAnotQp(((qp) ? qp : ''));
		this.setCoracaoAnotHf(((hf) ? hf : ''));

		var coracaoArray = JSON.stringify(this);
		var clienteId = consultasTela.SelectedClient.getClienteId();

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'inserirConsultaCoracaozinho',
				idCliente: clienteId,
				coracao: coracaoArray
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				var data = resposta['data'];
				var resultado = resposta['resultado'];
				if(resultado === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(4, '\'' + data + '\'');
				} else if(resultado == 'form_vazio') {
					alert('O formulário está vazio, por favor preencha pelo menos um campo.');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	};

	this.limpaModalCoracaozinho = function() {

		this.limpaCamposModalCoracaozinho();

		var clienteId = consultasTela.SelectedClient.getClienteId();
		var strhtml = '';
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
				for(var i in resposta) {
					strhtml += '<option value=\''+ resposta[i]['consulta_coracaozinho_id'] +'\'>' + resposta[i]['data'] + '</option>'
				}
				$('#coracao_ultimos_exames').html(strhtml);
			}
		});
		
		$('#coracao_botao_imprimir').unbind();
		$('#coracao_botao_link').html('<i class="icon-inbox"></i> Gravar');
		$('#coracao_botao_gravar').unbind();
		$('#coracao_botao_gravar').click(function() {
			consultasTela.SelectedCoracaoObj.insereConsultaCoracaoServidor();
		});
	}

	this.limpaCamposModalCoracaozinho = function() {

		$('input:checkbox[name=\'coracao_qp[]\']').prop('checked', false);
		$('input:checkbox[name=\'coracao_hf[]\']').prop('checked', false);
		$('#coracao-mao-direita').val('');
		$('#coracao-pe').val('');
		$('#coracao-diferenca').val('');
		$('#coracao_outros_exames').val('');
		$('#coracao-observacoes').val('');
		$('input:radio[name=\'coracao_conclusao\']').prop('checked', false);
	}

	this.imprimeResultadoTesteCoracao = function() {

		var clienteId = consultasTela.SelectedClient.getClienteId();

		var gestacao = $('select#con_gestacao option:selected').val();
		var parto = $('select#con_parto option:selected').val();
		var idadeGestacional = $('#idade_gestacional').val();
		var apgar = $('#apgar').val();
		var peso = $('#peso_nascimento').val();

		var mao_dir = $('#coracao-mao-direita').val();
		var pe = $('#coracao-pe').val();
		var diff = $('#coracao-diferenca').val();
		var outros = $('#coracao_outros_exames').val();
		var obs = $('#coracao-observacoes').val();
		var conclusao = $('input:radio[name=coracao_conclusao]:checked').attr('coracao_conclusao_nome');
		var qp = new Array();
		var hf = new Array();

		$("input:checkbox[name='coracao_qp[]']:checked").each(function() {
			qp.push($(this).attr('coracao_qp_nome'));
		});

		$("input:checkbox[name='coracao_hf[]']:checked").each(function() {
			hf.push($(this).attr('coracao_hf_nome'));
		});

		this.setConsultaCoracaoId('');
		this.setPercMaoDireita(((mao_dir) ? mao_dir : ''));
		this.setPercPe(((pe) ? pe : ''));
		this.setPercDiferenca(((diff) ? diff : ''));
		this.setOutrosExames(((outros) ? outros : ''));
		this.setObservacao(((obs) ? obs : ''));
		this.setConclusaoTeste(((conclusao) ? conclusao : ''));
		this.setCoracaoAnotQp(((qp) ? qp : ''));
		this.setCoracaoAnotHf(((hf) ? hf : ''));

		var coracaoArray = JSON.stringify(this);

		var corArray = new Array();

		corArray.push((gestacao) ? gestacao : '');
		corArray.push((parto) ? parto : '');
		corArray.push((idadeGestacional) ? idadeGestacional : '');
		corArray.push((apgar) ? apgar : '');
		corArray.push((peso) ? peso : '');

		var corArr = JSON.stringify(corArray);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeResultadoCoracaozinho',
				coracao: coracaoArray,
				corArr: corArr,
				clienteId: clienteId
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				window.open('index2.php?module=resultadocoracaozinho', '_blank');
			}
		});
		return this;
	};

	this.imprimeListaMedicos = function() {
		window.open('index2.php?module=listagemmedicos', '_blank');
	}
	/**
	 * Métodos de encapsulamento (get e set);
	 */

	this.getConsultaCoracaoId = function() {
		return this.consultaCoracaoId;
	};

	this.setConsultaCoracaoId = function(consultaCoracaoId) {
		this.consultaCoracaoId = consultaCoracaoId;
		return this;
	};

	this.getPercMaoDireita = function() {
		return this.percMaoDireita;
	};

	this.setPercMaoDireita = function(percMaoDireita) {
		this.percMaoDireita = percMaoDireita;
		return this;
	};

	this.getPercPe = function() {
		return this.percPe;
	};

	this.setPercPe = function(percPe) {
		this.percPe = percPe;
		return this;
	};

	this.getPercDiferenca = function() {
		return this.percDiferenca;
	};

	this.setPercDiferenca = function(percDiferenca) {
		this.percDiferenca = percDiferenca;
		return this;
	};

	this.getConclusaoTeste = function() {
		return this.conclusaoTeste;
	};

	this.setConclusaoTeste = function(conclusaoTeste) {
		this.conclusaoTeste = conclusaoTeste;
		return this;
	};

	this.getOutrosExames = function() {
		return this.outrosExames;
	};

	this.setOutrosExames = function(outrosExames) {
		this.outrosExames = outrosExames;
		return this;
	};

	this.getObservacao = function() {
		return this.observacao;
	};

	this.setObservacao = function(observacao) {
		this.observacao = observacao;
		return this;
	};

	this.getCoracaoAnotQp = function() {
		return this.coracao_anot_qp;
	};

	this.setCoracaoAnotQp = function(coracao_anot_qp) {
		this.coracao_anot_qp = coracao_anot_qp;
		return this;
	};

	this.getCoracaoAnotHf = function() {
		return this.coracao_anot_hf;
	};

	this.setCoracaoAnotHf = function(coracao_anot_hf) {
		this.coracao_anot_hf = coracao_anot_hf;
		return this;
	};

};