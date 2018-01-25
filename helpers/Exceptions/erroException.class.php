<?php

/**
 * This class call "ErroException" becase PHP have a class named ErrorException to throw PHP native Errors; <br/>
 * This exception have the objective to throw errors on system. <br/> 
 * Generally fatal errors <br/>
 * Always generates a log file
 * 
 * @category PHP
 * @package Helpers
 * @subpackage Exceptions
 * @version 1.0
 * 
 * @author Mario Chapela <elderzinho@hotmail.com> and Victor Lacerda <victorgerin@live.com>
 * @copyright (c) 2013, Mario Chapela and Victor Lacerda
 * @license   http://creativecommons.org/licenses/by/3.0/br/deed.pt_BR CreativeCommons - CC BY 3.0
 */
class ErroException extends BasicException
{

    /**
     * This exception have the objective to throw errors on system. <br/> 
     * Generally fatal errors <br/>
     * Always generates a log file
     * 
     * @param String $message Message of Exception
     * @param Integer $code The code of exception <br/>[default: 0]
     * @param Mixed $previous Exception | BasicException.  <br/> Previous Exception to be stored on log like exceptions backtrace
     * @param Boolean $fatal If true this exception is a fatal exception and the the program will failure. <br/> [default: false]
     * @param Boolean $doLog If true the exception will generate a logFile with information about this exception <br/> [default: true]
     * @access public
     * @return Void
     */
    public function __construct($message, $code = 0, $previous = null,
                                $fatal = false, $doLog = true)
    {
        $this->setClassName(__CLASS__);
        $this->setFileLog('erroExceptions.log');
        parent::__construct($message, $code, $previous, $fatal, $doLog);
    }

    /**
     * Converts data of Exception to String
     * 
     * @access public
     * @return String
     */
    public function __toString()
    {
        return 'Erro:' . parent::__toString();
    }
}