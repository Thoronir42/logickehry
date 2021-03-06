<?php

namespace model\database\tables;

use model\database\DB_Entity;
use model\services\DB_Service;

use libs\ColorManager;

/**
 * Description of GameType
 *
 * @author Stepan
 */
class GameType extends DB_Entity {

	public static function getExportColumns() {
		return ['game_name', 'game_subtitle', 'avg_playtime', 'min_players', 'max_players'];
	}

	/**
	 * 
	 * @param \PDO $pdo
	 * @return GameType[]
	 */
	public static function fetchAll($pdo) {
		return $pdo->query("SELECT * FROM `game_type`")->fetchAll(\PDO::FETCH_CLASS, self::class);
	}

	/**
	 * 
	 * @param \PDO $pdo
	 * @param mixed[] $pars
	 */
	public static function insert($pdo, $pars) {
		$statement = $pdo->prepare("INSERT INTO `web_logickehry_db`.`game_type` "
				. "(`game_type_id`, `game_name`, `game_subtitle`, `avg_playtime`, `max_players`, `min_players`) "
				. "VALUES ( :game_type_id,  :game_name,  :game_subtitle,  :avg_playtime,  :max_players,  :min_players )");
		if (!$statement->execute($pars)) {
			DB_Service::logError($statement->errorInfo(), __CLASS__."::".__FUNCTION__, $statement->queryString, $pars);
			return false;
		}
		return true;
	}

	/**
	 * 
	 * @param \PDO $pdo
	 * @param mixed[] $pars
	 * @return boolean
	 */
	public static function update($pdo, $pars, $id) {
		
		$sql = "UPDATE `web_logickehry_db`.`game_type` SET ";
		foreach($pars as $par => $val){
			$sql .="`$par` = :$par, ";
		}
		$sql .= "WHERE `game_type_id` = :id ";
		$statement = $pdo->prepare($sql);
		
		$pars['id'] = $id;
		if ($statement->execute($pars)) {
			DB_Service::logError($statement->errorInfo(), __CLASS__."::".__FUNCTION__, $statement->queryString, $pars);
			return false;
		}
		return true;
	}

	public static function prepareExport($pdo) {
		$columns = self::getExportColumns();
		$games = self::fetchAll($pdo);

		$gamesArray = [];
		foreach ($games as $g) {
			$ga = [];
			foreach ($columns as $c) {
				$ga[$c] = $g->$c;
			}
			$gamesArray[] = $ga;
		}
		return $gamesArray;
	}

	/**
	 * 
	 * @param \PDO $pdo
	 * @return type
	 */
	public static function nextId($pdo) {
		$result = $pdo->query("SELECT game_type_id FROM game_type "
						. "ORDER BY game_type_id DESC")->fetchColumn();
		return $result + 1;
	}

	/**
	 * 
	 * @return GameType
	 */
	public static function fromPOST() {
		$gt = parent::createFromPost(self::class);
		if (empty($gt->max_players)) {
			$gt->max_players = $gt->min_players;
		}
		return $gt;
	}

	var $game_type_id = false;
	var $game_name;
	var $game_subtitle = false;
	var $avg_playtime;
	var $min_players;
	var $max_players = false;

	public function readyForInsert() {
		if(parent::readyForInsert()){
			return true;
		}
		return parent::checkRequiredProperties(self::class);
	}

	public function getColor() {
		return ColorManager::numberToColor($this->game_type_id);
	}

	public function getFullName() {
		$return = $this->game_name;
		if (!empty($this->game_subtitle)) {
			$return .= ' ' . $this->game_subtitle;
		}
		return $return;
	}

	public function getPlayerCount($separator = ' - ') {
		$reverse = ($this->min_players > $this->max_players);
		$less = $reverse ? $this->max_players : $this->min_players;
		$more = $reverse ? $this->min_players : $this->max_players;
		if ($less < 1 || $less == $more) {
			return $more;
		}
		return "$less$separator$more";
	}

}
