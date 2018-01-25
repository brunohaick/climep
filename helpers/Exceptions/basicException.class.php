<?php

/**
 * This class is basic for all exceptions of application.
 * It implants same properties and methods to increse the Exception 
 * expirence.
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
class BasicException extends Exception
{
    /**
     * If true this exception is a fatal exception and the program will failure. <br/>
     * [default:false]
     * 
     * @name $fatal
     * @var Boolean
     * @access protected
     */
    protected $fatal = false;

    /**
     * The log to be recorded (Can be Array with information, string, integer...)
     * 
     * @name $log
     * @var Mixed 
     * @access protected
     */
    protected $log;

    /**
     * The name of file to record log information.
     * 
     * @name $fileLog
     * @var String
     * @access protected
     */
    protected $fileLog;

    /**
     * The name of file to record log information.
     * 
     * @name $logName
     * @var String 
     * @access protected
     */
    protected $logName;

    /**
     * The name of the class that generates the Exception
     * 
     * @name $className
     * @var String
     * @access protected
     */
    protected $className;

    /**
     * This class is basic for all exceptions on application.
     * It implants same propertys and methods to increse the Exception 
     * expirence. Can be used to generate Log Files about exceptions, and
     * do a backtrace exceptions.
     * 
     * @param String $message Message of Exception
     * @param Integer $code The code of exception <br/>[default: 0]
     * @param Mixed $previous Exception | BasicException.  <br/> Previus Exception to be stored on log like exceptions backtrace
     * @param Boolean $fatal If true this exception is a fatal exception and the the program will failure. <br/> [default: false]
     * @param Boolean $doLog If true the exception will generate a logFile with information about this exception <br/> [default: true]
     * @access public
     * @return Void
     */
    public function __construct($message, $code = 0, $previous = null,
                                $fatal = false, $doLog = true)
    {
        parent::__construct($message, $code, $previous);
        $this->isFatal($fatal);
        $this->getLog();
        $this->doLog($doLog);
    }

    /**
     * To set the name of the class that generated the Exception
     * 
     * @param String $className The name of the class <br/> [default:__CLASS__]
     * @access protected
     * @return Void
     */
    protected function setClassName($className = __CLASS__)
    {
        $this->className = $className;
    }

    /**
     * Return the name of the class that generates the Exception
     * 
     * @access protected
     * @return String $this->className
     */
    protected function getClassName()
    {
        if (is_null($this->className)) {
            $this->setClassName();
        }
        return $this->className;
    }

    /**
     * Set log to be recorded on file;
     * 
     * @param Mixed $log String | Array. <br/> Data to be recorded <br/> [default: '']
     * @param Boolean $append If true will append the actual log on end of file, <br/> if false will clear log file and insert this log <br/> [default: False]
     * @param String $index If $append is true will insert the $log on $index of actual log array <br/> [default: Null]
     * @access public
     * @return Mixed Log data
     */
    public function setLog($log = '', $append = false, $index = null)
    {
        if (is_array($log) && $append === true) {
            return $this->setLog(array_merge($this->getLog(), $log));
        }
        if (empty($log)) {
            $log['Code'] = $this->getCode();
            $log['Message'] = $this->getMessage();
            $log['Fatal'] = $this->isFatal();
            $log['File'] = $this->getFile();
            $log['Line'] = $this->getLine();
            $log['Type'] = $this->getClassName();
            $log['PreviousError'] = $this->getPreviousError();
            //$log['Trace String']= $this->getTraceAsString();
            $log['Trace'] = $this->getTrace();
        }
        if ($append === true) {
            $this->log[$index] = $log;
        } else {
            $this->log = $log;
        }
        return $this->log;
    }

    /**
     * Return the log
     * 
     * @access public
     * @return Mixed Log data
     */
    public function getLog()
    {
        if (is_null($this->log)) {
            $this->setLog();
        }
        return $this->log;
    }

    /**
     * Set file name to record the log
     * 
     * @param String $fileLog File name <br/> [default: 'basicExceptions.log']
     * @access public
     * @return Void
     */
    public function setFileLog($fileLog = 'basicExceptions.log')
    {
        $this->fileLog = $fileLog;
    }

    /**
     * Return the file name that contents the log
     * 
     * @access public
     * @return String File Name
     */
    public function getFileLog()
    {
        if (is_null($this->fileLog)) {
            $this->setFileLog();
        }
        return $this->fileLog;
    }

    /**
     * To know if the exception is fatal or
     * to set if the exception is fatal.
     * 
     * @param Mixed $fatal Null | Boolean. <br/> If not null it's the new value for $isFatal <br/> [default: False]
     * @access public
     * @return Boolean $this->fatal
     */
    public function isFatal($fatal = null)
    {
        if (!is_null($fatal)) {
            $this->fatal = (bool) $fatal;
        }
        return $this->fatal;
    }

    /**
     * Set value for $doLog
     * 
     * @param Boolean $doLog If true the exception will generate a logFile with information about this exception <br/> [default: False]
     * @access public
     * @return Void
     */
    public function doLog($doLog = false)
    {
        if ($doLog === true) {
            $this->logName = Log::registrar($this->getLog(), $this->getFileLog());
        }
    }

    /**
     * Return previous error or string explaning this.
     * 
     * @return Mixed Exception | BasicException | String
     */
    public function getPreviousError()
    {
        if ($this->getPrevious() === null) {
            return 'No Errors Founded';
        } else {
            $arrayPrevious = Array('Code' => $this->getCode(), 'Message' => $this->getPrevious()->getMessage());
            if ($this->getPrevious() instanceof BasicException) {
                $arrayPrevious['LogName'] = $this->getPrevious()->logName;
                $arrayPrevious['LogFile'] = $this->getPrevious()->fileLog;
            }
            return $arrayPrevious;
        }
    }

    /**
     * Converts data of Exception to String
     * 
     * @access public
     * @return String
     */
    public function __toString()
    {
        return $this->getClassName() . ": [{$this->code}]: {$this->message} \n";
    }
}