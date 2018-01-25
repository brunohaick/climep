<?php

/**
 * Classe de String
 *
 * @author Mario Chapela
 */
Class String {

    /**
     * Padroniza  o encode do texto
     * 
     * @param String  $texto texto a ser convertido
     * @param Bollean $imprime TRUE = Gera um ECHO, FALSE = Apenas Retorna a STRING (texto)
     * @param String  $targetEncode Encode para o qual o texto deve ser convertido.
     * @param String  $encodings Lista de encodings que o texto pode estar.
     * @return String String no encode padrão
     */
    public static function fixEncode($texto = '', $targetEncode = null, $imprime = false, $funcao = 2, $encodings = 'UTF-8, ISO-8859-1') {
        $targetEncode = (is_null($targetEncode)) ? 'UTF-8' : $targetEncode;
        $encodeOriginal = mb_detect_encoding($texto . 'x', $encodings, true);
        switch ($funcao) {
            case 1:
                $texto = ($targetEncode == $encodeOriginal) ? $texto : html_entity_decode(htmlentities($texto, ENT_QUOTES, $encodeOriginal), ENT_QUOTES, $targetEncode);
                break;
            case 2:
                $texto = ($targetEncode == $encodeOriginal) ? $texto : iconv($encodeOriginal, $targetEncode /* TRANSLIT */, $texto);
                break;
            default:
                $texto = ($targetEncode == $encodeOriginal) ? $texto : mb_convert_encoding($texto, $targetEncode);
                break;
        }
        if ($imprime !== true) {
            return (mb_check_encoding($texto, $targetEncode) === true) ? $texto : false;
        } else {
            echo($texto);
            return $texto;
        }
    }

    /**
     * isEmpty
     * @param String $string
     * @return Boolean
     */
    public static function isEmpty($string) {
        return empty($string);
    }

    /**
     * compare
     * Compara Duas strings
     * @param String $string1
     * @param String $string2
     * @param Boolean $caseSensitive
     * @return Boolean
     */
    public static function compare($string1, $string2, $caseSensitive = true) {
        return ($caseSensitive === true) ? ($string1 === $string2) : (strtolower($string1) === strtolower($string2));
    }

    /**
     * formatCurrency
     * @param String $valor Valor a ser tratado
     * @param String $formato 'US' ou 'BR'
     * @return String Valor tratado
     */
    public static function formatCurrency($valor, $formato = 'BR') {
        return ($formato == 'US') ? number_format(str_replace(',', '.', str_replace('.', '', $valor)), 2, '.') : number_format($valor, 2, ',', '.');
    }

    /**
     * getOnlyText
     * @param String $string
     * @return String 
     */
    public static function getOnlyText($string) {
        return preg_replace("/[^A-Za-zÀ-ú]/", "", $string);
    }

    /**
     * getOnlyNumbers
     * @param String $string
     * @return String 
     */
    public static function getOnlyNumbers($string) {
        return preg_replace("/[^0-9]/", "", $string);
    }

    /**
     * removeOnlyText
     * @param String $string
     * @return String 
     */
    public static function removeOnlyText($string) {
        return preg_replace("/[A-Za-zÀ-ú]/", "", $string);
    }

    /**
     * removeOnlyNumbers
     * @param String $string
     * @return String 
     */
    public static function removeOnlyNumbers($string) {
        return preg_replace("/[0-9]/", "", $string);
    }

    /**
     * @name isValidCPF
     * @param String $cpf Numero de CPF com ou sem pontuacao
     * @return Boolean 
     */
    public static function isValidCPF($cpf) {
        $cpf = self::getOnlyNumbers($cpf);
        if (empty($cpf) || strlen($cpf) < 11) {
            return false;
        }
        $digitoUm = 0;
        $digitoDois = 0;
        for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
            $digitoUm += $cpf[$i] * $x;
        }
        for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
            if (str_repeat($i, 11) == $cpf) {
                return false;
            }
            $digitoDois += $cpf[$i] * $x;
        }
        $calculoUm = (($digitoUm % 11) < 2) ? 0 : 11 - ($digitoUm % 11);
        $calculoDois = (($digitoDois % 11) < 2) ? 0 : 11 - ($digitoDois % 11);
        if ($calculoUm <> $cpf[9] || $calculoDois <> $cpf[10]) {
            return false;
        }
        return true;
    }

    /**
     * @name isValidCNPJ
     * @param String $cnpj Numero do CNPJ com ou sem pontuacao
     * @return Boolean 
     */
    public static function isValidCNPJ($cnpj) {
        $cnpj = self::getOnlyNumbers($cnpj);
        if (strlen($cnpj) <> 14) {
            return false;
        }
        $soma = 0;
        $soma += ($cnpj[0] * 5);
        $soma += ($cnpj[1] * 4);
        $soma += ($cnpj[2] * 3);
        $soma += ($cnpj[3] * 2);
        $soma += ($cnpj[4] * 9);
        $soma += ($cnpj[5] * 8);
        $soma += ($cnpj[6] * 7);
        $soma += ($cnpj[7] * 6);
        $soma += ($cnpj[8] * 5);
        $soma += ($cnpj[9] * 4);
        $soma += ($cnpj[10] * 3);
        $soma += ($cnpj[11] * 2);

        $d1 = $soma % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;

        $soma = 0;
        $soma += ($cnpj[0] * 6);
        $soma += ($cnpj[1] * 5);
        $soma += ($cnpj[2] * 4);
        $soma += ($cnpj[3] * 3);
        $soma += ($cnpj[4] * 2);
        $soma += ($cnpj[5] * 9);
        $soma += ($cnpj[6] * 8);
        $soma += ($cnpj[7] * 7);
        $soma += ($cnpj[8] * 6);
        $soma += ($cnpj[9] * 5);
        $soma += ($cnpj[10] * 4);
        $soma += ($cnpj[11] * 3);
        $soma += ($cnpj[12] * 2);

        $d2 = $soma % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;

        if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @name formataCPF
     * @param String $cpf Numero do CPF com ou sem pontuacao
     * @return Mixed Boolean || String
     */
    public function formatarCPF($cpf) {
        return self::formatarCPF_CNPJ($cpf);
    }

    /**
     * @name formataCNPJ
     * @param String $cnpj Numero do CNPJ com ou sem pontuacao
     * @return Mixed Boolean || String
     */
    public function formataCNPJ($cnpj) {
        return self::formatarCPF_CNPJ($cnpj);
    }

    /**
     * @name formataCPF_CNPJ
     * @param String $numero Numero de CPF ou CNPJ com ou sem pontuacao
     * @return Mixed Boolean || String
     */
    public static function formataCPF_CNPJ($numero) {
        $numero = self::getOnlyNumbers($numero);
        if (empty($numero)) {
            return false;
        }
        $mascaras = Array(11 => Array("111.222.333-44", Array("111", "222", "333", "44")), 14 => Array("11.222.333/4444-55", Array("11", "222", "333", "4444", "55")), 15 => Array("111.222.333/4444-55", Array("111", "222", "333", "4444", "55")));
        $tamanho = strlen($numero);
        if (!array_key_exists($tamanho, $mascaras)) {
            return $tamanho;
        }
        $numeroFormatado = $mascaras[$tamanho][0];
        $controle = 0;
        foreach ($mascaras[$tamanho][1] as $campo) {
            $tamanhoCampo = strlen($campo);
            $numeroFormatado = str_replace($campo, substr($numero, $controle, $tamanhoCampo), $numeroFormatado);
            $controle += $tamanhoCampo;
        }

        return $numeroFormatado;
    }

    /**
     * extractInfo
     * obter dados de strings que estejam entre chaves;
     * @param String $string   Texto a vasculhado [/Usuario/Info/{var1}/{var2}]
     * @param String $marcadorI Delimitador inicial de cada palavra. defaul:"{"
     * @param String $marcadorF Delimitador final de cada palavra. defaul:"}"
     * @param String $spliter Divisor da string para o EXPLODE defaul:"/"
     * @return Array
     */
    public static function extractInfo($string, $marcadorI = '{', $marcadorF = '}', $spliter = '/') {
        $array = explode($spliter, $string);
        $data = Array();
        foreach ($array as $indice => $item) {
            if (empty($item)) {
                unset($array[$indice]);
            }
            if (($posI = strpos($item, $marcadorI)) !== false && ($posF = strpos($item, $marcadorF)) !== false) {
                $data[] = substr($item, 1, -1);
            }
        }
        return $data;
    }

    /**
     * strip
     * metodo para separar uma string em pedacos baseado em uma string inicial, string final e um separador
     * @param String $texto String original
     * @param String $palavra_inicial apartir dessa string o Texto deve ser trazido
     * @param String $palavra_final até essa string o text deve ser trazido
     * @param String $separador o texto deve ser quebrado a cada vez que essa string for encontrada
     * @todo testar e aprimorar o metodo STRIP
     * @return Array
     */
    public static function strip($texto, $palavra_inicial = null, $palavra_final = null, $separador = null, $limpa_delimitadores = false) {
        if (!empty($palavra_inicial)) {
            $texto = strstr($texto, $palavra_inicial);
        }

        $posic_final = empty($palavra_final) ? strlen($texto) : strlen($palavra_final) + strpos($texto, $palavra_final);
        $texto = array(substr($texto, 0, $posic_final));
        
        if (!empty($separador)) {
            $texto = explode($separador, $texto[0]);
        }

        if ($limpa_delimitadores) {
            foreach ($texto as $chave => $valor) {
                $texto[$chave] = str_replace(Array($palavra_inicial, $palavra_final, $separador), "", $valor);
            }
        }
        return $texto;
    }

}