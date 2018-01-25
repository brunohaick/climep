$(document).ready(function(){

/**
 * Função que faz parte da transferencia de material
 * Trata uma tabela, retirando os dados dela e enviando-os para o controller
 * para que o insert destes dados seja feita no banco de dados 
 *
 * @author Marcus Dias
 * @date Criação: 04/10/2012
 *
 */
	$("input#submit-transf-vacina").click(function() {
		var i = 0;
		var j = 0;

		var data = Array();	
		$("table tr.tr-tv").each(function(i, v){
			data[i] = Array();
			$(this).children('th#th-tv').each(function(ii, vv){
				data[i][ii] = $(this).text();
			});
		})

		//alert(data);

		var origemtransf = $('#origem-transf').val();
		var destinotransf = $('#destino-transf').val();
		var motivotransf = $('#motivo-transf').val();

		if(data.length > 0) {
			if($.isNumeric(origemtransf)) {
				if($.isNumeric(destinotransf)) {
			$.post("index.php?module=transfvacina&tmp=1",{motivotransf:motivotransf,destinotransf:destinotransf,origemtransf:origemtransf,data:data,submitTransferencia:'tv-insert'},function(result){
				$("div#result-transf").html(result);
			});

				} else {
					alert("Um Destino deve ser Escolhido");
				}
			} else {
				alert("Uma Origem deve ser Escolhida");
			}
		} else {
			alert("Não há itens presentes na lista de transferência de vacina");
		}

	});

/**
 *
 * Trata o evento do click no botão de adicionar 
 * material para transferencia 
 *
 * @author Marcus Dias
 * @date Criação: 04/10/2012
 *
 * Cada vez que o Botão para adição de material
 * é clicado, manda-se dados para o controller
 * e este retorna um array com os dados do serviço
 * (transferencia) para montar mais uma linha da 
 * tabela com os dados já preenchidos.
 *
 */

	$("input#tv-table-append").click(function() {
		$('table tbody tr:odd').css('background','#FFF');
		$('table tbody tr:even').css('background','#D3D6FF');

		var idMaterial = $('#material-id').val();
		var idLote = $('#lote-id').val();
		var validade = $('#tv-validade').val();
		var quantidade = $('#tv-quantidade').val();
		var tipo = $('#tv-tipo').val();


		if($.isNumeric(idMaterial)) {
			if($.isNumeric(idLote)) {
				if($.isNumeric(quantidade)) {
					$.post("index.php?module=transfvacina&tmp=1",{idMaterial:idMaterial,idLote:idLote,validade:validade,quantidade:quantidade,tipo:tipo},function(result){
						var rowId = parseInt($('#hiddenRowId').val());

						var html = "<tr name='table-color' class='dayhead tr-tv' id='teste-"+ rowId +"' >";
						html += "<th id='th-tv' onclick='removerItem(\"teste-"+ rowId +"\");'>X</th>";
						html += "<th id='th-tv'>"+ result['codigo'] +"</th>";
						html += "<th id='th-tv'>"+ result['material'] +"</th>";
						html += "<th id='th-tv'>"+ result['quantidade'] +"</th>";
						html += "<th id='th-tv'>"+ result['loteAtual'] +"</th>";
						html += "<th id='th-tv'>"+ result['loteAtual'] +"</th>";
						html += "<th id='th-tv'>"+ result['almoxarifado'] +"</th>";
						html += "<th id='th-tv'>"+ result['salaImunizacao'] +"</th>";
						html += "<th id='th-tv'>"+ result['validade'] +"</th>";
						html += "<th id='th-tv'>"+ result['tipo'] +"</th>";

						html += "</tr>";

						rowId = parseInt($('#hiddenRowId').val()) + 1;
						$('input#hiddenRowId').val(rowId);

						$("tbody#table-transf-vacina-append").append(html);
					},"json");
				} else {
						alert("Quantidade Inválida.");
				}
			} else {
					alert("O Lote deve ser Escolhido.");
			}
		} else {
				alert("A Vacina deve ser Escolhida.");
		}
	});


/**
 *
 * Trata o evento do click no botão de adicionar 
 * material para a lista de material da entrada nf
 *
 * @author Marcus Dias
 * @date Criação: 08/10/2012
 *
 * Cada vez que o Botão para adição de material
 * é clicado, manda-se dados para o controller
 * e este retorna um array com os dados do serviço
 * (Entrada de material NF) para montar mais uma linha da 
 * tabela com os dados já preenchidos.
 *
 */

	$("input#entrada-table-append").click(function() {
		$('table tbody tr:odd').css('background','#FFF');
		$('table tbody tr:even').css('background','#D3D6FF');


		var idMaterial = $('#material-id').val();
		var lote = $('#em-lote').val();
		var validade = $('#em-validade').val();
		var quantidade = $('#em-quantidade').val();
		var tipo = $('#em-tipo').val();
		var custo = $('#em-custo').val();
		
		if($.isNumeric(idMaterial)) {
			if(lote.length > 0) {
				if(validade.length > 0) {
					if(custo.length > 0) {
						if($.isNumeric(quantidade)) {
		$.post("index.php?module=entradanf&tmp=1",{idMaterial:idMaterial,lote:lote,validade:validade,quantidade:quantidade,tipo:tipo,custo:custo},function(result){
			var rowId = parseInt($('#hiddenRowId').val());

			var html = "<tr name='table-color' class='dayhead tr-tv' id='teste-"+ rowId +"' >";
			html += "<th id='th-tv' align='center' onclick='removerItem(\"teste-"+ rowId +"\");'><a class='btn btn-mini btn-danger mrg-center'><i class='icon-remove icon-white'></i></a></th>";
			html += "<th id='th-tv' align='center'>"+ result['codigo'] +"</th>";
			html += "<th id='th-tv'>"+ result['material'] +"</th>";
			html += "<th id='th-tv'>"+ result['loteAtual'] +"</th>";
			html += "<th id='th-tv' align='center'>"+ result['validade'] +"</th>";
			html += "<th id='th-tv' align='center'>"+ result['quantidade'] +"</th>";
			html += "<th id='th-tv' align='center'>"+ result['tipo'] +"</th>";
			html += "<th id='th-tv' align='right'>"+ result['custo'] +"</th>";

			html += "</tr>";

			rowId = parseInt($('#hiddenRowId').val()) + 1;
			$('input#hiddenRowId').val(rowId);

			$("tbody#table-transf-vacina-append").append(html);
		},"json");

						} else {
								alert("Quantidade Inválida.");
						}
					} else {
							alert("Valor Unitário deve ser Digitado.");
					}
				} else {
						alert("A Validade deve ser Digitada.");
				}
			} else {
					alert("O Lote deve ser Digitado.");
			}
		} else {
				alert("A Vacina deve ser Escolhida.");
		}
	});

/**
 * Função que faz parte da entrada de material na nota fiscal
 * Trata uma tabela, retirando os dados dela e enviando-os para o controller
 * para que o insert destes dados seja feita no banco de dados 
 *
 * @author Marcus Dias
 * @date Criação: 04/10/2012
 *
 */
	$("input#submit-entrada-vacina").click(function() {
		var i = 0;
		var j = 0;

		var data = Array();
		$("table tr.tr-tv").each(function(i, v){
			data[i] = Array();
			$(this).children('th#th-tv').each(function(ii, vv){
				data[i][ii] = $(this).text();
			});
		})

		var parcelas = Array();
		$("table tr:[name=tr-parcela-nf]").each(function(i, v){
			parcelas[i] = Array();
			$(this).children('td').each(function(ii, vv){
				parcelas[i][ii] = $(this).text();
			});
		})

		var nffornecedorlarge = $('#nf-fornecedor-large').val();
		var nffrete = $('#nf-frete').val();
		var numnf = $('#num-nf').val();
		var nftotalnota = $('#nf-total-nota').val();
		var nfvalorfrete = $('#nf-valor-frete').val();
		var nfdataentrada = $('#nf-data-entrada').val();
		var nfdataemissao = $('#nf-data-emissao').val();
		var origemtransf = $('#origem-transf').val();
		var destinotransf = $('#destino-transf').val();
		var tipo = $('#nf-selc-tipo').val();
		var banco = $('#nf-banco').val();

		if(numnf.length > 0) {
			if(nfdataentrada.length > 0) {
				if(nfdataemissao.length > 0) {
					if($.isNumeric(nftotalnota.length)) {
						if($.isNumeric(nfvalorfrete.length)) {
							if($.isNumeric(nffornecedorlarge)) {
								if($.isNumeric(origemtransf)) {
									if($.isNumeric(destinotransf)) {
										if(data.length > 0) {
											$.post("index.php?module=entradanf&tmp=1",{
												tipo:tipo,
												banco:banco,
												parcelas:parcelas,
												destinotransf:destinotransf,
												origemtransf:origemtransf,
												nffornecedorlarge:nffornecedorlarge,
												nffrete:nffrete,
												numnf:numnf,
												nftotalnota:nftotalnota,
												nfvalorfrete:nfvalorfrete,
												nfdataentrada:nfdataentrada,
												nfdataemissao:nfdataemissao,
												data:data,
												submitEntrada:'em-insert'
											},function(result){
												$("div#result-transf").html(result);
											});
										} else {
											alert("Não há itens presentes na lista de entrada de material na Nota Fiscal");
										}
									} else {
										alert("Um Destino deve ser Escolhido");
									}
								} else {
									alert("Uma Origem deve ser Escolhida");
								}
							} else {
								alert("Um Fornecedor deve ser Escolhido");
							}
						} else {
							alert("Valor do Frete Inválido");
						}
					} else {
						alert("Valor Total da Nota Inválido");
					}
				} else {
					alert("Uma data de emissão Inválida");
				}
			} else {
				alert("Uma data de entrada Inválida");
			}
		} else {
			alert("Um número de Nota Fiscal deve ser Digitado");
		}
	});
});

/**
 * Função para remover algum item inserido incorretamente
 * na tabela.
 * @author Marcus Dias
 * @date Criação: 08/10/2012
 *
 */
	function removerItem(parametro){
		$("tr#"+ parametro).remove();
	}
