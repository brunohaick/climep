<?php

/**
 * @file  model/funcGerais.php
 * @brief  Guarda as funções que tem alguma função direta com o banco
 * @version 0.01
 * @date  Criação: 05/09/2012
 * @date  Alteração: 05/09/2012
 * @todo
 * 	@li nenhum
 *
 * */

/**
 * função exemplo
 * Busca todos os usuarios na fila de espera
 *
 * @author Andrey Maia
 * @date Criação: 05/09/2012
 *
 * @param string $x utilizada para isso e aquilo
 *
 * @return
 * 	TRUE on success, FALSE on failure.
 *
 * 	@todo
 * 		criar a funcao
 */
function filaEspera($x) {

//    listar
}

function buscaNovoMembro($matricula) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			MAX(cliente.membro) as membro
		FROM
			cliente
		where
			cliente.matricula=$matricula
			
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    if (!empty($arr['membro'])) {
        return $arr['membro'] + 1;
    } else {
        return false;
    }
}

function buscaNumeroControle($idGuia) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			numero_controle
		FROM
			controle
		WHERE
			id = $idGuia
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    return $arr['numero_controle'];
}

function buscaNovoNumeroControle() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				MAX(numero_controle) as numero
			FROM
				guia_controle
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    if (!empty($arr['numero'])) {
        return $arr['numero'] + 1;
    } else {
        return 1;
    }
}

function buscaNovoIDControle() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				MAX(id) as numero
			FROM
				guia_controle
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    if (!empty($arr['numero'])) {
        return $arr['numero'] + 1;
    } else {
        return 1;
    }
}

function buscaNovaMatricula() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			MAX(matricula) as matricula
		FROM
			cliente
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    if (!empty($arr['matricula'])) {
        return $arr['matricula'] + 1;
    } else {
        return false;
    }
}

/**
 * Busca o numero da carteira do convenio e validade desta do cliente.
 *
 * @param int $id
 * 	inteiro contendo o ID do cliente
 *
 * @return
 * 	STRING com o nome do cliente encontrado
 * 	ou false, caso nao encontre nenhum valor.
 */
function buscaCarteiraConvenioCliente($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				numero_carteira,
				validade_carteira
			FROM
				cliente
			WHERE
				cliente.cliente_id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $arr = mysqli_fetch_assoc($query);

    return $arr;
}

/**
 * Busca o primeiro nome da pessoa, de acordo com o ID do cliente
 *
 * @param int $id
 * 	inteiro contendo o ID do cliente
 *
 * @return
 * 	STRING com o nome do cliente encontrado
 * 	ou false, caso nao encontre nenhum valor.
 */
function buscaNomeCliente($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.nome,
				pessoa.sobrenome
			FROM
				pessoa,cliente
			WHERE
				cliente.cliente_id = pessoa.id
				AND
				cliente.cliente_id = $id
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    if (!empty($arr['nome'])) {
        return $arr['nome'] . " " . $arr['sobrenome'];
    } else {
        return false;
    }
}

/**
 * Busca as categorias existentes no banco de dados, na tabela categoria.
 *
 * @return
 * 	Array com todas as categorias contidas no banco de dados
 */
function buscaCategoria() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				convenio
			ORDER BY
				nome
    ";
    Database::query($sql);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * Busca a categoria no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Nome da categoria (String)
 */
function categoriaById($categoriaId) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				nome
			FROM
				convenio
			WHERE
				id = $categoriaId
	";

    $query = mysqli_query(banco::$connection, $sql);
    $categoria = mysqli_fetch_row($query);

    return $categoria[0];
}

/**
 * Busca as origens existentes no banco de dados, na tabela origem.
 *
 * @return
 * 	Array com todas as origens contidas no banco de dados
 *
 */
function buscaOrigem() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				origem
			ORDER BY
				origem_nome
    ";
    Database::query($sql);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * Busca a Origem no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @param int $origemId
 *
 * @return
 * 	Nome da Origem (String)
 */
function origemById($origemId) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				origem_nome
			FROM
				origem
			WHERE
				id_origem = $origemId
	";

    $query = mysqli_query(banco::$connection, $sql);
    $origem = mysqli_fetch_row($query);

    return $origem[0];
}

/**
 * Função para conferir se pessoa já foi cadastrada anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Bruno Haick
 * @date 05/09/2012
 *
 * @param string $nome
 * @param string $sobrenome
 *
 * @return
 * 	TRUE se o usuario existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaPessoaPorNome($nome, $sobrenome) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			COUNT(*)
		FROM
			pessoa
		WHERE
			nome = '$nome'
			AND
			sobrenome = '$sobrenome'
	";
//die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    ver();

    return true;
}

/**
 * Função para conferir se pessoa já foi encaminhada para a fila de
 * vacina e não foi atendida.
 *
 * @author Bruno Haick
 * @date 26/06/2013
 *
 * @param int id
 * @param string $sobrenome
 *
 * @return
 * 	TRUE se o usuario existe no banco, caso contrario, FALSE.
 *
 */
function usuarioExisteNaFilaEsperaVacina($idCliente) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $hoje = date('Y-m-d');

    $sql = "SELECT
			COUNT(*)
		FROM
			fila_espera_vacina
		WHERE
			cliente_id = $idCliente
			AND
			data = '$hoje'
			AND
			hora_atendimento IS NULL
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um Lancamento.
 *
 * @author Bruno Haick
 * @date Criação: 18/03/2013
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em caso de sucesso, e FALSE em caso de falha
 *
 */
function insereLancamento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('lancamentos', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereServicoDetalhado($guiaid, $controleid, $materialid, $clienteId, $preco) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $hoje = date('Y-m-d');
    $usuario = $_SESSION['usuario']['id'];
    $sql = "INSERT INTO servico (cliente_cliente_id,material_id,status_id,usuario_id,qtd_doses,preco,data)
     VALUES('$clienteId','$materialid','16','$usuario','10','$preco',NOW());";
    if (mysqli_query(banco::$connection, $sql)) {
        return mysqli_insert_id(banco::$connection);
    } else {
        return 0;
    }
}

/**
 * Função para inserir um servico de um cliente.
 *
 * @author Bruno Haick
 * @date Criação: 06/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em caso de sucesso, e FALSE em caso de falha
 *
 */
function insereServico($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('servico', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um historico de um cliente.
 *
 * @author Bruno Haick
 * @date Criação: 26/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em caso de sucesso, e FALSE em caso de falha
 *
 */
function insereHistorico($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('historico', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um grupohistorico de um cliente.
 *
 * @author Bruno Haick
 * @date Criação: 26/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em caso de sucesso, e FALSE em caso de falha
 *
 */
function insereGrupoHistorico($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('grupo_historico', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir uma pessoa no banco.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em caso de sucesso, e FALSE em caso de falha
 *
 */
function inserePessoa($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('pessoa', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um cliente no banco.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereCliente($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('cliente', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um titular no banco de dados.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereTitular($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('titular', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um dependente no banco de dados.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereDependente($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('dependente', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um Grupo de Procedimentos.
 *
 * @author Bruno Haick
 * @date 17/10/2012
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereGrupoProcedimento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('grupo_procedimento', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um Historico de Procedimento.
 *
 * @param string $usuario Login do sistema
 * @param string $senha Senha do sistema em sha1
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereHistoricoProcedimento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('historico_procedimento', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um usuario no banco de dados.
 *
 * @param string $usuario Login do sistema
 * @param string $senha Senha do sistema em sha1
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereUsuario($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('usuario', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir uma Guia TISS.
 *
 * @author Bruno Haick
 * @date Criação: 14/10/2012
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereGuiaTiss($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('guia_tiss', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um Fornecedor.
 *
 * @author Bruno Haick
 * @date Criação: 14/10/2012
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereFornecedor($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('fornecedores', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um Material.
 *
 * @author Bruno Haick
 * @date Criação: 10/10/2012
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereMaterial($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('material', $dados)) {
        return true;
    } else {
        die("Falha de Insercao");
        return false;
    }
}

/**
 * Função para inserir um medico no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereMedico($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('medico', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir um enfermeiro no banco de dados.
 *
 * @param string $crm
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 * @todo
 * 	@li colocar os campos para entrar na função em forma de array.
 */
function insereEnfermeiro() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $dados['id_usuario'] = mysqli_insert_id(banco::$connection);

    if (inserir('enfermeiro', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para editar uma pessoa na tabela pessoa.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @param int $matricula
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarPessoa($dados, $id_pessoa) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $where = "id = $id_pessoa";

    if (alterar('pessoa', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para editar clientes na tabela cliente.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @param int $id
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarCliente($dados, $id_cliente) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $where = "cliente_id = $id_cliente";

    if (alterar('cliente', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para excluir um evento adverso de historico Vacina
 *
 * @param Array contendo o id do procedimento
 *
 * @param int $id
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarHistoricoExcluirEventoAdverso($idHistorico) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "UPDATE
			historico
		SET
			eventos_adversos_id = NULL,
			conduta = NULL,
			evolucao = NULL,
			data_queixa = NULL
		WHERE
			id = $idHistorico
	";

    $query = mysqli_query(banco::$connection, $sql);

    if ($query) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir o id da guia tiss no histrico Procedimento.
 *
 * @param Array contendo o id do procedimento
 *
 * @param int $id
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarGrupoProcedimento($id, $guia_tiss_id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "UPDATE
				grupo_procedimento
			SET
				guia_tiss_id = $guia_tiss_id
			WHERE
				id = $id
	";
//die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    if ($query) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para editar um titular na tabela titular.
 *
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @param int $id_titular
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarTitular($dados, $id_titular) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $where = "titular_id = $id_titular";

    if (alterar('titular', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para editar um dependente na tabela dependente.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @param int $id_dependente
 *
 * @return
 * TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarDependente($dados, $id_dependente) {

    $where = "dependente_id = $id_dependente";

    if (alterar('dependente', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para editar um Servico.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @param int $id_servico
 *
 * @author Bruno Haick
 * @date Criação: 06/02/2012
 *
 * @return
 * TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarServico($finalizado, $id_servico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE
				servico
			SET
				finalizado = '$finalizado'
			WHERE
				id = $id_servico
	";

    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

/**
 * Função para editar um historico.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @author Bruno Haick
 * @date Criação: 06/02/2012
 *
 * @param int $id_dependente
 *
 * @return
 * TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarHistorico($dados, $id_historico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id_historico";

    if (alterar('historico', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para excluir Todos os grupo_historico
 * de um determinado historico do tipo grupo.
 *
 * @param id do historico
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 * @return
 * TRUE em sucesso, e FALSE em caso de falha
 *
 */
function excluiGrupoHistorico($id_historico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "historico_id_agrupador = $id_historico";

    if (remover('grupo_historico', $where)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para excluir um historico.
 *
 * @param id do historico
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 * @return
 * TRUE em sucesso, e FALSE em caso de falha
 *
 */
function excluiHistorico($id_historico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id_historico";

    if (remover('historico', $where)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para excluir um Servico.
 *
 * @param id do servico.
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 * @return
 * TRUE em sucesso, e FALSE em caso de falha
 *
 */
function excluiServico($id_servico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id_servico";

    if (remover('servico', $where)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para Dr as ultimas 4 consultas existentes no banco.
 *
 * @author Bruno Haick
 * @date Criação: 02/02/2013
 *
 * @return
 * Array contendo os ultimos 4 registros
 *
 */
function buscaUltimasConsultas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				data,
				texto
			FROM
				consulta
			ORDER BY
				data DESC
			LIMIT 0,4
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $ultimasConsultas[] = $linha;
    }

    return $ultimasConsultas;
}

/**
 * Função para buscar os ultimos 4 procedimentos existentes no banco.
 *
 * @author Bruno Haick
 * @date Criação: 14/10/2012
 *
 * @return
 * Array contendo os ultimos 4 registros
 *
 */
function buscaUltimosProcedimentos() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				procedimento.codigo				AS codigo,
				procedimento.nome				AS nome,
				historico_procedimento.qtd		AS qtd,
				historico_procedimento.valor	AS valor
			FROM
				historico_procedimento,procedimento
			WHERE
				historico_procedimento.procedimento_id = procedimento.id
			ORDER BY
				historico_procedimento.id DESC
			LIMIT 0,4
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $ultimosProcs[] = $linha;
    }

    return $ultimosProcs;
}

/**
 * Função para buscar usuario a partir de um login e uma senha
 *
 * @param string $login
 * @param string $senha
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * Array contendo o usuario encontrado com o determinado login e senha.
 *
 */
function buscaUsuario($login, $senha) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.nome,
				usuario.login,
				usuario.usuario_id,
				tipo_usuario.nome_tipo_usuario
			FROM
				pessoa,usuario,tipo_usuario
			WHERE
				usuario.usuario_id = pessoa.id
				AND
				usuario.tipo_usuario_id = tipo_usuario.id
				AND 
				usuario.login = '$login'
				AND 
				usuario.senha = '$senha'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    if (!empty($linha)) {
        return $linha;
    } else {
        return false;
    }
}

/**
 * Função para buscar dados de uma fatura de convenio por Id
 *
 * @param int $id
 *
 * @return
 * dados da fatura
 *
 */
function dadosFaturaPorId($idFatura) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				fatura_convenio.id,
				IF(fatura_convenio.faturado is null,fatura_convenio.valor_total,fatura_convenio.faturado) as 'faturado',
				fatura_convenio.taxa,
				fatura_convenio.empresa_id,
				conta_corrente.banco_id as 'banco'
			FROM
				fatura_convenio LEFT JOIN conta_corrente ON fatura_convenio.conta_corrente_id = conta_corrente.id
			WHERE
				fatura_convenio.id = '$idFatura'
	";
//die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar todos os eventos adversos
 *
 * Bruno Haick
 * Data: 30/06/2013
 *
 * MongoInt32@param 
 *
 * @return
 * dados dos eventos
 *
 */
function buscaTodosEventosAdversos() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			*
		FROM
			eventos_adversos
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    while ($linha = mysqli_fetch_assoc($query)) {
        $eventos[] = $linha;
    }

    return $eventos;
}

/**
 * Função para buscar todos os usuarios
 *
 * @param int $id
 *
 * @return
 * dados dos usuarios encontrados
 *
 */
function buscaTodosUsuarios() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.id,
				pessoa.nome
			FROM
				pessoa,usuario
			WHERE
				usuario.usuario_id = pessoa.id
				AND
				usuario.ativo = 1
			ORDER BY
				pessoa.nome
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    while ($linha = mysqli_fetch_assoc($query)) {
        $usuarios[] = $linha;
    }

    return $usuarios;
}

/**
 * Função para buscar buscar os dados de um modulo
 * dado seu id.
 *
 * @author Bruno Haick
 *
 * @param int $modulo_id
 *
 * @return
 *  itens do modulo não finalizado do cliente.
 *
 */
function buscaModulosHasMaterialPorIdModulo($modulo_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				a.id,s.material_id,a.valor,a.data,a.posicao_vertical,a.posicao_horizontal,a.finalizado,a.status, m.cliente_id, s.id AS 'servico_id'
			FROM
				modulos_has_material AS a
				INNER JOIN modulos AS m ON m.id = a.modulos_id
				INNER JOIN servico s ON a.id = s.modulo_has_material_id 
			WHERE
				a.modulos_id = $modulo_id";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $itens[] = $linha;
    }

    return $itens;
}

/**
 * Função para buscar buscar os modulos de Imunoterapia de um
 * cliente dado seu id.
 *
 * @param int $cliente_id
 *
 * @return
 *  Array itens do modulo não finalizado do cliente.
 *
 */
function buscaModulosImunoterapiaPorCliente($cliente_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				m.*
			FROM
				modulos as m
			WHERE
				m.cliente_id = '$cliente_id'
				AND
				m.tipo = '0' 
				AND
				m.finalizado = '1'
				AND
				m.data = CURDATE()
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $itens[] = $linha;
    }

    return $itens;
}

/**
 * Função para buscar buscar os modulos de um
 * cliente dado seu id.
 *
 * @param int $cliente_id
 *
 * @return
 *  itens do modulo não finalizado do cliente.
 *
 */
function buscaModulosVacinasPorCliente($cliente_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				m.*
			FROM
				modulos as m
			WHERE
				m.cliente_id = '$cliente_id'
				AND
				tipo = '1'
				AND
				finalizado = '1'
				AND
				m.data = CURDATE()
			ORDER BY m.data DESC
			LIMIT 1
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $itens[] = $linha;
    }

    return $itens;
}

/**
 * Função para buscar buscar os itens de um modulo não finalizado
 * dado o id de um cliente.
 *
 * @param int $cliente_id
 * @param int tipo tipo de modulo
 *
 * @return
 *  itens do modulo não finalizado do cliente.
 *
 */
function buscaItensModuloNaoFinalizadoCliente($cliente_id, $tipo) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				t.id as id_material,
				t.nome as nome_material,
				h.id as 'item_id',
				h.data as 'data_item',
				h.posicao_vertical,
				h.posicao_horizontal,
				m.*
			FROM
				modulos m INNER JOIN modulos_has_material h
				ON
				m.id = h.modulos_id INNER JOIN material t
				ON
				h.material_id = t.id
				WHERE
				m.id = (
						SELECT
							id
						FROM
							modulos
						WHERE
							cliente_id = '$cliente_id'
							AND
							finalizado = 0
						ORDER BY
							id DESC LIMIT 1
						)
				AND
				tipo = $tipo
	";
    /*
     * 	 */
//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $itens[] = $linha;
    }

    return $itens;
}

/**
 * Função para buscar todos os usuarios
 *
 * @param int $id
 *
 * @return
 * dados dos usuarios encontrados
 *
 */
function buscaTodosMidias() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				m.id,
				m.nome
			FROM
				midias as m
			ORDER BY m.nome
			";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    while ($linha = mysqli_fetch_assoc($query)) {
        $usuarios[] = $linha;
    }

    return $usuarios;
}

/**
 * Função para buscar todos os usuarios ativos para Login
 *
 * @param int $id
 *
 * @return
 * dados dos usuarios encontrados
 *
 */
function buscaTodosUsuariosLogin() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			usuario.login
		FROM
			pessoa,usuario
		WHERE
			usuario.usuario_id = pessoa.id
			AND
			usuario.ativo=1
		ORDER BY
			usuario.login
	";

    $query = mysqli_query(banco::$connection, $sql) or die(mysqli_error(banco::$connection));
    $linha = mysqli_fetch_assoc($query);

    while ($linha = mysqli_fetch_assoc($query)) {
        $usuarios[] = $linha;
    }

    return $usuarios;
}

/**
 * Função para buscar usuario a partir de um id.
 *
 * @param int $id
 *
 * @return
 * Nome do usuario encontrado com o determinado id.
 *
 */
function buscaUsuarioPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.nome
			FROM
				pessoa,usuario,tipo_usuario
			WHERE
				usuario.usuario_id = pessoa.id
				AND
				usuario.tipo_usuario_id = tipo_usuario.id
				AND 
				usuario.usuario_id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    if (!empty($linha)) {
        return $linha['nome'];
    } else {
        return false;
    }
}

/**
 * Função para buscar cliente a partir de seu id
 *
 * @param string $matricula
 *
 * @author Bruno Haick
 * @date Criação: 02/07/2013
 *
 * @return
 * 	Array contendo os dados encontrados do cliente
 * 	com a determinada matricula.
 *
 */
function dadosUsuario($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			pessoa.nome,
			pessoa.sobrenome
		FROM
			pessoa,usuario
		WHERE
			usuario.usuario_id = pessoa.id
			AND 
			usuario.usuario_id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar cliente a partir de seu id
 *
 * @param string $matricula
 *
 * @author Bruno Haick
 * @date Criação: 02/07/2013
 *
 * @return
 * 	Array contendo os dados encontrados do cliente
 * 	com a determinada matricula.
 *
 */
function dadosPessoaCliente($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			pessoa.id,
			pessoa.nome,
			pessoa.sobrenome,
			pessoa.sexo,
			pessoa.endereco,
			pessoa.numero,
			pessoa.cidade,
			pessoa.estado,
			DATE_FORMAT(pessoa.data_nascimento,'%d/%m/%Y') as data_nascimento,
			DATE_FORMAT(pessoa.data_nascimento,'%m/%d/%Y') as data_nascimento_en,
			cliente.matricula,
			cliente.membro
		FROM
			pessoa,cliente
		WHERE
			cliente.cliente_id = pessoa.id
			AND 
			cliente.cliente_id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

function dadosPessoa($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    ;

    $sql = "SELECT
			*
		FROM
			pessoa
		WHERE
			id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar cliente a partir de sua matricula
 *
 * @param string $matricula
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo os dados encontrados do cliente
 * 	com a determinada matricula.
 *
 */
function buscaCliente($matricula) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			*,
			DATE_FORMAT(pessoa.ultima_modificacao,'%d/%m/%Y %H:%i') as ultima_modificacao
		FROM
			pessoa,cliente,titular
		WHERE
			cliente.cliente_id = pessoa.id
			AND
			cliente.cliente_id = titular.titular_id
			AND 
			cliente.cliente_id = '$matricula'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar cliente a partir de seu id
 * idenpendente se for titular ou dependente
 * @param string $matricula
 *
 * @author Marcus Dias
 * @date Criação: 30/10/2012
 *
 * @return
 * 	Array contendo os dados encontrados do cliente
 * 	com a determinado id.
 *
 */
function buscaClienteById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				pessoa,cliente
			WHERE
				cliente.cliente_id = pessoa.id
				AND 
				cliente.cliente_id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar titular a partir de seu id
 * @param string $matricula
 *
 * @author Luiz Cortinhas
 * @date Criação: 07/08/2013
 *
 * @return
 * 	Array contendo os dados encontrados do cliente
 * 	com a determinado id.
 *
 */
function buscaTitularById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				titular
			WHERE
				titular.titular_id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar a ultima matricula
 * de cliente existente no banco
 *
 * @param string $matricula
 *
 * @author Bruno Haick
 * @date Criação: 9/6/2013
 *
 * @return
 * 	matricula
 *
 */
function ultimaMatriculaCliente() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
		     MAX(matricula) as matricula
		FROM 
		     cliente
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_row($query);

    return $linha[0];
}

/**
 * Função para buscar o nome do Titular
 * a partir de sua própria matricula.
 *
 * @param string $matricula
 *
 * @author Bruno Haick
 * @date Criação: 25/09/2012
 *
 * @return
 * 	Array contendo os dados encontrados
 * 	do titular com a determinada matricula.
 *
 */
function buscaNomeTitular($matricula) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
					pessoa.id,
					pessoa.nome,
					pessoa.sobrenome
			FROM
				pessoa,cliente,titular
			WHERE
				cliente.cliente_id = pessoa.id
				AND
				cliente.cliente_id = titular.titular_id
				AND
				cliente.cliente_id = '$matricula'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar o tipo de Cliente
 *
 * @param string $id
 *
 * @author Bruno Haick
 * @date Criação: 19/02/2013
 *
 * @return
 * 	Array contendo os dados encontrados
 *
 */
function buscaTipoCliente($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				COUNT(*) AS count
			FROM
				titular
			WHERE
				titular_id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);
    $count = mysqli_fetch_row($query);

    if ($count[0] == 1) {
        return "titular";
    }

    unset($count);

    $sql = "SELECT
				COUNT(*) AS count
			FROM
				dependente
			WHERE
				dependente_id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);
    $count = mysqli_fetch_row($query);

    if ($count[0] == 1) {
        return "dependente";
    }

    return false;
}

/**
 * Função para buscar o id do cliente a partir da matricula.
 * 
 * @param int $matricula
 *
 * @author Bruno Haick
 * @date Criação: 08/08/2013
 *
 * @return
 * id do cliente
 *
 */
function clienteId($matricula) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				cliente.cliente_id
			FROM
				cliente
			WHERE
				cliente.matricula = $matricula
	";

    $query = mysqli_query(banco::$connection, $sql);

    $titular = mysqli_fetch_row($query);

    return $titular[0];
}

/**
 * Função para buscar Titular a partir da matricula de um Dependente;
 * 
 * @param string $matriculaDependente
 *
 * @author Bruno Haick
 * @date Criação: 10/09/2012
 *
 * @return
 * 	Array contendo o Titular encontrado;
 *
 */
function titularExiste($matricula) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			pessoa.id
		FROM
			pessoa,cliente
		WHERE
			cliente.cliente_id = pessoa.id
			AND
			cliente.flag = 'T'
			AND
			cliente.matricula = $matricula
	";

    $query = mysqli_query(banco::$connection, $sql);

    $titular = mysqli_fetch_row($query);

    return $titular[0];
}

/**
 * Função para buscar Titular a partir da matricula de um Dependente;
 * 
 * @param string $idDependente
 *
 * @author Bruno Haick
 * @date Criação: 10/09/2012
 *
 * @return
 * 	Array contendo o Titular encontrado;
 *
 */
function buscaTitularPorDependente($idDependente) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.nome,
				pessoa.sobrenome,
				pessoa.id,
				pessoa.data_nascimento,
				cliente.matricula,
				cliente.membro
			FROM
				pessoa,cliente,dependente,titular
			WHERE
				dependente.dependente_id = $idDependente
				AND
				dependente.fk_titular_id = titular.titular_id
				AND
				titular.titular_id = cliente.cliente_id
				AND
				cliente.cliente_id = pessoa.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $titular = mysqli_fetch_assoc($query);

    return $titular;
}

/**
 * Função para buscar Dependentes a partir da matricula
 * do seu Titular;
 * @param string $matriculaTitular
 *
 * @author Bruno Haick
 * @date Criação: 10/09/2012
 *
 * @return
 * 	Array contendo os dependentes encontrados;
 *
 */
function buscaDependentesPorTitular($matriculaTitular) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				pessoa,cliente,dependente
			WHERE
				cliente.cliente_id = pessoa.id
				AND
				cliente.cliente_id = dependente.dependente_id
				AND
				dependente.fk_titular_id = '$matriculaTitular'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $dependentes[] = $linha;
    }
    return $dependentes;
}

function buscaDadosControle($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			controle
		WHERE
			guia_controle_id = '$id'
		ORDER BY
			cliente_id,modulo
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $arr[] = $linha;
    }

    return $arr;
}

function buscaGuiaControlePorId($idGuia) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*,
			DATE_FORMAT(data,'%d/%m/%y') as data1,
			DATE_FORMAT(data,'%d/%m/%Y') as data2
		FROM
			guia_controle
		WHERE
			id = '$idGuia'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

function buscaControlesPorTitularPorData($idTitular, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			guia_controle
		WHERE
			titular_id = '$idTitular'
			AND
			data = '$data'
		ORDER BY
			data,hora
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $arr[] = $linha;
    }

    return $arr;
}

function buscaControlesPorTitular($idTitular) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*,
			DATE_FORMAT(hora,'%H:%i') as hora
		FROM
			guia_controle
		WHERE
			titular_id = '$idTitular'
		ORDER BY
			data,hora
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $arr[] = $linha;
    }

    return $arr;
}

/**
 * Função para buscar Nomes dos Dependentes a partir 
 * da matricula do seu Titular;
 *
 * @param string $matriculaTitular
 *
 * @author Bruno Haick
 * @date Criação: 25/09/2012
 *
 * @return
 * 	Array contendo os dependentes encontrados;
 *
 */
function buscaNomeDependentesPorTitular($matriculaTitular) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.id,
				pessoa.nome,
				pessoa.sobrenome
			FROM
				pessoa,cliente,dependente
			WHERE
				cliente.cliente_id = pessoa.id
				AND
				cliente.cliente_id = dependente.dependente_id
				AND
				dependente.fk_titular_id = '$matriculaTitular'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $dependentes[] = $linha;
    }

    return $dependentes;
}

/**
 * Função para buscar os dados de um Dependentes
 *
 * @param string $matriculaDependente
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo os dados encontrados do dependente;
 *
 */
function buscaDadosDependente($matriculaDependente) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*,
				DATE_FORMAT(pessoa.data_nascimento, '%d/%m/%Y') as data_nasc,
				DATE_FORMAT(cliente.validade_carteira, '%d/%m/%Y') as validade_cart
			FROM
				pessoa,cliente,dependente
			WHERE
				cliente.cliente_id = pessoa.id
				AND
				cliente.cliente_id = dependente.dependente_id
				AND
				dependente.dependente_id = '$matriculaDependente'
	";

//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);
    $dependente = mysqli_fetch_assoc($query);

    return $dependente;
}

function buscaDadosDepedente2($idDoDeprendente) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = 'SELECT s.titular,a.*,s.dependente,p.*
	    FROM (
		SELECT c.cliente_id as \'dependente\',f.cliente_id as \'titular\'
		FROM `cliente` c 
		INNER JOIN `dependente` d 
		    ON c.cliente_id = d.dependente_id
		INNER JOIN `titular` t 
		    ON d.fk_titular_id = t.titular_id
		INNER JOIN `cliente` f ON t.titular_id = f.cliente_id
	    ) as s
	    INNER JOIN `pessoa` p 
		ON p.id = s.dependente
	    INNER JOIN `pessoa` a 
		ON a.id = s.titular
	    WHERE s.dependente = ' . $idDoDeprendente . ' ;';

    $query = mysqli_query(banco::$connection, $sql);

    $dependente = mysqli_fetch_assoc($query);

    return $dependente;
}

/**
 * Função para buscar o Nome do Medico a partir da seu Id.
 *
 * @param string $idMedico
 *
 * @author Bruno Haick
 * @date Criação: 18/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function dadosMedicoIndPorId($idMedico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.id,
				pessoa.nome,
				pessoa.sobrenome,
				pessoa.estado,
				pessoa.cidade,
				pessoa.cep,
				pessoa.cidade,
				pessoa.endereco,
				pessoa.numero,
				medico_indicacao.crm
			FROM
				pessoa,medico_indicacao
			WHERE
				medico_indicacao.medico_id = pessoa.id
				AND
				medico_indicacao.medico_id = $idMedico
	";

    $query = mysqli_query(banco::$connection, $sql);
    $medico = mysqli_fetch_assoc($query);

    return $medico;
}

/**
 * Função para buscar Medico a partir da seu CRM.
 *
 * @param string $matriculaCliente
 *
 * @author Bruno Haick
 * @date Criação: 06/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function dadosUnimedMedicoPorCrm($crm) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				medico.crm_mascara,
				medico.digito_unimed,
				medico.crm
			FROM
				medico
			WHERE
				medico.crm = $crm
	";

    $query = mysqli_query(banco::$connection, $sql);

    $medico = mysqli_fetch_assoc($query);

    return $medico;
}

/**
 * Função para buscar o Nome do Medico a partir da seu Id.
 *
 * @param string $idMedico
 *
 * @author Bruno Haick
 * @date Criação: 18/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function dadosMedicoPorId($idMedico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.nome,
				pessoa.sobrenome,
				pessoa.estado,
				pessoa.cidade,
				pessoa.cep,
				pessoa.cidade,
				pessoa.endereco,
				pessoa.numero,
				medico.crm,
				medico.crm_mascara
			FROM
				pessoa,medico,usuario
			WHERE
				usuario.usuario_id = pessoa.id
				AND
				medico.medico_id = usuario.usuario_id
				AND
				medico.medico_id = $idMedico
	";

    $query = mysqli_query(banco::$connection, $sql);
    $medico = mysqli_fetch_assoc($query);

    return $medico;
}

function buscaMedicoIndPorCrm($crm) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			pessoa.nome,
			pessoa.id
		FROM
			pessoa,medico_indicacao
		WHERE
			medico_indicacao.medico_id = pessoa.id
			AND
			medico_indicacao.crm = $crm
	";

    $query = mysqli_query(banco::$connection, $sql);

    $medico = mysqli_fetch_assoc($query);

    return $medico;
}

/**
 * Função para buscar Medico a partir da seu CRM.
 *
 * @param string $matriculaCliente
 *
 * @author Bruno Haick
 * @date Criação: 06/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function buscaMedicoPorCrm($crm) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				pessoa.nome,
				pessoa.id
			FROM
				pessoa,medico,usuario
			WHERE
				usuario.usuario_id = pessoa.id
				AND
				medico.medico_id = usuario.usuario_id
				AND
				medico.crm = $crm
	";

    $query = mysqli_query(banco::$connection, $sql);

    $medico = mysqli_fetch_assoc($query);

    return $medico;
}

/**
 * Função para buscar Convênio A partir do id do grupo_procedimento;
 * @param string $matriculaCliente
 *
 * @author Bruno Haick
 * @date Criação: 20/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function buscaNomeConvenioPorGrupoProcedimento($idConvenio) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				nome
			FROM
				convenio
			WHERE
				id = '$idConvenio'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $convenio = mysqli_fetch_row($query);

    return $convenio[0];
}

/**
 * Função para buscar Cliente A partir do id do grupo_procedimento;
 * @param string $matriculaCliente
 *
 * @author Bruno Haick
 * @date Criação: 20/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function buscaNomeClientePorGrupoProcedimento($idCliente) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				nome,
				sobrenome
			FROM
				pessoa
			WHERE
				id = '$idCliente'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $cliente = mysqli_fetch_row($query);

    return $cliente[0] . " " . $cliente[1];
}

/**
 * Função para buscar Medico A partir do id do grupo_procedimento;
 * @param string $matriculaCliente
 *
 * @author Bruno Haick
 * @date Criação: 20/10/2012
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function buscaNomeMedicoPorGrupoProcedimento($idMedico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				nome
			FROM
				pessoa
			WHERE
				id = '$idMedico'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $medico = mysqli_fetch_row($query);

    return $medico[0];
}

/**
 * Função para buscar Medico a partir da matricula
 * do seu Cliente;
 * @param string $idCliente
 *
 * @author Bruno Haick
 * @date Criação: 08/09/2013
 *
 * @return
 * 	Array contendo os dados do Medico encontrado.
 *
 */
function buscaMedicoPorCliente($idCliente) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			pessoa,cliente,medico_indicacao
		WHERE
			medico_indicacao.medico_id = pessoa.id
			AND 
			medico_indicacao.medico_id = cliente.medico_id
			AND
			cliente.cliente_id = '$idCliente'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $medico = mysqli_fetch_assoc($query);

    return $medico;
}

/**
 * Função para buscar Todos os Medicos existentes
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo os Medicos encontrados;
 *
 */
function buscaTodosMedicosInd() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*,
				CONCAT_WS(' ', pessoa.nome, pessoa.sobrenome) as medico_nome
			FROM
				pessoa,medico_indicacao
			WHERE
				medico_indicacao.medico_id = pessoa.id
				AND
				pessoa.nome NOT LIKE '%TRANS%'
				AND
				pessoa.nome NOT LIKE '%APOSE%'
				AND
				pessoa.nome NOT LIKE '%CRM%'
				AND
				pessoa.nome NOT LIKE '%(%'
				AND
				pessoa.nome NOT LIKE '%)%'
				AND
				pessoa.nome NOT LIKE '%CANC%'
				AND
				pessoa.nome NOT LIKE '%...%'
			GROUP BY
				medico_indicacao.crm
			ORDER BY
				pessoa.nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $medicos[] = $linha;
    }

    return $medicos;
}

/**
 * Função para buscar o Consultório por Médico
 *
 * @param
 * 	$medico_id
 *
 * @author Bruno Haick
 * @date Criação: 10/08/2013
 *
 * @return
 * 	Consultório
 *
 */
function buscatodosMedicosComConsultorio() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				consultorio.nome as consultorio,
				medico.crm,
				medico.medico_id,
				pessoa.nome,
				pessoa.sobrenome
			FROM
				pessoa,medico,consultorio
			WHERE
				medico.consultorio_id = consultorio.id
				AND
				medico.medico_id = pessoa.id
				AND
				medico.consultorio_id IS NOT NULL
			ORDER BY
				pessoa.nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $medicos[] = $linha;
    }

    return $medicos;
}

/**
 * Função para buscar Todos os Medicos existentes
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo os Medicos encontrados;
 *
 */
function buscaTodosMedicos() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				pessoa,medico,usuario
			WHERE
				usuario.usuario_id = pessoa.id
				AND
				medico.medico_id = usuario.usuario_id
				AND
				usuario.ativo=1
			GROUP BY
				medico.crm
			ORDER BY
				pessoa.nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $medicos[] = $linha;
    }

    return $medicos;
}

/**
 * Função para buscar Todos os Convenios 
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 22/03/2013
 *
 * @return
 * 	Array contendo os Convenios encontrados;
 *
 */
function buscaTodosConvenios($flag) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				convenio
			WHERE
				ativo = $flag
                        ORDER BY nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $convenios[] = $linha;
    }

    return $convenios;
}

/**
 * Função para buscar Todos os Medicos existentes
 *
 * MongoInt32@param
 *
 * @author Andrey Maia
 * @date Criação: 01/02/2012
 *
 * @return
 * 	Array contendo os Medicos encontrados;
 *
 */
function buscaTodosEspecialidades() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			especialidades
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $especialidades[] = $linha;
    }

    return $especialidades;
}

/**
 * Função para buscar Todos os Vacinadores existentes
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo os Vacinadores encontrados;
 *
 */
function buscaTodosVacinadores() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			pessoa,enfermeiro,usuario
		WHERE
			usuario.usuario_id = pessoa.id
			AND
			enfermeiro.enfermeiro_id = usuario.usuario_id
		ORDER BY
			pessoa.nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $vacinadores[] = $linha;
    }

    return $vacinadores;
}

/**
 * Função para buscar Todos as Tipos de Atendimento
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados dos tipos de Atendimentos cadastrados no banco.
 *
 */
function listaTipoAtendimento() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			tipo_atendimento
		ORDER BY
			nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($tipoAtend = mysqli_fetch_assoc($query)) {
        $arr[] = $tipoAtend;
    }

    return $arr;
}

/**
 * Função para buscar Todos as Indicações de Acidente
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados das indicações de acidente cadastrados no banco.
 *
 */
function listaIndicacaoAcidente() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			indicacao_acidente
		ORDER BY
			nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($ind = mysqli_fetch_assoc($query)) {
        $arr[] = $ind;
    }

    return $arr;
}

/**
 * Função para buscar Todos os Tipos de Saida.
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados dos tipos de saida cadastrados no banco.
 *
 */
function listaTipoSaida() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			tipo_saida
		ORDER BY
			nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($saida = mysqli_fetch_assoc($query)) {
        $saidas[] = $saida;
    }

    return $saidas;
}

/**
 * Função para buscar Todos os Tipos de Doença.
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados dos tipos de Doença cadastrados no banco.
 *
 */
function listaTipoDoenca() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			tipo_doenca
		ORDER BY
			nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($doenca = mysqli_fetch_assoc($query)) {
        $doencas[] = $doenca;
    }

    return $doencas;
}

/**
 * Função para buscar Todos os Tempos de Doença.
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados dos tempos de Doença cadastrados no banco.
 *
 */
function listaTempoDoenca() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			tempo_doenca
		ORDER BY
			nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($tempo = mysqli_fetch_assoc($query)) {
        $arr[] = $tempo;
    }

    return $arr;
}

/**
 * Função para buscar os dados de um Convenio.
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados de um convenio.
 *
 */
function buscaConvenioPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				convenio
			WHERE
				id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);
    $convenio = mysqli_fetch_assoc($query);

    return $convenio;
}

/**
 * Função para buscar lista dos caraters de Solicitação.
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 19/10/2012
 *
 * @return
 * 	Array com os dados dos caraters de Solicitação.
 *
 */
function listaCaraterSolicitacao() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				carater_solicitacao
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($carater = mysqli_fetch_assoc($query)) {
        $arr[] = $carater;
    }

    return $arr;
}

/**
 * Função para buscar Todos os CID existentes no banco
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 09/08/2013
 *
 * @return
 * 	Array com os dados das Doencas cadastradas no banco.
 *
 */
function listaCID() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				cid
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($cid = mysqli_fetch_assoc($query)) {
        $cids[] = $cid;
    }

    return $cids;
}

/**
 * Função para buscar Todos as Doenças existentes no banco
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 18/10/2012
 *
 * @return
 * 	Array com os dados das Doencas cadastradas no banco.
 *
 */
function listaDoencas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				doencas
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($doenca = mysqli_fetch_assoc($query)) {
        $doencas[] = $doenca;
    }

    return $doencas;
}

/**
 * Função para verificar se um numero de carteira
 * da unimed existe na tabela unimed879 da base de dados.
 *
 * @param $num_carteira
 *
 * @author Bruno Haick
 * @date Criação: 10/08/2013
 *
 * @return
 * 	count
 *
 */
function existeNumCarteira($num_carteira) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				COUNT(*) as count
			FROM
				unimed879
			WHERE
				numero = '$num_carteira'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $unimed = mysqli_fetch_row($query);

    return $unimed[0];
}

/**
 * Função para o Nome de um CID
 *
 * @param $id
 *
 * @author Bruno Haick
 * @date Criação: 09/08/2013
 *
 * @return
 * 	String com o nome do CID;
 *
 */
function descricaoCidById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				descricao,
				cbos
			FROM
				cid
			WHERE
				id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $cid = mysqli_fetch_assoc($query);

    return $cid;
}

/**
 * Função para o Nome de uma Doenca
 *
 * @param $id
 *
 * @author Bruno Haick
 * @date Criação: 18/10/2012
 *
 * @return
 * 	String com o nome da doença;
 *
 */
function nomeDoencaById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				nome
			FROM
				doencas
			WHERE
				id_doencas = $id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $doenca = mysqli_fetch_row($query);

    return $doenca[0];
}

/**
 * Função para criar uma lista de anos a serem apresentadas na 
 * ficha de Vacina
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 22/11/2012
 *
 * @return
 * 	Array Contendo a lista de anos que devem conter na ficha de vacinas
 * 	Retorna no Array os ultimos 5 anos, e as 5 decadas atenriores a estes.
 *
 * TODO Retirar esta funcao do projeto ela nao é mais utilizada.
  function listaAnos() {

  $dec = 10;
  $ano = date('Y');
  $anos[] = $ano;

  for($i = 0; $i < 4; $i++) {
  $anos[] = --$ano;
  }

  $ano = date('Y');
  $resto = $ano % $dec;
  $decada = $ano - $resto - $dec;
  $anos[] = $decada;

  for($i = 0; $i < 4; $i++) {
  $anos[] = $decada - 10;
  $decada = $decada - 10;
  }

  return $anos;
  }
 */

/**
 * Função para buscar Todos as Vacinas existentes no banco
 *
 * MongoInt32@param
 *
 * @author Bruno Haick
 * @date Criação: 16/09/2012
 *
 * @return
 * 	Array com apenas o nome das vacinas orenadas de acordo com a ficha de vacinas.
 *
 */
function listaVacinas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			material.id,
			material.nome
		FROM
			material,tipo_material
		WHERE
			tipo_material.id = material.tipo_material_id
			AND(
			tipo_material.nome = 'vacina'
			OR
			tipo_material.nome = 'teste')
			AND
			material.nome != 'NULL'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($vac = mysqli_fetch_assoc($query)) {
        $vacinas[] = $vac;
    }

    foreach ($vacinas as $vacina) {

        $nome = $vacina['nome'];
        $id = $vacina['id'];

        $res1 = stripos($nome, "BCG");
        $res2 = stripos($nome, "Febre Amarela");
        $res3 = stripos($nome, "Hepatite");
        $res4 = stripos($nome, "Difteria");
        $res5 = stripos($nome, "Tétano");
        $res6 = stripos($nome, "Coqueluche");
        $res7 = strpos($nome, "DUPLA");
        $res8 = stripos($nome, "dt");
        $res9 = stripos($nome, "Polio");
        $res10 = stripos($nome, "VIP");
        $res11 = stripos($nome, "VOP");
        $res12 = stripos($nome, "Hemófilos b");
        $res13 = stripos($nome, "Pneumo");
        $res14 = stripos($nome, "Meningo");
        $res15 = stripos($nome, "Sarampo");
        $res16 = stripos($nome, "Rubéola");
        $res17 = stripos($nome, "Caxumba");
        $res18 = stripos($nome, "Varicela");
        $res19 = stripos($nome, "triviral");
        $res20 = stripos($nome, "Influenza");
        $res21 = stripos($nome, "Rotavírus");
        $res22 = stripos($nome, "gripe");
        $res23 = stripos($nome, "PENTA");
        $res24 = stripos($nome, "HEXA");
        $res25 = stripos($nome, "DTPHib+VOP");
        $res26 = stripos($nome, "duplaviral");
        $res27 = stripos($nome, "tetraviral");
        $res28 = stripos($nome, "DTPHibHB");
        $res29 = stripos($nome, "DTP+VOP");

        $z = 0;

        if ($res1 !== false OR $res2 !== false) {
            $arr0[] = $id;
            $z++;
        }
        if ($res3 !== false OR $res24 !== false OR $res28 !== false) {
            $arr1[] = $id;
            $z++;
        }
        if ($res4 !== false OR $res5 !== false OR $res6 !== false OR $res7 !== false OR $res8 !== false OR $res23 !== false OR $res24 !== false OR $res28 !== false OR $res29 !== false) {
            $arr2[] = $id;
            $z++;
        }
        if ($res9 !== false OR $res10 !== false OR $res11 !== false OR $res23 !== false OR $res24 !== false) {
            $arr3[] = $id;
            $z++;
        }
        if ($res12 !== false OR $res23 !== false OR $res24 !== false OR $res25 !== false OR $res28 !== false) {
            $arr4[] = $id;
            $z++;
        }
        if ($res13 !== false) {
            $arr5[] = $id;
            $z++;
        }
        if ($res14 !== false) {
            $arr6[] = $id;
            $z++;
        }
        if ($res15 !== false OR $res16 !== false OR $res17 !== false OR $res18 !== false OR $res19 !== false OR $res26 !== false OR $res27 !== false) {
            $arr7[] = $id;
            $z++;
        }
        if ($res20 !== false OR $res21 !== false OR $res22 !== false) {
            $arr8[] = $id;
            $z++;
        }
        if ($z == 0) {
            $arr9[] = $id;
        }
        unset($z);
    }

    $count = count($arr0);

    if ($count == 1) {
        $lista_vacinas[0] = $arr0[0];
    } else {
        $lista_vacinas[0] = $arr0;
    }

    $count = count($arr1);
    if ($count == 1) {
        $lista_vacinas[1] = $arr1[0];
    } else {
        $lista_vacinas[1] = $arr1;
    }

    $count = count($arr2);
    if ($count == 1) {
        $lista_vacinas[2] = $arr2[0];
    } else {
        $lista_vacinas[2] = $arr2;
    }

    $count = count($arr3);
    if ($count == 1) {
        $lista_vacinas[3] = $arr3[0];
    } else {
        $lista_vacinas[3] = $arr3;
    }

    $count = count($arr4);
    if ($count == 1) {
        $lista_vacinas[4] = $arr4[0];
    } else {
        $lista_vacinas[4] = $arr4;
    }

    $count = count($arr5);
    if ($count == 1) {
        $lista_vacinas[5] = $arr5[0];
    } else {
        $lista_vacinas[5] = $arr5;
    }

    $count = count($arr6);
    if ($count == 1) {
        $lista_vacinas[6] = $arr6[0];
    } else {
        $lista_vacinas[6] = $arr6;
    }

    $count = count($arr7);
    if ($count == 1) {
        $lista_vacinas[7] = $arr7[0];
    } else {
        $lista_vacinas[7] = $arr7;
    }

    $count = count($arr8);
    if ($count == 1) {
        $lista_vacinas[8] = $arr8[0];
    } else {
        $lista_vacinas[8] = $arr8;
    }

    $count = count($arr9);
    if ($count == 1) {
        $lista_vacinas[9] = $arr9[0];
    } else {
        $lista_vacinas[9] = $arr9;
    }

    return $lista_vacinas;
}

function statusHistoricoPorId($idhist) {

    $sql = "SELECT
				status.nome
			FROM
				historico,status
			WHERE
				historico.id = $idhist
				AND
				historico.status_id = status.id
		";

//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    $hist = mysqli_fetch_row($query);

    return $hist[0];
}

function dadosHistoricoPorIds($str_ids) {

    $sql = "SELECT
			historico.id,
			historico.data,
			historico.status_id,
			historico.tipo,
			servico.material_id,
			servico.cliente_cliente_id as cliente_id
		FROM
			servico,historico
		WHERE
			historico.id IN ($str_ids)
			AND
			servico.id = historico.servico_id
		ORDER BY
			historico.data ASC
	";

//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

function vacinasPorClienteRealizadasPorData($idCliente, $data) {

    $sql = "SELECT
			status.nome AS status_nome,
			material.nome AS vacinaNome,
			material.id AS vacinaId
		FROM
			cliente,servico,historico,material,tipo_material,status,tipo_status
		WHERE
			cliente.cliente_id = $idCliente
			AND
			cliente.cliente_id = servico.cliente_cliente_id
			AND
			servico.id = historico.servico_id
			AND
			servico.material_id = material.id
			AND
			material.tipo_material_id = tipo_material.id
			AND
			historico.status_id = status.id
			AND
			status.tipo_status_id = tipo_status.id
			AND
			tipo_material.nome = 'vacina'
			AND
			historico.tipo = 'comum'
			AND
			status.nome = 'Realizado'
			AND
			historico.data = '$data'
			
		ORDER BY
			historico.data ASC
	";

//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

/**
 * Função para buscar as vacinas de acordo com seu status.
 * 
 * Aceita 3 valores para status:
 * 1 - A Realizar Hoje
 * 2 - Programado
 * 3 - Programado ou Pagto Antecipado
 * 
 * @param type $idCliente
 * @param type $stat
 * @return type
 */
function vacinasPorClientePorStatus($idCliente, $stat) {

    if ($stat == 1) {
        $status = "status.nome = 'A Realizar (Hoje)'";
    } else if ($stat == 2) {
        $status = "status.nome = 'Programado'";
    } else if ($stat == 3) {
        $status = "(status.nome = 'Programado' OR status.nome = 'Pagto Antecipado')";
    }

    $sql = "SELECT
			status.nome AS status_nome,
			material.nome AS vacinaNome,
			material.id AS vacinaId,
			material.descricao_ingles,
			historico.modulo,
			servico.id AS servico_id,
			DATE_FORMAT(historico.data,'%d/%m/%Y') AS data_prevista
		FROM
			cliente,servico,historico,material,tipo_material,status,tipo_status
		WHERE
			cliente.cliente_id = $idCliente
			AND
			cliente.cliente_id = servico.cliente_cliente_id
			AND
			servico.id = historico.servico_id
			AND
			servico.material_id = material.id
			AND
			material.tipo_material_id = tipo_material.id
			AND
			historico.status_id = status.id
			AND
			status.tipo_status_id = tipo_status.id
			AND
			(tipo_material.nome = 'vacina' OR tipo_material.nome = 'teste')
			AND
			historico.tipo = 'comum'
			AND
			$status
		ORDER BY
			historico.data ASC
	";
//historico.modulo = 0
//AND
//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

function vacinasPorCliente($idCliente) {

    $sql = "SELECT
			status.nome AS status_nome,
			material.nome AS vacinaNome,
			material.descricao_ingles,
			DATE_FORMAT(historico.data,'%m/%d/%y') AS data_prevista_en,
			DATE_FORMAT(historico.data,'%d/%m/%y') AS data_prevista
		FROM
			cliente,servico,historico,material,tipo_material,status,tipo_status
		WHERE
			cliente.cliente_id = $idCliente
			AND
			cliente.cliente_id = servico.cliente_cliente_id
			AND
			servico.id = historico.servico_id
			AND
			servico.material_id = material.id
			AND
			material.tipo_material_id = tipo_material.id
			AND
			historico.status_id = status.id
			AND
			status.tipo_status_id = tipo_status.id
			AND
			tipo_material.nome = 'vacina'
			AND
			historico.tipo = 'comum'
			AND
			status.nome <> 'Programado'
		ORDER BY
			historico.data ASC
	";

//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

function buscaResutadoTesteTcaPorHistorico($idHistorico) {

    $sql = "SELECT
				historico.data as data_aplicacao,
				resultado_teste.data AS data_leit,
				resultado_teste.valor
			FROM
				resultado_teste,historico
			WHERE
				historico.id = resultado_teste.historico_id
				AND
				historico.id = $idHistorico
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

function buscaResutadoTesteImuPorHistorico($idHistorico) {

    $sql = "SELECT
				historico.data as data_coleta,
				resultado_teste.valor
			FROM
				resultado_teste,historico
			WHERE
				historico.id = resultado_teste.historico_id
				AND
				historico.id = $idHistorico
	";

    $query = mysqli_query(banco::$connection, $sql);

    $hist = mysqli_fetch_assoc($query);

    return $hist;
}

function buscaResutadoTestePorHistorico($idHistorico) {

    $sql = "SELECT
				*
			FROM
				resultado_teste
			WHERE
				historico_id = $idHistorico
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

function tipoHistoricoPorHistorico($idHistorico) {

    $sql = "SELECT
			tipo
		FROM
			historico
		WHERE
			id = $idHistorico
	";

//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    $hist = mysqli_fetch_row($query);

    return $hist[0];
}

function grupoHistoricoPorIdHistorico($idHistorico) {

    $sql = "SELECT
			*,
			DATE_FORMAT(data,'%m/%y') AS data_hist
		FROM
			grupo_historico
		WHERE
			historico_id_agrupador = $idHistorico
		ORDER BY
			data ASC
	";

//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    while ($hist = mysqli_fetch_assoc($query)) {
        $h[] = $hist;
    }

    return $h;
}

/**
 * Função para buscar o Histórico de Vacinas para um Cliente
 *
 * @param $idCliente
 *
 * @author Bruno Haick
 * @date Criação: 16/09/2012
 *
 * @return
 * $historico
 *
 */
function historicoVacina($idCliente, $vacinas) {

    mysqli_set_charset(banco::$connection, 'utf8');
    foreach ($vacinas as $vacina) {

        $len = count($vacina);
        $and = "";

        if ($len > 0) {
            if ($len == 1) {
                $and .= "AND material.id = '$vacina'";
            } else if ($len > 1) {
                $i = 0;
                $and .= "AND (";
                while ($i < count($vacina)) {
                    $nome = $vacina[$i];
                    if ($i == 0) {
                        $and .= "material.id = '$nome'";
                    } else {
                        $and .= "OR material.id = '$nome'";
                    }
                    $i++;
                }
                $and .= ")";
            }

            $sql = "SELECT
					status.cor_hex AS cor_hex,
					material.nome AS vacinaNome,
					DATE_FORMAT(historico.data,'%d%b%y') AS data_prevista,
					historico.data AS data_pre,
					historico.id AS historicoId,
					historico.eventos_adversos_id,
					historico.conduta,
					historico.evolucao,
					historico.data_queixa,
					historico.agrupado,
					historico.tipo,
					servico.id AS servicoId
				FROM
					cliente,servico,historico,material,tipo_material,status,tipo_status
				WHERE
					cliente.cliente_id = $idCliente
					AND
					cliente.cliente_id = servico.cliente_cliente_id
					AND
					servico.id = historico.servico_id
					AND
					servico.material_id = material.id
					AND
					material.tipo_material_id = tipo_material.id
					AND
					historico.status_id = status.id
					AND
					status.tipo_status_id = tipo_status.id
					AND(
					tipo_material.nome = 'vacina'
					OR
					tipo_material.nome = 'teste')
					AND
					historico.agrupado <> 1
					$and
				ORDER BY
					historico.data ASC
			";

//AND
//tipo_status.nome = 'vacina'
//echo $sql;
            $query = mysqli_query(banco::$connection, $sql);
            while ($hist = mysqli_fetch_assoc($query)) {
                $h[] = $hist;
            }

            $count = count($h);
            $a = $count - 6;

            if ($count > 6) {

                while ($a < $count) {
                    $h2[] = $h[$a];
                    $a++;
                }
                $h2[] = "";
                $a = $count - 6;
                for ($i = 0; $i < $a; $i++) {
                    $h2[] = $h[$i];
                }

                $historico[] = $h2;
            } else {
                $historico[] = $h;
            }

            unset($h);
            unset($h2);
        } else {
            $historico[] = $h;
        }
    }

    return $historico;
}

/**
 * Função para buscar o Histórico de Vacinas para um Cliente 
 * Para Imprimir a carteira de Vacina.
 *
 * @param $idCliente
 *
 * @author Bruno Haick
 * @date Criação: 29/06/2013
 *
 * @return
 * $historico
 *
 */
function historicoVacinaImpressao($idCliente, $vacinas) {

    mysqli_set_charset(banco::$connection, 'utf8');
    foreach ($vacinas as $vacina) {

        $len = count($vacina);
        $and = "";

        if ($len > 0) {
            if ($len == 1) {
                $and .= "AND material.id = '$vacina'";
            } else if ($len > 1) {
                $i = 0;
                $and .= "AND (";
                while ($i < count($vacina)) {
                    $nome = $vacina[$i];
                    if ($i == 0) {
                        $and .= "material.id = '$nome'";
                    } else {
                        $and .= "OR material.id = '$nome'";
                    }
                    $i++;
                }
                $and .= ")";
            }

            $sql = "SELECT
					status.cor_hex AS cor_hex,
					material.nome AS vacinaNome,
					DATE_FORMAT(historico.data,'%d%b%y') AS data_prevista,
					historico.data AS data_pre,
					historico.id AS historicoId,
					servico.id AS servicoId
				FROM
					cliente,servico,historico,material,tipo_material,status,tipo_status
				WHERE
					cliente.cliente_id = $idCliente
					AND
					cliente.cliente_id = servico.cliente_cliente_id
					AND
					servico.id = historico.servico_id
					AND
					servico.material_id = material.id
					AND
					material.tipo_material_id = tipo_material.id
					AND
					historico.status_id = status.id
					AND
					status.tipo_status_id = tipo_status.id
					AND
					tipo_material.nome = 'vacina'
					AND
					status.nome <> 'Programado'
					$and
				ORDER BY
					historico.data ASC
			";

//AND
//tipo_status.nome = 'vacina'
//echo $sql;
            $query = mysqli_query(banco::$connection, $sql);
            while ($hist = mysqli_fetch_assoc($query)) {
                $h[] = $hist;
            }

            $historico[] = $h;
            unset($h);
        } else {
            $historico[] = $h; // $h está vazio.
        }
    }

    return $historico;
}

/**
 * Função para buscar os dados da  imunoterapia
 * através do id.
 *
 * @author Bruno Haick
 * @date Criação: 25/09/2012
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function dadosImunoterapia($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.quantidade_doses,
				material.nome,
				material.preco,
				material.qtd_ml_por_dose,
				material.preco_por_dose
			FROM
				material,tipo_material
			where
				material.id = $id
				and
				tipo_material.id = material.tipo_material_id
				and
				tipo_material.nome = 'imunoterapia'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $dadosimuno[] = $linha;
    }

    return $dadosimuno[0];
}

function buscaNomeLoginUsuarioPorServico($idServico) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				usuario.login
			FROM
				servico, usuario
			WHERE
				servico.usuario_id = usuario.usuario_id
				AND
				id = $idServico
	";

    $query = mysqli_query(banco::$connection, $sql) or die(mysqli_error(banco::$connection));

    $linha = mysqli_fetch_assoc($query);

    return $linha['login'];
}

/**
 * Função para buscar os dados de todos os serviços em
 * aberto por Família através do id dos integrantes da
 * mesma.
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function todasImunoterapiasCliente($idCliente) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			servico.data as servico_data,
			material.nome as material_nome
		FROM
			material,servico,tipo_material,cliente,`status`,tipo_status,pessoa
		WHERE
			tipo_material.id = material.tipo_material_id
			AND
			tipo_material.nome = 'imunoterapia'
			AND
			status.tipo_status_id = tipo_status.id
			AND
			cliente.cliente_id = servico.cliente_cliente_id
			AND
			servico.material_id = material.id
			AND
			servico.status_id = status.id
			AND
			status.nome = 'Pago'
			AND
			servico.finalizado = '0'
			AND
			cliente.cliente_id = pessoa.id
			AND
			cliente.cliente_id = '$idCliente'
	";

    $query = mysqli_query(banco::$connection, $sql) or die(mysqli_error(banco::$connection));

    while ($linha = mysqli_fetch_assoc($query)) {
        $dadosimuno[] = $linha;
    }

    return $dadosimuno;
}

/**
 * Função para buscar os dados de todos os serviços em
 * aberto por Família através do id dos integrantes da
 * mesma.
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function todosServicosFamilia($arr) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (count($arr) == 1) {
        $and = "AND cliente.cliente_id = '$arr[0]'";
    } else if (count($arr) > 1) {

        $a = 0;
        $and .= "AND (";
        foreach ($arr as $familia) {

            if ($a == 0) {
                $and .= "cliente.cliente_id = '$familia'";
                $a++;
            } else {
                $and .= "OR cliente.cliente_id = '$familia'";
            }
        }
        $and .= ")";
    }

    $sql = "SELECT
			servico.qtd_doses * material.qtd_ml_por_dose as servico_qtd_ml_total,
			servico.id as servico_id,
			servico.preco as servico_preco,
			status.nome as status_nome,
			material.qtd_ml_por_dose as material_qtd_ml_por_dose,
			material.nome as material_nome,
			status.nome as status_nome,
			pessoa.nome as pessoa_nome
		FROM
			material,servico,tipo_material,cliente,`status`,tipo_status,pessoa
		WHERE
			tipo_material.id = material.tipo_material_id
			AND
			tipo_material.nome = 'imunoterapia'
			AND
			status.tipo_status_id = tipo_status.id
			AND
			cliente.cliente_id = servico.cliente_cliente_id
			AND
			servico.material_id = material.id
			AND
			servico.status_id = status.id
			AND
			status.nome = 'Pago'
			AND
			servico.finalizado = '0'
			AND
			cliente.cliente_id = pessoa.id
			$and
	";

    $query = mysqli_query(banco::$connection, $sql) or die(mysqli_error(banco::$connection));

    while ($linha = mysqli_fetch_assoc($query)) {
        $dadosimuno[] = $linha;
    }

    return $dadosimuno;
//return $sql;
}

/**
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function somaDosesHistoricoPorServico($servico_id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				SUM(historico.qtd_ml) as qtd_ml
			FROM
				historico,servico
			WHERE
				historico.servico_id = servico.id
				AND
				servico.id = $servico_id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $soma = mysqli_fetch_row($query);

    return $soma[0];
}

/**
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function dosesRestantesServico($arr) {

    $todosServicos = todosServicosFamilia($arr);
    foreach ($todosServicos as $indice => $servico) {

        $qtdDosesHist = somaDosesHistoricoPorServico($servico['servico_id']);
        $x = $servico['servico_qtd_ml_total'] - $qtdDosesHist;

        $resto = $x % $servico['material_qtd_ml_por_dose'];
        $numDoses = (int) ($x / $servico['material_qtd_ml_por_dose']);

        if ($resto == 0) {
            $todosServicos[$indice]['qtd'] = $numDoses . " d";
        } else {
            $todosServicos[$indice]['qtd'] = $numDoses . " d + " . $resto . "ml";
        }
    }

    return $todosServicos;
}

/**
 * Função para buscar Todos os testes 
 * presentes no banco de dados.
 *
 * @author Bruno Haick, Amir
 * @date Criação: 10/01/2014
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function todosTeste() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			material.*
		FROM
			material,tipo_material
		WHERE
			tipo_material.nome = 'teste'
			AND
			tipo_material.id = material.tipo_material_id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para buscar Todas as imunoterapias 
 * presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação: 25/09/2012
 *
 * @return
 * 	Array contendo todas as imunoterapias
 *
 */
function todasImunoterapias() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			material.*
		FROM
			material,tipo_material
		WHERE
			tipo_material.nome = 'imunoterapia'
			AND
			tipo_material.id = material.tipo_material_id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para buscar enfermeiro a partir da matricula
 * do seu Cliente;
 * @param string $matriculaCliente
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @return
 * 	Array contendo os dados do Enfermeiro encontrado;
 *
 */
function buscaEnfermeiroPorCliente($matriculaCliente) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			pessoa,cliente,enfermeiro,usuario
		WHERE
			usuario.usuario_id = pessoa.id
			AND
			enfermeiro.enfermeiro_id = usuario.usuario_id
			AND
			enfermeiro.enfermeiro_id = cliente.fk_enfermeiro_id
			AND
			cliente.cliente_id = '$matriculaCliente'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $enfermeiro = mysqli_fetch_assoc($query);

    return $enfermeiro;
}

/**
 * Busca clientes atraves de um dos parametros.
 *
 * @param string $nome
 * @param string $nascimento
 * @param string $dados_outro
 * @param string $outro
 * @param string $tipo
 *
 * @return
 * 	Array contendo os clientes encontrados com o tipo especificado
 *
 * 	@todo
 * 		@li REFAZER A FUNCAO
 *
 */
function pesquisaCliente($nome, $nascimento, $dados_outro, $outro, $tipo, $pagina, $arquivoMorto) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = 'SELECT cliente.cliente_id, cliente.flag, cliente.membro,antecedente_pessoal, cliente.antecedente_familiar, cliente.alergias, pessoa.* 
			FROM pessoa INNER JOIN cliente 
			ON pessoa.id = cliente.cliente_id 
            LEFT JOIN titular ON pessoa.id = titular.titular_id
            WHERE  
                CONCAT(TRIM(`nome`),
                \' \',
                IF(`sobrenome` IS NOT NULL,
                    TRIM(pessoa.sobrenome)
                    ,\'\')
                ) 
                    LIKE \'%' . $nome . '%\' ';


    if (!empty($nascimento)) {
        $sql .= " AND pessoa.data_nascimento LIKE '%" . converteData($nascimento) . "%' ";
    }

    if ($arquivoMorto !== 'false') {
        $sql .= ' AND pessoa.ativo = 1 ';
    }

    if ($tipo == 'familiares') {
        $sql .= " AND cliente.flag = 'T' ";
    } else if ($tipo == 'membros') {
        $sql .= " AND cliente.flag = 'D' ";
    }

    if (!empty($dados_outro)) {
        if ($outro == 'telefone') {
            $sql .= " AND pessoa.tel_residencial LIKE '%$dados_outro%' ";
        }
        if ($outro == 'cpf') {
            $sql .= ' AND titular.doc_nf LIKE \'' . $dados_outro . '%\' ';
        }
        if ($outro == 'conjuge') {
            $sql .= " AND pessoa.conjuge LIKE '%$dados_outro%' ";
        }
        if ($outro == 'email') {
            $sql .= " AND pessoa.email LIKE '%$dados_outro%' ";
        }
    }

    $pagina *= 100;

    $sql .= " ORDER BY pessoa.nome, pessoa.sobrenome LIMIT {$pagina},100;";
// die(var_dump($sql));
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }


    return $array;
}

function pesquisaClienteCount($nome, $nascimento, $dados_outro, $outro, $tipo, $pagina, $arquivoMorto) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = 'SELECT count(cliente.cliente_id) as \'count\', cliente.flag, cliente.membro,antecedente_pessoal, cliente.antecedente_familiar, cliente.alergias, pessoa.*
			FROM pessoa INNER JOIN cliente
			ON pessoa.id = cliente.cliente_id WHERE  CONCAT(TRIM(`nome`),\' \',IF(`sobrenome` IS NOT NULL,TRIM(pessoa.sobrenome),\'\')) LIKE \'%' . $nome . '%\' ';


    if (!empty($nascimento)) {
        $sql .= " AND pessoa.data_nascimento LIKE '%" . converteData($nascimento) . "%' ";
    }

    if ($arquivoMorto !== 'false') {
        $sql .= ' AND pessoa.ativo = 1 ';
    }

    if ($tipo == 'familiares') {
        $sql .= " AND cliente.flag = 'T' ";
    } else if ($tipo == 'membros') {
        $sql .= " AND cliente.flag = 'D' ";
    }

    if (!empty($dados_outro)) {
        if ($outro == 'telefone') {
            $sql .= " AND pessoa.tel_residencial LIKE '%$dados_outro%' ";
        } else if ($outro == 'cpf') {
            $sql .= " AND titular.doc_nf LIKE '%$dados_outro%' ";
        } else if ($outro == 'conjuge') {
            $sql .= " AND pessoa.conjuge LIKE '%$dados_outro%' ";
        } else if ($outro == 'email') {
            $sql .= " AND pessoa.email LIKE '%$dados_outro%' ";
        }
    }


    $pagina *= 100;
    $sql .= " ORDER BY pessoa.nome LIMIT {$pagina},100;";
    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);
    return $linha['count'];
}

/**
 * Função para excluir uma Guia Tiss
 *
 * @param string $matricula
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function deletarGuiaTiss($id_guia) {

    $where = "id = " . $id_guia;

    if (remover('guia_tiss', $where)) {
        return true;
    } else {
        return false;
    }
}

/**
 * # Ainda nao utilizada no software
 *
 * Função para excluir uma pessoa
 *
 * @param string $matricula
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function excluirPessoa($matricula) {

    $where = "id = " . $matricula;

    if (remover('pessoa', $where)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir os antecedentes morbidos de um cliente no banco.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereClienteAntMorbido($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('cliente_has_antecedente_morbido', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para conferir se os antecedentes morbidos de um cliente já foram cadastrados anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param string $id
 * @param string $dados
 *
 * @return
 * 	TRUE se o usuario existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaClienteAntMorbido($id, $id_AM) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			COUNT(*)
		FROM
			cliente_has_antecedente_morbido
		WHERE
			cliente_cliente_id = '$id'
			AND
			antecedente_morbido_id = '$id_AM'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    return true;
}

/**
 * Função para inserir as alergias de um cliente no banco.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereClienteAlergias($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('cliente_has_alergias', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir resultados dos testes a partir da ficha de vacina.
 * esta funcao insere resultado para os testes:
 * Mantoux leit; Mitsuda; T.Pesinho(Master,Plus,Ampliada); HIV I e II;
 * Imunodeficiencia; TCA Imunidade;
 *
 * @author Bruno Haick
 * @date Criação : 17/07/2013
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereResultadoTestes($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('resultado_teste', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereControle($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('controle', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereControleDetalhado($clienteid, $materialid, $guiaid, $servicoid, $numerocontrole) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO controle (cliente_id,material_id,guia_controle_id,servico_id,data,hora,status,modulo,numero_controle) 
    VALUES('$clienteid','$materialid','$guiaid','$servicoid',CURDATE(),CURTIME(),'A REALIZAR (HOJE)','0','$numerocontrole')";
// var_dump(print_r($sql));

    if (mysqli_query(banco::$connection, $sql)) {
        return true;
    } else {
        return false;
    }
}

function removerControleDetalhado($controleid, $materialid) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "DELETE FROM controle WHERE numero_controle = '$controleid' AND material_id = '$materialid';";
// die(var_dump($sql));
    if (mysqli_query(banco::$connection, $sql)) {
        return true;
    } else {
        return false;
    }
}

function removerServicoDetalhado($servicoid) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "DELETE FROM servico WHERE id = '$servicoid';";
    if (mysqli_query(banco::$connection, $sql)) {
        return true;
    } else {
        return false;
    }
}

function insereGuiaControle($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (inserir('guia_controle', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para conferir se as alergias de um cliente já foram cadastradas anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param string $id
 * @param string $dados
 *
 * @return
 * 	TRUE se o usuario existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaClienteAlergias($id, $id_alergias) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			COUNT(*)
		FROM
			cliente_has_alergias
		WHERE
			cliente_cliente_id = '$id'
			AND
			alergias_id = '$id_alergias'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    return true;
}

/**
 * Função para inserir as condições de nascimento de um cliente no banco.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereClienteCN($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('cliente_has_condicoes_nascimento', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para conferir se as condições de nascimento de um cliente já foram cadastradas anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param string $id
 * @param string $dados
 *
 * @return
 * 	TRUE se o usuario existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaClienteCN($id, $id_CN) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			COUNT(*)
		FROM
			cliente_has_condicoes_nascimento
		WHERE
			cliente_cliente_id = '$id'
			AND
			condicoes_nascimento_id = '$id_CN'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    return true;
}

/**
 * Função para buscar os bairros presentes no banco de dados.
 *
 * @return
 * 	Array contendo todos os bairros encontrados
 *
 */
function buscaBairros() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('bairros', 'bairro');

    return $array;
}

/**
 * Função para buscar os antecedentes morbidos presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  16/09/2012
 * @return
 * 	Array contendo todos os antecedentes morbidos encontradas.
 *
 */
function buscaAntecedenteMorbido() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('antecedente_morbido', '*');

    return $array;
}

/**
 * Função para buscar as alergias presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  16/09/2012
 * @return
 * 	Array contendo todas as alergias encontradas.
 *
 */
function buscaAlergias() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('alergias', '*');

    return $array;
}

/**
 * Função para buscar as condições de nascimento presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  16/09/2012
 * @return
 * 	Array contendo todas as condições de nascimento encontradas.
 *
 */
function buscaCondicoesDeNascimento() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('condicoes_nascimento', '*');

    return $array;
}

/**
 * Função para buscar os partos presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  16/09/2012
 * @return
 * 	Array contendo todos os tipos de parto encontrados.
 *
 */
function buscaParto() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('parto', '*');

    return $array;
}

/**
 * Função para buscar as gestações presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  16/09/2012
 * @return
 * 	Array contendo todos os tipos de gestação encontrados.
 *
 */
function buscaGestacao() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('gestacao', '*');

    return $array;
}

/**
 *  Função para montar uma string com um array para 
 *  que a função do bootstrap de typeahead funcione.
 *
 * @return String com a lista dos bairros
 * 	
 * 	@todo
 * 		@li explicar melhor esta funcao
 *
 */
function mostraBairros() {

    $arr = buscaBairros();
    $i = 0;
    $count = count($arr);
    $listabairros = "[";
    while ($i < $count) {

        $virgula = ",";
        if ($i == 0) {
            $virgula = "";
        }
        $listabairros .= $virgula . "\"" . $arr[$i]['bairro'] . "\"";
        $i++;
    }
    $listabairros .= "]";

    return $listabairros;
}

/**
 * Busca clientes atraves de um dos parametros.
 *
 * @author  Andrey Maia
 *
 * @param int $id id do médico que vamos buscar os agendamentos
 *
 * @return
 * 	Array com todos os agendamentos
 *
 * 	@todo
 *
 */
function buscaAgendamentos($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			agendamento.*,
			medico.nome As nome_medico,
			paciente.nome As nome_paciente
		FROM
			agendamento
		INNER JOIN
			pessoa medico ON (pessoa.id = agendamento.medico_id)
		INNER JOIN
			pessoa paciente ON (pessoa.id = agendamento.cliente_id)
	";

    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 * Função para buscar os dados da fila de espera presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  16/09/2012
 * @return
 * 	Array contendo todos os tipos de gestação encontrados.
 *
 */
function buscaFilaDeEsperaDataMedico($data, $medico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = "";

    $sql = "SELECT
			*
		FROM
			fila_espera_consulta
		WHERE
			data = '$data'
		AND
			medico_id = '$medico'
	";

    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function atendeFileDeEsperaMedico($id, $hora) {
    $sql = 'UPDATE `fila_espera_consulta` SET `hora_atendimento`= \'' . $hora . '\' WHERE id = ' . $id . ' ;';
    mysqli_query(banco::$connection, $sql);
}

function desatendeFilaDeEsperaMedico($id) {
    $sql = 'UPDATE `fila_espera_consulta` SET `hora_atendimento`= NULL WHERE id = ' . $id . ' ;';
    mysqli_query(banco::$connection, $sql);
}

/**
 * Função para buscar os convenios presentes no banco de dados.
 *
 * @author  Bruno Haick
 * @date Criação :  14/01/2014
 * @return
 * 	Array contendo todos os convenios encontrados ordenados por nome.
 *
 */
function buscaConvenio() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				convenio
			ORDER BY
				nome
    ";
    Database::query($sql);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * Função para buscar os dados da tabela de nome 'tabela' presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  20/09/2012
 * @return
 * 	Array contendo todos os nomes da tabela encontrados.
 *
 */
function buscaTabela() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('tabela', '*');

    return $array;
}

/**
 * Função para buscar os procedimentos presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  20/09/2012
 * @returnhttp://lists.mysql.com/mysql/205106
 * 	Array contendo todos os procedimentos encontrados.
 *
 */
function buscaProcedimento() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('procedimento', '*');

    return $array;
}

/**
 * Função para buscar os dados de um procedimento.
 *
 * @author  Bruno Haick
 * @date Criação : 08/10/2012
 *
 * @param $id - Id do procedimento
 *
 * @return
 * 	Array contendo os dados do procedimento
 *
 */
function dadosProcedimento($id, $convenio_id, $tabela_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			procedimento.*,
			convenio_has_procedimento_has_tabela.valor_procedimento as valor
		FROM
			procedimento,convenio_has_procedimento_has_tabela
		WHERE
			procedimento.id = $id
			AND
			convenio_has_procedimento_has_tabela.procedimento_id = procedimento.id
			AND
			convenio_has_procedimento_has_tabela.convenio_id = $convenio_id
			AND
			convenio_has_procedimento_has_tabela.tabela_id = $tabela_id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para buscar os dados do Grupo de Procedimentos através de seu id
 *
 * @author  Bruno Haick
 * @date Criação : 16/10/2012
 *
 * @return
 * 	Array contendo os dados do grupo de procedimentos
 *
 */
function buscaGrupoProcedimento($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			grupo_procedimento.*
		FROM
			grupo_procedimento
		WHERE
			id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $grupo = mysqli_fetch_assoc($query);

    return $grupo;
}

/**
 * Função para buscar as Guias TISS existentes
 *
 * @author  Bruno Haick
 * @date Criação : 12/08/2013
 *
 * @return
 * 	Array contendo todas as guias TISS
 *
 */
function listaGruposProcedimentos($usuario_id, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				g.*
			FROM
				`grupo_procedimento` g INNER JOIN medico m
				ON
				m.medico_id = g.medico_medico_id INNER JOIN `cliente` c
				ON
				c.cliente_id = g.cliente_cliente_id INNER JOIN `usuario` e
				ON
				e.usuario_id = g.usuario_id LEFT JOIN `guia_tiss` u
				ON
				g.guia_tiss_id = u.id
			WHERE
				g.usuario_id = $usuario_id
				AND
				g.data = '$data'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $listaGuias[] = $linha;
    }

    return $listaGuias;
}

/**
 * Função para buscar os procedimentos ligados aos ids 
 * dados como parametros.
 *
 * @author  Bruno Haick
 * @date Criação :  08/10/2012
 *
 * @param $convenio_id - Id do convenio
 * @param $tabela_id -  Id da tabela
 *
 * @return
 * 	Array contendo todos os procedimentos que estejam ligados
 * 	aos ids dados como parâmetro para a função..
 *
 */
function listaProcedimentos($convenio_id, $tabela_id, $medico_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			procedimento.*,
			convenio_has_procedimento_has_tabela.valor_ch as 'ch',
			convenio_has_procedimento_has_tabela.valor_procedimento as 'real'
		FROM
			convenio_has_procedimento_has_tabela, convenio, tabela, procedimento
		WHERE
			convenio_has_procedimento_has_tabela.convenio_id = convenio.id
			AND
			convenio_has_procedimento_has_tabela.procedimento_id = procedimento.id
			AND
			convenio_has_procedimento_has_tabela.tabela_id = tabela.id
			AND
			convenio.id = '$convenio_id'
			AND
			tabela.id = '$tabela_id'
		ORDER BY
			procedimento.nome
	";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $listaProc[] = $linha;
    }
//print_r($listaProc);

    return $listaProc;
}

/**
 * Função para buscar o id de um status
 *
 * @author  Bruno Haick
 * @date Criação :  10/10/2012
 *
 * @param $nomeStatus - Nome do status
 * @param $tipoStatus - Tipo do status desejado
 *
 * @return
 * 	int id
 *
 */
function statusIdPorNome($nome, $tipoStatus) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			status.id
		FROM
			status,tipo_status
		WHERE
			status.tipo_status_id = tipo_status.id
			AND
			status.nome = '$nome'
			AND
			tipo_status.nome = '$tipoStatus'
	";
//	echo $sql;
//	die();
    $query = mysqli_query(banco::$connection, $sql);

    $status = mysqli_fetch_row($query);

    return $status[0];
}

/**
 * Função para buscar o status de um determinado Serviço
 *
 * @author  Bruno Haick
 * @date Criação :  03/10/2012
 *
 * @param $ - Tipo do status desejado
 * @param $tipoStatus - Tipo do status desejado
 *
 * @return
 * 	Array contendo todos os status do tipo escolhido.
 *
 */
function buscaStatusServico($servico_id, $tipoStatus) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				status.nome
			FROM
				servico,status,tipo_status
			WHERE
				status.tipo_status_id = tipo_status.id
				AND
				tipo_status.nome = '$tipoStatus'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 * Função para buscar os status de qualquer tipo 
 * existente no banco (agendamento, vacina, imunoterapia).
 *
 * @author  Bruno Haick
 * @date Criação :  26/09/2012
 *
 * @param $tipoStatus - Tipo do status desejado
 *
 * @return
 * 	Array contendo todos os status do tipo escolhido.
 *
 */
function buscaStatus($tipoStatus) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				status.*
			FROM
				status,tipo_status
			WHERE
				status.tipo_status_id = tipo_status.id
				AND
				tipo_status.nome = '$tipoStatus'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 * Função para buscar clientes (seja titular ou dependente) a partir de sua matricula
 *
 * @param string $matricula
 *
 * @author Marcus Dias
 * @date Criação: 20/09/2012
 *
 * @return
 * 	Array contendo os clientes encontrados com a determinada matricula
 *
 * 	@todo
 * 		utilizar a funcao generica inserir.
 *
 */
function buscaTodosClientes($matricula) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
					*
			FROM
				pessoa,cliente
			WHERE
				cliente.cliente_id = pessoa.id
				AND 
				cliente.cliente_id = '$matricula'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para conferir se o agendamento já foi feito anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 20/09/2012
 *
 * @param string $id
 * @param string $dados
 *
 * @return
 * 	TRUE se o agendamento existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaAgendamento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				COUNT(*), *
			FROM
				agendamento
			WHERE
				hora_chegada = '" . $dados['hora_chegada'] . "'
				AND ag.status_id != '9'
				AND medico_id = '" . $dados['medico_id'] . "'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

/**
 * Função para inserir um agendamento de um cliente no banco.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereAgendamento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('agendamento', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para desbloquear agendamento.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function desbloqueiaAgendamento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("agendamento", "hora_chegada ='" . $dados['hora_chegada'] . "' AND medico_id = " . $dados['medico_id'])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para editar um agendamento de um cliente no banco.
 *
 * @author Marcus Dias
 * @date Criação : 16/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarAgendamento($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $where = "id = $id";
    if (alterar('agendamento', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para buscar os agendamentos de certo diae medico, presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  20/09/2012
 * @return
 * 	Array contendo todos os procedimentos encontrados.
 *
 */
function buscaAgendamento($dia, $medico, $tmp) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $dia = converteData($dia);
    $array = listar("agendamento", "*", "data_agendamento = '$dia' AND medico_id = $medico AND hora_chegada = '$tmp'");

    return $array;
}

function busAgendamentoCompleto($dia, $medico, $tmp) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $dia = converteData($dia);
    return listar("agendamento", "*", 'data_agendamento = ' . $dia . ' AND medico_id = ' . $medico . 'AND hora_chegada IN (' . implode(',', $tmp) . ')');
}

/**
 * Busca o id da Imunoterapia dando 
 * como parametro o seu nome
 *
 * @author Bruno Haick
 * @date Criação: 26/09/2012
 *
 * @param String $nome
 *
 * @return int $id
 */
function idMaterialImunoByNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.id
			FROM
				material,tipo_material
			WHERE
				material.nome = '$nome'
				AND
				tipo_material.nome = 'imunoterapia'
				AND
				tipo_material.id = material.tipo_material_id
	";

    $query = mysqli_query(banco::$connection, $sql);
    $id = mysqli_fetch_row($query);

    return $id[0];
}

/**
 * Busca o id da Pessoa no banco de dados 
 * correspondente ao nome dado como 
 * parâmetro para a funcao.
 *
 * @author Bruno Haick
 * @date Criação: 27/09/2012
 *
 * @param String $nome
 *
 * @return int $id
 */
function idPessoaByNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				id
			FROM
				pessoa
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_row($query);

    return $linha[0];
}

/**
 * Busca o status no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * MongoInt32@return
 */
function idStatusServicoByNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				status
			WHERE
				status.nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql) or die(mysqli_error(banco::$connection));

    $id = mysqli_fetch_row($query);

    return $id[0];
}

/**
 * Busca o status no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * MongoInt32@return
 */
function statusById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("status", "nome", "id = $id");

    return $array['nome'];
}

/**
 * Busca o status no banco de dados correspondente a cor dado
 * como parâmetro para a funcao.
 *
 * @author Bruno Haick
 * @date Criação: 06/02/2013
 *
 * @param string $cor
 *
 * MongoInt32@return
 */
function statusByCor($cor) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("status", "id", "cor_hex = '$cor'");

    return $array['id'];
}

/**
 * Busca o status no banco de dados correspondente a cor dado
 * como parâmetro para a funcao.
 *
 * @author Bruno Haick
 * @date Criação: 06/02/2013
 *
 * @param string $nome
 *
 * MongoInt32@return
 */
function statusByNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("status", "id", "nome = '$nome'");

    return $array['id'];
}

/**
 * Busca o convenio no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * @return
 * 	Nome do convenio (String)
 */
function convenioById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("convenio", "nome", "id = $id");

    return $array['nome'];
}

/**
 * Busca o procedimento no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * @return
 * 	Nome do procedimento (String)
 */
function procedimentoById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("procedimento", "nome", "id = $id");

    return $array['nome'];
}

/**
 * Busca o agendamento no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * @return
 * 	array do agendamento
 */
function agendamentoById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("agendamento", "*", "id = $id");

    return $array;
}

/**
 * Confere o status do agendamento conforme os parametros recebidos
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * @return
 * 	id do status
 */
function confereStatus($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("agendamento", "status_id", "hora_chegada ='" . $dados['hora_chegada'] . "' AND data_agendamento = '" . $dados['data_agendamento'] . "' AND medico_id = " . $dados['medico_id']);
    return $array['status_id'];
}

/**
 * Função para mostrar os materiais no historico de estoque 
 *
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function buscaMaterialHistEstoque($idMaterial, $dataInicio, $dataFim) {

    $entrada = histEstoqueEntrada($idMaterial, $dataInicio, $dataFim);
    $saida = histEstoqueSaida($idMaterial, $dataInicio, $dataFim);
    $transferencia = histEstoqueTransf($idMaterial, $dataInicio, $dataFim);
    if (!empty($entrada) && !empty($saida) && !empty($transferencia)) {
        $materiais = array_merge($entrada, $transferencia, $saida);
    } else if (!empty($entrada) && !empty($saida)) {
        $materiais = array_merge($entrada, $saida);
    } else if (!empty($entrada) && !empty($transferencia)) {
        $materiais = array_merge($entrada, $transferencia);
    } else if (!empty($saida) && !empty($transferencia)) {
        $materiais = array_merge($saida, $transferencia);
    } else if (!empty($entrada)) {
        $materiais = $entrada;
    } else if (!empty($saida)) {
        $materiais = $saida;
    } else if (!empty($transferencia)) {
        $materiais = $transferencia;
    }

    return $materiais;
}

/**
 * Função para mostrar materiais de Entrada 
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function histEstoqueEntrada($idMaterial, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			movimentacao.id as id_movimentacao,
			movimentacao.data as data_movimentacao,
			movimentacao.flag,
			movimentacao.quantidade as quantidade,
			movimentacao.usuario_id,
			lote.id as id_lote,
			lote.nome as nome_lote,
			lote.validade,
			nota_fiscal.nota_fiscal,
			material.qtd_ml_por_dose,
			material.quantidade_doses,
			material.id as materialid
		FROM
			movimentacao,lote,material,entrada,nota_fiscal
		WHERE
			entrada.id = movimentacao.id
			AND
			entrada.nota_fiscal_id = nota_fiscal.id
			AND
			movimentacao.material_id = material.id
			AND
			movimentacao.lote_id = lote.id
			AND
			material.id = $idMaterial
			AND
			movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para mostrar materiais de Saida 
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function histEstoqueSaida($idMaterial, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			movimentacao.id as id_movimentacao,
			movimentacao.data as data_movimentacao,
			movimentacao.flag,
			movimentacao.quantidade as quantidade,
			movimentacao.usuario_id,
			lote.id as id_lote,
			lote.nome as nome_lote,
			lote.validade,
			motivo.id as id_motivo,
			material.qtd_ml_por_dose,
			material.quantidade_doses,
			material.id as materialid
		FROM
			movimentacao,lote,material,saida,motivo
		WHERE
			saida.id = movimentacao.id
			AND
			motivo.id = saida.motivo_id
			AND
			movimentacao.id = saida.id
			AND
			movimentacao.material_id = material.id
			AND
			movimentacao.lote_id = lote.id
			AND
			material.id = $idMaterial
			AND
			movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para mostrar materiais de Transferencia 
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function histEstoqueTransf($idMaterial, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				movimentacao.id as id_movimentacao,
				movimentacao.data as data_movimentacao,
				movimentacao.flag,
				movimentacao.quantidade as quantidade,
				movimentacao.usuario_id,
				lote.id as id_lote,
				lote.nome as nome_lote,
				lote.validade,
				material.qtd_ml_por_dose,
				material.quantidade_doses,
				material.id as materialid
			FROM
				movimentacao,lote,material,transferencia
			WHERE
				transferencia.id = movimentacao.id
				AND
				movimentacao.material_id = material.id
				AND
				movimentacao.lote_id = lote.id
				AND
				material.id = $idMaterial
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para mostrar os materiais no mapa de consumo 
 *
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function buscaMaterialMPConsumo($nome, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				lote.*,
				material.id as materialid,
				material.nome as materialnome
			FROM
				movimentacao,material,tipo_material,lote,saida
			WHERE
				tipo_material.nome LIKE '%$nome%'
				AND
				tipo_material.id = material.tipo_material_id
				AND
				material.id = movimentacao.material_id
				AND
				lote.id = movimentacao.lote_id
				AND
				movimentacao.flag = 'S'
				AND
				saida.motivo_id = 2
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				lote.nome,material.nome
			ORDER BY
				lote.validade ASC
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

function buscaMaterialMPConsumoAux($materialId, $loteId, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				movimentacao.data,SUM(movimentacao.quantidade) as qtd,
				DATE_FORMAT(movimentacao.data,'%w') as semana,
				movimentacao.data
			FROM
				movimentacao,material,lote,saida
			WHERE
				movimentacao.id = saida.id
				AND
				material.id = movimentacao.material_id
				AND
				lote.id = movimentacao.lote_id
				AND
				movimentacao.flag = 'S'
				AND
				saida.motivo_id = 2
				AND
				material.id = $materialId
				AND
				lote.id = $loteId
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				movimentacao.data
			ORDER BY
				movimentacao.data
			LIMIT 0,7
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para buscar material por Id
 *
 * @author Bruno Haick
 * @date Criação: 22/03/2014
 *
 * @param nomeProc nome do procedimento
 * @param valorProc valor do procedimento
 * 
 * @return
 * 	id do material
 *
 */
function buscaIdMaterialPorProcedimento($nomeProc, $valorProc) {
    $valorProc = rtrim($valorProc, '0');
    $valorProc = rtrim($valorProc, '.');
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				id
			FROM
				material
			WHERE
				nome = '$nomeProc'
				AND
				preco like $valorProc
	";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);
    $material = mysqli_fetch_row($query);

    return $material[0];
}

/**
 * Função para buscar material por Id
 *
 * @author Bruno Haick
 * @date Criação: 30/06/2013
 *
 * @return
 * 	nome do material
 *
 */
function buscaMaterialPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			nome
		FROM
			material
		WHERE
			id = $id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $material = mysqli_fetch_row($query);

    return $material[0];
}

/**
 * @description Função generica para buscar materiais
 *
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 * 
 * @Modified Bruno Haick
 * @date 05/01/2014
 * 
 * @param $nomeTipoMaterial nome do tipo de Material
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function buscaMaterial($nomeTipoMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.*
			FROM
				material,tipo_material
			WHERE
				tipo_material.nome LIKE '%$nomeTipoMaterial%'
				AND
				tipo_material.id = material.tipo_material_id
			ORDER BY
				material.nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para retornar o id de uma vacina dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação: 06/02/2013
 *
 * @return
 * 	Array contendo todos os dados da vacina
 *
 */
function idVacinaByName($nomeVac) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.id
			FROM
				material,tipo_material
			WHERE
				tipo_material.nome = 'vacina'
				AND
				tipo_material.id = material.tipo_material_id
				AND
				material.nome = '$nomeVac'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $vac = mysqli_fetch_assoc($query);

    return $vac['id'];
}

/**
 * Função para retornar os dados de um determinado material.
 *
 * @author Bruno Haick
 * @date Criação: 25/01/2013
 *
 * @return
 * 	nome do Material
 *
 */
function nomeMaterialPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			nome
		FROM
			material
		WHERE
			id = '$id'
	";
//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    $vac = mysqli_fetch_row($query);

    return $vac[0];
}

/**
 * Função para retornar os dados de um determinado material.
 *
 * @author Bruno Haick
 * @date Criação: 25/01/2013
 *
 * @return
 * 	Array contendo todos os dados da vacina
 *
 */
function dadosVacinaPorNome($nomeMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				material
			WHERE
				BINARY nome = '$nomeMaterial'
	";
//echo $sql;die();
    $query = mysqli_query(banco::$connection, $sql);

    $vac = mysqli_fetch_assoc($query);

    return $vac;
}

/**
 * Função para retornar os dados de um determinado material.
 *
 * @author Bruno Haick
 * @date Criação: 25/01/2013
 *
 * @return
 * 	Array contendo todos os dados da vacina
 *
 */
function dadosVacina($idMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.*
			FROM
				material,tipo_material
			WHERE
				tipo_material.id = material.tipo_material_id
				AND
				material.id = $idMaterial
	";
//echo $sql;
    $query = mysqli_query(banco::$connection, $sql);

    $vac = mysqli_fetch_assoc($query);

    return $vac;
}

/**
 * Função para buscar Todas as vacinas para mostrar no estoque
 *
 * @author Andrey Maia
 * @date Criação: 25/09/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function todosMateriais() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.*
			FROM
				material
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para buscar Todas as vacinas para mostrar no estoque
 *
 * @author Andrey Maia
 * @date Criação: 25/09/2012
 *
 * @return
 * 	Array contendo todos os dados da lista de vacinas
 *
 */
function todasVacinas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.*
			FROM
				material,tipo_material
			WHERE
				tipo_material.nome = 'vacina'
				AND
				tipo_material.id = material.tipo_material_id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para buscar os fretes presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  26/09/2012
 * @return
 * 	Array contendo todos os fretes encontrados.
 *
 */
function buscaFrete() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('frete', '*');

    return $array;
}

/**
 * Função para buscar id do Tipo de Operacao dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	Array contendo todos tipos de operacao
 *
 */
function buscaIdTipoOperacaoPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				tipo_operacao
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $tipo = mysqli_fetch_assoc($query);

    return $tipo['id'];
}

/**
 * Função para buscar os tipos de operacao por codigo
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	Array contendo todos os tipos de operacao.
 *
 */
function buscaNomeTipoOperacaoPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				tipo_operacao
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $cliente = mysqli_fetch_assoc($query);

    return $cliente['nome'];
}

/**
 * Função para buscar os nomes dos tipos e Operacao.
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 * @return
 * 	Array contendo os nomes dos tipos e Operacao.
 *
 */
function buscaNomeTipoOperacao($tipo = '%') {

    $sql = "SELECT
				nome
			FROM
				tipo_operacao
			WHERE
				e_s LIKE '$tipo'
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $tipos[] = $linha['nome'];
    }

    return $tipos;
}

/**
 * Função para buscar id da Conta Corrente dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	Array contendo conta corrente id
 *
 */
function buscaIdContaCorrentePorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				conta_corrente
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $conta = mysqli_fetch_assoc($query);

    return $conta['id'];
}

/**
 * Função para buscar as contas correntes.
 *
 * @author Bruno Haick
 * @date Criação :  15/03/2013
 * @return
 * 	Array contendo todas as contas encontrados.
 *
 */
function buscaTodasContasCorrente() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			id,
			nome
		FROM
			conta_corrente
		ORDER BY
			nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $contas[] = $linha;
    }

    return $contas;
}

/**
 * Função para buscar os tipos de Operacao.
 *
 * @author Bruno Haick
 * @date Criação :  18/03/2013
 * @return
 * 	Array contendo todos os tipos de Operacao.
 *
 */
function buscaNomeTiposOperacao($tipo = '%') {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				tipo_operacao
			WHERE
				e_s LIKE '$tipo'
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $tipos[] = $linha['nome'];
    }

    return $tipos;
}

/**
 * Função para buscar os tipos de Operacao por codigo.
 *
 * @author Bruno Haick
 * @date Criação :  18/03/2013
 * @return
 * 	Array contendo todos os tipos de Operacao por codigo.
 *
 */
function buscaNomeTiposOperacaoPorCodigo($cod, $tipo = '%') {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				tipo_operacao
			WHERE
				codigo ='$cod'
				AND
				e_s LIKE '$tipo'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $tipo = mysqli_fetch_assoc($query);

    return $tipo['nome'];
}

/**
 * Função para buscar os nomes de todos os convenios.
 *
 * @author Bruno Haick
 * @date Criação :  21/03/2013
 * @return
 * 	Array contendo todos os convenios
 *
 */
function buscaNomeConvenios() {
    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				convenio
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $convenios[] = $linha['nome'];
    }

    return $convenios;
}

/**
 * Função para buscar as contas correntes.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 * @return
 * 	Array contendo todas as contas encontrados.
 *
 */
function buscaNomeContasCorrente() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				conta_corrente
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $contas[] = $linha['nome'];
    }

    return $contas;
}

/**
 * Função para buscar as contas correntes Por Codigo.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 * @return
 * 	Array contendo todas as contas encontrados por codigo.
 *
 */
function buscaNomeContasCorrentePorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				conta_corrente
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $conta = mysqli_fetch_assoc($query);

    return $conta['nome'];
}

/**
 * Função para buscar os Tipo de Clientes do financeiro banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 * @return
 * 	Array contendo todos os Tipo Clientes Cnpj encontrados.
 *
 */
function buscaNomeTipoClientesCnpj() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				tipo_cliente
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $tipos[] = $linha;
    }

    return $tipos;
}

/**
 * Função para buscar os Clientes com CNPJ presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 * @return
 * 	Array contendo todos os Clientes Cnpj encontrados.
 *
 */
function buscaNomeClientesCnpj() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				clientes_cnpj
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $clientes[] = $linha['nome'];
    }

    return $clientes;
}

/**
 * Função para buscar o nome de um Cliente dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  06/02/2013
 *
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaNomeClientePorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				clientes_cnpj
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $cliente = mysqli_fetch_assoc($query);

    return $cliente['nome'];
}

/**
 * Função para buscar id do fornecedor dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  11/02/2013
 *
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaIdClienteCnpjPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				clientes_cnpj
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $cliente = mysqli_fetch_assoc($query);

    return $cliente['id'];
}

/**
 * @description Função para buscar os fornecedores presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  26/09/2012
 * Modificado Bruno Haick - 05/01/2014
 * 
 * @return
 * 	Array contendo todos os fornecedores encontrados ordenados por nome.
 *
 */
function buscaFornecedores() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				fornecedores
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $fornecedores[] = $linha;
    }

    return $fornecedores;
}

/**
 * Função para buscar os fornecedores presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  06/02/2013
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaNomeFornecedores() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				fornecedores
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $fornecedores[] = $linha['nome'];
    }

    return $fornecedores;
}

/**
 * Função para buscar o  nome de um fornecedor dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  06/02/2013
 *
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaNomeFornecedorPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				fornecedores
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $fornecedor = mysqli_fetch_assoc($query);

    return $fornecedor['nome'];
}

/**
 * Função para buscar o codigo de um fornecedor dado seu id.
 *
 * @author Bruno Haick
 * @date Criação :  06/02/2013
 *
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaCodFornecedorPorFornecedorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				codigo
			FROM
				fornecedores
			WHERE
				id = '$id'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $fornecedor = mysqli_fetch_assoc($query);

    return $fornecedor['codigo'];
}

/**
 * Função para buscar o  nome de um fornecedor dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  06/02/2013
 *
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaIdFornecedorPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				fornecedores
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $fornecedor = mysqli_fetch_assoc($query);

    return $fornecedor['id'];
}

/**
 * Função para buscar id do fornecedor dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  11/02/2013
 *
 * @return
 * 	Array contendo todos os fornecedores encontrados.
 *
 */
function buscaIdFornecedorPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				fornecedores
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $fornecedor = mysqli_fetch_assoc($query);

    return $fornecedor['id'];
}

/**
 * Função para buscar as empresas presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos as empresas encontradas.
 *
 */
function buscaNomeEmpresas() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				empresa
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $empresas[] = $linha['nome'];
    }

    return $empresas;
}

/**
 * Função para buscar o nome do convenio dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  21/03/2013
 *
 * @return
 * 	Array contendo todos os convenios encontrados.
 *
 */
function buscaNomeConvenioPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				convenio
			WHERE
				codigo = '$cod'
	";
//				AND
//				ativo = 1

    $query = mysqli_query(banco::$connection, $sql);

    $convenio = mysqli_fetch_assoc($query);

    return $convenio['nome'];
}

/**
 * Função para buscar o nome da empresa dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos as empresas encontradas.
 *
 */
function buscaNomeEmpresaPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				empresa
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $empresa = mysqli_fetch_assoc($query);

    return $empresa['nome'];
}

/**
 * Função para buscar o valor da taxa de imposto por convenio
 *
 * @author Bruno Haick
 * @date Criação :  21/03/2013
 * @return
 * 	Array contendo a taxa.
 *
 */
function buscaConvenioImposto($idConvenio) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				convenio_imposto.taxa
			FROM
				convenio_imposto,convenio
			WHERE
				convenio.convenio_imposto_id = convenio_imposto.id
				AND
				convenio.id = $idConvenio
	";

    $query = mysqli_query(banco::$connection, $sql);

    $convenio = mysqli_fetch_assoc($query);

    return $convenio['taxa'];
}

/**
 * Função para buscar o valor total de procedimentos de um
 * determinado convenio em um periodo.
 *
 * @author Bruno Haick
 * @date Criação :  21/03/2013
 * @return
 * 	Array contendo todos a soma de valores
 *
 */
function buscaTotalPorConvenioPorPeriodo($idConvenio, $data_inicio, $data_fim) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT 
                sum(f.valor) as 'faturado'
            FROM 
                `guia_controle` g INNER JOIN `guia_controle_has_forma_pagamento` f 
                ON g.id = f.guia_controle_id
            WHERE
                f.forma_pagamento_id = '7'
                AND
                g.convenio_id = '$idConvenio'
                AND
                g.data BETWEEN '$data_inicio' AND '$data_fim'
                AND
                g.finalizado = '1' 
            GROUP BY
                f.forma_pagamento_id
            ";
//    die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    $convenio = mysqli_fetch_assoc($query);

    return $convenio;
}

/**
 * Função para buscar o id de uma forma_pagamento dado o seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  24/04/2013
 * @return
 * 	Array contendo forma_pagamento encontrada.
 *
 */
function buscaIdFormaPagamentoPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				forma_pagamento
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $convenio = mysqli_fetch_assoc($query);

    return $convenio['id'];
}

/**
 * Função para buscar o id de um convenio dado o seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  21/03/2013
 * @return
 * 	Array contendo todos os convenios encontradas.
 *
 */
function buscaIdConvenioPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
                    id
            FROM
                    convenio
            WHERE
                    nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $convenio = mysqli_fetch_assoc($query);

    return $convenio['id'];
}

/**
 * Função para buscar o id de uma empresa dado o seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  11/02/2013
 * @return
 * 	Array contendo todos as empresas encontradas.
 *
 */
function buscaIdEmpresaPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				empresa
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $empresa = mysqli_fetch_assoc($query);

    return $empresa['id'];
}

/**
 * Função para buscar os TipoDoc presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 * @return
 * 	Array contendo todos os TipoDoc encontrados.
 *
 */
function buscaNomeTipoDoc() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				tipo_doc
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $tipo_doc[] = $linha['nome'];
    }

    return $tipo_doc;
}

/**
 * Função para buscar o nome do TipoDoc dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos os TipoDoc encontrados.
 *
 */
function buscaNomeTipoDocPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				tipo_doc
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $tipo_doc = mysqli_fetch_assoc($query);

    return $tipo_doc['nome'];
}

/**
 * Função para buscar o id do TipoDoc dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  11/02/2013
 *
 * @return
 * 	Array contendo todos os TipoDoc encontrados.
 *
 */
function buscaIdTipoDocPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				tipo_doc
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $tipo_doc = mysqli_fetch_assoc($query);

    return $tipo_doc['id'];
}

/**
 * Função para buscar os bancos Ativos presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  23/02/2013
 *
 * @return
 * 	Array contendo todos os bancos encontrados.
 *
 */
function buscaNomeBancoAtivo() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				banco
			WHERE
				status = 'Ativo'
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $bancos[] = $linha['nome'];
    }

    return $bancos;
}

/**
 * Função para buscar os bancos presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos os bancos encontrados.
 *
 */
function buscaNomeBanco() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				banco
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $bancos[] = $linha['nome'];
    }

    return $bancos;
}

/**
 * Função para buscar o nome do banco dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos os bancos encontrados.
 *
 */
function buscaNomeBancoPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				banco
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $banco = mysqli_fetch_assoc($query);

    return $banco['nome'];
}

/**
 * Função para buscar o id do banco dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  11/02/2013
 *
 * @return
 * 	Array contendo todos os bancos encontrados.
 *
 */
function buscaIdBancoPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				banco
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $banco = mysqli_fetch_assoc($query);

    return $banco['id'];
}

/**
 * Função para buscar todos os Tipos de Operacao.
 *
 * @author Bruno Haick
 * @date Criação :  20/03/2013
 *
 * @return
 * 	Array contendo todos tipos de operacao
 *
 */
function buscaTodosTipoOperacoes($tipo = '%') {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				tipo_operacao
			WHERE
				e_s LIKE '$tipo'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $tipos[] = $linha;
    }

    return $tipos;
}

/**
 * Função para buscar os Cartões de crédito
 *
 * @author Bruno Haick
 * @date Criação :  15/03/2013
 *
 * @return
 * 	Array contendo os cartẽs.
 *
 */
function buscaTodosCartoes() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				cartao_bandeiras
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $cartoes[] = $linha;
    }

    return $cartoes;
}

/**
 * Função para buscar os clientes cnpj
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 *
 * @return
 * 	Array contendo os clientes.
 *
 */
function buscaTodosClientesCnpj() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				clientes_cnpj
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $clientes[] = $linha;
    }

    return $clientes;
}

/**
 * Função para buscar todos os bancos
 *
 * @author Bruno Haick
 * @date Criação :  23/07/2013
 *
 * @return
 * 	Array contendo todos os bancos encontrados.
 *
 */
function buscaTodosBancos() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				banco
			WHERE
				status = 'Ativo'
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $bancos[] = $linha;
    }

    return $bancos;
}

/**
 * Função para buscar as midias presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  26/07/2013
 *
 * @return
 * 	Array contendo todos as midias encontradas.
 *
 */
function buscaTodasMidias() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				midias
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $midias[] = $linha;
    }

    return $midias;
}

/**
 * Função para buscar as moedas presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos as moedas encontradas.
 *
 */
function buscaTodasMoedas() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				moeda
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $moedas[] = $linha;
    }

    return $moedas;
}

/**
 * Função para buscar os nomes das moedas presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos as moedas encontradas.
 *
 */
function buscaNomeMoedas() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				moeda
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $moedas[] = $linha['nome'];
    }

    return $moedas;
}

/**
 * Função para buscar o nome da moeda dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  10/02/2013
 *
 * @return
 * 	Array contendo todos as moedas encontradas.
 *
 */
function buscaNomeMoedaPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				moeda
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $moeda = mysqli_fetch_assoc($query);

    return $moeda['nome'];
}

/**
 * Função para buscar o id da moeda dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  11/02/2013
 *
 * @return
 * 	Array contendo todos as moedas encontradas.
 *
 */
function buscaIdMoedaPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				moeda
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $moeda = mysqli_fetch_assoc($query);

    return $moeda['id'];
}

/**
 * Função para buscar os nomes dos planos de contas presentes no banco de dados.
 *
 * @author Bruno Haick
 * @date Criação :  13/02/2013
 *
 * @return
 * 	Array contendo todos as moedas encontradas.
 *
 */
function buscaNomePlanoContas() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				plano_contas
			ORDER BY
				nome
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $plano[] = $linha['nome'];
    }

    return $plano;
}

/**
 * Função para buscar o nome do plano de contas dado seu codigo.
 *
 * @author Bruno Haick
 * @date Criação :  12/02/2013
 *
 * @return
 * 	Array contendo todos os planos de contas encontradas.
 *
 */
function buscaNomePlanoContasPorCodigo($cod) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				plano_contas
			WHERE
				codigo = '$cod'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $plano = mysqli_fetch_assoc($query);

    return $plano['nome'];
}

function buscaPlanoContasPorFornecedor($fornecedor) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				plano_contas.nome
			FROM
				fornecedores,plano_contas
			WHERE
				fornecedores.nome = '$fornecedor'
				AND
				fornecedores.plano_contas_id = plano_contas.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $plano = mysqli_fetch_assoc($query);

    return $plano['nome'];
}

/**
 * Função para buscar o id do plano de contas dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  12/02/2013
 *
 * @return
 * 	Array contendo todos os planos de contas encontradas.
 *
 */
function buscaIdPlanoContasPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				plano_contas
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $plano = mysqli_fetch_assoc($query);

    return $plano['id'];
}

/**
 * Função para buscar os status do tipo duplicata.
 *
 * @author Bruno Haick
 * @date Criação :  12/02/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaTodosStatusDuplicata() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			status.*
		FROM
			status,tipo_status
		WHERE
			status.tipo_status_id = tipo_status.id
			AND
			tipo_status.nome = 'servico'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($stat = mysqli_fetch_assoc($query)) {
        $status[] = $stat;
    }

    return $status;
}

/**
 * Função para buscar Subtotal de faturas por grupo.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 *
 * @return
 * 	Array com todas as faturas encontradas
 *
 */
//function buscaFaturasPorPeriodoSubtotal($tipoClienteId, $clienteId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {
function buscaFaturasPorPeriodoSubtotal($clienteId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {

    mysqli_set_charset(banco::$connection, 'utf8');

//    if ($tipoClienteId == '00') {
//        $tipo_cliente = "fatura.tipo_cliente_id LIKE '%'";
//    } else {
//        $tipo_cliente = "fatura.tipo_cliente_id = $clienteId";
//    }

    if ($clienteId == '00') {
        $cliente = "fatura.clientes_cnpj_id LIKE '%'";
    } else {
        $cliente = "fatura.clientes_cnpj_id = $clienteId";
    }

    if ($empresaId == '00') {
        $empresa = "fatura.empresa_id LIKE '%'";
    } else {
        $empresa = "fatura.empresa_id = $empresaId";
    }

    if ($tipoDocId == '00') {
        $tipo_doc = "fatura.tipo_doc_id LIKE '%'";
    } else {
        $tipo_doc = "fatura.tipo_doc_id = $tipoDocId";
    }

    if ($moedaId == 0) {
        $moeda = "fatura.moeda_id LIKE '%'";
    } else {
        $moeda = "fatura.moeda_id = $moedaId";
    }

    if ($statusId == 0) {
        $status = "fatura.status_id LIKE '%'";
    } else {
        $status = "fatura.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "fatura.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "fatura.data_emissao";
    }

    if ($ordenado == 1) {
        $order = "clientes_cnpj.nome";
    } else if ($ordenado == 2) {
        $order = "empresa.nome";
    } else if ($ordenado == 3) {
        $order = "tipo_doc.nome";
    } else if ($ordenado == 4) {
        $order = "fatura.data_lancamento";
    } else if ($ordenado == 5) {
        $order = "fatura.data_emissao";
    } else if ($ordenado == 6) {
        $order = "moeda.nome";
    } else if ($ordenado == 7) {
        $order = "banco.nome";
    }

    $sql = "SELECT DISTINCT
				SUM(fatura_parcelas.valor) as 'total',
				$order
			FROM
				fatura INNER JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id
				LEFT JOIN clientes_cnpj ON fatura.clientes_cnpj_id = clientes_cnpj.id
				LEFT JOIN tipo_doc ON fatura.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON fatura.empresa_id = empresa.id
				LEFT JOIN moeda ON fatura.moeda_id = moeda.id
				LEFT JOIN status ON fatura.status_id = status.id
				LEFT JOIN banco ON fatura.banco_id = banco.id
				LEFT JOIN usuario ON fatura.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id
				LEFT JOIN plano_contas ON fatura.plano_contas_id = plano_contas.id
			WHERE
				$cliente
				AND
				$empresa
				AND
				$tipo_doc
				AND
				$moeda
				AND
				$status
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
			GROUP BY
				$order
			ORDER BY
				$order
	";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($fatura = mysqli_fetch_assoc($query)) {
        $faturas[] = $fatura;
    }

    return $faturas;
}

/**
 * Função para buscar Faturas.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 *
 * @return
 * 	Array com todas as faturas encontradas
 *
 */
//function buscaFaturasPorPeriodo($tipoClienteId, $clienteId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {
function buscaFaturasPorPeriodo($clienteId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {

    mysqli_set_charset(banco::$connection, 'utf8');

//    if ($tipoClienteId == '00') {
//        $tipo_cliente = "fatura.tipo_cliente_id LIKE '%'";
//    } else {
//        $tipo_cliente = "fatura.tipo_cliente_id = $clienteId";
//    }

    if ($clienteId == '00') {
        $cliente = "fatura.clientes_cnpj_id LIKE '%'";
    } else {
        $cliente = "fatura.clientes_cnpj_id = $clienteId";
    }

    if ($empresaId == '00') {
        $empresa = "fatura.empresa_id LIKE '%'";
    } else {
        $empresa = "fatura.empresa_id = $empresaId";
    }

    if ($tipoDocId == '00') {
        $tipo_doc = "fatura.tipo_doc_id LIKE '%'";
    } else {
        $tipo_doc = "fatura.tipo_doc_id = $tipoDocId";
    }

    if ($moedaId == 0) {
        $moeda = "fatura.moeda_id LIKE '%'";
    } else {
        $moeda = "fatura.moeda_id = $moedaId";
    }

    if ($statusId == 0) {
        $status = "fatura.status_id LIKE '%'";
    } else {
        $status = "fatura.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "fatura.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "fatura.data_emissao";
    }

    if ($ordenado == 1) {
        $order = "clientes_cnpj.nome";
    } else if ($ordenado == 2) {
        $order = "empresa.nome";
    } else if ($ordenado == 3) {
        $order = "tipo_doc.nome";
    } else if ($ordenado == 4) {
        $order = "fatura.data_lancamento";
    } else if ($ordenado == 5) {
        $order = "fatura.data_emissao";
    } else if ($ordenado == 6) {
        $order = "moeda.nome";
    } else if ($ordenado == 7) {
        $order = "banco.nome";
    }

    $sql = "SELECT DISTINCT
				fatura.id,
				fatura.id as fatura_id,
				fatura.numero_fatura as 'numero',
				DATE_FORMAT(fatura.data_lancamento, '%d/%m/%Y') as data_lancamento,
				DATE_FORMAT(fatura.data_emissao, '%d/%m/%Y') as data_emissao,
				fatura.observacao,
				fatura.numero_fatura,
				clientes_cnpj.nome as nome_cliente,
				empresa.nome as nome_empresa,
				moeda.nome as nome_moeda,
				banco.nome as nome_banco,
				plano_contas.nome as nome_plano_contas,
				tipo_doc.nome as nome_tipo_doc,
				CASE status.id
					WHEN '18' then 'A'
					WHEN '19' then 'P'
					WHEN '20' then 'B'
				END as nome_status,
				pessoa.nome as nome_pessoa,
				pessoa.sobrenome as sobrenome_pessoa,
				SUM(fatura_parcelas.valor) as 'total'
			FROM
				fatura INNER JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id
				LEFT JOIN clientes_cnpj ON fatura.clientes_cnpj_id = clientes_cnpj.id
				LEFT JOIN tipo_doc ON fatura.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON fatura.empresa_id = empresa.id
				LEFT JOIN moeda ON fatura.moeda_id = moeda.id
				LEFT JOIN status ON fatura.status_id = status.id
				LEFT JOIN banco ON fatura.banco_id = banco.id
				LEFT JOIN usuario ON fatura.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id
				LEFT JOIN plano_contas ON fatura.plano_contas_id = plano_contas.id
			WHERE
				$cliente
				AND
				$empresa
				AND
				$tipo_doc
				AND
				$moeda
				AND
				$status
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
			GROUP BY
				fatura.id, $order
			ORDER BY
				$order
	";
//die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($fatura = mysqli_fetch_assoc($query)) {
        $faturas[] = $fatura;
    }

    return $faturas;
}

/**
 * Função para buscar um subtotal por agrupamento de acordo com a opção
 * escolhida, ou seja, se agrupar por fornecedor, haverá um sibtotal
 * para este grupo de registros.
 *
 * @author Bruno Haick
 * @date Criação :  12/02/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaDuplicatasPorPeriodoSubtotalPorGrupo($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if ($fornecedorId == '00') {
        $fornecedor = "duplicata.fornecedores_id LIKE '%'";
    } else {
        $fornecedor = "duplicata.fornecedores_id = $fornecedorId";
    }

    if ($empresaId == '00') {
        $empresa = "duplicata.empresa_id LIKE '%'";
    } else {
        $empresa = "duplicata.empresa_id = $empresaId";
    }

    if ($tipoDocId == '00') {
        $tipo_doc = "duplicata.tipo_doc_id LIKE '%'";
    } else {
        $tipo_doc = "duplicata.tipo_doc_id = $tipoDocId";
    }

    if ($moedaId == 0) {
        $moeda = "duplicata.moeda_id LIKE '%'";
    } else {
        $moeda = "duplicata.moeda_id = $moedaId";
    }

    if ($statusId == 0) {
        $status = "duplicata.status_id LIKE '%'";
    } else {
        $status = "duplicata.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "duplicata.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "duplicata.data_emissao";
    }

    if ($ordenado == 1) {
        $order = "fornecedores.nome";
    } else if ($ordenado == 2) {
        $order = "empresa.nome";
    } else if ($ordenado == 3) {
        $order = "duplicata.data_lancamento";
    } else if ($ordenado == 4) {
        $order = "duplicata.data_emissao";
    } else if ($ordenado == 5) {
        $order = "moeda.nome";
    } else if ($ordenado == 6) {
        $order = "banco.nome";
    } else if ($ordenado == 7) {
        $order = "duplicata_parcelas.data_baixa";
    } else if ($ordenado == 8) {
        $order = "status.nome";
    }

    $sql = "SELECT DISTINCT
				SUM(duplicata_parcelas.valor) as total,
				$order
			FROM
				duplicata 
				LEFT JOIN duplicata_parcelas ON duplicata.id = duplicata_parcelas.duplicata_id 
				LEFT JOIN fornecedores ON duplicata.fornecedores_id = fornecedores.id
				LEFT JOIN tipo_doc ON duplicata.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON duplicata.empresa_id = empresa.id
				LEFT JOIN moeda ON duplicata.moeda_id = moeda.id
				LEFT JOIN status ON duplicata.status_id = status.id
				LEFT JOIN banco ON duplicata.banco_id = banco.id
				LEFT JOIN usuario ON duplicata.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id
				LEFT JOIN plano_contas ON duplicata.plano_contas_id = plano_contas.id
			WHERE
				$fornecedor
				AND $empresa
				AND $tipo_doc
				AND $moeda
				AND $status
				AND $data BETWEEN '$data_inicio' AND '$data_fim'
			GROUP BY 
				$order
			ORDER BY 
				$order
		";

    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return $duplicatas;
}

/**
 * Função para buscar duplicatas.
 *
 * @author Bruno Haick
 * @date Criação :  12/02/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaDuplicatasPorPeriodo($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if ($fornecedorId == '00') {
        $fornecedor = "duplicata.fornecedores_id LIKE '%'";
    } else {
        $fornecedor = "duplicata.fornecedores_id = $fornecedorId";
    }

    if ($empresaId == '00') {
        $empresa = "duplicata.empresa_id LIKE '%'";
    } else {
        $empresa = "duplicata.empresa_id = $empresaId";
    }

    if ($tipoDocId == '00') {
        $tipo_doc = "duplicata.tipo_doc_id LIKE '%'";
    } else {
        $tipo_doc = "duplicata.tipo_doc_id = $tipoDocId";
    }

    if ($moedaId == 0) {
        $moeda = "duplicata.moeda_id LIKE '%'";
    } else {
        $moeda = "duplicata.moeda_id = $moedaId";
    }

    if ($statusId == 0) {
        $status = "duplicata.status_id LIKE '%'";
    } else {
        $status = "duplicata.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "duplicata.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "duplicata.data_emissao";
    }

    if ($ordenado == 1) {
        $order = "fornecedores.nome";
    } else if ($ordenado == 2) {
        $order = "empresa.nome";
    } else if ($ordenado == 3) {
        $order = "duplicata.data_lancamento";
    } else if ($ordenado == 4) {
        $order = "duplicata.data_emissao";
    } else if ($ordenado == 5) {
        $order = "moeda.nome";
    } else if ($ordenado == 6) {
        $order = "banco.nome";
    } else if ($ordenado == 7) {
        $order = "duplicata_parcelas.data_baixa";
    } else if ($ordenado == 8) {
        $order = "status.nome";
    }

    $sql = "SELECT DISTINCT
				duplicata.id,
				duplicata.numero,
				DATE_FORMAT(duplicata.data_lancamento, '%d/%m/%Y') as data_lancamento,
				DATE_FORMAT(duplicata.data_emissao, '%d/%m/%Y') as data_emissao,
				duplicata.observacao,
				fornecedores.nome as nome_fornecedor,
				empresa.nome as nome_empresa,
				moeda.nome as nome_moeda,
				banco.nome as nome_banco,
				plano_contas.nome as nome_plano_contas,
				tipo_doc.nome as nome_tipo_doc,
				CASE status.id
					WHEN '18' then 'A'
					WHEN '19' then 'P'
					WHEN '20' then 'B'
				END as nome_status,
				p.nome as nome_pessoa,
				p.sobrenome as sobrenome_pessoa ,
				DATE_FORMAT(duplicata_parcelas.data_baixa, '%d/%m/%Y') as data_baixa,
				usuario_baixa.nome as 'usuario_baixa_parcela',
				SUM(duplicata_parcelas.valor) as total
			FROM
				duplicata 
				LEFT JOIN duplicata_parcelas ON duplicata.id = duplicata_parcelas.duplicata_id 
				LEFT JOIN fornecedores ON duplicata.fornecedores_id = fornecedores.id
				LEFT JOIN tipo_doc ON duplicata.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON duplicata.empresa_id = empresa.id
				LEFT JOIN moeda ON duplicata.moeda_id = moeda.id
				LEFT JOIN status ON duplicata.status_id = status.id
				LEFT JOIN banco ON duplicata.banco_id = banco.id
				LEFT JOIN usuario ON duplicata.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa as p ON usuario.usuario_id = p.id
				LEFT JOIN pessoa as usuario_baixa ON usuario_baixa.id = duplicata_parcelas.usuario_baixou_id
				LEFT JOIN plano_contas ON duplicata.plano_contas_id = plano_contas.id
			WHERE
				$fornecedor
				AND $empresa
				AND $tipo_doc
				AND $moeda
				AND $status
				AND $data BETWEEN '$data_inicio' AND '$data_fim'
			GROUP BY
				duplicata.id,$order
			ORDER BY 
				$order
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return $duplicatas;
}

/**
 * Função para buscar o id da Fatura a partir do id da parcela_fatura
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	id 
 *
 */
function buscaFaturaPorParcela($idParcela) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				fatura.id
			FROM
				fatura,fatura_parcelas
			WHERE
				fatura.id = fatura_parcelas.fatura_id
				AND
				fatura_parcelas.id = $idParcela
	";

    $query = mysqli_query(banco::$connection, $sql);

    $fatura = mysqli_fetch_assoc($query);

    return $fatura['id'];
}

/**
 * Função para buscar o id da duplica a partir do id da parcela_duplicata
 *
 * @author Bruno Haick
 * @date Criação :  04/03/2013
 *
 * @return
 * 	id 
 *
 */
function buscaDuplicataComprovante($idParcela) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				duplicata_parcelas.valor,
				pessoa.nome as nome_pessoa,
				banco.nome as nome_banco,
				duplicata_parcelas.numero_cheque,
				duplicata_parcelas.historico,
				empresa.nome as nome_empresa,
				fornecedores.nome as nome_fornecedor
			FROM
				duplicata,duplicata_parcelas,usuario,pessoa,banco,fornecedores,empresa
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata_parcelas.id = $idParcela
				AND
				duplicata_parcelas.usuario_baixou_id = usuario.usuario_id
				AND
				usuario.usuario_id = pessoa.id
				AND
				duplicata.banco_id = banco.id
				AND
				duplicata.empresa_id = empresa.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $duplicata = mysqli_fetch_assoc($query);

    return $duplicata;
}

/**
 * Função para buscar o id da duplica a partir do id da parcela_duplicata
 *
 * @author Bruno Haick
 * @date Criação :  04/03/2013
 *
 * @return
 * 	id 
 *
 */
function buscaDuplicataPorParcela($idParcela) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				duplicata.id
			FROM
				duplicata,duplicata_parcelas
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata_parcelas.id = $idParcela
	";

    $query = mysqli_query(banco::$connection, $sql);

    $duplicata = mysqli_fetch_assoc($query);

    return $duplicata['id'];
}

/**
 * Função para verificar qtd de parcelas de uma fatura
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	quantidade de parcelas de Fatura 
 *
 */
function buscaQtdParcelasFatura($idFatura) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				fatura_parcelas.*
			FROM
				fatura,fatura_parcelas
			WHERE
				fatura.id = fatura_parcelas.fatura_id
				AND
				fatura.id = $idFatura
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($fatura = mysqli_fetch_assoc($query)) {
        $faturas[] = $fatura;
    }

    return count($faturas);
}

/**
 * Função para verificar se existe alguma parcela de Fatura em aberto.
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	quantidade de parcelas de Fatura em aberto
 *
 */
function buscaQtdParcelasFaturaAberto($idFatura) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				fatura_parcelas.*
			FROM
				fatura,fatura_parcelas,status
			WHERE
				fatura.id = fatura_parcelas.fatura_id
				AND
				fatura.id = $idFatura
				AND
				fatura_parcelas.status_id = status.id
				AND
				status.nome = 'Em Aberto'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($fatura = mysqli_fetch_assoc($query)) {
        $faturas[] = $fatura;
    }

    return count($faturas);
}

/**
 * Função para verificar se existe alguma parcelas.
 *
 * @author Bruno Haick
 * @date Criação :  13/03/2013
 *
 * @return
 * 	quantidade de parcelas
 *
 */
function buscaQtdParcelas($idDuplicata) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				duplicata_parcelas.*
			FROM
				duplicata,duplicata_parcelas
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata.id = $idDuplicata
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return count($duplicatas);
}

/**
 * Função para verificar se existe alguma parcela em aberto.
 *
 * @author Bruno Haick
 * @date Criação :  04/03/2013
 *
 * @return
 * 	quantidade de parcelas em aberto
 *
 */
function buscaQtdParcelasAberto($idDuplicata) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				duplicata_parcelas.*
			FROM
				duplicata,duplicata_parcelas,status
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata.id = $idDuplicata
				AND
				duplicata_parcelas.status_id = status.id
				AND
				status.nome = 'Em Aberto'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return count($duplicatas);
}

/**
 * Função para buscar o valor total de lancamentos até um dia para o extrato financeiro
 *
 * @author Bruno Haick
 * @date Criação :  20/03/2013
 *
 * @return
 * 	valor total de parcelas de lancamentos
 *
 */
function extratoFinanceiroLancamentoTotal($conta_corrente_id, $data, $e_s) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				SUM(lancamentos.valor) as total
			FROM
				lancamentos,tipo_operacao
			WHERE
				lancamentos.conta_corrente_id = $conta_corrente_id
				AND
				data < '$data'
				AND
				lancamentos.tipo_operacao_id = tipo_operacao.id
				AND
				tipo_operacao.e_s = '$e_s'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $lancamento = mysqli_fetch_assoc($query);

    return $lancamento['total'];
}

/**
 * Função para buscar as lancamentos para um dia para o extrato financeiro
 *
 * @author Bruno Haick
 * @date Criação :  20/03/2013
 *
 * @return
 * 	lancamentos
 *
 */
function extratoFinanceiroLancamentoPorDia($conta_corrente_id, $data, $e_s) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				lancamentos.valor,
				tipo_operacao.nome as nome_operacao
			FROM
				lancamentos,tipo_operacao
			WHERE
				lancamentos.conta_corrente_id = $conta_corrente_id
				AND
				data = '$data'
				AND
				lancamentos.tipo_operacao_id = tipo_operacao.id
				AND
				tipo_operacao.e_s = '$e_s'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($lancamento = mysqli_fetch_assoc($query)) {
        $lancamentos[] = $lancamento;
    }

    return $lancamentos;
}

/**
 * Função para buscar as duplicatas para um dia para o extrato financeiro
 *
 * @author Bruno Haick
 * @date Criação :  17/03/2013
 *
 * @return
 * 	parcelas de Duplicatas
 *
 */
function extratoFinanceiroDuplicataPorDia($conta_corrente_id, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				duplicata_parcelas.valor,
				duplicata_parcelas.historico
			FROM
				duplicata,duplicata_parcelas,status
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata_parcelas.conta_corrente_id = $conta_corrente_id
				AND
				duplicata_parcelas.data_baixa = '$data'
				AND
				duplicata_parcelas.status_id = status.id
				AND
				status.nome = 'Baixado'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return $duplicatas;
}

/**
 * Função para buscar o valor total de duplicatas até um dia para o extrato financeiro
 *
 * @author Bruno Haick
 * @date Criação :  17/03/2013
 *
 * @return
 * 	valor total de parcelas de Duplicatas
 * === ERRADO ===
 */
function extratoConvenioFaturaTotal($conta_corrente_id, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				SUM(duplicata_parcelas.valor) as total
			FROM
				duplicata,duplicata_parcelas,status
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata_parcelas.conta_corrente_id = $conta_corrente_id
				AND
				duplicata_parcelas.data_baixa < '$data'
				AND
				duplicata_parcelas.status_id = status.id
				AND
				status.nome = 'Baixado'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $duplicata = mysqli_fetch_assoc($query);

    return $duplicata['total'];
}

/**
 * Função para buscar o valor total de duplicatas até um dia para o extrato financeiro
 *
 * @author Bruno Haick
 * @date Criação :  17/03/2013
 *
 * @return
 * 	valor total de parcelas de Duplicatas
 *
 */
function extratoFinanceiroDuplicataTotal($conta_corrente_id, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				SUM(duplicata_parcelas.valor) as total
			FROM
				duplicata,duplicata_parcelas,status
			WHERE
				duplicata.id = duplicata_parcelas.duplicata_id
				AND
				duplicata_parcelas.conta_corrente_id = $conta_corrente_id
				AND
				duplicata_parcelas.data_baixa < '$data'
				AND
				duplicata_parcelas.status_id = status.id
				AND
				status.nome = 'Baixado'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $duplicata = mysqli_fetch_assoc($query);

    return $duplicata['total'];
}

/**
 * Função para buscar as faturas para um dia para o extrato financeiro
 *
 * @author Bruno Haick
 * @date Criação :  16/03/2013
 *
 * @return
 * 	parcelas de Faturas
 *
 */
function extratoFinanceiroFaturaPorDia($conta_corrente_id, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				fatura_parcelas.valor,
				cartao_bandeiras.nome as nome_cartao
			FROM
				fatura,fatura_parcelas,status,cartao_bandeiras
			WHERE
				fatura.id = fatura_parcelas.fatura_id
				AND
				fatura.cartao_bandeiras_id = cartao_bandeiras.id
				AND
				fatura_parcelas.conta_corrente_id = $conta_corrente_id
				AND
				fatura_parcelas.data_baixa = '$data'
				AND
				fatura_parcelas.status_id = status.id
				AND
				status.nome = 'Baixado'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($fatura = mysqli_fetch_assoc($query)) {
        $faturas[] = $fatura;
    }

    return $faturas;
}

/**
 * Função para buscar o valor total de parcelas de faturas
 *
 * @author Bruno Haick e Luiz Cortinhsa
 * @date Criação :  17/03/2013
 * @date Modificaç�o: 11/01/2014
 *
 * @return
 * 	valor total
 *
 */
function extratoFinanceiroAnterior($conta_corrente_id, $datainicio) {

    $sql = "SELECT * FROM (SELECT * FROM
			(SELECT 
					duplicata_parcelas.data_baixa as 'data',
					duplicata.id  as 'documento',
             		fornecedores.nome as 'fornecedor',
             		SUM(duplicata_parcelas.valor) as 'valor',
              		'd' as tipo,
             		'S' as 'I/O'
             FROM `duplicata_parcelas` 
					INNER JOIN duplicata ON duplicata.id = duplicata_parcelas.duplicata_id 
					INNER JOIN fornecedores ON duplicata.fornecedores_id = fornecedores.id 
             		INNER JOIN conta_corrente ON conta_corrente.banco_id = duplicata.banco_id
			WHERE 
					conta_corrente.id = '$conta_corrente_id'
					AND duplicata.status_id = '20'
					AND duplicata_parcelas.data_baixa BETWEEN '1970-01-01' AND '$datainicio'
			GROUP BY duplicata_parcelas.data_baixa,fornecedores.id
            ) as duplicata 
				UNION ALL 

			SELECT 
             		convenio.nome as 'fornecedor',
					fatura_convenio.id as 'documento',
             		fatura_convenio.data_baixa as 'data',
             		fatura_convenio.valor_pago as 'valor',
					'c' as tipo,
					'E' as 'I/O'
             FROM fatura_convenio 
             		INNER JOIN empresa ON empresa.id = fatura_convenio.convenio_id
             		INNER JOIN convenio ON convenio.id = fatura_convenio.convenio_id
             		LEFT JOIN tipo_operacao ON tipo_operacao.id = fatura_convenio.tipo_operacao_id
             		LEFT JOIN conta_corrente ON fatura_convenio.conta_corrente_id = conta_corrente.id AND conta_corrente.banco_id = '1'
             WHERE 
             		fatura_convenio.status_id = '20'
					AND fatura_convenio.conta_corrente_id = '$conta_corrente_id'
             		AND fatura_convenio.data_baixa between '1970-01-01' AND '$datainicio'
             		AND fatura_convenio.valor_pago is not null
             GROUP BY fatura_convenio.data_baixa,convenio.nome
				
				UNION ALL
				
				SELECT SUM(fatura_parcelas.valor) as 'valor',
						fatura.id as 'documento',
						clientes_cnpj.nome as 'fornecedor',
						fatura_parcelas.data_baixa as 'data',
						'f' as 'tipo',
						'E' as 'I/O'
				FROM `fatura` 
						INNER JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id 
						INNER JOIN clientes_cnpj ON clientes_cnpj.id = fatura.clientes_cnpj_id
						INNER JOIN conta_corrente ON conta_corrente.banco_id = fatura_parcelas.conta_corrente_id 
				WHERE 
						fatura_parcelas.status_id = '20'
						AND conta_corrente.id = '$conta_corrente_id'
						AND fatura_parcelas.data_baixa BETWEEN '1970-01-01' AND '$datainicio'
				GROUP BY fatura.id,fatura_parcelas.data_baixa
			UNION ALL
				SELECT 
				l.data as 'data',
				l.id as 'documento',
				t.nome as 'fornecedor',
				sum(l.valor) as 'valor',
				'l' as 'tipo',
				t.e_s as 'I/O'
			FROM `lancamentos` l
				INNER JOIN tipo_operacao t ON l.tipo_operacao_id = t.id
				INNER JOIN conta_corrente c ON c.id = l.conta_corrente_id 
			WHERE 
				c.id = '$conta_corrente_id'
				AND l.data between '1970-01-01' AND '$datainicio'
			GROUP BY l.data) a order by a.data ASC
	";
//	die($sql);
    $query = database::query($sql);
    $fatura = database::fetchAll();
    $saldo_anterior = 0;
    foreach ($fatura as $conta) {
        if ($conta['I/O'] === "E") {
            $saldo_anterior = $saldo_anterior + $conta['valor'];
        } else {
            $saldo_anterior = $saldo_anterior - $conta['valor'];
        }
    }
    return $saldo_anterior;
}

/**
 * Função para buscar o valor total de parcelas de faturas
 *
 * @author Bruno Haick e Luiz Cortinhsa
 * @date Criação :  17/03/2013
 * @date Modificaç�o: 11/01/2014
 *
 * @return
 * 	valor total
 *
 */
function extratoFinanceiroTudo($conta_corrente_id, $datainicio, $datafim) {

    $sql = "SELECT * FROM (SELECT * FROM
			(SELECT 
					DATE_FORMAT(duplicata_parcelas.data_baixa,'%d-%m-%Y') as 'data',
					duplicata.id  as 'documento',
             		fornecedores.nome as 'fornecedor',
             		SUM(duplicata_parcelas.valor) as 'valor',
              		'd' as tipo,
             		'S' as 'I/O'
             FROM `duplicata_parcelas` 
					INNER JOIN duplicata ON duplicata.id = duplicata_parcelas.duplicata_id 
					INNER JOIN fornecedores ON duplicata.fornecedores_id = fornecedores.id 
             		INNER JOIN conta_corrente ON conta_corrente.banco_id = duplicata.banco_id
			WHERE 
					conta_corrente.id = '$conta_corrente_id'
					AND duplicata.status_id = '20'
					AND duplicata_parcelas.data_baixa BETWEEN '$datainicio' AND '$datafim'
			GROUP BY fornecedores.id
            ) as duplicata 
				UNION ALL 

			SELECT 
             		convenio.nome as 'fornecedor',
					fatura_convenio.id as 'documento',
             		DATE_FORMAT(fatura_convenio.data_baixa ,'%d-%m-%Y') as 'data',
             		fatura_convenio.valor_pago as 'valor',
					'c' as tipo,
					'E' as 'I/O'
             FROM fatura_convenio 
             		INNER JOIN empresa ON empresa.id = fatura_convenio.convenio_id
             		INNER JOIN convenio ON convenio.id = fatura_convenio.convenio_id
             		LEFT JOIN tipo_operacao ON tipo_operacao.id = fatura_convenio.tipo_operacao_id
             		LEFT JOIN conta_corrente ON fatura_convenio.conta_corrente_id = conta_corrente.id AND conta_corrente.banco_id = '1'
             WHERE 
             		fatura_convenio.status_id = '20'
					AND fatura_convenio.conta_corrente_id = '$conta_corrente_id'
             		AND fatura_convenio.data_baixa between '$datainicio' AND '$datafim'
             		AND fatura_convenio.valor_pago is not null
             GROUP BY convenio.nome
				
				UNION ALL
				
				SELECT DATE_FORMAT(fatura_parcelas.data_baixa,'%d-%m-%Y') as 'data',
               			fatura.id as 'documento',
               			clientes_cnpj.nome as 'fornecedor',
               			SUM(fatura_parcelas.valor) as 'valor',
						'f' as 'tipo',
						'E' as 'I/O'
				FROM `fatura` 
						INNER JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id 
						INNER JOIN clientes_cnpj ON clientes_cnpj.id = fatura.clientes_cnpj_id
						INNER JOIN conta_corrente ON conta_corrente.id = fatura_parcelas.conta_corrente_id 
				WHERE 
						fatura_parcelas.status_id = '20'
						AND conta_corrente.id = '$conta_corrente_id'
						AND fatura_parcelas.data_baixa BETWEEN '$datainicio' AND '$datafim'
				GROUP BY fatura.id
			UNION ALL
				SELECT 
				DATE_FORMAT(l.data ,'%d-%m-%Y') as 'data',
				l.id as 'documento',
				t.nome as 'fornecedor',
				l.valor as 'valor',
				'l' as 'tipo',
				t.e_s as 'I/O'
			FROM `lancamentos` l
				INNER JOIN tipo_operacao t ON l.tipo_operacao_id = t.id
				INNER JOIN conta_corrente c ON c.id = l.conta_corrente_id 
			WHERE 
				c.id = '$conta_corrente_id'
				AND l.data between '$datainicio' AND '$datafim'
			) a order by a.data ASC
	";
//	die($sql);
    Database::query($sql);

    $fatura = Database::fetchAll();

    return $fatura;
}

/**
 * Função para buscar duplicatas para baixa.
 *
 * @author Luiz Cortinhas
 * @date Criação :  12/01/2014
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaDuplicatasBaixaCRPorPeriodoTotal($clienteId, $empresaId, $bancoId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado, $cartao, $numero_fatura) {
    mysqli_set_charset(banco::$connection, 'utf8');

    if ($numero_fatura !== '0' && $numero_fatura > 0) {
        $fatura = ' AND fatura.id = \'' . $numero_fatura . '\'';
    }
    if ($clienteId == '00') {
        $cliente = "fatura.clientes_cnpj_id LIKE '%'";
    } else {
        $cliente = "fatura.clientes_cnpj_id = $clienteId";
    }
    if ($empresaId == '00') {
        $empresa = "fatura.empresa_id LIKE '%'";
    } else {
        $empresa = "fatura.empresa_id = $empresaId";
    }
    if ($bancoId == '00') {
        $banco = "fatura.banco_id LIKE '%'";
    } else {
        $banco = "fatura.banco_id = $bancoId";
    }
    if ($moedaId == 0) {
        $moeda = "fatura.moeda_id LIKE '%'";
    } else {
        $moeda = "fatura.moeda_id = $moedaId";
    }
    if ($statusId == 0) {
        $status = "fatura_parcelas.status_id LIKE '%'";
    } else {
        $status = "fatura_parcelas.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "fatura.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "fatura_parcelas.data_vencimento";
    } else if ($selecionado == 3) {
        $data = "fatura.data_emissao";
    } else if ($selecionado == 4) {
        $data = "fatura_parcelas.data_baixa";
    }

    if ($ordenado == 1) {
        $order = "CASE status.id
					WHEN '18' then 'A'
					WHEN '19' then 'P'
					WHEN '20' then 'B'
				END as nome
		";
    } else if ($ordenado == 2) {
        $order = "clientes_cnpj.nome";
    } else if ($ordenado == 3) {
        $order = "fatura.data_emissao";
    } else if ($ordenado == 4) {
        $order = "fatura_parcelas.data_vencimento";
    } else if ($ordenado == 5) {
        $order = "moeda.nome";
    } else if ($ordenado == 6) {
        $order = "banco.nome";
    } else if ($ordenado == 7) {
        $order = "tipo_doc.nome";
    }

    $count = count($cartao);
    if ($count > 0) {
        $bandeira = ",cartao_bandeiras";
        $cartoes = "";
        $cartoes .= " AND (fatura.cartao_bandeiras_id = '$cartao[0]'";

        if ($count > 1) {
            for ($i = 1; $i < $count; $i++) {
                $cartoes .= ' OR fatura.cartao_bandeiras_id =\'' . $cartao[$i] . '\'';
            }
        }

        $cartoes .= ")";
    }

    $sql = "SELECT DISTINCT
				SUM(fatura_parcelas.valor) as 'total_parcela',
				SUM(fatura_parcelas.juros) as 'total_juros',
				SUM(fatura_parcelas.multa) as 'total_multa',
				SUM(fatura_parcelas.desconto) as 'total_desconto',
				SUM(fatura_parcelas.valor_pago) as 'total_pago',
				SUM(fatura_parcelas.valor_a_pagar) as 'total_a_pagar',
				$order
			FROM				
				fatura
				LEFT JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id
				LEFT JOIN conta_corrente ON fatura_parcelas.conta_corrente_id = conta_corrente.id
				LEFT JOIN clientes_cnpj ON	fatura.clientes_cnpj_id = clientes_cnpj.id
				LEFT JOIN tipo_doc ON fatura.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON fatura.empresa_id = empresa.id
				LEFT JOIN moeda ON fatura.moeda_id = moeda.id
				LEFT JOIN status ON fatura_parcelas.status_id = status.id
				LEFT JOIN banco ON fatura.banco_id = banco.id
				LEFT JOIN usuario ON fatura.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id
				LEFT JOIN plano_contas ON fatura.plano_contas_id = plano_contas.id
				LEFT JOIN tipo_cliente ON fatura.tipo_cliente_id = tipo_cliente.id
				LEFT JOIN cartao_bandeiras ON fatura.cartao_bandeiras_id = cartao_bandeiras.id
			WHERE
				$cliente
				AND
				$empresa
				AND
				$banco
				AND
				$moeda
				AND
				$status
				$cartoes
				$fatura
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
	";
    if ($ordenado != 1) {
        $sql .= "GROUP BY
				$order
			ORDER BY
				$order
	";
    } else {
        $order = "status.nome";
        $sql .= "GROUP BY
			$order
		ORDER BY
			$order
	";
    }
//   die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }
    return $duplicatas;
}

/**
 * Função para buscar duplicatas para baixa.
 *
 * @author Bruno Haick
 * @date Criação :  17/02/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaDuplicatasBaixaCRPorPeriodo($clienteId, $empresaId, $bancoId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado, $cartao, $numero_fatura) {
    mysqli_set_charset(banco::$connection, 'utf8');

    if ($numero_fatura !== '0' && $numero_fatura > 0) {
        $fatura = ' AND fatura.id = \'' . $numero_fatura . '\'';
    }
    if ($clienteId == '00') {
        $cliente = "fatura.clientes_cnpj_id LIKE '%'";
    } else {
        $cliente = "fatura.clientes_cnpj_id = $clienteId";
    }
    if ($empresaId == '00') {
        $empresa = "fatura.empresa_id LIKE '%'";
    } else {
        $empresa = "fatura.empresa_id = $empresaId";
    }
    if ($bancoId == '00') {
        $banco = "fatura.banco_id LIKE '%'";
    } else {
        $banco = "fatura.banco_id = $bancoId";
    }
    if ($moedaId == 0) {
        $moeda = "fatura.moeda_id LIKE '%'";
    } else {
        $moeda = "fatura.moeda_id = $moedaId";
    }
    if ($statusId == 0) {
        $status = "fatura_parcelas.status_id LIKE '%'";
    } else {
        $status = "fatura_parcelas.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "fatura.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "fatura_parcelas.data_vencimento";
    } else if ($selecionado == 3) {
        $data = "fatura.data_emissao";
    } else if ($selecionado == 4) {
        $data = "fatura_parcelas.data_baixa";
    }

    if ($ordenado == 1) {
        $order = "status.nome";
    } else if ($ordenado == 2) {
        $order = "clientes_cnpj.nome";
    } else if ($ordenado == 3) {
        $order = "fatura.data_emissao";
    } else if ($ordenado == 4) {
        $order = "fatura_parcelas.data_vencimento";
    } else if ($ordenado == 5) {
        $order = "moeda.nome";
    } else if ($ordenado == 6) {
        $order = "banco.nome";
    } else if ($ordenado == 7) {
        $order = "tipo_doc.nome";
    }

    $count = count($cartao);
    if ($count > 0) {
        $bandeira = ",cartao_bandeiras";
        $cartoes = "";
        $cartoes .= " AND (fatura.cartao_bandeiras_id = '$cartao[0]'";

        if ($count > 1) {
            for ($i = 1; $i < $count; $i++) {
                $cartoes .= ' OR fatura.cartao_bandeiras_id =\'' . $cartao[$i] . '\'';
            }
        }

        $cartoes .= ")";
    }

    $sql = "SELECT DISTINCT
				fatura.id,
				DATE_FORMAT(fatura.data_lancamento, '%d/%m/%Y') as data_lancamento,
				DATE_FORMAT(fatura.data_emissao, '%d/%m/%Y') as data_emissao,
				DATE_FORMAT(fatura_parcelas.data_vencimento, '%d/%m/%Y') as data_vencimento,
				DATE_FORMAT(fatura_parcelas.data_baixa, '%d/%m/%Y') as data_baixa,
				DATEDIFF(CURDATE(), fatura_parcelas.data_vencimento) as dias_atraso,
				fatura_parcelas.valor as 'valor_parcela',
				fatura_parcelas.juros as 'juros',
				fatura_parcelas.multa as 'multa',
				fatura_parcelas.desconto as 'desconto',
				fatura_parcelas.valor_pago,
				fatura_parcelas.valor_a_pagar,
				CASE status.id
					WHEN '18' then 'A'
					WHEN '19' then 'P'
					WHEN '20' then 'B'
				END as nome_status,
				fatura.observacao,
				fatura.numero_fatura,
				fatura.taxa,
				fatura_parcelas.id as id_parcela,
				fatura_parcelas.numero as numero_parcela,
				clientes_cnpj.nome as nome_cliente,
				tipo_cliente.nome as nome_tipo_cliente,
				empresa.nome as nome_empresa,
				moeda.nome as nome_moeda,
				banco.nome as nome_banco,
				plano_contas.nome as nome_plano_contas,
				tipo_doc.nome as nome_tipo_doc,
				pessoa.nome as nome_pessoa,
				conta_corrente.nome
			FROM				
				fatura
				LEFT JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id
				LEFT JOIN conta_corrente ON fatura_parcelas.conta_corrente_id = conta_corrente.id
				LEFT JOIN clientes_cnpj ON	fatura.clientes_cnpj_id = clientes_cnpj.id
				LEFT JOIN tipo_doc ON fatura.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON fatura.empresa_id = empresa.id
				LEFT JOIN moeda ON fatura.moeda_id = moeda.id
				LEFT JOIN status ON fatura_parcelas.status_id = status.id
				LEFT JOIN banco ON fatura.banco_id = banco.id
				LEFT JOIN usuario ON fatura.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id
				LEFT JOIN plano_contas ON fatura.plano_contas_id = plano_contas.id
				LEFT JOIN tipo_cliente ON fatura.tipo_cliente_id = tipo_cliente.id
				LEFT JOIN cartao_bandeiras ON fatura.cartao_bandeiras_id = cartao_bandeiras.id
			WHERE
				$cliente
				AND
				$empresa
				AND
				$banco
				AND
				$moeda
				AND
				$status
				$cartoes
				$fatura
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
			ORDER BY
				$order
	";
//   die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }
    return $duplicatas;
}

/**
 * Função para buscar duplicatas para baixa.
 *
 * @author Bruno Haick
 * @date Criação :  21/03/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaFaturasConvenioPorPeriodo($convenioId, $empresaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if ($convenioId == '00') {
        $convenio = "fatura_convenio.convenio_id LIKE '%'";
    } else {
        $convenio = "fatura_convenio.convenio_id = $convenioId";
    }

    if ($empresaId == '00') {
        $empresa = "fatura_convenio.empresa_id LIKE '%'";
    } else {
        $empresa = "fatura_convenio.empresa_id = $empresaId";
    }

    if ($statusId == 0) {
        $status = "fatura_convenio.status_id LIKE '%'";
    } else {
        $status = "fatura_convenio.status_id = $statusId";
    }

    if ($selecionado == 1) {
//        $data = "fatura_convenio.data_faturamento";
        $data = "fatura_convenio.data_emissao";
    } else if ($selecionado == 2) {
        $data = "fatura_convenio.data_vencimento";
    } else if ($selecionado == 3) {
        $data = "fatura_convenio.data_baixa";
    } else if ($selecionado == 4) {
//	$data = "fatura_convenio.";
    }

    if ($ordenado == 1) {
        $order = "empresa.nome";
    } else if ($ordenado == 2) {
//        $order = "fatura_convenio.data_faturamento";
        $order = "fatura_convenio.data_lancamento";
    } else if ($ordenado == 3) {
        $order = "fatura_convenio.data_vencimento";
    } else if ($ordenado == 4) {
        $order = "fatura_convenio.data_baixa";
    } else if ($ordenado == 5) {
//$data = "fatura_convenio.";
    }
    $sql = "SELECT DISTINCT
				fatura_convenio.id,
				fatura_convenio.numero,
				IF(fatura_convenio.numero_nota is null,'',fatura_convenio.numero_nota) as 'numero_nota',
				fatura_convenio.data_emissao,
				IF(fatura_convenio.data_faturamento is null,'',fatura_convenio.data_faturamento) as 'data_faturamento',
				IF(fatura_convenio.data_inicio is null,'',fatura_convenio.data_inicio) as 'data_inicio',
				IF(fatura_convenio.data_fim is null, '',fatura_convenio.data_fim) as 'data_fim',
				IF(fatura_convenio.valor_a_pagar is null,'',fatura_convenio.valor_a_pagar) as 'valor_a_pagar',
				IF(fatura_convenio.valor_pago is null,'',fatura_convenio.valor_pago)  as 'valor_pago',
				IF(fatura_convenio.data_emissao is null,'',fatura_convenio.data_emissao) as 'data_emissao',
				IF(fatura_convenio.taxa is null,'',fatura_convenio.taxa) as 'taxa',
				IF(fatura_convenio.ajuste is null,'',fatura_convenio.ajuste) as 'ajuste',
				IF(fatura_convenio.faturado is null,'',fatura_convenio.faturado) as 'faturado',
				IF(fatura_convenio.glosa is null,'',fatura_convenio.glosa)  as 'glosa',
				IF(fatura_convenio.desconto is null,'',fatura_convenio.desconto) as 'desconto',
				IF(fatura_convenio.liquido is null,'',fatura_convenio.liquido) as 'liquido',
				IF(fatura_convenio.usuario_baixou_id is null,'',fatura_convenio.usuario_baixou_id) as 'usuario_baixou_id',
				IF(fatura_convenio.data_baixa is null,'',fatura_convenio.data_baixa) as 'data_baixa',
				IF(fatura_convenio.data_vencimento is null,'',fatura_convenio.data_baixa) as 'data_vencimento',
				convenio.nome as nome_convenio,
				empresa.nome as nome_empresa,
				status.nome as nome_status,
				pessoa.nome as nome_pessoa
			FROM
				fatura_convenio,empresa,status,usuario,pessoa,convenio
			WHERE
				$convenio
				AND
				fatura_convenio.convenio_id = convenio.id
				AND
				$empresa
				AND
				fatura_convenio.empresa_id = empresa.id
				AND
				$status
				AND
				fatura_convenio.status_id = status.id
				AND
				fatura_convenio.usuario_id = usuario.usuario_id
				AND
				usuario.usuario_id = pessoa.id
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
			ORDER BY
				$order ";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);
    while ($fatura = mysqli_fetch_assoc($query)) {
        $fatura['data_faturamento'] = converteData($fatura['data_faturamento']);
        $fatura['data_inicio'] = converteData($fatura['data_inicio']);
        $fatura['data_fim'] = converteData($fatura['data_fim']);
        $fatura['data_emissao'] = converteData($fatura['data_emissao']);
        $fatura['data_vencimento'] = converteData($fatura['data_vencimento']);
        $fatura['data_baixa'] = converteData($fatura['data_baixa']);
        $faturas[] = $fatura;
    }
    return $faturas;
}

/**
 * Função para buscar duplicatas para baixa Sub total por grupo
 * escolhido para ordenação. As linhas deste array são subtotais
 * dos grupos formados na busca de parcelas de duplicatas.
 *
 * @author Bruno Haick
 * @date Criação:  11/01/2014
 *
 * @return
 * 	Array.
 *
 */
function buscaDuplicatasBaixaPorPeriodoSubTotal($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if ($fornecedorId == '00') {
        $fornecedor = "duplicata.fornecedores_id LIKE '%'";
    } else {
        $fornecedor = "duplicata.fornecedores_id = $fornecedorId";
    }

    if ($empresaId == '00') {
        $empresa = "duplicata.empresa_id LIKE '%'";
    } else {
        $empresa = "duplicata.empresa_id = $empresaId";
    }

    if ($tipoDocId == '00') {
        $tipo_doc = "duplicata.tipo_doc_id LIKE '%'";
    } else {
        $tipo_doc = "duplicata.tipo_doc_id = $tipoDocId";
    }

    if ($moedaId == 0) {
        $moeda = "duplicata.moeda_id LIKE '%'";
    } else {
        $moeda = "duplicata.moeda_id = $moedaId";
    }

    if ($statusId == 0) {
        $status = "duplicata_parcelas.status_id LIKE '%'";
    } else {
        $status = "duplicata_parcelas.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "duplicata.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "duplicata_parcelas.data_vencimento";
    } else if ($selecionado == 3) {
        $data = "duplicata.data_emissao";
    } else if ($selecionado == 4) {
        $data = "duplicata_parcelas.data_baixa";
    }

    if ($ordenado == 1) {
        $order = "duplicata.id";
    } else if ($ordenado == 2) {
        $order = "fornecedores.nome";
    } else if ($ordenado == 3) {
        $order = "empresa.nome";
    } else if ($ordenado == 4) {
        $order = "duplicata.data_emissao";
    } else if ($ordenado == 5) {
        $order = "duplicata_parcelas.data_vencimento";
    } else if ($ordenado == 6) {
        $order = "duplicata_parcelas.data_baixa";
    } else if ($ordenado == 7) {
        $order = "moeda.nome";
    } else if ($ordenado == 8) {
        $order = "banco.nome";
    }

//				duplicata_parcelas.banco_id,
//				plano_contas.nome as nome_plano_contas,
//				plano_contas.codigo as codigo_plano_contas,
//,plano_contas,nota_fiscal
//				AND
//				duplicata.plano_contas_id = plano_contas.id
    $sql = "SELECT
				SUM(duplicata_parcelas.valor) as 'total',
				SUM(duplicata_parcelas.desconto) as 'total_desconto',
				SUM(duplicata_parcelas.multa) as 'total_multa',
				SUM(duplicata_parcelas.juros) as 'total_juros',
				SUM(duplicata_parcelas.valor_pago) as 'total_pago',
				SUM(duplicata_parcelas.valor_a_pagar) as 'total_a_pagar',
				$order
			FROM
				duplicata 
				INNER JOIN duplicata_parcelas ON duplicata_parcelas.duplicata_id = duplicata.id 
				LEFT JOIN fornecedores ON duplicata.fornecedores_id = fornecedores.id
				LEFT JOIN tipo_doc ON duplicata.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON duplicata.empresa_id = empresa.id 
				LEFT JOIN moeda ON duplicata.moeda_id = moeda.id
				LEFT JOIN status ON duplicata_parcelas.status_id = status.id
				LEFT JOIN banco ON duplicata.banco_id = banco.id
				LEFT JOIN usuario ON duplicata.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id 
			WHERE
				$fornecedor
				AND
				$empresa
				AND
				$tipo_doc
				AND
				$moeda
				AND
				$status
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
			GROUP BY
				$order
			ORDER BY
				$order
	";

//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return $duplicatas;
}

/**
 * Função para buscar duplicatas para baixa.
 *
 * @author Bruno Haick
 * @date Criação :  17/02/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaDuplicatasBaixaPorPeriodo($fornecedorId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if ($fornecedorId == '00') {
        $fornecedor = "duplicata.fornecedores_id LIKE '%'";
    } else {
        $fornecedor = "duplicata.fornecedores_id = $fornecedorId";
    }

    if ($empresaId == '00') {
        $empresa = "duplicata.empresa_id LIKE '%'";
    } else {
        $empresa = "duplicata.empresa_id = $empresaId";
    }

    if ($tipoDocId == '00') {
        $tipo_doc = "duplicata.tipo_doc_id LIKE '%'";
    } else {
        $tipo_doc = "duplicata.tipo_doc_id = $tipoDocId";
    }

    if ($moedaId == 0) {
        $moeda = "duplicata.moeda_id LIKE '%'";
    } else {
        $moeda = "duplicata.moeda_id = $moedaId";
    }

    if ($statusId == 0) {
        $status = "duplicata_parcelas.status_id LIKE '%'";
    } else {
        $status = "duplicata_parcelas.status_id = $statusId";
    }

    if ($selecionado == 1) {
        $data = "duplicata.data_lancamento";
    } else if ($selecionado == 2) {
        $data = "duplicata_parcelas.data_vencimento";
    } else if ($selecionado == 3) {
        $data = "duplicata.data_emissao";
    } else if ($selecionado == 4) {
        $data = "duplicata_parcelas.data_baixa";
    }

    if ($ordenado == 1) {
        $order = "duplicata.id";
    } else if ($ordenado == 2) {
        $order = "fornecedores.nome";
    } else if ($ordenado == 3) {
        $order = "empresa.nome";
    } else if ($ordenado == 4) {
        $order = "duplicata.data_emissao";
    } else if ($ordenado == 5) {
        $order = "duplicata_parcelas.data_vencimento";
    } else if ($ordenado == 6) {
        $order = "duplicata_parcelas.data_baixa";
    } else if ($ordenado == 7) {
        $order = "moeda.nome";
    } else if ($ordenado == 8) {
        $order = "banco.nome";
    }

//				duplicata_parcelas.banco_id,
//				plano_contas.nome as nome_plano_contas,
//				plano_contas.codigo as codigo_plano_contas,
//,plano_contas,nota_fiscal
//				AND
//				duplicata.plano_contas_id = plano_contas.id
    $sql = "SELECT
				status.id as status_id,
				duplicata.numero,
				duplicata.id as id_duplicata,
				duplicata.nota_fiscal,
				DATE_FORMAT(duplicata.data_lancamento, '%d/%m/%Y') as data_lancamento,
				DATE_FORMAT(duplicata.data_emissao, '%d/%m/%Y') as data_emissao,
				duplicata.codigo_barras,
				duplicata_parcelas.historico as observacao,
				duplicata_parcelas.id,
				DATE_FORMAT(duplicata_parcelas.data_vencimento, '%d/%m/%Y') as data_vencimento,
				duplicata_parcelas.numero as numero_parcela,
				DATE_FORMAT(duplicata_parcelas.data_baixa, '%d/%m/%Y') as data_baixa,
				duplicata_parcelas.valor as valor_parcela,
				duplicata_parcelas.desconto,
				duplicata_parcelas.multa,
				duplicata_parcelas.juros,
				duplicata_parcelas.valor_pago,
				duplicata_parcelas.valor_a_pagar,
				fornecedores.nome as nome_fornecedor,
				empresa.nome as nome_empresa,
				moeda.nome as nome_moeda,
				banco.nome as nome_banco,
				tipo_doc.nome as nome_tipo_doc,
				CASE status.id
					WHEN '18' then 'A'
					WHEN '19' then 'P'
					WHEN '20' then 'B'
				END as nome_status,
				pessoa.nome as nome_pessoa,
				pessoa.sobrenome as sobrenome_pessoa,
				DATEDIFF(CURDATE(), duplicata_parcelas.data_vencimento) as dias_atraso
			FROM
				duplicata 
				INNER JOIN duplicata_parcelas ON duplicata_parcelas.duplicata_id = duplicata.id 
				LEFT JOIN fornecedores ON duplicata.fornecedores_id = fornecedores.id
				LEFT JOIN tipo_doc ON duplicata.tipo_doc_id = tipo_doc.id
				LEFT JOIN empresa ON duplicata.empresa_id = empresa.id 
				LEFT JOIN moeda ON duplicata.moeda_id = moeda.id
				LEFT JOIN status ON duplicata_parcelas.status_id = status.id
				LEFT JOIN banco ON duplicata.banco_id = banco.id
				LEFT JOIN usuario ON duplicata.usuario_id = usuario.usuario_id
				LEFT JOIN pessoa ON usuario.usuario_id = pessoa.id 
			WHERE
				$fornecedor
				AND
				$empresa
				AND
				$tipo_doc
				AND
				$moeda
				AND
				$status
				AND
				$data BETWEEN '$data_inicio' AND '$data_fim'
			ORDER BY
				$order
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($duplicata = mysqli_fetch_assoc($query)) {
        $duplicatas[] = $duplicata;
    }

    return $duplicatas;
}

function updateFaturaParcelaStatusEstorno($idStatus, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE
				fatura_parcelas
			SET
				status_id = $idStatus,
				conta_corrente_id = NULL,
				tipo_operacao_id = NULL,
				usuario_baixou_id = NULL,
				data_baixa = NULL
			WHERE
				id = $id
	";

    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function updateFaturaParcelaStatusBaixa($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('fatura_parcelas', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function updateDuplicataParcelaStatusEstorno($idStatus, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE
				duplicata_parcelas
			SET
				status_id = $idStatus,
				conta_corrente_id = NULL,
				tipo_operacao_id = NULL,
				numero_cheque = NULL,
				historico = NULL,
				usuario_baixou_id = NULL,
				data_baixa = NULL
			WHERE
				id = $id
	";

    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function updateFaturaConvenioStatusBaixa($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";
//	die(print_r($dados));
    if (alterar('fatura_convenio', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function updateDuplicataParcelaStatusBaixa($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('duplicata_parcelas', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para buscar parcela_fatura por id ESTORNO
 *
 * @author Bruno Haick
 * @date Criação :  12/03/2013
 *
 * @return
 * 	Array contendo as parcelas de faturas para ESTORNO
 *
 */
function buscaParcelasFaturaEstornoPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				fatura.data_emissao,
				fatura.numero_fatura,
				fatura_parcelas.data_vencimento,
				fatura_parcelas.data_baixa,
				fatura_parcelas.valor,
				fatura_parcelas.numero,
				empresa.nome as nome_empresa,
				tipo_cliente.nome as nome_tipo_cliente,
				clientes_cnpj.nome as nome_cliente,
				conta_corrente.nome as nome_conta_corrente,
				tipo_operacao.nome as nome_tipo_operacao
			FROM
				fatura,fatura_parcelas,empresa,tipo_cliente,clientes_cnpj,tipo_operacao,conta_corrente
			WHERE
				fatura_parcelas.id = $id
				AND
				fatura_parcelas.fatura_id = fatura.id
				AND
				fatura.tipo_cliente_id = tipo_cliente.id
				AND
				fatura.empresa_id = empresa.id
				AND
				fatura.clientes_cnpj_id = clientes_cnpj.id
				AND
				fatura_parcelas.tipo_operacao_id = tipo_operacao.id
				AND
				fatura_parcelas.conta_corrente_id = conta_corrente.id

	";

    $query = mysqli_query(banco::$connection, $sql);

    $parcela = mysqli_fetch_assoc($query);

    return $parcela;
}

/**
 * Função para buscar parcela_fatura por id
 *
 * @author Bruno Haick
 * @date Criação :  12/03/2013
 *
 * @return
 * 	Array contendo as parcelas de faturas para baixa
 *
 */
function buscaParcelasFaturaPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				fatura.data_emissao,
				fatura.numero_fatura,
				fatura_parcelas.data_vencimento,
				fatura_parcelas.valor,
				fatura_parcelas.numero,
				empresa.nome as nome_empresa,
				tipo_cliente.nome as nome_tipo_cliente,
				clientes_cnpj.nome as nome_cliente
			FROM
				fatura,fatura_parcelas,empresa,tipo_cliente,clientes_cnpj
			WHERE
				fatura_parcelas.id = $id
				AND
				fatura_parcelas.fatura_id = fatura.id
				AND
				fatura.tipo_cliente_id = tipo_cliente.id
				AND
				fatura.empresa_id = empresa.id
				AND
				fatura.clientes_cnpj_id = clientes_cnpj.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $parcela = mysqli_fetch_assoc($query);

    return $parcela;
}

/**
 * Função para buscar a conta corrente de cada parcela de Fatura
 * mas este campo só é preenchido se a parcela estiver baixada.
 *
 * @author Bruno Haick
 * @date Criação :  14/03/2013
 *
 * @return
 * 	Array contendo o nome da conta corrente
 *
 */
function buscaContaCorrentePorParcelaFatura($idParcela) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				conta_corrente.nome
			FROM
				fatura_parcelas,conta_corrente
			WHERE
				fatura_parcelas.id = $idParcela
				AND
				fatura_parcelas.conta_corrente_id = conta_corrente.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $parcela = mysqli_fetch_assoc($query);

    return $parcela['nome'];
}

/**
 * Função para buscar parcela_duplicata por id ESTORNO
 *
 * @author Bruno Haick
 * @date Criação :  22/02/2013
 *
 * @return
 * 	Array contendo a soma de valores por duplicata ESTORNO
 *
 */
function buscaParcelasEstornoPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				duplicata.data_emissao,
				duplicata.numero as numero_fatura,
				duplicata_parcelas.data_vencimento,
				duplicata_parcelas.valor,
				duplicata_parcelas.data_baixa,
				duplicata_parcelas.numero_cheque,
				duplicata_parcelas.historico,
				duplicata_parcelas.numero as numero_parcela,
				empresa.nome as nome_empresa,
				tipo_operacao.nome as nome_operacao,
				fornecedores.nome as nome_fornecedor,
				fornecedores.codigo as cod_fornecedor,
				conta_corrente.nome as nome_conta_corrente
			FROM
				duplicata,duplicata_parcelas,fornecedores,empresa,conta_corrente,tipo_operacao
			WHERE
				duplicata_parcelas.id = $id
				AND
				duplicata_parcelas.duplicata_id = duplicata.id
				AND
				duplicata_parcelas.tipo_operacao_id = tipo_operacao.id
				AND
				duplicata.fornecedores_id = fornecedores.id
				AND
				duplicata.empresa_id = empresa.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $parcela = mysqli_fetch_assoc($query);

    return $parcela;
}

/**
 * Função para buscar parcela_duplicata por id
 *
 * @author Bruno Haick
 * @date Criação :  22/02/2013
 *
 * @return
 * 	Array contendo a soma de valores por duplicata.
 *
 */
function buscaParcelasPorId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				duplicata_parcelas.data_vencimento,
				duplicata.data_emissao,
				duplicata_parcelas.valor,
				duplicata_parcelas.numero,
				empresa.nome as nome_empresa,
				fornecedores.nome as nome_fornecedor,
				fornecedores.codigo as cod_fornecedor
			FROM
				duplicata,duplicata_parcelas,fornecedores,empresa
			WHERE
				duplicata_parcelas.id = $id
				AND
				duplicata_parcelas.duplicata_id = duplicata.id
				AND
				duplicata.fornecedores_id = fornecedores.id
				AND
				duplicata.empresa_id = empresa.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $parcela = mysqli_fetch_assoc($query);

    return $parcela;
}

/**
 * Função para calcular o valor total por Fatura.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 *
 * @return
 * 	Array contendo a soma de valores por fatura.
 *
 */
function buscaParcelasPorFatura($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				fatura_parcelas.id,
				fatura_parcelas.data_vencimento,
				fatura_parcelas.valor,
				fatura_parcelas.numero,
				status.nome as nome_status
			FROM
				fatura,fatura_parcelas,status
			WHERE
				fatura.id = $id
				AND
				fatuar_parcelas.duplicata_id = fatura.id
				AND
				fatura_parcelas.status_id = status.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($parcela = mysqli_fetch_assoc($query)) {
        $parcelas[] = $parcela;
    }

    return $parcelas;
}

/**
 * Função para buscar a ultima parcela baixada pro duplicata
 *
 * @author Bruno Haick
 * @date Criação :  14/03/2013
 *
 * @return
 * 	Array contendo a data e o usuário que baixou a parcela
 *
 */
function ultimaBaixaPorDuplicata($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				duplicata_parcelas.id,
				duplicata_parcelas.data_baixa,
				pessoa.nome
			FROM
				duplicata,duplicata_parcelas,usuario,pessoa,status
			WHERE
				duplicata.id = $id
				AND
				duplicata_parcelas.duplicata_id = duplicata.id
				AND
				duplicata_parcelas.usuario_baixou_id = usuario.usuario_id
				AND
				usuario.usuario_id = pessoa.id
				AND
				status.nome = 'Baixado'
				AND
				status.id = duplicata_parcelas.status_id
			ORDER BY
				duplicata_parcelas.data_baixa DESC
			LIMIT 0,1
	";

    $query = mysqli_query(banco::$connection, $sql);

    $parcela = mysqli_fetch_assoc($query);

    return $parcela;
}

/**
 * Função para calcular o valor total por duplicata.
 *
 * @author Bruno Haick
 * @date Criação :  13/02/2013
 *
 * @return
 * 	Array contendo a soma de valores por duplicata.
 *
 */
function buscaParcelasPorDuplicata($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				duplicata_parcelas.id,
				duplicata_parcelas.data_vencimento,
				duplicata_parcelas.valor,
				duplicata_parcelas.numero,
				status.nome as nome_status
			FROM
				duplicata,duplicata_parcelas,status
			WHERE
				duplicata.id = $id
				AND
				duplicata_parcelas.duplicata_id = duplicata.id
				AND
				duplicata_parcelas.status_id = status.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($parcela = mysqli_fetch_assoc($query)) {
        $parcelas[] = $parcela;
    }

    return $parcelas;
}

/**
 * Função para calcular o valor total por fatura.
 *
 * @author Bruno Haick
 * @date Criação :  03/03/2013
 *
 * @return
 * 	Array contendo a soma de valores por duplicata.
 *
 */
function totalPorFatura($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				SUM(fatura_parcelas.valor) as total
			FROM
				fatura INNER JOIN fatura_parcelas ON fatura_parcelas.fatura_id = fatura.id
			WHERE
				fatura.id = $id
				AND
				fatura_parcelas.fatura_id = fatura.id
			GROUP BY
				fatura_parcelas.fatura_id 
	";

    $query = mysqli_query(banco::$connection, $sql);

    $soma = mysqli_fetch_assoc($query);

    return $soma['total'];
}

/**
 * Função para calcular o valor total por duplicata.
 *
 * @author Bruno Haick
 * @date Criação :  13/02/2013
 *
 * @return
 * 	Array contendo a soma de valores por duplicata.
 *
 */
function totalPorDuplicata($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				SUM(duplicata_parcelas.valor) as total
			FROM
				duplicata,duplicata_parcelas
			WHERE
				duplicata.id = $id
				AND
				duplicata_parcelas.duplicata_id = duplicata.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    $soma = mysqli_fetch_assoc($query);

    return $soma['total'];
}

/**
 * Função para buscar o id de um status dado seu nome.
 *
 * @author Bruno Haick
 * @date Criação :  12/02/2013
 *
 * @return
 * 	Array contendo o id do status encontrado.
 *
 */
function buscaIdStatusPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				status
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $moeda = mysqli_fetch_assoc($query);

    return $moeda['id'];
}

/**
 * Busca o fornecedor no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 26/09/2012
 *
 * @param int $id
 *
 * @return
 * 	CNPJ do fornecedor (String)
 */
function fornecedorById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("fornecedores", "cnpj", "id = $id");

    return $array['cnpj'];
}

/**
 * Função para conferir se a nota fiscal já foi feito anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 25/09/2012
 *
 * @param string $id
 * @param string $dados
 *
 * @return
 * 	TRUE se a nota fiscal existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaNotaFiscal($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				COUNT(*)
			FROM
				nota_fiscal
			WHERE
				nota_fiscal = '" . $dados['nota_fiscal'] . "'
				AND
				valor_nota_fiscal = '" . $dados['valor_nota_fiscal'] . "'
				AND
				fornecedores_id = '" . $dados['fornecedores_id'] . "'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    return true;
}

/**
 * Função para inserir uma nota fiscal no banco.
 *
 * @author Marcus Dias
 * @date Criação : 25/09/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereNotaFiscal($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('nota_fiscal', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função par ainserir novo modulo no banco
 * 
 * @date 26/07/2013
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function insereModulos($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserircomNull('modulos', $dados)) {
        return true;
    } else {
        return false;
    }
}

function editarModulosItem($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $where = "id = $id";
    if (alterar('modulos_has_material', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função par ainserir novo item do modulo no banco
 * 
 * @date 26/07/2013
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function insereModulosItem($moduloId, $materialid, $valor, $i, $j, $finalizado = 1) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO `modulos_has_material`(modulos_id,material_id,valor,data,posicao_vertical,posicao_horizontal,finalizado) VALUES('$moduloId','$materialid','$valor',NOW(),'$i','$j',$finalizado)";
//    echo(print_r($sql));
    return mysqli_query(banco::$connection, $sql);
}

/**
 * Função para inserir as parcelas de uma nota fiscal no banco.
 *
 * @author Bruno Haick
 * @date Criação : 23/07/2013
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereNotaFiscalParcela($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('parcelas_nota_fiscal', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para buscar os locais presentes no banco de dados.
 * @author  marcus dias
 * @date criação :  30/09/2012
 * @return
 * 	array contendo as movimentações encontradas.
 *
 */
function buscaLocal() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('local_movimentacao', '*');

    return $array;
}

/**
 * Função para buscar os locais presentes no banco de dados.
 * @author  marcus dias
 * @date criação :  30/09/2012
 * @return
 * 	array contendo as movimentações encontradas.
 *
 */
function buscaLocalArmazenamento() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('local_movimentacao', '*', "(nome = 'Almoxarifado' OR nome = 'Sala de Imunização')");

    return $array;
}

/**
 * Função para buscar as movimentações presentes no banco de dados.
 * conforme o parametro id enviado.
 * @author  marcus dias
 * @date criação :  30/09/2012
 * @return
 * 	array contendo as movimentações encontradas.
 *
 */
function usoById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver('uso', 'nome', "id = $id");

    return $array['nome'];
}

/**
 * Busca o id do Tipo Material dado seu nome;
 *
 * @author Bruno Haick
 * @date Criação: 14/10/2012
 *
 * @return
 * 	id do tipo de material (int)
 */
function idTipoMaterialByNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				id
			FROM
				tipo_material
			WHERE
				nome = '$nome'
	";
    $query = mysqli_query(banco::$connection, $sql);

    $arr = mysqli_fetch_row($query);

    return $arr[0];
}

/* conforme o parametro id recebido.
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * 	String nome do tipo de material.
 *
 */

function tipoMaterialById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("tipo_material", "nome", "id = $id");

    return $array['nome'];
}

/**
 * Função para buscar as movimentações presentes no banco de dados.
 * conforme o parametro id do material enviado.
 * @author  marcus dias
 * @date criação :  30/09/2012
 * @return
 * 	array contendo as movimentações encontradas.
 *
 */
function buscaLotebyMaterialId($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * FROM (SELECT
				lote.id as 'LoteID',
				if(count(lote.id)> movimentacao.quantidade,0,1) as 'valido'
				
			FROM
				movimentacao INNER JOIN lote ON lote.id = movimentacao.lote_id
			WHERE
				movimentacao.material_id = '$id'
			GROUP BY
				lote.id
			ORDER BY
				valido DESC,lote.validade) a
		WHERE a.valido = '1'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para conferir as movimentações de certo material 
 * conforme o parametro id do material 
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * 	Array da movimentacao.
 *
 */
function confereMovimentacaoMaterial($idMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*,SUM(quantidade) as saldo
		FROM
			movimentacao,lote
		WHERE
			movimentacao.material_id = '$idMaterial'
		AND
			lote.id = movimentacao.lote_id
		GROUP BY
			lote.nome,movimentacao.flag
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para conferir as movimentações de certo material de certo lote 
 * conforme o parametro id do material e id do lote
 * @author Marcus Dias
 * @date Criação: 10/10/2012
 *
 * @return
 * Array da movimentacao.
 *
 */
function movimentacaoMaterialPorLote($idMaterial, $idLote, $dataInicio, $dataFim, $tipo) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*,movimentacao.id as id_movimentacao,SUM(quantidade) as saldo
			FROM
				movimentacao,lote
			WHERE
				movimentacao.material_id = '$idMaterial'
			AND
				lote.id = movimentacao.lote_id
			AND
				lote.id = '$idLote'
	";
    if ($tipo != 'total' && $tipo != 'quantidade') {
        $sql .= "
				AND
					movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
		";
    }
    $sql .= "
			GROUP BY
				lote.nome,movimentacao.flag
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para buscar a quantidade de material disponivel por lote.
 * conforme o parametro id do material recebido e lote
 * @author Marcus Dias
 * @date Criação: 10/10/2012
 * @return
 * Quantidade do Material.
 *
 */
function estoqueMaterialPorLote($idMaterial, $idLote, $dataInicio, $dataFim, $tipo) {
    $somaEstoque = 0;
    foreach (movimentacaoMaterialPorLote($idMaterial, $idLote, $dataInicio, $dataFim, $tipo) as $estoqueMaterial) {
        if ($estoqueMaterial['flag'] == 'E') {
            $somaEstoque = $somaEstoque + $estoqueMaterial['saldo'];
            $entrada += $estoqueMaterial['saldo'];
        } else if ($estoqueMaterial['flag'] == 'S') {
            $somaEstoque = $somaEstoque - $estoqueMaterial['saldo'];
            $consumo += $estoqueMaterial['saldo'];
        }
    }

    $material = materialById($idMaterial);
    $divisor = $material['quantidade_doses'] * $material['qtd_ml_por_dose'];

    if ($tipo == 'total') {
        $somaEstoque = ($somaEstoque) / ($divisor);
        $strTotal = frascosML($material['quantidade_doses'], $material['qtd_ml_por_dose'], $somaEstoque);
        return $strTotal;
    } else if ($tipo == 'consumo') {
        if ($consumo != 0) {
            $arrayConsumo = consumoMaterialPorLote($idMaterial, $idLote, $dataInicio, $dataFim);
            $consumo = $arrayConsumo[0]['saldo'];
        }
        $consumo = ($consumo) / ($divisor);
        $strConsumo = frascosML($material['quantidade_doses'], $material['qtd_ml_por_dose'], $consumo);
        return $strConsumo;
    } else if ($tipo == 'consumoArray') {
        if ($consumo != 0) {
            $arrayConsumo = consumoMaterialPorLote($idMaterial, $idLote, $dataInicio, $dataFim);
            $consumo = $arrayConsumo[0]['saldo'];
        }
        $arrayConsumo['qtd'] = $consumo;
        $arrayConsumo['qtd_doses'] = $material['quantidade_doses'];
        $arrayConsumo['qtd_ml'] = $material['qtd_ml_por_dose'];
        $consumo = ($consumo) / ($divisor);
        $arrayConsumo['str'] = frascosML($material['quantidade_doses'], $material['qtd_ml_por_dose'], $consumo);
        return $arrayConsumo;
    } else if ($tipo == 'entrada') {
        $entrada = ($entrada) / ($divisor);
        $strEntrada = frascosML($material['quantidade_doses'], $material['qtd_ml_por_dose'], $entrada);
        return $strEntrada;
    } else if ($tipo == 'quantidade') {
        $arraySomaEstoque['qtd'] = $somaEstoque;
        $arraySomaEstoque['qtd_doses'] = $material['quantidade_doses'];
        $arraySomaEstoque['qtd_ml'] = $material['qtd_ml_por_dose'];
        return $arraySomaEstoque;
    }
}

/**
 * Função para retornar a quantidade de frascos e Mls de certo material
 * conforme o divisor( qtd de doses por frasco, e MLs por Dose ) e a soma de 
 * quantidade recebido (em FRASCOS).
 * @author Marcus Dias
 * @date Criação: 11/10/2012
 * @return
 * Quantidade do Material.
 *
 */
function frascosML($qtdDoses, $qtdMlPorDose, $somaEstoque, $flag) {

    if ($flag) {

        $somaEstoque = ($somaEstoque / ($qtdMlPorDose * $qtdDoses));
    }
    if (is_decimal($somaEstoque)) {
        $decimal = $somaEstoque - intval($somaEstoque);
        $strQtdDoses = $decimal * $qtdDoses;

        if (is_decimal($strQtdDoses)) {
            $decimal2 = ($strQtdDoses) - intval($strQtdDoses);
            $strEstoqueMaterial = intval($somaEstoque) . " F " . (intval($strQtdDoses)) . " D " . ($decimal2 * $qtdMlPorDose) . " ML";
        } else {
            $strEstoqueMaterial = intval($somaEstoque) . " F " . ($strQtdDoses) . " D";
        }
    } else {
        $strEstoqueMaterial = intval($somaEstoque) . " F";
    }

    return $strEstoqueMaterial;
}

/**
 * Função para buscar a quantidade de material disponivel.
 * conforme o parametro id do material recebido
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 * @return
 * Quantidade do Material.
 *
 */
function estoqueMaterial($id, $tipo) {
    $somaEstoque = 0;
    foreach (confereMovimentacaoMaterial($id) as $estoqueMaterial) {
        if ($estoqueMaterial['flag'] == 'E') {
            $entrada += $estoqueMaterial['saldo'];
            $somaEstoque = $somaEstoque + $estoqueMaterial['saldo'];
        } else if ($estoqueMaterial['flag'] == 'S') {
            $somaEstoque = $somaEstoque - $estoqueMaterial['saldo'];
            $consumo += $estoqueMaterial['saldo'];
        }
    }

    $material = materialById($id);
    $divisor = $material['quantidade_doses'] * $material['qtd_ml_por_dose'];
    if ($tipo == 'entrada') {
        $somaEstoque = $entrada;
    } else if ($tipo == 'consumo') {
        if ($consumo != 0) {
            $arrayConsumo = consumoMaterial($id);
            $consumo = $arrayConsumo[0]['saldo'];
        }
        $somaEstoque = $consumo;
    }

    $somaEstoque = ($somaEstoque) / ($divisor);
    $strEstoqueMaterial = frascosML($material['quantidade_doses'], $material['qtd_ml_por_dose'], $somaEstoque);
    return $strEstoqueMaterial;
}

/**
 * Função para buscar a quantidade de material disponivel.
 * conforme o parametro id do material recebido, id do local e id do 
 * lote.
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 * @return
 * Quantidade do Material.
 *
 */
function quantidadeMaterialByLocalId($id, $localId, $lote) {
    $somaEstoque = 0;

    foreach (saldoDestinoTransferenciaMovimentacaoMaterial($id, $lote, $localId) as $estoqueMaterial) {
        $somaEstoque = $somaEstoque + $estoqueMaterial['saldo'];
    }
    foreach (saldoOrigemTransferenciaMovimentacaoMaterial($id, $lote, $localId) as $estoqueMaterial) {
        $somaEstoque = $somaEstoque - $estoqueMaterial['saldo'];
    }
    $material = materialById($id);
    $divisor = $material['quantidade_doses'] * $material['qtd_ml_por_dose'];
    $somaEstoque = ($somaEstoque) / ($divisor);
    $strEstoqueMaterial = frascosML($material['quantidade_doses'], $material['qtd_ml_por_dose'], $somaEstoque);

    return $strEstoqueMaterial;
}

function saldoDestinoTransferenciaMovimentacaoMaterial($idMaterial, $idLote, $idDestino) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				movimentacao.flag,SUM(movimentacao.quantidade) as saldo
			FROM
				movimentacao,lote
			WHERE
				lote.id = movimentacao.lote_id
				AND
				movimentacao.material_id = '$idMaterial'
				AND
				lote.id = '$idLote'
				AND
				movimentacao.local_movimentacao_destino_id = '$idDestino'
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function saldoOrigemTransferenciaMovimentacaoMaterial($idMaterial, $idLote, $idOrigem) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				movimentacao.flag,SUM(movimentacao.quantidade) as saldo
			FROM
				movimentacao,lote
			WHERE
				lote.id = movimentacao.lote_id
				AND
				movimentacao.material_id = '$idMaterial'
				AND
				lote.id = '$idLote'
				AND
				movimentacao.local_movimentacao_origem_id = '$idOrigem'
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para buscar um lote  presente no banco de dados.
 * conforme o parametro nome e validade recebidos.
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * Int id do lote
 */
function idLoteByNomeValidade($nome, $validade) {

    $array = ver("lote", "id", "nome = '$nome' AND validade = '$validade'");
    return $array['id'];
}

/**
 * Função para buscar um lote  presente no banco de dados.
 * conforme o parametro id recebido.
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * Array do lote.
 */
function loteById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("lote", "*", "id = $id");

    return $array;
}

/**
 * Função para buscar um lote  presente no banco de dados.
 * conforme o parametro id recebido.
 *
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * Array do material.
 *
 */
function materialById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("material", "*", "id = $id");

    return $array;
}

/**
 * Função para inserir uma movimentacao de material no banco.
 *
 * @author Marcus Dias
 * @date Criação : 05/10/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereMovimentacaoMaterial($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('movimentacao', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir uma transferencia em sua tabela no banco.
 *
 * @author Marcus Dias
 * @date Criação : 05/10/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereTransferencia($dados) {
    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('transferencia', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir uma entrada em sua tabela no banco.
 *
 * @author Marcus Dias
 * @date Criação : 08/10/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereEntrada($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('entrada', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para inserir uma saida em sua tabela no banco.
 *
 * @author Marcus Dias
 * @date Criação : 09/10/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereSaida($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('saida', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para buscar os motivos presentes no banco de dados.
 *
 * @author  Marcus Dias
 * @date Criação :  08/10/2012
 * @return
 * 	Array contendo todos os fretes encontrados.
 *
 */
function buscaMotivo() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('motivo', '*');

    return $array;
}

/**
 * Função para conferir se o lote já cadastrado foi feito anteriormente. 
 * Ou seja, conferir se já existe registro no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 25/09/2012
 *
 * @param string $id
 * @param string $dados
 *
 * @return
 * 	TRUE se o lote existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaLote($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				COUNT(*)
			FROM
				lote
			WHERE
				nome = '" . $dados['nome'] . "'
				AND
				validade = '" . $dados['validade'] . "'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    return true;
}

/**
 * Função para inserir um lote no banco.
 *
 * @author Marcus Dias
 * @date Criação : 05/10/2012
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereLote($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('lote', $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para buscar uma nota fiscal presente no banco de dados.
 * conforme o parametro nota fiscal e fornecedor_id recebidos.
 * @author Marcus Dias
 * @date Criação: 30/09/2012
 *
 * @return
 * Int id da nota fiscal.
 *
 */
function idNotaFiscalByDados($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("nota_fiscal", "id", "nota_fiscal = '" . $dados['nota_fiscal'] . "' AND fornecedores_id = '" . $dados['fornecedores_id'] . "'AND valor_nota_fiscal = '" . $dados['valor_nota_fiscal'] . "'
");
    return $array['id'];
}

/**
 * Busca o historico correspondente a flag recebida e se a flag for de saida
 * o motivo da saida é buscado.
 *
 * @author Marcus Dias
 * @date Criação: 18/10/2012
 *
 * @param int $id
 * @param int $idMotivo
 *
 * @return
 * 	Historico (String)
 */
function histByFlag($flag, $idMotivo) {

    switch ($flag) {
        case 'S':
            $nome = motivoById($idMotivo);
            break;
        case 'T':
            $nome = 'Transferencia';
            break;
        case 'E':
            $nome = 'Entrada';
            break;
    }
    return $nome;
}

/**
 * Busca o motivo no banco de dados correspondente ao id dado
 * como parâmetro para a funcao.
 *
 * @author Marcus Dias
 * @date Criação: 24/09/2012
 *
 * @param int $id
 *
 * @return
 * 	Nome do procedimento (String)
 */
function motivoById($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("motivo", "nome", "id = $id");

    return $array['nome'];
}

/**
 * Função para mostrar as ultimas entradas quando opção marcada
 * for de entrada.
 * @author Marcus Dias
 * @date Criação: 18/10/2012
 *
 * @param int $idmaterial
 * @param date $dataInicio
 * @param date $dataFim
 *
 * @return
 * 	Array ultimas Entradas
 */
function ultEntradasEntrada($idMaterial, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				movimentacao.id as id_movimentacao,
				movimentacao.quantidade as quantidade,
				nota_fiscal.nota_fiscal,
				nota_fiscal.data_entrada,
				nota_fiscal.valor_nota_fiscal,
				fornecedores.nome as nome_fornecedor,
				entrada.valor_unit,
				material.qtd_ml_por_dose,
				material.quantidade_doses,
				material.nome as nome_material
			FROM
				movimentacao,lote,material,entrada,nota_fiscal,fornecedores
			WHERE
				entrada.id = movimentacao.id
				AND
				entrada.nota_fiscal_id = nota_fiscal.id
				AND
				nota_fiscal.fornecedores_id = fornecedores.id
				AND
				movimentacao.material_id = material.id
				AND
				movimentacao.lote_id = lote.id
	";
    if (!empty($idMaterial)) {
        $sql .= "
					AND
					material.id = $idMaterial
		";
    }
    $sql .= "
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para mostrar as ultimas entradas quando opção marcada
 * for de ultimas 6 entradas.
 * @author Marcus Dias
 * @date Criação: 18/10/2012
 *
 * @param int $idmaterial
 * @param date $dataInicio
 * @param date $dataFim
 *
 * @return
 * 	Array ultimas Entradas
 */
function ultEntradasUlt6Entradas($idMaterial, $dataInicio, $dataFim, $aux) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				movimentacao.id as id_movimentacao,
				movimentacao.data as data_movimentacao,
				movimentacao.quantidade as quantidade,
				nota_fiscal.nota_fiscal,
				nota_fiscal.data_entrada,
				nota_fiscal.valor_nota_fiscal,
				fornecedores.nome as nome_fornecedor,
				entrada.valor_unit,
				material.id as id_material,
				material.nome as nome_material,
				material.qtd_ml_por_dose,
				material.quantidade_doses,
				material.nome as nome_material
			FROM
				movimentacao,lote,material,entrada,nota_fiscal,fornecedores
			WHERE
				entrada.id = movimentacao.id
				AND
				entrada.nota_fiscal_id = nota_fiscal.id
				AND
				nota_fiscal.fornecedores_id = fornecedores.id
				AND
				movimentacao.material_id = material.id
				AND
				movimentacao.lote_id = lote.id
	";
    if (!empty($idMaterial)) {
        $sql .= "
					AND
					material.id = $idMaterial
		";
    }
    $sql .= "
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
	";
    if (isset($aux)) {
        $sql .= "
				GROUP BY 
					material.id
		";
    } else {
        $sql .= "
				ORDER BY 
					movimentacao.data
				LIMIT 0,6
		";
    }
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para mostrar as ultimas entradas quando opção marcada
 * for de fluxo de vacinas.
 * @author Marcus Dias
 * @date Criação: 18/10/2012
 *
 * @param int $idmaterial
 * @param date $dataInicio
 * @param date $dataFim
 *
 * @return
 * 	Array ultimas Entradas
 */
function ultEntradasFluxoVacinas($idMaterial, $dataInicio, $dataFim, $tipoMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				material.id as id_material,
				material.qtd_ml_por_dose,
				material.quantidade_doses,
				material.nome as nome_material
			FROM
				movimentacao,material,tipo_material
			WHERE
				movimentacao.material_id = material.id
				AND
				material.tipo_material_id = tipo_material.id
	";
    if (!empty($idMaterial)) {
        $sql .= "
					AND
					material.id = $idMaterial
		";
    }
    $sql .= "
				AND
				tipo_material.nome LIKE '%$tipoMaterial%'
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				material.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $materiais[] = $linha;
    }

    return $materiais;
}

/**
 * Função para buscar o consumo de certo material de certo lote 
 * conforme o parametro id do material e id do lote
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * Array
 */
function consumoMaterial($idMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				SUM(movimentacao.quantidade) as saldo
			FROM
				movimentacao,saida,lote
			WHERE
				lote.id = movimentacao.lote_id
				AND
				movimentacao.id = saida.id
				AND
				movimentacao.flag = 'S'
				AND
				saida.motivo_id = 2
				AND
				movimentacao.material_id = '$idMaterial'
			GROUP BY
				movimentacao.flag
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para buscar o consumo de certo material de certo lote 
 * conforme o parametro id do material e id do lote
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * Array
 */
function consumoMaterialPorLote($idMaterial, $idLote, $dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				saida.usuario_id,
				movimentacao.data,
				SUM(movimentacao.quantidade) as saldo
			FROM
				movimentacao,lote,saida
			WHERE
				lote.id = movimentacao.lote_id
				AND
				movimentacao.id = saida.id
				AND
				movimentacao.flag = 'S'
				AND
				saida.motivo_id = 2
				AND
				lote.id = '$idLote'
				AND
				movimentacao.material_id = '$idMaterial'
				AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				movimentacao.flag
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para buscar os pacotes de certo material 
 * conforme o parametro id do material recebido
 *
 * @author Marcus Dias
 * @date Criação: 22/10/2012
 *
 * @return
 * Quantidade de Pacotes
 */
function buscaPacotes($idMaterial) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			((servico.qtd_doses * material.qtd_ml_por_dose) - SUM(historico.qtd_ml)) as qtdML
		FROM
			servico,material,status,historico
		WHERE
			servico.material_id = material.id
			AND
			servico.id = historico.servico_id
			AND
			servico.status_id = status.id
			AND
			servico.finalizado = '0'
			AND
			material.id = '$idMaterial'
			AND
			status.nome = 'Pago'
			AND
			(
			historico.status_id = '1'
			OR
			historico.status_id = '7'
			)
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para filtrar os convenios conforme a tabela escolhida
 *
 * @author Marcus Dias
 * @date Criação: 23/10/2012
 *
 * @return
 * Quantidade de Pacotes
 */
function filtraConvenioPorTabela($tabelaID) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				convenio.*
			FROM
				convenio_has_procedimento_has_tabela,convenio
			WHERE
				convenio_has_procedimento_has_tabela.convenio_id = convenio.id
				AND
				tabela_id = $tabelaID
			GROUP BY
				convenio.id
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para filtrar as tabelas conforme o convenio escolhido
 *
 * @author Marcus Dias
 * @date Criação: 23/10/2012
 *
 * @return
 * Quantidade de Pacotes
 */
function filtraTabelaPorConvenio($convenioID) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				tabela.*
			FROM
				convenio_has_procedimento_has_tabela,tabela
			WHERE
				convenio_has_procedimento_has_tabela.tabela_id = tabela.id
				AND
				convenio_id = $convenioID
			GROUP BY
				tabela.id
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para conferir as movimentações de certo material de certo lote 
 * conforme o parametro id do material e id do lote
 * @author Marcus Dias
 * @date Criação: 10/10/2012
 *
 * @return
 * Array da movimentacao.
 *
 */
function movimentacaoPorLoteEntrada($dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
					 *,
					 nota_fiscal.id as nota_fiscalId,
					 lote.id as loteId,
					 material.id as materialId,
					 lote.nome as loteNome,
					 material.nome as materialNome,
					 SUM(movimentacao.quantidade) as qtd
			FROM
				movimentacao,lote,entrada,nota_fiscal,material
			WHERE
				material.id = movimentacao.material_id
			AND
				entrada.nota_fiscal_id = nota_fiscal.id
			AND
				movimentacao.id = entrada.id
			AND
				lote.id = movimentacao.lote_id
			AND
				movimentacao.flag = 'E'
			AND
				movimentacao.data BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				lote.nome
			ORDER BY
				nota_fiscalId
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para buscar o consumo de certo material de certo lote 
 * conforme o parametro id do material e id do lote
 * @author Marcus Dias
 * @date Criação: 16/10/2012
 *
 * @return
 * Array
 */
function consumidoresMaterialPorLote($idMaterial, $idLote) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				saida.usuario_id,
				movimentacao.data
			FROM
				movimentacao,lote,saida
			WHERE
				lote.id = movimentacao.lote_id
				AND
				movimentacao.id = saida.id
				AND
				movimentacao.flag = 'S'
				AND
				saida.motivo_id = 2
				AND
				lote.id = '$idLote'
				AND
				movimentacao.material_id = '$idMaterial'
			GROUP BY
				saida.usuario_id
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

/**
 * Função para inserir uma consulta no banco.
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @author Marcus Dias
 * @date Criação: 11/11/2012
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function insereConsulta($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('consulta', $dados)) {
        return true;
    } else {
        return false;
    }
}

function removeConsulta($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql1 = "DELETE FROM `consultas_has_exames` WHERE `consultas_id`=$id";
    $sql2 = "DELETE FROM `consultas_has_cid10` WHERE `consultas_id`=$id";
    $sql3 = "DELETE FROM `consultas` WHERE `id`=$id";
    if (mysqli_query(banco::$connection, $sql1)) {
        if (mysqli_query(banco::$connection, $sql2)) {
            if (mysqli_query(banco::$connection, $sql3)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Função para editar consultas
 *
 * @param Array contendo os campos da tabela como indice do Array
 * e os valores do array como valores dos campos.
 *
 * @param int $id
 *
 * @return
 * 	TRUE em sucesso, e FALSE em caso de falha
 *
 */
function editarConsulta($dados, $id_consulta) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $where = "id = $id_consulta";

    if (alterar('consulta', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Função para conferir se a consulta já existe no banco de dados.
 *
 * @author Marcus Dias
 * @date Criação : 12/11/2012
 *
 * @param array $dados
 *
 * @return
 * 	TRUE se o agendamento existe no banco, caso contrario, FALSE.
 *
 */
function confereExistenciaConsulta($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				consultas.id
			FROM
				consultas
			WHERE
				cliente_cliente_id = '" . $dados['cliente_cliente_id'] . "'
				AND
				medico_id = '" . $dados['medico_medico_id'] . "'
				AND
				data = '" . $dados['data'] . "'";
    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);

    if (count($linha) == 0) {
        return false;
    }

    return $linha['id'];
}

/**
 * Função para buscar uma consulta ou uma consulta de certo cliente no banco de dados.
 * dependendo do type recebido
 *
 * @author Marcus Dias
 * @date Criação : 12/11/2012
 *
 * @param array $id
 * @param string $type
 *
 * @return
 * 	array
 *
 */
function buscaConsultasCliente($id, $type) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consultas
			WHERE
	";
    if ($type == 'cliente') {
        $sql .= "
				cliente_cliente_id = '$id'
	";
    } else if ($type == 'consulta') {
        $sql .= "
				id = '$id'
	";
    }
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/* CORACAOZINHO */

function anotacoesHfCoracaozinho() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "
		SELECT
			*
		FROM
			coracao_anot_hf
	";
    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function anotacoesQpCoracaozinho() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "
		SELECT
			*
		FROM
			coracao_anot_qp
	";
    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/* LINGUINHA */

function anotacoesQpLinguinha() {
    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			*
		FROM
			lingua_anot_qp
	";

    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaTriagemLinguinhaCliente($id, $type) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_linguinha
			WHERE
				cliente_id = '$id'
			ORDER BY
				id DESC
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    if ($type == 'ult_consulta') {
        return $array[0];
    } else {
        return $array;
    }
}

function confereResultadoLinguinha($id_resultado, $id_consulta) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				COUNT(*)
			FROM
				lingua_resultados_has_consulta_linguinha
			WHERE
				lingua_resultados_id = '$id_resultado'
				AND
				consulta_linguinha_id = '$id_consulta'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_row($query);

    if ($linha[0] == 0) {
        return false;
    }

    return true;
}

/* ORELHINHA1 */

function buscaTriagemOrelhinha1Cliente($id, $type) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_orelhinha1
			WHERE
				cliente_id = '$id'
			ORDER BY
				id DESC
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    if ($type == 'ult_consulta') {
        return $array[0];
    } else {
        return $array;
    }
}

/* ORELHINHA2 */

function listaConclusaoOrelinha2($lado) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			conclusao_orelhinha
	";

    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaTriagemOrelhinha2Cliente($id, $type) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_orelhinha2
			WHERE
				cliente_id = '$id'
			ORDER BY
				id DESC
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    if ($type == 'ult_consulta') {
        return $array[0];
    } else {
        return $array;
    }
}

/* OLHINHO */

function buscaTriagemOlhinhoCliente($id, $type) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_olhinho
			WHERE
				cliente_id = '$id'
			ORDER BY
				id DESC
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    if ($type == 'ult_consulta') {
        return $array[0];
    } else {
        return $array;
    }
}

function buscaResultadoLinguinha($id_consulta) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				lingua_resultados_has_consulta_linguinha
			WHERE
				consulta_linguinha_id = '$id_consulta'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaQpLinguinha($id_consulta) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_linguinha_has_lingua_anot_qp
			WHERE
				consulta_linguinha_id = '$id_consulta'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaHfCoracaozinho($id_consulta) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_coracaozinho_has_coracao_anot_hf
			WHERE
				consulta_coracaozinho_id = '$id_consulta'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaQpCoracaozinho($id_consulta) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				*
			FROM
				consulta_coracaozinho_has_coracao_anot_qp
			WHERE
				consulta_coracaozinho_id = '$id_consulta'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function deletarGrupoProcedimento($idGrupo) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover('grupo_procedimento', "id = $idGrupo")) {
        return true;
    } else {
        return false;
    }
}

function deletarHistoricoProcedimentosPorGrupoProcedimento($idGrupo) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover('historico_procedimento', "grupo_procedimento_id = $idGrupo")) {
        return true;
    } else {
        return false;
    }
}

function deleteControlesPorGuia($idGuia) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("controle", "guia_controle_id = $idGuia")) {
        return true;
    } else {
        return false;
    }
}

function deletarClienteFilaEsperaMedico($medico_id, $cliente_id, $data) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "DELETE
			FROM 
				fila_espera_consulta
			WHERE
				medico_id = $medico_id
				AND
				cliente_id = $cliente_id
				AND
				data = '$data'
	";

    $query = mysqli_query(banco::$connection, $sql) or die(mysqli_error(banco::$connection));

    if ($query) {
        return true;
    } else {
        return false;
    }
}

function deleteClienteAntMorbido($id) {
    if (remover("cliente_has_antecedente_morbido", "cliente_cliente_id = $id")) {
        return true;
    } else {
        return false;
    }
}

function deleteClienteAlergias($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("cliente_has_alergias", "cliente_cliente_id = $id")) {
        return true;
    } else {
        return false;
    }
}

function deleteClienteCondNascimento($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("cliente_has_condicoes_nascimento", "cliente_cliente_id = $id")) {
        return true;
    } else {
        return false;
    }
}

function existeFilaEsperaCaixaPorTitular($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $data = date('Y-m-d');

    $sql = "SELECT
				fila_espera_caixa.*
			FROM
				fila_espera_caixa,
				pessoa
			WHERE
				fila_espera_caixa.cliente_cliente_id = pessoa.id
				AND
				fila_espera_caixa.cliente_cliente_id = $id
				AND
				fila_espera_caixa.finalizado != 1
				AND
				data = '$data'
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $fila[] = $linha;
    }

    return $fila;
}

function carregaFilaEsperaCaixa($data = null) {


//	mysqli_set_charset(banco::$connection,'utf8');
    $sql = "SELECT
	 d.fk_titular_id as 'responsavel',  cli.matricula as 'responsavel_matricula',
cli.fk_medico_id as 'responsavel_medico_id', CONCAT(pes1.nome,' ',pes1.sobrenome) as 'responsavel_nome'  ,
pes1.data_nascimento as 'responsavel_nascimento',d.dependente_id as 'cliente',cli2.matricula as 'cliente_matricula',
cli2.fk_medico_id as 'cliente_medico_id', CONCAT(pes2.nome,' ',pes2.sobrenome) as 'cliente_nome',pes2.data_nascimento as 'cliente_nascimento',
d.vacina_casa as 'vacina_casa',con.guia_controle_id as 'id_controle',con.numero_controle as 'numero_controle',guia.numero_controle as 'numero_guia',con.`data` as 'controle_data',con.hora as 'controle_hora',con.material_id as 'controle_material', con.modulo as 'controle_modulo',con.`status` as 'controle_status', guia.convenio_id as 'convenio_guia',guia.`data` as 'data_guia',guia.operador as 'operador',guia.fila_id as 'numero_fila'
FROM
	cliente c
INNER JOIN dependente d ON c.cliente_id = d.fk_titular_id
INNER JOIN controle AS con ON con.cliente_id = d.dependente_id INNER JOIN
(
	SELECT
		g.id as 'GuiaControle_ID',
		g.titular_id,
		g.convenio_id,
		g.finalizado,
		g.`data`,
		g.numero_controle,
		f.id AS 'fila_id',
		f.data as 'fila_data',
		f.operador_id AS 'operador'
	FROM
		guia_controle g
	INNER JOIN fila_espera_caixa f ON f.cliente_cliente_id = g.titular_id AND f.finalizado = '0' 
) AS guia ON guia.titular_id =  d.fk_titular_id INNER JOIN cliente as cli ON cli.cliente_id =  d.fk_titular_id INNER JOIN cliente as cli2 ON cli2.cliente_id = d.dependente_id  INNER JOIN pessoa pes1 ON pes1.id = d.fk_titular_id INNER JOIN pessoa pes2 ON pes2.id =  d.dependente_id
WHERE
	guia.finalizado = 0
GROUP BY 
	guia.GuiaControle_ID;";

//	if ($data == null) {
//		$sql .= ' AND data="' . date('Y-m-d') . '" ';
//	} else {
//		$sql .= " AND data='$data' ";
//	}

    return pesquisaOOP($sql);
}

//
//ATESTADOS
function atualizaAtestadoConsulta($idc, $atestado) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `consultas` SET atestado='$atestado' WHERE id = $idc";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function buscaAtestados() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('atestado', '*');
    return $array;
}

function verAtestado($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver('atestado', '*', "id = $id");
    return $array;
}

function inserirAtestado($dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if (inserir('atestado', $dados))
        return true;
    else {
        return false;
    }
}

function atualizarRps($id, $dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if (alterar('rps', "id = $id", $dados)) {
        return true;
    } else {
        return false;
    }
}

function atualizarAtestado($id, $dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if (alterar('atestado', "id = $id", $dados)) {
        return true;
    } else {
        return false;
    }
}

function removerAtestado($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover('atestado', "id = $id")) {
        return true;
    } else {
        return false;
    }
}

/**
 * Busca lista dos prestadores - utilizado principalmente no modal de 
 * requisições e encaminhamentos no modulo de consulta
 *
 * @return
 * 	Array com lista de prestadores
 */
function buscaPrestador() {

    mysqli_set_charset(banco::$connection, 'utf8');
//$array = listar('prestador', '*');
    $sql = "SELECT * FROM prestador ORDER BY favorito DESC, nome";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaRequisicaoServico() {

    mysqli_set_charset(banco::$connection, 'utf8');
//$array = listar('requisicao_servico', '*');
    $sql = "SELECT * FROM requisicao_servico ORDER BY favorito DESC, nome";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaPrestadorById($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver('prestador', '*', "id = '$id'");
    return $array;
}

function buscaRequisicaoServicoById($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver('requisicao_servico', '*', "id = '$id'");
    return $array;
}

function inserirPrestador($nome, $end, $tel, $email) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO `prestador` (nome, endereco, telefone, email) VALUES('$nome','$end','$tel','$email')";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function editarPrestador($id, $nome, $end, $tel, $email) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `prestador` SET nome='$nome', endereco='$end', telefone='$tel', email='$email' WHERE id=$id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function removerPrestador($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("prestador", "id = $id"))
        return true;
    else
        return false;
}

function marcadesmarcaFavoritoPrestador($id, $val) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `prestador` SET favorito=$val WHERE id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function inserirRequisicao($nome, $subt) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO `requisicao_servico` (nome, subtestes) VALUES('$nome','$subt')";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function editarRequisicao($id, $nome, $subt) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `requisicao_servico` SET nome='$nome', subtestes='$subt' WHERE id=$id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function removerRequisicao($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("requisicao_servico", "id = $id"))
        return true;
    else
        return false;
}

function marcadesmarcaFavoritoRequisicao($id, $val) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `requisicao_servico` SET favorito=$val WHERE id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function buscaDoencas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar('doencas', '*');
    return $array;
}

//Atualisa prescricoes de determinada consulta
function atualizaPrescricoesConsulta($idc, $prescricao) {
    mysqli_set_charset(banco::$connection, 'utf8');
    ;
    $sql = "UPDATE `consultas` SET prescricao='$prescricao' WHERE id = $idc";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function buscaPrescricoes($medico_id = null, $filtro = null) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "";
    if ($medico_id != null)
        $sql .= " medico_respons_id = '$medico_id' ";
    if ($filtro != null && $medico_id != null)
        $sql .= " AND ";
    if ($filtro != null)
        $sql .= "nome LIKE '%$filtro%' ";

    $array = listar("prescricoes", "*", $sql);
    return $array;
}

function buscaRecomendacoes($filtro) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar("recomendacoes", "*", "nome LIKE '%$filtro%'OR descricao LIKE '%$filtro%' ");
    return $array;
}

function buscaHipoteseDoencas($medico_id, $filtro) {
    mysqli_set_charset(banco::$connection, 'utf8');
    if ($medico_id == -1) {
        $sql = "SELECT
                                    *
                            FROM
                                    doencas
                            WHERE                                    
                                    nome LIKE '%$filtro%'                            
                            LIMIT 100

            ";
    } else {
        $sql = "SELECT
                                    doencas.id, doencas.nome, doencas.codigo, m.favorito
                            FROM
                                    doencas, (SELECT 
                                                            doencas_id, favorito 
                                                    FROM 
                                                            medico_has_doencas 
                                                    WHERE 
                                                            medico_id=$medico_id) as m
                            WHERE
                                    doencas.id=m.doencas_id
                                    AND
                                    doencas.nome LIKE '%$filtro%'
                            ORDER BY m.favorito DESC, doencas.nome
                            LIMIT 100
            ";
    }
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function jsonRemoveUnicodeSequences($struct) {
    return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", saidaJson($struct));
}

//Atualisa a hipotese diagnostica para determinada consulta
function atualizaHipoteseConsulta($idc, $hipotese) {
    mysqli_set_charset(banco::$connection, 'utf8');
    ;
    $sql = "UPDATE `consultas` SET hipotese_diag='$hipotese' WHERE id = $idc";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function marcadesmarcaFavoritoHipotese($idd, $idm, $val) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `medico_has_doencas` SET favorito=$val WHERE doencas_id = $idd AND medico_id = $idm";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function inserirHipoteseDoenca($cod, $texto, $idm) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO `doencas` (codigo, nome) VALUES('$cod', '$texto')";
    if (mysqli_query(banco::$connection, $sql)) {
        $sql = "INSERT INTO `medico_has_doencas` (medico_id, doencas_id) VALUES($idm, (SELECT MAX(id) FROM `doencas`))";
        if (mysqli_query(banco::$connection, $sql))
            return true;
        else
            return false;
    } else
        return false;
}

function inserirHipoteseMedicoDoenca($idd, $idm) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO `medico_has_doencas` (medico_id, doencas_id) VALUES($idm, $idd)";
    if (mysqli_query(banco::$connection, $sql)) {
        return true;
    } else
        return false;
}

function editarHipoteseDoenca($id, $cod, $texto) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `doencas` SET  nome='$texto', codigo='$cod' WHERE id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function removerHipoteseMedicoDoenca($id, $idm) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "DELETE FROM `medico_has_doencas` WHERE medico_id = $idm AND doencas_id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function removerHipoteseDoenca($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "DELETE FROM `doencas` WHERE id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function buscaHipoteseDiagnostica($medico_id, $filtro) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar("hipotese_diagnostica", "*", "medico_respons_id = '$medico_id' AND descricao LIKE '%$filtro%' ");
    return $array;
}

function buscaTextoPrescricao($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("prescricoes", "*", "id = $id");
    return $array;
}

function buscaTextoRecomendacao($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = ver("recomendacoes", "*", "id = $id");
    return $array;
}

function buscaAlegenos($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * FROM alergeno WHERE testes_id=$id";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $arr['nomes'][] = $linha['nome'];
        $arr['totalDados'][] = $linha;
    }
    return $arr;
}

function insereNovaPrescricao($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('prescricoes', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereFaturaConvenio($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('fatura_convenio', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereFatura($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('fatura', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereDuplicata($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('duplicata', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereFaturaParcelas($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('fatura_parcelas', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereDuplicataParcelas($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('duplicata_parcelas', $dados)) {
        return true;
    } else {
        return false;
    }
}

function deletePrescricao($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    if (remover("prescricoes", "id = $id")) {
        return true;
    } else {
        return false;
    }
}

function buscaConsultaHasRequisicaoServico($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * "
            . "FROM consulta_has_requisicao_servico, (SELECT * "
            . "                                         FROM prestador) as p "
            . "   WHERE cliente_id = $id AND consulta_has_requisicao_servico.prestador_id = p.id";
    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function buscaConsultaHasRequisicaoServicoPorPrestador($idc, $idp, $data) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * "
            . "FROM consulta_has_requisicao_servico, (SELECT * "
            . "                                         FROM prestador) as p "
            . "   WHERE consulta_has_requisicao_servico.cliente_id = $idc "
            . "             AND consulta_has_requisicao_servico.prestador_id = $idp "
            . "             AND p.id = $idp"
            . "             AND data = '$data'";
    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function insereServicoPrestador($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "INSERT INTO consulta_has_requisicao_servico VALUES(" .
            $dados['cliente_id'] . ",'" . $dados['data'] . "'," . $dados['prestador_id'] . ",'" . $dados['encaminhamento'] . "')";
    return mysqli_query(banco::$connection, $sql);
//	if (inserir('consulta_has_requisicao_servico', $dados)) {
//		return true;
//	} else {
//		return false;
//	}
}

function deletaServicoPrestador($string) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "DELETE FROM consulta_has_requisicao_servico WHERE $string";
//if (remover("consulta_has_requisicao_servico", "$string")) {
    if (mysqli_query(banco::$connection, $sql)) {
        return true;
    } else {
        return false;
    }
}

function buscaServicoPrestador($cliente_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $array = listar("consulta_has_requisicao_servico", "*", "cliente_id = '$cliente_id'");
    return $array;
}

function buscaServicosFilaCaixa($id) {
    $sql = "SELECT
	 d.fk_titular_id as 'responsavel',  cli.matricula as 'responsavel_matricula',
cli.medico_id as 'responsavel_medico_id', CONCAT(pes1.nome,' ',pes1.sobrenome) as 'responsavel_nome'  ,
pes1.data_nascimento as 'responsavel_nascimento',d.dependente_id as 'cliente',cli2.matricula as 'cliente_matricula',cli2.membro as 'cliente_membro',
cli2.medico_id as 'cliente_medico_id', CONCAT(pes2.nome,' ',pes2.sobrenome) as 'cliente_nome',pes2.data_nascimento as 'cliente_nascimento',
d.vacina_casa as 'vacina_casa',con.guia_controle_id as 'id_controle',con.numero_controle as 'numero_controle',guia.numero_controle as 'numero_guia',con.`data` as 'controle_data',con.hora as 'controle_hora',con.material_id as 'controle_material', con.modulo as 'controle_modulo',con.`status` as 'controle_status', guia.convenio_id as 'convenio_guia',guia.`data` as 'data_guia',guia.operador as 'operador',guia.fila_id as 'numero_fila',
mat.descricao as 'material_descricao',mat.nome as 'material_nome',mat.preco as 'material_preco_vista', mat.preco_cartao as 'material_preco_cartao',mat.preco_por_dose as 'material_preco_dose'
FROM
	cliente c
INNER JOIN dependente d ON c.cliente_id = d.fk_titular_id
INNER JOIN controle AS con ON con.cliente_id = d.dependente_id INNER JOIN
(
	SELECT
		g.id as 'GuiaControle_ID',
		g.titular_id,
		g.convenio_id,
		g.finalizado,
		g.`data`,
		g.numero_controle,
		f.id AS 'fila_id',
		f.data as 'fila_data',
		f.operador_id AS 'operador'
	FROM
		guia_controle g
	INNER JOIN fila_espera_caixa f ON f.cliente_cliente_id = g.titular_id AND f.finalizado = '0' 
) AS guia ON guia.titular_id =  d.fk_titular_id INNER JOIN cliente as cli ON cli.cliente_id =  d.fk_titular_id INNER JOIN cliente as cli2 ON cli2.cliente_id = d.dependente_id  INNER JOIN pessoa pes1 ON pes1.id = d.fk_titular_id INNER JOIN pessoa pes2 ON pes2.id =  d.dependente_id
INNER JOIN material mat ON mat.id = con.material_id
WHERE
	guia.finalizado = 0 AND guia.numero_controle = $id
GROUP BY 
	guia.GuiaControle_ID;"; //ADICIONEI O GROUP BY POR MINHA CONTA!!! CORTINHAS FAVOR VERIFICAR!
    return pesquisaOOP($sql);
}

/**
 * @description Funcao para pegar o preco do Material. Pode pegar
 * o preço de cartão ou o preço a vista de acordo o parametro passado.
 * 
 * @author Bruno Haick
 * 
 * @param type $idMat
 * @param type $tipoPreco (1 - preço cartão / 2 - preço a vista / 3 - os dois)
 * 
 * @return mix Array/Float preço
 */
function buscaPrecoMaterial($idMat, $tipoPreco) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if ($tipoPreco == 1) {
        $campo = "preco_cartao AS preco";
    } else if ($tipoPreco == 2) {
        $campo = "preco";
    } else if ($tipoPreco == 3) {
        $campo = "preco, preco_cartao";
    }

    $sql = "SELECT
				$campo
			FROM
				material
			WHERE
				id = '$idMat'
	";
    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

    return $linha;
}

function buscaPrecoVacImu($id, $tipo) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $campo = ($tipo == 'avista') ? 'preco' : 'preco_cartao';
    $sql = "
		SELECT
			material.$campo
		FROM
			servico,material
		WHERE
			servico.material_id = material.id
			AND
			servico.id=$id
	";
    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);

//	return $sql;
    return $linha[$campo];
}

function insereTesteCutaneo($teste, $data, $cliente) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $dados['testes_id'] = $teste;
    $dados['data'] = $data;
    $dados['cliente_cliente_id'] = $cliente;
//       die(print_r($dados));
    inserir('testes_realizados', $dados);
    $ultimoId = mysqli_insert_id(banco::$connection);

    return $ultimoId;
}

function buscaAlergeno($nome, $idTeste) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			*
		FROM
			alergeno
		WHERE
			testes_id=$idTeste
			AND
			nome='$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($query);


    return $linha['id'];
}

function insereResultadoTeste($idTesteRealizado, $alergenoId, $resultado) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $dados['testes_realizados_id'] = $idTesteRealizado;
    $dados['alergeno_id'] = $alergenoId;
    $dados['resultado'] = $resultado;
//die(print_r($dados));//OK by Luiz Cortinhas
    inserir('testes_resultados', $dados);
}

function insereFormaPagamento($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');
    remover('fila_espera_caixa_has_forma_pagamento', 'forma_pagamento_id=' . $dados['forma_pagamento_id']);
    if (inserir('fila_espera_caixa_has_forma_pagamento', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereAtividadesCaixa($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('atividades_caixa', $dados)) {
        return true;
    } else {
        return false;
    }
}

function insereFilaEspera($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('fila_espera_caixa', $dados)) {
        return true;
    } else {
        return false;
    }
}

function inserePagamentoCartao($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    if (inserir('fila_espera_caixa_has_cartao_bandeiras', $dados)) {
        return true;
    } else {
        return false;
    }
}

function atualizaFilaCaixa($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('fila_espera_caixa', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function atualizaPrescricao($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('prescricoes', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function atualizaStatusFatura($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('fatura', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function atualizaStatusDuplicata($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('duplicata', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function atualizaDataParcelaDuplicata($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('duplicata_parcelas', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function buscaCategoriaTransacaoCaixaPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				id
			FROM
				categoria_transacao_caixa
			WHERE
				nome = '$nome'
	";

    $query = mysqli_query(banco::$connection, $sql);

    $categoria = mysqli_fetch_row($query);

    return $categoria[0];
}

/*
 * Função que busca os serviços pertecentes a guia_controle
 * ordenados pelo nome do cliente.
 * 
 * @author Bruno Haick
 * 
 * @param id da guia de controle
 * 
 * @return Array com todos os serviços
 */

function carregaServicosResumoCaixaPorGuia($id) {

    $sql = "SELECT 
				g.*, s.*,if(s.tipo_servico = 0,m.preco_por_dose,m.preco) as 'servico_preco',
				if(s.tipo_servico = 0,m.preco_por_dose,m.preco_cartao) as 'servico_preco_cartao',
				t.nome as 'statusNome', cl.membro, d.descontoBCG , d.descontoMedico, d.descontoPromocional, 
				m.nome, m.preco, CONCAT_WS(' ',p.nome,p.sobrenome) as 'Nome Dependente', 
				IF(s.modulo_has_material_id is null,'0','1') as 'Modulo',c.forma_pagamento
			FROM 
				`guia_controle` g 
				INNER JOIN `controle` c ON g.id = c.guia_controle_id 
				INNER JOIN `servico` s ON s.id = c.servico_id 
				INNER JOIN `material` m ON m.id = s.material_id
				INNER JOIN `pessoa` p ON p.id = s.cliente_cliente_id
				INNER JOIN `cliente` cl ON cl.cliente_id = p.id
				INNER JOIN `status` t ON t.id = s.status_id
				LEFT JOIN `modulos_has_material` o ON o.id = s.modulo_has_material_id
				LEFT JOIN  `modulos` d ON d.id = o.modulos_id
			WHERE 
				g.id = '$id'
			ORDER BY 
				s.cliente_cliente_id
	";
//	die($sql);
    Database::query($sql);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

function carregaResumoCaixa($dataInicio, $dataFim) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				g.*,r.numero_nfse, c.matricula, fp.nome as 'forma_pagamento',
				IF(fp.id = '5',d.nome,fp.nome) as 'nome_forma_pagamento',
				CONCAT_WS(' ',p.nome, p.sobrenome) as 'nomeTitular',
				IF(g.usuario_caixa_id != 'NULL',u.login,'') as 'nomeOperador', n.nome as 'categoria'
			FROM
				guia_controle g
				INNER JOIN pessoa p ON g.titular_id = p.id
				LEFT JOIN usuario u ON u.usuario_id = g.usuario_caixa_id
				INNER JOIN cliente c ON c.cliente_id = p.id
				INNER JOIN guia_controle_has_forma_pagamento f ON f.guia_controle_id = g.id
				INNER JOIN forma_pagamento fp ON fp.id = f.forma_pagamento_id
				INNER JOIN titular t ON t.titular_id = g.titular_id 
				INNER JOIN convenio n ON n.id = g.convenio_id 
				LEFT JOIN cartao_bandeiras d ON d.id = f.bandeira_id
				LEFT JOIN `rps` r ON g.id = r.guia_controle_id
			WHERE
				g.data BETWEEN '$dataInicio' AND '$dataFim'
				AND
				g.finalizado = '1'
			GROUP BY
				g.id
			ORDER BY
				g.numero_controle
	";

//    die($sql);
    Database::query($sql);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

function arrayUtf8Enconde(array $array) {
    $novo = array();
    foreach ($array as $i => $value) {
        if (is_array($value)) {
            $value = arrayUtf8Enconde($value);
        } elseif (!mb_check_encoding($value, 'UTF-8')) {//se não for array, verifica se o valor está codificado como UTF-8
            $value = utf8_encode($value);
        }
        $novo[$i] = $value;
    }
    return $novo;
}

function carregaResumoBandeirasPorGuiaId($guia_id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				IF(fp.id = '5',d.nome,fp.nome) AS 'nome_forma_pagamento',
				f.valor,
				f.parcelas
			FROM
				guia_controle g
				INNER JOIN guia_controle_has_forma_pagamento f ON f.guia_controle_id = g.id
				INNER JOIN forma_pagamento fp ON fp.id = f.forma_pagamento_id
				LEFT JOIN cartao_bandeiras d ON d.id = f.bandeira_id
			WHERE
				g.id = '$guia_id'";

//die($sql);
    Database::query($sql);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

function carregaResumoCaixa_2($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				SUM(g.valor) as valor, g.forma_pagamento_id
			FROM
				guia_controle_has_forma_pagamento g INNER JOIN forma_pagamento f
					ON f.id = g.forma_pagamento_id
			WHERE
				g.guia_controle_id = '$id'"
            . "GROUP BY g.guia_controle_id,g.forma_pagamento_id";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaHistoricoImunoterapia($idCliente, $idImuno) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			material.nome,
			material.qtd_ml_por_dose,
			historico.data,
			historico.qtd_ml,
			servico.id,
			servico.qtd_doses,
			status.nome as status
		FROM
			servico,
			historico,
			status,
			material 
		WHERE
			status.id = historico.status_id
			AND
			historico.status_id = 7
			AND
			historico.servico_id = servico.id
			AND
			material.tipo_material_id = 2
			AND
			material.id = servico.material_id 
			AND
			cliente_cliente_id = $idCliente
			AND
			material.id = $idImuno
			";
//	echo $sql;

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaHistoricoImunoterapiaFicha($idServico) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			material.nome,
			material.qtd_ml_por_dose,
			historico.data,
			historico.qtd_ml,
			servico.id,
			servico.qtd_doses,
			status.nome as status
		FROM
			servico,
			historico,
			status,
			material 
		WHERE
			status.id = historico.status_id
			AND
			historico.status_id = 7
			AND
			historico.servico_id = servico.id
			AND
			material.tipo_material_id = 2
			AND
			material.id = servico.material_id 
			AND
			servico.id = $idServico
		";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function carregaResumoCaixa_3($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			pessoa.nome as nomeCliente,
			pessoa.sobrenome as sobrenomeCliente,
			categoria_transacao_caixa.nome as servico,
			atividades_caixa.valor
		FROM
			pessoa,
			atividades_caixa,
			categoria_transacao_caixa
		WHERE
			atividades_caixa.fila_espera_caixa_id = $id
		AND
			atividades_caixa.cliente_id = pessoa.id
		AND
			atividades_caixa.categoria_transacao_caixa_id = categoria_transacao_caixa.id
		";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function carregaResumoCaixa_4($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			fila_espera_caixa_has_cartao_bandeiras.valor,
			cartao_bandeiras.nome
		FROM
			fila_espera_caixa_has_cartao_bandeiras,
			cartao_bandeiras
		WHERE
			fila_espera_caixa_has_cartao_bandeiras.fila_espera_caixa_id = $id
		AND
			 fila_espera_caixa_has_cartao_bandeiras.cartao_bandeiras_id = cartao_bandeiras.id
		";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 * 
 * @author Luiz Curtinhas
 * 
 * @param type $data_inicio
 * @param type $data_fim
 * @return Array
 */
function pegaVacinasPagasCaixa($data_inicio, $data_fim, $tipo) {

	$periodo = DiffDatetoArray($data_inicio, $data_fim);
    mysqli_set_charset(banco::$connection, 'utf8');
	if($tipo == 3) {
		$str_tipo_material = "(material.tipo_material_id = '3' OR material.tipo_material_id = '5')";
	} else {
		$str_tipo_material = "material.tipo_material_id = '$tipo'";		
	}
	$i = 0;
	foreach ($periodo as $mes) {
		$sql = "select a.gid,a.id,a.nome,sum(a.valor) as 'valor',COUNT(*) as 'qtd',a.mes,a.ano from (
			SELECT 
				@a1:= guia_controle.id as 'gid',
				material.id as 'id',
				material.nome_comum as 'nome',
				material.nome_comum as 'nome_comum',
				IF(s.tipo_servico = '0',material.preco_por_dose,
					IF((SELECT IF(SUM(forma_pagamento_id) is null,1,SUM(forma_pagamento_id)) 
						FROM guia_controle_has_forma_pagamento 
						WHERE 
							guia_controle_id = @a1 
							AND valor != '0.00' 
							AND bandeira_id NOT IN (16,15,17)) IN (1,0)
							AND controle.modulo ='0', material.preco, material.preco_cartao
					)) as 'valor',
				MONTH(controle.data) as 'mes',
				YEAR(controle.data) as 'ano'
			FROM
				controle
				INNER JOIN material ON controle.material_id = material.id
				INNER JOIN guia_controle ON controle.guia_controle_id = guia_controle.id
				INNER JOIN servico s ON controle.servico_id = s.id
			WHERE
				guia_controle.data BETWEEN '$data_inicio' AND '$data_fim'
				AND $str_tipo_material 
				AND (s.status_id = '15' OR s.status_id = '26' OR s.status_id = '25')
				AND guia_controle.finalizado = '1'

			)a GROUP BY a.nome_comum
		";

		$query = mysqli_query(banco::$connection, $sql);
		if (mysqli_num_rows($query)) {
			while ($linha = mysqli_fetch_assoc($query)) {
				$array[$i][] = $linha;
			}
		} else {
			$falsoPositivo['id'] = 0;
			$falsoPositivo['nome'] = '';
			$falsoPositivo['valor'] = 0;
			$falsoPositivo['mes'] = 0;
			$falsoPositivo['ano'] = 0;
			$array[$i][] = $falsoPositivo;
		}
		$i++;
	}
    return $array;
}

function pegaVacinasPagasCaixaImunoterapia($data_inicio, $data_fim) {
    $periodo = DiffDatetoArray($data_inicio, $data_fim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $vacinas = todasVacinas();
    foreach ($vacinas as $vacina) {
        $i = 0;
        $id = $vacina['id'];
        foreach ($periodo as $mes) {
            $sql = "SELECT
                        material.id as 'id',
                        material.nome as 'nome',
                        SUM(material.preco) as 'valor',
                        COUNT(*) as 'qtd',
                        MONTH(servico.data) as 'mes',
                        YEAR(servico.data) as 'ano'
                FROM
                        controle
                        INNER JOIN	servico ON controle.servico_id = servico.id
                        INNER JOIN material ON servico.material_id
                WHERE
                        servico.data LIKE '$mes%'
                        AND	servico.finalizado = '1'
                        AND	servico.status_id = '15'
                        AND material.id = '$id'
                        AND material.tipo_material_id = '2'
                GROUP BY
                        material.nome
            ";
            $query = mysqli_query(banco::$connection, $sql);
            if (mysqli_num_rows($query)) {
                while ($linha = mysqli_fetch_assoc($query)) {
                    $array[$i][] = $linha;
                }
            } else {
                $falsoPositivo['id'] = 0;
                $falsoPositivo['nome'] = '';
                $falsoPositivo['valor'] = 0;
                $falsoPositivo['mes'] = 0;
                $falsoPositivo['ano'] = 0;
                $array[$i][] = $falsoPositivo;
            }
            $i++;
        }
        return $array;
    }
}

function pegaVacinasPagasCaixaTestes($data_inicio, $data_fim) {
    $periodo = DiffDatetoArray($data_inicio, $data_fim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $vacinas = todasVacinas();
    foreach ($vacinas as $vacina) {
        $i = 0;
        $id = $vacina['id'];
        foreach ($periodo as $mes) {
            $sql = "SELECT
					material.id as 'id',
					material.nome as 'nome',
					SUM(material.preco) as 'valor',
					COUNT(*) as 'qtd',
					MONTH(servico.data) as 'mes',
					YEAR(servico.data) as 'ano'
				FROM
					controle
					INNER JOIN	servico ON controle.servico_id = servico.id
					INNER JOIN material ON servico.material_id
				WHERE
					servico.data LIKE '$mes%'
					AND	servico.finalizado = '1'
					AND	servico.status_id = '15'
					AND material.id = '$id'
					AND material.tipo_material_id = '3'
				GROUP BY
					material.nome
		";
            $query = mysqli_query(banco::$connection, $sql);
            if (mysqli_num_rows($query)) {
                while ($linha = mysqli_fetch_assoc($query)) {
                    $array[$i][] = $linha;
                }
            } else {
                $falsoPositivo['id'] = 0;
                $falsoPositivo['nome'] = '';
                $falsoPositivo['valor'] = 0;
                $falsoPositivo['mes'] = 0;
                $falsoPositivo['ano'] = 0;
                $array[$i][] = $falsoPositivo;
            }
            $i++;
        }
        return $array;
    }
}

function pegaVacinasPagasCaixaTotal($data_inicio, $data_fim, $tipo) {
    $periodo = DiffDatetoArray($data_inicio, $data_fim);
    mysqli_set_charset(banco::$connection, 'utf8');

    foreach ($periodo as $mes) {
//		$sql ="SELECT sum(a.valor) as 'valor',count(*) as 'qtd', a.mes as 'mes',a.ano as 'ano' FROM (select a.gid,a.id,a.nome,sum(a.valor) as 'valor',COUNT(*) as 'qtd',a.mes as 'mes',a.ano as 'ano' from (
//			SELECT 
//				@a1:= guia_controle.id as 'gid',
//				material.id as 'id',
//				material.nome_comum as 'nome',
//				material.nome_comum as 'nome_comum',
//				IF(s.tipo_servico = '0',material.preco_por_dose,
//					IF((SELECT IF(SUM(forma_pagamento_id) is null,1,SUM(forma_pagamento_id)) 
//						FROM guia_controle_has_forma_pagamento 
//						WHERE 
//							guia_controle_id = @a1 
//							AND valor != '0.00' 
//							AND bandeira_id NOT IN (16,15,17)) IN (1,0)
//							AND controle.modulo ='0', material.preco, material.preco_cartao
//					)) as 'valor',
//				MONTH(controle.data) as 'mes',
//				YEAR(controle.data) as 'ano'
//			FROM
//				controle
//				INNER JOIN material ON controle.material_id = material.id
//				INNER JOIN guia_controle ON controle.guia_controle_id = guia_controle.id
//				INNER JOIN servico s ON controle.servico_id = s.id
//			WHERE
//				guia_controle.data BETWEEN '$data_inicio' AND '$data_fim'
//				and guia_controle.data = '$mes'
//				AND (s.status_id = '15' OR s.status_id = '26' OR s.status_id = '25')
//				AND guia_controle.finalizado = '1'
//			)a ";
//		
        $sql = "SELECT
					SUM(material.preco) as 'valor',
					COUNT(*) as 'qtd',
					MONTH(servico.data) as 'mes',
					YEAR(servico.data) as 'ano'
				FROM
					controle
					INNER JOIN	servico ON controle.servico_id = servico.id
					INNER JOIN material ON servico.material_id
					INNER JOIN guia_controle ON guia_controle.id = controle.guia_controle_id
				WHERE
					guia_controle.data BETWEEN '$data_inicio' AND '$data_fim'
					AND guia_controle.data LIKE '$mes%'
					AND guia_controle.finalizado = '1'
					AND	servico.status_id = '15'
					AND (
						material.tipo_material_id = '3'
						OR material.tipo_material_id = '2'
						OR material.tipo_material_id = '$tipo'
					)
				GROUP BY
					MONTH(servico.data)
		";
//		die($sql);
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query)) {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $array[]['valor'] = 0;
            $array[]['qtd'] = 0;
            $array[]['mes'] = 0;
            $array[]['ano'] = 0;
        }
    }
    return $array;
}

/**
 * Função que busca todas as vacinas Realizadas, em um determinado periodo
 * Para cada vacina verifica cada mes do periodo especificado, se não houver
 * vacina realizada no mes, eh adicionado valor 0 para que o array tenha numero
 * de indices igual para todas as vacinas.
 * 
 * @author Luiz Curtinhas
 * 
 * @param type $data_inicio
 * @param type $data_fim
 * @return Array
 * 
 */
function pegaVacinasRealizadas($data_inicio, $data_fim, $tipo) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $periodo = DiffDatetoArray($data_inicio, $data_fim);

    if ($tipo == 1)
        $vacinas = todasVacinas();
    else if ($tipo == 2){
        $vacinas = todasImunoterapias();
        //die(print_r($vacinas));
        }
    else if ($tipo == 3)
        $vacinas = todosTeste();

    foreach ($vacinas as $vacina) {
        $i = 0;
        $id = $vacina['id'];
        foreach ($periodo as $mes) {
            $sql = "		(SELECT 
					material.codigo, 
					material.nome, 
 					@a1:= g.id,
					IF((SELECT IF(SUM(forma_pagamento_id) is null,1,SUM(forma_pagamento_id)) 
						FROM guia_controle_has_forma_pagamento 
						WHERE 
							guia_controle_id = @a1 
							AND valor != '0.00' 
							AND bandeira_id NOT IN (16,15,17)) IN (1,0)                        
                        ,SUM(material.preco),SUM(material.preco_cartao))  AS valor,

				    COUNT(material.nome) as media,
					COUNT( * ) AS qtd, DAY( controle.data ) AS dia,
					MONTH( controle.data ) AS mes,
					YEAR( controle.data ) AS ano
				FROM 
					material
					INNER JOIN servico ON material.id = servico.material_id
					INNER JOIN tipo_material ON tipo_material.id = material.tipo_material_id
					INNER JOIN historico h ON h.servico_id = servico.id
					INNER JOIN controle ON controle.servico_id = servico.id
                    INNER JOIN guia_controle g ON controle.guia_controle_id = g.id
				WHERE 
					controle.data LIKE  '$mes%'
                    AND controle.data BETWEEN '$data_inicio' AND '$data_fim'
					AND h.status_id = '1' 
					AND material.tipo_material_id = '$tipo'
					AND material.id = '$id'
                    AND controle.modulo = '0'
				GROUP BY
					material.nome_comum,
					MONTH( servico.data ) 
				ORDER BY
					ano,
					material.nome )"
                    . " UNION "
                    . "(SELECT 
                                                material.codigo, 
                				material.nome, 
                                                @a1:= g.id,
                        			SUM(servico.preco)  AS valor,
                                    		SUM(servico.preco)/COUNT(material.nome) as media,
                                        	COUNT( * ) AS qtd, DAY( controle.data ) AS dia,
                                                MONTH( controle.data ) AS mes,
            					YEAR( controle.data ) AS ano 
                                        FROM 
                                                material
                                                INNER JOIN servico ON material.id = servico.material_id
                                        	    INNER JOIN tipo_material ON tipo_material.id = material.tipo_material_id
                                		        INNER JOIN historico h ON h.servico_id = servico.id
                        			            INNER JOIN controle ON controle.servico_id = servico.id
                                                INNER JOIN guia_controle g ON controle.guia_controle_id = g.id
                                                INNER JOIN modulos_has_material m ON m.id = servico.modulo_has_material_id
                                        WHERE 
                                                controle.data LIKE  '$mes%'
                                                AND controle.data BETWEEN '$data_inicio' AND '$data_fim'
                                                AND h.status_id = '1' 
                                                AND material.tipo_material_id = '$tipo'
                                        	    AND material.id = '$id'
                                                AND controle.modulo = '1'
                                        GROUP BY
                            			material.nome_comum,
                				MONTH( servico.data ) 
            				ORDER BY
                				ano,
            					material.nome )";
                                
            $query = banco::$connection->query($sql);
            mysqli_free_result($query);
            $query = banco::$connection->query($sql);
             if($id == '388')
                die(print_r($sql));
            if (mysqli_num_rows($query) > '0') {
                
                while ($linha = mysqli_fetch_assoc($query)) {
                    //var_dump($linha);die(" morreu aqui");
                    $array[$i][] = $linha;

                }
            } else {
                $ano_mes = explode('-', $mes);
                $falsoPositivo['codigo'] = '';
                $falsoPositivo['nome'] = '';
                $falsoPositivo['valor'] = '0';
                $falsoPositivo['media'] = '0';
                $falsoPositivo['qtd'] = '0';
                $falsoPositivo['dia'] = '';
                $falsoPositivo['mes'] = intval($ano_mes[1]);
                $falsoPositivo['ano'] = $ano_mes[0];
                $array[$i][] = $falsoPositivo;
            }
            $i++;
        }
    }

    return $array;
}

/**
 * Funcção utilizada para trazer o total 
 * 
 * @param type $data_inicio
 * @param type $data_fim
 * @return string
 */
function pegaVacinasRealizadasTotal($data_inicio, $data_fim, $tipo) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $periodo = DiffDatetoArray($data_inicio, $data_fim);

    foreach ($periodo as $mes) {
        $sql = "SELECT
					SUM(material.preco) AS valor,
					SUM(material.preco) / COUNT(material.nome) AS media,
					COUNT(*) AS qtd,
					DAY (controle. DATA) AS dia,
					MONTH (controle. DATA) AS mes,
					YEAR (controle. DATA) AS ano
				FROM
					material
					INNER JOIN servico ON material.id = servico.material_id
					INNER JOIN tipo_material ON tipo_material.id = material.tipo_material_id
					INNER JOIN historico h ON h.servico_id = servico.id
					INNER JOIN controle ON controle.servico_id = servico.id
					
				WHERE
					controle.data LIKE '$mes%'
					AND h.status_id = 1
					AND material.tipo_material_id = '$tipo'
				GROUP BY
					YEAR (servico. DATA),
					MONTH (servico. DATA)
		";
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = $mes;
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['media'] = '0';
            $falsoPositivo['qtd'] = '0';
            $array[] = $falsoPositivo;
        }
    }

    return $array;
}

function ACL($id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
			modulos_sistema.modulo
		FROM
			usuario,
			tipo_usuario,
			tipo_usuario_has_modulos_sistema,
			modulos_sistema
		WHERE
			usuario.tipo_usuario_id = tipo_usuario.id
		AND
			tipo_usuario_has_modulos_sistema.tipo_usuario_id = tipo_usuario.id
		AND
			modulos_sistema.id = tipo_usuario_has_modulos_sistema.modulos_sistema_id
		AND
			usuario.usuario_id = '$id'
		";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function buscaCPFDependente($matricula) {
    
}

/*
 * Função para buscar as Notas Fiscais ainda não enviadas ao
 * webservice da prefeitura.
 * @author Bruno Haick
 * return Array com as notas não enviadas
 */

function buscaNotasNaoEnviadas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				c.matricula,
				c.membro,
				g.numero_controle,
				r.id as rps_id,
				r.*
			FROM
				guia_controle g
				INNER JOIN rps r ON r.guia_controle_id = g.id
				INNER JOIN `cliente` c ON c.cliente_id = g.titular_id
			WHERE
				r.enviado = 0
			GROUP BY g.id
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $arr[] = $linha;
    }

    return $arr;
}

/*
 * @description Função para buscar as Notas Fiscais ainda não
 * enviadas ao webservice da prefeitura, por Id de RPS.
 * 
 * @author Bruno Haick & Luiz Cortinhas
 * 
 * return Array com as notas não enviadas
 */

function buscaNotasporId($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				c.matricula,
				c.membro,
				g.numero_controle,
				r.*
			FROM
				guia_controle g
				INNER JOIN rps r
				ON r.guia_controle_id = g.id
				INNER JOIN `cliente` c
				ON c.cliente_id = g.titular_id
			WHERE
				r.id = '$id'";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    return $arr;
}

/**
 * Busca na tabela plano contas o numero de plano de 
 * contas na camada mais geral. Para isso busca-se no 
 * banco o primeiro caracter do codigo de cada registro, 
 * com distinct, e então retorna somente o ultimo rregistro.
 * Ex: 1 - Receitas, 2 - Despesas , etc...
 * 
 * @author Bruno Haick
 * @date Criação: 25/04/2013
 * @return ultimo codigo geral.
 * 
 */
function buscaQtdPlanoContasGeral() {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT DISTINCT
				LEFT(codigo, 1) AS codigo
			FROM 
				plano_contas
			ORDER BY
				codigo DESC
			LIMIT 0,1
	";

    $query = mysqli_query(banco::$connection, $sql);

    $qtd = mysqli_fetch_row($query);

    return $qtd[0];
}

/**
 *
 * Calcula o valor total de contas a receber de todos os planos e contas
 * para um determinado periodo, para Duplicata
 *
 * @author Bruno Haick
 * @date Criação: 29/04/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasPorMesPorPeriodoDuplicata($dataInicio, $dataFim) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
//    die(print_r($entreperiodos));
    foreach ($entreperiodos as $MesPesquisa) {
//        echo $MesPesquisa;
        $sql = "SELECT (IF(a.valor IS NULL,0,a.valor)+IF(b.valor IS NULL,0,b.valor)) as valor, CONCAT(YEAR(a.data),'-',
                                IF(MONTH(a.data)<10,
                                    CONCAT('0',MONTH(a.data)),
                                    MONTH(a.data))
                                ) as mes
				FROM 
				(SELECT
					duplicata_parcelas.data_baixa as 'data',
					SUM(duplicata_parcelas.valor) as valor
				FROM
					plano_contas 
                 			INNER JOIN duplicata ON duplicata.plano_contas_id = plano_contas.id INNER JOIN duplicata_parcelas ON duplicata.id = duplicata_parcelas.duplicata_id
				WHERE
					plano_contas.codigo LIKE '2%'
					AND
					duplicata_parcelas.data_baixa LIKE '$MesPesquisa%'
				GROUP BY
					MONTH(duplicata_parcelas.data_baixa)) a 
LEFT JOIN 
		   (
    SELECT l.data as'data',SUM(l.valor) as 'valor' FROM plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id
	WHERE l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '2%' GROUP BY MONTH(l.data),YEAR(l.data)) b ON a.data = b.data
";
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = '0';
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}
/**
 *
 * Calcula o valor total de contas a receber de todos os planos e contas
 * para um determinado periodo, para Duplicata
 *
 * @author Bruno Haick
 * @date Criação: 29/04/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasDuplicataPADRAO($dataInicio, $dataFim) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
//    die(print_r($entreperiodos));
    foreach ($entreperiodos as $MesPesquisa) {
//        echo $MesPesquisa;
        $sql = "SELECT (IF(a.valor IS NULL,0,a.valor)+IF(b.valor IS NULL,0,b.valor)) as valor, CONCAT(YEAR(a.data),'-',
                                IF(MONTH(a.data)<10,
                                    CONCAT('0',MONTH(a.data)),
                                    MONTH(a.data))
                                ) as mes
				FROM 
				(SELECT
					duplicata_parcelas.data_baixa as 'data',
					SUM(duplicata_parcelas.valor) as valor
				FROM
					plano_contas 
                 			INNER JOIN duplicata ON duplicata.plano_contas_id = plano_contas.id INNER JOIN duplicata_parcelas ON duplicata.id = duplicata_parcelas.duplicata_id
				WHERE
					plano_contas.codigo LIKE '2%'
					AND
					duplicata_parcelas.data_baixa LIKE '$MesPesquisa%'
				GROUP BY
					MONTH(duplicata_parcelas.data_baixa)) a 
LEFT JOIN 
		   (
    SELECT l.data as'data',SUM(l.valor) as 'valor' FROM plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id
	WHERE l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '2%' GROUP BY MONTH(l.data),YEAR(l.data)) b ON a.data = b.data
";
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = '0';
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber de todos os planos e contas
 * para um determinado periodo, para convenio
 *
 * @author Bruno Haick
 * @date Criação: 26/04/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasPorMesPorPeriodoConvenio($dataInicio, $dataFim) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
//    die(print_r($entreperiodos));
    foreach ($entreperiodos as $MesPesquisa) {
//        echo $MesPesquisa;
        $sql = "SELECT
				CONCAT(YEAR(fatura_convenio.data_baixa),'-',
                                IF(MONTH(fatura_convenio.data_baixa)<10,
                                    CONCAT('0',MONTH(fatura_convenio.data_baixa)),
                                    MONTH(fatura_parcelas.data_baixa))
                                ) as mes,
				SUM(fatura_convenio.valor) as valor
			FROM
				plano_contas,fatura_convenio
			WHERE
				fatura_convenio.plano_contas_id = plano_contas.id
				AND
				plano_contas.codigo LIKE '%'
				AND
				fatura_convenio.data_baixa LIKE '$MesPesquisa%'
			GROUP BY
				MONTH(fatura_convenio.data_baixa)
                        ORDER BY fatura_parcelas.data_baixa ASC
	";
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = '0';
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber para lancamentos para um determinado periodo
 *
 * @author Bruno Haick
 * @date Criação: 06/05/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasPorMesPorPeriodoLancamentos($dataInicio, $dataFim, $tipo) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				MONTH(lancamentos.data) as mes,
				SUM(lancamentos.valor) as valor
			FROM
				lancamentos,tipo_operacao
			WHERE
				tipo_operacao.id = lancamentos.tipo_operacao_id
				AND
				tipo_operacao.e_s = '$tipo'
				AND
				lancamentos.data BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				MONTH(lancamentos.data)
	";

//echo $sql;

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber de todos os planos e contas
 * para um determinado periodo, 
 *
 * @author Bruno Haick
 * @date Criação: 26/04/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasPorMesPorPeriodoFatPadrao($dataInicio, $dataFim) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    foreach ($entreperiodos as $MesPesquisa) {
        $sql = "
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM (SELECT fatura_parcelas.data_baixa  as 'data',
                    SUM(fatura_parcelas.valor) as 'soma'
              FROM
		    plano_contas 
              INNER JOIN 
              fatura 
                    ON fatura.plano_contas_id = plano_contas.id 
              INNER JOIN 
              fatura_parcelas 
                    ON fatura.id = fatura_parcelas.fatura_id
              WHERE
                    plano_contas.codigo LIKE '1%'
                    AND	fatura_parcelas.data_baixa LIKE '$MesPesquisa%'
              GROUP BY MONTH(fatura_parcelas.data_baixa )
              ORDER BY fatura_parcelas.data_baixa  ASC
        ) a 
        UNION ALL 
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM 
                    (SELECT l.data as'data',
                            SUM(l.valor) as 'soma' 
                     FROM plano_contas p INNER JOIN lancamentos l 
                            ON l.plano_contas_id = p.id 
                     WHERE l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '1%'
                     GROUP BY MONTH(l.data),YEAR(l.data)
        ) a 
        UNION ALL 
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM 
                    (SELECT fatura_convenio.data_baixa as 'data',	
                            SUM(fatura_convenio.valor_pago) as 'soma' 
                     FROM plano_contas,fatura_convenio 
                     WHERE fatura_convenio.plano_contas_id = plano_contas.id AND 
                           plano_contas.codigo LIKE '1%' AND fatura_convenio.data_baixa LIKE '$MesPesquisa%'
                     GROUP BY YEAR(fatura_convenio.data_baixa),MONTH(fatura_convenio.data_baixa)
        ) a ";
//       die($sql);
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = '0';
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}
/**
 *
 * Calcula o valor total de contas a receber de todos os planos e contas
 * para um determinado periodo, 
 *
 * @author Bruno Haick
 * @date Criação: 26/04/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasPADRAO($dataInicio, $dataFim) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    foreach ($entreperiodos as $MesPesquisa) {
        $sql = "
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM (SELECT fatura_parcelas.data_baixa  as 'data',
                    SUM(fatura_parcelas.valor) as 'soma'
              FROM
		    plano_contas 
              INNER JOIN 
              fatura 
                    ON fatura.plano_contas_id = plano_contas.id 
              INNER JOIN 
              fatura_parcelas 
                    ON fatura.id = fatura_parcelas.fatura_id
              WHERE
                    plano_contas.codigo LIKE '1%'
                    AND	fatura_parcelas.data_baixa LIKE '$MesPesquisa%'
              GROUP BY MONTH(fatura_parcelas.data_baixa )
              ORDER BY fatura_parcelas.data_baixa  ASC
        ) a 
        UNION ALL 
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM 
                    (SELECT l.data as'data',
                            SUM(l.valor) as 'soma' 
                     FROM plano_contas p INNER JOIN lancamentos l 
                            ON l.plano_contas_id = p.id 
                     WHERE l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '1%'
                     GROUP BY MONTH(l.data),YEAR(l.data)
        ) a 
        UNION ALL 
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM 
                    (SELECT fatura_convenio.data_baixa as 'data',	
                            SUM(fatura_convenio.valor_pago) as 'soma' 
                     FROM plano_contas,fatura_convenio 
                     WHERE fatura_convenio.plano_contas_id = plano_contas.id AND 
                           plano_contas.codigo LIKE '1%' AND fatura_convenio.data_baixa LIKE '$MesPesquisa%'
                     GROUP BY YEAR(fatura_convenio.data_baixa),MONTH(fatura_convenio.data_baixa)
        ) a ";
//       die($sql);
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = '0';
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber de todos os planos e contas
 * para um determinado periodo, 
 *
 * @author Bruno Haick
 * @date Criação: 26/04/2013
 *
 * @return
 * valor total por mes
 */
function buscaTotalPlanoContasPorMesPorPeriodoFat($dataInicio, $dataFim) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    foreach ($entreperiodos as $MesPesquisa) {
        $sql ="SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM (SELECT fatura.data_lancamento  as 'data',
                    SUM(fatura_parcelas.valor) as 'soma'
              FROM
		    plano_contas 
              INNER JOIN 
              fatura 
                    ON fatura.plano_contas_id = plano_contas.id 
              INNER JOIN 
              fatura_parcelas 
                    ON fatura.id = fatura_parcelas.fatura_id
              WHERE
                    plano_contas.codigo LIKE '1%'
                    AND	fatura.data_lancamento LIKE '$MesPesquisa%'
                    AND	fatura.data_lancamento between '$dataInicio' AND '$dataFim'
              GROUP BY MONTH(fatura.data_lancamento )
              ORDER BY fatura.data_lancamento  ASC
        ) a 
        UNION ALL 
        SELECT CONCAT(YEAR(a.data),'-',
               IF(MONTH(a.data)<10,
               CONCAT('0',MONTH(a.data)),
               MONTH(a.data))) as mes,
               IF(a.soma IS NULL,0,a.soma)  as 'valor'
	FROM 
                    (SELECT l.data as'data',
                            SUM(l.valor) as 'soma' 
                     FROM plano_contas p INNER JOIN lancamentos l 
                            ON l.plano_contas_id = p.id 
                     WHERE 
                         l.data LIKE '$MesPesquisa%' 
                         AND l.data between '$dataInicio' AND '$dataFim'
                         AND p.codigo LIKE '1%'
                     GROUP BY MONTH(l.data),YEAR(l.data)
        ) a 
        UNION ALL 
        SELECT a.mes as 'mes',SUM(a.valor) as 'valor'
           FROM (SELECT
				CONCAT_WS('-',YEAR(g.data), IF(MONTH(g.data)<10,
                                    CONCAT('0',MONTH(g.data)),
                                    MONTH(g.data))
                                ) as mes,f.valor as 'valor'
				FROM `guia_controle` g
                                INNER JOIN `guia_controle_has_forma_pagamento` f ON g.id = f.guia_controle_id
                                INNER JOIN `convenio`c ON g.convenio_id = c.id
                                INNER JOIN `plano_contas` p ON p.nome = c.nome
                                WHERE 
                                    f.forma_pagamento_id = '7' 
                                    AND g.data LIKE '$MesPesquisa%'
                                    AND g.data between '$dataInicio' AND '$dataFim'
                                    AND p.codigo LIKE '1.04%'
                                GROUP BY MONTH(g.data), g.numero_controle 
                   ) a
         UNION ALL 
         SELECT a.mes as 'mes',SUM(a.valor) as 'valor'
           FROM (SELECT
				CONCAT_WS('-',YEAR(g.data), IF(MONTH(g.data)<10,
                                    CONCAT('0',MONTH(g.data)),
                                    MONTH(g.data))
                                ) as mes,f.valor as 'valor'
				FROM `guia_controle` g
                                INNER JOIN `guia_controle_has_forma_pagamento` f ON g.id = f.guia_controle_id
                                INNER JOIN `convenio`c ON g.convenio_id = c.id
                                INNER JOIN `plano_contas` p ON p.nome = c.nome
                                WHERE 
                                    (f.forma_pagamento_id = '19'  )
                                    AND g.data LIKE '$MesPesquisa%'
                                    AND g.data between '$dataInicio' AND '$dataFim'
                                    GROUP BY MONTH(g.data), g.numero_controle 
                   ) a
         ";
       //die($sql);
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = '0';
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber para um determinado plano de contas dentro de um 
 * determinado periodo, plano contas deve ser referente a convenio.
 *
 * @author Bruno Haick
 * @date Criação: 29/04/2013
 *
 * MongoInt32@return
 */
function buscaTotalPorPlanoContasPorPeriodoConvenio($dataInicio, $dataFim, $codigo) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    if ($codigo == '1.04') {
        $codigo = $codigo . '%';
    }
    foreach ($entreperiodos as $MesPesquisa) {
        $sql = "SELECT a.mes as 'mes',SUM(a.valor) as 'valor',
            	(
			SUM(a.valor)/
			ROUND(IF(PERIOD_DIFF(
                	DATE_FORMAT('$dataFim','%Y%m'),
                        DATE_FORMAT('$dataInicio','%Y%m')
                        ) =0,'1',PERIOD_DIFF(
                        DATE_FORMAT('$dataFim','%Y%m'),
        		DATE_FORMAT('$dataInicio','%Y%m')
                )),2)
            ) as media FROM (SELECT
				CONCAT_WS('-',YEAR(g.data), IF(MONTH(g.data)<10,
                                    CONCAT('0',MONTH(g.data)),
                                    MONTH(g.data))
                                ) as mes,f.valor as 'valor'
				FROM `guia_controle` g
                                INNER JOIN `guia_controle_has_forma_pagamento` f ON g.id = f.guia_controle_id
                                INNER JOIN `convenio`c ON g.convenio_id = c.id
                                INNER JOIN `plano_contas` p ON p.nome = c.nome
                                WHERE 
                                    f.forma_pagamento_id = '7'  
                                    AND g.data LIKE '$MesPesquisa%'
                                    AND p.codigo LIKE '$codigo'
                                GROUP BY MONTH(g.data), g.numero_controle 
                                ) a";
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = $MesPesquisa;
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['media'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber para um determinado plano de contas dentro de um 
 * determinado periodo Em Duplicatas
 *
 * @author Bruno Haick
 * @date Criação: 26/04/2013
 *
 * MongoInt32@return
 */
function buscaTotalPorPlanoContasPorPeriodoDuplicata($dataInicio, $dataFim, $codigo) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    foreach ($entreperiodos as $MesPesquisa) {
        $sql = "SELECT * FROM
				(SELECT 
					(IF
						(a.valor is null,0,a.valor) + IF(b.valor is null,0,b.valor)) as 'valor',
					(
					ROUND(
						SUM(IF(a.valor is null,b.valor,a.valor))/
						PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						), 2
					)
				) as media,

				CONCAT(YEAR(a.mes),'-',
                                IF(MONTH(a.mes) < 10,
                                    CONCAT('0',MONTH(a.mes)),
                                    MONTH(a.mes))
                                ) as mes
			FROM
			(SELECT 
             	duplicata_parcelas.data_baixa mes,
				SUM(duplicata_parcelas.valor) as valor
             FROM
                plano_contas INNER JOIN duplicata ON duplicata.plano_contas_id = plano_contas.id
             	INNER JOIN duplicata_parcelas ON duplicata.id = duplicata_parcelas.duplicata_id
			WHERE
				plano_contas.codigo LIKE '$codigo%'
				AND
				duplicata_parcelas.data_baixa LIKE '$MesPesquisa%'
			GROUP BY
				MONTH(duplicata_parcelas.data_baixa))
			a LEFT JOIN (
				SELECT
					l.data as'data2',SUM(l.valor) as 'valor' 
				FROM
					plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id 
				WHERE
					l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '$codigo%' 
				GROUP BY
					MONTH(l.data),YEAR(l.data)) b ON b.data2 = a.mes) c
				UNION  ALL (
				SELECT 
					(IF(a.valor is null,0,a.valor) + IF(b.valor is null,0,b.valor)) as 'valor',
						(
							ROUND(
								SUM(IF(a.valor is null,b.valor,a.valor))/
								PERIOD_DIFF(
										DATE_FORMAT('$dataFim','%Y%m'),
									 	DATE_FORMAT('$dataInicio','%Y%m')
								), 2
							)
						) as media,

					CONCAT(YEAR(b.data2),'-',
							IF(MONTH(b.data2) < 10,
								CONCAT('0',MONTH(b.data2)),
								MONTH(b.data2))
							) as mes
			FROM
			(SELECT 
             	duplicata_parcelas.data_baixa mes,
				SUM(duplicata_parcelas.valor) as valor
             FROM
                plano_contas INNER JOIN duplicata ON duplicata.plano_contas_id = plano_contas.id
             	INNER JOIN duplicata_parcelas ON duplicata.id = duplicata_parcelas.duplicata_id
			WHERE
				plano_contas.codigo LIKE '$codigo%'
				AND
				duplicata_parcelas.data_baixa LIKE '$MesPesquisa%'
			GROUP BY
				MONTH(duplicata_parcelas.data_baixa))
			a RIGHT JOIN (
				SELECT 
					l.data as'data2',SUM(l.valor) as 'valor'
				FROM
					plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id 
				WHERE
					l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '$codigo%'
				GROUP BY
					MONTH(l.data),YEAR(l.data)) b ON b.data2 = a.mes)
				ORDER BY valor DESC LIMIT 1
		";

        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = $MesPesquisa;
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['media'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber para um determinado plano de contas dentro de um 
 * determinado periodo 
 *
 * @author Bruno Haick
 * @date Criação: 08/01/2014
 *
 * return array 
 */
function buscaTotalPorPlanoContasPorPeriodoPadrao($dataInicio, $dataFim, $codigo) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    foreach ($entreperiodos as $MesPesquisa) {
        $sql = "SELECT * FROM (
				SELECT (
					IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)) as valor ,
				(
					ROUND(
						SUM((IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)))/
						IF(PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)= 0,1,PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)), 2
					)
				) as media,
                                IF(a.mes is not null,CONCAT_WS('-',YEAR(a.mes),
					IF(MONTH(a.mes)<10,
                                        	CONCAT_WS('0',MONTH(a.mes)),
						MONTH(a.mes))
					),CONCAT_WS('-',YEAR(b.data2),
					IF(MONTH(b.data2)<10,
						CONCAT('0',MONTH(b.data2)),
						MONTH(b.data2))
                                        )
                                ) as mes

				
			FROM (SELECT
				fatura_parcelas.data_baixa  mes,
				SUM(fatura_parcelas.valor) as valor
				
			FROM
				plano_contas INNER JOIN fatura ON fatura.plano_contas_id = plano_contas.id
				INNER JOIN fatura_parcelas ON fatura.id = fatura_parcelas.fatura_id
			WHERE
				plano_contas.codigo LIKE '$codigo%'
				AND
				fatura_parcelas.data_baixa  LIKE '$MesPesquisa%'
			GROUP BY
				MONTH(fatura_parcelas.data_baixa)
			ORDER BY
				fatura_parcelas.data_baixa DESC)
			a LEFT JOIN (
				SELECT
					l.data as'data2',SUM(l.valor) as 'valor'
				FROM 
					plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id 
				WHERE 
					l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '$codigo%'
				GROUP BY
					MONTH(l.data),YEAR(l.data)
			) b ON b.data2 = a.mes)a
			UNION ALL
				(SELECT (IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)) as valor ,
					(
					ROUND(
						SUM((IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)))/
						IF(PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)= 0,1,PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)), 2
					)
				) as media,
					IF(a.mes is not null,CONCAT_WS('-',YEAR(a.mes),
							IF(MONTH(a.mes)<10,
								CONCAT_WS('0',MONTH(a.mes)),
								MONTH(a.mes))
							),CONCAT_WS('-',YEAR(b.data2),
							IF(MONTH(b.data2)<10,
								CONCAT('0',MONTH(b.data2)),
								MONTH(b.data2))
							)) as mes
				FROM (SELECT
					fatura_parcelas.data_baixa  mes,
					SUM(fatura_parcelas.valor) as valor
				FROM
					plano_contas INNER JOIN fatura ON fatura.plano_contas_id = plano_contas.id
					INNER JOIN fatura_parcelas ON fatura.id = fatura_parcelas.fatura_id
				WHERE
					plano_contas.codigo LIKE '$codigo%'
					AND
					fatura_parcelas.data_baixa  LIKE '$MesPesquisa%'
				GROUP BY
					MONTH(fatura_parcelas.data_baixa )
				ORDER BY
					fatura_parcelas.data_baixa DESC)
				a RIGHT JOIN (
					SELECT
						l.data as'data2',SUM(l.valor) as 'valor'
					FROM 
						plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id 
					WHERE 
						l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '$codigo%'
					GROUP BY
						MONTH(l.data),YEAR(l.data)
				) b ON b.data2 = a.mes) ORDER BY valor DESC LIMIT 1
		";
//        if($codigo == '1.03'){
//            die($sql);
//        }
        $query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = $MesPesquisa;
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['media'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/**
 *
 * Calcula o valor total de contas a receber para um determinado plano de contas dentro de um 
 * determinado periodo 
 *
 * @author Bruno Haick
 * @date Criação: 08/01/2014
 *
 * return array 
 */
function buscaTotalPorPlanoContasPorPeriodo($dataInicio, $dataFim, $codigo) {
    $entreperiodos = DiffDatetoArray($dataInicio, $dataFim);
    mysqli_set_charset(banco::$connection, 'utf8');
    $array = null;
    foreach ($entreperiodos as $MesPesquisa) {
        $sql = "SELECT * FROM (
				SELECT (
					IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)) as valor ,
				(
					ROUND(
						SUM((IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)))/
						IF(PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)= 0,1,PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)), 2
					)
				) as media,
                                IF(a.mes is not null,CONCAT_WS('-',YEAR(a.mes),
					IF(MONTH(a.mes)<10,
                                        	CONCAT_WS('0',MONTH(a.mes)),
						MONTH(a.mes))
					),CONCAT_WS('-',YEAR(b.data2),
					IF(MONTH(b.data2)<10,
						CONCAT('0',MONTH(b.data2)),
						MONTH(b.data2))
                                        )
                                ) as mes

				
			FROM (SELECT
				fatura.data_lancamento  mes,
				SUM(fatura_parcelas.valor) as valor
				
			FROM
				plano_contas INNER JOIN fatura ON fatura.plano_contas_id = plano_contas.id
				INNER JOIN fatura_parcelas ON fatura.id = fatura_parcelas.fatura_id
			WHERE
				plano_contas.codigo LIKE '$codigo%'
				AND
				fatura.data_lancamento  LIKE '$MesPesquisa%'
			GROUP BY
				MONTH(fatura.data_lancamento)
			ORDER BY
				fatura.data_lancamento DESC)
			a LEFT JOIN (
				SELECT
					l.data as'data2',SUM(l.valor) as 'valor'
				FROM 
					plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id 
				WHERE 
					l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '$codigo%'
				GROUP BY
					MONTH(l.data),YEAR(l.data)
			) b ON MONTH(b.data2) = MONTH(a.mes) and YEAR(b.data2) = YEAR(a.mes))a
			UNION ALL
				(SELECT (IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)) as valor ,
					(
					ROUND(
						SUM((IF(a.valor is null,0,a.valor)+IF(b.valor is null,0,b.valor)))/
						IF(PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)= 0,1,PERIOD_DIFF(
								DATE_FORMAT('$dataFim','%Y%m'),
								DATE_FORMAT('$dataInicio','%Y%m')
						)), 2
					)
				) as media,
					IF(a.mes is not null,CONCAT_WS('-',YEAR(a.mes),
							IF(MONTH(a.mes)<10,
								CONCAT_WS('0',MONTH(a.mes)),
								MONTH(a.mes))
							),CONCAT_WS('-',YEAR(b.data2),
							IF(MONTH(b.data2)<10,
								CONCAT('0',MONTH(b.data2)),
								MONTH(b.data2))
							)) as mes
				FROM (SELECT
					fatura.data_lancamento  mes,
					SUM(fatura_parcelas.valor) as valor
				FROM
					plano_contas INNER JOIN fatura ON fatura.plano_contas_id = plano_contas.id
					INNER JOIN fatura_parcelas ON fatura.id = fatura_parcelas.fatura_id
				WHERE
					plano_contas.codigo LIKE '$codigo%'
					AND
					fatura.data_lancamento  LIKE '$MesPesquisa%'
				GROUP BY
					MONTH(fatura.data_lancamento )
				ORDER BY
					fatura.data_lancamento DESC)
				a RIGHT JOIN (
					SELECT
						l.data as'data2',SUM(l.valor) as 'valor'
					FROM 
						plano_contas p INNER JOIN lancamentos l ON l.plano_contas_id = p.id 
					WHERE 
						l.data LIKE '$MesPesquisa%' AND p.codigo LIKE '$codigo%'
					GROUP BY
						MONTH(l.data),YEAR(l.data)
				) b ON b.data2 = a.mes) ORDER BY valor DESC LIMIT 1
		";
//                die($sql);
		$query = mysqli_query(banco::$connection, $sql);
        if (mysqli_num_rows($query) > '0') {
            while ($linha = mysqli_fetch_assoc($query)) {
                $array[] = $linha;
            }
        } else {
            $falsoPositivo['mes'] = $MesPesquisa;
            $falsoPositivo['valor'] = '0';
            $falsoPositivo['media'] = '0';
            $falsoPositivo['taxa'] = '0';
            $array[] = $falsoPositivo;
        }
    }
    return $array;
}

/* * *
 * @author Luiz Cortinhas
 * @ description esta funç�o serve para obter os meses intermediarios entre duas datas
 */

function DiffDatetoArray($inicio, $fim) {
    $inicio_explodido = explode('-', $inicio);
    $fim_explodido = explode('-', $fim);
    $arrayEntrePeriodos [] = $inicio_explodido[0] . '-' . $inicio_explodido[1];
    while ($inicio_explodido[1] < $fim_explodido[1] || $inicio_explodido[0] < $fim_explodido[0]) {
        if ($fim_explodido[0] == $inicio_explodido[0]) {
            $inicio_explodido[1] = $inicio_explodido[1] + 1;
            if ($inicio_explodido[1] < 10) {
                $inicio_explodido[1] = '0' . $inicio_explodido[1];
            }
            $arrayEntrePeriodos[] = $inicio_explodido[0] . '-' . $inicio_explodido[1];
        } else {
            if ($inicio_explodido[1] == '12') {
                $inicio_explodido[1] = '1';
                $inicio_explodido[0] = $inicio_explodido[0] + 1;
                if ($inicio_explodido[1] < 10) {
                    $inicio_explodido[1] = '0' . $inicio_explodido[1];
                }
                $arrayEntrePeriodos[] = $inicio_explodido[0] . '-' . $inicio_explodido[1];
            } else {
                $inicio_explodido[1] = $inicio_explodido[1] + 1;
                if ($inicio_explodido[1] < 10) {
                    $inicio_explodido[1] = '0' . $inicio_explodido[1];
                }
                $arrayEntrePeriodos[] = $inicio_explodido[0] . '-' . $inicio_explodido[1];
            }
        }
    }
    return $arrayEntrePeriodos;
}

/**
 *
 * Busca Planos de contas e sub-planos dado o numero do plano geral
 *
 * Ex: 1 - Receitas, 2 - Despesas , etc...
 *
 * @author Bruno Haick
 * @date Criação: 25/04/2013
 *
 * @return
 * 	os planos de contas referentes a cada classificação geral
 */
function pegaNomeBanderiaporID($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nome
			FROM
				cartao_bandeiras
			WHERE
				id = '$id'
			
	";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 *
 * Busca Planos de contas e sub-planos dado o numero do plano geral
 *
 * Ex: 1 - Receitas, 2 - Despesas , etc...
 *
 * @author Bruno Haick
 * @date Criação: 25/04/2013
 *
 * @return
 * 	os planos de contas referentes a cada classificação geral
 */
function buscaPlanoContasPorNome($nome) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				plano_contas
			WHERE
				nome LIKE '%$nome%'
			ORDER BY
				codigo
	";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

/**
 *
 * Busca Planos de contas e sub-planos dado o numero do plano geral
 *
 * Ex: 1 - Receitas, 2 - Despesas , etc...
 *
 * @author Bruno Haick
 * @date Criação: 25/04/2013
 *
 * @return
 * 	os planos de contas referentes a cada classificação geral
 */
function buscaPlanoContas($plano) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				plano_contas
			WHERE
				codigo LIKE '$plano%'
			ORDER BY
				codigo
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}
/**
 *
 * Busca Planos de contas e sub-planos dado o numero do plano geral
 *
 * Ex: 1 - Receitas, 2 - Despesas , etc...
 *
 * @author Bruno Haick
 * @date Criação: 25/04/2013
 *
 * @return
 * 	os planos de contas referentes a cada classificação geral
 */
function buscaPlanoContasDistintos($plano) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				*
			FROM
				plano_contas
			WHERE
				codigo LIKE '$plano%'
                        GROUP BY nome
			ORDER BY
				codigo
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}
/*
 * Função para buscar as Notas Fiscais já enviadas ao
 * webservice da prefeitura.
 * 
 * @author Bruno Haick
 * 
 * return Array com as notas fiscais
 * 
 * @TODO a query não funciona pois o banco está modificado
 */

function buscaNotasEnviadas() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				c.matricula,
				c.membro,
				g.numero_controle,
				r.id as rps_id,
				r.*
			FROM
				guia_controle g
				INNER JOIN rps r ON r.guia_controle_id = g.id
				INNER JOIN `cliente` c ON c.cliente_id = g.titular_id
			WHERE
				r.enviado=1
			GROUP BY g.id
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $arr[] = $linha;
    }

    return $arr;
}

function buscaProducaoTiss($idConvenio, $dataInicio, $dataFim, $ordenado) {
    //$dataInicio = converteData($dataInicio);
    //$dataFim = converteData($dataFim);
    if ($ordenado == 1) {
        $order = "t.data_autorizacao";
    } else if ($ordenado == 2) {
        $order = "dep.nome";
    }

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				t.num_guia_principal as 'numero_da_guia',
				DATE_FORMAT(t.data_autorizacao,'%d/%m/%Y') as 'data_autorizacao',
				c.matricula,
				CONCAT_WS(' ', dep.nome, dep.sobrenome) as 'depnome',
				p.nome as 'servico',
				hp.valor as 'valor',
				conv.nome as 'convenio'
			FROM
				guia_tiss t 
				INNER JOIN grupo_procedimento as gp ON gp.guia_tiss_id = t.id
				INNER JOIN historico_procedimento as hp ON hp.grupo_procedimento_id = gp.id
				INNER JOIN procedimento as p ON p.id = hp.procedimento_id
				INNER JOIN dependente as d ON d.dependente_id = gp.cliente_cliente_id
				INNER JOIN cliente as c ON c.cliente_id = d.fk_titular_id
				INNER JOIN pessoa as ps ON ps.id = c.cliente_id 
				INNER JOIN pessoa as dep ON dep.id = d.dependente_id
				INNER JOIN convenio as conv ON conv.id = gp.convenio_id
			WHERE
				conv.id = '$idConvenio'
				AND
				t.data_autorizacao BETWEEN '$dataInicio' AND '$dataFim'
			ORDER BY
				$order
	";
//   die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function buscaAtendimentosConvenio($idConvenio, $dataInicio, $dataFim, $ordenado, $selecionado) {

    if ($selecionado == 1) {
        $sel = "gp.data";
    } else if ($selecionado == 2) {
        $sel = "gp.data";
    } else if ($selecionado == 3) {
        $sel = "gp.data";
    }

    if ($ordenado == 1) {
        $order = "p.codigo, gp.data";
    } else if ($ordenado == 2) {
        $order = "conv.nome";
    } else if ($ordenado == 3) {
        $order = "med.nome";
    } else if ($ordenado == 4) {
        $order = "gp.data";
    } else if ($ordenado == 5) {
        $order = "dep.nome, gp.data";
    } else if ($ordenado == 6) {
        $order = "conv.nome, p.codigo";
    } else if ($ordenado == 7) {
        $order = "med.nome, dep.nome";
    } else if ($ordenado == 8) {
        $order = "gp.valor";
    }

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				t.num_guia_principal as 'numero_da_guia',
				c.matricula,
				CONCAT_WS(' ', dep.nome,dep.sobrenome) as 'depnome',
				p.nome as 'servico',
				p.codigo,
				DATE_FORMAT(gp.data,'%d/%m/%Y') as data,
				hp.valor as 'valor',
				CONCAT_WS(' ',med.nome,med.sobrenome) as 'medico',
				CONCAT_WS(' ',usu.nome,usu.sobrenome) as 'usuario',
				conv.nome as 'convenio'
			FROM
				guia_tiss t
				INNER JOIN grupo_procedimento as gp ON gp.guia_tiss_id = t.id
				INNER JOIN historico_procedimento as hp ON hp.grupo_procedimento_id = gp.id
				INNER JOIN procedimento as p ON p.id = hp.procedimento_id
				INNER JOIN dependente as d ON d.dependente_id = gp.cliente_cliente_id
				INNER JOIN cliente as c ON c.cliente_id = d.fk_titular_id
				INNER JOIN pessoa as ps ON ps.id = c.cliente_id 
				INNER JOIN pessoa as dep ON dep.id = d.dependente_id
				INNER JOIN pessoa as med ON med.id = gp.medico_medico_id 
				INNER JOIN pessoa as usu ON usu.id = gp.usuario_id
				INNER JOIN convenio as conv ON conv.id = gp.convenio_id
			WHERE
				conv.id = '$idConvenio'
				AND
				$sel BETWEEN '$dataInicio' AND '$dataFim'
			ORDER BY
				$order
	";
//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function buscaAtendimentosConvenioSubtotal($idConvenio, $dataInicio, $dataFim, $ordenado, $selecionado) {

    if ($selecionado == 1) {
        $sel = "gp.data";
    } else if ($selecionado == 2) {
        $sel = "gp.data";
    } else if ($selecionado == 3) {
        $sel = "gp.data";
    }

    if ($ordenado == 1) {
        $order = "p.codigo, gp.data";
    } else if ($ordenado == 2) {
        $order = "conv.nome";
    } else if ($ordenado == 3) {
        $order = "med.nome";
    } else if ($ordenado == 4) {
        $order = "gp.data";
    } else if ($ordenado == 5) {
        $order = "dep.nome, gp.data";
    } else if ($ordenado == 6) {
        $order = "conv.nome, p.codigo";
    } else if ($ordenado == 7) {
        $order = "med.nome, dep.nome";
    } else if ($ordenado == 8) {
        $order = "gp.valor";
    }

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				SUM(hp.valor) as 'valor',
			";
    if ($ordenado == 1)
        $sql .= "DATE_FORMAT(gp.data,'%d/%m/%Y') as data, p.codigo ";
    else if ($ordenado == 4)
        $sql .= "DATE_FORMAT(gp.data,'%d/%m/%Y') as data ";
    else if ($ordenado == 5)
        $sql .= "DATE_FORMAT(gp.data,'%d/%m/%Y') as data, dep.nome ";

    $sql .="FROM
				guia_tiss t 
				INNER JOIN grupo_procedimento as gp ON gp.guia_tiss_id = t.id
				INNER JOIN historico_procedimento as hp ON hp.grupo_procedimento_id = gp.id
				INNER JOIN procedimento as p ON p.id = hp.procedimento_id
				INNER JOIN dependente as d ON d.dependente_id = gp.cliente_cliente_id
				INNER JOIN cliente as c ON c.cliente_id = d.fk_titular_id
				INNER JOIN pessoa as ps ON ps.id = c.cliente_id 
				INNER JOIN pessoa as dep ON dep.id = d.dependente_id
				INNER JOIN pessoa as med ON med.id = gp.medico_medico_id 
				INNER JOIN pessoa as usu ON usu.id = gp.usuario_id
				INNER JOIN convenio as conv ON conv.id = gp.convenio_id
			WHERE
				conv.id = '$idConvenio'
				AND
				$sel BETWEEN '$dataInicio' AND '$dataFim'
			GROUP BY
				$order
			ORDER BY
				$order
	";

//	die($sql);
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function histEntradaNotaFiscal($dados) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $ordenado = $dados['ordenado'];

    if ($dados['fornecedor'] > 0)
        $sqltmp = "nota_fiscal.fornecedores_id = '{$dados['fornecedor']}' AND ";
    else
        $sqltmp = '';

    if ($ordenado == 1) {
        $sqltmp2 = 'nota_fiscal.data_entrada';
    } else if ($ordenado == 2) {
        $sqltmp2 = 'nota_fiscal.data_emissao,fornecedores.nome';
    } else if ($ordenado == 3) {
        $sqltmp2 = 'fornecedores.nome,nota_fiscal.nota_fiscal';
    } if ($ordenado == 4) {
        $sqltmp2 = 'fornecedores.nome,nota_fiscal.nota_fiscal';
    }

    $sql = "SELECT
				nota_fiscal.*,
				fornecedores.nome as fornecedor
			FROM
				nota_fiscal,fornecedores
			WHERE
				$sqltmp
				nota_fiscal.fornecedores_id = fornecedores.id
			AND
				nota_fiscal.data_entrada BETWEEN '{$dados['dataInicio']}' AND '{$dados['dataFim']}'
			ORDER BY
				$sqltmp2
	";

    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function detalhamentoNotaFiscal($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
				nota_fiscal.*,entrada.*,movimentacao.*,material.nome as material_nome,material.id as material_id,lote.nome as lote_nome,lote.validade
			FROM
				nota_fiscal,entrada,movimentacao,material,lote
			WHERE
				nota_fiscal.id = entrada.nota_fiscal_id
			AND
				entrada.id = movimentacao.id
			AND 
				lote.id = movimentacao.lote_id
			AND 
				material.id = movimentacao.material_id
			AND
				nota_fiscal.id = $id
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array;
}

function totalEntradaMaterial($id) {

    mysqli_set_charset(banco::$connection, 'utf8');

    $sql = "SELECT
			SUM(quantidade) as saldo
			FROM
				entrada,movimentacao,material
			WHERE
				entrada.id = movimentacao.id
			AND 
				material.id = movimentacao.material_id
			AND
				material.id = $id
			AND
				movimentacao.data BETWEEN '" . date("Y-m-") . "01' AND '" . date("Y-m-t") . "'
	";
    $query = mysqli_query(banco::$connection, $sql);

    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }

    return $array[0];
}

function orcamentoPegaUsuarioInfomacao($moduloId) {
    $result = mysqli_query(banco::$connection, 'SELECT 
    P.estado as \'estadoDaPessoa\', 
    P.cidade as \'cidadeDaPessoa\', 
    P.cep as \'cepDaPessoa\', 
    P.numero as \'numeroDaPessoa\', 
    P.endereco as \'enderecoDaPessoa\', 
    P.sexo as \'generoDaPessoa\', 
    P.nome as \'nomeDaPessoa\' , 
    P.sobrenome as \'sobrenomeDaPessoa\',
	c.matricula as matricula,
    A.*
	FROM (
	    SELECT 
		mod.data as \'modulo_data\', 
		dep.fk_titular_id as \'titular_id\', 
		pes.nome as \'depedenteNome\',
		pes.sobrenome as \'depedenteSobrenome\'
	    FROM `modulos` as `mod` 
	    INNER JOIN `cliente` as `cli` ON `mod`.`cliente_id` = `cli`.`cliente_id`
	    INNER JOIN `pessoa` as `pes` ON `mod`.`cliente_id` = `pes`.`id`
	    INNER JOIN `dependente` as `dep` ON `dep`.`dependente_id` = `cli`.`cliente_id`
	    WHERE `mod`.`id` = \'' . $moduloId . '\'
	) as A 
	INNER JOIN cliente as c ON c.cliente_id = A.titular_id
	INNER JOIN pessoa as P on A.titular_id = P.id'
    );

    return mysqli_fetch_assoc($result);
}

function orcamentoPegaVacinas($moduloId) {
	
	$sql = "
			SELECT
			@a := h.material_id AS material_id,
			h. DATA,
			h.posicao_horizontal,
			(
				SELECT
					count(m.posicao_vertical)
				FROM
					modulos_has_material AS m
				WHERE
					m.material_id = @a
				AND m.modulos_id = '$moduloId'
				GROUP BY
					m.posicao_vertical
			) AS count,
			h.finalizado,
			m.nome,
			m.preco_cartao,
			m.preco AS preco_aVista,
			`mod`.descontoBCG,
			`mod`.descontoMedico,
			`mod`.descontoPromocional
		FROM
			`modulos_has_material` AS h
			INNER JOIN `material` AS m ON m.`id` = h.`material_id`
			INNER JOIN `modulos` AS `mod` ON `mod`.`id` = h.`modulos_id`
		WHERE
			h.`modulos_id` = '$moduloId'
		ORDER BY
			h.material_id	
		";
//	die($sql);
    return mysqli_query(banco::$connection, $sql);
}

/**
 * Lista todos os grupos de imuneterapia
 * @return MySQLi_Result contendo a lista de imuneterapia
 */
function listaGruposImuneterapia() {
    return mysqli_query(banco::$connection, 'SELECT id, nomeGrupo as grupo FROM grupo_material');
}

function confirmaHorariodeAtendimento($idDaFila, $idAtedendte) {
    $sql = 'UPDATE `fila_espera_vacina` '
			. 'SET '
			. '`hora_atendimento`=\'' . date("H:i:s") . '\','
			. '`usuario_id_atendente`= ' . $idAtedendte
			. ' WHERE num_ord = ' . $idDaFila . ' '
			. 'AND '
			. 'data = CURDATE()';
//    die(print_r($sql));
    return mysqli_query(banco::$connection, $sql);
}

function pegaGruposDemAterialDoUsuario($idDoUser) {
//	 return '
//		 SELECT * FROM `usuario` u 
//		 INNER JOIN `servico` s ON u.usuario_id = s.cliente_cliente_id 
//		 INNER JOIN `material` m ON m.id = s.material_id
//		 INNER JOIN `grupo_material` g ON g.id = m.grupo_material_id
//		 WHERE s.cliente_cliente_id = \''.$idDoUser. '\' GROUP BY g.nomeGrupo';
    return mysqli_query(banco::$connection, '	SELECT * FROM `material` m INNER JOIN `grupo_material` g ON g.id = m.grupo_material_id INNER JOIN `servico` s ON s.material_id = m.id WHERE s.cliente_cliente_id = \'' . $idDoUser . '\' AND m.tipo_material_id = \'2\' GROUP BY m.grupo_material_id');
}

function buscaDadosConsulta($idConsulta) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * FROM consultas WHERE id = $idConsulta";
    $query = mysqli_query(banco::$connection, $sql);
    return mysqli_fetch_assoc($query);
}

function buscaDadosCliente($idCliente) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * FROM cliente WHERE cliente_id = $idCliente";
    $query = mysqli_query(banco::$connection, $sql);
    return mysqli_fetch_assoc($query);
}

function buscaDadosMedico($idMedico) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * FROM medico WHERE medico_id = $idMedico";
    $query = mysqli_query(banco::$connection, $sql);
    return mysqli_fetch_assoc($query);
}

function buscaTodasHipoteseCliente($idCliente) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT hipotese_diag FROM consultas WHERE cliente_cliente_id = $idCliente AND categoria_consultas_id = 6 ORDER BY data";
    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function buscarMedicoCrm($idCliente) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT login, m2.crm "
            . "FROM usuario, (SELECT medico.medico_id, crm "
            . "               FROM medico, (SELECT medico_id "
            . "                             FROM consultas "
            . "                             WHERE cliente_cliente_id=$idCliente AND categoria_consultas_id=6) as m1"
            . "               WHERE medico.medico_id = m1.medico_id) as m2 "
            . "WHERE usuario_id=m2.medico_id";
    $query = mysqli_query(banco::$connection, $sql);
    while ($linha = mysqli_fetch_assoc($query)) {
        $array[] = $linha;
    }
    return $array;
}

function atualizarAntecedentesFamiliares($id, $data) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `cliente` SET antecedente_familiar='$data' WHERE cliente_id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function atualizarAntecedentesPessoal($id, $data) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE `cliente` SET antecedente_pessoal='$data' WHERE cliente_id = $id";
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function verificaConsultaHasReqServ($dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT * "
            . "   FROM consulta_has_requisicao_servico "
            . "       WHERE cliente_id = " . $dados['cliente_id'] . " AND data = '" . $dados['data'] . "' AND prestador_id = " . $dados['prestador_id'];
    $query = mysqli_query(banco::$connection, $sql);

    $linha = mysqli_fetch_assoc($query);

    if (count($linha) == 0) {
        return false;
    }

    return $linha;
}

function atualizaServicoPrestador($dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "UPDATE consulta_has_requisicao_servico "
            . "   SET encaminhamento='" . $dados['encaminhamento'] . "' "
            . "      WHERE cliente_id = " . $dados['cliente_id'] . " "
            . "        AND data = '" . $dados['data'] . "' "
            . "        AND prestador_id = " . $dados['prestador_id'];
    if (mysqli_query(banco::$connection, $sql))
        return true;
    else
        return false;
}

function pegaClienteFilaEspera($idf) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT cliente_id FROM fila_espera_consulta WHERE id = $idf";
    $query = mysqli_query(banco::$connection, $sql);
    return mysqli_fetch_assoc($query);
}

function pesquisaOOP($sql) {
    $arrayList = Array();
    try {
        Database::query($sql);
        $arrayList = Database::fetchAll();
    } catch (BasicException $ex) {
//$ex->doLog(true);
    }
    return $arrayList;
}
