<?php

namespace model\database\tables;

/**
 * Description of GameType
 *
 * @author Stepan
 */
class GameType extends \model\database\DB_Entity {

	/**
	 * 
	 * @param \PDO $pdo
	 * @param mixed[] $pars
	 */
	public static function insert($pdo, $pars) {
		$statement = $pdo->prepare("INSERT INTO `web_logickehry_db`.`game_type` "
				. "(`game_type_id`, `game_name`, `subtitle`, `avg_playtime`, `max_players`, `min_players`) "
				. "VALUES ( :game_type_id,  :game_name,  :subtitle,  :avg_playtime,  :max_players,  :min_players )");
		if ($statement->execute($pars)) {
			return true;
		}
		var_dump($statement->errorInfo());
		echo '<br/>';
		var_dump($statement->queryString);
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
		return parent::fromPOST(self::class);
	}

	var $game_type_id = false;
	var $game_name;
	var $game_subtitle = false;
	var $avg_playtime;
	var $min_players;
	var $max_players;

	protected function checkRequiredProperties() {
		return parent::checkRequiredProperties(self::class);
	}

	public function getColor() {
		return \model\ColorManager::numberToColor($this->game_type_id);
	}

}
