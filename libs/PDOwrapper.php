<?php
namespace libs;

use PDO;
use model\database\views as Views;
use model\database\tables as Tables;


class PDOwrapper{
    /** @var PDO */
    private $connection;
    
	/**
	 * 
	 * @param array $cfg
	 * @return \PDOwrapper
	 */
    public static function getConnection($cfg){
        $cfg['password'] = isset($cfg['password']) ? $cfg['password'] : null;
        $pdo = new PDO("mysql:host=$cfg[host];dbname=$cfg[db_name];charset=utf8", $cfg['user'], $cfg['password']);
		return new PDOwrapper($pdo);
    }
	
	/**
	 * 
	 * @param PDO $pdo
	 */
	private function __construct($pdo) {
		$this->connection = $pdo;
	}
	
	public function getGamesWithScores(){
		$result = $this->connection->query("SELECT * FROM `game_type_w_score`")
				->fetchAll(PDO::FETCH_CLASS, Views\GameTypeExtended::class);
		return $result;
	}
	
	public function getReservationsAndAll(){
		$result = $this->connection->query("SELECT * FROM `reservation_and_all`")
				->fetchAll(PDO::FETCH_CLASS, Views\ReservationAndAll::class);
		return $result;
	}
	
	public function getUsers(){
		$result = $this->connection->query("SELECT * FROM user")
				->fetchAll(PDO::FETCH_CLASS, Tables\User::class);
		return $result;
	}
	public function getGameTypes(){
		$result = $this->connection->query("SELECT * FROM game_type")
				->fetchAll(PDO::FETCH_CLASS, Tables\GameType::class);
		return $result;
	}
	
	public function getGameBoxes(){
		$result = $this->connection->query("SELECT * FROM game_box")
				->fetchAll(PDO::FETCH_CLASS, Tables\GameBox::class);
		return $result;
	}
	
	public function getDesks(){
		$result = $this->connection->query("SELECT * FROM desk")
				->fetchAll(PDO::FETCH_CLASS, Tables\GameBox::class);
		return $result;
	}

	/**
	 * @return Tables\GameType Typ hry id
	 */
	public function fetchGame($id) {
		if(!is_numeric($id)){ return null; }
		$statement = $this->connection->prepare("SELECT * FROM game_type"
				. "WHERE game_type_id == :game_type_id");
		$result = $statement->fetchObject(\Tables\GameType::class,
				[ "game_type_id" => $id ]);
		return $result;
	}

}