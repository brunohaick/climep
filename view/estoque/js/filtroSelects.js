function buscaLotes() {
	var id = $('#material-id').val();
	$.post("index.php?module=filtro_select_tv&tmp=1",{ id: id },
	function(data) {
	$("#tv-lote").html(data);
	});
}
function buscaLotesFichaVacina() {
	var id = $('#vaci-vacina').val();
	$.post("index.php?module=filtro_select_tv&tmp=1",{ id: id },
	function(data) {
	$("#fv-lote").html(data);
	});
}
function buscaValidadeLote() {
	var lote_id = $('#lote-id').val();
	$.post("index.php?module=filtro_select_tv&tmp=1",{ lote_id: lote_id },
	function(data) {
	$("#tv-validade").html(data);
	});
}
