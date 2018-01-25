$(function() {
//	consultas.AtualizaHitorico();
});

/**
 * editarMembro
 * Fecha um modal e redireciona o usuario para a tela de edição de cliente com o
 * determinado id recebido.
 *
 * @author Marcus Dias
 * @date Criação: 14/09/2012
 *
 * @param int id
 *	este parametro é a matricula do cliente.
 *
 */
function editarMembro(idTitular, idPessoa) {
	var idCliente = idPessoa;
	$.post("index.php?module=consultas&tmp=1", {
		flag: 'modalPesquisar',
		idCliente: idCliente
	}, function(result) {
		$("#nome-cliente").val(result['nome']);
		$("#medico-cliente").val(result['medico']);
		$("#matricula-cliente").val(result['matricula']);
		$("#idade-cliente").val(result['idade']);
		$("#email-cliente").val(result['email']);
		$("#cep-cliente").val(result['cep']);
		$("#endereco-cliente").val(result['endereco']);
		$("#telefone-cliente").val(result['telefone']);
		$("textarea[name='ant-pessoal']").val(result['antecedente_pessoal']);
		$("textarea[name='ant-familiar']").val(result['antecedente_familiar']);
		$("textarea[name='alergias']").val(result['alergias']);
		$("#peso-nascimento").val(result['peso_nascimento']);
		$("#idade-gestacional").val(result['idade_gestacional']);
		$("#apgar").val(result['apgar']);
	}, "json");
	carregaTabelaHistoricoConsulta(idCliente);
	carregaClienteFilaEspera(idCliente);

	fechar_modal('boxes-busca');
}