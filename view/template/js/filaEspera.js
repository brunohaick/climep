$(document).ready(function () {

	var aSelected = [];
	oTable = $("#tabela_sc").dataTable({
		"bProcessing": true,
		"bRetrieve": true,
		"bDestroy": true,
		"bServerSide": true,
		"sAjaxSource": "index.php?module=fila_espera_vacina&at_fila=1&tmp=1"
	});


	$(document).on('dblclick', '#tabela_sc tbody tr', function (event) {
		var aPos = oTable.fnGetPosition(this);
		var aData = oTable.fnGetData(aPos);
		gIDNumber = aData['DT_RowId'];
//        $(this).attr("style","background-color: rgb(211, 214, 255)");
		//console.log("Numero d aFila::"+aData[0]);
		$.ajax({
			url: 'index.php?module=fila_espera_vacina&tmp=1',
			type: 'POST',
			async: false,
			data: {confirma_atendimento: true, filaId: aData[0]},
			success: function () {
			}});
		verificaTitular(gIDNumber);
	});

    function verificaTitular(matricula) {

		var matriculaTitular = matricula;
		var tipoForm = "verifica_titular"

		$.post("index.php?module=editar&tmp=1", {flag: tipoForm, matriculaTitular: matriculaTitular}, function(result) {

			//if (result == 0) {
			//	alert('Matricula Inexistente');
			//} else {
				document.location.href = "index.php?module=editar&matricula=" + matricula;
			//}
		}, "json");
	}

    $(document).on('hover', '#tabela_sc tbody tr', function (event) {
//        $(this).popover('toggle');
		var aPos = oTable.fnGetPosition(this);
		var aData = oTable.fnGetData(aPos);
//        console.log(aData);
		$(this).attr("rel", aData['rel']);
		$(this).attr("title", aData['title']);
		$(this).attr("data-content", aData['data-content']);
		gIDNumber = aData['DT_RowId'];
//        $(this).attr("style","background-color: rgb(212, 255, 0)");
	});
  
   

//    $('tr.pointer-cursor.usuarioSelecionavel').dblclick(function() {
//        if ($(this).children('#Atendeu').html() === '' && $(this).parent().attr('usuarioTipo') === 'medico')
//            $.ajax({
//                url: 'index.php?module=fila_espera_vacina',
//                type: 'POST',
//                async: false,
//                data: {confirma_atendimento: true, filaId: $(this).children('#idDaFilaDeEspera').html()},
//                success: function() {
//                    document.location.reload();
//                }
//            });
//        document.location.href = "index.php?module=editar&matricula=" + $(this).attr('clienteId');
//    });
   
});

setInterval(function() {
    $("#tabela_sc").dataTable().fnDraw();
    
//    $('#tabela_sc tbody tr').each( function() {this.setAttribute('title','titulo')});
    
//    oTable.$('tr').tooltip( {
//        "delay": 0,
//        "track": true,
//        "fade": 250
//    } );
//                $("#tabela_sc").dataTable().fnDraw();

}, 1000000);


//function fnGetSelected( oTableLocal )
//{
//    return $("#tabela_sc").$('tr.row_selected');
//}

