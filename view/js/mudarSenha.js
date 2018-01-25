$(document).ready(function(){
/**
 * Muda a senha de Clientes
 *
 * @author Marcus Dias
 * @date Criação:
 *
 * @param
 *
 * @return
 *	True em caso de sucesso e False em caso de Falha.
 *
 */
	$("input#submit-nova-senha").click(function(){

		var login = $("#login-usuario").val();
		var senhaAtual = $("#senha-atual").val();
		var novaSenha = $("#nova-senha").val();
		var novaSenha2 = $("#nova-senha2").val();
		var submitSenha = $("input#submit-nova-senha").val();

		$.post("index.php?module=mudarsenha&tmp=1",{login:login,senhaAtual:senhaAtual,novaSenha:novaSenha,novaSenha2:novaSenha2,submitSenha:submitSenha},function(result){
			$("#modal-mudar-senha").html(result);
		});
	});
});
