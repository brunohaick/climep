<?php
/**
 * Classe de TIME
 *
 * @author Mario Chapela
 */
Class Time {
    
    /**
     * @var String $dia dia
     */
    private static $dia;
    
    /**
     * @var String $mes Mes
     */
    private static $mes;
    
    /**
     * @var String $ano Ano
     */
    private static $ano;
    
    /**
     * @var String $hora Hora
     */
    private static $hora;
    
    /**
     * @var String $minuto Minuto
     */
    private static $minuto;
    
    /**
     * @var String $segundo Segundo
     */
    private static $segundo;

    /**
     * @var String $dia_atual Dia atual
     */
    private static $dia_atual;
    
    /**
     * @var String $mes_atual Mes atual
     */
    private static $mes_atual;
    
    /**
     * @var String $ano_atual Ano atual
     */
    private static $ano_atual;
    
    /**
     * @var String $hora_atual Hora atual
     */
    private static $hora_atual;
    
    /**
     * @var String $hora_atual Hora atual
     */
    private static $minuto_atual;
    
    /**
     * @var String $segundo_atual Segundo atual
     */
    private static $segundo_atual;
    
    /**
     * @var String $data Data dd/mm/aaaa ou aaaa-mm-dd
     */
    private static $data;
 
    /**
     * @var Boolean $horario_de_veral Horario de verao
     */
    private static $horario_de_verao;
        
    /**
     * @var Array $TIME data atual no padrao brasileiro e americano
     */
    private static $TIME;
    
    /**
     * @var Array $diasDaSemana
     */
    private static $diasDaSemana = Array('1' => Array('Seg', 'Segunda'  , 'Segunda Feira'),
                                         '2' => Array('Ter', 'Terça'    , 'Terça Feira'),
                                         '3' => Array('Qua', 'Quarta'   , 'Quarta Feira'),
                                         '4' => Array('Qui', 'Quinta'   , 'Quinta Feira'),
                                         '5' => Array('Sex', 'Sexta'    , 'Sexta Feira'),
                                         '6' => Array('Sab', 'Sábado'   , 'Sábado'),
                                         '7' => Array('Dom', 'Domingo'  , 'Domingo'));
    
    /**
     * @var Array $mesesDoAno
     */
    private static $mesesDoAno = Array( '1' => Array('Jan', 'Janeiro'  ),
                                        '2' => Array('Fev', 'Fevereiro'),
                                        '3' => Array('Mar', 'Março'    ),
                                        '4' => Array('Abr', 'Abril'    ),
                                        '5' => Array('Mai', 'Maio'     ),
                                        '6' => Array('Jun', 'Junho'    ),
                                        '7' => Array('Jul', 'Julho'    ),
                                        '8' => Array('Ago', 'Agosto'   ),
                                        '9' => Array('Set', 'Setembro' ),
                                       '10' => Array('Out', 'Outubro'  ),
                                       '11' => Array('Nov', 'Novembro' ),
                                       '12' => Array('Dez', 'Dezembro' ));
    /**
     * @var Array $padroesDeDataEHora
     */
   private static $padroesDeDataEHora = Array(
        'Data_BR_e_HORA_e_SEG'  => Array(
            'pesquisa'  => "/[0-3]?[0-9][\/-][0-1]?[0-9][\/-][0-3][0-9][0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]/",
            'captura'   => "/([0-3]?[0-9])[\/-]([0-1]?[0-9])[\/-]([0-3][0-9][0-9][0-9]) ([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/",
            'dia'       => 1,'mes'       => 2,'ano'       => 3,'hora'      => 4,'minuto'    => 5,'segundo'   => 6,'metodo'    => 'getDataEHoraCompleta'
        ),
        'Data_EN_e_HORA_e_SEG'  => Array(
            'pesquisa'  => "/[0-2][0-9][0-9][0-9][\/-][0-1]?[0-9][\/-][0-3]?[0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9]/",
            'captura'   => "/([0-3][0-9][0-9][0-9])[\/-]([0-1]?[0-9])[\/-]([0-3]?[0-9]) ([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/",
            'dia'       => 3,'mes'       => 2,'ano'       => 1,'hora'      => 4,'minuto'    => 5,'segundo'   => 6,'metodo'    => 'getDataEHoraCompleta'
         ),
        'Data_BR_e_HORA'=> Array(
            'pesquisa'  => "/[0-3]?[0-9][\/-][0-1]?[0-9][\/-][0-3][0-9][0-9][0-9] [0-9][0-9]:[0-9][0-9]/",
            'captura'   => "/([0-3]?[0-9])[\/-]([0-1]?[0-9])[\/-]([0-3][0-9][0-9][0-9]) ([0-9][0-9]):([0-9][0-9])/",
            'dia'       => 1,'mes'       => 2,'ano'       => 3,'hora'      => 4,'minuto'    => 5,'metodo'    => 'getDataEHora'
        ),
        'Data_EN_e_HORA'=> Array(
            'pesquisa'  => "/[0-2][0-9][0-9][0-9][\/-][0-1]?[0-9][\/-][0-3]?[0-9] [0-9][0-9]:[0-9][0-9]/",
            'captura'   => "/([0-3][0-9][0-9][0-9])[\/-]([0-1]?[0-9])[\/-]([0-3]?[0-9]) ([0-9][0-9]):([0-9][0-9])/",
            'dia'       => 3,'mes'       => 2,'ano'       => 1,'hora'      => 4,'minuto'    => 5,'metodo'    => 'getDataEHora'
        ),
        'Data_BR'       => Array(
            'pesquisa'  => "/[0-3]?[0-9][\/-][0-1]?[0-9][\/-][0-3][0-9][0-9][0-9]/",
            'captura'   => "/([0-3]?[0-9])[\/-]([0-1]?[0-9])[\/-]([0-3][0-9][0-9][0-9])/",
            'dia'       => 1,'mes'       => 2,'ano'       => 3,'metodo'    => 'getData'
        ),
        'Data_EN'       => Array(
            'pesquisa'  => "/[0-2][0-9][0-9][0-9][\/-][0-1]?[0-9][\/-][0-3]?[0-9]/",
            'captura'   => "/([0-3][0-9][0-9][0-9])[\/-]([0-1]?[0-9])[\/-]([0-3]?[0-9])/",
            'dia'       => 3,'mes'       => 2,'ano'       => 1,'metodo'    => 'getData'
        ));

                                                                      
    /**
     * __construct()
     * @return Object Time
     */
    public function __construct() {
        self::getTime();
        return $this;
    }
    
    /**
     * Set
     * @param String $data String da data a ser convertida, deve ser passada: dd/mm/aaaa OU aaaa-mm-dd
     * @param String $local
     * @param Boolean $comSegundos Caso true trabalha os segundos se nao existirem coloca '00'
     * @param Boolean $isClean Caso true retorna a data no formato solicitado mas sem qualquer caracter como ('/', '-', ' ', ':')
     * @return Void()
     */
    public static function set($data, $local = 'EN', $comSegundos = false, $isClean = false) {
       if(is_array($data)){
            return self::changeArrayData($data, $local, $comSegundos, $isClean);
        }
        if(($padraoInfo = self::isValidData($data)) !== false){
            if(preg_match($padraoInfo['pesquisa'], $data)){
                preg_match_all($padraoInfo['captura'], $data, $tempArray);
                self::setDia($tempArray[$padraoInfo['dia']][0]);
                self::setMes($tempArray[$padraoInfo['mes']][0]);
                self::setAno($tempArray[$padraoInfo['ano']][0]);
                if(isset($padraoInfo['hora'])){
                    self::setHora($tempArray[$padraoInfo['hora']][0]);
                }
                if(isset($padraoInfo['minuto'])){
                    self::setMinuto($tempArray[$padraoInfo['minuto']][0]);
                }
                $temSegundos = (isset($padraoInfo['segundo'])) ? true : false;
                if($comSegundos){
                    $padraoInfo['metodo'] = 'getDataEHoraCompleta';
                } else if (!$comSegundos && $temSegundos) {
                    $padraoInfo['metodo'] = 'getDataEHora';
                }
                self::setSegundo(($temSegundos) ? $tempArray[$padraoInfo['segundo']][0] : '00');
                return $padraoInfo;
            }
        }
        return false;
    }
    
    /**
     * setDia
     * @param String $dia
     * @return String $dia
     */
    public static function setDia($dia = null) {
        self::$dia = str_pad(((is_null($dia) /*|| $dia < 1 || $dia > 31*/) ? self::getDiaAtual() : $dia), 2, "0", STR_PAD_LEFT);
        return self::getDia();
    }
    
    /**
     * getDia
     * @return String
     */
    public static function getDia() {
        if(is_null(self::$dia)){
            return self::setDia();
        }
        return (string) self::$dia;
    }

    /**
     * setMes
     * @param String $mes
     * @return String $mes
     */
    public static function setMes($mes = null) {
        self::$mes = str_pad(((is_null($mes) /* || $mes < 1 || $mes > 12*/) ? self::getMesAtual() : $mes), 2, "0", STR_PAD_LEFT);
        return self::getMes();
    }
    
    /**
     * getMes
     * @return String
     */
    public static function getMes() {
        if(is_null(self::$mes)){
            return self::setMes();
        }
        return (string) self::$mes;
    }
    
    /**
     * setAno
     * @param String $ano
     * @return String $ano
     */
    public static function setAno($ano = null) {
        self::$ano = is_null($ano)      ? self::getAnoAtual() : intval($ano);
        self::$ano = (self::$ano < 1)   ? self::getAnoAtual() : self::$ano;
        self::$ano = str_pad(self::$ano, 4, "20", STR_PAD_LEFT);
        /*self::$ano = (self::$ano > (self::getAnoAtual() + 50)) ? self::getAnoAtual() : self::$ano;*/
        self::$ano = (self::$ano < 1500) ? self::getAnoAtual() : self::$ano;
        return self::getAno();
    }
    
    /**
     * getAno
     * @return String
     */
    public static function getAno() {
        if(is_null(self::$ano)){
            return self::setAno();
        }
        return (string) self::$ano;
    }
    
    /**
     * setHora
     * @param String $hora
     * @return String $hora
     */
    public static function setHora($hora = null) {
        self::$hora = str_pad(((is_null($hora) /*|| $hora < 1 || $hora > 23*/) ? self::getHoraAtual() : $hora), 2, "0", STR_PAD_LEFT);
        return self::getHora();
    }
    
    /**
     * getHora
     * @return String
     */
    public static function getHora() {
        if(is_null(self::$hora)){
            return self::setHora();
        }
        return (string) self::$hora;
    }
    
    /**
     * setMinuto
     * @param String $minuto
     * @return String $minuto
     */
    public static function setMinuto($minuto = null) {
        self::$minuto = str_pad(((is_null($minuto) /*|| $minuto < 1 || $minuto > 59*/) ? self::getMinutoAtual() : $minuto), 2, "0", STR_PAD_LEFT);
        return self::getMinuto();
    }
    
    /**
     * getMinuto
     * @return String
     */
    public static function getMinuto() {
        if(is_null(self::$minuto)){
            return self::setMinuto();
        }
        return (string) self::$minuto;
    }
    
    /**
     * setSegundo
     * @param String $segundo
     * @return String $segundo
     */
    public static function setSegundo($segundo = null) {
        self::$segundo = str_pad(((is_null($segundo) /*|| $segundo < 0 || $segundo > 59*/) ? self::getSegundoAtual() : $segundo), 2, "0", STR_PAD_LEFT);
        return self::getSegundo();
    }
    
    /**
     * getSegundo
     * @return String
     */
    public static function getSegundo() {
        if(is_null(self::$segundo)){
            return self::setSegundo();
        }
        return (string) self::$segundo;
    }

    /**
     * setDiaAtual
     * @return String $dia_atual
     */
    private static function setDiaAtual() {
        self::$dia_atual = date('d');
        return self::getDia();
    }
    
    /**
     * getDia
     * @return String
     */
    public static function getDiaAtual() {
        if(is_null(self::$dia_atual)){
            return self::setDiaAtual();
        }
        return (string) self::$dia_atual;
    }

    /**
     * setMesAtual
     * @return String $mes_atual
     */
    private static function setMesAtual() {
        self::$mes_atual = date('m');
        return self::getMesAtual();
    }
    
    /**
     * getMesAtual
     * @return String
     */
    public static function getMesAtual() {
        if(is_null(self::$mes_atual)){
            return self::setMesAtual();
        }
        return (string) self::$mes_atual;
    }
    
    /**
     * setAnoAtual
     * @return String $ano_atual
     */
    private static function setAnoAtual() {
        self::$ano_atual = date('Y');
        return self::getAnoAtual();
    }
    
    /**
     * getAnoAtual
     * @return String
     */
    public static function getAnoAtual() {
        if(is_null(self::$ano_atual)){
            return self::setAnoAtual();
        }
        return (string) self::$ano_atual;
    }

    /**
     * setHoraAtual
     * @return String $hora_atual
     */
    private static function setHoraAtual() {
        self::$hora_atual = date('H');
        //self::$hora_atual = str_pad((self::$hora_atual  -=( (self::isHorarioDeVerao()) ? 1 : 0) ), 2, "0", STR_PAD_LEFT);
        return self::getHoraAtual();
    }
    
    /**
     * getHoraAtual
     * @return String
     */
    public static function getHoraAtual() {
        if(is_null(self::$hora_atual)){
            return self::setHoraAtual();
        }
        return (string) self::$hora_atual;
    }
    
    /**
     * setMinutoAtual
     * @return String $minuto_atual
     */
    private static function setMinutoAtual() {
        self::$minuto_atual = date('i');
        return self::getMinutoAtual();
    }
    
    /**
     * getMinutoAtual
     * @return String
     */
    public static function getMinutoAtual() {
        if(is_null(self::$minuto_atual)){
            return self::setMinutoAtual();
        }
        return (string) self::$minuto_atual;
    }
    
    /**
     * setSegundoAtual
     * @return String $segundo_atual
     */
    private static function setSegundoAtual() {
        self::$segundo_atual = date('s');
        return self::getSegundoAtual();
    }
    
    /**
     * getSegundoAtual
     * @return String
     */
    public static function getSegundoAtual() {
        if(is_null(self::$segundo_atual)){
            return self::setSegundoAtual();
        }
        return (string) self::$segundo_atual;
    }
    
    /**
     * getDiaDaSemana
     * @param Integer $dia dia da semana onde 1 eh segunda e 7 eh domingo
     * @param Integer $tamanho 0 = SEG, 1 = SEGUNDA, 2 = SEGUNDA FEIRA
     * @return String 
     */
    public static function getDiaDaSemana($dia = null, $tamanho = 0) {
        $dia        = (is_null($dia) || $dia < 1 || $dia > 7) ? date('N') : $dia;
        $tamanho    = ($tamanho > 2 || $tamanho < 0) ? 0 : $tamanho;
        return String::fixEncode(self::$diasDaSemana[$dia][$tamanho]);
    }
    
    /**
     * getDiaDaSemanaAtual
     * @param Integer $tamanho 0 = SEG, 1 = SEGUNDA, 2 = SEGUNDA FEIRA
     * @return String 
     */
    public static function getDiaDaSemanaAtual($tamanho = 0) {
        return self::getDiaDaSemana(null, $tamanho);
    }

    /**
     * getNomeDoMes
     * @param Integer $mes Mes 1 eh janeiro e 12 eh dezembro
     * @param Integer $tamanho 0 = JAN, 1 = JANEIRO
     * @return String 
     */
    public static function getNomeDoMes($mes = null, $tamanho = 0) {
        $mes        = (is_null($mes) || $mes < 1 || $mes > 12) ? date('n') : $mes;
        $tamanho    = ($tamanho > 1 || $tamanho < 0) ? 0 : $tamanho;
        return String::fixEncode(self::$mesesDoAno[$mes][$tamanho]);
    }

    /**
     * getNomeDoMesAtual
     * @param Integer $tamanho 0 = JAN, 1 = JANEIRO
     * @return String 
     */
    public static function getNomeDoMesAtual($tamanho = 0) {
        return self::getNomeDoMes(null, $tamanho);
    }
    
    /**
     * isBisexto
     * @return Booleam
     */
    public static function isBisexto() {
        return (boolean) date('L');
    }
    
    /**
     * setData
     * @param String $data Data  dd/mm/aaaa ou aaaa-mm-dd
     * @return String $data
     */
    public static function setData($data = null) {
        self::$data = (is_null($data)) ? self::getDia() . '/' . self::getMes() . '/' . self::getAno() : $data;
        return self::$data;
    }
    
    /**
     * getData
     * @param String $formato EN = aaaa-mm-dd ou dd/mm/aaaa
     * @return String
     */
    public static function getData($formato = 'EN') {
        if(is_null(self::$data)){
            self::setData();
        }
        return ($formato == 'EN') ? self::getDataEN() : self::getDataBR();
    }
    
    /**
     * getDataEN
     * @return String
     */
    private static function getDataEN() {
        if(is_null(self::$data)){
            self::setData();
        }
        return self::getAno() . "-" . self::getMes() . "-" . self::getDia();
    }
    
    /**
     * getDataBR
     * @return String
     */
    private static function getDataBR() {
        if(is_null(self::$data)){
            self::setData();
        }
        return self::getDia() . "/" . self::getMes() . "/" . self::getAno();
    }
    
    /**
     * getDataAtual
     * @param String $formato EN = aaaa-mm-dd ou dd/mm/aaaa
     * @return String
     */
    public static function getDataAtual($formato = 'EN') {
        return self::change(self::getDiaAtual() . '/' . self::getMesAtual() . '/' . self::getAnoAtual(), $formato);
    }
    
    /**
     * setHorarioDeVerao
     * A funcao possui um if ternario 
     * para saber se eh horario de verao mas nao eh dinamico precisa
     * ser alterado para cada ano.
     * 
     * O Horario de verao inicia no terceiro domingo de outubro e vai ate o
     * terceiro domingo do ano seguinte. ( Se este domingo coincidir 
     * com o carnaval o fim do horario de verao vai para a semana seguinte. ) 
     * O Carnaval ocorre 47 dias antes da pascoa
     * + Infos e datas (carnaval): http://pt.wikipedia.org/wiki/Carnaval 
     * + Infos (horario de verao): http://pcdsh01.on.br/DecHV.html
     * 
     * @return Boolean $horario_de_verao
     */
    private static function setHorarioDeVerao() {
        $dataJunta = self::getAnoAtual() . self::getMesAtual() . self::getDiaAtual();
        //self::$horario_de_verao = ($dataJunta > '20121021' && $dataJunta < '20130217' ) ? true : false;
        self::$horario_de_verao = (date('I') == "1") ? true : false;
        return self::getHorarioDeVerao();
    }
    
    /**
     * getHorarioDeVerao
     * @return Boolean
     */
    public static function getHorarioDeVerao() {
        if(is_null(self::$horario_de_verao)){
            return self::setHorarioDeVerao();
        }
        return self::$horario_de_verao;
    }
    
    /**
     * isHorarioDeVerao
     * @return Boolean
     */
    public static function isHorarioDeVerao() {
        return self::getHorarioDeVerao();
    }
    
    /**
     * getHorario
     * @param Boolean $completo TRUE = HH:MM:SS - FALSE = HH:MM
     * @return String
     */
    public static function getHorario($completo = false) {
        return self::getHora() . ':' . self::getMinuto() . (($completo) ? ':' . self::getSegundo() : '');
    }
    
    /**
     * getDataEHora
     * @param String $formato EN = aaaa-mm-dd HH:MM ou dd/mm/aaaa HH:MM
     * @return String
     */
    public static function getDataEHora($formato = 'EN') {
        return self::getData($formato) . ' ' . self::getHorario();
    }
    
    /**
     * getDataEHoraCompleta
     * @param String $formato EN = aaaa-mm-dd HH:MM:SS ou dd/mm/aaaa HH:MM:SS
     * @return String
     */
    public static function getDataEHoraCompleta($formato = 'EN') {
        return self::getData($formato) . ' ' . self::getHorario(true);
    }

    /**
     * setTime
     * @return Array
     */
    private static function setTime(){
        self::$TIME['DIA']                      = self::getDiaAtual();
        self::$TIME['MES']                      = self::getMesAtual();
        self::$TIME['ANO']                      = self::getAnoAtual();
        self::$TIME['HORA']                     = self::getHoraAtual();
        self::$TIME['MINUTO']                   = self::getMinutoAtual();
        self::$TIME['SEGUNDO']                  = self::getSegundoAtual();
        self::$TIME['DATA_BR']                  = self::getData('BR');
        self::$TIME['DATA_EN']                  = self::getData();
        self::$TIME['HORARIO_DE_VERAO']         = self::isHorarioDeVerao();
        self::$TIME['HORARIO']                  = self::getHorario();
        self::$TIME['HORARIO_COMPLETO']         = self::getHorario(true);
        self::$TIME['DATA_E_HORA_EN']           = self::getDataEHora();
        self::$TIME['DATA_E_HORA_BR']           = self::getDataEHora('BR');
        self::$TIME['DATA_E_HORA_COMPLETA_EN']  = self::getDataEHoraCompleta();
        self::$TIME['DATA_E_HORA_COMPLETA_BR']  = self::getDataEHoraCompleta('BR');
        self::$TIME['DATA']                     = self::getData('BR');
        self::$TIME['DATA_E_HORA']              = self::getDataEHora('BR');
        self::$TIME['DATA_E_HORA_COMPLETA']     = self::getDataEHoraCompleta('BR');
        return self::getTime();
    }
    
    /**
     * getTime
     * @param String $indice - Indice de time DESEJADO (DIA, MES, ANO, HORA, MINUTO, SEGUNDO, HORARIO, HORARIO_COMPLETO, DATA_EN, DATA_BR, HORARIO_DE_VERAO, DATA_E_HORA_EN, DATA_E_HORA_BR, DATA, DATA_E_HORA, DATA_E_HORA_COMPLETA_EN, DATA_E_HORA_COMPLETA_BR, DATA_E_HORA_COMPLETA)
     * @return String 
     */
    public static function getTime($indice = null){
        if(is_null(self::$TIME)){
            self::setTime();
        }
        if(!is_null($indice) && isset(self::$TIME[$indice])){
            return self::$TIME[str_replace(' ', '_', strtoupper($indice))];
        }
        return self::$TIME;
    }
    
    /**
     * change
     * Função para converter datas do sistema brasileiro para o americano e do americano para o brasileiro.
     * Retorna: String com a data no formato: (dd/mm/aaaa OU aaaa-mm-dd) ou (dd/mm/aaaa hh:mm:ss OU aaaa-mm-dd hh:mm:ss)
     * 
     * @param String $data String da data a ser convertida, deve ser passada: dd/mm/aaaa OU aaaa-mm-dd
     * @param String $local Formato americano EN ou brazileiro PT
     * @param Boolean $comSegundos Caso true trabalha os segundos se nao existirem coloca '00'
     * @param Boolean $isClean Caso true retorna a data no formato solicitado mas sem qualquer caracter como ('/', '-', ' ', ':')
     * @return String $data No formato solicitado 
     */
    public static function change($data, $local = 'EN', $comSegundos = false, $isClean = false) {
        if(is_array($data)){
            return self::changeArrayData($data, $local, $comSegundos);
        }
        if(($padraoInfo = self::set($data, $local, $comSegundos)) !== false){
            if($isClean){
                return self::clean(self::$padraoInfo['metodo']($local));
            }
            return self::$padraoInfo['metodo']($local);
        }
        return self::change(date('d/m/Y'), $local, $comSegundos, $isClean);
    }

    /**
     * changeArrayData
     * Altera todas as datas de um array de datas
     * @param Array $array
     * @param String $local
     * @param Boolean $comSegundos Caso true trabalha os segundos se nao existirem coloca '00'
     * @param Boolean $isClean Caso true retorna a data no formato solicitado mas sem qualquer caracter como ('/', '-', ' ', ':')
     * @return Array
     */
    public static function changeArrayData(Array $array, $local = 'EN', $comSegundos = false, $isClean = false){
        $arrayResult = Array();
        foreach ($array as $key => $value) {
            if(String::isEmpty($value)){
                unset($array[$key]);
                continue;
            }
            $arrayResult[] = self::change($value, $local, $comSegundos, $isClean);
        }
        $arrayResult = (count($array) === 1) ? $arrayResult[0] : $arrayResult;
        
        if(count($arrayResult) === 0) {
            return self::change(date('d/m/Y'), $local, $comSegundos, $isClean);
        }
        return $arrayResult;
    }
    
    /**
     * isValidData
     * @param String $data String da data a ser convertida, deve ser passada: dd/mm/aaaa OU aaaa-mm-dd
     * @return String
     */
    public static function isValidData($data) {
        if(!is_string($data) || String::isEmpty($data)){return false;}
        foreach (self::$padroesDeDataEHora as $padrao => $padraoInfo) {
            if(preg_match($padraoInfo['pesquisa'], $data)){
                return $padraoInfo;
            }
        }
        return false;
    }
    
    /**
     * clean
     * @param String $string
     * @return String
     */
    public static function clean($string){
        return str_replace(Array('/', '-', ' ', ':'), '', $string);
    }

    /**
     * Metodos de Calculo
     */
    /**
     * executaCalculo
     * @return String
     */
    public static function executaCalculo(){
        self::set(date("d/m/Y H:i:s", mktime(self::getHora(), self::getMinuto(), self::getSegundo(), self::getMes(), self::getDia(), self::getAno())));
        return self::getDataEHoraCompleta('BR');
    }
    
    /**
     * somaDia
     * @param String $data data a ser somada
     * @param Integer $dias Dias a serem somados a data ja setada
     * @return String
     */
    public static function somaDia($dias = 1, $data = null){
        self::change($data);
        self::setDia(self::getDia()+$dias);
        return self::executaCalculo();
    }
    
    /**
     * somaMes
     * @param String $data data a ser somada
     * @param Integer $meses Meses a serem somados a data ja setada
     * @return String
     */
    public static function somaMes($meses = 1, $data = null){
        self::change($data);
        self::setMes(self::getMes()+$meses);
        return self::executaCalculo();
    }
    
    /**
     * somaAno
     * @param String $data data a ser somada
     * @param Integer $anos Anos a serem somados a data ja setada
     * @return String
     */
    public static function somaAno($anos = 1, $data = null){
        self::change($data);
        self::setAno(self::getAno()+$anos);
        return self::executaCalculo();
    }
    
    /**
     * somaSegundo
     * @param String $data data a ser somada
     * @param Integer $segundos Segundos a serem somados a data ja setada
     * @return String
     */
    public static function somaSegundo($segundos = 1, $data = null){
        self::change($data);
        self::setSegundo(self::getSegundo()+$segundos);
        return self::executaCalculo();
    }
    
    /**
     * somaMinuto
     * @param String $data data a ser somada
     * @param Integer $minutos Minutos a serem somados a data ja setada
     * @return String
     */
    public static function somaMinuto($minutos = 1, $data = null){
        self::change($data);
        self::setMinuto(self::getMinuto() + $minutos);
        return self::executaCalculo();
    }
    
    /**
     * somaHora
     * @param String $data data a ser somada
     * @param Integer $horas Horas a serem somados a data ja setada
     * @return String
     */
    public static function somaHora($horas = 1, $data = null){
        self::change($data);
        self::setHora(self::getHora() + $horas);
        return self::executaCalculo();
    }

    /**
     * diferenca
     * Retorna diferença entre as datas em Dias, Horas ou Minutos
     * 
     * @param String $data1 Data
     * @param String $data2 Data
     * @param String $tipo Determina a formatacao do retorno [dias horas minutos segundos]: <br/>
     * "s": Segundos                                                                        <br/>
     * "M": Minutos                                                                         <br/>
     * "m": Minutos arredondados                                                            <br/>
     * "H": Horas                                                                           <br/>
     * "h": Horas arredondada                                                               <br/>
     * "D": Dias                                                                            <br/>
     * "d": Dias arredontados - DEFAULT                                                     <br/>
     * "SE":Semanas (com 7 dias)                                                            <br/>
     * "se":Semanas Arredondadas (com 7 dias)                                               <br/>
     * "ME":Meses (com 30 dias)                                                             <br/>
     * "me":Meses arredondados (com 30 dias)                                                <br/>
     * "A": Anos (com 365 dias)                                                             <br/>
     * "a": Anos arredondados (com 365 dias)                                                <br/>
     * @return Mixed Integer | Float 
     */
    public static function diferenca($data1, $data2, $tipo = 'd'){
        $arrayLimpo = self::set(Array($data1, $data2), 'EN', true, true);
        sort($arrayLimpo);
        $segundos   = mktime(
                    substr($arrayLimpo[1],  8, 2)  , //$horaMaior
                    substr($arrayLimpo[1], 10, 2)  , //$minutoMaior
                    substr($arrayLimpo[1], 12, 2)  , //$segundoMaior
                    substr($arrayLimpo[1],  4, 2)  , //$mesMaior
                    substr($arrayLimpo[1],  6, 2)  , //$diaMaior
                    substr($arrayLimpo[1],  0, 4)    //$anoMaior
                    ) - mktime (
                    substr($arrayLimpo[0],  8, 2)  , //$horaMenor
                    substr($arrayLimpo[0], 10, 2)  , //$minutoMenor
                    substr($arrayLimpo[0], 12, 2)  , //$segundoMenor
                    substr($arrayLimpo[0],  4, 2)  , //$mesMenor
                    substr($arrayLimpo[0],  6, 2)  , //$diaMenor
                    substr($arrayLimpo[0],  0, 4)    //$anoMenor
                    );
        switch($tipo){
            case 's': // Segundo
                return $segundos;
            break;
            case 'M': // Minuto
                return $segundos/60;
            break;
            case 'm': // Minuto Arrendodado
                return round($segundos/60);
            break;
            case 'H': // Hora
                return $segundos/3600;
            break;
            case 'h': // Hora Arredondada
                return round($segundos/3600);
            break;
            case 'D': // Dia
                return $segundos/86400;
            break;
            default:
            case 'd': // Dia Arredondado
                return round($segundos/86400);
            break;
            case 'SE': // Semanas
                return $segundos/604800;
            break;
            case 'se': // Semandas Arredondadas
                return round($segundos/604800);
            break;
            case 'ME': // Meses
                return $segundos/2592000;
            break;
            case 'me': // Meses Arredondados
                return round($segundos/2592000);
            break;
            case 'A': // Anos 
                return $segundos/31536000;
            break;
            case 'a': // Anos Arredondados
                return round($segundos/31536000);
            break;
        }
    }
}