<?php

/**
 * Generates messages to user; <br/>
 * Never generates log files; <br/>
 * Never is fatal.
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
class MenssageException extends BasicException
{

    /**
     * Generates messages to user; <br/>
     * Never generates log files; <br/>
     * Never is fatal.
     * 
     * @param String $message Message of Exception
     * @param Integer $code The code of exception <br/>[default: 0]
     * @param Mixed $previous Exception | BasicException.  <br/> Previus Exception to be stored on log like exceptions backtrace
     * @access public
     * @return Void
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        $this->setClassName(__CLASS__);
        parent::__construct($message, $code, $previous, false, false);
    }

    /**
     * Converts data of Exception to String
     * 
     * @access public
     * @return String
     */
    public function __toString()
    {
        return 'Mensagem:' . $this->message . "\n";
    }
}