/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*===========================================================================*/
/* MODAL LOGIN
 /*===========================================================================*/

$('#senha-climep').keydown(function(event) {
	alert("brunohaick");
	if (event.which === 13 || event.which === 10) {
		var login = $("#login-climep").val();
		var senha = $("#senha-climep").val();
		var logar = $("#submit_login").val();

		$.post("index.php?module=login&tmp=1", {login: login, senha: senha, logar: logar}, function(result) {
			$("#form-login-climep").html(result);
		});
	}
});


$(document).ready(function() {

	/*===========================================================================*/
	/*  EXEMPLO DE FUNCIONAMENTO  MENU DE CONTEXTO
	 /*===========================================================================*/

	$("#context-menu").contextMenu({
		menu: 'menu-context',
		leftButton: true
	});
//	$("#menu-plus").contextMenu({
//		menu: 'menu-context',
//		leftButton: true
//	});

	/*===========================================================================*/
	/* FIM  EXEMPLO DE FUNCIONAMENTO  MENU DE CONTEXTO
	 /*===========================================================================*/

	$('a[name=modal]').click(function(e) {

		e.preventDefault();

		var id = $(this).attr('href');
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();

		$('#mask').css({
			'width': maskWidth,
			'height': maskHeight
		});

		$('#mask').fadeIn(1000);
		$('#mask').fadeTo("slow", 0.7);
		var winH = $(window).height();
		var winW = $(window).width();

		$(id).css('top', winH / 2 - $(id).height() / 2);
		$(id).css('left', winW / 2 - $(id).width() / 2);

		$(id).fadeIn(2000);
		$('#login-climep').focus();
	});

	$('.window .close').click(function(e) {
		e.preventDefault();
		$('#mask').hide();
		$('.window').hide();
	});

});

/*===========================================================================*/
/* FIM MODAL LOGIN
 /*===========================================================================*/

/*===========================================================================*/
/* TELAS COM MODAIS
 /*===========================================================================*/

function abrir_modal(idModal) {
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

/*===========================================================================*/
/* FIM TELAS COM MODAL
 * ==========================================================================*/
