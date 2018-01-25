<?php

include('GuiaTissUtil.class.php');

$xml = GuiaTissUtil::buildXML($cabecalho, $loteId, $rpss,$assinatura);

//$client = new SoapClient('http://www.issdigitalbel.com.br/WsNFe2/LoteRps.jws?wsdl');
//	
//$paramns = Array(
//	'mensagemXml' => $xml);
//
//# var_dump($client->__getFunctions());
//
//echo htmlspecialchars($client->__soapCall('testeEnviar', $paramns));
//
//ini_set("display_errors", "ON");
//

//
//var_dump($assinatura);

?>
