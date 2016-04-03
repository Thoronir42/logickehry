<?php

namespace model\services;

use \PDO;
use \libs\MessageBuffer;

/**
 * Description of DB_Service
 *
 * @author Stepan
 */
class DB_Service {
	
	/**
	 * @var MessageBuffer
	 */
	public static $message_buffer;

	public static function logError($errorInfo, $function, $sql = null, $pars = null) {
		if (!\config\Config::LOG_DB_ERRORS) {
			return;
		}
		$message = sprintf("DB error <strong>%s</strong> in function <strong>%s</strong>", $errorInfo[2], $function);
		if (!is_null($sql)) {
			$message .= "<br/>SQL: $sql";
		}
		if (!is_null($pars)) {
			$message .= "<br/>Querry was called with following parameters:<br/>";
			$message .= self::paramArrayToString($pars);
			
		}
		self::$message_buffer->log($message, MessageBuffer::LVL_DNG);
	}
	
	private static function paramArrayToString($pars){
		$message = '';
		foreach($pars as $key => $val){
			if(is_array($val)){
				$str = self::paramArrayToString($val);
				$val = sprintf('[%s]', $str);
			}
			$message .= "$key => $val <br/>";
		}
		return $message;
	}
	
	public static function getErrorLog() {
		return self::$message_buffer->getLog();
	}
	
	
	/** @var PDO */
	protected $pdo;
	
	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	

}
