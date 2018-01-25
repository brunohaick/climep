$(document).ready(function() {
	
	$('input#cad-mat').keydown(function(event) {
		if (event.which === 13 || event.which === 10) {
			verificaTitular();
		}
	});

	$('input#inputTextImunoData').keydown(function(event) {
		if (event.which === 13 || event.which === 10) {
			dateComplete($(this));
			imunoterapia.addbyModal();
		}
	});

});

function imprimirFichaDeImunoterapia() {
	window.open('index2.php?module=imunoterapia&userId=' + $('input#opcao:checked').val() + '&imuno=' + $('select#modeloDeEscolhaDoGrupoDeImunoterapiaParaImpresao option:selected').html());
}

function etiquetaDeCarta() {
	window.open('index2.php?module=carta');
}

function anamense() {
	window.open('index2.php?module=anamnese&userId=' + $('input:checked#opcao').val());
}

function termoDeConsentimento() {
	window.open('index2.php?module=tcle&rg=' + $('input#inputTextRG').val() + '&userId=' + $('input:checked#opcao').val());
}

function fichaImunoSelecionaGrupos() {
	$.ajax({
		url: 'index.php?module=controle_imunoterapia&tmp',
		type: 'POST',
		data: {tipoFormImuno: 'pegaGrupos', userId: $('#fichaimuno_select_cliente :selected').val()},
		success: function(responce) {
			responce = $.parseJSON(responce);
			var $Select = $('select#fichaimuno_select_imunoterapia');
			$Select.html('');
			for (var grupo in responce) {
				grupo = responce[grupo];
				$Select.append($('<option/>').attr('grupoId', grupo['grupo_material_id']).html(grupo['nomeGrupo']));
			}
		}
	});
	var clienteID = $('#fichaimuno_select_cliente :selected').val();
	$.ajax({
		url: 'index.php?module=imunoterapia&look=clienteIndentificacao&tmp=0',
		type: 'POST',
		data: {cliente: clienteID},
		success: function(response) {
			response = $.parseJSON(response);
			$('#Corpo_tabela_de_itens_modulo').html('');
			console.log(response);
			for (var i in response) {
				var $tr = $('<tr/>');
				$tr.attr('itemId', response[i][2]);
				$tr.attr('materialId', response[i][5]);
				$tr.attr('clienteID', response[i][4]);
				$tr.attr('comprimento', '0');
				$tr.attr('moduloid', response[i][8]);
				
				var $span2 = $('<span />');
				$span2.attr('id', 'data_item_imuno');
				$span2.attr('posicao', response[i][7]);
				$span2.attr('materialId', response[i][4]);
				$span2.html(response[i][5]);
				
				var $td1 = $('<td/>');
				$td1.attr('id', 'itemModuloNomeMaterial');
				$td1.attr('aling', 'center');
				$td1.html($span2);

				var $span = $('<span />');
				$span.attr('id', 'data_item_imuno');
				$span.attr('posicao', response[i][7]);
				$span.attr('materialId', response[i][4]);
				$span.html(response[i][1]);
				
				var $td2 = $('<td/>');
				$td2.attr('id', 'itemModuloData');
				$td2.attr('aling', 'center');
				$td2.html($span);

				$tr.append($td1);
				$tr.append($td2);
				$('#Corpo_tabela_de_itens_modulo').append($tr);
				$('tbody tr td span#data_item_imuno').click(function data_item_click() {
					var $this = $(this);
					var text = $.trim($this.text());
					var $input = $('<input/>')
							.attr('data-mask', '99/99/9999')
							.attr('type', 'text')
							.attr('id', 'data_item_imuno')
							.css('width', '100px')
							.val(text)
							.blur(function() {
						var span = $('<span/>')
								.attr('id', 'data_item_imuno');
						span.html($input.val());
						span.click(data_item_click);
						$input.parent().html(span);
					});
					$this.parent().html($input);
				});
				$('#btn_tabela_modulos_imuno').attr('style','display:block;float:right;');	
		}

		}
	});
}

/**
 * Fechar Modal
 * Fecha um modal e redireciona o usuario para a tela de edição de cliente com o
 * determinado id recebido.
 *
 * @author Marcus Dias
 * @date Criação: 14/09/2012
 *
 * @param int id
 *	este parametro é a matricula do cliente.
 *
 */
function editarMembro(idTitular, idPessoa) {
	redirecionar("index.php?module=editar&matricula=" + idTitular);
}

function encaminhaTitular(id, matricula) {
	var add_fila = 1;
	var cliente_id = id;
	if (cliente_id === '') {
		alert("Escolha um usuário válido.");
	} else {
		$.post('index.php?module=fila_espera_vacina&tmp=1', {cliente_id: cliente_id, add_fila: add_fila}, function(data) {
                        //alert(data);
			if (data === 999999999999) {
				alert('Cliente já se encontra na fila de espera e não foi atendido até o momento.');
			} else {
				document.location.href = "index.php?module=editar&matricula=" + matricula;
				//alert("index.php?module=editar&matricula="+matricula);
			}
		}, "json");
	}

}

function encaminhaDependente(matricula) {
	var add_fila = 1;

	if ($('input:radio[id=opcao]').is(':checked')) {
		var cliente_id = $('input:radio[id=opcao]:checked').val();
		$.post('index.php?module=fila_espera_vacina&tmp=1', {cliente_id: cliente_id, add_fila: add_fila}, function(data) {
//            alert(data);
			if (data == 999999999999) {
				alert('Cliente já se encontra na fila de espera e não foi atendido até o momento.');
			} else {
				document.location.href = "index.php?module=editar&matricula=" + matricula;
				//alert("index.php?module=editar&matricula="+matricula);
			}
		});
	} else {
		alert("Escolha um Dependente.");
	}

}

function getAddress(cep) {
	$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep=" + cep, function() {
		if (resultadoCEP["resultado"] != 0) {
			$("#cad-cidade").val(unescape(resultadoCEP["cidade"]));
			$("#cad-estado").val(unescape(resultadoCEP["uf"]));
			$("#cad-bairro").val(unescape(resultadoCEP["bairro"]));
			$("#cad-endereco").val(unescape(resultadoCEP["tipo_logradouro"]) + " " + unescape(resultadoCEP["logradouro"]));
			$('#cad-numero').focus();
		}
	});
}

function abreModalTesteAuditivo() {
	abrir_modal('teste-auditivo');
}

function abremodaltestevisual() {
	abrir_modal('boxes-tst-visual');
}

function validateForm() {
	flag = false;
	
	var tipoform = $('#cad-cadastrar').val();

	if(tipoform == 'Cadastro') {
		var nome = $("#cad-nome").val();
		var sobrenome = $("#cad-sobrenome").val();
		$.ajax({
				url: "index.php?module=cadastro&tmp=1",
				data: {nome:nome,
					  sobrenome:sobrenome,
					  pessoaExiste: 1
				},
				type: "POST",
				async: false,
				success: function(data) {
					if(data == 1) {
						alert("Já existe uma pessoa com este Nome e SobreNome !");
						flag = true;
						return false;
					}
				}
		});
		if(flag == true) {
			return false;
		}
	}

	if ($("#cad-nome").val() == "" && flag == false) {
		alert("Por Favor, informe o nome do Usuário que deseja Cadastrar!");
		return false;
	} else if ($("#cad-nascimento").val() == "" && flag == false) {
		alert("Por Favor, informe a data de nascimento do Usuário!  aaa");
		return false;
	} else if ($("#cad-email").val() == "" && flag == false) {
		if (!confirm("Você não definiu o email! Deseja continuar assim mesmo?")) {
			return false;
		} else {
			flag = true;
		}
	}

	if (($("#fone-residencial").val() == "" && $("#fone-comercial").val() == "" && $("#fone-apoio").val() == "") && flag == false) {
		if (!confirm("Você não definiu nenhum Telefone! Deseja continuar assim mesmo?")) {
			return false;
		} else {
			flag = true;
		}
	}

	if ($("#nome-nf").val() == "" && flag == false) {
		if (!confirm("Você não definiu um nome para nota fiscal! Deseja continuar assim mesmo?"))
			return false;
		else
			flag = true;
	}

	if ($("#doc-nf").val() == "" && flag == false) {
		if (!confirm("Você não definiu nenhum CPF para nota fiscal! Deseja continuar assim mesmo?")) {
			return false;
		} else {
			flag = true;
		}
	}

	if (!IsEmail($("#cad-email").val()) && flag == false) {
		alert("Insira um email Válido!");
		return false;
	}

	if (!validaCpf($("#doc-nf").val()) && flag == false) {
		alert("Por Favor, Escreva um CPF válido!");
		return false;
	}

	if (($("#cad-cep").val() == "" || $("#cad-numero").val() == "") && flag == false) {
		alert("Por Favor, Escreva todas as informações do endereço!");
		return false;
	}

//	var nome = $("#cad-nome").val();
//	var sobrenome = $("#cad-sobrenome").val();
//	$.post('index.php?module=cadastro&tmp=1',{
//		nome:nome,
//		sobrenome:sobrenome,
//		pessoaExiste: 1
//	},function(data) {
//		alert(data);
//		if(data == 1) {
//			alert("Já existe uma pessoa com este Nome e SobreNome !");
//			return false;
//		}
//	},"json");

	//		return false;
	return true;

}

function validaCpf(cpf) {

	cpf = cpf.replace('.', '');
	cpf = cpf.replace('.', '');
	cpf = cpf.replace('.', '');
	cpf = cpf.replace('-', '');
	var numeros, digitos, soma, i, resultado, digitos_iguais;
	digitos_iguais = 1;
	if (cpf.length < 11)
		return false;
	for (i = 0; i < cpf.length - 1; i++)
		if (cpf.charAt(i) != cpf.charAt(i + 1))
		{
			digitos_iguais = 0;
			break;
		}
	if (!digitos_iguais)
	{
		numeros = cpf.substring(0, 9);
		digitos = cpf.substring(9);
		soma = 0;
		for (i = 10; i > 1; i--)
			soma += numeros.charAt(10 - i) * i;
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0))
			return false;
		numeros = cpf.substring(0, 10);
		soma = 0;
		for (i = 11; i > 1; i--)
			soma += numeros.charAt(11 - i) * i;
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1))
			return false;
		return true;
	}
	else
		return false;
}

function IsEmail(email) {

	var exclude = /[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
	var check = /@[\w\-]+\./;
	var checkend = /\.[a-zA-Z]{2,3}$/;
	if (((email.search(exclude) != -1) || (email.search(check)) == -1) || (email.search(checkend) == -1)) {
		return false;
	} else {
		return true;
	}
}

function abrefichaimunoterapia() {

	if ($('input:radio[id=opcao]').is(':checked')) {
		var id = $('input:radio[id=opcao]:checked').val();
		var nome = $("#opcao-dependente-" + id).html();
	} else {
		var id = $("#cad-matricula").val();
		var nome = $("#cad-nome").val() + " " + $("#cad-sobrenome").val();
	}

	var flag = "carregaImunoterapiaLista";
	$.post('index.php?module=editar&tmp=1', {id: id, flag: flag}, function(data) {
		html = "";
		if (data) {
			for (var i = 0; i < data.length; i++) {
				html += "<tr name='table-color'  class='dayhead '>";
				html += "<th align='center'>" + data[i]['status'] + "  </th>";
				html += "<th align='center'>" + data[i]['data'] + "</th>";
				html += "<th align='center'> " + data[i]['nome'] + " </th>";
				html += "<th align='center'>" + data[i]['dose'] + "  </th>";
				html += "<th align='center'></th>";
				html += "</tr>";
			}

			$("#tbody-listaimunoterapia-cliente").html(html);
		}
	}, "json"
			);

	$("#modal-imunoterapia-nome").html(nome);
	abrir_modal('boxes-imunoterapia');
}

function abrefichaimunoterapia2() {

	if ($('input:radio[id=opcao]').is(':checked')) {
		var id = $('input:radio[id=opcao]:checked').val();
		var nome = $("#opcao-dependente-" + id).html();
	} else {
		var id = $("#cad-matricula").val();
		var nome = $("#cad-nome").val() + " " + $("#cad-sobrenome").val();
	}

	abrir_modal('boxes-ficha-imuno');
}

function pintamodalimunoterapia(id, idImuno) {

	$("#id-lista-selecionado").val(id);
	$("#imunoterapia-lista-selecionado").val(idImuno);
	$(".imunoterapia-lista").css('background-color', '');
	$("#" + id).css('background-color', '#F5F5F5');

	var idCliente = $("#cad-matricula").val();
	var flag = "carregaImunoterapia";
	$.post(
			'index.php?module=editar&tmp=1',
			{idCliente: idCliente, idImuno: idImuno, flag: flag},
	function(data) {
		html = "";
		if (data) {
			for (var i = 0; i < data.length; i++) {
				html += "<tr name='table-color'  class='dayhead '>";
				html += "<th align='center'>" + data[i]['status'] + "  </th>";
				html += "<th align='center'>" + data[i]['data'] + "</th>";
				html += "<th align='center'> " + data[i]['nome'] + " </th>";
				html += "<th align='center'>" + data[i]['dose'] + "  </th>";
				html += "<th align='center'></th>";
				html += "</tr>";
			}
		} else {
			html = " ";

		}

		$("#tbody-listaimunoterapia-cliente").html(html);
	}, "json"
			);
}


function appendImuno() {

	idImuno = $("#imunoterapia-lista-selecionado").val();
	idCliente = $("#cad-matricula").val();
	flag = "appendImuno";

	$.post(
			'index.php?module=editar&tmp=1',
			{idCliente: idCliente, idImuno: idImuno, flag: flag},
	function(data) {
		if (data['finalizado'] == '1') {
			alert('Não existem doses pagas e não tomadas dessa Imunoterapia');
		} else {
			$("#servico-imunoterapia-lista-selecionado").val(data['id']);
			var selectDoseML = "<select id='tipo-dose-" + idImuno + "' onchange='tipoQtdImuno();'><option value = 'DOSE'> DOSE </option> <option value='ML'> ML </option></select>";
			var html = "";
			html += "<tr name='table-color'  class='dayhead '>";
			html += "<th align='center'>" + data['status'] + "</th>";
			html += "<th align='center'>" + data['data'] + "</th>";
			html += "<th align='center'>" + data['nome'] + "</th>";
			html += "<th align='center'>";
			html += selectDoseML;
			html += "<select id='adicionaimuno-" + idImuno + "'>";
			for (var i = 1; i <= data['dose']; i++) {
				html += "<option value='" + i + "'>" + i + "</option>";
			}
			html += "</select></th>";
			html += "<th align='center'><input type='button' onclick='adicionaImuno();' value='Adicionar'/></th>";
			html += "</tr>";
			$("#tbody-listaimunoterapia-cliente").append(html);
		}
	}, "json"
			);

}

function adicionaImuno() {

	idServico = $("#servico-imunoterapia-lista-selecionado").val();
	idImuno = $("#imunoterapia-lista-selecionado").val();
	id = $("#id-lista-selecionado").val();
	idCliente = $("#cad-matricula").val();
	tipoQtd = $("#tipo-dose-" + idImuno).val();
	qtd = $("#adicionaimuno-" + idImuno).val();
	flag = "adicionaImuno";
	$.post(
			'index.php?module=editar&tmp=1',
			{idCliente: idCliente, idImuno: idImuno, tipoQtd: tipoQtd, qtd: qtd, idServico: idServico, flag: flag},
	function(data) {
		if (data['sucesso']) {
			alert("Sucesso");
			pintamodalimunoterapia(id, idImuno);
		} else {
			alert("Erro");
		}

	}, "json"
			);


}

function tipoQtdImuno() {

	idImuno = $("#imunoterapia-lista-selecionado").val();
	idCliente = $("#cad-matricula").val();
	tipoQtd = $("#tipo-dose-" + idImuno).val();
	flag = "calculaQtd";
	$.post(
			'index.php?module=editar&tmp=1',
			{idCliente: idCliente, idImuno: idImuno, tipoQtd: tipoQtd, flag: flag},
	function(data) {
		$("#servico-imunoterapia-lista-selecionado").val(data['id']);
		html = "";
		for (var i = 1; i <= data['dose']; i++) {
			html += "<option value='" + i + "'>" + i + "</option>";
		}
		$("#adicionaimuno-" + idImuno).html(html);
	}, "json"
			);
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
function buscaVacPorCliente() {

	var idcliente = $('select:[name=cert-vac-membro]').val();

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
					html += "<td align='center'>";
					html += result[i]['data_prevista'];
					html += "</td>";
					html += "<td align='center'>";
					html += result[i]['vacinaNome'];
					html += "</td>";
					html += "<td align='center'>";
					html += "";
					html += "</td>";
					html += "<td align='center'>";
					html += stat;
					html += "</td>";
					html += "</tr>";
					stat = "";
				}
			}

		$("tbody#cert-vac-vacs").html(html);
	}, "json");
}

function imprimirCertificadoVacinaCad() {

	var id = $('select:[name=cert-vac-membro]').val();
	var language = $('input:[name=language]:checked').val();

	var idTitular = $("#cad-matricula").val();
	var tipoForm = "imprimir_certificadovacina"

	$.post("index.php?module=vacina&tmp=1", {tipoForm: tipoForm, language: language, idClienteVacina: id, idTitular: idTitular}, function(result) {
		window.open('index2.php?module=certificadovacina', '_blank');
	});

}

function verificaTitular() {

	var matriculaTitular = $("#cad-mat").val();
	var tipoForm = "verifica_titular"

	$.post("index.php?module=editar&tmp=1", {flag: tipoForm, matriculaTitular: matriculaTitular}, function(result) {

		if (result == 0) {
			alert('Matricula Inexistente');
		} else {
			document.location.href = "index.php?module=editar&matricula=" + result;
		}
	}, "json");
}

function mostraDesdobramentoSelect() {
	var origem = $("#cad-origem option:selected").val();
	
	if(origem == 8) {
		$("div#cad_desdobramento").css("display", "block");
	} else {
		$("div#cad_desdobramento").css("display", "none");
	}
}

function goToModulos() {
	$("div#ctr-imu2").hide("slide", function() {
		$("div#ctr-imu2").css("display", "none");
		$("div#ctr-imu").css("display", "block");

	});
}

function goToModulos2() {
	$("div#ctr-imu").hide("slide", function() {
		$("div#ctr-imu").css("display", "none");
		$("div#ctr-imu2").css("display", "block");
	});
}
