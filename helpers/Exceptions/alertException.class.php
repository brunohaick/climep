<?php

/**
 * Exception to be used for throw alert (serius warnings)
 * Alerts can or not make logs
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
class AlertException extends BasicException
{

    /**
     * Exception to be used for throw alert (serious warnings)
     * Alerts can or not make logs
     * 
     * @param String $message Message of Exception
     * @param Integer $code The code of exception <br/>[default: 0]
     * @param Mixed $previous Exception | BasicException.  <br/> Previous Exception to be stored on log like exceptions backtrace
     * @param Boolean $doLog If true the exception will generate a logFile with information about this exception <br/> [default: true]
     * @access public
     * @return Void
     */
    public function __construct($message, $code = 0, $previous = null,
                                $doLog = true)
    {
        $this->setClassName(__CLASS__);
        $this->setFileLog('alertaExceptions.log');
        parent::__construct($message, $code, $previous, false, $doLog);
    }

    /**
     * Converts data of Exception to String
     * 
     * @access public
     * @return String
     */
    public function __toString()
    {
        return 'Alerta: [' . $this->code . ']: ' . $this->message . " \n";
    }
}