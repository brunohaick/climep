/**
 * Login de Usuario
 * Após o formulário ser submetido, envia-se todos os dados contidos para um
 * script em php fazer verificação de usuario e então dar session para o
 * usuario.
 *
 * @author Marcus Dias
 * @date Criação: 13/09/2012
 *
 * @param
 *
 * @return
 *	True em caso de sucesso e False em caso de Falha.
 *
 */
$(document).ready(function(){
	$("input#submit_login").click(function(){

		var login = $("#login-climep").val();
		var senha = $("#senha-climep").val();
		var logar = $("#submit_login").val();

		$.post("index.php?module=login&tmp=1",{login:login,senha:senha,logar:logar},function(result){
		    $("#form-login-climep").html(result);
		});
	});

});

/**
 * Redirecionar
 * Função de redirecionamento através da javascript
 *
 * @author Marcus Dias
 * @date Criação: 13/09/2012
 *
 * @param
 * string local
 *
 */
function redirecionar(local) {
	location.href=local;
}
