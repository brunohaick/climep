<?php
/**
 * Classe de Logs
 *
 * @author Mario Chapela
 */
Class Log {

    private static $logName;
    private static $log;
    private static $data;
    private static $file;
    private static $path;
    private static $append;
    private static $content;

    public function __construct() {
        
    }

    /**
     * registrar
     *  Função para fazer LOG
     *  1 MB = 1048576 (bytes)
     *  3 MB = 3145728
     * @param Mixed Array | String $log Dados a serem armazenados
     * @param String $file nome do arquivo de log
     * @param String $logName Nome do registro
     * @param String $path Caminho para onde armazenar o log
     * @param Boolean $append Caso true adicionar ao fim, caso false inclui como unico
     * @return String $logName Nome ou id do log gerado
     */
    public static function registrar($log, $file = null, $logName = null, $append = null, $path = null) {
        self::setLog($log);
        self::setFile($file);
        self::setPath($path);
        self::setAppend($append);
        self::getContent(!(self::getAppend()));
        self::setLogName($logName);

        self::setContent(Array('Data' => self::getData(), 'Log' => self::getLog()));

        File::write(self::getFile(), saidaJson(self::$content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return self::getLogName();
    }

    public static function setContent($content) {
        self::$content[self::getLogName()] = $content;
    }

    public static function getContent($clear = false, $arrayFormat = true) {
        if ($clear === true || ($text = File::getContents(self::getFile())) === false) {
            self::$content = Array();
        } else {
            self::$content = json_decode($text, $arrayFormat);
        }
        return self::$content;
    }

    public static function setLog($log = '') {
        self::$log = $log;
    }

    public static function getLog() {
        if (is_null(self::$log)) {
            self::setLog();
        }
        return self::$log;
    }

    public static function setLogName($logName = null) {
        self::$logName = (is_null($logName)) ? 'Log_' . count(self::$content) : $logName;
    }

    public static function getLogName() {
        if (is_null(self::$logName)) {
            self::setLogName();
        }
        return self::$logName;
    }

    public static function setData() {
        self::$data = date('d/m/Y H:i:s');
    }

    public static function getData() {
        if (is_null(self::$data)) {
            self::setData();
        }
        return self::$data;
    }

    public static function setFile($file = 'logs.log') {
        self::$file = $file;
    }

    public static function getFile() {
        if (is_null(self::$file)) {
            self::setFile();
        }

        self::$file = File::mergeFileToPath(self::getPath(), self::$file);

        if ((!File::exists(self::$file)) && (!File::create(self::$file))) {
            return false;
        }

        self::checkAndMakeBackup(3145728, self::getPath() . './backup_log/');

        return self::$file;
    }

    public static function setAppend($append = true) {
        self::$append = $append;
    }

    public static function getAppend() {
        if (is_null(self::$append)) {
            self::setAppend();
        }
        return self::$append;
    }

    public static function setPath($path = '') {
        $path = (is_null($path) || empty($path)) ? './logs/' : $path;
        self::$path = $path;
    }

    public static function getPath() {
        if (is_null(self::$path)) {
            self::setPath();
        }

        if ((!File::exists(self::$path)) && (!File::createDir(self::$path))) {
            return false;
        }

        return self::$path;
    }

    /**
     * checkAndMakeBackup
     * Função que executa uma verificação no arquivo e verifica se é necessario 
     * criar um backup e caso sim o faz.
     * 
     * @param String $file Caminho até o arquivo a ser checado
     * @param Integer $sizeToBackup Quantidade de Bytes que a partir de o arquivo deve ser movido
     * @param String $toDir Caminho do diretorio em que os backups devem ser colocados
     * @param String $prefixFile String a ser colocada antes do nome do backup;
     * @param String $sufixFile String a ser colocada após o nome do backup
     * @param Boolean $delOriginFile Variavel que dermina se o arquivo ap�s copiado para o diretorio de backup deve ser excluido
     * @return Mixed String | Boolean
     */
    public static function checkAndMakeBackup($sizeToBackup = 1048576, $toDir = './backup_log/', $prefixFile = '', $sufixFile = '.backup', $delOriginFile = true) {
        clearstatcache();
        $fileFullPath = File::mergeFileToPath($toDir, self::$file);
        if (empty($fileFullPath) || !File::exists($fileFullPath) || (filesize($fileFullPath) > $sizeToBackup)) {
            return 'not need backup';
        }
        $prefixFile = (empty($prefixFile)) ? date('d.m.Y_[H-i-s]-') : $prefixFile;
        if ((!File::exists($toDir)) && (!File::createDir($toDir))) {
            return false;
        }
        return File::copy($fileFullPath, File::mergeFileToPath($toDir, ($prefixFile . self::$file . $sufixFile)), $delOriginFile);
    }

}