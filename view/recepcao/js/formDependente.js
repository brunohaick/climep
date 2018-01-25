/**
 * Edicao de Dependente
 * Após o formulário ser submetido, envia-se todos os dados contidos para um
 * scrpit em php para motar a query sql de edicao de dados no banco.
 *
 * @author Bruno Haick
 * @date Criação: 12/09/2012
 *
 * @param
 *
 * @return
 *	True em caso de sucesso e False em caso de Falha.
 *
 */
function editarDepentente() {

	var matricula = $("input[name=dep-matricula]").val();
	var membro = $("input:text[name=dep-membro]").val();
	var vacinaCasa = $("input:checkbox#vacina_em_casa:checked").val();
	var lembrete = $("input:checkbox#nao_imprimir:checked").val();
	var nome = $("input:text[name=dep-nome]").val();
	var sobrenome = $("input:text[name=dep-sobrenome]").val();
	var nascimento = $("input:text[name=dep-nascimento]").val();
	var sexo = $("select[name=dep-sexo]").val();
	var medico = $("select[name=dep-medico]").val();
	var email = $("input:text[name=dep-email]").val();
	var vacinador = $("select[name=dep-vacinador]").val();
	var num_carteira = $("input:text[name=dep-num_carteira]").val();
	var validade_carteira = $("input:text[name=dep-validade_carteira]").val();
	var facebook = $("input:text[name=dep-facebook]").val();
	var twitter = $("input:text[name=dep-twitter]").val();
	var idTitular = $("#cad-matricula").val();
	var cadastroDependente = "cadastroDependente";
	var tipoForm = "submit-form-dependente";
	var idDependente = $("input:radio.default-checkbox[name='opcao']:checked").val();

	if (nome == "") {
		alert("Por Favor, informe o Nome do Usuário que deseja Cadastrar!");
		return false;
	} else if (sobrenome == "") {
		alert("Por Favor, informe o Sobrenome do Usuário que deseja Cadastrar!");
		return false;
	} else if (nascimento == "") {
		alert("Por Favor, informe a Data de nascimento do Usuário!");
		return false;
	} else if (medico == "") {
		alert("Por Favor, informe o Médico!");
		return false;
	} else if ((email == "") || (!IsEmail(email))) {
			alert("Insira um email Válido!");
			return false;
		//} else if(vacinador == "") {
		//	alert("Por Favor, informe o Vacinador!");
		//	return false;
	}

	$.ajax({
		url: 'index.php?module=edita_dependente&tmp=1',
		type: 'POST',
		data: {
			matricula: matricula,
			membro: membro,
			vacinaCasa: vacinaCasa,
			lembrete: lembrete,
			nome: nome,
			sobrenome: sobrenome,
			nascimento: nascimento,
			sexo: sexo,
			medico: medico,
			email: email,
			vacinador: vacinador,
			validade_carteira: validade_carteira,
			num_carteira: num_carteira,
			facebook: facebook,
			twitter: twitter,
			idTitular: idTitular,
			cadastroDependente: cadastroDependente,
			idDependente: idDependente,
			tipoForm: tipoForm
		},
		success: function(resposta) {
			console.log(resposta);
		if (resposta == 1)
			alert("Edição Realizada com Sucesso");
		else
			alert("Ocorreu um erro ao Editar Dependente. Por favor tente novamente");

//			limpaFichaDependente();
//			document.location.href = "index.php?module=editar&matricula=" + idTitular + "&msgcode=";
		}
	});
}

/**
 * Cadastro de Dependente
 * 
 * Após o formulário ser submetido, envia-se todos os dados contidos para um
 * scrpit em php para motar a query sql de inserção de dados no banco.
 *
 * @author Bruno Haick
 * @date Criação: 12/09/2012
 *
 * @param
 *
 * @return
 *	True em caso de sucesso e False em caso de Falha.
 *
 */
function cadastrarDepentente() {

	var matricula = $("#dep-matricula").val();
	var membro = $("#dep-membro").val();
	var vacinaCasa = $('input:checkbox#vacina_em_casa:checked').val();
	var lembrete = $('input:checkbox#nao_imprimir:checked').val();
	var nome = $("#dep-nome").val();
	var sobrenome = $("#dep-sobrenome").val();
	var nascimento = $("#dep-nascimento").val();
	var sexo = $("#dep-sexo").val();
	var medico = $("#dep-medico").val();
	var email = $("#dep-email").val();
	var vacinador = $("#dep-vacinador").val();
	var num_carteira = $("#dep-num_carteira").val();
	var validade_carteira = $("#dep-validade_carteira").val();
	var facebook = $("#dep-facebook").val();
	var twitter = $("#dep-twitter").val();
	var idTitular = $("#cad-matricula").val(); // campo contido no form principal de titular
	var cadastroDependente = "cadastroDependente";

	if (nome == "") {
		alert("Por Favor, informe o Nome do Usuário que deseja Cadastrar!");
		return false;
	} else if (sobrenome == "") {
		alert("Por Favor, informe o Sobrenome do Usuário que deseja Cadastrar!");
		return false;
	} else if (nascimento == "") {
		alert("Por Favor, informe a Data de nascimento do Usuário!");
		return false;
	} else if (medico == "") {
		alert("Por Favor, informe o Médico!");
		return false;
	} else if (email == "") {
		if (!confirm("Você não definiu o Email! Deseja continuar assim mesmo?"))
			return false;
		//} else if(vacinador == "") {
		//	alert("Por Favor, informe o Vacinador!");
		//	return false;
	}
	if (email != "")
		if (!IsEmail(email)) {
			alert("Insira um email Válido!");
			return false;
		}

	$.ajax({
		url: 'index.php?module=cadastro_dependente&tmp=1',
		type: 'POST',
		data: {
			matricula: matricula,
			membro: membro,
			vacinaCasa: vacinaCasa,
			lembrete: lembrete,
			nome: nome,
			sobrenome: sobrenome,
			nascimento: nascimento,
			sexo: sexo,
			medico: medico,
			email: email,
			vacinador: vacinador,
			num_carteira: num_carteira,
			validade_carteira: validade_carteira,
			facebook: facebook,
			twitter: twitter,
			idTitular: idTitular,
			cadastroDependente: cadastroDependente
		},
		success: function(resposta) {

			if (resposta == 1) {
				alert("Dependente Cadastro com Sucesso");
			} else if (resposta == 2) {
				alert("já existe um cliente cadastrado com este Nome");
				return false;
			} else {
				alert("Ocorreu um erro ao Cadastrar Dependente. Por favor tente novamente");
			}
			limpaFichaDependente();
			document.location.href = "index.php?module=editar&matricula=" + idTitular + "&msgcode=";
		}
	});

}

/**
 * Editar Dependente
 * 
 * Envia os dados do dependente para o modal 
 * referente a edicao de Dependentes
 *
 * @author Bruno Haick
 * @date Criação: 23/03/2014
 *
 * Abre o Modal de dependentes com os campos preenchidos.
 * 
 */
function modalEditarDependente() {

	var titulo = "Edição de Dependentes";
	var tipoForm = "edicaoDependente";
	var mat = $('#cad-mat').val();

	if ($('input:radio[id=opcao]').is(':checked')) {

		var idDependente = $("input:radio.default-checkbox[name='opcao']:checked").val();

		$.post("index.php?module=edita_dependente&tmp=1", {
			titulo: titulo,
			tipoForm: tipoForm,
			idDependente: idDependente,
			mat: mat
		}, function(data) {
			console.log(data);

			$("h2.titulo").html(titulo);

			if (data.vacina_casa == 1) {
				$("input:checkbox#vacina_em_casa").prop('checked', 'checked');
			}

			if (data.imprimir_lembrete == 1) {
				$("input:checkbox#nao_imprimir").prop('checked', 'checked');
			}
			
			if (data.medico != null) {
				$("select#dep-medico option[value='" + data.medico.id + "']").prop('selected', true);
			}

			if (data.fk_enfermeiro_id != null) {
				$("select#dep-vacinador option[value='" + data.fk_enfermeiro_id + "']").prop('selected', true);
			}

			$("input#dep-mat").val(data.matricula);
			$("input:text#dep-membro").val(data.membro);
			$("input:text#dep-nome").val(data.nome);
			$("input:text#dep-sobrenome").val(data.sobrenome);
			$("input:text#dep-nascimento").val(data.data_nasc);
			$("select#dep-sexo option[value='" + data.sexo + "']").prop('selected', true);
			$("input:text#dep-email").val(data.email);
			$("input:text#dep-num_carteira").val(data.numero_carteira);
			$("input:text#dep-validade_carteira").val(data.validade_cart);
			$("input:text#dep-facebook").val(data.facebook);
			$("input:text#dep-twitter").val(data.twitter);

			botao_editar();

			abrir_modal("form-dependente-modal");
		}, "json");
	} else {
		alert("Escolha um Dependente.");
	}
}

/**
 * Cadastrar Dependente
 * 
 * Envia alguns dados para o modal referente a edicao de Dependentes
 *
 * @author Bruno Haick
 * @date Criação: 10/10/2012
 *
 * @param
 *
 * @return
 *	Formulario html com os campos para preencher os dados do dependente.
 *
 */
function modalCadastrarDependente() {

	var titulo = "Cadastro de Dependentes";
	botao_cadastrar();
	$("h2.titulo").html(titulo);

	abrir_modal("form-dependente-modal");
//	var titular_id = $("#cad-cliente-hidden").val();
//	var tipoForm = "cadastro-dep-modal";
//
//	$.post("index.php?module=edita_dependente&tmp=1", {tipoForm: tipoForm, titular_id: titular_id}, function(result) {
//		$("div#form-dependente-result").html(result);
//	});
}

/**
 * Função para buscar o medico pelo seu CRM e vice-versa
 *
 * @author Marcus Dias
 * @date Criação: 31/10/2012
 *
 * @param
 *
 * @return
 *	Formulario html preenchido com os dados do dependente.
 *
 */
function buscaMedicoPorCrm(tipo, prefixo) {

	if (tipo == 'crm') {
		var crm = $("#medico-crm").val();
		var tipoForm = "busca_medico_por_crm";
		$.post("index.php?module=edita_dependente&tmp=1", {tipoForm: tipoForm, crm: crm}, function(result) {

			if (result == null)
				result = 0;

			$("select:[name=" + prefixo + "dep-medico] option[value='" + result + "']").prop('selected', true);
		}, "json");

	} else if (tipo == 'medico') {
		var id_medico = $("#dep-medico").val();
		var tipoForm = "busca_crm_medico_por_id";

		$.post("index.php?module=edita_dependente&tmp=1", {tipoForm: tipoForm, id: id_medico}, function(result) {

			if (result == null)
				result = '';

			$("input:[name=" + prefixo + "medico-crm]").val(result);
		}, "json");
	}
}


function limpaFichaDependente() {
	$("input:checkbox#vacina-name").prop('checked', false);
	$("input:checkbox#nao-imprimir").prop('checked', false);
	$("select#dep-medico").val('');
	$("select#dep-vacinador").val('');
	$("input#dep-mat").val('');
	$("input:text#dep-membro").val('');
	$("input:text#dep-nome").val('');
	$("input:text#dep-sobrenome").val('');
	$("input:text#dep-nascimento").val('');
	$("select#dep-sexo").val('');
	$("input:text#dep-email").val('');
	$("input:text#dep-num_carteira").val('');
	$("input:text#dep-validade_carteira").val('');
	$("input:text#dep-facebook").val('');
	$("input:text#dep-twitter").val('');
}

function botao_editar() {
	var botao_str = '<button id="dep_editar_button" type="submit" onclick="editarDepentente();" name="ok" class="btn btn-large span12"> <i class="icon-ok"></i> Editar </button>';
	$("div#dep_botao_div").html(botao_str);
}

function botao_cadastrar() {
	var botao_str = '<button id="dep_cadastrar_button" type="submit" onclick="cadastrarDepentente();" name="ok" class="btn btn-large span12"> <i class="icon-ok"></i> Cadastrar </button>';
	$("div#dep_botao_div").html(botao_str);
}