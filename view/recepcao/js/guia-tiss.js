$(document).ready(function() {

	$('select[name=guia-tiss-carater_solicitacao] option').popover({trigger: 'manual'}).hover(function() {
		$(this).popover('toggle');
	});
	$('select[name=guia-tiss-tipo_atendimento] option').popover({trigger: 'manual'}).hover(function() {
		$(this).popover('toggle');
	});
	$('select[name=guia-tiss-indicacao_acidente] option').popover({trigger: 'manual'}).hover(function() {
		$(this).popover('toggle');
	});
	$('select[name=guia-tiss-tipo_saida] option').popover({trigger: 'manual'}).hover(function() {
		$(this).popover('toggle');
	});
	$('select[name=guia-tiss-tipo_doenca] option').popover({trigger: 'manual'}).hover(function() {
		$(this).popover('toggle');
	});
	$('select[name=guia-tiss-tempo_doenca] option').popover({trigger: 'manual'}).hover(function() {
		$(this).popover('toggle');
	});

	$('input#guia-tiss-data_autorizacao').keydown(function(event) {
		if (event.which === 13 || event.which === 10) {
			dateComplete($(this));
			setaCamposData();
		}
	});

	$('input#proc-matricula').keydown(function(event) {
		if (event.which === 13 || event.which === 10) {
			buscaMembros();
		}
	});

	$('input#guia-valida-unimed_879').keydown(function(event) {
		if (event.which === 13 || event.which === 10) {
			validaCarteiraUnimed879();
		}
	});

});

function setIndicacao() {
	var id_cid = $('select[name=guia-tiss-cid] option:checked').attr('idRegistro');
	var tipoForm = "guia-tiss-nome-doenca";
	var tabela = $('input[name=guia-tiss-tabela]').val();

	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, id_cid: id_cid}, function(result) {
		$('input[name=guia-tiss-indicacao_clinica]').val(result.descricao);

		if (tabela == 16) { // Convenio CASSI
			$('input[name=guia-tiss-codigo_cbos]').val(result.cbos);
			$('input[name=guia-tiss-codigo_cbos2]').val(result.cbos);
		}
	}, "json");

}

function imprimirGuiaTiss() {
	var data = $('fieldset#DadosDaGuiaTiss').serializeArray();
	data.push({name: 'tipoForm', value: 'colodaDadosNaSessionGuiaTiss'});
	$('tbody#proc-tiss-table-proc-append tr').each(function(e) {
		data.push({name: 'procedimentos[' + e + '][descrisao]', value: $(this).children('#descrisao').html()});
		data.push({name: 'procedimentos[' + e + '][codigo]', value: $(this).children('#codigo').html()});
		data.push({name: 'procedimentos[' + e + '][preco]', value: $(this).children('#preco').html()});
		data.push({name: 'procedimentos[' + e + '][quantidade]', value: $(this).children('#quantidade').html()});
	});
	$.ajax({
		url: 'index.php?module=guia_tiss&tmp=1',
		type: 'POST',
		data: data,
		success: function(responce) {
			window.open('index2.php?module=guia-tiss');
		}
	});
}

/**
 * Abre o modal de Guia Tiss
 *
 * @author Bruno Haick
 * @date Criação: 15/10/2012
 *
 */
function modalGuiaTiss() {

	var tipo_proc = $("input:radio[name='proc-tiss-tipo']:checked").val();
	var listProcs = new Array();
	$("table tr#proc-tiss-lista-proc-tr-append").each(function(i, v) {
		listProcs[i] = Array();
		$(this).children('th').each(function(ii, vv) {
			listProcs[i][ii] = $(this).text();
		});
	})

	var qtdProcs = listProcs.length;
	//var x = 0;
	//var totalGuia = 0;
	//while(x < qtdProcs) {
	//	totalGuia += parseFloat(listProcs[x][3]);
	//	x++;
	//}

	var totalGuia = $('input:text[name=valor-total-procedimento1]').val();
	var cliente_id = $("input:radio[name='proc_tiss_usuario_sel']:checked").val();
	var medico_id = $("select[name='proc-tiss-medico_id']").val();
	var convenio_id = $("select[name='proc-tiss-convenio_id']").val();
	var tipoForm = "modal-guia-tiss";

//	if(qtdProcs > 0) {
	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, qtdProcs: qtdProcs, tipo_proc: tipo_proc}, function(result) {
		$("#boxes-guia-tiss-result").html(result);
		abrir_modal('boxes-guia-tiss');
	});

	tipoForm = "guia-tiss-dados_guia";
	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, medico_id: medico_id, cliente_id: cliente_id, convenio_id: convenio_id}, function(result) {

		/* Dados do Médico escolhido no Formulário. */
		var nome = result['medico_escolhido_nome'];
		var logradouro = "";

		if (result['medico_escolhido_logradouro'] !== 'NULL')
			logradouro = result['medico_escolhido_logradouro'];

		$('input:text[name=guia-tiss-consulta-guia_principal]').val($("#num_guia-procedimento").val());
		$('input:text[name=guia-tiss-guia_principal]').val($("#num_guia-procedimento").val());
		$('input:text[name=guia-tiss-senha]').val($("#num_guia-procedimento").val());

		$('input:text[name=guia-tiss-numero_carteira]').val(result['cliente_carteira']);
		$('input:text[name=guia-tiss-data_validade_carteira]').val(result['cliente_carteira_validade']);

		$('input:text[name=guia-tiss-nome_executante]').val(nome);
		$('input:text[name=guia-tiss-nome_profissional_executante]').val(nome);
		$('input:text[name=guia-tiss-estado2]').val(result['medico_escolhido_estado']);
		$('input:text[name=guia-tiss-estado3]').val(result['medico_escolhido_estado']);
		$('input:text[name=guia-tiss-codigo_executante]').val(result['medico_escolhido_crm_mascara']);
		$('input:text[name=guia-tiss-numero_conselho2]').val(result['medico_escolhido_crm_mascara']);
		$('input:text[name=guia-tiss-codigo_executante]').val(result['medico_escolhido_crm_mascara']);
		$('input:text[name=guia-tiss-municipio]').val(result['medico_escolhido_cidade']);
		$('input:text[name=guia-tiss-cep]').val(result['medico_escolhido_cep']);
		$('input:text[name=guia-tiss-logradouro]').val(logradouro);

		$('input:text[name=guia-tiss-tabela]').val(result['tabela']);

		/* Dados do Médico Assistente do Cliente informado no Formulário. */
		nome = result['medico_assistente_nome'];
		$('input:text[name=guia-tiss-nome_contratado]').val(nome);
		$('input:text[name=guia-tiss-nome_profissional_solicitante]').val(nome);
		$('input:text[name=guia-tiss-codigo_operadora]').val(result['medico_assistente_crm_mascara']);
		$('input:text[name=guia-tiss-numero_conselho]').val(result['medico_assistente_crm_mascara']);
		$('input:text[name=guia-tiss-estado]').val(result['medico_assistente_estado']);

		/* Dados do Cliente informado no Formulário. */
		$('input:text[name=guia-tiss-nome]').val(result['cliente_nome']);

		/* Dados do Convênio informado no Formulário. */
		$('input:text[name=guia-tiss-registro_ans]').val(result['convenio_registro_ans']);
		$('input:text[name=guia-tiss-plano]').val(result['convenio_nome']);

		/* Total Geral Da Guia */
		$('input:text[name=guia-tiss-total_geral_guia]').val(totalGuia);

	}, "json");


//	} else {
//		alert("Não há itens presentes na lista de Procedimentos");
//	}

}

/**
 * Quando o campo numero 3 eh preenchido com a data correta, os outros campos
 * serão setados com a data também.
 *
 * @author Bruno Haick
 * @date Criação: 10/11/2012
 *
 */
function setaCamposDataConsulta() {

	var data_autorizacao = $("input[name='guia-tiss-consulta-data_autorizacao']").val();

	$('input:text[name=guia-tiss-consulta-data_atendimento]').val(data_autorizacao);
	$('input:text[name=guia-tiss-consulta-data_solicitante]').val(data_autorizacao);
	$('input:text[name=guia-tiss-consulta-data_beneficio_responsavel]').val(data_autorizacao);

}

/**
 * Quando o campo numero 4 eh preenchido com a data correta, os outros 5 campos
 * serão setados com a data também. e tem um deles que é setado com um mes a
 * mais, por isso o post, para o php somar 1 mes na data.
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 */
function setaCamposData() {

	var data_autorizacao = $("input[name='guia-tiss-data_autorizacao']").val();
	var tipoForm = "guia-tiss-soma_data";

	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, data_autorizacao: data_autorizacao}, function(result) {
		$('input:text[name=guia-tiss-data_validade_senha]').val(result);
		$('input:text[name=guia-tiss-data_hora_solicitacao]').val(data_autorizacao);
		$('input:text[name=guia-tiss-data_solicitante]').val(data_autorizacao);
		$('input:text[name=guia-tiss-data_responsavel_autorizacao]').val(data_autorizacao);
		$('input:text[name=guia-tiss-data_emissao_senha]').val(data_autorizacao);
		$('input:text[name=guia-tiss-data_beneficio_responsavel]').val(data_autorizacao);
		$('input:text[name=guia-tiss-data_prestador_executante]').val(data_autorizacao);
	}, "json");

}

/**
 * Abre o modal de Extorno de Guias Tiss
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 */
function modalEstornoGuiaTiss2() {

	var usuario_id = $("select[name='proc-tiss-usuario_id'] option:checked").val();
	var convenio_id = $("select[name='proc-tiss-convenio_id'] option:checked").val();
	var tipoForm = "modal-estorno-guia-tiss2";
	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, convenio_id: convenio_id, usuario_id:usuario_id}, function(result) {

		var html = "";
		if(result != null)
			for(var i = 0; i < result.length; i++) {
				var id_grupo = result[i]['grupo_id'];
				var classe = 'proc-tiss-lista-proc-tr-' + id_grupo;

				html += "<tr id='proc-tiss-lista-proc-tr' name='table-color' class='" + classe + " dayhead'>";
					html += '<th align="center"> ' + result[i]['data'] + ' </th>';
					html += '<th align="center"> ' + result[i]['nome_medico'] + ' </th>';
					html += '<th align="center"> ' + result[i]['nome_convenio'] + ' </th>';
					html += '<th align="center"> ' + result[i]['nome_cliente'] + ' </th>';
					html += '<th align="center"> ' + result[i]['valor'] + ' </th>';
					html += '<th align="center" onclick="ImprimirFicha(' + result[i]['grupo_id'] +');"> <a class="btn mrg-center"> Imprimir </a> </th>';
					html += '<th align="center" onclick="estornarGuiaTiss(' + classe + ',' + id_grupo +');"> <a class="btn btn-mini btn-danger mrg-center"><i class="icon-trash icon-white"></i></a> </th>';
				html += "</tr>";
			}

		$("tbody#proc-tiss-lista-proc-tbody").html(html);
	},"json");

}

/**
 * Abre o modal de Extorno de Guias Tiss
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 */
function modalEstornoGuiaTiss() {

	var convenio_id = $("select[name='proc-tiss-convenio_id']").val();
	var tipoForm = "modal-estorno-guia-tiss";
	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, convenio_id: convenio_id}, function(result) {
		$("#boxes-estorno-guia-tiss").html(result);
		abrir_modal('boxes-estorno-guia-tiss');
	});

}

/**
 * Envia os ids das guias tiss para o controller realizar o estorno das mesmas
 *
 * @author Bruno Haick
 * @date Criação: 20/10/2012
 *
 */
function estornarGuiaTiss(classe, grupo_id) {

	tipoForm = "estorno_guia_tiss";

	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, grupo_id: grupo_id}, function(result) {
		$("tr." + classe).remove();
	});

}

function ImprimirFicha(grupo_id) {

	$.ajax({
		url: 'index.php?module=guia_tiss&tmp=1',
		type: 'POST',
		data: {tipoForm: 'getProcedimentos', grupoId: grupo_id},
		success: function(response) {
			data = $.parseJSON(response);
			response = data.procedimentos;
			var Nomes = '';
			var nomeDoMenbro;
			var numeroDaOrdem;
			var matricula;
			$.each(response, function(index, value) {
				if (index !== 'ID'){
					//matricula = value['matricula'];
					matricula = data.matricula;
					nomeDoMenbro = value['nome'] + ((value['sobrenome'] !== null) ? value['sobrenome'] : '');
					Nomes += ((index === 0) ? '' : '&') + 'nomes[]=' + value['descricao'];
				} else {
					numeroDaOrdem = value['id'];
				}
			});

			window.open('index2.php?module=fichaatendimento&matricula=' + matricula + '&numeroDaOrdem=' + numeroDaOrdem + '&nomeDoMenbro=' + nomeDoMenbro + '&' + Nomes);
//			console.log(response);
		}
	});

//	var Nomes = '';
//	$('tbody#proc-tiss-table-proc-append tr#proc-tiss-lista-proc-tr-append').each(function(index, value) {
//		Nomes += ((index === 0) ? '' : '&') + 'nomes[]=' + $(this).children('#descrisao').html();
//	});
//	var $select = $('input[name="proc_tiss_usuario_sel"]:checked');
//	var matricula = $('#proc-matricula').val();
//	var nomeDoMenbro = $select.parent().parent().children('#nomeDoMenbro').html();

}

/**
 * Insere Guia Tiss
 *
 * Pega todos os dados do formulário Guia-TISS e envia para o controller inserir
 * no banco de dados e relacionar a guia com seus procedimentos;
 *
 * @author Bruno Haick
 * @date Criação: 14/10/2012
 *
 */
function insereGuiaTiss() {

	var guia = new Array();

	guia[0] = $("input[name='guia-tiss-registro_ans']").val();
	guia[1] = $("input[name='guia-tiss-guia_principal']").val();
	guia[2] = $("input[name='guia-tiss-data_autorizacao']").val();
	guia[3] = $("input[name='guia-tiss-senha']").val();
	guia[4] = $("input[name='guia-tiss-data_validade_senha']").val();
	guia[5] = $("input[name='guia-tiss-data_emissao_senha']").val();
	guia[6] = $("input[name='guia-tiss-numero_carteira']").val();
	guia[7] = $("input[name='guia-tiss-plano']").val();
	guia[8] = $("input[name='guia-tiss-data_validade_carteira']").val();
	guia[9] = $("input[name='guia-tiss-nome']").val();
	guia[10] = $("input[name='guia-tiss-numero_cns']").val();
	guia[11] = $("input[name='guia-tiss-codigo_operadora']").val();
	guia[12] = $("input[name='guia-tiss-nome_contratado']").val();
	guia[13] = $("input[name='guia-tiss-numero_cnes']").val();
	guia[14] = $("input[name='guia-tiss-nome_profissional_solicitante']").val();
	guia[15] = $("input[name='guia-tiss-conselho_profissional']").val();
	guia[16] = $("input[name='guia-tiss-numero_conselho']").val();
	guia[17] = $("input[name='guia-tiss-estado']").val();
	guia[18] = $("input[name='guia-tiss-codigo_cbos']").val();
	guia[19] = $("input[name='guia-tiss-data_hora_solicitacao']").val();
	guia[20] = $("select[name='guia-tiss-carater_solicitacao'] option:checked").attr('idRegistro');
	guia[21] = $("select[name='guia-tiss-cid'] option:checked").attr('idRegistro');
	guia[22] = $("input[name='guia-tiss-indicacao_clinica']").val();
	guia[23] = $("input[name='guia-tiss-codigo_executante']").val();
	guia[24] = $("input[name='guia-tiss-nome_executante']").val();
	guia[25] = $("input[name='guia-tiss-tl']").val();
	guia[26] = $("input[name='guia-tiss-logradouro']").val();
	guia[27] = $("input[name='guia-tiss-municipio']").val();
	guia[28] = $("input[name='guia-tiss-estado2']").val();
	guia[29] = $("input[name='guia-tiss-codigo_ibge']").val();
	guia[30] = $("input[name='guia-tiss-cep']").val();
	if ($("input[name='guia-tiss-cnes']").val() == undefined)
		guia[31] = '';
	else
		guia[31] = $("input[name='guia-tiss-cnes']").val();

	guia[32] = $("input[name='guia-tiss-codigo_executante']").val();
	guia[33] = $("input[name='guia-tiss-nome_profissional_executante']").val();
	guia[34] = $("input[name='guia-tiss-conselho_profissional2']").val();
	guia[35] = $("input[name='guia-tiss-numero_conselho2']").val();
	guia[36] = $("input[name='guia-tiss-estado3']").val();
	guia[37] = $("input[name='guia-tiss-codigo_cbos2']").val();
	guia[38] = $("input[name='guia-tiss-grau_particoes']").val();
	guia[39] = $("select[name='guia-tiss-tipo_atendimento'] option:checked").attr('idRegistro');
	guia[40] = $("select[name='guia-tiss-indicacao_acidente'] option:checked").attr('idRegistro');
	guia[41] = $("select[name='guia-tiss-tipo_saida'] option:checked").attr('idRegistro');
	guia[42] = $("select[name='guia-tiss-tipo_doenca'] option:checked").attr('idRegistro');
	guia[43] = $("select[name='guia-tiss-tempo_doenca'] option:checked").attr('idRegistro');
	guia[44] = $("textarea[name='guia-tiss-observacoes']").val();

	guia[45] = $("input[name='guia-tiss-total_procedimentos']").val();
	guia[46] = $("input[name='guia-tiss-total_taxas_alugueis']").val();
	guia[47] = $("input[name='guia-tiss-total_materiais']").val();
	guia[48] = $("input[name='guia-tiss-total_medicamento']").val();
	guia[49] = $("input[name='guia-tiss-total_diarias']").val();
	guia[50] = $("input[name='guia-tiss-total_gases_medicinais']").val();
	guia[51] = $("input[name='guia-tiss-total_geral_guia']").val();
	guia[52] = $("input[name='guia-tiss-data_solicitante']").val();
	guia[53] = $("input[name='guia-tiss-data_responsavel_autorizacao']").val();
	guia[54] = $("input[name='guia-tiss-data_beneficio_responsavel']").val();
	guia[55] = $("input[name='guia-tiss-data_prestador_executante']").val();

	var tipoForm = "insere_guia_tiss";
	//var matricula_proc = $("input[name='proc-tiss-matricula_id']").val();

	$.post("index.php?module=guia_tiss&tmp=1",{tipoForm: tipoForm, guia: guia}, function(result) {
            if(result == 1) {
                alert('Guia Inserida com sucesso');
                $('input[type="button"][name="grava_guia_tiss"]').attr('disabled','disabled');
                //fechar_modal('boxes-guia-tiss-result');
            }
	});

}

/**
 * Insere Guia Tiss Consulta
 *
 * Pega todos os dados do formulário Guia-TISS-Consulta e envia para o controller inserir
 * no banco de dados e relacionar a guia com seus procedimentos;
 *
 * @author Bruno Haick
 * @date Criação: 10/11/2012
 *
 */
function insereGuiaTissConsulta() {

	var guia = new Array();

	guia[0] = $("input[name='guia-tiss-consulta-registro_ans']").val();
	guia[1] = $("input[name='guia-tiss-consulta-guia_principal']").val();
	guia[2] = $("input[name='guia-tiss-consulta-data_autorizacao']").val();
	guia[3] = $("input[name='guia-tiss-consulta-numero_carteira']").val();
	guia[4] = $("input[name='guia-tiss-consulta-plano']").val();
	guia[5] = $("input[name='guia-tiss-consulta-data_validade_carteira']").val();
	guia[6] = $("input[name='guia-tiss-consulta-nome']").val();
	guia[7] = $("input[name='guia-tiss-consulta-codigo_cnes']").val();
	guia[8] = $("input[name='guia-tiss-consulta-numero_cns']").val();
	guia[9] = $("input[name='guia-tiss-consulta-codigo_operadora']").val();
	guia[10] = $("input[name='guia-tiss-consulta-nome_contratado']").val();
	guia[11] = $("input[name='guia-tiss-consulta-tl']").val();
	guia[12] = $("input[name='guia-tiss-consulta-logradouro']").val();
	guia[13] = $("input[name='guia-tiss-consulta-municipio']").val();
	guia[14] = $("input[name='guia-tiss-consulta-estado']").val();
	guia[15] = $("input[name='guia-tiss-consulta-codigo_ibge']").val();
	guia[16] = $("input[name='guia-tiss-consulta-cep']").val();
	guia[17] = $("input[name='guia-tiss-consulta-nome_profissional_executante']").val();
	guia[18] = $("input[name='guia-tiss-consulta-conselho_profissional2']").val();
	guia[19] = $("input[name='guia-tiss-consulta-numero_conselho2']").val();
	guia[20] = $("input[name='guia-tiss-consulta-estado2']").val();
	guia[21] = $("input[name='guia-tiss-consulta-codigo_cbos2']").val();
	guia[22] = $("select[name='guia-tiss-consulta-tipo_saida']").val();
	guia[23] = $("select[name='guia-tiss-consulta-indicacao_acidente']").val();
	guia[24] = $("select[name='guia-tiss-consulta-tipo_doenca']").val();
	guia[25] = $("select[name='guia-tiss-consulta-tempo_doenca']").val();
	guia[26] = $("select[name='guia-tiss-consulta-cid1']").val();
	guia[27] = $("select[name='guia-tiss-consulta-cid2']").val();
	guia[28] = $("select[name='guia-tiss-consulta-cid3']").val();
	guia[29] = $("select[name='guia-tiss-consulta-cid4']").val();
	guia[30] = $("select[name='guia-tiss-consulta-data_atendimento']").val();
	guia[31] = $("select[name='guia-tiss-consulta-codigo_tabela']").val();
	guia[32] = $("select[name='guia-tiss-consulta-codigo_procedimento']").val();
	guia[33] = $("select[name='guia-tiss-consulta-tipo_atendimento']").val();
	guia[34] = $("textarea[name='guia-tiss-consulta-observacoes']").val();
	guia[35] = $("input[name='guia-tiss-consulta-data_solicitante']").val();
	guia[36] = $("input[name='guia-tiss-consulta-data_beneficio_responsavel']").val();

	var tipoForm = "insere_guia_tiss_consulta";
	//var matricula_proc = $("input[name='proc-tiss-matricula_id']").val();

	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, guia: guia}, function(result) {

		$('input[type="button"][name="grava_guia_tiss"]').attr('disabled', 'disabled');
		fechar_modal('boxes-guia-tiss');
	});

}

function validaCarteiraUnimed879() {

	var num_carteira = $("input[name='guia-valida-unimed_879']").val();
	var tipoForm = "valida_carteira_unimed_879";

	$.post("index.php?module=guia_tiss&tmp=1", {tipoForm: tipoForm, num_carteira: num_carteira}, function(result) {
		if (result == 1) {
			alert('Esta carteira pertence a Unimed 879.');
		} else if (result == 0) {
			alert('Esta carteira NÃO pertence a Unimed 879.');
		}
	}, "json");

}
