<?php

namespace model\database\views;

/**
 * Description of GameRatingExtended
 *
 * @author Stepan
 */
class GameRatingExtended extends \model\database\tables\GameRating {

	/**
	 * 
	 * @param \libs\PDOwrapper $pw
	 * @param type $user_id
	 * @param type $game_type_id
	 * @return GameRatingExtended
	 */
	public static function fetchOne($pw, $user_id, $game_type_id) {
		$statement = $pw->con->prepare("SELECT * FROM `web_logickehry_db`.`game_rating_extended` "
				. "WHERE `game_type_id` = :gid AND `user_id` = :uid;");
		if ($statement->execute(['gid' => $game_type_id, 'uid' => $user_id])) {
			return $statement->fetchObject(self::class);
		}
		var_dump($statement->errorInfo());
		return false;
	}

	var $name;
	var $orion_login;
	var $game_name;
	var $subtitle;

}
