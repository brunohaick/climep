/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/*
$(document).ready(function(){
    $('tr:even[name=table-color]').css('background','#D3D6FF');
    $('tr:odd[name=table-color]').css('background','#FFF');
});
*/

function goToEntrada2(){
	$("div#entrada1").hide("slide", function(){
		$("div#entrada1").css("display","none");
		$("div#entrada2").css("display","block");
	});
}

function goToEntrada1(){
	$("div#entrada2").hide("slide", function(){
		$("div#entrada2").css("display","none");
		$("div#entrada1").css("display","block");
	});
}

/**
 * Função para buscar Codigo do fornecedor selecionado
 * @author  Bruno Haick
 */
function buscaCodFornecedor() {

    var id = $("#nf-fornecedor-large option:selected").val();

	var tipoForm = "busca_codigo_fornecedor";
    $.post("index.php?module=entradanf&tmp=1",{
		id:id,
		tipoForm:tipoForm
	},function(result){
        $("input#nf-fornecedor").val(result);
    });

}

function buscaFornecedor() {

    codigo = $("input#nf-fornecedor").val();

	var tipoForm = "busca_fornecedor_por_codigo";
    $.post("index.php?module=entradanf&tmp=1",{codigo:codigo,tipoForm:tipoForm},function(result){
		$("#nf-fornecedor-large option[value='" + result + "']").prop('selected', true);
    },"json");

}


/**
 * Função para buscar Codigo do fornecedor selecionado
 * @author  Bruno Haick
 */
function buscaCodFornecedor2() {

    id = $("#hist-entrada-fornecedor-select option:selected").val();

	if(id == '') {
		$("#hist-entrada-fornecedor").val('');
		return false;
	} else if(id == 0) {
		$("#hist-entrada-fornecedor").val('00');
		return false;
	}

	var tipoForm = "busca_codigo_fornecedor";
	$.post("index.php?module=entradanf&tmp=1",{id:id,tipoForm:tipoForm},function(result){
		$("#hist-entrada-fornecedor").val(result);
	});

}

function buscaFornecedor2(cnpj,id) {

    codigo = $("input#hist-entrada-fornecedor").val();

	if(codigo == 0) {
		result = 0;
		$("#hist-entrada-fornecedor-select option[value='" + result + "']").prop('selected', true);
		return false;
	}

	var tipoForm = "busca_fornecedor_por_codigo";
    $.post("index.php?module=entradanf&tmp=1",{codigo:codigo,tipoForm:tipoForm},function(result){
		$("#hist-entrada-fornecedor-select option[value='" + result + "']").prop('selected', true);
    },"json");

}

function validaEntradanfForm(){

	fornecedor = $("select#[name=nf-fornecedor-large]").val();
	goToEntrada2();
}

/**
 * Função para criar uma tabela representando as parecelas
 * da nota fiscal em com o valor informado.
 * @author Bruno Haick
 */
function processarDuplicatas() {

	var tipo = $('select:[name=nf-selc-tipo]').val();
	var data_venc = $('input:[name=nf-selc-vencto]').val();
	var num_parcelas = $('select:[name=nf-selc-parcelas]').val();
	var intervalo_parcelas = $('select:[name=nf-selc-intervalo]').val();
	var valor = $('input:[name=nf-valor]').val();
	var numero_doc = $('input:[name=nf-numero]').val();

	if(valor == "") {
		alert("Por Favor, Informe o Valor da primeira Parcela!");
		return false;
	} else if(data_venc == "") {
		alert("Por Favor, Informe a Data do Primeiro Vencimento!");
		return false;
	}

	var tipoForm = "busca_data_parcelas";

	$.post("index.php?module=entradanf&tmp=1",{tipoForm:tipoForm,num_parcelas:num_parcelas,intervalo_parcelas:intervalo_parcelas,data_venc:data_venc},function(result){
		var datas = result;
		var strhtml = '';
		var valor_parcela = '';
		var vencimento_parcela = '';

		for(var i = 0; i < num_parcelas; i++){

			valor_parcela = parseFloat(valor).toFixed(2) / parseFloat(num_parcelas);
			valor_parcela =  valor_parcela;

			vencimento_parcela = data_venc + (i * intervalo_parcelas);

			strhtml += '<tr name="tr-parcela-nf" >';
				strhtml += '<td align="center">';
					strhtml += numero_doc;
				strhtml += '</td>';
				strhtml += '<td align="center">';
					strhtml += i + 1;
				strhtml += '</td>';
				strhtml += '<td align="center">';
					strhtml += '<span ondblclick="editaDataParcelaNf($(this))">';
						strhtml += datas[i];
					strhtml += '</span>';
				strhtml += '</td>';
				strhtml += '<td align="right">';
					strhtml += '<span id="'+ i+1 +'" ondblclick="editaValorParcelaNf($(this))">';
						strhtml += valor;
					strhtml += '</span>';
				strhtml += '</td>';
			strhtml += '</tr>';
		}

		$('#tbody-nf-parcelas').html(strhtml);
	},"json");
	
}

function editaValorParcelaNf(field) {

	var text = $.trim(field.text());
	var id = field.attr('id');
	var inputbox = "<input id='"+id+"' onKeyPress=\"return(MascaraMoeda(this,'.',',',event))\" style='width: 150px;' type='text' onblur='editaValorParcelaNfBlur($(this))' value=\""+text+"\"/>";
	field.parent().html(inputbox);
	$("input#"+id).focus();
}

function editaValorParcelaNfBlur(field) {

	var text = field.val();
	var id = field.attr('id');
	if(text == ""){
		alert("Campo em branco!");
	} else {
		field.parent().html('<span id='+id+' onclick="editaValorParcelaNf($(this))">'+text+'</span>');
	}
}

function editaDataParcelaNf(field) {

	var text = $.trim(field.text());
	var id = field.attr('id');
	var inputbox = "<input id='"+id+"' data-mask='99/99/9999' style='width: 150px;' type='text' onblur='editaDataParcelaNfBlur($(this))' value=\""+text+"\"/>";
	field.parent().html(inputbox);
	$("input#"+id).focus();
}

function editaDataParcelaNfBlur(field) {

	var text = field.val();
	var id = field.attr('id');
	var inputbox = '<span id='+id+' ondblclick="editaDataParcelaNf($(this))">'+text+'</span>';
	if(text == ""){
		alert("Campo em branco!");
	} else {
		field.parent().html(inputbox);
	}
}

/**
 * Função para enviar o id do material por GET para o controle
 * e assim dar o detalhamento do estoque.
 * @author Marcus Dias
 */
function detalhamentoEstoque(id) {
	redirecionar('index.php?module=saldoestoque&id=' + id);
}

/**
 * Função para enviar o id do material, Data de inicio e Data Final 
 * por POST para o controle e assim trazer o historico
 * deste material no historico de estoque.
 * @author Marcus Dias
 * @date 15/10/2012
 */
function histEstoqueMaterial() {

    var id = $("#id-material option:selected").val();
	var dataInicio = $("#he-dataInicio").val();
	var dataFim = $("#he-dataFim").val();

    $.post("index.php?module=historicoestoque&tmp=1",{
		id:id,
		dataInicio:dataInicio,
		dataFim:dataFim
    },function(result){
        $("#conteudo").html(result);
    });
}

/**
 * @descrition Busca o preço do material e coloca ele no campo 
 * para inserir o material na nota fiscal de estoque.
 * 
 * @author Bruno Haick
 * @returns {undefined}
 */
function buscaPrecoMaterial() {

	var tipoForm = 'busca_preco_material';
	var idMat = $('#material-id option:selected').val();
    $.post("index.php?module=entradanf&tmp=1",{
		idMat:idMat,
		tipoForm:tipoForm
    },function(result) {
        $("#em-custo").val(result);
    },"json");
}


/**
 * Função para enviar o id do material, Data de inicio e Data Final 
 * além do radio marcado, por POST para o controle 
 * e assim trazer as ultimas entradas deste material.
 * @author Marcus Dias
 * @date 16/10/2012
 */
function ultEntradasMaterial() {

    var id = $("#ue-id-material option:selected").val();
	var selectOption = $("#ue-select-option option:selected").val();
    var option = $("[name='option']:checked").val();
	var dataInicio = $("#ue-dataInicio").val();
	var dataFim = $("#ue-dataFim").val();
	var soVacina = $("input[name='ue-checkbox']:checked").val();

	if(dataInicio == "") {
		alert('Preencha data de Inicio');
	} else if(dataFim == "") {
		alert('Preencha data Fim');
	}

    $.post("index.php?module=ultentradas&tmp=1",{
		id:id,
		selectOption:selectOption,
		option:option,
		soVacina:soVacina,
		dataInicio:dataInicio,
		dataFim:dataFim
    },function(result){
        $("#conteudo").html(result);
    });

}

/**
 * Função para marcar fluxo de vacinas caso o select
 * que tem as opçoes de fluxo seja mudado (onchange)
 * @author Marcus Dias
 * @date 16/10/2012
 */
function checkFluxo() {

	var id = $("#ue-id-material option:selected").val();
	var selectOption = $("#ue-select-option option:selected").val();
	var option = 'fluxo-vacinas';
	var dataInicio = $("#ue-dataInicio").val();
	var dataFim = $("#ue-dataFim").val();
	var soVacina = $("input[name='ue-checkbox']:checked").val();

	$.post("index.php?module=ultentradas&tmp=1",{
		id:id,
		selectOption:selectOption,
		option:option,
		soVacina:soVacina,
		dataInicio:dataInicio,
		dataFim:dataFim
	},function(result){
		$("#conteudo").html(result);
	});

}

/**
 * Função para imprimir pdf de mapa de consumo.
 * 
 * @author Bruno Haick
 * @date 05/01/2014
 */
function imprimeMapaConsumo() {
	window.open('index2.php?module=mapaconsumo','_blank');
}

/**
 * Função para enviar o id do material, Data de inicio e Data Final 
 * por POST para o controle e assim trazer o mapa deste tipo de
 * material no mapa de estoque.Além de servir como filtro de material
 * assim que ele é mudado
 * @author Marcus Dias
 * @date 17/10/2012
 */
function mapaConsumoMaterial() {

    var material = $("#mc-material-consumo option:selected").val();
	var dataInicio = $("#mc-dataInicio").val();
	var dataFim = $("#mc-dataFim").val();
	var flag = "consumo";

    $.post("index.php?module=mpconsumo&tmp=1",{
		material:material,
		dataInicio:dataInicio,
		dataFim:dataFim,
		flag:flag
    },function(result){
        $("#conteudo").html(result);
    });

}

/**
 * Função para enviar o id do material por GET para o controle
 * e assim dar o detalhamento do estoque.
 * @author Marcus Dias
 */
function detalhamentoDeLote(idMaterial,idLote) {
	var dataInicio = $("#dl-dataInicio").val();
	var dataFim = $("#dl-dataFim").val();

    $.post("index.php?module=detalhamentolote&tmp=1",{
		idMaterial:idMaterial,
		idLote:idLote,
		dataInicio:dataInicio,
		dataFim:dataFim,
		submit:'submit'
    },function(result){
        $("#conteudo").html(result);
    });
}

/**
 * Função para enviar o id do material, Data de inicio e Data Final 
 * por POST para o controle e assim trazer o mapa deste tipo de
 * material no mapa de estoque.Além de servir como filtro de material
 * assim que ele é mudado
 * @author Marcus Dias
 * @date 17/10/2012
 */
function detalhamentoLote() {

	var dataInicio = $("#dl-dataInicio").val();
	var dataFim = $("#dl-dataFim").val();

    $.post("index.php?module=detalhamentolote&tmp=1",{
		dataInicio:dataInicio,
		dataFim:dataFim
    },function(result){
        $("#conteudo").html(result);
    });

}

function histEntradaNF() {

	var fornecedor = $("#hist-entrada-fornecedor-select option:selected").val();
	var dataInicio = $("#hist-entrada-data-inicio").val();
	var dataFim = $("#hist-entrada-data-fim").val();
	var ordenado = $("#hist-entrada-ordenado-select").val();

	if(dataInicio == "") {
		alert('Preencha data de Inicio');
	} else if(dataFim == "") {
		alert('Preencha data Fim');
	}

    $.post("index.php?module=historicoentrada&tmp=1",{
		fornecedor:fornecedor,
		dataInicio:dataInicio,
		dataFim:dataFim,
		ordenado:ordenado,
		flag:'histentrada'
    },function(data){
				var html = "";

			if(data != null) {
				for(var i = 0; i < data.length; i++) {
					var rowId = parseInt($('#hiddenRowId').val());

					html += "<tr style='cursor:pointer' id='remove-"+ rowId+"' >";
						html += "<th align='center'><a onclick='removeNF(\""+ data[i]['id'] +"\",\""+ data[i]['nota_fiscal'] +"\",\""+ rowId +"\")' class='btn btn-danger btn-mini mrg-center'><i class='icon-remove icon-white'></i></a></th>";
						html += "<th onclick='detalhaNF("+data[i]['id']+");' align='center'>"+ data[i]['nota_fiscal'] +"</th>";
						html += "<th onclick='detalhaNF("+data[i]['id']+");' align='center'>"+ data[i]['fornecedor'] +"</th>";
						html += "<th onclick='detalhaNF("+data[i]['id']+");' align='center'>"+ data[i]['valor_nota_fiscal'] +"</th>";
						html += "<th onclick='detalhaNF("+data[i]['id']+");' align='center'>"+ data[i]['data_emissao'] +"</th>";
						html += "<th onclick='detalhaNF("+data[i]['id']+");' align='center'>"+ data[i]['data_entrada'] +"</th>";
						html += "<th onclick='detalhaNF("+data[i]['id']+");' align='center'>Almoxarifado</th>";
					html += "</tr>";

					rowId = parseInt($('#hiddenRowId').val()) + 1;
					$('input#hiddenRowId').val(rowId);
				}

				// limpando as tabelas
				$("tbody#notas-fiscais-digitadas").html(html);
				$("tbody#itens-nota-fiscal").html("");
				$("th#item-saldo-atual").html("");
				$("th#item-consumo").html("");
				$("th#item-media-mes").html("");
				abrir_modal("boxes-historicoentrada");
			} else {
				alert("Sem dados Disponiveis para esta pesquisa !");
			}
    },"json");

}

function removeNF(id_nf,notafiscal,row) {

	if(confirm("Tem certeza de que deseja excluir esta Nota Fiscal de Nº "+notafiscal+" ?")) {
		$.post("index.php?module=historicoentrada&tmp=1",{
			nota_fiscal:id_nf,
			flag:'removeNF'
		},function(data){

			if(data['resultado'] == 'sucesso'){
				$("tr#remove-"+ row).remove();

				//limpando as tabelas da mesma pagina.
				$("tbody#itens-nota-fiscal").html("");
				$("th#item-saldo-atual").html("");
				$("th#item-consumo").html("");
				$("th#item-media-mes").html("");
				
				alert('Sucesso! Nota Fiscal Removida !');
			} else {
				alert('ERRO !');
			}
		},"json");
	}

}

function detalhaNF(id_nf) {

    $.post("index.php?module=historicoentrada&tmp=1",{
		nota_fiscal:id_nf,
		flag:'detalhamentoNF'
    },function(data){
				var html = "";
			if(data != null) {
				for(var i = 0; i < data.length; i++) {
					html += "<tr name='table-color' style='cursor:pointer' class='dayhead' onclick='detalhaItem("+ data[i]['material_id'] +");'>";
						html += "<th align='center'>"+ data[i]['material_nome'] +"</th>";
						html += "<th align='center'>"+ data[i]['quantidade'] +" ML </th>";
						html += "<th align='center'>"+ data[i]['validade'] +"</th>";
						html += "<th align='center'>"+ data[i]['lote_nome'] +"</th>";
						html += "<th align='center'>"+ data[i]['valor_unit'] +"</th>";
						html += "<th align='center'>"+ data[i]['valor_unit'] +"</th>";
					html += "</tr>";
				}
			} else {
					html += "<tr name='table-color' style='cursor:pointer' class='dayhead'>";
						html += "<th align='center'></th>";
						html += "<th align='center'></th>";
						html += "<th align='center'></th>";
						html += "<th align='center'></th>";
						html += "<th align='center'></th>";
						html += "<th align='center'></th>";
					html += "</tr>";
			}

		$("tbody#itens-nota-fiscal").html(html);
		$("th#item-saldo-atual").html("");
		$("th#item-consumo").html("");
		$("th#item-media-mes").html("");
    },"json");

}
function detalhaItem(id_material) {

    $.post("index.php?module=historicoentrada&tmp=1",{
		material:id_material,
		flag:'detalhaItem'
    },function(data){
		$("th#item-saldo-atual").html(data['saldo']);
		$("th#item-consumo").html(data['consumo']);
		$("th#item-media-mes").html(data['media']);
    },"json");

}
