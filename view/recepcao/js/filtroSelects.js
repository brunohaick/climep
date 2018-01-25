function filtraConvenio() {
	var id_tabela = $('#proc-tiss-tabela_id').val();
	$.post("index.php?module=filtro_select_guiatiss&tmp=1",{ id_tabela : id_tabela },
	function(data) {
	$("#convenio").html(data);
	});
}
function filtraTabela() {
	var id_convenio = $('#proc-tiss-convenio_id').val();
	$.post("index.php?module=filtro_select_guiatiss&tmp=1",{ id_convenio : id_convenio },
	function(data) {
	$("#tabela").html(data);
	});
}
