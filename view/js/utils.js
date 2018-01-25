function clearForm(idform) {
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

/**
 * Inicia um novo Processo de encaminhamento de Procedimentos.
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 */

function botaoFechar() {
    document.location.href = "index.php";
}

/**
 * Fechar Modal
 * Fecha uma div com id determinado contendo um modal.
 *
 * @author Bruno Haick
 * @date Criação: 13/09/2012
 *
 * @param string id 
 *	este parametro é o id da div que contém o modal.
 *
 */
function fechar_modal(id) {
    var obj;
    if (typeof id === 'string') {
	obj = $("#" + id);
    } else if (id && id.jquery) {
	obj = id;
    } else
	return;
    obj.modal('hide');
}

/**
 * Btn Show (Mostra Botao)
 *
 * Torna um input com o tipo Button que está invisível, visível.
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 * @param id
 */
function btnShow(nome) {
    $('input[type="button"][name="' + nome + '"]').show();
}

/**
 * Btn Hide (Esconde Botao)
 *
 * Torna um input com o tipo Button que está visível, invisível.
 *
 * @author Bruno Haick
 * @date Criação: 16/10/2012
 *
 *@param id
 */
function btnHide(nome) {
    $('input[type="button"][name="' + nome + '"]').hide();
}

/**
 * Funcao: MascaraMoeda
 * Sinopse: Mascara de preenchimento de moeda
 * Parametro:
 * objTextBox : Objeto (TextBox)
 * SeparadorMilesimo : Caracter separador de milésimos
 * SeparadorDecimal : Caracter separador de decimais
 * e : Evento
 */
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e) {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13 || whichCode == 8 || whichCode == 0)
	return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1)
	return false; // Chave inválida
    len = objTextBox.value.length;
    for (i = 0; i < len; i++)
	if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal))
	    break;
    aux = '';
    for (; i < len; i++)
	if (strCheck.indexOf(objTextBox.value.charAt(i)) != -1)
	    aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0)
	objTextBox.value = '';
    if (len == 1)
	objTextBox.value = '0' + SeparadorDecimal + '0' + aux;
    if (len == 2)
	objTextBox.value = '0' + SeparadorDecimal + aux;
    if (len > 2) {
	aux2 = '';
	for (j = 0, i = len - 3; i >= 0; i--) {
	    if (j == 3) {
		aux2 += SeparadorMilesimo;
		j = 0;
	    }
	    aux2 += aux.charAt(i);
	    j++;
	}
	objTextBox.value = '';
	len2 = aux2.length;
	for (i = len2 - 1; i >= 0; i--)
	    objTextBox.value += aux2.charAt(i);
	objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}
/**
 * Função para remover algum item inserido incorretamente
 * na tabela.
 * @author Marcus Dias
 * @date Criação: 08/10/2012
 *
 */
function removerItemClass(parametro) {
    $("tr." + parametro).remove();
}

/**
 * Função para remover algum item inserido incorretamente
 * na tabela.
 * @author Marcus Dias
 * @date Criação: 08/10/2012
 *
 */
function removerItem(parametro) {
    $("tr#" + parametro).remove();
}

/**
 * Função para 'hoverizar' um objeto
 * @author Amir
 * @date Criação: 14/01/2014
 *
 */
function hoverizar(string) {        
    $(string + ' tr').hover(
	    function() {
		$(this).css("background", "#C0D1E7");
	    },
	    function() {
		$(this).css("background", "");
	    }
    );

    $(string + ' tr').click(function() {
	var selected = $(this).hasClass("colorelinha");
	$(string + ' tr').removeClass("colorelinha");
	if (!selected)
	    $(this).addClass("colorelinha");
    });    
}
