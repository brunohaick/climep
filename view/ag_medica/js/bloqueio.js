$(document).ready(function() {

	/**
	 * Desbloqueio de Agendamento. Formulario de Desbloqueio.
	 *Após o formulário ser submetido, envia-se todos os dados contidos para um
	 * script em php para motar a query sql de desbloqueio de agendamento no banco.
	 *
	 *@author Marcus Dias
	 *@date Criação: 16/09/2012
	 *
	 * @param
	 *
	 * @return
	 *True em caso de sucesso e False em caso de Falha.
	 */
	$("input#ag-desbloquear").click(function() {
		var data = $("#arc-horario-data").val();
		var hora_inicio = $("#arc-horario-inicio option:selected").val();
		var hora_termino = $("#arc-horario-termino option:selected").val();
		var desbloquear = $("input#ag-desbloquear").val();
		var medico_id = $('#medico-agenda').val();
		$.post("index.php?module=bloqueio&tmp=1", {medico_id: medico_id, data: data, hora_inicio: hora_inicio, hora_termino: hora_termino, desbloquear: desbloquear}, function(result) {
			$("#result-bloqueio").html(result);
			carregaAgenda();
		});
	});

	/**
	 * Bloqueio de Agendamento. Formulario de Bloqueio.
	 *Após o formulário ser submetido, envia-se todos os dados contidos para um
	 * script em php para motar a query sql de bloqueio de agendamento no banco.
	 *
	 *@author Marcus Dias
	 *@date Criação: 16/09/2012
	 *
	 * @param
	 *
	 * @return
	 *True em caso de sucesso e False em caso de Falha.
	 */
	$("input#ag-bloquear").click(function() {
		var data = $("#arc-horario-data").val();
		var hora_inicio = $("#arc-horario-inicio option:selected").val();
		var hora_termino = $("#arc-horario-termino option:selected").val();
		var bloquear = $("input#ag-bloquear").val();
		var medico_id = $('#medico-agenda').val();

		$.post("index.php?module=bloqueio&tmp=1", {medico_id: medico_id, data: data, hora_inicio: hora_inicio, hora_termino: hora_termino, bloquear: bloquear}, function(result) {
			$("#result-bloqueio").html(result);
			carregaAgenda();
		});
	});
});

function desbloqueio_agendamento() {

	var desbloqueio = "desloqueio";

	$.post("index.php?module=bloqueio&tmp=1", {desbloqueio: desbloqueio}, function(result) {
		$("#form-desbloqueio").html(result);
		abrir_modal('boxes-acr-bloqueio')

	});
}

