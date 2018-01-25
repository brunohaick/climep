caixamodel = new function caixaModel() {
	/***
	 * Overryde de funç�o para abrir modal
	 * 
	 */
	this.abrirModalCaixaModel = function (idModal) {
		var obj;
		if (typeof idModal === 'string') {
			obj = $("#" + idModal);
		} else if (idModal && idModal.jquery) {
			obj = idModal;
		} else
			return;
		obj.modal({
			"backdrop": "static",
			"keyboard": true,
			"show": true
		});
	}

	this.fechar_modal = function (idModal) {
		var obj;
		if (typeof idModal === 'string') {
			obj = $("#" + idModal);
		} else if (idModal && idModal.jquery) {
			obj = idModal;
		} else
			return;
		obj.modal('hide');
	}

	this.selectedControlid;
	this.arrayModulos = [];
	this.valorComDesconto = 0;
	this.valorSemDesconto = 0;
	//variavel do tipo boolean para simbolizar quando o radio button de desconto for selecionado
	this.ModuloComDesconto = false;
	//array contendo os controles pendentes
	this.controlesPendentesArray = new Array();

	this.ControlesPendentes = function () {
		var flag = "getControles";
		$.ajax({
			type: "POST",
			data: {
				flag: flag
			},
			url: 'index.php?module=caixa&tmp=1',
			dataType: "json",
			async: false,
			success: function (result) {
				caixamodel.controlesPendentesArray = new Array();
				for (matrixKey in result) {
					caixamodel.controlesPendentesArray[matrixKey] = new controleModel(result[matrixKey]);
				}
			}
		});
//	$.post('index.php?module=caixa&tmp=1', {flag: flag},
//	function(result) {
//	    for (matrixKey in result) {
//		caixamodel.controlesPendentesArray[matrixKey] = new controleModel(result[matrixKey]);
//	    }
////             caixamodel.updateView();
//	}, "json");
	}

	this.getControlesPendentes = function () {
		return this.controlesPendentesArray;
	};
	/***
	 * @author Bruno Haick
	 * @description Esta função atualiza os dados na tela do caixa.
	 */
	this.updateView = function () {
//        caixamodel.ControlesPendentes();
		console.log("UpdateView");
		this.listaControlesPendentes();
		this.limpaDadosCaixa();
	};
	/***
	 * @author Bruno Haick
	 * @description Cria uma tabela html contendo uma lista
	 * de todos os controle pendentes existentes para a data de hoje
	 */
	this.listaControlesPendentes = function () {
		var strhtml = "";
		for (key in this.getControlesPendentes()) {
			if (this.getControlesPendentes()[key].modulo > 0) {
				strhtml += "<tr class='tr_controles_pendentes' style=\"color:red\" id='" + key + "' >";
			} else {
				strhtml += "<tr class='tr_controles_pendentes'  id='" + key + "' >";
			}
			strhtml += "<td>";
			strhtml += this.getControlesPendentes()[key].clienteTitular.getMatricula();
			strhtml += "</td>";
			strhtml += "<td>";
			strhtml += this.getControlesPendentes()[key].clienteTitular.getNome();
			strhtml += "</td>";
			strhtml += "<td>";
			strhtml += this.getControlesPendentes()[key].controle;
			strhtml += "</td>";
			strhtml += "<td>";
			strhtml += this.getControlesPendentes()[key].data;
			strhtml += "</td>";
			strhtml += "<td>";
			strhtml += this.getControlesPendentes()[key].usuario;
			strhtml += "</td>";
			strhtml += "</tr>";
		}
		$('#tbody_caixa_controles_pendentes').html(strhtml);
		$(".tr_controles_pendentes").dblclick(function () {

			$("input:text[name='dadoPagamento-definido_0']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_1']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_2']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_3']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_4']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_5']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_6']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_7']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_8']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_9']").prop('disabled', false);

			caixamodel.selectedControlid = $(this).attr('id');
			caixamodel.ModuloComDesconto = false;
			caixamodel.modulo = false;
			if (caixamodel.controlesPendentesArray[caixamodel.selectedControlid].categoria == '2') {
				$('#DescontoMedicoNoCaixa').prop('checked', true);
			} else {
				$('#DescontoMedicoNoCaixa').prop('checked', false);
			}
			caixamodel.resetaTabelaBandeiras();
			caixamodel.preencherDadosCliente($(this).attr('id'));
			$('.tabbable a[href="#servicosrealizados"]').tab('show');
			caixamodel.getServicostoTable($(this).attr('id'));
			caixamodel.listarModulosConfirmados();
			caixamodel.preencheFormReciboCaixa(caixamodel.selectedControlid);
			/*Outro comentario:
			 * for (key in caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos) {
			 */
			/*
			 * Adicionando evento de click nos botões "visita 1" e "visita 2"
			 */
			$('#caixa_visita1').unbind().click(function () {
				caixamodel.addVisita1();
			})
			$('#caixa_visita2').unbind().click(function () {
				caixamodel.addVisita2();
			})
			$('#caixa_cadastro_cliente').unbind().click(function () {
				window.open('index.php?module=editar&matricula=' + caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.getClienteId(), '_blank');
			})
		});
		$(".tr_controles_pendentes").hover(
				function () {
					$(this).css("background", "#C0D1E7");
				},
				function () {
					$(this).css("background", "");
				}
		);
	};

	/***
	 * @author Bruno Haick
	 * @description Limpa os dados contidos na tela do caixa.
	 */
	this.limpaDadosCaixa = function () {

		$('#total_servicos').html("Total:" + "");
		$('#convenio-caixa').val('');
		$('#nome-completo').html('');
		$('#num_controle').html("<h4> Nº </h4>");

		/* Aba Serviços a Serem Realizados Agora */
		$('#tbody_caixa_servicos').html("");

		/* Aba Programação Vacina */
		$('#select_programacaovacina').val('');
		$('#tbody_programacaovacina').html("");

		$('#radioButtonIndefinido').html("<input type='radio' name='radios'  value='1'/>Indefinido");
		$('#radioButtonDesconto').html("<input type='radio' name='radios' value='2'/> Desconto (R$)");
		$('#radioButtonCartao').html("<input type='radio' name='radios' value='3'/> Cartão (R$)");

		/* Aba Dados do Pagamento */

		caixamodel.resetaTabelaBandeiras();
		$('#num_parcelas_cartao_1').val('');
		$('#valor-cartao_1').val('');
		$('#autoriz-cartao_1').val('');
		$('#DescontoMedicoNoCaixa').prop('checked', false);
		$('#caixa-obs').val('');

		$("input:text[name='dadoPagamento-definido_0']").val('0.00');
		$("input:text[name='dadoPagamento-definido_1']").val('0.00');
		$("input:text[name='dadoPagamento-definido_2']").val('0.00');
		$("input:text[name='dadoPagamento-definido_3']").val('0.00');
		$("input:text[name='dadoPagamento-definido_4']").val('0.00');
		$("input:text[name='dadoPagamento-definido_5']").val('0.00');
		$("input:text[name='dadoPagamento-definido_6']").val('0.00');
		$("input:text[name='dadoPagamento-definido_7']").val('0.00');
		$("input:text[name='dadoPagamento-definido_8']").val('0.00');
		$("input:text[name='dadoPagamento-definido_9']").val('0.00');
		$("#dadoPagamento_recebido_total").val('0.00');

		$("#dadoPagamento-previsto_0").html('0.00');
		$("#dadoPagamento-previsto_1").html('0.00');
		$("#dadoPagamento-previsto_2").html('0.00');
		$("#dadoPagamento-previsto_3").html('0.00');
		$("#dadoPagamento-previsto_4").html('0.00');
		$("#dadoPagamento-previsto_5").html('0.00');
		$("#dadoPagamento-previsto_6").html('0.00');
		$("#dadoPagamento-previsto_7").html('0.00');
		$("#dadoPagamento-previsto_8").html('0.00');
		$("#dadoPagamento-previsto_9").html('0.00');

		$("#dadoPagamento_previsto_total").html('0.00');

		$("input:text[name='dadoPagamento-definido_0']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_1']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_2']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_3']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_4']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_5']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_6']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_7']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_8']").prop('disabled', false);
		$("input:text[name='dadoPagamento-definido_9']").prop('disabled', false);

		/* Aba Recibo */

//		$('#').prop('checked', false);		
		$('#nome_cliente_recibo').val('');
		$('#cpf_cliente_recibo').val('');
		$('#cep_cliente_recibo').val('');
		$('#ddd_cliente_recibo').val('');
		$('#telefone_cliente_recibo').val('');
		$('#endereco_cliente_recibo').val('');
		$('#bairro_cliente_recibo').val('');
		$('#categoria_cliente_recibo').val('');
		$('#cidade_cliente_recibo').val('');
		$('#estado_cliente_recibo').val('');
		$('#email_cliente_recibo').val('');
		$("input:checkbox[name='b']").prop('checked', false);
		$("input:checkbox#DescontoMedicoNoCaixa").prop('checked', false);
		$('#gerar_recibo_caixa').unbind();

		$('.tabbable a[href="#servicosrealizados"]').tab('show');
	}

	this.setDescontoMedico = function () {
		var desconto = 1;
		if (caixamodel.modulo) {
			$('#DescontoMedicoNoCaixa').prop('checked', false);
		}
		if ($('#DescontoMedicoNoCaixa').is(":checked")) {//If para reconhecer medicos
			var total = parseFloat($('#total_servicos').html().replace('h3', '').replace('h3', '').replace(/[^0-9.]/g, ''));
			console.log("TOTAL:" + total);
			var dinheiro = parseFloat($("input[name='dadoPagamento-definido_0']").val());
			var cheque = parseFloat($("input[name='dadoPagamento-definido_1']").val());
			var deposito = parseFloat($("input[name='dadoPagamento-definido_2']").val());
			var cartao = parseFloat($("input[name='dadoPagamento-definido_4']").val());

			if (dinheiro > 0 || cheque > 0 || deposito > 0) {
				desconto = 0.15;
			}

			if (cartao > 0) {
				desconto = 0.1;
			}
			visita1Seted = false;
			for (key in caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos) {
				if (caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialPrecoVista == 30) {
					visita1Seted = true;
					total = total - 30;
				}
			}
			visita2Seted = false;
			for (key in caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos) {
				if (caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialPrecoVista == 40) {
					visita2Seted = true;//bora ver se rola usar boolean em formula
					total = total - 40;
				}
			}

			if ($("input:text[name='dadoPagamento-definido_0']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_0']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_0']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_0']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_0']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_1']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_1']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_1']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_1']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_1']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_2']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_2']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_2']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_2']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_2']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_3']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_3']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_3']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_3']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_3']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_4']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_4']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_4']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));//Isso pra cartao..
			}else{
				$("input:text[name='dadoPagamento-definido_4']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_4']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_5']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_5']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_5']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_5']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_5']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_6']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_6']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_6']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_6']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_6']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_7']").val() > 30) {
				$("input:text[name='dadoPagamento-definido_7']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_7']").val() - (30 * visita1Seted) - (40 * visita2Seted)) * (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_7']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_7']").val()) * (1 - desconto)) , 2, '.', ','));
			}
			$("input[name='dadoPagamento-definido_8']").val(caixamodel.number_format(total * desconto, 2, '.', ','));//Só aqui usa total

			/*
			 * Bloquear os campos de valores do caixa para edição 
			 */
			$("input:text[name='dadoPagamento-definido_0']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_1']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_2']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_3']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_4']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_5']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_6']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_7']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_8']").prop('disabled', true);
			$("input:text[name='dadoPagamento-definido_9']").prop('disabled', true);

			//caixamodel.atualizaDadosPagamento();
		} else {
			var total = parseFloat($('#total_servicos').html().replace('h3', '').replace('h3', '').replace(/[^0-9.]/g, ''));
			var dinheiro = parseFloat($("input[name='dadoPagamento-definido_0']").val());
			var cheque = parseFloat($("input[name='dadoPagamento-definido_1']").val());
			var deposito = parseFloat($("input[name='dadoPagamento-definido_2']").val());
			var cartao = parseFloat($("input[name='dadoPagamento-definido_4']").val());

			if (dinheiro > 0 || cheque > 0 || deposito > 0) {
				desconto = 0.15;
			}

			if (cartao > 0) {
				desconto = 0.1;
			}

			if (caixamodel.visita1Seted) {
				total = total - 30;
			}

			if (caixamodel.visita2Seted) {
				total = total - 40;
			}
			if ($("input:text[name='dadoPagamento-definido_0']").val() > 30){
				$("input:text[name='dadoPagamento-definido_0']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_0']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_0']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_0']").val()) / (1 - desconto)), 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_1']").val() > 30){
				$("input:text[name='dadoPagamento-definido_1']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_1']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_1']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_1']").val()) / (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_2']").val() > 30){
				$("input:text[name='dadoPagamento-definido_2']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_2']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_2']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_2']").val() ) / (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_3']").val() > 30){
				$("input:text[name='dadoPagamento-definido_3']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_3']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_3']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_3']").val() ) / (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_4']").val() > 30){
				$("input:text[name='dadoPagamento-definido_4']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_4']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_4']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_4']").val() ) / (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_5']").val() > 30){
				$("input:text[name='dadoPagamento-definido_5']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_5']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_5']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_5']").val() ) / (1 - desconto)) , 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_6']").val() > 30){
				$("input:text[name='dadoPagamento-definido_6']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_6']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_6']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_6']").val()) / (1 - desconto)), 2, '.', ','));
			}
			if ($("input:text[name='dadoPagamento-definido_7']").val() > 30){
				$("input:text[name='dadoPagamento-definido_7']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_7']").val() - (30 * visita1Seted) - (40 * visita2Seted)) / (1 - desconto)) + (30 * visita1Seted) + (40 * visita2Seted), 2, '.', ','));
			}else{
				$("input:text[name='dadoPagamento-definido_7']").val(caixamodel.number_format(parseFloat(($("input:text[name='dadoPagamento-definido_7']").val() ) / (1 - desconto)), 2, '.', ','));
			}
			$("input[name='dadoPagamento-definido_8']").val('0.00');
			//caixamodel.atualizaDadosPagamento();

			$("input:text[name='dadoPagamento-definido_0']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_1']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_2']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_3']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_4']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_5']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_6']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_7']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_8']").prop('disabled', false);
			$("input:text[name='dadoPagamento-definido_9']").prop('disabled', false);
		}
	}
	this.preencheFormReciboCaixa = function (indice) {

		$('#nome_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getNome());
		$('#cpf_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getCpf());
		$('#cep_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getCep());
		$('#ddd_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getDdd());
		$('#telefone_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getTelefone());
		$('#endereco_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getEndereco());
		$('#bairro_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getBairro());
		$('#categoria_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getCategoria());
		$('#cidade_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getCidade());
		$('#estado_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getEstado());
		$('#email_cliente_recibo').val(this.getControlesPendentes()[indice].clienteTitular.getEmail());
		$('#gerar_recibo_caixa').unbind().click(function () {
			caixamodel.geraReciboCaixa(indice);
		});

	}

	this.geraReciboCaixa = function (indice) {

		var clienteTitular = JSON.stringify(this.getControlesPendentes()[indice].clienteTitular);
		var servicos = JSON.stringify(this.getControlesPendentes()[indice].servicos);
		$.ajax({
			url: 'index.php?module=caixa&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeReciboCaixa',
				clienteTitular: clienteTitular,
				servicos: servicos,
				total: parseFloat($('#total_servicos').html()),
				desconto: $("input:text[name='dadoPagamento-definido_8']").val()
			},
			success: function (resposta) {
				window.open('index2.php?module=recibo2', '_blank');
				caixamodel.ControlesPendentes();
				caixamodel.updateView();
			}
		});
	}

	/*
	 *@author Amir Zahlan
	 *@description Esta função busca todos os controles de um determinado servico
	 *@TODO Amir, voce est� se preoculpando somente com mostrar na tela. 
	 * Use a orientaç�o � objtos criada, veja o caixaModel ele � a raiz da arvore de OOP;
	 */
	this.getServicostoTable = function (idRow) {
		$('#tbody_caixa_servicos').html('');
		var html = "";
		var totalrs = 0;
		for (key2 in caixamodel.getControlesPendentes()[idRow].servicos) {
			console.log(caixamodel.getControlesPendentes()[idRow].servicos[key2].cliente_data);
			var idade = new Date(caixamodel.getControlesPendentes()[idRow].servicos[key2].cliente_data);
			var anos = new Date().getFullYear() - idade.getFullYear();
			var meses = new Date().getMonth() - idade.getMonth();
			var dias = new Date().getDay() - idade.getDay();
			if (dias < 0)
				meses--;
			if (meses < 0) {
				anos--;
				meses = 0;
			}
			idade = anos + "a " + meses + "m";
//        $('#tbody_caixa_servicos').html(html);
//        $('#total_servicos').html("Total:" + totalrs);
//        caixamodel.preencheDadosPagamento(idRow);

			html += "<tr>";
			html += "<td align='center'>" + caixamodel.getControlesPendentes()[idRow].servicos[key2].cliente_membro + "</td>";
			html += "<td align='center'>" + caixamodel.getControlesPendentes()[idRow].servicos[key2].cliente_nome + "</td>";
			html += "<td align='center'>" + idade + "</td>";
			html += "<td align='center'>" + caixamodel.getControlesPendentes()[idRow].servicos[key2].materialNome + "</td>";
			html += "<td align='center'>" + parseFloat(caixamodel.getControlesPendentes()[idRow].servicos[key2].materialPrecoVista).toFixed(2) + "</td>";
			html += "<td align='center'><select>" + caixamodel.getSelectedStatusSeletorTipo(caixamodel.getControlesPendentes()[idRow].servicos[key2].status) + "</select></td>";
			if (caixamodel.getControlesPendentes()[idRow].servicos[key2].modulo == "1") {
				html += "<td align='center'><select select='modulos' id='select_fpag_" + key2 + "' onClick='caixamodel.preencheDadosPagamento(" + idRow + ");'>" + caixamodel.getSelectedStatusSeletorFpag("modulos") + "<select></td>";
			} else {
				if (caixamodel.getControlesPendentes()[idRow].servicos[key2].japago == 1) {
					html += "<td align='center'><select id='select_fpag_" + key2 + "' onClick='caixamodel.preencheDadosPagamento(" + idRow + ");'>" + caixamodel.getSelectedStatusSeletorFpag("ja pago") + "<select></td>";
				}
				else {
					if (caixamodel.getControlesPendentes()[idRow].isconvenio == '1') {
						html += "<td align='center'><select id='select_fpag_" + key2 + "' onClick='caixamodel.preencheDadosPagamento(" + idRow + ");'>" + caixamodel.getSelectedStatusSeletorFpag("Convenio") + "<select></td>";
					} else {
						html += "<td align='center'><select id='select_fpag_" + key2 + "' onClick='caixamodel.preencheDadosPagamento(" + idRow + ");'>" + caixamodel.getSelectedStatusSeletorFpag("") + "<select></td>";
					}
				}
			}
			html += "</tr>";
			totalrs += parseFloat(caixamodel.getControlesPendentes()[idRow].servicos[key2].materialPrecoVista);
		}
		$('#tbody_caixa_servicos').html(html);
		$('#total_servicos').html("Total:" + totalrs);
		caixamodel.preencheDadosPagamento(idRow);
	};
	/*
	 *@author Amir Zahlan
	 *@description Esta função preenche os dados pessoas do cliente ao selecionar o controle
	 *@TODO Amir, esta funç�o est� no lugar errado, deveria estar no model do caixa, ele ser� o unico � interagir diretamente com a view
	 */
	this.preencherDadosCliente = function (idRow) {

		var mat = caixamodel.getControlesPendentes()[idRow].clienteTitular.getMatricula();
		var nome = caixamodel.getControlesPendentes()[idRow].clienteTitular.getNome();
		var cliente = '<h3> ' + nome + ' - ' + mat + ' </h3>';
		$('#convenio-caixa').val('');
		$('#nome-completo').html(cliente);
		$('#num_controle').html("<h4> Nº " + caixamodel.getControlesPendentes()[idRow].controle + "</h4>");
	};
	/*
	 *@author Amir
	 *@description Esta função preenche os dados de pagamento dos servicos ao selecionar o controle     
	 */
	this.preencheDadosPagamento = function (idRow) {
		$('#tabelaFormasPagamento > tbody').children().each(function () {
			$($(this).children().children().children()[1]).html("0.00");
			$($(this).children().children().children()[2]).val("0.00");
		});
		var serv = caixamodel.getControlesPendentes()[idRow].servicos;
		for (key in serv) {
			var valorVista = 0;
			var valorCartao = 0;
			var tipofp = $($($('#tbody_caixa_servicos').children()[key]).children()[6]).children().val();
			var tipofp2 = tipofp;
			if (serv[key]['Medicomarcado'] === false) {
				if (serv[key]['modulo'] == 1) {
					console.log("Desconto de 6%");
					valorVista = parseFloat(serv[key]['materialPrecoVista']);//Verificar Desconto a vista de 6% Não precisa!
					valorCartao = parseFloat(serv[key]['materialPrecoCartao']);// * 0.94;
				} else {
					valorVista = parseFloat(serv[key]['materialPrecoVista']);
					valorCartao = parseFloat(serv[key]['materialPrecoCartao']);
				}
			} else {
				console.log("Medico Marcado desconto de 15%");
				valorVista = (parseFloat(serv[key]['materialPrecoCartao']) / 0.85) * 0.80;
				valorCartao = parseFloat(serv[key]['materialPrecoCartao']);
			}
			if (tipofp2 == 'cartaocred' || tipofp2 == "cartaodeb") {
				tipofp2 = 'cartao';
			}
			if (tipofp == 'japago') {
				tipofp = 'japago';
				serv[key].japago = 1;
			} else {
				if (tipofp == 'cartaocred') {
					tipofp = 'cartao';
				} else {
					tipofp = 'dinheiro';
				}
			}
			serv[key]['forma_pagamento'] = tipofp2;

			var idS = key;
			console.log("Forma de pagamento:" + tipofp2);
			console.log("Chave::" + key);
			$('#tabelaFormasPagamento > tbody').children().each(function () {
				var previsto = $($(this).children().children().children()[0]).html();
				var valRelatorio = $($(this).children().children().children()[1]);
				var valRecebido = $($(this).children().children().children()[2]);
				previsto = removerAcento(previsto);
				previsto = removerCaracterEspecial(previsto);
				previsto = previsto.toLowerCase();
				console.log("Previsto::" + previsto);
				console.log("TipoFP::" + tipofp2);

				if (previsto == tipofp2) {
					console.log("Encontrado!");
					if (tipofp == 'cartao') {
						$(valRelatorio).html((parseFloat($(valRelatorio).html()) + parseFloat(valorCartao)).toFixed(2));
						$(valRecebido).val((parseFloat($(valRecebido).val()) + parseFloat(valorCartao)).toFixed(2));
						$($($('#tbody_caixa_servicos').children()[idS]).children()[4]).html(parseFloat(valorCartao).toFixed(2));
					} else {
						if (tipofp == 'japago') {
							$(valRelatorio).html((parseFloat($(valRelatorio).html()) + parseFloat(0)).toFixed(2));
							$(valRecebido).val((parseFloat($(valRecebido).val()) + parseFloat(0)).toFixed(2));
							$($($('#tbody_caixa_servicos').children()[idS]).children()[4]).html(parseFloat(0).toFixed(2));
						} else {
							$(valRelatorio).html((parseFloat($(valRelatorio).html()) + parseFloat(valorVista)).toFixed(2));
							$(valRecebido).val((parseFloat($(valRecebido).val()) + parseFloat(valorVista)).toFixed(2));
							$($($('#tbody_caixa_servicos').children()[idS]).children()[4]).html(parseFloat(valorVista).toFixed(2));
						}
					}
				}

			});
		}
		caixamodel.atualizaDadosPagamento();
	}

	/*
	 *@author Amir
	 *@description gera a lista do seletor definindo como selecionado o status igual ao valor de entrada     
	 */
	this.getSelectedStatusSeletorTipo = function (string) {
		string = string.replace(/[(). ]/g, '');
		string = string.replace(/[ç]/g, 'c');
		string = string.toLowerCase();
		var seletor = {realizado: 'Realizado', programado: 'Programado', externo: 'Externo',
			pagtoantecipado: 'Pagto Antecipado', doenca: 'Doença', arealizarhoje: 'A Realizar (Hoje)',
			marcado: 'Marcado', confirmado: 'Confirmado', cancelado: 'Cancelado', bloqueado: 'Bloqueado',
			pago: 'Pago', apagar: 'A Pagar', emaberto: 'Em Aberto', baixaparcial: 'Baixa Parcial',
			baixado: 'Baixado', livre: 'Livre'};
		var options = "";
		for (selectorKey in seletor) {
			if (selectorKey == string) {
				options += "<option selected='selected' value='" + selectorKey + "'>" + seletor[selectorKey] + "</option>"
			}
			else {
				options += "<option value='" + selectorKey + "'>" + seletor[selectorKey] + "</option>"
			}
		}
		return options;
	};
	/*
	 *@author Amir
	 *@description gera a lista do seletor definindo como selecionado o status igual ao valor de entrada     
	 */
	this.getSelectedStatusSeletorFpag = function (string) {
		console.log(string);
		string = string.replace(/[(). /-]/g, '');
		string = string.replace(/[ç]/g, 'c');
		string = string.replace(/[ã]/g, 'a');
		string = string.replace(/[é]/g, 'e');
		string = string.replace(/[ó]/g, 'o');
		string = string.replace(/[á]/g, 'a');
		string = string.replace(/[ô]/g, 'o');
		string = string.replace(/[ê]/g, 'e');
		string = string.toLowerCase();
		var seletor = {dinheiro: 'Dinheiro', chequedia: 'Cheque-dia', chequepre: 'Cheque-pré',
			cartaocred: 'Cartão Cred', cartaodeb: 'Cartão Deb', convenio: 'Convênio',
			cortesia: 'Cortesia', pendente: 'Pendente', modulos: 'Módulos', bonus: 'Bônus',
			japago: 'Já Pago', depositocc: 'Deposito C/C'};
		var options = "";
		for (selectorKey in seletor) {
			if (selectorKey == string) {
				options += "<option selected='selected' value='" + selectorKey + "'>" + seletor[selectorKey] + "</option>"
			}
			else {
				options += "<option value='" + selectorKey + "'>" + seletor[selectorKey] + "</option>"
			}
		}
		return options;
	};
	this.confirmaDadosPagamento = function (botao) {

		var idFilaEspera = $("#id-fila-espera-atual").val();
		var convenio = $("#convenio-caixa").val();
		var tmpcartao = new Array();
		for (var i = 1; i < $($('#tabela-bandeiras').children()).size(); i++) {
			var cartao = new Array();
			cartao[0] = $("#selectBandeiras_" + i + " option:selected").text();
			cartao[1] = $("#valor-cartao_" + i + "").val();
			cartao[2] = $("#num_parcelas_cartao_" + i + "").val();
			cartao[3] = $("#autoriz-cartao_" + i + "").val();
			cartao[4] = $("#selectBandeiras_" + i + " option:selected").val();
			tmpcartao.push(cartao);
		}

		var forma_pagamento = new Array();
		forma_pagamento[0] = $("input:text[name='dadoPagamento-definido_0']").val();
		forma_pagamento[1] = $("input:text[name='dadoPagamento-definido_1']").val();
		forma_pagamento[2] = $("input:text[name='dadoPagamento-definido_2']").val();
		forma_pagamento[3] = $("input:text[name='dadoPagamento-definido_3']").val();
		forma_pagamento[4] = $("input:text[name='dadoPagamento-definido_4']").val();
		forma_pagamento[5] = $("input:text[name='dadoPagamento-definido_5']").val();
		forma_pagamento[6] = $("input:text[name='dadoPagamento-definido_6']").val();
		forma_pagamento[7] = $("input:text[name='dadoPagamento-definido_7']").val();
		forma_pagamento[8] = $("input:text[name='dadoPagamento-definido_8']").val();
		forma_pagamento[9] = $("input:text[name='dadoPagamento-definido_9']").val();

		var soma_pagamento = parseFloat(0);
		for (i = 0; i < forma_pagamento.length - 1; i++) {
			soma_pagamento += parseFloat(forma_pagamento[i]);
		}
		soma_pagamento -= parseFloat(forma_pagamento[8]);
		var soma_previsto = parseFloat(0);
		for (i = 0; i < forma_pagamento.length; i++) {
			soma_previsto += parseFloat($("#dadoPagamento-previsto_" + i).html());
		}
		soma_previsto -= parseFloat(forma_pagamento[8]);
		soma_cartao_credito_recebido = parseFloat(forma_pagamento[4]);
		soma_cartao_debito_recebido = parseFloat(forma_pagamento[5]);
//        var tmpforma_pagamento = new Array();
//        tmpforma_pagamento.push(forma_pagamento);
		var pagamentodoControle = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].getPagamento();
		pagamentodoControle[0] = forma_pagamento;
		pagamentodoControle[1] = tmpcartao;
		$('#dadoPagamento_previsto_total').html(soma_previsto);
		$('#dadoPagamento_recebido_total').val(soma_pagamento);
		$('#total_servicos').html("<h3> Total:" + soma_previsto + "</h3>");
		caixamodel.getControlesPendentes()[caixamodel.selectedControlid].valortotal = soma_pagamento;
		caixamodel.save();
		alert('Pagamento Efetuado com sucesso!');
		$('.tabbable a[href="#recibo"]').tab('show');
	};
	/***
	 * @author Luiz Cortinhas
	 * @description Esta funç�o serve para salvar o caixa e suas funç�es, primeiro finalizando a guia_controle, depois salvando os pagamentos em guia_controle_has_forma_pagamento
	 * @returns {undefined}
	 * @algotimo
	 *      1 - Fecha o controle selecionado (OK)
	 *      1.1 - Salvar as formas de pagamento realizadas (OK)
	 *      2 - Inserir no RPS (OK)
	 *      3 - Baixa no estoque (OK)
	 *      4 - Mudar status dos serviços e no historico (OK)
	 *      5 - Inserir em Faturas todas as entradas de dinheiro. (Desenvolvendo)
	 */
	this.save = function () {
		// Executando Passo 1:
		var desconto = $("input:text[name='dadoPagamento-definido_8']").val();
		var controleId = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].id;
		var flag = "finalizarGuiaControleporId"; //Checked!
		var statusAtual;
		var isStatusdiferent = 0;
		var status = '25';
		for (var i = 0; i < caixamodel.getControlesPendentes()[caixamodel.selectedControlid].getServicos().length; i++) {
			if (i == 0) {
				statusAtual = $('#select_fpag_' + i).val();
				if ($('#select_fpag_' + i).val() == 'dinheiro') {
					status = '26';
				}
			} else {
				if (statusAtual != $('#select_fpag_' + i).val()) {
					status = '25';
				} else {
					if ($('#select_fpag_' + i).val() == 'dinheiro') {
						status = '26';
					} else {
						if ($('#select_fpag_' + i).val() == 'japago') {
							status = '27';
						}
					}
				}
			}
		}
		$.post('index.php?module=caixa&tmp=1', {
			flag: flag, controle_id: controleId, desconto: desconto, status_id: status
		}, function (result) {
			console.log(result);
			if (result == '1') {
				console.log("Guia Finalizada");
			} else {
				console.log("Problema ao Finaliza a Guia");
			}
		});
		//Executando Passo 1.5:
		var cartaoArray = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].getPagamento()[1];
		var pagamentoArray = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].getPagamento()[0];
		for (var i = 0; i < pagamentoArray.length; i++) {

			if (i == 0) {
				forma = 1;
			} else if (i == 1) {
				forma = 2;
			} else if (i == 2) {
				forma = 1;
			} else if (i == 3) {
				forma = 2;
			} else if (i == 5) {
				forma = 7;
			} else if (i == 6) {
				forma = 8;
			} else if (i == 7) {
				forma = 10;
			} else if (i == 8) {
				forma = 19;
			} else if (i == 9) {
				forma = 20;
			}
			var flag = "insereFormaPagamentoporGuia";
			var guiaid = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].id;
			var idForma = forma;
			var idBandeira = '';
			var valor = pagamentoArray[i];
			var parcelas = '';
			var autorizacao = '';
			$.ajax({
				type: "POST",
				data: {
					flag: flag,
					guiaid: guiaid,
					idforma: idForma,
					idbandeira: idBandeira,
					valor: valor,
					parcelas: parcelas,
					autorizacao: autorizacao,
					desconto: desconto
				},
				url: "index.php?module=caixa&tmp=1",
				dataType: "json",
				async: false,
				success: function (result) {
					if (result === 1) {
						console.log("Guia x Pagamento - Inseridos com Sucesso");
					} else {
						console.log("Guia x Pagamento - Falha ao inserir");
					}
				}
			});

			if (idForma == 1 || idForma == 2) {
				var vencimento = new Date();
				var datavencimento = vencimento.format('dd/mm/yyyy');
				$.ajax({
					type: "POST",
					data: {
						tipoForm: "insere_lancamentos_pelo_caixa",
						data_operacao: datavencimento,
						conta_corrente: '2',
						tipo_operacao: '551',
						numero_documento: '',
						valor: valor,
						observacao: "",
					},
					url: "index.php?module=lancamentos&tmp=1",
					dataType: "json",
					async: true,
					success: function (result) {

					}
				});
			}
		}
		desconto = desconto / cartaoArray.length;
		for (var i = 0; i < cartaoArray.length; i++) {
			//cartaoArray[i][4] = caixamodel.getFormadePagamentoIdbyNome(cartaoArray[i][0]);
			var flag = "insereFormaPagamentoporGuia";
			var guiaid = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].id;
			var idForma = '5';
			var idBandeira = cartaoArray[i][4];

			var valor = cartaoArray[i][1];
			var parcelas = cartaoArray[i][2];
			var autorizacao = cartaoArray[i][3];


			$.ajax({
				type: "POST",
				data: {
					flag: flag,
					guiaid: guiaid,
					idforma: idForma,
					idbandeira: idBandeira,
					valor: valor,
					parcelas: parcelas,
					autorizacao: autorizacao,
					desconto: desconto
				},
				url: "index.php?module=caixa&tmp=1",
				dataType: "json",
				async: false,
				success: function (result) {
					if (result === 1) {
						console.log("Guia x Pagamento - Inseridos com Sucesso");
					} else {
						console.log("Guia x Pagamento - Falha ao inserir");
					}
				}
			});
			//Passo 6 - Inserção do pagamento em Faturas
			if (idBandeira == '15' || idBandeira == '16' || idBandeira == '17') {
				flag = "insere_faturas";
				parcelasFatura = new Array();
				var hoje = new Date();
				var vencimento = new Date();
//                vencimento.setDate(hoje.getDate() + 30);
				var datavencimento = hoje.format('dd/mm/yyyy');
				$.ajax({
					type: "POST",
					data: {
						tipoForm: "fatura_data_parcelas",
						data_venc: datavencimento,
						valor: valor,
						num_parcelas: parcelas,
						intervalo_parcelas: "1"
					},
					url: "index.php?module=faturas&tmp=1",
					dataType: "json",
					async: false,
					success: function (result) {
						parcelasFatura = result;
					}
				});
				console.log("Ajax - POST ");
				console.log(parcelasFatura);
				$.ajax({
					type: "POST",
					data: {
						tipoForm: flag,
						numero_fatura: null,
						tipo_cliente: null,
						cliente: "Caixa Interior",
						empresa: "Climep",
						tipoDoc: "Faturas",
						banco: "Climep",
						moeda: "Real",
						plano_contas: cartaoArray[i][0].trim(),
						num_parcelas: parcelas,
						data_emissao: new Date().format('dd/mm/yyyy'),
						data_vencimento: datavencimento,
						intervalo_parcelas: "1",
						parcelas: parcelasFatura,
						obs: ""

					},
					url: "index.php?module=faturas&tmp=1",
					dataType: "json",
					async: false,
					success: function (result) {
						if (result === 1) {
							console.log("Guia x Fatura - Inserido com Sucesso");
						} else {
							console.log("Guia x Fatura - Falha ao inserir");
						}
					}
				});
				console.log("Ajax - POST Finish");
			} else {
				flag = "insere_faturas";
				var parcelasFatura = new Array();
				var hoje = new Date();
				var vencimento = new Date();
//                vencimento.setDate(hoje.getDate() + 30);
				var datavencimento = vencimento.format('dd/mm/yyyy');
				$.ajax({
					type: "POST",
					data: {
						tipoForm: "fatura_data_parcelas",
						data_venc: datavencimento,
						num_parcelas: parcelas,
						intervalo_parcelas: "30"
					},
					url: "index.php?module=faturas&tmp=1",
					dataType: "json",
					async: false,
					success: function (result) {
						parcelasFatura = result;
					}
				});
				$.ajax({
					type: "POST",
					data: {
						tipoForm: flag,
						numero_fatura: null,
						tipo_cliente: null,
						cliente: "Caixa Interior",
						empresa: "Climep",
						tipoDoc: "Faturas",
						banco: "Climep",
						moeda: "Real",
						plano_contas: cartaoArray[i][0].trim(),
						num_parcelas: parcelas,
						data_emissao: new Date().format('dd/mm/yyyy'),
						data_vencimento: datavencimento,
						intervalo_parcelas: "intervalo_parcelas",
						obs: "",
						parcelas: parcelasFatura
					},
					url: "index.php?module=faturas&tmp=1",
					dataType: "json",
					async: false,
					success: function (result) {
						if (result === 1) {
							console.log("Guia x Fatura - Inserido com Sucesso");
						} else {
							console.log("Guia x Fatura - Falha ao inserir");
						}
					}
				});
			}
		}

		//Executando Passo 2 Modificaç�o para inserir fatura:
		var flag = "insereRPSporGuia";
		var guiaid = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].id;
		var valortotal = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].valortotal;
		var nomeTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.nome;
		var dddTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.ddd;
		var telefoneTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.telefone;
		var cpfTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.cpf;
		var cepTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.cep;
		var emailTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.email;
		var estadoTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.estado;
		var cidadeTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.cidade;
		var bairroTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.bairro;
		var enderecoTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.endereco;
		var numeroLogradouroTomador = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.numeroEndereco;
		$.ajax({
			type: "POST",
			data: {
				flag: flag,
				guiaid: guiaid,
				valortotal: valortotal,
				nometomador: nomeTomador,
				dddtomador: dddTomador,
				telefonetomador: telefoneTomador,
				cpftomador: cpfTomador,
				ceptomador: cepTomador,
				emailtomador: emailTomador,
				estadotomador: estadoTomador,
				cidadetomador: cidadeTomador,
				bairrotomador: bairroTomador,
				enderecotomador: enderecoTomador,
				numerologradourotomador: numeroLogradouroTomador
			},
			url: "index.php?module=caixa&tmp=1",
			dataType: "json",
			async: false,
			success: function (result) {
				if (result === 1) {
					console.log("Guia x RPS - Inseridos com Sucesso");
				} else {
					console.log("Guia x RPS - Falha ao inserir");
				}
			}
		});
		//Executando Passo 3: 
		var servicos = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].getServicos();
		for (Key in servicos) {
			var materialid = servicos[Key].materialid;
			//var lote = '';//buscaLotebyMaterialId($id)
			var quantidade = 1;
			var hoje = new Date();
			var dia = hoje.getDate();
			var mes = hoje.getMonth();
			var ano = hoje.getFullYear();
			if (dia < 10)
				dia = "0" + dia
			if (ano < 2000)
				ano = "19" + ano
			//O mes começa em Zero, então soma-se 1
			var data = dia + "/" + (mes + 1) + "/" + ano;
			flag = "baixadoEstoqueporMaterialID";
			$.ajax({
				type: "POST",
				data: {
					flag: flag,
					materialid: materialid,
					data: data,
					quantidade: quantidade
				},
				url: "index.php?module=caixa&tmp=1",
				dataType: "json",
				async: false,
				success: function (result) {
					if (result === 1) {
						console.log("Guia x Estoque - Inseridos com Sucesso");
					} else {
						if (result === 2 && materialid > '100') {
//                            alert("O Material de numero (" + materialid + "), n�o possui lote no estoque");
						} else {
							console.log("Guia x Estoque - Falha ao inserir");
						}
					}
				}
			});
			//Passo4 - Status dos Serviços
			var status_id = 15;
			if (servicos[Key].japago === 1) {
				status_id = 27;
			} else {
				status_id = 15;
			}
			var tipofp = servicos[Key].forma_pagamento;
			if (tipofp == 'cartaocred') {
				tipofp = 5;
			} else if (tipofp == 'cartaodeb') {
				tipofp = 6;
			} else if (tipofp == 'desconto') {
				tipofp = 19;
			} else {
				tipofp = 1;
			}
			flag = "modificaStatusServicoPagoPorID";
			$.ajax({
				type: "POST",
				data: {
					flag: flag,
					servicoid: servicos[Key].id,
					forma_pagamento: tipofp,
					status: status_id
				},
				url: "index.php?module=caixa&tmp=1",
				dataType: "json",
				async: false,
				success: function (result) {
					if (result === 1) {
						console.log("Guia x Servico - Alterado com Sucesso");
					} else {
						console.log("Guia x Servico - Sem Alteraç�o");
					}
				}
			});
			//Passo 5 - Historico da Operaç�o  
			flag = "inseriHistoricoPorServico";
			var servicoid = servicos[Key].id;
			var servicostatus = servicos[Key].status;
			var ismodulo = servicos[Key].modulo;
			$.ajax({
				type: "POST",
				data: {
					flag: flag,
					servicoid: servicoid,
					ismodulo: ismodulo
				},
				url: "index.php?module=caixa&tmp=1",
				dataType: "json",
				async: false,
				success: function (result) {
					if (result === 1) {
						console.log("Guia x Historico - Inserido com Sucesso");
					} else {
						console.log("Guia x Historico - Falha ao inserir");
					}
				}
			});

		}
	};
	this.getFormadePagamentoIdbyNome = function (nome) {
		var flag = "getBandeiraIdByNome";
		id = 0;
		$.ajax({
			type: "POST",
			data: {flag: flag, nome: nome, titular_id: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.getClienteId()},
			url: "index.php?module=caixa&tmp=1",
			dataType: "json",
			async: false,
			success: function (result) {
//               result = $.parseJSON(result);
				//console.log(result);
				id = result[0]["id"];
			}
		});
		return id;
	}

	this.modulo = false;
	this.atualizaDadosPagamento = function () {
		var forma_pagamento = new Array();

		forma_pagamento[0] = $("input:text[name='dadoPagamento-definido_0']").val();
		forma_pagamento[1] = $("input:text[name='dadoPagamento-definido_1']").val();
		forma_pagamento[2] = $("input:text[name='dadoPagamento-definido_2']").val();
		forma_pagamento[3] = $("input:text[name='dadoPagamento-definido_3']").val();
		forma_pagamento[4] = $("input:text[name='dadoPagamento-definido_4']").val();
		forma_pagamento[5] = $("input:text[name='dadoPagamento-definido_5']").val();
		forma_pagamento[6] = $("input:text[name='dadoPagamento-definido_6']").val();
		forma_pagamento[7] = $("input:text[name='dadoPagamento-definido_7']").val();
		forma_pagamento[8] = $("input:text[name='dadoPagamento-definido_8']").val();//desconto
		forma_pagamento[9] = $("input:text[name='dadoPagamento-definido_9']").val();//Utilização de Credito
//        console.log("#####--- Pagamento Previsto ----#####");
		var soma_previsto = parseFloat(0);
		var soma_pagamento = parseFloat(0);
		//var controle = caixamodel.getControlesPendentes()[caixamodel.selectedControlid];
		//console.log(controle.getServicos());
		if (caixamodel.modulo) {
			if (caixamodel.ModuloComDesconto) {
				soma_pagamento = caixamodel.valorComDesconto;
			} else {
				soma_pagamento = caixamodel.valorSemDesconto;
				$("input:text[name='dadoPagamento-definido_4']").val(soma_pagamento);
			}
			soma_cartao_credito_recebido = parseFloat(soma_pagamento);

			//soma_cartao_debito_recebido = parseFloat(0);
		} else {
			soma_previsto = soma_pagamento;

			for (i = 0; i < forma_pagamento.length - 1; i++) {
				soma_pagamento += parseFloat(forma_pagamento[i]);
				//console.log(i + " : " + soma_pagamento);
			}
			soma_pagamento += parseFloat(forma_pagamento[9]);
			for (i = 0; i < forma_pagamento.length; i++) {
				soma_previsto += parseFloat($("#dadoPagamento-previsto_" + i).html());
				//console.log(i + " : " + $("#dadoPagamento-previsto_" + i).html());
			}
//        console.log("#####--- END ----#####");

			soma_cartao_credito_recebido = parseFloat(forma_pagamento[4]);
			soma_cartao_debito_recebido = parseFloat(forma_pagamento[5]);
		}
		soma_pagamento = soma_pagamento - (forma_pagamento[8]);//ok
		$('#dadoPagamento_previsto_total').html(parseFloat(soma_pagamento).toFixed(2));
		$('#dadoPagamento_recebido_total').val(parseFloat(soma_pagamento).toFixed(2));
		console.log("Somatorio Parcial:" + soma_pagamento);
		$('#total_servicos').html("<h3> Total:  " + parseFloat(soma_pagamento).toFixed(2) + "</h3>");
		if ($('#DescontoMedicoNoCaixa').is(":checked")) {
			this.setDescontoMedico();
		}

	};
	/*
	 *@author Amir
	 *@description gera a lista de modulos confirmados do cliente titular
	 */
	this.listarModulosConfirmados = function () {
		$.ajaxSetup({async: false});
		var flag = "getModulosConfirmados";
		$.post('index.php?module=caixa&tmp=1', {flag: flag, titular_id: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.getClienteId()},
		function (result) {
			if (result) {
				caixamodel.arrayModulos = new Array();
				var idmodulo = -100;
				$('#select_programacaovacina').html('');
				$('#select_programacaovacina').append("<option></option>");
				var tmpArrayModulos = new Array();
				for (key in result) {
					if (result[key]['modulos_id'] !== idmodulo) {
						caixamodel.arrayModulos[idmodulo] = tmpArrayModulos;
						tmpArrayModulos = new Array();
						idmodulo = result[key]['modulos_id'];
						$('#select_programacaovacina').append("<option value='" + result[key]['modulos_id'] + "'>" + result[key]['modulos_id'] + ' - ' + result[key]['data'] + ' - ' + result[key]['membro_nome'] + "</option>");
						tmpArrayModulos.push(result[key]);
					} else {
						tmpArrayModulos.push(result[key]);
					}
				}
				caixamodel.arrayModulos[idmodulo] = tmpArrayModulos;
			}
		}, "json");
	};
	/*
	 *@author Amir
	 *@description gera a lista de itens do modulo selecionado
	 */
	this.listarItensModulo = function () {
		var totalDinheiro = 0;
		var totalCartao = 0;
		$('#tbody_programacaovacina').html('');
		var html = "";
		console.log("Listando Modulos");
		var modulos = caixamodel.arrayModulos[$('#select_programacaovacina').val()];
		//Modified By Luiz Cortinhas, add module as Servico on Selected ControleModel.
		caixamodel.getControlesPendentes() [caixamodel.selectedControlid].addModuleasServico(caixamodel.getControlesPendentes() [caixamodel.selectedControlid].servicos, modulos);
		//End Modification

		var forma_pagamento = new Array();
		forma_pagamento[0] = $("input:text[name='dadoPagamento-definido_0']").val();
		forma_pagamento[1] = $("input:text[name='dadoPagamento-definido_1']").val();
		forma_pagamento[2] = $("input:text[name='dadoPagamento-definido_2']").val();
		forma_pagamento[3] = $("input:text[name='dadoPagamento-definido_3']").val();
		forma_pagamento[4] = $("input:text[name='dadoPagamento-definido_4']").val();
		forma_pagamento[5] = $("input:text[name='dadoPagamento-definido_5']").val();
		forma_pagamento[6] = $("input:text[name='dadoPagamento-definido_6']").val();
		forma_pagamento[7] = $("input:text[name='dadoPagamento-definido_7']").val();
		forma_pagamento[8] = $("input:text[name='dadoPagamento-definido_8']").val();
		var soma_pagamento = parseFloat(0);
		for (i = 0; i < forma_pagamento.length - 1; i++) {
			soma_pagamento += parseFloat(forma_pagamento[i]);
		}
		soma_pagamento -= parseFloat(forma_pagamento[8]);
		if (modulos) {
			$.post('index.php?module=caixa&tmp=1', {flag: 'getSomaValorModulo', idModulo: modulos[0]['modulos_id']},
			function (result) {
//                console.log(result);
				if (result) {
					caixamodel.valorComDesconto = result[0]['ComDesconto'];
					caixamodel.valorSemDesconto = result[0]['SemDesconto'];
				}
			}, "json");
		}
		//somatorio dos valores filtrados

//        console.log("Aqui");
		soma_previsto -= parseFloat(forma_pagamento[8]);
		soma_cartao_credito_recebido = caixamodel.valorSemDesconto;//parseFloat(forma_pagamento[4]);
		soma_cartao_debito_recebido = caixamodel.valorComDesconto;//parseFloat(forma_pagamento[5]);
		$('#dadoPagamento_previsto_total').html(parseFloat(soma_previsto).toFixed(2));
		$('#dadoPagamento_recebido_total').val(parseFloat(soma_pagamento).toFixed(2));
		$('#total_servicos').html("<h3> Total:  " + parseFloat(soma_previsto).toFixed(2) + "</h3>");
	};
	/*
	 *@author Amir
	 *@description gera a lista de modulos confirmados do cliente titular
	 */
	this.listarModulosConfirmados = function () {
		$.ajaxSetup({async: false});
		var flag = "getModulosConfirmados";
		$.post('index.php?module=caixa&tmp=1', {flag: flag, titular_id: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].clienteTitular.getClienteId()},
		function (result) {
			if (result) {
				caixamodel.arrayModulos = new Array();
				var idmodulo = -100;
				$('#select_programacaovacina').html('');
				$('#select_programacaovacina').append("<option></option>");
				var tmpArrayModulos = new Array();
				for (key in result) {
					if (result[key]['modulos_id'] !== idmodulo) {
						caixamodel.arrayModulos[idmodulo] = tmpArrayModulos;
						tmpArrayModulos = new Array();
						idmodulo = result[key]['modulos_id'];
						$('#select_programacaovacina').append("<option value='" + result[key]['modulos_id'] + "'>" + result[key]['modulos_id'] + ' - ' + result[key]['data'] + ' - ' + result[key]['membro_nome'] + "</option>");
						tmpArrayModulos.push(result[key]);
					} else {
						tmpArrayModulos.push(result[key]);
					}
				}
				caixamodel.arrayModulos[idmodulo] = tmpArrayModulos;
			}
		}, "json");
	};
	/*
	 *@author Amir
	 *@description gera a lista de itens do modulo selecionado
	 */
	this.listarItensModulo = function () {
		var totalDinheiro = 0;
		var totalCartao = 0;
		$('#tbody_programacaovacina').html('');
		var html = "";
		console.log("Listando Modulos");
		var modulos = caixamodel.arrayModulos[$('#select_programacaovacina').val()];
		//Modified By Luiz Cortinhas, add module as Servico on Selected ControleModel.
		//console.log(modulos);
		caixamodel.getControlesPendentes() [caixamodel.selectedControlid].addModuleasServico(caixamodel.getControlesPendentes() [caixamodel.selectedControlid].servicos, modulos);
		//End Modification
		if (modulos) {
			caixamodel.valorSemDesconto = parseFloat(0);
			caixamodel.valorComDesconto = parseFloat(0);
			$.post('index.php?module=caixa&tmp=1', {flag: 'getSomaValorModulo', idModulo: modulos[0]['modulos_id']},
			function (result) {
				console.log(result);
				if (result) {
					caixamodel.valorComDesconto = result[0]['ComDesconto'];
					caixamodel.valorSemDesconto = result[0]['SemDesconto'];
				}
			}, "json");
//                caixamodel.valorSemDesconto = caixamodel.valorSemDesconto + parseFloat(caixamodel.getControlesPendentes() [caixamodel.selectedControlid].getServicos()[i].materialPrecoVista);
//                caixamodel.valorComDesconto = caixamodel.valorComDesconto + parseFloat(caixamodel.getControlesPendentes() [caixamodel.selectedControlid].getServicos()[i].materialPrecoCartao);
		}

		if (caixamodel.arrayModulos[$('#select_programacaovacina').val()] !== null) {
			console.log("Preencheu Lista");
			for (key in modulos) {
				html += "<tr name='table-color' class='dayhead tr_programacao_vacina'>";
				html += "<th align='center'>";
				html += modulos[key]['membro_id']
				html += "</th>"
				html += "<th align='center'>";
				html += modulos[key]['membro_nome']
				html += "</th>"
				html += "<th align='center'>";
				html += modulos[key]['nome_material']
				html += "</th>"
				html += "<th align='center'>";
				if (parseFloat(modulos[key]['valor_cartao']) < parseFloat(modulos[key]['valor_vista'])) {
					html += parseFloat(modulos[key]['valor_vista']).toFixed(2);
				} else {
					html += parseFloat(modulos[key]['valor_cartao']).toFixed(2);
				}
				html += "</th>"
				html += "<th align='center'>";
				html += '0'
				html += "</th>"
				html += "</tr>";
				totalDinheiro += parseFloat(modulos[key]['valor_vista']);
				totalCartao += parseFloat(modulos[key]['valor_cartao']);
			}
		}

		var soma_previsto = parseFloat(0);
//        for (i = 0; i < forma_pagamento.length; i++) {
//            soma_previsto += parseFloat($("#dadoPagamento-previsto_" + i).html());
//        }

		$('#tbody_programacaovacina').html(html);
		$(".tr_programacao_vacina").hover(
				function () {
					$(this).css("background", "#C0D1E7");
				},
				function () {
					$(this).css("background", "");
				}
		);
		//$('#radioButtonIndefinido').html("<input type='radio' name='radios'  value='1'/>Indefinido");
		$('#radioButtonDesconto').html("<input type='radio' name='radios' value='2'/> Desconto (R$" + parseFloat(caixamodel.valorComDesconto) + ")");
		$('#radioButtonCartao').html("<input type='radio' name='radios' value='3'/> Cartão (R$" + parseFloat(caixamodel.valorSemDesconto) + ")");
//        $('#radioButtonAvistaCartao').html("<input type='radio' name='radios' value='3'/> À Vista + Cartão(R$" + totalCartao + ")");
		$('#radioButtonIndefinido').unbind().click(function (e) {
			if ($('#radioButtonIndefinido').select()) {
				var tmp = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos;
				for (key in tmp) {
					if (tmp[key].modulo === "1") {
						$('#select_fpag_' + key).val('modulos');
						caixamodel.preencheDadosPagamento(caixamodel.selectedControlid);
					}
				}
			}
		});
		$('#radioButtonDesconto').unbind().click(function (e) {
			if ($('#radioButtonDesconto').select()) {
				var tmp = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos;
				for (key in tmp) {
					if (tmp[key].modulo === "1") {
						$('#select_fpag_' + key).val('dinheiro');
						caixamodel.modulo = true;
						caixamodel.ModuloComDesconto = true;
						caixamodel.preencheDadosPagamento(caixamodel.selectedControlid);
					}
				}
			}
		});
		$('#radioButtonCartao').unbind().click(function (e) {
			if ($('#radioButtonCartao').select()) {
				var tmp = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos;
				for (key in tmp) {
					if (tmp[key].modulo === "1") {
						$('#select_fpag_' + key).val('cartaocred');
						caixamodel.modulo = true;
						caixamodel.ModuloComDesconto = false;
						caixamodel.preencheDadosPagamento(caixamodel.selectedControlid);
					}
				}
			}
		});
		$('#radioButtonAvistaCartao').unbind().click(function (e) {
			if ($('#radioButtonAvistaCartao').select()) {
				var tmp = caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos;
				for (key in tmp) {
					if (tmp[key].modulo === "1") {
						$('#select_fpag_' + key).val('cartaocred');
						caixamodel.preencheDadosPagamento(caixamodel.selectedControlid);
					}
				}
			}
		});
		caixamodel.getServicostoTable(caixamodel.selectedControlid);
	};
	/*
	 * Funcao para carregar os dados no modal de resumo e abrir o modal 
	 * para o usuário utilizar
	 * 
	 */
	this.modalResumoCaixa = function () {
		/*
		 * Adcionando evento de click no botão "Mostrar" dentro do modal 
		 * o botão responsável por carregar a tabela com o resumo. 
		 */
		$('#carrega_resumo_caixa').click(function () {
			caixamodel.carregaResumoCaixa();
		});
		/*
		 * Adcionando evento de click no botão "Mostrar" dentro do modal 
		 * o botão responsável por carregar a tabela com o resumo. 
		 */
		$('#imprime_resumo_caixa').unbind().click(function () {
			caixamodel.imprimeResumoCaixa();
		});
		caixamodel.abrirModalCaixaModel('boxes-resumocaixa');
	}

	/*
	 * Função para imprimir o pdf do resumo do caixa
	 * 
	 * @author Bruno Haick
	 * 
	 * @returns {undefined}
	 */
	this.imprimeResumoCaixa = function () {
		data_inicio = $("#data_inicio_resumo_caixa").val();
		data_fim = $("#data_fim_resumo_caixa").val();
		$.ajax({
			url: 'index.php?module=caixa&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeResumoCaixa',
				data_inicio: data_inicio,
				data_fim: data_fim
			},
			success: function (resposta) {
				window.open('index2.php?module=resumocaixa', '_blank');
			}
		});
	}

	/*
	 * Função para carregar a tabela de resumo de caixa no modal.
	 */
	this.carregaResumoCaixa = function () {

		data_inicio = $("#data_inicio_resumo_caixa").val();
		data_fim = $("#data_fim_resumo_caixa").val();
		$.post('index.php?module=caixa&tmp=1',
				{
					flag: "carregaResumoCaixa",
					data_inicio: data_inicio,
					data_fim: data_fim
				},
		function (result) {
			var html = "";
			console.log(result);
			if (result) {
				for (var i = 0; i < result.length; i++) {
					if (result[i]['nota_fiscal'] == null || result[i]['nota_fiscal'] === undefined) {
						result[i]['nota_fiscal'] = '';
					}

					html += "<tr name=\"table-color\" class=\"dayhead\">";
					html += "<th align=\"center\">" + result[i]['data'] + "</th>";
					html += "<th align=\"center\">" + result[i]['controle'] + "</th>";
					html += "<th align=\"center\">" + result[i]['matricula'] + "</th>";
					html += "<th align=\"center\">" + result[i]['responsavel'] + "</th>";
					html += "<th align=\"center\">" + result[i]['operador'] + "</th>";
					if (typeof result[i]['dinheiro'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['dinheiro'] + "</th>";
					} else {
						html += "<th></th>";
					}
					if (typeof result[i]['cheque'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['cheque'] + "</th>";
					} else {
						html += "<th></th>";
					}
					if (typeof result[i]['cartao'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['cartao'] + "</th>";
					} else {
						html += "<th></th>";
					}
					if (typeof result[i]['convenio'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['convenio'] + "</th>";
					} else {
						html += "<th></th>";
					}
					if (typeof result[i]['cortesia'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['cortesia'] + "</th>";
					} else {
						html += "<th></th>";
					}
					if (typeof result[i]['desconto'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['desconto'] + "</th>";
					} else {
						html += "<th></th>";
					}
					if (typeof result[i]['debito'] != 'undefined') {
						html += "<th style='text-align:right;'>" + result[i]['debito'] + "</th>";
					} else {
						html += "<th></th>";
					}
					html += "<th style='text-align:right;'>" + result[i]['valor_total'] + "</th>";
					html += "<th>" + result[i]['nota_fiscal'] + "</th>";
					html += "</tr>";
				}
			}
			$("#tbody-resumo-caixa").html(html);
		}, "json");
	};
	/*
	 *@author Amir, Luiz Cortinhas
	 *@description adiciona visita1 a model de servicos e atualiza a tabela de servicos
	 */
	this.visita1Seted = false;

	this.addVisita1 = function () {
		var array = new Array();
		var tmp = caixamodel.getControlesPendentes()[caixamodel.selectedControlid];
		var isseted = false;
		for (key in caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos) {
			if (caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialPrecoVista == 30) {
console.log("Visita1");
				visita1Seted = false;
				$.ajax({
					type: "POST",
					data: {
						flag: "removerServicoParaGuia",
						guiaid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].id,
						controleid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].controle,
						materialid: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialid,
						servicoid: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].id
					},
					url: "index.php?module=caixa&tmp=1",
					dataType: "json",
					async: false,
					success: function (result) {
						console.log("Ajax remove finish!");
					}});
				isseted = true;
				caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos.splice(key, 1);
				caixamodel.getServicostoTable(caixamodel.selectedControlid);
			}
		}
		if (!isseted) {
			for (key in caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos) {
				if (caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialPrecoVista == 30) {
					console.log("Removendo!");
					$.post('index.php?module=caixa&tmp=1',
							{
								flag: "removerServicoParaGuia",
								guiaid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].id,
								controleid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].controle,
								materialid: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialid,
								servicoid: caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].id
							},
					function (result) {
						console.log('Servico removido com sucesso no banco de dados.');
					}, "json");
				}
			}
			visita1Seted = true;
			$.post('index.php?module=caixa&tmp=1',
					{flag: "inserirServicoParaGuia",
						guiaid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].id,
						controleid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].controle,
						materialid: '902',
						clienteid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].clienteTitular.clienteId,
						preco: 30},
			function (result) {
				data = result;
				//console.log('add visita 1');
				array['cliente_nome'] = tmp.clienteTitular.nome;
				array['cliente_id'] = tmp.clienteTitular.clienteId;
				array['data_nascimento'] = tmp.clienteTitular.data_nascimento;
				array['membro'] = tmp.clienteTitular.membro;
				array['material_nome'] = "vacina em casa";
				array['tipo_servico'] = 1;
				array['preco_cartao'] = 30;
				array['material_id'] = 902; // Se ocorrer problemas desconfie do id do material no banco.
				array['servico_preco'] = 30;
				array['tipo_material_id'] = 4;
				array['status'] = "A REALIZAR (HOJE)";

				console.log('Servico inserido com sucesso no banco de dados.');
				console.log('Resultado::' + data);
				array['servico_id'] = data;
				caixamodel.getControlesPendentes() [caixamodel.selectedControlid].addVisitasServico(array);
				caixamodel.getServicostoTable(caixamodel.selectedControlid);
			}, "json");
		}
	}

	/*
	 *@author Amir
	 *@description adiciona visita2 a model de servicos e atualiza a tabela de servicos
	 */
	this.visita2Seted = false;
	this.addVisita2 = function () {
		var array = new Array();
		var tmp = caixamodel.getControlesPendentes()[caixamodel.selectedControlid];
		var isseted = false;
		for (key in caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos) {
			if (caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialNome === "vacina em casa 2" && caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos[key].materialPrecoVista === 40) {
				visita2Seted = false;
				caixamodel.getControlesPendentes()[caixamodel.selectedControlid].servicos.splice(key, 1);
				caixamodel.getServicostoTable(caixamodel.selectedControlid);
				isseted = true;
			}
		}
		if (!isseted) {
			console.log('add visita 2');
			array['cliente_nome'] = tmp.clienteTitular.nome;
			array['cliente_id'] = tmp.clienteTitular.clienteId;
			array['data_nascimento'] = tmp.clienteTitular.data_nascimento;
			array['membro'] = tmp.clienteTitular.membro;
			array['material_nome'] = "vacina em casa 2";
			array['precocartao'] = 40;
			array['servico_preco'] = 40;
			array['status'] = "A REALIZAR (HOJE)";
			visita2Seted = true;
			$.post('index.php?module=caixa&tmp=1',
					{flag: "inserirServicoParaGuia",
						guiaid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].id,
						controleid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].controle,
						materialid: '903',
						clienteid: caixamodel.getControlesPendentes() [caixamodel.selectedControlid].clienteTitular.clienteId,
						preco: 40},
			function (result) {
				data = result;
				console.log('add visita 2');
				array['cliente_nome'] = tmp.clienteTitular.nome;
				array['cliente_id'] = tmp.clienteTitular.clienteId;
				array['data_nascimento'] = tmp.clienteTitular.data_nascimento;
				array['membro'] = tmp.clienteTitular.membro;
				array['material_nome'] = "vacina em casa 2";
				array['tipo_servico'] = 1;
				array['preco_cartao'] = 40;
				array['material_id'] = 903; // Se ocorrer problemas desconfie do id do material no banco.
				array['servico_preco'] = 40;
				array['tipo_material_id'] = 4;
				array['status'] = "A REALIZAR (HOJE)";
				console.log('Servico inserido com sucesso no banco de dados.');
				console.log('Resultado::' + data);
				array['servico_id'] = data;
				caixamodel.getControlesPendentes() [caixamodel.selectedControlid].addVisitasServico(array);
				caixamodel.getServicostoTable(caixamodel.selectedControlid);
			}, "json");
			caixamodel.getControlesPendentes() [caixamodel.selectedControlid].addVisitasServico(array);
			caixamodel.getServicostoTable(caixamodel.selectedControlid);
		}
	};

	this.insereNovaLinhaBandeira = function (linha) {

		var valorCartao = $("input:text[name='dadoPagamento-definido_4']").val();
		$("input#valor-cartao_" + linha).val(valorCartao);

		var nbandeiras = $($('#tabela-bandeiras').children()).size() + 1;
		var html = "<tr numbandeira='" + nbandeiras + "' class='dayhead' name='table-color'>\
        <th align='center'>\
			<select id='selectBandeiras_" + nbandeiras + "' onchange='caixamodel.insereNovaLinhaBandeira(" + nbandeiras + ")'>\
				<option></option>\
				<option value='1'> VISA </option>\
				<option value='2'> VISA 2x a 6x </option>\
				<option value='3'> VISA 7x a 12x </option>\
				<option value='4'> CREDICARD </option>\
				<option value='5'> Credicard 2x a 6x </option>\
				<option value=6> Credicard 7x a 12x </option>\
				<option value='7'> AMEX </option>\
				<option value='8'> YAMADA </option>\
				<option value='9'> DINNERS </option>\
				<option value='10'> Dinners 2x a 6x </option>\
				<option value='11'> Dinners 7x a 12x </option>\
				<option value='12'> ELO </option>\
				<option value='13'> Elo 2x a 6x </option>\
				<option value='14'> Elo 7x a 12x </option>\
				<option value='15'> ELETRO-VISA </option>\
				<option value='16'> MAESTRO </option>\
				<option value='17'> Elo dÃƒÂ©bito </option>\
			</select>\
        </th>\
        <th align='center'>\
			<input type='text' onkeypress='return(MascaraMoeda(this, '', '.', event))' id='valor-cartao_" + nbandeiras + "' name='valor-cartao' style='width: 120px;'>\
        </th>\
        <th align='center'>\
			<select id='num_parcelas_cartao_" + nbandeiras + "'>\
				<option></option>\
				<option> 1 </option>\
				<option> 2 </option>\
				<option> 3 </option>\
				<option> 4 </option>\
				<option> 5 </option>\
				<option> 6 </option>\
				<option> 7 </option>\
				<option> 8 </option>\
				<option> 9 </option>\
				<option> 10 </option>\
				<option> 11 </option>\
			</select>\
        </th>\
        <th align='center'>\
			<input type='text' id='autoriz-cartao_" + nbandeiras + "' name='valor-cartao' style='width: 120px;'>\
        </th>\
        </tr>\n\
        ";
		if (linha == nbandeiras - 1)
			$('#tabela-bandeiras').append(html);
	};
	/*
	 * @description Função para preencher os dados do modal de Notas Fiscais.
	 * Por padrão, a exibe a lista de Notas Fiscais pendentes de serem emitidas.
	 * Depois cria os eventos para os botões no modal.
	 * 
	 * @author Bruno Haick
	 * 
	 * @returns {undefined}
	 */
	this.modalNotaFiscal = function () {

		caixamodel.carregaNotasFiscaisNaoEmitidas();
		/*
		 * @description Adicionando evento ao checkbox: 
		 * 
		 *  - Se Marcar o CheckBox: Exibe uma lista de notas (NFSe)
		 * já emitidas ao webservice da prefeitura.
		 * 
		 *  - Se Desmarcar o CheckBox: Exibe uma lista de notas (NFSe)
		 * NÃO emitidas ao webservice da prefeitura.
		 * 
		 */
		$('#nfse_notas_emitidas').click(function () {
			if ($('#nfse_notas_emitidas').is(":checked")) {
				caixamodel.carregaNotasFiscaisEmitidas();
			} else {
				caixamodel.carregaNotasFiscaisNaoEmitidas();
			}
		});
		caixamodel.abrirModalCaixaModel('boxes-emissaonfs');
	};
	/*
	 * @description Carrega do banco de dados as notas ainda não
	 * emitidas, ou seja, não enviadas ao webservice da prefeitura.
	 * Exibe o resultado em forma de tabela no modal de NFSe.
	 * 
	 * @author Bruno Haick
	 * 
	 * @returns {undefined}
	 * 
	 * @TODO corrigir nome de indices de array conforme query
	 */
	this.carregaNotasFiscaisNaoEmitidas = function () {

		$.ajax({
			url: 'index.php?module=caixa&tmp=1',
			type: 'POST',
			data: {
				flag: 'buscaNotasNaoEmitidas'
			},
			success: function (data) {
				data = $.parseJSON(data);
				console.log(data);
				$("tbody#tbody-nfse").html("");
				var html = "";
				if (data !== "null") {
					for (var i = 0; i < data.length; i++) {
						html += "<tr name='table-color' class='dayhead tr_nfse'>";
						html += "<th style='text-align:center;'> <input id='nfse_rpsid_editar' type='checkbox' value='" + data[i]['rps_id'] + "' /> </th>";
						html += "<th> " + data[i]['numero_controle'] + " </th>";
						html += "<th> " + data[i]['numero_nfse'] + " </th>";
						html += "<th> " + data[i]['matricula'] + " </th>";
						html += "<th> " + data[i]['membro'] + " </th>";
						html += "<th> " + data[i]['CPFCNPJTomador'] + " </th>";
						html += "<th> " + data[i]['nomeTomador'] + " </th>";
						html += "<th style='text-align:right;'> " + caixamodel.number_format(data[i]['ValorTotalServicos'], 2, ',', '.') + " </th>";
						html += "</tr>";
					}
				}

				/*
				 * Disabilitando botões já que na listagem de notas emitidas,
				 * não poderá ser aceito o usuário exclui ou imprimir uma nota.
				 */
				$('#nfse_excluir_nota').unbind();
				$('#nfse_imprimir_nota').unbind();
				$('#nfse_excluir_nota').attr('disabled', true);
				$('#nfse_imprimir_nota').attr('disabled', true);

				/*
				 * habilitando botões.
				 */
				$('#nfse_processar_nota').attr('disabled', false);
				$('#nfse_editar_nota').attr('disabled', false);
				/*
				 * Adicionando evento ao botão de processar NFSe
				 */
				$('#nfse_processar_nota').unbind().click(function () {
					caixamodel.processarNfse();
				});
				/*
				 * Adicionando evento ao botão de Editar NFSe
				 */
				$('#nfse_editar_nota').unbind().click(function () {
					caixamodel.modalEditarNfse();
				});

				var cb = false;
				$("tbody#tbody-nfse").html(html);
				$("input#nfse_rpsid_editar").unbind().mousedown(function (e) {
					cb = true;
				});
				$(".tr_nfse").unbind().mousedown(function () {
					var isCheck = $($($('.tr_nfse').children()[0]).children()).is(':checked');
					if (cb != true) {
						$($($('.tr_nfse').children()[0]).children()).prop('checked', !isCheck);
						cb = false;
					}
					if (!isCheck)
						cb = false;
				});
				$(".tr_nfse").hover(
						function () {
							$(this).css("background", "#C0D1E7");
						},
						function () {
							$(this).css("background", "");
						});
			}
		});
	};
	/*
	 * @description Carrega do banco de dados as notas já emitidas,
	 * ou seja, já enviadas ao webservice da prefeitura. Exibe o resultado
	 * em forma de tabela no modal de NFSe.
	 * 
	 * @author Bruno Haick
	 * 
	 * @returns {undefined}
	 * 
	 * @TODO corrigir nome de indices de array conforme query
	 */
	this.carregaNotasFiscaisEmitidas = function () {

		$.ajax({
			url: 'index.php?module=caixa&tmp=1',
			type: 'POST',
			data: {
				flag: 'buscaNotasEmitidas'
			},
			success: function (data) {
				data = $.parseJSON(data);
				$("tbody#tbody-nfse").html("");
				var html = "";
				if (data !== "null") {
					for (var i = 0; i < data.length; i++) {
						html += "<tr name='table-color' class='dayhead tr_nfse'>";
						html += "<th style='text-align:center;'> <input id='nfse_rpsid_editar' type='checkbox' value='" + data[i]['rps_id'] + "' /> </th>";
						html += "<th> " + data[i]['numero_controle'] + " </th>";
						html += "<th> " + data[i]['numero_nfse'] + " </th>";
						html += "<th> " + data[i]['matricula'] + " </th>";
						html += "<th> " + data[i]['membro'] + " </th>";
						html += "<th> " + data[i]['CPFCNPJTomador'] + " </th>";
						html += "<th> " + data[i]['nomeTomador'] + " </th>";
						html += "<th style='text-align:right;'> " + caixamodel.number_format(data[i]['ValorTotalServicos'], 2, ',', '.') + " </th>";
						html += "</tr>";
					}
				}

				/*
				 * Disabilitando botões já que na listagem de notas emitidas,
				 * não poderá ser aceito o usuário processar ou editar uma nota.
				 */
				$('#nfse_processar_nota').unbind();
				$('#nfse_editar_nota').unbind();
				$('#nfse_processar_nota').attr('disabled', true);
				$('#nfse_editar_nota').attr('disabled', true);

				$('#nfse_excluir_nota').attr('disabled', false);
				$('#nfse_imprimir_nota').attr('disabled', false);
				/*
				 * Adicionando evento ao botão de Excluir NFSe
				 */
				$('#nfse_excluir_nota').unbind().click(function () {
					caixamodel.excluirNfse();
				});
				/*
				 * Adicionando evento ao botão Imprimir NFSe
				 */
				$('#nfse_imprimir_nota').unbind().click(function () {
					caixamodel.imprimeNfse();
				});


				var cb = false;
				$("tbody#tbody-nfse").html(html);
				$("input#nfse_rpsid_editar").unbind().mousedown(function (e) {
					cb = true;
				});
				$(".tr_nfse").unbind().mousedown(function () {
					var isCheck = $($($('.tr_nfse').children()[0]).children()).is(':checked');
					if (cb != true) {
						$($($('.tr_nfse').children()[0]).children()).prop('checked', !isCheck);
						cb = false;
					}
					if (!isCheck)
						cb = false;
				});
				$(".tr_nfse").hover(
						function () {
							$(this).css("background", "#C0D1E7");
						},
						function () {
							$(this).css("background", "");
						});
			}
		});
	};
	/*
	 * @description Função para enviar os dados para a geração do xml
	 * da nota fiscal, e depois envia ao webservice da prefeitura.
	 * 
	 * @author Bruno Haick
	 * 
	 * @returns {undefined}
	 * 
	 * @TODO verificar.
	 */
	this.processarNfse = function () {
		/**
		 * Este bloco pode ser utilizado para enviar mais de uma rps
		 * para o xml caso queira se mandar rps em lote.
		 */
//		var nfe = new Array();
//		$("input[type='checkbox']:checked").each(function(i) {
//			nfe.push($(this).val());
//		});

		var idrps = $("#nfse_rpsid_editar:checked").val();
		var aliquota = $('#nfse_aliquota option:selected').val();
		var tipo_recolhimento = $('#nfse_tipo_recolhimento option:selected').val();

		if (!$.isNumeric(idrps)) {
			alert('Selecione um item da lista.');
			return;
		}

		/*
		 * Atualiza a aliquota na tabela da rps escolhida
		 */
		$.post('index.php?module=caixa&tmp=1', {
			flag: 'editaDadosNotaFiscal',
			processar_nota: true,
			idrps: idrps,
			aliquota: aliquota,
			tipo_recolhimento: tipo_recolhimento
		},
		function (resultado) {
//			alert(resultado);
		});
		if ($('#nfse_notas_emitidas').is(":checked")) {
			caixamodel.carregaNotasFiscaisEmitidas();
		} else {
			caixamodel.carregaNotasFiscaisNaoEmitidas();
		}

//		$.post('index.php?module=caixa&tmp=1',{
//			flag: 'enviaNFE',
//			idrps: idrps
//		},function(data) {
//			$.post('lib/nfe/index.php',{data: data, tipo_xml: 'enviaNFE'},
//			function(resultado) {
//				if ($('#nfse_notas_emitidas').is(":checked")) {
//					caixamodel.carregaNotasFiscaisEmitidas();
//				} else {
//					caixamodel.carregaNotasFiscaisNaoEmitidas();
//				}
//			});
//		});
	};
	/*
	 * @description Preenche o modal de edição de NFSe.
	 * 
	 * @returns {undefined}
	 */

	this.modalEditarNfse = function () {

		var idrps = $("#nfse_rpsid_editar:checked").val();
		if (!$.isNumeric(idrps)) {
			alert('Selecione um item da lista.');
			return;
		}

		caixamodel.preencheFormNfse(idrps);
		$('#nfse_botao_editar').unbind().click(function () {
			caixamodel.editarNfse(idrps);
		});
		caixamodel.abrirModalCaixaModel('boxes-editanfs');
	};
	/*
	 * @description Preenche o formulário para edição de dados
	 * da NFSe selecionada.
	 * 
	 * @returns {undefined}
	 */
	this.preencheFormNfse = function (idrps) {

		$.ajax({
			type: "POST",
			data: {
				flag: 'buscaNotasNaoEmitidasPorIdRps',
				idrps: idrps
			},
			url: "index.php?module=caixa&tmp=1",
			dataType: "json",
			async: false,
			success: function (data) {
				$('#nfse_matricula').val(data['matricula']);
				$('#nfse_membro').val(data['mamembro']);
				$('#nfse_valor_servico').val(caixamodel.number_format(data['ValorTotalServicos'], 2, ',', '.'));
				$('#nfse_data_emissao').val(data['dataEmissaoRPS']);
				$('#nfse_cpf_cnpj').val(data['CPFCNPJTomador']);
				$('#nfse_razao_social').val(data['RazaoSocialTomador']);
				$('#nfse_tipo_log').val(data['TipoLogradouroTomador']);
				$('#nfse_logradouro').val(data['LogradouroTomador']);
				$('#nfse_numero_casa').val(data['NumeroEnderecoTomador']);
				$('#nfse_bairro').val(data['BairroTomador']);
				$('#nfse_cidade').val(data['CidadeTomador']);
				$('#nfse_estado').val('PA');
				$('#nfse_cep').val(data['CEPTomador']);
				$('#nfse_email').val(data['EmailTomador']);
				$('#nfse_descricao').val(data['CidadeTomadorDescricao']);
				$('#nfse_ddd').val(data['DDDTomador']);
				$('#nfse_telefone').val(data['TelefoneTomador']);
			}
		});
	}

	/*
	 * @description Editar os dados da Nota Fiscal.
	 * 
	 * @returns {undefined}
	 */
	this.editarNfse = function (idrps) {

		var idrps = idrps;
		var valor = $('#nfse_valor_servico').val();
		var data_emissao = $('#nfse_data_emissao').val();
		var cpf_cnpj = $('#nfse_cpf_cnpj').val();
		var razao_social = $('#nfse_razao_social').val();
		var tipo_log = $('#nfse_tipo_log').val();
		var logradouro = $('#nfse_logradouro').val();
		var numero_casa = $('#nfse_numero_casa').val();
		var bairro = $('#nfse_bairro').val();
		var cidade = $('#nfse_cidade').val();
		var estado = $('#nfse_estado').val();
		var cep = $('#nfse_cep').val();
		var email = $('#nfse_email').val();
		var descricao = $('#nfse_descricao').val();
		var ddd = $('#nfse_ddd').val();
		var telefone = $('#nfse_telefone').val();
		$.ajax({
			url: 'index.php?module=caixa&tmp=1',
			type: 'POST',
			data: {
				flag: 'editaDadosNotaFiscal',
				idrps: idrps,
				valor: valor,
				data_emissao: data_emissao,
				cpf_cnpj: cpf_cnpj,
				razao_social: razao_social,
				tipo_log: tipo_log,
				logradouro: logradouro,
				numero_casa: numero_casa,
				bairro: bairro,
				cidade: cidade,
				estado: estado,
				cep: cep,
				email: email,
				descricao: descricao,
				ddd: ddd,
				telefone: telefone
			},
			success: function (data) {
				data = $.parseJSON(data);
				if (data == true) {
					alert('Atualizado com Sucesso.');
					caixamodel.carregaNotasFiscaisNaoEmitidas();
					fechar_modal('boxes-editanfs');
				}
			}
		});
	}

	/*
	 * @descritpion Cria o xml e envia a requisição para 
	 * o webservice da prefeitura, para deleção de RPS.
	 * 
	 * @author Bruno Haick
	 * 
	 * @returns {undefined}
	 */
	this.excluirNfse = function () {
		var idrps = $("#nfse_rpsid_editar:checked").val();
		if (!$.isNumeric(idrps)) {
			alert('Selecione um item da lista.');
			return;
		}

		/*
		 * Atualiza a aliquota na tabela da rps escolhida
		 */
		$.post('index.php?module=caixa&tmp=1', {
			flag: 'editaDadosNotaFiscal',
			excluir: true,
			idrps: idrps
		},
		function (data) {
			if ($('#nfse_notas_emitidas').is(":checked")) {
				caixamodel.carregaNotasFiscaisEmitidas();
			} else {
				caixamodel.carregaNotasFiscaisNaoEmitidas();
			}
		});
	};
	/*
	 * @description cria o pdf da Nota Fiscal
	 * 
	 * @returns {undefined}
	 */
	this.imprimeNfse = function () {

		var idrps = $("#nfse_rpsid_editar:checked").val();
		if (!$.isNumeric(idrps)) {
			alert('Selecione um item da lista.');
			return;
		}

		$.ajax({
			url: 'index.php?module=caixa&tmp=1',
			type: 'POST',
			data: {
				flag: 'imprimeNotaFiscal',
				idrps: idrps
			},
			success: function () {
				window.open('index2.php?module=notafiscal', '_blank');
			}
		});
	};
	/* 
	 * Bruno Haick: No caso de nosso software estamos utilizando a opção 
	 *				do exemplo 3
	 * 
	 * From: http://phpjs.org/functions
	 * +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	 * +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	 * +     bugfix by: Michael White (http://getsprink.com)
	 * +     bugfix by: Benjamin Lupton
	 * +     bugfix by: Allan Jensen (http://www.winternet.no)
	 * +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	 * +     bugfix by: Howard Yeend
	 * +    revised by: Luke Smith (http://lucassmith.name)
	 * +     bugfix by: Diogo Resende
	 * +     bugfix by: Rival
	 * +      input by: Kheang Hok Chin (http://www.distantia.ca/)
	 * +   improved by: davook
	 * +   improved by: Brett Zamir (http://brett-zamir.me)
	 * +      input by: Jay Klehr
	 * +   improved by: Brett Zamir (http://brett-zamir.me)
	 * +      input by: Amir Habibi (http://www.residence-mixte.com/)
	 * +     bugfix by: Brett Zamir (http://brett-zamir.me)
	 * +   improved by: Theriault
	 * +      input by: Amirouche
	 * +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	 * *     example 1: number_format(1234.56);
	 * *     returns 1: '1,235'
	 * *     example 2: number_format(1234.56, 2, ',', ' ');
	 * *     returns 2: '1 234,56'
	 * *     example 3: number_format(1234.5678, 2, '.', '');
	 * *     returns 3: '1234.57'
	 * *     example 4: number_format(67, 2, ',', '.');
	 * *     returns 4: '67,00'
	 * *     example 5: number_format(1000);
	 * *     returns 5: '1,000'
	 * *     example 6: number_format(67.311, 2);
	 * *     returns 6: '67.31'
	 * *     example 7: number_format(1000.55, 1);
	 * *     returns 7: '1,000.6'
	 * *     example 8: number_format(67000, 5, ',', '.');
	 * *     returns 8: '67.000,00000'
	 * *     example 9: number_format(0.9, 0);
	 * *     returns 9: '1'
	 * *    example 10: number_format('1.20', 2);
	 * *    returns 10: '1.20'
	 * *    example 11: number_format('1.20', 4);
	 * *    returns 11: '1.2000'
	 * *    example 12: number_format('1.2000', 3);
	 * *    returns 12: '1.200'
	 * *    example 13: number_format('1 000,50', 2, '.', ' ');
	 * *    returns 13: '100 050.00'
	 * Strip all characters but numerical ones.
	 */
	this.number_format = function (number, decimals, dec_point, thousands_sep) {
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				s = '',
				toFixedFix = function (n, prec) {
					var k = Math.pow(10, prec);
					return '' + Math.round(n * k) / k;
				};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	};
	this.resetaTabelaBandeiras = function () {
		var html = "<tr numbandeira='" + 1 + "' class='dayhead' name='table-color'>\
        <th align='center'>\
        <select id='selectBandeiras_" + 1 + "' onchange='caixamodel.insereNovaLinhaBandeira(" + 1 + ")'>\
        <option></option>\
        <option value='1'> VISA </option>\
        <option value='2'> VISA 2x a 6x </option>\
        <option value='3'> VISA 7x a 12x </option>\
        <option value='4'> CREDICARD </option>\
        <option value='5'> Credicard 2x a 6x </option>\
        <option value=6> Credicard 7x a 12x </option>\
        <option value='7'> AMEX </option>\
        <option value='8'> YAMADA </option>\
        <option value='9'> DINNERS </option>\
        <option value='10'> Dinners 2x a 6x </option>\
        <option value='11'> Dinners 7x a 12x </option>\
        <option value='12'> ELO </option>\
        <option value='13'> Elo 2x a 6x </option>\
        <option value='14'> Elo 7x a 12x </option>\
        <option value='15'> ELETRO-VISA </option>\
        <option value='16'> MAESTRO </option>\
        <option value='17'> Elo dÃƒÂ©bito </option>\
        </select>\
        </th>\
        <th align='center'>\
        <input type='text' onkeypress='return(MascaraMoeda(this, '', '.', event))' id='valor-cartao_" + 1 + "' name='valor-cartao' style='width: 120px;'>\
        </th>\
        <th align='center'>\
        <select id='num_parcelas_cartao_" + 1 + "'>\
        <option></option>\
        <option> 1 </option>\
        <option> 2 </option>\
        <option> 3 </option>\
        <option> 4 </option>\
        <option> 5 </option>\
        <option> 6 </option>\
        <option> 7 </option>\
        <option> 8 </option>\
        <option> 9 </option>\
        <option> 10 </option>\
        <option> 11 </option>\
		<option> 12 </option>\
        </select>\
        </th>\
        <th align='center'>\
        <input type='text' id='autoriz-cartao_" + 1 + "' name='valor-cartao' style='width: 120px;'>\
        </th>\
        </tr>\n\
        ";
		$('#tabela-bandeiras').html(html);
	};
}
caixamodel.ControlesPendentes();
$(document).ready(function () {
	caixamodel.updateView();
	/*
	 *  Adicionando evento ao botão de resumo do caixa
	 */
	$('#botao_resumo_caixa').click(function () {
		caixamodel.modalResumoCaixa();
	});
	/*
	 *  Adicionando evento ao botão de resumo do caixa
	 */
	$('#botao_nota_fiscal').click(function () {
		caixamodel.modalNotaFiscal();
	});
});
