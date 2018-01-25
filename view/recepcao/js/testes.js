function buscaClientes(tipo) {

	var idCliente = $("#cad-matricula").val();
	$.post("index.php?module=testesRecepcao&tmp=1",{
		flag:'buscaClientes',
		idTitular:idCliente
	},function(result){ 
		var html = "<select id='matricula-cliente-"+ tipo +"' onchange=\"carregaDadosCliente"+ tipo +"();\">";
			html += "<option></option>";
		for(var i=0; i < result.length; i++) {
			html += "<option value='"+ result[i]['id'] + "'> "+ result[i]['nome'] +" "+ result[i]['sobrenome'] +"</option>";
		}
		html += "</select>";

		$("#selectClientes-"+tipo).html(html);
	},"json");
}

function inserirConsultaOrelhinha1() {

	var flag = "inserirConsultaOrelhinha1";
	var idCliente = $("#matricula-cliente-auditivo").val();
	var O1ODTEOAE		=  $("#tst-od-teoae").val();
	var O1ODNOISE		=  $("#tst-od-noise").val();
	var O1ODFrequencia	=  $("#tst-OD-frequencia").val();
	var O1OETEOAE		=  $("#tst-od-teoae").val();
	var O1OENOISE		=  $("#tst-od-noise").val();
	var O1OEFrequencia	=  $("#tst-OE-frequencia").val();
	var O1MeatoOD		=  $("#tst-meato-OD").val();
	var O1MeatoOE		=  $("#tst-meato-OE").val();
	var O1Localizacao   =  $("#tst-localizacao").val();
	var O1Observacoes   =  $("#tst-observacoes").val();
	var O1Reavaliacao   =  $("#tst-em-data").val();

	$.post(
		'index.php?module=consultas&tmp=1',
		{idCliente:idCliente, flag: flag, O1ODTEOAE: O1ODTEOAE,O1ODNOISE:O1ODNOISE,O1ODFrequencia:O1ODFrequencia,O1OETEOAE: O1OETEOAE,O1OENOISE:O1OENOISE,O1OEFrequencia:O1OEFrequencia,O1MeatoOD:O1MeatoOD, O1MeatoOE: O1MeatoOE, O1Localizacao: O1Localizacao, O1Observacoes: O1Observacoes, O1Reavaliacao: O1Reavaliacao},
		function(data) {
			alert(data);
		}
	);
}

function inserirConsultaOlhinho() {
	var flag = "inserirConsultaOlhinho";
	var idCliente = $("#matricula-cliente-visual").val();
	var olhinhoResultadoOd  = $("#olhinho-resultado-od").val();
	var olhinhoResultadoOe  = $("#olhinho-resultado-oe").val();
	var olhinhoAnotacoesOp  = $("#olhinho-anotacoes-op").val();
	var olhinhoAnotacoesHf  = $("#olhinho-anotacoes-hf").val();
	var olhinhoAnotacoesOut = $("#olhinho-anotacoes-outros").val();
	var olhinhoAnotacoesObs = $("#olhinho-anotacoes-obs").val();
	var olhinhoRetesteData  = $("#tst-visual-data").val();
	var olhinhoRetesteOk		= $("input[name='olhinho-reteste-ok']:checked").val();
	var olhinhoSugFuncaoNormal =  $("input[name='olhinho-sug-funcao-normal']:checked").val();
	var olhinhoSugFuncaoAnormal =  $("input[name='olhinho-sug-funcao-anormal']:checked").val();

	$.post(
		'index.php?module=consultas&tmp=1',
		{olhinhoRetesteOk:olhinhoRetesteOk,olhinhoSugFuncaoNormal:olhinhoSugFuncaoNormal,olhinhoSugFuncaoAnormal: olhinhoSugFuncaoAnormal, idCliente: idCliente, flag: flag,olhinhoResultadoOd: olhinhoResultadoOd, olhinhoResultadoOe: olhinhoResultadoOe, olhinhoAnotacoesOp:olhinhoAnotacoesOp,olhinhoAnotacoesHf:olhinhoAnotacoesHf, olhinhoAnotacoesOut:olhinhoAnotacoesOut,olhinhoAnotacoesObs:olhinhoAnotacoesObs, olhinhoRetesteData:olhinhoRetesteData },
		function(data) {
			alert(data);
		}
	);
}

function carregaDadosClientevisual() {
	var idCliente = $("#matricula-cliente-visual").val();
	
	$.post("index.php?module=testesRecepcao&tmp=1",{
		flag:'buscarTesteVisual',
		idCliente:idCliente
	},function(result){ 
		if(result) {
			$("#tst-nascimento-visual").val(result['data-nascimento']);
			$("#peso-nascimento").val(result['peso_nascimento']);
			$("#idade-gestacional").val(result['idade_gestacional']);
			$("#apgar").val(result['apgar']);
			$("#con-gestacao option[value='" + result['gestacao'] + "']").prop('selected', true);
			$("#con-parto option[value='" + result['parto'] + "']").prop('selected', true);

			if(result['olhinho-existe'] == 1) {
				$("#olhinho-resultado-od option[value='" + result['olhinho-resultado-od'] + "']").prop('selected', true);
				$("#olhinho-resultado-oe option[value='" + result['olhinho-resultado-oe'] + "']").prop('selected', true);
				$("#olhinho-anotacoes-op").val(result['olhinho-anotacoes-op']);
				$("#olhinho-anotacoes-hf").val(result['olhinho-anotacoes-hf']);
				$("#olhinho-anotacoes-outros").val(result['olhinho-anotacoes-outros']);
				$("#olhinho-anotacoes-obs").val(result['olhinho-anotacoes-obs']);

				if(result['olhinho-reteste-data'] != '0000-00-00'){
					$("#tst-visual-data").val(result['olhinho-reteste-data']);
					$("#olhinho-reteste-ok").prop('checked',true);
				}

				if(result['olhinho-sug-funcao-normal'] == 1) {
					$("#olhinho-sug-funcao-normal").prop('checked',true);
				}
				if(result['olhinho-sug-funcao-anormal'] == 1) {
					$("#olhinho-sug-funcao-anormal").prop('checked',true);
				}
			}
		}
	},"json");
}
function carregaDadosClienteauditivo() {
	var idCliente = $("#matricula-cliente-auditivo").val();
	
	$.post("index.php?module=testesRecepcao&tmp=1",{
		flag:'buscarTesteAuditivo',
		idCliente:idCliente
	},function(result){ 
		if(result) {
		$("#tst-nascimento-auditivo").val(result['data-nascimento']);
		$("#tst-pediatra option[value='" + result['medico'] + "']").prop('selected', true);
			if(result['orelhinha1-existe'] == 1) {
			$("#tst-od-teoae").val(result['O1-OD-TEOAE']);
			$("#tst-od-noise").val(result['O1-OD-NOISE']);
			$("#tst-od-frequencia option[value='" + result['O1-OD-frequencia'] + "']").prop('selected', true);
			$("#tst-oe-teoae").val(result['O1-OE-TEOAE']);
			$("#tst-oe-noise").val(result['O1-OE-NOISE']);
			$("#tst-oe-frequencia option[value='" + result['O1-OE-frequencia'] + "']").prop('selected', true);
			$("#tst-meato-od option[value='" + result['O1-meato-OD'] + "']").prop('selected', true);
			$("#tst-meato-oe option[value='" + result['O1-meato-OE'] + "']").prop('selected', true);
			$("#tst-localizacao option[value='" + result['O1-localizacao'] + "']").prop('selected', true);
			$("#tst-observacoes").val(result['O1-observacoes']);
				if(result['O2-reavaliacao'] != '0000-00-00'){
					$("#tst-em-data").val(result['O1-reavaliacao']);
					$("#tst-check-reavaliacao").prop('checked',true);
				}
			}
		}
	},"json");
}
