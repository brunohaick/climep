<?php

if (isset($_POST['tipoForm']) && $_POST['tipoForm'] == "lista_procedimentos") {

    $convenio_id = _INPUT('convenio_id', 'int', 'post');
    $tabela_id = _INPUT('tabela_id', 'int', 'post');
    $medico_id = _INPUT('medico_id', 'int', 'post');
    $listaProcedimentos = listaProcedimentos($convenio_id, $tabela_id, $medico_id);

    require('view/recepcao/modal-inserir-procedimento.phtml');
} else if (isset($_POST['tipoForm']) && $_POST['tipoForm'] == "adiciona_procedimentos") {

    $idProc = _INPUT('id_proc', 'int', 'post');
    $qtdProc = _INPUT('qtd_proc', 'int', 'post');
    $convenio_id = _INPUT('convenio_id', 'int', 'post');
    $tabela_id = _INPUT('tabela_id', 'int', 'post');

    foreach ($idProc as $id) {
        $procedimentos[] = dadosProcedimento($id, $convenio_id, $tabela_id);
    }

    foreach ($procedimentos as $indice => $procedimento) {
        $procedimentos[$indice]['qtd'] = $qtdProc;
    }

    echo saidaJson($procedimentos);
} else if (isset($_POST['tipoForm']) && $_POST['tipoForm'] == "insere_procedimentos") {

    $usuario_id = $_SESSION['usuario']['id'];
    $data = date('Y-m-d');
    $hora = date('H:i:s');

    $arr_proc = _INPUT('proc', 'int', 'post'); // Array contendo os procedimentos
    $proc_id_cliente = _INPUT('id_cliente', 'int', 'post');
    $proc_id_medico = _INPUT('id_medico', 'int', 'post');
    $proc_id_convenio = _INPUT('id_convenio', 'int', 'post');
    $proc_id_tabela = _INPUT('id_tabela', 'int', 'post');
    $valor = _INPUT('valor', 'int', 'post');

    $dadosGrupoProc['cliente_cliente_id'] = $proc_id_cliente;
    $dadosGrupoProc['medico_medico_id'] = $proc_id_medico;
    $dadosGrupoProc['convenio_id'] = $proc_id_convenio;
    $dadosGrupoProc['tabela_id'] = $proc_id_tabela;
    $dadosGrupoProc['data'] = $data;
    $dadosGrupoProc['valor'] = $valor;
    $dadosGrupoProc['usuario_id'] = $usuario_id;

    $qtdProcs = count($arr_proc);


    if (insereGrupoProcedimento($dadosGrupoProc)) {
//            die("Here");
        $idGrupoProc = mysqli_insert_id(banco::$connection);
        $_SESSION['grupo_procedimento'] = $idGrupoProc;


        /**
         * Inserindo os dados da Guia Controle: Inserindo uma Guia de controle
         * contendo Procedimentos Tiss.
         */
        $tipoCliente = buscaTipoCliente($proc_id_cliente);

        if ($tipoCliente == "titular") {
            $idTitular = $proc_id_cliente;
        } else {
            $titular = buscaTitularPorDependente($proc_id_cliente);
            $idTitular = $titular['id'];
        }
        $novoNum = buscaNovoNumeroControle();
        $dadosGuiaControle['data'] = $data;
        $dadosGuiaControle['hora'] = $hora;
        $dadosGuiaControle['titular_id'] = $idTitular;
        $dadosGuiaControle['finalizado'] = 0;
        $dadosGuiaControle['numero_controle'] = $novoNum;
        $dadosGuiaControle['convenio_id'] = $proc_id_convenio;
        $dadosGuiaControle['usuario_id'] = $usuario_id;

        $bool = insereGuiaControle($dadosGuiaControle);

        /**
         * Inserindo Serviços e para serviços inserindo um controle.
         */
//                die("Here");
        if ($bool) {
            $idGuia = mysqli_insert_id(banco::$connection);

            $dadosServico['cliente_cliente_id'] = $proc_id_cliente;
            $dadosServico['status_id'] = 25;
            $dadosServico['usuario_id'] = $usuario_id;
            $dadosServico['qtd_doses'] = 1;
            $dadosServico['preco'] = "0";
            $dadosServico['data'] = $data;

            $dadosControle['cliente_id'] = $proc_id_cliente;
            $dadosControle['guia_controle_id'] = $idGuia;
            $dadosControle['numero_controle'] = $novoNum;
            $dadosControle['data'] = $data;
            $dadosControle['hora'] = $hora;
            $dadosControle['status'] = "";
            $dadosControle['modulo'] = 0;
            $dadosControle['forma_pagamento'] = 7;

            /**
             * Inserindo Historico Procedimentos, ou seja, 
             * Os procedimentos contidos em um grupo de procedimentos.
             * E para cada historico serão inseridos um serviço e um controle
             */
            for ($i = 0; $i < $qtdProcs; $i++) {
                $idProc = $arr_proc[$i][4];
                $nomeProc = $arr_proc[$i][1];
                $valorTotalProcs = isset($arr_proc[$i][3]) ? str_replace ( ",", ".", $arr_proc[$i][3]) : 0;
                $qtdProc = $arr_proc[$i][2];
                $valorTotalProcs2 = str_replace(',', '.', $valorTotalProcs);
                $valorUnidProc = bcdiv($valorTotalProcs2, $qtdProc, 2);

                $dadosHistoricoProcedimento['procedimento_id'] = $idProc;
                $dadosHistoricoProcedimento['qtd'] = $qtdProc;
                $dadosHistoricoProcedimento['valor'] = $valorTotalProcs;
                $dadosHistoricoProcedimento['grupo_procedimento_id'] = $idGrupoProc;
                insereHistoricoProcedimento($dadosHistoricoProcedimento);

                $materialId = buscaIdMaterialPorProcedimento($nomeProc, $valorUnidProc);
//                                die(var_dump($materialId));
                if ($materialId == NULL) {
                    $dadosMaterial['tipo_material_id'] = '3';
                    $dadosMaterial['codigo'] = '';
                    $dadosMaterial['quantidade_doses'] = '1';
                    $dadosMaterial['nome'] = $nomeProc;
                    $dadosMaterial['preco'] = $valorTotalProcs2;
                    $dadosMaterial['qtd_ml_por_dose'] = '1';
                    $dadosMaterial['preco_por_dose'] = $valorTotalProcs2;
                    $dadosMaterial['descricao'] = $nomeProc;
                    $dadosMaterial['descricao_ingles'] = $nomeProc;
                    $dadosMaterial['descricao_lembrete'] = $nomeProc;
                    $dadosMaterial['preco_cartao'] = $valorTotalProcs2;
                    insereMaterial($dadosMaterial);
                }
                $materialId = buscaIdMaterialPorProcedimento($nomeProc, $valorUnidProc);
//                                die(var_dump($materialId));
                $dadosServico['material_id'] = $materialId;
                if (insereServico($dadosServico)) {
                    $dadosControle['material_id'] = $materialId;
                    $dadosControle['servico_id'] = mysqli_insert_id(banco::$connection);
                    insereControle($dadosControle);
                }
            }
        }
        
        die(saidaJson(1));
    } else {
        die(saidaJson(0));
    }
}
