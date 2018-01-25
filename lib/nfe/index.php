<?php

include('NFSeUtil.class.php');
include('funcoesCertificado.class.php');
include('config.php');

$cabecalho = Array(
    'CodCidade' => COD_CIDADE,
    'CPFCNPJRemetente' => CNPJ,
    'RazaoSocialRemetente' => RAZAO_SOCIAL_REMETENTE,
    'dtInicio' => '2013-03-16',
    'dtFim' => '2013-03-16',
    'QtdRPS' => '1',
    'ValorTotalServicos' => '0',
    'ValorTotalDeducoes' => '0',
    'Versao' => VERSAO,
    'MetodoEnvio' => METODO_ENVIO
);

$loteId = '1ABCDZ';

$data = json_decode($_POST['data'], true);
foreach ($data as $key => $value) {
    $array[$key] = $value;
}
$servicos = pegaServicosPorRPS($array['id']);
foreach ($servicos as $item) {
    $itens[] = Array(
        'DiscriminacaoServico' => $item['descricao'], // atividades caixa id_referencia e busca em material o nome.
        'Quantidade' => '1.0000', // atividades_caixa quantidade ou sempre 1
        'ValorUnitario' => number_format($item['preco'], '4', '.', ''), // 59.6700 atividades_caixa valor 
        'ValorTotal' => number_format($item['preco'], '2', '.', ''), // 59.67 atividades_caixa valor
        'Tributavel' => 'S' // sempre S
    );
}

$rpss = Array(
    Array(
        'id' => $array['id'],
        'Assinatura' => '',
        'InscricaoMunicipalPrestador' => INSCRICAO_MUNICIPAL_PRESTADOR,
        'RazaoSocialPrestador' => RAZAO_SOCIAL_PRESTADOR,
        'TipoRPS' => TIPO_RPS,
        'SerieRPS' => SERIE_RPS,
        'NumeroRPS' => NUMERO_RPS,
        'DataEmissaoRPS' => date("Y-m-d\TH:i:s", $array['dataEmissaoRPS']), // '2013-01-18T00:00:00'
        'SituacaoRPS' => $array['situacaoRPS'],
        'SerieRPSSubstituido' => '', // Não é obrigatorio
        'NumeroRPSSubstituido' => '0', // Não é obrigatorio
        'NumeroNFSeSubstituida' => '0', // Não é obrigatorio
        'DataEmissaoNFSeSubstituida' => '1900-01-01', // Não é obrigatorio
        'SeriePrestacao' => '99',
        'InscricaoMunicipalTomador' => $array['inscricaoMunicipalTomador'],
        'CPFCNPJTomador' => $array['CPFCNPJTomador'],
        'RazaoSocialTomador' => $array['RazaoSocialTomador'],
        'TipoLogradouroTomador' => $array['TipoLogradouroTomador'],
        'LogradouroTomador' => $array['LogradouroTomador'],
        'NumeroEnderecoTomador' => $array['NumeroEnderecoTomador'],
        'ComplementoEnderecoTomador' => $array['ComplementoEnderecoTomador'],
        'TipoBairroTomador' => $array['TipoBairroTomador'],
        'BairroTomador' => $array['BairroTomador'],
        'CidadeTomador' => $array['CidadeTomador'],
        'CidadeTomadorDescricao' => $array['CidadeTomadorDescricao'],
        'CEPTomador' => $array['CEPTomador'], // só numeros
        'EmailTomador' => $array['EmailTomador'],
        'CodigoAtividade' => $array['CodigoAtividade'],
        'AliquotaAtividade' => $array['AliquotaAtividade'],
        'TipoRecolhimento' => $array['TipoRecolhimento'],
        'MunicipioPrestacao' => '0000427', // n tem no banco
        'MunicipioPrestacaoDescricao' => 'BELEM', // n tem no banco
        'Operacao' => $array['Operacao'],
        'Tributacao' => $array['Tributacao'],
        'ValorPIS' => $array['ValorPIS'],
        'ValorCOFINS' => $array['ValorCOFINS'],
        'ValorINSS' => $array['ValorINSS'],
        'ValorIR' => $array['ValorIR'],
        'ValorCSLL' => $array['ValorCSLL'],
        'AliquotaPIS' => $array['AliquotaPIS'],
        'AliquotaCOFINS' => $array['AliquotaCOFINS'],
        'AliquotaINSS' => $array['AliquotaINSS'],
        'AliquotaIR' => $array['AliquotaIR'],
        'AliquotaCSLL' => $array['AliquotaCSLL'],
        'DescricaoRPS' => $array['DescricaoRPS'],
        'DDDPrestador' => DDD_PRESTADOR,
        'TelefonePrestador' => TELEFONE_PRESTADOR,
        'DDDTomador' => $array['DDDTomador'],
        'TelefoneTomador' => $array['TelefoneTomador'],
        'MotCancelamento' => $array['MotCancelamento'],
        'CPFCNPJIntermediario' => $array['CPFCNPJIntermediario'],
        'Deducoes' => '',
        'Itens' => $itens)
);


//$rpss= Array(
//	Array(
//		'id' => '0',
//		'Assinatura' => '',
//		'InscricaoMunicipalPrestador' => INSCRICAO_MUNICIPAL_PRESTADOR,
//		'RazaoSocialPrestador' => RAZAO_SOCIAL_PRESTADOR,
//		'TipoRPS' => TIPO_RPS,
//		'SerieRPS' => SERIE_RPS, 
//		'NumeroRPS' => NUMERO_RPS,
//		'DataEmissaoRPS' => '2013-01-18T00:00:00',
//		'SituacaoRPS' => 'N',
//		'SerieRPSSubstituido' => '',
//		'NumeroRPSSubstituido' => '0',
//		'NumeroNFSeSubstituida' => '0',
//		'DataEmissaoNFSeSubstituida' => '1900-01-01',
//		'SeriePrestacao' => '99',
//		'InscricaoMunicipalTomador' => '0000000',
//		'CPFCNPJTomador' => '70869863215',
//		'RazaoSocialTomador' => 'SAMARA AYAN VELOSO DA SILVA',
//		'TipoLogradouroTomador' => 'TRAVESSA',
//		'LogradouroTomador' => 'CURUZU',
//		'NumeroEnderecoTomador' => '2235',
//		'ComplementoEnderecoTomador' => '',
//		'TipoBairroTomador' => 'BAIRRO',
//		'BairroTomador' => 'MARCO',
//		'CidadeTomador' => '0000427',
//		'CidadeTomadorDescricao' => 'BELEM',
//		'CEPTomador' => '66090140',
//		'EmailTomador' => 'test@gmail.com',
//		'CodigoAtividade' => '863050600',
//		'AliquotaAtividade' => '3.00',
//		'TipoRecolhimento' => 'A',
//		'MunicipioPrestacao' => '0000427',
//		'MunicipioPrestacaoDescricao' => 'BELEM',
//		'Operacao' => 'A',
//		'Tributacao' => 'T',
//		'ValorPIS' => '0.00',
//		'ValorCOFINS' => '0.00',
//		'ValorINSS' => '0.00',
//		'ValorIR' => '0.00',
//		'ValorCSLL' => '0.00',
//		'AliquotaPIS' => '0.0000',
//		'AliquotaCOFINS' => '0.0000',
//		'AliquotaINSS' => '0.0000',
//		'AliquotaIR' => '0.0000',
//		'AliquotaCSLL' => '0.0000',
//		'DescricaoRPS' => 'SERVICOS MEDICOS PRESTADOS DE VACINACAO',
//		'DDDPrestador' => '091',
//		'TelefonePrestador' => '31811644',
//		'DDDTomador' => '091',
//		'TelefoneTomador' => '32222222',
//		'MotCancelamento' => '',
//		'CPFCNPJIntermediario' => '',
//		'Deducoes' => '',
//		'Itens' => $itens)
//);
$signature = NFSeUtil::generateNFSeSignature($rpss[0]['InscricaoMunicipalPrestador'], $rpss[0]['SerieRPS'], $rpss[0]['NumeroRPS'], $rpss[0]['DataEmissaoRPS'], $rpss[0]['Tributacao'], $rpss[0]['SituacaoRPS'], $rpss[0]['TipoRecolhimento'] == 'A' ? 'N' : 'S', $itens[0]['ValorTotal'], $rpss[0]['Deducoes'], $rpss[0]['CodigoAtividade'], $rpss[0]['CPFCNPJTomador']);

$rpss[0]['Assinatura'] = $signature;

$tipoXML = $_POST['tipo_xml'];
$xmlstring = NFSeUtil::buildXML($cabecalho, $loteId, $rpss);
$certificado = new Certificado();
$certificado_file = file_get_contents("CertificadoSerasa.pfx");
$certificado->setCertificado($certificado_file, "249875");
$isValid = $certificado->validCert();
$isVencido = $certificado->certVencimento();
$assinatura = $certificado->assinaXML("Lote", $xmlstring);

switch ($tipoXML) {

    case 'consulta_nfeRPS':
        $xml = NFSeUtil::buildXML_ConsultaNFSeRPS($cabecalho, $loteId, $notas, $rpss);
        break;
    case 'consulta_notas':
        $xml = NFSeUtil::buildXML_ConsultaNotas($cabecalho, $assinatura);
        break;
    case 'consulta_seqRPS':
        $xml = NFSeUtil::buildXML_ConsultaSeqRPS($cabecalho);
        break;
    case 'consulta_lote':
        $xml = NFSeUtil::buildXML_ConsultaLote($cabecalho);
        break;
    case 'cancelamento':
        $xml = NFSeUtil::buildXML_Cancelamento($cabecalho, $loteId, $nota, $assinatura);
        break;
    default:
        $xml = NFSeUtil::buildXML($cabecalho, $loteId, $rpss, $assinatura);
        break;
}

$client = new SoapClient('http://www.issdigitalbel.com.br/WsNFe2/LoteRps.jws?wsdl');

$paramns = Array(
    'mensagemXml' => $xml);

# var_dump($client->__getFunctions());

echo htmlspecialchars($client->__soapCall('testeEnviar', $paramns));

//ini_set("display_errors", "ON");
//

//
//var_dump($assinatura);