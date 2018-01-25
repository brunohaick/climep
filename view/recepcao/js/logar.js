/**
 * Login de Usuario
 * Apenas utilizado se o usuario ainda não fez login,
 * Faz com que o modal com o formulario de login seja aberto.
 *
 * @author Marcus Dias
 * @date Criação: 13/09/2012
 *
 * @param
 *
 *
 */
function open_modal( id ){
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});

	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);	
	
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();

	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);
	$(id).fadeIn(2000); 
};

$(document).ready(function() {	

	$('a[name=modal]').click(function(e)
		{
			e.preventDefault();
			open_modal(
				$(this).attr('href')
				);	
		});

	open_modal('#dialog');//abrindo

});
