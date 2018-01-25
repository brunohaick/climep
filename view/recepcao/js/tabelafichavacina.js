$('html').click(function() {
	$("#tabela-scr").removeClass('tabela-scr2');
	$("#tabela-scr").addClass('tabela-scr');
});

function reposicionaDropDown(id) {
	//top: "-100px",
	var casas = 4;
	var linhaAtual = parseInt(id.substr((id.length - 2), 2));
	if (isNaN(linhaAtual)) {
		linhaAtual = parseInt(id.substr((id.length - 1), 1));
		casas--;
	}

	if (linhaAtual >= ($('#vaci_vacinas_tabela_id tr').length - 5)) {
		$("#" + id).css({
			top: "-" + ($(("#" + id)).css("height"))

		});
	}
	if (linhaAtual == 1 || linhaAtual == 2) {
		$("#" + id).css({
			top: "-" + ($(("#" + id)).css("height"))
		});
		$("#tabela-scr").removeClass('tabela-scr');
		$("#tabela-scr").addClass('tabela-scr2');
	}

	if (parseInt(id.substr((id.length - casas), 1)) > 7) {
		$("#" + id).css({
			left: "-135px"
		});
	}
}


function reposicionaDropDown2(id, event) {
	var casas = 4;
	var eixoY = 430; //COL1
	var eixoX = 1150;//COL1
	var eixoYclick = event.pageY;
	var eixoXclick = event.pageX;

	var linhaAtual = parseInt(id.substr((id.length - 2), 2));
	if (isNaN(linhaAtual)) {
		linhaAtual = parseInt(id.substr((id.length - 1), 1));
		casas--;
	}

	// Coluna 9
	if (eixoYclick >= eixoY && eixoXclick >= eixoX) {
		if (linhaAtual == 0 || linhaAtual == 1) {
			$("#" + id).css({top: "-400px"});
			$("#tabela-scr").removeClass('tabela-scr');
			$("#tabela-scr").addClass('tabela-scr2');
		} else if (linhaAtual == 2) {
			$("#" + id).css({top: "-500px"});
			$("#tabela-scr").removeClass('tabela-scr');
			$("#tabela-scr").addClass('tabela-scr2');
		} else if (linhaAtual == 3) {
			$("#" + id).css({top: "-600px"});
			$("#tabela-scr").removeClass('tabela-scr');
			$("#tabela-scr").addClass('tabela-scr2');
		} else {
			$("#" + id).css({top: "-700px"});
			$("#tabela-scr").removeClass('tabela-scr');
			$("#tabela-scr").addClass('tabela-scr2');
		}
	}


	if (linhaAtual >= ($('#vaci_vacinas_tabela_id tr').length - 5)) {
		if (linhaAtual == 3) {
			$("#" + id).css({
				top: "-" + ($(("#" + id)).css("height"))
			});
			$("#tabela-scr").removeClass('tabela-scr');
			$("#tabela-scr").addClass('tabela-scr2');
		} else {
			$("#" + id).css({
				top: "-" + ($(("#" + id)).css("height"))

			});
		}
	}

	if (parseInt(id.substr((id.length - casas), 1)) > 7) {
		$("#" + id).css({
			left: "-135px"
		});
	}
}

