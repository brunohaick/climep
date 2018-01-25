<?php

/**
 * This class do a connection with database based on configuration from config.json
 * 
 * @static Static class (to be used without a new instance) Example: Class::method() NOT $instance->method();
 * 
 * @category PHP
 * @package Helpers
 * @subpackage Database
 * @version 1.0
 * 
 * @author Mario Chapela <elderzinho@hotmail.com> and Victor Lacerda <victorgerin@live.com>
 * @copyright (c) 2013, Mario Chapela and Victor Lacerda
 * @license   http://creativecommons.org/licenses/by/3.0/br/deed.pt_BR CreativeCommons - CC BY 3.0
 */
class Connection
{
    /**
     * Contents the connection instance
     * 
     * @name $connection
     * @var MySQLi Object
     * @static Static property
     * @access private
     */
    private static $connection;

    /**
     * Check if have a connection if true return this, otherwise <br/>
     * Create a instance of MySQLi based on parameter of config.json readed by "Configuracoes" class. <br/>
     * If same error was found throw a ErroException.
     * 
     * @static Static method <br/> Use example: Class::method(); NEVER use like: $instance->method();
     * @access private
     * @return Object MySQLi
     */
    private static function set()
    {
        if (!is_null(self::$connection)) {
            self::get();
        }
		global $config;
        self::$connection = New mysqli($config['host'],
                                       $config['usuario'],
                                       $config['senha'],
                                       $config['banco']);
        if (self::$connection->connect_errno) {
            throw new ErroException('1001[' . self::errorCode() . '] {' . self::errorInfo() . '}',
                                    '1001');
            //Output::show('Não foi possivel fazer uma conexao ao banco. <br/> Contate o adminsitrador do sistema.');
        }
        return self::get();
    }

    /**
     * Return the connection with database;
     * 
     * @static Static method <br/> Use example: Class::method(); NEVER use like: $instance->method();
     * @access public
     * @return Object MySQLi
     */
    public static function get()
    {
        if (is_null(self::$connection)) {
            self::set();
        }
        return self::$connection;
    }

    /**
     * Return the string error on connection.
     * 
     * @static Static method <br/> Use example: Class::method(); NEVER use like: $instance->method();
     * @access public
     * @return String
     */
    public static function errorInfo()
    {
        return self::get()->error;
    }

    /**
     * Return the code of error on connection.
     * 
     * @static Static method <br/> Use example: Class::method(); NEVER use like: $instance->method();
     * @access public
     * @return Integer
     */
    public static function errorCode()
    {
        return self::get()->errno;
    }

    /**
     * Close the connection with database
     * 
     * @static Static method <br/> Use example: Class::method(); NEVER use like: $instance->method();
     * @access public
     * @return boolean
     */
    public static function close()
    {
        return mysqli_close(self::get());
    }
}