/**
 * Função para enviar a Data da Consulta
 * Formulario é submetido sem necessidade de um submit.É usado o método onChange
 * Após o formulário ser submetido, envia-se todos os dados contidos para um
 * script em php para enviar a data da consulta para a página.
 *
 * @author Marcus Dias
 * @date Criação: 17/09/2012
 *
 * @param
 *
 * @return
 *	True em caso de sucesso e False em caso de Falha.
 *
 */
function carregaData() {
	var data = $("#data-estoque").val();
	var medico = $("#medico").val();

	$.post("index.php?module=sala_espera&tmp=1", {medico: medico, data: data}, function(result) {
		$("#form-data-atendimento").html(result);
	});
}

function carregaHorariosDoMedico($Elemento) {

	$.ajax({
		url: 'index.php?module=sala_espera&medico=' + $Elemento.attr('medicoId') + '&tmp&PegaHorario',
		type: 'POST',
		data: {'data-atendimento': $('#data-estoque').val()},
		success: function(result) {
			result = $.parseJSON(result);
			var $tbody = $('#salaEsperaHorarios');
			$tbody.html('');
			$.each(result, function(index, valor) {
//				console.log(valor);
				var atendido = valor['hora_atendimento'] != null;
				var $tr = $('<tr/>')
						.attr('name', 'table-color')
						.attr('class', 'dayhead pointer-cursor')
						.attr('atendido', atendido).dblclick(function() {
					$.ajax({
						url: 'index.php?module=sala_espera&medico=' + $Elemento.attr('medicoId') + '&tmp&AtendeUsuario',
						type: 'POST',
						async: false,
						data: {idDaFila: $(this).children('#idDaFila').html(), atendido: $(this).attr('atendido')},
						success: function(r) {
							console.log(r);
							carregaHorariosDoMedico($Elemento);
						}
					});
				});
				$tr.append($('<th/>').attr('aling', 'center').html((atendido) ? 'JÁ' : ''));
				$tr.append($('<th id="idDaFila"/>').attr('aling', 'center').html(valor['id']));
				$tr.append($('<th/>').attr('aling', 'center').html(valor['hora_recepcao']));
				$tr.append($('<th/>').attr('aling', 'center').html((atendido) ? valor['hora_atendimento'] : ''));
				$tr.append($('<th/>').attr('aling', 'center').html(valor['cliente_id']));
				$tr.append($('<th/>').attr('aling', 'center').html(valor['cliente_nome']));
				$tr.append($('<th/>').attr('aling', 'center').html(valor['recepcionista_id']));
				$tbody.append($tr);
			});
		}
	});
}
