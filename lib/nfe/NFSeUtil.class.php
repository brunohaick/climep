<?php
class NFSeUtil {

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

		$xml->startElement("ns1:ReqEnvioLoteRPS");

		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ReqEnvioLoteRPS.xsd');

		$xml->startElement('Cabecalho');
		$xml->writeElement('CodCidade', $cabecalho['CodCidade']);
		$xml->writeElement('CPFCNPJRemetente', $cabecalho['CPFCNPJRemetente']);
		$xml->writeElement('RazaoSocialRemetente', $cabecalho['RazaoSocialRemetente']);
		$xml->writeElement('transacao');
		$xml->writeElement('dtInicio', $cabecalho['dtInicio']);
		$xml->writeElement('dtFim', $cabecalho['dtFim']);
		$xml->writeElement('QtdRPS', $cabecalho['QtdRPS']);
		$xml->writeElement('ValorTotalServicos', $cabecalho['ValorTotalServicos']);
		$xml->writeElement('ValorTotalDeducoes', $cabecalho['ValorTotalDeducoes']);
		$xml->writeElement('Versao', $cabecalho['Versao']);
		$xml->writeElement('MetodoEnvio', $cabecalho['MetodoEnvio']);
		$xml->writeElement('VersaoComponente', VERSAO_COMPONENTE);
		$xml->endElement();

		$xml->startElement('Lote');
		$xml->writeAttribute('Id', 'lote:'.$idLote);

		foreach($rpss as $rps) {
			$xml->startElement('RPS');
			$xml->writeAttribute('Id','rps:'.$rps['id']);
			$xml->writeElement('Assinatura', $rps['Assinatura']);
			$xml->writeElement('InscricaoMunicipalPrestador', $rps['InscricaoMunicipalPrestador']);
			$xml->writeElement('RazaoSocialPrestador', $rps['RazaoSocialPrestador']);
			$xml->writeElement('TipoRPS', $rps['TipoRPS']);
			$xml->writeElement('SerieRPS', $rps['SerieRPS']);
			$xml->writeElement('NumeroRPS', $rps['NumeroRPS']);
			$xml->writeElement('DataEmissaoRPS', $rps['DataEmissaoRPS']);
			$xml->writeElement('SituacaoRPS', $rps['SituacaoRPS']);
			$xml->writeElement('SerieRPSSubstituido', $rps['SerieRPSSubstituido']);
			$xml->writeElement('NumeroRPSSubstituido', $rps['NumeroRPSSubstituido']);
			$xml->writeElement('NumeroNFSeSubstituida', $rps['NumeroNFSeSubstituida']);
			$xml->writeElement('DataEmissaoNFSeSubstituida', $rps['DataEmissaoNFSeSubstituida']);
			$xml->writeElement('SeriePrestacao', $rps['SeriePrestacao']);
			$xml->writeElement('InscricaoMunicipalTomador', $rps['InscricaoMunicipalTomador']);
			$xml->writeElement('CPFCNPJTomador', $rps['CPFCNPJTomador']);
			$xml->writeElement('RazaoSocialTomador', $rps['RazaoSocialTomador']);
			$xml->writeElement('TipoLogradouroTomador', $rps['TipoLogradouroTomador']);
			$xml->writeElement('LogradouroTomador', $rps['LogradouroTomador']);
			$xml->writeElement('NumeroEnderecoTomador', $rps['NumeroEnderecoTomador']);
			$xml->writeElement('TipoBairroTomador', $rps['TipoBairroTomador']);
			$xml->writeElement('BairroTomador', $rps['BairroTomador']);
			$xml->writeElement('CidadeTomador', $rps['CidadeTomador']);
			$xml->writeElement('CidadeTomadorDescricao', $rps['CidadeTomadorDescricao']);
			$xml->writeElement('CEPTomador', $rps['CEPTomador']);
			$xml->writeElement('EmailTomador', $rps['EmailTomador']);
			$xml->writeElement('CodigoAtividade', $rps['CodigoAtividade']);
			$xml->writeElement('AliquotaAtividade', $rps['AliquotaAtividade']);
			$xml->writeElement('TipoRecolhimento', $rps['TipoRecolhimento']);
			$xml->writeElement('MunicipioPrestacao', $rps['MunicipioPrestacao']);
			$xml->writeElement('MunicipioPrestacaoDescricao', $rps['MunicipioPrestacaoDescricao']);
			$xml->writeElement('Operacao', $rps['Operacao']);
			$xml->writeElement('Tributacao', $rps['Tributacao']);
			$xml->writeElement('ValorPIS', $rps['ValorPIS']);
			$xml->writeElement('ValorCOFINS', $rps['ValorCOFINS']);
			$xml->writeElement('ValorINSS', $rps['ValorINSS']);
			$xml->writeElement('ValorIR', $rps['ValorIR']);
			$xml->writeElement('ValorCSLL', $rps['ValorCSLL']);
			$xml->writeElement('AliquotaPIS', $rps['AliquotaPIS']);
			$xml->writeElement('AliquotaCOFINS', $rps['AliquotaCOFINS']);
			$xml->writeElement('AliquotaINSS', $rps['AliquotaINSS']);
			$xml->writeElement('AliquotaIR', $rps['AliquotaIR']);
			$xml->writeElement('AliquotaCSLL', $rps['AliquotaCSLL']);
			$xml->writeElement('DescricaoRPS', $rps['DescricaoRPS']);
			$xml->writeElement('DDDPrestador', $rps['DDDPrestador']);
			$xml->writeElement('TelefonePrestador', $rps['TelefonePrestador']);
			$xml->writeElement('DDDTomador', $rps['DDDTomador']);
			$xml->writeElement('TelefoneTomador', $rps['TelefoneTomador']);
			$xml->writeElement('MotCancelamento', $rps['MotCancelamento']);
			$xml->writeElement('Deducoes', $rps['Deducoes']);

			$xml->startElement('Itens');
			foreach($rps['Itens'] as $item){
				$xml->startElement('Item');
				$xml->writeElement('DiscriminacaoServico', $item['DiscriminacaoServico']);
				$xml->writeElement('Quantidade', $item['Quantidade']);
				$xml->writeElement('ValorUnitario', $item['ValorUnitario']);
				$xml->writeElement('ValorTotal', $item['ValorTotal']);
				$xml->writeElement('Tributavel', $item['Tributavel']); 
				$xml->endElement();
			}
			$xml->endElement();
			$xml->endElement();
		}

		$xml->endElement();

		$xml->startElement('Signature');
		$xml->writeAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');

		$xml->startElement('SignedInfo');
		$xml->startElement('CanonicalizationMethod');
		$xml->writeAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$xml->endElement();
		$xml->startElement('SignatureMethod');
		$xml->writeAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
		$xml->endElement();
		$xml->startElement('Reference');
		$xml->writeAttribute('URI', '#lote:1ABCDZ');
		$xml->startElement('Transforms');
		$xml->startElement('Transform');
		$xml->writeAttribute('Algorithm','http://www.w3.org/2000/09/xmldsig#enveloped-signature');
		$xml->endElement();
		$xml->startElement('Transform');
		$xml->writeAttribute('Algorithm','http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$xml->endElement();
		$xml->endElement();
		$xml->startElement('DigestMethod');
		$xml->writeAttribute('Algorithm','http://www.w3.org/2000/09/xmldsig#sha1');
		$xml->endElement();
		$xml->writeElement('DigestValue', $certificado['DigestValue']);
		$xml->endElement();
		$xml->endElement();
		// signature value 
		$xml->writeElement('SignatureValue',$certificado['SignatureValue']);
		$xml->startElement('KeyInfo');
		$xml->startElement('X509Data');
		// certificate
		$xml->writeElement('X509Certificate',$certificado['X509Certificate']);
		$xml->endElement();
		$xml->endElement();
		$xml->endElement();

		$xml->endElement();


		$xml->endElement();
		$xml->endElement();

	//	if(!empty($certificado['DigestValue'])){
	//	 header( 'Content-type: text/xml' );
	//	 print $xml->outputMemory(true);
	//	 exit;
	//	}

	//	$fh = fopen('Enviar.xml', 'w');
	//	fwrite($fh,$xml->outputMemory(true));
	//	fclose($fh);
//	//	exit;
		return $xml->outputMemory(true);


	}

	/*
	 dataEmissaoRPS = yyyyMMdd
	 Tipo Recolhimento, se for “A” preenche com “N” senão “S” 
	 Valor do serviço subtraindo a dedução 

	*/
	function generateNFSeSignature($inscricaoMunicipal, $serieRPS, $numeroRPS, $dataEmissaoRPS, $tributacao, $situacaoRPS, $tipoRecolhimento, $valorServico, $valorDeducao, $codAtividade, $cpfOrcnpjTomador) {

		$signature = str_pad($inscricaoMunicipal, 11, '0', STR_PAD_LEFT) . 
			str_pad($serieRPS, 5) . 
			str_pad($numeroRPS, 12, '0', STR_PAD_LEFT) . 
			$dataEmissaoRPS . 
			str_pad($tributacao, 2) . 
			$situacaoRPS . 
			$tipoRecolhimento . 
			str_pad($valorServico, 15, '0', STR_PAD_LEFT) .
			str_pad($valorDeducao, 15, '0', STR_PAD_LEFT) .
			str_pad($codAtividade, 10, '0', STR_PAD_LEFT) .
			str_pad($cpfOrcnpjTomador, 14, '0', STR_PAD_LEFT);

		return sha1($signature);
	}

//$inscricaoMunicipal = '00000317330';
//$serieRPS = 'NF';
//$numeroRPS = '000000038663';
//$dataEmissaoRPS = '20090905';
//$tributacao = 'T';
//$situacaoRPS = 'N';
//$tipoRecolhimento = 'N';
//$valorServico = '000000000001686';
//$valorDeducao = '000000000000000';
//$codAtividade = '0829979900';
//$cpfOrcnpjTomador = '08764130000102';
//$teste = NFSeUtil::generateNFSeSignature($inscricaoMunicipal, $serieRPS, $numeroRPS, $dataEmissaoRPS, $tributacao, $situacaoRPS, $tipoRecolhimento, $valorServico, $valorDeducao, $codAtividade, $cpfOrcnpjTomador);
//echo "<br>";
//echo $teste;

	function buildXML_Cancelamento($cabecalho, $idLote, $nota, $certificado) {

		$xml = new XMLWriter;

		$xml->openMemory();

		$xml->startElement("ns1:ReqCancelamentoNFSe");

		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ReqCancelamentoNFSe.xsd');

		$xml->startElement('Cabecalho');
		$xml->writeElement('CodCidade', $cabecalho['CodCidade']);
		$xml->writeElement('CPFCNPJRemetente', $cabecalho['CPFCNPJRemetente']);
		$xml->writeElement('transacao', $cabecalho['transacao']);
		$xml->writeElement('Versao', $cabecalho['Versao']);
		$xml->endElement();

		$xml->startElement('Lote');
		$xml->writeAttribute('Id', 'lote:'.$idLote);

		$xml->startElement('Nota');
		$idNota = 5;
		$xml->writeAttribute('Id', 'nota:'.$nota['id']);

		$xml->writeElement('InscricaoMunicipalPrestador', $nota['InscricaoMunicipalPrestador']);
		$xml->writeElement('NumeroNota', $nota['NumeroNota']);
		$xml->writeElement('CodigoVerificacao', $nota['CodigoVerificacao']);
		$xml->writeElement('MotivoCancelamento', $nota['MotivoCancelamento']);
		$xml->endElement();
		$xml->endElement();
		$xml->startElement('Signature');
		$xml->writeAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');

		$xml->startElement('SignedInfo');
		$xml->startElement('CanonicalizationMethod');
		$xml->writeAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$xml->endElement();
		$xml->startElement('SignatureMethod');
		$xml->writeAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
		$xml->endElement();
		$xml->startElement('Reference');
		$xml->writeAttribute('URI', '#lote:1ABCDZ');
		$xml->startElement('Transforms');
		$xml->startElement('Transform');
		$xml->writeAttribute('Algorithm','http://www.w3.org/2000/09/xmldsig#enveloped-signature');
		$xml->endElement();
		$xml->startElement('Transform');
		$xml->writeAttribute('Algorithm','http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$xml->endElement();
		$xml->endElement();
		$xml->startElement('DigestMethod');
		$xml->writeAttribute('Algorithm','http://www.w3.org/2000/09/xmldsig#sha1');
		$xml->endElement();
		$xml->writeElement('DigestValue', $certificado['DigestValue']);
		$xml->endElement();
		$xml->endElement();
		// signature value 
		$xml->writeElement('SignatureValue',$certificado['SignatureValue']);
		$xml->startElement('KeyInfo');
		$xml->startElement('X509Data');
		// certificate
		$xml->writeElement('X509Certificate',$certificado['X509Certificate']);
		$xml->endElement();
		$xml->endElement();
		$xml->endElement();

		$xml->endElement();

		$xml->endElement();

	//	return $xml->outputMemory(true);

		 header( 'Content-type: text/xml' );
		 print $xml->outputMemory(true);
	}


	function buildXML_ConsultaNFSeRPS($cabecalho, $idLote, $notas, $rpss) {

		$xml = new XMLWriter;

		$xml->openMemory();

		$xml->startElement("ns1:ReqConsultaNFSeRPS");

		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ReqConsultaNFSeRPS.xsd');

		$xml->startElement('Cabecalho');
		$xml->writeElement('CodCidade', $cabecalho['CodCidade']);
		$xml->writeElement('CPFCNPJRemetente', $cabecalho['CPFCNPJRemetente']);
		$xml->writeElement('transacao', $cabecalho['transacao']);
		$xml->writeElement('Versao', $cabecalho['Versao']);
		$xml->endElement();

		$xml->startElement('Lote');
		$xml->writeAttribute('Id', 'lote:'.$idLote);

		$notas[0]['idNota'] = 1;
		$notas[0]['InscricaoMunicipalPrestador'] = '0915712';
		$notas[0]['NumeroNota'] = '1';
		$notas[0]['CodigoVerificacao'] = '575fec57';
		$notas[1]['idNota'] = '2';
		$notas[1]['InscricaoMunicipalPrestador'] = '0915712';
		$notas[1]['NumeroNota'] = '2';
		$notas[1]['CodigoVerificacao'] = 'de6e9074';

		$xml->startElement('NotaConsulta');
		foreach($notas as $nota) {

		$xml->startElement('Nota');
		$xml->writeAttribute('Id', 'nota:'.$nota['idNota']);
		$xml->writeElement('InscricaoMunicipalPrestador', $nota['InscricaoMunicipalPrestador']);
		$xml->writeElement('NumeroNota', $nota['NumeroNota']);
		$xml->writeElement('CodigoVerificacao', $nota['CodigoVerificacao']);
		$xml->endElement();
		}
		$xml->endElement();

		$xml->startElement('RPSConsulta');
		foreach($rpss as $rps) {
			$xml->startElement('RPS');
			$xml->writeAttribute('Id', 'rps:'.$rps['id']);
			$xml->writeElement('InscricaoMunicipalPrestador', $rps['InscricaoMunicipalPrestador']);
			$xml->writeElement('NumeroRPS', $rps['NumeroRPS']);
			$xml->writeElement('SeriePrestacao', $rps['SeriePrestacao']);
			$xml->endElement();
		}
		$xml->endElement();

		$xml->endElement();
		$xml->endElement();

	//	return $xml->outputMemory(true);

		 header( 'Content-type: text/xml' );
		 print $xml->outputMemory(true);
	}

	function buildXML_ConsultaSeqRps($cabecalho) {

		$xml = new XMLWriter;

		$xml->openMemory();

		$xml->startElement("ns1:ConsultaSeqRps");

		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ConsultaSeqRps.xsd');

		$xml->startElement('Cabecalho');
		$xml->writeElement('CodCid', $cabecalho['CodCid']);
		$xml->writeElement('IMPrestador', $cabecalho['IMPrestador']);
		$xml->writeElement('CPFCNPJRemetente', $cabecalho['CPFCNPJRemetente']);
		$xml->writeElement('SeriePrestacao', $cabecalho['SeriePrestacao']);
		$xml->writeElement('Versao', $cabecalho['Versao']);
		$xml->endElement();

		$xml->endElement();

	//	return $xml->outputMemory(true);

		 header( 'Content-type: text/xml' );
		 print $xml->outputMemory(true);
	}

	function buildXML_ConsultaLote($cabecalho) {

		$xml = new XMLWriter;

		$xml->openMemory();

		$xml->startElement("ns1:ReqConsultaLote");

		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ReqConsultaLote.xsd');

		$xml->startElement('Cabecalho');
		$xml->writeElement('CodCidade', $cabecalho['CodCidade']);
		$xml->writeElement('CPFCNPJRemetente', $cabecalho['CPFCNPJRemetente']);
		$xml->writeElement('Versao', $cabecalho['Versao']);
		$xml->writeElement('NumeroLote', $cabecalho['NumeroLote']);
		$xml->endElement();

		$xml->endElement();

	//	return $xml->outputMemory(true);

		 header( 'Content-type: text/xml' );
		 print $xml->outputMemory(true);
	}
	
	function buildXML_ConsultaNotas($cabecalho,$certificado) {

		$xml = new XMLWriter;

		$xml->openMemory();

		$xml->startElement("ns1:ReqConsultaNotas");

		$xml->writeAttribute('xmlns:ns1', 'http://localhost:8080/WsNFe2/lote');
		$xml->writeAttribute('xmlns:tipos', 'http://localhost:8080/WsNFe2/tp');
		$xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->writeAttribute('xsi:schemaLocation', 'http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ReqConsultaNotas.xsd');

		$xml->startElement('Cabecalho');
		$xml->writeAttribute('Id','Consulta:notas');
		$xml->writeElement('CodCidade', $cabecalho['CodCidade']);
		$xml->writeElement('CPFCNPJRemetente', $cabecalho['CPFCNPJRemetente']);
		$xml->writeElement('InscricaoMunicipalPrestador', $cabecalho['InscricaoMunicipalPrestador']);
		$xml->writeElement('dtInicio', $cabecalho['dtInicio']);
		$xml->writeElement('dtFim', $cabecalho['dtFim']);
		$xml->writeElement('NotaInicial', $cabecalho['NotaInicial']);
		$xml->writeElement('Versao', $cabecalho['Versao']);

		$xml->endElement();


		$xml->startElement('Signature');
		$xml->writeAttribute('xmlns', 'http://www.w3.org/2000/09/xmldsig#');

		$xml->startElement('SignedInfo');
		$xml->startElement('CanonicalizationMethod');
		$xml->writeAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$xml->endElement();
		$xml->startElement('SignatureMethod');
		$xml->writeAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');
		$xml->endElement();
		$xml->startElement('Reference');
		$xml->writeAttribute('URI', '#lote:1ABCDZ');
		$xml->startElement('Transforms');
		$xml->startElement('Transform');
		$xml->writeAttribute('Algorithm','http://www.w3.org/2000/09/xmldsig#enveloped-signature');
		$xml->endElement();
		$xml->startElement('Transform');
		$xml->writeAttribute('Algorithm','http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
		$xml->endElement();
		$xml->endElement();
		$xml->startElement('DigestMethod');
		$xml->writeAttribute('Algorithm','http://www.w3.org/2000/09/xmldsig#sha1');
		$xml->endElement();
		$xml->writeElement('DigestValue', $certificado['DigestValue']);
		$xml->endElement();
		$xml->endElement();
		// signature value 
		$xml->writeElement('SignatureValue',$certificado['SignatureValue']);
		$xml->startElement('KeyInfo');
		$xml->startElement('X509Data');
		// certificate
		$xml->writeElement('X509Certificate',$certificado['X509Certificate']);
		$xml->endElement();
		$xml->endElement();
		$xml->endElement();

		$xml->endElement();

		$xml->endElement();

	//	return $xml->outputMemory(true);

		 header( 'Content-type: text/xml' );
		 print $xml->outputMemory(true);
	}
}