function lingua(consultaLinguaId) {

	this.consultaLinguaId = consultaLinguaId;
	this.conclusaoTeste;
	this.outrosExames;
	this.observacao;
	this.freioLingualNormal;
	this.freioLingualCurto;
	this.insercaoAnteriorizada;
	this.anquiloglossia;
	this.lingua_anot_qp;

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

	this.insereConsultaLinguaServidor = function() {

		var qp = new Array();
		var linguinhaOutrosExames = $("#linguinha_outros_exames").val();
		var linguinhaObservacoes = $("#linguinha_observacoes").val();
		var freioNormal = $('#linguinha_result_freio_ling_normal:checked').val();
		var freioCurto = $('#linguinha_result_freio_ling_curto:checked').val();
		var insercaoAnter = $('#linguinha_result_insercao_anter:checked').val();
		var anq = $('#linguinha_result_anquiloglossia:checked').val();
		var conclusao = $('input:radio[name=\'linguinha_conclusao\']:checked').val();

		$("input:checkbox[name='linguinha_qp[]']:checked").each(function() {
			qp.push($(this).val());
		});
		
		this.setConsultaLinguaId('');
		this.setConclusaoTeste(((conclusao) ? conclusao : ''));
		this.setOutrosExames(((linguinhaOutrosExames) ? linguinhaOutrosExames : ''));
		this.setObservacao(((linguinhaObservacoes) ? linguinhaObservacoes : ''));
		this.setFreioLingualNormal(((freioNormal) ? freioNormal : ''));
		this.setFreioLingualCurto(((freioCurto) ? freioCurto : ''));
		this.setInsercaoAnteriorizada(((insercaoAnter) ? insercaoAnter : ''));
		this.setAnquiloglossia(((anq) ? anq : ''));
		this.setLingua_anot_qp(((qp) ? qp : ''));

		var linguinhaArray = JSON.stringify(this);	
		var clienteId = consultasTela.SelectedClient.getClienteId();

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
			flag: 'inserirConsultaLinguinha',
			idCliente: clienteId,
			lingua: linguinhaArray
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				var data = resposta['data'];
				var resultado = resposta['resultado'];
				if(resultado === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(5, '\'' + data + '\'');
				} else if(resultado == 'form_vazio') {
					alert('O formulário está vazio, por favor preencha pelo menos um campo.');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
		return this;
	};

	this.atualizaConsultaLinguaServidor = function(idConsultaLingua, data) {

		var qp = new Array();
		var linguinhaOutrosExames = $("#linguinha_outros_exames").val();
		var linguinhaObservacoes = $("#linguinha_observacoes").val();
		var freioNormal = $('#linguinha_result_freio_ling_normal:checked').val();
		var freioCurto = $('#linguinha_result_freio_ling_curto:checked').val();
		var insercaoAnter = $('#linguinha_result_insercao_anter:checked').val();
		var anq = $('#linguinha_result_anquiloglossia:checked').val();
		var conclusao = $('input:radio[name=\'linguinha_conclusao\']:checked').val();

		$("input:checkbox[name='linguinha_qp[]']:checked").each(function() {
			qp.push($(this).val());
		});
		
		this.setConsultaLinguaId(idConsultaLingua);
		this.setConclusaoTeste(((conclusao) ? conclusao : ''));
		this.setOutrosExames(((linguinhaOutrosExames) ? linguinhaOutrosExames : ''));
		this.setObservacao(((linguinhaObservacoes) ? linguinhaObservacoes : ''));
		this.setFreioLingualNormal(((freioNormal) ? freioNormal : ''));
		this.setFreioLingualCurto(((freioCurto) ? freioCurto : ''));
		this.setInsercaoAnteriorizada(((insercaoAnter) ? insercaoAnter : ''));
		this.setAnquiloglossia(((anq) ? anq : ''));
		this.setLingua_anot_qp(((qp) ? qp : ''));

		var linguinhaArray = JSON.stringify(this);
		console.log(linguinhaArray);
		
		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
			flag: 'atualizaConsultaLinguinha',
			lingua: linguinhaArray
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				if(resposta === true) {
					alert('Operação realizada com sucesso.');
					consultasTela.AtualizaTelaTriagem(5, '\'' + data + '\'');
				} else {
					alert('Ocorreu um erro ao inserir o registro. Por favor Tente Novamente.');
				}
			}
		});
	};

	this.limpaModalLinguinha = function() {

		this.limpaCamposModalLinguinha();
		
		var clienteId = consultasTela.SelectedClient.getClienteId();
		var strhtml = '';
		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'buscaULtimasConsultasLinguaData',
				idCliente: clienteId
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				strhtml += '<option></option>';
				for(var i in resposta) {
					strhtml += '<option value=\''+ resposta[i]['consulta_linguinha_id'] +'\'>' + resposta[i]['data'] + '</option>'
				}
				$('#lingua_ultimos_exames').html(strhtml);
			}
		});
		
		$('#lingua_botao_imprimir').unbind();
		$('#lingua_botao_link').html('<i class="icon-inbox"></i> Gravar');
		$('#lingua_botao_gravar').unbind();
		$('#lingua_botao_gravar').click(function() {
			consultasTela.SelectedLinguaObj.insereConsultaLinguaServidor();
		});
	}

	this.limpaCamposModalLinguinha = function() {
		$('input:checkbox[name=\'linguinha_qp[]\']').prop('checked', false);
		$('#linguinha_result_freio_ling_normal').prop('checked', false);
		$('#linguinha_result_freio_ling_curto').prop('checked', false);
		$('#linguinha_result_insercao_anter').prop('checked', false);
		$('#linguinha_result_anquiloglossia').prop('checked', false);
		$('#linguinha_outros_exames').val('');
		$('#linguinha_observacoes').val('');
		$('input:radio[name=\'linguinha_conclusao\']').prop('checked', false);		
	}

	this.imprimeResultadoTesteLingua = function() {

		var clienteId = consultasTela.SelectedClient.getClienteId();

		var gestacao = $('select#con_gestacao option:selected').val();
		var parto = $('select#con_parto option:selected').val();
		var idadeGestacional = $('#idade_gestacional').val();
		var apgar = $('#apgar').val();
		var peso = $('#peso_nascimento').val();

		var qp = new Array();
		var linguinhaOutrosExames = $("#linguinha_outros_exames").val();
		var linguinhaObservacoes = $("#linguinha_observacoes").val();
		var freioNormal = $('#linguinha_result_freio_ling_normal:checked').attr('linguinha_resultado_nome');
		var freioCurto = $('#linguinha_result_freio_ling_curto:checked').attr('linguinha_resultado_nome');
		var insercaoAnter = $('#linguinha_result_insercao_anter:checked').attr('linguinha_resultado_nome');
		var anq = $('#linguinha_result_anquiloglossia:checked').attr('linguinha_resultado_nome');
		var conclusao = $('input:radio[name=\'linguinha_conclusao\']:checked').attr('linguinha_conclusao_nome');

		qp[0] = new Array();
		qp[1] = new Array();
		$("input:checkbox[name='linguinha_qp[]']:checked").each(function(i, v) {
			if($(this).val() < 6) {
				qp[0][i] = $(this).attr('linguinha_qp_descricao');
			} else {
				qp[1][i - 5] = $(this).attr('linguinha_qp_descricao');
			}
		});
		
		this.setConsultaLinguaId('');
		this.setConclusaoTeste(((conclusao) ? conclusao : ''));
		this.setOutrosExames(((linguinhaOutrosExames) ? linguinhaOutrosExames : ''));
		this.setObservacao(((linguinhaObservacoes) ? linguinhaObservacoes : ''));
		this.setFreioLingualNormal(((freioNormal) ? freioNormal : ''));
		this.setFreioLingualCurto(((freioCurto) ? freioCurto : ''));
		this.setInsercaoAnteriorizada(((insercaoAnter) ? insercaoAnter : ''));
		this.setAnquiloglossia(((anq) ? anq : ''));
		this.setLingua_anot_qp(((qp) ? qp : ''));

		var linguinhaArray = JSON.stringify(this);
		console.log(linguinhaArray);

		var linArray = new Array();

		linArray.push((gestacao) ? gestacao : '');
		linArray.push((parto) ? parto : '');
		linArray.push((idadeGestacional) ? idadeGestacional : '');
		linArray.push((apgar) ? apgar : '');
		linArray.push((peso) ? peso : '');

		var linArr = JSON.stringify(linArray);

		$.ajax({
			url: 'index.php?module=consultas&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeResultadoLinguinha',
				lingua: linguinhaArray,
				linArr: linArr,
				clienteId: clienteId
			},
			success: function(resposta) {
				resposta = $.parseJSON(resposta);
				window.open('index2.php?module=resultadolinguinha', '_blank');
			}
		});
		return this;
	};

	/**
	 * Métodos de encapsulamento (get e set);
	 */
	
	this.getConsultaLinguaId = function() {
		return this.consultaLinguaId;
	};

	this.setConsultaLinguaId = function(consultaLinguaId) {
		this.consultaLinguaId = consultaLinguaId;
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

	this.getFreioLingualNormal = function() {
		return this.freioLingualNormal;
	};

	this.setFreioLingualNormal = function(freioLingualNormal) {
		this.freioLingualNormal = freioLingualNormal;
		return this;
	};
	
	this.getFreioLingualCurto = function() {
		return this.freioLingualCurto;
	};

	this.setFreioLingualCurto = function(freioLingualCurto) {
		this.freioLingualCurto = freioLingualCurto;
		return this;
	};

	this.getInsercaoAnteriorizada = function() {
		return this.insercaoAnteriorizada;
	};

	this.setInsercaoAnteriorizada = function(insercaoAnteriorizada) {
		this.insercaoAnteriorizada = insercaoAnteriorizada;
		return this;
	};

	this.getAnquiloglossia = function() {
		return this.anquiloglossia;
	};

	this.setAnquiloglossia = function(anquiloglossia) {
		this.anquiloglossia = anquiloglossia
		return this;
	};

	this.getLingua_anot_qp = function() {
		return this.lingua_anot_qp;
	};

	this.setLingua_anot_qp = function(lingua_anot_qp) {
		this.lingua_anot_qp = lingua_anot_qp;
		return this;
	};

};