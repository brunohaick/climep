function filtraConvenio() {
	var id_tabela = $('#arc-horario-tabela').val();
	$.post("index.php?module=filtro_select_agmedica&tmp=1",{ id_tabela : id_tabela },
	function(data) {
	$("#convenio").html(data);
	});
}
function filtraTabela() {
	var id_convenio = $('#arc-horario-convenio').val();
	$.post("index.php?module=filtro_select_agmedica&tmp=1",{ id_convenio : id_convenio },
	function(data) {
	$("#tabela").html(data);
	});
}
function filtraProcedimento() {
	var tabela_id = $('#arc-horario-tabela').val();
	var convenio_id = $('#arc-horario-convenio').val();
	$.post("index.php?module=filtro_select_agmedica&tmp=1",{ tabela_id:tabela_id,convenio_id:convenio_id },
	function(data) {
	$("#arc-horario-servicos").html(data);
	});
}
