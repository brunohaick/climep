<?php

/* * *
 * Esta classe � utilizada para armazenar a conex�o com o banco de forma estatica.
 */

class banco {

    static $connection;

}

/**
 * Nesta função, vamos gerenciar a conexão com um banco de dados, direto de nosso
 * arquivo de configuração com informações do banco.
 */
function conectar() {
    global $config;
    banco::$connection = new mysqli ($config['host'], $config['usuario'], $config['senha'],$config['banco']);
    

    return banco::$connection;
}

/*
 * função para desconectar o banco de dados
 */

function desconectar($con) {
    mysqli_close($con);
}

/**
 * Nesta função, simplificamos a maneira de inserir dados em uma tabela.
 *
 * @param string $tabela Nome da tabela a receber dados
 * @param array $dados Dados a serem inseridos na tabela, em forma de um array multi-dimensional
 */
function inserir($tabela, $dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    /**
     * Para cada chave e valor em nosso array, criamos dois novos arrays.
     * Um com colunas, outro com valores.
     *
     * $valores é um array com os valores a serem inseridos, envolvidos em aspas simples: 'lorem ipsum'.
     * Logo abaixo usamos implode para transformar esses valores em uma string separada
     * por vírgulas: 'lorem ipsum', 'dolor sit amet', 'nepet quisquam'
     *
     * Depois, basta jogar essa string na nossa query.
     * $dados['NOME_COLUNA'] = $VALOR_COLUNA
     */
    foreach ($dados as $coluna => $valor) {
        $colunas[] = "`$coluna`"; // Envolvemos o valor em crases para evitar erros na query SQL
        $valores[] = "'$valor'";
    }

    /**
     * Transformamos nosso array de colunas em uma string, separada por vírgulas
     */
    $colunas = implode(", ", $colunas);

    /**
     * Transformamos nosso array de substitutos em uma string, separada por vírgulas
     */
    $valores = implode(", ", $valores);

    /**
     * Montamos nossa query SQL
     */
    $query = "INSERT INTO `$tabela` ($colunas) VALUES ($valores)";
    /**
     * Preparamos e executamos nossa query
     */
//   if($tabela == 'material')//
//           var_dump($query);
//    die($query);
    if (mysqli_query(banco::$connection, $query)) {
        return true;
    } else {
//        echo $query;
        return false;
    }
}

/**
 * Nesta função, simplificamos a maneira de inserir dados em uma tabela.
 *
 * @param string $tabela Nome da tabela a receber dados
 * @param array $dados Dados a serem inseridos na tabela, em forma de um array multi-dimensional
 */
function inserircomNull($tabela, $dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    /**
     * Para cada chave e valor em nosso array, criamos dois novos arrays.
     * Um com colunas, outro com valores.
     *
     * $valores é um array com os valores a serem inseridos, envolvidos em aspas simples: 'lorem ipsum'.
     * Logo abaixo usamos implode para transformar esses valores em uma string separada
     * por vírgulas: 'lorem ipsum', 'dolor sit amet', 'nepet quisquam'
     *
     * Depois, basta jogar essa string na nossa query.
     * $dados['NOME_COLUNA'] = $VALOR_COLUNA
     */
    foreach ($dados as $coluna => $valor) {
        $colunas[] = "`$coluna`"; // Envolvemos o valor em crases para evitar erros na query SQL
        $valores[] = ($valor == '' ) ? "null" : "'$valor'";
    }

    /**
     * Transformamos nosso array de colunas em uma string, separada por vírgulas
     */
    $colunas = implode(", ", $colunas);

    /**
     * Transformamos nosso array de substitutos em uma string, separada por vírgulas
     */
    $valores = implode(", ", $valores);

    /**
     * Montamos nossa query SQL
     */
    $query = "INSERT INTO `$tabela` ($colunas) VALUES ($valores)";
    /**
     * Preparamos e executamos nossa query
     */
//  var_dump($query);die(); 
    if (mysqli_query(banco::$connection, $query)) {
        return true;
    } else {
        //echo $query;
        return false;
    }
}

/**
 * Nesta função, simplificamos a maneira de alterar dados em uma tabela.
 *
 * @param string $tabela Nome da tabela a ter dados alterados
 * @param string $onde Onde os dados serão alterados
 * @param array $dados Dados a serem alterados na tabela, em forma de um array multi-dimensional
 */
function alterar($tabela, $onde, $dados) {
    mysqli_set_charset(banco::$connection, 'utf8');
    /**
     * Pegaremos os valores e campos recebidos no método e os organizaremos
     * de modo que fique mais fácil montar a query logo a seguir
     */
    foreach ($dados as $coluna => $valor) {
        if (is_int($valor)) {
            $set[] = "`$coluna` = $valor";
        } else {
            $set[] = "`$coluna` = '$valor'";
        }
    }

    /**
     * Transformamos nosso array de valores em uma string, separada por vírgulas
     */
    $set = implode(", ", $set);

    /**
     * Montamos nossa query SQL
     */
    $query = "UPDATE `$tabela` SET $set WHERE $onde";
//	die($query);
    /**
     * Preparamos e executamos nossa query
     */
    //return $query;
    if (mysqli_query(banco::$connection, $query))
        return true;
    else
        return false;
}

/**
 * Nesta função, simplificamos a maneira de remover dados de uma tabela.
 *
 * @param string $tabela Nome da tabela a ter dados removidos
 * @param string $onde Onde os dados serão removidos
 */
function remover($tabela, $onde = null) {
    mysqli_set_charset(banco::$connection, 'utf8');
    /**
     * Montamos nossa query SQL
     */
    $query = "DELETE FROM `$tabela`";

    /**
     * Caso tenhamos um valor de onde deletar dados, adicionamos a cláusula WHERE
     */
    if (!empty($onde)) {
        $query .= " WHERE $onde";
    }

    /**
     * Preparamos e executamos nossa query
     */
    if (mysqli_query(banco::$connection, $query))
        return true;
    else
        return false;
}

function listarCIDs($consulta_id = 0) {
    $query = "SELECT idCID,grupo,descricao,IF(idCID NOT IN (SELECT idCID FROM `cid10` a inner join `consultas_has_cid10` b ON a.idCID = b.cid10_idCID WHERE b.consultas_id = '$consulta_id'),0,1) as 'marcado' FROM `cid10` ORDER BY marcado DESC,idCID ASC LIMIT 1000";
    Database::query($query);
    $count = Database::rowCount();
    if ($count > 0) {
        $resultado = Database::fetchAll();
        return $resultado;
    } else {
        $query = "SELECT *,'0' as 'marcado'FROM `cid10`";
        Database::query($query);

        $resultado = Database::fetchAll();
        return $resultado;
    }
}

function listarTesteCutaneo($cliente) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $query = "SELECT * FROM `testes_realizados` b INNER JOIN `testes` a ON b.testes_id = a.id WHERE b.cliente_cliente_id = '$cliente'";
    return pesquisaOOP($query);
}

/**
 * Nesta função, simplificamos a maneira de consultar dados de uma tabela.
 *
 * @param string $tabela Nome da tabela a ter dados consultados
 * @param string $campos Quais campos serão selecionados na tabela
 * @param string $onde Onde os dados serão consultados
 * @param string $ordem Ordem dos dados a serem consultados
 * @param string $filtro Filtrar dados consultados por conter uma palavra
 * @param string $limite Limitar dados consultados
 */
function listar($tabela, $campos, $onde = null, $filtro = null, $ordem = null, $limite = null) {
    mysqli_set_charset(banco::$connection, 'utf8');
    /**
     * Montamos nossa query SQL
     */
    $query = "SELECT $campos FROM `$tabela`";

    /**
     * Caso tenhamos um valor de onde selecionar dados, adicionamos a cláusula WHERE
     */
    if (!empty($onde)) {
        $query .= " WHERE $onde";
    }

    /**
     * Caso tenhamos um valor de como filtrar dados que contenham uma regra, adicionamos a cláusula LIKE
     */
    if (!empty($filtro)) {
        $query .= " LIKE $filtro";
    }
    /**
     * Caso tenhamos um valor de como ordenar dados, adicionamos a cláusula ORDER BY
     */
    if (!empty($ordem)) {
        $query .= " ORDER BY $ordem ASC";
    }

    /**
     * Caso tenhamos um valor de como limitar os dados consultados, adicionamos a cláusula LIMIT
     */
    if (!empty($limite)) {
        $query .= " LIMIT $limite";
    }

//  /**
//   * Preparamos e executamos nossa query
//   */
//  $consulta = mysqli_query($query);
//
//  /**
//   * Se tivermos resultados para nossa consulta
//   */
//  if (mysqli_num_rows($consulta) != 0) {
//      /**
//       * Guardamos os resultados dentro do array resultados, que será retornado para a aplicação
//       */
//      while ($item = mysqli_fetch_assoc($consulta)) {
//          $resultados[] = $item;
//      }
//
//      return $resultados;
//  }
    /**
     * 
     * Não tem de que. =)
     * 
     * E caso esteja insatisfeito... desulpe hahaha
     * 
     * Att. Mario Chapela.
     * 
     */
    return pesquisaOOP($query);
}

/**
 * Essa função recebe o nome da vacina e retorna o preço unitario no cartão de credito da mesma
 * @param type $nome  Nome da vacina a ser pesquisada.
 * @author Luiz  Cortinhas <luizcf14@gmail.com>
 */
function vacinapreco_cartao($nome) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $result = '';
    $query = 'SELECT nome,preco,preco_cartao FROM `material`  where BINARY nome = \'' . $nome . '\'';
    $consulta = mysqli_query(banco::$connection, $query);
    $resultados = mysqli_fetch_assoc($consulta);
    return ($resultados['preco_cartao']);
}

/**
 * Essa função recebe o nome da vacina e retorna o preço unitario à vista da mesma
 * @param type $nome  Nome da vacina a ser pesquisada.
 * @author Luiz  Cortinhas <luizcf14@gmail.com>
 */
function vacinapreco_vista($nome) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $result = '';
    $query = 'SELECT nome,preco,preco_cartao FROM `material`  where BINARY nome like \'' . $nome . '\'';
    $consulta = mysqli_query(banco::$connection, $query);
    $resultados = mysqli_fetch_assoc($consulta);
    return ($resultados['preco']);
}

function buscaGuiasTISSparaXMLporData($inicio, $fim, $convenio) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				t.*,
				CONCAT_WS(' ',p.nome,p.sobrenome) as 'cliente',
				CONCAT_WS(' ',med.nome,med.sobrenome) as 'medico',
				hp.valor as 'valor_procedimento',
				cid.nome as 'cid',
				e.nome as 'solicitacao',
				o.nome as 'tempo_doenca',
				ta.nome as 'atendimento',
				ts.nome as 'tipo_saida',
				td.nome as 'tipo_doenca',
				c.codigo as 'codigo_procedimento',
                                c.nome as 'nome_procedimento'
			FROM `guia_tiss` t 
			INNER JOIN `grupo_procedimento` g ON g.guia_tiss_id = t.id
			INNER JOIN `historico_procedimento` as hp ON hp.grupo_procedimento_id = g.id
			INNER JOIN `procedimento` c ON hp.procedimento_id = c.id
			INNER JOIN `pessoa` p ON p.id = g.cliente_cliente_id 
			INNER JOIN `pessoa` as med ON med.id = g.medico_medico_id
			INNER JOIN `cid` as cid ON cid.id = t.cid_id
			INNER JOIN `carater_solicitacao` e ON e.id = t.carater_solicitacao_id
			INNER JOIN `tempo_doenca` o ON o.id = t.tempo_doenca_id
			INNER JOIN `tipo_atendimento` as ta ON ta.id = t.tipo_atendimento_id
			INNER JOIN `tipo_doenca` as td ON td.id = t.tipo_doenca_id
			INNER JOIN `tipo_saida` as ts ON ts.id = t.tipo_saida_id
			INNER JOIN `indicacao_acidente` as ia ON ia.id = t.indicacao_acidente_id
			WHERE 
			t.data_autorizacao BETWEEN '$inicio' AND '$fim'
			AND g.convenio_id = '$convenio'";


    $consulta = mysqli_query(banco::$connection, $sql);
    while ($re = mysqli_fetch_assoc($consulta)) {
        $resultados[$re['id']][] = $re;
        $resultados[$re['id']]['total'] = buscaGuiasTissParaXMLTotalporData($inicio, $fim, $re['id']);
    }
    return $resultados;
}

function buscaGuiasTissParaXMLTotalporData($inicio, $fim, $id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT sum(hp.valor) as 'valor_procedimento'
			FROM `guia_tiss` t 
				INNER JOIN `grupo_procedimento` g ON g.guia_tiss_id = t.id
				INNER JOIN `historico_procedimento` as hp ON hp.grupo_procedimento_id = g.id
				INNER JOIN `procedimento` c ON hp.procedimento_id = c.id
				INNER JOIN `pessoa` p ON p.id = g.cliente_cliente_id 
				INNER JOIN `pessoa` as med ON med.id = g.medico_medico_id
			WHERE 
				t.data_autorizacao BETWEEN '$inicio' AND '$fim'
				AND t.id = '$id'
			GROUP BY 
				t.id
			";
    $consulta = mysqli_query(banco::$connection, $sql);
    $re = mysqli_fetch_row($consulta);
    return $re[0];
}

/**
 * Essa função tem por objetivo retornar um array contendo as vacinas que ainda não estão no quadro geral de modulos.
 * @param type $arrayconteudo  Array contendo o nome de todas as vacinas já incorporadas ao quadro.
 * @author Luiz  Cortinhas <luizcf14@gmail.com>
 */
function listavacinasDisponiveis($arrayConteudo) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $result = '';
    $query = '';
    //aqui chega
    $where = 'where ';
    if (count($arrayConteudo) > 0) {
        for ($i = 0; $i < count($arrayConteudo) - 1; $i++) {
            $where .= 'BINARY nome != \'' . $arrayConteudo[$i] . '\' AND ';
        }
        $where .= 'BINARY nome != \'' . $arrayConteudo[$i] . '\'';
//        $where .= ' AND preco > 0';
        $query = 'SELECT DISTINCT(BINARY nome) as nome FROM `material` ' . $where . ' ORDER BY nome ASC';
    } else {
        $query = 'SELECT DISTINCT(BINARY nome) as nome FROM `material` ORDER BY nome ASC';
    }
    $consulta = mysqli_query(banco::$connection, $query);
    while ($re = mysqli_fetch_assoc($consulta)) {
        $resultados[] = $re;
    }

    return $resultados;
}

/**
 * Nesta função, simplificamos a maneira de consultar apenas um dado de uma tabela
 *
 * @param string $tabela Nome da tabela a ter dados consultados
 * @param string $campos Quais campos serão selecionados na tabela
 * @param string $onde Onde os dados serão consultados
 */
function ver($tabela, $campos, $onde) {
    mysqli_set_charset(banco::$connection, 'utf8');
    /**
     * Montamos nossa query SQL para pegar apenas um dado
     */
    $query = "SELECT $campos FROM `$tabela`";

    /**
     * Selecionamos onde queremos pegar este dado
     */
    if (!empty($onde)) {
        $query .= " WHERE $onde";
    }

    /**
     * Limitamos para apenas 1 resultado
     */
    $query .= " LIMIT 1";

    /**
     * Preparamos e executamos nossa query
     */
//    echo $query;
    $consulta = mysqli_query(banco::$connection, $query);

    /**
     * Guardamos os resultados dentro do array resultados, que será retornado para a aplicação
     */
    $resultados = mysqli_fetch_assoc($consulta);
    return $resultados;
}

/**
 * Essa função recebe o idcliente do usuario que sofrerá alteração e o idTitular da familia que terá seu titular trocado.
 * @param type int $idclienteNovo  Numero do novo titular.
 * @param type int $idTitular Numero da familia.
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function Transferencia_TrocaTitularFamilia($idclienteNovo, $idTitular) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $idTitular = clienteId($idTitular); // Adicionada linha para converter matricula e clienteid
    removerTitular($idTitular);
    adicionarTitular($idclienteNovo);
    adicionaDependente($idclienteNovo, $idTitular);
    Transferencia_TitularDaPropriaFamilia($idclienteNovo);
}

/**
 * Essa insere um novo dependente em uma familia ja existente.
 * @param type int $id_dependente  Numero do novo Dependente.
 * @param type int $idTitular Numero da familia.
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function Transferencia_AdicionarDependenteFamilia($id_dependente, $idTitular) {
    mysqli_set_charset(banco::$connection, 'utf8');

    $idTitular = clienteId($idTitular); // Adicionada linha para converter matricula em clienteid
    removerDependente($id_dependente);
    if (!adicionaDependente($id_dependente, $idTitular)) {
        removerTitular($id_dependente);
        adicionaDependente($id_dependente, $idTitular);
    }
    $query = 'INSERT INTO `climep`.`dependente` (dependente_id,fk_titular_id,vacina_casa) VALUES(\'' . $id_dependente . '\',\'' . $idTitular . '\',null);';
    $consulta = mysqli_query(banco::$connection, $query);
    reajusteMembros($idnovoTitular);
    return ($consulta);
}

/**
 * Essa função cria uma nova familia contendo de inicio o proprio titular como dependente.
 * @param type int $idnovoTitular  Numero do novo titular.
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function Transferencia_NovaFamilia($idnovoTitular) {
    mysqli_set_charset(banco::$connection, 'utf8');
    removerDependente($idnovoTitular);
    removerTitular($idnovoTitular);
    adicionarTitular($idnovoTitular);
    adicionaDependente($idnovoTitular, $idnovoTitular);
}

/**
 * Essa função altera o usuario de dependente para titular de uma familia
 * @param type int $idnovoTitular  Numero do novo titular.
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function Transferencia_TitularDaPropriaFamilia($idnovoTitular) {
    mysqli_set_charset(banco::$connection, 'utf8');
    var_dump($idnovoTitular);
    $titularAntigo = buscaTitularPorDependente($idnovoTitular);
    $titularId = $titularAntigo['id'];
    adicionarTitular($idnovoTitular); //<-- Aqui
    $queryUpdate = 'UPDATE `climep`.`dependente` SET fk_titular_id = \'' . $idnovoTitular . '\' WHERE fk_titular_id = \'' . $titularId . '\' ';
    mysqli_query(banco::$connection, $queryUpdate);
    var_dump($queryUpdate);
    removerTitular($titularAntigo); //<-- Aqui

    reajusteMembros($idnovoTitular);
}

/**
 * Esta função tem por objetivo retornar um array contendo as todos os clientes que se consultaram com o medico dentro do periodo estabelecido
 * @param  int $idMedico  Numero referente ao medico na tabela.
 * @param  date $PeriodoInicial  Periodo Inicial da Busca
 * @param  date $PeriodoFinal  Periodo Inicial da Busca
 * @return Array Contendo os clientes consultados
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function pegaColsutasporClientesporPeriodo($idMedico, $PeriodoInicial, $PeriodoFinal) {
    $sqlInicial = "SELECT
                    a.*,c.matricula,
                    c.membro,
                    CONCAT(p.nome,' ',p.sobrenome) as 'nome',
                    CONCAT(FLOOR((DATEDIFF(NOW(), p.data_nascimento))/360),'a ',ROUND(12-((360*((DATEDIFF(NOW(), p.data_nascimento))/360 - FLOOR((DATEDIFF(NOW(), p.data_nascimento))/360)))/30)),'m' ) as 'Idade'
                    FROM (SELECT con.cliente_cliente_id as 'cliente_id',MAX(con.data) as 'data' FROM `consultas` con WHERE con.medico_id = '$idMedico' AND con.data >= '$PeriodoInicial' AND con.data <= '$PeriodoFinal' GROUP BY con.cliente_cliente_id) as a 
                    INNER JOIN `cliente` c ON a.cliente_id = c.cliente_id 
                    INNER JOIN `pessoa` p ON c.cliente_id = p.id";
    Database::query($sqlInicial);
    if (Database::rowCount() > 0) {
        $cliente = Database::fetchAll();
        return $cliente;
    }
}

/**
 * Esta função tem por objetivo retornar um array contendo as ultimas 300 consultas realizadas pelo medico
 * @param  int $idMedico  Numero referente ao medico na tabela.
 * @return Array Contendo os id's das ultimas 300 consultas de categoria 6.
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function BuscaConsultasIndexadaPorMedico($idMedico, $index) {
    $sqlInicial = "SELECT DISTINCT(con.cliente_cliente_id) FROM `consultas` con WHERE con.medico_id = '$idMedico' LIMIT $index,1";
//    die($sqlInicial);
    Database::query($sqlInicial);
    if (Database::rowCount() > 0) {
        $cliente = Database::fetch();
        $cliente = $cliente ['cliente_cliente_id'];
        $sqlFinal = "SELECT con.id as consultaID,@a1:= con.medico_id,(SELECT pessoa.nome FROM pessoa WHERe pessoa.id = @a1) as 'medico_consulta', dep.parto_id, dep.gestacao_id, dep.cliente_id clienteId, dep.apgar, dep.idade_gestacional, dep.peso_nascimento, dep.antecedente_pessoal ant_pessoal, dep.antecedente_familiar ant_familiar, dep.alergias, CONCAT(p.nome,' ',p.sobrenome) as 'cliente', CONCAT(med.nome,' ',med.sobrenome) as 'medico', med.nome as medicoNome, p.data_nascimento, titu.matricula , con.* 
                FROM dependente a 
                INNER JOIN cliente dep ON dep.cliente_id = a.dependente_id
                INNER JOIN cliente titu ON titu.cliente_id = a.fk_titular_id
                INNER JOIN consultas con ON con.cliente_cliente_id = dep.cliente_id
                INNER JOIN pessoa p ON p.id = dep.cliente_id
                INNER JOIN pessoa p_titular ON p_titular.id = titu.cliente_id
                INNER JOIN pessoa med ON med.id = dep.medico_id
                INNER JOIN pessoa consultaMed ON consultaMed.id = dep.medico_id
                WHERE con.cliente_cliente_id='$cliente' ORDER BY con.data DESC";
//        die($sqlFinal);
        Database::query($sqlFinal);
        $linhas = Database::rowCount();
        if ($linhas > 0) {
            $array[0] = Database::fetchAll();
            return $array;
        } else {
            return '0';
        }
    } else {
        return '0';
    }
}

function adicionarTitular($idtitular) {
    $cliente = buscaClienteById($idtitular);
    $queryInsert = 'INSERT INTO `climep`.`titular` (titular_id,origem_id,nome_nf,doc_nf,data_pamp,categoria_id) VALUES(\'' . $idtitular . '\', \'1\', \'' . $cliente['nome'] . ' ' . $cliente['sobrenome'] . '\',\'00000000000\',\'\',\'1\')';
    var_dump($queryInsert);
    return mysqli_query(banco::$connection, $queryInsert);
}

function removerTitular($idtitular) {
    foreach (buscaDependentesPorTitular($idtitular) as $dependente) {
        if ($dependente['id'] !== $idtitular) {
            Transferencia_TitularDaPropriaFamilia($dependente['id']);
        }
    }
    $queryInsert = 'DELETE FROM `climep`.`titular` WHERE titular_id = \'' . $idtitular . '\'';
    var_dump($queryInsert);
    mysqli_query(banco::$connection, $queryInsert);
}

function adicionaDependente($idDependente, $idtitular) {
    $queryInsert = 'INSERT INTO `climep`.`dependente`(dependente_id,fk_titular_id,vacina_casa) VALUES(\'' . $idDependente . '\',\'' . $idtitular . '\',null)';
    var_dump($queryInsert);
    mysqli_query(banco::$connection, $queryInsert);
}

function removerDependente($id) {
    $queryDelete = 'DELETE FROM `climep`.`dependente` WHERE dependente_id = \'' . $id . '\'';
    mysqli_query(banco::$connection, $queryDelete);
}

function reajusteMembros($idtitular) {
    $posicaoAtual = 1;
    $queryMembroUpdate = '';
    foreach (buscaDependentesPorTitular($idtitular) as $dependente) {
        if ($dependente['cliente_id'] == $idtitular) {
            $queryMembroUpdate = 'UPDATE `climep`.`cliente` SET membro = \'1\' WHERE cliente_id = \'' . $idtitular . '\'';
        } else {
            $queryMembroUpdate = 'UPDATE `climep`.`cliente` SET membro = \'' . ($posicaoAtual + 1) . '\' WHERE cliente_id = \'' . $dependente['cliente_id'] . '\'';
            $posicaoAtual++;
        }
        var_dump($queryMembroUpdate);
        mysqli_query(banco::$connection, $queryMembroUpdate);
    }
}

function verificaMaterialModulo($material) {
    $sql = 'SELECT tipo_material_id FROM climep.material WHERE id = \'' . $material . '\'';
    $resultado = mysqli_query(banco::$connection, $sql);
    $linha = mysqli_fetch_assoc($resultado);
    $tipo = $linha['tipo_material_id'];
    if ($tipo == 1 || $tipo == 3)
        return 1;
    else
        return $tipo;
}

function gerarControleDeImunoTerapia($depsId, $date = null, $maior = false) {
    if ($date === null)
        $date = date('Y-m-d');
    $sql = 'SELECT servico.id,cliente.Membro,pessoa.nome, pessoa.sobrenome, cliente.cliente_id, servico.material_id,material.nome as \'material_nome\' FROM servico 
INNER JOIN cliente ON cliente_cliente_id = cliente.cliente_id
INNER JOIN pessoa ON cliente_cliente_id = pessoa.id
INNER JOIN material ON material.id = servico.material_id
WHERE data ' . (($maior === true) ? '>' : '=') . ' \'' . $date . '\'  AND servico.status_id != 15 AND servico.tipo_servico = \'1\' AND cliente_cliente_id IN (' . implode(',', $depsId) . ') AND material.tipo_material_id = 2'
            . ' GROUP BY servico.material_id,servico.tipo_servico '
            . ' UNION ALL'
            . ' SELECT servico.id,cliente.Membro,pessoa.nome, pessoa.sobrenome, cliente.cliente_id, servico.material_id,material.nome as \'material_nome\' FROM servico 
INNER JOIN cliente ON cliente_cliente_id = cliente.cliente_id
INNER JOIN pessoa ON cliente_cliente_id = pessoa.id
INNER JOIN material ON material.id = servico.material_id
WHERE data ' . (($maior === true) ? '>' : '=') . ' \'' . $date . '\' AND servico.tipo_servico = \'0\' AND cliente_cliente_id IN (' . implode(',', $depsId) . ') AND material.tipo_material_id = 2';
//	die(var_dump($sql));
    $resultQuery = mysqli_query(banco::$connection, $sql);
    while ($row = mysqli_fetch_assoc($resultQuery)) {
        $data[] = $row;
    }
    return $data;
}

function gerarControleDeImunoTerapiaJaPago($depsId) {
    if ($date === null)
        $date = date('Y-m-d');
    $sql = "SELECT s.id,c.Membro,p.nome, p.sobrenome, c.cliente_id, s.material_id,CONCAT(m.nome,' - (Dose)') as 'material_nome'
FROM `imuno_controle` i inner join servico s ON s.id = i.servico_id INNER JOIN pessoa p ON p.id = s.cliente_cliente_id INNER JOIN material m ON m.id = s.material_id INNER JOIN cliente c ON c.cliente_id = s.cliente_cliente_id 
WHERE s.status_id = 15 AND cliente_cliente_id IN (" . implode(',', $depsId) . ") AND m.tipo_material_id = 2 "
            . " UNION ALL "
            . " SELECT s.id,c.Membro,p.nome, p.sobrenome, c.cliente_id, s.material_id,CONCAT(m.nome,' - (Frasco/Dose)') as 'material_nome'
FROM servico s INNER JOIN pessoa p ON p.id = s.cliente_cliente_id INNER JOIN material m ON m.id = s.material_id INNER JOIN cliente c ON c.cliente_id = s.cliente_cliente_id INNER JOIN historico h ON h.servico_id = s.id
WHERE s.status_id = '15' AND h.status_pagamento = '15' AND cliente_cliente_id IN (" . implode(',', $depsId) . ")  AND m.tipo_material_id = 2 ";
//		die(var_dump($sql));
    $resultQuery = mysqli_query(banco::$connection, $sql);
    while ($row = mysqli_fetch_assoc($resultQuery)) {
        $data[] = $row;
    }
    return $data;
}

function buscaModulosDeImunoterapiaPorClientes($depsId) {
    $str_ids = implode(',', $depsId);
    $sql = 'SELECT * FROM `material` m INNER JOIN `grupo_material` g ON m.grupo_material_id = g.id INNER JOIN `servico` s ON m.id = s.material_id INNER JOIN `cliente` c ON c.cliente_id= s.cliente_cliente_id IN (' . $str_ids . ') INNER JOIN pessoa ON pessoa.id = s.cliente_cliente_id WHERE g.id = 1';
    return mysqli_query(banco::$connection, $sql);
}

function setHorarioDeTrabalho($medico_ID, $mInicio, $mFim, $tInicio, $tFim) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $cout = 'SELECT count(*) as count FROM `horario` WHERE medico_id = ' . $medico_ID . '';
    $cout = mysqli_query(banco::$connection, $cout);
    $cout = mysqli_fetch_assoc($cout);
    if ($cout['count'] == 0) {
        $sql = 'INSERT INTO `horario`(`medico_id`, `horario_inicio_manha`, `horario_fim_manha`, `horario_inicio_tarde`, `horario_fim_tarde`) VALUES (\'' . $medico_ID . '\',\'' . $mInicio . '\',\'' . $mFim . '\',\'' . $tInicio . '\',\'' . $tFim . '\')';
    } else {
        $sql = 'UPDATE `horario` SET `horario_inicio_manha` = \'' . $mInicio . '\', `horario_fim_manha` = \'' . $mFim . '\', `horario_inicio_tarde` = \'' . $tInicio . '\', `horario_fim_tarde` = \'' . $tFim . '\' WHERE medico_id = \'' . $medico_ID . '\'';
    }
    mysqli_query(banco::$connection, $sql);
}

function pegaHorariosDeTrabalho($medico_ID) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $cout = 'SELECT * FROM `horario` WHERE medico_id = ' . $medico_ID;

    $cout = mysqli_query(banco::$connection, $cout);
    return mysqli_fetch_assoc($cout);
}

function getMensagemPrivada($medico_ID) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $cout = 'SELECT * FROM `mensagem_interna` WHERE medico_id = ' . $medico_ID;
    $cout = mysqli_query(banco::$connection, $cout);
    return mysqli_fetch_assoc($cout);
}

function setMensagemPrivada($medico_ID, $mensagem) {
    $cout = 'SELECT count(*) as count FROM `mensagem_interna` WHERE medico_id = ' . $medico_ID;
    $cout = mysqli_query(banco::$connection, $cout);
    $cout = mysqli_fetch_assoc($cout);
    if ($cout['count'] == 0) {
        $sql = 'INSERT INTO `mensagem_interna` (`medico_id`, `mensagem`) VALUES (\'' . $medico_ID . '\', \'' . $mensagem . '\');';
        mysqli_query(banco::$connection, $sql);
    } else {
        $sql = 'UPDATE `mensagem_interna` SET  `mensagem`= \'' . $mensagem . '\' WHERE medico_id = ' . $medico_ID;
        mysqli_query(banco::$connection, $sql);
    }
}

function pegaHorarios($medicoId, $horaMenor, $horaMaior, $cancel, $dia) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = 'SELECT aten.login as atendente, ag.nome_ag as nome, s.nome as status, ag.hora_chegada, ag.contato_ag, proc.descricao, conv.nome as convenio, ag.*, ag.responsavel as resp_ag
            FROM agendamento as ag
            LEFT JOIN status as s ON s.id = ag.status_id 
            LEFT JOIN procedimento as proc ON proc.id = ag.convenio_has_procedimento_has_tabela_procedimento_id 
            LEFT JOIN convenio as conv ON conv.id = ag.convenio_has_procedimento_has_tabela_convenio_id 
            LEFT JOIN usuario as aten ON aten.usuario_id = agendador_id
            WHERE ag.data_agendamento = \'' . $dia . '\' 
            AND ag.medico_id = \'' . $medicoId . '\'
            AND ag.status_id ' . (($cancel) ? '=' : '!=') . ' \'9\'
            AND ag.hora_chegada >= \'' . $horaMenor . '\' AND ag.hora_chegada <= \'' . $horaMaior . '\'';
    $query = mysqli_query(banco::$connection, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        $saida[] = $row;
    }
    return $row;
}

function SalvaHorario($medico_id, $covenio_id, $tabela_id, $procedimento_id, $hora, $vobservacao, $nome, $responsavel, $contato, $celular, $status, $data, $atendente_id) {
    $sql = 'INSERT INTO agendamento (`medico_id`, `convenio_has_procedimento_has_tabela_convenio_id`, `convenio_has_procedimento_has_tabela_tabela_id`, `convenio_has_procedimento_has_tabela_procedimento_id`, `hora_chegada`, `observacao`, `nome_ag`, `responsavel`, `contato_ag`, `celular_ag`, `status_id`, `data_agendamento`, `agendador_id`) VALUES 
            (\'' . $medico_id . '\', \'' . $covenio_id . '\', \'' . $tabela_id . '\', \'' . $procedimento_id . '\', \'' . $hora . '\', \'' . $vobservacao . '\', \'' . $nome . '\', \'' . $responsavel . '\', \'' . $contato . '\', \'' . $celular . '\', \'' . $status . '\', \'' . $data . '\', \'' . $atendente_id . '\')';
    if (mysqli_query(banco::$connection, $sql))
        return('OK');
    else
        return ('nao');
}

function UpdateHorario($medico_id, $covenio_id, $tabela_id, $procedimento_id, $hora, $vobservacao, $nome, $responsavel, $contato, $celular, $status, $data, $atendente_id, $id) {
    $sql = 'UPDATE `agendamento` SET 
                medico_id = \'' . $medico_id . '\', 
                convenio_has_procedimento_has_tabela_convenio_id = \'' . $covenio_id . '\',
                convenio_has_procedimento_has_tabela_tabela_id = \'' . $tabela_id . '\',
                convenio_has_procedimento_has_tabela_procedimento_id = \'' . $procedimento_id . '\',
                hora_chegada = \'' . $hora . '\',
                observacao = \'' . $vobservacao . '\',
                nome_ag = \'' . $nome . '\',
                responsavel = \'' . $responsavel . '\',
                contato_ag = \'' . $contato . '\',
                celular_ag = \'' . $celular . '\',
                status_id =  \'' . $status . '\',
                data_agendamento =  \'' . $data . '\',
                agendador_id =  \'' . $atendente_id . '\'
            WHERE id = ' . $id . ' ;';

    if (mysqli_query(banco::$connection, $sql))
        return ('OK');
    else
        return ('nao');
}

function capturaUltimoClienteMedico($medicoID) {
    try {
        $result = mysqli_query(banco::$connection, "SELECT con.cliente_cliente_id
                         FROM consultas AS con
                         WHERE con.medico_id = '$medicoID'
                         ORDER BY con.data DESC , con.id DESC ");
        $array = mysqli_fetch_array($result);
        return $array['cliente_cliente_id']; //['cliente_cliente_id'];
    } catch (BasicException $ex) {
        return array('Erro' => true, code => $ex->getCode());
    }
}

/**
 * Esta função retorna as consultas de um determinado cliente
 * porém somente as consultas com a categoria 'consulta' com id = 6
 * 
 * @author Victor Gerin (acho)
 * 
 * @param integer $cliente Id do Cliente.
 * 
 * @return Array Array contendo os resultados da pesquisa.
 */
function pegaHistoricoDeAtendimentoDoCliente($cliente) {
    $resultados = array();
	$sql = 'SELECT con.id as consultaID,@a1:= con.medico_id,(SELECT pessoa.nome FROM pessoa WHERe pessoa.id = @a1) as \'medico_consulta\', dep.parto_id, dep.gestacao_id, dep.cliente_id clienteId, dep.apgar, dep.idade_gestacional, dep.peso_nascimento, dep.antecedente_pessoal ant_pessoal, dep.antecedente_familiar ant_familiar, dep.alergias, CONCAT(p.nome,\' \',p.sobrenome) as \'cliente\', CONCAT(med.nome,\' \',med.sobrenome) as \'medico\', med.nome as medicoNome, p.data_nascimento, titu.matricula , con.* 
            FROM dependente a 
            INNER JOIN cliente dep ON dep.cliente_id = a.dependente_id
            INNER JOIN cliente titu ON titu.cliente_id = a.fk_titular_id
            INNER JOIN consultas con ON con.cliente_cliente_id = dep.cliente_id
            INNER JOIN pessoa p ON p.id = dep.cliente_id
            INNER JOIN pessoa p_titular ON p_titular.id = titu.cliente_id
            INNER JOIN pessoa med ON med.id = dep.medico_id
            INNER JOIN pessoa consultaMed ON consultaMed.id = dep.medico_id
            WHERE dep.cliente_id = ' . $cliente . ' 
            AND con.categoria_consultas_id = \'6\'
            ORDER BY con.data DESC , con.id DESC ;';

    Database::query($sql);

	if (Database::rowCount() > 0) {
		$resultados[0] = Database::fetchAll();
	} else {
		$resultados[0] = '';
	}

    return $resultados;
}

function pegaInformaçõesDoCliente($cliente) {
    try {
		$sql = '
            SELECT dep.parto_id, dep.gestacao_id, dep.cliente_id clienteId, dep.apgar, dep.idade_gestacional, dep.peso_nascimento, dep.antecedente_pessoal ant_pessoal, dep.antecedente_familiar ant_familiar, dep.alergias, CONCAT(p.nome,\' \',p.sobrenome) as \'cliente\', CONCAT(med.nome,\' \',med.sobrenome) as \'medico\', med.nome as medicoNome, p.data_nascimento, titu.matricula
            FROM dependente a 
            INNER JOIN cliente dep ON dep.cliente_id = a.dependente_id
            INNER JOIN cliente titu ON titu.cliente_id = a.fk_titular_id
            INNER JOIN pessoa p ON p.id = dep.cliente_id
            INNER JOIN pessoa p_titular ON p_titular.id = titu.cliente_id
            INNER JOIN pessoa med ON med.id = dep.medico_id
            INNER JOIN pessoa consultaMed ON consultaMed.id = dep.medico_id
            WHERE dep.cliente_id = ' . $cliente . ';';
		die($sql);
        Database::query($sql);
        return Database::fetchAll();
    } catch (BasicException $ex) {
        return array('Erro' => true, code => $ex->getCode());
    }
}

/**
 * Esta função retorna os dados de consulta do cliente, de acordo com 
 * a categoria de consulta(Coraçãozinho, orelinha 1 e 2, olhinho ou linguinha).
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param integer $cliente Id do Cliente.
 * @param integer $id_cat_consulta Id da categoria de consulta.
 * @param String $data se a data for prenchida, irá especificar a consulta.
 * 
 * @return Array Array contendo os resultados da pesquisa.
 */
function pegaHistoricoDeAtendimentoDoClienteTriagem($cliente, $id_cat_consulta, $data = '') {

    if (!empty($data)) {
        $str = 'AND con.data = \'' . $data . '\'';
    } else {
        $str = '';
    }

    $resultados = array();
    try {
        $sql = 'SELECT
                con.*
            FROM
                consultas as con 
            WHERE 
                con.categoria_consultas_id = ' . $id_cat_consulta . ' 
                AND
                con.cliente_cliente_id = ' . $cliente . ' ' . $str . '
            ORDER BY
                con.data DESC
            LIMIT 0,1
        ';

        Database::query($sql);

        if (Database::rowCount() > 0) {
            $resultados['consulta'] = Database::fetch();
            if ($id_cat_consulta == 1) {
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_olhinho`
                    WHERE
                        id = \'' . $resultados['consulta']['consulta_olhinho_id'] . '\'
                ');
                if (Database::rowCount() > 0) {//Consulta Campos Olhinho
                    $resultados['olhinho'] = Database::fetchAll();
                }
            } else if ($id_cat_consulta == 2) {
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_orelhinha1` a
                    WHERE
                        a.id = \'' . $resultados['consulta']['consulta_orelhinha1_id'] . '\'
                ');
//                  INNER JOIN `orelhinha1_frequencia` b ON b.id = a.orelhinha1_frequencia_id_od
//                  INNER JOIN `orelhinha1_frequencia` c ON c.id = a.orelhinha1_frequencia_id_oe

                if (Database::rowCount() > 0) {//Consulta Campos Orelhinha
                    $resultados['orelhinha1'] = Database::fetchAll();
                }
            } else if ($id_cat_consulta == 3) {
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_orelhinha2` a
                    WHERE
                        a.id = \'' . $resultados['consulta']['consulta_orelhinha2_id'] . '\'
                ');
//                  INNER JOIN `orelhinha2_resultado_cocleo` b ON b.id = a.orelhinha2_resultado_cocleo_id
//                  INNER JOIN `conclusao_orelhinha` c ON c.id = a.conclusao_orelhinha_od
//                  INNER JOIN `conclusao_orelhinha` d ON d.id = a.conclusao_orelhinha_oe
//                  INNER JOIN `equipamentos_teste` e ON a.equipamentos_teste_id = e.id

                if (Database::rowCount() > 0) {//Consulta Campos Orelhinha
                    $resultados['orelhinha2'] = Database::fetchAll();
                }
            } else if ($id_cat_consulta == 4) {
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_coracaozinho` a
                    WHERE
                        a.id =\'' . $resultados['consulta']['consulta_coracaozinho_id'] . '\'
                ');
                if (Database::rowCount() > 0) {//Consulta Campos Coracaozinho Geral
                    $resultados['coracaozinho'] = Database::fetch();
                }
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_coracaozinho` a
                    INNER JOIN `consulta_coracaozinho_has_coracao_anot_qp` c ON c.consulta_coracaozinho_id = a.id
                    WHERE
                        id = \'' . $resultados['consulta']['consulta_coracaozinho_id'] . '\'
                ');

                if (Database::rowCount() > 0) {//Consulta Campos Coracaozinho QP
                    $resultados['coracaozinho']['qp'] = Database::fetchAll();
                }

                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_coracaozinho` a
                    INNER JOIN `consulta_coracaozinho_has_coracao_anot_hf` b ON b.consulta_coracaozinho_id = a.id
                    WHERE
                        id = \'' . $resultados['consulta']['consulta_coracaozinho_id'] . '\'
                ');
                if (Database::rowCount() > 0) {//Consulta Campos Coracaozinho HF
                    $resultados['coracaozinho']['hf'] = Database::fetchAll();
                }
            } else if ($id_cat_consulta == 5) {
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_linguinha` a
                    WHERE
                        id = \'' . $resultados['consulta']['consulta_linguinha_id'] . '\'
                ');
                if (Database::rowCount() > 0) {//Consulta Campos Linguinha
                    $resultados['linguinha'] = Database::fetch();
                }
                Database::query('
                    SELECT
                        *
                    FROM
                        `consulta_linguinha` a
                    INNER JOIN `consulta_linguinha_has_lingua_anot_qp` b ON b.consulta_linguinha_id = a.id
                    WHERE
                        id = \'' . $resultados['consulta']['consulta_linguinha_id'] . '\'
                ');
                if (Database::rowCount() > 0) {//Consulta Campos Linguinha
                    $resultados['linguinha']['qp'] = Database::fetchAll();
                }
            }
        } else {
            $resultados = '';
        }
        return $resultados;
    } catch (BasicException $ex) {
        return $ex->getMessage();
    }
}

function atualizaDadosDoCliente($dados) {
    $antecedentePessoal = $dados['ant_pessoa'];
    $antecedentefamiliares = $dados['ant_fimiliares'];
    $alergias = $dados['alergia'];
    $cliente = $dados['cliente_id'];
    $apgar = $dados['apgar'];
    $idade_gestacional = $dados['idade_gestacional'];
    $peso_nascimento = $dados['pesoNasc'];
    $parto_id = $dados['partoId'];
    $gestacao_id = $dados['gestacaoId'];

    return Database::query('UPDATE `cliente` SET parto_id = \'' . $parto_id . '\', gestacao_id = \'' . $gestacao_id . '\', idade_gestacional = \'' . $idade_gestacional . '\', apgar = \'' . $apgar . '\', peso_nascimento = \'' . $peso_nascimento . '\', antecedente_pessoal = \'' . $antecedentePessoal . '\', antecedente_familiar = \'' . $antecedentefamiliares . '\', alergias = \'' . $alergias . '\' WHERE cliente_id = \'' . $cliente . '\'');
}

function atualizaDadosConsulta($dados) {
    $tamanho = $dados['Tamanho'];
    $peso = $dados['Peso'];
    $PA = $dados['PA'];
    $texto = $dados['texto'];
    $consulta_id = $dados['consulta_id'];
    return Database::query('UPDATE `consultas` SET texto_descritivo = \'' . $texto . '\', altura = \'' . $tamanho . '\', peso = \'' . $peso . '\', PA = \'' . $PA . '\' WHERE consultas.id = \'' . $consulta_id . '\'');
}

function atualizaModulo($idModulo) {

    $sql = 'UPDATE
				`modulos` 
			SET
				`finalizado` = 1
			WHERE 
				id =' . $idModulo;

    return Database::query($sql);
}

/**
 * Esta função busca o id de uma consultaCoracaozinho já existente
 * para um determinado cliente 
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param integer $idCliente Id do Cliente.
 * 
 * @return Mix id da consulta Coracaozinho se houver, senão retorna false
 */
function buscaIdConsultaCoracazinhoPorCliente($idCliente) {

    $arr = array();

    try {
        Database::query('
            SELECT
                b.id
            FROM
                `consultas` a
            INNER JOIN
                `consulta_coracaozinho` b ON a.consulta_coracaozinho_id = b.id
            WHERE
                b.categoria_consultas_id = \'' . $categoriaConsulta . '\'
                AND
                a.cliente_cliente_id = ' . $idCliente
        );

        if (Database::rowCount() > 0) {
            $arr = Database::fetch();
            return $arr['id'];
        } else {
            return false;
        }
    } catch (BasicException $ex) {
        return $ex->getMessage();
    }
}

/**
 * Esta função retorna as datas das consultas de um cliente de acordo
 * com a categoria especificada.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param integer $cliente Id do CDatabase::fetch();liente.
 * @param integer $id_cat_consulta Id da categoria de consulta.
 * 
 * @return Array Array contendo os resultados da pesquisa.
 */
function buscaConsultasDataPorCategoria($cliente, $id_cat_consulta) {

    $categorias = Array('', 'consulta_olhinho_id', 'consulta_orelhinha1_id', 'consulta_orelhinha2_id', 'consulta_coracaozinho_id', 'consulta_linguinha_id');

    $sql = 'SELECT
                DATE_FORMAT(con.data,\'%d/%m/%Y\') as data,
                con.' . $categorias[$id_cat_consulta] . '
            FROM
                consultas as con
            WHERE 
                con.categoria_consultas_id = ' . $id_cat_consulta . ' 
                AND
                con.cliente_cliente_id = ' . $cliente . '
            ORDER BY
                con.data DESC
    ';
    try {
        Database::query($sql);

        if (Database::rowCount() > 0) {
            $arr = Database::fetchAll();
        } else {
            $arr = '';
        }

        return $arr;
    } catch (BasicException $ex) {
        return $ex->getMessage();
    }
}

/**
 * Esta função Atualiza os dados da tabela consultas.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * @param integer $id Id da consulta.
 * 
 * @return Boolean true se atualizar corretamente, false se 
 * não atualizar o registro
 */
function atualizaConsultas($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('consultas', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Esta função Insere uma nova consulta na tabela consultas.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * 
 * @return integer id da consulta inserida
 */
function adicionaNovaConsulta($dados) {
    $peso = $dados['Peso'];
    $PA = $dados['PA'];
    $altura = $dados['altura'];
    $texto = $dados['texto'];
//    $medicoID = $_SESSION['usuario']['id'];
    $medicoID = 11;
    $clienteID = $dados['clienteID'];
    $data = $dados['data'];
    $idCategoriaConsulta = $dados['categoria_consultas_id'];
    Database::query('INSERT INTO `consultas`(`data`, `cliente_cliente_id`, `medico_id`, `altura`, `peso`, `PA`, `texto_descritivo`, `categoria_consultas_id`) VALUES (\'' . $data . '\',\'' . $clienteID . '\',\'' . $medicoID . '\',\'' . $altura . '\',\'' . $peso . '\',\'' . $PA . '\',\'' . $texto . '\',\'' . $idCategoriaConsulta . '\' )');

    return Database::getLastId();
}

/**
 * Esta função Atualiza os dados da tabela consulta_coracaozinho.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * @param integer $id Id da consulta_coracaozinho.
 * 
 * @return Boolean true se atualizar corretamente, false se 
 * não atualizar o registro
 */
function atualizaConsultaCoracaozinho($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('consulta_coracaozinho', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Esta função Atualiza os dados da tabela consulta_linguinha.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * @param integer $id Id da consulta_linguinha.
 * 
 * @return Boolean true se atualizar corretamente, false se 
 * não atualizar o registro
 */
function atualizaConsultaLinguinha($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('consulta_linguinha', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Esta função Atualiza os dados da tabela consulta_olhinho.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * @param integer $id Id da consulta_linguinha.
 * 
 * @return Boolean true se atualizar corretamente, false se 
 * não atualizar o registro
 */
function atualizaConsultaOlhinho($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('consulta_olhinho', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Esta função Atualiza os dados da tabela consulta_orelhinha1.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * @param integer $id Id da consulta_orelhinha1.
 * 
 * @return Boolean true se atualizar corretamente, false se 
 * não atualizar o registro
 */
function atualizaConsultaOrelhinha1($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('consulta_orelhinha1', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Esta função Atualiza os dados da tabela consulta_orelhinha2.
 * 
 * @author Bruno Haick <brunohaick@gmail.com>
 * 
 * @param Array $dados array cujo o indice corresponde a coluna
 * do banco de dados e o valor corresponde ao valor que será 
 * atribuido a coluna;
 * @param integer $id Id da consulta_orelhinha2.
 * 
 * @return Boolean true se atualizar corretamente, false se 
 * não atualizar o registro
 */
function atualizaConsultaOrelhinha2($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('consulta_orelhinha2', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

function insertCIDonConsultas($idcid, $idconsultas) {
    $query = "SELECT * FROM `consultas_has_cid10` WHERE consultas_id = '$idconsultas' AND cid10_idCID = '$idcid' LIMIT 1";
    Database::query($query);
    $count = Database::rowCount();
    if ($count > 0) {//significa que já existe um insert neste caso é feito um delete na relação, para assim trocar de 1 para 0
        $delete = "DELETE FROM `consultas_has_cid10` WHERE consultas_id = '$idconsultas' AND cid10_idCID = '$idcid'";
        Database::query($delete);
    } else {
        $insert = "INSERT INTO `consultas_has_cid10` values('$idconsultas','$idcid')";
        Database::query($insert);
    }
}

/**
 * Função para buscar cliente a partir de seu id
 * idenpendente se for titular ou dependente
 * 
 * @param int $id
 *
 * @author Bruno Haick
 *
 * @return Array contendo os dados encontrados do 
 * cliente com a determinado id.
 */
function buscaClientePorId($id) {

    $sql = "SELECT 
                    CONCAT(p.nome,' ',p.sobrenome) as 'nome', 
                    CONCAT(FLOOR((DATEDIFF(NOW(), p.data_nascimento))/360),'a ',ROUND(12-((360*((DATEDIFF(NOW(), p.data_nascimento))/360 - FLOOR((DATEDIFF(NOW(), p.data_nascimento))/360)))/30)),'m' ) as 'Idade', 
                    DATE_FORMAT(p.data_nascimento,'%d/%c/%y') as nascimento
            FROM 
                    cliente c 
                    INNER JOIN `pessoa` p ON c.cliente_id = p.id 
            WHERE 
                    c.cliente_id = p.id 
                    AND 
                    c.cliente_id = '$id' 
        ";

    Database::query($sql);

    if (Database::rowCount() > 0) {
        $resultado = Database::fetch();
    }

    return $resultado;
}

/**
 * Essa função altera o usuario de dependente para titular de uma familia
 * @param type int $idnovoTitular  Numero do novo titular.
 * @author Luiz Cortinhas <luizcf14@gmail.com>
 */
function BuscaTeste($idteste) {
    mysqli_set_charset(banco::$connection, 'utf8');
    var_dump($idnovoTitular);
    $titularAntigo = buscaTitularPorDependente($idnovoTitular);
    $titularId = $titularAntigo['id'];
    adicionarTitular($idnovoTitular); //<-- Aqui
    $queryUpdate = 'UPDATE `climep`.`dependente` SET fk_titular_id = \'' . $idnovoTitular . '\' WHERE fk_titular_id = \'' . $titularId . '\' ';
    mysqli_query(banco::$connection, $queryUpdate);
    var_dump($queryUpdate);
    removerTitular($titularAntigo); //<-- Aqui

    reajusteMembros($idnovoTitular);
}

/* * *
 * @author Luiz Cortinhas
 * @description Consulta o banco procurando por clientes na fila de espera do medico.
 * @return Array
 */

function pegaFilaEsperaPorMedico($medicoID) {
    $arr = "";
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'SELECT f.id,p.nome,f.hora_recepcao FROM `fila_espera_consulta` f INNER JOIN `pessoa` p ON f.cliente_id = p.id WHERE f.medico_id = \'' . $medicoID . '\' AND f.data = CURDATE() AND f.hora_atendimento is  NULL ORDER BY f.hora_recepcao DESC;';
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        $arr = Database::fetchAll();
    } else {
        $arr = '';
    }
    return $arr;
}

function pegaTodososAlergenosPorCliente($clienteID) {
    $arr = '';
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'SELECT t . * , r . * , a . * , e.nome AS \'teste_nome\' FROM `testes_realizados` t
INNER JOIN `testes_resultados` r ON t.id = r.testes_realizados_id
INNER JOIN alergeno a ON a.id = r.alergeno_id
INNER JOIN testes e ON e.id = t.testes_id WHERE t.cliente_cliente_id = \'' . $clienteID . '\'';
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        $arr = Database::fetchAll();
    } else {
        $arr = '';
    }
    return $arr;
}

/**
 * @author Luiz Cortinhas
 * @description Esta função é resposavel por retornar todas as controles pendentes em aberto 
 */
function pegaFilaControlePendentesdeHoje() {
    $arr = "";
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				c.*, b.*, p.*, u.login AS 'usuario_nome',
				t.doc_nf AS cpf,
				CONCAT_WS(' ',p.nome, p.sobrenome) AS clienteNome,
				c.id AS id_guia,
				c.id AS id_guia,
			IF (
				LENGTH(
					REPLACE (
						REPLACE (p.`tel_residencial`, '(', ''),
						')',
						''
					)
				) > 8,
				SUBSTRING(
					REPLACE (
						REPLACE (p.`tel_residencial`, '(', ''),
						')',
						''
					),
					1,
					2
				),
				'00'
			) AS 'ddd',
			IF (
				LENGTH(
					REPLACE (
						REPLACE (p.`tel_residencial`, '(', ''),
						')',
						''
					)
				) > 8,
				SUBSTRING(
					REPLACE (
						REPLACE (p.`tel_residencial`, '(', ''),
						')',
						''
					),
					2
				),
				p.`tel_residencial`
			) AS 'telefone_residencial',
			 v.nome as 'categoria',
			 v.id as 'categoria_id'
			FROM
				guia_controle c
			INNER JOIN cliente b ON c.titular_id = b.cliente_id
			INNER JOIN pessoa p ON p.id = b.cliente_id
            LEFT JOIN dependente d ON  d.dependente_id = c.titular_id
			INNER JOIN titular t ON t.titular_id = c.titular_id
			INNER JOIN usuario u ON c.usuario_id = u.usuario_id
			INNER JOIN `convenio` v ON v.id = c.convenio_id
			WHERE
				c.data = CURDATE()
				AND
				c.finalizado = '0'
			ORDER BY c.numero_controle DESC
	";
//	die($sql);
    Database::query($sql);
    if (Database::rowCount() > 0) {
        $arr = Database::fetchAll();
    } else {
        $arr = null;
    }
    return $arr;
}

/**
 * @author Bruno Haick
 * @description Esta função é resposavel por retornar todas as controles Finalizados
 */
function pegaFilaControleFinalizadosdeHoje() {
    $arr = "";
    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = 'SELECT *, CONCAT(p.nome,\' \',p.sobrenome) as clienteNome, c.id as id_guia FROM guia_controle c INNER JOIN cliente b ON c.titular_id = b.cliente_id INNER JOIN pessoa p ON p.id = b.cliente_id  WHERE c.data = DATE(NOW()) AND c.finalizado = \'1\'';

    Database::query($sql);
    if (Database::rowCount() > 0) {
        $arr = Database::fetchAll();
    } else {
        $arr = null;
    }
    return $arr;
}

/**
 * @author Amir
 * @description Retorna os servicos de um determinado controle 
 */
function buscarServicosControle($idControle) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'SELECT status.nome as tipo, pessoa.nome, pessoa.data_nascimento as data,
		    cliente.membro, servico.preco as valor, material.nome as servicos, servico.preco as fpag
            FROM controle, servico, pessoa, cliente, status, material
            WHERE 
            servico.cliente_cliente_id = pessoa.id 
            AND controle.id = $idControle 
            AND controle.servico_id = servico.id
            AND servico.cliente_cliente_id = cliente.cliente_id 
            AND status.id = servico.status_id
            AND servico.material_id = material.id';
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

function pegaBoletimImunobiologico($inicio, $fim) {
    $idadeArray = array(1 => " = '1'", 2 => " = '2'",
        3 => " = '3'", 4 => " = '4'",
        5 => " = '5'", 6 => " = '6'",
        7 => " = '7'", 8 => " = '8'",
        9 => " = '9'", 10 => " = '10'",
        11 => " = '11'", 12 => " = '12'",
        13 => " BETWEEN '13' AND '19'",
        14 => " BETWEEN '20' AND '59'",
        15 => " > '60'");
    $arrayCompleto;
    foreach ($idadeArray as $key => $idade) {
        $sql = "SELECT m.id,m.nome,count(h.id) as 'numero',(YEAR(CURDATE()) - YEAR(p.data_nascimento)) as 'idade'
			FROM `historico` h INNER JOIN `servico` s ON s.id = h.servico_id INNER JOIN `pessoa` p ON s.cliente_cliente_id = p.id
			INNER JOIN `material` m ON m.id = s.material_id
			WHERE h.status_id = '2' AND h.data BETWEEN '$inicio' AND '$fim' AND m.tipo_material_id = '1' AND (YEAR(CURDATE()) - YEAR(p.data_nascimento))  $idade
			GROUP BY s.material_id  ORDER BY m.nome ASC";
        Database::query($sql);
        if (Database::rowCount() > 0) {
            $array = Database::fetchAll();
            $arrayCompleto[$array['id']][$key] = $array;
        }
    }
    return $arrayCompleto;
}

/**
 * @author Amir Zahlan,Luiz Cortinhas
 * @description Recebe o numero da guia de controle e retorna o conjunto (Material,controle) modelado como Servico
 */
function pegaServicosPorGuiaControle($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'SELECT a.*,c.*,s.*,p.data_nascimento,m.preco as \'servico_preco\',m.* ,s.tipo_servico as \'tipo_servico\',c.cliente_id as cliente_id, m.nome as \'material_nome\',
		CONCAT_WS(\' \', p.nome, p.sobrenome) as cliente_nome,
		d.id AS  \'mod.id\', d.preco AS  \'mod.preco\', d.descontoBCG AS  \'mod.descontoBCG\', d.descontoMedico AS \'mod.descontoMedico\', d.descontoPromocional AS  \'mod.descontoPromocional\'
        FROM  `controle` c
        INNER JOIN  `material` m ON c.material_id = m.id
        INNER JOIN cliente a ON c.cliente_id = a.cliente_id
        INNER JOIN servico s ON c.servico_id = s.id
        INNER JOIN pessoa p ON p.id = c.cliente_id
		LEFT JOIN modulos AS d ON d.id = s.modulo_has_material_id
        WHERE c.guia_controle_id =\'' . $id . '\' ';
    //die($SQL);
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Recebe o numero da RPS e retorna o conjunto (Material,controle) modelado como Servico
 */
function pegaServicosPorRPS($id) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'SELECT 
				m.*,
				c.cliente_id as cliente_id,
				m.nome as \'material_nome\',
				CONCAT_WS(\' \', p.nome, p.sobrenome) as cliente_nome, 
				d.id AS \'mod.id\',
				d.preco AS  \'mod.preco\'
			FROM  `controle` c
				INNER JOIN  `material` m ON c.material_id = m.id
				INNER JOIN cliente a ON c.cliente_id = a.cliente_id
				INNER JOIN servico s ON c.servico_id = s.id
				INNER JOIN pessoa p ON p.id = c.cliente_id
				INNER JOIN `rps` r ON r.guia_controle_id = c.guia_controle_id
				LEFT JOIN modulos AS d ON d.id = s.modulo_has_material_id
			WHERE r.id = \'' . $id . '\'';

    Database::query($SQL);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * Atualiza a tabela serviço
 *
 * @author Bruno Haick
 * @date Criação: 21/12/2013
 *
 * @param Array de dados para fazer update
 * @param int $id
 *
 * @return bool
 */
function atualizaServico($dados, $id) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $where = "id = $id";

    if (alterar('servico', $where, $dados)) {
        return true;
    } else {
        return false;
    }
}

/**
 * @author Luiz Cortinhas
 * @description busca modulos do titular
 */
function buscaModulosConfirmados($idTitular) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "SELECT
                        m.*,m.parcelas as 'parcelas_modulo', h.*,a.nome as 'nome_material',a.preco as 'valor_vista',a.preco_cartao as 'valor_cartao',c.membro as 'membro_id',CONCAT_WS('',p.nome,p.sobrenome) as 'membro_nome',p.data_nascimento as 'membro_nascimento'
                FROM
                        `modulos` m
                INNER JOIN `modulos_has_material` h ON m.id = h.modulos_id
                            INNER JOIN `material` a 
                            ON a.id = h.material_id
                            INNER JOIN `cliente` c
                            ON m.cliente_id = c.cliente_id
                            INNER JOIN `pessoa` p 
                            ON p.id = c.cliente_id
                WHERE
                        m.cliente_id IN (
                                SELECT
                                        d.dependente_id
                                FROM
                                        `titular` t
                                INNER JOIN `dependente` d ON t.titular_id = d.fk_titular_id
                                WHERE
                                        fk_titular_id = '$idTitular'
                        )";
//    die(print_r($SQL));
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * @author Luiz Cortinhas
 * @description busca a soma do valor do modulo com desconto e sem desconto
 */
function buscaPrecoModulo($idModulo) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "SELECT
					m.preco_vista as 'ComDesconto',
					m.preco_cartao as 'SemDesconto',
					m.descontoPromocional as 'descPromocional',
					m.descontoMedico as 'descMedico',
					m.descontoBCG as 'descBCG'
            FROM
                    `modulos` m
            WHERE
                    m.id = '$idModulo'";
//    die(print_r($SQL));
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * @author Luiz Cortinhas
 * @description finaliza uma guia de controle, apartir do ID passado.
 */
function finalizaGuiaControleporID($guia_id, $desconto, $status) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $sqlBaixaControle = "SELECT servico_id FROM  `controle` WHERE  `guia_controle_id` ='$guia_id'";
    //die($sqlBaixaControle);
    Database::query($sqlBaixaControle);
    $servicos = Database::fetchAll();
    foreach ($servicos as $servico) {
        $id = $servico['servico_id'];
        $sqlBaixaServico = "UPDATE servico SET servico.status_id = '$status', servico.finalizado = '1' WHERE servico.id = '$id'";
//		die($sqlBaixaServico);
        Database::query($sqlBaixaServico);
    }
	$idUserCaixa = $_SESSION['usuario']['id'];
    $SQL = "UPDATE  `guia_controle` SET finalizado = '1' , desconto = '$desconto', usuario_caixa_id = '$idUserCaixa' WHERE id = '$guia_id';";
    //die($SQL);
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Recebe o nome da bandeira, e retorna o id da bandeira
 */
function pegaBanderiaIdPorNome($nome) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'SELECT c.id FROM cartao_bandeiras c WHERE c.nome LIKE \'' . $nome . '\'';
    Database::query($SQL);
    if (Database::rowCount() > 0) {
        return Database::fetchAll();
    } else {
        return null;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Insere a relaç�o guia_controle x forma_pagamento no banco
 */
function insereGuiaControleFormaPagamento($guiaid, $idFormaPag, $idBandeira, $valor, $parcelas, $autorizacao) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "INSERT INTO `guia_controle_has_forma_pagamento`(guia_controle_id,forma_pagamento_id,bandeira_id,valor,parcelas,autorizacao) VALUES('$guiaid','$idFormaPag','$idBandeira','$valor','$parcelas','$autorizacao');";
//    die($SQL);
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Insere a relaç�o guia_controle x RPS (Nota Fiscal) no banco
 */
function insereGuiaControleRPS($guiaid, $valorTotal, $cpf, $nome, $endereco, $numeroLogradouroTomador, $bairro, $cep, $email) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "INSERT INTO `climep`.`rps` (`id`, `guia_controle_id`, `ValorTotalServicos`, `ValorTotalDeducoes`, `dataEmissaoRPS`,"
            . " `situacaoRPS`, `InscricaoMunicipalTomador`, `CPFCNPJTomador`, `RazaoSocialTomador`, `TipoLogradouroTomador`,"
            . " `LogradouroTomador`, `NumeroEnderecoTomador`, `ComplementoEnderecoTomador`, `TipoBairroTomador`, `BairroTomador`,"
            . " `CidadeTomador`, `CidadeTomadorDescricao`, `CEPTomador`, `EmailTomador`, `CodigoAtividade`, `AliquotaAtividade`,"
            . " `Operacao`, `Tributacao`, `ValorPIS`, `ValorCOFINS`, `ValorINSS`, `ValorIR`, `ValorCSLL`, `AliquotaPIS`,"
            . " `AliquotaCOFINS`, `AliquotaINSS`, `AliquotaIR`, `AliquotaCSLL`, `DescricaoRPS`, `DDDPrestador`, `TelefonePrestador`,"
            . " `DDDTomador`, `TelefoneTomador`, `MotCancelamento`, `CpfCnpjIntermediario`, `enviado`, `numero_nfse`, `nomeTomador`,"
            . " `EstadoTomador`, `TipoRecolhimento`) "
            . "VALUES (NULL, '$guiaid', '$valorTotal', NULL, NULL, NULL, NULL, '$cpf', '$nome', NULL, '$endereco', "
            . "'$numeroLogradouroTomador', NULL, 'BAIRRO', '$bairro', '0000427', 'BELEM', '$cep', '$email', NULL, NULL, NULL, "
            . "'Retido na fonte', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '91', '3227887', "
            . "NULL, NULL, '0', NULL, '$nome', 'PA', NULL);";
//    die(print_r($SQL));
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

function buscaservicoJaexiste($materialID, $clienteID) {
    $sqlServico = "SELECT servico.id as 'servico' FROM `servico` INNER JOIN `material` O"
            . "N material.id = servico.material_id  "
            . "WHERE servico.material_id = '$materialID' "
            . "AND servico.cliente_cliente_id='$clienteID' "
            . "AND material.tipo_material_id = '2' "
            . "AND servico.qtd_doses < material.quantidade_doses";
//	var_dump($sqlServico);
    Database::query($sqlServico);
    if (Database::isValidNumRows(1)) {
        return true;
    } else {
        return false;
    }
}

function atualizaQtdDoseServico($idServico) {
    $sql = "UPDATE `servico` SET qtd_doses = (qtd_doses +1) WHERE servico.id = '$idServico'";
//	var_dump($sql);
    Database::query($sql);
    if (Database::getAffectedRows() == 1) {
        return true;
    } else {
        return false;
    }
}

/*
 * Atualiza a Categoria da Guia de Controle.
 * 
 * Bruno Haick
 */

function atualizaCategoriaGuiaControle($idCategoria, $idGuia) {
    $sql = "UPDATE `guia_controle` SET convenio_id = '$idCategoria' WHERE id = '$idGuia'";

	Database::query($sql);
    if (Database::getAffectedRows() == 1) {
        return true;
    } else {
        return false;
    }
}

function buscahistoricoJaexiste($servicoID, $data) {
    $sqlServico = "SELECT historico.id as 'historico' FROM"
            . " `servico` INNER JOIN `historico` ON servico.id = historico.servico_id "
            . "WHERE servico.id = '$servicoID' "
            . "AND historico.data = '$data';";
    Database::query($sqlServico);
    if (Database::isValidNumRows(1)) {
        return true;
    } else {
        return false;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Insere a Fatura do caixa no banco
 */
function insereGuiaControleFatura($usuarioID, $planocontas, $autorizacao) {
//	die(var_dump($planocontas));
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "INSERT INTO `fatura`( `usuario_id`, `plano_contas_id`, `moeda_id`, `empresa_id`, `clientes_cnpj_id`, "
            . "`tipo_doc_id`, `banco_id`, `status_id`, `fila_espera_caixa_id`, `cartao_bandeiras_id`, `tipo_cliente_id`, "
            . "`data_lancamento`, `data_emissao`, `numero`, `observacao`, `numero_fatura`, `taxa`) "
            . "VALUES ('$usuarioID','$planocontas','1','1','243','14','999','18',null,"
            . "NULL,'11',CURDATE(),CURDATE(),NULL,NULL,'$autorizacao',null)";
//    die(print_r($SQL));
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return Database::getLastId();
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Insere a Fatura do caixa no banco
 */
function insereGuiaControleFaturaParcelas($fatura_id, $usuarioID, $valor, $numeroParcela, $desconto, $numero_parcela) {
//	die(var_dump($planocontas));
    $intervalo = 30 * $numeroParcela;
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "INSERT INTO `fatura_parcelas`(`fatura_id`, `status_id`, `tipo_operacao_id`, `usuario_baixou_id`, "
            . "`conta_corrente_id`, `data_vencimento`, `numero`, `valor`, `data_baixa`, `desconto`, `multa`, `juros`,"
            . " `valor_pago`, `valor_a_pagar`) "
            . "VALUES ('$fatura_id','18','238','$usuarioID','2',DATE_ADD(CURDATE() ,INTERVAL $intervalo DAY),'$numero_parcela',"
            . "'$valor',NULL,'$desconto','0','0','0','$valor')";
//    die(print_r($SQL));
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Insere a relaç�o guia_controle x Estoque (Tabela Movimentacao) no banco
 */
function insereGuiaControleBaixaEstoque($materialid, $lote, $quantidade, $data, $usuarioid) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "INSERT INTO `climep`.`movimentacao` (`id`, `material_id`, `lote_id`, `quantidade`, `data`, "
            . "`flag`, `local_movimentacao_origem_id`, `local_movimentacao_destino_id`, `usuario_id`) "
            . "VALUES (NULL, '$materialid', '$lote', '$quantidade', NOW(), 'S', '2', '4', '$usuarioid');";
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Modifica a forma de pagamento feita para uma guia
 */
function modificaControleParaFormadePagamento($servico_id, $forma_pagamento) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = 'UPDATE `controle` SET forma_pagamento = \'' . $forma_pagamento . '\' WHERE servico_id = \'' . $servico_id . '\';';
    // die(var_dump($SQL));    
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Modifica o status do servico para PAGO apartir do id 
 */
function modificaStatusServicoParaPago($id, $status) {
    mysqli_set_charset(banco::$connection, 'utf8');
    $SQL = "UPDATE `servico` SET status_id ='$status' WHERE id = '$id';";
//    die($SQL);
    Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * @author Luiz Cortinhas
 * @description Modifica o status do servico para PAGO apartir do id 
 */
function InsereHistoricoPorServico($servicoid) {
    mysqli_set_charset(banco::$connection, 'utf8'); //@TODO nunca Voltar praca..nunca
    $sqlBusca = "SELECT m.tipo_material_id as 'tipo',h.id as 'id',h.status_id as 'status',h.status_pagamento as 'pagamento',s.status_id as 'status_servico',s.tipo_servico as 'tipo_servico' FROM `servico` s INNER JOIN `historico` h ON s.id = h.servico_id INNER JOIN `material` m ON m.id = s.material_id WHERE s.id = '$servicoid'";
    Database::query($sqlBusca);
    $historicos = Database::fetchAll();
    foreach ($historicos as $historico) {
        if ($historico['status_servico'] == '15' && $historico['pagamento'] == '15' && $historico['tipo_servico'] == '1') {
            $sql = "UPDATE `climep`.`historico` SET status_pagamento = '15',status_id = '1' WHERE id = '" . $historico['id'] . "'";
//			die(print_r($sql));
            Database::query($sql);
            break;
        } else {
            $id = $historico['id'];
            if ($historico['tipo'] != '2') {
                if ($historico['status'] == '2') {//Programado
                    $status_id = '4';
                } else if ($historico['status'] == '6') { // A Realizar Hoje
                    $status_id = '1';
                } else if ($historico['status'] == '4') { // A Realizar Hoje
                    $status_id = '1';
                }
                $SQL = "UPDATE `climep`.`historico` SET status_id = '$status_id',status_pagamento = '15' WHERE id='$id'";
                Database::query($SQL);
            } else {
                $SQL = "UPDATE `climep`.`historico` SET status_pagamento = '15' WHERE id='$id'";
                Database::query($SQL);
            }
        }
    }
//	$SQL = "UPDATE `climep`.`servico` SET status_id = '15' WHERE servico_id='$servicoid'";
//	Database::query($SQL);
    if (Database::getAffectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

function buscaIdPlanoContasPorTipoOperacaoId($tipo_operacao_id) {

    $sql = "SELECT
				plano_contas.id
			FROM
				plano_contas LEFT JOIN tipo_operacao
				ON tipo_operacao.plano_contas_id = plano_contas.id
			WHERE
				tipo_operacao.id = '$tipo_operacao_id'
	";
//	die($sql);
    Database::query($sql);
    if (Database::rowCount() > 0) {
        $arr = Database::fetch();
        return $arr['id'];
    } else {
        return null;
    }
}

/**
 * Função para buscar a categoria
 * de um determinado Titular;
 * 
 * @param int $idTitular
 *
 * @author Bruno Haick
 * @date Criação: 27/01/2015
 *
 * @return
 * 	int $idCategoria;
 *
 */
function buscaCategoriaTitular($idTitular) {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				categoria_id
			FROM
				titular
			WHERE 
				titular_id = '$idTitular'
	";

	Database::query($sql);
    if (Database::rowCount() > 0) {
        $arr = Database::fetch();
        return $arr['categoria_id'];
    } else {
        return null;
    }
}

function buscaNovoNumeroOrdemFilaEsperaVacina() {

    mysqli_set_charset(banco::$connection, 'utf8');
    $sql = "SELECT
				MAX(num_ord) as ord
			FROM
				fila_espera_vacina
			WHERE
				data = CURDATE()
	";
    $query = mysqli_query(banco::$connection, $sql);
    $arr = mysqli_fetch_assoc($query);

    if (!empty($arr['ord'])) {
        return $arr['ord'] + 1;
    } else {
        return 1;
    }
}
