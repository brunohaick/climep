/**
 *
 * Imunoterapia
 *
 * @author Bruno Haick & Luiz Cortinhas
 * @date Criação: 25/09/2012
 *
 */

$(document).ready(function () {
	imunoterapia = new modelImunoterapia();
	imunoterapia.modelImunoterapia();


	/**
	 *
	 * Trata o evento do click no botão de Submeter
	 * o formulário de Imunoterapias, para que seja
	 * seja enviado para o controller e assim inserir
	 * os dados no banco de dados.
	 *
	 * @author Bruno Haick
	 * @date Criação: 25/09/2012
	 *
	 */
	$("button#submit-controle-imuno").click(function () {

		var i = 0;
		var numRows;
		var x;
		var hist = Array();
		var serv = Array();

		$("table tr#tr-imuno-insert-historico").each(function (i, v) {
			hist[i] = Array();
			$(this).children('th#th-imuno-insert-historico').each(function (ii, vv) {
				hist[i][ii] = $(this).text();
			});
		})

		/*
		 * Esse bloco monta um array percorrendo as linhas da tabela
		 * referentes aos serviços já existentes para a família
		 * assim só vai ser preciso esses dados se o usuário for
		 * realizar um procedimento hoje, ou seja se marcar o checkbox
		 * e definir a divisão no campo de input.
		 *
		 * */

		numRows = hist.length;
		x = 0;
		while (x < numRows) {
			if ($('input:[name=hist-fazerhoje-' + x + ']').is(":checked")) {
				hist[x][5] = $('input:[name=hist-fazerhoje-' + x + ']').val();
				hist[x][6] = $('input:[name=hist-divisao-' + x + ']').val();
				hist[x][7] = $('#hidden-hist-servico-' + x).val();
			}
			x++;
		}

		/*
		 * Esse bloco monta um array percorrendo as linhas da tabela
		 * referentes aos serviços que estão sendo inseridos nesta sessão
		 * assim precisa-se dos dados para inserir tanto um serviço novo,
		 * como um histórico, mas nesse caso só se o cliente fo realizar
		 * um procedimento hoje, ou seja se marcar o checkbox e definir
		 * a divisão no campo de input.
		 *
		 * */
		$("table tr#tr-imuno-insert-servico").each(function (i, v) {
			serv[i] = Array();
			$(this).children('th#th-imuno-insert-servico').each(function (ii, vv) {
				serv[i][ii] = $(this).text();
			});
		})

		numRows = serv.length;

		if (numRows == 0) {
			alert("Por favor, selecione ao menos um serviço!");
			return false;
		}

		x = 0;
		while (x < numRows) {
			serv[x][0] = $('#hidden-membro-' + x).val();
			serv[x][1] = $('#hidden-material-' + x).val();
			serv[x][4] = $('select:[name=qtd_doses-' + x + ']').val();
			if ($('input:[name=serv-fazerhoje-' + x + ']').is(":checked")) {
				serv[x][5] = $('input:[name=serv-fazerhoje-' + x + ']').val();
				serv[x][6] = $('input:[name=serv-divisao-' + x + ']').val();
			}
			x++;
		}
		//alert(serv);

		var precoTotal = $('#precoTotal').val();

		$.post("index.php?module=controle_imunoterapia&tmp=1", {hist: hist, serv: serv, precoTotal: precoTotal, tipoFormImuno: 'imuno-insert'}, function (result) {
			alert("Operação Realizada com Sucesso !");
			fechar_modal('boxes-ctr-imu');
		});
	});


	/**
	 *
	 * Trata o evento do click no botão de adicionar
	 * imunoterapias a um cliente no formulário de
	 * controle de Imunoterapias.
	 *nomeMembro
	 * @author Bruno Haick
	 * @date Criação: 25/09/2012
	 *
	 * Cadas vex que o Botão para adição de imuno
	 * é clicado, manda-se dados para o controller
	 * e este retorna um array com os dados do serviço
	 * (Imunoterapia) para montar mais uma linha da
	 * tabela com os dados já preenchidos.
	 *
	 */

	$("button#imuno-append").click(function () {
		$('table tbody tr:odd').css('background', '#FFF');
		$('table tbody tr:[name=tr-imuno-' + rowId + ']:even').css('background', '#D3D6FF');

		var rowId = parseInt($('#hiddenRowId').val());

		var idImuno = $('#ctr-imu-servico').val();
		var idMembro = $('#ctr-imu-membro').val();
		$.post("index.php?module=controle_imunoterapia&tmp=1", {idImuno: idImuno, idMembro: idMembro}, function (result) {

			var precoTotal = parseFloat($('#precoTotal').val()) + parseFloat(result['preco']);
			$('input#precoTotal').val(precoTotal);

			var html = "<tr id='tr-imuno-insert-servico' name='tr-imuno-" + rowId + "' class='dayhead tr-imuno-insert-servico-" + rowId + "'>";

			html += "<input type='hidden' id='hidden-membro-" + rowId + "' value='" + $('#ctr-imu-membro').val() + "'>";
			html += "<input type='hidden' id='hidden-material-" + rowId + "' value='" + $('#ctr-imu-servico').val() + "'>";

			html += "<th id='th-imuno-insert-servico' name='nomeMembro-" + rowId + "'>" + result['nomeMembro'] + "</th>";
			html += "<th id='th-imuno-insert-servico' name='nomeImuno-" + rowId + "'>" + result['nome'] + "</th>";

			/* Qtd Doses */

			html += "<th>"
			html += "<select id='qtd-doses-" + rowId + "' name='qtd_doses-" + rowId + "' onchange='calculaPreco(" + rowId + ");' >";

			var x = 1;
			var numDoses = result['quantidade_doses'];
			var qtd;
			var selected = "";
			while (x <= numDoses) {

				if (x == numDoses) {
					qtd = numDoses + ' - Frasco';
					selected = "selected='selected'";
				} else {
					qtd = x;
				}

				html += "<option " + selected + " value='" + x + "'>";
				html += qtd;
				html += "</option>";

				selected = "";
				x++;
			}

			html += "</select>";
			html += "</th>"

			/* Fim Qtd Doses*/

			/* Preço Calculado a partir do número de Doses. */

			html += "<th id='th-imuno-insert-servico' name='preco-" + rowId + "'>" + result['preco'] + "</th>";

			html += "<th id='th-imuno-insert-servico'>" + result['status'] + "</th>";

			html += "<th> <center>"
			html += "<input type='checkbox' name='serv-fazerhoje-" + rowId + "' id='serv-fazerhoje-" + rowId + "' value='" + rowId + "' >";
			html += " </center> </th>"

			html += "<th>"
			html += "<div class='span8'>"
			html += "<input type='text' name='serv-divisao-" + rowId + "' id='serv-divisao-" + rowId + "' onblur='verificaQtdMl(\"" + rowId + "\",\"" + result['qtd_ml_por_dose'] + "\",\"serv\");' value='" + result['qtd_ml_por_dose'] + "'>";
			html += "</div>"

			html += "</th>"
			html += "<th align='center' onclick='calculaPrecoRemove(\"" + rowId + "\");removerItemClass(\"tr-imuno-insert-servico-" + rowId + "\");' ><a class='btn btn-danger mrg-center' ><i class='icon-remove icon-white'></i></a></th>";

			html += "</tr>";

			rowId = parseInt($('#hiddenRowId').val()) + 1;
			$('input#hiddenRowId').val(rowId);

			$("tbody#table-imuno-append").append(html);
		}, "json");
	});

}); /* FIM DOCUMENT */
/**
 *
 * Controle Imunoterapia
 *
 * @author Bruno Haick
 * @date Criação: 25/09/2012
 *
 * Passa dados para o controller do modulo
 * de imunoterapia, e abre o modal para inserção de
 * novas imunoterapias. Essa função é chamad quando
 * a partir do menu de botão direito no modulo de edição
 * de usuários.
 *
 */
function controle_imunoterapia() {

	var idDocliente = $("#cad-matricula").val();

	$.ajax({
		url: 'index.php?module=controle_imunoterapia&tmp',
		type: 'POST',
		data: {idcliente: idDocliente, tipoFormImuno: 'imuno-modal'},
		async: false,
		success: function (result) {
			result = $.parseJSON(result);

			var hoje = result['hoje'];
			var $tbody = $('tbody#controle-Imunoterapia-hoje');
			$tbody.html('');
			if (hoje !== null)
				$.each(hoje, function (index, valor) {
					var $tr = $('<tr/>').attr('class', 'pointer-cursor').click(function () {
						var $input = $(this).children('th').children('input[type=checkbox]');
						$input.attr('checked', !$input.is(':checked'));
					});
					$tr.append($('<th/>').html(valor['Membro']));
					$tr.append($('<th/>').html(valor['nome'] + valor['sobrenome']));
					$tr.append($('<th/>').html(valor['material_nome']));
					$tr.append($('<th/>').html(
							$('<input/>')
							.attr('type', 'checkbox')
							.attr('checked', 'checked')
							.attr('clienteId', valor['cliente_id'])
							.attr('materialId', valor['material_id'])
							.attr('servicoId', valor['id'])
							).attr('align', 'center'));

					$tbody.append($tr);
				});

			var naotomados = result['naotomados'];
			var $tbody = $('tbody#controle-Imunoterapia-pagos');
			$tbody.html('');
			if (naotomados !== null)
				$.each(naotomados, function (index, valor) {
					var $tr = $('<tr/>').attr('class', 'pointer-cursor').click(function () {
						var $input = $(this).children('th').children('input[type=checkbox]');
						$input.attr('checked', !$input.is(':checked'));
					});
					$tr.append($('<th/>').html(valor['Membro']));
					$tr.append($('<th/>').html(valor['nome'] + valor['sobrenome']));
					$tr.append($('<th/>').html(valor['material_nome']));
					$tr.append($('<th/>').html(
							$('<input/>')
							.attr('type', 'checkbox')
//                            .attr('checked', '')
							.attr('clienteId', valor['cliente_id'])
							.attr('materialId', valor['material_id'])
							.attr('servicoId', valor['id'])
							).attr('align', 'center'));

					$tbody.append($tr);
				});

			var prog = result['prog'];

			$tbody = $('tbody#controle-Imunoterapia-programados');
			$tbody.html('');

			if (prog !== null)
				$.each(prog, function (index, valor) {
					var $tr = $('<tr/>').attr('class', 'pointer-cursor').click(function () {
						var $input = $(this).children('th').children('input[type=checkbox]');
						$input.attr('checked', !$input.is(':checked'));
					});

					$tr.append($('<th/>').html(valor['Membro']));
					$tr.append($('<th/>').html(valor['nome'] + valor['sobrenome']));
					$tr.append($('<th/>').html(valor['material_nome']));
					$tr.append($('<th/>').html(
							$('<input/>')
							.attr('type', 'checkbox')
							.attr('clienteId', valor['cliente_id'])
							.attr('materialId', valor['material_id'])
							.attr('servicoId', valor['id'])
							).attr('align', 'center'));

					$tbody.append($tr);
				});

			var controle = result['controle'];
			if (result.controle !== null) {
				var html = "<option value='0'>Novo Controle</option>";
				for (var i = 0; i < controle.length; i++) {

					var idControle = controle[i]['id'];
					var numeroControle = controle[i]['numero_controle'];

					html += "<option value='" + idControle + "'>";
					html += "Controle Nº " + numeroControle;
					html += "</option>";
				}
			}
			$("select#select_controle_ctrl").html(html);

			html = "";
			console.log(result.modulos);
			var modulos = result.modulos;
			if (result.modulos != null) {
				for (var i = 0; i < modulos.length; i++) {

					html += '<li class=\'line-hover thumbnails\'>';
					html += '<label class=\'ClassDependenteModulos\' clienteid=\'' + modulos[i]['cliente_id'] + '\'>';
					html += '<input class=\'controle-Imunoterapia-modulos\' name=\'modulos_controle_imuno[]\' type=\'checkbox\' value=\'' + modulos[i]['id'] + '\'>';
					html += modulos[i]['preco'] + ' - ' + modulos[i]['nome'];
					html += '</label>';
					html += '</li>';
				}
			}
			$("ul#ULDependenteModulosImuno").html(html);
		}
	});

	abrir_modal('boxes-ctr-imu');
//	var idTitular = $("#cad-matricula").val();
//	var idDepImuno = $('input:radio[name=opcao]:checked').val();
//
//	$.post("index.php?module=controle_imunoterapia&tmp=1", {idTitular: idTitular, idDepImuno: idDepImuno, tipoFormImuno: 'imuno-modal'}, function(result) {
//		$("div#controle-imunoterapia-result").html(result);
//		abrir_modal('boxes-ctr-imu');
//	});
}

function controle_imunoterapia_imprimir() {

	var idTitular = $("#cad-matricula").val();
	var idControle = $('select#select_controle_imunoterapia').val();

	var hoje = new Array();
	$('tbody#controle-Imunoterapia-hoje input[type=checkbox]:checked').each(function () {
		hoje.push($(this).attr('materialId') + "/" + $(this).attr('clienteId') + "/" + $(this).attr('servicoId'));
	});

	var prog = new Array();
	$('tbody#controle-Imunoterapia-programados input[type=checkbox]:checked').each(function () {
		prog.push($(this).attr('materialId') + "/" + $(this).attr('clienteId') + "/" + $(this).attr('servicoId'));
	});

	var naotomado = new Array();
	$('tbody#controle-Imunoterapia-pagos input[type=checkbox]:checked').each(function () {
		naotomado.push($(this).attr('materialId') + "/" + $(this).attr('clienteId') + "/" + $(this).attr('servicoId'));
	});
	if (naotomado.length > 0) {
		hoje = naotomado;
	}

	var modulos = new Array();
	$("input.controle-Imunoterapia-modulos[type=checkbox]:checked").each(function () {
		modulos.push($(this).val());
	});

	$.ajax({
		url: 'index.php?module=controle_imunoterapia&tmp',
		type: 'POST',
		data: {tipoFormImuno: 'salvaNoBancoOGuiaControleEControle', hoje: hoje, prog: prog, modulos: modulos, idTitular: idTitular, idControle: idControle},
		success: function (result) {
			window.open('index2.php?module=controlenovo', '_blank');
		}
	});
}
/**
 *
 * cacula Preco
 *
 * @author Bruno Haick
 * @date Criação: 03/10/2012
 *
 * pega o numero de doses setado no campo, realiza
 * o caculo e modifica o campo preco com o valor
 * correto.
 *
 */
function calculaPreco(rowId) {

	var valor = 0;
	var valorAtual = 0;
	var valorCalculado = 0;
	var precoTotal = 0;
	var nomeImuno = "";
	var qtdDoses = $('select:[name=qtd_doses-' + rowId + ']').val();
	var campo = "th:[name=preco-" + rowId + "]";

	var data = Array();
	$("table tr:[name=tr-imuno-" + rowId + "]").each(function (i, v) {
		data[i] = Array();
		$(this).children('th').each(function (ii, vv) {
			data[i][ii] = $(this).text();
		});
	})

	nomeImuno = data[0][1];
	valorAtual = data[0][3];

	$.post("index.php?module=controle_imunoterapia&tmp=1", {qtdDoses: qtdDoses, nomeImuno: nomeImuno, tipoFormImuno: 'calculaPreco'}, function (result) {
		valorCalculado = result['preco'];

		if (valorCalculado > valorAtual) {
			valor = valorCalculado - valorAtual;
			precoTotal = parseFloat($('#precoTotal').val()) + parseFloat(valor);
		} else if (valorCalculado < valorAtual) {
			valor = valorAtual - valorCalculado;
			precoTotal = parseFloat($('#precoTotal').val()) - parseFloat(valor);
		}

		$('input#precoTotal').val(precoTotal);

		$(campo).text(valorCalculado);
	}, "json");

	return false;

}
/**
 *
 * cacula Preco 2
 *
 * @author Bruno Haick
 * @date Criação: 01/01/2013
 *
 * Quando clica no X pra remover a linha,
 * calcula-se o novo valor total, retirando
 * o valor atual da linha do valor total
 * atualmente.
 *
 */
function calculaPrecoRemove(rowId) {

	var valorTotalAtual = $('input#precoTotal').val();

	var data = Array();
	$("table tr:[name=tr-imuno-" + rowId + "]").each(function (i, v) {
		data[i] = Array();
		$(this).children('th').each(function (ii, vv) {
			data[i][ii] = $(this).text();
		});
	})

	valorLinhaAtual = data[0][3];

	novoValorTotal = parseFloat(valorTotalAtual) - parseFloat(valorLinhaAtual);

	$('input#precoTotal').val(novoValorTotal);

}
/**
 *
 * verifica Quantidade de Ml no campo divisao
 *
 * @author Bruno Haick
 * @date Criação: 06/10/2012
 *
 * Compara o valor digitado no campo de divisao
 * com a quantidade de ml por dose do material
 * especificado no servico.
 *
 * A função é ativada com o onchange no campo divisao
 *
 */
function verificaQtdMl(rowId, qtd_ml, tipo) {

	var divArr = Array();
	var soma = 0;
	divArr = $('input:[name=' + tipo + '-divisao-' + rowId + ']').val().split('/');

	$.each(divArr, function (indice, valor) {
		if (valor.length != 0) {
			soma += parseFloat(valor);
		}
	});

	if (soma > qtd_ml) {
		alert('A Quantidade Não pode ser maior que ' + qtd_ml + ' mL');
		//$('input:[name='+tipo+'-divisao-'+rowId+']').focus();
		$("#serv-divisao-" + rowId).focus();
		return false;
	}

}
/**
 *
 * Busca Imuno Por Cliente
 *
 * @author Bruno Haick
 * @date Criação: 26/6/2013
 *
 * Busca as imunoterapias do cliente;
 *
 */
function buscaImunoPorCliente() {
	var matricula = $('select:[name=ctr-imu-membro]').val();
	$.post("index.php?module=controle_imunoterapia&tmp=1", {matricula: matricula, tipoFormImuno: 'imuno-busca'}, function (result) {
		var html = "";
		if (result != null)
			for (var i = 0; i < result.length; i++) {
				html += "<tr name='table-color' class='dayhead'>";
				html += "<td align='center'>";
				html += result[i]['servico_data'];
				html += "</td>";
				html += "<td align='center'>";
				html += result[i]['material_nome'];
				html += "</td>";
				html += "<td align='center'>";
				html += "";
				html += "</td>";
				html += "</tr>";
			}

		$("tbody#ctr-imu-imunos").html(html);
	}, "json");
}

function modelImunoterapia() {
	this.mapImunoterapia = ['Frasco', 'Intervalo', 'Observação', 'Dose: 1', 'Dose: 2', 'Dose: 3', 'Dose: 4', 'Dose: 5', 'Dose: 6', 'Dose: 7', 'Dose: 8', 'Dose: 9', 'Dose: 10', 'Dose: 11', 'Dose: 12'];
	this.mapImunoVacinas = [];
	this.linha = 0;
	this.coluna = 0;
	this.colunaatual = 1;

	this.salvarTabela = function () {
		var clienteID = $('#fichaimuno_select_cliente :selected').val();
		if (parseInt(clienteID) > 0) {
			var arraycomvalores = [];
			$('tbody#tabelaImunoterapia > tr').each(function (index1, valor) {
				arraycomvalores[index1] = [];
				$(this).children('th').each(function (index2, celula) {
					if (index2 !== 0 && $(this).attr('novo') == 1) {
						var textoNome = '';
						if ($(this).attr('id').length == 3 || $(this).attr('id').length == 4) {
							textoNome = $(this).attr('id');
						} else {
							textoNome = $(this).html();
						}
						arraycomvalores [index1][index2 - 1] = textoNome;
					} else {
						arraycomvalores [index1][index2 - 1] = '';
					}
				});
			});

			$.ajax({
				url: 'index.php?module=imunoterapia&look=SessionSave&tmp=0',
				type: 'POST',
				dataType: 'json',
				data: {
					imunoterapias: arraycomvalores,
					cliente: clienteID,
					modulo: this.flag_modulo_usado
				}, success: function (resposta) {
					alert("Itens salvos no cadastro do cliente");
					imunoterapia.criaTabelaImunoterapia();
				}
			});
			if (this.flag_modulo_usado) {
				this.finalizaModuloAberto();
			}
		} else {
			alert('Selecione um cliente');
		}
	};

	this.modelImunoterapia = function () {
		this.criaTabelaImunoterapia();
		$('tbody > tr > th[linha!=1][linha!=2] span#data_item').click(function data_item_click() {
			var $this = $(this);
			var text = $.trim($this.text());
			var $input = $('<input/>')
					.attr('data-mask', '99/99/9999')
					.attr('type', 'text')
					.attr('id', 'data_item')
					.css('width', '100px')
					.val(text)
					.blur(function () {
						var span = $('<span/>')
								.attr('id', 'data_item');
						span.html($input.val());
						span.click(data_item_click);
						$input.parent().html(span);
					});
			$this.parent().html($input);
		});
	};
	this.evento = function evento() {
		var text = $.trim($(this).html());
		var $input = $('<input />')
				.attr('data-mask', '99/99/9999')
				.attr('type', 'text')
				.attr('id', 'data_item')
				.css('width', '100px')
				.val(text);
		$(this).html($input);
	};
	this.criaTabelaImunoterapia = function () {
		this.colunaatual = 1;
		var j = 0, i = 0;
		$('#tabelaImunoterapia').html('');
		for (i = 0; i < this.mapImunoterapia.length; i++) {
			var span = $('<span/>').attr('id', 'data_item');
			span.html('');
			var $tr = $('<tr />');
			var $thTitulo = $("<th/>")
					.attr('class', 'titulo')
					.attr('align', 'center')
					.attr('linha', i)
					.attr('style', 'width: 5px')
					.attr('coluna', 0)
					.html(this.mapImunoterapia[i]);
			$tr.append($thTitulo);
			for (j = 1; j <= 7; j++) {
				var $th = $("<th/>")
						.attr('class', 'titulo')
						.attr('id', 'celulaTabela')
						.attr('align', 'center')
						.attr('style', 'width: 100px')
						.attr('linha', i)
						.attr('coluna', j);

				if (i > 2) {
					$th.dblclick(function data_item_click() {
						var $this = $(this);
						var text = $.trim($this.text());
						var $input = $('<input/>')
								.attr('data-mask', '99/99/9999')
								.attr('type', 'text')
								.attr('id', 'data_item')
								.css('width', '80px')
								.val(text)
								.blur(function () {
//							var span = $('</>')
//									.attr('id', 'data_item');
//							span.html($input.val());
//							span.click(data_item_click);
									$this.html($input.val());
								});

						$this.html($input);
					});
				}
				$tr.append($th);

			}
			j = 0;
			$('#tabelaImunoterapia').append($tr);
		}
	};
	this.vetor = [];
	this.id_material;
	this.nome_material;
	this.inicio_data;

	this.abrirModal = function (id, nome, data) {
		this.id_material = id;
		this.nome_material = nome;
		this.inicio_data = data;
		this.flag_modulo_usado = 0;
		abrir_modal('boxe-dataimunoterapia');
	};

	this.addbyModal = function () {
		var data = $('#inputTextImunoData').val();
		console.log(this.id_material + " -> " + this.nome_material + " -> " + data);
		this.adicionar(this.id_material, this.nome_material, data, false, true);
		fechar_modal("boxe-dataimunoterapia");
	}
	this.adicionar = function adicionar(id_material, nome_material, data_inicio, isFrasco, isNovo) {
		var modificador = '';
		var novo = '0';
		if (isNovo) {
			novo = '1';
		}
		if ($('#dose_select_imunoterapia option:selected').val() == 1 || isFrasco) {
			$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').html(nome_material);
			$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('novo', novo);
			$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('id', id_material);
			$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('tipo', isFrasco);
			this.mapImunoVacinas[id_material] = nome_material;
			if (id_material == 426) {
				this.preencherACAROSF0(data_inicio, isNovo);
			} else {
				if (id_material == 427) {
					this.preencherACAROSF1(data_inicio, isNovo);
				} else {
					if (id_material == 428) {
						this.preencherACAROSF2(data_inicio, isNovo);
					} else {
						if (id_material == 429) {
							this.preencherACAROSF3(data_inicio, isNovo);
						} else {
							if (id_material == 430) {
								this.preencherACAROSF4a(data_inicio, isNovo);
							} else {
								if (id_material == 431) {
									this.preencherACAROSF4b(data_inicio, isNovo);
								} else {
									if (id_material == 425 || id_material == 436 || id_material == 434) {
										this.preencherEstrofiloHistaminaToxovacin1(data_inicio, isNovo);
									} else {
										if (id_material == 483 || id_material == 489 || id_material == 492) {
											this.preencherEstrofiloHistaminaToxovacin2(data_inicio, isNovo);
										} else {
											if (id_material == 484 || id_material == 490 || id_material == 493) {
												this.preencherEstrofiloHistaminaToxovacin3(data_inicio, isNovo);
											} else {
												if (id_material == 485 || id_material == 491 || id_material == 494) {
													this.preencherEstrofiloHistaminaToxovacin4(data_inicio, isNovo);
												} else {
													if (id_material == 435 || id_material == 423 || id_material == 438 || id_material == 437) {
														this.preencherAcneCandidinaHerpesDisidrovac1(data_inicio, isNovo);
													} else {
														if (id_material == 787 || id_material == 481 || id_material == 499 || id_material == 83) {
															this.preencherAcneCandidinaHerpesDisidrovac2(data_inicio, isNovo);
														} else {
															if (id_material == 788 || id_material == 482 || id_material == 500 || id_material == 84) {
																this.preencherAcneCandidinaHerpesDisidrovac3(data_inicio, isNovo);
															} else {
																this.preencherOraleInalantes(data_inicio, isNovo);
															}
														}
													}

												}
											}
										}
									}

								}
							}
						}
					}
				}
			}

		} else {
			this.preencherGenerico_Manual(isNovo, nome_material, id_material, "personalizado", data_inicio, '', '', '', '', '', '', '', '', '', '');
		}
		this.colunaatual++;
	};
	this.preencherSelectorGrupos = function preencherSelectorGrupos() {
		var clienteID = $('#fichaimuno_select_cliente option:selected').val();
		var conteudo = '';
		$('#fichaimuno_select_imunoterapia').html('');
		$.ajax({
			url: 'index.php?module=imunoterapia&look=ListGroup&tmp=0',
			type: 'POST',
			dataType: 'json',
			data: {cliente: clienteID},
			success: function (resposta) {
				$('#fichaimuno_select_imunoterapia').html('');
				$('#fichaimuno_select_imunoterapia').append(new Option(' ', 0, true, true));
				for (var i = 0; i < resposta.length; i++) {
					$('#fichaimuno_select_imunoterapia').append(new Option(resposta[i]['nomeGrupo'], resposta[i]['id'], true, true));
				}
				$('#fichaimuno_select_imunoterapia').val('');//Obrigatorio
			}
		});
		$('#fichaimuno_select_imunoterapia').val('');//Obrigatorio tambem, eu já resolvi :D
	};

	this.enviarbtn = function () {
		var vetor = [];
		var vetor_id = [];
		var i = 0;
		var j = 0;
		$('tbody#Corpo_tabela_de_itens_modulo > tr > td span').each(function () {
			vetor.push($(this).html());
			vetor_id.push($(this).attr('materialId'));
		});
		this.colunaatual = 1;
		$('#tabelaImunoterapia').html('');
		this.criaTabelaImunoterapia();
		for (var i = 0; i < vetor.length; i = i + 2) {
			var nome = vetor[i];
			var data = vetor[i + 1];
			this.adicionar(vetor_id[i], nome, data, true, true);
		}
		this.flag_modulo_usado = 1;
		$('#tabelaDeItensDoModulo').html('');//Limpar tela
	};

	this.finalizaModuloAberto = function (clienteID) {
		var clienteID = $('#fichaimuno_select_cliente option:selected').val();
		$.ajax({
			url: 'index.php?module=modulos&look=DeletarModulo&tmp=0',
			type: 'POST',
			data: {cliente: clienteID},
			success: function (data) {
				$('#tabelaImunoterapia').html('');
				alert("Modulo Salvo como Serviço com Sucesso!");
			}
		});
	}
	this.mostrargrupo = function () {
		console.log("mostragrupo");
		var grupoID = $('#fichaimuno_select_imunoterapia option:selected').val();
		var grupoNome = $('#fichaimuno_select_imunoterapia option:selected').html();
		$('#tabelaimunoterapiaNOME').html(grupoNome);
		$('#tabelaImunoterapia').html('');
		var clienteID = $('#fichaimuno_select_cliente option:selected').val();
		this.colunaatual = 1;
		this.criaTabelaImunoterapia();
		console.log(grupoID);
		var THIS = this;
		$.ajax({//Sempre que esta consulta nao retornar nada , desconfie da inserção do historico.
			url: 'index.php?module=imunoterapia&look=SearchMaterialbyGroup&tmp=0',
			type: 'POST',
			dataType: 'json',
			data: {grupo: grupoID,
				cliente: clienteID},
			success: function (resposta) {
				this.vetor = resposta;
				console.log(resposta);
				for (var res in resposta) {
					var obj = resposta[res];
					var nome = resposta[res].nome;
					var periodo = 1;
					if (obj[1] instanceof Array && obj[2] instanceof Array) {
						var dataObj1 = obj[1][1];
						if (dataObj1 != null && dataObj1 != undefined) {
							dataObj1 = dataObj1.split('-');
							dataObj1 = dataObj1[2] + '/' + dataObj1[1] + '/' + dataObj1[0];
						} else {
							dataObj1 = '0000-00-00';
						}
						var dataObj2 = obj[2][1];
						dataObj2 = dataObj2.split('-');
						dataObj2 = dataObj2[2] + '/' + dataObj2[1] + '/' + dataObj2[0];
						if (dataObj2 !== dataObj1) {
							periodo = THIS.difDate(dataObj1, dataObj2) + ' Dias';
						}
						else {
							if (obj[1] instanceof Array && obj[3] instanceof Array) {
								var dataObj2 = obj[3][1];
								dataObj2 = dataObj2.split('-');
								dataObj2 = dataObj2[2] + '/' + dataObj2[1] + '/' + dataObj2[0];
								if (dataObj2 !== dataObj1) {
									periodo = THIS.difDate(dataObj1, dataObj2) + ' Dias';
								} else {
									var dataObj2 = obj[4][1];
									dataObj2 = dataObj2.split('-');
									dataObj2 = dataObj2[2] + '/' + dataObj2[1] + '/' + dataObj2[0];
									periodo = THIS.difDate(dataObj1, dataObj2) + ' Dias';
								}
							} else {
								periodo = 'Personalizado';
							}
						}
					} else {
						periodo = 'Personalizado';
					}
					THIS.mapImunoVacinas[nome] = nome;
					THIS.preencherGenerico_Manual(false, nome, 0, periodo, obj[1], obj[2], obj[3], obj[4], obj[5], obj[6], obj[7], obj[8], obj[9], obj[10], obj[11], obj[12]);
					THIS.colunaatual++;
					console.log("Mostrar Grupo chegou ao fim!");//Não ta executando a função de visualização...estranho neh  qual eh a funcao ?
				}
				//alert("O Grupo selecionado foi inserido na tabela");
			}
		});

	};
	this.difDate = function difDate(data1, data2) {
		var d1 = new Date(data1.substr(6, 4), data1.substr(3, 2) - 1, data1.substr(0, 2));
		var d2 = new Date(data2.substr(6, 4), data2.substr(3, 2) - 1, data2.substr(0, 2));
		return Math.ceil((d2.getTime() - d1.getTime()) / 1000 / 60 / 60 / 24);
	};
	this.adicionarGenerico = function adicionarGenerico(id_material, nome_material, periodo) {
		$('#tabelaImunoterapia > tr > th[linha=s0][coluna=' + this.colunaatual + ']').html(nome_material);
		$('#tabelaImunoterapia > tr > th[linha=s0][coluna=' + this.colunaatual + ']').attr('tipo', 'true');

	};
	this.preencherGenerico_Manual = function preencherGenerico(isNovo, nome, id_material, periodo, data1, data2, data3, data4, data5, data6, data7, data8, data9, data10, data11, data12) {
		var data = '';
		var cor = '';
		var novo = 0;
		if (isNovo) {
			novo = 1;
		}
		if (data1 instanceof Array) {
			var data = data1[1];
			var cor = data1[0];
			data = data.split('-');
			data = data[2] + '/' + data[1] + '/' + data[0];
		} else {
			var data = data1;
			var cor = '';
		}
		$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').html(nome);
		$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('novo', novo);
		$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('tipo', 'true');
		$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').attr('id', id_material);
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo);
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo);
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data2 instanceof Array) {
			if (data2 !== undefined && data[1] !== '') {
				data = data2[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data2[0];
			}
		} else {
			var data = data2;
			var cor = '#778899';
		}

		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo);
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		if (data3 instanceof Array) {
			if (data3 !== undefined && data3[1] !== '') {
				data = data3[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data3[0];
			}
		} else {
			var data = data3;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data4 instanceof Array) {
			if (data4 !== undefined && data4[1] !== '') {
				data = data4[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data4[0];
			}
		} else {
			var data = data4;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data5 instanceof Array) {
			if (data5 !== undefined && data5[1] !== '') {
				data = data5[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data5[0];
			}
		} else {
			var data = data5;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data6 instanceof Array)
		{
			if (data6 !== undefined && data6[1] !== '') {
				data = data6[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data6[0];
			}
		} else {
			var data = data6;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data7 instanceof Array)
		{
			if (data7 !== undefined && data7[1] !== '') {
				data = data7[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data7[0];
			}
		} else {
			var data = data7;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data8 instanceof Array)
		{
			if (data8 !== undefined && data8[1] !== '') {
				data = data8[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data8[0];
			}
		} else {
			var data = data8;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data9 instanceof Array)
		{
			if (data9 !== undefined && data9[1] !== '') {
				data = data9[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data9[0];
			}
		} else {
			var data = data9;
			var cor = '#778899';
		}

		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data10 instanceof Array)
		{
			if (data10 !== undefined && data10[1] !== '') {
				data = data10[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data10[0];
			}
		} else {
			var data = data10;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data11 instanceof Array)
		{
			if (data11 !== undefined && data11[1] !== '') {
				data = data11[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data11[0];
			}
		} else {
			var data = data11;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo);
		if (data12 instanceof Array)
		{
			if (data12 !== undefined && data12[1] !== '') {
				data = data12[1].split('-');
				data = data[2] + '/' + data[1] + '/' + data[0];
				cor = data12[0];
			}
		} else {
			var data = data12;
			var cor = '#778899';
		}
		$('#tabelaImunoterapia > tr > th[linha=14][coluna=' + this.colunaatual + ']').html(data);
		$('#tabelaImunoterapia > tr > th[linha=14][coluna=' + this.colunaatual + ']').attr('style', 'color:' + cor);
		$('#tabelaImunoterapia > tr > th[linha=14][coluna=' + this.colunaatual + ']').attr('update', '1');
		$('#tabelaImunoterapia > tr > th[linha=14][coluna=' + this.colunaatual + ']').attr('novo', novo);

	}
	;


	this.preencherGenerico_Automatico = function preencherGenerico(nome, periodo, data) {
		var data_hoje = '';
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		$('#tabelaImunoterapia > tr > th[linha=0][coluna=' + this.colunaatual + ']').html(nome);
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo);
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
	};
	this.preencherACAROSF0 = function preencherACAROS(data, isNovo) {
		var data_hoje = '';
		var marcadoNovo = '';
		var novo = 0;
		if (isNovo) {
			novo = 1;
		}
		if (data != null || data == '') {

			var data = data.split('/');
			if (data[1])
				data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 3;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherACAROSF1 = function preencherACAROSF1(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 7;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherACAROSF2 = function preencherACAROSF2(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 10;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherACAROSF3 = function preencherACAROSF3(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 15;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherACAROSF4a = function preencherACAROSF4a(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 21;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherACAROSF4b = function preencherACAROSF4b(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 30;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherEstrofiloHistaminaToxovacin1 = function preencherEstrofiloHistaminaToxovacin1(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 3;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherEstrofiloHistaminaToxovacin2 = function preencherEstrofiloHistaminaToxovacin2(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 7;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherEstrofiloHistaminaToxovacin3 = function preencherEstrofiloHistaminaToxovacin3(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 10;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherEstrofiloHistaminaToxovacin4 = function preencherEstrofiloHistaminaToxovacin4(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 15;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 10
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=12][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 11
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=13][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherAcneCandidinaHerpesDisidrovac1 = function preencherAcneCandidinaHerpesDisidrovac1(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 7;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 8
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=10][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 9
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=11][coluna=' + this.colunaatual + ']').attr('novo', novo)

	};
	this.preencherAcneCandidinaHerpesDisidrovac2 = function preencherAcneCandidinaHerpesDisidrovac2(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 7;
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 2
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherAcneCandidinaHerpesDisidrovac3 = function preencherAcneCandidinaHerpesDisidrovac3(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 7;

		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo + " Dias");
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)

		//Dose 2
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=4][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 3
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=5][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 4
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=6][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 5
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=7][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 6
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=8][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 7
		data_hoje.setDate(data_hoje.getDate() + periodo);
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=9][coluna=' + this.colunaatual + ']').attr('novo', novo)
	};
	this.preencherOraleInalantes = function preencherAcneCandidinaHerpesDisidrovac3(data, isNovo) {
		var data_hoje = '';
		var novo = 0;
		if (isNovo === true) {
			novo = 1;
		}
		if (data != null || data == '') {
			var data = data.split('/');
			data_hoje = new Date(parseInt(data[2]), parseInt(data[1] - 1), parseInt(data[0]));
		} else {
			data_hoje = new Date();
		}
		var periodo = 'Personalizado';
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').html(periodo);
		$('#tabelaImunoterapia > tr > th[linha=1][coluna=' + this.colunaatual + ']').attr('novo', novo)
		//Dose 1
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').html((data_hoje.getDate() < 10 ? '0' + data_hoje.getDate() : data_hoje.getDate()) + "/" + ((data_hoje.getMonth() + 1) < 10 ? '0' + (parseInt(data_hoje.getMonth()) + 1) : (data_hoje.getMonth() + 1)) + "/" + data_hoje.getFullYear());
		$('#tabelaImunoterapia > tr > th[linha=3][coluna=' + this.colunaatual + ']').attr('novo', novo)

	};

}
