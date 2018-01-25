<?php

$tipoForm = _INPUT('tipoForm', 'string', 'post');

if ($tipoForm == "fatura_data_parcelas") {

    $data = _INPUT('data_venc', 'string', 'post');
    $num_parcelas = _INPUT('num_parcelas', 'string', 'post');
    $valor = _INPUT('valor', 'string', 'post');
    $intervalo = _INPUT('intervalo_parcelas', 'string', 'post');
    $tipo = 'dia';
    $qtd = 0;
    $arrData[] = somarData($data, $qtd, $tipo);
    if ($num_parcelas == 1) {
        $arrData[][0] = $data;
        $arrData[][1] = $valor;
        $arrData[][2] = 1;
    } else {
        for ($i = 1; $i < $num_parcelas; $i++) {

            $qtd = $i * $intervalo;
            $arrData[][0] = somarData($data, $qtd, $tipo);
            $arrData[][1] = $valor / $num_parcelas;
            $arrData[][2] = $i;
        }
    }

    die(saidaJson($arrData));
} else if ($tipoForm == "fatura_busca") {

    $arr['clientes'] = buscaNomeClientesCnpj();
    $arr['empresas'] = buscaNomeEmpresas();
    $arr['tipo_doc'] = buscaNomeTipoDoc();
    $arr['bancos'] = buscaNomeBanco();
    $arr['moedas'] = buscaNomeMoedas();
    $arr['plano_contas'] = buscaNomePlanoContas();

    $arr['consulta_clientes'] = $arr['clientes'];
    $arr['consulta_empresas'] = $arr['empresas'];
    $arr['consulta_tipo_doc'] = $arr['tipo_doc'];

    $arr['consulta_clientes'][] = "TODOS";
    $arr['consulta_empresas'][] = "TODOS";
    $arr['consulta_tipo_doc'][] = "TODOS";

    echo saidaJson($arr);
} else if ($tipoForm == "busca_por_codigo") {

    $flag = _INPUT('flag', 'string', 'post');
    $cod = _INPUT('cod', 'int', 'post');

    if ($flag == 'cliente') {
        if ($cod == '00') {
            $nome = "TODOS";
        } else {
            $nome = buscaNomeClientePorCodigo($cod);
        }
    } else if ($flag == 'empresa') {
        if ($cod == '00') {
            $nome = "TODOS";
        } else {
            $nome = buscaNomeEmpresaPorCodigo($cod);
        }
    } else if ($flag == 'tipoDoc') {
        if ($cod == '00') {
            $nome = "TODOS";
        } else {
            $nome = buscaNomeTipoDocPorCodigo($cod);
        }
    } else if ($flag == 'banco') {
        $nome = buscaNomeBancoPorCodigo($cod);
    } else if ($flag == 'moeda') {
        $nome = buscaNomeMoedaPorCodigo($cod);
    } else if ($flag == 'plano_contas') {
        $nome = buscaNomePlanoContasPorCodigo($cod);
    }

    echo saidaJson($nome);
} else if ($tipoForm == "insere_faturas") {
    $numero_fatura = _INPUT('numero_fatura', 'string', 'post');
    $tipo_cliente = _INPUT('tipo_cliente', 'string', 'post');
    $cliente = _INPUT('cliente', 'string', 'post');
    $empresa = _INPUT('empresa', 'string', 'post');
    $tipoDoc = _INPUT('tipoDoc', 'string', 'post');
    $banco = _INPUT('banco', 'string', 'post');
    $moeda = _INPUT('moeda', 'string', 'post');
    $plano_contas = _INPUT('plano_contas', 'string', 'post');
    $num_parcelas = _INPUT('num_parcelas', 'string', 'post');
    $data_emissao = converteData(_INPUT('data_emissao', 'string', 'post'));
    $data_vencimento = converteData(_INPUT('data_vencimento', 'string', 'post'));
    $intervalo_parcelas = _INPUT('intervalo_parcelas', 'string', 'post');
    $obs = _INPUT('obs', 'string', 'post');
    $parcelas = _INPUT('parcelas', 'string', 'post');

//        die(print_r($parcelas));
    $dadosFatura['tipo_cliente_id'] = $tipo_cliente;
    $dadosFatura['clientes_cnpj_id'] = buscaIdClienteCnpjPorNome($cliente);
    $dadosFatura['empresa_id'] = buscaIdEmpresaPorNome($empresa);
    $dadosFatura['tipo_doc_id'] = buscaIdTipoDocPorNome($tipoDoc);
    $dadosFatura['banco_id'] = buscaIdBancoPorNome($banco);
    $dadosFatura['moeda_id'] = buscaIdMoedaPorNome($moeda);
    $dadosFatura['plano_contas_id'] = buscaIdPlanoContasPorNome($plano_contas);
    $dadosFatura['status_id'] = buscaIdStatusPorNome("Em Aberto");
    $dadosFatura['usuario_id'] = $_SESSION['usuario']['id'];
    $dadosFatura['data_lancamento'] = date('Y-m-d');
    $dadosFatura['data_emissao'] = $data_emissao;
    $dadosFatura['observacao'] = $obs;
    $dadosFatura['numero_fatura'] = $numero_fatura;
//        die(print_r($dadosFatura));
    if (insereFatura($dadosFatura)) {
        $dadosFaturaParcela['fatura_id'] = mysqli_insert_id(banco::$connection);
        $dadosFaturaParcela['status_id'] = $dadosFatura['status_id'];
        foreach ($parcelas as $parcela) {
            $dadosFaturaParcela['data_vencimento'] = converteData($parcela[0]);
            $dadosFaturaParcela['valor'] = $parcela[1];
            $dadosFaturaParcela['numero'] = $parcela[2];
            die(print_r($dadosFaturaParcela));
            insereFaturaParcelas($dadosFaturaParcela);
        }

        echo saidaJson(1);
    }
} else if ($tipoForm == "consulta_lista_faturas") {

    $nomeCliente = _INPUT('cliente', 'string', 'post');
    $nomeEmpresa = _INPUT('empresa', 'string', 'post');
    $nomeTipoDoc = _INPUT('tipoDoc', 'string', 'post');

    if ($nomeCliente == "TODOS") {
        $clienteId = '00';
    } else {
        $clienteId = buscaIdClienteCnpjPorNome($nomeCliente);
    }

    if ($nomeEmpresa == "TODOS") {
        $empresaId = '00';
    } else {
        $empresaId = buscaIdEmpresaPorNome($nomeEmpresa);
    }

    if ($nomeTipoDoc == "TODOS") {
        $tipoDocId = '00';
    } else {
        $tipoDocId = buscaIdTipoDocPorNome($nomeTipoDoc);
    }

//	$tipoClienteId = _INPUT('tipo_cliente','string','post');
    $moedaId = _INPUT('moeda', 'string', 'post');
    $statusId = _INPUT('status', 'string', 'post');
    $selecionado = _INPUT('selecionado', 'string', 'post');
    $data_inicio = converteData(_INPUT('data_inicio', 'string', 'post'));
    $data_fim = converteData(_INPUT('data_fim', 'string', 'post'));
    $ordenado = _INPUT('ordenado', 'string', 'post');

    unset($cabecalho);
    $cabecalho['cliente'] = $nomeCliente;
    $cabecalho['empresa'] = $nomeEmpresa;
    $cabecalho['tipodoc'] = $nomeTipoDoc;
    $cabecalho['data_inicio'] = $data_inicio;
    $cabecalho['data_fim'] = $data_fim;
    $cabecalho['selecionado'] = $selecionado;
    $cabecalho['status'] = $statusId;
    $cabecalho['moeda'] = $moedaId;
    $cabecalho['ordenado'] = $ordenado;

    $listaFaturas['lista'] = buscaFaturasPorPeriodo($clienteId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);
    $listaFaturas['subtotal'] = buscaFaturasPorPeriodoSubtotal($clienteId, $empresaId, $tipoDocId, $moedaId, $statusId, $selecionado, $data_inicio, $data_fim, $ordenado);

    $_SESSION['listaFaturas'] = $listaFaturas;
    $_SESSION['listaFaturas']['cabecalho'] = $cabecalho;

    echo saidaJson($listaFaturas);
} else if ($tipoForm == "busca_parcelas_modal_consulta_fatura") {

    $id = _INPUT('id', 'string', 'post');

    $parcelas = buscaParcelasPorFatura($id);

    for ($i = 0; $i < count($parcelas); $i++) {

        $parcelas[$i]['data_vencimento'] = converteData($parcelas[$i]['data_vencimento']);

        if ($parcelas[$i]['nome_status'] == "Em Aberto") {
            $parcelas[$i]['nome_status'] = "A";
        } else if ($parcelas[$i]['nome_status'] == "Baixado") {
            $parcelas[$i]['nome_status'] = "B";
        } else if ($parcelas[$i]['nome_status'] == "Baixa Parcial") {
            $parcelas[$i]['nome_status'] = "P";
        }
    }

    echo saidaJson($parcelas);
} else {
    include('view/financeiro/faturas.phtml');
}