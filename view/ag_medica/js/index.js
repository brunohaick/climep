$(document).ready(function() {

	$('tr:even[name=table-color]').css('background', '#D3D6FF');
	$('tr:odd[name=table-color]').css('background', '#FFF');
	$('#calendario').DatePicker({
		mode: 'single',
		inline: true,
		date: new Date(),
		onChange: function() {
			var date = $(this).DatePickerGetDate()[0];
			$('input#agenda-calendario').val(date.format('BR'));
			carregaAgenda();
		}
	});

	$('#ag-gravar').click(function() {
		var medico_id = $('#medico-agenda').val();
		var covenio_id = $('#arc-horario-convenio').val();
		var tabela_id = $('#arc-horario-tabela').val();
		var procedimento_id = $('#arc-horario-servicos').val();
		var hora = $('#arc-horario-chegada').val();
		var observacao = $('#arc-horario-obs').val();
		var nome = $('#arc-horario-nome').val();
		var responsavel = $('#arc-horario-resp').val();
		var contato = $('#arc-horario-contato').val();
		var celular = $('#arc-horario-celular').val();
		var status = $('#arc-horario-status').val();
		var data = $('#agenda-calendario').val();
		var flag = $('#TipoDeIncersao').val();
		var id = $('#IdDoHorario').val();
		$.ajax({
			url: 'index.php?module=ag_medica&tmp=1',
			type: 'POST',
			data: {
				flag: flag,
				id: id,
				medico_id: medico_id,
				covenio_id: covenio_id,
				tabela_id: tabela_id,
				procedimento_id: procedimento_id,
				hora: hora,
				observacao: observacao,
				nome: nome,
				responsavel: responsavel,
				contato: contato,
				celular: celular,
				status: status,
				data: data
			},
			success: function(t) {
				if (t === 'OK') {
					alert('Cliente agendado com sucesso');
					fechar_modal('boxes-acr-horario');
				}
				else if (t === 'nao') {
					alert('Houve um problema ao inserir o agendamento');
				}
				$('#agenda-calendario').val($('#agenda-calendario').data('antigo'));
				carregaAgenda();
			}
		});
	});

//	$('button#botaoAgendaBuscar').click(function() {
//		alert('asd');
//		Pesquisa(function(T) {});
//	});
	$('#btn-msg-interna').click(function() {
		var mensagem = $('#msg-interna-body').val();
		var medicoId = $("#medico-agenda option:selected").val();
		$.ajax({
			url: 'index.php?module=ag_medica&tmp=1',
			type: 'POST',
			data: {flag: 'setMensagemPrivada', data: $("#agenda-calendario").val(), medicoId: medicoId, mensagem: mensagem},
			success: function() {
				alert('Mensagem alterada');
			}
		});
	});
	$('#arc-data').change(function() {
		$('#agenda-calendario').val($(this).val());
		$(this).val($('#agenda-calendario').val());
	});

	$('button#BottaoBuscarAgenda').click(function() {
		Pesquisa(function(T) {
			$.ajax({
				url: 'index.php?module=pesquisaMatriculaTitular&tmp',
				type: 'POST',
				data: {flag: 'PegaInformacoesDoCliente', cliente_id: T.matricula},
				success: function(resposta) {
					resposta = $.parseJSON(resposta);
					$('#arc-horario-nome').val(T['nome'] + ((T['sobrenome'] !== null) ? ' ' + T['sobrenome'] : ''));
					$('#arc-horario-contato').val(T['tel_residencial']);
					$('#arc-horario-celular').val(T['tel_comercial']);
					$('#arc-horario-resp').val(resposta['nome'] + ((resposta['sobrenome'] !== null) ? ' ' + resposta['sobrenome'] : ''));
					$('#arc-data').val($('#agenda-calendario').val());
				}
			});

		}, true);
	});
	carregaAgenda();
});

function insereHorario() {
	var mInicio = $('#horariomanhaInicio').val();
	var mFim = $('#horarioManhaFim').val();
	var tInicio = $('#horarioTardeInicio').val();
	var tFim = $('#horarioTardeFim').val();
	var medicoId = $("#medico-agenda option:selected").val();
	$.ajax({
		url: 'index.php?module=ag_medica&tmp=1',
		type: 'POST',
		data: {flag: 'setHorarioDeTrabalho', medicoId: medicoId, mInicio: mInicio, mFim: mFim, tInicio: tInicio, tFim: tFim},
		success: function() {
			alert('Horario alterado');
			carregaAgenda();
		}
	});
}

function carregaAgenda() {

	var medicoId = $("#medico-agenda option:selected").val();
	$.ajax({
		url: 'index.php?module=ag_medica&tmp=1',
		type: 'POST',
		data: {flag: 'getMensagemPrivada', data: $("#agenda-calendario").val(), medicoId: medicoId},
		success: function(resposta) {
			resposta = $.parseJSON(resposta);
			if (resposta)
				$('#msg-interna-body').val(resposta['mensagem']);
			else
				$('#msg-interna-body').val('');
		}
	});
	//intervalo calculado
	//fazendo assim 
	//1 ms * 1000 = 1 sec
	//1 sec * 60  = 1 min
	//1 min * 15  = 15 min
	var intervalo_hora_data = 15 * 60 * 1000;

	//Dia, mes e ano escolhido ao acaso o importante é os dois serem iguais
	//De preferencia eu quis pegar o inico do TimeStamp para trabalhas com valores menores na calculos
	var hora_inicio_cal = new Date(1970, 0, 1, 8);
	var hora_fim_cal = new Date(1970, 0, 1, 20);

	var $tbody = $('div#tabela-agenda table#tabela-body tbody');
	var arc_horario_chegada = $('#arc-horario-chegada');
	$tbody.html('');
	arc_horario_chegada.html('');
	var horaAtual = hora_inicio_cal;

//	var horasListadas = new Array();
	var count = 0;
	while (horaAtual.getTime() <= hora_fim_cal.getTime()) {
		var hora = horaAtual.format('isoTime');

		arc_horario_chegada.append($('<option/>').attr('id', horaAtual.format('HH-MM-ss')).html(hora).val(hora));

		var $tr = $('<tr/>').attr('name', 'table-color').attr('class', 'dayhead pointer-cursor').attr('id', horaAtual.format('HH-MM-ss'));

		$tr.append($('<th width="10%"/>').html(++count));
		$tr.append($('<th status align="center"/>').html('Bloqueado'));
		$tr.append($('<th align="center"/>').html(hora));
		$tr.append($('<th name align="center"/>'));
		$tr.append($('<th contatos align="center"/>'));
		$tr.append($('<th procedimentos align="center"/>'));
		$tr.append($('<th convenio align="center"/>'));
		$tr.append($('<th Resposavel align="center"/>'));
		$tr.append($('<th obs align="center"/>'));

		$tr.dblclick(function() {
			if ($('#medico-agenda').val() !== '' && $(this).children('th[status]').html() !== 'Bloqueado') {
				abrir_modal('boxes-acr-horario');
				$('#agenda-calendario').data('antigo', $('#agenda-calendario').val());
				var info = $(this).data('infos');
				if (info !== undefined) {
					$('#arc-horario-nome').val(info['nome']);
					$('#arc-horario-resp').val(info['resp_ag']);
					$('#arc-horario-contato').val(info['contato_ag']);
					$('#arc-horario-obs').val(info['observacao']);
					$('#arc-horario-status').children('option').each(function() {
						if ($(this).val() === info['status_id']) {
							$(this).attr('selected', 'selected');
						}
					});

					$('#arc-horario-tabela').children('option').each(function() {
						if ($(this).val() === info['convenio_has_procedimento_has_tabela_tabela_id']) {
							$(this).attr('selected', 'selected');
						}
					});

					$('#arc-horario-convenio').children('option').each(function() {
						if ($(this).val() === info['convenio_has_procedimento_has_tabela_convenio_id']) {
							$(this).attr('selected', 'selected');
						}
					});

					$('#arc-horario-servicos').children('option').each(function() {
						if ($(this).val() === info['convenio_has_procedimento_has_tabela_procedimento_id']) {
							$(this).attr('selected', 'selected');
						}
					});
					$('#TipoDeIncersao').val('UpdateHorario');
					$('#IdDoHorario').val(info.id);
					$('#arc-data').val($('#agenda-calendario').val());
					if (info.status !== 'Livre' && info.status !== 'Bloqueado')
						$('#arc-data').attr('disabled', false);
					else {
						$('#arc-horario-tabela').val('');
						$('#arc-horario-convenio').val('');
						$('#arc-horario-servicos').val('');
						$('#arc-data').attr('disabled', true);
					}
				} else {
					$('#arc-horario-nome').val('');
					$('#arc-horario-resp').val('');
					$('#arc-horario-contato').val('');
					$('#arc-horario-obs').val('');
					$('#arc-horario-celular').html('');
					$('#TipoDeIncersao').val('SalvaHorario');
					$('#IdDoHorario').val('');
					$('#arc-horario-tabela').val('');
					$('#arc-horario-convenio').val('');
					$('#arc-horario-servicos').val('');
					$('#arc-data').val($('#agenda-calendario').val());
					$('#arc-data').attr('disabled', true);
				}
				var THIStemp = this;
				arc_horario_chegada.children('option').each(function() {
					if ($(this).attr('id') === $(THIStemp).attr('id')) {
						$(this).attr('selected', 'selected');
					}
				});
			} else {
				if ($(this).children('th[status]').html() !== 'Bloqueado')
					alert('Selecione um medico');
				else
					alert('Não pode alterar uma data bloqueada');
			}
		});


		$tr.bind("contextmenu", function(e) {

			var info = $(this).data('infos');
			var id = $(this).attr('id');

			while (id.indexOf('-') !== - 1)
				id = id.replace('-', ':');

			var hora_inicio = id;
			var data = $('#agenda-calendario').val();
			var medico_id = $('#medico-agenda').val();
			if (info === undefined || info['status'] !== 'Bloqueado') {
				$.post("index.php?module=bloqueio&tmp=1", {medico_id: medico_id, data: data, hora: hora_inicio, flag: 'Bloqueia'}, function(result) {

//					console.log(result);
					carregaAgenda();
				});
			} else {
				$.post("index.php?module=bloqueio&tmp=1", {medico_id: medico_id, data: data, hora: hora_inicio, flag: 'Desbloqueia'}, function(result) {
//					$("#result-bloqueio").html(result);
					carregaAgenda();
				});
			}

			return false;
		});

		$tbody.append($tr);
//		horasListadas.push('\'' + hora + '\'');
		horaAtual = new Date(horaAtual.getTime() + intervalo_hora_data);
	}

	var medicoId = $("#medico-agenda option:selected").val();
//	if (medicoId != '')
	$.ajax({
		url: 'index.php?module=ag_medica&tmp=1',
		type: 'POST',
		data: {flag: 'pegaHorariosDeTrabalho', medicoId: medicoId},
		async: false,
		success: function(response) {
			response = $.parseJSON(response);
			if (response) {
				var temp = response['horario_inicio_manha'].split(':');
				$('#horariomanhaInicio').val(response['horario_inicio_manha']);
				var horario_inicio_manha = new Date(1970, 0, 1, temp[0], temp[1], temp[2]);
				temp = response['horario_fim_manha'].split(':');
				$('#horarioManhaFim').val(response['horario_fim_manha']);
				var horario_fim_manha = new Date(1970, 0, 1, temp[0], temp[1], temp[2]);

				temp = response['horario_inicio_tarde'].split(':');
				$('#horarioTardeInicio').val(response['horario_inicio_tarde']);
				var horario_inicio_tarde = new Date(1970, 0, 1, temp[0], temp[1], temp[2]);
				temp = response['horario_fim_tarde'].split(':');
				$('#horarioTardeFim').val(response['horario_fim_tarde']);
				var horario_fim_tarde = new Date(1970, 0, 1, temp[0], temp[1], temp[2]);

				$('div#tabela-agenda table#tabela-body tbody tr').each(function() {
					var $status = $(this).children('th[status]');
					temp = $(this).attr('id').split('-');
					var horarioDaLinha = new Date(1970, 0, 1, temp[0], temp[1], temp[2]);
					if ($status.html() === 'Bloqueado' && (horario_inicio_manha <= horarioDaLinha && horario_fim_manha >= horarioDaLinha) || (horario_inicio_tarde <= horarioDaLinha && horario_fim_tarde >= horarioDaLinha)) {
						if (!$(this).data('infos'))
							$status.html('Livre');
					}
				});

			} else {
				$('#horariomanhaInicio').val('');
				$('#horarioManhaFim').val('');
				$('#horarioTardeInicio').val('');
				$('#horarioTardeFim').val('');
			}
		}
	});
	$.ajax({
		url: 'index.php?module=ag_medica&tmp=1',
		type: 'POST',
		data: {flag: 'pegaHorarios', data: $("#agenda-calendario").val(), cancelados: $('input[name=cancelados]').is(':checked'), horaMenor: hora_inicio_cal.format('isoTime'), horaMaior: hora_fim_cal.format('isoTime'), medicoId: medicoId},
		async: false,
		success: function(response) {
			response = $.parseJSON(response);
			if (response !== null) {
				$.each(response, function(index, value) {
//					console.log(value);
					if (value['hora_chegada'].indexOf('-') === -1)
						while (value['hora_chegada'].indexOf(':') !== - 1)
							value['hora_chegada'] = value['hora_chegada'].replace(':', '-');
					$('tr#' + value['hora_chegada'] + ' th[name]').html(value['nome']);
					$('tr#' + value['hora_chegada'] + ' th[status]').html(value['status']);
					//if(value['status'] === 'Livre')
					//	value['status'] = 'Bloqueado';
					$('tr#' + value['hora_chegada'] + ' th[contatos]').html(((value['status'] !== 'Bloqueado' && value['status'] !== 'Livre') ? value['contato_ag'] : ''));
					$('tr#' + value['hora_chegada'] + ' th[procedimentos]').html(((value['status'] !== 'Bloqueado' && value['status'] !== 'Livre') ? value['descricao'] : ''));
					$('tr#' + value['hora_chegada'] + ' th[convenio]').html(((value['status'] !== 'Bloqueado' && value['status'] !== 'Livre') ? value['convenio'] : ''));
					$('tr#' + value['hora_chegada'] + ' th[Resposavel]').html(((value['status'] !== 'Bloqueado' && value['status'] !== 'Livre') ? value['resp_ag'] : ''));
					$('tr#' + value['hora_chegada'] + ' th[obs]').html(((value['status'] !== 'Bloqueado' && value['status'] !== 'Livre') ? ((value['observacao']) ? value['observacao'] : '').substring(0, 15) : ''));
					$('tr#' + value['hora_chegada']).data('infos', value);
					if ((value['status'] !== 'Bloqueado' && value['status'] !== 'Livre')) {
						var $div = $('tr#' + value['hora_chegada']);
						$div.attr('title', 'Atendente').attr('data-content', value.atendente).popover({trigger: 'manual'}).hover(function() {
							$(this).popover('toggle');
						});
					}
					//'class="UsuarioQueAtendeu" rel="popover" title="Usuario que atendeu" data-content="'. $row['login'].'" 
				});
			}
		}
	});


}

//function carregaAgendamento() {
//
//	if ($("#medico option:selected").val() == "") {
//		return false;
//	} else if ($("#agenda-calendario").val() == "") {
//		return false;
//	}
//
//	var id = $("#medico-agenda option:selected").val();
//	var data = $("#agenda-calendario").val();
//
//	$.post("index.php?module=ag_medica&tmp=1", {
//		id: id,
//		data: data
//	}, function(result) {
//		$("#conteudo").html(result);
//		data = data.split('/');
//		$('#calendario').DatePickerSetDate(new Date(data[2], data[1] - 1, data[0]));
//	});
//
//}
//
//function chamaModal() {
//	var isd = "aaa";
//	//e.preventDefault();
//	$('#teste').reveal({
//		animation: 'fadeAndPop', //fade, fadeAndPop, none
//		animationspeed: 300, //how fast animtions are
//		closeonbackgroundclick: true, //if you click background will modal close?
//		dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
//
//	});
//
//}

