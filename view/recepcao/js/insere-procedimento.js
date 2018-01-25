/**
 * Adicionar Procedimentos
 *
 * Fecha um modal e redireciona o usuario para a tela de edição de cliente com o
 * determinado id recebido.
 *
 * @author Bruno Haick
 * @date Criação: 08/10/2012
 *
 */

function adicionarProcedimentos() {
    var valorAtual = parseFloat($("#valor-total-procedimento").val());
    var preco = 0;
    var convenio_id = $("select[name='proc-tiss-convenio_id']").val();
    var tabela_id = $("select[name='proc-tiss-tabela_id']").val();

    var id_proc = new Array();
    $("input[type=checkbox][name='proc-tiss-checkbox[]']:checked").each(function() {
        id_proc.push($(this).val());
    });

    var qtd_proc = $("input:[name=proc-tiss-qtd-proc]").val();
    var tipoForm = 'adiciona_procedimentos';

    if (id_proc.length > 0) {
        if ($.isNumeric(qtd_proc)) {
			/*
			 * Esse IF eh responsável por verifica se o checkbox de selecionar 16
			 * procedimentos na tela de procedimento Tiss foi selecionado, para entao 
			 * mudar os procs por TP Master, mas na Guia Tiss, deve ir os 16 procs e nao 
			 * TP Master.
			 * 
			 * TODO
			 *		Deve-se criar uma forma de fazer com que seja gravado TP MASTER
			 *		e para a guia TISS tenhamos as informações dos procedimentos
			 *		que compõem cada TP MASTER
			 * 
			 * Bruno Haick 26/02/15
			 * 
			 */

			if ($('#marcar_todos').is(':checked')) {
                $.post("index.php?module=insere_procedimento&tmp=1", {
                    tipoForm: tipoForm,
                    id_proc: id_proc,
                    qtd_proc: qtd_proc,
                    convenio_id: convenio_id,
                    tabela_id: tabela_id
                    
                }, function(result) {
                    var valor_tpmaster = 0;
                    var rowId = parseInt($('#hiddenRowId').val());
                    var html = "";
                    var $tabela = $("tbody#proc-tiss-table-proc-append");
                    $.each(result, function(indice, valor) {
                        preco = valor['qtd'] * valor['valor'];
                        valorAtual += preco;
                        valor_tpmaster += preco;
                        console.log(valor_tpmaster);
                    });
                        rowId = parseInt($('#hiddenRowId').val()) + 1;
                        $('input#hiddenRowId').val(rowId);
                        html += "<tr id='proc-tiss-lista-proc-tr-append' class='proc-tiss-lista-proc-tr-append-" + rowId + "'>";
							html += "<th id='codigo' style='text-align:left;'> 213 </th>";
							html += "<th id='descrisao' style='text-align:left;'>TP Master</th>"; //coluna nome do banco está vazia neste momento
							html += "<th id='quantidade' style='text-align:center;'>1</th>";
							html += "<th id='preco' style='text-align:right;'>" + valor_tpmaster + "</th>";
							html += "<th align='center' onclick='removerItemClass(\"proc-tiss-lista-proc-tr-append-" + rowId + "\", " + valor_tpmaster + ");'><a class='btn btn-mini btn-danger mrg-center' ><i class='icon-trash icon-white'></i></a><input type='hidden' name='proc-tiss-hidden-proc_id[]' value='250'></th>";
                        html += "</tr>";
                    $("#valor-total-procedimento").val(valorAtual.toFixed(2));
                    $tabela.append(html);
                }, "json");
            } else {
                $.post("index.php?module=insere_procedimento&tmp=1", {
                    tipoForm: tipoForm,
                    id_proc: id_proc,
                    qtd_proc: qtd_proc,
                    convenio_id: convenio_id,
                    tabela_id: tabela_id
                }, function(result) {
                    var rowId = parseInt($('#hiddenRowId').val());
                    var html = "";
                    var $tabela = $("tbody#proc-tiss-table-proc-append");
                    $.each(result, function(indice, valor) {
                        preco = valor['qtd'] * valor['valor'];
                        valorAtual += preco;
                        html += "<tr id='proc-tiss-lista-proc-tr-append' class='proc-tiss-lista-proc-tr-append-" + rowId + "'>";
							html += "<th id='codigo' style='text-align:left;'>" + valor['codigo'] + "</th>";
							html += "<th id='descrisao' style='text-align:left;'>" + valor['descricao'] + "</th>"; //coluna nome do banco está vazia neste momento
							html += "<th id='quantidade' style='text-align:center;'>" + valor['qtd'] + "</th>";
							html += "<th id='preco' style='text-align:right;'>" + number_format(preco, 2, ',', '.') + "</th>";
							html += "<th align='center' onclick='removerItemClass(\"proc-tiss-lista-proc-tr-append-" + rowId + "\", " + preco + ");'><a class='btn btn-mini btn-danger mrg-center' ><i class='icon-trash icon-white'></i></a><input type='hidden' name='proc-tiss-hidden-proc_id[]' value='" + valor['id'] + "'></th>";
                        html += "</tr>";

//						html+= "<input type='hidden' name='proc-tiss-hidden-proc_id[]' value='"+valor['id']+"'>";
                        rowId = parseInt($('#hiddenRowId').val()) + 1;
                        $('input#hiddenRowId').val(rowId);
                    });

                    $("#valor-total-procedimento").val(valorAtual.toFixed(2));
                    $tabela.append(html);
                }, "json");
            }
        } else {
            alert("Quantidade Inválida.");
        }
    } else {
        alert("Deve-se marcar ao menos um Procedimento.");
    }
}

/**
 * Grava Historico Procedimentos
 *
 * De acordo com cada inserção de procedimentos, tem-se campos hidden para
 * criar um registro para cada linha da tabela, relacionando os procedimentos 
 * inseridos com o cliente, seu medico, a tabela e o convênio. Daí recupera-se 
 * esses valores e é montado um array para enviá-lo ao script em php através do
 * método POST.
 *
 * @author Bruno Haick
 * @date Criação: 12/10/2012
 *
 */

function grava_historico_procedimentos() {

    var id_convenio = $("select[name='proc-tiss-convenio_id']").val();
    var id_tabela = $("select[name='proc-tiss-tabela_id']").val();
    var id_medico = $("select[name='proc-tiss-medico_id']").val();
    var id_cliente = $("input:radio[name='proc_tiss_usuario_sel']:checked").val();
    var moeda = $("select[name='moeda-pagamento-procedimento']").val();
    var tipoForm = 'insere_procedimentos';

    var id_procedimentos = new Array();
    $("input[type=hidden][name='proc-tiss-hidden-proc_id[]']").each(function() {
        id_procedimentos.push($(this).val());
    });

    var list = new Array();
    $("table tr#proc-tiss-lista-proc-tr-append").each(function(i, v) {
        list[i] = Array();
        $(this).children('th').each(function(ii, vv) {
            list[i][ii] = $(this).text();
        });
    });

    var valor = 0;

    var qtdRows = list.length;
    var x = 0;
	/*
	 * Trocando "," por "." para não dar erro no calculo, já que se o numero tiver ","
	 * o numero eh considerado inteiro, perdendo os centavos apos a ",".
	 */
    while (x < qtdRows) {
        list[x][4] = id_procedimentos[x];
		valortmp = list[x][3].replace(/,/gi, ".");
        valor += parseFloat(valortmp);
        x++;
    }

    if (qtdRows > 0) {

        var cliente_id = $("input:radio[name='proc_tiss_usuario_sel']:checked").val();
        if (!$.isNumeric(cliente_id)) {
            alert("Selecione um Cliente")
            return false;
        }
        var numeroDaOrdem = encaminha_fila_espera();
        if (numeroDaOrdem > 0 && confirm("Deseja Imprimir a Guia de Atendimento ?")) {
            var Nomes = '';
            $('tbody#proc-tiss-table-proc-append tr#proc-tiss-lista-proc-tr-append').each(function(index, value) {
                Nomes += ((index === 0) ? '' : '&') + 'nomes[]=' + $(this).children('#descrisao').html();
            });
            var $select = $('input[name="proc_tiss_usuario_sel"]:checked');
            var matricula = $('#proc-matricula').val();
            var nomeDoMenbro = $select.parent().parent().children('#nomeDoMenbro').html();
            window.open('index2.php?module=fichaatendimento&matricula=' + matricula + '&numeroDaOrdem=' + numeroDaOrdem + '&nomeDoMenbro=' + nomeDoMenbro + '&' + Nomes);
        }

        $.ajax({
            url: "index.php?module=insere_procedimento&tmp=1",
            type: 'POST',
            data: {
                tipoForm: tipoForm,
                proc: list,
                id_cliente: id_cliente,
                id_medico: id_medico,
                valor: valor,
                id_tabela: id_tabela,
                id_convenio: id_convenio,
                moeda: moeda
            },
            async: false,
            success: function(result) {
                if (result == 1) {
                    $("#proc-tiss-boxes-ins-pro-result").html(result);
//						btnHide("gravar-proc");
//						btnShow("novo-proc");
                }
            }
        });

    } else {
        alert("Não há itens presentes na lista de Procedimentos")
        return false;
    }

    if (confirm("Deseja Imprimir a Guia Tiss ?")) {
        modalGuiaTiss();
    }
}

/**
 * Inicia um novo Processo de encaminhamento de Procedimentos.
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 */

function novo_historico_procedimentos() {
    document.location.href = "index.php?module=encaminhamentopro";
}

/**
 * Lista Procedimentos
 *
 * Retorna uma lista de procedimentos de acordo com
 * o convenio e tabela
 *
 * @author Bruno Haick
 * @date Criação: 08/10/2012
 *
 */
function listaProcedimentos() {

    var cliente_id = $("input[name='proc-tiss-matricula_id']").val();

    if ($.isNumeric(cliente_id)) {
        var medico = $("select[name='proc-tiss-medico_id']").val();
        var convenio = $("select[name='proc-tiss-convenio_id']").val();
        var tabela = $("select[name='proc-tiss-tabela_id']").val();
        var tipoForm = 'lista_procedimentos';

        $.post("index.php?module=insere_procedimento&tmp=1", {tipoForm: tipoForm, medico_id: medico, convenio_id: convenio, tabela_id: tabela}, function(result) {
            $("#proc-tiss-boxes-ins-pro-result").html(result);
            abrir_modal('proc-tiss-boxes-ins-pro');
        });
    } else {
        alert("É Necessário Preencher o campo Matrícula corretamente");
    }
}

/*
 * Função para marcar os 16 procedimentos da unimed predefinidos
 * @author Bruno Haick
 * @date Criação: 07/08/2013
 *
 */

function marcaProcedimentosUnimed(tipo) {

    if (tipo == 1) {
        $('input.procs_unimed').attr('checked', $('input#marcar_todos').is(':checked'));
    } else if (tipo == 2) {
        $('input.procs_unimed_orelinha').attr('checked', $('input#marcar_orelinha').is(':checked'));
    }

}

/**
 * Função para remover algum item inserido incorretamente
 * na tabela.
 * @author Marcus Dias
 * @date Criação: 08/10/2012
 *
 */
function removerItemClass(parametro, preco) {
    $("tr." + parametro).remove();

    var valorAtual = parseFloat($("#valor-total-procedimento").val());
    valorAtual = valorAtual.toFixed(2) - preco.toFixed(2);
    $("#valor-total-procedimento").val(valorAtual.toFixed(2))

}
/**
 * @author Bruno Haick
 * @since Earth begginer date. by Luiz Cortinhas
 * @author Luiz Cortinhas
 * @since Consultorio Modulo, em 08/12/13
 * @description Esa funç�o � chamada na guia tiss ao apertar o bot�o gravar, tem a funç�o de encaminhar para o medico salvando na tabela de 
 * */
function encaminha_fila_espera() {

    if (confirm("Você gostaria de enviar este paciente para fila de Espera do médico?")) {
        var cliente_id = $("input:radio[name='proc_tiss_usuario_sel']:checked").val();
        var medico = $("select[name='proc-tiss-medico_id']").val();
        var flag = "insereFilaEsperaMedico";
        var numeroDaOrdem = 0;
        $.ajax({
            url: "index.php?module=editar&tmp=1",
            type: 'POST',
            data: {medico: medico, cliente_id: cliente_id, flag: flag},
            async: false,
            success: function(data) {
                console.log(data);
                if (data === 'ja esta na fila o usuario') {
                    alert('usuario já está na fila de espera');
                    return;
                }
                data = $.parseJSON(data);
                if (data > 0) {
                    numeroDaOrdem = data;
                    alert("inserido na fila de espera com sucesso!");
                } else {
                    alert("Não inserido na fila de espera, ERRO-001");
                }
            }
        });
        return numeroDaOrdem;
    } else {
        return 0;
    }
}

function carregaDados() {
    var matricula = $("#proc-matricula").val();
    var flag = "buscaCPF";
    $.post('index.php?module=pesquisa&tmp=1', {matricula: matricula, flag: flag}, function(data) {
        $("#proc-cpf").val("");
        $("#proc-cpf").val(data);
    }, "json");
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
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
}
;
