<?php

if ($_POST['flag'] == "gerarxml") {

    $dataInicio = converteData($_POST['dataInicio']);
    $dataFim = converteData($_POST['dataFim']);
    $data_inicio = $_POST['dataInicio'];
    $nlote = $_POST['nlote'];
    $ans = $_POST['ans'];
    $versao = $_POST['versao'];
    $codop = $_POST['codop'];
    $convenio = $_POST['convenio'];
    $nomeConvenio = $_POST['nomeConvenio'];
    $tipo = $_POST['tipo'];
    $total = 0;
    
    $data = date('Y-m-d');
    $hora = date('H:i:s');
    
    if(stripos($nomeConvenio, 'unimed') !== false){
        $codigoPrestadorNaOperadora = "088210000930";
    } else {
        $codigoPrestadorNaOperadora = "05083142000183";
    }
    

    $dados = buscaGuiasTISSparaXMLporData($dataInicio, $dataFim, $convenio);
    die(print_r($dados));
    reset($dados);
    $atual = current($dados);
    $nregistro_ans = $atual[0]['registro_ans'];
    
    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->startDocument('1.0', 'iso-8859-1');

    $xml->startElement("ansTISS:mensagemTISS");
    $xml->writeAttribute('xmlns:ansTISS', 'www.ans.gov.br/padroes/tiss/schemas');
    $xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $xml->writeAttribute('xsi:schemaLocation', 'http://www.ans.gov.br/padroes/tiss/schemas http://www.ans.gov.br/padroes/tiss/schemas/tissV2_02_03.xsd');
    
        $xml->startElement("ansTISS:cabecalho");            
            $xml->startElement("ansTISS:identificacaoTransacao");    
                $xml->writeElement("ansTISS:tipoTransacao", "ENVIO_LOTE_GUIAS");
                $xml->writeElement("ansTISS:sequenciaTransacao",$nlote);
                $xml->writeElement("ansTISS:dataRegistroTransacao", $data);                
                $xml->writeElement("ansTISS:horaRegistroTransacao", $hora);                
            $xml->endElement();//identificacaoTransacao
            
            $xml->startElement("ansTISS:origem");    
                $xml->startElement("ansTISS:codigoPrestadorNaOperadora");                    
                    $xml->writeElement("ansTISS:codigoPrestadorNaOperadora", $codigoPrestadorNaOperadora);
                $xml->endElement();//codigoPrestadorNaOperadora                
            $xml->endElement();//origem     
            
            $xml->startElement("ansTISS:destino");    
                $xml->writeElement("ansTISS:ansTISS:registroANS", $nregistro_ans);                                                    
            $xml->endElement();//destino  
            
            $xml->writeElement("ansTISS:versaoPadrao", $versao);
            
            $xml->startElement("ansTISS:identificacaoSoftwareGerador");
                $xml->writeElement("ansTISS:nomeAplicativo", "Blablabla");                
                $xml->writeElement("ansTISS:versaoAplicativo", "1.0");                
                $xml->writeElement("ansTISS:fabricanteAplicativo", "Bruno Haick");                
            $xml->endElement();//identificacaoSoftwareGerador    
        $xml->endElement();//cabecalho
        
        $xml->startElement("ansTISS:prestadorParaOperadora"); 
            $xml->startElement("ansTISS:loteGuias"); 
                $xml->writeElement("ansTISS:numeroLote", $nlote);
                $xml->startElement("ansTISS:guias");                 
                    $xml->startElement("ansTISS:guiaFaturamento");
                        foreach($dados as $dado){
                            $total = $dado['total'];
                            $d = current($dado);
                            $d = $d[0];
                            $xml->startElement("ansTISS:guiaSP_SADT");                        
                                $xml->startElement("ansTISS:identificacaoGuiaSADTSP");                        
                                    $xml->startElement("ansTISS:identificacaoFontePagadora");
                                       $xml->writeElement("ansTISS:ansTISS:registroANS", $d['registro_ans']); 
                                    $xml->endElement();//identificacaoFontePagadora
                                    $xml->writeElement("ansTISS:dataEmissaoGuia", $d['data_autorizacao']);
                                    $xml->writeElement("ansTISS:numeroGuiaPrestador", $d['num_guia_principal']);
                                    $xml->writeElement("ansTISS:numeroGuiaOperadora", $d['num_guia_principal']);
                                $xml->endElement();//identificacaoGuiaSADTSP
                                $xml->writeElement("ansTISS:numeroGuiaPrincipal", $d['num_guia_principal']);
                                $xml->startElement("ansTISS:dadosautorizacao");                                    
                                    $xml->writeElement("ansTISS:dataAutorizacao", $d['data_autorizacao']);
                                    $xml->writeElement("ansTISS:senhaAutorizacao", $d['senha']);
                                    $xml->writeElement("ansTISS:validadeSenha", $d['senha_data_validade']);
                                $xml->endElement();//dadosautorizacao
                                $xml->startElement("ansTISS:dadosBeneficiario");                                   
                                    $xml->writeElement("ansTISS:numeroCarteira", $d['num_carteira']);
                                    $xml->writeElement("ansTISS:nomeBeneficiario", $d['cliente']);
                                    $xml->writeElement("ansTISS:nomePlano", $d['plano']);
                                    $xml->writeElement("ansTISS:validadeCarteira", $d['carteira_validade']);
                                $xml->endElement();//dadosBeneficiario


                                $xml->startElement("ansTISS:dadosSolicitante");
                                    $xml->startElement("ansTISS:contratado");
                                        $xml->startElement("ansTISS:identificacao");
                                            $xml->writeElement("ansTISS:codigoPrestadorNaOperadora", $codigoPrestadorNaOperadora);
                                        $xml->endElement();//identificacao
                                        $xml->writeElement("ansTISS:nomeContratado", $d['medico']);
                                    $xml->endElement();//contratado
                                    $xml->startElement("ansTISS:profissional");
                                        $xml->writeElement("ansTISS:profissional", $d['medico']);
                                        $xml->startElement("ansTISS:conselhoProfissional");
                                            $xml->writeElement("ansTISS:siglaConselho", $d['conselho_profissional']);
                                            $xml->writeElement("ansTISS:numeroConselho", $d['conselho_profissional']);
                                            $xml->writeElement("ansTISS:ufConselho", "PA");
                                        $xml->endElement();//conselhoProfissional
                                    $xml->endElement();//profissional
                                $xml->endElement();//dadosSolicitante

                                $xml->startElement("ansTISS:prestadorExecutante");                                
                                    $xml->startElement("ansTISS:identificacao");
                                        $xml->writeElement("ansTISS:codigoPrestadorNaOperadora", $codigoPrestadorNaOperadora);
                                    $xml->endElement();//identificacao                                    
                                    $xml->writeElement("ansTISS:nomeContratado", "CLIMEP");
                                    $xml->startElement("ansTISS:enderecoContratado");
                                        $xml->writeElement("tipoLogradouro" ,"008");
                                        $xml->writeElement("logradouro", "Braz de Aguiar");
                                        $xml->writeElement("numero", "410");
                                        $xml->writeElement("complemento", "");
                                        $xml->writeElement("codigoIBGEMunicipio" , "0150140");
                                        $xml->writeElement("municipio","BELÉM");
                                        $xml->writeElement("codigoUF", "PA");
                                        $xml->writeElement("cep", "66035000");
                                    $xml->endElement();//enderecoContratado 
                                    $xml->startElement("ansTISS:profissionalExecuta nteCompl");
                                        $xml->writeElement("ansTISS:nomeExecutante", "CLIMEP");
                                        $xml->startElement("ansTISS:conselhoProfissional");                                        
                                            $xml->writeElement("siglaConselho", "CRM");
                                            $xml->writeElement("numeroConselho", $codigoPrestadorNaOperadora);                                           
                                            $xml->writeElement("ufConselho", "PA");
                                        $xml->endElement();//conselhoProfissional             
                                    $xml->endElement();//profissionalExecutanteCompl                                      
                                $xml->endElement();//prestadorExecutante              

                                $xml->writeElement("caraterAtendimento", $d['solicitacao']);
                                $xml->writeElement("dataHoraAtendimento", $d['data_hora_solicitacao']);

                                $xml->startElement("ansTISS:diagnosticoAtendimento");
                                    $xml->startElement("ansTISS:CID");
                                        $xml->writeElement("nomeTabela", "CID-10");
                                        $xml->writeElement("codigoDiagnostico", $d['cid']);
                                        $xml->writeElement("descricaoDiagnostico", "");
                                    $xml->endElement();//CID              
                                $xml->endElement();//diagnosticoAtendimento
                                $xml->writeElement("tipoSaida", $d['tipo_saida']);
                                $xml->writeElement("tipoAtendimento", $d['atendimento']);

                                $xml->startElement("ansTISS:procedimentosRealizados");
                                foreach($dado as $d2){
                                    $xml->startElement("ansTISS:procedimentos");
                                        $xml->startElement("ansTISS:procedimento");
                                            $xml->writeElement("codigo", str_replace('.', '', $d2['codigo_procedimento']));
                                            if(strtolower($nomeConvenio) == "cassi"){
                                                $xml->writeElement("tipoTabela", "16");
                                            } else {
                                                $xml->writeElement("tipoTabela", "02");
                                            }                                             
                                            $xml->writeElement("descricao", $d2['nome_procedimento']);
                                        $xml->endElement();//procedimento
                                        $xml->writeElement("data", $data);
                                        $xml->writeElement("horaInicio", $hora);
                                        $xml->writeElement("horaFim", $hora);
                                        $xml->writeElement("quantidadeRealizada", "1");
                                        $xml->writeElement("viaAcesso", $d2['solicitacao']);
                                        $xml->writeElement("tecnicaUtilizada", $d2['tipo_doenca']);
                                        $xml->writeElement("reducaoAcrescimo", "0.00");
                                        $xml->writeElement("valor", $d2['valor_procedimento']);
                                        $xml->writeElement("valorTotal", $d2['valor_procedimento']);
                                    $xml->endElement();//procedimentos
                                }
                                $xml->endElement();//procedimentosRealizados

                                $xml->startElement("ansTISS:valorTotal");
                                    $xml->writeElement("servicosExecutados", number_format($total, 2, ',', '.'));
                                    $xml->writeElement("diarias", "0.00");
                                    $xml->writeElement("taxas", "0.00");
                                    $xml->writeElement("materiais", "0.00");
                                    $xml->writeElement("medicamentos", "0.00");
                                    $xml->writeElement("gases", "0.00");
                                    $xml->writeElement("totalGeral", number_format($total, 2, ',', '.'));
                                $xml->endElement();//valorTotal

                                $xml->writeElement("observacao", "");

                            $xml->endElement();//guiaSP_SADT                            
                        }
                    $xml->endElement();//guiaFaturamento
                $xml->endElement();//guias
            $xml->endElement();//loteGuias
        $xml->endElement();//pretadorParaOperadora
        
        $xml->startElement("ansTISS:epilogo");
            $xml->writeElement("ansTISS:hash", "20fe56471b48bb1599dd10a64496ccd0");
        $xml->endElement();//epilogo
    
    $xml->endElement();//mensagemTISS    

    die(print_r($xml->outputMemory(true)));
} 