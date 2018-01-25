<?php

if ($_POST['flag'] == "insereTestesCutaneos") {

    $dados = $_POST['dados'];
    $idCliente = $_POST['idCliente'];
    $data = date('Y-m-d');
    $dadosArray = array();
    $j = 0;

    // colocando os dados no array
    for ($i = 0; $i < count($dados); $i++) {
        $dadosArray[$j]['teste'] = $dados[$i][0];
        $dadosArray[$j]['alergeno'] = $dados[$i][1];
        $dadosArray[$j]['resultado'] = $dados[$i][2];
        $j++;
    }

    $j = 0;
    //dados Organizados
    for ($i = 0; $i < count($dadosArray); $i++) {
        if ($dadosArray[$i]['teste'] != $dadosArray[$i - 1]['teste']) {
            $j++;
            $dadosOrg[$j]['teste'] = $dadosArray[$i]['teste'];
        }
        $dadosOrg[$j]['resultados'][] = $dadosArray[$i]['resultado'];
        $dadosOrg[$j]['alergenos'][] = $dadosArray[$i]['alergeno'];
    }

    //percorrendo o array organizado para enviar ao banco
    for ($i = 1; $i <= count($dadosOrg); $i++) {
        //insereteste
        $idInsertTeste = insereTesteCutaneo($dadosOrg[$i]['teste'], $data, $idCliente);
        //die("Id do ultimo teste:".$idInsertTeste); ID OK!
        //die(print_r($dadosOrg));
        for ($j = 0; $j < count($dadosOrg[$i]['resultados']); $j++) {
            //insereosresultados
            $alergenoId = buscaAlergeno($dadosOrg[$i]['alergenos'][$j], $dadosOrg[$i]['teste']);
            insereResultadoTeste($idInsertTeste, $alergenoId, $dadosOrg[$i]['resultados'][$j]);
        }
    }

    // nao ta fazendo verificação se foi inserido ou nao todos os usuarios
    $msg = "Testes Inseridos com sucesso";
    echo saidaJson($msg);
    die();
} else if ($_POST['flag'] == 'TabelaHistoricoConsulta') {
    $idCliente = _INPUT('idCliente', 'int', 'post');
    $historicoCliente = buscaConsultasCliente($idCliente, 'cliente');
    foreach ($historicoCliente as $historicoConsulta) {
        $dadosMedico = dadosMedicoPorId($historicoConsulta['medico_medico_id']);
        echo "
			<tr title='" . $historicoConsulta['texto'] . "' name='table-color' class='dayhead' onclick='carregaClienteHistoricoConsulta(" . $idCliente . "," . $historicoConsulta['id'] . ");'>
				<th align='center'>" . $historicoConsulta['id'] . "</th>
				<th align='center'>" . converteData($historicoConsulta['data']) . "</th>
				<th align='center'>" . $historicoConsulta['hora'] . "</th>
				<th align='center'>" . $dadosMedico['nome'] . "</th>
			</tr>
		";
    }
    $contadorHistorico = count($historicoCliente);
    if ($contadorHistorico == 0) {
        echo "
			<tr name='table-color' class='dayhead'>
			    <th colspan='4' style='text-align:center;'>No data available in table</th>
			</tr>
		";
    } else {
        while ($contadorHistorico < 3) {
            echo "
				<tr name='table-color' class='dayhead'>
					<th align='center' height='15px'>  </th>
					<th align='center'>  </th>
					<th align='center'>  </th>
					<th align='center'>  </th>
				</tr>
			";
            $contadorHistorico++;
        }
    }
} else if ($_POST['flag'] == 'HistoricoConsulta') {

    $idCliente = _INPUT('idCliente', 'int', 'post');
    $idConsulta = _INPUT('idConsulta', 'int', 'post');
    $consulta = buscaConsultasCliente($idConsulta, 'consulta');

    $dadosConsulta['cm'] = $consulta[0]['cm'];
    $dadosConsulta['kg'] = $consulta[0]['kg'];
    $dadosConsulta['PA'] = $consulta[0]['PA'];
    $dadosConsulta['data'] = $consulta[0]['data'];
    $dadosConsulta['data2'] = converteData($consulta[0]['data']);
    $dadosConsulta['texto'] = $consulta[0]['texto'];
    $dadosConsulta['prescricao'] = $consulta[0]['prescricao'];
    $dadosConsulta['hipotese_diag'] = $consulta[0]['hipotese_diag'];
    $dadosConsulta['atestado'] = $consulta[0]['atestado'];
    $dadosConsulta['resultado_encaminhamentos'] = $consulta[0]['resultado_encaminhamentos'];

    $prestadorServico = buscaServicoPrestador($idCliente);
    $i = 0;
    foreach ($prestadorServico as $teste) {
        $prestador = buscaPrestadorById($teste['prestador_id']);
        $servico = buscaRequisicaoServicoById($teste['requisicao_servico_id']);
        $dadosConsulta['id'][$i] = $idConsulta . "-" . $teste['prestador_id'] . "-" . $teste['requisicao_servico_id'];
        $dadosConsulta['prestador'][$i] = $prestador['nome'];
        $dadosConsulta['servicos'][$i] = explode(" -", $servico['subtestes']);
        $i++;
    }

    echo saidaJson($dadosConsulta);
} else if ($_POST['flag'] == 'FilaEspera' || $_POST['flag'] == 'modalPesquisar') {

    $idCliente = _INPUT('idCliente', 'int', 'post');
    $cliente = buscaClienteById($idCliente);
    $medico = dadosMedicoPorId($cliente['fk_medico_id']);
    $dif = diferenca_data($cliente['data_nascimento'], date('Y-m-d'));

    $triagemCoracaozinho = buscaTriagemCoracaozinhoCliente($idCliente, 'ult_consulta');
    $triagemLinguinha = buscaTriagemLinguinhaCliente($idCliente, 'ult_consulta');
    $triagemOlhinho = buscaTriagemOlhinhoCliente($idCliente, 'ult_consulta');
    $triagemOrelhinha1 = buscaTriagemOrelhinha1Cliente($idCliente, 'ult_consulta');
    $triagemOrelhinha2 = buscaTriagemOrelhinha2Cliente($idCliente, 'ult_consulta');

    $dados['olhinho-existe'] = 0;
    if (!empty($triagemOlhinho)) {
        $dados['olhinho-existe'] = 1;
    }
    $dados['orelhinha1-existe'] = 0;
    if (!empty($triagemOrelhinha1)) {
        $dados['orelhinha1-existe'] = 1;
    }
    $dados['orelhinha2-existe'] = 0;
    if (!empty($triagemOrelhinha2)) {
        $dados['orelhinha2-existe'] = 1;
    }
    $dados['coracaozinho-existe'] = 0;
    if (!empty($triagemCoracaozinho)) {
        $dados['coracaozinho-existe'] = 1;
        $triagemHfCoracaozinho = buscaHfCoracaozinho($triagemCoracaozinho['id']);
        $triagemQpCoracaozinho = buscaQpCoracaozinho($triagemCoracaozinho['id']);
    }
    $dados['linguinha-existe'] = 0;
    if (!empty($triagemLinguinha)) {
        $dados['linguinha-existe'] = 1;
        $triagemQpLinguinha = buscaQpLinguinha($triagemLinguinha['id']);
        $triagemResultadoLinguinha = buscaResultadoLinguinha($triagemLinguinha['id']);
    }

    $dados['nome'] = $cliente['nome'] . " " . $cliente['sobrenome'];
    $dados['medico'] = $medico['nome'];
    $dados['medico_id'] = $cliente['fk_medico_id'];
    $dados['matricula'] = $cliente['id'];
    $dados['idade'] = $dif['ano'] . "a " . $dif['mes'] . "m " . $dif['dia'] . "d";
    $dados['email'] = $cliente['email'];
    $dados['cep'] = $cliente['cep'];
    $dados['endereco'] = $cliente['endereco'];
    $dados['telefone'] = $cliente['tel_residencial'];
    $dados['parto'] = $cliente['parto_id'];
    $dados['gestacao'] = $cliente['gestacao_id'];

    $dados['antecedente_pessoal'] = $cliente['antecedente_pessoal'];
    $dados['antecedente_familiar'] = $cliente['antecedente_familiar'];
    $dados['alergias'] = $cliente['alergias'];
    $dados['idade_gestacional'] = $cliente['idade_gestacional'];
    $dados['peso_nascimento'] = $cliente['peso_nascimento'];
    $dados['apgar'] = $cliente['apgar'];

    $dados['coracao-mao-direita'] = $triagemCoracaozinho['perc_maoDireita'];
    $dados['coracao-pe'] = $triagemCoracaozinho['perc_pe'];
    $dados['coracao-diferenca'] = $triagemCoracaozinho['perc_diferenca'];
    $dados['coracao-outros-exames'] = $triagemCoracaozinho['outros_exames'];
    $dados['coracao-observacoes'] = $triagemCoracaozinho['observacao'];
    $dados['coracao-conclusao'] = $triagemCoracaozinho['conclusao_teste'];
    $dados['coracao-id'] = $triagemCoracaozinho['id'];
    foreach ($triagemHfCoracaozinho as $hf) {
        $dados['coracao-hf'][] = $hf['coracao_anot_hf_id'];
    }

    foreach ($triagemQpCoracaozinho as $qp) {
        $dados['coracao-qp'][] = $qp['coracao_anot_qp_id'];
    }

    $dados['linguinha-outros-exames'] = $triagemLinguinha['outros_exames'];
    $dados['linguinha-observacoes'] = $triagemLinguinha['observacao'];
    $dados['linguinha-reavaliacao'] = converteData($triagemLinguinha['conclusao_reavaliacao']);
    $dados['linguinha-checkbox-cn'] = $triagemLinguinha['conclusao_normal'];
    $dados['linguinha-checkbox-ca'] = $triagemLinguinha['conclusao_alteracao'];

    foreach ($triagemQpLinguinha as $qp) {
        $dados['linguinha_qp'][] = $qp['lingua_anot_qp_id'];
    }

    foreach ($triagemResultadoLinguinha as $result) {
        $dados['linguinha-resultado'][] = $result['lingua_resultados_id'];
    }
    $dados['O1-OD-TEOAE'] = $triagemOrelhinha1['teoae_od'];
    $dados['O1-OD-NOISE'] = $triagemOrelhinha1['noise_od'];
    $dados['O1-OD-frequencia'] = $triagemOrelhinha1['frequencia_od'];
    $dados['O1-OE-TEOAE'] = $triagemOrelhinha1['teoae_oe'];
    $dados['O1-OE-NOISE'] = $triagemOrelhinha1['noise_oe'];
    $dados['O1-OE-frequencia'] = $triagemOrelhinha1['frequencia_oe'];
    $dados['O1-meato-OD'] = $triagemOrelhinha1['obstrucao_meato_od'];
    $dados['O1-meato-OE'] = $triagemOrelhinha1['obstrucao_meato_oe'];
    $dados['O1-localizacao'] = $triagemOrelhinha1['localizacao_meato'];
    $dados['O1-observacoes'] = $triagemOrelhinha1['observacao'];
    $dados['O1-reavaliacao'] = converteData($triagemOrelhinha1['reavaliacao']);

    $dados['O2-OD-conclusao'] = $triagemOrelhinha2['conclusao_orelhinha_od'];
    $dados['O2-OE-conclusao'] = $triagemOrelhinha2['conclusao_orelhinha_oe'];
    $dados['O2-equipamento'] = $triagemOrelhinha2['equipamentos_teste_id'];
    $dados['O2-meato-OD'] = $triagemOrelhinha2['obstrucao_meato_od'];
    $dados['O2-meato-OE'] = $triagemOrelhinha2['obstrucao_meato_oe'];
    $dados['O2-cocleo'] = $triagemOrelhinha2['orelinha2_resultado_cocleo_id'];
    $dados['O2-observacoes'] = $triagemOrelhinha2['observacao'];
    $dados['O2-reavaliacao'] = converteData($triagemOrelhinha2['reavaliacao']);

    $dados['olhinho-resultado-od'] = $triagemOlhinho['resultado_od'];
    $dados['olhinho-resultado-oe'] = $triagemOlhinho['resultado_oe'];
    $dados['olhinho-anotacoes-op'] = $triagemOlhinho['anotacoes'];
    $dados['olhinho-anotacoes-hf'] = $triagemOlhinho['hf'];
    $dados['olhinho-anotacoes-outros'] = $triagemOlhinho['outros_exames'];
    $dados['olhinho-anotacoes-obs'] = $triagemOlhinho['observacao'];
    $dados['olhinho-reteste-data'] = converteData($triagemOlhinho['data_reteste']);
    $dados['olhinho-reteste-ok'] = $triagemOlhinho['data_reteste'];
    $dados['olhinho-sug-funcao-normal'] = $triagemOlhinho['visual_normal'];
    $dados['olhinho-sug-funcao-anormal'] = $triagemOlhinho['visual_anormal'];

    echo saidaJson($dados);
} else if ($_POST['flag'] == "consulta") {

    $idCliente = _INPUT('idCliente', 'int', 'post');
    $idMedico = $_SESSION['usuario']['id']; // _INPUT('idMedico','int','post');

    if (!empty($_POST['antecedente_pessoal']))
        $dadosCliente['antecedente_pessoal'] = _INPUT('antecedente_pessoal', 'string', 'post');
    if (!empty($_POST['antecedente_familiar']))
        $dadosCliente['antecedente_familiar'] = _INPUT('antecedente_familiar', 'string', 'post');
    if (!empty($_POST['alergias']))
        $dadosCliente['alergias'] = _INPUT('alergias', 'string', 'post');
    if (!empty($_POST['idade_gestacional']))
        $dadosCliente['idade_gestacional'] = _INPUT('idade_gestacional', 'string', 'post');
    if (!empty($_POST['peso_nascimento']))
        $dadosCliente['peso_nascimento'] = _INPUT('peso_nascimento', 'string', 'post');
    if (!empty($_POST['apgar']))
        $dadosCliente['apgar'] = _INPUT('apgar', 'string', 'post');
    if (!empty($_POST['fk_medico_id']))
        $dadosCliente['medico'] = _INPUT('medico', 'string', 'post');
    if (!empty($_POST['gestacao']))
        $dadosCliente['gestacao_id'] = _INPUT('gestacao', 'string', 'post');
    if (!empty($_POST['parto']))
        $dadosCliente['parto_id'] = _INPUT('parto', 'string', 'post');
    if (!empty($_POST['data']))
        $dadosConsulta['data'] = _INPUT('data', 'string', 'post');

    $dadosConsulta['cliente_cliente_id'] = $idCliente;
    $dadosConsulta['medico_medico_id'] = $idMedico;

    if (!empty($_POST['cm']))
        $dadosConsulta['cm'] = _INPUT('cm', 'string', 'post');

    if (!empty($_POST['kg']))
        $dadosConsulta['kg'] = _INPUT('kg', 'string', 'post');

    if (!empty($_POST['PA']))
        $dadosConsulta['PA'] = _INPUT('PA', 'string', 'post');

    if (!empty($_POST['texto']))
        $dadosConsulta['texto'] = _INPUT('texto', 'string', 'post');

    if (!empty($_POST['prescricao']))
        $dadosConsulta['prescricao'] = _INPUT('prescricao', 'string', 'post');

    if (!empty($_POST['hipotese_diag']))
        $dadosConsulta['hipotese_diag'] = _INPUT('hipotese_diag', 'string', 'post');

    if (!empty($_POST['atestado']))
        $dadosConsulta['atestado'] = _INPUT('atestado', 'string', 'post');

    if (!empty($_POST['resultados_exame']))
        $dadosConsulta['resultado_encaminhamentos'] = _INPUT('resultados_exame', 'string', 'post');

    if (editarCliente($dadosCliente, $idCliente)) {
        echo "sucessoCLI<br>";
    } else {
        echo "erroCLI<br>";
    }

    $idConsulta = confereExistenciaConsulta($dadosConsulta);

    if ($idConsulta != '') {
        if (editarConsulta($dadosConsulta, $idConsulta)) {
            echo "sucesso:EDIção consulta";
        } else {
            echo "erroCONSULTAEdição";
        }
    } else {
        if (insereConsulta($dadosConsulta)) {
            echo "sucesso:INSERE CONSULTA";
        } else {
            echo "erroCONSULTAAdi";
        }
    }
} else if ($_POST['flag'] == "buscaTextoCID") {
    $consulta_id = $_POST['consulta_id'];
    $array = listarCIDs($consulta_id);

    die(saidaJson($array));
} else if ($_POST['flag'] == "insertCID") {
    $consulta_id = $_POST['idconsulta'];
    $idcid = $_POST['idcid'];
    insertCIDonConsultas($idcid, $consulta_id);
    die('save');
} else if ($_POST['flag'] == "pegaClienteFilaEspera") {
    die(saidaJson(pegaClienteFilaEspera($_POST['idFila'])));
} else if ($_POST['flag'] == "pegaFilaEspera") {
    die(saidaJson(pegaFilaEsperaPorMedico($_SESSION['usuario']['id'])));
} else if ($_POST['flag'] == "getUltimoClienteporMedico") {
    $medicoID = $_SESSION['usuario']['id'];
    $row = capturaUltimoClienteMedico($medicoID);
    die(saidaJson($row));
} else if ($_POST['flag'] == "criaTabelaTesteCutaneo") {
    $clienteID = $_POST['idcliente'];
    $array = listarTesteCutaneo($clienteID);
    foreach ($array as $value) {
        $retorno = "<tr name=\"table-color\" class='dayhead testescutConf'>";
        $retorno = $retorno . "<th align=\"center\" colspan=\"4\"><center> AQUI</center> </th>";
        $retorno = $retorno . "</tr>";
    }
    die($retorno);
} else if ($_POST['flag'] == "inserirConsultaCoracaozinho") {

    /*
     * Action responsável por inserir os dados de uma consulta
     * Coracaozinho. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    /*
     * Validação para saber se o formulário foi submetido
     * com todos os valores vazios. Se isso acontecer, será
     * retornado um erro ao usuário.
     */
    $tmp = 0;
    $coracaoArr = json_decode($_POST['coracao'], true);
    foreach ($coracaoArr as $coracao) {
        if (!empty($coracao)) {
            $tmp = 1;
        }
    }

    if ($tmp == 0) {
        $resposta['data'] = '';
        $resposta['resultado'] = 'form_vazio';
        die(saidaJson($resposta));
    }

    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */

    $idCliente = $_POST['idCliente'];
    $idCategoriaConsulta = 4; // id da categoria coracaozinho
    $dadosCoracao['perc_maoDireita'] = $coracaoArr['percMaoDireita'];
    $dadosCoracao['perc_pe'] = $coracaoArr['percPe'];
    $dadosCoracao['perc_diferenca'] = $coracaoArr['percDiferenca'];
    $dadosCoracao['outros_exames'] = $coracaoArr['outrosExames'];
    $dadosCoracao['observacao'] = $coracaoArr['observacao'];
    $dadosCoracao['conclusao_teste'] = $coracaoArr['conclusaoTeste'];

    $dadosNovaConsulta['altura'] = '';
    $dadosNovaConsulta['Peso'] = '';
    $dadosNovaConsulta['PA'] = '';
    $dadosNovaConsulta['texto'] = '';
    $dadosNovaConsulta['clienteID'] = $_POST['idCliente'];
    $dadosNovaConsulta['data'] = date('Y-m-d');
    $dadosNovaConsulta['categoria_consultas_id'] = $idCategoriaConsulta;

    $idConsulta = adicionaNovaConsulta($dadosNovaConsulta);
    /*
     * Se adicionar a consulta corretamente, temos então o 
     * id desta consulta, armazenado na variável $idConsulta.
     * 
     */
    if ($idConsulta > 0) {
        /*
         * Se inserir corretamente a consulta_coracaozinho, pode-se armazenar
         * o id da nova consulta coracaozinho na variável $idConsultaCoracao.
         */
        $bool = inserir('consulta_coracaozinho', $dadosCoracao);
        if ($bool) {
            /*
             * Se inserir corretamente a consulta_coracaozinho, deve-se
             * agora adicionar o id desta na consulta criada anteriormente,
             * fazendo um update no campo consulta_coracaozinho_id na 
             * tabela consultas.
             */
            $idConsultaCoracao = mysqli_insert_id(banco::$connection);
            $dadosAtualizaConsulta['consulta_coracaozinho_id'] = $idConsultaCoracao;
            atualizaConsultas($dadosAtualizaConsulta, $idConsulta);

            /*
             * acessando o indice do array que contem o array de
             * anotacaoqp para inserir no banco.
             */
            $anotacaoqp = $coracaoArr['coracao_anot_qp'];

            if (!empty($anotacaoqp)) {
                foreach ($anotacaoqp as $check) {
                    $dadosCheck['consulta_coracaozinho_id'] = $idConsultaCoracao;
                    $dadosCheck['coracao_anot_qp_id'] = $check;
                    inserir('consulta_coracaozinho_has_coracao_anot_qp', $dadosCheck);
                }
            }
            /*
             * acessando o indice do array que contem o array de
             * anotacaohf para inserir no banco.
             */
            $anotacaohf = $coracaoArr['coracao_anot_hf'];

            if (!empty($anotacaohf)) {
                foreach ($anotacaohf as $check) {
                    $dadosCheck2['consulta_coracaozinho_id'] = $idConsultaCoracao;
                    $dadosCheck2['coracao_anot_hf_id'] = $check;
                    inserir('consulta_coracaozinho_has_coracao_anot_hf', $dadosCheck2);
                }
            }

            $resposta['data'] = date('d/m/Y');
            $resposta['resultado'] = true;
            die(saidaJson($resposta));
        } else {
            $resposta['data'] = '';
            $resposta['resultado'] = false;
            die(saidaJson($resposta));
        }
    }
} else if ($_POST['flag'] == "atualizaConsultaCoracaozinho") {

    /*
     * Action responsável por atualizar os dados de uma consulta
     * Coracaozinho. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    $coracaoArr = json_decode($_POST['coracao'], true);
    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */
    $dadosCoracao['perc_maoDireita'] = $coracaoArr['percMaoDireita'];
    $dadosCoracao['perc_pe'] = $coracaoArr['percPe'];
    $dadosCoracao['perc_diferenca'] = $coracaoArr['percDiferenca'];
    $dadosCoracao['outros_exames'] = $coracaoArr['outrosExames'];
    $dadosCoracao['observacao'] = $coracaoArr['observacao'];
    $dadosCoracao['conclusao_teste'] = $coracaoArr['conclusaoTeste'];

    $idConsultaCoracao = $coracaoArr['consultaCoracaoId'];
    $bool = atualizaConsultaCoracaozinho($dadosCoracao, $idConsultaCoracao);
    /*
     * acessando o indice do array que contem o array de
     * anotacaoqp para inserir no banco.
     */
    $anotacaoqp = $coracaoArr['coracao_anot_qp'];

    $idConsultaCoracao = $coracaoArr['consultaCoracaoId'];
    $bool = atualizaConsultaCoracaozinho($dadosCoracao, $idConsultaCoracao);
    /*
     * acessando o indice do array que contem o array de
     * anotacaoqp para inserir no banco.
     */
    $anotacaoqp = $coracaoArr['coracao_anot_qp'];

    remover('consulta_coracaozinho_has_coracao_anot_qp', 'consulta_coracaozinho_id=' . $idConsultaCoracao);
    if (!empty($anotacaoqp)) {
        foreach ($anotacaoqp as $check) {
            $dadosCheck['consulta_coracaozinho_id'] = $idConsultaCoracao;
            $dadosCheck['coracao_anot_qp_id'] = $check;
            inserir('consulta_coracaozinho_has_coracao_anot_qp', $dadosCheck);
        }
    }

    /*
     * acessando o indice do array que contem o array de
     * anotacaohf para inserir no banco.
     */
    $anotacaohf = $coracaoArr['coracao_anot_hf'];

    remover('consulta_coracaozinho_has_coracao_anot_hf', 'consulta_coracaozinho_id=' . $idConsultaCoracao);
    if (!empty($anotacaohf)) {
        foreach ($anotacaohf as $check) {
            $dadosCheck2['consulta_coracaozinho_id'] = $idConsultaCoracao;
            $dadosCheck2['coracao_anot_hf_id'] = $check;
            inserir('consulta_coracaozinho_has_coracao_anot_hf', $dadosCheck2);
        }
    }
    die(saidaJson($bool));
} else if ($_POST['flag'] == "inserirConsultaLinguinha") {

    /*
     * Action responsável por inserir os dados de uma consulta
     * Linguinha. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    /*
     * Validação para saber se o formulário foi submetido
     * com todos os valores vazios. Se isso acontecer, será
     * retornado um erro ao usuário.
     */
    $tmp = 0;
    $linguaArr = json_decode($_POST['lingua'], true);
    foreach ($linguaArr as $lingua) {
        if (!empty($lingua)) {
            $tmp = 1;
        }
    }

    if ($tmp == 0) {
        $resposta['data'] = '';
        $resposta['resultado'] = 'form_vazio';
        die(saidaJson($resposta));
    }

    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */

    $idCliente = $_POST['idCliente'];
    $idCategoriaConsulta = 5; // id da categoria linguinha
    $dadosLingua['observacao'] = $linguaArr['observacao'];
    $dadosLingua['outros_exames'] = $linguaArr['outrosExames'];
    $dadosLingua['conclusao_teste'] = $linguaArr['conclusaoTeste'];
    $dadosLingua['freio_lingual_normal'] = $linguaArr['freioLingualNormal'];
    $dadosLingua['freio_lingual_curto'] = $linguaArr['freioLingualCurto'];
    $dadosLingua['insercao_anteriorizada'] = $linguaArr['insercaoAnteriorizada'];
    $dadosLingua['Anquiloglossia'] = $linguaArr['anquiloglossia'];

    $dadosNovaConsulta['altura'] = '';
    $dadosNovaConsulta['Peso'] = '';
    $dadosNovaConsulta['PA'] = '';
    $dadosNovaConsulta['texto'] = '';
    $dadosNovaConsulta['clienteID'] = $_POST['idCliente'];
    $dadosNovaConsulta['data'] = date('Y-m-d');
    $dadosNovaConsulta['categoria_consultas_id'] = $idCategoriaConsulta;

    $idConsulta = adicionaNovaConsulta($dadosNovaConsulta);
    /*
     * Se adicionar a consulta corretamente, temos então o 
     * id desta consulta, armazenado na variável $idConsulta.
     * 
     */
    if ($idConsulta > 0) {
        /*
         * Se inserir corretamente a consulta_linguinha, pode-se armazenar
         * o id da nova consulta linguinha na variável $idConsultaLingua.
         */
        $bool = inserir('consulta_linguinha', $dadosLingua);
        if ($bool) {
            /*
             * Se inserir corretamente a consulta_linguinha, deve-se
             * agora adicionar o id desta na consulta criada anteriormente,
             * fazendo um update no campo consulta_linguinha_id na 
             * tabela consultas.
             */
            $idConsultaLingua = mysqli_insert_id(banco::$connection);
            $dadosAtualizaConsulta['consulta_linguinha_id'] = $idConsultaLingua;
            atualizaConsultas($dadosAtualizaConsulta, $idConsulta);

            /*
             * acessando o indice do array que contem o array de
             * anotacaoqp para inserir no banco.
             */

            $linguinhaqp = $linguaArr['lingua_anot_qp'];

            //remover('consulta_linguinha_has_lingua_anot_qp', 'consulta_linguinha_id=' . $idConsultaLingua);
            foreach ($linguinhaqp as $check) {
                $dadosCheck2['consulta_linguinha_id'] = $idConsultaLingua;
                $dadosCheck2['lingua_anot_qp_id'] = $check;
                inserir('consulta_linguinha_has_lingua_anot_qp', $dadosCheck2);
            }

            $resposta['data'] = date('d/m/Y');
            $resposta['resultado'] = true;
            die(saidaJson($resposta));
        } else {
            $resposta['data'] = '';
            $resposta['resultado'] = false;
            die(saidaJson($resposta));
        }
    }
} else if ($_POST['flag'] == "atualizaConsultaLinguinha") {

    /*
     * Action responsável por atualiza os dados de uma consulta
     * Linguinha. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */

    $linguaArr = json_decode($_POST['lingua'], true);

    $linguaArr = json_decode($_POST['lingua'], true);

    $dadosLingua['observacao'] = $linguaArr['observacao'];
    $dadosLingua['outros_exames'] = $linguaArr['outrosExames'];
    $dadosLingua['conclusao_teste'] = $linguaArr['conclusaoTeste'];
    $dadosLingua['freio_lingual_normal'] = $linguaArr['freioLingualNormal'];
    $dadosLingua['freio_lingual_curto'] = $linguaArr['freioLingualCurto'];
    $dadosLingua['insercao_anteriorizada'] = $linguaArr['insercaoAnteriorizada'];
    $dadosLingua['Anquiloglossia'] = $linguaArr['anquiloglossia'];

    $idConsultaLingua = $linguaArr['consultaLinguaId'];
    $bool = atualizaConsultaLinguinha($dadosLingua, $idConsultaLingua);

    /*
     * acessando o indice do array que contem o array de
     * $linguinhaqp para inserir no banco.
     */

    $linguinhaqp = $linguaArr['lingua_anot_qp'];

    remover('consulta_linguinha_has_lingua_anot_qp', 'consulta_linguinha_id=' . $idConsultaLingua);
    if (!empty($linguinhaqp)) {
        foreach ($linguinhaqp as $check) {
            $dadosCheck2['consulta_linguinha_id'] = $idConsultaLingua;
            $dadosCheck2['lingua_anot_qp_id'] = $check;
            inserir('consulta_linguinha_has_lingua_anot_qp', $dadosCheck2);
        }
    }
    die(saidaJson($bool));
} else if ($_POST['flag'] == "inserirConsultaOlhinho") {

    /*
     * Action responsável por inserir os dados de uma consulta
     * Olhinho. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    /*
     * Validação para saber se o formulário foi submetido
     * com todos os valores vazios. Se isso acontecer, será
     * retornado um erro ao usuário.
     */
    $tmp = 0;
    $olhoArr = json_decode($_POST['olho'], true);
    foreach ($olhoArr as $olho) {
        if (!empty($olho)) {
            $tmp = 1;
        }
    }

    if ($tmp == 0) {
        $resposta['data'] = '';
        $resposta['resultado'] = 'form_vazio';
        die(saidaJson($resposta));
    }

    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */

    $idCliente = $_POST['idCliente'];
    $idCategoriaConsulta = 1; // id da categoria olhinho
    $dadosOlho['resultado_od_normal'] = $olhoArr['resultadoNormalOd'];
    $dadosOlho['resultado_od_suspeito'] = $olhoArr['resultadoSuspeitoOd'];
    $dadosOlho['resultado_od_leucoria'] = $olhoArr['resultadoLeucoriaOd'];
    $dadosOlho['resultado_oe_normal'] = $olhoArr['resultadoNormalOe'];
    $dadosOlho['resultado_oe_suspeito'] = $olhoArr['resultadoSuspeitoOe'];
    $dadosOlho['resultado_oe_leucoria'] = $olhoArr['resultadoLeucoriaOe'];
    $dadosOlho['qp'] = $olhoArr['olho_anot_qp'];
    $dadosOlho['hf'] = $olhoArr['olho_anot_hf'];
    $dadosOlho['outros_exames'] = $olhoArr['outrosExames'];
    $dadosOlho['observacao'] = $olhoArr['observacao'];
    $dadosOlho['visual_normal'] = $olhoArr['visualNormal'];

    $dadosNovaConsulta['altura'] = '';
    $dadosNovaConsulta['Peso'] = '';
    $dadosNovaConsulta['PA'] = '';
    $dadosNovaConsulta['texto'] = '';
    $dadosNovaConsulta['clienteID'] = $_POST['idCliente'];
    $dadosNovaConsulta['data'] = date('Y-m-d');
    $dadosNovaConsulta['categoria_consultas_id'] = $idCategoriaConsulta;

    $idConsulta = adicionaNovaConsulta($dadosNovaConsulta);
    /*
     * Se adicionar a consulta corretamente, temos então o 
     * id desta consulta, armazenado na variável $idConsulta.
     * 
     */
    if ($idConsulta > 0) {
        /*
         * Se inserir corretamente a consulta_olhinho, pode-se armazenar
         * o id da nova consulta olhinho na variável $idConsultaOlhinho.
         */
        $bool = inserir('consulta_olhinho', $dadosOlho);
        if ($bool) {
            /*
             * Se inserir corretamente a consulta_olhinho, deve-se
             * agora adicionar o id desta na consulta criada anteriormente,
             * fazendo um update no campo consulta_olhinho_id na
             * tabela consultas.
             */
            $idConsultaOlho = mysqli_insert_id(banco::$connection);
            $dadosAtualizaConsulta['consulta_olhinho_id'] = $idConsultaOlho;
            atualizaConsultas($dadosAtualizaConsulta, $idConsulta);

            $resposta['data'] = date('d/m/Y');
            $resposta['resultado'] = true;
            die(saidaJson($resposta));
        } else {
            $resposta['data'] = '';
            $resposta['resultado'] = false;
            die(saidaJson($resposta));
        }
    }
} else if ($_POST['flag'] == "atualizaConsultaOlhinho") {

    /*
     * Action responsável por atualizar os dados de uma consulta
     * Olhinho. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    $olhoArr = json_decode($_POST['olho'], true);

    $dadosOlho['resultado_od_normal'] = $olhoArr['resultadoNormalOd'];
    $dadosOlho['resultado_od_suspeito'] = $olhoArr['resultadoSuspeitoOd'];
    $dadosOlho['resultado_od_leucoria'] = $olhoArr['resultadoLeucoriaOd'];
    $dadosOlho['resultado_oe_normal'] = $olhoArr['resultadoNormalOe'];
    $dadosOlho['resultado_oe_suspeito'] = $olhoArr['resultadoSuspeitoOe'];
    $dadosOlho['resultado_oe_leucoria'] = $olhoArr['resultadoLeucoriaOe'];
    $dadosOlho['qp'] = $olhoArr['olho_anot_qp'];
    $dadosOlho['hf'] = $olhoArr['olho_anot_hf'];
    $dadosOlho['outros_exames'] = $olhoArr['outrosExames'];
    $dadosOlho['observacao'] = $olhoArr['observacao'];
    $dadosOlho['visual_normal'] = $olhoArr['visualNormal'];
} else if ($_POST['flag'] == "inserirConsultaOrelhinha1") {

    /*
     * Action responsável por inserir os dados de uma consulta
     * orelhinha1. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    /*
     * Validação para saber se o formulário foi submetido
     * com todos os valores vazios. Se isso acontecer, será
     * retornado um erro ao usuário.
     */
    $tmp = 0;
    $orelha1Arr = json_decode($_POST['orelha1'], true);
    foreach ($orelha1Arr as $orelha1) {
        if (!empty($orelha1)) {
            $tmp = 1;
        }
    }

    if ($tmp == 0) {
        $resposta['data'] = '';
        $resposta['resultado'] = 'form_vazio';
        die(saidaJson($resposta));
    }

    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */

    $idCliente = $_POST['idCliente'];
    $idCategoriaConsulta = 2; // id da categoria Orelhinha1

    $dadosOrelha1['orelhinha1_frequencia_id_od'] = $orelha1Arr['frequenciaOd'];
    $dadosOrelha1['orelhinha1_frequencia_id_oe'] = $orelha1Arr['frequenciaOe'];
    $dadosOrelha1['observacao'] = $orelha1Arr['observacao'];
    $dadosOrelha1['obstrucao_meato_od'] = $orelha1Arr['obstrucaoMeatoOd'];
    $dadosOrelha1['obstrucao_meato_oe'] = $orelha1Arr['obstrucaoMeatoOe'];
    $dadosOrelha1['localizacao_meato'] = $orelha1Arr['localizacaoMeato'];
    $dadosOrelha1['teoae_od'] = $orelha1Arr['tahoeOd'];
    $dadosOrelha1['teoae_oe'] = $orelha1Arr['tahoeOe'];
    $dadosOrelha1['noise_od'] = $orelha1Arr['noiseOd'];
    $dadosOrelha1['noise_oe'] = $orelha1Arr['noiseOe'];
    $dadosOrelha1['comportamento_select1'] = $orelha1Arr['comportamento1'];
    $dadosOrelha1['comportamento_select2'] = $orelha1Arr['comportamento2'];
    $dadosOrelha1['comportamento_select3'] = $orelha1Arr['comportamento3'];
    $dadosOrelha1['comportamento_select4'] = $orelha1Arr['comportamento4'];
    $dadosOrelha1['funcao_coclear_presente_bilateral'] = $orelha1Arr['funcaoCoclear'];
    //$dadosOrelha1['reavaliacao'] = $orelha1Arr[''];

    $dadosNovaConsulta['altura'] = '';
    $dadosNovaConsulta['Peso'] = '';
    $dadosNovaConsulta['PA'] = '';
    $dadosNovaConsulta['texto'] = '';
    $dadosNovaConsulta['clienteID'] = $_POST['idCliente'];
    $dadosNovaConsulta['data'] = date('Y-m-d');
    $dadosNovaConsulta['categoria_consultas_id'] = $idCategoriaConsulta;

    $idConsulta = adicionaNovaConsulta($dadosNovaConsulta);
    /*
     * Se adicionar a consulta corretamente, temos então o 
     * id desta consulta, armazenado na variável $idConsulta.
     * 
     */
    if ($idConsulta > 0) {
        /*
         * Se inserir corretamente a consulta_orelhinha, pode-se armazenar
         * o id da nova consulta orelhinha na variável $idConsultaOrelha1.
         */
        $bool = inserir('consulta_orelhinha1', $dadosOrelha1);
        if ($bool) {
            /*
             * Se inserir corretamente a consulta_orelhinha1, deve-se
             * agora adicionar o id desta na consulta criada anteriormente,
             * fazendo um update no campo consulta_orelhinha1_id na
             * tabela consultas.
             */
            $idConsultaOrelha1 = mysqli_insert_id(banco::$connection);
            $dadosAtualizaConsulta['consulta_orelhinha1_id'] = $idConsultaOrelha1;
            atualizaConsultas($dadosAtualizaConsulta, $idConsulta);

            $resposta['data'] = date('d/m/Y');
            $resposta['resultado'] = true;
            die(saidaJson($resposta));
        } else {
            $resposta['data'] = '';
            $resposta['resultado'] = false;
            die(saidaJson($resposta));
        }
    }
} else if ($_POST['flag'] == "atualizaConsultaOrelhinha1") {

    /*
     * Action responsável por atualizar os dados de uma consulta
     * orelhinha1. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    $orelha1Arr = json_decode($_POST['orelha1'], true);

    $dadosOrelha1['orelhinha1_frequencia_id_od'] = $orelha1Arr['frequenciaOd'];
    $dadosOrelha1['orelhinha1_frequencia_id_oe'] = $orelha1Arr['frequenciaOe'];
    $dadosOrelha1['observacao'] = $orelha1Arr['observacao'];
    $dadosOrelha1['obstrucao_meato_od'] = $orelha1Arr['obstrucaoMeatoOd'];
    $dadosOrelha1['obstrucao_meato_oe'] = $orelha1Arr['obstrucaoMeatoOe'];
    $dadosOrelha1['localizacao_meato'] = $orelha1Arr['localizacaoMeato'];
    $dadosOrelha1['teoae_od'] = $orelha1Arr['tahoeOd'];
    $dadosOrelha1['teoae_oe'] = $orelha1Arr['tahoeOe'];
    $dadosOrelha1['noise_od'] = $orelha1Arr['noiseOd'];
    $dadosOrelha1['noise_oe'] = $orelha1Arr['noiseOe'];
    $dadosOrelha1['comportamento_select1'] = $orelha1Arr['comportamento1'];
    $dadosOrelha1['comportamento_select2'] = $orelha1Arr['comportamento2'];
    $dadosOrelha1['comportamento_select3'] = $orelha1Arr['comportamento3'];
    $dadosOrelha1['comportamento_select4'] = $orelha1Arr['comportamento4'];
    $dadosOrelha1['funcao_coclear_presente_bilateral'] = $orelha1Arr['funcaoCoclear'];

    $idConsultaOrelha1 = $orelha1Arr['consultaOrelhaId'];
    $bool = atualizaConsultaOrelhinha1($dadosOrelha1, $idConsultaOrelha1);
    die(saidaJson($bool));
} else if ($_POST['flag'] == "inserirConsultaOrelhinha2") {

    /*
     * Action responsável por inserir os dados de uma consulta
     * orelhinha2. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    /*
     * Validação para saber se o formulário foi submetido
     * com todos os valores vazios. Se isso acontecer, será
     * retornado um erro ao usuário.
     */
    $tmp = 0;
    $orelha2Arr = json_decode($_POST['orelha2'], true);
    foreach ($orelha2Arr as $orelha2) {
        if (!empty($orelha2)) {
            $tmp = 1;
        }
    }

    if ($tmp == 0) {
        $resposta['data'] = '';
        $resposta['resultado'] = 'form_vazio';
        die(saidaJson($resposta));
    }

    /*
     * Esta action recebe o objeto coracao preenchido em forma
     * de array, cria o array que irá ser enviado ao banco de
     * de dados.
     */
    $idCliente = $_POST['idCliente'];
    $idCategoriaConsulta = 3; // id da categoria Orelhinha2

    $dadosOrelha2['conclusao_orelhinha_od'] = $orelha2Arr['conclusaoOd'];
    $dadosOrelha2['conclusao_orelhinha_oe'] = $orelha2Arr['conclusaoOe'];
    $dadosOrelha2['equipamentos_teste_id'] = $orelha2Arr['equipamentoTeste'];
    $dadosOrelha2['orelhinha2_resultado_cocleo_id'] = $orelha2Arr['resultadoCocleo'];
    $dadosOrelha2['obstrucao_meato_od'] = $orelha2Arr['obstrucaoMeatoOd'];
    $dadosOrelha2['obstrucao_meato_oe'] = $orelha2Arr['obstrucaoMeatoOe'];
    $dadosOrelha2['reavaliacao'] = $orelha2Arr[''];
    $dadosOrelha2['observacao'] = $orelha2Arr['observacao'];
    $dadosOrelha2['funcao_coclear_presente_bilateral'] = $orelha2Arr['funcaoCoclear'];

    $dadosNovaConsulta['altura'] = '';
    $dadosNovaConsulta['Peso'] = '';
    $dadosNovaConsulta['PA'] = '';
    $dadosNovaConsulta['texto'] = '';
    $dadosNovaConsulta['clienteID'] = $_POST['idCliente'];
    $dadosNovaConsulta['data'] = date('Y-m-d');
    $dadosNovaConsulta['categoria_consultas_id'] = $idCategoriaConsulta;

    $idConsulta = adicionaNovaConsulta($dadosNovaConsulta);
    /*
     * Se adicionar a consulta corretamente, temos então o 
     * id desta consulta, armazenado na variável $idConsulta.
     * 
     */
    if ($idConsulta > 0) {
        /*
         * Se inserir corretamente a consulta_orelhinha2, pode-se armazenar
         * o id da nova consulta orelhinha na variável $idConsultaOrelha2.
         */
        $bool = inserir('consulta_orelhinha2', $dadosOrelha2);
        if ($bool) {
            /*
             * Se inserir corretamente a consulta_orelhinha2, deve-se
             * agora adicionar o id desta na consulta criada anteriormente,
             * fazendo um update no campo consulta_orelhinha2_id na
             * tabela consultas.
             */
            $idConsultaOrelha2 = mysqli_insert_id(banco::$connection);
            $dadosAtualizaConsulta['consulta_orelhinha2_id'] = $idConsultaOrelha2;
            atualizaConsultas($dadosAtualizaConsulta, $idConsulta);

            $resposta['data'] = date('d/m/Y');
            $resposta['resultado'] = true;
            die(saidaJson($resposta));
        } else {
            $resposta['data'] = '';
            $resposta['resultado'] = false;
            die(saidaJson($resposta));
        }
    }
} else if ($_POST['flag'] == "atualizaConsultaOrelhinha2") {

    /*
     * Action responsável por atualizar os dados de uma consulta
     * orelhinha2. Estes dados são recebidos a partir do formulário
     * e enviados ao banco de dados.
     */

    $orelha2Arr = json_decode($_POST['orelha2'], true);

    $dadosOrelha2['conclusao_orelhinha_od'] = $orelha2Arr['conclusaoOd'];
    $dadosOrelha2['conclusao_orelhinha_oe'] = $orelha2Arr['conclusaoOe'];
    $dadosOrelha2['equipamentos_teste_id'] = $orelha2Arr['equipamentoTeste'];
    $dadosOrelha2['orelhinha2_resultado_cocleo_id'] = $orelha2Arr['resultadoCocleo'];
    $dadosOrelha2['obstrucao_meato_od'] = $orelha2Arr['obstrucaoMeatoOd'];
    $dadosOrelha2['obstrucao_meato_oe'] = $orelha2Arr['obstrucaoMeatoOe'];
    $dadosOrelha2['reavaliacao'] = $orelha2Arr[''];
    $dadosOrelha2['observacao'] = $orelha2Arr['observacao'];
    $dadosOrelha2['funcao_coclear_presente_bilateral'] = $orelha2Arr['funcaoCoclear'];

    $idConsultaOrelha2 = $orelha2Arr['consultaOrelhaId'];
    $bool = atualizaConsultaOrelhinha2($dadosOrelha2, $idConsultaOrelha2);
    die(saidaJson($bool));
} else if ($_POST['flag'] == "buscaTextoPrescricao") {
    $dadosPrescricao = buscaTextoPrescricao($_POST['idPrescricao']);
    $list['texto'] = "- " . $dadosPrescricao['descricao'];
    $list['id'] = $dadosPrescricao['id'];
    $list['nome'] = $dadosPrescricao['nome'];
    echo saidaJson($list);
} else if ($_POST['flag'] == "buscaTextoPrescricaoEditar") {
    $dadosPrescricao = buscaTextoPrescricao($_POST['idPrescricao']);
    $list['texto'] = $dadosPrescricao['descricao'];
    $list['id'] = $dadosPrescricao['id'];
    $list['nome'] = $dadosPrescricao['nome'];
    echo saidaJson($list);
} else if ($_POST['flag'] == "resultadoTestesCutaneos") {
    $idTeste = $_POST['idTeste'];
    $arr = buscaAlegenos($idTeste);
    $arr = $arr['totalDados'];
    die(saidaJson($arr));
} else if ($_POST['flag'] == "buscaAlegenos") {
    $idTeste = $_POST['idTeste'];
    $texto = buscaAlegenos($idTeste);
    $texto = $texto['nomes'];
    $nomeTeste = ver("testes", "*", "id=$idTeste");

    $arr['idTeste'] = $nomeTeste['id'];
    $arr['nome'] = $nomeTeste['nome'];
    $arr['alergeno'] = implode(', ', $texto);

    echo saidaJson($arr);
} else if ($_POST['flag'] == "buscaTodosAlegenosPorCliente") {
    /*
     * @todo Criar uma query para obter todos os resultados dos alergenos e retornar no array 
     *      
     */
    $cliente = $_POST['cliente_id'];
    $nomeTeste = pegaTodososAlergenosPorCliente($cliente);
    $arr = '';
    foreach ($nomeTeste as $linha) {
        $arr[$linha['testes_id']]['id'] = $linha['testes_realizados_id']; // id do teste
        $arr[$linha['testes_id']]['nome'] = $linha['teste_nome']; //nome do teste
        if (!isset($arr[$linha['testes_id']]['alergeno'])) {
            $arr[$linha['testes_id']]['alergeno'] = $linha['nome']; //nome dos alergenos testados
        } else {
            $arr[$linha['testes_id']]['alergeno'] .= ', ' . $linha['nome']; //nome dos alergenos testados
        }
        if (!isset($arr[$linha['testes_id']]['resultado'])) {
            $arr[$linha['testes_id']]['resultado'] = $linha['nome'] . '(' . $linha['resultado'] . ')'; //data do teste
        } else {
            $arr[$linha['testes_id']]['resultado'] .= ', ' . $linha['nome'] . '(' . $linha['resultado'] . ')'; //data do teste
        }
        $arr[$linha['testes_id']]['data'] = $linha['data']; //data do teste
    }
    die(saidaJson($arr));
} else if ($_POST['flag'] == "buscaTextoRecomendacao") {
    $dadosRecomendacao = buscaTextoRecomendacao($_POST['idPrescricao']);
    $list['id'] = $dadosRecomendacao['id'];
    $list['nome'] = $dadosRecomendacao['nome'];
    $list['texto'] = "- " . $dadosRecomendacao['descricao'];
    echo saidaJson($list);
} else if ($_POST['flag'] == 'filtroPrescricoes') {
    $medico_id = $_POST['idMedico'];
    $filtro = $_POST['filtro'];
    $array = buscaPrescricoes($medico_id, $filtro);
    die(saidaJson($array));
} else if ($_POST['flag'] == 'filtroRecomendacoes') {
    $filtro = $_POST['filtro'];
    $array = buscaRecomendacoes($filtro);
    die(saidaJson($array));
} else if ($_POST['flag'] == 'filtroHipoteses') {
    $medico_id = $_POST['idMedico'];
    $filtro = $_POST['filtro'];
    $modelRow = "";
    foreach (buscaHipoteseDoencas($medico_id, $filtro) as $array) {
        $id = ($array['id']);
        $name = ($array['nome']);
        $fav = $array['favorito'];
        $codigo = $array['codigo'];
        $star = "view/img/star.png";
        $modelRow .= "<tr id = '" . $id . "' value='" . $array['codigo'] . "' name = 'table-color' class= 'dayhead doencaRow'><th allign='center'>";
        $modelRow .= "<input type='checkbox' name='" . $id . "' id='hipoteses' value = '" . $codigo . " - " . $name . "' ";
        $modelRow .= "onchange='atualizaTextareaHipotese()' /> " . $name . " ";
        if ($fav == 1) {
            $modelRow .= "<img id='hipoteseFavorito' name='" . $id . "' align='right' src='" . $star . "' />";
        }
        $modeRow .= "</tr>";
    }
    //echo jsonRemoveUnicodeSequences($modelRow);
    echo $modelRow;
} else if (($_POST['flag'] === 'atualizaHipotese')) { //Opcoes do context menu da tabela doencas em hipoteses
    $idd = $_POST['idDoenca'];
    $idm = $_POST['idMedico'];
    $opcao = $_POST['opcao'];
    $filtro = $_POST['filtro'];
    $cod = $_POST['codigo'];
    $texto = $_POST['texto'];
    $modelRow = "";

    if ($opcao == 1) { //Nova doenca    
        if (inserirHipoteseDoenca($cod, $texto, $idm)) {// && inserirHipoteseMedicoDoenca($idd, $idm)){
            $modelRow = "";
            foreach (buscaHipoteseDoencas($idm, $filtro) as $array) {
                $id = ($array['id']);
                $name = ($array['nome']);
                $fav = $array['favorito'];
                $cod = $array['codigo'];
                $star = "view/img/star.png";
                $modelRow .= "<tr id = '" . $id . "' value='" . $array['codigo'] . "' name = 'table-color' class= 'dayhead doencaRow'><th allign='center'>";
                $modelRow .= "<input type='checkbox' name='" . $id . "' id='hipoteses' value = '" . $cod . " - " . $name . "' ";
                $modelRow .= "onchange='atualizaTextareaHipotese()' /> " . $name . " ";
                if ($fav == 1) {
                    $modelRow .= "<img id='hipoteseFavorito' name='" . $id . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
            //echo jsonRemoveUnicodeSequences($modelRow);
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 2) { //Editar doenca
        if (editarHipoteseDoenca($idd, $cod, $texto)) {
            $modelRow = "";
            foreach (buscaHipoteseDoencas($idm, $filtro) as $array) {
                $id = ($array['id']);
                $name = ($array['nome']);
                $fav = $array['favorito'];
                $cod = $array['codigo'];
                $star = "view/img/star.png";
                $modelRow .= "<tr id = '" . $id . "' value='" . $array['codigo'] . "' name = 'table-color' class= 'dayhead doencaRow'><th allign='center'>";
                $modelRow .= "<input type='checkbox' name='" . $id . "' id='hipoteses' value = '" . $cod . " - " . $name . "' ";
                $modelRow .= "onchange='atualizaTextareaHipotese()' /> " . $name . " ";
                if ($fav == 1) {
                    $modelRow .= "<img id='hipoteseFavorito' name='" . $id . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
            //echo jsonRemoveUnicodeSequences($modelRow);
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 3) { //Remover doenca        
        if (removerHipoteseMedicoDoenca($idd, $idm)) {
            $modelRow = "";
            foreach (buscaHipoteseDoencas($idm, $filtro) as $array) {
                $id = ($array['id']);
                $name = ($array['nome']);
                $fav = $array['favorito'];
                $star = "view/img/star.png";
                $cod = $array['codigo'];
                $modelRow .= "<tr id = '" . $id . "' value='" . $array['codigo'] . "' name = 'table-color' class= 'dayhead doencaRow'><th allign='center'>";
                $modelRow .= "<input type='checkbox' name='" . $id . "' id='hipoteses' value = '" . $cod . " - " . $name . "' ";
                $modelRow .= "onchange='atualizaTextareaHipotese()' /> " . $name . " ";
                if ($fav == 1) {
                    $modelRow .= "<img id='hipoteseFavorito' name='" . $id . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }

            //echo jsonRemoveUnicodeSequences($modelRow);        
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 4) { //Marcar como favorito
        if (marcadesmarcaFavoritoHipotese($idd, $idm, 1)) {
            $modelRow = "";
            foreach (buscaHipoteseDoencas($idm, $filtro) as $array) {
                $id = ($array['id']);
                $name = ($array['nome']);
                $fav = $array['favorito'];
                $star = "view/img/star.png";
                $cod = $array['codigo'];
                $modelRow .= "<tr id = '" . $id . "' value='" . $array['codigo'] . "' name = 'table-color' class= 'dayhead doencaRow'><th allign='center'>";
                $modelRow .= "<input type='checkbox' name='" . $id . "' id='hipoteses' value = '" . $cod . " - " . $name . "' ";
                $modelRow .= "onchange='atualizaTextareaHipotese()' /> " . $name . " ";
                if ($fav == 1) {
                    $modelRow .= "<img id='hipoteseFavorito' name='" . $id . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
            //echo jsonRemoveUnicodeSequences($modelRow);
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 5) { //Desmarcar como favorito
        if (marcadesmarcaFavoritoHipotese($idd, $idm, "NULL")) {
            $modelRow = "";
            foreach (buscaHipoteseDoencas($idm, $filtro) as $array) {
                $id = ($array['id']);
                $name = ($array['nome']);
                $fav = $array['favorito'];
                $star = "view/img/star.png";
                $cod = $array['codigo'];
                $modelRow .= "<tr id = '" . $id . "' value='" . $array['codigo'] . "' name = 'table-color' class= 'dayhead doencaRow'><th allign='center'>";
                $modelRow .= "<input type='checkbox' name='" . $id . "' id='hipoteses' value = '" . $cod . " - " . $name . "' ";
                $modelRow .= "onchange='atualizaTextareaHipotese()' /> " . $name . " ";
                if ($fav == 1) {
                    $modelRow .= "<img id='hipoteseFavorito' name='" . $id . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
            //echo jsonRemoveUnicodeSequences($modelRow);
        } else {
            echo saidaJson(0);
        }
    }

    echo $modelRow;
} else if ($_POST['flag'] == 'atualizaPrestador') {

    $idp = $_POST['id'];
    $end = $_POST['endereco'];
    $nome = $_POST['nome'];
    $tel = $_POST['telefone'];
    $email = $_POST['email'];
    $modelRow = "";
    $star = "view/img/star.png";
    $opcao = $_POST['opcao'];

    if ($opcao == 1) { //Nova prestador                
        if (inserirPrestador($nome, $end, $tel, $email)) {
            $modelRow = "";
            foreach (buscaPrestador() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id='" . $array['id'] . "' name='table-color' class='dayhead prestadorRow' onClick='atualizaServicoPrestador();' >";
                $modelRow .= "<th align='center'><input id='prestador' type='radio' name='prestador' value ='" . $array['id'] . "'> " . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='prestadorFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 2) { //Editar prestador
        if (editarPrestador($idp, $nome, $end, $tel, $email)) {
            $modelRow = "";
            foreach (buscaPrestador() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id='" . $array['id'] . "' name='table-color' class='dayhead prestadorRow' onClick='atualizaServicoPrestador();' >";
                $modelRow .= "<th align='center'><input id='prestador' type='radio' name='prestador' value ='" . $array['id'] . "'> " . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='prestadorFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 3) { //Remover prestador     
        if (removerPrestador($idp)) {
            $modelRow = "";
            foreach (buscaPrestador() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id='" . $array['id'] . "' name='table-color' class='dayhead prestadorRow' onClick='atualizaServicoPrestador();' >";
                $modelRow .= "<th align='center'><input id='prestador' type='radio' name='prestador' value ='" . $array['id'] . "'> " . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='prestadorFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 4) { //Marcar como favorito
        if (marcadesmarcaFavoritoPrestador($idp, 1)) {
            $modelRow = "";
            foreach (buscaPrestador() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id='" . $array['id'] . "' name='table-color' class='dayhead prestadorRow' onClick='atualizaServicoPrestador();' >";
                $modelRow .= "<th align='center'><input id='prestador' type='radio' name='prestador' value ='" . $array['id'] . "'> " . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='prestadorFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 5) { //Desmarcar como favorito
        if (marcadesmarcaFavoritoPrestador($idp, "NULL")) {
            $modelRow = "";
            foreach (buscaPrestador() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id='" . $array['id'] . "' name='table-color' class='dayhead prestadorRow' onClick='atualizaServicoPrestador();' >";
                $modelRow .= "<th align='center'><input id='prestador' type='radio' name='prestador' value ='" . $array['id'] . "'> " . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='prestadorFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    }

    echo $modelRow;
} else if ($_POST['flag'] == 'atualizaRequisicao') {

    $idr = $_POST['idr'];
    $idp = $_POST['idp'];
    $nome = $_POST['nome'];
    $subt = $_POST['subt'];
    $modelRow = "";
    $star = "view/img/star.png";
    $opcao = $_POST['opcao'];

    if ($opcao == 1) { //Nova prestador                
        if (inserirRequisicao($nome, $subt, $idp)) {
            $modelRow = "";
            foreach (buscaRequisicaoServico() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id=" . $array['id'] . " name='table-color' class='dayhead servicoRow' onClick='atualizaServicoPrestador();' ><th align='center'>";
                $modelRow .= "<input id='reqservico' type='radio' name='reqServicos' value ='" . $array['id'] . "' />" . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='servicoFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 2) { //Editar prestador
        if (editarRequisicao($idr, $idp, $nome, $subt)) {
            $modelRow = "";
            foreach (buscaRequisicaoServico() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id=" . $array['id'] . " name='table-color' class='dayhead servicoRow' onClick='atualizaServicoPrestador();' ><th align='center'>";
                $modelRow .= "<input id='reqservico' type='radio' name='reqServicos' value ='" . $array['id'] . "' />" . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='servicoFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 3) { //Remover prestador     
        if (removerRequisicao($idr)) {
            $modelRow = "";
            foreach (buscaRequisicaoServico() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id=" . $array['id'] . " name='table-color' class='dayhead servicoRow' onClick='atualizaServicoPrestador();' ><th align='center'>";
                $modelRow .= "<input id='reqservico' type='radio' name='reqServicos' value ='" . $array['id'] . "' />" . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='servicoFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 4) { //Marcar como favorito
        if (marcadesmarcaFavoritoRequisicao($idr, 1)) {
            $modelRow = "";
            foreach (buscaRequisicaoServico() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id=" . $array['id'] . " name='table-color' class='dayhead servicoRow' onClick='atualizaServicoPrestador();' ><th align='center'>";
                $modelRow .= "<input id='reqservico' type='radio' name='reqServicos' value ='" . $array['id'] . "' />" . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='servicoFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    } else if ($opcao == 5) { //Desmarcar como favorito
        if (marcadesmarcaFavoritoRequisicao($idr, "NULL")) {
            $modelRow = "";
            foreach (buscaRequisicaoServico() as $array) {
                $fav = $array['favorito'];
                $modelRow .= "<tr id=" . $array['id'] . " name='table-color' class='dayhead servicoRow' onClick='atualizaServicoPrestador();' ><th align='center'>";
                $modelRow .= "<input id='reqservico' type='radio' name='reqServicos' value ='" . $array['id'] . "' />" . $array['nome'];
                if ($fav == 1) {
                    $modelRow .= "<img id='servicoFavorito' name='" . $array['id'] . "' align='right' src='" . $star . "' />";
                }
                $modeRow .= "</th></tr>";
            }
        } else {
            echo saidaJson(0);
        }
    }

    echo $modelRow;
} else if ($_POST['flag'] == 'filtroHipoteseDiag') {
    $medico_id = $_POST['idMedico'];
    $filtro = $_POST['filtro'];
    $array = buscaHipoteseDiagnostica($medico_id, $filtro);
    die(saidaJson($array));

//} else if($_POST['flag'] == 'buscaReqServico') {
//
//	$idPrestador = $_POST['idPrestador'];
//	$array = buscaRequisicaoServico($idPrestador);
//
//	echo saidaJson($array);
} else if ($_POST['flag'] == 'textoReqServico') {

    $idPrestador = $_POST['idPrestador'];
    $idServico = $_POST['idServico'];
    $prestador = buscaPrestadorById($idPrestador);
    $servico = buscaRequisicaoServicoById($idServico);
    $texto['prestador'] = $prestador['nome'];
    $texto['telefone'] = $prestador['telefone'];
    $texto['endereco'] = $prestador['endereco'];
    $texto['email'] = $prestador['email'];
    $texto['servico'] = $servico['nome'];
    $texto['subtestes'] = $servico['subtestes'];

    die(saidaJson($texto));
} else if ($_POST['flag'] == 'defTabelaReqServico') {
    $tmp = buscaConsultaHasRequisicaoServico($_POST['idCliente']);

    $dadoss = "";
    foreach ($tmp as $t) {
        $dados = "";
        $dados['cliente_id'] = $t['cliente_id'];
        $dados['data'] = $t['data'];
        $dados['nome'] = $t['nome'];
        $dados['prestador_id'] = $t['prestador_id'];

        $texto = split("\n", $t['encaminhamento']);
        $arraytratada = "";
        foreach ($texto as $t2) {
            if (strripos($t2, "-") < 2 && strripos($t2, "-") !== false) {
                $arraytratada[] = $t2;
            }
        }
        $dados['encaminhamento'] = $arraytratada;
        $dadoss[] = $dados;
    }
    die(saidaJson($dadoss));
} else if ($_POST['flag'] == 'buscaReqServicoImpressao') {
    $tmp = buscaConsultaHasRequisicaoServicoPorPrestador($_POST['idCliente'], $_POST['idPrestador'], $_POST['data']);
    $dados = "";
    $dados['nomeCliente'] = $_POST['nomeCliente'];
    foreach ($tmp as $t) {
        $dados['cliente_id'] = $t['cliente_id'];
        $dados['data'] = $t['data'];
        $dados['nome'] = $t['nome'];
        $dados['prestador_id'] = $t['prestador_id'];
        $dados['encaminhamento'] = $t['encaminhamento'];
    }

    die(saidaJson($dados));
} else if ($_POST['flag'] == 'appendReqServico') {

    $dadosConsulta['medico_id'] = $_SESSION['usuario']['id']; // _INPUT('idMedico','int','post');
    $dadosConsulta['cliente_cliente_id'] = _INPUT('idCliente', 'int', 'post');
    $dadosConsulta['data'] = _INPUT('data', 'string', 'post');
    //$idConsulta = confereExistenciaConsulta($dadosConsulta);        

    $idPrestador = $_POST['prestador'];
    $idServico = $_POST['servicos'];
    $prestador = buscaPrestadorById($idPrestador);
    $servico = buscaRequisicaoServicoById($idServico);
    $dados['cliente_id'] = $dadosConsulta['cliente_cliente_id'];
    $dados['data'] = $_POST['data'];
    $dados['prestador_id'] = $idPrestador;
    $dados['requisicao'] = $_POST['requisicao'];
    $dados['encaminhamento'] = $_POST['encaminhamento'];

    $texto = split("\n", $dados['requisicao']);
    $arraytratada = "";
    $first = true;
    foreach ($texto as $t2) {
        if (strpos($t2, "-") < 2 && strpos($t2, "-") !== false) {
            if ($first === true) {
                $arraytratada .= $t2;
                $first = false;
            } else {
                $arraytratada .= "\n" . $t2;
            }
        }
    }
    $dados['requisicao'] = $arraytratada;

    $requisicao = verificaConsultaHasReqServ($dados);
    if ($requisicao) {
        if ($dados['requisicao'] !== "") {
            $dados['encaminhamento'] = $requisicao['encaminhamento'] . "\n" . $dados['requisicao'];
        }
        atualizaServicoPrestador($dados);
    } else {
        if ($dados['requisicao'] !== "") {
            $dados['encaminhamento'] = $dados['requisicao'];
            insereServicoPrestador($dados);
        }
    }

    $tabela['id'] = $dados['cliente_id'] . "-" . $dados['prestador_id'] . "-" . $dados['data'];
    $tabela['prestador'] = $prestador['nome'];
    $tabela['servicos'] = explode(" -", $servico['subtestes']);
    $tabela['data'] = converteData(date('Y-m-d'));
    $tabela['encaminhamento'] = $dados['encaminhamento'];
    //$tabela['servicos'] = $servico['subtestes'];

    die(saidaJson($tabela));
} else if ($_POST['flag'] == 'deleteReqServico') {
    $id = explode("-", $_POST['id']);
    $stringdelete = "cliente_id = $id[0] AND prestador_id = $id[1] AND data = '$id[2]-$id[3]-$id[4]'";

    deletaServicoPrestador($stringdelete);
} else if ($_POST['flag'] == 'deleteReqServicoLinha') {
    $dados['prestador_id'] = $_POST['idPrestador'];
    $dados['cliente_id'] = $_POST['idCliente'];
    $dados['data'] = $_POST['data'];
    $id = $_POST['idServicos'];

    $requisicao = buscaConsultaHasRequisicaoServicoPorPrestador($dados['cliente_id'], $dados['prestador_id'], $dados['data']);
    $requisicao = $requisicao[0];
    $requisicao = $requisicao['encaminhamento'];

    $texto = split("\n", $requisicao);

    $arraytratada = "";
    $first = true;
    for ($i = 0; $i < sizeof($texto); $i++) {
        if (strpos($texto[$i], "-") < 2 && $i != $id) {
            if ($first === true) {
                $arraytratada .= $texto[$i];
                $first = false;
            } else {
                $arraytratada .= "\n" . $texto[$i];
            }
        }
    }
    $dados['encaminhamento'] = $arraytratada;

    atualizaServicoPrestador($dados);
} else if ($_POST['flag'] == 'filtroCID') {
    $filtro = $_POST['filtro'];
    $consulta_id = $_POST['consulta_id'];
    Database::query("SELECT idCID,grupo,descricao,IF(idCID NOT IN (SELECT idCID FROM `cid10` a inner join `consultas_has_cid10` b ON a.idCID = b.cid10_idCID WHERE b.consultas_id = '$consulta_id'),0,1) as 'marcado' FROM `cid10` WHERE idCID like '%$filtro%' || descricao like '%$filtro%' ORDER BY marcado DESC,idCID  LIMIT 1000");
    $return = Database::fetchAll();
    die(saidaJson($return));
} else if ($_POST['flag'] == 'deletaPrescricao') {

    $prescricoes = _INPUT('prescricoes', 'string', 'post');

    foreach ($prescricoes as $prescricao) {
        list($idstr, $nome) = split("-", $prescricao);
        $id = trim($idstr);

        if (!deletePrescricao($id)) {
            die(saidaJson(0));
        }
    }

    die(saidaJson(1));
} else if ($_POST['flag'] == 'editaTextoPrescricao') {

    $idPrescricao = _INPUT('idPrescricao', 'string', 'post');
    $dadosPrescricao['descricao'] = _INPUT('texto', 'string', 'post');

    if (atualizaPrescricao($dadosPrescricao, $idPrescricao)) {
        echo saidaJson(1);
    } else {
        echo saidaJson(0);
    }
} else if ($_POST['flag'] == 'insereNovaPrescricao') {

    $dadosPrescricao['nome'] = _INPUT('nomePrescricao', 'string', 'post');
    $dadosPrescricao['medico_respons_id'] = _INPUT('idMedico', 'string', 'post');

    if (insereNovaPrescricao($dadosPrescricao))
        echo saidaJson(1);
    else
        echo saidaJson(0);

    die();
} elseif ($_POST['flag'] == "pegaHistoricoDeAtendimento") {
//                die("Ate aqui ok");
    if ($_POST['subflag'] === '2') {
        die(saidaJson(pegaInformaçõesDoCliente($_POST['clienteID'])));
    } else {
        if ($_POST['clienteID']) {
            die(saidaJson(pegaHistoricoDeAtendimentoDoCliente($_POST['clienteID'])));
        } else {
            die('null');
        }
    }

    /* /
     * As actions abaixo, são responsáveis por buscar as datas das ultimas
     * consultas de cada categoria.
     */
} elseif ($_POST['flag'] == "buscaULtimasConsultasOlhoData") {
    $arr = buscaConsultasDataPorCategoria($_POST['idCliente'], 1);
    die(saidaJson($arr));
} elseif ($_POST['flag'] == "buscaULtimasConsultasOrelha1Data") {
    $arr = buscaConsultasDataPorCategoria($_POST['idCliente'], 2);
    die(saidaJson($arr));
} elseif ($_POST['flag'] == "buscaULtimasConsultasOrelha2Data") {
    $arr = buscaConsultasDataPorCategoria($_POST['idCliente'], 3);
    die(saidaJson($arr));
} elseif ($_POST['flag'] == "buscaULtimasConsultasCoracaoData") {
    $arr = buscaConsultasDataPorCategoria($_POST['idCliente'], 4);
    die(saidaJson($arr));
} elseif ($_POST['flag'] == "buscaULtimasConsultasLinguaData") {
    $arr = buscaConsultasDataPorCategoria($_POST['idCliente'], 5);
    die(saidaJson($arr));
    /*
     * Fim bloco de actions para buscar data.
     */

    /*
     * As actions abaixo, são responsáveis por colocar os dados 
     * de uma consulta na sessão, para utilizá-los na imporessão.
     */
} elseif ($_POST['flag'] == "imprimeResultadoOlhinho") {
    $_SESSION['triagem']['olhoArr'] = json_decode($_POST['olho'], true);
    $_SESSION['triagem']['olhoArr']['consulta'] = json_decode($_POST['olhArr'], true);

    $arr = buscaClientePorId($_POST['clienteId']);
    $dataArr = explode('/', $arr['nascimento']);

    $data = Time::getNomeDoMes($dataArr[1], 0);
    $dataArr[1] = strtolower($data);
    $arr['nascimento'] = implode($dataArr);

    $_SESSION['triagem']['olhoArr']['cliente'] = $arr;
    $_SESSION['triagem']['olhoArr']['medico'] = buscaMedicoPorCliente($_POST['clienteId']);
    die();
} elseif ($_POST['flag'] == "imprimeResultadoOrelhinha1") {
    $_SESSION['triagem']['orelha1rr'] = json_decode($_POST['orelha1'], true);
    die();
} elseif ($_POST['flag'] == "imprimeResultadoOrelhinha2") {
    $_SESSION['triagem']['orelha2Arr'] = json_decode($_POST['orelha2'], true);
    $_SESSION['triagem']['orelha2Arr']['consulta'] = json_decode($_POST['orel2Arr'], true);

    $arr = buscaClientePorId($_POST['clienteId']);
    $dataArr = explode('/', $arr['nascimento']);

    $data = Time::getNomeDoMes($dataArr[1], 0);
    $dataArr[1] = strtolower($data);
    $arr['nascimento'] = implode($dataArr);

    $_SESSION['triagem']['orelha2Arr']['cliente'] = $arr;
    $_SESSION['triagem']['orelha2Arr']['medico'] = buscaMedicoPorCliente($_POST['clienteId']);
    die();
} elseif ($_POST['flag'] == "imprimeResultadoCoracaozinho") {
    $_SESSION['triagem']['coracaoArr'] = json_decode($_POST['coracao'], true);
    $_SESSION['triagem']['coracaoArr']['consulta'] = json_decode($_POST['corArr'], true);

    $arr = buscaClientePorId($_POST['clienteId']);
    $dataArr = explode('/', $arr['nascimento']);

    $data = Time::getNomeDoMes($dataArr[1], 0);
    $dataArr[1] = strtolower($data);
    $arr['nascimento'] = implode($dataArr);

    $_SESSION['triagem']['coracaoArr']['cliente'] = $arr;
    $_SESSION['triagem']['coracaoArr']['medico'] = buscaMedicoPorCliente($_POST['clienteId']);
    die();
} elseif ($_POST['flag'] == "imprimeResultadoLinguinha") {
    $_SESSION['triagem']['linguaArr'] = json_decode($_POST['lingua'], true);
    $_SESSION['triagem']['linguaArr']['consulta'] = json_decode($_POST['linArr'], true);

    $arr = buscaClientePorId($_POST['clienteId']);
    $dataArr = explode('/', $arr['nascimento']);

    $data = Time::getNomeDoMes($dataArr[1], 0);
    $dataArr[1] = strtolower($data);
    $arr['nascimento'] = implode($dataArr);

    $_SESSION['triagem']['linguaArr']['cliente'] = $arr;
    $_SESSION['triagem']['linguaArr']['medico'] = buscaMedicoPorCliente($_POST['clienteId']);

    die();
    /*
     * Fim bloco de actions para alimentar a sessão para impressão.
     */
} elseif ($_POST['flag'] == "pegaClientesporPeriodo") {
    $medico_id = $_SESSION['usuario']['id'];
    if (!$medico_id) {
        die(saidaJson('Usuário não está logado para fazer a pesquisa'));
    }
    $periodoInicial = $_POST['PeriodoInicial'];
    $periodoFinal = $_POST['PeriodoFinal'];
    $arr = pegaColsutasporClientesporPeriodo($medico_id, $periodoInicial, $periodoFinal);
    die(saidaJson($arr));
} elseif ($_POST['flag'] == "pegaHistoricoDeAtendimentoTriagem") {
    if ($_POST['clienteID']) {
        if ($_POST['tipo'] == 'data') {
            $data = Time::change($_POST['data']);
            $arr = pegaHistoricoDeAtendimentoDoClienteTriagem($_POST['clienteID'], $_POST['idCategoria'], $data);
        } else {
            $arr = '';
            //$arr = pegaHistoricoDeAtendimentoDoClienteTriagem($_POST['clienteID'], $_POST['idCategoria']);
        }

        die(saidaJson($arr));
    } else {
        die('null');
    }
} else if ($_POST['flag'] === 'atualizaCliente') {
    die(saidaJson(atualizaDadosDoCliente($_POST)));
} else if ($_POST['flag'] === 'atualizaConsulta') {
    if ($_POST['novo'] === 'true') {
        die(saidaJson(adicionaNovaConsulta($_POST)));
    } else {
        die(saidaJson(atualizaDadosConsulta($_POST)));
    }
} else if ($_POST['flag'] === 'pegaConsultaporMedico') {

    if (isset($_SESSION['usuario']['id']) && isset($_POST['indexLimit'])) {
//                die('here!');
        die(saidaJson(BuscaConsultasIndexadaPorMedico($_SESSION['usuario']['id'], $_POST['indexLimit'])));
    } else {
        die('Login não encontrado');
    }
} else if ($_POST['flag'] === 'getLogeddUser') {

    die(saidaJson($_SESSION['usuario']));
} else if (($_POST['flag'] === 'atualizaAtestado')) {

    $dadosAtestado['nome'] = $_POST['titulo'];
    $dadosAtestado['descricao'] = html_entity_decode(mysqli_escape_string(mysqli_real_escape_string($_POST['texto'])));
    //$dadosAtestado['descricao'] = mysqli_escape_string($_POST['texto']);
    $opcao = $_POST['opcao'];
    //die(print_r($dadosAtestado));

    if ($opcao == 1) { //Novo atestado    
        if (inserirAtestado($dadosAtestado)) {
            $modelRow = "";
            foreach (buscaAtestados() as $at) {
                $idAtestado = $at['id'];
                $nomeAtestado = utf8_encode($at['nome']);
                $descricaoAtestado = (utf8_encode(str_replace("'", "", $at['descricao'])));
                $modelRow .= "<tr name=" . $idAtestado . " id=\"IdAtestado\" class=\"dayhead \" onClick=\"carregaAtestado('" . $descricaoAtestado . "');\"><th align=\"center\">" . $nomeAtestado . "</th></tr>";
            }
            echo saidaJson($modelRow);
        } else
            echo saidaJson(0);
    } else if ($opcao == 2) { //Editar atestado
        if (atualizarAtestado($_POST['id'], $dadosAtestado)) {
            $modelRow = "";
            foreach (buscaAtestados() as $at) {
                $idAtestado = $at['id'];
                $nomeAtestado = utf8_encode($at['nome']);
                $descricaoAtestado = (utf8_encode(str_replace("'", "", $at['descricao'])));
                $modelRow .= "<tr name=" . $idAtestado . " id=\"IdAtestado\" class=\"dayhead \" onClick=\"carregaAtestado('" . $descricaoAtestado . "');\"><th align=\"center\">" . $nomeAtestado . "</th></tr>";
            }
            echo saidaJson($modelRow);
        } else
            echo saidaJson(0);
    } else if ($opcao == 3) { //Remover atestado        
        if (removerAtestado($_POST['id'])) {
            $modelRow = "";
            foreach (buscaAtestados() as $at) {
                $idAtestado = $at['id'];
                $nomeAtestado = utf8_encode($at['nome']);
                $descricaoAtestado = (utf8_encode(str_replace("'", "", $at['descricao'])));
                $modelRow .= "<tr name=" . $idAtestado . " id=\"IdAtestado\" class=\"dayhead \" onClick=\"carregaAtestado('" . $descricaoAtestado . "');\"><th align=\"center\">" . $nomeAtestado . "</th></tr>";
            }
            echo saidaJson($modelRow);
        } else
            echo saidaJson(0);
    }
} else if (($_POST['flag'] === 'removerConsultaHistorico')) {
    if (removeConsulta($_POST['idConsulta']))
        echo saidaJson(1);
    else
        echo saidaJson(0);
} else if (($_POST['flag'] === 'imprimirResultadoPrescricao')) {
    $_SESSION['prescricao']['prescricaoArray'] = json_decode($_POST['prescricao'], true);
    die();
} else if (($_POST['flag'] === 'getDadosPessoaPorId')) {
    echo saidaJson(dadosPessoa($_POST['id']));
} else if (($_POST['flag'] === 'salvarHipotese')) {
    atualizaHipoteseConsulta($_POST['idConsulta'], $_POST['hipotese']);
} else if (($_POST['flag'] === 'salvarPrescricoes')) {
    if (atualizaPrescricoesConsulta($_POST['idConsulta'], $_POST['prescricao']))
        echo saidaJson(1);
    else
        echo saidaJson(0);
} else if (($_POST['flag'] === 'salvarAtestado')) {
    if (atualizaAtestadoConsulta($_POST['idConsulta'], $_POST['atestado']))
        echo saidaJson(1);
    else
        echo saidaJson(0);
} else if (($_POST['flag'] === 'getDadosConsulta')) {
    echo saidaJson(buscaDadosConsulta($_POST['idConsulta']));
} else if (($_POST['flag'] === 'getDadosCliente')) {
    echo saidaJson(buscaDadosCliente($_POST['idCliente']));
} else if (($_POST['flag'] === 'getDadosMedico')) {
    echo saidaJson(buscaDadosMedico($_POST['idMedico']));
} else if (($_POST['flag'] === 'atualizarAntecedentePessoal')) {
    atualizarAntecedentesPessoal($_POST['id'], $_POST['data']);
    die();
} else if (($_POST['flag'] === 'atualizarAntecedenteFamiliar')) {
    atualizarAntecedentesFamiliares($_POST['id'], $_POST['data']);
    die();
} else if (($_POST['flag'] === 'boletimImunobiologico')) {
//	die("aqui");
	$inicio = $_POST['inicio'];
	$fim = $_POST['fim'];
    $array = pegaBoletimImunobiologico($inicio, $fim);
//	die(print_r($array));
    die(saidaJson($array));
}else if (($_POST['flag'] === 'imprimirHistoricoConsulta')) {
    $_SESSION['historicoConsulta']['historicoConsultaArray'] = json_decode($_POST['historicoConsulta'], true);
    die();
} else if (($_POST['flag'] === 'buscarTodasHipotesesCliente')) {
    echo saidaJson(buscaTodasHipoteseCliente($_POST['idCliente']));
} else if (($_POST['flag'] === 'buscaNomeMedicoCRM')) {
    echo saidaJson(buscarMedicoCrm($_POST['idCliente']));
} else if (($_POST['flag'] === 'imprimirAtestado')) {
    $_SESSION['atestadoConsulta']['atestadoConsultaText'] = $_POST['text'];
    die();
} else if (($_POST['flag'] === 'imprimirPrestServ')) {
    $_SESSION['prestadorServico']['prestadorServicoText'] = json_decode($_POST['prestadorServico'], true);
    die();
} else if (($_POST['flag'] === 'imprimirTesteCutaneo')) {
    
    $tc = "";
    $tc['gat'] = 'NE';$tc['cão'] = 'NE';$tc['pó'] = 'NE';$tc['fgs'] = 'NE';$tc['tab'] = 'NE';$tc['bar'] = 'NE';$tc['pen'] = 'NE';
    $tc['lte'] = 'NE';$tc['tgo'] = 'NE';$tc['ave'] = 'NE';$tc['can'] = 'NE';$tc['cas'] = 'NE';$tc['cam'] = 'NE';$tc['car'] = 'NE';$tc['cho'] = 'NE';$tc['ovo'] = 'NE';$tc['pxe'] = 'NE';
    $tc['btr'] = 'NE';$tc['dpt'] = 'NE';$tc['dfa'] = 'NE';
    $tc['afu'] = 'NE';$tc['aal'] = 'NE';$tc['cal'] = 'NE';$tc['che'] = 'NE';$tc['cgl'] = 'NE';$tc['mmu'] = 'NE';$tc['pno'] = 'NE';$tc['ppu'] = 'NE';
    $tc['que'] = 'NE';$tc['suí'] = 'NE';$tc['cap'] = 'NE';$tc['bov'] = 'NE';$tc['ovi'] = 'NE';$tc['coe'] = 'NE';$tc['gat2'] = 'NE';$tc['cão2'] = 'NE';
    $tc['pga'] = 'NE';$tc['mos'] = 'NE';$tc['for'] = 'NE';$tc['cul'] = 'NE';$tc['lát'] = 'NE';
    $tcng = "";
    $tcng['ant'] = 'NE';$tcng['bal'] = 'NE';$tcng['ppd'] = 'NE';$tcng['hid'] = 'NE';$tcng['bik'] = 'NE';
    $tcng['plg'] = 'NE';$tcng['but'] = 'NE';$tcng['neo'] = 'NE';$tcng['irg'] = 'NE';$tcng['kat'] = 'NE';
    $tcng['cbz'] = 'NE';$tcng['lan'] = 'NE';$tcng['tiu'] = 'NE';$tcng['eti'] = 'NE';$tcng['per'] = 'NE';
    $tcng['mer'] = 'NE';$tcng['ben'] = 'NE';$tcng['qar'] = 'NE';$tcng['qui'] = 'NE';$tcng['nit'] = 'NE';
    $tcng['prb'] = 'NE';$tcng['res'] = 'NE';$tcng['thi'] = 'NE';$tcng['tbt'] = 'NE';$tcng['cab'] = 'NE';
    $tcng['pmz'] = 'NE';$tcng['sfn'] = 'NE';$tcng['clf'] = 'NE';$tcng['pfd'] = 'NE';$tcng['fmd'] = 'NE';
    $tcng['an'] = 'NE';$tcng['ba'] = 'NE';$tcng['pp'] = 'NE';$tcng['hi'] = 'NE';$tcng['bi'] = 'NE';
    $tcng['pl'] = 'NE';$tcng['bu'] = 'NE';$tcng['ne'] = 'NE';$tcng['ir'] = 'NE';$tcng['ka'] = 'NE';
    $tcng['cb'] = 'NE';$tcng['la'] = 'NE';$tcng['ti'] = 'NE';$tcng['et'] = 'NE';$tcng['pe'] = 'NE';
    $tcng['me'] = 'NE';$tcng['be'] = 'NE';$tcng['qa'] = 'NE';$tcng['qu'] = 'NE';$tcng['ni'] = 'NE';
    $tcng['pr'] = 'NE';$tcng['re'] = 'NE';$tcng['th'] = 'NE';$tcng['tb'] = 'NE';$tcng['ca'] = 'NE';
    $tcng['pm'] = 'NE';$tcng['sf'] = 'NE';$tcng['cl'] = 'NE';$tcng['pf'] = 'NE';$tcng['fm'] = 'NE';
    
    $tc['nome'] = $_POST['nome'];
    $tcng['nome'] = $_POST['nome'];
    
    if ($_POST['tcversao'] == 1) {
        
        $dados = $_POST['testeCutaneo'];
        $dados = strtolower($dados);
        $dados = split(',', $dados);                
        $ant = '';
        foreach($dados as $dado){                        
            if($ant == 'coe'){
                $tc['gat2'] = substr($dado, strrpos($dado, '(')+1, substr($dado, strrpos($dado, '(')) - 1);            
                $ant = 'gat2';
            }else if($ant =='gat2'){
                $tc['cão2'] = substr($dado, strrpos($dado, '(')+1, substr($dado, strrpos($dado, '(')) - 1);                            
                $ant = '';
            }else{
                $tc[substr($dado, 0, strrpos($dado, '('))] = substr($dado, strrpos($dado, '(')+1, substr($dado, strrpos($dado, '(')) - 1);            
                $ant = substr($dado, 0, strrpos($dado, '('));
            }                 
        }
        
        $_SESSION['testecutaneo']['testecutaneoAntigo'] = $tc;
        die();
    } else {
        
        $dados = $_POST['testeCutaneo'];
        $dados = strtolower($dados);
        $dados = split(',', $dados);                        
        foreach($dados as $dado){                        
            $tcng[substr($dado, 0, strrpos($dado, '('))] = substr($dado, strrpos($dado, '(')+1, substr($dado, strrpos($dado, '(')) - 1);                        
        }
        
        $_SESSION['testecutaneo']['testecutaneoNovo'] = $tcng;
        die();
    }
} else {
    include('view/consultas/consultas.phtml');
}
