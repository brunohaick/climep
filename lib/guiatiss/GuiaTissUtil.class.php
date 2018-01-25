<?php
class GuiaTIssUtil {

	function SOAPEnv($text) {

		$xml = new XMLWriter;

		$xml->openMemory();

		$xml->startDocument('1.0');

		$xml->startElement("soapenv:Envelope");
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema'); 
		$xml->writeAttribute('xmlns:soapenv', 'http://schemas.xmlsoap.org/soap/envelope');
		$xml->writeAttribute('xmlns:dsf', 'http://dsfnet.com.br');

		$xml->startElement("soapenv:Body");

		$xml->startElement('dsf:enviar');
		$xml->writeAttribute('soapenv:encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');

		$xml->startElement('mensagemXml');
		$xml->writeAttribute('xsi:type', 'xsd:string');

		$xml->writeCData($text);

		$xml->endElement();

		$xml->endElement();

		$xml->endElement();

		$xml->endElement();

		return $xml->outputMemory(true);
	}

	function buildXML($cabecalho, $idLote, $rpss, $certificado) {

		$xml = new XMLWriter;

		$xml->openMemory();
		$xml->startElement("ansTISS:mensagemTISS");

//		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
//		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://www.ans.gov.br/padroes/tiss/schemas http://www.ans.gov.br/padroes/tiss/schemas/tissV2_02_03.xsd');

			$xml->startElement('ansTISS:cabecalho');
	
				$xml->startElement('ansTISS:identificacaoTransacao');
					$cabecalho['tipoTransacao'] = "ENVIO_LOTE_GUIAS";
					$xml->writeElement('ansTISS:tipoTransacao', $cabecalho['tipoTransacao']);
					$cabecalho['sequencialTransacao'] = "1021";
					$xml->writeElement('ansTISS:sequencialTransacao', $cabecalho['sequencialTransacao']);
					$cabecalho['dataRegistroTransacao'] = "2012-12-17";
					$xml->writeElement('ansTISS:dataRegistroTransacao', $cabecalho['dataRegistroTransacao']);
					$cabecalho['horaRegistroTransacao'] = "17:02:31";
					$xml->writeElement('ansTISS:horaRegistroTransacao', $cabecalho['horaRegistroTransacao']);
				$xml->endElement();
	
				$xml->startElement('ansTISS:origem');
					$xml->startElement('ansTISS:codigoPrestadorNaOperadora');
					$cabecalho['codigoPrestadorNaOperadora'] = "088210000930";
					$xml->writeElement('ansTISS:codigoPrestadorNaOperadora', $cabecalho['codigoPrestadorNaOperadora']);
					$xml->endElement();
				$xml->endElement();
	
				$xml->startElement('ansTISS:destino');
					$cabecalho['registroANS'] = "303679";
					$xml->writeElement('ansTISS:registroANS', $cabecalho['registroANS']);
				$xml->endElement();
				$cabecalho['versaoPadrao'] = "2.02.03";
				$xml->writeElement('ansTISS:versaoPadrao', $cabecalho['versaoPadrao']);
	
				$xml->startElement('ansTISS:identificacaoSoftwareGerador');
					$cabecalho['nomeAplicativo'] = "VACINNE7";
					$xml->writeElement('ansTISS:nomeAplicativo', $cabecalho['nomeAplicativo']);
					$cabecalho['versaoAplicativo'] = "2.00.01";
					$xml->writeElement('ansTISS:versaoAplicativo', $cabecalho['versaoAplicativo']);
					$cabecalho['fabricanteAplicativo'] = "TT CORDEIRO COMERCIAL";
					$xml->writeElement('ansTISS:fabricanteAplicativo', $cabecalho['fabricanteAplicativo']);
				$xml->endElement();
					
			$xml->endElement();
			$xml->startElement('ansTISS:prestadorParaOperadora');
				$xml->startElement('ansTISS:loteGuias');
					$idLote = "1021";
					$xml->writeElement('ansTISS:numeroLote', $idLote);
					$xml->startElement('ansTISS:guias');
						$xml->startElement('ansTISS:guiaFaturamento');
						$guiaSP_SADT = 1; 
						foreach($guiaSP_SADT as $guia) {
							$xml->startElement('ansTISS:guiaSP_SADT');
								$xml->startElement('ansTISS:identificacaoGuiaSADTSP');
									$xml->startElement('ansTISS:identificacaoFontePagadora');
										$guia['registroANS'] = "303679";
										$xml->writeElement('ansTISS:registroANS', $guia['registroANS']);
									$xml->endElement();
									$guia['dataEmissaoGuia'] = "2012-11-26";
									$xml->writeElement('ansTISS:dataEmissaoGuia', $guia['dataEmissaoGuia']);
									$guia['numeroGuiaPrestador'] = "23399546";
									$xml->writeElement('ansTISS:numeroGuiaPrestador', $guia['numeroGuiaPrestador']);
									$guia['numeroGuiaOperadora'] = "23399546";
									$xml->writeElement('ansTISS:numeroGuiaOperadora', $guia['numeroGuiaOperadora']);
								$xml->endElement();
								$guia['numeroGuiaPrincipal'] = "23399546";
								$xml->writeElement('ansTISS:numeroGuiaPrincipal', $guia['numeroGuiaPrincipal']);
								$xml->startElement('ansTISS:dadosAutorizacao');
									$guia['dataAutorizacao'] = "2012-11-26";
									$xml->writeElement('ansTISS:dataAutorizacao', $guia['dataAutorizacao']);
									$guia['senhaAutorizacao'] = "23399546";
									$xml->writeElement('ansTISS:senhaAutorizacao', $guia['senhaAutorizacao']);
									$guia['validadeSenha'] = "2012-12-25";
									$xml->writeElement('ansTISS:validadeSenha', $guia['validadeSenha']);
								$xml->endElement();
								$xml->startElement('ansTISS:dadosBeneficiario');
									$guia['numeroCarteira'] = "0880854640340003";
									$xml->writeElement('ansTISS:numeroCarteira', $guia['numeroCarteira']);
									$guia['nomeBeneficiario'] = "CARLA CAROLINY NUNES PINTO";
									$xml->writeElement('ansTISS:nomeBeneficiario', $guia['nomeBeneficiario']);
									$guia['nomePlano'] = "UNIMED";
									$xml->writeElement('ansTISS:nomePlano', $guia['nomePlano']);
									$guia['validadeCarteira'] = "2014-08-05";
									$xml->writeElement('ansTISS:validadeCarteira', $guia['validadeCarteira']);
									$guia['identificadorBeneficiario'] = "0880854640340003";
									$xml->writeElement('ansTISS:identificadorBeneficiario', $guia['identificadorBeneficiario']);
								$xml->endElement();
								$xml->startElement('ansTISS:dadosSolicitante');
									$xml->startElement('ansTISS:contratado');
										$xml->startElement('ansTISS:identificacao');
											$guia['codigoPrestadorNaOperadora'] = "088000063677";
											$xml->writeElement('ansTISS:codigoPrestadorNaOperadora', $guia['codigoPrestadorNaOperadora']);
										$xml->endElement();
										$guia['nomeContratado'] = "ANNA VALERIA VERAS FONSECA";
										$xml->writeElement('ansTISS:nomeContratado', $guia['nomeContratado']);
									$xml->endElement();
									$xml->startElement('ansTISS:profissional');
										$guia['nomeProfissional'] = "ANNA VALERIA VERAS FONSECA";
										$xml->writeElement('ansTISS:nomeProfissional', $guia['nomeProfissional']);
										$xml->startElement('ansTISS:conselhoProfissional');
											$guia['siglaConselho'] = "CRM";
											$xml->writeElement('ansTISS:siglaConselho', $guia['siglaConselho']);
											$guia['numeroConselho'] = "088000063677";
											$xml->writeElement('ansTISS:numeroConselho', $guia['numeroConselho']);
											$guia['ufConselho'] = "PA";
											$xml->writeElement('ansTISS:ufConselho', $guia['ufConselho']);
										$xml->endElement();
									$xml->endElement();
								$xml->endElement();
								$xml->startElement('ansTISS:prestadorExecutante');
									$xml->startElement('ansTISS:identificacao');
										$guia['codigoPrestadorNaOperadora'] = "088210000930";
										$xml->writeElement('ansTISS:codigoPrestadorNaOperadora', $guia['codigoPrestadorNaOperadora']);
									$xml->endElement();
									$guia['nomeContratado'] = "CLIMEP";
									$xml->writeElement('ansTISS:nomeContratado', $guia['nomeContratado']);
									$xml->startElement('ansTISS:enderecoContratado');
										$guia['tipoLogradouro'] = "008";
										$xml->writeElement('ansTISS:tipoLogradouro', $guia['tipoLogradouro']);
										$guia['logradouro'] = "Braz de Aguiar";
										$xml->writeElement('ansTISS:logradouro', $guia['logradouro']);
										$guia['numero'] = "410";
										$xml->writeElement('ansTISS:numero', $guia['numero']);
										$guia['complemento'] = "";
										$xml->writeElement('ansTISS:complemento', $guia['complemento']);
										$guia['codigoIBGEMunicipio'] = "0150140";
										$xml->writeElement('ansTISS:codigoIBGEMunicipio', $guia['codigoIBGEMunicipio']);
										$guia['municipio'] = "BELÉM";
										$xml->writeElement('ansTISS:municipio', $guia['municipio']);
										$guia['codigoUF'] = "PA";
										$xml->writeElement('ansTISS:codigoUF', $guia['codigoUF']);
										$guia['cep'] = "66035000";
										$xml->writeElement('ansTISS:cep', $guia['cep']);
									$xml->endElement();

									$xml->startElement('ansTISS:profissionalExecutanteCompl');
										$guia['nomeExecutante'] = "CLIMEP";
										$xml->writeElement('ansTISS:nomeExecutante', $guia['nomeExecutante']);
										$xml->startElement('ansTISS:conselhoProfissional');
											$guia['siglaConselho'] = "CRM";
											$xml->writeElement('ansTISS:siglaConselho', $guia['siglaConselho']);
											$guia['numeroConselho'] = "088210000930";
											$xml->writeElement('ansTISS:numeroConselho', $guia['numeroConselho']);
											$guia['ufConselho'] = "PA";
											$xml->writeElement('ansTISS:ufConselho', $guia['ufConselho']);
										$xml->endElement();
										$guia['codigoCBOS'] = "";
										$xml->writeElement('ansTISS:codigoCBOS', $guia['codigoCBOS']);
									$xml->endElement();
								$xml->endElement();
								$guia['caraterAtendimento'] = "E";
								$xml->writeElement('ansTISS:caraterAtendimento', $guia['caraterAtendimento']);
								$guia['dataHoraAtendimento'] = "2012-11-26T00:00:00";
								$xml->writeElement('ansTISS:dataHoraAtendimento', $guia['dataHoraAtendimento']);
								$xml->startElement('ansTISS:diagnosticoAtendimento');
									$xml->startElement('ansTISS:CID');
										$guia['nomeTabela'] = "CID-10";
										$xml->writeElement('ansTISS:nomeTabela', $guia['nomeTabela']);
										$guia['codigoDiagnostico'] = "Z138";
										$xml->writeElement('ansTISS:codigoDiagnostico', $guia['codigoDiagnostico']);
										$guia['descricaoDiagnostico'] = "";
										$xml->writeElement('ansTISS:descricaoDiagnostico', $guia['descricaoDiagnostico']);
									$xml->endElement();
								$xml->endElement();
								$guia['tipoSaida'] = "2";
								$xml->writeElement('ansTISS:tipoSaida', $guia['tipoSaida']);
								$guia['tipoAtendimento'] = "05";
								$xml->writeElement('ansTISS:tipoAtendimento', $guia['tipoAtendimento']);
								$xml->startElement('ansTISS:procedimentosRealizados');
									$guia['procedimentos'] = 1;
									foreach($guia['procedimentos'] as $procedimento){
										$xml->startElement('ansTISS:procedimento');
											$procedimento['codigo'] = '40316017';
											$xml->writeElement('ansTISS:codigo', $guia['codigo']);
											$procedimento['tipoTabela'] = '16';
											$xml->writeElement('ansTISS:tipoTabela', $guia['tipoTabela']);
											$procedimento['descricao'] = '17- ALFA-HIDROXIPROGESTERONA';
											$xml->writeElement('ansTISS:descricao', $guia['descricao']);
										$xml->endElement();
										$procedimento['data'] = '2012-11-29';
										$xml->writeElement('ansTISS:data', $guia['data']);
										$procedimento['horaInicio'] = '08:23:43';
										$xml->writeElement('ansTISS:horaInicio', $guia['horaInicio']);
										$procedimento['horaFim'] = '08:23:43';
										$xml->writeElement('ansTISS:horaFim', $guia['horaFim']);
										$procedimento['quantidadeRealizada'] = '1';
										$xml->writeElement('ansTISS:quantidadeRealizada', $guia['quantidadeRealizada']);
										$procedimento['viaAcesso'] = 'U';
										$xml->writeElement('ansTISS:viaAcesso', $guia['viaAcesso']);
										$procedimento['tecnicaUtilizada'] = 'C';
										$xml->writeElement('ansTISS:tecnicaUtilizada', $guia['tecnicaUtilizada']);
										$procedimento['reducaoAcrescimo'] = '0.00';
										$xml->writeElement('ansTISS:reducaoAcrescimo', $guia['reducaoAcrescimo']);
										$procedimento['valor'] = '33.00';
										$xml->writeElement('ansTISS:valor', $guia['valor']);
										$procedimento['valorTotal'] = '33.00';
										$xml->writeElement('ansTISS:valorTotal', $guia['valorTotal']);
									}
								$xml->endElement();
								$xml->startElement('ansTISS:valorTotal');
									$guia['servicosExecutados'] = "256.17";
									$xml->writeElement('ansTISS:servicosExecutados', $guia['servicosExecutados']);
									$guia['diarias'] = "0.00";
									$xml->writeElement('ansTISS:diarias', $guia['diarias']);
									$guia['taxas'] = "0.00";
									$xml->writeElement('ansTISS:taxas', $guia['taxas']);
									$guia['materiais'] = "0.00";
									$xml->writeElement('ansTISS:materiais', $guia['materiais']);
									$guia['medicamentos'] = "0.00";
									$xml->writeElement('ansTISS:medicamentos', $guia['medicamentos']);
									$guia['gases'] = "0.00";
									$xml->writeElement('ansTISS:gases', $guia['gases']);
									$guia['totalGeral'] = "256.17";
									$xml->writeElement('ansTISS:totalGeral', $guia['totalGeral']);
								$xml->endElement();
								$guia['observacao'] = "";
								$xml->writeElement('ansTISS:observacao', $guia['observacao']);
							$xml->endElement();
						}

						$xml->endElement();

					$xml->endElement();

				$xml->endElement();

			$xml->endElement();

			$xml->startElement('ansTISS:epilogo');
				$hash = "211221669623C7E3047EDE269AEC7548";
				$xml->writeElement('ansTISS:hash', $hash);
			$xml->endElement();

		$xml->endElement();

		 header( 'Content-type: text/xml' );
		 print $xml->outputMemory(true);
		 exit;

		return $xml->outputMemory(true);
	}

}
?>
