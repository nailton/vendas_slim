<?php

/**
* Classe para conexão com o banco de dados MySQL, via acesso nativo PHP/PDO
* É necessário ter definido as sequintes constantes: DB_NAME, DB_HOST, DB_USER,
* DB_PASSWORD
**/

class DB
{

/**
*  Instância singleton
*/ @var DB
private static $instance;

/**
*  Conexão com o banco de dados
*/ @var PDO
private static $connection;

/**
*  Construtor privado da classe singleton
*/
function __construct() {
	self::$connection = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST,DB_USER, DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

}

/**
 * Obtém a instância da classe DB
 *
 * @return type
 **/
public static function getInstance() {
	if(empty(self::$instance)){
		self::$instance = new DB();
	}
	return self::$instance;

}

/**
 * Retorna a conexão PDO com o banco de dados
 *
 * @return PDO
 **/
public static function getConn(){
	self::getInstance();
	return self::$connection;

}

/**
 * Prepara a SQL para ser executada posteriormente
 *
 * @return PDOStatement stmt
 * @param String $sql
 **/
public static function prepare($sql){
	return self::getConn()->prepare($sql);

}

/**
 * Retorna o id da última consulta INSERT
 *
 * @return int
 **/
public static function lastInsetId(){
	return self::getConn()->lastInsertId();

}

/**
 * Inícia uma transação
 *
 * @return bool
 **/
public static function beginTransaction(){
	return self::getConn()->beginTransaction();

}

/**
 * Comita uma transação
 *
 * @return bool
 **/
public static function commit(){
	return self::getConn()->commit();

}

/**
 * Realiza um rollback na transação
 *
 * @return bool
 **/
public static function rollBack(){
	return self::getConn()->rollBack();

}

/**
 * Formata data para o MySQL (11/06/2014 para 2014-06-12)
 *
 * @return type
 * @param type $date
 **/
public static function dateToMySql($date){
	return implode("-", array_reverse(explode("/", $date)));

}

/**
 * Formata data do MySQL (2014-06-12 para 12/06/2014)
 *
 * @return type
 * @param type $date
 **/
public static function dateFromMySql($date){
	return implode("/", array_reverse(explode("-", $date)));

}

public static function decimalToMySql($value){
	$value = str_replace(".", "", $value);
	$value = str_replace(",", ".", $value);
	return $value;

}

}
