<?php
/**
 * Classe de Arquivos
 *
 * @author Mario Chapela
 */
Class File {
   
    /**
    * create
    * @param String $file nome do arquivo a ser criado
    * @param String $mode Permissao a ser alterada (1 = execucao, 2 = escrita, 4 = leitura) (0 + proprietario + grupo do proprietario + qualquer outro)
    * @return Resource OR Boolean
    */
    public static function create($file, $mode = 0777){
        return (self::open($file)) ? self::changePermission($file, $mode) : false;
    }
    
    /**
    * delete
    * @param String $file nome do arquivo a ser deletado
    * @return Boolean
    */
    public static function delete($file){
        if(!unlink($file)) {
            self::changePermission($file);
            return unlink($file);
        }
        return true;
    }
    
    /**
    * copy
    * @param String $origem caminho completo do arquivo de origem
    * @param String $destino caminho completo do arquivo de destino
    * @param Boolean $delete caso true apaga o arquivo de origem
    * @return Boolean
    */
    public static function copy($origem, $destino, $del = false){
        if(self::exists($origem)){
            $troco = copy($origem, $destino);
            if($del === true){
                self::delete($origem);
            }
            return $troco;
        }else{
            return false;
        }
    }
    
    /**
    * exists
    * @param String $file nome do arquivo a ser verificado se existe
    * @return Boolean
    */
    public static function exists($file){
        return file_exists($file);
    }
    
    /**
     * open
     * @param String $file caminho do arquivo a ser aberto
     * @param String $mode modo para abrir o arquivo; ('r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+')
     * @return Resource OR Boolean
     */
    public static function open($file, $mode = "w+"){
        return fopen($file, $mode);
    }
    
    /**
     * write
     * @param String $file caminho do arquivo a ser escrito
     * @param String $text texto a ser escrito
     * @return Boolean
     */
    public static function write($file, $text){
        $handle = self::open($file);
        $foi    = fwrite($handle, $text);
        fclose($handle);
        return $foi;
    }
    
    /**
     * changePermission
     * @param String $file caminho do arquivo a ser escrito
     * @param String $permission Permissao a ser alterada (1 = execucao, 2 = escrita, 4 = leitura) (0 + proprietario + grupo do proprietario + qualquer outro)
     * @return Boolean
     */
    public static function changePermission($file, $permission = 0777){
        return chmod($file, $permission);
    }    
    
    /**
    * getContents
    * @param String  $file caminho completo para obter o arquivo
    * @return Mixed String file content OR Boolean
    */
    public static function getContents($file){
        return (self::exists($file)) ? file_get_contents($file) : false;
    }
    
    /**
    * findAndGetContents
    * @param String $file nome do arquivo
    * @param String $path caminho de onde procurar o arquivo
    * @return Mixed String file content or Boolean
    */
    public static function findAndGetFileContents($file, $path){
        $file = self::find($file, $path, true);
        return ($file !== false) ? self::getContents($file) : false;
    }
    
    /**
     * append
     * @param String $file caminho para o arquivo a ser preenchido
     * @param String $text texto para preencher o arquivo 
     * @param Boolean $final caso true inclue no final caso false inclue no comeco
     * @return Boolean 
     */
    public static function append($file, $text, $final = true){
        $content    = self::getContents($file);
        $content    = (($final) ? ($content . $text) : ($text . $content));
        return self::write($file, $content);
    }
    
    /**
     * replace
     * @param Mixed $remove Array ou String a ser retirada
     * @param Mixed $put Array ou String a ser colocada
     * @param String $file caminho para o arquivo a ser substituido
     * @return Mixed String replaced or Boolean 
     */
    public static function replace($remove, $put, $file){
        if(!self::exists($file)){return false;}
        return str_replace($remove, $put, self::getContents($file));
    }
    
    /**
     * replaceForEver
     * @param Mixed $remove Array ou String a ser retirada
     * @param Mixed $put Array ou String a ser colocada
     * @param String $file caminho para o arquivo a ser substituido
     * @return Mixed String replaced or Boolean 
     */
    public static function replaceForEver($remove, $put, $file){
        return self::write($file, self::replace($remove, $put, $file));
    }
    
    // Diretorios
    
    /**
     * createDir
     * @param String $local caminho do diretorio a ser criado Ex. ./pasta_existente/pasta_para_criar/
     * @param Integer $mode Permissoes da pasta
     * @return Boolean
     */
    public static function createDir($local, $mode = 0777){
        return (!self::exists($local)) ? mkdir($local, $mode) : false;
    }
    
    /**
     * cleanDir
     * @param String $directory path para a pasta a ser limpa
     * @param Boolean $deleteDirectory Caso true apaga o diretorio inicial
     * @return Void
     */
    public static function cleanDir($directory, $deleteDirectory) {
        if(!is_dir($directory)){return false;}
        $directory  = dir($directory);
        while (false !== ($object = $directory->read())) {
            if($object == '.' || $object == '..'){continue;}
            $object = self::mergeFileToPath($directory->path, $object);
            if(!self::delete($object) && is_dir($object)){
                self::cleanDir($object, true);
            }
        }
        if ($deleteDirectory){rmdir($directory->path);}
    }
    
    /**
     * lerDiretorio
     * @param String $path Path a ser lido
     * @param Boolean $recursive Se deve ler os diretorios abaixo dele
     * @param Boolean $find Se o metodo sera usado para procurar um arquivo
     * @param String $fileToFind nome do arquivoa ser localizado
	 * @param Array $ignore Arquivos e dirs a ignorar
     * @return Mixed String com o caminho do arquivo OU False
     */
    public static function find($file, $path, $recursive = true, $ignore = Array('.', '..')){
        if(!is_dir($path)){return false;}
        $diretorio  = dir($path);
        while (false !== ($item = $diretorio->read())) {
            if(is_array($ignore) && (in_array($item, $ignore) || in_array(self::mergeFileToPath($diretorio->path, $item) . '/', $ignore))) {continue;}
            if((is_dir(self::mergeFileToPath($diretorio->path, $item) . '/')) && ($recursive === true)){
                $directoryData = self::find($file, self::mergeFileToPath($diretorio->path, $item) . '/', true);
                if(is_string($directoryData)) {
                    $fileFinded = explode('/', $directoryData);
                    $fileFinded = $fileFinded[count($fileFinded)-1];
                    if(self::compareFileName($fileFinded, $file)){
                        return $directoryData;
                    }
                }
                continue;
            } else {
                if(self::compareFileName($file, $item)){
                    return self::mergeFileToPath($diretorio->path, $item);
                }
            }
        }
        $diretorio->close();
        return false;
    }

    // Uteis
    
    /**
     * compareFileName 
     * Compara duas strings
     * @param type $file1 String #1 a ser comparada
     * @param type $file2 String #2 a ser comparada
     * @return Boolean
     */
    public static function compareFileName($file1, $file2){
        return String::compare($file1, $file2);
    }

    /**
     * mergeFileToPath
     * @param String $path
     * @param String $file
     * @return String 
     */
    public static function mergeFileToPath($path, $file) {
        $path .= ($path{(strlen($path)-1)} == '/' ) ? '' : '/';
        if(($posicao = strrpos($file, '/')) !== false){
            $file = substr($file, $posicao+1);
        }
        return $path . $file;
    }
}