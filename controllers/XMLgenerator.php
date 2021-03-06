<?php

namespace controllers;

use \libs\PDOwrapper;
use \model\database\tables as Tables,
	\model\database\views as Views;

class XMLgenerator {

	/** @var boolean */
	var $blockSauce = true;

	/** @var \PDO */
	var $pdo;

	/** @var string */
	var $layout;

	/** @var array */
	var $game_type;

	/** @var array */
	var $game_box;

	/** @var array */
	var $reservation;

	/** @var array */
	var $template;

	public function buildSubmenu() {
		return false;
	}

	public function __construct($support) {
		$this->template = [];
		$this->layout = "xml.twig";
		$this->pdo = $support['pdo'];
	}

	public function startUp() {
		header('Content-type: text/xml');
		header('Pragma: public');
		header('Cache-control: private');
		header('Expires: -1');
	}

	public function renderInventory() {
		$this->template["game_type"] = Views\GameTypeExtended::fetchAll($this->pdo);
		$this->template["game_box"] = Views\GameBoxExtended::fetchAll($this->pdo, false);
	}

	public function renderReservations() {
		
	}

	public function fetchWeek($week) {
		$this->game_type = $this->fetchGameTypes($week);
		$this->game_box = $this->fetchGameBoxes($week);
		$this->reservation = $this->fetchReservations($week);
	}

	public function preRender() {
		return false;
	}

	public function __toString() {
		return explode("\\", get_class($this))[1];
	}

}
