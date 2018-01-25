/**
 * Edicao de Membro. Formulario Vacina.
 * Após o formulário ser submetido, envia-se todos os dados contidos para um
 * scrpit em php para motar a query sql de edicao de dados no banco.
 *
 * @author Marcus Dias
 * @date Criação: 16/09/2012
 *
 * @param
 *
 * @return
 *	True em caso de sucesso e False em caso de Falha.
 */
$(document).ready(function() {
	$("input#vaci-gravar").click(function() {

		var condicoesnascimento = new Array();
		$("input[name='vaci-cnascimento']:checked").each(function(i) {
			condicoesnascimento.push($(this).val());
		});

		var alergias = new Array();
		$("input[name='vaci-alergias']:checked").each(function(i) {
			alergias.push($(this).val());
		});

		var antmorbido = new Array();
		$("input[name='vaci-antecedentes']:checked").each(function(i) {
			antmorbido.push($(this).val());
		});
		var obs = $("#vaci-obs").val();
		var imunodeficiente = $('input:checkbox[name=vaci-imunodeficiente]:checked').val();
		var gravar = $("#vaci-gravar").val();
		var parto = $("#vaci-parto").val();
		var gestacao = $("#vaci-gestacao").val();
		var peso = $("#vaci-peso").val();
		var id = $("#hidden-id").val();
		
		var categoria_id = $("select[name='vaci_categoria_id'] option:selected").val();
		var data_nascimento = $("#vaci-nasc").val();

		$.post("index.php?module=vacinaEditar&tmp=1", {
			id: id, 
			antmorbido: antmorbido, 
			alergias: alergias, 
			obs: obs, 
			imunodeficiente: imunodeficiente, 
			parto: parto, 
			gestacao: gestacao, 
			peso: peso, 
			condicoesnascimento: condicoesnascimento,
			categoria_id: categoria_id,
			data_nascimento: data_nascimento,
			gravar: gravar
		}, function(result) {
			console.log("result == " + result);
			if(result == "1") {
				alert("A Atulização foi feita com Sucesso.");
//				abaVacina(id, idTitular);
			} else {
				alert("Ocorreu um erro ao atualizar os dados do Cliente.");
			}
		});
	});
//    $('tbody tr td input#intemModCheckBox').click(function() {
//	var $this = $(this);
//    });

	$('tbody tr td span#data_item').click(function data_item_click() {
		var $this = $(this);
		var text = $.trim($this.text());
		var $input = $('<input/>')
			.attr('data-mask', '99/99/9999')
			.attr('type', 'text')
			.attr('onblur', 'dateComplete($(this));')
			.attr('id', 'data_item')
			.css('width', '100px')
			.val(text)
			.blur(function() {
			var span = $('<span/>')
				.attr('id', 'data_item');
				span.html($input.val());
				span.click(data_item_click);
				$input.parent().html(span);
		});
		$this.parent().html($input);
	});

	$('fieldset.vaci-servicos input#vaci-servicos-enviar').click(function() {
		$('table#tabelaDeItensDoModulo tbody tr').each(function(index) {
			var $this = $(this);
			var nomeDoMaterial = $this.children('#itemModuloNomeMaterial').html();
			var data = $this.children('#itemModuloData').children().html();
			if (data === '') {
				data = $this.children('#itemModuloData').children().val();
			}
			var clientID = $this.attr('clienteID');
			var moduloId = $this.attr('moduloId');
			var itemId = $this.attr('itemId');
			$.ajax({
			url: 'index.php?module=vacina&tmp&isModulo=' + moduloId,
			type: 'POST',
			data: {tipoForm: 'insere_vacina_cliente', nomeVac: nomeDoMaterial, idClienteVacina: clientID, data: data, itemId: itemId},
			complete: function() {
				abaVacina(clientID, $("#cad-matricula").val());
				if($this.attr('comprimento') - 1 == index) {
					abaVacina(clientID, $("#cad-matricula").val());
				}
			}
			});//listar os serivos do cliente q não possuem controle por idmaterial do tipo modulo
		});
    });
});

/**
 * @author Bruno Haick
 * @date Criação: 22/11/2012
 *
 */
function listaVacinaAno(ano) {

    var id;
    var i;

//	for(i = 0; i < 10; i++) {
//		id = i + "-" + ano;
    $("#" + ano).css("display", "block");
//		alert("#"+id);
//	}
}

/**
 * Vacina
 *	Funcao utilizada para abrir o modal de vacinas
 *	enviando para o script o id do titular.
 *
 * @author Bruno Haick
 * @date Criação: 12/09/2012
 *
 */
function telaVacina() {
    var idTitular = $("#cad-matricula").val();
    var membroVacina = 1;

    if (idTitular == '') {
		alert("Escolha um usuário válido.");
    } else {
		$.post("index.php?module=vacina&tmp=1", {idTitular: idTitular, membroVacina: membroVacina}, function(result) {
			$("div#ficha-vacina").html(result);

			abrir_modal('ficha-vacina');
		});
    }
}

function imprimirFichaVacina(id) {

    var idClienteVacina = id;
    var tipoForm = "imprimir_fichavacina"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idClienteVacina: idClienteVacina}, function(result) {
	window.open('index2.php?module=fichavacina', '_blank', status = 'no', width = '125', height = '100')
    });

}

function modalImprimirControleVacina(idCliente) {

    buscaVacPorClienteIdControle();

    abrir_modal('boxes-controle-novo');
}

function modalReimprimirControleVacina(idCliente) {

    $("tbody#tbody-reimprimir-controle").html("");

    var idTitular = $("#cad-matricula").val();
    var tipoForm = "busca_controles"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idTitular: idTitular}, function(result) {

	html = "<option></option>"
	if (result != null)
	    for (var i = 0; i < result.length; i++) {

		var str = result[i]['numero_controle'] + ", " + result[i]['data2'] + " " + result[i]['hora'] + " (há " + result[i]['strdata'] + " dias)" + result[i]['strfin'];

		html += "<option value='" + result[i]['id'] + "'>";
		html += str;
		html += "</option>";
	    }
	$("select#reimprimir-controle-controles").html(html);

    }, "json");

    abrir_modal('boxes-controle-reimprimir');
}

function modalResultadosTestes(tipo, unidade, idHistorico, nome, idCliente) {

    var html = "";
    var html2 = "";

    if (tipo == 1) {
	html += "<label>Informe o resultado do teste:</label>";
	html += "<input type='text' name='resultado_teste_modal_valor' placeholder='Digite o valor'/>";
    } else if (tipo == 2) {
	html += "<label>Informe o resultado do teste:</label>";
	html += "<input type='text' name='resultado_teste_modal_valor' placeholder='Digite o valor'/>";
    } else if (tipo == 3) {
	html += "<label>Informe o resultado do teste:</label>";
	html += "<select name='resultado_teste_modal_valor_select'>"
	html += "<option value='Normal'>Normal</option>"
	html += "<option value='Alterado'>Alterado</option>"
	html += "</select>"
    } else if (tipo == 4) {

	$('input:[name=resultado_valor_tca_candidina]').val("");
	$('input:[name=resultado_valor_tca_ecoli]').val("");
	$('input:[name=resultado_valor_tca_tricofitina]').val("");
	$('input:[name=resultado_valor_tca_ppd]').val("");
	$('input:[name=resultado_dataleit_tca_candidina]').val("");
	$('input:[name=resultado_dataleit_tca_ecoli]').val("");
	$('input:[name=resultado_dataleit_tca_tricofitina]').val("");
	$('input:[name=resultado_dataleit_tca_ppd]').val("");

	html2 = "<a href='#' onclick=\"gravarResultadoTeste('" + tipo + "','" + unidade + "','" + nome + "','" + idHistorico + "','" + idCliente + "')\" class='btn'>Salvar</a>"
	html2 += "<a href='#' onclick='fechar_modal(\"boxe-resultadoteste_tca\")' class='btn'> Fechar</a>";
	$("div#resultado_teste_vacina_botao_tca").html(html2);

	abrir_modal('boxe-resultadoteste_tca');
	return false;
    }

    html2 = "<a href='#' onclick=\"gravarResultadoTeste('" + tipo + "','" + unidade + "','" + nome + "','" + idHistorico + "','" + idCliente + "')\" class='btn'>Salvar</a>"
    html2 += "<a href='#' onclick='fechar_modal(\"boxe-resultadoteste\")' class='btn'> Fechar</a>";

    $("div#resultado_teste_vacina_modal").html(html);
    $("div#resultado_teste_vacina_botao").html(html2);

    abrir_modal('boxe-resultadoteste');

}

function gravarResultadoTeste(tipo, unidade, nome, idHistorico, idCliente) {

    var idTitular = $("#cad-matricula").val();

    if (tipo == 1 || tipo == 2) {
		var name = nome;
		var valor = $('input:[name=resultado_teste_modal_valor]').val();
		$('input:[name=resultado_teste_modal_valor]').val("");
    } else if (tipo == 3) {
		var valor = $('select:[name=resultado_teste_modal_valor_select]').val();
		$('select:[name=resultado_teste_modal_valor_select]').val("");
		var name = nome;
    } else if (tipo == 4) {
		var name = Array();
		var valor = Array();
		var dataLeit = Array();

		name[0] = 'candidina';
		name[1] = 'ecoli';
		name[2] = 'tricofitina';
		name[3] = 'ppd';

		valor[0] = $('input:[name=resultado_valor_tca_candidina]').val();
		valor[1] = $('input:[name=resultado_valor_tca_ecoli]').val();
		valor[2] = $('input:[name=resultado_valor_tca_tricofitina]').val();
		valor[3] = $('input:[name=resultado_valor_tca_ppd]').val();

		dataLeit[0] = $('input:[name=resultado_dataleit_tca_candidina]').val();
		dataLeit[1] = $('input:[name=resultado_dataleit_tca_ecoli]').val();
		dataLeit[2] = $('input:[name=resultado_dataleit_tca_tricofitina]').val();
		dataLeit[3] = $('input:[name=resultado_dataleit_tca_ppd]').val();

		if (dataLeit[0] == '') {
			alert('Preencha a data de Leitura de Candidina');
			return false;
		} else if (dataLeit[1] == '') {
			alert('Preencha a data de Leitura de E. Coli');
			return false;
		} else if (dataLeit[2] == '') {
			alert('Preencha a data de Leitura de Tricofitina');
			return false;
		} else if (dataLeit[3] == '') {
			alert('Preencha a data de Leitura de PPD');
			return false;
		}

		if (valor[0] == '') {
			alert('Preencha o resultado de Candidina');
			return false;
		} else if (valor[1] == '') {
			alert('Preencha o resultado de E. Coli');
			return false;
		} else if (valor[2] == '') {
			alert('Preencha o resultado de Tricofitina');
			return false;
		} else if (valor[3] == '') {
			alert('Preencha o resultado de PPD');
			return false;
		}

		$('input:[name=resultado_valor_tca_candidina]').val("");
		$('input:[name=resultado_valor_tca_ecoli]').val("");
		$('input:[name=resultado_valor_tca_tricofitina]').val("");
		$('input:[name=resultado_valor_tca_ppd]').val("");
		$('input:[name=resultado_dataleit_tca_candidina]').val("");
		$('input:[name=resultado_dataleit_tca_ecoli]').val("");
		$('input:[name=resultado_dataleit_tca_tricofitina]').val("");
		$('input:[name=resultado_dataleit_tca_ppd]').val("");
    }
    var tipoForm = "gravar_resultado_teste"

    $.post("index.php?module=vacina&tmp=1", 
	{tipoForm: tipoForm, tipo: tipo, valor: valor, unidade: unidade, nome: name, idHistorico: idHistorico, dataLeit: dataLeit}, function(result) {
		fechar_modal('boxe-resultadoteste_tca');
		fechar_modal('boxe-resultadoteste');
		abaVacina(idCliente, idTitular);
    });
}

function modal_ResultadoTcaEditar(idHistorico, idCliente) {

    var tipoForm = "busca_dados_resultado_tca"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idHistorico: idHistorico, idCliente: idCliente}, function(result) {

	$('input:[name=resultado_valor_tca_candidina]').val(result['teste'][0]['valor']);
	$('input:[name=resultado_valor_tca_ecoli]').val(result['teste'][1]['valor']);
	$('input:[name=resultado_valor_tca_tricofitina]').val(result['teste'][2]['valor']);
	$('input:[name=resultado_valor_tca_ppd]').val(result['teste'][3]['valor']);
	$('input:[name=resultado_dataleit_tca_candidina]').val(result['teste'][0]['data']);
	$('input:[name=resultado_dataleit_tca_ecoli]').val(result['teste'][1]['data']);
	$('input:[name=resultado_dataleit_tca_tricofitina]').val(result['teste'][2]['data']);
	$('input:[name=resultado_dataleit_tca_ppd]').val(result['teste'][3]['data']);

	$('input:[name=resultado_valor_tca_candidina]').attr("disabled", true);
	$('input:[name=resultado_valor_tca_ecoli]').attr("disabled", true);
	$('input:[name=resultado_valor_tca_tricofitina]').attr("disabled", true);
	$('input:[name=resultado_valor_tca_ppd]').attr("disabled", true);
	$('input:[name=resultado_dataleit_tca_candidina]').attr("disabled", true);
	$('input:[name=resultado_dataleit_tca_ecoli]').attr("disabled", true);
	$('input:[name=resultado_dataleit_tca_tricofitina]').attr("disabled", true);
	$('input:[name=resultado_dataleit_tca_ppd]').attr("disabled", true);
    }, "json");

    var html2 = "<a href='#' onclick=\"imprimirLaudoTCA('" + idHistorico + "', '" + idCliente + "')\" class='btn'>Imprimir</a>"
    html2 += "<a href='#' onclick='fechar_modal(\"boxe-resultadoteste_tca\")' class='btn'> Fechar</a>";
    $("div#resultado_teste_vacina_botao_tca").html(html2);

    abrir_modal('boxe-resultadoteste_tca');
}

function imprimirLaudoTCA(idHistorico, idCliente) {

    var tipoForm = "imprimir_laudo_tca_imunidade"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idCliente: idCliente, idHistorico: idHistorico}, function(result) {
	//$('input:[name=resultado_valor_tca_candidina]').val("");
	//$('input:[name=resultado_valor_tca_ecoli]').val("");
	//$('input:[name=resultado_valor_tca_tricofitina]').val("");
	//$('input:[name=resultado_valor_tca_ppd]').val("");
	//$('input:[name=resultado_dataleit_tca_candidina]').val("");
	//$('input:[name=resultado_dataleit_tca_ecoli]').val("");
	//$('input:[name=resultado_dataleit_tca_tricofitina]').val("");
	//$('input:[name=resultado_dataleit_tca_ppd]').val("");
	window.open('index2.php?module=laudotca', '_blank');
    });
}

function modalImprimirLaudoImunodeficiencia(idCliente) {

    var idHist = $("input[name='hidden_idHistorico_imu']").val();

    if (idHist == null) {
		alert('Cliente não possui Imunodeficiência');
		return false;
    }

    var html = "<a href='#' onclick=\"imprimirLaudoImu('" + idCliente + "','" + idHist + "')\" class='btn'>Imprimir</a>"
    html += "<a href='#' onclick='fechar_modal(\"boxe-data_imprime_imuno\")' class='btn'> Fechar</a>";
    $("div#resultado_laudo_imu_botao").html(html);

    abrir_modal('boxe-data_imprime_imuno');
}


function imprimirLaudoImu(idCliente, idHist) {

    var data = $("input[name='data_laudo_imu']").val();
    var idTitular = $("#cad-matricula").val();
    var tipoForm = "imprimir_laudo_imunodeficiencia"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idCliente: idCliente, idTitular: idTitular, idHist: idHist, data: data}, function(result) {
	$("input[name='data_laudo_imu']").val("");
	window.open('index2.php?module=imunodeficiencia', '_blank');
	fechar_modal('boxe-data_imprime_imuno');
    });
}

function modalImprimirCertificadoVacina(idCliente) {

    $("select#cert-vac-membro option[value='" + idCliente + "']").prop('selected', true);
    buscaVacPorClienteId(idCliente);
    var html = '<input onclick="imprimirCertificadoVacina()" class="btn btn-mini span10" type="button" name="" value="Imprimir" />'
    $("div#botao_imprimir").html(html);

    abrir_modal('boxes-certificado-imu');
}

function modalImprimirDeclaracaoComparecimento(idCliente, membro) {

    $("select[name='comparecimento_cliente'] option[value='" + idCliente + "']").prop('selected', true)
    $("select[name='comparecimento_acompanhante']").val('');

    var nomeCliente = $("select[name='comparecimento_cliente'] option:selected").text();
    var html = "<h3>" + membro + " - " + nomeCliente + "</h3>";

    $("div#comparecimento_cliente_nome").html(html);

    abrir_modal('boxe-comparecimento');
}

function imprimirCertificadoVacina() {

    var id = $('select:[name=cert-vac-membro]').val();
    var language = $('input:[name=language]:checked').val();

    var idTitular = $("#cad-matricula").val();
    var tipoForm = "imprimir_certificadovacina"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, language: language, idClienteVacina: id, idTitular: idTitular}, function(result) {
	window.open('index2.php?module=certificadovacina', '_blank');
    });
}

function reimprimirControle() {

    var idGuiaControle = $("#reimprimir-controle-controles").val();
    var idTitular = $("#cad-matricula").val();

    var tipoForm = "reimprimir_controle"
	$.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idTitular: idTitular, idGuiaControle: idGuiaControle}, function(result) {
		window.open('index2.php?module=controlenovo', '_blank');
    });
}

function imprimirControleNovo() {

    var idTitular = $("#cad-matricula").val();
    var idControle = $('select:[name=select_controle_ctrl]').val();
    var progs = new Array();

    $("input[type=checkbox][name='materialControle[]']:checked").each(function() {
		var progstmp = new Array();
		progstmp.push($(this).attr('material_id'));
		progstmp.push($(this).attr('cliente_id'));
		progstmp.push($(this).attr('servico_id'));
		progs.push(progstmp);
    });

	var hoje = new Array();
    $("input[type=checkbox][name='materialControleHoje[]']:checked").each(function() {
		var hojetmp = new Array();
		hojetmp.push($(this).attr('material_id'));
		hojetmp.push($(this).attr('cliente_id'));
		hojetmp.push($(this).attr('servico_id'));
		hoje.push(hojetmp);
    });

    var modulos = new Array();
    $("input[type=checkbox][name='modulos_controle[]']:checked").each(function() {
		modulos.push($(this).val());
    });

    var tipoForm = "imprimir_controlenovo"
    $.post("index.php?module=vacina&tmp=1", {
		tipoForm: tipoForm, progs:progs, hoje:hoje, idTitular:idTitular, idControle:idControle, modulos:modulos
	}, function(result) {
		window.open('index2.php?module=controlenovo', '_blank');
    });
}

function imprimirDeclaracaoComparecimento() {

    var idClienteDec = $("select[name='comparecimento_cliente'] option:selected").val();
    var idAcompanhante = $("select[name='comparecimento_acompanhante'] option:selected").val();
    var nomeClienteDec = $("select[name='comparecimento_cliente'] option:selected").text();
    var nomeAcompanhante = $("select[name='comparecimento_acompanhante'] option:selected").text();

    var idTitular = $("#cad-matricula").val();
    var tipoForm = "imprimir_declaracao_comparecimento"

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, idClienteDec: idClienteDec, idAcompanhante: idAcompanhante, nomeClienteDec: nomeClienteDec, nomeAcompanhante: nomeAcompanhante}, function(result) {
	//window.open('index2.php?module=declaracao','_blank');
	AbrePopUp('index2.php?module=declaracao', 'CLIMEP', 800, 600, 'yes', 'yes', 'yes');
    });

}
/**
 *
 * Busca Vac Por Cliente
 *
 * @author Bruno Haick
 * @date Criação: 19/12/2013
 *
 * Busca as vacinas para o controle interno
 *
 */
function buscaVacPorClienteIdControle() {

	var idtitular = $("#cad-matricula").val();        
	$.post("index.php?module=vacina&tmp=1", {idcliente: idtitular, tipoForm: 'vacina_busca_controle'}, function(result) {

		var html = "";
		var prog = result.prog;
		var hoje = result.hoje;
		var modulos = result.modulos;
		var controle = result.controle;

		if (result.prog != null) {
			for (var i = 0; i < prog.length; i++) {

				idMaterial = prog[i]['idmaterial'];
				idCliente = prog[i]['idCliente'];
				idServico = prog[i]['idServico'];

				html += "<tr name='table-color' class='dayhead'>";
					html += "<td class='line-table' align='center'>";
						html += prog[i]['membro'];
					html += "</td>";
					html += "<td class='line-table'>";
						html += prog[i]['cliente'];
					html += "</td>";
					html += "<td class='line-table'>";
						html += prog[i]['material'];
					html += "</td>";
					html += "<td  class='line-table' align='center'>";
						html += "<input type='checkbox' servico_id='" + idServico + "' material_id='" + idMaterial + "' cliente_id='" + idCliente + "' name='materialControle[]' value='" + idMaterial + "/" + idCliente + "'>";
					html += "</td>";
				html += "</tr>";
			}
		}
		$("tbody#controle-vac-vacs-programados").html(html);

		html = "";
		if (result.hoje != null) {
			for (var i = 0; i < hoje.length; i++) {

				idMaterial = hoje[i]['idmaterial'];
				idCliente = hoje[i]['idCliente'];
				idServico = hoje[i]['idServico'];

				if(hoje[i]['modulo'] == 0) {
					var str = "checked='checked'";
				} else if(hoje[i]['modulo'] == 1) {
					var str = "disabled='disabled'"; checked='checked'
				}

				html += "<tr name='table-color' class='dayhead'>";
					html += "<td class='line-table' align='center'>";
					html += hoje[i]['membro'];
					html += "</td>";
					html += "<td class='line-table'>";
					html += hoje[i]['cliente'];
					html += "</td>";
					html += "<td class='line-table'>";
					html += hoje[i]['material'];
					html += "</td>";
					html += "<td class='line-table' align='center'>";
					html += "<input type='checkbox' servico_id='" + idServico + "' material_id='" + idMaterial + "' cliente_id='" + idCliente + "' name='materialControleHoje[]' " + str + " value='" + idMaterial + "/" + idCliente + "'>";
					html += "</td>";
				html += "</tr>";
			}
		}
		$("tbody#controle-vac-vacs-hoje").html(html);

		html = "";
		console.log(result.modulos);
		if (result.modulos != null) {
			for (var i = 0; i < modulos.length; i++) {

				html += '<li class=\'line-hover thumbnails\'>';
					html += '<label class=\'ClassDependenteModulos\' clienteid=\'' + modulos[i]['cliente_id'] + '\'>';
						html += '<input class=\'controle-Imunoterapia-modulos\' name=\'modulos_controle[]\' type=\'checkbox\' value=\'' + modulos[i]['id'] + '\'>';
						html += modulos[i]['preco'] + ' - ' + modulos[i]['nome'];
					html += '</label>';
				html += '</li>';
			}
		}
		$("ul#dependenteModulos").html(html);

		html = "<option value='0'>Novo Controle</option>"
		if (result.controle != null)
			for (var i = 0; i < controle.length; i++) {

			idControle = controle[i]['id'];
			numeroControle = controle[i]['numero_controle'];

			html += "<option value='" + idControle + "'>";
			html += "Controle Nº " + numeroControle;
			html += "</option>";
			}
		$("select#select_controle_ctrl").html(html);
    }, "json");
}


/**
 *
 * Busca Vac Por Cliente
 *
 * @author Bruno Haick
 * @date Criação: 26/6/2013
 *
 * Busca as imunoterapias do cliente;
 *
 */
function buscaVacPorClienteId(id) {

    var idcliente = id;

    $.post("index.php?module=vacina&tmp=1", {idcliente: idcliente, tipoForm: 'vacina_busca'}, function(result) {

	var html = "";
	var stat = "";

	if (result != null)
	    for (var i = 0; i < result.length; i++) {
		if (result[i]['status_nome'] == 'Programado') {

		} else {

		    if (result[i]['status_nome'] == 'Externo')
			stat = 'Externo';

		    html += "<tr name='table-color' class='dayhead'>";
		    html += "<td class='line-table'>";
		    html += result[i]['data_prevista'];
		    html += "</td>";
		    html += "<td class='line-table'>";
		    html += result[i]['vacinaNome'];
		    html += "</td>";
		    html += "<td class='line-table'>";
		    html += "";
		    html += "</td>";
		    html += "<td class='line-table'>";
		    html += stat;
		    html += "</td>";
		    html += "</tr>";
		    stat = "";
		}
	    }

	$("tbody#cert-vac-vacs").html(html);
    }, "json");
}

function buscaDadosControle() {

    var idControle = $('select:[name=reimprimir-controle-controles]').val();

    $.post("index.php?module=vacina&tmp=1", {idControle: idControle, tipoForm: 'controle_busca'}, function(result) {

	var html = "";

	if (result != null)
	    for (var i = 0; i < result.length; i++) {
		html += "<tr>";
			html += "<td class='line-table'>";
			html += result[i]['membro'];
			html += "</td>";
			html += "<td class='line-table'>";
			html += result[i]['nome'];
			html += "</td>";
			html += "<td class='line-table'>";
			html += result[i]['material'];
			html += "</td>";
			html += "<td class='line-table'>";
			html += result[i]['tipo'];
			html += "</td>";
		html += "</tr>";
	    }

	$("tbody#tbody-reimprimir-controle").html(html);
    }, "json");
}

/**
 * abaVacina
 *
 *	Funcao utilizada para recarregar o modal de vacinas
 *	quando for escolhido outro usuario, dependente ou titular
 *	quando o modal já estiver aberto. Enviando o id do Cliente
 *	e do Titular para o controller.
 *
 *
 * @author Bruno Haick
 * @date Criação: 12/09/2012
 *
 */

function abaVacina(id, idTitular) {

    var idTitular = idTitular;
    var idClienteVacina = id;

    $.post("index.php?module=vacina&tmp=1", {idTitular: idTitular, idClienteVacina: idClienteVacina}, function(result) {
	$("#ficha-vacina").html(result);
    });
}

/**
 * insere_vac_cliente
 *
 *	Funcao utilizada para inserir uma vacina para um cliente
 *	depois de inserir a vacina chama a funcao abaVacina para
 *	atualizar a ficha de vacina.
 *
 * @author Bruno Haick
 * @date Criação: 25/01/2013
 *
 */

function insere_vac_cliente(idCliente, nomeVac) {

    var idTitular = $("#cad-matricula").val();
    var tipoForm = "insere_vacina_cliente";

    $.post("index.php?module=vacina&tmp=1", {
		idClienteVacina: idCliente,
		nomeVac: nomeVac,
		tipoForm: tipoForm
	}, function(result) {
		abaVacina(idCliente, idTitular);
	//$("input#hidden-idClienteAba").val("");
    });
}

/**
 * edita_vac_cliente
 *
 *	Funcao utilizada para editar a data de uma vacina de um cliente
 *	depois de editar a vacina chama a funcao abaVacina para
 *	atualizar a ficha de vacina.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */

function edita_data_vac_cliente(idCliente, idHistorico) {

    var idTitular = $("#cad-matricula").val();
    var data = $('input#data_edicao_vacina').val();
//    var data = $('input#data_' + idHistorico).val();

    var tipoForm = "edita_data_vacina_cliente";
    $.post("index.php?module=vacina&tmp=1", {idHistorico: idHistorico, data: data, tipoForm: tipoForm}, function(result) {
	if (result == 1) {
	    alert('Você não tem permissão para esta ação.');
	    return false;
	}

    }, "json");

    fechar_modal('boxe-datavacina');
    abaVacina(idCliente, idTitular);
}

/**
 * edita_vac_cliente
 *
 *	Funcao utilizada para editar uma vacina de um cliente
 *	depois de editar a vacina chama a funcao abaVacina para
 *	atualizar a ficha de vacina.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */

function edita_vac_cliente(idCliente, idHistorico, nomeStatus) {

    var idTitular = $("#cad-matricula").val();
    var tipoForm = "edita_vacina_cliente";

    $.post("index.php?module=vacina&tmp=1", {idHistorico: idHistorico, idClienteVacina: idCliente, nomeStatus: nomeStatus, tipoForm: tipoForm}, function(result) {

	if (result == 1) {
	    alert('Você não tem permissão para esta ação.');
	    return false;
	}
	abaVacina(idCliente, idTitular);
    }, "json");
}

/**
 * exclui_vac_cliente
 *
 *	Funcao utilizada para excluir uma vacina de um cliente
 *	depois de excluir a vacina chama a funcao abaVacina para
 *	atualizar a ficha de vacina.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */

function exclui_vac_cliente(idCliente, idHistorico, idServico) {

    if (!confirm("Tem certeza de que deseja excluir este historico de Vacina ?"))
	return false;

    var idTitular = $("#cad-matricula").val();
    var tipoForm = "exclui_vacina_cliente";

    $.post("index.php?module=vacina&tmp=1", {idHistorico: idHistorico, idClienteVacina: idCliente, idServico: idServico, tipoForm: tipoForm}, function(result) {
	if (result == 1) {
	    alert('Você não tem permissão para esta ação.');
	    return false;
	}
	abaVacina(idCliente, idTitular);
    });
}

/**
 * mostra_nome_usuario
 *
 *	Funcao utilizada para mostrar o nome do usuario que 
 *  inseriu a vacina.
 *
 * @author Bruno Haick
 * @date Criação: 16/07/2013
 *
 */

function mostra_nome_usuario(usuario) {

    $('p#usuario_hist_vac').html(usuario);
    //alert(usuario);
}

/**
 * agrupa_vac_cliente
 *
 *	Funcao utilizada para agrupar o id do historico da celula 
 * 	escolhida na sessão para deopis poder agrupar as vacinas 
 *	no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */

function agrupa_vac_cliente(idHistorico) {

    var tipoForm = "agrupa_vacina_cliente";

    $('#td_' + idHistorico).css("background-color", "#FFC0CB");
    $.post("index.php?module=vacina&tmp=1", {idHistorico: idHistorico, tipoForm: tipoForm}, function(result) {
    });
}

/**
 * agrupa_vac_cliente
 *
 *	Funcao utilizada para agrupar o id do historico da celula 
 * 	escolhida na sessão para deopis poder agrupar as vacinas 
 *	no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */

function finaliza_agrupamento_vacinas(idCliente, idTitular) {

    var tipoForm = "finaliza_agrupa_vacina_cliente";

    $.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm}, function(result) {

	if (result == 1) {
	    alert('Não é permitido selecionar mais de um grupo.');
	    return false;
	}
	abaVacina(idCliente, idTitular);
    }, "json");
}

/**
 * modal_eventosAdversos
 *
 *	Funcao utilizada para popular o modal de eventos adveros
 *	após isso, abre o modal.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */
function modal_eventosAdversos(idCliente, idHistorico, titulo) {

    var html = "<h3>" + titulo + "</h3>";

    $("div#reacoes_vac_nome").html(html);
    $("input#hidden-idHistorico").val(idHistorico);
    $("input#hidden-idClienteAba").val(idCliente);

    abrir_modal("boxe-eventos");
}

function modal_data_vacina(idCliente, idHistorico, data_pre) {

//    html += "<input type='text' data-mask='99/99/9999' id='data_" + idHistorico + "' value='" + data_pre + "' placeholder='Digite a data'/>";
    var html = "<label>Informe a data da vacina:</label>";
    html += "<input type='text' data-mask='99/99/9999' onblur='dateComplete($(this));' id='data_edicao_vacina' value='" + data_pre + "' placeholder='Digite a data' autofocus />";
    var html2 = "<a href='#' onclick='edita_data_vac_cliente(" + idCliente + "," + idHistorico + ")' class='btn'>Salvar</a>"
    html2 += "<a href='#' onclick='fechar_modal(\"boxe-datavacina\")' class='btn'> Fechar</a>";

    $("div#editar_data_vacina_modal").html(html);
    $("div#editar_data_vacina_botao").html(html2);

    abrir_modal("boxe-datavacina");
}

function modal_eventosAdversosEditar(idCliente, idHistorico, idEvento, data, conduta, evolucao, titulo) {

    var html = "<h3>" + titulo + "</h3>";

    $("div#reacoes_vac_nome").html(html);
    $("input#hidden-idHistorico").val(idHistorico);
    $("input#hidden-idClienteAba").val(idCliente);
    $('select:[name=reacoes_vac_evento]').val(idEvento);
    $('input:[name=reacoes_vac_data]').val(data);
    $('textarea:[name=reacoes_vac_conduta]').val(conduta);
    $('textarea:[name=reacoes_vac_evolucao]').val(evolucao);

    abrir_modal("boxe-eventos");
}

function excluir_evento_adverso() {

    var idTitular = $("#cad-matricula").val();
    var idHistorico = $("input#hidden-idHistorico").val();
    var idCliente = $("input#hidden-idClienteAba").val();

    var tipoForm = "exclui_eventos_adversos";

    $.post("index.php?module=vacina&tmp=1", {idHistorico: idHistorico, tipoForm: tipoForm}, function(result) {
	if (result == 1) {
	    alert("Evento Excluido com sucesso !");
	    $("input#hidden-idHistorico").val("");
	    $("input#hidden-idClienteAba").val("");
	    $('select:[name=reacoes_vac_evento]').val("");
	    $('input:[name=reacoes_vac_data]').val("");
	    $('textarea:[name=reacoes_vac_conduta]').val("");
	    $('textarea:[name=reacoes_vac_evolucao]').val("");
	    fechar_modal('boxe-eventos');
	    abaVacina(idCliente, idTitular);
	}
    }, "json");
}


function readFormObj(idform) {
    var form;
    if (idform == null) {
	form = "form";
    } else {
	form = "#" + idform;
    }
    var obj = {};
    var fieldsetCount = $(form).children().length;
    for (var i = 1; i <= fieldsetCount; ++i) {
	$(form).children(':nth-child(' + parseInt(i) + ')').find(':input:not(button)').each(function() {
	    if ($(this).attr("type") == 'checkbox') {
		if ($(this).is(':checked')) {
		    obj[$(this).attr("name")] = 1;
		} else {
		    obj[$(this).attr("name")] = false;
		}
	    } else {
		obj[$(this).attr("name")] = $(this).val();
	    }
	});
    }
    return obj;
}

/**
 * insere_evento_adverso
 *
 *	Funcao utilizada para inserir um evento adverso para um 
 *	historico servico depois de inserir chama a funcao abaVacina para
 *	atualizar a ficha de vacina.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 */

function insere_evento_adverso() {

    var lerform = readFormObj('formvacina');
    var idTitular = $("#cad-matricula").val();
    var idHistorico = $("input#hidden-idHistorico").val();
    var idCliente = $("input#hidden-idClienteAba").val();
    var idEvento = lerform.reacoes_vac_evento;
    var data = lerform.reacoes_vac_data;
    var conduta = lerform.reacoes_vac_conduta;
    var evolucao = lerform.reacoes_vac_evolucao;

    var tipoForm = "insere_eventos_adversos";

    $.post("index.php?module=vacina&tmp=1", {idEvento: idEvento, idHistorico: idHistorico, data: data, conduta: conduta, evolucao: evolucao, tipoForm: tipoForm}, function(result) {
	if (result == 1) {
	    alert("Evento Cadastrado com sucesso.");
	    $("input#hidden-idHistorico").val("");
	    $("input#hidden-idClienteAba").val("");
	    $('select:[name=reacoes-vac_evento]').val("");
	    $('input:[name=reacoes-vac_data]').val("");
	    $('textarea:[name=reacoes-vac_conduta]').val("");
	    $('textarea:[name=reacoes-vac_evolucao]').val("");
	    fechar_modal('boxe-eventos');
	    abaVacina(idCliente, idTitular);
	    return false;
	} else {
	    alert('Ocorreu um Erro ao inserirr o registro, por favor Tente Novamente.');
	    return false
	}
    }, "json");
}



/**
 * editaFichaVacina
 *
 *	Funcao utilizada para preencher os campos da ficha
 *	de edição ou inserção de vacinas para um cliente.
 *
 * @author Bruno Haick
 * @date Criação: 06/02/2013
 *
 */

function editaFichaVacina(idCliente, nomeVac, cor, data_prev, idServico, idHistorico, flag) {

    if (flag == "editar") {

	var tipoForm = "busca_dados_vacina";

	$.ajax({
	    type: 'POST',
	    url: 'index.php?module=vacina&tmp=1',
	    dataType: 'json',
	    data: {cor: cor, tipoForm: tipoForm, nomeVac: nomeVac},
	    success: function(result) {

		$("select#vaci-nome option[value='" + idCliente + "']").prop('selected', true);
		$("select#vaci-vacina option[value='" + result.idVac + "']").prop('selected', true);
		$("select#vaci-status option[value='" + result.idStat + "']").prop('selected', true);
		$("input#ficha-vacina-data").val(data_prev);
		$("input#btn-inserir-vacina").hide();
		$("input#btn-editar-vacina").show();

		$("input#hidden-idServico").val(idServico);
		$("input#hidden-idHistorico").val(idHistorico);
		$("input#hidden-idClienteAba").val(idCliente);

		$('ul#tabsul a:last').tab('show');
	    }
	});

    } else if (flag == "inserir") {

	$("select#vaci-nome option[value='" + idCliente + "']").prop('selected', true);

	$("input#hidden-idClienteAba").val(idCliente);
	$("input#btn-editar-vacina").hide();
	$("input#btn-inserir-vacina").show();

	$('ul#tabsul a:last').tab('show');
    }
}



//Limpando o formulário

function clearForm(idform)
{
    var form;
    if (idform == null) {
	form = "form";
    } else {
	form = "#" + idform;
    }
    var fieldsetCount = $(form).children().length;
    for (var i = 1; i < fieldsetCount; ++i) {
	$(form).children(':nth-child(' + parseInt(i) + ')').find(':input:not(button)').each(function() {
	    $(this).val('');
	});
    }
}

function AbrePopUp(Url, Name, Wi, He, scrolling, status, resizable) {

    if (!scrolling)
	scrolling = 'auto';

    if (!status)
	status = 'no';

    if (!resizable)
	resizable = 'no';

    PopUp = window.open(Url, Name, 'width=' + Wi + ',height=' + He + ',scrollbars=' + scrolling + ',toolbar=no,location=no,status=' + status + ',menubar=no,resizable=' + resizable + ',left=100,top=100');
}