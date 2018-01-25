<?php

class Certificado extends DOMDocument
{

    /**
     * $URLxsi
     * @var string
     */
    private $URLxsi = 'http://www.w3.org/2001/XMLSchema-instance';
    /**
     * $URLxsd
     * @var string
     */
    private $URLxsd = 'http://www.w3.org/2001/XMLSchema';
    /**
     * $URLnfe
     * @var string
     */
    private $URLnfe = 'http://www.portalfiscal.inf.br/nfe';
    /**
     * $URLdsig
     * @var string
     */
    private $URLdsig = 'http://www.w3.org/2000/09/xmldsig#';
    /**
     * $URLCanonMeth
     * @var string
     */
    private $URLCanonMeth = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * $URLSigMeth
     * @var string
     */
    private $URLSigMeth = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    /**
     * $URLTransfMeth_1
     * @var string
     */
    private $URLTransfMeth_1 =
        'http://www.w3.org/2000/09/xmldsig#enveloped-signature';
    /**
     * $URLTransfMeth_2
     * @var string
     */
    private $URLTransfMeth_2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    /**
     * $URLDigestMeth
     * @var string
     */
    private $URLDigestMeth = 'http://www.w3.org/2000/09/xmldsig#sha1';
    /**
     * Chave Pública do certificado
     *
     * @var string
     */

    var $pubKey = '';

    /**
     * Chave Privada do certificado
     *
     * @var string
     */

    var $privKey = '';

    /**
     * assinaXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para
     * assinar
     * os arquivos XML
     *
     * @name assinaXML
     * @version 1.1
     * @param    string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return    mixed FALSE se houve erro ou string com o XML
     * assinado
     */
    public function assinaXML($tagid, $xmlstring)
    {
        if ($tagid == '') {
            throw new Exception('Tag não pode ser vazia.');
        }
        if (($this->privKey == '') || ($this->pubKey == '')) {
            throw new Exception('Carregue um certificado antes.');
        }
        $pkeyid = openssl_get_privatekey($this->privKey);
        //$this->load('Enviar.xml');
		$this->loadXML($xmlstring);
        $root = $this->documentElement;
        //extrair a tag com os dados a serem assinados
        $node = $this->getElementsByTagName($tagid)->item(0);
        if ($node == '') {
            throw new Exception("Tag $tagid não existe no XML. Impossível assinar");
        }
        $id = trim($node->getAttribute("Id"));
        $idnome = preg_replace('/[^0-9]/', '', $id);
        //extrai os dados da tag para uma string
        $dados = $node->C14N(false, false, null, null);
        //calcular o hash dos dados
        $hashValue = hash('sha1', $dados, true);
        //converte o valor para base64 para serem colocados no xml
        $digValue = base64_encode($hashValue);
        //monta a tag da assinatura digital
        $Signature = $this->createElementNS($this->URLdsig, 'Signature');
        $root->appendChild($Signature);
        $SignedInfo = $this->createElement('SignedInfo');
        $Signature->appendChild($SignedInfo);
        //Cannocalization
        $newNode = $this->createElement('CanonicalizationMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
        //SignatureMethod
        $newNode = $this->createElement('SignatureMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLSigMeth);
        //Reference
        $Reference = $this->createElement('Reference');
        $SignedInfo->appendChild($Reference);
        $Reference->setAttribute('URI', empty($id) ? '' : '#' . $id);
        //Transforms
        $Transforms = $this->createElement('Transforms');
        $Reference->appendChild($Transforms);
        //Transform
        $newNode = $this->createElement('Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
        //Transform
        $newNode = $this->createElement('Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
        //DigestMethod
        $newNode = $this->createElement('DigestMethod');
        $Reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
        //DigestValue
        $newNode = $this->createElement('DigestValue', $digValue);
//		echo "DIGEST =>".$digValue;
//		echo "<br>";
        $Reference->appendChild($newNode);
        // extrai os dados a serem assinados para uma string
        $dados = $SignedInfo->C14N(false, false, null, null);
        //inicializa a variavel que irá receber a assinatura
        $signature = '';
        //executa a assinatura digital usando o resource da chave privada
        $resp = openssl_sign($dados, $signature, $pkeyid);
        //codifica assinatura para o padrao base64
        $signatureValue = base64_encode($signature);
//		echo "SIGNATURE =>".$signatureValue;
//		echo "<br>";
        //SignatureValue
        $newNode = $this->createElement('SignatureValue', $signatureValue);
        $Signature->appendChild($newNode);
        //KeyInfo
        $KeyInfo = $this->createElement('KeyInfo');
        $Signature->appendChild($KeyInfo);
        //X509Data
        $X509Data = $this->createElement('X509Data');
        $KeyInfo->appendChild($X509Data);
        //carrega o certificado sem as tags de inicio e fim
        $cert = $this->_getPubKey();
        //X509Certificate

//		echo "CERT =>".$cert;
//		echo "<br>";
        $newNode = $this->createElement('X509Certificate', $cert);
        $X509Data->appendChild($newNode);

        // libera a memoria
        openssl_free_key($pkeyid);

		$certificado['DigestValue'] = $digValue;
		$certificado['SignatureValue'] = $signatureValue;
		$certificado['X509Certificate'] = $cert;
		return $certificado;


    }


    /**
     * Retira as chaves de inicio e fim do certificado digital
     * para inclusão na tag assinatura do xml
     *
     * @return   string contendo a chave digital
     **/
    private function _getPubKey()
    {
        return (str_replace(array('-----BEGIN CERTIFICATE-----',
            '-----END CERTIFICATE-----', "\n"), '', $this->pubKey));
    }


    /**
     * Carrega o certificado pfx e gera as chaves privada e publica no
     * formato pem para uso nas assinaturas digitais
     * Além disso esta função também avalia a validade do certificado.
     * Os certificados padrão A1 (que são usados pelo sistema) tem
     * validade
     * limitada à 1 ano e caso esteja vencido a função retornará
     * FALSE.
     *
     *
     * @param    $conteudoPFX, obtido com file_get_contents(arq_pfx);
     * @return    boolean TRUE se o certificado foi carregado e FALSE
     * se nao
     **/
    public function setCertificado($conteudoPFX, $password = '')
    {
        //carrega os certificados e chaves para um array denominado $x509certdata
        if (!@openssl_pkcs12_read($conteudoPFX, $x509certdata, $password)) {
            throw new Exception('O certificado não pode ser lido. Provavelmente está corrompido ou senha é inválida');
        }
        //verifica sua validade
        if ($this->validCert($x509certdata['cert']) === false) {
            throw new Exception("Certificado vencido.");
        }
        $this->privKey = $x509certdata['pkey'];
        $this->pubKey = $x509certdata['cert'];

        return true;
    }


    /**
     * Validaçao do cerificado digital
     *
     * @param string  $cert Certificado digital no formato pem
     * @return integer|boolean  número de dias para expirar, ou
     * falso se expirado
     */
    public function validCert($cert = null)
    {
        $dValid = $this->certVencimento($cert);
        // obtem o timestamp da data de hoje
        $dHoje = gmmktime(0, 0, 0, date("m"), date("d"), date("Y"));
        // compara a data de validade com a data atual
        if ($dValid < $dHoje) {
            return false;
        } else {
            return round(($dValid - $dHoje) / (60 * 60 * 24), 0);
        }
    }

    /**
     * Retorna o vencimento do certificado
     *
     * @param    string  $cert OPCIONAL Certificado digital no formato
     * pem
     * @return    integer|boolean  timestamp
     */
    public function certVencimento($cert = null)
    {
        if ($cert === null) {
            $cert = $this->pubKey;
        }
        $data = openssl_x509_read($cert);
        $cert_data = openssl_x509_parse($data);
        // reformata a data de validade;
        $ano = substr($cert_data['validTo'], 0, 2);
        $mes = substr($cert_data['validTo'], 2, 2);
        $dia = substr($cert_data['validTo'], 4, 2);
        //obtem o timeestamp da data de validade do certificado
        return gmmktime(0, 0, 0, $mes, $dia, $ano);
    }
}


