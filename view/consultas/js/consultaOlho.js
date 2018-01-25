function olho(consultaOlhoId) {

	this.consultaOlhoId = consultaOlhoId;
	this.resultadoNormalOd;
	this.resultadoSuspeitoOd;
	this.resultadoLeucoriaOd;
	this.resultadoNormalOe;
	this.resultadoSuspeitoOe;
	this.resultadoLeucoriaOe;
	this.visualNormal;
	this.outrosExames;
	this.observacao;
	this.olho_anot_qp;
	this.olho_anot_hf;

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

	this.atualizaConsultaOlhoServidor = function(idConsultaOlho, data) {

		console.log('atualiza olho');

		var normalOd = $('#olhinho_resultado_normal_od:checked').val();
		var leucoriaOd = $('#olhinho_resultado_leucoria_od:checked').val();
		var suspeitoOd = $('#olhinho_resultado_suspeito_od:checked').val();
		var normalOe = $('#olhinho_resultado_normal_oe:checked').val();
		var leucoriaOe = $('#olhinho_resultado_leucoria_oe:checked').val();
		var suspeitoOe = $('#olhinho_resultado_suspeito_oe:checked').val();
		var qp = $('#olhinho_anotacoes_qp').val();
		var hf = $('#olhinho_anotacoes_hf').val();
		var outros = $('#olhinho_anotacoes_outros').val();
		var obs = $('#olhinho_anotacoes_obs').val();
		var conclusaoNormal = $('input:radio[name=olhinho_conclusao_normal]:checked').val();

		this.setConsultaOlhoId(idConsultaOlho);
		this.setResultadoNormalOd(((normalOd) ? normalOd : ''));
		this.setResultadoLeucoriaOd(((leucoriaOd) ? leucoriaOd : ''));
		this.setResultadoSuspeitoOd(((suspeitoOd) ? suspeitoOd : ''));
		this.setResultadoNormalOe(((normalOe) ? normalOe : ''));
		this.setResultadoLeucoriaOe(((leucoriaOe) ? leucoriaOe : ''));
		this.setResultadoSuspeitoOe(((suspeitoOe) ? suspeitoOe : ''));
		this.setVisualNormal(((conclusaoNormal) ? conclusaoNormal : ''));
		this.setOutrosExames(((outros) ? outros : ''));
		this.setObservacao(((obs) ? obs: ''));
		this.setOlhoAnotQp(((qp) ? qp : ''));
		this.setOlhoAnotHf(((hf) ? hf : ''));

		var olhinhoArray = JSON.stringify(this);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'atualizaConsultaOlhinho',
				olho: olhinhoArray
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				if(resposta === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(1, '\'' + data + '\'');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	};

	this.insereConsultaOlhoServidor = function() {

		var normalOd = $('#olhinho_resultado_normal_od:checked').val();
		var leucoriaOd = $('#olhinho_resultado_leucoria_od:checked').val();
		var suspeitoOd = $('#olhinho_resultado_suspeito_od:checked').val();
		var normalOe = $('#olhinho_resultado_normal_oe:checked').val();
		var leucoriaOe = $('#olhinho_resultado_leucoria_oe:checked').val();
		var suspeitoOe = $('#olhinho_resultado_suspeito_oe:checked').val();
		var qp = $('#olhinho_anotacoes_qp').val();
		var hf = $('#olhinho_anotacoes_hf').val();
		var outros = $('#olhinho_anotacoes_outros').val();
		var obs = $('#olhinho_anotacoes_obs').val();
		var conclusaoNormal = $('input:radio[name=olhinho_conclusao_normal]:checked').val();

		this.setConsultaOlhoId('');
		this.setResultadoNormalOd(((normalOd) ? normalOd : ''));
		this.setResultadoLeucoriaOd(((leucoriaOd) ? leucoriaOd : ''));
		this.setResultadoSuspeitoOd(((suspeitoOd) ? suspeitoOd : ''));
		this.setResultadoNormalOe(((normalOe) ? normalOe : ''));
		this.setResultadoLeucoriaOe(((leucoriaOe) ? leucoriaOe : ''));
		this.setResultadoSuspeitoOe(((suspeitoOe) ? suspeitoOe : ''));
		this.setVisualNormal(((conclusaoNormal) ? conclusaoNormal : ''));
		this.setOutrosExames(((outros) ? outros : ''));
		this.setObservacao(((obs) ? obs: ''));
		this.setOlhoAnotQp(((qp) ? qp : ''));
		this.setOlhoAnotHf(((hf) ? hf : ''));

		var olhinhoArray = JSON.stringify(this);
		var clienteId = consultasTela.SelectedClient.getClienteId();

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'inserirConsultaOlhinho',
				idCliente: clienteId,
				olho: olhinhoArray
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				var data = resposta['data'];
				var resultado = resposta['resultado'];
				if(resultado === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(1, '\'' + data + '\'');
				} else if(resultado == 'form_vazio') {
					alert('O formulário está vazio, por favor preencha pelo menos um campo.');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	};

	this.limpaModalOlhinho = function() {

		this.limpaCamposModalOlhinho();

		var clienteId = consultasTela.SelectedClient.getClienteId();
		var strhtml = '';
		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'buscaULtimasConsultasOlhoData',
				idCliente: clienteId
			},
			success: function(resposta) {
				console.log(resposta);
				resposta = $.parseJSON(resposta);
				strhtml += '<option></option>';
				for(var i in resposta) {
					strhtml += '<option value=\''+ resposta[i]['consulta_olhinho_id'] +'\'>' + resposta[i]['data'] + '</option>'
				}
				$('#olho_ultimos_exames').html(strhtml);
			}
		});
		$('#olho_botao_link').html('<i class="icon-inbox"></i> Gravar');
		$('#olho_botao_gravar').unbind();
		$('#olho_botao_gravar').click(function() {
			consultasTela.SelectedOlhoObj.insereConsultaOlhoServidor();
		});
	}

	this.limpaCamposModalOlhinho = function() {
		$('#olhinho_resultado_normal_od').prop('checked', false);
		$('#olhinho_resultado_leucoria_od').prop('checked', false);
		$('#olhinho_resultado_suspeito_od').prop('checked', false);
		$('#olhinho_resultado_normal_oe').prop('checked', false);
		$('#olhinho_resultado_leucoria_oe').prop('checked', false);
		$('#olhinho_resultado_suspeito_oe').prop('checked', false);
		$('#olhinho_anotacoes_qp').val('');
		$('#olhinho_anotacoes_hf').val('');
		$('#olhinho_anotacoes_outros').val('');
		$('#olhinho_anotacoes_obs').val('');
		$('input:radio[name=\'olhinho_conclusao_normal\']').prop('checked', false);
	}

	this.imprimeResultadoTesteOlhinho = function() {

		var clienteId = consultasTela.SelectedClient.getClienteId();

		var gestacao = $('select#con_gestacao option:selected').val();
		var parto = $('select#con_parto option:selected').val();
		var idadeGestacional = $('#idade_gestacional').val();
		var apgar = $('#apgar').val();
		var peso = $('#peso_nascimento').val();

		var normalOd = $('#olhinho_resultado_normal_od:checked').val();
		var leucoriaOd = $('#olhinho_resultado_leucoria_od:checked').val();
		var suspeitoOd = $('#olhinho_resultado_suspeito_od:checked').val();
		var normalOe = $('#olhinho_resultado_normal_oe:checked').val();
		var leucoriaOe = $('#olhinho_resultado_leucoria_oe:checked').val();
		var suspeitoOe = $('#olhinho_resultado_suspeito_oe:checked').val();
		var qp = $('#olhinho_anotacoes_qp').val();
		var hf = $('#olhinho_anotacoes_hf').val();
		var outros = $('#olhinho_anotacoes_outros').val();
		var obs = $('#olhinho_anotacoes_obs').val();
		var conclusaoNormal = $('input:radio[name=olhinho_conclusao_normal]:checked').val();

		this.setConsultaOlhoId('');
		this.setResultadoNormalOd(((normalOd) ? normalOd : ''));
		this.setResultadoLeucoriaOd(((leucoriaOd) ? leucoriaOd : ''));
		this.setResultadoSuspeitoOd(((suspeitoOd) ? suspeitoOd : ''));
		this.setResultadoNormalOe(((normalOe) ? normalOe : ''));
		this.setResultadoLeucoriaOe(((leucoriaOe) ? leucoriaOe : ''));
		this.setResultadoSuspeitoOe(((suspeitoOe) ? suspeitoOe : ''));
		this.setVisualNormal(((conclusaoNormal) ? conclusaoNormal : ''));
		this.setOutrosExames(((outros) ? outros : ''));
		this.setObservacao(((obs) ? obs: ''));
		this.setOlhoAnotQp(((qp) ? qp : ''));
		this.setOlhoAnotHf(((hf) ? hf : ''));

		var olhoArray = JSON.stringify(this);
		var clienteId = consultasTela.SelectedClient.getClienteId();

		var olhArray = new Array();

		olhArray.push((gestacao) ? gestacao : '');
		olhArray.push((parto) ? parto : '');
		olhArray.push((idadeGestacional) ? idadeGestacional : '');
		olhArray.push((apgar) ? apgar : '');
		olhArray.push((peso) ? peso : '');

		var olhArr = JSON.stringify(olhArray);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeResultadoOlhinho',
				olho: olhoArray,
				olhArr: olhArr,
				clienteId: clienteId
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				window.open('index2.php?module=resultadolinho', '_blank');
			}
		});
		return this;
	};

	/**
	 * Métodos de encapsulamento (get e set);
	 */
	
	this.getConsultaOlhoId = function() {
		return this.consultaOlhoId;
	};

	this.setConsultaOlhoId = function(consultaOlhoId) {
		this.consultaOlhoId = consultaOlhoId;
		return this;
	};

	this.getResultadoNormalOd = function() {
		return this.resultadoNormalOd;
	};

	this.setResultadoNormalOd = function(resultadoNormalOd) {
		this.resultadoNormalOd = resultadoNormalOd;
		return this;
	};	

	this.getResultadoSuspeitoOd = function() {
		return this.resultadoSuspeitoOd;
	};

	this.setResultadoSuspeitoOd = function(resultadoSuspeitoOd) {
		this.resultadoSuspeitoOd = resultadoSuspeitoOd;
		return this;
	};	

	this.getResultadoLeucoriaOd = function() {
		return this.resultadoLeucoriaOd;
	};

	this.setResultadoLeucoriaOd = function(resultadoLeucoriaOd) {
		this.resultadoLeucoriaOd = resultadoLeucoriaOd;
		return this;
	};	

	this.getResultadoNormalOe = function() {
		return this.resultadoNormalOe;
	};

	this.setResultadoNormalOe = function(resultadoNormalOe) {
		this.resultadoNormalOe = resultadoNormalOe;
		return this;
	};	

	this.getResultadoSuspeitoOe = function() {
		return this.resultadoSuspeitoOe;
	};

	this.setResultadoSuspeitoOe = function(resultadoSuspeitoOe) {
		this.resultadoSuspeitoOe = resultadoSuspeitoOe;
		return this;
	};	

	this.getResultadoLeucoriaOe = function() {
		return this.resultadoLeucoriaOe;
	};

	this.setResultadoLeucoriaOe = function(resultadoLeucoriaOe) {
		this.resultadoLeucoriaOe = resultadoLeucoriaOe;
		return this;
	};	

	this.getVisualNormal = function() {
		return this.visualNormal;
	};

	this.setVisualNormal = function(visualNormal) {
		this.visualNormal = visualNormal;
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

	this.getOlhoAnotQp = function() {
		return this.olho_anot_qp;
	};

	this.setOlhoAnotQp = function(olho_anot_qp) {
		this.olho_anot_qp = olho_anot_qp;
		return this;
	};

	this.getOlhoAnotHf = function() {
		return this.olho_anot_hf;
	};

	this.setOlhoAnotHf = function(olho_anot_hf) {
		this.olho_anot_hf = olho_anot_hf;
		return this;
	};	

};