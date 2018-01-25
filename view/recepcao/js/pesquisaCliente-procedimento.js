/**
 * Fechar Modal
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
function editarMembro(idTitular,idPessoa){
	var flag = 'tabela-membros';
	var idTitular = idTitular;

	$.post("index.php?module=encaminhamentopro&tmp=1",{flag:flag,idTitular:idTitular},function(result){
		$("#proc-matricula").val(idPessoa);
		$("tbody#tabela-membros").html(result);
	});

	fechar_modal('boxes-busca');
//	redirecionar("index.php?module=editar&matricula=" + id);
}

function buscaMembros(){

	var tipoForm = "busca_membros";
	var matricula = $("input[name=proc-tiss-matricula_id]").val();
	var strhtml = '';

	$.post("index.php?module=encaminhamentopro&tmp=1",{tipoForm:tipoForm,matricula:matricula},function(result){

		if(result != null) {
			for(var i = 0; i < result.length; i++){
				strhtml += "<tr name='table-color'>";
					strhtml += "<th align='center'><input type='radio' name='proc_tiss_usuario_sel' value='" + result[i]['id'] + "'/> </th>";
					strhtml += "<th align='center'> " + result[i]['membro'] + " </th>";
					strhtml += "<th id='nomeDoMenbro'> " + result[i]['nome'] + " </th>";
					strhtml += "<th align='center'> " + result[i]['idade'] + " </th>";
				strhtml += "</tr>";
			}
		}

		$("tbody#tabela-membros").html(strhtml);

	},"json");

}
