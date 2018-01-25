<?php

/**
 * @file  model/utils.php
 * @brief Guarda as funções que nao tem acesso ao banco e que são uteis a
 * 		logica do sistema
 * @version 0.01
 * @date  Criação: 05/09/2012
 * @date  Alteração: 05/09/2012
 * @todo
 * 	@li colocar filtros e expressões regulares na funcao _INPUT
 * 
 * */

/**
 * Utilizada para encurtar uma string qualquer e um limite de caracteres
 *
 * @author Andrey Maia
 * @date Criação: 05/09/2012
 *
 * @param string $string String que será encurtada
 * @param integer $limite Limite de caracteres desejado
 *
 * @return
 * 	String encurtada contendo ... no fim da string
 *
 */
function encurtar($string, $limite) {

    $string = strip_tags($string);

    if (strlen($string) >= $limite) {
        $string = substr($string, 0, $limite);
        $palavra = strrpos($string, ' ');
        $string = substr($string, 0, $palavra);
        $string .= "..."; // Colocamos três pontinhos no final da string
    }

    return $string;
}

/**
 * Utilizada para encurtar uma string qualquer e um limite de caracteres
 *
 * @author Bruno Haick
 * @date Criação: 05/09/2012
 *
 * @param string $string String que será encurtada
 * @param integer $limite Limite de caracteres desejado
 *
 * @return
 * 	String encurtada
 *
 */
function encurtar2($string, $limite) {

    if (strlen($string) >= $limite) {
        $string = substr($string, 0, $limite);
    }

    return $string;
}

/**
 * Utilizada para encurtar uma string qualquer e um limite de caracteres
 *
 * @author Andrey Maia
 * @date Criação: 05/09/2012
 *
 * @param string $string String que será encurtada
 * @param integer $limite Limite de caracteres desejado
 *
 * @return
 * 	String encurtada
 *
 */
function formata_dinheiro($valor) {

    $valor = number_format($valor, 2, ',', '');

    return "R$ " . $valor;
}

function saidaJson($saida) {
    
    if(is_array($saida)){
        die(json_encode(arrayUtf8Enconde($saida)));
    }else{
        die(json_encode($saida));
    }
  }

    /**
     * Utilizada para encurtar uma string qualquer e um limite de caracteres
     *
     * @author Andrey Maia
     * @date Criação: 21/03/2012
     *
     * @param string $string String que será encurtada
     * @param integer $limite Limite de caracteres desejado
     *
     * @return
     * 	String encurtada
     *
     */
    function formata_dinheiro2($valor){

            $valor = number_format(  $valor, 2, ',', '');

    return $valor;

    }

    /**
     * Função para Diminuir dias, meses ou anos a uma determinada data que é 
     * passada como parâmetro desta funcção.
     *
     * @param $data
     * @param $qtdDias
     * @param $tipo (dia, mes ou ano)
     *
     * @author Bruno Haick
     * @date Criação: 18/10/2012
     *
     * @return
     * 	$data subtraindo-se o que se deseja
     *
     */
    function subtrairData ($data, $qtd, $tipo) {

            if ( $tipo == "dia") {
            $tipo = "days";
    } else if ( $tipo == "mes") {
            $tipo = "month";
    } else if ( $tipo == "ano") {
            $tipo = "year";
            }

            $data = converteData($data);

    return date( 'd/m/Y', strtotime("-$qtd  $tipo", strtotime($data)));

    }

    /**
     * Função para calcular a data do historicoVacina, a partir
     * da posição marcada na tela de modulos.
     *
     * @param $data no formato xx/xx/xxxx
     * @param $posicao_horizontal
     *
     * @author Bruno Haick
     * @date Criação: 27/7/2013
     *
     * @return
     * 	$data somando-se o que se deseja no formato xx/xx/xxxx
     *
     */
    function somaPosicaoData ($data, $posicao_horizontal) {

            if($posicao_horizontal == 1 || $posicao_horizontal == 0) { // 0 mes
    return $data;
    } else if($posicao_horizontal == 18) { // 2 anos
    $qtd = 2;
            $tipo = "year";
    } else if($posicao_horizontal == 19) { // 4 anos
    $qtd = 4;
            $tipo = "year";
    } else if($posicao_horizontal > 1 && $posicao_horizontal < 18) { // entre 1 e 16 meses
            $qtd = $posicao_horizontal;
            $tipo = "month";
            }

            $data = converteData($data);

    return date( 'd/m/Y', strtotime("+$qtd  $tipo", strtotime($data)));

    }

    /**
     * Função para Adicionar dias, meses ou anos a uma determinada data que é
     * passada como parâmetro desta funcção.
     *
     * @param $data no formato xx/xx/xxxx
     * @param $qtd
     * @param $tipo (dia, mes ou ano)
     *
     * @author Bruno Haick
     * @date Criação: 18/10/2012
     *
     * @return
     * 	$data somando-se o que se deseja no formato xx/xx/xxxx
     *
     */
    function somarData ($data, $qtd, $tipo) {

            if ( $tipo == "dia") {
            $tipo = "days";
    } else if ( $tipo == "mes") {
            $tipo = "month";
    } else if ( $tipo == "ano") {
            $tipo = "year";
            }

            $data = converteData($data);

    return date( 'd/m/Y', strtotime("+$qtd  $tipo", strtotime($data)));

    }

    /**
     * Função para Traduzir uma data em Ingês para Português
     *
     * @author Bruno Haick
     * @date Criação: 10/10/2013
     *
     * @param $data
     * @param $mes_ano Array já existente em Bootstrap.php 
     * contendo os meses do ano em Português.
     *
     * @return String $data_traduzida
     *      $data traduzida no formato XX/mmm/XXXX
     * mmm = 3 primeiras letras do nome do Mês, 
     * por exemplo Jan, Fev, Mar, Nov, Dez...
     * 
     * Exemplo de retorno: 10out2013
     *
     */
    function traduzData ($data, $mes_ano) {

            $dia = substr ( $data, 0, 2);
            $mes = substr ( $data, 2, 3);
            $ano = substr ( $data, 5, 4);

            $Mes = substr($mes_ano [ $mes], 0, 3);
            $data_traduzida = strtolower ( $dia . $Mes.$ano);

    return $data_traduzida;

    }

    /**
     * Utilizada para fazer diff entre duas datas e retornar o numero de dias
     *
     * @author Bruno Haick
     * @date Criação: 14/03/2013
     *
     * @param string $antiga data mais antiga no formato xxxx-xx-xx
     * @param string $atual data mais atual no formato xxxx-xx-xx
     *
     * @return
     * 	Array contendo a diferença em dias
     *
     */
    function diferenca_data_dias( $antiga, $atual) {

            $data1 = explode ("-", $antiga);
            $data2 = explode ("-", $atual);

            $ano = $data2 [ 0] - $data1[0];
            $mes = $data2 [ 1] - $data1[1];
            $dia = $data2 [ 2] - $data1[2];

            $timestamp1 = mktime   (0, 0, 0, $data1[1], $data1[2], $data1[0]);
            $timestamp2 = mktime   (0, 0, 0, $data2[1], $data2[2], $data2[0]);

            $diff = $timestamp1 - $timestamp2;

            if ( $diff < 0) {
            $diferenca  = abs($diff);
            $dias_diferenca = $diferenca / (60 * 60 * 24);
    return $dias_diferenca;
    } else {
    return 0;
    }

    }

    /**
     * Utilizada para fazer diff entre duas datas
     *
     * @author Bruno Haick
     * @date Criação: 14/09/2012
     *
     * @param string $antiga data mais antiga no formato xxxx-xx-xx
     * @param string $atual data mais atual no formato xxxx-xx-xx
     *
     * @return
     * 	Array contendo a diferença em dias, meses e anos em cada indice ('dia','mes','ano').
     *
     */
    function diferenca_data( $antiga, $atual) {

            $data1 = explode ("-", $antiga);
            $data2 = explode ("-", $atual);

            $ano = $data2 [ 0] - $data1[0];
            $mes = $data2 [ 1] - $data1[1];
            $dia = $data2 [ 2] - $data1[2];

            if ( $mes < 0) {
    $ano--;
            $mes = 12 + $mes;
    }

            if ( $dia < 0) {
    $mes--;
            $dia = 30 + $dia;
            }

            $diff['dia'] = $dia;
            $diff['mes'] = $mes;
            $diff['ano'] = $ano;

    return $diff;

    }

    /**
     * Utilizada para encurtar uma string qualquer e um limite de caracteres
     *
     * @author Andrey Maia
     * @date Criação: 05/09/2012
     *
     * @param string $string String que será encurtada
     * @param integer $limite Limite de caracteres desejado
     *
     * @return
     * 	String encurtada
     *
     */
    function mostraMes($m){
    switch($m){
    case 01: case 1: $mes = "Janeiro";
    break;
    case 02: case 2: $mes = "Fevereiro";
    break;
    case 03: case 3: $mes = "Mar&ccedil;o";
    break;
    case 04: case 4: $mes = "Abril";
    break;
    case 05: case 5: $mes = "Maio";
    break;
    case 06: case 6: $mes = "Junho";
    break;
    case 07: case 7: $mes = "Julho";
    break;
    case 08: case 8: $mes = "Agosto";
    break;
    case 09: case 9: $mes = "Setembro";
    break;
        case 10: $mes = "Outubro";
    break;
        case 11: $mes = "Novembro";
    break;
        case 12: $mes = "Dezembro";
    break;
    }
    return

    $mes;
    }

    function mostraSemana($semana){

    switch ($semana) {
        case 0: $semana = "Domingo";
    break;
        case 1: $semana = "Segunda-Feira";
    break;
        case 2: $semana = "Terça-Feira";
    break;
        case 3: $semana = "Quarta-Feira";
    break;
        case 4: $semana = "Quinta-Feira";
    break;
        case 5: $semana = "Sexta-Feira";
    break;
        case 6: $semana = "Sábado";
    break;
    }
    return $semana;
    }

    /**
     * Utilizada para encurtar uma string qualquer e um limite de caracteres
     *
     * @author Andrey Maia
     * @date Criação: 05/09/2012
     *
     * @param string $string String que será encurtada
     * @param integer $limite Limite de caracteres desejado
     *
     * @return
     * 	String encurtada
     *
     */
    function valida_data($data) {
            $data = preg_split( "/[-,\/]/", $data);
            if(!checkdate( $data[1], $data[0], $data [ 2]) and!checkdate( $data[1], $data[2], $data[0])) {
    return false;
    }
    return true;
    }

    /**
     * Utilizada para percorrer uma matriz de historico de vacinas
     * e retornar o numero de indices da linha da matriz que tiver o
     * maior numero de registros.
     *
     * @author Bruno Haick
     * @date Criação: 20/09/2012
     *
     * @param Array
     *
     * @return
     * int $maior
     *
     */
    function maiorLinhaMatriz($arr) {

    $atual = 0;
    $maior = 0;
    foreach ( $arr as $hist){
            $atual = count($hist);

            if($atual >
        $maior)
                $maior = $atual;
    }

    return $maior;

    }

    /**
     * Utilizada para tanto para transformar as datas para serem inseridas no banco de dados
     * quanto para as trazer do banco e mostra-las corretamente.
     *
     * @author Andrey Maia
     * @date Criação: 05/09/2012
     * 
     * Documentação
     * @author Marcus Dias
     * @date Alteração: 11/09/2012
     *
     * @param date $data Data que será transformada
     *
     * @return
     * Date transformada.
     *
     * Ou NULL caso a data seja composta totalmente por 0 (zeros);
     *
     */
    function converteData($data) {
            if(valida_data($data)) {
    return implode(!strstr ($data, '/')  ? "/"   : "-", array_reverse(explode(!strstr ($data, '/')  ? "-"   : "/", $data)));
    } 
        else
        return "";
    }

    /**
     *  Função utilizada para fazer o filtro de POST e GET
     *
     * @author Andrey Maia
     * @date Criação: 05/09/2012
     *
     * @param string $nome Nome da chave do Array que se quer filtrar
     * @param string $vartipo tipo da variavel para obter o melhor tipo de filtro (int, 
     * etc...)
     * @param string $tipoform O array de origem (POST ou GET)
     *
     * @todo colocar expressões regulares e tipos de filtros.
     *
     */
    function _INPUT ($nome, $vartipo  = null, $tipoform = null) {
    //vartipo
    //  =string
    //  =int
    //  =date
    //  = email
    //tipoform
    //  = post
    //  = get
    switch ($tipoform) {
    case 'post':
        return $_POST[$nome];
    break;
    case 'get':
                $tpform = '_GET';
        return $_GET[$nome];
        default:
        return $_POST[$nome];
    break;
    }

    function get_rnd_iv($iv_len) {

    $iv = '';

    while ($iv_len -- > 0) {
            $iv .= chr(mt_rand ( ) & 0xff);

    }

    return $iv;
    }

    /**
     * Utilizada para criptografar o get
     *
     * @author Desconhecido
     * @date Criação: -
     *
     * @todo falta documentacao
     *
     */
    function md5_encrypt( $plain_text, $password, $iv_len = 16) {

            $plain_text .= "sw_";
            $n = strlen($plain_text);

            if ( $n
        % 16) $plain_text .= str_repeat ("", 16 - ($n % 16));

    $i = 0;

            $enc_text = get_rnd_iv($iv_len);

            $iv = substr($password ^  $enc_text, 0, 512);

    while ( $i < $n) {

            $block = substr(  $plain_text, $i, 16) ^ pack('H*', md5($iv));

            $enc_text .= $block;

            $iv = substr($block . $iv, 0, 512) ^ $password;

            $i += 16;

    }

    return base64_encode($enc_text);
    }

    /**
     * Utilizada para decriptografar o get
     *
     * @author Desconhecido
     * @date Criação: -
     *
     * @todo falta documentacao
     *
     */
    function md5_decrypt( $enc_text, $password, $iv_len = 16) {

            $enc_text = base64_decode($enc_text);
            $n = strlen($enc_text);
            $i = $iv_len;
    $plain_text = '';

            $iv = substr($password ^ substr(  $enc_text, 0,   $iv_len), 0, 512);

    while ( $i < $n) {
            $block = substr(  $enc_text, $i, 16);
            $plain_text .= $block ^ pack('H*', md5($iv));
            $iv = substr($block . $iv, 0, 512) ^ $password;

            $i += 16;
    }

    return preg_replace(  '/\sw_\x00*$/', '', $plain_text);

    }
    }

    /**
     * Da internet mas dá problema com floats
     * ex : 2 != 2
     * resolvido com o abs, mas não é seguro.
     */
    function is_decimal_net($val) {
    return is_numeric($val ) && floor($val ) != $val && abs(floor ( $val) - $val) > 0.00001;
    }

    /**
     * Função que retorna se um numero é decimal
     *
     * @author Marcus Dias
     * @date 11/10/2012
     */
    function is_decimal($val) {
            if(is_numeric ($val)){
            $precisao = 10000;
            $testeVal = $val*$precisao; // Precisao de 4 casas decimais

            if($testeVal % $precisao != 0) {
    return true;
    } else {
    return false;
    }
    } else {
    return false;
    }
    }

    /**
     * Função que trata a máscara de valor (em dinheiro)
     * para enviar um valor correto para o banco de dados
     *
     * @author Marcus Dias
     * @date 11/10/2012
     *
     */
    function trataValorMoeda($valor) {

            $valor = str_replace ( ".", "", $valor);
            $valor = str_replace ( ",", ".", $valor);

    return $valor;
    }

    /**
     * Função que da internet pra escrever valores por extenso
     *
     * @author Bruno Haick
     * @date 20/3/2013
     *
     */
    function valorPorExtenso( $valor = 0, $complemento = true) {

            $singular = array( "centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array( "centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

            $c = array ("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
            $d = array ("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
            $d10 = array( "dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
            $u = array ("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

    $z = 0;

            $valor = number_format(  $valor, 2, ".", ".");
            $inteiro = explode (".", $valor);
    for ($i = 0;$i<count($inteiro);$i++)
        for($ii = strlen($inteiro [$i]); $ii<3;$ii++)
                    $inteiro[$i] = "0".$inteiro[$i];

    // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
            $fim = count($inteiro ) - ($inteiro[count($inteiro ) - 1] > 0  ? 1  : 2);
    for ($i = 0;
            $i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor [ 1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor [ 1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e "   : "" ) .$rd . (($rd && $ru) ? " e "   : "").$ru;
            $t = count($inteiro ) - 1-$i;
            if ($complemento == true) {
            $r .= $r  ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor ==
        "000")$z++;
            elseif ( $z
         > 0) $z--;
            if ( ($t == 1) && ( $z>0) && ($inteiro [ 0]
        > 0)) $r .= (($z>1) ? " de "   : "").$plural[$t];
    }
    if
         ($r) $rt = $rt . ( (($i > 0) && ($i <= $fim ) && ($inteiro [ 0] > 0) && ($z < 1)) ? ( ($i < $fim)  ? ", " : " e ")   : " ") . $r;
    }

    return($rt  ? $rt : "zero"

);
    }

    function retira_acentos($texto) {

            $array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
        , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" );
            $array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
        , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" );

    return str_replace( $array1, $array2, $texto);
    }

    /**
     * Calcula diferença dos horarios
     * 
     * @param String $primeiraHora {hora:min:seg}
     * @param String $segundaHora {hora:min:seg}
     * @return Array no formato {hora:min:seg}
     */
    function calculaDiferençaMinutos( $primeiraHora, $segundaHora) {
            $H1 =  split(':', $primeiraHora);
            $H2 =  split(":", $segundaHora);

            $H1[1] -= $H2[1];
            $H1[0] -= $H2[0];
            $H1[0] *= 60;
            $H1[2] -= $H2[2];
            $H1[2] /= 60;

    return number_format ( $H1 [ 0] + $H1 [ 1] + $H1[2], 1);
}
