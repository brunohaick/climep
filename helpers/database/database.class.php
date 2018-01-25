<?php

/**
 * This class execute queries and process the result
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
class Database {

	/**
	 * Result of query execution; <br/>
	 * Boolean | mysqli_result
	 * 
	 * @name $queryReturn
	 * @var Mixed
	 * @static Static property
	 * @access private
	 */
	private static $queryReturn;

	/**
	 * String of SQL Query
	 * 
	 * @name $queryString
	 * @var String
	 * @static Static property
	 * @access private
	 */
	private static $queryString;

	/**
	 * Execute a query and return the result.<br/>
	 * Before execute, set the queryString; <br/>
	 * After execute, set queryReturn;
	 * 
	 * @static Static method <br/> Use example: Class::method(); NEVER use like: $instance->method();
	 * @access public
	 * @return Boolean | mysqli_result
	 */
	public static function query($queryString = null) {
		self::setQueryString($queryString);
		return self::setQueryReturn(Connection::get()->query(self::getQueryString()));
	}

	//===============================================

	/**
	 * setQueryString
	 * @param String $queryString
	 * @return String
	 */
	public static function setQueryString($queryString) {
		if (empty($queryString)) {
			throw new AlertException('Query em branco', 1002);
		}
		self::$queryString = (String) $queryString;
		return self::getQueryString();
	}

	/**
	 * getQueryString
	 * @return String
	 */
	public static function getQueryString() {
		return self::$queryString;
	}

	/**
	 * setQueryReturn
	 * @param Mixed $queryReturn
	 * @return Mixed
	 */
	public static function setQueryReturn($queryReturn) {
		self::$queryReturn = $queryReturn;
//		if (self::getQueryReturn() === false) {
//			throw new AlertException('Erro na query' . 'SQL CODE [' . Connection::errorCode() . '] SQL MESSAGE {' . Connection::errorInfo() . '}', 1003);
//		}
		return self::getQueryReturn();
	}

	/**
	 * getQueryReturn
	 * @return Mixed
	 */
	public static function getQueryReturn() {
		return self::$queryReturn;
	}

	/**
	 * @return Array Array associativo da tabela consultada
	 */
	public static function fetch() {
		if (self::isValidNumRows(1, 0) === false) {
			throw new MenssageException('Numero de  Registros invalidos.', '0003');
		}

		return self::getQueryReturn()->fetch_assoc();
	}

	/**
	 * @return Array Array multidimensional associativo da tabela consultada
	 */
	public static function fetchAll() {
		if (self::isValidNumRows() === false) {
			throw new MenssageException('NumeroDeRegistrosInvalidos', '0004');
		}

		$arrayResult = Array();
		while ($row = self::getQueryReturn()->fetch_assoc()) {
			if (!is_array($row)) {
				throw new AlertException('ErroNosResultados', '0005');
			}
			$arrayResult[] = $row;
		}
		return $arrayResult;
	}
	
	/**
	 * isValidNumRows
	 * @param Integer $minimoNumRows Minimo de linhas necessarias
         * @param Integer $maximoNumRows Maximo de linhas necessarias
	 * @return Boolean
	 */
	public static function isValidNumRows($minimoNumRows = 1, $maximoNumRows = 5000) {
		return (self::rowCount() >= $minimoNumRows &&  (($maximoNumRows <= 0) ? true : self::rowCount() <= $maximoNumRows )) ? true : false;
	}

	/**
	 * rowCount
	 * @return Integer
	 */
	public static function rowCount() {
		return self::getQueryReturn()->num_rows;
	}

	/**
	 * freeResult
	 * Limpa os resultados obtidos da memoria
	 * @return Void();
	 */
	public static function freeResult() {
		self::getQueryReturn()->free();
	}

	/**
	 * getLastId
	 * @return Integer Integer Last inserted Id
	 */
	public static function getLastId() {
		return Connection::get()->insert_id;
	}

	/**
	 * getAffectedRows
	 * @return Integer Integer of number rows affected on last query
	 */
	public static function getAffectedRows() {
		return Connection::get()->affected_rows;
	}

	/**
	 * escapeString
	 * @param String $string 
	 * @return String
	 */
	public static function escapeString($string) {
		return Connection::get()->real_escape_string($string);
	}

}
