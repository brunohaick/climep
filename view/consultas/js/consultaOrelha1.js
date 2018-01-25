function orelha1(consultaOrelhaId) {

	this.consultaOrelhaId = consultaOrelhaId;
	this.frequenciaOd;
	this.frequenciaOe;
	this.obstrucaoMeatoOd;
	this.obstrucaoMeatoOe;
	this.observacao;
	this.localizacaoMeato;
	this.tahoeOd;
	this.tahoeOe;
	this.noiseOd;
	this.noiseOe;
	this.comportamento1;
	this.comportamento2;
	this.comportamento3;
	this.comportamento4;
	this.funcaoCoclear;

	this.atualizaConsultaOrelha1Servidor = function(idConsultaOrelha1, data) {

		var tahoeOd = $('#orelhinha1_tahoe_od').val();
		var noiseOd = $('#orelhinha1_noise_od').val();
		var freqOd = $('select#orelhinha1_frequencia_od option:selected').val();
		var tahoeOe = $('#orelhinha1_tahoe_oe').val();
		var noiseOe = $('#orelhinha1_noise_oe').val();
		var freqOe = $('select#orelhinha1_frequencia_oe option:selected').val();
		var meatoOd = $('select#orelhinha1_meato_od option:selected').val();
		var meatoOe = $('select#orelhinha1_meato_oe option:selected').val();
		var localizacao = $('select#orelhinha1_localizacao option:selected').val();
		var avaliacao1 = $('select#orelhinha1_avaliacao_01 option:selected').val();
		var avaliacao2 = $('select#orelhinha1_avaliacao_02 option:selected').val();
		var avaliacao3 = $('select#orelhinha1_avaliacao_03 option:selected').val();
		var avaliacao4 = $('select#orelhinha1_avaliacao_04 option:selected').val();
		var obs = $('#orelhinha1_observacoes').val();
		var funcaoCoclear = $('input:checkbox#orelhinha1_conclusao:checked').val();

		this.setConsultaOrelhaId(idConsultaOrelha1);
		this.setFrequenciaOd(((freqOd) ? freqOd : ''));
		this.setFrequenciaOe(((freqOe) ? freqOe : ''));
		this.setObstrucaoMeatoOd(((meatoOd) ? meatoOd : ''));
		this.setObstrucaoMeatoOe(((meatoOe) ? meatoOe : ''));
		this.setLocalizacaoMeato(((localizacao) ? localizacao : ''));
		this.setTahoeOd(((tahoeOd) ? tahoeOd : ''));
		this.setTahoeOe(((tahoeOe) ? tahoeOe : ''));
		this.setNoiseOd(((noiseOd) ? noiseOd : ''));
		this.setNoiseOe(((noiseOe) ? noiseOe : ''));
		this.setComportamento1(((avaliacao1) ? avaliacao1 : ''));
		this.setComportamento2(((avaliacao2) ? avaliacao2 : ''));
		this.setComportamento3(((avaliacao3) ? avaliacao3 : ''));
		this.setComportamento4(((avaliacao4) ? avaliacao4 : ''));
		this.setObservacao(((obs) ? obs : ''));
		this.setFuncaoCoclear(((funcaoCoclear) ? funcaoCoclear : ''));

		var orelha1Array = JSON.stringify(this);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'atualizaConsultaOrelhinha1',
				orelha1: orelha1Array
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				if(resposta === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(2, '\'' + data + '\'');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	}

	this.insereConsultaOrelha1Servidor = function() {

		var tahoeOd = $('#orelhinha1_tahoe_od').val();
		var noiseOd = $('#orelhinha1_noise_od').val();
		var freqOd = $('select#orelhinha1_frequencia_od option:selected').val();
		var tahoeOe = $('#orelhinha1_tahoe_oe').val();
		var noiseOe = $('#orelhinha1_noise_oe').val();
		var freqOe = $('select#orelhinha1_frequencia_oe option:selected').val();
		var meatoOd = $('select#orelhinha1_meato_od option:selected').val();
		var meatoOe = $('select#orelhinha1_meato_oe option:selected').val();
		var localizacao = $('select#orelhinha1_localizacao option:selected').val();
		var avaliacao1 = $('select#orelhinha1_avaliacao_01 option:selected').val();
		var avaliacao2 = $('select#orelhinha1_avaliacao_02 option:selected').val();
		var avaliacao3 = $('select#orelhinha1_avaliacao_03 option:selected').val();
		var avaliacao4 = $('select#orelhinha1_avaliacao_04 option:selected').val();
		var obs = $('#orelhinha1_observacoes').val();
		var funcaoCoclear = $('input:checkbox#orelhinha1_conclusao:checked').val();

		this.setConsultaOrelhaId('');
		this.setFrequenciaOd(((freqOd) ? freqOd : ''));
		this.setFrequenciaOe(((freqOe) ? freqOe : ''));
		this.setObstrucaoMeatoOd(((meatoOd) ? meatoOd : ''));
		this.setObstrucaoMeatoOe(((meatoOe) ? meatoOe : ''));
		this.setLocalizacaoMeato(((localizacao) ? localizacao : ''));
		this.setTahoeOd(((tahoeOd) ? tahoeOd : ''));
		this.setTahoeOe(((tahoeOe) ? tahoeOe : ''));
		this.setNoiseOd(((noiseOd) ? noiseOd : ''));
		this.setNoiseOe(((noiseOe) ? noiseOe : ''));
		this.setComportamento1(((avaliacao1) ? avaliacao1 : ''));
		this.setComportamento2(((avaliacao2) ? avaliacao2 : ''));
		this.setComportamento3(((avaliacao3) ? avaliacao3 : ''));
		this.setComportamento4(((avaliacao4) ? avaliacao4 : ''));
		this.setObservacao(((obs) ? obs : ''));
		this.setFuncaoCoclear(((funcaoCoclear) ? funcaoCoclear : ''));

		var orelha1Array = JSON.stringify(this);
		var clienteId = consultasTela.SelectedClient.getClienteId();

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'inserirConsultaOrelhinha1',
				idCliente: clienteId,
				orelha1: orelha1Array
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				var data = resposta['data'];
				var resultado = resposta['resultado'];
				if(resultado === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(2, '\'' + data + '\'');
				} else if(resultado == 'form_vazio') {
					alert('O formulário está vazio, por favor preencha pelo menos um campo.');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	}

	this.limpaModalOrelhinha1 = function() {

		this.limpaCamposModalOrelhinha1();

		var clienteId = consultasTela.SelectedClient.getClienteId();
		var strhtml = '';
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
				for(var i in resposta) {
					strhtml += '<option value=\''+ resposta[i]['consulta_orelhinha1_id'] +'\'>' + resposta[i]['data'] + '</option>'
				}
				$('#orelha1_ultimos_exames').html(strhtml);
			}
		});
		$('#orelha1_botao_link').html('<i class="icon-inbox"></i> Gravar');
		$('#orelha1_botao_gravar').unbind();
		$('#orelha1_botao_gravar').click(function() {
			consultasTela.SelectedOrelha1Obj.insereConsultaOrelha1Servidor();
		});
	}

	this.limpaCamposModalOrelhinha1 = function() {
		$('#orelhinha1_tahoe_od').val('');
		$('#orelhinha1_noise_od').val('');
		$('select#orelhinha1_frequencia_od').val('');
		$('#orelhinha1_tahoe_oe').val('');
		$('#orelhinha1_noise_oe').val('');
		$('select#orelhinha1_frequencia_oe').val('');
		$('select#orelhinha1_meato_od').val('');
		$('select#orelhinha1_meato_oe').val('');
		$('select#orelhinha1_localizacao').val('');
		$('select#orelhinha1_avaliacao_01').val('');
		$('select#orelhinha1_avaliacao_02').val('');
		$('select#orelhinha1_avaliacao_03').val('');
		$('select#orelhinha1_avaliacao_04').val('');
		$('#orelhinha1_observacoes').val('');
		$('input:checkbox#orelhinha1_conclusao').prop('checked', false);
	}

	/**
	 * Métodos de encapsulamento (get e set);
	 */

	this.getConsultaOrelhaId = function() {
		return this.consultaOrelhaId;
	};

	this.setConsultaOrelhaId = function(consultaOrelhaId) {
		this.consultaOrelhaId = consultaOrelhaId;
		return this;
	};

	this.getFrequenciaOd = function() {
		return this.frequenciaOd;
	};

	this.setFrequenciaOd = function(frequenciaOd) {
		this.frequenciaOd = frequenciaOd;
		return this;
	};

	this.getFrequenciaOe = function() {
		return this.frequenciaOe;
	};

	this.setFrequenciaOe = function(frequenciaOe) {
		this.frequenciaOe = frequenciaOe;
		return this;
	};

	this.getObstrucaoMeatoOd = function() {
		return this.obstrucaoMeatoOd;
	};

	this.setObstrucaoMeatoOd = function(obstrucaoMeatoOd) {
		this.obstrucaoMeatoOd = obstrucaoMeatoOd;
		return this;
	};

	this.getObstrucaoMeatoOe = function() {
		return this.obstrucaoMeatoOe;
	};

	this.setObstrucaoMeatoOe = function(obstrucaoMeatoOe) {
		this.obstrucaoMeatoOe = obstrucaoMeatoOe;
		return this;
	};

	this.getObservacao = function() {
		return this.observacao;
	};

	this.setObservacao = function(observacao) {
		this.observacao = observacao;
		return this;
	};

	this.getLocalizacaoMeato = function() {
		return this.localizacaoMeato;
	};

	this.setLocalizacaoMeato = function(localizacaoMeato) {
		this.localizacaoMeato = localizacaoMeato;
		return this;
	};

	this.getTahoeOd = function() {
		return this.tahoeOd;
	};

	this.setTahoeOd = function(tahoeOd) {
		this.tahoeOd = tahoeOd;
		return this;
	};

	this.getTahoeOe = function() {
		return this.tahoeOe;
	};

	this.setTahoeOe = function(tahoeOe) {
		this.tahoeOe = tahoeOe;
		return this;
	};

	this.getNoiseOd = function() {
		return this.noiseOd;
	};

	this.setNoiseOd = function(noiseOd) {
		this.noiseOd = noiseOd;
		return this;
	};

	this.getNoiseOe = function() {
		return this.noiseOe;
	};

	this.setNoiseOe = function(noiseOe) {
		this.noiseOe = noiseOe;
		return this;
	};

	this.getComportamento1 = function() {
		return this.comportamento1;
	};

	this.setComportamento1 = function(comportamento1) {
		this.comportamento1 = comportamento1;
		return this;
	};
	this.getComportamento2 = function() {
		return this.comportamento2;
	};

	this.setComportamento2 = function(comportamento2) {
		this.comportamento2 = comportamento2;
		return this;
	};

	this.getComportamento3 = function() {
		return this.comportamento3;
	};

	this.setComportamento3 = function(comportamento3) {
		this.comportamento3 = comportamento3;
		return this;
	};

	this.getComportamento4 = function() {
		return this.comportamento4;
	};

	this.setComportamento4 = function(comportamento4) {
		this.comportamento4 = comportamento4;
		return this;
	};

	this.getFuncaoCoclear = function() {
		return this.funcaoCoclear;
	};

	this.setFuncaoCoclear = function(funcaoCoclear) {
		this.funcaoCoclear = funcaoCoclear;
		return this;
	};

};