function orelha2(consultaOrelhaId) {

	this.consultaOrelhaId = consultaOrelhaId;
	this.obstrucaoMeatoOd;
	this.obstrucaoMeatoOe;
	this.observacao;
	this.funcaoCoclear;
	this.conclusaoOd;
	this.conclusaoOe;
	this.equipamentoTeste;
	this.resutadoCocleo;

	this.atualizaConsultaOrelha2Servidor = function(idConsultaOrelha2, data) {

		var conclusaoOd = $('select#orelhinha2_conclusao_od option:selected').val();
		var conclusaoOe = $('select#orelhinha2_conclusao_oe option:selected').val();
		var equipamento = $('select#orelhinha2_equipamento option:selected').val();
		var meatoOd = $('select#orelhinha2_meato_od option:selected').val();
		var meatoOe = $('select#orelhinha2_meato_oe option:selected').val();
		var resultCocleo = $('select#orelhinha2_cocleo option:selected').val();
		var obs = $('#orelhinha2_observacoes').val();
		var funcaoCoclear = $('input:checkbox#orelhinha2_conclusao:checked').val();

		this.setConsultaOrelhaId(idConsultaOrelha2);
		this.setObstrucaoMeatoOd(((meatoOd) ? meatoOd : ''))
		this.setObstrucaoMeatoOe(((meatoOe) ? meatoOe : ''))
		this.setObservacao(((obs) ? obs : ''))
		this.setFuncaoCoclear(((funcaoCoclear) ? funcaoCoclear : ''))
		this.setConclusaoOd(((conclusaoOd) ? conclusaoOd : ''))
		this.setConclusaoOe(((conclusaoOe) ? conclusaoOe : ''))
		this.setEquipamentoTeste(((equipamento) ? equipamento : ''))
		this.setResultadoCocleo(((resultCocleo) ? resultCocleo : ''));

		var orelha2Array = JSON.stringify(this);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'atualizaConsultaOrelhinha2',
				orelha2: orelha2Array
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				if(resposta === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(3, '\'' + data + '\'');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	}

	this.insereConsultaOrelha2Servidor = function() {
		var conclusaoOd = $('select#orelhinha2_conclusao_od option:selected').val();
		var conclusaoOe = $('select#orelhinha2_conclusao_oe option:selected').val();
		var equipamento = $('select#orelhinha2_equipamento option:selected').val();
		var meatoOd = $('select#orelhinha2_meato_od option:selected').val();
		var meatoOe = $('select#orelhinha2_meato_oe option:selected').val();
		var resultCocleo = $('select#orelhinha2_cocleo option:selected').val();
		var obs = $('#orelhinha2_observacoes').val();
		var funcaoCoclear = $('input:checkbox#orelhinha2_conclusao:checked').val();

		this.setConsultaOrelhaId('');
		this.setObstrucaoMeatoOd(((meatoOd) ? meatoOd : ''))
		this.setObstrucaoMeatoOe(((meatoOe) ? meatoOe : ''))
		this.setObservacao(((obs) ? obs : ''))
		this.setFuncaoCoclear(((funcaoCoclear) ? funcaoCoclear : ''))
		this.setConclusaoOd(((conclusaoOd) ? conclusaoOd : ''))
		this.setConclusaoOe(((conclusaoOe) ? conclusaoOe : ''))
		this.setEquipamentoTeste(((equipamento) ? equipamento : ''))
		this.setResultadoCocleo(((resultCocleo) ? resultCocleo : ''));

		var orelha2Array = JSON.stringify(this);
		var clienteId = consultasTela.SelectedClient.getClienteId();

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'inserirConsultaOrelhinha2',
				idCliente: clienteId,
				orelha2: orelha2Array
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				var data = resposta['data'];
				var resultado = resposta['resultado'];
				if(resultado === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(3, '\'' + data + '\'');
				} else if(resultado == 'form_vazio') {
					alert('O formulário está vazio, por favor preencha pelo menos um campo.');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	}

	this.limpaModalOrelhinha2 = function() {

		this.limpaCamposModalOrelhinha2();

		var clienteId = consultasTela.SelectedClient.getClienteId();
		var strhtml = '';
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
				for(var i in resposta) {
					strhtml += '<option value=\''+ resposta[i]['consulta_orelhinha2_id'] +'\'>' + resposta[i]['data'] + '</option>'
				}
				$('#orelha2_ultimos_exames').html(strhtml);
			}
		});
		$('#orelha2_botao_link').html('<i class="icon-inbox"></i> Gravar');
		$('#orelha2_botao_gravar').unbind();
		$('#orelha2_botao_gravar').click(function() {
			consultasTela.SelectedOrelha2Obj.insereConsultaOrelha2Servidor();
		});

	}

	this.limpaCamposModalOrelhinha2 = function() {
		$('select#orelhinha2_conclusao_od').val('');
		$('select#orelhinha2_conclusao_oe').val('');
		$('select#orelhinha2_equipamento').val('');
		$('select#orelhinha2_meato_od').val('');
		$('select#orelhinha2_meato_oe').val('');
		$('select#orelhinha2_cocleo').val('');
		$('#orelhinha2_observacoes').val('');
		$('input:checkbox#orelhinha2_conclusao').prop('checked', false);
	}

	this.imprimeResultadoTesteOrelinha2 = function() {

		var clienteId = consultasTela.SelectedClient.getClienteId();

		var gestacao = $('select#con_gestacao option:selected').val();
		var parto = $('select#con_parto option:selected').val();
		var idadeGestacional = $('#idade_gestacional').val();
		var apgar = $('#apgar').val();
		var peso = $('#peso_nascimento').val();

		var conclusaoOd = $('select#orelhinha2_conclusao_od option:selected').attr('conclusao_od_nome');
		var conclusaoOe = $('select#orelhinha2_conclusao_oe option:selected').attr('conclusao_oe_nome');
		var equipamento = $('select#orelhinha2_equipamento option:selected').attr('equipamento_nome');
		var meatoOd = $('select#orelhinha2_meato_od option:selected').val();
		var meatoOe = $('select#orelhinha2_meato_oe option:selected').val();
		var resultCocleo = $('select#orelhinha2_cocleo option:selected').attr('resultado_cocleo_nome');
		var obs = $('#orelhinha2_observacoes').val();
		var funcaoCoclear = $('input:checkbox#orelhinha2_conclusao:checked').val();

		this.setConsultaOrelhaId('');
		this.setObstrucaoMeatoOd(((meatoOd) ? meatoOd : ''))
		this.setObstrucaoMeatoOe(((meatoOe) ? meatoOe : ''))
		this.setObservacao(((obs) ? obs : ''))
		this.setFuncaoCoclear(((funcaoCoclear) ? funcaoCoclear : ''))
		this.setConclusaoOd(((conclusaoOd) ? conclusaoOd : ''))
		this.setConclusaoOe(((conclusaoOe) ? conclusaoOe : ''))
		this.setEquipamentoTeste(((equipamento) ? equipamento : ''))
		this.setResultadoCocleo(((resultCocleo) ? resultCocleo : ''));

		var orelha2Array = JSON.stringify(this);
		var clienteId = consultasTela.SelectedClient.getClienteId();

		var orel2Array = new Array();

		orel2Array.push((gestacao) ? gestacao : '');
		orel2Array.push((parto) ? parto : '');
		orel2Array.push((idadeGestacional) ? idadeGestacional : '');
		orel2Array.push((apgar) ? apgar : '');
		orel2Array.push((peso) ? peso : '');

		var orel2Arr = JSON.stringify(orel2Array);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeResultadoOrelhinha2',
				orelha2: orelha2Array,
				orel2Arr: orel2Arr,
				clienteId: clienteId
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				window.open('index2.php?module=resultadoorelhinha2', '_blank');
			}
		});
		return this;
	};

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

	this.getFuncaoCoclear = function() {
		return this.funcaoCoclear;
	};

	this.setFuncaoCoclear = function(funcaoCoclear) {
		this.funcaoCoclear = funcaoCoclear;
		return this;
	};

	this.getConclusaoOd = function() {
		return this.conclusaoOd;
	};

	this.setConclusaoOd = function(conclusaoOd) {
		this.conclusaoOd = conclusaoOd;
		return this;
	};

	this.getConclusaoOe = function() {
		return this.conclusaoOe;
	};

	this.setConclusaoOe = function(conclusaoOe) {
		this.conclusaoOe = conclusaoOe;
		return this;
	};

	this.getEquipamentoTeste = function() {
		return this.equipamentoTeste;
	};

	this.setEquipamentoTeste = function(equipamentoTeste) {
		this.equipamentoTeste = equipamentoTeste;
		return this;
	};

	this.getResultadoCocleo = function() {
		return this.resultadoCocleo;
	};

	this.setResultadoCocleo = function(resultadoCocleo) {
		this.resultadoCocleo = resultadoCocleo;
		return this;
	};

};